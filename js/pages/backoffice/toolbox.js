/*
 * Copyright (C) 2013-2020 Combodo SARL
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
function ShowAboutBox()
{
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'about_box'}, function(data){
		$('body').append(data);
	});
	return false;
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
//TODO 3.0.0 Is this the right place to put this method ?
function SwitchTabMode()
{
	let aTabContainer = $('[data-role="ibo-tab-container"]');
	if (!aTabContainer.hasClass('ibo-is-vertical'))
	{
		aTabContainer.removeClass('ibo-is-horizontal');
		aTabContainer.addClass('ibo-is-vertical');
		SetUserPreference('tab_layout', 'vertical', true);
	} else
	{
		aTabContainer.removeClass('ibo-is-vertical');
		aTabContainer.addClass('ibo-is-horizontal');
		SetUserPreference('tab_layout', 'horizontal', true);
	}
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
	ToggleFullscreenForElement: function(oElem) {
		if(oElem.hasClass('ibo-is-fullscreen')) {
			this.ExitFullscreenForElement(oElem);
		}
		else {
			this.EnterFullscreenForElement(oElem);
		}
	}
};

// Processing
$(document).ready(function(){
	// Enable tooltips based on existing HTML markup, won't work on markup added dynamically after DOM ready (AJAX, ...)
	$('[data-tooltip-content]:not([data-tooltip-instanciated="true"])').each(function(){
		CombodoGlobalToolbox.InitTooltipFromMarkup($(this));
	});

	// Enable fullscreen togglers based on existing HTML markup, won't work on markup added dynamically after DOM ready (AJAX, ...)
	$('[data-fullscreen-toggler-target]:not([data-fullscreen-toggler-instanciated="true"])').each(function(){
		const sTargetSelector = $(this).attr('data-fullscreen-toggler-target');
		let oTargetElem = null;

		// Check if target selector is a jQuery expression, meaning that it needs to be evaluated (eg. $(this).closest('[data-role="ibo-xxx"]'))
		if(sTargetSelector.indexOf('$') !== -1) {
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

		// If target selector return one element, it's the good target
		oTargetElem.on('click', function(oEvent){
			// Prevent anchor default behavior
			oEvent.preventDefault();

			CombodoBackofficeToolbox.ToggleFullscreenForElement(oTargetElem);
		});
	});
});