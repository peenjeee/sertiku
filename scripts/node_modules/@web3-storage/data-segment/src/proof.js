import * as API from './api.js'

import * as Bytes from 'multiformats/bytes'
import { Size as NodeSize } from './node.js'
import { CBOR, SHA256 } from './ipld.js'

/**
 * @param {API.ProofData} proof
 * @returns {API.MerkleTreePath}
 */
export const path = ([, path]) => path

/**
 * @param {API.ProofData} proof
 * @returns {API.uint64}
 */
export const offset = ([offset]) => offset

/**
 * @param {API.ProofData} proof
 * @returns {number}
 */
export const depth = (proof) => path(proof).length

/**
 * Verifies that `proof` proves that `claim.node` is contained by
 * the `claim.tree` merkle tree.
 *
 * @param {API.ProofData} proof
 * @param {object} claim
 * @param {API.MerkleTreeNode} claim.tree
 * @param {API.MerkleTreeNode} claim.node
 * @returns {API.Result<{}, Error>}
 */
export const verify = (proof, { tree, node }) => {
  const computedRoot = resolveRoot(proof, node)
  if (computedRoot.error) {
    return { error: new Error(`computing root: ${computedRoot.error.message}`) }
  }

  if (!Bytes.equals(computedRoot.ok, tree)) {
    return {
      error: new Error('inclusion proof does not lead to the same root'),
    }
  }
  return { ok: {} }
}

const MAX_DEPTH = 63

/**
 * Resolves the root of the merkle tree from given proof and node that root
 * supposedly includes. It does so by computing parent node from provided node
 * and node in the proof path, then combining that with the next node in the
 * path and so on until the root is reached. Function may return an error if
 * proof path is too long or if proof offset falls out of bounds.
 *
 * @param {API.ProofData} proof
 * @param {API.MerkleTreeNode} node
 * @returns {API.Result<API.MerkleTreeNode, RangeError>}
 */
export function resolveRoot(proof, node) {
  if (depth(proof) > MAX_DEPTH) {
    return {
      error: new RangeError(
        'merkle proofs with depths greater than 63 are not supported'
      ),
    }
  }

  let position = offset(proof)
  if (position >> BigInt(depth(proof)) !== 0n) {
    return { error: new RangeError('offset greater than width of the tree') }
  }

  let top = node
  let right = 0n

  for (const node of path(proof)) {
    right =  position & 1n
    position = position >> 1n
    top = right === 1n ? computeNode(node, top) : computeNode(top, node)
  }

  return { ok: top }
}

/**
 * @param {Uint8Array} payload
 * @param {object} [options]
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher]
 * @returns {API.MerkleTreeNode}
 */
export function truncatedHash(payload, options = {}) {
  const hasher = options.hasher || SHA256
  const { digest } = hasher.digest(payload)
  return truncate(digest)
}

/**
 * @param {API.MerkleTreeNode} left
 * @param {API.MerkleTreeNode} right
 * @param {object} [options]
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher]
 * @returns {API.MerkleTreeNode}
 */
export const computeNode = (left, right, options) => {
  const payload = new Uint8Array(left.length + right.length)
  payload.set(left, 0)
  payload.set(right, left.length)
  return truncatedHash(payload, options)
}

/**
 * @param {API.MerkleTreeNode} node
 * @returns {API.MerkleTreeNode}
 */
export function truncate(node) {
  node[NodeSize - 1] &= 0b00111111
  return node
}

/**
 * Takes data model and returns an IPLD View of it.
 *
 * @param {object} source
 * @param {API.uint64} source.offset
 * @param {API.MerkleTreePath} source.path
 * @returns {API.ProofData}
 */
export const create = ({ offset, path }) => [offset, path]

/**
 * Takes proof in somewhat arbitrary form and returns a proof data.
 *
 * @param {API.IntoProofData} source
 * @returns {API.ProofData}
 */
export const from = (source) => {
  const [offset, path] = Array.isArray(source)
    ? source
    : [source.offset, source.path]

  return create({ offset: BigInt(offset), path })
}

/**
 * @param {number} height - Height of the merkle tree
 * @param {number} level - Level of the node in the merkle tree
 * @param {API.uint64} index - Index of the node in the level
 */
export const validateLevelIndex = (height, level, index) => {
  if (level < 0) {
    throw new RangeError('level can not be negative')
  }

  if (level > height) {
    throw new RangeError(`level too high: ${level} >= ${height}`)
  }

  if (index > (1 << (height - level)) - 1) {
    throw new RangeError(
      `index too large for level: idx ${index}, level ${level} : ${
        (1 << (height - level)) - 1
      }`
    )
  }
}
