import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
export declare class MentionConverter extends Plugin {
    static get pluginName(): string;
    init(): Promise<unknown> | void | undefined | null;
}
