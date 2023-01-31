let LinkSetWorker = new function(){

	// defines
	const ROUTER_BASE_URL = '../pages/ajax.render.php';
	const ROUTE_LINK_SET_DELETE_OBJECT = 'linkset.delete_linked_object';
	const ROUTE_LINK_SET_DETACH_OBJECT = 'linkset.detach_linked_object';
	const ROUTE_LINK_SET_MODIFY_OBJECT = 'object.modify';
	const ROUTE_LINK_SET_CREATE_OBJECT = 'linkset.create_linked_object';

	/**
	 * CallAjaxDeleteLinkedObject.
	 *
	 * @param sLinkedObjectClass
	 * @param sLinkedObjectKey
	 * @param sTableId
	 * @constructor
	 */
	const CallAjaxDeleteLinkedObject = function(sLinkedObjectClass, sLinkedObjectKey, sTableId){
		let oTableSettingsDialog = $('#datatable_dlg_datatable_' + sTableId);

		$.post(`${ROUTER_BASE_URL}?route=${ROUTE_LINK_SET_DELETE_OBJECT}`, {
			linked_object_class: sLinkedObjectClass,
			linked_object_key: sLinkedObjectKey,
			transaction_id: $('#linkset_transactions_id').val()
		}, function (data) {
			if(data.data.success === true){
				oTableSettingsDialog.DataTableSettings('DoRefresh');
			}
			else{
				CombodoModal.OpenInformativeModal(data.data.error_message, 'error');
			}
		});
	};

	/**
	 * CallAjaxDetachLinkedObject.
	 *
	 * @param sLinkedObjectClass
	 * @param sLinkedObjectKey
	 * @param sExternalKeyAttCode
	 * @param sTableId
	 * @constructor
	 */
	const CallAjaxDetachLinkedObject = function(sLinkedObjectClass, sLinkedObjectKey, sExternalKeyAttCode, sTableId){
		let oTableSettingsDialog = $('#datatable_dlg_datatable_' + sTableId);

		$.post(`${ROUTER_BASE_URL}?route=${ROUTE_LINK_SET_DETACH_OBJECT}`, {
			linked_object_class: sLinkedObjectClass,
			linked_object_key: sLinkedObjectKey,
			external_key_att_code: sExternalKeyAttCode,
			transaction_id: $('#linkset_transactions_id').val()
		}, function (data) {
			if(data.data.success === true){
				oTableSettingsDialog.DataTableSettings('DoRefresh');
			}
			else{
				CombodoModal.OpenInformativeModal(data.data.error_message, 'error');
			}
		});
	};

	/**
	 * CallAjaxModifyLinkedObject.
	 *
	 * @param {string} sLinkedObjectClass
	 * @param {string} sLinkedObjectKey
	 * @param {string} sTableId
	 * @constructor
	 */
	const CallAjaxModifyLinkedObject = function(sLinkedObjectClass, sLinkedObjectKey, sTableId){
		let oTable = $('#datatable_' + sTableId);
		let oTableSettingsDialog = $('#datatable_dlg_datatable_' + sTableId);

		let oOptions = {
			title: Dict.S('UI:Links:ActionRow:Modify:Modal:Title'),
			content: {
				endpoint: `${ROUTER_BASE_URL}?route=${ROUTE_LINK_SET_MODIFY_OBJECT}`,
				data: {
					class: sLinkedObjectClass,
					id: sLinkedObjectKey,
				},
			},
			extra_options: {
				callback_on_modal_close: function () {
					oTableSettingsDialog.DataTableSettings('DoRefresh');
					$(this).find("form").remove();
					$(this).dialog('destroy');
				}
			},
		}
		CombodoModal.OpenModal(oOptions);
	};

	/**
	 * @param {string} sTableId
	 */
	const CallAjaxCreateLinkedObject = function(sTableId){
		let oTable = $('#datatable_' + sTableId);
		let oTableSettingsDialog = $('#datatable_dlg_datatable_' + sTableId);
		let sClass = oTable.closest('[data-role="ibo-block-links-table"]').attr('data-link-class');
		let sAttCode = oTable.closest('[data-role="ibo-block-links-table"]').attr('data-link-attcode');
		let sHostObjectClass = oTable.closest('[data-role="ibo-object-details"]').attr('data-object-class');
		let sHostObjectId = oTable.closest('[data-role="ibo-object-details"]').attr('data-object-id');
		
		let oOptions = {
			title: Dict.S('UI:Layout:ObjectDetails:New:Modal:Title'),
			content: {
				endpoint: `${ROUTER_BASE_URL}?route=${ROUTE_LINK_SET_CREATE_OBJECT}`,
				data: {
					class: sClass,
					att_code: sAttCode,
					host_class: sHostObjectClass,
					host_id: sHostObjectId
				}
			},
			extra_options: {
				callback_on_modal_close: function () {
					oTableSettingsDialog.DataTableSettings('DoRefresh');
					$(this).find("form").remove();
					$(this).dialog('destroy');
				}
			},
		}
		CombodoModal.OpenModal(oOptions);
	};

	return {
		DeleteLinkedObject: CallAjaxDeleteLinkedObject,
		DetachLinkedObject: CallAjaxDetachLinkedObject,
		ModifyLinkedObject: CallAjaxModifyLinkedObject,
		CreateLinkedObject: CallAjaxCreateLinkedObject
	}
};