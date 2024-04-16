import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import { Dialog } from '@ckeditor/ckeditor5-ui';
/**
 * ObjectShortcut Plugin.
 *
 *
 */
export declare class ObjectShortcut extends Plugin {
    static get pluginName(): string;
    get requires(): (typeof Dialog)[];
    init(): void;
}
