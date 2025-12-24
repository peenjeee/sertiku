import * as API from '../api.js'
import { Size as NodeSize } from '../node.js'
import * as Proof from '../proof.js'
export { computeNode } from '../proof.js'

// The value is an unsigned, 32-bit integer that is always numerically greater
// than the highest index in the array. This means our tree can represent a
// piece up to 128 GiB in size.
export const MAX_LEAF_COUNT = 2 ** 32 - 1

/**
 * Allocates a tree for a given amount of leafs.
 *
 * The construction rounds the amount of leafs up to the nearest two-power with
 * zeroed nodes to ensure that the tree is perfect and hence all internal node's
 * have well-defined children.
 *
 * @param {number} leafs
 */
export function allocate(leafs) {
  const adjustedLeafs = 2 ** Math.ceil(Math.log2(leafs))

  if (adjustedLeafs > MAX_LEAF_COUNT) {
    throw new RangeError(
      `too many leafs ${adjustedLeafs} exceeds ${MAX_LEAF_COUNT} limit`
    )
  }

  const height = Math.ceil(Math.log2(adjustedLeafs))
  const nodes = new Array(height + 1)

  for (const level of nodes.keys()) {
    nodes[level] = new Array(1 << level)
  }

  return new PieceTree({ nodes, height })
}

/**
 * @param {API.TreeData} tree
 */
const depth = (tree) => {
  return tree.nodes.length
}

/**
 *
 * @param {API.TreeData} tree
 * @returns {API.MerkleTreeNode}
 */
export const root = (tree) => {
  return tree.nodes[0][0]
}

/**
 * @param {Uint8Array} source
 * @returns {API.MerkleTreeNode[]}
 */
export const split = (source) => {
  const count = source.length / NodeSize
  const chunks = new Array(count)
  for (let n = 0; n < count; n++) {
    const offset = n * NodeSize
    const chunk = source.subarray(offset, offset + NodeSize)
    chunks[n] = chunk
  }
  return chunks
}

/**
 * @param {API.Fr23Padded} source
 */
export const build = (source) => fromChunks(split(source))

/**
 * @param {API.MerkleTreeNode[]} chunks
 */
export const fromChunks = (chunks) => {
  if (chunks.length === 0) {
    throw new RangeError('Empty source')
  }

  const leafs = chunks //await Promise.all(chunks.map(truncatedHash))
  return fromLeafs(leafs)
}

/**
 * @param {API.MerkleTreeNode[]} leafs
 * @returns {API.PieceTree}
 */
export const fromLeafs = (leafs) => {
  const tree = allocate(leafs.length)
  // Set the padded leaf nodes
  tree.nodes[depth(tree) - 1] = padLeafs(leafs)
  let parentNodes = tree.nodes[depth(tree) - 1]
  // Construct the Merkle tree bottom-up, starting from the leafs
  // Note the -1 due to 0-indexing the root level
  for (let level = depth(tree) - 2; level >= 0; level--) {
    /** @type {API.MerkleTreeNode[]} */
    const currentLevel = new Array(Math.ceil(parentNodes.length / 2))
    // Traverse the level left to right
    for (let i = 0; i + 1 < parentNodes.length; i = i + 2) {
      currentLevel[Math.floor(i / 2)] = Proof.computeNode(
        parentNodes[i],
        parentNodes[i + 1]
      )
    }
    tree.nodes[level] = currentLevel
    parentNodes = currentLevel
  }

  return new PieceTree(tree)
}

/**
 * @param {API.MerkleTreeNode[]} leafs
 * @returns {API.MerkleTreeNode[]}
 */
export const padLeafs = (leafs) => {
  const paddingAmount = (1 << Math.ceil(Math.log2(leafs.length))) - leafs.length
  // arrays are zeroed by default in JS
  const paddingLeafs = new Array(paddingAmount)

  return [...leafs, ...paddingLeafs]
}

/**
 * @implements {API.PieceTree}
 */
class PieceTree {
  /**
   * @param {object} data
   * @param {API.MerkleTreeNode[][]} data.nodes
   * @param {number} data.height
   */
  constructor({ nodes, height }) {
    this.nodes = nodes
    this.height = height
  }

  get root() {
    return root(this)
  }
  get leafs() {
    const { nodes } = this
    return nodes[nodes.length - 1]
  }
  get leafCount() {
    return 2 ** this.height
  }
  /**
   *
   * @param {number} level
   * @param {number} index
   */
  node(level, index) {
    const { nodes } = this
    return nodes[level][index]
  }
}
