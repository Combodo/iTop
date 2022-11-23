/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

const TABLE_ACTION_CONFIRMATION_PREFIX = 'table_action_row';
const TABLE_ACTION_CONFIRMATION_DIALOG_SELECTOR = '#table-row-action-confirmation-dialog';

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
 * HandleActionRowConfirmation.
 *
 * @param sTitle title for confirmation dialog
 * @param sMessage message of the confirmation dialog
 * @param sDoNotShowAgainPreferenceKey iTop preference key to store "do not show again" flag
 * @param oConfirmHandler confirm button handler
 * @param aConfirmHandlerData confirm button handler data
 * @constructor
 */
const HandleActionRowConfirmation = function (sTitle, sMessage, sDoNotShowAgainPreferenceKey, oConfirmHandler, aConfirmHandlerData){

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
	$('.ibo-row-action--confirmation--explanation', $(TABLE_ACTION_CONFIRMATION_DIALOG_SELECTOR)).html(sMessage);
	$('.ibo-row-action--confirmation--do-not-show-again', $(TABLE_ACTION_CONFIRMATION_DIALOG_SELECTOR)).toggle(sDoNotShowAgainPreferenceKey != null);

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
						const bDoNotShowAgain = $(this).find($('.ibo-row-action--confirmation--do-not-show-again--checkbox')).prop('checked');
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
