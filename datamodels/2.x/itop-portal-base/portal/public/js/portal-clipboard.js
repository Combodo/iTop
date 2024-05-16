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
	// Bootstrap modal: initialize .url-to-clipboard and set the widget container to the modal
	$('body').on('loaded.bs.modal', function (oEvent) {
		var oModalsElem = $(this).find('.modal.in');
		oModalsElem.each(function()
		{
			var oModalElem = $(this);
			oModalElem.find('.url-to-clipboard').each(function()
			{
				$(this).clipboard({'container': oModalElem[0]});
			});
		});
	});
});