//iTop Search form criteria date_time
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_date_time' the widget name
	$.widget( 'itop.search_form_criteria_date_time', $.itop.search_form_criteria_date_abstract,
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
					"default_time_add": false,
					"show_on_advanced": false,
					"value_index": 0,
					"onclose_show" : "until",
					"synced_with": "from_time",
					"getter_code":"from_time",
				},
				{
					"code": "from_time",
					"code_uc_first":"FromTime",
					"x_picker" : 'datetimepicker',
					"default_time_add": 0,
					"show_on_advanced": true,
					"value_index": 0,
					"onclose_show" : "until_time",
					"synced_with": "from",
				},
				{
					"code": "until",
					"code_uc_first":"Until",
					"x_picker" : 'datepicker',
					"default_time_add": false,
					"show_on_advanced": false,
					"value_index": 1,
					"synced_with": "until_time",
					"getter_code":"until_time",
				},
				{
					"code": "until_time",
					"code_uc_first":"UntilTime",
					"x_picker" : 'datetimepicker',
					"default_time_add": 86399, // 24 * 60 * 60 - 1
					"show_on_advanced": true,
					"value_index": 1,
					"synced_with": "until"
				}
			]
		},

   
		// the constructor
		_create: function()
		{
			var me = this;

			this._super();
			this.element.addClass('search_form_criteria_date_time');
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria_date_time');
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
