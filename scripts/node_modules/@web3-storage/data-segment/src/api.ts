import type { Link, ToString } from 'multiformats'
import type {
  MultihashDigest,
  BlockEncoder,
  BlockDecoder,
  BlockCodec,
  SyncMultihashHasher,
} from 'multiformats'
import type * as Multihash from './multihash.js'
import type { Sha256Trunc254Padded, FilCommitmentUnsealed } from './piece.js'
import { SHA256 } from './ipld.js'

export type RAW_CODE = MulticodecCode<0x55, 'raw'>
export type SHA256_CODE = typeof SHA256.code

/**
 * Type describes a byte representation of a `Data` encoded using
 * the multicodec with `Code` code.
 */
export type ByteView<Data, Code extends MulticodecCode> = New<
  { Bytes: Uint8Array },
  { code: Code; model: Data }
>

export type {
  ToString,
  Link,
  BlockEncoder,
  BlockDecoder,
  BlockCodec,
  SyncMultihashHasher,
}
/**
 * Implementers of the `Read` interface are called "readers". Readers
 * allow for reading bytes from an underlying source.
 *
 * Readers are defined by one required method, `read`. Each call to `read`
 * will attempt to pull bytes from this source into a provided buffer. A
 * number of high level functions are implemented in terms of `read`, giving
 * users a number of ways to read bytes while only needing to implement a
 * single method.
 *
 * This is based on [std::io::Read] trait in Rust.
 * [std::io::Read]:https://doc.rust-lang.org/nightly/std/io/trait.Read.html
 */
export interface Read {
  /**
   * Pull some bytes from this source into the specified buffer, returning how
   * many bytes were read.
   *
   * If the return value of this method is `{ok: n}`, then implementations MUST
   * guarantee that `0 <= n <= buffer.length`. A nonzero `n` value indicates
   * that the buffer has been filled in with `n` bytes of data from this source.
   * If `n` is `0`, then it can indicate one of two scenarios:
   *
   * 1. This reader has reached its “end of file” and will likely no longer be
   *    able to produce bytes. Note that this does not mean that the reader
   *    will always no longer be able to produce bytes. For example, underlying
   *    source may be a stream that simply does not currently have more data,
   *    but once more data is added read may succeed.
   *
   * 2. The buffer specified was 0 bytes in length.
   *
   * It is not an error if the returned value `n` is smaller than the `buffer`
   * size, even when the reader is not at the end of the stream yet. This may
   * happen for example because fewer bytes are actually available right now.
   */
  read(buffer: Uint8Array): Poll<number, Error>
}

export interface StreamDigest<
  Code extends MulticodecCode = MulticodecCode,
  Size extends number = number
> extends MultihashDigest<Code> {
  size: Size
}

export interface StreamingHasher<
  Code extends MulticodecCode,
  Size extends number,
  Digest = StreamDigest<Code, Size>
> {
  code: Code
  name: string
  /**
   * Number of bytes currently consumed.
   */
  count(): bigint

  /**
   * Returns multihash digest of the bytes written so far.
   */
  digest(): Digest

  /**
   * Computes the digest of the given input and writes it into the given output
   * at the given offset. Unless `asMultihash` is `false` multihash is
   * written otherwise only the digest (without multihash prefix) is written.
   *
   * Returns the number of bytes that were written.
   */
  digestInto(output: Uint8Array, offset?: number, asMultihash?: boolean): number

  /**
   * Writes bytes to be digested.
   */
  write(bytes: Uint8Array): this

  /**
   * Resets this hasher to its initial state.
   */
  reset(): this

  /**
   * Disposes this hasher and frees up any resources it may be holding on to.
   * After this is called this hasher should not be used.
   */
  dispose(): void
}

export interface Piece {
  /**
   * Height of the tree.
   */
  height: number

  /**
   * Root node of this Merkle tree.
   */
  root: MerkleTreeNode
}

export interface PieceView extends Piece {
  link: PieceLink
  /**
   * Size is the number of padded bytes that is contained in this piece.
   */
  size: PieceSize
  toInfo(): PieceInfoView
  toJSON(): { '/': ToString<PieceLink> }
  toString(): ToString<PieceLink>
}

type Poll<T, X> = Variant<{
  ok: T
  error: X
  wait: Promise<void>
}>

export interface Aggregate extends Piece {}

export interface AggregateView extends Aggregate, PieceView {
  indexSize: number
  limit: number
  tree: AggregateTree

  /**
   * Resolves inclusion proof for the given piece. If aggregate does not include
   * the given piece `{ error: RangeError }` is returned.
   */
  resolveProof(piece: PieceLink): Result<InclusionProof, RangeError>
}

/**
 * Top level proof provided by the aggregator after the deal lands on chain.
 *
 * @see https://github.com/filecoin-project/go-data-segment/blob/master/datasegment/verifier.go#L8-L14
 */
export type DataAggregationProof = [
  inclusion: InclusionProof,
  dataType: DataAggregationProofDataType,
  dataSource: SingletonMarketSource
]

export type IntoDataAggregationProof = [
  inclusion: IntoInclusionProof,
  dataType: DataAggregationProofDataType,
  dataSource: IntoSingletonMarketSource
]

/**
 * @see https://github.com/filecoin-project/go-data-segment/blob/master/datasegment/verifier.go#L16-L18
 */
export type SingletonMarketSource = [dealID: DealID]

export type IntoSingletonMarketSource = [dealID: DealID | number]
/**
 * @see https://github.com/filecoin-project/go-state-types/blob/master/abi/deal.go#L5
 */
export type DealID = uint64

/**
 * @see https://github.com/filecoin-project/go-data-segment/blob/master/datasegment/verifier.go#L12
 */
export type DataAggregationProofDataType = 0

/**
 * Proof that content piece (merkle tree) is a fully contained segment of the
 * aggregate (merke tree).
 *
 * @see https://github.com/filecoin-project/go-data-segment/blob/e3257b64fa2c84e0df95df35de409cfed7a38438/merkletree/proof.go#L9-L14
 * @see https://github.com/filecoin-project/go-data-segment/blob/e3257b64fa2c84e0df95df35de409cfed7a38438/datasegment/inclusion.go#L31-L39
 */
export type InclusionProof = [tree: ProofData, index: ProofData]

export type IntoInclusionProof = [tree: IntoProofData, index: IntoProofData]

export interface Vector<T> extends Iterable<T> {
  append(value: T): Vector<T>
}

export type uint64 = bigint

/**
 * Represents a filecoin piece size which MUST satisfy certain invariants to
 * allow for perfect binary tree construction, specifically size must be
 * `2 ^ N * 128` which implies that minimum piece size is `128` bytes, enough
 * for a (perfect) binary tree with `4` leaves.
 */
export type PieceSize = New<{ PieceSize: uint64 }>

/**
 * Represents size of the 0-padded payload of from which filecoin piece is
 * derived. This is a size pre fr32 expansion that must satisfy certain
 * so that size after FR32 expansion will meet {@link PieceSize} requirements.
 *
 * Specifically size must be `2 ^ N * 127` which implies that minimum piece
 * size is `127` bytes, enough for a (perfect) binary tree with `4` leaves.
 */
export type PaddedSize = New<{ FR32ExpandedSize: uint64 }>

/**
 * `UnpaddedPieceSize` is the size of a piece, in bytes.
 *
 * @see https://github.com/filecoin-project/go-state-types/blob/ff2ed169ff566458f2acd8b135d62e8ca27e7d0c/abi/piece.go#L10C4-L11
 */
export type UnpaddedPieceSize = New<{ UnpaddedPieceSize: uint64 }>

export type Fr23Padded = New<{ Fr23Padded: Uint8Array }>

export interface IndexData extends Array<SegmentInfo> {}

export interface MerkleTree<I extends uint64 | number = uint64 | number> {
  /**
   * Root node of this Merkle tree.
   */
  root: MerkleTreeNode

  /**
   * Height of the tree.
   */
  height: number

  /**
   * Amount of leafs in this Merkle tree.
   */
  leafCount: I

  /**
   * Returns a node at the given level and index.
   *
   * @param level
   * @param index
   */
  node(level: number, index: I): MerkleTreeNode | undefined
}

export interface Piece {
  /**
   * Root node of this Merkle tree.
   */
  root: MerkleTreeNode

  /**
   * Height of the tree.
   */
  height: number

  /**
   * Padding that was applied to the payload to make this piece.
   */
  padding: uint64
}

export interface MerkleTreeBuilder<
  I extends uint64 | number = uint64 | number
> {
  clear(): this
  setNode(level: number, index: I, node: MerkleTreeNode): this
}

export interface PieceTree extends MerkleTree<number> {
  /**
   * All leaf nodes of this Merkle tree.
   */
  leafs: MerkleTreeNode[]
}

export interface AggregateTree<I extends uint64 | number = uint64>
  extends MerkleTree<I>,
    MerkleTreeBuilder<I> {
  collectProof(level: number, index: I): ProofData
}

export interface PieceInfo {
  /**
   * Commitment to the data segment (Merkle node which is the root of the
   * subtree containing all the nodes making up the data segment)
   */
  link: LegacyPieceLink

  /**
   * Size is the number of padded bytes that is contained in this piece.
   */
  size: PieceSize
}

export interface PieceInfoView extends PieceInfo, Piece {}

export interface PieceDigest
  extends StreamDigest<FR32_SHA2_256_TRUNC254_PADDED_BINARY_TREE>,
    Piece {
  name: typeof Multihash.name
}

export interface PieceInfoJSON {
  link: { '/': string }
  height: number
}

export type FR32_SHA2_256_TRUNC254_PADDED_BINARY_TREE = typeof Multihash.code

/**
 * Represents Piece link V2
 * @see https://github.com/filecoin-project/FIPs/pull/758/files
 */
export type PieceLink = Link<
  MerkleTreeNode,
  RAW_CODE,
  FR32_SHA2_256_TRUNC254_PADDED_BINARY_TREE
>

/**
 * Aggregate link is a Piece link V2, but we define it as an alias to
 * convey the semantic difference.
 */
export type AggregateLink = PieceLink

export type SHA2_256_TRUNC254_PADDED = typeof Sha256Trunc254Padded
export type FIL_COMMITMENT_UNSEALED = typeof FilCommitmentUnsealed

/**
 * Represents a Piece link v1
 */
export type LegacyPieceLink = Link<
  MerkleTreeNode,
  FIL_COMMITMENT_UNSEALED,
  SHA2_256_TRUNC254_PADDED
>

/**
 * Contains a data segment description to be contained as two Fr32 elements in
 * 2 leaf nodes of the data segment index.
 *
 * @see https://github.com/filecoin-project/go-data-segment/blob/41a48065383eca6f52efc4ee78a9902a9d25293b/datasegment/index.go#L146C16-L156
 */
export interface Segment {
  /**
   * Commitment to the data segment (Merkle node which is the root of the
   * subtree containing all the nodes making up the data segment)
   */
  root: MerkleTreeNode

  /**
   * Offset is the offset from the start of the deal in padded bytes
   */
  offset: uint64

  /**
   * Number of padded bytes in this segment
   * reflected by this segment.
   */
  size: uint64
}

/**
 * Segment contains a data segment description to be contained as two Fr32
 * elements in 2 leaf nodes of the data segment index.
 */
export interface SegmentInfo extends Segment {
  /**
   * Checksum is a 126 bit checksum (SHA256) computes on `[...root, offset, size]`
   */
  checksum: Checksum<Segment, 16>
}

export type Checksum<Payload = unknown, Size extends number = number> = New<
  { Checksum: SizedUint8Array<number> },
  Payload
>

export type SizedUint8Array<Size extends number> = New<{
  SizedUint8Array: Uint8Array & { length: Size }
}>

/**
 * Represents a location in a Merkle tree.
 */
export interface MerkleTreeLocation {
  /**
   * Level is counted from the leaf layer, with 0 being leaf layer.
   */
  level: number
  index: uint64
}

/**
 * Represents a commitment and its location in a Merkle tree.
 */
export interface MerkleTreeNodeSource {
  node: MerkleTreeNode
  location: MerkleTreeLocation
}

export interface MerkleProof {
  /**
   * ConstructProof constructs a Merkle proof of the subtree (or leaf) at level lvl with index idx.
   * level 0 is the root and index 0 is the left-most node in a level.
   */
  constructProof(level?: number, index?: number): Result<ProofData, Error>
  /**
   * ValidateFromLeafs checks that the Merkle tree is correctly constructed based on all the leafData
   */
  validateFromLeafs(leafData: Uint8Array[]): Result<Unit, Error>
  /**
   * Validate checks that the Merkle tree is correctly constructed, based on the internal nodes
   */
  validate(): Result<Unit, Error>
}

export interface TreeData {
  /**
   * nodes start from root and go down left-to-right
   * thus `nodes[0].length === 1, nodes[1].length === 2len(nodes[1]) = 2`, etc...
   */
  nodes: MerkleTreeNode[][]

  /**
   * Leafs is the amount of raw leafs being used. I.e. without padding to
   * nearest two-power
   */
  height: number
}

export interface AggregateTreeData {
  /**
   * Height of the (perfect binary) tree.
   */
  height: number

  /**
   * Sparse array that contains tree nodes. Levels
   * of the tree are counted from the leaf layer (0).
   */
  data: SparseArray<MerkleTreeNode>
}

export interface SparseArray<T> {
  clear(): this
  at(index: uint64): T | undefined
  set(index: uint64, value: T): this
}

export type ProofData = [
  // indicates the index within the level where the element whose membership to prove is located
  // Leftmost node is at 0
  offset: uint64,
  path: MerkleTreePath
]

export type IntoProofData =
  | [offset: uint64 | number, path: MerkleTreePath]
  | { offset: uint64 | number; path: MerkleTreePath }

export type MerkleTreePath = MerkleTreeNode[]

export type MerkleTreeNode = New<{ Node: Uint8Array }, { size: 32 }>

export type Tagged<T> = {
  [Case in keyof T]: Exclude<keyof T, Case> extends never
    ? T
    : InferenceError<'It may only contain one key'>
}[keyof T]

declare const Marker: unique symbol

/**
 * A utility type to retain an unused type parameter `T`.
 * Similar to [phantom type parameters in Rust](https://doc.rust-lang.org/rust-by-example/generics/phantom.html).
 *
 * Capturing unused type parameters allows us to define "nominal types," which
 * TypeScript does not natively support. Nominal types in turn allow us to capture
 * semantics not represented in the actual type structure, without requiring us to define
 * new classes or pay additional runtime costs.
 *
 * For a concrete example, see {@link ByteView}, which extends the `Uint8Array` type to capture
 * type information about the structure of the data encoded into the array.
 */
export interface Phantom<T> {
  // This field can not be represented because field name is non-existent
  // unique symbol. But given that field is optional any object will valid
  // type constraint.
  [Marker]?: T
}

export type New<T, Type = Tagged<T>> = Tagged<T>[keyof Tagged<T>] &
  Phantom<Type>

/**
 * Utility type for including type errors in the typescript checking. It
 * defines impossible type (object with non-existent unique symbol field).
 * This type can be used in cases where typically `never` is used, but
 * where some error message would be useful.
 */
interface InferenceError<message> {
  [Marker]: never & message
}

/**
 * Defines result type in an idiomatic IPLD representation.
 */

export type Result<T extends {} = {}, X extends {} = {}> = Variant<{
  ok: T
  error: X
}>

/**
 * @see {@link https://en.wikipedia.org/wiki/Unit_type|Unit type - Wikipedia}
 */
export interface Unit {}

/**
 * [Multicodec code] usually used to tag [multiformat].
 *
 * [multiformat]:https://multiformats.io/
 * [multicodec code]:https://github.com/multiformats/multicodec/blob/master/table.csv
 */
export type MulticodecCode<
  Code extends number = number,
  Name extends string = string
> = Code & Phantom<Name>

/**
 * Utility type for defining a [keyed union] type as in IPLD Schema. In practice
 * this just works around typescript limitation that requires discriminant field
 * on all variants.
 *
 * ```ts
 * type Result<T, X> =
 *   | { ok: T }
 *   | { error: X }
 *
 * const demo = (result: Result<string, Error>) => {
 *   if (result.ok) {
 *   //  ^^^^^^^^^ Property 'ok' does not exist on type '{ error: Error; }`
 *   }
 * }
 * ```
 *
 * Using `Variant` type we can define same union type that works as expected:
 *
 * ```ts
 * type Result<T, X> = Variant<{
 *   ok: T
 *   error: X
 * }>
 *
 * const demo = (result: Result<string, Error>) => {
 *   if (result.ok) {
 *     result.ok.toUpperCase()
 *   }
 * }
 * ```
 *
 * [keyed union]:https://ipld.io/docs/schemas/features/representation-strategies/#union-keyed-representation
 */
export type Variant<U extends Record<string, unknown>> = {
  [Key in keyof U]: { [K in Exclude<keyof U, Key>]?: never } & {
    [K in Key]: U[Key]
  }
}[keyof U]
