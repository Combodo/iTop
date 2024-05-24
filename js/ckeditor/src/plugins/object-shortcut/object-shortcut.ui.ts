import { Plugin } from '@ckeditor/ckeditor5-core';
import {ButtonView, ContextualBalloon, clickOutsideHandler} from '@ckeditor/ckeditor5-ui';
import FormView from './object-shortcut.form-view';
import './styles.css';

// plugin icon
const sPluginIconSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48' +
    ' 48' +
    ' 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-32 252c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92H92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z"/></svg>';

export default class ObjectShortcutUI extends Plugin {
    static get requires() {
        return [ ContextualBalloon ];
    }

    oBalloon: ContextualBalloon | undefined;
    oFormView: FormView | undefined;

    init() {
        const editor = this.editor;

        // Create the balloon and the form view.
        this.oBalloon = this.editor.plugins.get( ContextualBalloon );
        this.oFormView = this._createFormView();

        editor.ui.componentFactory.add( 'object-shortcut', () => {
            const oButton = new ButtonView();
            oButton.label = editor.config.get('objectShortcut.buttonLabel') as string ?? 'Insert Object Shortcut';
            oButton.tooltip = true;
            oButton.icon = sPluginIconSVG;

            // Show the UI on button click.
            this.listenTo( oButton, 'execute', () => {
                this._showUI();
            } );

            return oButton;
        } );
    }

    _createFormView() {
        const oEditor = this.editor;
        const oFormView = new FormView( oEditor.locale );

        // Execute the command after clicking the "Save" button.
        this.listenTo( oFormView, 'submit', () => {
            // Grab values from the abbreviation and title input fields.

            let sLabel = '';
            const oLabelElement = <HTMLInputElement>oFormView.oLabelInputView.fieldView.element;
            if(oLabelElement !== null) {
                sLabel = oLabelElement.value;
            }

            let sObjectClass = 'object class';
            const oClassElement = <HTMLInputElement>oFormView.oClassInputView.fieldView.element;
            if(oClassElement !== null) {
                sObjectClass = oClassElement.value;
            }

            let sObjectReference = 'object reference';
            const oReferenceElement = <HTMLInputElement>oFormView.oReferenceInputView.fieldView.element;
            if(oReferenceElement !== null) {
                sObjectReference = oReferenceElement.value;
            }

            oEditor.model.change( writer => {
                const sText = `[[${sObjectClass}:${sObjectReference}${sLabel !== '' ? '|' + sLabel : ''}]]`;
                oEditor.model.insertContent(writer.createText(sText));
            } );

            // Hide the form view after submit.
            this._hideUI();
        } );

        // Hide the form view after clicking the "Cancel" button.
        this.listenTo(oFormView, 'cancel', () => {
            this._hideUI();
        } );


        const oBalloon = this.oBalloon;
        if(oBalloon !== undefined && oBalloon.view.element !== null){
            // Hide the form view when clicking outside the balloon.
            clickOutsideHandler( {
                emitter: oFormView,
                activator: () => oBalloon.visibleView === oFormView,
                contextElements: [ oBalloon.view.element ],
                callback: () => this._hideUI()
            } );
        }


        return oFormView;
    }

    _showUI() {

        // show balloon
        const pos = this._getBalloonPositionData();
        if(this.oBalloon !== undefined && this.oFormView !== undefined && pos !== null && pos.oTarget !== null){
            this.oBalloon.add( {
                view: this.oFormView,
                position: {
                    target: pos.oTarget
                }
            } );
        }

        // focus form view
        if(this.oFormView !== undefined){
            this.oFormView.focus();
        }

    }

    _hideUI() {
        if( this.oFormView !== undefined && this.oBalloon !== undefined){

            if( this.oFormView.element !== null){
                (<HTMLFormElement>this.oFormView.element).reset();
            }

            // remove balloon
            this.oBalloon.remove( this.oFormView );

            // Focus the editing view
            this.editor.editing.view.focus();
        }
    }

    _getBalloonPositionData(){
        const oView = this.editor.editing.view;
        const oViewDocument = oView.document;
        let oTarget = null;
        const oFirstRange = oViewDocument.selection.getFirstRange();
        if(oFirstRange !== null) {
            oTarget = () => oView.domConverter.viewRangeToDom(oFirstRange);
        }
        return {
            oTarget
        };
    }
}