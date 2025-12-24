/* c8 ignore next */
import { sha512 } from '@noble/hashes/sha512'
import { name, code, size, prefix, Digest } from './digest.js'

export { name, code, size }

/**
 * @param {Uint8Array} payload
 * @returns {import('multiformats').MultihashDigest<typeof code>}
 */
export const digest = (payload) => {
  const digest = new Uint8Array(prefix.length + size)
  digest.set(prefix, 0)
  digest.set(sha512(payload), prefix.length)

  return new Digest(digest)
}
