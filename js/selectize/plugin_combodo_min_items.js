Selectize.define("combodo_min_items", function (aOptions) {

	// Selectize instance
	let oSelf = this;

	// Plugin options
	aOptions = $.extend({
			minItems: 0,
			errorMessage: 'Minimum ' + aOptions.minItems + ' item(s) required.'
		},
		aOptions
	);

	// Override removeItem function
	oSelf.removeItem = (function () {
		let oOriginal = oSelf.removeItem;
		return function () {
			if(oSelf.items.length <= aOptions.minItems) {
				CombodoModal.OpenErrorModal(aOptions.errorMessage, []);
				return;
			}
			return oOriginal.apply(this, arguments);
		}
	})();

});