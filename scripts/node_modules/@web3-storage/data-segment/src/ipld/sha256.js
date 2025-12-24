import * as API from '../api.js'
import * as SHA256 from 'sync-multihash-sha2/sha256'
export * from 'sync-multihash-sha2/sha256'

/**
 * @type {API.MulticodecCode<typeof SHA256.code, typeof SHA256.name>}
 */
export const code = SHA256.code
