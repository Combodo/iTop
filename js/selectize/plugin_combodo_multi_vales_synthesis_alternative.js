/*
 * Copyright (C) 2013-2022 Combodo SARL
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
Selectize.define("combodo_multi_values_synthesis_alternative", function () {

	// Selectize instance
	let oSelf = this;
	oSelf.require("combodo_update_operations");

	// Items states css classes
	const ITEMS_CLASSES = {
		add: 'item-add',
		remove: 'item-remove',
	};

	// Change default settings
	oSelf.settings.placeholder = 'click to add or remove';
	oSelf.settings.items = [];
	oSelf.settings.itemClass += ' item-flex';

	// Override addItem function
	oSelf.addItem = (function () {
		let oOriginal = oSelf.addItem;
		return function () {
			oOriginal.apply(this, arguments);
			const $item = oSelf.getItem(arguments[0]);
			if(!$item.hasClass('item-choice')){
				// add choices
				$item.addClass('item-choice');
				oSelf.AddChoices($item, arguments[0]);
			}
			// update operations
			oSelf.updateOperations();
			oSelf.updateOperationsInput();
		}
	})();

	// Override updateOperations function
	oSelf.updateOperations = (function () {
		return function () {

			// Reset operations
			oSelf.operations = {};

			// reference data
			const aCurrentItems = Object.values(oSelf.items);
			const aCurrentOptions = Object.values(oSelf.options);

			// scan items in current items and having class delete
			aCurrentItems.forEach(function(e){

				// Retrieve item
				const $item = oSelf.getItem(arguments[0]);

				// Get radio operation
				const sOperation = $('.radio-toolbar input:checked', $item).val();

				// Create corresponding operation
				oSelf.operations[e] = {
					operation: sOperation,
					data: ExtractArrayItemsContainingThisKeyAndValue(aCurrentOptions, oSelf.settings.valueField, e)
				}
			});

		}
	})();

	// Override updateOperations function
	oSelf.AddChoices = (function () {
		return function ($item, item) {

			const aCurrentOptions = Object.values(oSelf.options);
			const aOption = ExtractArrayItemsContainingThisKeyAndValue(aCurrentOptions, oSelf.settings.valueField, item);

			// Create operations selector
			$firendlyName = $('.friendlyname', $item);
			$firendlyName.css('display', 'flex');
			$firendlyName.css('flex-grow', '1');

			// show radio
			let $radio =  $('.radio-toolbar', $item);
			$radio.show();

			// update operations on change
			$('input', $radio).on('change', function(){
				oSelf.updateOperationsInput();
			});

			// Disable
			if(aOption.full){
				$(`input[value="add"]`, $item).attr('disabled', true);
			}
			if(aOption.empty){
				$(`input[value="remove"]`, $item).attr('disabled', true);
			}

			// update ui
			$('input', $radio).on('click', function(){
				switch($(this).val()){
					case 'add':
						oSelf.Add($item);
						break;
					case 'remove':
						oSelf.Remove($item);
						break;
				}
			})

			oSelf.Add($item);
		}
	})();

	// Override updateOperations function
	oSelf.Add = (function () {
		return function (e) {
			oSelf.ResetElementClass(e);
			e.addClass(ITEMS_CLASSES.add);
		}
	})();

	// Override updateOperations function
	oSelf.Remove = (function () {
		return function (e) {
			oSelf.ResetElementClass(e);
			e.addClass(ITEMS_CLASSES.remove);
		}
	})();

	// Declare ResetElementClass function
	oSelf.ResetElementClass = (function () {
		return function (e) {
			e.removeClass(Object.values(ITEMS_CLASSES));
		}
	})();
});