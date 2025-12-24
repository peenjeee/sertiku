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
 * One byte is used to store the tree height.
 */
export const HEIGHT_SIZE: 1;
/**
 * Amount of bytes used to store the tree root.
 */
export const ROOT_SIZE: 32;
/**
 * Size of the multihash digest in bytes.
 */
export const MAX_DIGEST_SIZE: number;
export const TAG_SIZE: number;
/**
 * Max size of the multihash in bytes
 */
export const MAX_SIZE: number;
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
export function fromPiece({ padding, height, root }: API.Piece): API.PieceDigest;
export function fromBytes(bytes: Uint8Array): API.PieceDigest;
export function toBytes({ digest }: {
    digest: Uint8Array;
}): Uint8Array;
export function height({ digest }: {
    digest: Uint8Array;
}): number;
export function padding({ digest }: {
    digest: Uint8Array;
}): bigint;
export function root({ digest }: {
    digest: Uint8Array;
}): Uint8Array;
import * as API from './api.js';
//# sourceMappingURL=digest.d.ts.map