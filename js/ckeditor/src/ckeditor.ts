/**
 * @license Copyright (c) 2014-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

import { ClassicEditor } from '@ckeditor/ckeditor5-editor-classic';

import { Alignment } from '@ckeditor/ckeditor5-alignment';
import {
	Bold,
	Italic,
	Strikethrough,
	Subscript,
	Superscript,
	Underline
} from '@ckeditor/ckeditor5-basic-styles';
import { BlockQuote } from '@ckeditor/ckeditor5-block-quote';
import { CodeBlock } from '@ckeditor/ckeditor5-code-block';
import type { EditorConfig } from '@ckeditor/ckeditor5-core';
import { Essentials } from '@ckeditor/ckeditor5-essentials';
import { FontBackgroundColor, FontColor, FontFamily, FontSize } from '@ckeditor/ckeditor5-font';
import { Heading, Title } from '@ckeditor/ckeditor5-heading';
import { Highlight } from '@ckeditor/ckeditor5-highlight';
import { HorizontalLine } from '@ckeditor/ckeditor5-horizontal-line';
import { GeneralHtmlSupport } from '@ckeditor/ckeditor5-html-support';
import {
	Image,
	ImageCaption,
	ImageResize,
	ImageStyle,
	ImageToolbar,
	ImageUpload,
	PictureEditing
} from '@ckeditor/ckeditor5-image';
import { Indent, IndentBlock } from '@ckeditor/ckeditor5-indent';
import { AutoLink, Link, LinkImage } from '@ckeditor/ckeditor5-link';
import { List, ListProperties } from '@ckeditor/ckeditor5-list';
import { Mention } from '@ckeditor/ckeditor5-mention';
import { Paragraph } from '@ckeditor/ckeditor5-paragraph';
import {
	Table,
	TableCaption,
	TableCellProperties,
	TableColumnResize,
	TableProperties,
	TableToolbar
} from '@ckeditor/ckeditor5-table';
import { TextTransformation } from '@ckeditor/ckeditor5-typing';
import { Undo } from '@ckeditor/ckeditor5-undo';
import InsertHtml from './plugins/insert-html/insert-html.plugin';


// combodo plugins
import AppendITopClasses from "./plugins/append-itop-classes/append-itop-classes.plugin";
import KeyboardShortcut from "./plugins/keyboard-shortcut/keyboard-shortcut.plugin";
import MentionsMarkup from "./plugins/mentions-markup/mentions-markup.plugin";
import TriggerUpdateOnReady from "./plugins/trigger_update_on_ready/trigger_update_on_ready.plugin";
import Maximize from './plugins/maximize/maximize.plugin';
import ObjectShortcut from './plugins/object-shortcut/object-shortcut.plugin';
import DetectChanges from "./plugins/detect-change/detect-change.plugin";

// You can read more about extending the build with additional plugins in the "Installing plugins" guide.
// See https://ckeditor.com/docs/ckeditor5/latest/installation/plugins/installing-plugins.html for details.

// iTop console theme
import './resources/console-theme.css';

class Editor extends ClassicEditor {
	public static override builtinPlugins = [
		Alignment,
		AutoLink,
		BlockQuote,
		Bold,
		CodeBlock,
		Essentials,
		FontBackgroundColor,
		FontColor,
		FontFamily,
		FontSize,
		GeneralHtmlSupport,
		Heading,
		Highlight,
		HorizontalLine,
		Image,
		ImageCaption,
		ImageResize,
		ImageStyle,
		ImageToolbar,
		ImageUpload,
		Indent,
		IndentBlock,
		Italic,
		Link,
		LinkImage,
		List,
		ListProperties,
		Mention,
		Paragraph,
		PictureEditing,
		Strikethrough,
		Subscript,
		Superscript,
		Table,
		TableCaption,
		TableCellProperties,
		TableColumnResize,
		TableProperties,
		TableToolbar,
		TextTransformation,
		Underline,
		Undo,

        // combodo plugins
        AppendITopClasses,
        KeyboardShortcut,
        MentionsMarkup,
        TriggerUpdateOnReady,
        Maximize,
        ObjectShortcut,
        InsertHtml,
        DetectChanges
	];

    // default configuration editor
	public static override defaultConfig: EditorConfig = {
		toolbar: {
			items: [
                'maximize',
                '|',
                'undo',
                'redo',
                '|',
                'fontSize',
                'fontColor',
                'highlight:yellowMarker',
				'bold',
				'italic',
                'underline',
                {
                    label: 'More styles',
                    items: ['strikethrough', 'superscript', 'subscript' ]
                },
                '-',
                'link',
                'object-shortcut',
                'imageUpload',
                'blockQuote',
                'codeBlock',
                'bulletedList',
                'numberedList',
                'insertTable'
			],
            shouldNotGroupWhenFull: true
		},
		language: 'en',
		image: {
			toolbar: [
				'imageTextAlternative',
				'toggleImageCaption',
                '|',
				'imageStyle:inline',
				'imageStyle:block',
				'imageStyle:side',
				'linkImage',
                '|',
                'resizeImage:50',
                'resizeImage:75',
                'resizeImage:original',
			],
            resizeOptions: [
                {
                    name: 'resizeImage:original',
                    value: null,
                    icon: 'original'
                },
                {
                    name: 'resizeImage:50',
                    value: '50',
                    icon: 'medium'
                },
                {
                    name: 'resizeImage:75',
                    value: '75',
                    icon: 'large'
                }
            ],
		},
		table: {
			contentToolbar: [
				'tableColumn',
				'tableRow',
				'mergeTableCells',
				'tableCellProperties',
				'tableProperties'
			]
		},
        htmlSupport: {
            allow: [
                {
                    name: /.*/,
                    attributes: true,
                    classes: true,
                    styles: true
                }
            ]
        },
        link: {
            defaultProtocol: 'http://'
        }
	};
}

export default Editor;
