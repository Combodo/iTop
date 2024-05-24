/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

import { Plugin, type Editor } from '@ckeditor/ckeditor5-core';
/**
 * DetectChanges Plugin.
 *
 */
export default class DetectChanges extends Plugin {
    constructor(editor: Editor);
    init(): void;
    static get pluginName(): string;
}
