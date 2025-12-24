/**
 * create a did:mailto from an email address
 *
 * @param {import("./types.js").EmailAddress} email
 * @returns {import("./types.js").DidMailto}
 */
export function fromEmail(email: import("./types.js").EmailAddress): import("./types.js").DidMailto;
/**
 * @param {import("./types.js").DidMailto} did
 * @returns {import("./types.js").EmailAddress}
 */
export function toEmail(did: import("./types.js").DidMailto): import("./types.js").EmailAddress;
/**
 * given a string, if it is an EmailAddress, return it, otherwise throw an error.
 * Use this to parse string input to `EmailAddress` type to pass to `fromEmail` (when needed).
 * This is not meant to be a general RFC5322 (et al) email address validator, which would be more expensive.
 *
 * @param {string} input
 * @returns {import("./types.js").EmailAddress}
 */
export function email(input: string): import("./types.js").EmailAddress;
/**
 * parse a did mailto from a string
 *
 * @param {string} input
 * @returns {import("./types.js").DidMailto}
 */
export function fromString(input: string): import("./types.js").DidMailto;
export * from "./types.js";
//# sourceMappingURL=index.d.ts.map