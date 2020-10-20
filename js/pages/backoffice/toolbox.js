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
 * @type {{InitTooltipFromMarkup: CombodoBackofficeToolbox.InitTooltipFromMarkup}}
 * @api
 * @since 3.0.0
 */
const CombodoBackofficeToolbox = {
	// Instanciate tooltips (abstraction layer between iTop markup and tooltip plugin to ease its replacement in the future)
	/**
	 * Instanciate a tooltip on oElem from its data attributes
	 *
	 * Note: Content SHOULD be HTML entity encoded to avoid markup breaks (eg. when using a double quote in a sentence)
	 *
	 * @param oElem
	 * @constructor
	 */
	InitTooltipFromMarkup: function(oElem)
	{
		const oOptions = {
			allowHTML: true, // Always true so line breaks can work. Don't worry content will be sanitized.
		};

		// Content must be reworked before getting into the tooltip
		// - Should we enable HTML content or keep text as is
		const bEnableHTML = oElem.attr('data-tooltip-html-enabled') === 'true';

		// - Content should be sanitized unless the developer says otherwise
		// Note: Condition is inversed on purpose. When the developer is instanciating a tooltip,
		// we want him/her to explicitly declare that he/she wants the sanitizer to be skipped.
		// Whereas in this code, it's easier to follow the logic with the variable oriented this way.
		const bSanitizeContent = oElem.attr('data-tooltip-sanitizer-skipped') !== 'true';

		// - Sanitize content and make sure line breaks are kept
		const oTmpContentElem = $('<div />').html(oElem.attr('data-tooltip-content'));
		let sContent = '';
		if(bEnableHTML)
		{
			sContent = oTmpContentElem.html();
			if(bSanitizeContent)
			{
				sContent = sContent.replace(/<script/g, '&lt;script WARNING: scripts are not allowed in tooltips');
			}
		}
		else
		{
			sContent = oTmpContentElem.text();
			sContent = sContent.replace(/(\r\n|\n\r|\r|\n)/g, '<br/>');
		}
		oOptions['content'] = sContent;

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

		tippy(oElem[0], oOptions);
	}
};

// Processing
$(document).ready(function(){
	// Enable tooltips based on existing HTML markup, won't work on markup added dynamically after DOM ready (AJAX, ...)
	$('[data-tooltip-content]').each(function(){
		CombodoBackofficeToolbox.InitTooltipFromMarkup($(this));
	});
});