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

Selectize.define("combodo_add_button", function (aOptions) {

	// Selectize instance
	let oSelf = this;

	// Plugin options
	aOptions = $.extend({
			title: "Add Option",
			className: "selectize-add-option",
			label: "+",
			html: function () {
				return (
					'<a class="' + this.className + '"><i class="fas fa-plus" title="' + this.title + '"/></a>'
				);
			},
		},
		aOptions
	);

	// Override setup function
	oSelf.setup = (function () {
		let oOriginal = oSelf.setup;
		return function () {
			oOriginal.apply(oSelf, arguments);
			oSelf.$buttonAdd = $(aOptions.html());
			oSelf.$wrapper.append(oSelf.$buttonAdd);
			if(oSelf.settings.hasOwnProperty('onAdd')) {
				oSelf.on('add', oSelf.settings['onAdd']);
				oSelf.$buttonAdd.on('click', function(){
					oSelf.trigger( "add");
				});
			}
			else{
				oSelf.$buttonAdd.css({
					opacity: .5,
					cursor: 'default'
				});
			}
		};
	})();

});