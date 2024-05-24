import Plugin from '@ckeditor/ckeditor5-core/src/plugin';

/**
 * TriggerUpdateOnReady Plugin.
 *
 * - Trigger update event when editor is ready
 */
export default class TriggerUpdateOnReady extends Plugin {

    static get pluginName() {
        return 'TriggerUpdateOnReady';
    }

    init() {

        // retrieve editor instance
        const oEditor = this.editor;

        // trigger update event when editor is ready
        oEditor.ui.on('ready', () => {

            if (oEditor.ui.element !== null) {
                const oEvent = new Event("update");
                oEditor.ui.element.dispatchEvent(oEvent);
            }

            for (const oElement of document.getElementsByClassName('ck-body-wrapper')) {
                oElement.classList.add('ck-reset_all-excluded');
            }
        });
    }
}