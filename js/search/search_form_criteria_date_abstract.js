//iTop Search form criteria date_abstract
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_date_abstract' the widget name
	$.widget( 'itop.search_form_criteria_date_abstract', $.itop.search_form_criteria,
	{
		// default options
		options:
		{
			// Overload default operator
			'operator': 'between_dates',
			// Available operators
			'available_operators': {
				'between_dates': {
					'label': Dict.S('UI:Search:Criteria:Operator:DateTime:Between'),
					'code': 'between_days',
					'rank': 1,
				},
				'=': {
					'label': Dict.S('UI:Search:Criteria:Operator:DateTime:Equals'),//pre-existing, label changed
					// 'dropdown_group':1,
				},
				'>': {
					'label': Dict.S('UI:Search:Criteria:Operator:DateTime:GreaterThan'),
					'code': 'greater_than',
					'rank': 100,
					// 'dropdown_group':1,
				},
				'>=': {
					'label': Dict.S('UI:Search:Criteria:Operator:DateTime:GreaterThanOrEquals'),
					'code': 'greater_than_or_equals',
					'rank': 200,
					// 'dropdown_group':1,
				},
				'<': {
					'label': Dict.S('UI:Search:Criteria:Operator:DateTime:LessThan'),
					'code': 'less_than',
					'rank': 300,
					// 'dropdown_group':1,
				},
				'<=': {
					'label': Dict.S('UI:Search:Criteria:Operator:DateTime:LessThanOrEquals'),
					'code': 'less_than_or_equals',
					'rank': 400,
					// 'dropdown_group':1,
				},
				'!=': {
					'label': Dict.S('UI:Search:Criteria:Operator:DateTime:DifferentThan'),
					'code': 'different',
					'rank': 500,
					// 'dropdown_group':1,
				},
				'empty': {
					'rank': 700,//pre-existing, reordered
				},
				'not_empty': {
					'rank': 800,//pre-existing, reordered
				},
			},
			aInputsParam: [
				{
					// Common settings :
					//  "code": "from",					=> the code used in the HTML
					//  "code_uc_first":"From",			=> the code used in the translations
					//  "onclose_show" : "until",		=> on x_picker close, should we open another one (on "from" close shall we show "until")
					//  "value_index": 0,				=> the widget communicate with an array of values, the index 0 is "from" the index 1 is "until"

					// Date_time widget specifi settings :
					//  "x_picker" : 'datetimepicker',	=> the plugin used either datepicker or datetimepicker
					//  "default_time_add": false,		=> either false to disable it or number of second to add (used by the datetimepicker to choose the right time on synched datepicker change, its value change from 0 for "from" to +1d-1s for "until"
					//  "show_on_advanced": true,		=> is the input displaye on "more" or "less" mode advanced is an lais for "more" in the css
					//  "synced_with": "from_time",		=> from and until has both two input (datepicker and datetimepicker). each time one input change, the other one has to change
					//  "getter_code":"from_time"			=> iTop expect datetime to always provide time, so we must always use the datetimepicker in order to compute the value to be sent to iTop event if whe are in the display mode "less" with datepicker visible
				}
			]
		},

   
		// // the constructor
		// _create: function()
		// {
		// 	this._super();
		// },
		// // called when created, and later when changing options
		// _refresh: function()
		// {
		//
		// },
		// // events bound via _bind are removed automatically
		// // revert other modifications here
		// _destroy: function()
		// {
		// 	this._super();
		// },
		// // _setOptions is called with a hash of all options that are changing
		// // always refresh when changing options
		// _setOptions: function()
		// {
		// 	this._superApply(arguments);
		// },
		// // _setOption is called for each individual option that is changing
		// _setOption: function( key, value )
		// {
		// 	this._super( key, value );
		// },


		// Prepare operator's DOM element
		_prepareBetweenDaysOperator: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;
			var aInputsParam = me.options.aInputsParam;
			var aInputsParamLength = aInputsParam.length;
			var aValues = me._getValues();//TODO : tenir compte du refactoring de la structure
			var oInputsParamIndexByCode = {};
			for (var i = 0; i < aInputsParamLength; i++) {
				oInputsParamIndexByCode[aInputsParam[i].code] = i;
			}

			oContentElem = $(); //will be populated on each loop

			for (var i = 0; i < aInputsParamLength; i++) {
				var oInputParam = aInputsParam[i];
				var oOpContentElem = $('<span class="sfc_op_content_'+oInputParam.code+'_outer '+(oInputParam.show_on_advanced ? 'hide_on_less' : 'hide_on_advanced')+'"><label class="sfc_op_content_'+oInputParam.code+'_label" for=""> '+Dict.S('UI:Search:Criteria:DateTime:'+oInputParam.code_uc_first)+'</label><input type="text" name="'+oInputParam.code+'" placeholder="'+Dict.S('UI:Search:Criteria:DateTime:Placeholder'+oInputParam.code_uc_first)+'"/></span>');
				var oInputElem = oOpContentElem
					.find('input')
					.uniqueId()
				;
				oOpContentElem
					.find('label')
					.attr('for', oInputElem.attr('id'))
				;

				if (oInputParam.value_index in aValues && typeof aValues[oInputParam.value_index].value != 'undefined')
				{
					oInputElem.val(aValues[oInputParam.value_index].value);
				}

				oContentElem = oContentElem.add(oOpContentElem);
			}


			// Events
			// - Focus input on click (radio, label, ...)
			oOpElem.on('click', function(oEvent){
				if ($(oEvent.target).is('input[type="text"], select')) {
					return;
				}
				oOpElem.find('input:visible:first').focus();
			});
			// - Apply on "enter" key hit
			//todo: this could be refactored
			oOpContentElem.on('keyup', function(oEvent){
				// Check operator's radio if not already (typically when focusing in input via "tab" key)
				if(oOpElem.find('.sfc_op_radio').prop('checked') === false)
				{
					oOpElem.find('.sfc_op_radio').prop('checked', true)
				}

				me._markAsDraft();

				// Apply if enter key
				if(oEvent.key === 'Enter')
				{
					me._apply();
				}
			});

			oOpElem
				.find('.sfc_op_name')
					.remove()
				.end()
				.find('.sfc_op_content')
					.append(oContentElem)
			;


			// once the inputs are appended into the DOM we can safely use jQuery UI
			var odatetimepickerOptionsDefault = {
				dateFormat: 'yy-mm-dd',
				timeFormat: 'HH:mm:ss',
				buttonImage: GetAbsoluteUrlAppRoot()+"/images/calendar.png",
				buttonImageOnly: true,
				buttonText: "",
				showOn:'both'
			};
			for (var i = 0; i < aInputsParamLength; i++) {
				var oInputParam = aInputsParam[i];

				var odatetimepickerOptions = $.extend({}, odatetimepickerOptionsDefault, {
					onClose: function(dateText, inst) {
						var selectElem 				= $(this);
						var sOnCLoseShow 			= selectElem.data('onclose_show');

						// if (typeof oInputParam.onclose_show != 'undefined')
						// {
						// 	oOpElem.find('input[name="'+oInputParam.onclose_show+'"]')
						// 		[oInputParam.x_picker]('show')
						// 	;
						// }

						// if (sOnCLoseShow != undefined && selectElem.is(':visible'))
						// {
						// 	var oOnCLoseShowInputElem = oOpElem.find('input[name="'+sOnCLoseShow+'"]');
						// 	oOnCLoseShowInputElem[oInputParam.x_picker]('show');
						// }

					},
					onSelect: function(sDateText, oXPicker) {
						var selectElem 				= $(this);
						var sSyncedWith 			= selectElem.data('synced_with');


						if (sSyncedWith != undefined)
						{
							var sCode 				= selectElem.data('code');
							var oInputParam 		= aInputsParam[oInputsParamIndexByCode[sCode]];
							var oSyncedInputParam 	= aInputsParam[oInputsParamIndexByCode[sSyncedWith]];
							var oSyncedInputElem 	= oOpElem.find('input[name="'+sSyncedWith+'"]');

							var dSyncedDate 		= selectElem[oInputParam.x_picker]('getDate');

							if (typeof oSyncedInputParam.default_time_add != 'undefined' && oSyncedInputParam.default_time_add)
							{
								dSyncedDate.setSeconds(dSyncedDate.getSeconds() + oSyncedInputParam.default_time_add);
							}
							oSyncedInputElem[oSyncedInputParam.x_picker]('setDate', dSyncedDate);
						}

						me._apply();
					}
				});

				var oInputElem = oOpElem.find('input[name="'+oInputParam.code+'"]');
				oInputElem.data('code', oInputParam.code);
				oInputElem.data('onclose_show', oInputParam.onclose_show);
				oInputElem[oInputParam.x_picker](odatetimepickerOptions);

				if (typeof aInputsParam[oInputsParamIndexByCode[oInputParam.synced_with]] != 'undefined')
				{
					var oSyncedInputParam = aInputsParam[oInputsParamIndexByCode[oInputParam.synced_with]];
					oInputElem.data('synced_with', oSyncedInputParam.code);
				}
			}
		},



		_getBetweenDaysOperatorValues: function(oOpElem)
		{
			var me = this;
			var aValues = [];
			var aInputsParam = me.options.aInputsParam;
			var aInputsParamLength = aInputsParam.length;
			for (var i = 0; i < aInputsParamLength; i++) {
				var oInputParam = aInputsParam[i];
				var oInputElem = oOpElem.find('input[name="'+oInputParam.code+'"]');
				if (oInputElem.is(':visible'))
				{
					if (typeof oInputParam.getter_code != 'undefined')
					{
						oInputElem = oOpElem.find('input[name="'+oInputParam.getter_code+'"]');
					}

					aValues[oInputParam.value_index] = {value: oInputElem.val(), label: oInputElem.val()};
				}
			}
			//
			// var sValueFrom  = oOpElem.find('.sfc_op_content input[name="from"]').val();
			// var sValueUntil = oOpElem.find('.sfc_op_content input[name="until"]').val();
			//
			// aValues.push({value: sValueFrom, label: sValueFrom});
			// aValues.push({value: sValueUntil, label: sValueUntil});


			return aValues;
		},

		_setBetweenDaysOperatorValues: function(oOpElem, aValues)
		{

			var me = this;
			var aInputsParam = me.options.aInputsParam;
			var aInputsParamLength = aInputsParam.length;
			for (var i = 0; i < aInputsParamLength; i++) {
				var oInputParam = aInputsParam[i];
				var oInputElem = oOpElem.find('input[name="'+oInputParam.code+'"]');
				// oInputElem.val(aValues[0].value);

				if (typeof aValues[oInputParam.value_index] != 'undefined' && typeof aValues[oInputParam.value_index].value != 'undefined')
				{
					oInputElem[oInputParam.x_picker]('setDate', aValues[oInputParam.value_index].value);
				}
			}
			// switch (aValues.length)
			// {
			// 	case 2:
			// 		oOpElem.find('.sfc_op_content input[name="until"]').val(aValues[0].value);
			// 		//NO BREAK!!!
			// 	case 1:
			// 		oOpElem.find('.sfc_op_content input[name="from"]').val(aValues[0].value);
			// 		break;
			// 	default:
			// 		return false;
			// }
			//
			// return true;
		},

		_resetBetweenDaysOperator: function(oOpElem)
		{
			this._resetOperator(oOpElem);
		},

		//------------------
		// Inherited methods
		//------------------


		// Prepare operator's DOM element
		// - Base preparation, always called
		_prepareOperator: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;
			if (typeof oOp.dropdown_group == 'undefined')
			{
				return this._super(oOpElem, sOpIdx, oOp);
			}

			this._super(oOpElem, sOpIdx, oOp);
			oOpElem.addClass('force_hide')

			//TODO: move this into the abstract widget

			// DOM element
			oDropdownElem = this.element.find('select.dropdown_group_'+oOp.dropdown_group);
			if (oDropdownElem.length == 0)
			{
				oDropdownElem = $('<select class="dropdown_group_'+oOp.dropdown_group+'" data-dropdown-group="'+oOp.dropdown_group+'"></select>');

				oDropdownElem.on('change', function(){
					// $option = $(this);
					me.element.find('.sfc_fg_operator_dropdown_group .sfc_op_radio').val(oDropdownElem.val());

					oOptionOp = oDropdownElem.data('oOp');
					oDropdownElem.attr('data-operator-code', oOptionOp.code);
				});


				// Create DOM element from template
				var oOpElemDropdown = $(this._getOperatorTemplate()).uniqueId();

				//todo : if this code is keeped, the radio mustr have an id and the label need to point to it
				oOpElemDropdown
					.addClass('sfc_fg_operator_dropdown_group')
					.attr('data-operator-code', 'dropdown_group')
					.find('.sfc_op_name')
						.append(oDropdownElem)
					.end()
					.find('.sfc_op_radio')
						.val(sOpIdx)
					.end()
					.on('click', function(oEvent){
						var bIsChecked = oOpElemDropdown.find('.sfc_op_radio').prop('checked');

						if(bIsChecked === false)
						{
							oOpElemDropdown.find('.sfc_op_radio').prop('checked', true);
							me._markAsDraft();
						}
						oOpElemDropdown.find('input[type="text"]:first').focus();
					})
					.appendTo(this.element.find('.sfc_fg_operators'))
				;

				this._prepareDefaultOperator(oOpElemDropdown, sOpIdx, oOp);
			}

			oDropdownElem
				.append('<option value="'+sOpIdx+'" >'+oOp.label+'</option>')
				.data('oOp', oOp)
			;
		},


	});
});
