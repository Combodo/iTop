

type TUnicodeMap = {[key:string]:string};

type TUnicodeSets = {[key:string]:Set<string>};

type TCodePoints = [[number,number]];

type TCodePointObj = {folded:string,composed:string,code_point:number}

type TSequencePart = {start:number,end:number,length:number,substr:string}
