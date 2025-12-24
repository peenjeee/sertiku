import * as API from './api.js'
import { create as createDigest } from 'multiformats/hashes/digest'
import * as Digest from './digest.js'
import * as Link from 'multiformats/link'

import * as Raw from 'multiformats/codecs/raw'
import { digest, MAX_PAYLOAD_SIZE, code, name } from './multihash.js'

import * as Size from './piece/size.js'
export { MAX_PAYLOAD_SIZE, Size }

/**
 * @see https://github.com/multiformats/go-multihash/blob/dc3bd6897fcd17f6acd8d4d6ffd2cea3d4d3ebeb/multihash.go#L73
 * @type {API.MulticodecCode<0x1012, 'sha2-256-trunc254-padded'>}
 */
export const Sha256Trunc254Padded = 0x1012

/**
 * @see https://github.com/ipfs/go-cid/blob/829c826f6be23320846f4b7318aee4d17bf8e094/cid.go#L104
 * @type {API.MulticodecCode<0xf101, 'fil-commitment-unsealed'>}
 */
export const FilCommitmentUnsealed = 0xf101

/**
 * @param {API.PieceDigest} digest
 * @returns {API.PieceView}
 */
export const fromDigest = (digest) => fromLink(Link.create(Raw.code, digest))

/**
 *
 * @param {API.PieceLink} link
 * @returns {API.PieceView}
 */
export const fromLink = (link) => {
  if (link.code !== Raw.code) {
    throw new TypeError(`Piece link must have raw encoding`)
  }

  if (link.multihash.code !== code) {
    throw new Error(`Piece link must have ${name} multihash`)
  }

  return new Piece(link)
}

/**
 * @param {string} source
 */
export const fromString = (source) => fromLink(Link.parse(source))

/**
 *
 * @param {API.Piece} piece
 * @returns {API.ToString<API.PieceLink>}
 */
export const toString = (piece) => `${toLink(piece)}`

/**
 * @param {unknown} json
 */
export const fromJSON = (json) =>
  fromString(/** @type {{'/': string}} */ (json)['/'])

/**
 *
 * @param {API.Piece} piece
 * @returns {{'/': API.ToString<API.PieceLink>}}}
 */
export const toJSON = (piece) => ({ '/': toString(piece) })

/**
 * @param {Uint8Array} payload
 */
export const fromPayload = (payload) => fromDigest(digest(payload))

/**
 * @param {API.Piece} piece
 * @returns {API.PieceView}
 */
export const toView = (piece) => fromDigest(Digest.fromPiece(piece))

/**
 *
 * @param {API.Piece} piece
 * @returns {API.PieceLink}
 */
export const toLink = (piece) => Link.create(Raw.code, Digest.fromPiece(piece))

/**
 *
 * @param {API.Piece} piece
 * @returns {API.PieceInfoView}
 */
export const toInfo = (piece) => new Info(Digest.fromPiece(piece))

/**
 *
 * @param {API.PieceInfo} info
 * @returns
 */
export const fromInfo = (info) =>
  toView({
    height: Size.toHeight(info.size),
    root: info.link.multihash.digest,
    padding: 0n,
  })

class Piece {
  /**
   * @param {API.PieceLink} link
   */
  constructor(link) {
    this.link = link
  }
  get padding() {
    return Digest.padding(this.link.multihash)
  }
  get height() {
    return Digest.height(this.link.multihash)
  }
  get size() {
    return Size.fromHeight(this.height)
  }
  get root() {
    return Digest.root(this.link.multihash)
  }

  toJSON() {
    return {
      '/': this.toString(),
    }
  }
  toString() {
    return /** @type {API.ToString<API.PieceLink>} */ (this.link.toString())
  }

  toInfo() {
    return new Info(this)
  }
}

/**
 * @implements {API.PieceInfo}
 */
class Info {
  /**
   * @param {API.Piece} piece
   */
  constructor(piece) {
    this.piece = piece
    /** @type {API.LegacyPieceLink|undefined} */
    this._link
  }
  get height() {
    return this.piece.height
  }
  get root() {
    return this.piece.root
  }
  get size() {
    return Size.fromHeight(this.height)
  }
  get padding() {
    return this.piece.padding
  }

  get link() {
    if (this._link == null) {
      this._link = Link.create(
        FilCommitmentUnsealed,
        createDigest(Sha256Trunc254Padded, this.root)
      )
    }

    return this._link
  }
  toJSON() {
    return {
      link: { '/': this.link.toString() },
      height: this.height,
    }
  }
  toString() {
    return JSON.stringify(this.toJSON(), null, 2)
  }
}
