# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - 2019-02-15
### Added
- Zero-width non-joiners are now stripped to prevent output issues, similar to non-breaking whitespace

### Fixed
- Fix namespace in composer [#67](https://github.com/soundasleep/html2text/pull/67)

## [1.0.0] - 2019-02-14
### Added
- Added `drop_links` option to render links without the target href [#65](https://github.com/soundasleep/html2text/pull/65)

### Changed
- **Important:** Changed namespace from `\Html2Text\Html2Text` to `\Soundasleep\Html2text` [#45](https://github.com/soundasleep/html2text/issues/45)
- Treat non-breaking spaces consistently: never include them in output text [#64](https://github.com/soundasleep/html2text/pull/64)
- Second argument to `convert()` is now an array, rather than boolean [#65](https://github.com/soundasleep/html2text/pull/65)
- Optimise/improve newline & whitespace handling [#47](https://github.com/soundasleep/html2text/pull/47)
- Upgrade PHP support to PHP 7.3+
- Upgrade PHPUnit to 7.x
- Re-release project under MIT license [#58](https://github.com/soundasleep/html2text/issues/58)

## [0.5.0] - 2017-04-20
### Added
- Add ignore_error optional argument [#63](https://github.com/soundasleep/html2text/pull/63)
- Blockquote support [#50](https://github.com/soundasleep/html2text/pull/50)

[Unreleased]: https://github.com/soundasleep/html2text/compare/1.1.0...HEAD
[1.1.0]: https://github.com/soundasleep/html2text/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/soundasleep/html2text/compare/0.5.0...1.0.0
[0.5.0]: https://github.com/soundasleep/html2text/compare/0.5.0...0.3.4
