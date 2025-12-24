/**
 * @type {API.MulticodecCode<typeof CBOR.code, typeof CBOR.name>}
 */
export const code: API.MulticodecCode<typeof CBOR.code, typeof CBOR.name>;
export const name: "dag-cbor";
export function encode<T>(model: T): API.ByteView<T, API.MulticodecCode<113, "dag-cbor">>;
export function decode<T>(bytes: API.ByteView<T, API.MulticodecCode<113, "dag-cbor">>): T;
import * as API from '../api.js';
import * as CBOR from '@ipld/dag-cbor';
//# sourceMappingURL=cbor.d.ts.map