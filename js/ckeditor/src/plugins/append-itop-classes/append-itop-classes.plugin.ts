import Plugin from '@ckeditor/ckeditor5-core/src/plugin';

/**
 * AppendITopClasses Plugin.
 *
 * Appends ibo-is-html-content class
 */
export default class AppendITopClasses extends Plugin {

    static get pluginName() {
        return 'AppendITopClasses';
    }

    init() {

        // retrieve editor instance
        const oEditor = this.editor;

        // appends ibo-is-html-content class
        oEditor.editing.view.change( oWriter => {
            const oRootElement = oEditor.editing.view.document.getRoot();
            if(oRootElement !== null){
                oWriter.addClass( 'ibo-is-html-content', oRootElement);
            }
        });
    }
}

