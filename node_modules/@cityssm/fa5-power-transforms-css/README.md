# fa5-power-transforms-css
Font Awesome 5 "Power Transform" like functionality using straight CSS


## What is this?

Font Awesome 5 provides some really neat functionality they call Power Transforms.
Power Transforms allow you to shrink icons, rotate icons, and layer icons in endless ways.

The problem is, Power Transforms are only available to users of the SVG + JS version of Font Awesome.
At this time, I prefer the web fonts version for performance reasons.

This project attempts to recreate some of the key Power Transform features in straight CSS.


## Which `data-fa-transform` options are recreated?

`rotate-n`
- Rotates the icon by a given number of degrees.
- Supports whole numbers only, multiples of 5, -355 to 355.

`flip-h`, `flip-h`
- Flips an icon horizontally, vertically, or both.

`shrink-n`, `grow-n`
- Reduces the size, or increases the size.
- Supports whole numbers from 1 to 16.

### `.fa-layers` only

The Web Fonts version does offer [stacking](https://fontawesome.com/how-to-use/on-the-web/styling/stacking-icons) using the `.fa-stack` class, but the `.fa-layers` class is far more powerful.

Unlike the Font Awesome 5 implementation, the following options are only available to icons inside a `.fa-layers` container.

`up-n`, `down-n`, `left-n`, `right-n`
- Supports whole numbers from 1 to 8.


## Background Color Magic

*Note that the following attributes may require updated the CSS for your template.*

`data-fa-mask=""`
- Slightly different than the Font Awesome 5 specification.
- The existence of this attribute will **attempt** to set the icon's color to that of the background.

`data-fa-glow=""`
- Adds an outline to the icon for some really fancy layering.
- Defaults to white, with some Bootstrap 3 background color options.
- Include a value of `black`, `danger`, `warning`, `info`, or `success` to force a specific glow color.
- Defaults to an outline width of `4px`.
  - Include a value of `1px`, `2px`, or `3px` for a thinner width (or no width in IE).
  - Include a value from `5px` to `9px` for a thicker width.  (Not supported by IE.)


## I want to see it in action!

[See the examples](https://cityssm.github.io/fa5-power-transforms-css/)
