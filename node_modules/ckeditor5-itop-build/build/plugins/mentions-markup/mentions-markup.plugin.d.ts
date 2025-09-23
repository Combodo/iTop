import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
/**
 * MentionsMarkup Plugin.
 *
 * - Converter for mentions
 */
export default class MentionsMarkup extends Plugin {
    static get pluginName(): string;
    init(): void;
}
