export { top } from "./top.js";
/**
 * @deprecated - Use `ucan.attest` instead.
 */
export const session: Types.TheCapabilityParser<Types.CapabilityMatch<"ucan/attest", `did:${string}:${string}` & `did:${string}` & Types.Phantom<{
    protocol: "did:";
}>, Schema.InferStruct<{
    proof: Schema.Schema<Types.Link<unknown, number, number, 1>, any>;
}>>>;
/**
 * Account identifier.
 */
export const Account: Schema.Schema<`did:mailto:${string}` & `did:${string}` & Types.Phantom<{
    protocol: "did:";
}>, any>;
/**
 * Describes the capability requested.
 */
export const CapabilityRequest: Schema.StructSchema<{
    can: Schema.StringSchema<string, unknown>;
}, unknown>;
/**
 * Authorization request describing set of desired capabilities.
 */
export const AuthorizationRequest: Schema.StructSchema<{
    iss: Schema.Schema<(`did:mailto:${string}` & `did:${string}` & Types.Phantom<{
        protocol: "did:";
    }>) | undefined, any>;
    att: Schema.Schema<Schema.InferStruct<{
        can: Schema.StringSchema<string, unknown>;
    }>[], unknown>;
}, unknown>;
/**
 * Capability can only be delegated (but not invoked) allowing audience to
 * derived any `access/` prefixed capability for the agent identified
 * by did:key in the `with` field.
 */
export const access: Types.TheCapabilityParser<Types.CapabilityMatch<"access/*", Types.URI<"did:">, {}>>;
/**
 * Capability can be invoked by an agent to request set of capabilities from
 * the account.
 */
export const authorize: Types.TheCapabilityParser<Types.CapabilityMatch<"access/authorize", `did:key:${string}` & `did:${string}` & Types.Phantom<{
    protocol: "did:";
}>, Schema.InferStruct<{
    iss: Schema.Schema<(`did:mailto:${string}` & `did:${string}` & Types.Phantom<{
        protocol: "did:";
    }>) | undefined, any>;
    att: Schema.Schema<Schema.InferStruct<{
        can: Schema.StringSchema<string, unknown>;
    }>[], unknown>;
}>>>;
/**
 * Capability is delegated by us to the user allowing them to complete the
 * authorization flow. It allows us to ensure that user clicks the link and
 * we don't have some rogue agent trying to impersonate user clicking the link
 * in order to get access to their account.
 */
export const confirm: Types.TheCapabilityParser<Types.CapabilityMatch<"access/confirm", `did:${string}:${string}` & `did:${string}` & Types.Phantom<{
    protocol: "did:";
}>, Schema.InferStruct<{
    cause: Schema.Schema<Types.Link<unknown, number, number, 1>, any>;
    iss: Schema.Schema<`did:mailto:${string}` & `did:${string}` & Types.Phantom<{
        protocol: "did:";
    }>, any>;
    aud: Schema.Schema<`did:${string}:${string}` & `did:${string}` & Types.Phantom<{
        protocol: "did:";
    }>, any>;
    att: Schema.Schema<Schema.InferStruct<{
        can: Schema.StringSchema<string, unknown>;
    }>[], unknown>;
}>>>;
export const claim: Types.TheCapabilityParser<Types.CapabilityMatch<"access/claim", (`did:key:${string}` & `did:${string}` & Types.Phantom<{
    protocol: "did:";
}>) | (`did:mailto:${string}` & `did:${string}` & Types.Phantom<{
    protocol: "did:";
}>), {}>>;
export const delegate: Types.TheCapabilityParser<Types.CapabilityMatch<"access/delegate", `did:key:${string}` & `did:${string}` & Types.Phantom<{
    protocol: "did:";
}>, Schema.InferStruct<{
    delegations: Schema.Schema<AccessDelegateDelegations>;
}>>>;
export type AccessDelegateDelegations = Schema.Dictionary<string, Types.Link<unknown, number, number, 0 | 1>>;
/**
 * Parsed Capability for access/delegate
 */
export type ParsedAccessDelegate = {
    can: string;
    nb: {
        delegations?: Schema.Dictionary<string, Types.Link<unknown, number, number, 0 | 1>> | undefined;
    };
};
import * as Types from '@ucanto/interface';
import { Schema } from '@ucanto/validator';
//# sourceMappingURL=access.d.ts.map