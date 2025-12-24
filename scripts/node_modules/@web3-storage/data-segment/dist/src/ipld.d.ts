export * as SHA256 from "./ipld/sha256.js";
export * as CBOR from "./ipld/cbor.js";
export function createLink<Layout, Format extends API.MulticodecCode<number, string>, Hash extends API.MulticodecCode<number, string>>(bytes: API.ByteView<Layout, Format>, { hasher, codec }: {
    hasher: API.SyncMultihashHasher<Hash>;
    codec: {
        code: Format;
    };
}): API.Link<Layout, Format, Hash, 1>;
import * as API from './api.js';
import * as Link from 'multiformats/link';
//# sourceMappingURL=ipld.d.ts.map