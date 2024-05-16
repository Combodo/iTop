/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Function used to open OAuth popup
var oWindowObjectReference = null;
var sPreviousUrl = null;
var oListener = null;
var sOAuthAjaxURI = null;
var sOAuthObjClass = null;
var sOAuthObjKey = null;
var sOAuthReturnURI = null;


const oOnOauthSuccess = function (event) {
	if (oListener !== null) {
		clearInterval(oListener);
	}

	$.post(
		sOAuthAjaxURI,
		{
			operation: 'GetDisplayAuthenticationResults',
			class: sOAuthObjClass,
			id: sOAuthObjKey,
			redirect_url: event.data
		},
		function (oData) {
			window.location = oData.data;
		}
	);
}
const oOpenSignInWindow = function (url, name) {
	// Remove any existing event listener
	window.removeEventListener('message', oOnOauthSuccess);
	if (oListener !== null) {
		clearInterval(oListener);
	}

	// Window features
	const sWindowFeatures = 'toolbar=no, menubar=no, width=600, height=700, top=100, left=100';

	if (oWindowObjectReference === null || oWindowObjectReference.closed) {
		/* If the pointer to the window object in memory does not exist
		 or if such pointer exists but the window was closed */
		oWindowObjectReference = window.open(url, name, sWindowFeatures);
	} else if (sPreviousUrl !== url) {
		/* If the resource to load is different,
		 then we load it in the already opened secondary window, and then
		 we bring such window back on top/in front of its parent window. */
		oWindowObjectReference = window.open(url, name, sWindowFeatures);
		oWindowObjectReference.focus();
	} else {
		/* Else the window reference must exist and the window
		 is not closed; therefore, we can bring it back on top of any other
		 window with the focus() method. There would be no need to re-create
		 the window or to reload the referenced resource. */
		oWindowObjectReference.focus();
	}
	/* Let know every second our child window that we're waiting for it to complete,
	once we reach our landing page, it'll send us a reply
	 */
	oListener = window.setInterval(function () {
		if (oWindowObjectReference.closed) {
			clearInterval(oListener);
		}
		oWindowObjectReference.postMessage('anyone', sOAuthReturnURI);
	}, 1000);

	/* Once we receive a response, transmit it to the server to get authenticate and display
	results
	 */
	window.addEventListener('message', oOnOauthSuccess, false);
	// Assign the previous URL
	sPreviousUrl = url;
};


const OAuthConnect = function(sClass, sId, sAjaxUri) {
	sOAuthAjaxURI = sAjaxUri;
	sOAuthObjClass = sClass;
	sOAuthObjKey = sId;

	$.post(
		sOAuthAjaxURI,
		{
			operation: 'GetOAuthAuthorizationUrl',
			class: sOAuthObjClass,
			id: sOAuthObjKey
		},
		function (oData) {
			if (oData.status === 'success') {
				oOpenSignInWindow(oData.data.authorization_url, 'OAuth authorization')
			} else {
				CombodoModal.OpenErrorModal(oData.error_description);
			}
		}
	);
}