import * as API from './api.js'
import {
  OUT_BYTES_PER_QUAD,
  FR_RATIO,
  IN_BYTES_PER_QUAD,
  MIN_PAYLOAD_SIZE,
} from './constant.js'

/**
 * Determine the additional bytes of zeroed padding to append to the
 * end of a resource of `size` length in order to fit within a pow2 piece while
 * leaving enough room for Fr32 padding (2 bits per 254).
 *
 * @param {number} payloadSize - The size of the payload.
 * @returns {number}
 */
export function toZeroPaddedSize(payloadSize) {
  const size = Math.max(payloadSize, MIN_PAYLOAD_SIZE)
  const highestBit = Math.floor(Math.log2(size))

  const bound = Math.ceil(FR_RATIO * 2 ** (highestBit + 1))
  // the size is either the closest pow2 number, or the next pow2 number if we
  // don't have space for padding
  return size <= bound ? bound : Math.ceil(FR_RATIO * 2 ** (highestBit + 2))
}

/**
 * Derives fr32 padded size from the source content size (that MUST be
 * multiples of {@link IN_BYTES_PER_QUAD}) in bytes.
 *
 * @param {number} size
 */
export const toPieceSize = (size) => toZeroPaddedSize(size) / FR_RATIO

/**
 * Derives fr32 unpadded size from the Fr32 padded size in bytes.
 *
 * @param {number} size
 */
export const fromPieceSize = (size) => size * FR_RATIO

/**
 * Takes source bytes that returns fr32 padded bytes.
 *
 * @param {Uint8Array} source
 * @param {Uint8Array} output
 * @returns {API.Fr23Padded}
 */
export const pad = (
  source,
  output = new Uint8Array(toPieceSize(source.length))
) => {
  const size = toZeroPaddedSize(source.byteLength)
  // Calculate number of quads in the given source
  const quadCount = size / IN_BYTES_PER_QUAD

  // Cycle over four(4) 31-byte groups, leaving 1 byte in between:
  // 31 + 1 + 31 + 1 + 31 + 1 + 31 = 127
  for (let n = 0; n < quadCount; n++) {
    const readOffset = n * IN_BYTES_PER_QUAD
    const writeOffset = n * OUT_BYTES_PER_QUAD

    // First 31 bytes + 6 bits are taken as-is (trimmed later)
    output.set(source.subarray(readOffset, readOffset + 32), writeOffset)

    // first 2-bit "shim" forced into the otherwise identical output
    output[writeOffset + 31] &= 0b00111111

    // copy next Fr32 preceded with the last two bits of the previous Fr32
    for (let i = 32; i < 64; i++) {
      output[writeOffset + i] =
        (source[readOffset + i] << 2) | (source[readOffset + i - 1] >> 6)
    }

    // next 2-bit shim
    output[writeOffset + 63] &= 0b00111111

    for (let i = 64; i < 96; i++) {
      output[writeOffset + i] =
        (source[readOffset + i] << 4) | (source[readOffset + i - 1] >> 4)
    }

    // next 2-bit shim
    output[writeOffset + 95] &= 0b00111111

    for (let i = 96; i < 127; i++) {
      output[writeOffset + i] =
        (source[readOffset + i] << 6) | (source[readOffset + i - 1] >> 2)
    }

    // we shim last 2-bits by shifting the last byte by two bits
    output[writeOffset + 127] = source[readOffset + 126] >> 2
  }

  return output
}

/**
 * @param {API.Fr23Padded} source
 * @param {Uint8Array} [out]
 */
export const unpad = (
  source,
  out = new Uint8Array(fromPieceSize(source.length))
) => {
  const chunks = source.length / 128
  for (let chunk = 0; chunk < chunks; chunk++) {
    const inOffNext = chunk * 128 + 1
    const outOff = chunk * 127

    let at = source[chunk * 128]

    for (let i = 0; i < 32; i++) {
      const next = source[i + inOffNext]

      out[outOff + i] = at

      at = next
    }

    out[outOff + 31] |= at << 6

    for (let i = 32; i < 64; i++) {
      const next = source[i + inOffNext]

      out[outOff + i] = at >> 2
      out[outOff + i] |= next << 6

      at = next
    }

    out[outOff + 63] ^= (at << 6) ^ (at << 4)

    for (let i = 64; i < 96; i++) {
      const next = source[i + inOffNext]

      out[outOff + i] = at >> 4
      out[outOff + i] |= next << 4

      at = next
    }

    out[outOff + 95] ^= (at << 4) ^ (at << 2)

    for (let i = 96; i < 127; i++) {
      const next = source[i + inOffNext]

      out[outOff + i]
      out[outOff + i] = at >> 6
      out[outOff + i] |= next << 2

      at = next
    }
  }

  return out
}
