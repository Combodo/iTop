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

/**
 * A set of helpers to make JS manipulations easier.
 *
 * @since 2.7.0
 */
var CombodoPortalToolbox = {
	/**
	 * Close all opened modals on the page
	 */
	CloseAllModals: function()
	{
		$('.modal.in').modal('hide');
	},
	/**
	 * Open a standard modal and put the content of the URL in it.
	 *
	 * @param sTargetUrl
	 * @param bCloseOtherModals
	 */
	OpenUrlInModal: function(sTargetUrl, bCloseOtherModals){
		// Set default values
		if(bCloseOtherModals === undefined)
		{
			bCloseOtherModals = false;
		}

		// Close other modals if necessary
		if(bCloseOtherModals)
		{
			CombodoPortalToolbox.CloseAllModals();
		}

		// Opening modal
		CombodoPortalToolbox.OpenModal({
			content: {
				endpoint: sTargetUrl,
			}
		});
	},
	/**
	 * Generic function to create and open a modal, used by high-level functions such as "CombodoPortalToolbox.OpenUrlInModal()".
	 * When developing extensions, you should use them instead.
	 *
	 * @param oOptions
	 * @returns object The jQuery object of the modal element
	 */
	OpenModal: function(oOptions){
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

			// Force modal to have an HTML ID, otherwise it can lead to complications, especially with the portal_leave_handle.js
			// See N°3469
			var sModalID = (oOptions.id !== null) ? oOptions.id : 'modal-with-generated-id-'+Date.now();
			oModalElem.attr('id', sModalID)
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

				//Manually triggers bootstrap event in order to keep listeners working
				oModalElem.trigger('loaded.bs.modal');
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

						//Manually triggers bootstrap event in order to keep listeners working
						oModalElem.trigger('loaded.bs.modal');
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
	},
	/**
	 * Generic function to call a specific endpoint with callbacks
	 *
	 * @param sEndpointUrl
	 * @param oPostedData
	 * @param callbackOnSuccess
	 * @param callbackOnPending
	 */
	CallEndpoint: function(sEndpointUrl, oPostedData, callbackOnSuccess, callbackOnPending){
		// Call endpoint
		$.post(sEndpointUrl, oPostedData, function(oResponse) {
			// Call callback on success
			if(callbackOnSuccess !== undefined)
			{
				if(typeof callbackOnSuccess === 'string')
				{
					window[callbackOnSuccess](oResponse);
				}
				else if(typeof callbackOnSuccess === 'function')
				{
					callbackOnSuccess(oResponse);
				}
			}
		});

		// Call callback while waiting for response
		if(callbackOnPending !== undefined)
		{
			if(typeof callbackOnPending === 'string')
			{
				window[callbackOnPending](oResponse);
			}
			else if(typeof callbackOnPending === 'function')
			{
				callbackOnPending(oResponse);
			}
		}
	}
};
