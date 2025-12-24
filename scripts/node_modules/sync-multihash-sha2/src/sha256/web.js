/* c8 ignore next */
import { sha256 } from '@noble/hashes/sha256'
import { name, size, code, prefix, Digest } from './digest.js'
export { name, size, code }

/**
 * @param {Uint8Array} payload
 * @returns {import('multiformats').MultihashDigest<typeof code>}
 */
export const digest = (payload) => {
  const digest = new Uint8Array(prefix.length + size)
  digest.set(prefix, 0)
  digest.set(sha256(payload), prefix.length)

  return new Digest(digest)
}
