import { HtmlDataProcessor, type ViewDocument, type ViewDocumentFragment } from 'ckeditor5/src/engine.js';
export default class iTopDataProcessor extends HtmlDataProcessor {
    /**
     * HTML data processor used to process HTML if we detect changes
     * @private
     */
    private _htmlDP;
    /**
     * Initial value of the editor, we'll return it if we don't detect any changes
     * @private
     */
    private readonly _initialValue;
    /**
     * Transformed initial value of the editor, we'll use it to detect changes
     * @private
     */
    private _transformedInitialValue;
    /**
     * Creates a new instance of the Markdown data processor class.
     */
    constructor(document: ViewDocument, initialValue: string, transformedInitialValue: string);
    setTransformedInitialValue(transformedInitialValue: string): void;
    toData(viewFragment: ViewDocumentFragment): string;
}
