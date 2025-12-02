# Custom Elements Polyfill

This module provides the [Custom Elements V1 API](https://html.spec.whatwg.org/multipage/custom-elements.html#custom-elements-api) as defined by standards, including the ability to [extend builtin elements](https://html.spec.whatwg.org/multipage/custom-elements.html#custom-elements-customized-builtin-example), all in ~2K _minified_ and _gzipped_ / _brotlied_.


## Compatibility

The polyfill gracefully enhances the following minimum versions of at least these browsers, up to their latest version:

  * Chrome 38
  * Firefox 14
  * Opera 25
  * Internet Explorer 11 and Edge 12
  * Safari 8 and WebKit based
  * Samsung Internet 3


## How To

Either install this module via `npm i @ungap/custom-elements`, and include it in your project, or use a CDN such as [unpkg.com](https://unpkg.com/@ungap/custom-elements) to obtain the _minified_ version of this module.

```html
<!-- this should be on top of your HTML <head> scripts -->
<script src="//unpkg.com/@ungap/custom-elements"></script>
```

If targeted browsers are ES2015 compatible, the `es.js` file would provide the same polyfill, just lighter, as no transpilation is used.

```html
<script src="//unpkg.com/@ungap/custom-elements/es.js"></script>
```



If installed as module, please remember to include it on top of your main JS file.

```js
// ESM
import '@ungap/custom-elements';

// CJS
require('@ungap/custom-elements');
```

The module will incrementally patch the global `window`/`self` reference, adding a `customElements` object that is compatible with the API.


## Source Code

This module simply provides [@webreflection/custom-elements](https://github.com/WebReflection/custom-elements#readme) module under the [ungap](https://ungap.github.io/) umbrella.
