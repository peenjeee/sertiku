export { Expanded as Size } from "./piece/size.js";
export const MAX_CAPACITY: API.PieceSize;
export const Proof: typeof InclusionProof.Proof;
/**
 * Default aggregate size (32GiB).
 */
export const DEFAULT_DEAL_SIZE: API.PieceSize;
export function createBuilder({ size, hasher }?: {
    size?: API.PieceSize | undefined;
    hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
} | undefined): AggregateBuilder;
export function build({ pieces, size, hasher }: {
    pieces: API.Piece[];
    size?: API.PieceSize | undefined;
    hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
}): API.AggregateView;
export function toLink({ root, height }: {
    root: API.MerkleTreeNode;
    height: number;
}): API.PieceLink;
export function resolveSegment(aggregate: Aggregate, piece: API.Piece): API.Result<[number, API.SegmentInfo], RangeError>;
export function resolveProof(aggregate: Aggregate, piece: API.Piece): API.Result<API.InclusionProof, RangeError>;
import * as API from './api.js';
import * as InclusionProof from './inclusion.js';
import * as Tree from './aggregate/tree.js';
declare class AggregateBuilder {
    /**
     * @param {object} source
     * @param {API.PieceSize} source.size
     * @param {API.uint64} [source.offset]
     * @param {API.MerkleTreeNodeSource[]} [source.parts]
     * @param {number} [source.limit]
     * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [source.hasher]
     */
    constructor({ size, limit, offset, parts, hasher }: {
        size: API.PieceSize;
        offset?: bigint | undefined;
        parts?: API.MerkleTreeNodeSource[] | undefined;
        limit?: number | undefined;
        hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
    });
    size: API.PieceSize;
    offset: bigint;
    parts: API.MerkleTreeNodeSource[];
    /**
     * Maximum number of pieces that could be added to this aggregate.
     */
    limit: number;
    hasher: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
    /**
     * Size of the index in bytes.
     */
    get indexSize(): number;
    /**
     * Height of the perfect binary merkle tree corresponding to this aggregate.
     */
    get height(): number;
    /**
     * @returns {API.AggregateView}
     */
    build(): API.AggregateView;
    /**
     * @param {API.Piece} piece
     */
    write(piece: API.Piece): this;
    /**
     * Computes addition to the current aggregate if it were to write
     * provided segment.
     *
     * @param {API.Piece} piece
     * @returns {API.Result<{
     *   parts: [API.MerkleTreeNodeSource]
     *   offset: API.uint64
     * }, RangeError>}
     */
    estimate(piece: API.Piece): API.Result<{
        parts: [API.MerkleTreeNodeSource];
        offset: API.uint64;
    }, RangeError>;
}
import * as Piece from './piece.js';
/**
 * @implements {API.AggregateView}
 */
declare class Aggregate implements API.AggregateView {
    /**
     * @param {object} source
     * @param {API.PieceSize} source.size
     * @param {API.uint64} source.offset
     * @param {API.MerkleTreeNodeSource[]} source.parts
     * @param {API.IndexData} source.index
     * @param {number} source.limit
     * @param {API.AggregateTree} source.tree
     */
    constructor({ tree, parts, index, limit, size, offset }: {
        size: API.PieceSize;
        offset: API.uint64;
        parts: API.MerkleTreeNodeSource[];
        index: API.IndexData;
        limit: number;
        tree: API.AggregateTree;
    });
    tree: API.AggregateTree<bigint>;
    parts: API.MerkleTreeNodeSource[];
    index: API.IndexData;
    limit: number;
    size: API.PieceSize;
    offset: bigint;
    link: API.PieceLink;
    /**
     * Size of the index in bytes.
     */
    get indexSize(): number;
    get root(): API.MerkleTreeNode;
    /**
     * Height of the perfect binary merkle tree corresponding to this aggregate.
     */
    get height(): number;
    get padding(): bigint;
    toJSON(): {
        '/': API.ToString<API.PieceLink>;
    };
    toInfo(): API.PieceInfoView;
    /**
     * @param {API.PieceLink} piece
     */
    resolveProof(piece: API.PieceLink): API.Result<API.InclusionProof, RangeError>;
}
export { InclusionProof, Tree };
//# sourceMappingURL=aggregate.d.ts.map