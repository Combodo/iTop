# Emogrifier Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/).

## x.y.z

### Added

### Changed

### Deprecated

### Removed

### Fixed

## 2.1.0

### Added
- PHP 7.3 support
  ([#638](https://github.com/MyIntervals/emogrifier/pull/638))
  - Allow PHP 7.3 in `composer.json`
  - Test in Travis for PHP 7.3
- Add a `renderBodyContent()` method
  ([#633](https://github.com/MyIntervals/emogrifier/pull/633))
- Add a `getDomDocument()` method
  ([#630](https://github.com/MyIntervals/emogrifier/pull/630))
- Add a Composer script for PHP CS Fixer 
  ([#607](https://github.com/MyIntervals/emogrifier/pull/607))
- Copy matching rules with dynamic pseudo-classes or pseudo-elements in
  selectors to the style element
  ([#280](https://github.com/MyIntervals/emogrifier/issues/280),
  [#562](https://github.com/MyIntervals/emogrifier/pull/562),
  [#567](https://github.com/MyIntervals/emogrifier/pull/567))
- Add a CssToAttributeConverter
  ([#546](https://github.com/jjriv/emogrifier/pull/546))
- Expose the DOMDocument in AbstractHtmlProcessor
  ([#520](https://github.com/jjriv/emogrifier/pull/520))
- Add an HtmlNormalizer class
  ([#513](https://github.com/jjriv/emogrifier/pull/513),
  [#516](https://github.com/jjriv/emogrifier/pull/516))
- Add a CssInliner class
  ([#514](https://github.com/jjriv/emogrifier/pull/514),
  [#522](https://github.com/jjriv/emogrifier/pull/522))
- Composer scripts for the various CI build steps
- Validate the composer.json on Travis
  ([#476](https://github.com/jjriv/emogrifier/pull/476))

### Changed
- Mark the work-in-progress classes as `@internal`
  ([#640](https://github.com/MyIntervals/emogrifier/pull/640))
- Remove the unprocessable tags from the DOM, not from the raw HTML
  ([#627](https://github.com/MyIntervals/emogrifier/pull/627))
- Reject empty HTML in `setHtml()`
  ([#622](https://github.com/MyIntervals/emogrifier/pull/622))
- Stop passing the DOM document around
  ([#618](https://github.com/MyIntervals/emogrifier/pull/618))
- Improve performance by using explicit namespaces for PHP functions
  ([#573](https://github.com/MyIntervals/emogrifier/pull/573),
  [#576](https://github.com/MyIntervals/emogrifier/pull/576))
- Add type hint checking to the code sniffs
  ([#566](https://github.com/MyIntervals/emogrifier/pull/566))
- Check the code with PHPMD
  ([#561](https://github.com/jjriv/emogrifier/pull/561))
- Add the cyclomatic complexity to the checked code sniffs
  ([#558](https://github.com/jjriv/emogrifier/pull/558))
- Use the Symfony CSS selector component
  ([#540](https://github.com/jjriv/emogrifier/pull/540))

### Deprecated
- Support for PHP 5.5 will be removed in Emogrifier 3.0.
- Support for PHP 5.6 will be removed in Emogrifier 4.0.
- The removal of invisible nodes will be removed in Emogrifier 3.0.
  ([#473](https://github.com/jjriv/emogrifier/pull/473))
- Converting CSS styles to (non-CSS) HTML attributes will be removed
  in Emogrifier 3.0. Please use the new CssToAttributeConverter instead.
  ([#474](https://github.com/jjriv/emogrifier/pull/474))
- Emogrifier 3.x.y will be the last release that supports usage without
  Composer (i.e., you can still require the class file).
  Starting with version 4.0, Emogrifier will only work with Composer.
- The Emogrifier class will be superseded by CssInliner class in
  Emogrifier 3.0. For this, the Emogrifier class will be deprecated for
  version 3.0 and removed for version 4.0.

### Removed
- Drop the `@version` PHPDoc annotations
  ([#637](https://github.com/MyIntervals/emogrifier/pull/637))
- Drop the destructors
  ([#619](https://github.com/MyIntervals/emogrifier/pull/619))

### Fixed
- Add required XML PHP extension to `composer.json`
  ([#614](https://github.com/MyIntervals/emogrifier/pull/614))
- Add required DOM PHP extension to `composer.json`
  ([#595](https://github.com/MyIntervals/emogrifier/pull/595))
- Escape hyphens in regular expressions
  ([#588](https://github.com/MyIntervals/emogrifier/pull/588))
- Fix Travis for PHP 5.x
  ([#589](https://github.com/MyIntervals/emogrifier/pull/589))
- Allow CSS between empty `@media` rule and another `@media` rule
  ([#534](https://github.com/MyIntervals/emogrifier/pull/534))
- Allow additional whitespace in media-query-list of disallowed `@media` rules
  ([#532](https://github.com/MyIntervals/emogrifier/pull/532))
- Allow multiple minified `@import` rules in the CSS without error (note:
  `@import`s are currently ignored,
  [#527](https://github.com/MyIntervals/emogrifier/pull/527))
- Style property ordering when multiple mixed individual and shorthand
  properties apply ([#511](https://github.com/MyIntervals/emogrifier/pull/511),
  [#508](https://github.com/MyIntervals/emogrifier/issues/508))
- Calculation of selector precedence for selectors involving pseudo-classes
  and/or attributes ([#502](https://github.com/MyIntervals/emogrifier/pull/502))
- Allow `@charset` in the CSS without error (note: its value is currently
  ignored, [#507](https://github.com/MyIntervals/emogrifier/pull/507))
- Allow attribute selectors in descendants
  ([#506](https://github.com/MyIntervals/emogrifier/pull/506),
  [#381](https://github.com/MyIntervals/emogrifier/issues/381))
- Allow adjacent sibling CSS selector combinator in minified CSS
  ([#505](https://github.com/MyIntervals/emogrifier/pull/505))
- Allow CSS property values containing newlines
  ([#504](https://github.com/MyIntervals/emogrifier/pull/504))

## 2.0.0

### Added
- Support for CSS :not() selector
  ([#431](https://github.com/jjriv/emogrifier/pull/431))
- Automatically remove !important annotations from final inline style declarations
  ([#420](https://github.com/MyIntervals/emogrifier/pull/420))
- Automatically move `<style>` block from `<head>` to `<body>`
  ([#396](https://github.com/MyIntervals/emogrifier/pull/396))
- PHP 7.2 support ([#398](https://github.com/MyIntervals/emogrifier/pull/398))
  - Allow PHP 7.2 in `composer.json`, cleaner PHP version constraint
  - Test in Travis for PHP 7.2
- Debug mode. Throw debug exceptions only if debug is active.
  ([#392](https://github.com/MyIntervals/emogrifier/pull/392))

### Changed
- Test with latest and oldest dependencies on Travis
  ([#463](https://github.com/MyIntervals/emogrifier/pull/463))
- Always enable the debug mode in the tests
  ([#448](https://github.com/MyIntervals/emogrifier/pull/448))
- Optimize the string operations
  ([#430](https://github.com/MyIntervals/emogrifier/pull/430))

### Deprecated
- Support for PHP 5.5 will be removed in Emogrifier 3.0.
- Support for PHP 5.6 will be removed in Emogrifier 4.0.

### Removed
- Drop support for PHP 5.4
  ([#422](https://github.com/MyIntervals/emogrifier/pull/422))
- Drop support for HHVM
  ([#386](https://github.com/MyIntervals/emogrifier/pull/386))

### Fixed
- Handle invalid/unrecognized selectors in media query blocks
  ([#442](https://github.com/MyIntervals/emogrifier/pull/442))
- Throw (the correct) exception for invalid excluded selectors
  ([#437](https://github.com/MyIntervals/emogrifier/pull/437))
- emogrifyBody must not encode umlaut entities
  ([#414](https://github.com/MyIntervals/emogrifier/pull/414))
- Fix mapped HTML attribute values
  ([#405](https://github.com/MyIntervals/emogrifier/pull/405))
- Make sure the HTML always has a BODY element
  ([#410](https://github.com/MyIntervals/emogrifier/pull/410))
- Make inline style priority higher than css block priority
  ([#404](https://github.com/MyIntervals/emogrifier/pull/404))
- Fix media regex parsing
  ([#402](https://github.com/MyIntervals/emogrifier/pull/402))
- Silence purposefully ignored PHP Warnings
  ([#400](https://github.com/MyIntervals/emogrifier/pull/400))

## 1.2.0 (2017-03-02)

### Added
- Handling invalid xPath expression warnings
  ([#361](https://github.com/MyIntervals/emogrifier/pull/361))

### Deprecated
- Support for PHP 5.5 will be removed in Emogrifier 3.0.
- Support for PHP 5.4 will be removed in Emogrifier 2.0.

### Fixed
- Allow colon (`:`) and semi-colon (`;`) when using the `*=` selector
  ([#371](https://github.com/MyIntervals/emogrifier/pull/371))
- Ignore "auto" width and height
  ([#365](https://github.com/MyIntervals/emogrifier/pull/365))

## 1.1.0 (2016-09-18)

### Added
- Add support for PHP 7.1
  ([#342](https://github.com/MyIntervals/emogrifier/pull/342))
- Support the attr|=value selector
  ([#337](https://github.com/MyIntervals/emogrifier/pull/337))
- Support the attr*=value selector
  ([#330](https://github.com/MyIntervals/emogrifier/pull/330))
- Support the attr$=value selector
  ([#329](https://github.com/MyIntervals/emogrifier/pull/329))
- Support the attr^=value selector
  ([#324](https://github.com/MyIntervals/emogrifier/pull/324))
- Support the attr~=value selector
  ([#323](https://github.com/MyIntervals/emogrifier/pull/323))
- Add CSS to HTML attribute mapper
  ([#288](https://github.com/MyIntervals/emogrifier/pull/288))

### Changed
- Remove composer dependency from PHP mbstring extension
  (Actual code dependency were removed a lot of time ago)
  ([#295](https://github.com/MyIntervals/emogrifier/pull/295))

### Deprecated
- Support for PHP 5.5 will be removed in Emogrifier 3.0.
- Support for PHP 5.4 will be removed in Emogrifier 2.0.

### Fixed
- Method emogrifyBodyContent() doesn't keeps utf8 umlauts
  ([#349](https://github.com/MyIntervals/emogrifier/pull/349))
- Ignore value with words more than one in the attribute selector
  ([#327](https://github.com/MyIntervals/emogrifier/pull/327))
- Ignore spaces around the > in the direct child selector
  ([#322](https://github.com/MyIntervals/emogrifier/pull/322))
- Ignore empty media queries
  ([#307](https://github.com/MyIntervals/emogrifier/pull/307))
  ([#237](https://github.com/MyIntervals/emogrifier/issues/237))
- Ignore pseudo-class when combined with pseudo-element
  ([#308](https://github.com/MyIntervals/emogrifier/pull/308))
- First-child and last-child selectors are broken
  ([#293](https://github.com/MyIntervals/emogrifier/pull/293))
- Second !important rule needs to overwrite the first one
  ([#292](https://github.com/MyIntervals/emogrifier/pull/292))

## 1.0.0 (2015-10-15)

### Added
- Add branch alias ([#231](https://github.com/MyIntervals/emogrifier/pull/231))
- Remove media queries which do not impact the document
  ([#217](https://github.com/MyIntervals/emogrifier/pull/217))
- Allow elements to be excluded from emogrification
  ([#215](https://github.com/MyIntervals/emogrifier/pull/215))
- Handle !important ([#214](https://github.com/MyIntervals/emogrifier/pull/214))
- emogrifyBodyContent() method
  ([#206](https://github.com/MyIntervals/emogrifier/pull/206))
- Cache combinedStyles ([#211](https://github.com/MyIntervals/emogrifier/pull/211))
- Allow user to define media types to keep
  ([#200](https://github.com/MyIntervals/emogrifier/pull/200))
- Ignore invalid CSS selectors
  ([#194](https://github.com/MyIntervals/emogrifier/pull/194))
- isRemoveDisplayNoneEnabled option
  ([#162](https://github.com/MyIntervals/emogrifier/pull/162))
- Allow disabling of "inline style" and "style block" parsing
  ([#156](https://github.com/MyIntervals/emogrifier/pull/156))
- Preserve @media if necessary
  ([#62](https://github.com/MyIntervals/emogrifier/pull/62))
- Add extraction of style blocks within the HTML
- Add several new pseudo-selectors (first-child, last-child, nth-child,
  and nth-of-type)

### Changed
- Make HTML5 the default document type
  ([#245](https://github.com/MyIntervals/emogrifier/pull/245))
- Make copyCssWithMediaToStyleNode private
  ([#218](https://github.com/MyIntervals/emogrifier/pull/218))
- Stop encoding umlauts and dollar signs
  ([#170](https://github.com/MyIntervals/emogrifier/pull/170))
- Convert the classes to namespaces
  ([#41](https://github.com/MyIntervals/emogrifier/pull/41))

### Deprecated
- Support for PHP 5.4 will be removed in Emogrifier 2.0.

### Removed
- Drop support for PHP 5.3
  ([#114](https://github.com/MyIntervals/emogrifier/pull/114))
- Support for character sets other than UTF-8 was removed.

### Fixed
- Fix failing tests on Windows due to line endings
  ([#263](https://github.com/MyIntervals/emogrifier/pull/263))
- Parsing CSS declaration blocks
  ([#261](https://github.com/MyIntervals/emogrifier/pull/261))
- Fix first-child and last-child selectors
  ([#257](https://github.com/MyIntervals/emogrifier/pull/257))
- Fix parsing of CSS for data URIs
  ([#243](https://github.com/MyIntervals/emogrifier/pull/243))
- Fix multi-line media queries
  ([#241](https://github.com/MyIntervals/emogrifier/pull/241))
- Keep CSS media queries even if followed by CSS comments
  ([#201](https://github.com/MyIntervals/emogrifier/pull/201))
- Fix CSS selectors with exact attribute only
  ([#197](https://github.com/MyIntervals/emogrifier/pull/197))
- Properly handle UTF-8 characters and entities
  ([#189](https://github.com/MyIntervals/emogrifier/pull/189))
- Add mbstring extension to composer.json
  ([#93](https://github.com/MyIntervals/emogrifier/pull/93))
- Prevent incorrectly capitalized CSS selectors from being stripped
  ([#85](https://github.com/MyIntervals/emogrifier/pull/85))
- Fix CSS selectors with exact attribute only
  ([#197](https://github.com/MyIntervals/emogrifier/pull/197))
- Wrong selector extraction from minified CSS
  ([#69](https://github.com/MyIntervals/emogrifier/pull/69))
- Restore libxml error handler state after clearing
  ([#65](https://github.com/MyIntervals/emogrifier/pull/65))
- Ignore all warnings produced by DOMDocument::loadHTML()
  ([#63](https://github.com/MyIntervals/emogrifier/pull/63))
- Style tags in HTML cause an Xpath invalid query error
  ([#60](https://github.com/MyIntervals/emogrifier/pull/60))
- Fix PHP warnings with PHP 5.5
  ([#26](https://github.com/MyIntervals/emogrifier/pull/26))
- Make removal of invisible nodes operate in a case-insensitive manner
- Fix a bug that was overwriting existing inline styles from the original HTML
