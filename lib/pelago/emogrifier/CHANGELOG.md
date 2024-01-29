# Emogrifier Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/).

## x.y.z

### Added

### Changed

### Deprecated

### Removed

### Fixed

## 7.2.0: Add support for Symfony 7

### Added
- Add support for Symfony 7 (#1243)

## 7.1.0: Add support for PHP 8.3

### Added
- Add support for PHP 8.3 (#1218)

### Changed
- Disable HTML formatting by default (#1214)

## 7.0.0

### Added
- Add support for PHP 8.2 (#1155)

### Changed
- Throw exception with invalid CSS in debug mode (#1142)
- Only support up to 69 atomic expressions in a selector (#1113)
- Require `sabberworm/php-css-parser:^8.4.0` (#1134)
- Upgrade to PHPUnit 9 (#1112)

### Deprecated
- Support for PHP 7.3 will be removed in Emogrifier 8.0.

### Removed
- Drop support for Symfony 3.x and 5.3 (#1120, #1162)
- Drop support for PHP 7.2 (#1111)

### Fixed
- Bump the minimum Symfony 4.4 version to avoid PHP deprecation warnings (#1187)

## 6.0.0

### Added
- Test with Symfony 6-dev (#1109)
- Add support for PHP 8.1 (#1103)
- Add a dedicated class for caching (#1097)
- Allow installation together with Symfony 6 (#1065)
- Support more file types in the `.editorconfig` (#1035)
- Set `align` attribute of `<th>` elements with `CssToAttributeConverter` (#1008)

### Changed
- Use `sabberworm/php-css-parser` to parse the CSS (#1015)
- Also check the unit test code with Psalm (#1003)

### Deprecated
- Support for PHP 7.2 will be removed in Emogrifier 7.0.

### Removed
- Remove a redundant CSS data cache (#1018)
- Drop support for Symfony 5.1 and 5.2 (#972, #1104)
- Drop support for PHP 7.1 (#967)

### Fixed
- Allow `@import` after ignored invalid `@charset` (@1081)
- Allow line feeds within `<html>` tag (#987)

## 5.0.1

### Changed
- Switch the default branch from `master` to `main` (#951)

### Fixed
- Ignore `http-equiv` `Content-Type` in `<body>` (#961)
- Allow "Content-Type" in content (#959)

## 5.0.0

### Added
- Add an `.editorconfig` file (#940)
- Support PHP 8.0 (#926)
- Run the CI build once a week (#933)
- Move more development tools to PHIVE (#894, #907)

### Changed
- Automatically add a backslash for global functions (#909)
- Update the development tools (#898, #895)
- Upgrade to PHPUnit 7.5 (#888)
- Enforce constant visibility (#892)
- Rename the PHPCS configuration file (#891, #896)
- Make use of PHP 7.1 language features (#883)

### Deprecated
- Support for PHP 7.1 will be removed in Emogrifier 6.0.

### Removed
- Drop support for Symfony 4.3 and 5.0 (#936)
- Stop checking `tests/` with Psalm (#885)
- Drop support for PHP 7.0 (#880)

### Fixed
- Fix a nonsensical code example in the README (#920, #935)
- Remove `!important` from `style` attributes also when uppercase, mixed case or
  having whitespace after `!` (#911)
- Copy rules using `:...of-type` without a type to the `<style>` element (#904)
- Support combinator followed by dynamic pseudo-class in minified CSS (#903)
- Preserve all uninlinable (or otherwise unprocessed) at-rules (#899)
- Allow Windows CLI to run development tools installed through PHIVE (#900)
- Switch to a maintained package for parallel PHP linting (#884)
- Add `.0` version suffixes to PHP version requirements (#881)

## 4.0.0

### Added
- Extract and inject `@font-face` rules into head (#870)
- Test tag omission in conformant supplied HTML (#868)
- Check for missing return type hint annotations in the code sniffs (#860)
- Support `:only-of-type` (with a type) (#849, #856)
- Configuration setting methods now all return `$this` to allow chaining (#824, #854)
- Disable php-cs-fixer Yoda conditions (#791, #794)
- Check the code with psalm (#537, #779)
- Composer script to run tests with `--stop-on-failure` (#782)
- Test universal selector with combinators (#776)

### Changed
- Normalize DOCTYPE declaration according to polyglot markup recommendation (#866)
- Upgrade to V2 of the PHP setup GitHub action (#861)
- Move the development tools to PHIVE (#850, #851)
- Switch the parallel linting to a maintained fork (#842)
- Move continuous integration from Travis CI to GitHub actions (#832, #834, #838, #839, #840, #841, #843, #846, #849)
- Clean up the folder structure and autoloading configuration (#529, #785)
- Use `self` as the return type for `fromHtml` (#784)
- Make use of PHP 7.0 language features (#777)

### Deprecated
- Support for PHP 7.0 will be removed in Emogrifier 5.0.

### Removed
- Drop support for Symfony versions that have reached their end of life (#847)
- Drop the `Emogrifier` class (#774)
- Drop support for PHP 5.6 (#773)

### Fixed
- Allow `:last-of-type` etc. without type, without causing exception (#875)
- Make sure to use the Composer-installed development tools (#862, #865)
- Add missing `<head>` element when there's a `<header>` element (#844, #853)
- Fix mapping width/height when decimal is used (#845)
- Actually use the specified PHP version on GitHub actions (#836)
- Support `ci:php:lint` on Windows (#740, #780)

## 3.1.0

### Added
- Add support for PHP 7.4 (#821, #829)

### Changed
- Upgrade to Symfony 5.0 (#820)

## 3.0.0

### Added
- Test and document excluding entire subtree with `addExcludedSelector()` (#347, #768)
- Test that rules with `:optional` or `:required` are copied to the `<style>`
  element (#748, #765)
- Test that rules with `:only-of-type` are copied to the `<style>` element (#748, #760)
- Support `:last-of-type` (#748, #758)
- Support `:first-of-type` (#748, #757)
- Support `:empty` (#748, #756)
- Test that rules with `:any-link` are copied to the `<style>` element (#748, #755)
- Support and test `:only-child` (#747, #754)
- Support and test `:nth-last-of-type` (#747, #751)
- Support and test `:nth-last-child` (#747, #750)
- Support and test general sibling combinator (#723, #745)
- Test universal selector with combinators (#723, #743)
- Preserve `display: none` elements with `-emogrifier-keep` class (#252, #737)
- Preserve valid `@import` rules (#338, #334, #732, #735)
- Add `HtmlPruner::removeRedundantClassesAfterCssInlined` (#380, #724)
- Check on Travis that PHP-CS-Fixer will not change anything (#727)
- Support `:not(…)` as an entire selector (#469, #725)
- Add `HtmlPruner::removeRedundantClasses` (#380, #708)
- Support multiple attributes selectors (#385, #721)
- Support `> :first-child` and `> :last-child` in selectors (#384, #720)
- Add an `ArrayIntersector` class (#708, #710)
- Add `CssInliner::getMatchingUninlinableSelectors` (#380, #707)
- Add tests for `:nth-child` and `:nth-of-type` (#71, #698)

### Changed
- Relax the dependency on `symfony/css-selector` (#762)
- Rename `HtmlPruner::removeInvisibleNodes` to
  `HtmlPruner::removeElementsWithDisplayNone` (#717, #718)
- Mark the utility classes as internal (#715)
- Move utility classes to the `Pelago\Emogrifier\Utilities` namespace (#712)
- Make the `$css` parameter of the `inlineCss` method optional (#700)
- Update the development dependencies (#691)

### Deprecated
- Support for PHP 5.6 will be removed in Emogrifier 4.0.
- Deprecate the `Emogrifier` class (#701)

### Removed
- Drop `enableCssToHtmlMapping` and `disableInvisibleNodeRemoval` (#692)
- Drop support for PHP 5.5 (#690)

### Fixed
- Fix PhpStorm code inspection warnings (#729, #770)
- Uppercase type combined with class or ID in selector (#590, #769)
- Dynamic pseudo-class combined with static one (rules copied to `<style>`
  element, #746)
- Descendant attribute selectors (such as `html input[disabled]`) (#375, #709)
- Attribute selectors with hyphen in attribute name (#284, #540, #704)
- Attribute selectors with space, hyphen, colon, semicolon or (most) other
  non-alphanumeric characters in attribute value (#284, #333, #550, #540, #704)
- Don’t create empty `style` attributes for unparsable declarations (#259, #702)
- Allow `:not(:behavioural-pseudo-class)` in selectors (#697, #703)

## 2.2.0

### Added
- Add a `HtmlPruner` class (#679)
- Add `AbstractHtmlProcessor::fromDomDocument` (#676)
- Add `AbstractHtmlProcessor::fromHtml` (#675)

### Changed
- Make the closures static (#674)
- Keep `<wbr>` elements by default with `CssInliner` (#665)
- Make the `CssInliner` inherit `AbstractHtmlProcessor` (#660)
- Separate `CssInliner::inlineCss` and the rendering (#654)

### Removed
- Drop the removal of unprocessable tags from `CssInliner` (#685)
- Drop the removal of invisible nodes from `CssInliner` (#684)

### Fixed
- Remove opening `<body>` tag from `body` content when element has attribute(s) (#677, #683)
- Keep development files out of the Composer packages (#678)
- Call all static methods statically in `CssConcatenator` (#670)
- Support all HTML5 self-closing tags, including `<embed>`, `<source>`,
  `<track>` and `<wbr>` (#653)
- Remove all "unprocessable" (e.g. `<wbr>`) tags (#650)
- Correct translated xpath of `:nth-child` selector (#648)

## 2.1.1

### Changed
- Add a test that a missing document type gets added (#641)

### Fixed
- Keep the `style` element the `head` (#642)

## 2.1.0

### Added
- PHP 7.3 support (#638)
  - Allow PHP 7.3 in `composer.json`
  - Test in Travis for PHP 7.3
- Add a `renderBodyContent()` method (#633)
- Add a `getDomDocument()` method (#630)
- Add a Composer script for PHP CS Fixer (#607)
- Copy matching rules with dynamic pseudo-classes or pseudo-elements in
  selectors to the style element (#280, #562, #567)
- Add a CssToAttributeConverter (#546)
- Expose the DOMDocument in AbstractHtmlProcessor (#520)
- Add an HtmlNormalizer class (#513, #516)
- Add a CssInliner class (#514, #522)
- Composer scripts for the various CI build steps
- Validate the composer.json on Travis (#476)

### Changed
- Mark the work-in-progress classes as `@internal` (#640)
- Remove the unprocessable tags from the DOM, not from the raw HTML (#627)
- Reject empty HTML in `setHtml()` (#622)
- Stop passing the DOM document around (#618)
- Improve performance by using explicit namespaces for PHP functions (#573, #576)
- Add type hint checking to the code sniffs (#566)
- Check the code with PHPMD (#561)
- Add the cyclomatic complexity to the checked code sniffs (#558)
- Use the Symfony CSS selector component (#540)

### Deprecated
- Support for PHP 5.5 will be removed in Emogrifier 3.0.
- Support for PHP 5.6 will be removed in Emogrifier 4.0.
- The removal of invisible nodes will be removed in Emogrifier 3.0. (#473)
- Converting CSS styles to (non-CSS) HTML attributes will be removed
  in Emogrifier 3.0. Please use the new CssToAttributeConverter instead. (#474)
- Emogrifier 3.x.y will be the last release that supports usage without
  Composer (i.e., you can still require the class file).
  Starting with version 4.0, Emogrifier will only work with Composer.
- The Emogrifier class will be superseded by CssInliner class in
  Emogrifier 3.0. For this, the Emogrifier class will be deprecated for
  version 3.0 and removed for version 4.0.

### Removed
- Drop the `@version` PHPDoc annotations (#637)
- Drop the destructors (#619)

### Fixed
- Add required XML PHP extension to `composer.json` (#614)
- Add required DOM PHP extension to `composer.json` (#595)
- Escape hyphens in regular expressions (#588)
- Fix Travis for PHP 5.x (#589)
- Allow CSS between empty `@media` rule and another `@media` rule (#534)
- Allow additional whitespace in media-query-list of disallowed `@media` rules (#532)
- Allow multiple minified `@import` rules in the CSS without error (note:
  `@import`s are currently ignored, #527)
- Style property ordering when multiple mixed individual and shorthand
  properties apply (#511, #508)
- Calculation of selector precedence for selectors involving pseudo-classes
  and/or attributes (#502)
- Allow `@charset` in the CSS without error (note: its value is currently
  ignored, #507)
- Allow attribute selectors in descendants (#506, #381, #443)
- Allow adjacent sibling CSS selector combinator in minified CSS (#505)
- Allow CSS property values containing newlines (#504)

## 2.0.0

### Added
- Support for CSS :not() selector (#431)
- Automatically remove !important annotations from final inline style declarations (#420)
- Automatically move `<style>` block from `<head>` to `<body>` (#396)
- PHP 7.2 support (#398)
  - Allow PHP 7.2 in `composer.json`, cleaner PHP version constraint
  - Test in Travis for PHP 7.2
- Debug mode. Throw debug exceptions only if debug is active. (#392)

### Changed
- Test with latest and oldest dependencies on Travis (#463)
- Always enable the debug mode in the tests (#448)
- Optimize the string operations (#430)

### Deprecated
- Support for PHP 5.5 will be removed in Emogrifier 3.0.
- Support for PHP 5.6 will be removed in Emogrifier 4.0.

### Removed
- Drop support for PHP 5.4 (#422)
- Drop support for HHVM (#386)

### Fixed
- Handle invalid/unrecognized selectors in media query blocks (#442)
- Throw (the correct) exception for invalid excluded selectors (#437)
- emogrifyBody must not encode umlaut entities (#414)
- Fix mapped HTML attribute values (#405)
- Make sure the HTML always has a BODY element (#410)
- Make inline style priority higher than css block priority (#404)
- Fix media regex parsing (#402)
- Silence purposefully ignored PHP Warnings (#400)

## 1.2.0 (2017-03-02)

### Added
- Handling invalid xPath expression warnings (#361)

### Deprecated
- Support for PHP 5.5 will be removed in Emogrifier 3.0.
- Support for PHP 5.4 will be removed in Emogrifier 2.0.

### Fixed
- Allow colon (`:`) and semi-colon (`;`) when using the `*=` selector (#371)
- Ignore "auto" width and height (#365)

## 1.1.0 (2016-09-18)

### Added
- Add support for PHP 7.1 (#342)
- Support the attr|=value selector (#337)
- Support the attr*=value selector (#330)
- Support the attr$=value selector (#329)
- Support the attr^=value selector (#324)
- Support the attr~=value selector (#323)
- Add CSS to HTML attribute mapper (#288)

### Changed
- Remove composer dependency from PHP mbstring extension (Actual code dependency were removed a lot of time ago) (#295)

### Deprecated
- Support for PHP 5.5 will be removed in Emogrifier 3.0.
- Support for PHP 5.4 will be removed in Emogrifier 2.0.

### Fixed
- Method emogrifyBodyContent() doesn't keeps utf8 umlauts (#349)
- Ignore value with words more than one in the attribute selector (#327)
- Ignore spaces around the > in the direct child selector (#322)
- Ignore empty media queries (#307) (#237)
- Ignore pseudo-class when combined with pseudo-element (#308)
- First-child and last-child selectors are broken (#293)
- Second !important rule needs to overwrite the first one (#292)

## 1.0.0 (2015-10-15)

### Added
- Add branch alias (#231)
- Remove media queries which do not impact the document (#217)
- Allow elements to be excluded from emogrification (#215)
- Handle !important (#214)
- emogrifyBodyContent() method (#206)
- Cache combinedStyles (#211)
- Allow user to define media types to keep (#200)
- Ignore invalid CSS selectors (#194)
- isRemoveDisplayNoneEnabled option (#162)
- Allow disabling of "inline style" and "style block" parsing (#156)
- Preserve @media if necessary (#62)
- Add extraction of style blocks within the HTML
- Add several new pseudo-selectors (first-child, last-child, nth-child,
  and nth-of-type)

### Changed
- Make HTML5 the default document type (#245)
- Make copyCssWithMediaToStyleNode private (#218)
- Stop encoding umlauts and dollar signs (#170)
- Convert the classes to namespaces (#41)

### Deprecated
- Support for PHP 5.4 will be removed in Emogrifier 2.0.

### Removed
- Drop support for PHP 5.3 (#114)
- Support for character sets other than UTF-8 was removed.

### Fixed
- Fix failing tests on Windows due to line endings (#263)
- Parsing CSS declaration blocks (#261)
- Fix first-child and last-child selectors (#257)
- Fix parsing of CSS for data URIs (#243)
- Fix multi-line media queries (#241)
- Keep CSS media queries even if followed by CSS comments (#201)
- Fix CSS selectors with exact attribute only (#197)
- Properly handle UTF-8 characters and entities (#189)
- Add mbstring extension to composer.json (#93)
- Prevent incorrectly capitalized CSS selectors from being stripped (#85)
- Fix CSS selectors with exact attribute only (#197)
- Wrong selector extraction from minified CSS (#69)
- Restore libxml error handler state after clearing (#65)
- Ignore all warnings produced by DOMDocument::loadHTML() (#63)
- Style tags in HTML cause an Xpath invalid query error (#60)
- Fix PHP warnings with PHP 5.5 (#26)
- Make removal of invisible nodes operate in a case-insensitive manner
- Fix a bug that was overwriting existing inline styles from the original HTML
