/**
 * Number of bits per byte
 */
const BITS_PER_BYTE = 8

/**
 * The number of Frs per Block.
 */
export const FRS_PER_QUAD = 4

export const LEAFS_PER_QUAD = /** @type {4n} */ (BigInt(FRS_PER_QUAD))

/**
 * The amount of bits in an Fr when not padded.
 */
export const IN_BITS_FR = 254
/**
 * The amount of bits in an Fr when padded.
 */
export const OUT_BITS_FR = 256

export const IN_BYTES_PER_QUAD =
  /** @type {127} */
  ((FRS_PER_QUAD * IN_BITS_FR) / BITS_PER_BYTE)

export const OUT_BYTES_PER_QUAD =
  /** @type {128} */
  ((FRS_PER_QUAD * OUT_BITS_FR) / BITS_PER_BYTE)

export const PADDED_BYTES_PER_QUAD = /** @type {127n} */ (
  BigInt(IN_BYTES_PER_QUAD)
)

export const EXPANDED_BYTES_PER_QUAD = /** @type {128n} */ (
  BigInt(OUT_BYTES_PER_QUAD)
)

export const BYTES_PER_FR =
  /** @type {32} */
  OUT_BYTES_PER_QUAD / FRS_PER_QUAD

export const FR_RATIO = IN_BITS_FR / OUT_BITS_FR

/**
 * Size of a node in the merkle tree.
 */
export const NODE_SIZE =
  /** @type {32} */
  (OUT_BYTES_PER_QUAD / FRS_PER_QUAD)

export const EXPANDED_BYTES_PER_NODE = /** @type {32n} */ (BigInt(NODE_SIZE))

/**
 * The smallest amount of data for which FR32 padding has a defined result.
 * Silently upgrading 2 leaves to 4 would break the symmetry so we require
 * an extra byte and the rest can be 0 padded to expand to 4 leaves.
 */
export const MIN_PAYLOAD_SIZE = 2 * NODE_SIZE + 1
