/**
 * sifter.js
 * Copyright (c) 2013–2020 Brian Reavis & contributors
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
 * file except in compliance with the License. You may obtain a copy of the License at:
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under
 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
 * ANY KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 *
 * @author Brian Reavis <brian@thirdroute.com>
 */

import { scoreValue, getAttr, getAttrNesting, propToArray, iterate, cmp } from './utils';
import { getPattern, escape_regex } from '@orchidjs/unicode-variants';
import * as T from './types';

class Sifter{

	public items: any; // []|{};
	public settings: T.Settings;

	/**
	 * Textually searches arrays and hashes of objects
	 * by property (or multiple properties). Designed
	 * specifically for autocomplete.
	 *
	 */
	constructor(items:any, settings:T.Settings) {
		this.items = items;
		this.settings = settings || {diacritics: true};
	};

	/**
	 * Splits a search string into an array of individual
	 * regexps to be used to match results.
	 *
	 */
	tokenize(query:string, respect_word_boundaries?:boolean, weights?:T.Weights ):T.Token[] {
		if (!query || !query.length) return [];

		const tokens:T.Token[]	= [];
		const words				= query.split(/\s+/);
		var field_regex:RegExp;

		if( weights ){
			field_regex = new RegExp( '^('+ Object.keys(weights).map(escape_regex).join('|')+')\:(.*)$');
		}

		words.forEach((word:string) => {
			let field_match;
			let field:null|string	= null;
			let regex:null|string	= null;

			// look for "field:query" tokens
			if( field_regex && (field_match = word.match(field_regex)) ){
				field	= field_match[1]!;
				word	= field_match[2]!;
			}

			if( word.length > 0 ){
				if( this.settings.diacritics ){
					regex = getPattern(word) || null;
				}else{
					regex = escape_regex(word);
				}
				if( regex && respect_word_boundaries ) regex = "\\b"+regex;
			}

			tokens.push({
				string : word,
				regex  : regex ? new RegExp(regex,'iu') : null,
				field  : field,
			});
		});

		return tokens;
	};


	/**
	 * Returns a function to be used to score individual results.
	 *
	 * Good matches will have a higher score than poor matches.
	 * If an item is not a match, 0 will be returned by the function.
	 *
	 * @returns {T.ScoreFn}
	 */
	getScoreFunction(query:string, options:T.UserOptions ){
		var search = this.prepareSearch(query, options);
		return this._getScoreFunction(search);
	}

	/**
	 * @returns {T.ScoreFn}
	 *
	 */
	_getScoreFunction(search:T.PrepareObj ){
		const tokens		= search.tokens,
		token_count			= tokens.length;

		if (!token_count) {
			return function() { return 0; };
		}

		const fields	= search.options.fields,
		weights			= search.weights,
		field_count		= fields.length,
		getAttrFn		= search.getAttrFn;

		if (!field_count) {
			return function() { return 1; };
		}


		/**
		 * Calculates the score of an object
		 * against the search query.
		 *
		 */
		const scoreObject = (function() {


			if (field_count === 1) {
				return function(token:T.Token, data:{}) {
					const field = fields[0]!.field;
					return scoreValue(getAttrFn(data, field), token, weights[field]||1);
				};
			}

			return function(token:T.Token, data:{}) {
				var sum = 0;

				// is the token specific to a field?
				if( token.field ){

					const value = getAttrFn(data, token.field);

					if( !token.regex && value ){
						sum += (1/field_count);
					}else{
						sum += scoreValue(value, token, 1);
					}



				}else{
					iterate(weights, (weight:number, field:string) => {
						sum += scoreValue(getAttrFn(data, field), token, weight);
					});
				}

				return sum / field_count;
			};
		})();

		if (token_count === 1) {
			return function(data:{}) {
				return scoreObject(tokens[0]!, data);
			};
		}

		if (search.options.conjunction === 'and') {
			return function(data:{}) {
				var score, sum = 0;
				for( let token of tokens){
					score = scoreObject(token, data);
					if (score <= 0) return 0;
					sum += score;
				}
				return sum / token_count;
			};
		} else {
			return function(data:{}) {
				var sum = 0;
				iterate(tokens,(token:T.Token)=>{
					sum += scoreObject(token, data);
				});
				return sum / token_count;
			};
		}
	};

	/**
	 * Returns a function that can be used to compare two
	 * results, for sorting purposes. If no sorting should
	 * be performed, `null` will be returned.
	 *
	 * @return function(a,b)
	 */
	getSortFunction(query:string, options:T.UserOptions) {
		var search  = this.prepareSearch(query, options);
		return this._getSortFunction(search);
	}

	_getSortFunction(search:T.PrepareObj){
		var implicit_score,
		sort_flds:T.Sort[]	= [];

		const self	= this,
		options		= search.options,
		sort		= (!search.query && options.sort_empty) ? options.sort_empty : options.sort;


		if( typeof sort == 'function' ){
			return sort.bind(this);
		}

		/**
		 * Fetches the specified sort field value
		 * from a search result item.
		 *
		 */
		const get_field = function(name:string, result:T.ResultItem):string|number {
			if (name === '$score') return result.score;
			return search.getAttrFn(self.items[result.id], name);
		};

		// parse options
		if (sort) {
			for( let s of sort ){
				if (search.query || s.field !== '$score') {
					sort_flds.push(s);
				}
			}
		}

		// the "$score" field is implied to be the primary
		// sort field, unless it's manually specified
		if (search.query) {
			implicit_score = true;
			for( let fld of sort_flds ){
				if( fld.field === '$score' ){
					implicit_score = false;
					break;
				}
			}
			if (implicit_score) {
				sort_flds.unshift({field: '$score', direction: 'desc'});
			}

		// without a search.query, all items will have the same score
		} else {
			sort_flds = sort_flds.filter((fld) => fld.field !== '$score' );
		}


		// build function
		const sort_flds_count = sort_flds.length;
		if (!sort_flds_count) {
			return null;
		}

		return function(a:T.ResultItem, b:T.ResultItem) {
			var result, field;
			for( let sort_fld of sort_flds ){
				field = sort_fld.field;

				let	multiplier = sort_fld.direction === 'desc' ? -1 : 1;

				result = multiplier * cmp(
					get_field(field, a),
					get_field(field, b)
				);
				if (result) return result;
			}
			return 0;
		};

	};

	/**
	 * Parses a search query and returns an object
	 * with tokens and fields ready to be populated
	 * with results.
	 *
	 */
	prepareSearch(query:string, optsUser:T.UserOptions):T.PrepareObj {
		const weights:T.Weights = {};
		var options		= Object.assign({},optsUser) as T.Options;

		propToArray(options,'sort');
		propToArray(options,'sort_empty');

		// convert fields to new format
		if( options.fields ){
			propToArray(options,'fields');
			const fields:T.Field[] = [];
			options.fields.forEach((field:string|T.Field) => {
				if( typeof field == 'string' ){
					field = {field:field,weight:1};
				}
				fields.push(field);
				weights[field.field] = ('weight' in field) ? field.weight : 1;
			});
			options.fields = fields;
		}


		return {
			options		: options as T.Options,
			query		: query.toLowerCase().trim(),
			tokens		: this.tokenize(query, options.respect_word_boundaries, weights),
			total		: 0,
			items		: [],
			weights		: weights,
			getAttrFn	: (options.nesting) ? getAttrNesting : getAttr,
		};
	};

	/**
	 * Searches through all items and returns a sorted array of matches.
	 *
	 */
	search(query:string, options:T.UserOptions) : T.PrepareObj {
		var self = this, score, search: T.PrepareObj;

		search  = this.prepareSearch(query, options);
		options = search.options;
		query   = search.query;

		// generate result scoring function
		const fn_score:T.ScoreFn = options.score || self._getScoreFunction(search);

		// perform search and sort
		if (query.length) {
			iterate(self.items, (item:T.ResultItem, id:string|number) => {
				score = fn_score(item);
				if (options.filter === false || score > 0) {
					search.items.push({'score': score, 'id': id});
				}
			});
		} else {
			iterate(self.items, (_:T.ResultItem, id:string|number) => {
				search.items.push({'score': 1, 'id': id});
			});
		}

		const fn_sort = self._getSortFunction(search);
		if (fn_sort) search.items.sort(fn_sort);

		// apply limits
		search.total = search.items.length;
		if (typeof options.limit === 'number') {
			search.items = search.items.slice(0, options.limit);
		}

		return search;
	};
}

export { Sifter, scoreValue, getAttr, getAttrNesting, propToArray, iterate, cmp, getPattern }
