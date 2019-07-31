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
 *
 *
 */

/**
 * Creates a Bootstrap modal dialog from a base modal element (template or reusable one) and loads the content while displaying a nice loader.
 *
 * Technical: We made this to work around the base modal interactions as it was not possible to define a loader, nor to clone the base modal natively.
 *
 * @param oOptions
 * @constructor
 * @since 2.7.0
 */
var CreatePortalModal = function (oOptions)
{
	// Set default options
	oOptions = $.extend(
		true,
		{
			id: null,           // ID of the created modal
			attributes: {},     // HTML attributes
			base_modal: {
				usage: 'clone',             // Either 'clone' or 'replace'
				selector: '#modal-for-all' // Either a selector of the modal element used to base this one on or the modal element itself
			},
			content: undefined, // Either a string, an object containing the endpoint / data or undefined to keep base modal content as-is
			size: 'lg',         // Either 'xs' / 'sm' / 'md' / 'lg'
		},
		oOptions
	);

	// Compute modal selector
	var oSelectorElem = null;
	switch(typeof oOptions.base_modal.selector)
	{
		case 'string':
			oSelectorElem = $(oOptions.base_modal.selector);
			break;

		case 'object':
			oSelectorElem = oOptions.base_modal.selector;
			break;

		default:
			if (window.console && window.console.warn)
			{
				console.warn('Could not open modal dialog as the select option was malformed: ', oOptions.content);
			}
			break;
	}

	// Get modal element by either
	var oModalElem = null;
	// - Create a new modal from template
	//   Note : This could be better if we check for an existing modal first instead of always creating a new one
	if (oOptions.base_modal.usage === 'clone')
	{
		oModalElem = oSelectorElem.clone();
		oModalElem.attr('id', oOptions.id)
			.appendTo('body');
	}
	// - Get an existing modal in the DOM
	else
	{
		oModalElem = oSelectorElem;
	}

	// Set attributes
	for(var sProp in oOptions.attributes)
	{
		oModalElem.attr(sProp, oOptions.attributes[sProp]);
	}

	// Resize to small modal
	oModalElem.find('.modal-dialog')
		.removeClass('modal-lg')
		.addClass('modal-' + oOptions.size);

	// Load content
	switch (typeof oOptions.content)
	{
		case 'string':
			oModalElem.find('.modal-content').html(oOptions.content);
			break;

		case 'object':
			// Put loader while fetching content
			oModalElem.find('.modal-content').html($('#page_overlay .overlay_content').html());
			// Fetch content in background
			oModalElem.find('.modal-content').load(
				oOptions.content.endpoint,
				oOptions.content.data || {},
				function (sResponseText, sStatus)
				{
					// Hiding modal in case of error as the general AJAX error handler will display a message
					if (sStatus === 'error')
					{
						oModalElem.modal('hide');
					}
				}
			);
			break;

		case 'undefined':
			// Do nothing, we keep the content as-is
			break;

		default:
			if (window.console && window.console.warn)
			{
				console.warn('Could not open modal dialog as the content option was malformed: ', oOptions.content);
			}
	}

	// Show modal
	oModalElem.modal('show');

	return oModalElem;
};

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

		CreatePortalModal(oOptions);
	});
});