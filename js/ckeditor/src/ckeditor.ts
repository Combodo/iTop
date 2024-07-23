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
import {PasteFromOffice} from "@ckeditor/ckeditor5-paste-from-office";
import {
	Table,
	TableCaption,
	TableCellProperties,
	TableColumnResize,
	TableProperties,
	TableToolbar
} from '@ckeditor/ckeditor5-table';
import { Undo } from '@ckeditor/ckeditor5-undo';
import { RemoveFormat } from '@ckeditor/ckeditor5-remove-format';
import { SourceEditing } from '@ckeditor/ckeditor5-source-editing';

// Combodo plugins
import AppendITopClasses from "./plugins/append-itop-classes/append-itop-classes.plugin";
import KeyboardShortcut from "./plugins/keyboard-shortcut/keyboard-shortcut.plugin";
import MentionsMarkup from "./plugins/mentions-markup/mentions-markup.plugin";
import TriggerUpdateOnReady from "./plugins/trigger-update-on-ready/trigger-update-on-ready.plugin";
import Maximize from './plugins/maximize/maximize.plugin';
import ObjectShortcut from './plugins/object-shortcut/object-shortcut.plugin';
import DetectChanges from "./plugins/detect-change/detect-change.plugin";
import UpdateInputOnChange from "./plugins/update-input-on-change/update-input-on-change.plugin";
import Disabler from "./plugins/disabler/disabler.plugin";
import InsertHtml from './plugins/insert-html/insert-html.plugin';
import InsertCarriageReturnAfterBlock from "./plugins/insert-carriage-return-after-block/insert-carriage-return-after-block.plugin";

// You can read more about extending the build with additional plugins in the "Installing plugins" guide.
// See https://ckeditor.com/docs/ckeditor5/latest/installation/plugins/installing-plugins.html for details.

// iTop default theme
import './resources/styles/default-theme.css';

const transformationsConfig = {
    // Remove the 'ellipsis' transformation loaded by the 'typography' group.
    remove: [ 'ellipsis' ]
}

// Colors to be used in the different palettes (font color, table cell background color, table cell border color, ...)
const colorsPalette = [
    {
        color: '#000000',
        label: 'Black'
    },
    {
        color: '#4D4D4D',
        label: 'Dim grey'
    },
    {
        color: '#999999',
        label: 'Grey'
    },
    {
        color: '#E6E6E6',
        label: 'Light grey'
    },
    {
        color: '#FFFFFF',
        label: 'White'
    },
    {
        color: '#E64D4D',
        label: 'Red'
    },
    {
        color: '#E6994D',
        label: 'Orange'
    },
    {
        color: '#E6E64D',
        label: 'Yellow'
    },
    {
        color: '#99E64D',
        label: 'Light green'
    },
    {
        color: '#4DE64D',
        label: 'Green'
    },
    {
        color: '#4DE699',
        label: 'Aquamarine'
    },
    {
        color: '#4DE6E6',
        label: 'Turquoise'
    },
    {
        color: '#4D99E6',
        label: 'Light blue'
    },
    {
        color: '#4D4DE6',
        label: 'Blue'
    },
    {
        color: '#994DE6',
        label: 'Purple'
    },
];

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
		List,
		Mention,
		Paragraph,
        PasteFromOffice,
		Strikethrough,
		Table,
		TableCaption,
		TableCellProperties,
		TableColumnResize,
		TableProperties,
		TableToolbar,
		Underline,
		Undo,
        RemoveFormat,
        SourceEditing,

		// combodo plugins
		AppendITopClasses,
		KeyboardShortcut,
		MentionsMarkup,
		TriggerUpdateOnReady,
		Maximize,
        // ObjectShortcut, // wait a clean implementation before adding it (mentions plugin allow this feature)
		InsertHtml,
		DetectChanges,
        UpdateInputOnChange,
        Disabler,
        InsertCarriageReturnAfterBlock
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
                'heading',
                '|',
                'alignment',
                '|',
                {
                    label: 'Fonts',
                    icon: 'text',
                    items: ['fontfamily', 'fontSize', 'fontColor']
                },
                '|',
                'bold',
                'italic',
                'underline',
                'highlight'  ,
                {
                    label: 'More styles',
                    items: ['strikethrough', 'RemoveFormat']
                },
				'|',
                'horizontalLine',
				'link',
				'imageUpload',
				'codeBlock',
				'bulletedList',
				'numberedList',
				'insertTable',
                '|',
                'SourceEditing',
			],
			shouldNotGroupWhenFull: true
		},
		language: 'en',
        fontColor: {
            // Colors are redefined to be in HEX instead of RGB in order to be supported by mail clients
            colors: colorsPalette,
        },
		image: {
			toolbar: [
                'resizeImage:25',
                'resizeImage:50',
                'resizeImage:original',
				'|',
                'toggleImageCaption',
			],
			resizeOptions: [
				{
					name: 'resizeImage:original',
					value: null,
					icon: 'original'
				},
				{
                    name: 'resizeImage:25',
                    value: '25',
                    icon: 'small'
                },
                {
					name: 'resizeImage:50',
					value: '50',
					icon: 'medium'
				},
			],
		},
		table: {
			contentToolbar: [
				'tableColumn',
				'tableRow',
				'mergeTableCells',
                '|',
				'tableCellProperties',
				'tableProperties',
                '|',
                'toggleTableCaption'
			],
            tableCellProperties: {
                borderColors: colorsPalette,
                backgroundColors: colorsPalette,
            },
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
		},
		highlight: {
			options: [
                { model: 'yellowMarker', class: 'marker-yellow', title: 'Yellow marker', color: '#FDFD77', type: 'marker' },
                { model: 'greenMarker', class: 'marker-green', title: 'Green marker', color: '#62f962', type: 'marker' },
                { model: 'pinkMarker', class: 'marker-pink', title: 'Pink marker', color: '#FC7899', type: 'marker' },
                { model: 'blueMarker', class: 'marker-blue', title: 'Blue marker', color: '#72CCFD', type: 'marker' },
			]
		},
		codeBlock: {
			// Languages defined here are only the values of the dropdown list
			// It needs to be aligned with languages imports for highlight.js in the code-blocks-highlight-js.plugin.ts
			languages: [
				{language: 'plaintext', label: 'Plain text'},	// Default
				{language: 'abap', label: 'ABAP'},
				{language: 'apache', label: 'Apache'},
				{language: 'bash', label: 'Bash'},
				{language: 'cs', label: 'C#'},
				{language: 'cpp', label: 'C++'},
				{language: 'css', label: 'CSS'},
				{language: 'ciscocli', label: 'Cisco CLI'},
				{language: 'coffeescript', label: 'CoffeeScript'},
				{language: 'curl', label: 'cURL'},
				{language: 'diff', label: 'Diff'},
				{language: 'dnszonefile', label: 'DNS Zone File'},
				{language: 'html', label: 'HTML'},
				{language: 'http', label: 'HTTP'},
				{language: 'ini', label: 'Ini'},
				{language: 'json', label: 'JSON'},
				{language: 'java', label: 'Java'},
				{language: 'javascript', label: 'JavaScript'},
				{language: 'makefile', label: 'Makefile'},
				{language: 'markdown', label: 'Markdown'},
				{language: 'nginx', label: 'Nginx'},
				{language: 'objectivec', label: 'Objective C'},
				{language: 'php', label: 'PHP'},
				{language: 'perl', label: 'Perl'},
				{language: 'python', label: 'Python'},
				{language: 'ruby', label: 'Ruby'},
				{language: 'rust', label: 'Rust'},
				{language: 'scss', label: 'SCSS'},
				{language: 'sql', label: 'SQL'},
				{language: 'toml', label: 'TOML'},
				{language: 'twig', label: 'TWIG'},
				{language: 'typescript', label: 'TypeScript'},
				{language: 'vba', label: 'VBA'},
				{language: 'vbscript', label: 'VBScript'},
				{language: 'xml', label: 'XML'},
				{language: 'yaml', label: 'YAML'}
			]
		},
	};
}

export default Editor;
