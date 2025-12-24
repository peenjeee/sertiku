/**
 * The size of the checksum in bytes
 */
export const ChecksumSize: 16;
/**
 * Amount of bytes a uint64 will take.
 */
export const Uint64Size: number;
export function computeChecksum(segment: API.Segment, options?: {
    hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
} | undefined): API.Checksum<API.Segment, typeof ChecksumSize>;
export function withChecksum(segment: API.Segment, options?: {
    hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
} | undefined): API.SegmentInfo;
export function fromSource({ node, location }: API.MerkleTreeNodeSource): {
    root: API.MerkleTreeNode;
    offset: bigint;
    size: bigint;
};
export function toSource(segment: API.Segment): API.MerkleTreeNodeSource;
export function fromSourceWithChecksum(source: API.MerkleTreeNodeSource, options?: {
    hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
} | undefined): API.SegmentInfo;
export function toLeafIndex({ index, level }: API.MerkleTreeLocation): bigint;
export function toIndexNode(segment: API.Segment): API.MerkleTreeNode;
export function toBytes({ root, size, offset, checksum }: (API.Segment & {
    checksum?: undefined;
}) | API.SegmentInfo, options?: {
    hasher?: API.SyncMultihashHasher<API.MulticodecCode<18, "sha2-256">> | undefined;
} | undefined): API.ByteView<API.SegmentInfo, API.RAW_CODE>;
export function validate(segment: API.Segment): API.Result<API.Segment, Error>;
import * as API from './api.js';
//# sourceMappingURL=segment.d.ts.map