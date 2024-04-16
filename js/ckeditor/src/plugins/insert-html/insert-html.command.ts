import { Command } from 'ckeditor5/src/core';

/**
 * InsertHtmlCommand Command.
 *
 */
export default class InsertHtmlCommand extends Command {

    override execute( sContent:string ) {
        this.editor.setData(this.editor.getData() + sContent);
    }
}

