# Emogrifier Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

Emogrifier is in a pre-1.0 state. This means that its APIs and behavior are
subject to breaking changes without deprecation notices.


## [1.0.0][] (2015-10-15)

### Added
- Add branch alias ([#231](https://github.com/jjriv/emogrifier/pull/231))
- Remove media queries which do not impact the document
  ([#217](https://github.com/jjriv/emogrifier/pull/217))
- Allow elements to be excluded from emogrification
  ([#215](https://github.com/jjriv/emogrifier/pull/215))
- Handle !important ([#214](https://github.com/jjriv/emogrifier/pull/214))
- emogrifyBodyContent() method
  ([#206](https://github.com/jjriv/emogrifier/pull/206))
- Cache combinedStyles ([#211](https://github.com/jjriv/emogrifier/pull/211))
- Allow user to define media types to keep
  ([#200](https://github.com/jjriv/emogrifier/pull/200))
- Ignore invalid CSS selectors
  ([#194](https://github.com/jjriv/emogrifier/pull/194))
- isRemoveDisplayNoneEnabled option
  ([#162](https://github.com/jjriv/emogrifier/pull/162))
- Allow disabling of "inline style" and "style block" parsing
  ([#156](https://github.com/jjriv/emogrifier/pull/156))
- Preserve @media if necessary
  ([#62](https://github.com/jjriv/emogrifier/pull/62))
- Add extraction of style blocks within the HTML
- Add several new pseudo-selectors (first-child, last-child, nth-child,
  and nth-of-type)


### Changed
- Make HTML5 the default document type
  ([#245](https://github.com/jjriv/emogrifier/pull/245))
- Make copyCssWithMediaToStyleNode private
  ([#218](https://github.com/jjriv/emogrifier/pull/218))
- Stop encoding umlauts and dollar signs
  ([#170](https://github.com/jjriv/emogrifier/pull/170))
- Convert the classes to namespaces
  ([#41](https://github.com/jjriv/emogrifier/pull/41))


### Deprecated
- Support for PHP 5.4 will be removed in Emogrifier 2.0.


### Removed
- Drop support for PHP 5.3
  ([#114](https://github.com/jjriv/emogrifier/pull/114))
- Support for character sets other than UTF-8 was removed.


### Fixed
- Fix failing tests on Windows due to line endings
  ([#263](https://github.com/jjriv/emogrifier/pull/263))
- Parsing CSS declaration blocks
  ([#261](https://github.com/jjriv/emogrifier/pull/261))
- Fix first-child and last-child selectors
  ([#257](https://github.com/jjriv/emogrifier/pull/257))
- Fix parsing of CSS for data URIs
  ([#243](https://github.com/jjriv/emogrifier/pull/243))
- Fix multi-line media queries
  ([#241](https://github.com/jjriv/emogrifier/pull/241))
- Keep CSS media queries even if followed by CSS comments
  ([#201](https://github.com/jjriv/emogrifier/pull/201))
- Fix CSS selectors with exact attribute only
  ([#197](https://github.com/jjriv/emogrifier/pull/197))
- Properly handle UTF-8 characters and entities
  ([#189](https://github.com/jjriv/emogrifier/pull/189))
- Add mbstring extension to composer.json
  ([#93](https://github.com/jjriv/emogrifier/pull/93))
- Prevent incorrectly capitalized CSS selectors from being stripped
  ([#85](https://github.com/jjriv/emogrifier/pull/85))
- Fix CSS selectors with exact attribute only
  ([#197](https://github.com/jjriv/emogrifier/pull/197))
- Wrong selector extraction from minified CSS
  ([#69](https://github.com/jjriv/emogrifier/pull/69))
- Restore libxml error handler state after clearing
  ([#65](https://github.com/jjriv/emogrifier/pull/65))
- Ignore all warnings produced by DOMDocument::loadHTML()
  ([#63](https://github.com/jjriv/emogrifier/pull/63))
- Style tags in HTML cause an Xpath invalid query error
  ([#60](https://github.com/jjriv/emogrifier/pull/60))
- Fix PHP warnings with PHP 5.5
  ([#26](https://github.com/jjriv/emogrifier/pull/26))
- Make removal of invisible nodes operate in a case-insensitive manner
- Fix a bug that was overwriting existing inline styles from the original HTML
