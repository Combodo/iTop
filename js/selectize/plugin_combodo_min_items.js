Selectize.define("combodo_min_items", function (aOptions) {

	// Selectize instance
	let oSelf = this;

	// Plugin options
	aOptions = $.extend({
			minItems: 0,
			errorTitle: 'This change is not allowed',
			errorMessage: 'Minimum ' + aOptions.minItems + ' item(s) required.'
		},
		aOptions
	);

	// Override removeItem function
	oSelf.removeItem = (function () {
		let oOriginal = oSelf.removeItem;
		return function () {
			if(oSelf.items.length <= aOptions.minItems) {
				CombodoModal.OpenErrorModal(aOptions.errorMessage, {
					title: aOptions.errorTitle
				});
				return;
			}
			return oOriginal.apply(this, arguments);
		}
	})();

});