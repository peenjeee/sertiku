import * as API from '../../api.js'
import { log2Ceil, trailingZeros64 } from '../../uint64.js'
import {
  PADDED_BYTES_PER_QUAD,
  EXPANDED_BYTES_PER_QUAD,
  LEAFS_PER_QUAD,
} from '../../constant.js'

/**
 * Validates that given `size` is a valid {@link API.UnpaddedPieceSize} and
 * returns {@link API.UnpaddedPieceSize} capturing the validation at the type
 * level. If given `size` is not a valid `UnpaddedPieceSize` throws an error.
 *
 * This function is a variation on {@link validate} that throws exceptions
 * instead of returning a {@link API.Result}.
 *
 * @param {number|API.uint64} size
 * @returns {API.PaddedSize}
 */
export const from = (size) => {
  const result = tryFrom(size)
  if (result.error) {
    throw result.error
  } else {
    return result.ok
  }
}

/**
 * Validates that given `size` is a valid {@link API.UnpaddedPieceSize} that is
 * a power of 2 multiple of 127. Returns {@link API.Result} with
 * `UnpaddedPieceSize` ok case and an Error in the error case.
 *
 * @param {API.uint64|number} input
 * @returns {API.Result<API.PaddedSize, Error>}
 */
export const tryFrom = (input) => {
  const size = BigInt(input)
  if (size < PADDED_BYTES_PER_QUAD) {
    return {
      error: new RangeError(
        `Padded payload must contain at least ${PADDED_BYTES_PER_QUAD} bytes`
      ),
    }
  }

  if (size >> BigInt(trailingZeros64(size)) !== PADDED_BYTES_PER_QUAD) {
    return {
      error: new RangeError(
        `Padded payload size must be (2ⁿ * ${PADDED_BYTES_PER_QUAD})`
      ),
    }
  }

  return { ok: size }
}

/**
 * @param {API.PieceSize} size
 * @returns {API.PaddedSize}
 */
export const fromExpanded = (size) => fromQuads(size / EXPANDED_BYTES_PER_QUAD)

/**
 * Takes `{@link API.PaddedPieceSize}` and returns corresponding
 * {@link API.PieceSize}.
 *
 * Please note that this function does not validate the input size and
 * relies that type-checker will ensure that user passes valid unpadded
 * piece size created with {@link from} or {@link validate} functions.
 *
 *
 * @see https://github.com/filecoin-project/go-state-types/blob/master/abi/piece.go#L14-L16
 *
 * @param {API.PaddedSize} size
 * @returns {API.PieceSize}
 */
export const toExpanded = (size) => toQauds(size) * EXPANDED_BYTES_PER_QUAD

/**
 * Calculates the padded size of the piece from the given tree height.
 *
 * @param {number} height
 * @returns {API.uint64}
 */
export const fromHeight = (height) => {
  // We calculate number of quads tree by calculating number of nodes tree
  // at second layer. This works because we deal with a binary tree so first
  // layer nodes will contain 2 leaves and second layer nodes will contain 4
  // leaves hence number of quads.
  const quads = 2n ** BigInt(height - 2)
  return quads * PADDED_BYTES_PER_QUAD
}

/**
 * Calculates the height of the piece tree from unpadded size.
 *
 * @param {API.PaddedSize} size
 */
export const toHeight = (size) => log2Ceil(toWidth(size))

/**
 * Takes `{@link API.PaddedPieceSize}` and returns corresponding
 * piece tree width (leaf count).
 *
 * @param {API.PaddedSize} size
 */
export const toWidth = (size) => toQauds(size) * LEAFS_PER_QUAD

/**
 *
 * @param {API.uint64} width
 * @returns {API.PaddedSize}
 */
export const fromWidth = (width) => fromQuads(width / LEAFS_PER_QUAD)

/**
 * @param {API.PaddedSize} size
 */
const toQauds = (size) => size / PADDED_BYTES_PER_QUAD

/**
 *
 * @param {API.uint64} count
 * @returns {API.PaddedSize}
 */
const fromQuads = (count) => count * PADDED_BYTES_PER_QUAD
