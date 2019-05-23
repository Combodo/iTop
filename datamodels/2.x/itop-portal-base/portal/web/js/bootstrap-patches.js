/*
 * Patches for bootstrap 3 as it is no longer maintained by its editor
 */

//N°2166: Fix a boostrap/CKeditor incompatibility with their respective modals (source: https://stackoverflow.com/a/23667151)
$.fn.modal.Constructor.prototype.enforceFocus = function () {
	var $modalElement = this.$element;
	$(document).on('focusin.modal', function (e) {
		var $parent = $(e.target.parentNode);
		if ($modalElement[0] !== e.target && !$modalElement.has(e.target).length
			// add whatever conditions you need here:
			&&
			!$parent.hasClass('cke_dialog_ui_input_select') && !$parent.hasClass('cke_dialog_ui_input_text')) {
			$modalElement.focus()
		}
	})
};

// Hack to enable multiple modals by making sure the .modal-open class is set to the <body> when there is at least one modal open left
$(document).ready(function() {
	$('body').on('hidden.bs.modal', function () {
		if ($('.modal.in').length > 0) {
			$('body').addClass('modal-open');
		}
	});
});
