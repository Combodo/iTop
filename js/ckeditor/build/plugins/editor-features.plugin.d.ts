import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
/**
 * EditorFeatures Plugin.
 *
 * - trigger update event when editor is ready
 * - dispatch submit event on the closest editor form when Ctrl+Enter pressed
 *
 */
export declare class EditorFeatures extends Plugin {
    static get pluginName(): string;
    init(): void;
}
