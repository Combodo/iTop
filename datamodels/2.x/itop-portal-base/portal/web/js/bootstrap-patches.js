/*
 * Patches for bootstrap 3 as it is no longer maintained by its editor
 */

//N°2166: Fix a boostrap/CKeditor incompatibility with their respective modals (source: https://stackoverflow.com/a/31679096)
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
