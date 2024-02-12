Selectize.define("combodo_min_items", function (aOptions) {

	// Selectize instance
	let oSelf = this;

	// Plugin options
	aOptions = $.extend({
			minItems: 0,
		},
		aOptions
	);

	// Override removeItem function
	oSelf.removeItem = (function () {
		let oOriginal = oSelf.removeItem;
		return function () {
			if(oSelf.items.length <= aOptions.minItems) {
				CombodoModal.OpenErrorModal('Minimum ' + aOptions.minItems + ' items required.');
				return;
			}
			return oOriginal.apply(this, arguments);
		}
	})();

});