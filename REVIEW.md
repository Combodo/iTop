# REVIEW.md: AI code review instructions for iTop

For automated reviewers (GitHub Copilot code review, Greptile, ...). Project conventions and security rules are in `AGENTS.md`; this file says what to comment on and how.

## Output
- Write every comment in English.
- Start each comment with its priority: `[P1-Security]`, `[P2-Regression]`, `[P2-Performance]`, `[P2-Architecture]` or `[P3]`.
- Report every P1 and P2 issue. Report at most 3 `[P3]` comments per PR: keep the most useful ones.
- Put test suggestions in a single `[Tests]` comment; it does not count toward the P3 limit.
- If nothing is above P3, say so in one sentence. No praise, no file-by-file summary.

## Priorities
1. **P1 Security**: XSS, CSRF, OQL/SQL injection, missing authentication or rights check, sensitive data in logs, unsafe file or path handling, any breach of the "Critical rules" in `AGENTS.md`, a security option that is not secure by default or not named `security.disable_*`.
2. **P2 Regression**: behavior change in an API extensions rely on (public classes and methods, extension interfaces, events, configuration parameters, REST/webservices); change to an existing datamodel class or attribute; removed or renamed dictionary entry.
3. **P2 Performance**: queries inside loops, loading whole object sets when a count or a subset is enough, loading all attributes of an object in a loop instead of calling `\DBObjectSet::OptimizeColumnLoad`, repeated expensive calls.
4. **P2 Architecture**: logic in the wrong layer, a new service that is not a singleton, a new PHP class outside of the `sources/` folder, a new PHP class in `core/`, `application/` or `sources/` without the updated `lib/composer/autoload_classmap.php`, a new dependency missing from `setup/licenses/community-licenses.xml`, SCSS changed without the compiled CSS.
5. **P3 Other**: unclear names (variables, functions, classes), deviations from `AGENTS.md` conventions, typos and grammar in identifiers, comments and dictionary entries, UI labels that could be clearer or more consistent with existing ones.

## Tests
- When the PR changes behavior, propose the missing test cases: what to assert, in which file of `tests/php-unit-tests/unitary-tests/`.
- Propose missing test cases to ensure maximum coverage of the new code, and that it is not broken by future changes.
- Check readability, and that the base class is the lightest that works: `ItopDataTestCase` only if the test needs the datamodel. Flag unjustified process isolation and static state left behind.

## Do not comment on
- Formatting: indentation, spacing, line breaks, brace placement. PHP-CS-Fixer and `.editorconfig` handle it.
- Third-party code in `lib/` and `node_modules/`, and generated code in `env-*/`. Only check whether `lib/composer/autoload_*.php` should have changed.
- Code the PR does not touch, unless the change breaks it or it needs to be adjusted accordingly.

## Vulnerability fixes
This repository is public. If a change seems to fix an existing vulnerability, never describe the vulnerability, how to exploit it, or which versions are affected: neither in comments nor in the PR summary. Review it as an ordinary change. If the fix looks incomplete, describe only the missing check in neutral technical terms (for example "this value is not escaped here").