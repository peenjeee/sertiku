import crypto from 'node:crypto'
import { name, size, code, prefix, Digest } from './digest.js'

export { name, size, code }

/**
 * @param {Uint8Array} payload
 * @returns {import('multiformats').MultihashDigest<typeof code>}
 */
export const digest = (payload) => {
  const digest = new Uint8Array(prefix.length + size)
  digest.set(prefix, 0)
  digest.set(
    crypto.createHash('sha256').update(payload).digest(),
    prefix.length
  )

  return new Digest(digest)
}
