export { Digest };
/**
 * @see https://github.com/multiformats/multicodec/pull/331/files
 */
export const name: "fr32-sha2-256-trunc254-padded-binary-tree";
/**
 * @type {API.MulticodecCode<0x1011, typeof name>}
 * @see https://github.com/multiformats/multicodec/pull/331/files
 */
export const code: API.MulticodecCode<0x1011, typeof name>;
/**
 * Since first byte in the digest is the tree height, the maximum height is 255.
 *
 * @type {255}
 */
export const MAX_HEIGHT: 255;
/**
 * Max payload is determined by the maximum height of the tree, which is limited
 * by the int we could store in one byte. We calculate the max piece size
 * and derive max payload size that can would produce it after FR32 padding.
 */
export const MAX_PAYLOAD_SIZE: bigint;
export function digest(payload: Uint8Array): API.PieceDigest;
export function create(): API.StreamingHasher<typeof code, number, API.PieceDigest>;
export type Layers = [API.MerkleTreeNode[], ...API.MerkleTreeNode[][]];
import * as Digest from './digest.js';
import * as API from './api.js';
//# sourceMappingURL=multihash.d.ts.map