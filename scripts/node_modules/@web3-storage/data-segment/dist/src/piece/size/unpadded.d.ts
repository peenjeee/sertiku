export function fromPiece({ height, padding }: {
    height: number;
    padding: API.uint64;
}): bigint;
export function toPadding(size: API.uint64): bigint;
export function toPadded(size: API.uint64): API.PaddedSize;
export function toExpanded(size: API.uint64): API.PieceSize;
export function toWidth(size: API.uint64): bigint;
export function toHeight(size: API.uint64): number;
import * as API from '../../api.js';
//# sourceMappingURL=unpadded.d.ts.map