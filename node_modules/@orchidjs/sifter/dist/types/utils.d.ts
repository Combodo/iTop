import * as T from './types';
/**
 * A property getter resolving dot-notation
 * @param  {Object}  obj     The root object to fetch property on
 * @param  {String}  name    The optionally dotted property name to fetch
 * @return {Object}          The resolved property value
 */
export declare const getAttr: (obj: {
    [key: string]: any;
}, name: string) => any;
/**
 * A property getter resolving dot-notation
 * @param  {Object}  obj     The root object to fetch property on
 * @param  {String}  name    The optionally dotted property name to fetch
 * @return {Object}          The resolved property value
 */
export declare const getAttrNesting: (obj: {
    [key: string]: any;
}, name: string) => {
    [key: string]: any;
} | undefined;
/**
 * Calculates how close of a match the
 * given value is against a search token.
 *
 */
export declare const scoreValue: (value: string, token: T.Token, weight: number) => number;
/**
 * Cast object property to an array if it exists and has a value
 *
 */
export declare const propToArray: (obj: {
    [key: string]: any;
}, key: string) => any;
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
export declare const iterate: (object: [] | {
    [key: string]: any;
}, callback: (value: any, key: any) => any) => void;
export declare const cmp: (a: number | string, b: number | string) => 1 | -1 | 0;
