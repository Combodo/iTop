'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const {test} = require('node:test');

const sAppRootPath = path.resolve(__dirname, '../../../../../../');
const sCkEditorHandlerPath = path.join(sAppRootPath, 'js/ckeditor.handler.js');
const sActivityPanelPath = path.join(sAppRootPath, 'js/layouts/activity-panel/activity-panel.js');

test('CKEditor creation rejects when failure propagation is requested', async () => {
	const oContext = {
		$: () => [{}],
		ClassicEditor: {
			create: () => Promise.reject(new Error('CKEditor initialization failed')),
		},
		console: {
			error: () => {},
		},
	};
	vm.createContext(oContext);
	vm.runInContext(
		fs.readFileSync(sCkEditorHandlerPath, 'utf8')+'\nglobalThis.__testHandler = CombodoCKEditorHandler;',
		oContext
	);

	await assert.rejects(
		oContext.__testHandler.CreateInstance('#editor', {}, true),
		(oError) => oError.message === 'CKEditor initialization failed'
	);
});

test('activity panel rejects an undefined editor payload before posting', () => {
	let oWidgetDefinition;
	let iAjaxCallCount = 0;
	let bPreventedDefault = false;
	const $ = function (mArgument) {
		if (typeof mArgument === 'function') {
			mArgument();
		}

		return {};
	};
	$.widget = (sName, oDefinition) => {
		assert.equal(sName, 'itop.activity_panel');
		oWidgetDefinition = oDefinition;
	};
	$.post = () => {
		iAjaxCallCount++;
	};

	const oContext = {
		$,
		CombodoCKEditorHandler: {
			GetInstanceSynchronous: (sSelector) => {
				assert.equal(sSelector, '#case-log-editor');
				return {
					getData: () => 'undefined',
				};
			},
		},
	};
	vm.createContext(oContext);
	vm.runInContext(fs.readFileSync(sActivityPanelPath, 'utf8'), oContext);

	const oEditorElement = {
		attr: (sName) => (sName === 'id' ? 'case-log-editor' : undefined),
	};
	const sActionButtonsSelector = oWidgetDefinition.js_selectors.caselog_edition_save+', '+oWidgetDefinition.js_selectors.caselog_edition_cancel;
	const oActionButtons = {};
	const oFormElement = {
		find: (sSelector) => {
			if (sSelector === oWidgetDefinition.js_selectors.caselog_edition_editor) {
				return oEditorElement;
			}
			assert.equal(sSelector, sActionButtonsSelector);
			return oActionButtons;
		},
	};
	const oEntryElement = {
		find: (sSelector) => {
			assert.equal(sSelector, oWidgetDefinition.js_selectors.caselog_edition_form);
			return oFormElement;
		},
		attr: (sName) => ({
			'data-entry-caselog-index': '0',
			'data-entry-caselog-attribute-code': 'private_log',
		})[sName],
	};
	const oActivityPanel = Object.create(oWidgetDefinition);
	let bEditionErrorShown = false;
	oActivityPanel._ShowCaseLogEditionError = (oEntry) => {
		assert.equal(oEntry, oEntryElement);
		bEditionErrorShown = true;
	};

	oActivityPanel._onCaseLogEditionSaveClick({
		preventDefault: () => {
			bPreventedDefault = true;
		},
	}, oEntryElement);

	assert.equal(bPreventedDefault, true);
	assert.equal(bEditionErrorShown, true);
	assert.equal(iAjaxCallCount, 0);
});
