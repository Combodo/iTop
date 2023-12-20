/**
 * Generate a list of unicode variants from the list of code points
 * @param {TCodePoints} code_points
 * @yield {TCodePointObj}
 */
export function generator(code_points: TCodePoints): Generator<{
    folded: string;
    composed: string;
    code_point: number;
}, void, unknown>;
/** @type {TCodePoints} */
export const code_points: TCodePoints;
/** @type {TUnicodeMap} */
export let unicode_map: TUnicodeMap;
export function initialize(_code_points?: TCodePoints | undefined): void;
export function normalize(str: string, form?: string): string;
export function asciifold(str: string): string;
export function _asciifold(str: string): string;
export function generateSets(code_points: TCodePoints): TUnicodeSets;
export function generateMap(code_points: TCodePoints): TUnicodeMap;
export function mapSequence(strings: string[], min_replacement?: number): string;
export function substringsToPattern(str: string, min_replacement?: number): string;
export function getPattern(str: string): string | undefined;
export { escape_regex };
export type TUnicodeMap = {
    [key: string]: string;
};
export type TUnicodeSets = {
    [key: string]: Set<string>;
};
export type TCodePoints = [[number, number]];
export type TCodePointObj = {
    folded: string;
    composed: string;
    code_point: number;
};
export type TSequencePart = {
    start: number;
    end: number;
    length: number;
    substr: string;
};
import { escape_regex } from "./regex.mjs";
