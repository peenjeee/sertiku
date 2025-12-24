import * as Lib from "./gen/wasm.js"

export interface Rabin extends Lib.Rabin {
  readonly minSize: number
  readonly maxSize: number
  readonly windowSize: number
}

export function create(
  bits: number,
  minSize: number,
  maxSize: number,
  windowSize: number
): Rabin | Promise<Rabin>

export function createWithPolynom(
  polynom: BigInt,
  bits: number,
  minSize: number,
  maxSize: number,
  windowSize: number
): Rabin | Promise<Rabin>

export function cut(rabin: Rabin, bytes: Uint8Array, end: boolean): Int32Array

export interface Buffer {
  length: number
  copyTo(buffer: Uint8Array, offset: number): any
}

export function cutBuffer(
  rabin: Rabin,
  buffer: Buffer,
  end: boolean
): Int32Array
