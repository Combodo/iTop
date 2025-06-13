import { Plugin, type Editor } from 'ckeditor5/src/core.js';
/**
 * DetectChanges Plugin.
 *
 */
export default class DetectChanges extends Plugin {
    private readonly _processor;
    constructor(editor: Editor);
    init(): void;
    static get pluginName(): string;
}
