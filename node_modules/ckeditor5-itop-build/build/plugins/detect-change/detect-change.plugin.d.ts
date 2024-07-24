import { Plugin, type Editor } from 'ckeditor5/src/core.js';
import InsertCarriageReturnAfterBlock from "../insert-carriage-return-after-block/insert-carriage-return-after-block.plugin";
/**
 * DetectChanges Plugin.
 *
 */
export default class DetectChanges extends Plugin {
    private readonly _processor;
    constructor(editor: Editor);
    init(): void;
    static get pluginName(): string;
    static get requires(): (typeof InsertCarriageReturnAfterBlock)[];
}
