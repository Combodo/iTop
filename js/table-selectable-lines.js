/*
 *  Copyright (c) 2010-2024 Combodo SAS
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


	// Set a click handler on all tables containing inputs
	// since 3.0.1 N°4619 we are using only one handler for both cases :
	// - clicking in a cell that is not the first child, and that contains one input:radio or input:checkbox
	// - clicking anywhere in a line
	$(document).on('click', TABLE_SELECTOR+':has('+LINE_WITH_INPUTS_SELECTOR+')', function (event) {
		var $eventTarget = $(event.target);
		if (shouldExitHandler($eventTarget)) {
			return;
		}

		var $cellClicked = $eventTarget.closest("td");
		var $cellClickedInput = $cellClicked.find(INPUT_SELECTOR);
		if (($cellClickedInput.length === 1)
			&& ($cellClickedInput.is("input:radio") || $cellClickedInput.is("input:checkbox"))
		) {
			$cellClickedInput.click();

			if ($cellClicked.not(":first-child")) {
				return;
			}
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
	 * Our custom handlers should run only if clicking on somewhere without event already attached !
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
		if ($eventTarget.is(".fas, i.fa")) { // Font Awesome buttons
			return true;
		}
		if ($eventTarget.parent().is("a, button")) {
			return true;
		}
		if ($eventTarget.is("input, select, option")) {
			return true;
		}
		if ($eventTarget.parent().is(".selectize-control,.selectize-input")) {
			return true;
		}
		if ($eventTarget.is("img")) { // too hard to determine if an event handler is attached so excluding all !
			return true;
		}

		return false;
	}


	function updateLines($inputChanged) {
		var $selectedCell = $inputChanged.closest("td");
		if (false === $selectedCell.is("tr>td:first-child")) {
			return;
		}

		var $selectedLine = $inputChanged.closest("tr");

		if($inputChanged.prop('checked')) {
			$selectedLine.addClass(SELECTED_CLASS);
		} else {
			$selectedLine.removeClass(SELECTED_CLASS);
		}
	}
});
