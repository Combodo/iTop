import { Plugin } from '@ckeditor/ckeditor5-core';
/**
 * TriggerUpdateOnReady Plugin.
 *
 * - Trigger update event when editor is ready
 */
export default class TriggerUpdateOnReady extends Plugin {
    static get pluginName(): string;
    init(): void;
}
