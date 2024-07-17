import { Plugin, type Editor } from 'ckeditor5/src/core.js';
import iTopDataProcessor from "./itop-data-processor";
import InsertCarriageReturnAfterBlock from "../insert-carriage-return-after-block/insert-carriage-return-after-block.plugin";

/**
 * DetectChanges Plugin.
 *
 */
export default class DetectChanges extends Plugin {
    private readonly _processor: iTopDataProcessor | undefined;
    
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
        this._processor = oProcessor;
    }
    
    init() {
        const editor = this.editor;// Listen for the dataReady event only once
        editor.model.document.once('change:data', () => {
            if(this._processor ) {
                this._processor.setTransformedInitialValue(editor.getData());
            }
        });
    }
    static get pluginName() {
        return 'DetectChanges';
    }

    // Needed as InsertCarriageReturnAfterBlock will possibly change data on initialization if there's a block in the content, so we need to make sure it's loaded first
    static get requires() {
        return [ InsertCarriageReturnAfterBlock ];
    }
}
