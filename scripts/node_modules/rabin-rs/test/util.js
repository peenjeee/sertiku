import { sha256 } from "multiformats/hashes/sha2"

const utf8Encoder = new TextEncoder()

/**
 * @param {string} text
 */
export const encodeUTF8 = text => utf8Encoder.encode(text)

export const read = async path => {
  if (typeof fetch === "function") {
    const response = await fetch(`./test/${path}`)
    return new Uint8Array(await response.arrayBuffer())
  } else {
    const fs = "fs/promises"
    const FS = await import(fs)
    return await FS.readFile(`./test/${path}`)
  }
}

export const sharbage = async (
  byteLength,
  seed = encodeUTF8("hello world")
) => {
  const buffer = new Uint8Array(byteLength)
  let byteOffset = 0
  let bytes = seed
  while (byteOffset < byteLength) {
    bytes = await sha256.encode(bytes)
    buffer.set(bytes, byteOffset)
    byteOffset += bytes.byteLength
  }
  return buffer
}
