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

/*
 * Patches for bootstrap 3 as it is no longer maintained by its editor
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @since 2.6.2
 */

// N°2166: Fix a bootstrap/CKeditor incompatibility with their respective modals (source: https://stackoverflow.com/a/31679096)
$.fn.modal.Constructor.prototype.enforceFocus = function() {
	$( document )
		.off( 'focusin.bs.modal' ) // guard against infinite focus loop
		.on( 'focusin.bs.modal', $.proxy( function( e ) {
			if (
				this.$element[ 0 ] !== e.target && !this.$element.has( e.target ).length
				// CKEditor compatibility fix start.
				&& !$( e.target ).closest( '.cke_dialog, .cke' ).length
			// CKEditor compatibility fix end.
			) {
				this.$element.trigger( 'focus' );
			}
		}, this ) );
};

// Hack to enable multiple modals by making sure the .modal-open class is set to the <body> when there is at least one modal open left
$(document).ready(function() {
	$('body').on('hidden.bs.modal', function () {
		if ($('.modal.in').length > 0) {
			$('body').addClass('modal-open');
		}
	});
});
