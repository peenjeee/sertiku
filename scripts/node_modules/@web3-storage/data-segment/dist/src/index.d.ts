export const Uint64Size: number;
export const ChecksumSize: 16;
/**
 * The size of an entry in bytes. This is number of bytes encoded
 * {@link API.Segment} will take: {@link Node.Size} for the merkle root,
 * {@link Uint64Size} for the segment `offset`, {@link Uint64Size} for the
 * segment `size` and {@link Segment.ChecksumSize} for the segment `checksum`.
 */
export const EntrySize: bigint;
export function maxIndexEntriesInDeal(size: API.uint64): number;
import * as API from './api.js';
//# sourceMappingURL=index.d.ts.map