import { varint } from 'multiformats'
import * as API from './api.js'
import {
  IN_BYTES_PER_QUAD,
  IN_BITS_FR,
  OUT_BITS_FR,
  MIN_PAYLOAD_SIZE,
} from './constant.js'
import { SHA256 } from './ipld.js'
import { fromHeight as piceSizeFromHeight } from './piece/size/expanded.js'

/**
 * @see https://github.com/multiformats/multicodec/pull/331/files
 */
export const name = /** @type {const} */ (
  'fr32-sha2-256-trunc254-padded-binary-tree'
)

/**
 * @type {API.MulticodecCode<0x1011, typeof name>}
 * @see https://github.com/multiformats/multicodec/pull/331/files
 */
export const code = 0x1011

/**
 * Varint is used to encode the tree height which is limited to 9 bytes.
 *
 * @see https://github.com/multiformats/unsigned-varint#practical-maximum-of-9-bytes-for-security
 */
const MAX_PADDING_SIZE = 9
/**
 * One byte is used to store the tree height.
 */
export const HEIGHT_SIZE = 1

/**
 * Amount of bytes used to store the tree root.
 */
export const ROOT_SIZE = SHA256.size

/**
 * Size of the multihash digest in bytes.
 */
export const MAX_DIGEST_SIZE = MAX_PADDING_SIZE + HEIGHT_SIZE + SHA256.size

export const TAG_SIZE = varint.encodingLength(code)

/**
 * Max size of the multihash in bytes
 */
export const MAX_SIZE =
  TAG_SIZE + varint.encodingLength(MAX_DIGEST_SIZE) + MAX_DIGEST_SIZE

/**
 * Since first byte in the digest is the tree height, the maximum height is 255.
 *
 * @type {255}
 */
export const MAX_HEIGHT = 255

/**
 * Max payload is determined by the maximum height of the tree, which is limited
 * by the int we could store in one byte. We calculate the max piece size
 * and derive max payload size that can would produce it after FR32 padding.
 */
export const MAX_PAYLOAD_SIZE =
  (piceSizeFromHeight(MAX_HEIGHT) * BigInt(IN_BITS_FR)) / BigInt(OUT_BITS_FR)

/**
 * @param {API.Piece} piece
 * @returns {API.PieceDigest}
 */
export const fromPiece = ({ padding, height, root }) => {
  const paddingLength = varint.encodingLength(Number(padding))
  const size = paddingLength + HEIGHT_SIZE + ROOT_SIZE
  const sizeLength = varint.encodingLength(size)

  const multihashLength = TAG_SIZE + sizeLength + size

  let offset = 0
  const bytes = new Uint8Array(multihashLength)
  varint.encodeTo(code, bytes, offset)
  offset += TAG_SIZE

  varint.encodeTo(size, bytes, offset)
  offset += sizeLength

  varint.encodeTo(Number(padding), bytes, offset)
  offset += paddingLength

  bytes[offset] = height
  offset += HEIGHT_SIZE

  bytes.set(root, offset)

  return new Digest(bytes)
}

/**
 * @param {Uint8Array} bytes
 * @returns {API.PieceDigest}
 */
export const fromBytes = (bytes) => new Digest(bytes)

/**
 * @param {object} input
 * @param {Uint8Array} input.digest
 */
export const toBytes = ({ digest }) => {
  const SIZE_BYTE_LENGTH = varint.encodingLength(digest.length)

  // number of bytes prefix will take up
  const prefixByteLength = SIZE_BYTE_LENGTH + TAG_SIZE

  // if digest is view within a buffer that has enough bytes in front to
  // fit the prefix it may be already include a prefix in which case we
  // will simply use a larger slice.
  if (digest.byteOffset >= prefixByteLength) {
    const bytes = new Uint8Array(
      digest.buffer,
      digest.byteOffset - prefixByteLength,
      digest.byteOffset + digest.length
    )

    // if the prefix matches our bytes represent a multihash
    const [tag, offset] = varint.decode(bytes)
    if (tag === code && varint.decode(bytes, offset)[0] === digest.length) {
      return bytes
    }
  }

  const bytes = new Uint8Array(digest.length + prefixByteLength)
  varint.encodeTo(code, bytes)
  varint.encodeTo(digest.length, bytes, TAG_SIZE)
  bytes.set(digest, prefixByteLength)

  return bytes
}

/**
 * @param {object} input
 * @param {Uint8Array} input.digest
 */
export const height = ({ digest }) => {
  const [, offset] = varint.decode(digest)
  return digest[offset]
}

/**
 * @param {object} input
 * @param {Uint8Array} input.digest
 */
export const padding = ({ digest }) => {
  const [padding] = varint.decode(digest)
  return BigInt(padding)
}

/**
 * @param {object} input
 * @param {Uint8Array} input.digest
 */
export const root = ({ digest }) => {
  const [, offset] = varint.decode(digest)
  return digest.subarray(
    offset + HEIGHT_SIZE,
    offset + HEIGHT_SIZE + SHA256.size
  )
}

/**
 * @implements {API.PieceDigest}
 */
class Digest {
  /**
   * @param {Uint8Array} bytes
   */
  constructor(bytes) {
    this.bytes = bytes
    const [tag] = varint.decode(bytes)
    if (tag !== code) {
      throw new RangeError(`Expected multihash with code ${code}`)
    }

    let offset = TAG_SIZE
    const [size, length] = varint.decode(bytes, offset)
    offset += length
    const digest = bytes.subarray(offset)

    if (digest.length !== size) {
      throw new RangeError(
        `Invalid multihash size expected ${offset + size} bytes, got ${
          bytes.length
        } bytes`
      )
    }

    this.digest = digest
  }
  get name() {
    return name
  }
  get code() {
    return code
  }
  get size() {
    return this.digest.length
  }
  get padding() {
    return padding(this)
  }
  get height() {
    return height(this)
  }
  get root() {
    return root(this)
  }
}
