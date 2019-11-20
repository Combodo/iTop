/*
 * Copyright (C) 2013-2019 Combodo SARL
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

$(document).ready(function()
{
	var oBodyElem = $('body');

	// Hack to enable a same modal to load content from different urls
	oBodyElem.on('hidden.bs.modal', '.modal#modal-for-all', function ()
	{
		$(this).removeData('bs.modal');
		$(this).find('.modal-content').html(GetContentLoaderTemplate());
	});

	// Hide tooltips when a modal is opening, otherwise it might be overlapping it
	oBodyElem.on('show.bs.modal', function ()
	{
		$(this).find('.tooltip.in').tooltip('hide');
	});

	// Display a error message on modal if the content could not be loaded.
	// Note : As of now, we can't display a more detailled message based on the response because Bootstrap doesn't pass response data with the loaded event.
	oBodyElem.on('loaded.bs.modal', function (oEvent)
	{
		var sModalContent = $(oEvent.target).find('.modal-content').html();

		if ((sModalContent === '') || (sModalContent.replace(/[\n\r\t]+/g, '') === GetContentLoaderTemplate()))
		{
			$(oEvent.target).modal('hide');
		}
	});

	/**
	 * Set a listener on the BS modal DATA-API for modals with a custom "itop-portal-modal" toggle.
	 * This allows us to call our custom handler above and still use the lightest way to instantiate modal: Markup only, no JS
	 */
	$(document).on('click.bs.modal.data-api', '[data-toggle="itop-portal-modal"]', function (oEvent)
	{
		if ($(this).is('a'))
		{
			oEvent.preventDefault();
		}

		// Prepare base options
		var oOptions = {
			content: {
				endpoint: $(this).attr('href')
			}
		};

		// Add target modal if necessary
		if ($(this).attr('data-target') !== undefined)
		{
			oOptions.base_modal = {
				usage: 'clone',
				selector: $(this).attr('data-target')
			};
		}

		CombodoPortalToolbox.OpenModal(oOptions);
	});
});