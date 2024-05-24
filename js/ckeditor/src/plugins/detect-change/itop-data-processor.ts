import {HtmlDataProcessor,
        type ViewDocument,
        type ViewDocumentFragment
} from 'ckeditor5/src/engine.js';

export default class iTopDataProcessor extends HtmlDataProcessor {
    /**
     * HTML data processor used to process HTML if we detect changes
     * @private
     */
    private _htmlDP: HtmlDataProcessor;
    /**
     * Initial value of the editor, we'll return it if we don't detect any changes
     * @private
     */
    private readonly _initialValue: string;
    /**
     * Transformed initial value of the editor, we'll use it to detect changes
     * @private
     */
    private  _transformedInitialValue: string;
    /**
     * Creates a new instance of the Markdown data processor class.
     */
    constructor( document: ViewDocument, initialValue: string, transformedInitialValue: string) {
        super( document );
        this._htmlDP = new HtmlDataProcessor( document );
        this._initialValue = initialValue;
        // It'll probably be empty on the first call, we'll set it later
        this._transformedInitialValue = transformedInitialValue;
    }
    
    setTransformedInitialValue( transformedInitialValue: string ) {
        this._transformedInitialValue = transformedInitialValue;
    }
    
    override toData( viewFragment: ViewDocumentFragment ): string {
        const html = this._htmlDP.toData( viewFragment );
        if( html === this._transformedInitialValue ) {
            return this._initialValue;
        }
        return html;
    }
}