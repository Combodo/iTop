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
Selectize.define("combodo_multi_values_synthesis", function () {

	// Selectize instance
	let oSelf = this;
	oSelf.require("combodo_update_operations");

	// Items operations
	const OPERATIONS = {
		add: 'add',
		remove: 'remove',
		ignore: 'ignore',
	};

	// Items states css classes
	const ITEMS_CLASSES = {
		add: 'item-add',
		remove: 'item-remove',
		ignore_all: 'item-ignore-all',
		ignore_partial: 'item-ignore-partial'
	};

	// Local operations
	let aOperations = {};

	// Override addItem function
	oSelf.addItem = (function () {
		let oOriginal = oSelf.addItem;
		return function () {

			oOriginal.apply(this, arguments);

			// Retrieve item and item element
			const sItemValue = arguments[0];
			const $Item = oSelf.getItem(sItemValue);

			// Restore operation if exist and return
			if(typeof(aOperations[sItemValue]) !== 'undefined'){
				if(aOperations[sItemValue] === OPERATIONS.add){
					oSelf.Add($Item, sItemValue);
				}
				else if(aOperations[sItemValue] === OPERATIONS.remove){
					oSelf.Remove($Item, sItemValue);
				}
				else if(aOperations[sItemValue] === OPERATIONS.ignore){
					oSelf.Ignore($Item, sItemValue);
				}
				// Element exist in default selection,
				// click allow user to switch between add or ignore states
				if(oSelf.settings.initial.includes(sItemValue)) {
					oSelf.listenClick($Item, sItemValue);
				}
				return;
			}

			// If no operation to restore
			if(!oSelf.settings.initial.includes(sItemValue)) {

				// Element doesn't exist in initial value, we mark it as added
				oSelf.Add($Item, sItemValue);
			}
			else{

				// Element exist, we restore it
				oSelf.Ignore($Item, sItemValue);

				// Element exist in default selection,
				// click allow user to switch between add or ignore states
				oSelf.listenClick($Item, sItemValue);
			}
		}
	})();

	// Override removeItem function
	oSelf.removeItem = (function () {
		let oOriginal = oSelf.removeItem;
		return function () {

			// Retrieve item and item element
			const sItem = arguments[0];
			const $Item = oSelf.getItem(sItem);

			// Element doesn't exist in default selection,
			if(!oSelf.settings.initial.includes(sItem)) {

				// Remove operation
				delete aOperations[sItem];

				// Call original remove function (element will be removed of the input)
				oOriginal.apply(this, arguments);
			}
			else{

				// Store remove operation (element will NOT be removed)
				oSelf.Remove($Item, sItem);
			}
		}
	})();

	// Override updateOperations function
	oSelf.updateOperations = (function () {
		let oOriginal = oSelf.updateOperations;
		return function () {

			// Call original updateOperations function
			oOriginal.apply(this, arguments);

			// Iterate throw local operations...
			const aCurrentOptions = Object.values(oSelf.options);
			for (const [key, value] of Object.entries(aOperations)) {
				oSelf.operations[key] = {
					operation: value,
					data: ExtractArrayItemsContainingThisKeyAndValue(aCurrentOptions, oSelf.settings.valueField, key)
				}
			}
		}
	})();

	// Declare listenClick function
	oSelf.listenClick = (function () {
		return function ($item, item) {

			// Listen item element click event
			$item.on('click', function(){

				// If element has operation
				if(aOperations[item] === OPERATIONS.add || aOperations[item] === OPERATIONS.remove) {

					// Restore state
					oSelf.Ignore($item, item);
				}
				else{

					// Add element
					oSelf.Add($item, item);
				}
			});
		}
	})();

	// Declare Add function
	oSelf.Add = (function () {
		return function (e, i) {
			aOperations[i] = OPERATIONS.add;
			oSelf.updateOperationsInput();
			oSelf.ResetElementClass(e);
			e.addClass(ITEMS_CLASSES.add);
		}
	})();

	// Declare Remove function
	oSelf.Remove = (function () {
		return function (e, i) {
			aOperations[i] = OPERATIONS.remove;
			oSelf.updateOperationsInput();
			oSelf.ResetElementClass(e);
			e.addClass(ITEMS_CLASSES.remove);
		}
	})();

	// Declare Ignore function
	oSelf.Ignore = (function () {
		return function (e, i) {
			aOperations[i] = OPERATIONS.ignore;
			oSelf.updateOperationsInput();
			oSelf.ResetElementClass(e);
			oSelf.options[i]['full'] === true ?
				e.addClass(ITEMS_CLASSES.ignore_all) :
				e.addClass(ITEMS_CLASSES.ignore_partial);
		}
	})();

	// Declare ResetElementClass function
	oSelf.ResetElementClass = (function () {
		return function (e) {
			e.removeClass(Object.values(ITEMS_CLASSES));
		}
	})();
});