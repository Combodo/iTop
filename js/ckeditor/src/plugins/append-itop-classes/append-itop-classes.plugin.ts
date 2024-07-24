import Plugin from '@ckeditor/ckeditor5-core/src/plugin';

/**
 * AppendITopClasses Plugin.
 *
 * Appends ibo-is-html-content (backoffice) & ipb-is-html-content (portal) classes
 */
export default class AppendITopClasses extends Plugin {

    static get pluginName() {
        return 'AppendITopClasses';
    }

    init() {

        // retrieve editor instance
        const oEditor = this.editor;

        // appends ibo-is-html-content (backoffice) & ipb-is-html-content (portal) classes
        oEditor.editing.view.change( oWriter => {
            const oRootElement = oEditor.editing.view.document.getRoot();
            if(oRootElement !== null){
                // Add the proper class depending on the GUI we are in
                const sGUIType = document.body.getAttribute('data-gui-type');
                if (sGUIType === 'backoffice') {
                    oWriter.addClass( 'ibo-is-html-content', oRootElement);
                } else if (sGUIType === 'portal') {
                    oWriter.addClass('ipb-is-html-content', oRootElement);
                }
            }
        });
    }
}

