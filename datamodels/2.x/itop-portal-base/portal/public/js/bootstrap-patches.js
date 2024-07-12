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
 * Patches for bootstrap 3 as it is no longer maintained by its editor
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @since 2.6.2
 */

// N°2166: Fix a bootstrap/CKeditor incompatibility with their respective modals (source: https://stackoverflow.com/a/31679096)
//  N°7552  Update the ugly hacky hack to make it work with CKEditor 5. Only trigger the focus when the parent is not a CKEditor input (source: https://stackoverflow.com/questions/53556541/ckeditor-5-popup-controls-not-working-in-bootstrap-3-2018)
$.fn.modal.Constructor.prototype.enforceFocus = function () {
	var $modalElement = this.$element;
	$(document).on('focusin.modal', function (e) {
		var $parent = $(e.target.parentNode);
		if ($modalElement[0] !== e.target && !$modalElement.has(e.target).length &&
			!$parent.hasClass('ck-input')) {
			e.target.focus()
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
