import * as API from './api.js'
export * as SHA256 from './ipld/sha256.js'
export * as CBOR from './ipld/cbor.js'
import * as Link from 'multiformats/link'

/**
 * @template Layout
 * @template {API.MulticodecCode} Format
 * @template {API.MulticodecCode} Hash
 * @param {API.ByteView<Layout, Format>} bytes
 * @param {object} settings
 * @param {API.SyncMultihashHasher<Hash>} settings.hasher
 * @param {object} settings.codec
 * @param {Format} settings.codec.code
 * @returns {API.Link<Layout, Format, Hash>}
 */
export const createLink = (bytes, { hasher, codec }) => {
  const digest = hasher.digest(bytes)
  return Link.create(codec.code, digest)
}
