/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Some general purpose JS functions for the iTop application

/**
 * Reload a truncated list
 */
aTruncatedLists = {}; // To keep track of the list being loaded, each member is an ajaxRequest object

function ReloadTruncatedList(divId, sSerializedFilter, sExtraParams) {
	$('#'+divId).block();
	//$('#'+divId).blockUI();
	if (aTruncatedLists[divId] != undefined) {
		try {
			aAjaxRequest = aTruncatedLists[divId];
			aAjaxRequest.abort();
		} catch (e) {
			// Do nothing special, just continue
			console.log('Uh,uh, exception !');
		}
	}
	aTruncatedLists[divId] = $.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?style=list',
		{operation: 'ajax', filter: sSerializedFilter, extra_params: sExtraParams},
		function (data) {
			aTruncatedLists[divId] = undefined;
			if (data.length > 0) {
				$('#'+divId).html(data);
				//$('#'+divId+' .listResults').tableHover(); // hover tables
				$('#'+divId+' .listResults').each(function () {
					var table = $(this);
					var id = $(this).parent();
					aTruncatedLists[divId] = undefined;
					var checkbox = (table.find('th:first :checkbox').length > 0);
					if (checkbox) {
						// There is a checkbox in the first column, don't make it sortable
						table.tablesorter({headers: {0: {sorter: false}}, widgets: ['myZebra', 'truncatedList']}).tablesorterPager({container: $("#pager")}); // sortable and zebra tables
					} else {
						// There is NO checkbox in the first column, all columns are considered sortable
						table.tablesorter({widgets: ['myZebra', 'truncatedList']}).tablesorterPager({container: $("#pager"), totalRows: 97, filter: sSerializedFilter, extra_params: sExtraParams}); // sortable and zebra tables
					}
				});
				$('#'+divId).unblock();
			}
		}
	);
}

/**
 * Truncate a previously expanded list !
 */
function TruncateList(divId, iLimit, sNewLabel, sLinkLabel) {
	$('#'+divId).block();
	var iCount = 0;
	$('#'+divId+' table.listResults tr:gt('+iLimit+')').each(function () {
		$(this).remove();
	});
	$('#lbl_'+divId).html(sNewLabel);
	$('#'+divId+' table.listResults tr:last td').addClass('truncated');
	$('#'+divId+' table.listResults').addClass('truncated');
	$('#trc_'+divId).html(sLinkLabel);
	$('#'+divId+' .listResults').trigger("update"); //  Reset the cache
	$('#'+divId).unblock();
}

/**
 * Reload any block -- used for periodic auto-reload
 */
function ReloadBlock(divId, sStyle, sSerializedFilter, sExtraParams) {
	// Check if the user is not editing the list properties right now
	var bDialogOpen = false;
	//TODO 3.0.0 Datatable - to check
	var oDataTable = $('#'+divId+' .ibo-datatable');
	var bIsDataTable = false;
	if (oDataTable.length > 0) {
		bDialogOpen = ($('#datatable_dlg_datatable_'+divId+' :visible').length > 0);
		//bDialogOpen = oDataTable.datatable('IsDialogOpen');
		bIsDataTable = true;
	}
	if (!bDialogOpen) {
		if (bIsDataTable) {
			oDataTable.DataTable().clearPipeline();
			oDataTable.DataTable().ajax.reload(null, false);
		} else {
			$('#'+divId).block();

			$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?style='+sStyle,
				{operation: 'ajax', filter: sSerializedFilter, encoding: 'serialize', extra_params: sExtraParams},
				function (data) {
					$('#'+divId).empty();
					$('#'+divId).append(data);
					$('#'+divId).removeClass('loading');
				}
			);
		}
	}
}

function SaveGroupBySortOrder(sTableId, aValues) {
	var sDashboardId = $('#'+sTableId).closest('.ibo-dashboard').attr('id');
	var sPrefKey = 'GroupBy_'+sDashboardId+'_'+sTableId;
	if (aValues.length != 0) {
		$sValue = JSON.stringify(aValues);
		if (GetUserPreference(sPrefKey, null) != $sValue) {
			SetUserPreference(sPrefKey, $sValue, true);
		}
	}
}

function LoadGroupBySortOrder(sTableId) {
	var sDashboardId = $('#'+sTableId).closest('.ibo-dashboard').attr('id');
	var sPrefKey = 'GroupBy_'+sDashboardId+'_'+sTableId;
	var sValues = GetUserPreference(sPrefKey, null);
	if (sValues != null) {
		aValues = JSON.parse(sValues);
		window.setTimeout(function () {
			$('#'+sTableId+' table.listResults').trigger('sorton', [aValues]);
		}, 50);
	}

}

/**
 * Update the display and value of a file input widget when the user picks a new file
 */
function UpdateFileName(id, sNewFileName) {
	var aPath = sNewFileName.split('\\');
	var sNewFileName = aPath[aPath.length-1];

	$('#'+id).val(sNewFileName);
	$('#'+id).trigger('validate');
	$('#name_'+id).text(sNewFileName);
	if(sNewFileName=='') {
		$('#do_remove_'+id).val('1');
		$('#remove_attr_'+id).addClass('ibo-is-hidden');
	} else {
		$('#do_remove_'+id).val('0');
		$('#remove_attr_'+id).removeClass('ibo-is-hidden');
	}

	return true;
}

/**
 * Reload a search form for the specified class
 */
function ReloadSearchForm(divId, sClassName, sBaseClass, sContext, sTableId, sExtraParams) {
	var oDiv = $('#'+divId).parent();
	oDiv.block();
	// deprecated in jQuery 1.8 
	//var oFormEvents = $('#ds_'+divId+' form').data('events');
	var oForm = $('#'+divId+' form');
	var oFormEvents = $._data(oForm[0], "events");

	// Save the submit handlers
	aSubmit = new Array();
	if ((oFormEvents != null) && (oFormEvents.submit != undefined)) {
		for (var index = 0; index < oFormEvents.submit.length; index++) {
			aSubmit [index] = {data: oFormEvents.submit[index].data, namespace: oFormEvents.submit[index].namespace, handler: oFormEvents.submit[index].handler};
		}
	}
	sAction = $('#'+divId+' form').attr('action');

	// Save the current values in the form
	var oMap = {};
	$('#'+divId+" form :input[name!='']").each(function () {
		oMap[this.name] = this.value;
	});
	oMap.operation = 'search_form';
	oMap.className = sClassName;
	oMap.baseClass = sBaseClass;
	oMap.currentId = divId;
	oMap._table_id_ = sTableId;
	oMap.action = sAction;
	if(sExtraParams['selection_mode']) {
		oMap.selection_mode = sExtraParams['selection_mode'];
	}
	if(sExtraParams['result_list_outer_selector']) {
		oMap.result_list_outer_selector = sExtraParams['result_list_outer_selector'];
	}
	if(sExtraParams['cssCount']) {
		oMap.css_count = sExtraParams['cssCount'];
		$(sExtraParams['cssCount']).val(0).trigger('change');
	}
	if(sExtraParams['table_inner_id']) {
		oMap.table_inner_id = sExtraParams['table_inner_id'];
	} else{
		oMap.table_inner_id = sTableId;
	}

	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?'+sContext, oMap,
		function (data) {
			oDiv.empty();
			oDiv.append(data);
			oDiv.unblock();
			oDiv.parent().resize(); // Inform the parent that the form has just been (potentially) resized
			oDiv.find('form.search_form_handler').triggerHandler('itop.search.form.reloaded');
		}
	);
}

/**
 * Stores - in a persistent way - user specific preferences
 * depends on a global variable oUserPreferences created/filled by the iTopWebPage
 * that acts as a local -write through- cache
 */
function SetUserPreference(sPreferenceCode, sPrefValue, bPersistent) {
	sPreviousValue = undefined;
	try {
		sPreviousValue = oUserPreferences[sPreferenceCode];
	} catch (err) {
		sPreviousValue = undefined;
	}
	oUserPreferences[sPreferenceCode] = sPrefValue;
	if (bPersistent && (sPrefValue != sPreviousValue)) {
		ajax_request = $.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
			{operation: 'set_pref', code: sPreferenceCode, value: sPrefValue}); // Make it persistent
	}
}

/**
 * Get user specific preferences
 * that acts as a local -write through- cache
 * @borrows global variable oUserPreferences created/filled by the iTopWebPage if login method was called
 */
function GetUserPreference(sPreferenceCode, sDefaultValue) {
	var value = sDefaultValue;
	if ((typeof (oUserPreferences) !== 'undefined') && (typeof (oUserPreferences[sPreferenceCode]) !== 'undefined')) {
		value = oUserPreferences[sPreferenceCode];
	}
	return value;
}

/**
 * @param {string} sPreferenceCode
 * @param {boolean} bDefaultValue
 * @returns {boolean}
 * @since 3.0.0
 */
function GetUserPreferenceAsBoolean(sPreferenceCode, bDefaultValue) {
	let sVal = GetUserPreference(sPreferenceCode, bDefaultValue);
	try {
		sVal = sVal.toLowerCase();
	} catch (error) {
		// nothing : this may be the boolean default value !
	}

	if (sVal === "true") {
		return true;
	}
	if (sVal === "false") {
		return false;
	}

	return bDefaultValue;
}

/**
 * Check/uncheck a whole list of checkboxes
 */
function CheckAll(sSelector, bValue) {
	var value = bValue;
	$(sSelector).each(function () {
		if (this.checked != value) {
			this.checked = value;
			$(this).trigger('change');
		}
	});
}


/**
 * Toggle (enabled/disabled) the specified field of a form
 */
function ToggleField(value, field_id) {
	if (value) {
		$('#'+field_id).prop('disabled', false);
		if ($('#'+field_id).hasClass('selectized')) {
			$('#'+field_id)[0].selectize.enable();
		} else if ($('#'+field_id).parent().find('.ibo-input-select-autocomplete').length > 0) {
			$('#'+field_id).parent().find('.ibo-input-select-autocomplete').prop('disabled', false);
			$('#'+field_id).parent().find('.ibo-input-select--action-buttons').removeClass('ibo-is-hidden');
		} else {
			// In case the field is rendered as a div containing several inputs (e.g. RedundancySettings)
			$('#'+field_id+' :input').prop('disabled', false);
		}
	} else {
		$('#'+field_id).prop('disabled', true);
		if ($('#'+field_id).hasClass('selectized')) {
			$('#'+field_id)[0].selectize.disable();
		} else if ($('#'+field_id).parent().find('.ibo-input-select-autocomplete').length > 0) {
			$('#'+field_id).parent().find('.ibo-input-select-autocomplete').prop('disabled', true);
			$('#'+field_id).parent().find('.ibo-input-select--action-buttons').addClass('ibo-is-hidden');
		} else {
			// In case the field is rendered as a div containing several inputs (e.g. RedundancySettings)
			$('#'+field_id+' :input').prop('disabled', true);
		}
	}
	$('#'+field_id).trigger('update');
	$('#'+field_id).trigger('validate');
}

/**
 * For the fields that cannot be visually disabled, they can be blocked
 * @return
 */
function BlockField(field_id, bBlocked) {
	if (bBlocked) {
		$('#'+field_id).block({message: ' ** disabled ** ', enableValidation : true});
	} else {
		$('#'+field_id).unblock();
	}
}
/**
 * Updates (enables/disables) a "duration" field
 */
function ToggleDurationField(field_id) {
	// Toggle all the subfields that compose the "duration" input
	aSubFields = new Array('d', 'h', 'm', 's');

	if ($('#'+field_id).prop('disabled')) {
		for (var i = 0; i < aSubFields.length; i++) {
			$('#'+field_id+'_'+aSubFields[i]).prop('disabled', true);
		}
	} else {
		for (var i = 0; i < aSubFields.length; i++) {
			$('#'+field_id+'_'+aSubFields[i]).prop('disabled', false);
		}
	}
}

/**
 * PropagateCheckBox
 */
function PropagateCheckBox(bCurrValue, aFieldsList, bCheck) {
	if (bCurrValue == bCheck) {
		for (var i = 0; i < aFieldsList.length; i++) {
			var sFieldId = aFieldsList[i];
			$('#enable_'+sFieldId).prop('checked', bCheck);
			ToggleField(bCheck, sFieldId);

			// Cascade propagation
			$('#enable_'+sFieldId).trigger('change');
		}
	}
}

function FixTableSorter(table) {
	if (table[0].config == undefined) {
		// Table is not sort-able, let's fix it
		var checkbox = (table.find('th:first :checkbox').length > 0);
		if (checkbox) {
			// There is a checkbox in the first column, don't make it sort-able
			table.tablesorter({headers: {0: {sorter: false}}, widgets: ['myZebra', 'truncatedList']}); // sort-able and zebra tables
		} else {
			// There is NO checkbox in the first column, all columns are considered sort-able
			table.tablesorter({widgets: ['myZebra', 'truncatedList']}); // sort-able and zebra tables
		}
	}
}

function DashletCreationDlg(sOQL, sContext) {
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?'+sContext, {operation: 'dashlet_creation_dlg', oql: sOQL}, function (data) {
		$('body').append(data);
	});
	return false;
}

function ShortcutListDlg(sOQL, sDataTableId, sContext) {
	var sDataTableName = 'datatable_'+sDataTableId;
	var oTableSettings = {
		oColumns: $('#datatable_dlg_'+sDataTableName).DataTableSettings('GetColumns'),
		iPageSize: $('#'+sDataTableName).DataTable().ajax.params()['length']
	};
	var sTableSettings = JSON.stringify(oTableSettings);

	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?'+sContext, {operation: 'shortcut_list_dlg', oql: sOQL, table_settings: sTableSettings}, function (data) {
		$('body').append(data);
	});
	return false;
}

function ExportListDlg(sOQL, sDataTableId, sFormat, sDlgTitle) {
	var aFields = [];
	if (sDataTableId != '') {
		var sDataTableName = 'datatable_'+sDataTableId;
		var oColumns = $('#'+sDataTableName).DataTable().ajax.params()['columns'];
		for (var j in oColumns) {
			if (oColumns[j]['data']) {
				var sCode = oColumns[j]['data'].split("/");
				if (sCode[1] == '_key_') {
					sCode[1] = 'id';
				}
				aFields.push(sCode[0]+'.'+sCode[1]);
			} else {
				for (var k in oColumns[j]) {
					if (oColumns[j][k].checked) {
						var sCode = oColumns[j][k].code;
						if (sCode == '_key_') {
							sCode = 'id';
						}
						aFields.push(j+'.'+sCode);
					}
				}
			}
		}
	}

	var oParams = {
		interactive: 1,
		mode: 'dialog',
		expression: sOQL,
		suggested_fields: aFields.join(','),
		dialog_title: sDlgTitle
	};

	if (sFormat !== null) {
		oParams.format = sFormat;
	}

	$.post(GetAbsoluteUrlAppRoot()+'webservices/export-v2.php', oParams, function (data) {
		$('body').append(data);
	});
	return false;
}

function ExportToggleFormat(sFormat) {
	$('.form_part').hide();
	for (k in window.aFormParts[sFormat]) {
		$('#form_part_'+window.aFormParts[sFormat][k]).show().trigger('form-part-activate');
	}
}

function ExportStartExport() {
	var oParams = {};
	$('.form_part:visible :input').each(function () {
		if (this.name != '') {
			if ((this.type == 'radio') || (this.type == 'checkbox')) {
				if (this.checked) {
					oParams[this.name] = $(this).val();
				}
			} else {
				oParams[this.name] = $(this).val();
			}
		}
	});
	$('#export-form').addClass('ibo-is-hidden');
	$('#export-feedback').removeClass('ibo-is-hidden');
	oParams.operation = 'export_build';
	oParams.format = $('#export-form :input[name=format]').val();
	var sQueryMode = $(':input[name=query_mode]:checked').val();
	if ($(':input[name=query_mode]:checked').length > 0) {
		if (sQueryMode == 'oql') {
			oParams.expression = $('#export-form :input[name=expression]').val();
		} else {
			oParams.query = $('#export-form :input[name=query]').val();
		}
	} else {
		oParams.expression = $('#export-form :input[name=expression]').val();
		oParams.query = $('#export-form :input[name=query]').val();
	}
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function (data) {
			if (data == null) {
				ExportError('Export failed (no data provided), please contact your administrator');
			} else {
				ExportRun(data);
			}
		}, 'json')
		.fail(function (data) {
			ExportError('Export failed, please contact your administrator<br/>'+data.responseText);
		});
}

function ExportError(sMessage) {
	$('.export-message').html(sMessage);
	$('.export-progress-bar').hide();
	$('#export-btn').hide();
}

function ExportRun(data) {
	switch (data.code) {
		case 'run':
			// Continue
			$('.export-progress-bar').progressbar({value: data.percentage});
			$('.export-message').html(data.message);
			oParams = {};
			oParams.token = data.token;
			var sDataState = $('#export-form').attr('data-state');
			if (sDataState == 'cancelled') {
				oParams.operation = 'export_cancel';
			} else {
				oParams.operation = 'export_build';
			}

			$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function (data) {
					ExportRun(data);
				},
				'json');
			break;

		case 'done':
			$('#export-btn').hide();
			sMessage = '<a href="'+GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?operation=export_download&token='+data.token+'" target="_blank">'+data.message+'</a>';
			$('.export-message').html(sMessage);
			$('.export-progress-bar').hide();
			$('#export-form').attr('data-state', 'done');
			if (data.text_result != undefined) {
				if (data.mime_type == 'text/html') {
					$('#export_content').parent().html(data.text_result);
					$('#export_text_result').removeClass('ibo-is-hidden');
				} else {
					if ($('#export_text_result').closest('ui-dialog').length == 0) {
						// not inside a dialog box, adjust the height... approximately
						var jPane = $('#export_text_result').closest('.ui-layout-content');
						var iTotalHeight = jPane.height();
						jPane.children(':visible').each(function () {
							if ($(this).attr('id') != '') {
								iTotalHeight -= $(this).height();
							}
						});
						$('#export_content').height(iTotalHeight-80);
					}
					$('#export_content').val(data.text_result);
					$('#export_text_result').removeClass('ibo-is-hidden');
				}
			}
			$('#export-dlg-submit').button('option', 'label', Dict.S('UI:Button:Done')).button('enable');
			break;

		case 'error':
			$('#export-form').attr('data-state', 'error');
			$('.export-progress-bar').progressbar({value: data.percentage});
			$('.export-message').html(data.message);
			$('#export-dlg-submit').button('option', 'label', Dict.S('UI:Button:Done')).button('enable');
			$('#export-btn').hide();
		default:
	}
}

function ExportInitButton(sSelector) {
	$(sSelector).on('click', function () {
		var sDataState = $('#export-form').attr('data-state');
		switch (sDataState) {
			case 'not-yet-started':
				$('.form_part:visible').each(function () {
					$('#export-form').data('validation_messages', []);
					var ret = $(this).trigger('validate');
				});
				var aMessages = $('#export-form').data('validation_messages');

				if (aMessages.length > 0) {
					alert(aMessages.join(''));
					return;
				}
				if ($(this).hasClass('ui-button')) {
					$(this).button('option', 'label', Dict.S('UI:Button:Cancel'));
				} else {
					$(this).html(Dict.S('UI:Button:Cancel'));
				}
				$('#export-form').attr('data-state', 'running');
				ExportStartExport();
				break;

			case 'running':
				if ($(this).hasClass('ui-button')) {
					$(this).button('disable');
				} else {
					$(this).prop('disabled', true);
				}
				$('#export-form').attr('data-state', 'cancelled');
				break;

			case 'done':
			case 'error':
				$('#interactive_export_dlg').dialog('close');
				break;

			default:
			// Do nothing
		}
	});
}

/**
 * @deprecated 3.0.0 will be removed in 3.1, see N°3824
 */
function DisplayHistory(sSelector, sFilter, iCount, iStart) {
	$(sSelector).block();
	var oParams = {operation: 'history_from_filter', filter: sFilter, start: iStart, count: iCount};
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function (data) {
			$(sSelector).html(data).unblock();
		}
	);
}

/**
 * @deprecated 3.0.0 N°4367 deprecated, use {@see CombodoSanitizer.EscapeHtml} instead
 *
 * @param sValue value to escape
 * @param bReplaceAmp if false don't replace "&" (can be useful when sValue contains html entities we want to keep)
 * @returns {string} escaped value, ready to insert in the DOM without XSS risk
 *
 * @since 2.6.5, 2.7.2, 3.0.0 N°3332
 * @see https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html#rule-1-html-encode-before-inserting-untrusted-data-into-html-element-content
 * @see https://stackoverflow.com/questions/295566/sanitize-rewrite-html-on-the-client-side/430240#430240 why inserting in the DOM (for
 *        example the text() JQuery way) isn't safe
 */
function EncodeHtml(sValue, bReplaceAmp) {
	var sEncodedValue = (sValue+'')
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;')
		.replace(/"/g, '&quot;')
		.replace(/'/g, '&#x27;')
		.replace(/\//g, '&#x2F;');

	if (bReplaceAmp) {
		sEncodedValue = sEncodedValue.replace(/&/g, '&amp;');
	}

	return sEncodedValue;
}

// Very simple equivalent to format: placeholders are %1$s %2$d ...
function Format() {
	var args = [];
	var str = '';
	if (arguments[0] instanceof Array) {
		str = arguments[0][0].toString();
		args = arguments[0];
	} else {
		str = arguments[0].toString();
		if (arguments.length > 1) {
			var t = typeof arguments[1];
			args = ("string" === t || "number" === t) ? Array.prototype.slice.call(arguments) : arguments[1];
		}
	}
	var key;
	for (key in args) {
		str = str.replace(new RegExp("\\%"+key+"\\$.", "gi"), args[key]);
	}

	return str;
}

/**
 * Enable to access translation keys client side.
 * The called keys needs to be exported using \WebPage::add_dict_entry
 */
var Dict = {};
if (typeof aDictEntries == 'undefined') {
	Dict._entries = {}; // Entries have not been loaded (we are in the setup ?)
}
else {
	Dict._entries = aDictEntries; // Entries were loaded asynchronously via their own js files	
}
Dict.S = function (sEntry) {
	if (sEntry in Dict._entries) {
		return Dict._entries[sEntry];
	} else {
		return sEntry;
	}
};
Dict.Format = function () {
	var args = Array.from(arguments);
	args[0] = Dict.S(arguments[0]);
	return Format(args);
};

// TODO 3.0.0: Move functions above either in CombodoGlobalToolbox or CombodoBackofficeToolbox and deprecate them
/**
 * A toolbox for common JS operations accross the app no matter the GUI. Meant to be used by Combodo developers and the community.
 *
 * Note: All functions like those above should be moved in the corresponding toolbox to avoid name collision with other libs and scripts.
 *
 * @api
 * @since 3.0.0
 */
const CombodoGlobalToolbox = {
	/**
	 * Return true if oDOMElem is visible to the user, meaning that it is in the current viewport AND is not behind another element.
	 *
	 * @param oDOMElem {Object} DOM element to check
	 * @param bCompletely {boolean} Should oDOMElem be completely visible for the function to return true?
	 * @param iThreshold {integer} Use when bCompletely = true, a threshold in pixels to consider oDOMElem as completely visible. This is useful when elements are next to others as the browser can consider 1 pixel is overlapping the next element.
	 * @returns {boolean}
	 * @url: https://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport
	 */
	IsElementVisibleToTheUser: function (oDOMElem, bCompletely = false, iThreshold = 0) {
		const oRect = oDOMElem.getBoundingClientRect(),
			fViewportWidth = window.innerWidth || doc.documentElement.clientWidth,
			fViewportHeight = window.innerHeight || doc.documentElement.clientHeight,
			efp = function (x, y) {
				return document.elementFromPoint(x, y)
			};

		// Return false if it's not in the viewport
		if (oRect.right < 0 || oRect.bottom < 0
			|| oRect.left > fViewportWidth || oRect.top > fViewportHeight) {
			return false;
		}

		if (bCompletely === true) {
			// Return true if ALL of its four corners are visible
			return (
				oDOMElem.contains(efp(oRect.left+iThreshold, oRect.top+iThreshold))
				&& oDOMElem.contains(efp(oRect.right-iThreshold, oRect.top+iThreshold))
				&& oDOMElem.contains(efp(oRect.right-iThreshold, oRect.bottom-iThreshold))
				&& oDOMElem.contains(efp(oRect.left+iThreshold, oRect.bottom-iThreshold))
			);
		} else {
			// Return true if ANY of its four corners are visible
			return (
				oDOMElem.contains(efp(oRect.left, oRect.top))
				|| oDOMElem.contains(efp(oRect.right, oRect.top))
				|| oDOMElem.contains(efp(oRect.right, oRect.bottom))
				|| oDOMElem.contains(efp(oRect.left, oRect.bottom))
			);
		}
	},
	/**
	 * @param sUrl {string} The URL to append the new param to
	 * @param sParamName {string} Name of the parameter
	 * @param sParamValue {string} Value of the param, needs to be already URL encoded
	 * @return {string} The sUrl parameter with the sParamName / sParamValue append at the end of the query string (but before the hash if any)
	 */
	AddParameterToUrl: function(sUrl, sParamName, sParamValue)
	{
		const sNewParamForUrl = sParamName + '=' + sParamValue;

		// Split URL around the '#'. Note that if there are multiple '#' in the URL (which is not permitted!) this method won't work.
		const aHashParts = sUrl.split('#');
		// Part of the URL starting from the protocol to the character before the '#' if one, to the end of the URL otherwise
		const sPreHashPart = aHashParts[0];
		// Part of the URL starting just after the '#' if one, null otherwise
		const sPostHashPart = aHashParts[1] ?? null;

		sUrl = sPreHashPart + (sUrl.split('?')[1] ? '&' : '?') + sNewParamForUrl + (sPostHashPart !== null ? '#' + sPostHashPart : '');

		return sUrl;
	},
	/**
	 * This method should be a JS mirror of the PHP {@see utils::FilterXSS} method
	 *
	 * @param sInput {string} Input text to filter from XSS attacks
	 * @returns {string} The sInput string filtered from possible XSS attacks
	 */
	FilterXSS: function (sInput) {
		let sOutput = sInput;

		// Remove HTML script tags
		sOutput = sOutput.replace(/<script/g, '&lt;script WARNING: scripts are not allowed in tooltips');

		return sOutput;
	},
	/**
	 * Pause the JS activity for iDuration milliseconds
	 *
	 * @see N°2763 for the original code idea
	 * @return {void}
	 * @param iDuration {integer} Duration in milliseconds
	 */
	Pause: function (iDuration) {
		const oDate = new Date();
		let oCurrentDate = null;

		do {
			oCurrentDate = new Date();
		}
		while ((oCurrentDate - oDate) < iDuration);
	}
};

/**
 * Helper for tooltip instantiation (abstraction layer between iTop markup and tooltip plugin to ease its replacement in the future)
 *
 * Note: Content SHOULD be HTML entity encoded to avoid markup breaks (eg. when using a double quote in a sentence)
 *
 * @api
 * @since 3.0.0
 */
const CombodoTooltip = {
	/**
	 * Instantiate a tooltip on oElem from its data attributes
	 *
	 * Note: Content SHOULD be HTML entity encoded to avoid markup breaks (eg. when using a double quote in a sentence)
	 *
	 * @param {Object} oElem The jQuery object representing the element
	 * @param {boolean} bForce When set to true, tooltip will be instantiate even if one already exists, overwritting it.
	 */
	InitTooltipFromMarkup: function (oElem, bForce = false) {
		const oOptions = {};

		// First, check if the tooltip isn't already instantiated
		if ((oElem.attr('data-tooltip-instantiated') === 'true') && (bForce === false)) {
			return false;
		}
		else if((oElem.attr('data-tooltip-instantiated') === 'true') && (bForce === true) && (oElem[0]._tippy !== undefined)){
			oElem[0]._tippy.destroy();
		}

		// Content must be reworked before getting into the tooltip
		// - Should we enable HTML content or keep text as is
		const bEnableHTML = oElem.attr('data-tooltip-html-enabled') === 'true';
		oOptions['allowHTML'] = bEnableHTML;

		// - Content should be sanitized unless the developer says otherwise
		// Note: Condition is inversed on purpose. When the developer is instantiating a tooltip,
		// we want they to explicitly declare that they want the sanitizer to be skipped.
		// Whereas in this code, it's easier to follow the logic with the variable oriented this way.
		const bSanitizeContent = oElem.attr('data-tooltip-sanitizer-skipped') !== 'true';

		let sContent = oElem.attr('data-tooltip-content');
		// - Check if both HTML and sanitizer are enabled
		if (bEnableHTML && bSanitizeContent) {
			sContent = CombodoGlobalToolbox.FilterXSS(sContent);
		}
		oOptions['content'] = sContent;

		// Interaction (selection, click, ...) have to be enabled manually
		// Important: When set to true, if "data-tooltip-append-to" is not specified, tooltip will be append to the parent element instead of the body
		const bInteractive = oElem.attr('data-tooltip-interaction-enabled') === 'true';
		oOptions['interactive'] = bInteractive;

		// Element to append the tooltip to
		const sAppendToOriginalValue = oElem.attr('data-tooltip-append-to');
		let mAppendTo;

		if (sAppendToOriginalValue === undefined || sAppendToOriginalValue === '') {
			mAppendTo = null;
		} else if (sAppendToOriginalValue === 'body') {
			mAppendTo = document.body;
		} else if (sAppendToOriginalValue === 'parent') {
			mAppendTo = oElem.parent()[0];
		} else {
			// We have a selector, try to get the first matching element
			const oAppendToElems = $(sAppendToOriginalValue);
			if (oAppendToElems.length === 0) {
				CombodoJSConsole.Debug('CombodoTooltip: Could not create tooltip as there was no result for the element it should have been append to "'+sAppendToOriginalValue+'"');
				return false;
			} else {
				mAppendTo = oAppendToElems[0];
			}
		}

		// - Only set option if there is an actual value, otherwise, let the lib. handle it with it's default options
		if (mAppendTo !== null) {
			oOptions['appendTo'] = mAppendTo;
		}

		// Max. width overload
		const sMaxWidth = oElem.attr('data-tooltip-max-width');
		if ((sMaxWidth !== undefined) && (sMaxWidth !== '')) {
			oOptions['maxWidth'] = sMaxWidth;
		}

		oOptions['placement'] = oElem.attr('data-tooltip-placement') ?? 'top';
		oOptions['trigger'] = oElem.attr('data-tooltip-trigger') ?? 'mouseenter focus';

		const sShiftingOffset = oElem.attr('data-tooltip-shifting-offset');
		const sDistanceOffset = oElem.attr('data-tooltip-distance-offset');
		oOptions['offset'] = [
			(sShiftingOffset === undefined) ? 0 : parseInt(sShiftingOffset),
			(sDistanceOffset === undefined) ? 10 : parseInt(sDistanceOffset),
		];

		oOptions['animation'] = oElem.attr('data-tooltip-animation') ?? 'shift-away-subtle';

		const sShowDelay = oElem.attr('data-tooltip-show-delay');
		const sHideDelay = oElem.attr('data-tooltip-hide-delay');
		oOptions['delay'] = [
			(typeof sShowDelay === 'undefined') ? 200 : parseInt(sShowDelay),
			(typeof sHideDelay === 'undefined') ? null : parseInt(sHideDelay),
		];

		oOptions['theme'] = oElem.attr('data-tooltip-theme') ?? '';

		tippy(oElem[0], oOptions);

		// Mark tooltip as instantiated
		oElem.attr('data-tooltip-instantiated', 'true');
	},
	/**
	 * Instantiate all tooltips that are not already.
	 * Useful after AJAX calls or dynamic content modification for examples.
	 *
	 * @param {Object} oContainerElem Tooltips will only be instantiated if they are contained within this jQuery object
	 * @param {boolean} bForce Whether the tooltip instantiation should be forced or not (if already done)
	 */
	InitAllNonInstantiatedTooltips: function (oContainerElem = null, bForce = false) {
		if (oContainerElem === null) {
			oContainerElem = $('body');
		}

		oContainerElem.find('[data-tooltip-content]' + (bForce ? '' : ':not([data-tooltip-instantiated="true"])')).each(function () {
			CombodoTooltip.InitTooltipFromMarkup($(this), bForce);
		});
	},
	/**
	 * Instantiate a singleton for tooltips of elements matching sSelector.
	 * Used to guarantee that tooltips from said selector elements won't show at same time.
	 * Require selector elements tooltips to be instantiated before.
	 *
	 * @param {string} sSelector jQuery selector used to get elements tooltips
	 */
	InitSingletonFromSelector: function (sSelector) {
		let oTippyInstances = [];
		$(sSelector).each(function(){
			if($(this)[0]._tippy !== undefined){
				oTippyInstances.push($(this)[0]._tippy);
			}
		});
		let aOptions = {
			moveTransition: 'transform 0.2s ease-out',
		}
		tippy.createSingleton(oTippyInstances, $.extend(aOptions, oTippyInstances[0].props));
	}
};

/**
 * Helper to print messages in the browser JS console, use this instead of "console.xxx()" directly as this checks that the method exists.
 *
 * @api
 * @since 3.0.0
 */
const CombodoJSConsole = {
	/**
	 * @param sMessage {string} Message to output in the JS console
	 * @param sLevel {string} Console canal to use for the output, values can be log|debug|warn|error, default is log
	 * @returns {boolean}
	 * @internal
	 */
	_Trace: function(sMessage, sLevel = 'log') {
		// Check if browser has JS console
		if (!window.console) {
			return false;
		}

		// Check if browser has the wanted log level
		if (!window.console[sLevel]) {
			sLevel = 'log';
		}

		window.console[sLevel](sMessage);
	},
	/**
	 * Equivalent of a "console.log(sMessage)"
	 *
	 * @param sMessage {string}
	 */
	Log: function(sMessage) {
		this._Trace(sMessage, 'log');
	},
	/**
	 * Equivalent of a "console.info(sMessage)"
	 *
	 * @param sMessage {string}
	 */
	Info: function(sMessage) {
		this._Trace(sMessage, 'info');
	},
	/**
	 * Equivalent of a "console.debug(sMessage)"
	 *
	 * @param sMessage {string}
	 */
	Debug: function(sMessage) {
		this._Trace(sMessage, 'debug');
	},
	/**
	 * Equivalent of a "console.warn(sMessage)"
	 *
	 * @param sMessage {string}
	 */
	Warn: function(sMessage) {
		this._Trace(sMessage, 'warn');
	},
	/**
	 * Equivalent of a "console.error(sMessage)"
	 *
	 * @param sMessage {string}
	 */
	Error: function(sMessage) {
		this._Trace(sMessage, 'error');
	}
}

/**
 * Helper to Sanitize string
 *
 * Note: Same as in php (see \utils::Sanitize)
 *
 * @api
 * @since 2.6.5 2.7.6 3.0.0 N°4367
 */
const CombodoSanitizer = {
	ENUM_SANITIZATION_FILTER_INTEGER: 'integer',
	ENUM_SANITIZATION_FILTER_STRING: 'string',
	ENUM_SANITIZATION_FILTER_CONTEXT_PARAM: 'context_param',
	ENUM_SANITIZATION_FILTER_PARAMETER: 'parameter',
	ENUM_SANITIZATION_FILTER_FIELD_NAME: 'field_name',
	ENUM_SANITIZATION_FILTER_TRANSACTION_ID: 'transaction_id',
	ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER: 'element_identifier',
	ENUM_SANITIZATION_FILTER_VARIABLE_NAME: 'variable_name',

	/**
	 * @param {String} sValue The string to sanitize
	 * @param {String} sDefaultValue The string to return if sValue not match (used for some filters)
	 * @param {String} sSanitizationFilter one of the ENUM_SANITIZATION_FILTERs
	 */
	Sanitize: function (sValue, sDefaultValue, sSanitizationFilter) {
		switch (sSanitizationFilter) {
			case CombodoSanitizer.ENUM_SANITIZATION_FILTER_INTEGER:
				return this._CleanString(sValue, sDefaultValue, /[^0-9-+]*/g);

			case CombodoSanitizer.ENUM_SANITIZATION_FILTER_STRING:
				return $("<div>").text(sValue).text();

			case CombodoSanitizer.ENUM_SANITIZATION_FILTER_TRANSACTION_ID:
				return this._ReplaceString(sValue, sDefaultValue, /^([\. A-Za-z0-9_=-]*)$/g, '');

			case CombodoSanitizer.ENUM_SANITIZATION_FILTER_PARAMETER:
				return this._ReplaceString(sValue, sDefaultValue, /^([ A-Za-z0-9_=-]*)$/g);

			case CombodoSanitizer.ENUM_SANITIZATION_FILTER_FIELD_NAME:
				return this._ReplaceString(sValue, sDefaultValue, /^[A-Za-z0-9_]+(->[A-Za-z0-9_]+)*$/g);

			case CombodoSanitizer.ENUM_SANITIZATION_FILTER_CONTEXT_PARAM:
				return this._ReplaceString(sValue, sDefaultValue, /^[ A-Za-z0-9_=%:+-]*$/g);

			case CombodoSanitizer.ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER:
				return this._CleanString(sValue, sDefaultValue, /[^a-zA-Z0-9_-]/g);

			case CombodoSanitizer.ENUM_SANITIZATION_FILTER_VARIABLE_NAME:
				return this._CleanString(sValue, sDefaultValue, /[^a-zA-Z0-9_]/g);

		}
		return sDefaultValue;
	},
	_CleanString: function (sValue, sDefaultValue, sRegExp) {
		return sValue.replace(sRegExp, '');
	},

	_ReplaceString: function (sValue, sDefaultValue, sRegExp) {
		if (sRegExp.test(sValue)) {
			return sValue;
		} else {
			return sDefaultValue;
		}
	},

	/**
	 * @param sValue value to escape
	 * @param bReplaceAmp if false don't replace "&" (can be useful when sValue contains html entities we want to keep)
	 *
	 * @returns {string} escaped value, ready to insert in the DOM without XSS risk
	 *
	 * @since 2.6.5, 2.7.2, 3.0.0 N°3332
	 * @since 3.0.0 N°4367 deprecate EncodeHtml and copy the method here (CombodoSanitizer.EscapeHtml)
	 *
	 * @see https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html#rule-1-html-encode-before-inserting-untrusted-data-into-html-element-content
	 * @see https://stackoverflow.com/questions/295566/sanitize-rewrite-html-on-the-client-side/430240#430240 why inserting in the DOM (for
	 *        example the text() JQuery way) isn't safe
	 */
	EscapeHtml: function (sValue, bReplaceAmp) {
		if (bReplaceAmp) {
			return $('<div/>').text(sValue).html();
		}

		let sEncodedValue = (sValue+'')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#x27;')
			.replace(/\//g, '&#x2F;');

		return sEncodedValue;
	}
};

/**
 * Helper for InlineImages
 * @since 3.0.0
 */
const CombodoInlineImage = {
	/**
	 * Max width to apply on inline images
	 */
	max_width: 600,
	/**
	 * @param sMaxWidth {string} {@see CombodoInlineImage.max_width}
	 */
	SetMaxWidth: function (sMaxWidth) {
		this.max_width = sMaxWidth;
	},
	/**
	 * Apply the {@see CombodoInlineImage.max_width} to all inline images
	 */
	FixImagesWidth: function () {
		$('img[data-img-id]').each(function() {
			if ($(this).width() > CombodoInlineImage.max_width)
			{
				$(this).css({'max-width': CombodoInlineImage.max_width+'px', width: '', height: '', 'max-height': ''});
			}
			$(this).addClass('inline-image').attr('href', $(this).attr('src'));
		}).magnificPopup({type: 'image', closeOnContentClick: true });
	}
}