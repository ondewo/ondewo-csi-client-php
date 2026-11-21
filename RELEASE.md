# Release History

*****************

## Release ONDEWO CSI PHP Client 5.9.2

### New Features

* [[OND236-120]](https://ondewo.atlassian.net/browse/OND236-120) Keycloak authentication with offline-token start-up, the same behaviour as ondewo-csi-client-python 5.9.2: `refreshToken` in `ClientConfig` (`keycloakUrl`, `realm`, `clientId`, `username`, `password`, `tokenExpirationInS`, `verifySsl` and `refreshToken`, appended after the existing parameters, `refreshToken` last) hands the client a Keycloak *offline* refresh token instead of a password. The client then starts with one `grant_type=refresh_token` exchange and never sends a password grant, not even as a fallback; a refused token is never answered with a password login. Keycloak's brute-force detection counts failed LOGINS, so N processes booting at once with one technical user are a burst the realm penalises, while N refresh grants of one offline token are not.
* [[OND236-120]](https://ondewo.atlassian.net/browse/OND236-120) A Keycloak configuration needs a password OR a refresh token, and the Keycloak URL, realm and client id are all-or-nothing; when both credentials are set the token wins. The password and the token are redacted from every rendering of the configuration and take part (hashed) in the shared-provider registry key, so two token-only configurations of different tenants never share a provider. The `token_expiration_in_s` window is armed on both start-up paths.
* [[OND236-120]](https://ondewo.atlassian.net/browse/OND236-120) `channelOptions()` attaches a shared `KeycloakTokenProvider` as `update_metadata`, so every call carries `authorization: Bearer <token>`; providers are shared per credential set through a SHA-256-keyed registry that never hands out a closed one. PHP has no threads, so the refresh is lazy (inside the 30 s leeway).

### Bug Fixes

* [[OND236-120]](https://ondewo.atlassian.net/browse/OND236-120) A failed token refresh never ends the background refresh: any failure of the token endpoint (a 502 from a proxy, a DNS failure, a restarting Keycloak, a dead offline session) is retried with one full-jitter backoff (5 s doubling up to 300 s, measured from the moment the failure is recorded) that the lazy per-call path shares, and a success resets it. Concurrent callers during an outage share one in-flight refresh, so an outage costs one token request, not one per call.
* [[OND236-120]](https://ondewo.atlassian.net/browse/OND236-120) A token that is still valid keeps being served when its refresh fails inside the 30 s expiry leeway; only an EXPIRED token raises `KeycloakAuthenticationException` (the cause in `getPrevious()`), whatever failed underneath: a transport error, a non-2xx status, a body that is not JSON or not a JSON object, a missing `access_token` or a non-numeric `expires_in`, each with the original error as its cause. A malformed response changes no provider state, and a password login whose response carries no `refresh_token` keeps the previous one, as the python SDK does.

### Improvements

* [[OND236-120]](https://ondewo.atlassian.net/browse/OND236-120) One Keycloak contract across every ONDEWO client SDK, with the python SDK as the reference: the same constants, log lines and error texts. The token request is bounded by 10 s, quoted Keycloak error bodies are cut at 500 characters, a token response is reported by its field names only (never its values), and each failure logs one WARNING with the attempt number and the retry delay, the first success afterwards one INFO; no credential appears in either line.
* [[OND236-120]](https://ondewo.atlassian.net/browse/OND236-120) Tests implement the cross-SDK test catalogue T01-T62 plus edge cases, on a fake token endpoint and clock, with 100 % coverage of the hand-written authentication code.
* Tracking API Version [5.9.0](https://github.com/ondewo/ondewo-csi-api/releases/tag/5.9.0) ( [Documentation](https://ondewo.github.io/ondewo-csi-api/) ); the generated code is unchanged against 5.9.0.

*****************

## Release ONDEWO CSI PHP Client 5.9.0

### Improvements

* Tracking API Version [5.9.0](https://github.com/ondewo/ondewo-csi-api/releases/tag/5.9.0) ( [Documentation](https://ondewo.github.io/ondewo-csi-api/) )
* Generated with ondewo-proto-compiler 5.17.0 (protoc 35.1); new runtime floors: `google/protobuf` 5.35.1 (a major step from 4.x), `grpc/grpc` 1.85.0, php >= 8.2.0 (protoc 35.1).

*****************

## Release ONDEWO CSI PHP Client 5.7.0

### Improvements

* Tracking API Version [5.7.0](https://github.com/ondewo/ondewo-csi-api/releases/tag/5.7.0) ( [Documentation](https://ondewo.github.io/ondewo-csi-api/) )

*****************

## Release ONDEWO CSI PHP Client 5.6.2

### Improvements

* Tracking API Version [5.6.2](https://github.com/ondewo/ondewo-csi-api/releases/tag/5.6.2) ( [Documentation](https://ondewo.github.io/ondewo-csi-api/) )

*****************

## Release ONDEWO CSI PHP Client 5.6.0

### New Features

* Tracking API Version [5.6.0](https://github.com/ondewo/ondewo-csi-api/releases/tag/5.6.0) ( [Documentation](https://ondewo.github.io/ondewo-csi-api/) ).
  The regenerated `Ondewo\Csi\ConversationsClient` and messages carry the API's new surface:
  * The new unary RPC `SetCallMediaControl` (`CallMediaControlLevel` -> `SetCallMediaControlResponse`): per-call
    operator media control pushed by ondewo-sip, carrying the full effective level (`bot_muted`,
    `listening_paused`), a monotonic `generation` and a `reason`; the response reports the `applied` level,
    `changed`, `stale`, `bot_playback_in_flight` and a `refusal_reason`.
  * `ControlStreamResponse.media_control`: set only on media-control messages of `GetControlStream`. Handle such a
    message as media control and do not apply its echoed `control_status`.
  * `SipTrigger.INVITE` is documented as not implemented (use the ondewo-vtsi `Calls.InviteToCall` RPC).
* Purely additive: no field, enum value or RPC was renumbered or removed, so code written against 5.5.x keeps
  working.

### Improvements

* Proto compiler pinned to [5.15.5](https://github.com/ondewo/ondewo-proto-compiler/releases/tag/5.15.5).

### Tests

* `tests/Generated/ServiceClientTest.php` covers `SetCallMediaControl`.
* `tests/Generated/MessageSerializationTest.php` round-trips `ControlStreamResponse.media_control` and checks it
  stays unset on other messages.

*****************

## Release ONDEWO CSI PHP Client 5.5.1

### Improvements

* Tracking API Version [5.5.0](https://github.com/ondewo/ondewo-csi-api/releases/tag/5.5.0) ( [Documentation](https://ondewo.github.io/ondewo-csi-api/) )
* New `Ondewo\Csi\Auth\ClientConfig` (hand-written, in `auth/`) builds the target and the `$opts` of every
  generated `<Service>Client` for plaintext, TLS and mutual TLS, with the same rules as the other ONDEWO SDKs:
  * `grpcCert`, `grpcClientCert` and `grpcClientKey` take PEM **content**, never a file path; CRLF PEMs work. An
    empty `grpcCert` means the platform's default trust store.
  * `grpcClientCert` / `grpcClientKey` are both-or-neither: half a pair throws `InvalidArgumentException` when the
    config is built and again in `channelCredentials()`, before anything reaches ext-grpc (grpc-core aborts the
    process on a key without its certificate).
  * `useSecureChannel: false` with a client identity throws instead of silently dropping it; a plaintext channel
    logs a warning naming `host:port` on a PSR-3 logger if given, otherwise through `error_log()`.
  * `channelOptions()` refuses a `credentials` option of its own; every other option is passed through.
  * Exception messages name the field and `host:port`, never a PEM or the config. `(string)`, `var_dump()` /
    `print_r()` and `json_encode()` show a non-empty `grpcClientKey` as `***REDACTED***`; `serialize()`,
    `var_export()` and `(array)` are not redacted (documented). The key parameter is `#[\SensitiveParameter]`.
  * `target()` brackets a bare IPv6 literal (`[::1]:50051`) and leaves `[...]` / `scheme:` hosts alone.
  * `ClientConfig::DEFAULT_CHANNEL_OPTIONS` matches ondewo-client-utils-python: message size 2³¹-1 both ways,
    keepalive 30 s with `max_pings_without_data` 2 and no pings without calls, keepalive and HTTP/2 ping timeout
    20 s, reconnect backoff capped at 5 s. Documented gap: no per-method retry policy, only gRPC's transparent
    retries.
* `composer.json` suggests `psr/log` for the insecure-channel warning.
* README section "TLS, mutual TLS and certificates": modes, an openssl test PKI, security notes and troubleshooting.
* `make publish` / the release workflow: Packagist's `202 Accepted` counts as success, and the HTTP-code check no
  longer runs in a separate shell with an empty code (a comment inside the continued shell block split it), which
  failed every publish.
* Proto compiler pinned to [5.15.2](https://github.com/ondewo/ondewo-proto-compiler/releases/tag/5.15.2).
* `make check_build` (run by `make release` before its first push) camel-cases hyphenated proto names
  (`speech-to-text.proto` -> `SpeechToText.php`) instead of failing on them.

### Tests

* `tests/Auth/ClientConfigTest.php` covers `ClientConfig` (100% coverage of `auth/`).
* `tests/Tls/MutualTlsHandshakeTest.php`: real handshakes with a PKI generated at test time (ext-openssl) against a
  python grpcio server: TLS, mutual TLS, CRLF PEMs and `[::1]` connect; a missing or foreign client certificate, a
  wrong CA and the system roots against the test CA fail as `UNAVAILABLE`. CI installs grpcio 1.82.1.
* `tests/ReleaseNotesTest.php` pins the `RELEASE.md` heading and `*****` separator the GitHub release-notes slice
  relies on.

*****************

## Release ONDEWO CSI PHP Client 5.5.0

### New Features

* Initial release of the ONDEWO CSI (Conversational Speech Interface) gRPC client for PHP. The whole client
  surface is generated from the [ondewo-csi-api](https://github.com/ondewo/ondewo-csi-api) protocol buffer
  definitions by the `ondewo-php-proto-compiler` image of
  [ondewo-proto-compiler 5.15.1](https://github.com/ondewo/ondewo-proto-compiler/releases/tag/5.15.1),
  which is vendored as a git submodule and pinned to that tag: protoc's built-in `--php_out` for the messages
  and enums, `grpc_php_plugin` for the `<Service>Client` stubs, and a composer package whose optimized
  classmap autoloader is built and verified inside the image.
* The generated stubs are **committed** under `src/` — 988 files. CSI is an orchestration layer, so its api
  vendors the NLU, S2T and T2S protos and this package therefore ships 19 service clients:
  `Ondewo\Csi\ConversationsClient`, the 16 `Ondewo\Nlu\*Client` stubs, `Ondewo\S2t\Speech2TextClient` and
  `Ondewo\T2s\Text2SpeechClient`, together with their messages, enums and the non-well-known `google/*` protos
  they import. Packagist serves the tree of a git tag verbatim and composer has no build step, so stubs that
  are not committed do not exist for anybody who installs the package.
* Ships as the composer package `ondewo/csi-client-php`, installable with
  `composer require ondewo/csi-client-php`. Requires PHP >= 8.1 and the `grpc` PHP extension, which every
  generated `<Service>Client` needs because it extends `\Grpc\BaseStub`, and — for the JSON wire format only —
  `ext-bcmath`.
* Hand-written sources live in `auth/` at the repository root, never in the compiler-owned `src/`.
  `Ondewo\Csi\Auth\BearerTokenAuthenticator` turns a token into the `$opts` array a generated stub is
  constructed with and stamps `authorization: Bearer <token>` onto the metadata of every call. The namespace
  is CSI's own rather than a shared `Ondewo\Auth`, so installing this client next to another ONDEWO PHP
  client cannot collide.
* `make build` reproduces the stubs end to end — pinned submodules, compiler image, generation, ownership
  hand-back and version propagation into `composer.json`.

### Testing

* A real PHPUnit suite under `tests/` exercises the generated code rather than asserting around it: every
  committed class is loaded through the autoloader, `initOnce()` is called on every one of the 25
  `GPBMetadata` descriptors (so a missing transitive import fails CI rather than a consumer's first RPC),
  messages are round-tripped through the binary and JSON wire formats, `proto3 optional` fields are asserted
  to keep their zero values on the wire, enum zero constants are pinned, and the service stubs are constructed
  against a dummy channel and checked for the RPC methods and arities the api declares — including the bidi
  `S2sStream` and the server-streaming `GetControlStream`.
* `make coverage` measures the hand-written sources (`phpunit.xml.dist`'s `<source>` is `auth/`) and fails the
  build below 100% line coverage. Generated code is excluded from that metric and covered by the tests above.
* GitHub Actions runs `composer validate`, `php -l`, the suite and the coverage gate on PHP 8.1 and 8.4
  against the committed stubs — no docker image is built and no submodule is checked out there. No step is
  guarded by a directory check, so a tree without code goes red instead of reporting success.
* The job installs `ext-bcmath` alongside `ext-grpc`: `google/protobuf` only *suggests* bcmath, but its
  pure-PHP JSON parser range-checks every integer with `bccomp()`, so `mergeFromJsonString()` on a message
  with an int field dies without it. The suite covers that path explicitly.
* The dev tool chain (PHPUnit, the coverage gate) lives in its own composer project under `tools/`. It is
  deliberately **not** `require-dev` in the root manifest: `composer update --no-dev` still resolves dev
  requirements, and the compiler image resolves the merged manifest with the network disabled, so one
  `require-dev` entry would break `make generate_ondewo_protos`.

### Publishing

* Published to [Packagist](https://packagist.org/packages/ondewo/csi-client-php) as `ondewo/csi-client-php`.
  Packagist accepts no upload — it serves the tree of a git tag — so `make publish` validates the package and
  then pings `https://packagist.org/api/update-package` with `PACKAGIST_USERNAME` + `PACKAGIST_API_TOKEN` to
  have the new tag crawled. It is wired into `make release` after `push_to_gh`, and
  `make run_release_with_devops` reads both credentials from `account_packagist.env` in the
  `ondewo-devops-accounts` repository. The package still has to be **submitted once by hand**; see README
  "Publishing to Packagist".
* `make packagist_dry_run` is the credential-free half of that path and runs in CI on every push:
  `composer validate`, `composer validate --strict` with the three deliberate warnings enumerated (the
  `version` field and the two exact `google/protobuf` / `grpc/grpc` pins — anything new fails the build), the
  agreement between `ONDEWO_CSI_VERSION`, `composer.json`, `RELEASE.md` and the git tag, and the exact update
  payload the real publish POSTs.
* `.github/workflows/release.yml` runs on a bare `X.Y.Z` tag push, re-runs the dry run and the full test
  suite against the tagged tree and only then publishes, using GitHub secrets. A missing secret fails its
  first step with an explicit `::error::` instead of posting an unauthenticated request.

*****************
