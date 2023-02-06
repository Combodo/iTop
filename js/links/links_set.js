let LinkSet = new function () {

	/**
	 * Create a new link object and add it to set widget.
	 *
	 * @param sLinkedClass
	 * @param sCode
	 * @param sHostObjectClass
	 * @param sHostObjectKey
	 * @param sRemoteExtKey
	 * @param sRemoteClass
	 * @param oWidget
	 * @constructor
	 */
	const CallCreateLinkedObject = function(sLinkedClass, sCode, sHostObjectClass, sHostObjectKey, sRemoteExtKey, sRemoteClass, oWidget)
	{
		// Create link object
		LinkSetWorker.CreateLinkedObject(sLinkedClass, sCode, sHostObjectClass, sHostObjectKey,
			function(){
				$(this).find("form").remove();
				$(this).dialog('destroy');
			},
			function(event, data){

				// We have just create a link object, now request the remote object
				LinkSetWorker.GetRemoteObject(data.data.object.class_name, data.data.object.key, sRemoteExtKey, sRemoteClass, function(data){

					// Add the new remote object in widget set options list
					const selectize = oWidget[0].selectize;
					selectize.addOption(data.data.object);
					selectize.refreshOptions(false);

					// Select the new remote object
					selectize.addItem(data.data.object.key);

					// Add to initial values, to handle remove action
					selectize.addInitialValue(data.data.object.key);
				});
			});
	}


	return {
		CreateLinkedObject: CallCreateLinkedObject,
	}
};