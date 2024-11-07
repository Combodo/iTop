import { Plugin } from '@ckeditor/ckeditor5-core';
import { ClassicEditor } from "@ckeditor/ckeditor5-editor-classic";
export default class Disabler extends Plugin {
    static get pluginName(): string;
    init(): void;
    /**
     * Process ckeditor disabling.
     *
     * @param oEditor
     * @param oInputElement
     */
    static processDisabling(oEditor: ClassicEditor, oInputElement: HTMLInputElement): void;
}
