import { Plugin } from '@ckeditor/ckeditor5-core';
import ObjectShortcutUI from './object-shortcut.ui';

export default class ObjectShortcut extends Plugin {
    static get requires() {
        return [ObjectShortcutUI];
    }
}