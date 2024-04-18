import { Plugin, type Editor } from 'ckeditor5/src/core.js';
import iTopDataProcessor from "./itop-data-processor";

/**
 * DetectChanges Plugin.
 *
 */
export default class DetectChanges extends Plugin {

    constructor( editor: Editor ) {
        super( editor );
        const sInitialValue:string = editor.config.get('detectChanges.initialValue') as string;
        // TODO 3.2.0: How to use CombodoJSConsole here ?
        console.debug('DetectChanges initial value', sInitialValue);
        // If the initial value is not set or empty, we don't need to do anything
        if( !sInitialValue || sInitialValue === '') {
            return;
        }
        // Initialize our own data processor
        editor.data.processor= new iTopDataProcessor( editor.data.viewDocument,  sInitialValue,  editor.getData() ) as  iTopDataProcessor;
        // Listen for the dataReady event only once
        editor.model.document.once('change:data', () => {
            // Ignore linter as processor can be any kind of DataProcessor but we're sure that we have an iTopDataProcessor
            // @ts-ignore
            editor.data.processor.setTransformedInitialValue( editor.getData());
        });
    }
    init() {
        
    }
    static get pluginName() {
        return 'DetectChanges';
    }
}
