
import {Sifter} from './sifter';

export type Field = {
	field: string,
	weight: number,
}

export type Sort = {
	field: string,
	direction?: string,
}

export type SortFn = (this:Sifter, a:ResultItem, b:ResultItem)=>number;

export type UserOptions = {
 	fields: string[]|Field[],
	conjunction: string,
	sort: string|SortFn|Sort[],

	nesting?: boolean,
 	score?: ScoreFn,
 	filter?: boolean,
 	sort_empty?: SortFn|Sort[],
	respect_word_boundaries?: boolean,
	limit?: number,
}


export type Options = {
 	fields: Field[],
	conjunction: string,
	sort: SortFn|Sort[],

	nesting?: boolean,
 	score?: ScoreFn,
 	filter?: boolean,
 	sort_empty?: SortFn|Sort[],
	respect_word_boundaries?: boolean,
	limit?: number,
}

export type Token = {
	string:string,
	regex:RegExp|null,
	field:string|null,
}

export type Weights = {[key:string]:number}

export type PrepareObj = {
	options: Options,
	query: string,
	tokens: Token[],
	total: number,
	items: ResultItem[],
	weights: Weights,
	getAttrFn: (data:any,field:string)=>any,

}

export type Settings = {
	diacritics:boolean
}

export type ResultItem = {
	score: number,
	id: number|string,
}


export type ScoreFn = (item:ResultItem) => number;
