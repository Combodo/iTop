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
					'label': Dict.S('UI:Search:Criteria:Operator:Default:Between'),
					'code': 'between_days',
					'rank': 1,
				},
				'empty': {
					'rank': 700,//pre-existing, reordered
				},
				'not_empty': {
					'rank': 800,//pre-existing, reordered
				},
				'=': null
			},
			aInputsParam: [
				{
					// Common settings :
					//  "code": "from",					=> the code used in the HTML
					//  "code_uc_first":"From",			=> the code used in the translations
					//  "onclose_show" : "until",		=> on x_picker close, should we open another one (on "from" close shall we show "until")
					//  "value_index": 0,				=> the widget communicate with an array of values, the index 0 is "from" the index 1 is "until"
                    //  "has_time": true,				=> wether this field need the time (ie: in its formatting)

					// Date_time widget specific settings :
					// > Beware : a lot of those parameters are unused (the intial dev. tested several different UI before the final choice)
					//  "x_picker" : 'datetimepicker',	=> the plugin used either datepicker or datetimepicker
					//  "picker_extra_params": {			=> extram init params for the datepicker, use by the datetime to force the time of the "until" at 23:59:59
					//	  "hour": 23,
					//	  "minute":59,
					//	  "second":59
					//  },
					//  "default_time_add": false,		=> either false to disable it or number of second to add (used by the datetimepicker to choose the right time on synched datepicker change, its value change from 0 for "from" to +1d-1s for "until"
					//  "show_on_advanced": true,		=> is the input displaye on "more" or "less" mode advanced is an lais for "more" in the css
					//  "synced_with": "from_time",		=> from and until has both two input (datepicker and datetimepicker). each time one input change, the other one has to change
					//  "getter_code":"from_time"		=> iTop expect datetime to always provide time, so we must always use the datetimepicker in order to compute the value to be sent to iTop event if whe are in the display mode "less" with datepicker visible
					//  "getter_suffix": " 00:00:00",	=> iTop expect datetime to always provide time, so either we use the "getter_code" or we add a suffix to force the time
					//  "title_getter_code":"from",		=> if present, the title will be computed base on the given input code. Because the datetime widget title area is not large enought, so we remove the time info, in order to do so we use this.

				}
			]
		},



        _create: function() {
            var me = this;

            //let's hack the <= operator in order to fall back on the only one that matter here : `between_dates`
            if ('<=' == me.options.operator && me.options.values.constructor === Array)
            {
                me.options.operator = 'between_dates';
                me.options.values.unshift({"label":'', "value":''});
            }
            else if ('>=' == me.options.operator)
			{
                me.options.operator = 'between_dates';
			}

            me._superApply(arguments);
        },




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


				if (typeof oInputParam.show_on_advanced == 'undefined')
				{
					var customCssClass = '';
				}
				else
				{
					var customCssClass = oInputParam.show_on_advanced ? 'hide_on_less' : 'hide_on_advanced';
				}

				var oOpContentElem = $('<span class="sfc_op_content_'+oInputParam.code+'_outer '+customCssClass+'"><label class="sfc_op_content_'+oInputParam.code+'_label" for=""> '+Dict.S('UI:Search:Criteria:DateTime:'+oInputParam.code_uc_first)+' </label><input type="text" name="'+oInputParam.code+'" placeholder="'+Dict.S('UI:Search:Criteria:DateTime:Placeholder'+oInputParam.code_uc_first)+'"/></span>');
				var oInputElem = oOpContentElem
					.find('input')
					.uniqueId()
					//.attr('data-no-auto-focus', true)
				;
				oOpContentElem
					.find('label')
					.attr('for', oInputElem.attr('id'))
				;

				//on arrival, the date is always formated yyy-mm-dd, we need to apply the user's formating and to write it into both the dom and the values array.
				if (oInputParam.value_index in aValues && typeof aValues[oInputParam.value_index].value != 'undefined' && aValues[oInputParam.value_index].value != '')
				{
                    //ie9 do not hadle the timezone like the other browsers, so wee need to make extra computation in order to be sure to obtain consistency accross browsers : we declare the date without the UTC offset (the suffix "Z", then wee add the timezone offset
					var oDate = new Date(aValues[oInputParam.value_index].value.replace(' ', 'T')+'Z');
                    oDate.setTime(oDate.getTime() + oDate.getTimezoneOffset()*60000);//since the date was without the utc offset, we add it now
                    var sDate = me._formatDate(oDate);

					oInputElem.val(sDate);
                    this.options.values[oInputParam.value_index].value = sDate;
                    aValues = me._getValues();
				}

				oContentElem = oContentElem.add(oOpContentElem);

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
						var inputElem = $(this).find('input[type="text"]');
						inputElem.val(inputElem.val().trim());
						inputElem[oInputParam.x_picker]('hide');
						//Closing the criterion because the datetime picker widget catches the keydown event
						me._close();
						me._apply();
					}
				});
			}
			oOpElem
				.find('.sfc_op_name')
					.remove()
				.end()
				.find('.sfc_op_content')
					.append(oContentElem)
			;


			// once the inputs are appended into the DOM we can safely use jQuery UI
			var fHandleSynchCallback = function(select, bSetDate) {
				var selectElem 				= $(select);
				var sSyncedWith 			= selectElem.data('synced_with');
				var oInputParam 			= selectElem.data('oInputParam');

				if (bSetDate)
				{
					var sDate = selectElem.val().trim();
					if ('' == sDate)
					{
						selectElem[oInputParam.x_picker]('setDate', null);
					} else
					{
						selectElem[oInputParam.x_picker]('setDate', sDate);
					}
				}

				if (sSyncedWith != undefined)
				{
					var sCode 				= selectElem.data('code');
					var oInputParam 		= aInputsParam[oInputsParamIndexByCode[sCode]];
					var oSyncedInputParam 	= aInputsParam[oInputsParamIndexByCode[sSyncedWith]];
					var oSyncedInputElem 	= oOpElem.find('input[name="'+sSyncedWith+'"]');

					var dSyncedDate 		= selectElem[oInputParam.x_picker]('getDate');

					if (null == dSyncedDate)
					{
						// oSyncedInputElem.val('');
						oSyncedInputElem[oSyncedInputParam.x_picker]('setDate', null);
					}
					else
					{
						if (typeof oSyncedInputParam.default_time_add != 'undefined' && oSyncedInputParam.default_time_add)
						{
							dSyncedDate.setSeconds(dSyncedDate.getSeconds() + oSyncedInputParam.default_time_add);
						}
						oSyncedInputElem[oSyncedInputParam.x_picker]('setDate', dSyncedDate);
					}

				}

			};

			var odatetimepickerOptionsDefault = {
				// dateFormat: 'yy-mm-dd',
				// timeFormat: 'HH:mm:ss',
				showSecond: true,
				buttonImage: GetAbsoluteUrlAppRoot()+"/images/calendar.png",
				// buttonImageOnly: true,
				buttonText: "",
				showOn:'button',
				changeMonth:true,
				changeYear:true,
                beforeShow: function() {
                    setTimeout(function(){
                        $('.ui-datepicker').css('z-index', 99999999999999);//this is a hack that let us do not force the datepicker z-index globally but only here. This is needed when we are in presence of a dialog because the dialog has a greater z-index than the datepicker
                    }, 0);
                }
			};
			for (var i = 0; i < aInputsParamLength; i++) {
				var oInputParam = aInputsParam[i];



				var odatetimepickerOptions = $.extend({}, oInputParam.picker_extra_params, odatetimepickerOptionsDefault, me.options.datepicker, {
					onSelect: function() {
						fHandleSynchCallback(this, false);
						$(this).focus();
					}
				});



				var oInputElem = oOpElem.find('input[name="'+oInputParam.code+'"]');
				oInputElem.data('code', oInputParam.code);
				oInputElem.data('onclose_show', oInputParam.onclose_show);
				oInputElem.data('oInputParam', oInputParam);
				oInputElem.on('keydown', function(oEvent) {
					// Synch if "enter" key
					if(oEvent.key === 'Enter')
					{
						fHandleSynchCallback(this, true);
					}
				});
				oInputElem[oInputParam.x_picker](odatetimepickerOptions);

				if (typeof aInputsParam[oInputsParamIndexByCode[oInputParam.synced_with]] != 'undefined')
				{
					var oSyncedInputParam = aInputsParam[oInputsParamIndexByCode[oInputParam.synced_with]];
					oInputElem.data('synced_with', oSyncedInputParam.code);
				}
			}
		},


		_formatDate: function(oDate, bWithTime)
		{
            var me = this;

			var sLabel = $.datepicker.formatDate(me.options.datepicker.dateFormat , oDate);
            if (bWithTime === true || bWithTime === undefined)
            {
                sLabel = sLabel + ' ' + $.datepicker.formatTime(me.options.datepicker.timeFormat , {
                    hour: oDate.getHours(),
                    minute: oDate.getMinutes(),
                    second: oDate.getSeconds()
                });
            }
            return sLabel;
		},


		_getBetweenDaysOperatorValues: function(oOpElem)
		{
			var me = this;
			var aValues = [];
			var aInputsParam = me.options.aInputsParam;
			var aInputsParamLength = aInputsParam.length;
			var bAdvancedMode = (me.element.find('.sfc_form_group.advanced').length > 0);

			var sValue = '';
			var sLabel = '';

			for (var i = 0; i < aInputsParamLength; i++) {
				var oInputParam = aInputsParam[i];
                var oDate = oOpElem.find('input[name="'+oInputParam.code+'"]').datepicker( "getDate" ); //.val();

                var sLabel = '';

                if (oDate != null)
				{
                    sLabel = me._formatDate(oDate, oInputParam.has_time);
                    // sLabel = $.datepicker.formatDate(me.options.datepicker.dateFormat , oDate);
                    // if (oInputParam.has_time)
                    // {
                    //     sLabel = sLabel + ' ' + $.datepicker.formatTime(me.options.datepicker.timeFormat , {
                    //     	hour: oDate.getHours(),
                    //         minute: oDate.getMinutes(),
                    //         second: oDate.getSeconds()
						// });
                    // }
				}



				if (typeof oInputParam.show_on_advanced == 'undefined' || bAdvancedMode == oInputParam.show_on_advanced)
				{
					if (typeof oInputParam.getter_code != 'undefined')
					{
						sValue = sLabel; //oOpElem.find('input[name="'+oInputParam.getter_code+'"]').datepicker( "getDate" ); //.val();
                        //sValue = (sValue == null) ? '' : $.datepicker.formatDate(me.options.datepicker.timeFormat ,sValue);
					}
					else
					{
						sValue = sLabel;
					}

					aValues[oInputParam.value_index] = {value: sValue, label: sLabel};
				}
			}

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
					var sDate = aValues[oInputParam.value_index].value;
					if (sDate.trim() != '')
					{
                        try
						{
                            if (oInputParam.has_time)
                            {
                                var iSpacePos = sDate.indexOf(' ');
                                if (iSpacePos)
								{
                                    var sParsableDate =  sDate.substring(0, iSpacePos);
                                    var sParsableTime =  sDate.substring(iSpacePos + 1);
								}
                                else
                                	{
                                    var sParsableDate =  sDate
                                    var sParsableTime =  ''
								}


                            	var oDate = $.datepicker.parseDate( me.options.datepicker.dateFormat, sParsableDate );

                                if ('' != sParsableTime)
								{
                                    var oTime = $.datepicker.parseTime( me.options.datepicker.timeFormat, sParsableTime );
                                    oDate.setHours(oTime.hour);
                                    oDate.setMinutes(oTime.minute);
                                    oDate.setSeconds(oTime.second);
								}
                            }
                            else
                            {
                                var oDate = $.datepicker.parseDate( me.options.datepicker.dateFormat, sDate );
                            }

                            oInputElem[oInputParam.x_picker]('setDate', oDate);
						}
						catch (e) {
							//in theorie, this should not happen, but hey, it do not harm!
                            var oDate = new Date(); //sDate);
                            oInputElem[oInputParam.x_picker]('setDate', oDate);
                        }

					}
					else
					{
						oInputElem[oInputParam.x_picker]('setDate', sDate);
					}
				}
				// Note: In that particular case we don't use the ".ibo-is-hidden" CSS class because the widget will internally use the jQuery .show() to display itself which will not override correctly the .ibo-is-hidden rules.
				$('#ui-datepicker-div').hide();
			}
		},

		_resetBetweenDaysOperator: function(oOpElem)
		{
			this._resetOperator(oOpElem);
		},

		//------------------
		// Inherited methods
		//------------------
		_computeBetweenDaysOperatorTitle: function(sTitle)
		{
			var me = this;
			if (sTitle === undefined)
			{
				var aValues = me._getValues();
				// switch (true)
				// {
				// 	case (typeof aValues[0] == 'undefined' && typeof aValues[1] == 'undefined'):
				// 	case (typeof aValues[0].label == 'undefined' && typeof aValues[1].label == 'undefined'):
				// 	case (aValues[0].label.trim() == '' && aValues[1].label.trim() == ''):
				// 		var sDictEntrySuffix = ':All';
				// 		break;
				// 	case (typeof aValues[0] == 'undefined' ):
				// 	case (typeof aValues[0].label == 'undefined' ):
				// 	case (aValues[0].label.trim() == '' ):
				// 		var sDictEntrySuffix = ':Until';
				// 		break;
				// 	case (typeof aValues[1] == 'undefined'):
				// 	case (typeof aValues[1].label == 'undefined' ):
				// 	case (aValues[1].label.trim() == ''):
				// 		var sDictEntrySuffix = ':From';
				// 		break;
				// 	default:
				// 		var sDictEntrySuffix = '';
				// 		break;
				// }
				switch (true)
				{
					case (typeof aValues[0] == 'undefined' && typeof aValues[1] == 'undefined'):
						var sDictEntrySuffix = ':All';
						break;
					case (typeof aValues[0] == 'undefined' ):
						var sDictEntrySuffix = ':Until';
						break;
					case (typeof aValues[1] == 'undefined'):
						var sDictEntrySuffix = ':From';
						break;
					case (typeof aValues[0].label == 'undefined' && typeof aValues[1].label == 'undefined'):
						var sDictEntrySuffix = ':All';
						break;
					case (typeof aValues[0].label == 'undefined' ):
						var sDictEntrySuffix = ':Until';
						break;
					case (typeof aValues[1].label == 'undefined' ):
						var sDictEntrySuffix = ':From';
						break;
					case ((typeof aValues[0].label == 'string' && aValues[0].label.trim() == '') && (typeof aValues[1].label == 'string' && aValues[1].label.trim() == '')):
						var sDictEntrySuffix = ':All';
						break;
					case (typeof aValues[0].label == 'string' && aValues[0].label.trim() == ''):
						var sDictEntrySuffix = ':Until';
						break;
					case (typeof aValues[1].label == 'string' && aValues[1].label.trim() == ''):
						var sDictEntrySuffix = ':From';
						break;
					default:
						var sDictEntrySuffix = '';
						break;
				}

				var sDictEntry = 'UI:Search:Criteria:Title:' + this._toCamelCase(this.options.field.widget) + ':' + this._toCamelCase(me.options.operator) + sDictEntrySuffix ;
				// Fallback to default widget dict entry if none exists for the current widget
				if(Dict.S(sDictEntry) === sDictEntry)
				{
					sDictEntry = 'UI:Search:Criteria:Title:Default:' + this._toCamelCase(me.options.operator) + sDictEntrySuffix;
				}

				sTitle = Dict.Format(sDictEntry, this.options.field.label, '<span class="sfc_values">'+this._getValuesAsText()+'</span>');
				return sTitle;

			}

			return undefined;
		},


		// - Convert values to a standard string
		_getValuesAsText: function(aRawValues)
		{
			var me = this;

			var keepTime = true;

			if (aRawValues == undefined)
			{
				aRawValues = me._getValues();
			}
			if (me.options.operator == 'between_dates')
			{
				aRawValues = aRawValues.slice();//clone

				if (typeof aRawValues[1] == 'undefined' || typeof aRawValues[1].label == 'undefined' || aRawValues[1].label == '')
				{
					aRawValues.splice(1, 1);
				}
				else
				{
					if (
						null == aRawValues[1].label.match('23:59:59')
						||
						(typeof aRawValues[0] != 'undefined' && typeof aRawValues[0].label != 'undefined' && aRawValues[0].label != '' && null == aRawValues[0].label.match('00:00:00'))
					)
					{
						keepTime = false;
					}
					else {
						aRawValues[1].label = aRawValues[1].label.replace('23:59:59', '');
					}
				}
				if (typeof aRawValues[0] == 'undefined' || typeof aRawValues[0].label == 'undefined' || aRawValues[0].label == '')
				{
					aRawValues.splice(0, 1);
				}
				else
				{
					if (keepTime)
					{
						aRawValues[0].label = aRawValues[0].label.replace('00:00:00', '');
					}
				}
			}
			return me._super(aRawValues);
		},




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
