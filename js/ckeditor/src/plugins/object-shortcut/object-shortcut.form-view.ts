/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md.
 */

import {
    View,
    LabeledFieldView,
    createLabeledInputText,
    ButtonView,
    submitHandler,
} from '@ckeditor/ckeditor5-ui';
import { icons } from '@ckeditor/ckeditor5-core';
import{ Locale } from '@ckeditor/ckeditor5-utils';

export default class FormView extends View {

    // input fields
    oLabelInputView: LabeledFieldView;
    oClassInputView: LabeledFieldView;
    oReferenceInputView: LabeledFieldView;

    // buttons
    oSaveButtonView: ButtonView;
    oCancelButtonView: ButtonView;

    // child views
    oChildViews: any;

    constructor( oLocale: Locale ) {
        super( oLocale );

        // save button
        this.oSaveButtonView = this._createButton( 'Save', icons.check, 'ck-button-save' );
        this.oSaveButtonView.type = 'submit';

        // cancel button
        this.oCancelButtonView = this._createButton( 'Cancel', icons.cancel, 'ck-button-cancel' );
        this.oCancelButtonView.delegate( 'execute' ).to( this, 'cancel' );

        // create input fields
        this.oLabelInputView = this._createInput( 'Label' );
        this.oClassInputView = this._createInput( 'Object Class' );
        this.oReferenceInputView = this._createInput( 'Object Reference' );
        this.oChildViews = this.createCollection( [
            this.oLabelInputView,
            this.oClassInputView,
            this.oReferenceInputView,
            this.oSaveButtonView,
            this.oCancelButtonView
        ] );

        this.setTemplate( {
            tag: 'form',
            attributes: {
                class: [ 'ck', 'ck-object-shortcut-form' ],
                tabindex: '-1'
            },
            children: this.oChildViews
        } );
    }

    override render() {
        super.render();

        // Submit the form when the user clicked the save button or pressed enter in the input.
        submitHandler( {
            view: this
        } );
    }

    focus() {
        this.oChildViews.first.focus();
    }

    _createInput( sLabel: string ) {
        const oLabeledInput = new LabeledFieldView( this.locale, createLabeledInputText );
        oLabeledInput.label = sLabel;
        return oLabeledInput;
    }

    _createButton( sLabel: string, sIcon: string, sClassName: string ) {
        const oButton = new ButtonView();
        oButton.set( {
            label: sLabel,
            icon: sIcon,
            tooltip: true,
            class: sClassName
        } );
        return oButton;
    }
}