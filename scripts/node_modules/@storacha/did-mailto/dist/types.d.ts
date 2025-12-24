export type LocalPart = string;
export type Domain = string;
export type EmailAddress = `${LocalPart}@${Domain}`;
export type PercentEncoded<T extends string> = string;
export type DidMailto = `did:mailto:${PercentEncoded<Domain>}:${PercentEncoded<LocalPart>}`;
//# sourceMappingURL=types.d.ts.map