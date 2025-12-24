/**
 * Allocates a tree for a given amount of leafs.
 *
 * The construction rounds the amount of leafs up to the nearest two-power with
 * zeroed nodes to ensure that the tree is perfect and hence all internal node's
 * have well-defined children.
 *
 * @param {number} leafs
 */
export function allocate(leafs: number): PieceTree;
export { computeNode } from "../proof.js";
export const MAX_LEAF_COUNT: number;
export function root(tree: API.TreeData): API.MerkleTreeNode;
export function split(source: Uint8Array): API.MerkleTreeNode[];
export function build(source: API.Fr23Padded): API.PieceTree;
export function fromChunks(chunks: API.MerkleTreeNode[]): API.PieceTree;
export function fromLeafs(leafs: API.MerkleTreeNode[]): API.PieceTree;
export function padLeafs(leafs: API.MerkleTreeNode[]): API.MerkleTreeNode[];
/**
 * @implements {API.PieceTree}
 */
declare class PieceTree implements API.PieceTree {
    /**
     * @param {object} data
     * @param {API.MerkleTreeNode[][]} data.nodes
     * @param {number} data.height
     */
    constructor({ nodes, height }: {
        nodes: API.MerkleTreeNode[][];
        height: number;
    });
    nodes: API.MerkleTreeNode[][];
    height: number;
    get root(): API.MerkleTreeNode;
    get leafs(): API.MerkleTreeNode[];
    get leafCount(): number;
    /**
     *
     * @param {number} level
     * @param {number} index
     */
    node(level: number, index: number): API.MerkleTreeNode;
}
import * as API from '../api.js';
//# sourceMappingURL=tree.d.ts.map