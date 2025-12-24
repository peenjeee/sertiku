import * as API from '../../api.js'
import {
  PADDED_BYTES_PER_QUAD,
  EXPANDED_BYTES_PER_QUAD,
  LEAFS_PER_QUAD,
} from '../../constant.js'
import { log2Ceil } from '../../uint64.js'
import * as Padded from './padded.js'

/**
 * Takes the {@link API.Piece} sizing details (height and padding) and
 * calculates original payload size.
 *
 * @param {object} piece
 * @param {number} piece.height
 * @param {API.uint64} piece.padding
 */
export const fromPiece = ({ height, padding }) =>
  Padded.fromHeight(height) - padding

/**
 * Takes arbitrary payload size and calculates 0-padding required to
 * produce a {@link API.PaddedSize}.
 *
 * @param {API.uint64} size
 */
export const toPadding = (size) => toPadded(size) - size

/**
 * Takes arbitrary payload size and calculates size after required 0-padding.
 *
 * @param {API.uint64} size
 * @returns {API.PaddedSize}
 */
export const toPadded = (size) => toQauds(size) * PADDED_BYTES_PER_QUAD

/**
 * Takes arbitrary payload size and calculates the piece size after required
 * 0-padding and FR32 expansion is applied.
 *
 * @param {API.uint64} size
 * @returns {API.PieceSize}
 */
export const toExpanded = (size) => toQauds(size) * EXPANDED_BYTES_PER_QUAD

/**
 * Takes arbitrary payload size and calculates width of the piece tree (leaf
 * count) that will be required to represent it.
 *
 * @param {API.uint64} size
 */
export const toWidth = (size) => toQauds(size) * LEAFS_PER_QUAD

/**
 * Takes arbitrary payload size and calculates height of the piece tree that will be required to represent it.
 *
 * @param {API.uint64} size
 */
export const toHeight = (size) => log2Ceil(toWidth(size))

/**
 * Takes arbitrary payload size and calculates number of quads that will be
 * required to represent it.
 *
 * @param {API.uint64} size
 */
const toQauds = (size) => {
  // Number of quads required to fit given payload size.
  // Since bigint division truncates we add another quads shy of 1 number of
  // bytes to round up.
  const quadCount = (size + PADDED_BYTES_PER_QUAD - 1n) / PADDED_BYTES_PER_QUAD
  // Next we we log2 then pow2 with some rounding to ensure that result
  // is 2 ^ n.
  return 2n ** BigInt(log2Ceil(quadCount))
}
