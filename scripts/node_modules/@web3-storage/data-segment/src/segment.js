import * as SHA256 from 'sync-multihash-sha2/sha256'
import { Size as NodeSize } from './node.js'
import * as API from './api.js'
import { log2Ceil, pow2 } from './uint64.js'

/**
 * The size of the checksum in bytes
 */
export const ChecksumSize = 16

/**
 * Amount of bytes a uint64 will take.
 */
export const Uint64Size = 64 / 8

/**
 *
 * @param {API.Segment} segment
 * @param {object} [options]
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher] - A sync sha256 hasher.
 * @returns {API.Checksum<API.Segment, typeof ChecksumSize>}
 */
export const computeChecksum = (segment, options) =>
  /** Take only the first {@link ChecksumSize} bytes */
  toBytes(segment, options).subarray(NodeSize + Uint64Size + Uint64Size)

/**
 * @param {API.Segment} segment
 * @param {object} [options]
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher] - A sync sha256 hasher.
 * @returns {API.SegmentInfo}
 */
export const withChecksum = (segment, options) => ({
  ...segment,
  checksum: computeChecksum(segment, options),
})

/**
 * @param {API.MerkleTreeNodeSource} source
 */
export const fromSource = ({ node, location }) => ({
  root: node,
  offset: toLeafIndex(location) * BigInt(NodeSize),
  size: pow2(BigInt(location.level)) * BigInt(NodeSize),
})

/**
 *
 * @param {API.Segment} segment
 * @returns {API.MerkleTreeNodeSource}
 */
export const toSource = (segment) => {
  const level = log2Ceil(segment.size / BigInt(NodeSize))

  return {
    node: segment.root,
    location: {
      level,
      index: (segment.offset / BigInt(NodeSize)) >> BigInt(level),
    },
  }
}

/**
 * @param {API.MerkleTreeNodeSource} source
 * @param {object} [options]
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher] - A sync sha256 hasher.
 */
export const fromSourceWithChecksum = (source, options) =>
  withChecksum(fromSource(source), options)

/**
 * Calculates the `index` in the leaf layer corresponding to the given
 * `location` in (the hybrid merkle tree).
 *
 * @param {API.MerkleTreeLocation} location
 */
export const toLeafIndex = ({ index, level }) =>
  // This is done by bit shifting Index to the left by `level` places.
  // In the context of a binary tree, this operation essentially corresponds to
  // descending `level` levels down from the current node.
  index << BigInt(level)

/**
 * @param {API.Segment} segment
 * @returns {API.MerkleTreeNode}
 */
export const toIndexNode = (segment) => toBytes(segment).subarray(NodeSize)

/**
 * @see https://github.com/filecoin-project/go-data-segment/blob/e3257b64fa2c84e0df95df35de409cfed7a38438/datasegment/index.go#L232-L248
 *
 * @param {(API.Segment & {checksum?: undefined}) |API.SegmentInfo} segment
 * @param {object} [options]
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher] - A sync sha256 hasher.
 * @returns {API.ByteView<API.SegmentInfo, API.RAW_CODE>}
 */
export const toBytes = ({ root, size, offset, checksum }, options = {}) => {
  const buffer = new Uint8Array(
    NodeSize + Uint64Size + Uint64Size + ChecksumSize
  )
  buffer.set(root, 0)
  const view = new DataView(buffer.buffer)
  view.setBigUint64(NodeSize, BigInt(offset), true)
  view.setBigUint64(NodeSize + Uint64Size, BigInt(size), true)

  if (!checksum) {
    const hasher = options.hasher || SHA256
    const { digest } = hasher.digest(buffer)
    checksum = digest.subarray(0, ChecksumSize)
    // Truncate to  126 bits
    checksum[ChecksumSize - 1] &= 0b00111111
  }

  buffer.set(checksum, NodeSize + Uint64Size + Uint64Size)

  return buffer
}

/**
 * @param {API.Segment} segment
 * @returns {API.Result<API.Segment, Error>}
 */
export const validate = (segment) => {
  if (segment.offset % 128n !== 0n) {
    return { error: Error('offset is not aligned in padded data') }
  }

  return { ok: segment }
}
