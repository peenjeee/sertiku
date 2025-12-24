import * as Rabin from "./gen/wasm.js"

let wait = Rabin.activate().then(() => {
  wait = {
    then: fn => fn(),
  }
})

/**
 * @param {number} bits
 * @param {number} minSize
 * @param {number} maxSize
 * @param {number} windowSize
 */
export const create = (bits, minSize, maxSize, windowSize) =>
  wait.then(() => {
    const rabin = Rabin.create(bits, minSize, maxSize, windowSize)
    rabin.maxSize = maxSize
    rabin.minSize = minSize
    rabin.windowSize = windowSize
    return rabin
  })

/**
 * @param {number} bits
 * @param {number} minSize
 * @param {number} maxSize
 * @param {number} windowSize
 */
export const createWithPolynom = (
  polynom,
  bits,
  minSize,
  maxSize,
  windowSize
) =>
  wait.then(() => {
    const rabin = Rabin.createWithPolynomial(
      polynom,
      bits,
      minSize,
      maxSize,
      windowSize
    )
    rabin.maxSize = maxSize
    rabin.minSize = minSize
    rabin.windowSize = windowSize
    return rabin
  })

/**
 * @param {Rabin} rabin
 * @param {Uint8Array} bytes
 * @param {boolean} [end=false]
 */
export const cut = (rabin, bytes, end = false) =>
  // If we have less then `maxSize` of bytes & it's not the end, there is no
  // point to copy bytes into wasm as we'll get no chunks.
  !end & (bytes.byteLength < rabin.maxSize)
    ? none
    : Rabin.cut(rabin, bytes, end)

/**
 * @param {Rabin} rabin
 * @param {{length:number, copyTo:(target:Uint8Array, offset:number) => unknown}} buffer
 * @param {boolean} [end=false]
 */
export const cutBuffer = (rabin, buffer, end = false) =>
  // If we have less then `maxSize` of bytes & it's not the end, there is no
  // point to copy bytes into wasm as we'll get no chunks.
  !end & (buffer.byteLength < rabin.maxSize)
    ? none
    : Rabin.cut_buffer(rabin, buffer, end)

const none = new Uint32Array(0)
