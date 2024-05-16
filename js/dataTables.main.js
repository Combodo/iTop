/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

function checkAllDataTable(tableId, value, listId) {
	let tableSelector = $('#'+tableId);
	// Set the 'selectionMode' for the future objects to load
	let selectionMode = 'positive';
	if (value) {
		selectionMode = 'negative';
	}
	window['oSelectedItems'+CombodoSanitizer.Sanitize(listId, '', CombodoSanitizer.ENUM_SANITIZATION_FILTER_VARIABLE_NAME)] = [];
	// Mark all the displayed items as check or unchecked depending on the value
	tableSelector.find(':checkbox[name^=selectObj]:not([disabled])').each(function () {
		let currentCheckbox = $(this);
		currentCheckbox.prop('checked', value);
		let currentLine = currentCheckbox.closest("tr");
		(value) ? currentLine.addClass("selected") : currentLine.removeClass("selected");
	});

	tableSelector.closest(".dataTables_wrapper").parent().find(':input[name=selectionMode]').val(selectionMode);
	// Reset the list of saved selection...
	$(':input[name^=storedSelection]').remove();
	tableSelector.parent().find(':checkbox[name^=selectObj]').trigger("change");

	if (value) {
		tableSelector.DataTable().rows().select();
	} else {
		tableSelector.DataTable().rows({page: 'current'}).deselect();
	}
	updateDataTableSelection(listId, tableId);

	return true;
}

function updateDataTableSelection(listId, tableId) {
	let selectionContainer = $('#'+listId+' [data-target="ibo-datatable--selection"]');
	let selectionCount = $('#'+listId+' [name="selectionCount"]');
	let selectionMode = $('#'+listId+' [name=selectionMode]').val();

	selectionContainer.html('');
	let currentSelection = window['oSelectedItems'+CombodoSanitizer.Sanitize(listId, '', CombodoSanitizer.ENUM_SANITIZATION_FILTER_VARIABLE_NAME)];
	for (let i in currentSelection) {
		let value = currentSelection[i];
		selectionContainer.append('<input type="hidden" name="storedSelection[]" value="'+value+'">');
	}

	if (selectionMode === 'negative') {
		let total = $('#'+tableId).DataTable().page.info()["recordsTotal"];
		selectionCount.val(total-currentSelection.length);
		$('#'+tableId).closest('.ibo-panel').find('.ibo-datatable--selected-count').html(total-currentSelection.length);
	} else {
		selectionCount.val(currentSelection.length);
		$('#'+tableId).closest('.ibo-panel').find('.ibo-datatable--selected-count').html(currentSelection.length);
	}

	selectionCount.trigger('change');
}

function getMultipleSelectionParams(listId)
{
	var oRes = {};

	oRes.selectionMode = '';
	if ($('#'+listId+' [name=selectionMode]').length > 0) {
		oRes.selectionMode =  $('#'+listId+' [name=selectionMode]').val();
	}

	oRes.selectObject = [];
	$('#'+listId+' [name^=selectObject]:checked').each(function() {
		oRes.selectObject.push($(this).val());
	});

	oRes.storedSelection = [];
	$('#'+listId+' [name^=storedSelection]').each(function() {
		oRes.storedSelection.push($(this).val());
	});

	return oRes;
}

