let ObjectWorker = new function(){

	// defines
	const ROUTER_BASE_URL = '../pages/ajax.render.php';
	const ROUTE_MODIFY_OBJECT = 'object.modify';
	const ROUTE_GET_OBJECT = 'object.get';

	/**
	 * CallAjaxModifyObject.
	 *
	 * @param {string} sObjectClass
	 * @param {string} sObjectKey
	 * @param oOnModalCloseCallback
	 * @param oOnFormSubmittedCallback
	 * @constructor
	 */
	const CallAjaxModifyObject = function(sObjectClass, sObjectKey, oOnModalCloseCallback, oOnFormSubmittedCallback){

		let oOptions = {
			title: Dict.S('UI:Links:ActionRow:Modify:Modal:Title'),
			content: {
				endpoint: `${ROUTER_BASE_URL}?route=${ROUTE_MODIFY_OBJECT}`,
				data: {
					class: sObjectClass,
					id: sObjectKey,
				},
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
	 * CallAjaxGetObject.
	 *
	 * @param {string} sObjectClass
	 * @param {string} sObjectKey
	 * @param oOnResponseCallback
	 * @constructor
	 */
	const CallAjaxGetObject = function(sObjectClass, sObjectId, oOnResponseCallback){

		$.post(`${ROUTER_BASE_URL}?route=${ROUTE_GET_OBJECT}`, {
			object_class: sObjectClass,
			object_key: sObjectId,
		}, oOnResponseCallback);
	};


	return {
		ModifyObject: CallAjaxModifyObject,
		GetObject: CallAjaxGetObject
	}
};