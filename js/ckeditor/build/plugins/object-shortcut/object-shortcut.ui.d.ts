/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

import { Plugin } from '@ckeditor/ckeditor5-core';
import { ContextualBalloon } from '@ckeditor/ckeditor5-ui';
import FormView from './object-shortcut.form-view';
import './styles.css';
export default class ObjectShortcutUI extends Plugin {
    static get requires(): (typeof ContextualBalloon)[];
    oBalloon: ContextualBalloon | undefined;
    oFormView: FormView | undefined;
    init(): void;
    _createFormView(): FormView;
    _showUI(): void;
    _hideUI(): void;
    _getBalloonPositionData(): {
        oTarget: (() => Range) | null;
    };
}
