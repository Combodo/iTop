/**
 * @license Copyright (c) 2014-2024, CKSource Holding sp. z o.o. All rights reserved.
 * This file is licensed under the terms of the MIT License (see LICENSE.md).
 */

/* eslint-env browser */

( function() {
	const LOCAL_STORAGE_KEY = 'CKEDITOR_CS_CONFIG';

	function createDialog() {
		const overlay = document.createElement( 'div' );

		overlay.id = 'overlay';
		overlay.innerHTML = `
<form class="body">
	<h2>Connect CKEditor5 Premium Features</h2>
	<p>If you do not have CKEditor5 license key yet, <a href="https://ckeditor.com/docs/trial/latest/guides/overview.html" target="_blank">see the documentation</a>.</p>
	<div>
		<label for="license-key">CKEditor5 License Key</label>
		<input id="license-key">
	</div>
	<h2>Connect CKBox</h2>
	<div>
		<label for="ckbox-token-url">CKBox token URL</label>
		<input id="ckbox-token-url">
	</div>
	<button id="start" type="submit">Start</button>
</form>`;

		document.body.appendChild( overlay );

		const config = getStoredConfig();

		const licenseKeyInput = document.getElementById( 'license-key' );
		licenseKeyInput.value = config.licenseKey || '';

		const ckboxTokenUrlInput = document.getElementById( 'ckbox-token-url' );
		ckboxTokenUrlInput.value = config.ckboxTokenUrl || '';

		return new Promise( resolve => {
			overlay.querySelector( 'form' ).addEventListener( 'submit', event => {
				event.preventDefault();

				config.licenseKey = licenseKeyInput.value;

				config.ckboxTokenUrl = ckboxTokenUrlInput.value;

				overlay.remove();
				storeConfig( config );
				resolve( config );
			} );
		} );
	}

	function getStoredConfig() {
		return JSON.parse( localStorage.getItem( LOCAL_STORAGE_KEY ) || '{}' );
	}

	function storeConfig( config ) {
		localStorage.setItem( LOCAL_STORAGE_KEY, JSON.stringify( config ) );
	}

	window.createDialog = createDialog;
}() );
