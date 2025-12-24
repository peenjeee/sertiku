export { Proof };
export function indexAreaStart(size: API.PieceSize): API.uint64;
export function encode(proof: API.InclusionProof): API.ByteView<API.InclusionProof, typeof CBOR.code>;
export function link(proof: API.InclusionProof): API.Link<API.InclusionProof, API.MulticodecCode<113, "dag-cbor">, 18, 1>;
export function decode(bytes: API.ByteView<API.InclusionProof, typeof CBOR.code>): API.InclusionProof;
export function from([tree, index]: API.IntoInclusionProof): API.InclusionProof;
export function create({ tree, index }: {
    tree: API.ProofData;
    index: API.ProofData;
}): API.InclusionProof;
export function tree([tree]: API.InclusionProof): API.ProofData;
export function index([_, index]: API.InclusionProof): API.ProofData;
export function resolveAggregate(proof: API.InclusionProof, segmentPiece: API.PieceLink): API.Result<API.AggregateLink, Error>;
export function resolveAggregateFromProofIndex({ index, tree }: {
    tree: API.ProofData;
    index: API.ProofData;
}, piece: API.Piece): API.Result<API.AggregateLink, Error>;
export function resolveAggregateFromProofTree({ tree }: {
    tree: API.ProofData;
}, piece: API.Piece): API.Result<API.AggregateLink, Error>;
import * as Proof from './proof.js';
import * as API from './api.js';
import { CBOR } from './ipld.js';
import * as Piece from './piece.js';
//# sourceMappingURL=inclusion.d.ts.map