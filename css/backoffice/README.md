# Description
This is a brief description of the how the backoffice theme is structured using both BEM and SASS 7-1 systems and how to use them.
  * [7-1 pattern](#7-1-pattern)
    * [File structure](#file-structure)
    * [Usage](#usage)
  * [BEM methodology](#bem-methodology)
    * [Principles](#principles)
    * [Examples](#examples)

# 7-1 pattern
## File structure
SCSS files are structured following the [7-1 pattern](https://sass-guidelin.es/#the-7-1-pattern). \
@rveitch made a great summary with the following, which can also be found [here](https://gist.github.com/rveitch/84cea9650092119527bc).

_Note: Folders with an * are customizations we made to the original 7-1 pattern to best fit our needs_

```
css/backoffice/
|
|– utils/
|   |– variables/           # Sass Variables used in Functions, Mixins, Helpers, ...
|   |   |- colors/
|   |   |   |- _base.scss
|   |   |   |- _base-palette.scss       # Base colors used everywhere
|   |   |   |- _lifecycle-palette.scss  # Colors used for lifecycle of an object (e.g. representing states such as new, frozen, done, ...), based on the base colors
|   |   |   |- _semantic-palette.scss   # Colors used for semantic meaning (e.g. red for errors, green for success, ...), based on the base colors
|   |   |   ...
|   |   |
|   |   |- _depression.scss
|   |   |- _elevation.scss
|   |   |- _size.scss                   # Base sizes used everywhere (spacings, ...)
|   |   |- _spacing.scss
|   |   |- _typography.scss             # Typography sizes, weights, families, ...
|   |   ...
|   |
|   |– functions/           # Sass Functions
|   |   |- _color.scss      # Color manipulation functions
|   |
|   |– mixins/              # Sass Mixins
|   |– helpers/             # Class & placeholders helpers
|
|– vendors/                 # Third-party libs, should be either:
|                           # - Overload of the lib SCSS variables (BEST way, but possible only if the lib exposes them. e.g. Bulma)
|                           # - Overload of the lib necessary CSS classes only (not great as it duplicates some rules in the browser, which add weight and computation. e.g. dataTables)
|                           # - Duplicate the lib CSS completly to insert SCSS variables (not great as it will be outdated when updating the lib itself. e.g. jQuery UI)
|   |– _bulma-variables-overload.scss   # Bulma CSS framework
|   |– _jquery-ui.scss                  # jQuery UI
|   ...                                 # Etc…
|
|– base/
|   |– _reset.scss          # Reset/normalize
|   |– _typography.scss     # Typography fonts imports
|   ...                     # Etc…
|
|– components/              # Components of the UI, each corresponding to a UI block and being usable as a standalone
|   |– _button.scss
|   |– _button-group.scss
|   |– _global-search.scss
|   |– _quick-create.scss
|   ...
|
|– layout/                  # Elements of the UI made of several components, making the layout of the app
|   |– activity-panel/
|   |– dashboard/
|   |– object/              # DM object display (details, summary card, ...)
|   |– tab-container/
|   ...
|
|- *application/            # Elements that are not usable as a standalone (like componants and layouts are) and very application (the backoffice) specific
|   |- display-block
|   |- tabular-fields
|   ...
|
|- *datamodel/              # SCSS / CSS3 variables and CSS classes for *PHP* classes of the DM that are part of the core (not in a module) and cannot be styled otherwise
|   |- _action.scss
|   |- _user.scss
|   ...
|
|– pages/                   # SCSS / CSS3 variables and CSS classes for HTML elements specific to backoffice pages
|   |– _base.scss           # Base for all backoffice pages
|   |– _audit.scss          # Audit page
|   |– _csv-import.scss     # CSV Import page
|   ...                     # Etc…
|
|- *blocks-integrations     # Specific rules for the integration of a UI block with another one, those kind of rules should NEVER be in the block partial directly
|   |- alert/
|   |   |- _alert-with-blocks.scss          # How an alert should be displayed when after another block
|   |- button/
|   |   |- _button-with-button.scss         # How a button should be displayed when after another button
|   |   |- _button-with-button-group.scss   # How a button should be displayed when before/after a button group
|   |- panel/
|   |   |- _panel-with-blocks.scss          # How a panel should be displayed when after another block
|   |   |- _panel-within-main-content.scss  # How a panel becomes sticky when in the main content
|   |   |- _panel-within-modal.scss         # How a panel becomes sticky when in a modal
|   |- _tab-container-within-panel.scss     # Changes the negative margins of the datatable so it overlaps the panel's original padding
|   ...
|
|– themes/
|   |– _page-banner.scss    # ???
|   ...                     # Etc…
|
|
|- _fallback.scss           # Fallback file, should only contain rules that make standard HTML tags fallback to the style of a custom CSS class
|- _shame.scss              # Shame file, should contain all the ugly hacks (https://sass-guidelin.es/#shame-file)
`– main.scss                # Main Sass file
```

## Usage
To avoid common errors, files should be imported in the final file in the following order. Again those are just following the SASS guidelines:
- Utils
    - Variables
    - Functions
    - Mixins
    - Helpers
- Vendors
- Base
- Components
- Layout
- \*Application
- \*Datamodel
- Pages
- \*Block integrations
- Themes
- Shame file

# BEM methodology
## Principles
[BEM is a methodology](https://getbem.com/) that helps you to create reusable components and code sharing in front‑end development. \
The main idea is to use discriminant classes instead of nested basic selectors for 2 main reasons:
  * It's easier to understand the purpose of a specific class when seeing it in the HTML markup of the SCSS file
  * It's easier to override a specific class when needed as you don't need to use a selector at least as precise/complex as the one you want to override

In our implementation, we start with the code of the UI block, followed by the sub-element, then the property or modifier. Separation is made of `--` instead of `__`.

## Examples
### Classes and CSS properties example
```scss
// SCSS variables:
// - For CSS properties: CSS class, followed by CSS property
$ibo-button--padding-y: 6px !default;
$ibo-button--padding-x: 9px !default;
$ibo-button--border: 0 !default;
$ibo-button--border-radius: $ibo-border-radius-400 !default;
$ibo-button--box-shadow-bottom: 0px 2px 0px !default;
$ibo-button--box-shadow-top: inset 0px 2px 0px !default;

$ibo-button--label--margin-left: $ibo-spacing-200 !default;

// CSS classes:
.ibo-button {
    padding: $ibo-button--padding-y $ibo-button--padding-x;
    border: $ibo-button--border;
    border-radius: $ibo-button--border-radius;
}

.ibo-button--label {
    margin-left: $ibo-button--label--margin-left;
}
```

### States example
```scss
// SCSS variables:
// Same rule as before, but with a `--is-` or `--on--` suffix
$ibo-quick-create--input--padding: 0 default;
$ibo-quick-create--input--padding-x--is-opened: $ibo-spacing-300 !default;
$ibo-quick-create--input--padding-y--is-opened: $ibo-spacing-300 !default;

$ibo-quick-create--input--width: 0 !default;
$ibo-quick-create--input--width--is-opened: 245px !default;

$ibo-quick-create--input--background-color: $ibo-color-white-100 !default;
$ibo-quick-create--input--background-color--on-hover: $ibo-color-grey-200 !default;
```