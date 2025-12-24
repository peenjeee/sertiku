import * as API from './api.js'
import * as Tree from './aggregate/tree.js'
import * as Segment from './segment.js'
import * as Index from './index.js'
import * as Piece from './piece.js'
import * as Node from './node.js'
import { log2Ceil } from './uint64.js'
import { indexAreaStart } from './inclusion.js'
import * as Bytes from 'multiformats/bytes'
import { Expanded } from './piece/size.js'
export { Expanded as Size } from './piece/size.js'

import * as InclusionProof from './inclusion.js'

const NodeSize = BigInt(Node.Size)
const EntrySize = Number(Index.EntrySize)
export const MAX_CAPACITY = Expanded.fromHeight(Tree.MAX_HEIGHT)
export { InclusionProof }
export const Proof = InclusionProof.Proof

/**
 * Default aggregate size (32GiB).
 */
// Default is chosen based on our current average rate of 30GiB per hour.
// The 16GiB may also be a viable option, however given our current rate
// 32GiB is better default.
export const DEFAULT_DEAL_SIZE = Expanded.from(2n ** 35n)

export { Tree }

/**
 * @param {object} [options]
 * @param {API.PieceSize} [options.size] - Size of the aggregate in
 * (fr32 padded) bytes. If omitted default to 32 GiB. Note that sizes >=8GiB
 * are are too expensive for service providers and it may be challenging to
 * find a deal.
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher] - A sync sha256 hasher.
 */
export const createBuilder = ({ size = DEFAULT_DEAL_SIZE, hasher } = {}) =>
  new AggregateBuilder({ size, hasher })

/**
 * @param {object} options
 * @param {API.Piece[]} options.pieces - Pieces to add to the aggregate
 * @param {API.PieceSize} [options.size] - Size of the aggregate in
 * (fr32 padded) bytes. If omitted default to 32 GiB
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher] - A sync sha256 hasher.
 */
export const build = ({ pieces, size = DEFAULT_DEAL_SIZE, hasher }) => {
  const builder = createBuilder({ size, hasher })

  for (const piece of pieces) {
    builder.write(piece)
  }

  return builder.build()
}

/**
 *
 * @param {object} tree
 * @param {API.MerkleTreeNode} tree.root
 * @param {number} tree.height
 */
export const toLink = ({ root, height }) =>
  Piece.toLink({ root, height, padding: 0n })

class AggregateBuilder {
  /**
   * @param {object} source
   * @param {API.PieceSize} source.size
   * @param {API.uint64} [source.offset]
   * @param {API.MerkleTreeNodeSource[]} [source.parts]
   * @param {number} [source.limit]
   * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [source.hasher]
   */
  constructor({
    size,
    limit = Index.maxIndexEntriesInDeal(size),
    offset = 0n,
    parts = [],
    hasher
  }) {
    this.size = Expanded.from(size)
    this.offset = offset
    this.parts = parts

    /**
     * Maximum number of pieces that could be added to this aggregate.
     */
    this.limit = limit
    this.hasher = hasher
  }

  /**
   * Size of the index in bytes.
   */
  get indexSize() {
    return this.limit * EntrySize
  }

  /**
   * Height of the perfect binary merkle tree corresponding to this aggregate.
   */
  get height() {
    return log2Ceil(this.size / NodeSize)
  }

  /**
   * @returns {API.AggregateView}
   */
  build() {
    const { size, parts, limit, offset, height, hasher } = this
    const index = createIndex(parts, { hasher })

    const tree = Tree.create(height, { hasher })
    Tree.batchSet(tree, parts)
    Tree.batchSet(tree, createIndexNodes(size, index))

    return new Aggregate({
      size,
      tree,
      index,
      offset,
      parts,
      limit,
    })
  }

  /**
   * @param {API.Piece} piece
   */
  write(piece) {
    const result = this.estimate(piece)
    if (result.error) {
      throw result.error
    } else {
      const { parts, offset } = result.ok
      const [part] = parts

      this.offset += offset
      this.parts.push(part)
    }

    return this
  }

  /**
   * Computes addition to the current aggregate if it were to write
   * provided segment.
   *
   * @param {API.Piece} piece
   * @returns {API.Result<{
   *   parts: [API.MerkleTreeNodeSource]
   *   offset: API.uint64
   * }, RangeError>}
   */
  estimate(piece) {
    if (this.parts.length >= this.limit) {
      return {
        error: new RangeError(
          `Too many pieces for a ${this.size} sized aggregate: ${
            this.parts.length + 1
          } > ${this.limit}`
        ),
      }
    }

    const size = Expanded.fromHeight(piece.height)
    const sizeInNodes = size / NodeSize
    const level = log2Ceil(sizeInNodes)

    const index = (this.offset + sizeInNodes - 1n) / sizeInNodes
    const offset = (index + 1n) * sizeInNodes

    const total = offset * NodeSize + BigInt(this.limit) * BigInt(EntrySize)
    if (total > this.size) {
      return {
        error: new RangeError(
          `"Pieces are too large to fit in the index: ${total} (packed pieces) + ${
            this.limit * EntrySize
          } (index) > ${this.size} (dealSize)"`
        ),
      }
    }

    return {
      ok: {
        parts: [{ node: piece.root, location: { level, index } }],
        offset: offset - this.offset,
      },
    }
  }
}

/**
 * @param {API.PieceSize} size
 * @param {API.SegmentInfo[]} segments
 * @returns {Iterable<API.MerkleTreeNodeSource>}
 */
const createIndexNodes = function* (size, segments) {
  const indexStartNodes = indexAreaStart(size) / NodeSize

  for (const [n, segment] of segments.entries()) {
    const node = Segment.toIndexNode(segment)
    const index = n * 2

    yield {
      node: segment.root,
      location: {
        level: 0,
        index: indexStartNodes + BigInt(index),
      },
    }

    yield {
      node,
      location: {
        level: 0,
        index: indexStartNodes + BigInt(index + 1),
      },
    }
  }
}

/**
 * @param {API.MerkleTreeNodeSource[]} parts
 * @param {object} [options]
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher] - A sync sha256 hasher.
 * @returns {API.IndexData}
 */
const createIndex = (parts, options) =>
  parts.map((part) => Segment.fromSourceWithChecksum(part, options))

/**
 * @implements {API.AggregateView}
 */
class Aggregate {
  /**
   * @param {object} source
   * @param {API.PieceSize} source.size
   * @param {API.uint64} source.offset
   * @param {API.MerkleTreeNodeSource[]} source.parts
   * @param {API.IndexData} source.index
   * @param {number} source.limit
   * @param {API.AggregateTree} source.tree
   */
  constructor({ tree, parts, index, limit, size, offset }) {
    this.tree = tree
    this.parts = parts
    this.index = index
    this.limit = limit
    this.size = size
    this.offset = offset
    this.link = Piece.toLink({
      height: tree.height,
      root: tree.root,
      padding: 0n,
    })
  }

  /**
   * Size of the index in bytes.
   */
  get indexSize() {
    return this.limit * EntrySize
  }
  get root() {
    return this.tree.root
  }
  /**
   * Height of the perfect binary merkle tree corresponding to this aggregate.
   */
  get height() {
    return this.tree.height
  }
  get padding() {
    return 0n
  }
  toJSON() {
    return Piece.toJSON(this)
  }
  toInfo() {
    return Piece.toInfo(this)
  }

  /**
   * @param {API.PieceLink} piece
   */
  resolveProof(piece) {
    return resolveProof(this, Piece.fromLink(piece))
  }
}

/**
 *
 * @param {Aggregate} aggregate
 * @param {API.Piece} piece
 * @returns {API.Result<[number, API.SegmentInfo], RangeError>}
 */
export const resolveSegment = (aggregate, piece) => {
  const { height, root } = piece
  const size = Expanded.fromHeight(height)
  for (const [n, segment] of aggregate.index.entries()) {
    if (size === segment.size && Bytes.equals(root, segment.root)) {
      return { ok: [n, segment] }
    }
  }

  return {
    error: new RangeError(
      `Piece ${piece} was not found in aggregate ${aggregate.link}`
    ),
  }
}

/**
 * @see https://github.com/filecoin-project/go-data-segment/blob/master/datasegment/creation.go#L86-L105
 *
 * @param {Aggregate} aggregate
 * @param {API.Piece} piece
 * @returns {API.Result<API.InclusionProof, RangeError>}
 */
export const resolveProof = (aggregate, piece) => {
  const result = resolveSegment(aggregate, piece)
  if (result.error) {
    return result
  } else {
    const [n, segment] = result.ok
    const { level, index } = Segment.toSource(segment).location
    const subTreeProof = aggregate.tree.collectProof(level, index)

    const indexOffset =
      indexAreaStart(aggregate.size) / BigInt(EntrySize) + BigInt(n)
    const indexProof = aggregate.tree.collectProof(1, indexOffset)

    const inclusion = { tree: subTreeProof, index: indexProof }

    return { ok: InclusionProof.create(inclusion) }
  }
}
