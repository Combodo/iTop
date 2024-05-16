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
Selectize.define("combodo_update_operations", function (aOptions) {

	// Selectize instance
	let oSelf = this;

	// Plugin options
	aOptions = $.extend({
			initial: [],
		},
		aOptions
	);

	// Plugin variables
	oSelf.bIsInitialized = false;
	oSelf.operations = {};

	// Override setup function
	oSelf.setup = (function () {
		let oOriginal = oSelf.setup;
		return function () {
			oOriginal.apply(oSelf, arguments);
			oSelf.$operationsInput = $(`<input type="hidden" value="{}" name="${oSelf.$input.attr('name')}_operations">`)
			oSelf.$wrapper.append(oSelf.$operationsInput);
			oSelf.bIsInitialized = true;
			oSelf.updateOperationsInput();
		};
	})();

	// Override enable function
	oSelf.enable = (function () {
		let oOriginal = oSelf.enable;
		return function () {
			oOriginal.apply(oSelf, arguments);
			oSelf.$operationsInput.prop('disabled', false);
		}
	})();

	// Override disable function
	oSelf.disable = (function () {
		let oOriginal = oSelf.disable;
		return function () {
			oOriginal.apply(oSelf, arguments);
			if(oSelf.$operationsInput !== undefined)
				oSelf.$operationsInput.prop('disabled', true);
		}
	})();

	// Override addItem function
	oSelf.addItem = (function () {
		let oOriginal = oSelf.addItem;
		return function () {
			oOriginal.apply(this, arguments);
			if(oSelf.bIsInitialized && !arguments[1]){
				this.updateOperationsInput();
			}
		}
	})();

	// Override removeItem function
	oSelf.removeItem = (function () {
		let oOriginal = oSelf.removeItem;
		return function () {
			oOriginal.apply(this, arguments);
			if(oSelf.bIsInitialized){
				this.updateOperationsInput();
			}
		}
	})();

	// Declare updateOperationsInput function
	oSelf.updateOperationsInput = (function () {
		return function () {

			// update operations
			oSelf.updateOperations();

			// setup in progress
			if(typeof(oSelf.$operationsInput) === 'undefined'){
				return;
			}

			// update operations input
			oSelf.$operationsInput.val(JSON.stringify(oSelf.operations));
		};
	})();

	// Declare updateOperations function
	oSelf.updateOperations = (function () {
		return function () {

			// Reset operations
			oSelf.operations = {};

			// Reference data
			const aCurrentItems = Object.values(oSelf.items);
			const aCurrentOptions = Object.values(oSelf.options);

			// Scan items in current value and not in initial value
			aCurrentItems.forEach(function(e){
				if(!aOptions.initial.includes(e)){
					oSelf.operations[e] = {
						operation: 'add',
						data: CombodoGlobalToolbox.ExtractArrayItemsContainingThisKeyAndValue(aCurrentOptions, oSelf.settings.valueField, e)
					}
				}
			});

			// scan items in initial value and not in current value
			aOptions.initial.forEach(function(e){
				if(!aCurrentItems.includes(e)){
					oSelf.operations[e] = {
						operation: 'remove',
						data: CombodoGlobalToolbox.ExtractArrayItemsContainingThisKeyAndValue(aCurrentOptions, oSelf.settings.valueField, e)
					}
				}
			});
		};
	})();

	// Declare addInitialValue function
	oSelf.addInitialValue = (function () {
		return function (value) {
			aOptions.initial.push(value);
			oSelf.updateOperationsInput();
		};
	})();

});