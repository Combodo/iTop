/*! sifter.js | https://github.com/orchidjs/sifter.js | Apache License (v2) */
(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports) :
  typeof define === 'function' && define.amd ? define(['exports'], factory) :
  (global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory(global.sifter = {}));
}(this, (function (exports) { 'use strict';

  /*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */

  /**
   * Convert array of strings to a regular expression
   *	ex ['ab','a'] => (?:ab|a)
   * 	ex ['a','b'] => [ab]
   * @param {string[]} chars
   * @return {string}
   */
  const arrayToPattern = chars => {
    chars = chars.filter(Boolean);

    if (chars.length < 2) {
      return chars[0] || '';
    }

    return maxValueLength(chars) == 1 ? '[' + chars.join('') + ']' : '(?:' + chars.join('|') + ')';
  };
  /**
   * @param {string[]} array
   * @return {string}
   */


  const sequencePattern = array => {
    if (!hasDuplicates(array)) {
      return array.join('');
    }

    let pattern = '';
    let prev_char_count = 0;

    const prev_pattern = () => {
      if (prev_char_count > 1) {
        pattern += '{' + prev_char_count + '}';
      }
    };

    array.forEach((char, i) => {
      if (char === array[i - 1]) {
        prev_char_count++;
        return;
      }

      prev_pattern();
      pattern += char;
      prev_char_count = 1;
    });
    prev_pattern();
    return pattern;
  };
  /**
   * Convert array of strings to a regular expression
   *	ex ['ab','a'] => (?:ab|a)
   * 	ex ['a','b'] => [ab]
   * @param {Set<string>} chars
   * @return {string}
   */


  const setToPattern = chars => {
    let array = toArray(chars);
    return arrayToPattern(array);
  };
  /**
   *
   * https://stackoverflow.com/questions/7376598/in-javascript-how-do-i-check-if-an-array-has-duplicate-values
   * @param {any[]} array
   */


  const hasDuplicates = array => {
    return new Set(array).size !== array.length;
  };
  /**
   * https://stackoverflow.com/questions/63006601/why-does-u-throw-an-invalid-escape-error
   * @param {string} str
   * @return {string}
   */


  const escape_regex = str => {
    return (str + '').replace(/([\$\(-\+\.\?\[-\^\{-\}])/g, '\\$1');
  };
  /**
   * Return the max length of array values
   * @param {string[]} array
   *
   */


  const maxValueLength = array => {
    return array.reduce((longest, value) => Math.max(longest, unicodeLength(value)), 0);
  };
  /**
   * @param {string} str
   */


  const unicodeLength = str => {
    return toArray(str).length;
  };
  /**
   * @param {any} p
   * @return {any[]}
   */


  const toArray = p => Array.from(p);

  /*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */

  /**
   * Get all possible combinations of substrings that add up to the given string
   * https://stackoverflow.com/questions/30169587/find-all-the-combination-of-substrings-that-add-up-to-the-given-string
   * @param {string} input
   * @return {string[][]}
   */
  const allSubstrings = input => {
    if (input.length === 1) return [[input]];
    /** @type {string[][]} */

    let result = [];
    const start = input.substring(1);
    const suba = allSubstrings(start);
    suba.forEach(function (subresult) {
      let tmp = subresult.slice(0);
      tmp[0] = input.charAt(0) + tmp[0];
      result.push(tmp);
      tmp = subresult.slice(0);
      tmp.unshift(input.charAt(0));
      result.push(tmp);
    });
    return result;
  };

  /*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */
  /**
   * @typedef {{[key:string]:string}} TUnicodeMap
   * @typedef {{[key:string]:Set<string>}} TUnicodeSets
   * @typedef {[[number,number]]} TCodePoints
   * @typedef {{folded:string,composed:string,code_point:number}} TCodePointObj
   * @typedef {{start:number,end:number,length:number,substr:string}} TSequencePart
   */

  /** @type {TCodePoints} */

  const code_points = [[0, 65535]];
  const accent_pat = '[\u0300-\u036F\u{b7}\u{2be}\u{2bc}]';
  /** @type {TUnicodeMap} */

  let unicode_map;
  /** @type {RegExp} */

  let multi_char_reg;
  const max_char_length = 3;
  /** @type {TUnicodeMap} */

  const latin_convert = {};
  /** @type {TUnicodeMap} */

  const latin_condensed = {
    '/': '⁄∕',
    '0': '߀',
    "a": "ⱥɐɑ",
    "aa": "ꜳ",
    "ae": "æǽǣ",
    "ao": "ꜵ",
    "au": "ꜷ",
    "av": "ꜹꜻ",
    "ay": "ꜽ",
    "b": "ƀɓƃ",
    "c": "ꜿƈȼↄ",
    "d": "đɗɖᴅƌꮷԁɦ",
    "e": "ɛǝᴇɇ",
    "f": "ꝼƒ",
    "g": "ǥɠꞡᵹꝿɢ",
    "h": "ħⱨⱶɥ",
    "i": "ɨı",
    "j": "ɉȷ",
    "k": "ƙⱪꝁꝃꝅꞣ",
    "l": "łƚɫⱡꝉꝇꞁɭ",
    "m": "ɱɯϻ",
    "n": "ꞥƞɲꞑᴎлԉ",
    "o": "øǿɔɵꝋꝍᴑ",
    "oe": "œ",
    "oi": "ƣ",
    "oo": "ꝏ",
    "ou": "ȣ",
    "p": "ƥᵽꝑꝓꝕρ",
    "q": "ꝗꝙɋ",
    "r": "ɍɽꝛꞧꞃ",
    "s": "ßȿꞩꞅʂ",
    "t": "ŧƭʈⱦꞇ",
    "th": "þ",
    "tz": "ꜩ",
    "u": "ʉ",
    "v": "ʋꝟʌ",
    "vy": "ꝡ",
    "w": "ⱳ",
    "y": "ƴɏỿ",
    "z": "ƶȥɀⱬꝣ",
    "hv": "ƕ"
  };

  for (let latin in latin_condensed) {
    let unicode = latin_condensed[latin] || '';

    for (let i = 0; i < unicode.length; i++) {
      let char = unicode.substring(i, i + 1);
      latin_convert[char] = latin;
    }
  }

  const convert_pat = new RegExp(Object.keys(latin_convert).join('|') + '|' + accent_pat, 'gu');
  /**
   * Initialize the unicode_map from the give code point ranges
   *
   * @param {TCodePoints=} _code_points
   */

  const initialize = _code_points => {
    if (unicode_map !== undefined) return;
    unicode_map = generateMap(_code_points || code_points);
  };
  /**
   * Helper method for normalize a string
   * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/normalize
   * @param {string} str
   * @param {string} form
   */


  const normalize = (str, form = 'NFKD') => str.normalize(form);
  /**
   * Remove accents without reordering string
   * calling str.normalize('NFKD') on \u{594}\u{595}\u{596} becomes \u{596}\u{594}\u{595}
   * via https://github.com/krisk/Fuse/issues/133#issuecomment-318692703
   * @param {string} str
   * @return {string}
   */


  const asciifold = str => {
    return toArray(str).reduce(
    /**
     * @param {string} result
     * @param {string} char
     */
    (result, char) => {
      return result + _asciifold(char);
    }, '');
  };
  /**
   * @param {string} str
   * @return {string}
   */


  const _asciifold = str => {
    str = normalize(str).toLowerCase().replace(convert_pat,
    /** @type {string} */
    char => {
      return latin_convert[char] || '';
    }); //return str;

    return normalize(str, 'NFC');
  };
  /**
   * Generate a list of unicode variants from the list of code points
   * @param {TCodePoints} code_points
   * @yield {TCodePointObj}
   */


  function* generator(code_points) {
    for (const [code_point_min, code_point_max] of code_points) {
      for (let i = code_point_min; i <= code_point_max; i++) {
        let composed = String.fromCharCode(i);
        let folded = asciifold(composed);

        if (folded == composed.toLowerCase()) {
          continue;
        } // skip when folded is a string longer than 3 characters long
        // bc the resulting regex patterns will be long
        // eg:
        // folded صلى الله عليه وسلم length 18 code point 65018
        // folded جل جلاله length 8 code point 65019


        if (folded.length > max_char_length) {
          continue;
        }

        if (folded.length == 0) {
          continue;
        }

        yield {
          folded: folded,
          composed: composed,
          code_point: i
        };
      }
    }
  }
  /**
   * Generate a unicode map from the list of code points
   * @param {TCodePoints} code_points
   * @return {TUnicodeSets}
   */


  const generateSets = code_points => {
    /** @type {{[key:string]:Set<string>}} */
    const unicode_sets = {};
    /**
     * @param {string} folded
     * @param {string} to_add
     */

    const addMatching = (folded, to_add) => {
      /** @type {Set<string>} */
      const folded_set = unicode_sets[folded] || new Set();
      const patt = new RegExp('^' + setToPattern(folded_set) + '$', 'iu');

      if (to_add.match(patt)) {
        return;
      }

      folded_set.add(escape_regex(to_add));
      unicode_sets[folded] = folded_set;
    };

    for (let value of generator(code_points)) {
      addMatching(value.folded, value.folded);
      addMatching(value.folded, value.composed);
    }

    return unicode_sets;
  };
  /**
   * Generate a unicode map from the list of code points
   * ae => (?:(?:ae|Æ|Ǽ|Ǣ)|(?:A|Ⓐ|Ａ...)(?:E|ɛ|Ⓔ...))
   *
   * @param {TCodePoints} code_points
   * @return {TUnicodeMap}
   */


  const generateMap = code_points => {
    /** @type {TUnicodeSets} */
    const unicode_sets = generateSets(code_points);
    /** @type {TUnicodeMap} */

    const unicode_map = {};
    /** @type {string[]} */

    let multi_char = [];

    for (let folded in unicode_sets) {
      let set = unicode_sets[folded];

      if (set) {
        unicode_map[folded] = setToPattern(set);
      }

      if (folded.length > 1) {
        multi_char.push(escape_regex(folded));
      }
    }

    multi_char.sort((a, b) => b.length - a.length);
    const multi_char_patt = arrayToPattern(multi_char);
    multi_char_reg = new RegExp('^' + multi_char_patt, 'u');
    return unicode_map;
  };
  /**
   * Map each element of an array from it's folded value to all possible unicode matches
   * @param {string[]} strings
   * @param {number} min_replacement
   * @return {string}
   */


  const mapSequence = (strings, min_replacement = 1) => {
    let chars_replaced = 0;
    strings = strings.map(str => {
      if (unicode_map[str]) {
        chars_replaced += str.length;
      }

      return unicode_map[str] || str;
    });

    if (chars_replaced >= min_replacement) {
      return sequencePattern(strings);
    }

    return '';
  };
  /**
   * Convert a short string and split it into all possible patterns
   * Keep a pattern only if min_replacement is met
   *
   * 'abc'
   * 		=> [['abc'],['ab','c'],['a','bc'],['a','b','c']]
   *		=> ['abc-pattern','ab-c-pattern'...]
   *
   *
   * @param {string} str
   * @param {number} min_replacement
   * @return {string}
   */


  const substringsToPattern = (str, min_replacement = 1) => {
    min_replacement = Math.max(min_replacement, str.length - 1);
    return arrayToPattern(allSubstrings(str).map(sub_pat => {
      return mapSequence(sub_pat, min_replacement);
    }));
  };
  /**
   * Convert an array of sequences into a pattern
   * [{start:0,end:3,length:3,substr:'iii'}...] => (?:iii...)
   *
   * @param {Sequence[]} sequences
   * @param {boolean} all
   */


  const sequencesToPattern = (sequences, all = true) => {
    let min_replacement = sequences.length > 1 ? 1 : 0;
    return arrayToPattern(sequences.map(sequence => {
      let seq = [];
      const len = all ? sequence.length() : sequence.length() - 1;

      for (let j = 0; j < len; j++) {
        seq.push(substringsToPattern(sequence.substrs[j] || '', min_replacement));
      }

      return sequencePattern(seq);
    }));
  };
  /**
   * Return true if the sequence is already in the sequences
   * @param {Sequence} needle_seq
   * @param {Sequence[]} sequences
   */


  const inSequences = (needle_seq, sequences) => {
    for (const seq of sequences) {
      if (seq.start != needle_seq.start || seq.end != needle_seq.end) {
        continue;
      }

      if (seq.substrs.join('') !== needle_seq.substrs.join('')) {
        continue;
      }

      let needle_parts = needle_seq.parts;
      /**
       * @param {TSequencePart} part
       */

      const filter = part => {
        for (const needle_part of needle_parts) {
          if (needle_part.start === part.start && needle_part.substr === part.substr) {
            return false;
          }

          if (part.length == 1 || needle_part.length == 1) {
            continue;
          } // check for overlapping parts
          // a = ['::=','==']
          // b = ['::','===']
          // a = ['r','sm']
          // b = ['rs','m']


          if (part.start < needle_part.start && part.end > needle_part.start) {
            return true;
          }

          if (needle_part.start < part.start && needle_part.end > part.start) {
            return true;
          }
        }

        return false;
      };

      let filtered = seq.parts.filter(filter);

      if (filtered.length > 0) {
        continue;
      }

      return true;
    }

    return false;
  };

  class Sequence {
    constructor() {
      /** @type {TSequencePart[]} */
      this.parts = [];
      /** @type {string[]} */

      this.substrs = [];
      this.start = 0;
      this.end = 0;
    }
    /**
     * @param {TSequencePart|undefined} part
     */


    add(part) {
      if (part) {
        this.parts.push(part);
        this.substrs.push(part.substr);
        this.start = Math.min(part.start, this.start);
        this.end = Math.max(part.end, this.end);
      }
    }

    last() {
      return this.parts[this.parts.length - 1];
    }

    length() {
      return this.parts.length;
    }
    /**
     * @param {number} position
     * @param {TSequencePart} last_piece
     */


    clone(position, last_piece) {
      let clone = new Sequence();
      let parts = JSON.parse(JSON.stringify(this.parts));
      let last_part = parts.pop();

      for (const part of parts) {
        clone.add(part);
      }

      let last_substr = last_piece.substr.substring(0, position - last_part.start);
      let clone_last_len = last_substr.length;
      clone.add({
        start: last_part.start,
        end: last_part.start + clone_last_len,
        length: clone_last_len,
        substr: last_substr
      });
      return clone;
    }

  }
  /**
   * Expand a regular expression pattern to include unicode variants
   * 	eg /a/ becomes /aⓐａẚàáâầấẫẩãāăằắẵẳȧǡäǟảåǻǎȁȃạậặḁąⱥɐɑAⒶＡÀÁÂẦẤẪẨÃĀĂẰẮẴẲȦǠÄǞẢÅǺǍȀȂẠẬẶḀĄȺⱯ/
   *
   * Issue:
   *  ﺊﺋ [ 'ﺊ = \\u{fe8a}', 'ﺋ = \\u{fe8b}' ]
   *	becomes:	ئئ [ 'ي = \\u{64a}', 'ٔ = \\u{654}', 'ي = \\u{64a}', 'ٔ = \\u{654}' ]
   *
   *	İĲ = IIJ = ⅡJ
   *
   * 	1/2/4
   *
   * @param {string} str
   * @return {string|undefined}
   */


  const getPattern = str => {
    initialize();
    str = asciifold(str);
    let pattern = '';
    let sequences = [new Sequence()];

    for (let i = 0; i < str.length; i++) {
      let substr = str.substring(i);
      let match = substr.match(multi_char_reg);
      const char = str.substring(i, i + 1);
      const match_str = match ? match[0] : null; // loop through sequences
      // add either the char or multi_match

      let overlapping = [];
      let added_types = new Set();

      for (const sequence of sequences) {
        const last_piece = sequence.last();

        if (!last_piece || last_piece.length == 1 || last_piece.end <= i) {
          // if we have a multi match
          if (match_str) {
            const len = match_str.length;
            sequence.add({
              start: i,
              end: i + len,
              length: len,
              substr: match_str
            });
            added_types.add('1');
          } else {
            sequence.add({
              start: i,
              end: i + 1,
              length: 1,
              substr: char
            });
            added_types.add('2');
          }
        } else if (match_str) {
          let clone = sequence.clone(i, last_piece);
          const len = match_str.length;
          clone.add({
            start: i,
            end: i + len,
            length: len,
            substr: match_str
          });
          overlapping.push(clone);
        } else {
          // don't add char
          // adding would create invalid patterns: 234 => [2,34,4]
          added_types.add('3');
        }
      } // if we have overlapping


      if (overlapping.length > 0) {
        // ['ii','iii'] before ['i','i','iii']
        overlapping = overlapping.sort((a, b) => {
          return a.length() - b.length();
        });

        for (let clone of overlapping) {
          // don't add if we already have an equivalent sequence
          if (inSequences(clone, sequences)) {
            continue;
          }

          sequences.push(clone);
        }

        continue;
      } // if we haven't done anything unique
      // clean up the patterns
      // helps keep patterns smaller
      // if str = 'r₨㎧aarss', pattern will be 446 instead of 655


      if (i > 0 && added_types.size == 1 && !added_types.has('3')) {
        pattern += sequencesToPattern(sequences, false);
        let new_seq = new Sequence();
        const old_seq = sequences[0];

        if (old_seq) {
          new_seq.add(old_seq.last());
        }

        sequences = [new_seq];
      }
    }

    pattern += sequencesToPattern(sequences, true);
    return pattern;
  };

  /**
   * A property getter resolving dot-notation
   * @param  {Object}  obj     The root object to fetch property on
   * @param  {String}  name    The optionally dotted property name to fetch
   * @return {Object}          The resolved property value
   */
  const getAttr = (obj, name) => {
    if (!obj) return;
    return obj[name];
  };
  /**
   * A property getter resolving dot-notation
   * @param  {Object}  obj     The root object to fetch property on
   * @param  {String}  name    The optionally dotted property name to fetch
   * @return {Object}          The resolved property value
   */

  const getAttrNesting = (obj, name) => {
    if (!obj) return;
    var part,
        names = name.split(".");

    while ((part = names.shift()) && (obj = obj[part]));

    return obj;
  };
  /**
   * Calculates how close of a match the
   * given value is against a search token.
   *
   */

  const scoreValue = (value, token, weight) => {
    var score, pos;
    if (!value) return 0;
    value = value + '';
    if (token.regex == null) return 0;
    pos = value.search(token.regex);
    if (pos === -1) return 0;
    score = token.string.length / value.length;
    if (pos === 0) score += 0.5;
    return score * weight;
  };
  /**
   * Cast object property to an array if it exists and has a value
   *
   */

  const propToArray = (obj, key) => {
    var value = obj[key];
    if (typeof value == 'function') return value;

    if (value && !Array.isArray(value)) {
      obj[key] = [value];
    }
  };
  /**
   * Iterates over arrays and hashes.
   *
   * ```
   * iterate(this.items, function(item, id) {
   *    // invoked for each item
   * });
   * ```
   *
   */

  const iterate = (object, callback) => {
    if (Array.isArray(object)) {
      object.forEach(callback);
    } else {
      for (var key in object) {
        if (object.hasOwnProperty(key)) {
          callback(object[key], key);
        }
      }
    }
  };
  const cmp = (a, b) => {
    if (typeof a === 'number' && typeof b === 'number') {
      return a > b ? 1 : a < b ? -1 : 0;
    }

    a = asciifold(a + '').toLowerCase();
    b = asciifold(b + '').toLowerCase();
    if (a > b) return 1;
    if (b > a) return -1;
    return 0;
  };

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

  class Sifter {
    // []|{};

    /**
     * Textually searches arrays and hashes of objects
     * by property (or multiple properties). Designed
     * specifically for autocomplete.
     *
     */
    constructor(items, settings) {
      this.items = void 0;
      this.settings = void 0;
      this.items = items;
      this.settings = settings || {
        diacritics: true
      };
    }

    /**
     * Splits a search string into an array of individual
     * regexps to be used to match results.
     *
     */
    tokenize(query, respect_word_boundaries, weights) {
      if (!query || !query.length) return [];
      const tokens = [];
      const words = query.split(/\s+/);
      var field_regex;

      if (weights) {
        field_regex = new RegExp('^(' + Object.keys(weights).map(escape_regex).join('|') + ')\:(.*)$');
      }

      words.forEach(word => {
        let field_match;
        let field = null;
        let regex = null; // look for "field:query" tokens

        if (field_regex && (field_match = word.match(field_regex))) {
          field = field_match[1];
          word = field_match[2];
        }

        if (word.length > 0) {
          if (this.settings.diacritics) {
            regex = getPattern(word) || null;
          } else {
            regex = escape_regex(word);
          }

          if (regex && respect_word_boundaries) regex = "\\b" + regex;
        }

        tokens.push({
          string: word,
          regex: regex ? new RegExp(regex, 'iu') : null,
          field: field
        });
      });
      return tokens;
    }

    /**
     * Returns a function to be used to score individual results.
     *
     * Good matches will have a higher score than poor matches.
     * If an item is not a match, 0 will be returned by the function.
     *
     * @returns {T.ScoreFn}
     */
    getScoreFunction(query, options) {
      var search = this.prepareSearch(query, options);
      return this._getScoreFunction(search);
    }
    /**
     * @returns {T.ScoreFn}
     *
     */


    _getScoreFunction(search) {
      const tokens = search.tokens,
            token_count = tokens.length;

      if (!token_count) {
        return function () {
          return 0;
        };
      }

      const fields = search.options.fields,
            weights = search.weights,
            field_count = fields.length,
            getAttrFn = search.getAttrFn;

      if (!field_count) {
        return function () {
          return 1;
        };
      }
      /**
       * Calculates the score of an object
       * against the search query.
       *
       */


      const scoreObject = function () {
        if (field_count === 1) {
          return function (token, data) {
            const field = fields[0].field;
            return scoreValue(getAttrFn(data, field), token, weights[field] || 1);
          };
        }

        return function (token, data) {
          var sum = 0; // is the token specific to a field?

          if (token.field) {
            const value = getAttrFn(data, token.field);

            if (!token.regex && value) {
              sum += 1 / field_count;
            } else {
              sum += scoreValue(value, token, 1);
            }
          } else {
            iterate(weights, (weight, field) => {
              sum += scoreValue(getAttrFn(data, field), token, weight);
            });
          }

          return sum / field_count;
        };
      }();

      if (token_count === 1) {
        return function (data) {
          return scoreObject(tokens[0], data);
        };
      }

      if (search.options.conjunction === 'and') {
        return function (data) {
          var score,
              sum = 0;

          for (let token of tokens) {
            score = scoreObject(token, data);
            if (score <= 0) return 0;
            sum += score;
          }

          return sum / token_count;
        };
      } else {
        return function (data) {
          var sum = 0;
          iterate(tokens, token => {
            sum += scoreObject(token, data);
          });
          return sum / token_count;
        };
      }
    }

    /**
     * Returns a function that can be used to compare two
     * results, for sorting purposes. If no sorting should
     * be performed, `null` will be returned.
     *
     * @return function(a,b)
     */
    getSortFunction(query, options) {
      var search = this.prepareSearch(query, options);
      return this._getSortFunction(search);
    }

    _getSortFunction(search) {
      var implicit_score,
          sort_flds = [];
      const self = this,
            options = search.options,
            sort = !search.query && options.sort_empty ? options.sort_empty : options.sort;

      if (typeof sort == 'function') {
        return sort.bind(this);
      }
      /**
       * Fetches the specified sort field value
       * from a search result item.
       *
       */


      const get_field = function get_field(name, result) {
        if (name === '$score') return result.score;
        return search.getAttrFn(self.items[result.id], name);
      }; // parse options


      if (sort) {
        for (let s of sort) {
          if (search.query || s.field !== '$score') {
            sort_flds.push(s);
          }
        }
      } // the "$score" field is implied to be the primary
      // sort field, unless it's manually specified


      if (search.query) {
        implicit_score = true;

        for (let fld of sort_flds) {
          if (fld.field === '$score') {
            implicit_score = false;
            break;
          }
        }

        if (implicit_score) {
          sort_flds.unshift({
            field: '$score',
            direction: 'desc'
          });
        } // without a search.query, all items will have the same score

      } else {
        sort_flds = sort_flds.filter(fld => fld.field !== '$score');
      } // build function


      const sort_flds_count = sort_flds.length;

      if (!sort_flds_count) {
        return null;
      }

      return function (a, b) {
        var result, field;

        for (let sort_fld of sort_flds) {
          field = sort_fld.field;
          let multiplier = sort_fld.direction === 'desc' ? -1 : 1;
          result = multiplier * cmp(get_field(field, a), get_field(field, b));
          if (result) return result;
        }

        return 0;
      };
    }

    /**
     * Parses a search query and returns an object
     * with tokens and fields ready to be populated
     * with results.
     *
     */
    prepareSearch(query, optsUser) {
      const weights = {};
      var options = Object.assign({}, optsUser);
      propToArray(options, 'sort');
      propToArray(options, 'sort_empty'); // convert fields to new format

      if (options.fields) {
        propToArray(options, 'fields');
        const fields = [];
        options.fields.forEach(field => {
          if (typeof field == 'string') {
            field = {
              field: field,
              weight: 1
            };
          }

          fields.push(field);
          weights[field.field] = 'weight' in field ? field.weight : 1;
        });
        options.fields = fields;
      }

      return {
        options: options,
        query: query.toLowerCase().trim(),
        tokens: this.tokenize(query, options.respect_word_boundaries, weights),
        total: 0,
        items: [],
        weights: weights,
        getAttrFn: options.nesting ? getAttrNesting : getAttr
      };
    }

    /**
     * Searches through all items and returns a sorted array of matches.
     *
     */
    search(query, options) {
      var self = this,
          score,
          search;
      search = this.prepareSearch(query, options);
      options = search.options;
      query = search.query; // generate result scoring function

      const fn_score = options.score || self._getScoreFunction(search); // perform search and sort


      if (query.length) {
        iterate(self.items, (item, id) => {
          score = fn_score(item);

          if (options.filter === false || score > 0) {
            search.items.push({
              'score': score,
              'id': id
            });
          }
        });
      } else {
        iterate(self.items, (_, id) => {
          search.items.push({
            'score': 1,
            'id': id
          });
        });
      }

      const fn_sort = self._getSortFunction(search);

      if (fn_sort) search.items.sort(fn_sort); // apply limits

      search.total = search.items.length;

      if (typeof options.limit === 'number') {
        search.items = search.items.slice(0, options.limit);
      }

      return search;
    }

  }

  exports.Sifter = Sifter;
  exports.cmp = cmp;
  exports.getAttr = getAttr;
  exports.getAttrNesting = getAttrNesting;
  exports.getPattern = getPattern;
  exports.iterate = iterate;
  exports.propToArray = propToArray;
  exports.scoreValue = scoreValue;

  Object.defineProperty(exports, '__esModule', { value: true });

})));
//# sourceMappingURL=sifter.js.map
