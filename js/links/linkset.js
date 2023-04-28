const iTopLinkSet = new function () {

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
	const CallCreateLinkedObject = function(sLinkedClass, oWidget)
	{
		// Create link object
		iTopObjectWorker.CreateObject(Dict.S('UI:Links:Object:New:Modal:Title'), sLinkedClass, function(){
				$(this).find("form").remove();
				$(this).dialog('destroy');
			},
			function(event, data){

				// We have just create a link object, now request the remote object
				iTopObjectWorker.GetObject(data.data.object.class_name, data.data.object.key, function(data){

					// Add the new remote object in widget set options list
					const selectize = oWidget[0].selectize;
					selectize.addOption(data.data.object);
					selectize.refreshOptions(false);

					// Select the new remote object
					selectize.addItem(data.data.object.key);
				});
			});
	}


	return {
		CreateLinkedObject: CallCreateLinkedObject,
	}
};