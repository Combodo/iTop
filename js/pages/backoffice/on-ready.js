/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/*
 * Here we should put all the "static" (no variable) JS code that needs to be evaluated on DOM ready
 */

$(document).ready(function () {
	// AJAX calls handling
	// - Custom headers
	$(document).ajaxSend(function (event, jqxhr, options) {
		jqxhr.setRequestHeader('X-Combodo-Ajax', 'true');
	});
	// - Error messages regarding the error code
	$(document).ajaxError(function (event, jqxhr, options) {
		// User is not logged ing
		if (jqxhr.status == 401) {
			const oUserDisconnectedDialog = $('#ibo-user-disconnected-dialog');
			// Create dialog widget if not already instantiated
			if (oUserDisconnectedDialog.data('uiDialog') === undefined) {
				oUserDisconnectedDialog.removeClass('ibo-is-hidden');
				oUserDisconnectedDialog.dialog({
					modal: true,
					title: Dict.S('UI:DisconnectedDlgTitle'),
					close: function () {
						$(this).dialog('close');
					},
					minWidth: 400,
					buttons: [
						{
							text: Dict.S('UI:LoginAgain'),
							click: function () {
								window.location.href = GetAbsoluteUrlAppRoot()+'pages/UI.php'
							}
						},
						{
							text: Dict.S('UI:StayOnThePage'),
							click: function () {
								$(this).dialog('close');
							}
						}
					]
				});
			}
			// Just open it otherwise
			else {
				oUserDisconnectedDialog.dialog('open');
			}
		}
	});
	// - Successful
	$(document).ajaxSuccess(function () {
		// Async. markup, small timeout to allow markup to be built if necessary
		setTimeout(function () {
			CombodoTooltip.InitAllNonInstantiatedTooltips();
			CombodoBackofficeToolbox.InitCodeHighlighting();
			// Initialize date / datetime pickers if needed
			PrepareWidgets();
		}, 500);
	});
});
