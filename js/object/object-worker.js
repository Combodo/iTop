const iTopObjectWorker = new function(){

	// defines
	const ROUTER_BASE_URL = '../pages/ajax.render.php';
	const ROUTE_CREATE_OBJECT = 'object.new';
	const ROUTE_MODIFY_OBJECT = 'object.modify';
	const ROUTE_GET_OBJECT = 'object.get';

	const CallAjaxCreateObject = function(sClass, oOnModalCloseCallback = null, oOnFormSubmittedCallback = null){

		let oOptions = {
			title: Dict.S('UI:Object:Modal:Title'),
			content: {
				endpoint: `${ROUTER_BASE_URL}?route=${ROUTE_CREATE_OBJECT}`,
				data: {
					class: sClass,
				}
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
	 * @param {string} sObjectId
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
		CreateObject: CallAjaxCreateObject,
		ModifyObject: CallAjaxModifyObject,
		GetObject: CallAjaxGetObject
	}
};