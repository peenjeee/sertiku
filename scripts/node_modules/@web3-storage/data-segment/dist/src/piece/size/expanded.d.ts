export { toExpanded as fromUnpadded } from "./unpadded.js";
export function tryFrom(input: number | API.uint64): API.Result<API.PieceSize, RangeError>;
export function from(size: number | API.uint64): API.PieceSize;
export function fromHeight(height: number): API.PieceSize;
export function toHeight(size: API.PieceSize): number;
export function fromWidth(width: API.uint64): API.PieceSize;
export function toWidth(size: API.PieceSize): bigint;
import * as API from '../../api.js';
export { toExpanded as fromPadded, fromExpanded as toPadded } from "./padded.js";
//# sourceMappingURL=expanded.d.ts.map