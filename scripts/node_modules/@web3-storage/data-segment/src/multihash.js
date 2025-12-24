import * as API from './api.js'
import {
  IN_BYTES_PER_QUAD,
  IN_BITS_FR,
  OUT_BITS_FR,
  MIN_PAYLOAD_SIZE,
} from './constant.js'
import * as ZeroPad from './zero-comm.js'
import { computeNode } from './proof.js'
import { split } from './piece/tree.js'
import { pad } from './fr32.js'
import { fromHeight as piceSizeFromHeight } from './piece/size/expanded.js'
import { Unpadded } from './piece/size.js'
import * as Digest from './digest.js'
import { varint } from 'multiformats'

export { Digest }

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
 * Computes the digest of the given payload.
 *
 * @param {Uint8Array} payload
 * @returns {API.PieceDigest}
 */
export const digest = (payload) => {
  const hasher = new Hasher()
  hasher.write(payload)
  return hasher.digest()
}

/**
 * Creates a streaming hasher that can be used to consumer larger streams
 * of data than it would be practical to load into memory all at once.
 *
 * @returns {API.StreamingHasher<typeof code, number, API.PieceDigest>}
 */
export const create = () => new Hasher()

/**
 * @typedef {[API.MerkleTreeNode[], ...API.MerkleTreeNode[][]]} Layers
 *
 * @implements {API.StreamingHasher<typeof code, number, API.PieceDigest>}
 */
class Hasher {
  constructor() {
    /**
     * The number of bytes consumed by the hasher.
     *
     * @private
     */
    this.bytesWritten = 0n

    /**
     * This buffer is used to accumulate bytes until we have enough to fill a
     * quad.
     *
     * ⚠️ Note that you should never read bytes past {@link offset} as those
     * are considered dirty and may contain garbage.
     *
     * @protected
     */
    this.buffer = new Uint8Array(IN_BYTES_PER_QUAD)

    /**
     * Offset is the number of bytes in we have written into the buffer. If
     * offset is 0 it means that the buffer is effectively empty. When `offset`
     * is equal to `this.buffer.length` we have a quad that can be processed.
     *
     * @protected
     */
    this.offset = 0

    /**
     * The layers of the tree. Each layer will contain either 0 or 1 nodes
     * between writes. When we write into a hasher, if we have enough nodes
     * leaves will be created and pushed into the `layers[0]` array, after
     * which we flush and combine every two leafs into a node which is moved
     * to the next layer. This process is repeated until we reach the top
     * layer, leaving each layer either empty or with a single node.
     *
     * @type {Layers}
     */
    this.layers = [[]]
  }

  /**
   * Return the total number of bytes written into the hasher. Calling
   * {@link reset} will reset the hasher and the count will be reset to 0.
   *
   * @returns {bigint}
   */
  count() {
    return this.bytesWritten
  }

  /**
   * Computes the digest of all the data that has been written into this hasher.
   * This method does not have side-effects, meaning that you can continue
   * writing and call this method again to compute digest of all the data
   * written from the very beginning.
   */
  digest() {
    const bytes = new Uint8Array(Digest.MAX_SIZE)
    const count = this.digestInto(bytes, 0, true)
    return Digest.fromBytes(bytes.subarray(0, count))
  }

  /**
   * Computes the digest and writes into the given buffer. You can provide
   * optional `byteOffset` to write digest at that offset in the buffer. By
   * default the multihash prefix will be written into the buffer, but you can
   * opt-out by passing `false` as the `asMultihash` argument.
   *
   * @param {Uint8Array} output
   * @param {number} [byteOffset]
   * @param {boolean} asMultihash
   */
  digestInto(output, byteOffset = 0, asMultihash = true) {
    const { buffer, layers, offset, bytesWritten } = this

    // We do not want to mutate the layers, so we create a shallow copy of it
    // which we will use to compute the root.
    let [leaves, ...nodes] = layers

    // If we have some bytes in the buffer we fill rest with zeros and compute
    // leaves from it. Note that it is safe to mutate the buffer here as bytes
    // past `offset` are considered dirty and should not be read.
    if (offset > 0 || bytesWritten === 0n) {
      leaves = [...leaves, ...split(pad(buffer.fill(0, offset)))]
    }

    const tree = build([leaves, ...nodes])
    const height = tree.length - 1
    const [root] = tree[height]
    const padding = Number(Unpadded.toPadding(this.bytesWritten))

    const paddingLength = varint.encodingLength(
      /** @type {number & bigint} */ (padding)
    )

    let endOffset = byteOffset
    // Write the multihash prefix if requested
    if (asMultihash) {
      varint.encodeTo(code, output, endOffset)
      endOffset += Digest.TAG_SIZE

      const size = paddingLength + Digest.HEIGHT_SIZE + Digest.ROOT_SIZE
      const sizeLength = varint.encodingLength(size)
      varint.encodeTo(size, output, endOffset)
      endOffset += sizeLength
    }

    varint.encodeTo(padding, output, endOffset)
    endOffset += paddingLength

    // Write the tree height as the first byte of the digest
    output[endOffset] = height
    endOffset += 1

    // Write the root as the remaining 32 bytes of the digest
    output.set(root, endOffset)
    endOffset += root.length

    // Return number of bytes written
    return endOffset - byteOffset
  }
  /**
   * @param {Uint8Array} bytes
   */
  write(bytes) {
    const { buffer, offset, layers } = this
    const leaves = layers[0]
    const { length } = bytes
    // If we got no bytes there is nothing to do here
    if (length === 0) {
      return this
      /* c8 ignore next 5 */
    } else if (this.bytesWritten + BigInt(length) > MAX_PAYLOAD_SIZE) {
      throw new RangeError(
        `Writing ${length} bytes exceeds max payload size of ${MAX_PAYLOAD_SIZE}`
      )
    }
    // If we do not have enough bytes to form a quad, just add append new bytes
    // to the buffer and return.
    else if (offset + length < buffer.length) {
      buffer.set(bytes, offset)
      this.offset += length
      this.bytesWritten += BigInt(length)
      return this
    }
    // Otherwise we first fill the buffer to form a quad and create some leaves.
    // Then we slice remaining bytes into quads sized chunks and create leaves
    // from them. If we have some bytes left we copy them into the buffer and
    // flush to combining node pairs and propagate them up the tree.
    else {
      // Number of bytes required to fill the quad buffer
      const bytesRequired = buffer.length - offset
      // copy required bytes into the buffer and turn them into leaves
      // which we push into the leaf layer.
      buffer.set(bytes.subarray(0, bytesRequired), offset)
      leaves.push(...split(pad(buffer)))

      // Now we slice remaining bytes into quads, create leaves from them
      // and push them into the leaf layer.
      let readOffset = bytesRequired
      while (readOffset + IN_BYTES_PER_QUAD < length) {
        const quad = bytes.subarray(readOffset, readOffset + IN_BYTES_PER_QUAD)
        leaves.push(...split(pad(quad)))
        readOffset += IN_BYTES_PER_QUAD
      }

      // Whatever byte were left are copied into the buffer and we update
      // the offset to reflect that.
      this.buffer.set(bytes.subarray(readOffset), 0)
      this.offset = length - readOffset

      // We also update the total number of bytes written.
      this.bytesWritten += BigInt(length)

      // Now prune the layers to propagate all the new leaves up the tree.
      prune(this.layers)

      return this
    }
  }

  /**
   * Resets this hasher to its initial state so it could be recycled as new
   * instance.
   */
  reset() {
    this.offset = 0
    this.bytesWritten = 0n
    this.layers.length = 1
    this.layers[0].length = 0
    return this
  }

  /* c8 ignore next 3 */
  dispose() {
    this.reset()
  }
  get code() {
    return code
  }
  get name() {
    return name
  }
}

/**
 * Prunes layers by combining node pairs into nodes in the next layer and
 * removing them from the layer that they were in. After pruning each layer
 * will end up with at most one node. New layers may be created in the process
 * when nodes from the top layer are combined.
 *
 * @param {Layers} layers
 */
const prune = (layers) => flush(layers, false)

/**
 * Flushes all the nodes in layers by combining node pairs into nodes in the
 * next layer. Layers with only one node are combined with zero padded nodes
 * (corresponding to the level of the layer). Unlike {@link prune} combined
 * nodes are not removed and layers are copied instead of been mutated.
 *
 * @param {Layers} layers
 */
const build = (layers) => flush([...layers], true)

/**
 * @param {Layers} layers
 * @param {boolean} build
 * @returns {Layers}
 */
const flush = (layers, build) => {
  // Note it is important that we do not mutate any of the layers otherwise
  // writing more data into the hasher and computing the digest will produce
  // wrong results.
  let level = 0
  // We will walk up the tree until we reach the top layer. However, we may end
  // up with creating new layers in the process, so we will keep track of the
  while (level < layers.length) {
    let next = layers[level + 1]
    const layer = layers[level]

    // If we have the odd number of nodes and we have not reached the top
    // layer, we push a zero padding node corresponding to the current level.
    if (build && layer.length % 2 > 0 && next) {
      layer.push(ZeroPad.fromLevel(level))
    }

    level += 1

    // If we have 0 nodes in the current layer we just move to the next one.

    // If we have a next layer and we are building  will combine nodes from the current layer
    next = next ? (build ? [...next] : next) : []
    let index = 0
    // Note that we have checked that we have an even number of nodes so
    // we will never end up with an extra node when consuming two at a time.
    while (index + 1 < layer.length) {
      const node = computeNode(layer[index], layer[index + 1])

      // we proactively delete nodes in order to free up a memory used.
      delete layer[index]
      delete layer[index + 1]

      next.push(node)
      index += 2
    }

    if (next.length) {
      layers[level] = next
    }

    // we remove nodes that we have combined from the current layer to reduce
    // memory overhead and move to the next layer.
    layer.splice(0, index)
  }

  return layers
}
