import { Sifter } from './sifter';
export declare type Field = {
    field: string;
    weight: number;
};
export declare type Sort = {
    field: string;
    direction?: string;
};
export declare type SortFn = (this: Sifter, a: ResultItem, b: ResultItem) => number;
export declare type UserOptions = {
    fields: string[] | Field[];
    conjunction: string;
    sort: string | SortFn | Sort[];
    nesting?: boolean;
    score?: ScoreFn;
    filter?: boolean;
    sort_empty?: SortFn | Sort[];
    respect_word_boundaries?: boolean;
    limit?: number;
};
export declare type Options = {
    fields: Field[];
    conjunction: string;
    sort: SortFn | Sort[];
    nesting?: boolean;
    score?: ScoreFn;
    filter?: boolean;
    sort_empty?: SortFn | Sort[];
    respect_word_boundaries?: boolean;
    limit?: number;
};
export declare type Token = {
    string: string;
    regex: RegExp | null;
    field: string | null;
};
export declare type Weights = {
    [key: string]: number;
};
export declare type PrepareObj = {
    options: Options;
    query: string;
    tokens: Token[];
    total: number;
    items: ResultItem[];
    weights: Weights;
    getAttrFn: (data: any, field: string) => any;
};
export declare type Settings = {
    diacritics: boolean;
};
export declare type ResultItem = {
    score: number;
    id: number | string;
};
export declare type ScoreFn = (item: ResultItem) => number;
