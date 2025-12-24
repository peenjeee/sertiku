import * as API from '../../api.js'
import {
  EXPANDED_BYTES_PER_QUAD,
  EXPANDED_BYTES_PER_NODE,
} from '../../constant.js'
import { log2Ceil, onesCount64 } from '../../uint64.js'

export { toExpanded as fromPadded, fromExpanded as toPadded } from './padded.js'
export { toExpanded as fromUnpadded } from './unpadded.js'

/**
 * Validates that given `size` is a valid {@link API.PieceSize} that is a
 * power of 2. Returns {@link API.Result} with `PaddedPieceSize` ok case and an
 * Error in the error case.
 *
 * @see https://github.com/filecoin-project/go-state-types/blob/ff2ed169ff566458f2acd8b135d62e8ca27e7d0c/abi/piece.go#L18-L29
 *
 * @param {number|API.uint64} input
 * @returns {API.Result<API.PieceSize, RangeError>}
 */
export const tryFrom = (input) => {
  const size = BigInt(input)
  if (size < EXPANDED_BYTES_PER_QUAD) {
    return {
      error: RangeError(
        `Minimum piece size is ${EXPANDED_BYTES_PER_QUAD} bytes`
      ),
    }
  }

  if (onesCount64(size) !== 1) {
    return { error: RangeError('Piece size must be a power of 2') }
  }

  return { ok: size }
}

/**
 * Validates that given `size` is a valid {@link API.PieceSize} and
 * returns {@link API.PieceSize} capturing the validation at the type
 * level. If given `size` is not a valid `PaddedPieceSize` throws an error.
 *
 * This function is a variation on {@link validate} that throws exceptions
 * instead of returning a {@link API.Result}.
 *
 * @param {number|API.uint64} size
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
 * Calculates the {@link API.PieceSize} for the given height of the piece tree.
 *
 * @param {number} height
 * @returns {API.PieceSize}
 */
export const fromHeight = (height) => fromWidth(2n ** BigInt(height))

/**
 * Calculates the height of the piece tree from unpadded size.
 *
 * @param {API.PieceSize} size
 */
export const toHeight = (size) => log2Ceil(toWidth(size))

/**
 * Takes piece tree width (leaf count) and returns corresponding
 * {@link API.PieceSize}.
 *
 * @param {API.uint64} width
 * @returns {API.PieceSize}
 */
export const fromWidth = (width) => width * EXPANDED_BYTES_PER_NODE

/**
 * Takes `{@link API.PaddedPieceSize}` and returns corresponding
 * piece tree width (leaf count).
 *
 * @param {API.PieceSize} size
 */
export const toWidth = (size) => size / EXPANDED_BYTES_PER_NODE
