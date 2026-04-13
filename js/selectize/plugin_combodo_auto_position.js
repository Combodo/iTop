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
    const iDropdownContentHeightDifference = 4;

	// Plugin options
	aOptions = $.extend({
			maxDropDownHeight: '200px',
		},
		aOptions
	);

	// override settings
	oSelf.settings.dropdownParent = 'body';

	// Override position dropdown function
	oSelf.positionDropdown = (function () {
		return function () {
            // Clear previously set rules so the comparison is done with dropdown real height
            oSelf.$dropdown.css({
                'max-height': '',
            });

            oSelf.$dropdown_content.css({
                'max-height': '',
            });

            let iDropdownHeight = oSelf.$dropdown.outerHeight();
			if(oSelf.$control.offset().top + oSelf.$control.outerHeight() + iDropdownHeight > window.innerHeight){

                // Apply max-height as we are overflowing, that'll allow us to calculate where we should place ourselves later
                oSelf.$dropdown.css({
                    maxHeight: `${aOptions.maxDropDownHeight}`,
                })

                iDropdownHeight = oSelf.$dropdown.outerHeight();

                oSelf.$dropdown.css({
                    top: oSelf.$control.offset().top - iDropdownHeight + iDropdownContentHeightDifference, // Content will be shorter, so our real height too
                    left: oSelf.$control.offset().left,
					width: oSelf.$wrapper.outerWidth(),
					overflowY: 'auto',
                    borderTop : oSelf.$dropdown.css('border-bottom')
				});

                // N°9468 Dropdown content needs to be a few pixel shorter than the dropdown itself to avoid double scrollbar
                oSelf.$dropdown_content.css({
                    'max-height': `calc(${aOptions.maxDropDownHeight} - ${iDropdownContentHeightDifference}px)`
                });

			}
			else{
				oSelf.$dropdown.css({
					top: oSelf.$control.offset().top + oSelf.$control.outerHeight(),
					left: oSelf.$control.offset().left,
					width: oSelf.$wrapper.outerWidth(),
					overflowY: 'auto',
                    borderTop: 'none'
            });
			}
		};
	}());

});