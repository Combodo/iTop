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

/*
 * Initialize every modal DOM objects with class url-to-clipboard with itop.clipboard widget
 */
$(document).ready(function()
{
	/**
	 * Handle click events on elements with tile-navigation-trigger data attribute.
	 * If click happens on a link, it will not be handled.
	 * Il no navigation url is provided, nothing will happen.
	 */
	$('body').on('click', '[data-role="tile-navigation-trigger"]', function (oEvent) {
		// link high priority
		if($(oEvent.target).is('a')){
			return;
		}
		// let's find the closest tile-navigation-trigger
		let closestTriggeredElement = $(oEvent.target).closest('[data-role="tile-navigation-trigger"]');
		// retrieve tile-navigation-trigger url
		let sUrl = closestTriggeredElement.attr('data-tile-navigation-url');
		// open url
		if(sUrl !== undefined){

			if(closestTriggeredElement.attr('data-toggle') === 'modal'){
				CombodoModal.OpenUrlInModal(sUrl, true);
			}
			else{
				document.location.href = sUrl;
			}

			oEvent.stopPropagation();
		}
	});
});