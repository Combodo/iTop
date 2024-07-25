import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
/**
 * KeyboardShortcut Plugin.
 *
 * - Dispatch submit event on the closest editor form when Ctrl+Enter pressed
 */
export default class KeyboardShortcut extends Plugin {
    static get pluginName(): string;
    init(): void;
}
