//iTop Search form criteria external_field
;
$(function () {
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_external_field' the widget name
	$.widget('itop.search_form_criteria_external_field', $.itop.search_form_criteria_string,
		{
			// the constructor
			_create: function () {
				this._super();
				this.element.addClass('search_form_criteria_external_field');
			},

			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function () {
				this.element.removeClass('search_form_criteria_external_field');
				this._super();
			},

			//------------------
			// Inherited methods
			//------------------
		});
});
