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
import { getPattern } from '@orchidjs/unicode-variants';
import * as T from './types';
declare class Sifter {
    items: any;
    settings: T.Settings;
    /**
     * Textually searches arrays and hashes of objects
     * by property (or multiple properties). Designed
     * specifically for autocomplete.
     *
     */
    constructor(items: any, settings: T.Settings);
    /**
     * Splits a search string into an array of individual
     * regexps to be used to match results.
     *
     */
    tokenize(query: string, respect_word_boundaries?: boolean, weights?: T.Weights): T.Token[];
    /**
     * Returns a function to be used to score individual results.
     *
     * Good matches will have a higher score than poor matches.
     * If an item is not a match, 0 will be returned by the function.
     *
     * @returns {T.ScoreFn}
     */
    getScoreFunction(query: string, options: T.UserOptions): (data: {}) => number;
    /**
     * @returns {T.ScoreFn}
     *
     */
    _getScoreFunction(search: T.PrepareObj): (data: {}) => number;
    /**
     * Returns a function that can be used to compare two
     * results, for sorting purposes. If no sorting should
     * be performed, `null` will be returned.
     *
     * @return function(a,b)
     */
    getSortFunction(query: string, options: T.UserOptions): ((a: T.ResultItem, b: T.ResultItem) => number) | null;
    _getSortFunction(search: T.PrepareObj): ((a: T.ResultItem, b: T.ResultItem) => number) | null;
    /**
     * Parses a search query and returns an object
     * with tokens and fields ready to be populated
     * with results.
     *
     */
    prepareSearch(query: string, optsUser: T.UserOptions): T.PrepareObj;
    /**
     * Searches through all items and returns a sorted array of matches.
     *
     */
    search(query: string, options: T.UserOptions): T.PrepareObj;
}
export { Sifter, scoreValue, getAttr, getAttrNesting, propToArray, iterate, cmp, getPattern };
