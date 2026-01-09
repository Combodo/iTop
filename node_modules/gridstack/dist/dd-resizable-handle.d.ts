/**
 * dd-resizable-handle.ts 12.4.2
 * Copyright (c) 2021-2025  Alain Dumesny - see GridStack root license
 */
import { GridItemHTMLElement } from './gridstack';
export interface DDResizableHandleOpt {
    element?: string | HTMLElement;
    start?: (event: MouseEvent) => void;
    move?: (event: MouseEvent) => void;
    stop?: (event: MouseEvent) => void;
}
export declare class DDResizableHandle {
    protected host: GridItemHTMLElement;
    protected dir: string;
    protected option: DDResizableHandleOpt;
    constructor(host: GridItemHTMLElement, dir: string, option: DDResizableHandleOpt);
    /** call this when resize handle needs to be removed and cleaned up */
    destroy(): DDResizableHandle;
}
