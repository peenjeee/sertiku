export const ProviderDID: import("@ucanto/core/schema").Schema<`did:web:${string}` & `did:${string}` & API.Phantom<{
    protocol: "did:";
}>, any>;
export const AccountDID: import("@ucanto/core/schema").Schema<`did:mailto:${string}` & `did:${string}` & API.Phantom<{
    protocol: "did:";
}>, any>;
export function add<S extends Record<string, any> = API.Service>(agent: API.Agent<S>, { account, consumer, provider, proofs, }: {
    account: API.AccountDID;
    consumer: API.SpaceDID;
    provider?: `did:web:${string}` | undefined;
    proofs?: API.Delegation<API.Capabilities>[] | undefined;
}): Promise<API.Result<{}, API.Failure | API.HandlerNotFound | API.HandlerExecutionError | API.InvalidAudience | API.Unauthorized>>;
import * as API from './types.js';
//# sourceMappingURL=provider.d.ts.map