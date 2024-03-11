import Plugin from '@ckeditor/ckeditor5-core/src/plugin';

/**
 * KeyboardShortcut Plugin.
 *
 * - Dispatch submit event on the closest editor form when Ctrl+Enter pressed
 */
export default class KeyboardShortcut extends Plugin {

    static get pluginName() {
        return 'KeyboardShortcut';
    }

    init() {

        // retrieve editor instance
        const oEditor = this.editor;

        // Dispatch submit event on the closest editor form when Ctrl+Enter pressed
        oEditor.keystrokes.set('Ctrl+Enter', (data, stop) => {
            if (oEditor.ui.element !== null) {
                const oForm = oEditor.ui.element.closest('form');
                if (oForm !== null) {
                    const oEvent = new Event("submit");
                    oForm.dispatchEvent(oEvent);
                }
            }
        });
    }
};