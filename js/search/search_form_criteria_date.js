//iTop Search form criteria date
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_date' the widget name
	$.widget( 'itop.search_form_criteria_date', $.itop.search_form_criteria_date_abstract,
	{
		// default options
		options:
		{
			// // Overload default operator
			// 'operator': 'between_dates',
			// // Available operators
			// 'available_operators': {
			//
			// },
			aInputsParam: [
				{
					"code": "from",
					"code_uc_first":"From",
					"x_picker" : 'datepicker',
					"value_index": 0,
					"onclose_show" : "until",
                    "has_time": false,
				},
				{
					"code": "until",
					"code_uc_first":"Until",
					"x_picker" : 'datepicker',
					"value_index": 1,
                    "has_time": false,
				}
			]
		},

   
		// the constructor
		_create: function()
		{
			var me = this;

			this._super();
			this.element.addClass('search_form_criteria_date');
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria_date');
			this._super();
		},
		// _setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		_setOptions: function()
		{
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			this._super( key, value );
		},


		//------------------
		// Inherited methods
		//------------------




	});
});
