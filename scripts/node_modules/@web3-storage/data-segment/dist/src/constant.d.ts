/**
 * The number of Frs per Block.
 */
export const FRS_PER_QUAD: 4;
export const LEAFS_PER_QUAD: 4n;
/**
 * The amount of bits in an Fr when not padded.
 */
export const IN_BITS_FR: 254;
/**
 * The amount of bits in an Fr when padded.
 */
export const OUT_BITS_FR: 256;
export const IN_BYTES_PER_QUAD: 127;
export const OUT_BYTES_PER_QUAD: 128;
export const PADDED_BYTES_PER_QUAD: 127n;
export const EXPANDED_BYTES_PER_QUAD: 128n;
export const BYTES_PER_FR: number;
export const FR_RATIO: number;
/**
 * Size of a node in the merkle tree.
 */
export const NODE_SIZE: 32;
export const EXPANDED_BYTES_PER_NODE: 32n;
/**
 * The smallest amount of data for which FR32 padding has a defined result.
 * Silently upgrading 2 leaves to 4 would break the symmetry so we require
 * an extra byte and the rest can be 0 padded to expand to 4 leaves.
 */
export const MIN_PAYLOAD_SIZE: number;
//# sourceMappingURL=constant.d.ts.map