export const name = 'sha2-256'
export const code = 0x12
export const size = 32

export const prefix = new Uint8Array([18, 32])

export class Digest {
  /**
   * @param {Uint8Array} bytes
   */
  constructor(bytes) {
    /** @type {typeof code} */
    this.code = code
    /** @type {typeof name} */
    this.name = name
    this.bytes = bytes
    /** @type {typeof size} */
    this.size = size
    this.digest = bytes.subarray(2)
  }
}
