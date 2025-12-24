/**
 * @template {any} T
 * @returns {IteratorChannel<T>}
 */
export function create<T extends unknown>(): IteratorChannel<T>;
export type IteratorChannel<T extends unknown> = import("./coding.js").IteratorChannel<T>;
//# sourceMappingURL=iterator-channel.d.ts.map