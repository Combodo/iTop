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
Selectize.define("combodo_auto_position", function (aOptions) {

	// Selectize instance
	let oSelf = this;

	// Plugin options
	aOptions = $.extend({
			maxDropDownHeight: 200,
		},
		aOptions
	);

	// override settings
	oSelf.settings.dropdownParent = 'body';

	// Override position dropdown function
	oSelf.positionDropdown = (function () {
		return function () {
			let iRefHeight = oSelf.$dropdown.outerHeight() < aOptions.maxDropDownHeight ?
				oSelf.$dropdown.outerHeight() : aOptions.maxDropDownHeight;

			if(oSelf.$control.offset().top + oSelf.$control.outerHeight() + iRefHeight > window.innerHeight){

				oSelf.$dropdown.css({
					top: oSelf.$control.offset().top - iRefHeight,
					left: oSelf.$control.offset().left,
					width: oSelf.$wrapper.outerWidth(),
					'max-height': `${aOptions.maxDropDownHeight}px`,
					'overflow-y': 'auto',
					'border-top': '1px solid #d0d0d0',
				});
			}
			else{
				oSelf.$dropdown.css({
					top: oSelf.$control.offset().top + oSelf.$control.outerHeight(),
					left: oSelf.$control.offset().left,
					width: oSelf.$wrapper.outerWidth(),
					'max-height': `${aOptions.maxDropDownHeight}px`,
					'overflow-y': 'auto'
				});
			}
		};
	}());

});