export * from "@ucanto/core/result";
export function unwrap<T>({ ok, error }: API.Result<T, {}>): T;
import * as API from '@ucanto/interface';
export { unwrap as try, _try as try };
//# sourceMappingURL=result.d.ts.map