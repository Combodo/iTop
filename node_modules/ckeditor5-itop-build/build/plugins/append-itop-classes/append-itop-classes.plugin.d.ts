import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
/**
 * AppendITopClasses Plugin.
 *
 * Appends ibo-is-html-content (backoffice) & ipb-is-html-content (portal) classes
 */
export default class AppendITopClasses extends Plugin {
    static get pluginName(): string;
    init(): void;
}
