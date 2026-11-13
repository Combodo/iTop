# REVIEW.md: AI code review instructions for iTop

For automated reviewers (GitHub Copilot code review, Greptile, ...). Project conventions and security rules are in `AGENTS.md`; this file says what to comment on and how.

## Output
- Write every comment in English.
- Start each comment with its priority: `[P1-Security]`, `[P2-Bug]`, `[P2-Regression]`, `[P2-Performance]`, `[P2-Architecture]` or `[P3]`.
- Report every P1 and P2 issue. Report at most 3 `[P3]` comments per PR: keep the most useful ones.
- Prefix test suggestions with `[Tests]` and put them on the code to cover; they do not count toward the P3 limit.
- No praise, no comment that only describes the change.

## Priorities
1. **P1 Security**: XSS, CSRF, OQL/SQL injection, missing authentication or rights check, sensitive data in logs, unsafe file or path handling, any breach of the "Critical rules" in `AGENTS.md`, a security option that is not secure by default or not named `security.disable_*`.
2. **P2 Bug**: logic error, wrong condition, unhandled `null`/`false` return or exception, code incompatible with the minimum PHP version, broken existing feature, leftover debug output (`var_dump()`, `print_r()`, `console.log()`).
3. **P2 Regression**: breach of the "Backward compatibility" rules in `AGENTS.md`; change to an existing datamodel class or attribute.
4. **P2 Performance**: breach of the "Performance" rules in `AGENTS.md`, repeated expensive calls.
5. **P2 Architecture**: logic in the wrong layer, a new service that is not a singleton, a new core production PHP class outside of the `sources/` folder (excluding test classes and module-specific code), a new PHP class in `core/`, `application/` or `sources/` without the updated `lib/composer/autoload_classmap.php`, a new dependency missing from `setup/licenses/community-licenses.xml`, SCSS changed without its versioned compiled CSS.
6. **P3 Other**: unclear names (variables, functions, classes), deviations from `AGENTS.md` conventions, typos and grammar in identifiers, comments and dictionary entries, UI labels that could be clearer or more consistent with existing ones, duplicated logic or re-implementation of an existing helper (`utils`, `MetaModel`...), dead or commented-out code, function too long or too nested to read.

## Tests
- When the PR changes behavior, propose the missing test cases: what to assert, in which file of `tests/php-unit-tests/unitary-tests/`.
- Check that the base class is the lightest that works (`ItopDataTestCase` only if the test needs the datamodel). Flag unjustified process isolation and static state left behind.

## Do not comment on
- Formatting: indentation, spacing, line breaks, brace placement. PHP-CS-Fixer and `.editorconfig` handle it.
- Third-party code in `lib/` and `node_modules/`, and generated code in `env-*/`. Only check whether `lib/composer/autoload_*.php` should have changed.
- Code the PR does not touch, unless the change breaks it or it needs to be adjusted accordingly.

## Vulnerabilities
- A vulnerability introduced by the PR is always a `[P1-Security]` comment: explain the risk and how to fix it.
- This repository is public. If the PR seems to fix an existing vulnerability, review it as an ordinary change: never call it a security fix, never describe the vulnerability, how to exploit it, or the affected versions, in comments or in the PR overview. If the fix looks incomplete, describe only the missing check in neutral technical terms (for example "this value is not escaped here").