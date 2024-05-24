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
        // If the initial value is not set or empty, we don't need to do anything
        if( !sInitialValue || sInitialValue === '') {
            return;
        }
        // Initialize our own data processor
        const oProcessor  = new iTopDataProcessor( editor.data.viewDocument,  sInitialValue,  editor.getData() ) as  iTopDataProcessor;
        editor.data.processor = oProcessor;
        // Listen for the dataReady event only once
        editor.model.document.once('change:data', () => {
            oProcessor.setTransformedInitialValue( editor.getData());
        });
    }
    init() {
        
    }
    static get pluginName() {
        return 'DetectChanges';
    }
}
