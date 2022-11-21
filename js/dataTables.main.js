/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

function checkAllDataTable(tableId, value, listId) {
	let tableSelector = $('#'+tableId);
	// Set the 'selectionMode' for the future objects to load
	let selectionMode = 'positive';
	if (value) {
		selectionMode = 'negative';
	}
	window['oSelectedItems'+CombodoSanitizer.Sanitize(listId, '', CombodoSanitizer.ENUM_SANITIZATION_FILTER_VARIABLE_NAME)] = [];
	// Mark all the displayed items as check or unchecked depending on the value
	tableSelector.find(':checkbox[name^=selectObj]:not([disabled])').each(function () {
		let currentCheckbox = $(this);
		currentCheckbox.prop('checked', value);
		let currentLine = currentCheckbox.closest("tr");
		(value) ? currentLine.addClass("selected") : currentLine.removeClass("selected");
	});

	tableSelector.closest(".dataTables_wrapper").parent().find(':input[name=selectionMode]').val(selectionMode);
	// Reset the list of saved selection...
	$(':input[name^=storedSelection]').remove();
	tableSelector.parent().find(':checkbox[name^=selectObj]').trigger("change");

	if (value) {
		tableSelector.DataTable().rows().select();
	} else {
		tableSelector.DataTable().rows({page: 'current'}).deselect();
	}
	updateDataTableSelection(listId, tableId);

	return true;
}

function updateDataTableSelection(listId, tableId) {
	let selectionContainer = $('#'+listId+' [data-target="ibo-datatable--selection"]');
	let selectionCount = $('#'+listId+' [name="selectionCount"]');
	let selectionMode = $('#'+listId+' [name=selectionMode]').val();

	selectionContainer.html('');
	let currentSelection = window['oSelectedItems'+CombodoSanitizer.Sanitize(listId, '', CombodoSanitizer.ENUM_SANITIZATION_FILTER_VARIABLE_NAME)];
	for (let i in currentSelection) {
		let value = currentSelection[i];
		selectionContainer.append('<input type="hidden" name="storedSelection[]" value="'+value+'">');
	}

	if (selectionMode === 'negative') {
		let total = $('#'+tableId).DataTable().page.info()["recordsTotal"];
		selectionCount.val(total-currentSelection.length);
		$('#'+tableId).closest('.ibo-panel').find('.ibo-datatable--selected-count').html(total-currentSelection.length);
	} else {
		selectionCount.val(currentSelection.length);
		$('#'+tableId).closest('.ibo-panel').find('.ibo-datatable--selected-count').html(currentSelection.length);
	}

	selectionCount.trigger('change');
}

function getMultipleSelectionParams(listId)
{
	var oRes = {};

	oRes.selectionMode = '';
	if ($('#'+listId+' [name=selectionMode]').length > 0) {
		oRes.selectionMode =  $('#'+listId+' [name=selectionMode]').val();
	}

	oRes.selectObject = [];
	$('#'+listId+' [name^=selectObject]:checked').each(function() {
		oRes.selectObject.push($(this).val());
	});

	oRes.storedSelection = [];
	$('#'+listId+' [name^=storedSelection]').each(function() {
		oRes.storedSelection.push($(this).val());
	});

	return oRes;
}

/**
 * Return column JSON declaration for row actions.
 * Could be part of column or columnDefs declaration of datatable.js.
 *
 * @param sTableId
 * @param iColumnTargetIndex
 * @returns {*}
 * @since 3.1.0
 */
function getRowActionsColumnDefinition(sTableId, iColumnTargetIndex = -1)
{
	let aColumn = {
		type: "html",
		orderable: false,
		render: function ( data, type, row, meta ) {
			return $(`#${sTableId}_actions_buttons_template`).html();
		}
	};

	if (iColumnTargetIndex !== -1) {
		aColumn['targets'] = iColumnTargetIndex;
	}

	return aColumn;
}


/**
 * HandleActionRow.
 *
 * @param sTitle title for confirmation dialog
 * @param sMessage message of the confirmation dialog
 * @param sDoNotShowAgainPreferenceKey iTop preference key to store "do not show again" flag
 * @param oConfirmHandler confirm button handler
 * @param aConfirmHandlerData confirm button handler data
 * @constructor
 */
const TABLE_ACTION_CONFIRMATION_PREFIX = 'table_action_row';
const TABLE_ACTION_CONFIRMATION_DIALOG_SELECTOR = '[data-role="ibo-datatable--row-action--confirmation-dialog"]';
const HandleActionRow = function (sTitle, sMessage, sDoNotShowAgainPreferenceKey, oConfirmHandler, aConfirmHandlerData){

	// confirmation preference
	if(sDoNotShowAgainPreferenceKey != null){

		// retrieve need confirmation user preference
		let bNeedConfirmation = GetUserPreferenceAsBoolean(`${TABLE_ACTION_CONFIRMATION_PREFIX}.${sDoNotShowAgainPreferenceKey}`, true);

		// confirm handler if no confirmation requested
		if(!bNeedConfirmation){
			oConfirmHandler(aConfirmHandlerData.datatable, aConfirmHandlerData.tr_element, aConfirmHandlerData.action_id, aConfirmHandlerData.row_data);
			return;
		}
	}

	// fill confirmation dialog
	$('.ibo-abstract-block-links-view-table--action-confirmation-explanation', $(TABLE_ACTION_CONFIRMATION_DIALOG_SELECTOR)).html(sMessage);
	$('.ibo-abstract-block-links-view-table--action-confirmation-preference', $(TABLE_ACTION_CONFIRMATION_DIALOG_SELECTOR)).toggle(sDoNotShowAgainPreferenceKey != null);

	// open confirmation dialog
	$(TABLE_ACTION_CONFIRMATION_DIALOG_SELECTOR).dialog({
		autoOpen: false,
		minWidth: 400,
		modal: true,
		title: sTitle,
		autoOpen: true,
		position: {my: "center center", at: "center center", of: $('body')},
		close: function () {
			// destroy dialog object
			$(TABLE_ACTION_CONFIRMATION_DIALOG_SELECTOR).dialog( "destroy" );
		},
		buttons: [
			{
				text: Dict.S('UI:Button:Cancel'),
				class: 'ibo-is-alternative',
				click: function () {
					// close dialog
					$(TABLE_ACTION_CONFIRMATION_DIALOG_SELECTOR).dialog('close');
				}
			},
			{
				text: Dict.S('UI:Button:Ok'),
				class: 'ibo-is-primary',
				click: function () {
					// handle "do not show again" user preference
					if(sDoNotShowAgainPreferenceKey != null){
						// save preference
						const bDoNotShowAgain = $(this).find($('.ibo-abstract-block-links-view-table--action-confirmation-preference-input')).prop('checked');
						if (bDoNotShowAgain) {
							SetUserPreference(`${TABLE_ACTION_CONFIRMATION_PREFIX}.${sDoNotShowAgainPreferenceKey}`, 'false', true);
						}
					}
					// call confirm handler and close dialog
					if(oConfirmHandler(aConfirmHandlerData.datatable, aConfirmHandlerData.tr_element, aConfirmHandlerData.action_id, aConfirmHandlerData.row_data)){
						$(TABLE_ACTION_CONFIRMATION_DIALOG_SELECTOR).dialog('close');
					}
				}
			},
		],
	});
}
