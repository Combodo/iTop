## Description
This is a brief description of the SASS 7-1 system and how to use it.
- [File structure](#file-structure)
- [Usage](#usage)

## File structure
SCSS files are structured following the [7-1 pattern](https://sass-guidelin.es/#the-7-1-pattern). \
@rveitch made a great summary with the following, which can also be found [here](https://gist.github.com/rveitch/84cea9650092119527bc).

_Note: Folders with an * are customizations we made to the original 7-1 pattern to best fit our needs_  

```
css/backoffice/
|
|– utils/
|   |– _variables.scss   # Sass Variables
|   |– _functions.scss   # Sass Functions
|   |– _mixins.scss      # Sass Mixins
|   |– _helpers.scss     # Class & placeholders helpers
|
|– vendors/
|   |– _bootstrap.scss   # Bootstrap
|   |– _jquery-ui.scss   # jQuery UI
|   ...                  # Etc…
|
|– base/
|   |– _reset.scss       # Reset/normalize
|   |– _typography.scss  # Typography rules
|   ...                  # Etc…
|
|– components/
|   |– _buttons.scss     # Buttons
|   |– _carousel.scss    # Carousel
|   |– _cover.scss       # Cover
|   |– _dropdown.scss    # Dropdown
|   ...                  # Etc…
|
|– layout/
|   |– _navigation.scss  # Navigation
|   |– _grid.scss        # Grid system
|   |– _header.scss      # Header
|   |– _footer.scss      # Footer
|   |– _sidebar.scss     # Sidebar
|   |– _forms.scss       # Forms
|   ...                  # Etc…
|
|- *application/         # Elements that are not usable as a standalone (like componants and layouts are) and very application (the backoffice) specific
|   |- display-block
|   |- tabular-fields
|   ...
|
|– pages/
|   |– _home.scss        # Home specific styles
|   |– _contact.scss     # Contact specific styles
|   ...                  # Etc…
|
|- *blocks-integrations  # Specific rules for the integration of a block with another one, those kind of rules should never be in the block partial directly
|   |- _panel-with-datatable.scss   # Changes the negative margins of the datatable so it overlaps the panel's original padding
|   ...
|
|– themes/
|   |– _theme.scss       # Default theme
|   |– _admin.scss       # Admin theme
|   ...                  # Etc…
|
|
`- _shame.scss           # Shame file, should contain all the ugly hacks (https://sass-guidelin.es/#shame-file)
`– main.scss             # Main Sass file
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
- Pages
- \*Block integrations
- Themes
- Shame file