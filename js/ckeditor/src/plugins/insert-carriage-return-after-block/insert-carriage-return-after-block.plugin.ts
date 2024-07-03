import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import Node from '@ckeditor/ckeditor5-engine/src/model/node';

export default class InsertCarriageReturnAfterBlock extends Plugin {
    init() {
        const editor = this.editor;

        // Array of block elements type to check for
        const blockElements = ['codeBlock', 'div', 'pre'];

        // This function checks if the inserted element is one of the block elements we want a newline after
        const isBlockElement = (node: Node | null) => {
            return node ? blockElements.some(element => node.is('element', element)) : false;
        };

        // Listen to changes in the model
        editor.model.document.on('change:data', (evt, batch) => {
            if (batch.isLocal) {
                const changes = Array.from(editor.model.document.differ.getChanges());
                const currentCursorPosition = editor.model.document.selection.getFirstPosition();
                
                // Iterate over the changes and insert a newline after the block element when needed
                changes.forEach(change => {
                    if (change.type === 'insert' &&  isBlockElement(change.position.nodeAfter)) {
                        editor.model.change(writer => {
                            const position = change.position.getShiftedBy(change.length);
                            // Insert a newline after the block element
                            editor.execute( 'insertParagraph', {
                                position: position,
                            } );
                        });
                    }
                });
                // Restore the cursor position (most likely in the created block)
                editor.model.change(writer => {
                    writer.setSelection(currentCursorPosition);
                });
            }
        });
    }
}
