/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/*
 * Here we should put all the "static" (no variable) JS code that needs to be evaluated on DOM ready
 */

$(document).ready(function () {
	// AJAX calls handling
	// - Error messages regarding the error code
	$(document).ajaxError(function (event, jqxhr, options) {
		// User is not logged in
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
								try {
									// Try to reload the page so the login form redirects on the current page automatically
									// Note: We don't use window.location.reload() as it could be a potential vulnerability. Indeed, if the previous page was a login form, the data would be posted as-is again without prompting the user, auto-logging them, which would give access to the foe.
									window.location.href = CombodoGlobalToolbox.AddParameterToUrl(window.location.href, 'login_again', Date.now());
								} catch (oError) {
									// In case of exception, redirect to the login page
									window.location.href = GetAbsoluteUrlAppRoot()+'pages/UI.php';
								}
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

	// jQuery dialogs handling
	// - Force dialog to be stacked correctly
	//
	//   Note: This should be handle natively by jQuery, but we couldn't find why it's not.
	//         Might be related to the fact that we set the content on the pre-existing dialog content instead of passing it as an option.
	$('body').on('dialogopen', '.ui-dialog-content', function(oEvent, oUI) {
		$(this).dialog('moveToTop');

		let oDialogElem = $(this).dialog('instance').uiDialog;
		oDialogElem.next('.ui-widget-overlay').css('z-index', oDialogElem.css('z-index') - 1);
	} );

	// Initialize leave handler when a form with touched fields is about to be closed
	$('body').leave_handler({
		'message': Dict.S('UI:NavigateAwayConfirmationMessage'),
		'extra_events': {
			'body': ['dialogbeforeclose'] // jQueryUI dialog
		}
	});
});
