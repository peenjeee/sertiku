/**
 * Resolves the root of the merkle tree from given proof and node that root
 * supposedly includes. It does so by computing parent node from provided node
 * and node in the proof path, then combining that with the next node in the
 * path and so on until the root is reached. Function may return an error if
 * proof path is too long or if proof offset falls out of bounds.
 *
 * @param {API.ProofData} proof
 * @param {API.MerkleTreeNode} node
 * @returns {API.Result<API.MerkleTreeNode, RangeError>}
 */
export function resolveRoot(proof: API.ProofData, node: API.MerkleTreeNode): API.Result<API.MerkleTreeNode, RangeError>;
/**
 * @param {Uint8Array} payload
 * @param {object} [options]
 * @param {API.SyncMultihashHasher<API.SHA256_CODE>} [options.hasher]
 * @returns {API.MerkleTreeNode}
 */
export function truncatedHash(payload: Uint8Array, options?: {
    hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
} | undefined): API.MerkleTreeNode;
/**
 * @param {API.MerkleTreeNode} node
 * @returns {API.MerkleTreeNode}
 */
export function truncate(node: API.MerkleTreeNode): API.MerkleTreeNode;
export function path([, path]: API.ProofData): API.MerkleTreePath;
export function offset([offset]: API.ProofData): API.uint64;
export function depth(proof: API.ProofData): number;
export function verify(proof: API.ProofData, { tree, node }: {
    tree: API.MerkleTreeNode;
    node: API.MerkleTreeNode;
}): API.Result<{}, Error>;
export function computeNode(left: API.MerkleTreeNode, right: API.MerkleTreeNode, options?: {
    hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
} | undefined): API.MerkleTreeNode;
export function create({ offset, path }: {
    offset: API.uint64;
    path: API.MerkleTreePath;
}): API.ProofData;
export function from(source: API.IntoProofData): API.ProofData;
export function validateLevelIndex(height: number, level: number, index: API.uint64): void;
import * as API from './api.js';
//# sourceMappingURL=proof.d.ts.map