import * as API from './api.js'
import { NODE_SIZE as Size } from './constant.js'

export { Size }

/**
 * @param {number[]} bytes
 */
export const of = (...bytes) => from(bytes)

/**
 * @param {Iterable<number>} bytes
 * @returns {API.MerkleTreeNode}
 */
export const from = (bytes) => {
  /* c8 ignore next 7 */
  if (bytes instanceof Uint8Array) {
    if (bytes.length > Size) {
      return bytes.subarray(0, Size)
    } else if (bytes.length == Size) {
      return bytes
    }
  }

  const node = new Uint8Array(Size)
  node.set([...bytes])
  return node
}

export const empty = () => EMPTY

const EMPTY = from(new Uint8Array(Size).fill(0))
Object.freeze(EMPTY.buffer)
