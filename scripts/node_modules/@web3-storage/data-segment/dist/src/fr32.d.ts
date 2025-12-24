/**
 * Determine the additional bytes of zeroed padding to append to the
 * end of a resource of `size` length in order to fit within a pow2 piece while
 * leaving enough room for Fr32 padding (2 bits per 254).
 *
 * @param {number} payloadSize - The size of the payload.
 * @returns {number}
 */
export function toZeroPaddedSize(payloadSize: number): number;
export function toPieceSize(size: number): number;
export function fromPieceSize(size: number): number;
export function pad(source: Uint8Array, output?: Uint8Array): API.Fr23Padded;
export function unpad(source: API.Fr23Padded, out?: Uint8Array | undefined): Uint8Array;
import * as API from './api.js';
//# sourceMappingURL=fr32.d.ts.map