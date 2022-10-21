/*
 * Copyright (C) 2013-2021 Combodo SARL
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
					$(oElem).parent().addClass('ibo-hljs-container');
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
					$(oElem).parent().addClass('ibo-hljs-container');
				});
			}
		}
	}
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