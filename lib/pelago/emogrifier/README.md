# Emogrifier

[![Build Status](https://travis-ci.org/MyIntervals/emogrifier.svg?branch=master)](https://travis-ci.org/MyIntervals/emogrifier)
[![Latest Stable Version](https://poser.pugx.org/pelago/emogrifier/v/stable.svg)](https://packagist.org/packages/pelago/emogrifier)
[![Total Downloads](https://poser.pugx.org/pelago/emogrifier/downloads.svg)](https://packagist.org/packages/pelago/emogrifier)
[![Latest Unstable Version](https://poser.pugx.org/pelago/emogrifier/v/unstable.svg)](https://packagist.org/packages/pelago/emogrifier)
[![License](https://poser.pugx.org/pelago/emogrifier/license.svg)](https://packagist.org/packages/pelago/emogrifier)

_n. e•mog•ri•fi•er [\ē-'mä-grƏ-,fī-Ər\] - a utility for changing completely the
nature or appearance of HTML email, esp. in a particularly fantastic or bizarre
manner_

Emogrifier converts CSS styles into inline style attributes in your HTML code.
This ensures proper display on email and mobile device readers that lack
stylesheet support.

This utility was developed as part of [Intervals](http://www.myintervals.com/)
to deal with the problems posed by certain email clients (namely Outlook 2007
and GoogleMail) when it comes to the way they handle styling contained in HTML
emails. As many web developers and designers already know, certain email
clients are notorious for their lack of CSS support. While attempts are being
made to develop common [email standards](http://www.email-standards.org/),
implementation is still a ways off.

The primary problem with uncooperative email clients is that most tend to only
regard inline CSS, discarding all `<style>` elements and links to stylesheets
in `<link>` elements. Emogrifier solves this problem by converting CSS styles
into inline style attributes in your HTML code.

- [How it works](#how-it-works)
- [Installation](#installation)
- [Usage](#usage)
- [Options](#options)
- [Installing with Composer](#installing-with-composer)
- [Supported CSS selectors](#supported-css-selectors)
- [Caveats](#caveats)
- [Processing HTML](#processing-html)
- [Maintainers](#maintainers)

## How it Works

Emogrifier automagically transmogrifies your HTML by parsing your CSS and
inserting your CSS definitions into tags within your HTML based on your CSS
selectors.

## Installation

For installing emogrifier, either add pelago/emogrifier to your
project's composer.json, or you can use composer as below:

```bash
composer require pelago/emogrifier
```

## Usage

First, you provide Emogrifier with the HTML and CSS you would like to merge.
This can happen directly during instantiation:

```php
$html = '<html><h1>Hello world!</h1></html>';
$css = 'h1 {font-size: 32px;}';
$emogrifier = new \Pelago\Emogrifier($html, $css);
```

You could also use the setters for providing this data after instantiation:

```php
$emogrifier = new \Pelago\Emogrifier();

$html = '<html><h1>Hello world!</h1></html>';
$css = 'h1 {font-size: 32px;}';

$emogrifier->setHtml($html);
$emogrifier->setCss($css);
```

After you have set the HTML and CSS, you can call the `emogrify` method to
merge both:

```php
$mergedHtml = $emogrifier->emogrify();
```

Emogrifier automatically adds a Content-Type meta tag to set the charset for
the document (if it is not provided).

If you would like to get back only the content of the BODY element instead of
the complete HTML document, you can use the `emogrifyBodyContent` instead:

```php
$bodyContent = $emogrifier->emogrifyBodyContent();
```

## Options

There are several options that you can set on the Emogrifier object before
calling the `emogrify` method:

* `$emogrifier->disableStyleBlocksParsing()` - By default, Emogrifier will grab
  all `<style>` blocks in the HTML and will apply the CSS styles as inline
  "style" attributes to the HTML. The `<style>` blocks will then be removed
  from the HTML. If you want to disable this functionality so that Emogrifier
  leaves these `<style>` blocks in the HTML and does not parse them, you should
  use this option. If you use this option, the contents of the `<style>` blocks
  will _not_ be applied as inline styles and any CSS you want Emogrifier to
  use must be passed in as described in the [Usage section](#usage) above.
* `$emogrifier->disableInlineStylesParsing()` - By default, Emogrifier
  preserves all of the "style" attributes on tags in the HTML you pass to it.
  However if you want to discard all existing inline styles in the HTML before
  the CSS is applied, you should use this option.
* `$emogrifier->disableInvisibleNodeRemoval()` - By default, Emogrifier removes
  elements from the DOM that have the style attribute `display: none;`.  If
  you would like to keep invisible elements in the DOM, use this option.
  Note: This option will be removed in Emogrifier 3.0. HTML tags with
  `display: none;` then will always be retained.
* `$emogrifier->addAllowedMediaType(string $mediaName)` - By default, Emogrifier
  will keep only media types `all`, `screen` and `print`. If you want to keep
  some others, you can use this method to define them.
* `$emogrifier->removeAllowedMediaType(string $mediaName)` - You can use this
  method to remove media types that Emogrifier keeps.
* `$emogrifier->addExcludedSelector(string $selector)` - Keeps elements from
  being affected by emogrification.
* `$emogrifier->enableCssToHtmlMapping()` - Some email clients don't support CSS
  well, even if inline and prefer HTML attributes. This function allows you to
  put properties such as height, width, background color and font color in your
  CSS while the transformed content will have all the available HTML
  attributes set. This option will be removed in Emogrifier 3.0. Please use the
  `CssToAttributeConverter` class instead.

## Installing with Composer

Download the [`composer.phar`](https://getcomposer.org/composer.phar) locally
or install [Composer](https://getcomposer.org/) globally:

```bash
curl -s https://getcomposer.org/installer | php
```

Run the following command for a local installation:

```bash
php composer.phar require pelago/emogrifier:^2.1.0
```

Or for a global installation, run the following command:

```bash
composer require pelago/emogrifier:^2.1.0
```

You can also add follow lines to your `composer.json` and run the
`composer update` command:

```json
"require": {
  "pelago/emogrifier": "^2.1.0"
}
```

See https://getcomposer.org/ for more information and documentation.

## Supported CSS selectors

Emogrifier currently supports the following
[CSS selectors](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Selectors):

 * [type](https://developer.mozilla.org/en-US/docs/Web/CSS/Type_selectors)
 * [class](https://developer.mozilla.org/en-US/docs/Web/CSS/Class_selectors)
 * [ID](https://developer.mozilla.org/en-US/docs/Web/CSS/ID_selectors)
 * [universal](https://developer.mozilla.org/en-US/docs/Web/CSS/Universal_selectors):
 * [attribute](https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors):
    * presence
    * exact value match
    * value with `~` (one word within a whitespace-separated list of words)
    * value with `|` (either exact value match or prefix followed by a hyphen)
    * value with `^` (prefix match)
    * value with `$` (suffix match)
    * value with `*` (substring match)
 * [adjacent](https://developer.mozilla.org/en-US/docs/Web/CSS/Adjacent_sibling_selectors)
 * [child](https://developer.mozilla.org/en-US/docs/Web/CSS/Child_selectors)
 * [descendant](https://developer.mozilla.org/en-US/docs/Web/CSS/Descendant_selectors)
 * [pseudo-classes](https://developer.mozilla.org/en-US/docs/Web/CSS/Pseudo-classes):
   * [first-child](https://developer.mozilla.org/en-US/docs/Web/CSS/:first-child)
   * [last-child](https://developer.mozilla.org/en-US/docs/Web/CSS/:last-child)
   * [not()](https://developer.mozilla.org/en-US/docs/Web/CSS/:not)

The following selectors are not implemented yet:

 * [universal](https://developer.mozilla.org/en-US/docs/Web/CSS/Universal_selectors)
 * [case-insensitive attribute value](https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors#case-insensitive)
 * [general sibling](https://developer.mozilla.org/en-US/docs/Web/CSS/General_sibling_selectors)
 * [pseudo-classes](https://developer.mozilla.org/en-US/docs/Web/CSS/Pseudo-classes)
   (some of them will never be supported)
 * [pseudo-elements](https://developer.mozilla.org/en-US/docs/Web/CSS/Pseudo-elements)

## Caveats

* Emogrifier requires the HTML and the CSS to be UTF-8. Encodings like
  ISO8859-1 or ISO8859-15 are not supported.
* Emogrifier now preserves all valuable @media queries. Media queries
  can be very useful in responsive email design. See
  [media query support](https://litmus.com/help/email-clients/media-query-support/).
* Emogrifier will grab existing inline style attributes _and_ will
  grab `<style>` blocks from your HTML, but it will not grab CSS files
  referenced in <link> elements. (The problem email clients are going to ignore
  these tags anyway, so why leave them in your HTML?)
* Even with styles inline, certain CSS properties are ignored by certain email
  clients. For more information, refer to these resources:
    * [http://www.email-standards.org/](http://www.email-standards.org/)
    * [https://www.campaignmonitor.com/css/](https://www.campaignmonitor.com/css/)
    * [http://templates.mailchimp.com/resources/email-client-css-support/](http://templates.mailchimp.com/resources/email-client-css-support/)
* All CSS attributes that apply to a node will be applied, even if they are
  redundant. For example, if you define a font attribute _and_ a font-size
  attribute, both attributes will be applied to that node (in other words, the
  more specific attribute will not be combined into the more general
  attribute).
* There's a good chance you might encounter problems if your HTML is not
  well-formed and valid (DOMDocument might complain). If you get problems like
  this, consider running your HTML through
  [Tidy](http://php.net/manual/en/book.tidy.php) before you pass it to
  Emogrifier.
* Emogrifier automatically converts the provided (X)HTML into HTML5, i.e.,
  self-closing tags will lose their slash. To keep your HTML valid, it is
  recommended to use HTML5 instead of one of the XHTML variants.
* Emogrifier only supports CSS1 level selectors and a few CSS2 level selectors
  (but not all of them). It does not support pseudo selectors. (Emogrifier
  works by converting CSS selectors to XPath selectors, and pseudo selectors
  cannot be converted accurately).

## Processing HTML

The Emogrifier package also provides classes for (post-)processing the HTML
generated by `emogrify` (and it also works on any other HTML).

### Normalizing and cleaning up HTML

The `HtmlNormalizer` class normalizes the given HTML in the following ways:

- add a document type (HTML5) if missing
- disentangle incorrectly nested tags
- add HEAD and BODY elements (if they are missing)
- reformat the HTML

The class can be used like this:

```php
$normalizer = new \Pelago\Emogrifier\HtmlProcessor\HtmlNormalizer($rawHtml);
$cleanHtml = $normalizer->render();
```

### Converting CSS styles to visual HTML attributes

The `CssToAttributeConverter` converts a few style attributes values to visual
HTML attributes. This allows to get at least a bit of visual styling for email
clients that do not support CSS well. For example, `style="width: 100px"`
will be converted to `width="100"`.

The class can be used like this:

```php
$converter = new \Pelago\Emogrifier\HtmlProcessor\CssToAttributeConverter($rawHtml);
$visualHtml = $converter->convertCssToVisualAttributes()->render();
```

### Technology preview of new classes

Currently, a refactoring effort is underway, aiming towards replacing the
grown-over-time `Emogrifier` class with the new `CssInliner` class and moving
additional HTML processing into separate `CssProcessor` classes (which will
inherit from `AbstractHtmlProcessor`). You can try the new classes, but be
aware that the APIs of the new classes still are subject to change. 

## Steps to release a new version

1. Create a pull request "Prepare release of version x.y.z" with the following
   changes.
1. In the [composer.json](composer.json), update the `branch-alias` entry to
   point to the release _after_ the upcoming release.
1. In the [README.md](README.md), update the version numbers in the section
   [Installing with Composer](#installing-with-composer).
1. In the [CHANGELOG.md](CHANGELOG.md), set the version number and remove any
   empty sections.
1. Have the pull request reviewed and merged.
1. In the [Releases tab](https://github.com/MyIntervals/emogrifier/releases),
   create a new release and copy the change log entries to the new release.
1. Post about the new release on social media.

## Maintainers

* [Oliver Klee](https://github.com/oliverklee)
* [Zoli Szabó](https://github.com/zoliszabo)
* [Jake Hotson](https://github.com/JakeQZ)
* [John Reeve](https://github.com/jjriv)
