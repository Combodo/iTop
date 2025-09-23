const iTopLinkSetWorker = new function(){

	// defines
	const ROUTER_BASE_URL = '../pages/ajax.render.php';
	const ROUTE_LINK_SET_DELETE_OBJECT = 'linkset.delete_linked_object';
	const ROUTE_LINK_SET_DETACH_OBJECT = 'linkset.detach_linked_object';
	const ROUTE_LINK_SET_CREATE_OBJECT = 'linkset.create_linked_object';
	const ROUTE_LINK_GET_REMOTE_OBJECT = 'linkset.get_remote_object';

	/**
	 * CallAjaxDeleteLinkedObject.
	 *
	 * @param {string} sLinkedObjectClass
	 * @param {string} sLinkedObjectKey
	 * @param oOnResponseCallback
	 * @constructor
	 */
	const CallAjaxDeleteLinkedObject = function(sLinkedObjectClass, sLinkedObjectKey, oOnResponseCallback){

		$.post(`${ROUTER_BASE_URL}?route=${ROUTE_LINK_SET_DELETE_OBJECT}`, {
			linked_object_class: sLinkedObjectClass,
			linked_object_key: sLinkedObjectKey,
			transaction_id: $('#linkset_transactions_id').val()
		}, oOnResponseCallback);
	};

	/**
	 * CallAjaxDetachLinkedObject.
	 *
	 * @param {string} sLinkedObjectClass
	 * @param {string} sLinkedObjectKey
	 * @param {string} sExternalKeyAttCode
	 * @param oOnResponseCallback
	 * @constructor
	 */
	const CallAjaxDetachLinkedObject = function(sLinkedObjectClass, sLinkedObjectKey, sExternalKeyAttCode, oOnResponseCallback){

		$.post(`${ROUTER_BASE_URL}?route=${ROUTE_LINK_SET_DETACH_OBJECT}`, {
			linked_object_class: sLinkedObjectClass,
			linked_object_key: sLinkedObjectKey,
			external_key_att_code: sExternalKeyAttCode,
			transaction_id: $('#linkset_transactions_id').val()
		}, oOnResponseCallback);
	};

	/**
	 * CallAjaxCreateLinkedObject.
	 *
	 * @param {string} sModalTitle
	 * @param {string} sClass
	 * @param {string} sAttCode
	 * @param {string} sHostObjectClass
	 * @param {string} sHostObjectId
	 * @param oOnModalCloseCallback
	 * @param oOnFormSubmittedCallback
	 * @param {Object} aAdditionalData
	 */
	const CallAjaxCreateLinkedObject = function(sModalTitle, sClass, sAttCode, sHostObjectClass, sHostObjectId, oOnModalCloseCallback = null, oOnFormSubmittedCallback = null, aAdditionalData = []){

		let aData = $.extend(
			{
				class: sClass,
				att_code: sAttCode,
				host_class: sHostObjectClass,
				host_id: sHostObjectId
			},
			aAdditionalData
		);

		let oOptions = {
			title: sModalTitle,
			content: {
				endpoint: `${ROUTER_BASE_URL}?route=${ROUTE_LINK_SET_CREATE_OBJECT}`,
				data: aData,
			},
			extra_options: {
				callback_on_modal_close: oOnModalCloseCallback
			},
		}

		const oModal = CombodoModal.OpenModal(oOptions);
		if(oOnFormSubmittedCallback !== null){
			oModal.on('itop.form.submitted', 'form', oOnFormSubmittedCallback);
		}
	};

	/**
	 * CallGetRemoteObject.
	 *
	 * @param sLinkedObjectClass
	 * @param sLinkedObjectKey
	 * @param sExternalKeyAttCode
	 * @param sRemoteClass
	 * @param oOnResponseCallback
	 * @constructor
	 */
	const CallGetRemoteObject = function(sLinkedObjectClass, sLinkedObjectKey, sExternalKeyAttCode, sRemoteClass, oOnResponseCallback){

		$.post(`${ROUTER_BASE_URL}?route=${ROUTE_LINK_GET_REMOTE_OBJECT}`, {
			linked_object_class: sLinkedObjectClass,
			linked_object_key: sLinkedObjectKey,
			external_key_att_code: sExternalKeyAttCode,
			remote_class: sRemoteClass
		}, oOnResponseCallback);
	};

	return {
		DeleteLinkedObject: CallAjaxDeleteLinkedObject,
		DetachLinkedObject: CallAjaxDetachLinkedObject,
		CreateLinkedObject: CallAjaxCreateLinkedObject,
		GetRemoteObject: CallGetRemoteObject
	}
};