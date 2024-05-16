/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Return column JSON declaration for row actions.
 * Could be part of column or columnDefs declaration of datatable.js.
 *
 * @param sTableId
 * @param iColumnTargetIndex
 * @returns {*}
 * @since 3.1.0
 */
function getRowActionsColumnDefinition(sTableId, iColumnTargetIndex = -1)
{
	let aColumn = {
		type: "html",
		orderable: false,
		render: function ( data, type, row, meta ) {
			return $(`#${sTableId}_actions_buttons_template`).html();
		}
	};

	if (iColumnTargetIndex !== -1) {
		aColumn['targets'] = iColumnTargetIndex;
	}

	return aColumn;
}