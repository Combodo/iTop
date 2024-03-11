/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md.
 */
import { View, LabeledFieldView, ButtonView } from '@ckeditor/ckeditor5-ui';
import { Locale } from '@ckeditor/ckeditor5-utils';
export default class FormView extends View {
    abbrInputView: LabeledFieldView;
    titleInputView: LabeledFieldView;
    saveButtonView: ButtonView;
    cancelButtonView: ButtonView;
    childViews: any;
    constructor(locale: Locale);
    render(): void;
    focus(): void;
    _createInput(label: string): LabeledFieldView<import("@ckeditor/ckeditor5-ui").InputTextView>;
    _createButton(label: string, icon: string, className: string): ButtonView;
}
