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
			// aInputsParam: [
			// 	{
			// 		"code": "from",
			// 		"code_uc_first":"From",
			// 		"x_picker" : 'datepicker',
			// 		"default_time_add": false,
			// 		"show_on_advanced": false,
			// 		"value_index": 0,
			// 		"onclose_show" : "until",
			// 		"synced_with": "from_time",
			// 		//"getter_code":"from_time",
			// 		"getter_suffix":" 00:00:00",
			// 	},
			// 	{
			// 		"code": "from_time",
			// 		"code_uc_first":"FromTime",
			// 		"x_picker" : 'datetimepicker',
			// 		"default_time_add": 0,
			// 		"show_on_advanced": true,
			// 		"value_index": 0,
			// 		"onclose_show" : "until_time",
			// 		"synced_with": "from",
			// 		"title_getter_code":"from",
			// 	},
			// 	{
			// 		"code": "until",
			// 		"code_uc_first":"Until",
			// 		"x_picker" : 'datepicker',
			// 		"default_time_add": false,
			// 		"show_on_advanced": false,
			// 		"value_index": 1,
			// 		"synced_with": "until_time",
			// 		//"getter_code":"until_time",
			// 		"getter_suffix":" 23:59:59",
			// 	},
			// 	{
			// 		"code": "until_time",
			// 		"code_uc_first":"UntilTime",
			// 		"x_picker" : 'datetimepicker',
			// 		"default_time_add": 86399, // 24 * 60 * 60 - 1
			// 		"show_on_advanced": true,
			// 		"value_index": 1,
			// 		"synced_with": "until",
			// 		"title_getter_code":"until",
			// 	}
			// ]
			aInputsParam: [
				{
					"code": "from_time",
					"code_uc_first":"FromTime",
					"x_picker" : 'datetimepicker',
					"default_time_add": 0,
					"value_index": 0,
					"onclose_show" : "until_time",
					"has_time": true,
				},
				{
					"code": "until_time",
					"code_uc_first":"UntilTime",
					"x_picker" : 'datetimepicker',
					// "default_time_add": 86399, // 24 * 60 * 60 - 1
					"picker_extra_params": {
						"hour": 23,
						"minute":59,
						"second":59
					},
					"value_index": 1,
                    "has_time": true,
				}
			]
		},


		//------------------
		// Inherited methods
		//------------------
   
		// the constructor
		_create: function()
		{
			var me = this;

			me._super();
			me.element.addClass('search_form_criteria_date_time');
		},

		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			var me = this;

			me.element.removeClass('search_form_criteria_date_time');
			me._super();
		},

		_prepareBetweenDaysOperator: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;
			var showAvancedOnInit = false;

			me._super(oOpElem, sOpIdx, oOp);
			for (i = 0; i <= 1; i++) {
				if (typeof me.options.values[i] != 'undefined' && typeof me.options.values[i].value != 'undefined')
				{
					if (me.options.values[i].value.length > 10)
					{
						if (i == 0 && me.options.values[i].value.indexOf(' 00:00:00') == 10)
						{
							continue;
						}
						if (i == 1 && me.options.values[i].value.indexOf(' 23:59:59') == 10)
						{
							continue;
						}

						showAvancedOnInit = true;
					}
				}
			}

			if (showAvancedOnInit)
			{
				this.element.find('.sfc_form_group').addClass('advanced');
			}

		}



	});
});
