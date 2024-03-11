import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import {Element} from "@ckeditor/ckeditor5-engine";

/**
 * MentionsMarkup Plugin.
 *
 * - Converter for mentions
 */
export default class MentionsMarkup extends Plugin {

    static get pluginName() {
        return 'MentionsMarkup';
    }

    init() {

        // retrieve editor instance
        const oEditor = this.editor;

        // convert view > model
        oEditor.conversion.for('upcast').elementToAttribute({
            view: {
                name: 'a',
                attributes: {
                    href: true,
                    'data-role': true,
                    'data-object-class': true,
                    'data-object-id': true
                }
            },
            model: {
                key: 'mention',
                value: (oViewItem: Element) => {
                    return oEditor.plugins.get( 'Mention' ).toMentionAttribute( oViewItem, {
                        link: oViewItem.getAttribute( 'href' ),
                        id: oViewItem.getAttribute( 'data-object-id' ),
                        class_name: oViewItem.getAttribute( 'data-object-class' ),
                        mention: 'object-mention',
                    } );
                }
            },
            converterPriority: 'high'
        } );

        // convert model > view
        oEditor.conversion.for( 'downcast' ).attributeToElement( {
            model: 'mention',
            view: ( oModelAttributeValue, { writer } ) => {

                // Do not convert empty attributes (lack of value means no mention).
                if ( !oModelAttributeValue ) {
                    return;
                }

                return writer.createAttributeElement( 'a', {
                    'data-role' : 'object-mention',
                    'data-object-class' : oModelAttributeValue.class_name,
                    'data-object-id' : oModelAttributeValue.id,
                    'href': oModelAttributeValue.link
                }, {
                    priority: 20,
                    id: oModelAttributeValue.uid
                } );
            },
            converterPriority: 'high'
        } );
    }
}

