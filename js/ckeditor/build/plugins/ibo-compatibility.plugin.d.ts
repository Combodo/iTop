import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
/**
 * IBOCompatibility Plugin.
 *
 * - exclude ck-reset_all for mention dropdown
 * - appends ibo-is-html-content class
 */
export declare class IBOCompatibility extends Plugin {
    static get pluginName(): string;
    init(): Promise<unknown> | void | undefined | null;
}
