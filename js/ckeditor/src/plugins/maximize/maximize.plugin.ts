
import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import ButtonView from '@ckeditor/ckeditor5-ui/src/button/buttonview';

// plugin icons
const sMaximizeIconSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M448 344v112a23.9 23.9 0 0 1 -24 24H312c-21.4' +
        ' 0-32.1-25.9-17-41l36.2-36.2L224 295.6 116.8 402.9 153 439c15.1 15.1 4.4 41-17 41H24a23.9 23.9 0 0 1 -24-24V344c0-21.4 25.9-32.1 41-17l36.2 36.2L184.5 256 77.2 148.7 41 185c-15.1 15.1-41 4.4-41-17V56a23.9 23.9 0 0 1 24-24h112c21.4 0 32.1 25.9 17 41l-36.2 36.2L224 216.4l107.2-107.3L295 73c-15.1-15.1-4.4-41 17-41h112a23.9 23.9 0 0 1 24 24v112c0 21.4-25.9 32.1-41 17l-36.2-36.2L263.5 256l107.3 107.3L407 327.1c15.1-15.2 41-4.5 41 16.9z"/></svg>';
const sMinimizeIconSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M200 288H88c-21.4 0-32.1 25.8-17 41l32.9 31-99.2' +
        ' 99.3c-6.2 6.2-6.2 16.4 0 22.6l25.4 25.4c6.2 6.2 16.4 6.2 22.6 0L152 408l31.1 33c15.1 15.1 40.9 4.4 40.9-17V312c0-13.3-10.7-24-24-24zm112-64h112c21.4 0 32.1-25.9 17-41l-33-31 99.3-99.3c6.2-6.2 6.2-16.4 0-22.6L481.9 4.7c-6.2-6.2-16.4-6.2-22.6 0L360 104l-31.1-33C313.8 55.9 288 66.6 288 88v112c0 13.3 10.7 24 24 24zm96 136l33-31.1c15.1-15.1 4.4-40.9-17-40.9H312c-13.3 0-24 10.7-24 24v112c0 21.4 25.9 32.1 41 17l31-32.9 99.3 99.3c6.2 6.2 16.4 6.2 22.6 0l25.4-25.4c6.2-6.2 6.2-16.4 0-22.6L408 360zM183 71.1L152 104 52.7 4.7c-6.2-6.2-16.4-6.2-22.6 0L4.7 30.1c-6.2 6.2-6.2 16.4 0 22.6L104 152l-33 31.1C55.9 198.2 66.6 224 88 224h112c13.3 0 24-10.7 24-24V88c0-21.3-25.9-32-41-16.9z"/></svg>';

export default class Maximize extends Plugin {

    static get pluginName() {
        return 'Maximize';
    }

    init() {

        // retrieve editor instance
        const oEditor = this.editor;

        // initial editor parent element
        let oInitialParentElement: HTMLElement;

        // add maximize button
        oEditor.ui.componentFactory.add( 'maximize', () => {

            // button
            const oButton = new ButtonView();
            oButton.set( {
                icon: sMaximizeIconSVG,
                isToggleable: true
            } );

            this.listenTo( oButton, 'execute', () => {
                if(oEditor.ui.element !== null){
                    if(oButton.isOn){
                        oInitialParentElement.append(oEditor.ui.element);
                        oEditor.ui.element.classList.remove('cke-maximized');
                        document.body.classList.remove('cke-maximized');
                        oButton.icon = sMaximizeIconSVG;
                    }
                    else{
                        oInitialParentElement = oEditor.ui.element.parentElement ?? oInitialParentElement;
                        oEditor.ui.element.remove();
                        document.body.append(oEditor.ui.element);
                        document.body.classList.add('cke-maximized'); // Add class to body to prevent scrollbars
                        oEditor.ui.element.classList.add('cke-maximized');
                        oButton.icon = sMinimizeIconSVG;
                    }

                    oButton.isOn = !oButton.isOn;
                }
            });


            return oButton;
        } );
    }
}

