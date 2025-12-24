export function create({ inclusion, dealID }: {
    inclusion: API.InclusionProof;
    dealID: API.DealID;
}): API.DataAggregationProof;
export function inclusion([inclusion]: API.DataAggregationProof): API.InclusionProof;
export function dealID([, , [dealID]]: API.DataAggregationProof): API.DealID;
export function encode(proof: API.DataAggregationProof): API.ByteView<API.DataAggregationProof, typeof CBOR.code>;
export function decode(bytes: API.ByteView<API.DataAggregationProof, typeof CBOR.code>): API.DataAggregationProof;
export function link(proof: API.DataAggregationProof): API.Link<API.DataAggregationProof, API.MulticodecCode<113, "dag-cbor">, 18, 1>;
export function resolveAggregate(proof: API.DataAggregationProof, piece: API.PieceLink): API.Result<API.AggregateLink, Error>;
export function verify(proof: API.DataAggregationProof, claim: {
    dealID: API.DealID;
    piece: API.PieceLink;
    aggregate: API.AggregateLink;
}): API.Result<API.Unit, Error>;
import * as API from '../api.js';
import { CBOR } from '../ipld.js';
//# sourceMappingURL=aggregation.d.ts.map