/*
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

// Helpers
function ShowAboutBox(sTitle)
{
	var loadingDialog = $('<div id="ibo-about-box--loader"></div>');
	loadingDialog.dialog( {title:sTitle,autoOpen: true, modal: true, width: 700, height:350});
	$('#ibo-about-box--loader').block();
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'about_box'}, function(data){
		$('#ibo-about-box--loader').unblock();
		$('body').append(data);
	}).always(function() {
		loadingDialog.empty();
		loadingDialog.remove();
	});
	return false;
}
function ShowDebug()
{
	if ($('#ibo-raw-output').html() !== '')
	{
		$('#ibo-raw-output')
			// Note: We remove the CSS class before opening the dialog, otherwise the dialog will not be positioned correctly due to its content being still hidden
			.removeClass('ibo-is-hidden')
			.dialog( {autoOpen: true, modal: true, width: '80%', maxHeight: window.innerHeight * 0.8});
	}
}
function ArchiveMode(bEnable)
{
	var sPrevUrl = StripArchiveArgument(window.location.search);
	if (bEnable)
	{
		window.location.search = sPrevUrl + '&with-archive=1';
	}
	else
	{
		window.location.search = sPrevUrl + '&with-archive=0';
	}
}
function StripArchiveArgument(sUrl)
{
	var res = sUrl.replace(/&with-archive=[01]/g, '');
	return res;
}
function goBack()
{
	window.history.back();
}
function BackToDetails(sClass, id, sDefaultUrl, sOwnershipToken)
{
	window.bInCancel = true;
	if (id > 0)
	{
		sToken = '';
		if (sOwnershipToken != undefined)
		{
			sToken = '&token='+sOwnershipToken;
		}
		window.location.href = AddAppContext(GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=release_lock_and_details&class='+sClass+'&id='+id+sToken);
	}
	else
	{
		window.location.href = sDefaultUrl; // Already contains the context...
	}
}
function BackToList(sClass)
{
	window.location.href = AddAppContext(GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=search_oql&oql_class='+sClass+'&oql_clause=WHERE id=0');
}

/**
 * A toolbox for common JS operations in the backoffice. Meant to be used by Combodo developers and the community.
 * @api
 * @since 3.0.0
 */
const CombodoBackofficeToolbox = {
	/**
	 * Make the oElem enter the fullscreen mode, meaning that it will take all the screen and be above everything else.
	 *
	 * @param {object} oElem The jQuery object of the element
	 * @constructor
	 */
	EnterFullscreenForElement: function(oElem) {
		oElem.parents().addClass('ibo-has-fullscreen-descendant');
		oElem.addClass('ibo-is-fullscreen');
	},
	/**
	 * Make the oElem exit the fullscreen mode.
	 * If none passed, all fullscreen elements will be removed from it
	 *
	 * @param {object|null} oElem The jQuery object of the element
	 * @constructor
	 */
	ExitFullscreenForElement: function(oElem = null) {
		// If no element passed, remove any element from fullscreen mode
		if(oElem === null) {
			$(document).find('.ibo-has-fullscreen-descendant').removeClass('ibo-has-fullscreen-descendant');
			$(document).find('.ibo-is-fullscreen').removeClass('ibo-is-fullscreen');
		}
		else {
			oElem.parents().removeClass('ibo-has-fullscreen-descendant');
			oElem.removeClass('ibo-is-fullscreen');
		}
	},
	/**
	 * Make the oElem enter or exit the fullscreen mode depending on it's current state.
	 *
	 * @param {object} oElem The jQuery object of the element
	 * @constructor
	 */
	ToggleFullscreenForElement: function (oElem) {
		if (oElem.hasClass('ibo-is-fullscreen')) {
			this.ExitFullscreenForElement(oElem);
		} else {
			this.EnterFullscreenForElement(oElem);
		}
	},

	/**
	 * Initialize the code highlighting on elements
	 *
	 * @param {Object} oContainerElem code highlighting will only be init. on elements within the container
	 * @param {boolean} bForce Whether the highlighting should be forced or not (if already done)
	 * @return {void}
	 * @constructor
	 */
	InitCodeHighlighting: function (oContainerElem = null, bForce = false) {
		if (oContainerElem === null) {
			oContainerElem = $('body');
		}

		const sComplementarySelector = bForce ? '' : ':not(.hljs)';

		// AttributeHTML and HTML AttributeText
		let oCodeElements = oContainerElem.find('[data-attribute-type="AttributeHTML"], [data-attribute-type="AttributeText"], [data-attribute-type="AttributeTemplateHTML"]').find('.HTML pre > code'+sComplementarySelector);
		if (oCodeElements.length > 0) {
			if (typeof hljs === 'undefined') {
				CombodoJSConsole.Error('Cannot format code snippets in HTML fields as the highlight.js lib is not loaded');
			} else {
				oCodeElements.each(function (iIdx, oElem) {
					hljs.highlightBlock(oElem);
					$(oElem).parent().addClass('common-hljs-container');
				});
			}
		}

		// CaseLogs
		oCodeElements = oContainerElem.find('[data-role="ibo-activity-entry--main-information-content"] pre > code'+sComplementarySelector);
		if (oCodeElements.length > 0) {
			if (typeof hljs === 'undefined') {
				CombodoJSConsole.Error('Cannot format code snippets in log entries as the highlight.js lib is not loaded');
			} else {
				oCodeElements.each(function (iIdx, oElem) {
					hljs.highlightBlock(oElem);
					$(oElem).parent().addClass('common-hljs-container');
				});
			}
		}
	}
};

/**
 * @override
 * @inheritDoc
 */
CombodoModal.CloseAllModals = function() {
	$('.ui-dialog .ui-dialog-content').each(function () {
		// Don't try to close dialog if not instantiated yet
		if ($(this).dialog('instance') === undefined) {
			return false;
		}

		$(this).dialog('close');
	});
};
/**
 * @override
 * @inheritDoc
 */
CombodoModal._GetDefaultBaseModalSelector = function() {
	return '#ibo-modal-template';
};
/**
 * @override
 * @inheritDoc
 */
CombodoModal._InstantiateModal = function(oModalElem, oOptions) {
	const me = this;

	// Default options of the jQuery Dialog widget
	let oJQueryOptions = {
		width: 'auto',
		height: 'auto',
		modal: oOptions.extra_options.modal ?? true,
		classes: oOptions.classes ?? {},
		close: oOptions.extra_options.callback_on_modal_close,
		autoOpen: oOptions.auto_open,
		title: oOptions.title,
		buttons: this._ConvertButtonDefinition(oOptions.buttons)
	};
	
	const aSizeMap = {
		'xs': 'extra-small',
		's': 'small',
		'md': 'medium',
		'lg': 'large',
	};

	// Resize to desired size
	switch (typeof oOptions.size) {
		case 'string':
			if(aSizeMap[oOptions.size] !== undefined && aSizeMap[oOptions.size] !== 'auto') {
				let sSize = 'ibo-is-' + aSizeMap[oOptions.size];
				if (oJQueryOptions.classes['ui-dialog-content'] !== undefined) {
					oJQueryOptions.classes['ui-dialog-content'] += ' ' + sSize;
				} else {
					oJQueryOptions.classes['ui-dialog-content'] = sSize;
				}
			}
			break;

		case 'object':
			if (oOptions.size.width !== undefined) {
				oJQueryOptions.width = oOptions.size.width;
			}
			if (oOptions.size.height !== undefined) {
				oJQueryOptions.height = oOptions.size.height;
			}
			break;
	}

	// Load content
	switch (typeof oOptions.content)
	{
		case 'string':
			oModalElem.html(oOptions.content);
			this._OnContentLoaded(oModalElem, oOptions.callback_on_content_loaded);
			break;

		case 'object':
			// Put loader while fetching content
			const oLoaderElem = $($('#ibo-large-loading-placeholder-template')[0].content.cloneNode(true));
			oModalElem.html(oLoaderElem.html());

			// Fetch content in background
			oModalElem.load(
				oOptions.content.endpoint,
				oOptions.content.data || {},
				function(sResponseText, sStatus) {
					// Hiding modal in case of error as the general AJAX error handler will display a message
					// TODO 3.1: Add general ajax error handler
					if (sStatus === 'error') {
						oModalElem.dialog('close');
						return;
					}

					// Update position as the new content is most likely not like the previous one (if any)
					// - First when content is displayed
					me._CenterModalInViewport(oModalElem);
					// - Then content is fully initialized
					// TODO 3.1: We need to put an event when an object is done initialiazing (fields generated, etc)
					setTimeout(function () {
						me._CenterModalInViewport(oModalElem);
					}, 500);

					me._OnContentLoaded(oModalElem, oOptions.callback_on_content_loaded);
				}
			);
			break;

		case 'undefined':
			// Do nothing, we keep the content as-is
			break;

		default:
			CombodoJSConsole.Warn('Could not open modal dialog as the content option was malformed: ' + oOptions.content);
			return false;
	}

	// Show modal
	oModalElem.dialog(oJQueryOptions);

	return true;
};
/**
 * @override
 * @inheritDoc
 */
CombodoModal._BindEvents = function (oModalElem) {
	const me = this;

	// Center modal on resize
	if(window.ResizeObserver) {
		const oModalObs = new ResizeObserver(function(){
			me._CenterModalInViewport(oModalElem);
		});
		oModalObs.observe(oModalElem[0]);
	}
};
/**
 * Convert generic buttons definitions to jquery ui dialog definitions.
 *
 * @param aButtonsDefinitions
 * @returns {*[]}
 * @private
 */
CombodoModal._ConvertButtonDefinition = function (aButtonsDefinitions) {
	const aConverted = [];
	if(aButtonsDefinitions === null) {
		return aConverted
	}
	Object.keys(aButtonsDefinitions).forEach(key => {
				const element = aButtonsDefinitions[key];
				const aButton = {
					text: element.text,
					class: typeof(element.classes) !== 'undefined' ? element.classes.join(' ') : '',
					click: element.callback_on_click
				}
				aConverted.push(aButton);
			}
	);
	return aConverted;
};
/**
 * @override
 * @inheritDoc
 */
CombodoModal._CenterModalInViewport = function (oModalElem) {
	if(oModalElem.dialog('instance') === undefined){
		CombodoJSConsole.Error('CombodoModal._CenterModalInViewport: Cannot center modal as it is not a jQuery UI dialog widget');
		return false;
	}

	oModalElem.dialog('option', {
		position: {my: 'center', at: 'center', of: window},
	});
};
/**
 * @override
 * @inheritDoc
 */
CombodoModal.OpenConfirmationModal = function(oOptions, aData) {

	// Check do not show again preference key
	if(oOptions.do_not_show_again_pref_key !== null){
		if(GetUserPreference(oOptions.do_not_show_again_pref_key, false)){
			if(oOptions.callback_on_confirm !== null){
				oOptions.callback_on_confirm(...aData);
			}
			return;
		}
	}
	// Merge external options with confirmation modal default options
	oOptions = $.extend(true, {
		title: Dict.S('UI:Modal:Confirmation:DefaultTitle'),
		content: '',
		do_not_show_again_pref_key: null,
		callback_on_confirm: null,
		callback_on_cancel: null,
		extra_options: {
			callback_on_modal_close: function () {
				$(this).dialog( "destroy" ); // destroy dialog object
			}
		},
		buttons: {
			cancel: {
				text: Dict.S('UI:Button:Cancel'),
				classes: ['ibo-is-alternative'],
				callback_on_click: function () {
					// call confirm handler and close dialog
					let bCanClose = true;
					if (oOptions.callback_on_cancel != null) {
						bCanClose = oOptions.callback_on_cancel(...aData) !== false;
					}
					if (bCanClose) {
						$(this).dialog('close'); // close dialog
					}
				}
			},
			confirm: {
				text: Dict.S('UI:Button:Confirm'),
				classes: ['ibo-is-primary'],
				callback_on_click: function () {
					// Call confirm handler and close dialog
					let bCanClose = true;
					if (oOptions.callback_on_confirm != null) {
						bCanClose = oOptions.callback_on_confirm(...aData) !== false;
					}
					if (bCanClose) {
						$(this).dialog('close'); // close dialog
						// Handle "do not show again" user preference
						let bDoNotShowAgain = oOptions.do_not_show_again_pref_key !== null ?
							$('[name="do_not_show_again"]', $(this)).prop('checked') :
							false;
						if (bDoNotShowAgain) {
							SetUserPreference(oOptions.do_not_show_again_pref_key, true, true);
						}
					}
				}
			}
		},
		callback_on_content_loaded: function(oModalContentElement){
			// Add option do not show again from template
			if(oOptions.do_not_show_again_pref_key !== null) {
				oModalContentElement.append($('#ibo-modal-option--do-not-show-again-template').html());
			}
		}
	}, oOptions);

	// Open modal
	CombodoModal.OpenModal(oOptions);
}
/**
 * @override
 * @inheritDoc
 */
CombodoModal.OpenInformativeModal = function(sMessage, sSeverity, oOptions) {
	let sFirstLetterUppercaseSeverity = sSeverity.charAt(0).toUpperCase() + sSeverity.slice(1);
	// Merge external options with confirmation modal default options
	oOptions = $.extend({
		title: Dict.S('UI:Modal:Informative' + sFirstLetterUppercaseSeverity + ':Title'),
		classes : {
			'ui-dialog-content': 'ibo-is-informative ibo-is-'+sSeverity,	
		},
		content: sMessage,
		extra_options: {
			callback_on_modal_close: function () {
				$(this).dialog( "destroy" );
			}
		},
		buttons: {
			ok: {
				text: Dict.S('UI:Button:Ok'),
				classes: ['ibo-is-regular', 'ibo-is-neutral'],
				callback_on_click: function () {
					$(this).dialog('close');
				}
			},
		},
	}, oOptions);

	// Open modal
	CombodoModal.OpenModal(oOptions);
}
/**
 * @override
 * @inheritDoc
 */
CombodoToast.OpenToast = function(sMessage, sSeverity, aOptions) {
	aOptions = $.extend({
		text: sMessage,
		className: "ibo-toast ibo-is-" + sSeverity,
		duration: 6000,
		close: true,
		gravity: GetUserPreference('toasts_vertical_position', 'bottom'),
		position: "right",
		stopOnFocus: true,
	}, aOptions);
	
	if(aOptions.duration !== -1){
		aOptions.className += ' ibo-is-auto-closeable';
	}
	
	Toastify(aOptions).showToast();
};

// Processing on each pages of the backoffice
$(document).ready(function(){
	// Initialize global keyboard shortcuts
	$('body').keyboard_shortcuts({shortcuts: aKeyboardShortcuts});
	
	// Enable tooltips based on existing HTML markup, won't work on markup added dynamically after DOM ready (AJAX, ...)
	$('[data-tooltip-content]:not([data-tooltip-instantiated="true"])').each(function () {
		CombodoTooltip.InitTooltipFromMarkup($(this));
	});

	// Enable fullscreen togglers based on existing HTML markup, won't work on markup added dynamically after DOM ready (AJAX, ...)
	$('[data-fullscreen-toggler-target]:not([data-fullscreen-toggler-instantiated="true"])').each(function () {
		const sTargetSelector = $(this).attr('data-fullscreen-toggler-target');
		let oTargetElem = null;

		// Check if target selector is a jQuery expression, meaning that it needs to be evaluated (eg. $(this).closest('[data-role="ibo-xxx"]'))
		if (sTargetSelector.indexOf('$') !== -1) {
			oTargetElem = eval(sTargetSelector);
		}
		// Otherwise it should be a simple selector (eg. #abc, .def)
		else {
			oTargetElem = $(document).find(sTargetSelector);
		}

		// Still no target element found, abort
		if((oTargetElem === null) || (oTargetElem.length === 0)) {
			return false;
		}

		// Toggle fullscreen on toggler click
		$(this).on('click', function (oEvent) {
			// Prevent anchor default behavior
			oEvent.preventDefault();

			CombodoBackofficeToolbox.ToggleFullscreenForElement(oTargetElem);
		});
		// Exit fullscreen on "Esc" key hit when focus is in either the toggler or the target
		// - Toggler
		$(this).on('keyup', function (oEvent) {
			if ((oEvent.key === 'Escape') && ($(oEvent.target).attr('data-fullscreen-toggler-instantiated'))) {
				CombodoBackofficeToolbox.ExitFullscreenForElement(oTargetElem);
			}
		});
		// - Target
		oTargetElem.on('keyup', function (oEvent) {
			if ((oEvent.key === 'Escape') && ($(oEvent.target).attr('data-fullscreen-target'))) {
				CombodoBackofficeToolbox.ExitFullscreenForElement(oTargetElem);
			}
		});

		oTargetElem.attr('data-fullscreen-target', 'true');
		$(this).attr('data-fullscreen-toggler-instantiated', 'true');
	});

	// Processing on datatables refresh
	$(document).on('init.dt draw.dt', function (oEvent) {
		CombodoTooltip.InitAllNonInstantiatedTooltips($(oEvent.target), true);
	});

	// Code highlighting
	CombodoBackofficeToolbox.InitCodeHighlighting();
});