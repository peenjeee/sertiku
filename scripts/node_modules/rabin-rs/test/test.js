// @ts-check

import { encodeUTF8, read, sharbage } from "./util.js"
import { create, cut, cutBuffer, createWithPolynom } from "../lib.js"
import { assert } from "chai"
import * as FZSTD from "fzstd"

describe("rabin", () => {
  it("chunks for 1MiB.txt", async () => {
    const prefix = await read("./1MiB.txt")
    const suffix = encodeUTF8("hello")
    const buffer = new Uint8Array(prefix.byteLength + suffix.byteLength)
    buffer.set(prefix, 0)
    buffer.set(suffix, prefix.byteLength)

    const rabin = await create(18, 87381.33333333333, 393216, 64)

    assert.deepEqual(
      [...cut(rabin, buffer, false)],
      [353816, 112050, 147806, 393216]
    )
    assert.deepEqual(
      [...cut(rabin, buffer, true)],
      [353816, 112050, 147806, 393216, 41693]
    )
  })

  // @see https://github.com/ribasushi/DAGger/issues/1
  it("shoud be empty", async () => {
    const buffer = new Uint8Array(10 * 256)
    buffer.fill("a".charCodeAt(0))

    const rabin = await create(8, 18, 262144, 64)
    assert.deepEqual([...cut(rabin, buffer, false)], [])
    assert.deepEqual([...cut(rabin, buffer, true)], [buffer.byteLength])
  })

  it("shoud respect window size", async () => {
    const b1 = new Uint8Array(2 * 256)
    const b2 = new Uint8Array(1 * 119)
    const b3 = new Uint8Array(5 * 256)

    b1.fill("a".charCodeAt(0))
    b2.fill("b".charCodeAt(0))
    b3.fill("c".charCodeAt(0))
    const buffer = new Uint8Array(b1.byteLength + b2.byteLength + b3.byteLength)
    buffer.set(b1, 0)
    buffer.set(b2, b1.byteLength)
    buffer.set(b3, b1.byteLength + b2.byteLength)

    const rabin = await create(6, 48, 192, 64)
    assert.deepEqual(
      [...cut(rabin, buffer, true)],
      [192, 192, 157, 64, 78, 192, 192, 192, 192, 192, 192, 76]
    )
  })

  it("shoud accept custom buffers", async () => {
    const b1 = new Uint8Array(2 * 256)
    const b2 = new Uint8Array(1 * 119)
    const b3 = new Uint8Array(5 * 256)

    b1.fill("a".charCodeAt(0))
    b2.fill("b".charCodeAt(0))
    b3.fill("c".charCodeAt(0))

    const custom = {
      length: b1.byteLength + b2.byteLength + b3.byteLength,
      copyTo(buffer, offset) {
        buffer.set(b1, offset)
        buffer.set(b2, offset + b1.byteLength)
        buffer.set(b3, offset + b1.byteLength + b2.byteLength)
      },
    }

    const rabin = await create(6, 48, 192, 64)
    assert.deepEqual(
      [...cutBuffer(rabin, custom, true)],
      [192, 192, 157, 64, 78, 192, 192, 192, 192, 192, 192, 76]
    )
  })

  it("chunks for rand_5MiB.zst", async () => {
    const buffer = FZSTD.decompress(await read("./rand_5MiB.zst"))

    const rabin = await createWithPolynom(
      17437180132763653n,
      Math.log2(524288),
      262144,
      1048576,
      16
    )

    assert.deepEqual(
      [...cut(rabin, buffer, false)],
      [895059, 686255, 467859, 626819, 280748, 310603, 734239, 499556]
    )

    assert.deepEqual(
      [...cut(rabin, buffer, true)],
      [895059, 686255, 467859, 626819, 280748, 310603, 734239, 499556, 741742]
    )
  })

  it("is stateless", async () => {
    const prefix = await read("./1MiB.txt")
    const suffix = encodeUTF8("hello")
    const buffer = new Uint8Array(prefix.byteLength + suffix.byteLength)
    buffer.set(prefix, 0)
    buffer.set(suffix, prefix.byteLength)

    const rabin = await create(18, 87381.33333333333, 393216, 64)

    assert.deepEqual([...cut(rabin, buffer.slice(0, 736976), false)], [353816])

    assert.deepEqual(
      [...cut(rabin, buffer.slice(0, 736976), true)],
      [353816, 112050, 147806, /* remainder*/ 123304]
    )

    assert.deepEqual(
      [...cut(rabin, buffer, false)],
      [353816, 112050, 147806, 393216]
    )
    assert.deepEqual(
      [...cut(rabin, buffer, true)],
      [353816, 112050, 147806, 393216, 41693]
    )
  })

  it("compat test", async () => {
    const buffer = await sharbage(524288)

    const rabin = await create(18, 87381, 393216, 16)

    assert.deepEqual([...cut(rabin, buffer, false)], [189236])
    assert.deepEqual([...cut(rabin, buffer, true)], [189236, 177457, 157595])
  })
})
