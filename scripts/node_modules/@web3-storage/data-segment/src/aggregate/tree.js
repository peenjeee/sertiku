import * as API from '../api.js'
import * as Node from '../node.js'
import * as ZeroComm from '../zero-comm.js'
import * as Proof from '../proof.js'
import { pow2 } from '../uint64.js'

/**
 * We limit tree height to 60, since we have a perfect binary merkle tree this
 * will fit up to 2 ** 60 of leafs nodes.
 */
export const MAX_HEIGHT = 60

/**
 * Creates a new tree with a given tree `height`.
 *
 * @param {number} height
 * @param {object} [options]
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher]
 * @returns {API.AggregateTree}
 */
export const create = (height, options) => {
  if (height > MAX_HEIGHT) {
    throw new RangeError(`too many leafs: 2 ** ${height}`)
  }

  if (height < 0) {
    throw new RangeError(`cannot have negative log2Leafs`)
  }

  return new AggregateTree(height, options)
}

/**
 * @implements {API.AggregateTree}
 */
class AggregateTree {
  /**
   * @param {number} height
   * @param {object} [options]
   * @param {SparseArray<API.MerkleTreeNode>} [options.data]
   * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher]
   */
  constructor(height, options = {}) {
    /**
     * The sparse array contains the data of the tree. Levels of the tree are
     * counted from the leaf layer (layer 0).
     *
     * Where the leaf layer lands depends on the `height` of the tree.
     */
    this.data = options.data || new SparseArray()
    this.height = height
    this.hasher = options.hasher
  }

  get leafCount() {
    // Since this is a perfect binary tree, the leaf count is 2 ** height, it
    // is a bigint as it may exceed Number.MAX_SAFE_INTEGER (2 ** 53 - 1).
    return 2n ** BigInt(this.height)
  }

  get root() {
    return this.node(this.height, 0n)
  }

  /**
   * Collects a proof from the specified node to the root of the tree.
   *
   * @param {number} level
   * @param {API.uint64} offset
   * @returns {API.ProofData}
   */
  collectProof(level, offset) {
    Proof.validateLevelIndex(this.height, level, offset)
    const path = []
    let currentLevel = level
    let position = offset
    while (currentLevel < this.height) {
      // idx^1 is the sibling index
      const node = this.node(currentLevel, position ^ 1n)
      position = position / 2n
      path.push(node)
      currentLevel++
    }

    return Proof.create({ path, offset })
  }

  /**
   *
   * @param {number} level
   * @param {API.uint64} index
   */
  node(level, index) {
    const node = getNodeRaw(this, level, index)
    return node || ZeroComm.fromLevel(level)
  }

  /**
   *
   * @param {number} level
   * @param {API.uint64} index
   * @param {API.MerkleTreeNode} node
   */
  setNode(level, index, node) {
    Proof.validateLevelIndex(this.height, level, index)

    if (level > 0) {
      let left = getNodeRaw(this, level - 1, 2n * index)
      let right = getNodeRaw(this, level - 1, 2n * index + 1n)

      if (left) {
        throw new RangeError('left subtree is not empty')
      }

      if (right) {
        throw new RangeError('right subtree is not empty')
      }
    }

    this.data.set(idxFor(this.height, level, index), node)

    let currentIndex = index
    let n = level
    while (n < this.height) {
      const nextIndex = currentIndex >> 1n
      // clear the lowest bit of index for left node
      const left = getNodeRaw(this, n, currentIndex & ~1n)
      // set the lowest bit of index for right now
      const right = getNodeRaw(this, n, currentIndex | 1n)

      const node =
        /* c8 ignore next 2 */ // TODO: make test to cover this code path
        left == null && right == null
          ? Node.empty()
          : Proof.computeNode(
              left || ZeroComm.fromLevel(n),
              right || ZeroComm.fromLevel(n),
              { hasher: this.hasher }
            )

      this.data.set(idxFor(this.height, n + 1, nextIndex), node)
      currentIndex = nextIndex
      n++
    }
    return this
  }

  clear() {
    clear(this)
    return this
  }
}

/**
 * @type {number}
 */
const SparseBlockLog2Size = 8

/**
 * @type {number}
 */
const SparseBlockSize = 1 << SparseBlockLog2Size

const BigIntSparseBlockSize = BigInt(SparseBlockSize)

/**
 * @template T
 * @implements {API.SparseArray<T>}
 */
class SparseArray {
  /**
   * @param {Map<API.uint64, T[]>} shards
   */
  constructor(shards = new Map()) {
    /**
     * @private
     */
    this.shards = shards
  }
  clear() {
    this.shards.clear()
    return this
  }
  /**
   * @param {API.uint64} index
   * @returns {T | undefined}
   */
  at(index) {
    const subIndex = index / BigIntSparseBlockSize
    const sub = this.shards.get(subIndex)
    if (!sub) {
      return undefined
    }

    return sub[Number(index % BigIntSparseBlockSize)]
  }
  /**
   * @param {API.uint64} index
   * @param {T} value
   */
  set(index, value) {
    const subIndex = index / BigIntSparseBlockSize
    let shard = this.shards.get(subIndex)
    if (!shard) {
      shard = new Array(SparseBlockSize)
      this.shards.set(subIndex, shard)
    }

    shard[Number(index % BigIntSparseBlockSize)] = value

    return this
  }

  // ignore fon now it will be used by inclusion code
  /* c8 ignore next 25 */
  /**
   * @param {API.uint64} start
   * @param {API.uint64} end
   * @private
   */
  slice(start, end) {
    const startShard = start / BigIntSparseBlockSize
    const endShard = (end - 1n) / BigIntSparseBlockSize
    if (startShard !== endShard) {
      throw new Error('requested slice does not align with one sparse block')
    }

    let shard = this.shards.get(startShard)
    if (!shard) {
      shard = new Array(SparseBlockSize)
      this.shards.set(startShard, shard)
    }

    return shard.slice(
      Number(start % BigIntSparseBlockSize),
      Number(end % BigIntSparseBlockSize)
    )
  }
}

/**
 * @param {API.MerkleTreeBuilder} tree
 * @param {Iterable<API.MerkleTreeNodeSource>} values
 */
export const batchSet = (tree, values) => {
  for (const {
    location: { level, index },
    node,
  } of values) {
    tree.setNode(level, index, node)
  }
}

/**
 * @param {AggregateTree} tree
 */
export const clear = (tree) => {
  tree.data.clear()
}

/**
 * @typedef {{
 * height: number
 * data: SparseArray<API.MerkleTreeNode>
 * }} Model
 *
 * @param {Model} tree
 * @param {number} level
 * @param {API.uint64} idx
 */
const getNodeRaw = (tree, level, idx) => {
  Proof.validateLevelIndex(tree.height, level, idx)

  return tree.data.at(idxFor(tree.height, level, idx))
}

/**
 * @param {number} height
 * @param {number} level
 * @param {API.uint64} index
 * @returns {API.uint64}
 */
export const idxFor = (height, level, index) => {
  const depth = height - level
  // Hybrid Tree stores the MT as smaller trees in chunks dictated by SparseBlockSize
  // For example with SparseBlockLog2Size of 8, each SparseBlock will store a single
  // 8 deep tree. These threes are then stored one after breath-wise.
  const SubtreeDepth = SparseBlockLog2Size

  // how deep is the subtree counted by subtree
  const depthOfSubtree = Math.floor(depth / SubtreeDepth)
  const depthInSubtree = depth % SubtreeDepth

  // how wide is the subtree for given depth
  const widthOfSubtreeAtDepth = pow2(BigInt(depthInSubtree))
  // what is the index of the subtree we should write to
  const indexOfSubtree = index / widthOfSubtreeAtDepth
  // what is the index in subtree
  const indexInSubtree = widthOfSubtreeAtDepth + (index % widthOfSubtreeAtDepth)

  const offsetOfSubtreeLayer =
    (pow2(BigInt(depthOfSubtree + 1) * BigInt(SparseBlockLog2Size)) - 1n) /
      (BigIntSparseBlockSize - 1n) -
    1n

  const offsetOfSubtree =
    offsetOfSubtreeLayer + BigIntSparseBlockSize * indexOfSubtree

  return offsetOfSubtree + indexInSubtree
}
