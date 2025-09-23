const iTopObjectWorker = new function(){

	// defines
	const ROUTER_BASE_URL = '../pages/ajax.render.php';
	const ROUTE_CREATE_OBJECT = 'object.new';
	const ROUTE_MODIFY_OBJECT = 'object.modify';
	const ROUTE_GET_OBJECT = 'object.get';

	/**
	 * CallAjaxCreateObject.
	 *
	 * @param {string} sTitle
	 * @param {string} sClass
	 * @param oOnModalCloseCallback
	 * @param oOnFormSubmittedCallback
	 * @param {Object} aAdditionalData
	 * @constructor
	 */
	const CallAjaxCreateObject = function(sTitle, sClass, oOnModalCloseCallback = null, oOnFormSubmittedCallback = null, aAdditionalData = []){
		let aData = $.extend(
			{
				class: sClass,
			},
			aAdditionalData
		);

		let oOptions = {
			title: sTitle,
			content: {
				endpoint: `${ROUTER_BASE_URL}?route=${ROUTE_CREATE_OBJECT}`,
				data: aData
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
	 * @param {string} sTitle
	 * @param {string} sObjectClass
	 * @param {string} sObjectKey
	 * @param oOnModalCloseCallback
	 * @param oOnFormSubmittedCallback
	 * @param {Object} aAdditionalData
	 * @constructor
	 */
	const CallAjaxModifyObject = function(sTitle, sObjectClass, sObjectKey, oOnModalCloseCallback, oOnFormSubmittedCallback, aAdditionalData = []){
		let aData = $.extend(
			{
				class: sObjectClass,
				id: sObjectKey,
			},
			aAdditionalData
		);

		let oOptions = {
			title: sTitle,
			content: {
				endpoint: `${ROUTER_BASE_URL}?route=${ROUTE_MODIFY_OBJECT}`,
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
	 * CallAjaxGetObject.
	 *
	 * @param {string} sObjectClass
	 * @param {string} sObjectId
	 * @param oOnResponseCallback
	 * @param {Object} aAdditionalData
	 * @constructor
	 */
	const CallAjaxGetObject = function(sObjectClass, sObjectId, oOnResponseCallback, aAdditionalData = []){
		let aData = $.extend(
			{
				object_class: sObjectClass,
				object_key: sObjectId,
			},
			aAdditionalData
		)

		$.post(`${ROUTER_BASE_URL}?route=${ROUTE_GET_OBJECT}`, aData, oOnResponseCallback);
	};


	return {
		CreateObject: CallAjaxCreateObject,
		ModifyObject: CallAjaxModifyObject,
		GetObject: CallAjaxGetObject
	}
};