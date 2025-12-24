/**
 * We limit tree height to 60, since we have a perfect binary merkle tree this
 * will fit up to 2 ** 60 of leafs nodes.
 */
export const MAX_HEIGHT: 60;
export function create(height: number, options?: {
    hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
} | undefined): API.AggregateTree;
export function batchSet(tree: API.MerkleTreeBuilder, values: Iterable<API.MerkleTreeNodeSource>): void;
export function clear(tree: AggregateTree): void;
export function idxFor(height: number, level: number, index: API.uint64): API.uint64;
export type Model = {
    height: number;
    data: SparseArray<API.MerkleTreeNode>;
};
import * as API from '../api.js';
/**
 * @implements {API.AggregateTree}
 */
declare class AggregateTree implements API.AggregateTree {
    /**
     * @param {number} height
     * @param {object} [options]
     * @param {SparseArray<API.MerkleTreeNode>} [options.data]
     * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher]
     */
    constructor(height: number, options?: {
        data?: SparseArray<API.MerkleTreeNode> | undefined;
        hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
    } | undefined);
    /**
     * The sparse array contains the data of the tree. Levels of the tree are
     * counted from the leaf layer (layer 0).
     *
     * Where the leaf layer lands depends on the `height` of the tree.
     */
    data: SparseArray<API.MerkleTreeNode>;
    height: number;
    hasher: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
    get leafCount(): bigint;
    get root(): API.MerkleTreeNode;
    /**
     * Collects a proof from the specified node to the root of the tree.
     *
     * @param {number} level
     * @param {API.uint64} offset
     * @returns {API.ProofData}
     */
    collectProof(level: number, offset: API.uint64): API.ProofData;
    /**
     *
     * @param {number} level
     * @param {API.uint64} index
     */
    node(level: number, index: API.uint64): API.MerkleTreeNode;
    /**
     *
     * @param {number} level
     * @param {API.uint64} index
     * @param {API.MerkleTreeNode} node
     */
    setNode(level: number, index: API.uint64, node: API.MerkleTreeNode): this;
    clear(): this;
}
/**
 * @template T
 * @implements {API.SparseArray<T>}
 */
declare class SparseArray<T> implements API.SparseArray<T> {
    /**
     * @param {Map<API.uint64, T[]>} shards
     */
    constructor(shards?: Map<API.uint64, T[]>);
    /**
     * @private
     */
    private shards;
    clear(): this;
    /**
     * @param {API.uint64} index
     * @returns {T | undefined}
     */
    at(index: API.uint64): T | undefined;
    /**
     * @param {API.uint64} index
     * @param {T} value
     */
    set(index: API.uint64, value: T): this;
    /**
     * @param {API.uint64} start
     * @param {API.uint64} end
     * @private
     */
    private slice;
}
export {};
//# sourceMappingURL=tree.d.ts.map