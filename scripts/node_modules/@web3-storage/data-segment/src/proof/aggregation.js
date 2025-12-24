import * as API from '../api.js'
import { CBOR, SHA256 } from '../ipld.js'
import { Inclusion } from '../lib.js'
import * as IPLD from '../ipld.js'

/**
 * @param {object} source
 * @param {API.InclusionProof} source.inclusion
 * @param {API.DealID} source.dealID
 * @returns {API.DataAggregationProof}
 */
export const create = ({ inclusion, dealID }) => [inclusion, 0, [dealID]]

/**
 * Returns piece inclusion proof.
 *
 * @param {API.DataAggregationProof} proof
 * @returns {API.InclusionProof}
 */
export const inclusion = ([inclusion]) => inclusion

/**
 * @param {API.DataAggregationProof} proof
 * @returns {API.DealID}
 */
export const dealID = ([, , [dealID]]) => dealID

/**
 * Encodes data layout into a CBOR block.
 *
 * @param {API.DataAggregationProof} proof
 * @returns {API.ByteView<API.DataAggregationProof, typeof CBOR.code>}
 */
export const encode = (proof) => CBOR.encode(proof)

/**
 * Decodes CBOR encoded data layout. It is reverse of {@link encode}.
 *
 * @param {API.ByteView<API.DataAggregationProof, typeof CBOR.code>} bytes
 * @returns {API.DataAggregationProof}
 */
export const decode = (bytes) => {
  const [inclusion, dataType, [dealID]] = CBOR.decode(bytes)
  return [Inclusion.from(inclusion), dataType, [BigInt(dealID)]]
}

/**
 * @param {API.DataAggregationProof} proof
 */
export const link = (proof) =>
  IPLD.createLink(encode(proof), { codec: CBOR, hasher: SHA256 })

/**
 *
 * @param {API.DataAggregationProof} proof
 * @param {API.PieceLink} piece
 * @returns {API.Result<API.AggregateLink, Error>}
 */
export const resolveAggregate = (proof, piece) =>
  Inclusion.resolveAggregate(inclusion(proof), piece)

/**
 * Verifies that `proof` is valid evidence that `claim.piece` is a segment of the `claim.aggregate` 
 * and that `proof` is for the `claim.dealID`.
 *
 * @param {API.DataAggregationProof} proof
 * @param {object} claim
 * @param {API.DealID} claim.dealID
 * @param {API.PieceLink} claim.piece
 * @param {API.AggregateLink} claim.aggregate
 * @returns {API.Result<API.Unit, Error>}
 */
export const verify = (proof, claim) => {
  if (dealID(proof) !== claim.dealID) {
    return {
      error: new Error(
        `Proof is for deal ${dealID(proof)} not ${claim.dealID}`
      ),
    }
  }

  const aggregate = resolveAggregate(proof, claim.piece)
  if (aggregate.error) {
    return aggregate
  }

  if (claim.aggregate.toString() !== aggregate.ok.toString()) {
    return {
      error: new Error(
        `Computed aggregate ${aggregate.ok} does not match claimed ${claim.aggregate}`
      ),
    }
  }

  return { ok: {} }
}
