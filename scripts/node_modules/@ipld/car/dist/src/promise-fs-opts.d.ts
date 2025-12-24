/**
 * @description This function is needed not to initialize the `fs.read` on load time. To run in cf workers without polyfill.
 * @param {number} fd
 * @param {Uint8Array} buffer
 * @param {number} offset
 * @param {number} length
 * @param {number} position
 * @returns {Promise<{ bytesRead: number, buffer: Uint8Array }>}
 */
export function fsread(fd: number, buffer: Uint8Array, offset: number, length: number, position: number): Promise<{
    bytesRead: number;
    buffer: Uint8Array;
}>;
/**
 * @description This function is needed not to initialize the `fs.write` on load time. To run in cf workers without polyfill.
 * @param {number} fd
 * @param {Uint8Array} buffer
 * @param {number} offset
 * @param {number} length
 * @param {number} position
 * @returns {Promise<{ bytesRead: number, buffer: Uint8Array }>}
 */
export function fswrite(fd: number, buffer: Uint8Array, offset: number, length: number, position: number): Promise<{
    bytesRead: number;
    buffer: Uint8Array;
}>;
export const hasFS: boolean;
//# sourceMappingURL=promise-fs-opts.d.ts.map