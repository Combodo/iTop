import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import InsertHtmlCommand from "./insert-html.command";

/**
 * InsertHtml Plugin.
 *
 */
export default class InsertHtml extends Plugin {

    static get pluginName() {
        return 'InsertHtmlContent';
    }

    init() {

        // retrieve editor instance
        const oEditor = this.editor;

        // appends ibo-is-html-content class
        oEditor.commands.add( 'insert-html', new InsertHtmlCommand(oEditor) );
    }
}

