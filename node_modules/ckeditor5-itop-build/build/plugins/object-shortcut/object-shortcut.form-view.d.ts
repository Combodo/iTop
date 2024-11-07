/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md.
 */
import { View, LabeledFieldView, ButtonView } from '@ckeditor/ckeditor5-ui';
import { Locale } from '@ckeditor/ckeditor5-utils';
export default class FormView extends View {
    oLabelInputView: LabeledFieldView;
    oClassInputView: LabeledFieldView;
    oReferenceInputView: LabeledFieldView;
    oSaveButtonView: ButtonView;
    oCancelButtonView: ButtonView;
    oChildViews: any;
    constructor(oLocale: Locale);
    render(): void;
    focus(): void;
    _createInput(sLabel: string): LabeledFieldView<import("@ckeditor/ckeditor5-ui").InputTextView>;
    _createButton(sLabel: string, sIcon: string, sClassName: string): ButtonView;
}
