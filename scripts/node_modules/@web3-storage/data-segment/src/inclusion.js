import * as API from './api.js'
import { maxIndexEntriesInDeal, EntrySize } from './index.js'
import * as Proof from './proof.js'
import * as Piece from './piece.js'
import * as Inclusion from './inclusion.js'
import * as Segment from './segment.js'
import * as Aggregate from './aggregate.js'
import { SHA256, CBOR } from './ipld.js'
import * as IPLD from './ipld.js'
import { Expanded } from './piece/size.js'

export { Proof }

/**
 * @param {API.PieceSize} size
 * @returns {API.uint64}
 */
export const indexAreaStart = (size) =>
  size - BigInt(maxIndexEntriesInDeal(size)) * EntrySize

/**
 * Encodes data layout into a CBOR block.
 *
 * @param {API.InclusionProof} proof
 * @returns {API.ByteView<API.InclusionProof, typeof CBOR.code>}
 */
export const encode = (proof) => CBOR.encode(proof)

/**
 * @param {API.InclusionProof} proof
 */
export const link = (proof) =>
  IPLD.createLink(encode(proof), { codec: CBOR, hasher: SHA256 })

/**
 * Decodes CBOR encoded data layout. It is reverse of {@link encode}.
 *
 * @param {API.ByteView<API.InclusionProof, typeof CBOR.code>} bytes
 */
export const decode = (bytes) => from(CBOR.decode(bytes))

/**
 * Takes proof in either object or array form and returns a proof data.
 *
 * @param {API.IntoInclusionProof} proof
 * @returns {API.InclusionProof}
 */
export const from = ([tree, index]) => [Proof.from(tree), Proof.from(index)]

/**
 * Takes data model and returns an IPLD View of it.
 *
 * @param {object} source
 * @param {API.ProofData} source.tree
 * @param {API.ProofData} source.index
 * @returns {API.InclusionProof}
 */
export const create = ({ tree, index }) => [tree, index]

/**
 * Accessor for the segment (sub) tree.
 *
 * @param {API.InclusionProof} proof
 * @returns {API.ProofData}
 */
export const tree = ([tree]) => tree

/**
 * Accessor for the segment index.
 *
 * @param {API.InclusionProof} proof
 * @returns {API.ProofData}
 */
export const index = ([_, index]) => index

/**
 * Resolves an (aggregate) piece (link) from the inclusion proof and a
 * (segment) piece (link). It will resolve (aggregate) piece root and size
 * from both tree and index proofs and unless they match it will return an
 * error. Function may also return an error if indices fall out of bound.
 *
 * @param {API.InclusionProof} proof
 * @param {API.PieceLink} segmentPiece
 * @returns {API.Result<API.AggregateLink, Error>}
 */
export const resolveAggregate = (proof, segmentPiece) => {
  // Read out piece info from the given segment link
  const piece = Piece.fromLink(segmentPiece)
  const tree = Inclusion.tree(proof)
  const index = Inclusion.index(proof)

  const { ok: aggregate, error } = resolveAggregateFromProofTree(
    { tree },
    piece
  )
  if (error) {
    return { error }
  }

  const result = resolveAggregateFromProofIndex({ index, tree }, piece)
  if (result.error) {
    return result
  }

  if (aggregate.toString() !== result.ok.toString()) {
    return { error: new Error('Inclusion proof is invalid') }
  }

  return { ok: aggregate }
}

/**
 * Resolves aggregate from the (sub)tree and index proofs. It will use provided
 * piece information to derive corresponding index nodes and then resolve the
 * root of the provided proofs index path. Aggregate size is also derived from
 * the proofs index path. Function also verifies that proof index offset falls
 * within the bounds of the (aggregate) tree index range. Returns aggregate
 * (link) or an error if indices fall out of bound.
 *
 * @param {object} proof
 * @param {API.ProofData} proof.tree
 * @param {API.ProofData} proof.index
 * @param {API.Piece} piece
 * @returns {API.Result<API.AggregateLink, Error>}
 */
export const resolveAggregateFromProofIndex = ({ index, tree }, piece) => {
  // Derive piece size from it's tree height.
  const size = Expanded.fromHeight(piece.height)
  // Derive piece root offset within the (aggregate) tree.
  const offset = Proof.offset(tree) * size
  // Encode segment which produces piece root bytes followed by it's index
  // node. Which are two leaves of the (aggregate) tree.
  const segment = Segment.toBytes({ root: piece.root, offset, size })
  // We compute parent node from the (pieceRoot, pieceIndex) which is the node
  // our index proof leads to.
  const node = Proof.truncatedHash(segment)
  // We increment the height by one to account for the parent node we derived
  // above.
  const height = Proof.depth(index) + 1
  const { ok: root, error } = Proof.resolveRoot(index, node)

  if (error) {
    return { error }
  }

  // Compute index offset for this aggregate
  const indexOffset = indexAreaStart(Expanded.fromHeight(height))

  // Error if index offset is out of bounds for this aggregate
  const nodeOffset = Proof.offset(index) * EntrySize
  if (nodeOffset < indexOffset) {
    return {
      error: new RangeError(
        `Index entry at a wrong offset: ${nodeOffset} < ${indexOffset}`
      ),
    }
  }

  return { ok: Aggregate.toLink({ root, height }) }
}

/**
 * Resolves an (aggregate) piece from the (sub)tree of the given inclusion
 * `proof`. It will use provided piece information to resolve the
 * root of the aggregate from the proof (sub) tree path. Aggregate size is
 * also derived from the proof (sub) tree path and piece size.
 *
 * @param {object} proof
 * @param {API.ProofData} proof.tree
 * @param {API.Piece} piece
 * @returns {API.Result<API.AggregateLink, Error>}
 */
export const resolveAggregateFromProofTree = ({ tree }, piece) => {
  // Resolve the the aggregate piece root from the given proof and piece.
  const { ok: root, error } = Proof.resolveRoot(tree, piece.root)
  if (error) {
    return { error }
  }
  // Derive the aggregate tree height by adding depth of the proof tree to the
  // height of the piece (sub)tree.
  const height = piece.height + Proof.depth(tree)

  return { ok: Aggregate.toLink({ root, height }) }
}
