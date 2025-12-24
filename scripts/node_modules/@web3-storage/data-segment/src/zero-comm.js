import * as API from './api.js'
import * as Node from './node.js'
import * as Proof from './proof.js'

const MAX_LEVEL = 64

/**
 * This is a lazy zero-comm buffer which we fill up on demand.
 */
class ZeroComm {
  constructor() {
    this.bytes = new Uint8Array(MAX_LEVEL * Node.Size)
    this.bytes.set(Node.empty(), 0)
    /** @private */
    this.node = Node.empty()
    /** @private */
    this.length = Node.Size
  }
  /**
   * @param {number} start
   * @param {number} end
   */
  slice(start, end) {
    while (this.length < end) {
      this.node = Proof.computeNode(this.node, this.node)
      this.bytes.set(this.node, this.length)
      this.length += Node.Size
    }

    return this.bytes.subarray(start, end)
  }
}
const ZERO_COMM = new ZeroComm()

/**
 * simple access by level, only levels between `0` and `64` inclusive are
 * available otherwise throws an error.
 *
 * @param {number} level
 * @returns {API.MerkleTreeNode}
 */
export const fromLevel = (level) => {
  if (level < 0 || level >= MAX_LEVEL) {
    throw new Error(
      `Only levels between 0 and ${MAX_LEVEL - 1} inclusive are available`
    )
  }

  return ZERO_COMM.slice(Node.Size * level, Node.Size * (level + 1))
}
