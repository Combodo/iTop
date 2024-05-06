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

/**
 * A set of helpers to make JS manipulations easier.
 *
 * @since 2.7.0
 */
const CombodoPortalToolbox = {
	/**
	 * Close all opened modals on the page
	 * @deprecated 3.1.0 Use CombodoModal.CloseAllModals() instead
	 */
	CloseAllModals: function() {
		CombodoModal.CloseAllModals();
	},
	/**
	 * @deprecated 3.1.0 Use CombodoModal.OpenUrlInModal() instead
	 */
	OpenUrlInModal: function(sTargetUrl, bCloseOtherModals) {
		CombodoModal.OpenUrlInModal(sTargetUrl, bCloseOtherModals);
	},
	/**
	 * @deprecated 3.1.0 Use CombodoModal.OpenModal() instead
	 */
	OpenModal: function(oOptions) {
		// Default value fallback for calls prior to 3.1.0
		if (oOptions.size === undefined) {
			oOptions.size = 'lg';
		}
		return CombodoModal.OpenModal(oOptions);
	},
	/**
	 * Generic function to call a specific endpoint with callbacks
	 *
	 * @param sEndpointUrl
	 * @param oPostedData
	 * @param callbackOnSuccess
	 * @param callbackOnPending
	 */
	CallEndpoint: function(sEndpointUrl, oPostedData, callbackOnSuccess, callbackOnPending) {
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

/**
 * @override
 * @inheritDoc
 */
CombodoModal.CloseAllModals = function() {
	$('.modal.in').modal('hide');
};
/**
 * @override
 * @inheritDoc
 */
CombodoModal._GetDefaultBaseModalSelector = function() {
	return '#modal-for-all';
};
/**
 * @override
 * @inheritDoc
 */
CombodoModal._InstantiateModal = function(oModalElem, oOptions) {
	const me = this;

	// Resize to desired size
	switch (typeof oOptions.size) {
		case 'string':
			if(oOptions.size !== undefined && oOptions.size !== 'auto') {
				oModalElem.find('.modal-dialog')
					.removeClass('modal-lg')
					.addClass('modal-'+oOptions.size);
			}
			break;

		case 'object':
			CombodoJSConsole.Warn('Specifying a specific width / height on a modal dialog is not supported yet in the portal. Only "xs", "sm", "md", "lg" sizes are supported.');
	}

	// Load content
	switch (typeof oOptions.content)
	{
		case 'string':
			oModalElem.find('.modal-content').html(oOptions.content);

			// Internal callbacks
			this._OnContentLoaded(oModalElem, oOptions.callback_on_content_loaded);

			// Manually triggers bootstrap event in order to keep listeners working
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

					// Internal callbacks
					me._OnContentLoaded(oModalElem, oOptions.callback_on_content_loaded);

					//Manually triggers bootstrap event in order to keep listeners working
					oModalElem.trigger('loaded.bs.modal');
				}
			);
			break;

		case 'undefined':
			// Do nothing, we keep the content as-is
			break;

		default:
			CombodoJSConsole.Warn('Could not open modal dialog as the content option was malformed: ' + oOptions.content);
			return false;
	}

	// Show modal
	if (oOptions.auto_open) {
		oModalElem.modal('show');
	}

	return true;
};
/**
 * @override
 * @inheritDoc
 */
CombodoModal._CenterModalInViewport = function (oModalElem) {
	// No need to override, modal centers itself automatically
};