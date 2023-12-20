
import { asciifold } from '@orchidjs/unicode-variants';
import * as T from './types';


/**
 * A property getter resolving dot-notation
 * @param  {Object}  obj     The root object to fetch property on
 * @param  {String}  name    The optionally dotted property name to fetch
 * @return {Object}          The resolved property value
 */
export const getAttr = (obj:{[key:string]:any}, name:string ) => {
    if (!obj ) return;
    return obj[name];
};

/**
 * A property getter resolving dot-notation
 * @param  {Object}  obj     The root object to fetch property on
 * @param  {String}  name    The optionally dotted property name to fetch
 * @return {Object}          The resolved property value
 */
export const getAttrNesting = (obj:{[key:string]:any}, name:string ) => {
    if (!obj ) return;
    var part, names = name.split(".");
	while( (part = names.shift()) && (obj = obj[part]));
    return obj;
};

/**
 * Calculates how close of a match the
 * given value is against a search token.
 *
 */
export const scoreValue = (value:string, token:T.Token, weight:number ):number => {
	var score, pos;

	if (!value) return 0;

	value = value + '';
	if( token.regex == null ) return 0;
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
export const propToArray = (obj:{[key:string]:any}, key:string) => {
	var value = obj[key];

	if( typeof value == 'function' ) return value;

	if( value && !Array.isArray(value) ){
		obj[key] = [value];
	}
}


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
export const iterate = (object:[]|{[key:string]:any}, callback:(value:any,key:any)=>any) => {

	if ( Array.isArray(object)) {
		object.forEach(callback);

	}else{

		for (var key in object) {
			if (object.hasOwnProperty(key)) {
				callback(object[key], key);
			}
		}
	}
};



export const cmp = (a:number|string, b:number|string) => {
	if (typeof a === 'number' && typeof b === 'number') {
		return a > b ? 1 : (a < b ? -1 : 0);
	}
	a = asciifold(a + '').toLowerCase();
	b = asciifold(b + '').toLowerCase();
	if (a > b) return 1;
	if (b > a) return -1;
	return 0;
};
