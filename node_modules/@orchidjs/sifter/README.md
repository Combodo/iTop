# sifter.js
[![Build Status](https://img.shields.io/travis/com/orchidjs/sifter.js)](https://travis-ci.com/github/orchidjs/sifter.js)
[![Coverage Status](http://img.shields.io/coveralls/orchidjs/sifter.js/master.svg?style=flat)](https://coveralls.io/r/orchidjs/sifter.js)
<a href="https://www.npmjs.com/package/@orchidjs/sifter" class="m-1 d-inline-block"><img alt="npm (scoped)" src="https://img.shields.io/npm/v/@orchidjs/sifter?color=007ec6"></a>

Sifter is a fast and small (<6kb) client and server-side library (coded in TypeScript and available in [CJS, UMD, and ESM](https://irian.to/blogs/what-are-cjs-amd-umd-and-esm-in-javascript/)) for textually searching arrays and hashes of objects by property – or multiple properties. It's designed specifically for autocomplete. The process is three-step: *score*, *filter*, *sort*.

* **Supports díåcritîçs.**<br>For example, if searching for "montana" and an item in the set has a value of "montaña", it will still be matched. Sorting will also play nicely with diacritics. (using [unicode-variants](https://github.com/orchidjs/unicode-variants))
* **Smart scoring.**<br>Items are scored / sorted intelligently depending on where a match is found in the string (how close to the beginning) and what percentage of the string matches.
* **Multi-field sorting.**<br>When scores aren't enough to go by – like when getting results for an empty query – it can sort by one or more fields. For example, sort by a person's first name and last name without actually merging the properties to a single string.
* **Nested properties.**<br>Allows to search and sort on nested properties so you can perform search on complex objects without flattening them simply by using dot-notation to reference fields (ie. `nested.property`).
* **Weighted fields.**<br>Assign weights to multi-field configurations for more control of search results
* **Field searching**<br>Search for values in one field with "field-name:query"


```sh
$ npm install @orchidjs/sifter # node.js
```

## Usage

```js
import {Sifter} from '@orchidjs/sifter';

var sifter = new Sifter([
	{title: 'Annapurna I', location: 'Nepal', continent: 'Asia'},
	{title: 'Annapurna II', location: 'Nepal', continent: 'Asia'},
	{title: 'Annapurna III', location: 'Nepal', continent: 'Asia'},
	{title: 'Eiger', location: 'Switzerland', continent: 'Europe'},
	{title: 'Everest', location: 'Nepal', continent: 'Asia'},
	{title: 'Gannett', location: 'Wyoming', continent: 'North America'},
	{title: 'Denali', location: 'Alaska', continent: 'North America'}
]);

var result = sifter.search('anna', {
	fields: [{field:'title',weight:2}, {field:'location'}, {field:'continent',weight:0.5}],
	sort: [{field: 'title', direction: 'asc'}],
	limit: 3
});
```

Seaching will provide back meta information and an "items" array that contains objects with the index (or key, if searching a hash) and a score that represents how good of a match the item was. Items that did not match will not be returned.

```js
{ score: 0.5757575757575758, id: 0 },
{ score: 0.5555555555555555, id: 1 },
{ score: 0.5384615384615384, id: 2 }
```

Items are sorted by best-match, primarily. If two or more items have the same score (which will be the case when searching with an empty string), it will resort to the fields listed in the "sort" option.

The full result comes back in the format of:

```js
{
	options: {
		fields: [{field:"title",weight:2},{field:"location",weight:1}, {field:"continent",weight:0.5}],
		sort: [
			{field: "title", direction: "asc"}
		],
		limit: 3
	},
	query: "anna",
	tokens: [{
		string: "anna",
		regex: /[aÀÁÂÃÄÅàáâãäå][nÑñ][nÑñ][aÀÁÂÃÄÅàáâãäå]/
	}],
	total: 3,
	items: [
		{ score: 0.5757575757575758, id: 0 },
     	{ score: 0.5555555555555555, id: 1 },
     	{ score: 0.5384615384615384, id: 2 }
	]
}
```

### API

#### #.search(query, options)

Performs a search for `query` with the provided `options`.

<table width="100%">
	<tr>
		<th align="left">Option</th>
		<th align="left">Type</th>
		<th align="left" width="100%">Description</th>
	</tr>
	<tr>
		<td valign="top"><code>fields</code></td>
		<td valign="top">array</td>
		<td valign="top">An array of property names and optional weights to be searched.

```js
fields: [
	{field:"title",weight:2},
	{field:"location",weight:1},
	{field:"continent",weight:0.5}
],
```
</td>
	</tr>
	<tr>
		<td valign="top"><code>limit</code></td>
		<td valign="top">integer</td>
		<td valign="top">The maximum number of results to return.</td>
	</tr>
	<tr>
		<td valign="top"><code>sort</code></td>
		<td valign="top">array|function</td>
		<td valign="top">
		An array of fields to sort by.
		Each item should be an object containing at least a <code>"field"</code> property. Optionally, <code>direction</code> can be set to <code>"asc"</code> or <code>"desc"</code>.
		The order of the array defines the sort precedence.
		<br/><br/>		
		Unless present, a special <code>"$score"</code> property will be automatically added to the beginning of the sort list.
		This will make results sorted primarily by match quality (descending).
		<br/><br/>
		Alternatively, you can define a callback function to handle sorting. For example:

```js
sort: function(a,b){
	var item_a = this.items[a.id];
	var item_b = this.items[b.id];
	return item_a.fielda.localeCompare(item_b.fielda);
},
```
</td>
	</tr>
	<tr>
		<td valign="top"><code>sort_empty</code></td>
		<td valign="top">array</td>
		<td valign="top">Optional. Defaults to "sort" setting. If provided, these sort settings are used when no query is present.</td>
	</tr>
	<tr>
		<td valign="top"><code>filter</code></td>
		<td valign="top">boolean</td>
		<td valign="top">If <code>false</code>, items with a score of zero will <em>not</em> be filtered out of the result-set.</td>
	</tr>
	<tr>
		<td valign="top"><code>conjunction</code></td>
		<td valign="top">string</td>
		<td valign="top">Determines how multiple search terms are joined (<code>"and"</code> or <code>"or"</code>, defaults to <code>"or"</code>).</td>
	</tr>
	<tr>
		<td valign="top"><code>nesting</code></td>
		<td valign="top">boolean</td>
		<td valign="top">If <code>true</code>, nested fields will be available for search and sort using dot-notation to reference them (e.g. <code>nested.property</code>)<br><em>Warning: can reduce performance</em></td>
	</tr>
	<tr>
		<td valign="top"><code>respect_word_boundaries</code></td>
		<td valign="top">boolean</td>
		<td valign="top">If <code>true</code>, matches only at start of word boundaries (e.g. the beginning of words, instead of matching the middle of words)</td>
	</tr>
</table>


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

Copyright &copy; 2013–2021 [Contributors](https://github.com/orchidjs/sifter.js/graphs/contributors)

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at: http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
