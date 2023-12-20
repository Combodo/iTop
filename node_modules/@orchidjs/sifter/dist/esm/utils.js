/*! sifter.js | https://github.com/orchidjs/sifter.js | Apache License (v2) */
import { asciifold } from '@orchidjs/unicode-variants';

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

export { cmp, getAttr, getAttrNesting, iterate, propToArray, scoreValue };
//# sourceMappingURL=utils.js.map
