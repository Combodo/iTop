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
	 * Set the oElem in fullscreen mode, meaning that it will take all the screen and be above everything else.
	 *
	 * @param {object} oElem The jQuery object of the element
	 * @constructor
	 */
	SetElementToFullscreenMode: function(oElem) {
		oElem.parents().addClass('ibo-has-fullscreen-descendant');
		oElem.addClass('ibo-is-fullscreen');
	},
	/**
	 * Remove the oElem from fullscreen mode.
	 * If none passed, all fullscreen elements will be removed from it
	 *
	 * @param {object|null} oElem The jQuery object of the element
	 * @constructor
	 */
	RemoveElementFromFullscreenMode: function(oElem = null) {
		// If no element passed, remove any element from fullscreen mode
		if(oElem === null) {
			$(document).find('.ibo-has-fullscreen-descendant').removeClass('ibo-has-fullscreen-descendant');
			$(document).find('.ibo-is-fullscreen').removeClass('ibo-is-fullscreen');
		}
		else {
			oElem.parents().removeClass('ibo-has-fullscreen-descendant');
			oElem.removeClass('ibo-is-fullscreen');
		}
	}
};

// Processing
$(document).ready(function(){
	// Enable tooltips based on existing HTML markup, won't work on markup added dynamically after DOM ready (AJAX, ...)
	$('[data-tooltip-content]:not([data-tooltip-instanciated="true"])').each(function(){
		CombodoGlobalToolbox.InitTooltipFromMarkup($(this));
	});
});