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
Selectize.define("combodo_multi_values_synthesis", function (aOptions) {

	// Selectize instance
	let oSelf = this;
	oSelf.require("combodo_update_operations");

	// Plugin options
	aOptions = $.extend({
		tooltip_links_will_be_created_for_all_objects: 'Links will be created for all objects',
		tooltip_links_will_be_deleted_from_all_objects: 'Links will be deleted from all objects',
		tooltip_links_will_be_created_for_one_object: 'Links will be created for one object',
		tooltip_links_will_be_deleted_from_one_object: 'Links will be deleted from one object',
		tooltip_links_will_be_created_for_x_objects: 'Links will be created for {count} objects',
		tooltip_links_will_be_deleted_from_x_objects: 'Links will be deleted from {count} objects',
		tooltip_links_exist_for_all_objects: 'Links exist for all objects',
		tooltip_links_exist_for_one_object: 'Links exist for one object',
		tooltip_links_exist_for_x_objects: 'Links exist for some objects'
		},
		aOptions
	);

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
				if(oSelf.plugins.settings.combodo_update_operations.initial.includes(sItemValue)) {
					oSelf.listenClick($Item, sItemValue);
				}
				return;
			}

			// If no operation to restore
			if(!oSelf.plugins.settings.combodo_update_operations.initial.includes(sItemValue)) {

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
			if(!oSelf.plugins.settings.combodo_update_operations.initial.includes(sItem)) {

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
					data: CombodoGlobalToolbox.ExtractArrayItemsContainingThisKeyAndValue(aCurrentOptions, oSelf.settings.valueField, key)
				}
			}
		}
	})();

	// Declare listenClick function
	oSelf.listenClick = (function () {
		return function ($item, sItem) {

			// Listen item element click event
			$item.on('click', function(){

				// input disabled
				if(oSelf.$input.is(':disabled')){
					return;
				}

				// If element has operation
				if(aOperations[sItem] === OPERATIONS.add || aOperations[sItem] === OPERATIONS.remove) {

					// Restore state
					oSelf.Ignore($item, sItem);
				}
				else{

					// No need to add
					if(oSelf.options[sItem]['full'])
						return;

					// Add element
					oSelf.Add($item, sItem);
				}
			});
		}
	})();

	// Declare Add function
	oSelf.Add = (function () {
		return function ($item, sItem) {
			aOperations[sItem] = OPERATIONS.add;
			oSelf.updateOperationsInput();
			oSelf.ResetElementClass($item);
			oSelf.UpdateAllTooltip($item, sItem);
			$item.addClass(ITEMS_CLASSES.add);
		}
	})();

	// Declare Remove function
	oSelf.Remove = (function () {
		return function ($item, sItem) {
			aOperations[sItem] = OPERATIONS.remove;
			oSelf.updateOperationsInput();
			oSelf.ResetElementClass($item);
			oSelf.UpdateRemoveTooltip($item, sItem);
			$item.addClass(ITEMS_CLASSES.remove);
		}
	})();

	// Declare Ignore function
	oSelf.Ignore = (function () {
		return function ($item, sItem) {
			aOperations[sItem] = OPERATIONS.ignore;
			oSelf.updateOperationsInput();
			oSelf.ResetElementClass($item);
			oSelf.UpdateIgnoreTooltip($item, sItem);
			oSelf.options[sItem]['full'] ?
				$item.addClass(ITEMS_CLASSES.ignore_all) :
				$item.addClass(ITEMS_CLASSES.ignore_partial);
		}
	})();

	// Declare ResetElementClass function
	oSelf.ResetElementClass = (function () {
		return function ($item) {
			$item.removeClass(Object.values(ITEMS_CLASSES));
		}
	})();

	// Update add tooltip
	oSelf.UpdateAllTooltip = (function () {
		return function ($item, sItem) {
			const iOccurrence = oSelf.options[sItem]['occurrence'];
			let sTooltip = '';
			if(oSelf.options[sItem]['empty']){
				sTooltip = aOptions.tooltip_links_will_be_created_for_all_objects;
			}
			else if(iOccurrence === '1'){
				sTooltip = aOptions.tooltip_links_will_be_created_for_one_object;
			}
			else{
				sTooltip = aOptions.tooltip_links_will_be_created_for_x_objects.replaceAll('{count}', iOccurrence);
			}
			oSelf.CreateTooltip($item, sItem, sTooltip);
		}
	})();

	// Update remove tooltip
	oSelf.UpdateRemoveTooltip = (function () {
		return function ($item, sItem) {
			const iOccurrence = oSelf.options[sItem]['occurrence'];
			let sTooltip = '';
			if(oSelf.options[sItem]['full']){
				sTooltip = aOptions.tooltip_links_will_be_deleted_from_all_objects;
			}
			else if(oSelf.options[sItem]['occurrence'] === '1'){
				sTooltip = aOptions.tooltip_links_will_be_deleted_from_one_object;
			}
			else{
				sTooltip = aOptions.tooltip_links_will_be_deleted_from_x_objects.replaceAll('{count}', iOccurrence);
			}
			oSelf.CreateTooltip($item, sItem, sTooltip);
		}
	})();

	// Update ignore tooltip
	oSelf.UpdateIgnoreTooltip = (function () {
		return function ($item, sItem) {
			const iOccurrence = oSelf.options[sItem]['occurrence'];
			let sTooltip = '';
			if(oSelf.options[sItem]['full']){
				sTooltip = aOptions.tooltip_links_exist_for_all_objects;
			}
			else if(iOccurrence === '1'){
				sTooltip = aOptions.tooltip_links_exist_for_one_object;
			}
			else{
				sTooltip = aOptions.tooltip_links_exist_for_x_objects.replaceAll('{count}', iOccurrence);
			}
			oSelf.CreateTooltip($item, sItem, sTooltip);
		}
	})();

	// Update ignore tooltip
	oSelf.CreateTooltip = (function () {
		return function ($item, sItem, sTooltip) {
			$item.attr('data-tooltip-content', oSelf.options[sItem][this.settings.tooltipField] + '<br><span class="ibo-linked-set--bulk-tooltip-info">' + sTooltip + '</span>');
			$item.attr('data-tooltip-html-enabled', true);
			CombodoTooltip.InitTooltipFromMarkup($item, true);
		}
	})();
});