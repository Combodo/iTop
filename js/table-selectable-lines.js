/*
 *  Copyright (c) 2010-2018 Combodo SARL
 *
 *    This file is part of iTop.
 *
 *    iTop is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    iTop is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

$(document).ready(function () {
	var SELECTED_CLASS = "selected";
	var TABLE_SELECTOR = 'table.listResults';

	// we want to select :radio and :checkbox, but there is no :is() selector, so we're using existing :not()
	// not ideal but I don't have a better idea for now :/
	var INPUT_SELECTOR = 'input:not([type=image],[type=button],[type=submit])';

	var FIRST_CELL_WITH_INPUT_SELECTOR = 'td:first-child>'+INPUT_SELECTOR;
	var LINE_WITH_INPUT_IN_FIRST_CELL_SELECTOR = "tbody>tr>"+FIRST_CELL_WITH_INPUT_SELECTOR;
	var CELLS_WITH_INPUT_SELECTOR = 'td>'+INPUT_SELECTOR;
	var LINE_WITH_INPUTS_SELECTOR = "tbody>tr>"+CELLS_WITH_INPUT_SELECTOR;


	// Tables with inputs inside cells
	$(document).on('click', TABLE_SELECTOR+':has('+LINE_WITH_INPUTS_SELECTOR+')', function (event) {
		var $eventTarget = $(event.target);
		if (shouldExitHandler($eventTarget)) {
			return;
		}

		var $cellClicked = $eventTarget.closest("td");
		var $cellClickedInput = $cellClicked.find(INPUT_SELECTOR);
		if ($cellClickedInput.length === 1) {
			$cellClickedInput.click();
		}
	});


	// Tables with one input in the first cell to select lines
	$(document).on('click', TABLE_SELECTOR+':has('+LINE_WITH_INPUT_IN_FIRST_CELL_SELECTOR+')', function (event) {
		var $eventTarget = $(event.target);
		if (shouldExitHandler($eventTarget)) {
			return;
		}

		var $lineClicked = $eventTarget.closest("tr");
		var $lineClickedInput = $lineClicked.find(FIRST_CELL_WITH_INPUT_SELECTOR);
		$lineClickedInput.click();
	});

	$(document).on('change', TABLE_SELECTOR, function (event) {
		var $eventTarget = $(event.target);
		if (!$eventTarget.has(LINE_WITH_INPUT_IN_FIRST_CELL_SELECTOR))
		// Originally we had :has in the handler selector but performances were very bad :(
		// Filtering directly in JQuery is far much quicker ! => N°2192
		{
			return;
		}
		if (!$eventTarget.is(INPUT_SELECTOR)) {
			return;
		}

		updateLines($eventTarget);
	});

	// check_all event is fired for tableSorter JQuery plugin
	$(document).on("check_all", TABLE_SELECTOR, function (event) {
		var $eventTarget = $(event.target);
		if (!$eventTarget.has(LINE_WITH_INPUT_IN_FIRST_CELL_SELECTOR))
		// Originally we had :has in the handler selector but performances were very bad :(
		// Filtering directly in JQuery is far much quicker ! => N°2192
		{
			return;
		}
		$(this).find("tbody>tr").addClass(SELECTED_CLASS);
	});

	// update when clicking on the header checkbox/radio input is handled in tablesorterPager !


	/**
	 * Our custom handlers chould run only if clicking on somewhere without event already attached !
	 * @param $eventTarget
	 * @returns {boolean} true if our custom handler shouldn't be run
	 */
	function shouldExitHandler($eventTarget) {
		if ($eventTarget.is("table")) { // might happen on cell padding/margin/border
			return true;
		}
		if ($eventTarget.is("a, button")) {
			return true;
		}
		if ($eventTarget.parent().is("a, button")) {
			return true;
		}
		if ($eventTarget.is("input, select, option")) {
			return true;
		}
		if ($eventTarget.is("img")) { // too hard to determine if an event handler is attached so excluding all !
			return true;
		}

		return false;
	}


	function updateLines($inputChanged) {
		var $selectedLine = $inputChanged.closest("tr");

		// didn't find a proper event fired when radio is deselected... so doing this !
		if ($inputChanged.is('input:radio'))
		{
			$selectedLine
				.closest('table')
				.find('tr')
				.removeClass(SELECTED_CLASS);
		}

		$selectedLine.toggleClass(SELECTED_CLASS);
	}
});
