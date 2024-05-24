import { Plugin } from '@ckeditor/ckeditor5-core';
import {ClassicEditor} from "@ckeditor/ckeditor5-editor-classic";

export default class UpdateInputOnChange extends Plugin {

    static get pluginName() {
        return 'UpdateInputOnChange';
    }

    init() {

        // retrieve editor instance
        const oEditor:ClassicEditor = this.editor as ClassicEditor;

        if(oEditor.sourceElement !== undefined) {
            const oInputElement = oEditor.sourceElement as HTMLInputElement;

            // update input when data change
            oEditor.model.document.on('change:data', (event) => {
                oInputElement.value = oEditor.getData();
                const oEvent = new Event('change');
                oInputElement.dispatchEvent(oEvent);
            });

        }
    }

}