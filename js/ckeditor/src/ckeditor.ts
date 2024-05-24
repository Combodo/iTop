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

// You can read more about extending the build with additional plugins in the "Installing plugins" guide.
// See https://ckeditor.com/docs/ckeditor5/latest/installation/plugins/installing-plugins.html for details.

// iTop default theme
import './resources/styles/default-theme.css';

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
        // ObjectShortcut, // wait a clean implementation before adding it (mentions plugin allow this feature)
		InsertHtml,
		DetectChanges,
        UpdateInputOnChange,
        Disabler
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
				'bold',
				'italic',
				'underline',
                'fontSize',
                'fontColor',
                'highlight',
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
                'resizeImage:25',
                'resizeImage:50',
                'resizeImage:original',
				'|',
                'imageStyle:alignLeft',
                'imageStyle:alignCenter',
                'imageStyle:alignRight',
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
		},
		highlight: {
			options: [
				{
					model: 'yellowMarker',
					class: 'marker-yellow',
					title: 'Yellow marker',
					color: 'var(--ck-highlight-marker-yellow)',
					type: 'marker'
				},
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
		}
	};
}

export default Editor;
