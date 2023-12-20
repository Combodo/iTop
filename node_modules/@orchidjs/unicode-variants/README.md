# Unicode Variants
[![Build Status](https://img.shields.io/travis/com/orchidjs/unicode-variants)](https://travis-ci.com/github/orchidjs/unicode-variants)
[![Coverage Status](http://img.shields.io/coveralls/orchidjs/unicode-variants/master.svg?style=flat)](https://coveralls.io/r/orchidjs/unicode-variants)
<a href="https://www.npmjs.com/package/@orchidjs/unicode-variants" class="m-1 d-inline-block"><img alt="npm (scoped)" src="https://img.shields.io/npm/v/@orchidjs/unicode-variants?color=007ec6"></a>

A small utility for comparing strings with unicode variants

Supported comparisons:
* 1/4 and ¼
* TM and ™
* À, Á, Â, Ã, Ä, Å, Ⓐ and A
* キロ and ㌔
* and thousands more

## Example

```js

const stringa   = '1/4';
const stringb   = '¼';

// without @orchidjs/unicode-variants
let regex       = new RegExp(stringa,'ui');
console.log(regex.test(stringa)); // true
console.log(regex.test(stringb)); // false

// with @orchidjs/unicode-variants
import {getPattern} from '@orchidjs/unicode-variants';
let pattern     = getPattern(stringa);
regex           = new RegExp(stringa,'ui');
console.log(regex.test(stringa)); // true
console.log(regex.test(stringb)); // true

```

## Installation

```sh
$ npm install @orchidjs/unicode-variants
```

## Contributing

Install the dependencies that are required to build and test:

```sh
$ npm install
```

Build from typescript
```sh
$ npm run build
```

Run tests
```sh
$ npm test
```

## License

Copyright &copy; 2013–2021 [Contributors](https://github.com/orchidjs/unicode-variants/graphs/contributors)

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at: http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
