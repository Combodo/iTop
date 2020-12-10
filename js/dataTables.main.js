function checkAllDataTable(tableId, value, listId) {
    let tableSelector = $('#'+tableId);
    // Set the 'selectionMode' for the future objects to load
    let selectionMode = 'positive';
    if (value) {
        selectionMode = 'negative';
    }
    window['oSelectedItems' + listId] = [];
    updateDataTableSelection(listId);
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
        $('#btn_ok_'+tableId).prop('disabled', false);
    } else {
        tableSelector.DataTable().rows({page: 'current'}).deselect();
        $('#btn_ok_'+tableId).prop('disabled', true);
    }

    return true;
}

function updateDataTableSelection(listId) {
    let selectionContainer = $('#'+listId+' [data-target="ibo-datatable--selection"]');
    let selectionCount = $('#'+listId+' [name="selectionCount"]');
    selectionContainer.html('');
    let currentSelection = window['oSelectedItems'+listId];
    for(let i in currentSelection) {
        let value = currentSelection[i];
        selectionContainer.append('<input type="hidden" name="storedSelection[]" value="' + value + '">');
    }
    selectionCount.val(currentSelection.length);
}