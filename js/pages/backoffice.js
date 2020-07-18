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

// Processing
$(document).ready(function(){
	// Enable tooltips (abstraction layer between iTop markup and tooltip plugin to ease its replacement in the future)
	// - Existing HTML markup, won't work on markup added dynamically after DOM ready (AJAX, ...)
	$('[data-tooltip-content]').each(function(){
		const oOptions = {};

		oOptions['content'] = $(this).attr('data-tooltip-content');
		oOptions['placement'] = $(this).attr('data-tooltip-placement') ?? 'top';
		oOptions['trigger'] = $(this).attr('data-tooltip-trigger') ?? 'mouseenter focus';

		const sShiftingOffset = $(this).attr('data-tooltip-shifting-offset');
		const sDistanceOffset = $(this).attr('data-tooltip-distance-offset');
		oOptions['offset'] = [
			(sShiftingOffset === undefined) ? 0 : parseInt(sShiftingOffset),
			(sDistanceOffset === undefined) ? 10 : parseInt(sDistanceOffset),
		];

		oOptions['animation'] = $(this).attr('data-tooltip-animation') ?? 'shift-away-subtle';

		const sShowDelay = $(this).attr('data-tooltip-show-delay');
		const sHideDelay = $(this).attr('data-tooltip-hide-delay');
		oOptions['delay'] = [
			(typeof sShowDelay === 'undefined') ? 200 : parseInt(sShowDelay),
			(typeof sHideDelay === 'undefined') ? null : parseInt(sHideDelay),
		];

		tippy(this, oOptions);
	});
});