/**
 * @see https://github.com/multiformats/go-multihash/blob/dc3bd6897fcd17f6acd8d4d6ffd2cea3d4d3ebeb/multihash.go#L73
 * @type {API.MulticodecCode<0x1012, 'sha2-256-trunc254-padded'>}
 */
export const Sha256Trunc254Padded: API.MulticodecCode<0x1012, 'sha2-256-trunc254-padded'>;
/**
 * @see https://github.com/ipfs/go-cid/blob/829c826f6be23320846f4b7318aee4d17bf8e094/cid.go#L104
 * @type {API.MulticodecCode<0xf101, 'fil-commitment-unsealed'>}
 */
export const FilCommitmentUnsealed: API.MulticodecCode<0xf101, 'fil-commitment-unsealed'>;
export function fromDigest(digest: API.PieceDigest): API.PieceView;
export function fromLink(link: API.PieceLink): API.PieceView;
export function fromString(source: string): API.PieceView;
export function toString(piece: API.Piece): API.ToString<API.PieceLink>;
export function fromJSON(json: unknown): API.PieceView;
export function toJSON(piece: API.Piece): {
    '/': API.ToString<API.PieceLink>;
};
export function fromPayload(payload: Uint8Array): API.PieceView;
export function toView(piece: API.Piece): API.PieceView;
export function toLink(piece: API.Piece): API.PieceLink;
export function toInfo(piece: API.Piece): API.PieceInfoView;
export function fromInfo(info: API.PieceInfo): API.PieceView;
import { MAX_PAYLOAD_SIZE } from './multihash.js';
import * as Size from './piece/size.js';
import * as API from './api.js';
declare class Piece {
    /**
     * @param {API.PieceLink} link
     */
    constructor(link: API.PieceLink);
    link: API.PieceLink;
    get padding(): bigint;
    get height(): number;
    get size(): API.PieceSize;
    get root(): Uint8Array;
    toJSON(): {
        '/': API.ToString<API.PieceLink>;
    };
    toString(): API.ToString<API.PieceLink>;
    toInfo(): Info;
}
/**
 * @implements {API.PieceInfo}
 */
declare class Info implements API.PieceInfo {
    /**
     * @param {API.Piece} piece
     */
    constructor(piece: API.Piece);
    piece: API.Piece;
    /** @type {API.LegacyPieceLink|undefined} */
    _link: API.LegacyPieceLink | undefined;
    get height(): number;
    get root(): API.MerkleTreeNode;
    get size(): API.PieceSize;
    get padding(): bigint;
    get link(): API.LegacyPieceLink;
    toJSON(): {
        link: {
            '/': API.ToString<API.Link<API.MerkleTreeNode, API.MulticodecCode<61697, "fil-commitment-unsealed">, API.MulticodecCode<4114, "sha2-256-trunc254-padded">, Link.Version>, string>;
        };
        height: number;
    };
    toString(): string;
}
import * as Link from 'multiformats/link';
export { MAX_PAYLOAD_SIZE, Size };
//# sourceMappingURL=piece.d.ts.map