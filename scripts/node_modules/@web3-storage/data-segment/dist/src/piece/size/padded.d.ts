export function from(size: number | API.uint64): API.PaddedSize;
export function tryFrom(input: API.uint64 | number): API.Result<API.PaddedSize, Error>;
export function fromExpanded(size: API.PieceSize): API.PaddedSize;
export function toExpanded(size: API.PaddedSize): API.PieceSize;
export function fromHeight(height: number): API.uint64;
export function toHeight(size: API.PaddedSize): number;
export function toWidth(size: API.PaddedSize): bigint;
export function fromWidth(width: API.uint64): API.PaddedSize;
import * as API from '../../api.js';
//# sourceMappingURL=padded.d.ts.map