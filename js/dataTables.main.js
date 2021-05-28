/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

function checkAllDataTable(tableId, value, listId) {
	let tableSelector = $('#'+tableId);
	// Set the 'selectionMode' for the future objects to load
	let selectionMode = 'positive';
	if (value) {
		selectionMode = 'negative';
	}
	window['oSelectedItems'+listId] = [];
	// Mark all the displayed items as check or unchecked depending on the value
	tableSelector.find(':checkbox[name^=selectObj]:not([disabled])').each(function () {
		let currentCheckbox = $(this);
		currentCheckbox.prop('checked', value);
		let currentLine = currentCheckbox.closest("tr");
		(value) ? currentLine.addClass("selected") : currentLine.removeClass("selected");
	});

	tableSelector.parent().parent().find(':input[name=selectionMode]').val(selectionMode);
	// Reset the list of saved selection...
	$(':input[name^=storedSelection]').remove();
	tableSelector.parent().find(':checkbox[name^=selectObj]').trigger("change");

	if (value) {
		tableSelector.DataTable().rows().select();
	} else {
		tableSelector.DataTable().rows({page: 'current'}).deselect();
	}
	updateDataTableSelection(listId);

	return true;
}

function updateDataTableSelection(listId) {
	let selectionContainer = $('#'+listId+' [data-target="ibo-datatable--selection"]');
	let selectionCount = $('#'+listId+' [name="selectionCount"]');
	let selectionMode = $('#'+listId+' [name=selectionMode]').val();

	selectionContainer.html('');
	let currentSelection = window['oSelectedItems'+listId];
	for(let i in currentSelection) {
		let value = currentSelection[i];
		selectionContainer.append('<input type="hidden" name="storedSelection[]" value="' + value + '">');
	}

	if (selectionMode === 'negative') {
		let total = $('#'+$aOptions.sListId).DataTable().page.info()["recordsTotal"];
		selectionCount.val(total-currentSelection.length);
	} else {
		selectionCount.val(currentSelection.length);
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