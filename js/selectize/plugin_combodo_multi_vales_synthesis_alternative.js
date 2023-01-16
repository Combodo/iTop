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

	// Change default settings
	oSelf.settings.placeholder = 'cliquer pour ajouter ou supprimer';
	oSelf.settings.items = [];

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
		let oOriginal = oSelf.updateOperations;
		return function () {
			oOriginal.apply(this, arguments);

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

			// Variables
			let sInputName = oSelf.$input.attr('name');
			let sName = `${sInputName}${item}`;
			let sAdd = `${sInputName}${item}Add`;
			let sRem = `${sInputName}${item}Rem`;

			const aCurrentOptions = Object.values(oSelf.options);
			const aOption = ExtractArrayItemsContainingThisKeyAndValue(aCurrentOptions, oSelf.settings.valueField, item);

			// Create operations selector
			$item.css('display', 'inline-flex');
			// $item.css('display', 'flex');
			$item.css('justify-content', 'space-between');
			let sRadio = `
<div class="radio-toolbar" style="float: right;margin-left: 10px;">
    <input type="radio" id="${sAdd}" name="${sName}" value="add" checked>
    <label for="${sAdd}">Add</label>
    <input type="radio" id="${sRem}" name="${sName}" value="remove">
    <label for="${sRem}">Remove</label>
</div>`;
			let $radio =  $(sRadio);
			$item.append($radio);

			// update operations on change
			$('input', $radio).on('change', function(){
				oSelf.updateOperations();
				oSelf.updateOperationsInput();
			});

			if(aOption.full){
				$(`input[id="${sAdd}"]`).attr('disabled', true);
			}
			if(aOption.empty){
				$(`input[id="${sRem}"]`).attr('disabled', true);
			}

			// update ui
			$('input', $radio).on('click', function(){
				switch($(this).val()){
					case 'add':
						oSelf.Add($(this));
						break;
					case 'remove':
						oSelf.Remove($(this));
						break;
					case 'ignore':
						oSelf.Ignore($(this));
						break;
				}
			})

		}
	})();

	// Override updateOperations function
	oSelf.Add = (function () {
		return function (e) {
			e.closest('.attribute-set-item').removeClass('item-delete');
		}
	})();

	// Override updateOperations function
	oSelf.Remove = (function () {
		return function (e) {
			e.closest('.attribute-set-item').addClass('item-delete');
		}
	})();

	// Override updateOperations function
	oSelf.Ignore = (function () {
		return function (e) {
			e.closest('.attribute-set-item').removeClass('item-delete');
		}
	})();
});