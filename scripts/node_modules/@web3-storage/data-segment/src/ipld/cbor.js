import * as API from '../api.js'
import * as CBOR from '@ipld/dag-cbor'

/**
 * @type {API.MulticodecCode<typeof CBOR.code, typeof CBOR.name>}
 */
export const code = CBOR.code

export const name = CBOR.name

export const encode =
  /** @type {<T>(model:T) => API.ByteView<T, typeof code>} */
  (CBOR.encode)

export const decode =
  /** @type {<T>(bytes:API.ByteView<T, typeof code>) => T} */
  (CBOR.decode)
