//iTop Search form criteria enum
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_enum' the widget name
	$.widget( 'itop.search_form_criteria_enum', $.itop.search_form_criteria,
	{
		// default options
		options:
		{
			// Overload default operator
			'operator': 'IN',
			// Available operators
			'available_operators': {
				'IN': {
					'label': Dict.S('UI:Search:Criteria:Operator:Enum:In'),
					'code': 'in',
					'rank': 10,
				},
				'=': null,			// Remove this one from enum widget.
				'empty': null,		// Remove as it will be handle by the "null" value in the "IN" operator
				'not_empty': null,	// Remove as it will be handle by the "null" value in the "IN" operator
			},

			// Null value
			'null_value': {
				'code': 'null',
				'label': Dict.S('Enum:Undefined'),
			},

			// Autocomplete
			'autocomplete': {
				'xhr_throttle': 200,
				'min_autocomplete_chars': 2,
			},
		},

   
		// the constructor
		_create: function()
		{
			var me = this;

			// when the allowed valued indexes has  sequentially & numerical keys, they are passed has an array which break some further code, let's enforce them as an object.
			if (Array.isArray(this.options.field.allowed_values.values))
			{
				var arrayValues = this.options.field.allowed_values.values;
				this.options.field.allowed_values.values = {};
				for (var i = 0; i < arrayValues.length; i++) {
					this.options.field.allowed_values.values[i] = arrayValues[i];
				}
			}

			this._super();
			this.element.addClass('search_form_criteria_enum');
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria_enum');
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
		// - Bind external events
		_bindEvents: function()
		{
			var me = this;

			this._super();

			// Add selected data
			this.element.on('itop.search.criteria_enum.add_selected_values', function(oEvent, oData){
				return me._onAddSelectedValues(oData);
			});

			this.element.off('input.form_criteria_add_title_on_value_change, change.form_criteria_add_title_on_value_change, non_interactive_change.form_criteria_add_title_on_value_change');//the parent method bind for input changes events in order to add a title on the input, for the filter we do not want to do this, so let's remove all the listeners
		},

		// Events callbacks
		_onAddSelectedValues: function(oData)
		{
			this._addSelectedValues(oData);
			// Note: Selecting values elsewhere (eg. a popup) closes the criteria on the first click, so when the user goes back to the form, the criteria has the new values displayed but not selected. So as a workaround we apply and re-open the criteria.
			this._apply();
			this._open();
		},

		// DOM element helpers
		// - Prepare element DOM structure
		_prepareElement: function()
		{
			this._super();

			// Remove more/less buttons
			this.element.find('.sfc_fg_buttons .sfc_fg_more, .sfc_fg_buttons .sfc_fg_less').remove();
		},
		_prepareInOperator: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;

			// Hide radio & name for now, until there is more than one operator
			oOpElem.find('.sfc_op_radio, .sfc_op_name').hide();

			// DOM elements
			var sOpId = oOpElem.attr('id');
			var oOpContentElem = $('<div></div>')
				.addClass('sfc_opc_multichoices')
				.appendTo(oOpElem.find('.sfc_op_content'));

			// - Check / Uncheck all togglers
			var sTogglerId = 'toggle_' + sOpId;
			var oTogglerElem = $('<div></div>')
				.addClass('sfc_opc_mc_toggler')
				.append('<label for="' + sTogglerId + '"><input type="checkbox" id="' + sTogglerId + '" /><span data-label-regular="' + Dict.S('UI:Search:Value:Toggler:CheckAllNone') + '" data-label-filtered="' + Dict.S('UI:Search:Value:Toggler:CheckAllNoneFiltered') + '">' + Dict.S('UI:Search:Value:Toggler:CheckAllNone') + '</span></label>')
				.appendTo(oOpContentElem);

			// - Filter
			var sFilterId = 'filter_' + sOpId;
			var sFilterPlaceholder = (this._hasAutocompleteAllowedValues()) ? Dict.S('UI:Search:Value:Search:Placeholder') : Dict.S('UI:Search:Value:Filter:Placeholder');
			var oFilterElem = $('<div></div>')
				.addClass('sf_filter')
				.append('<span class="sff_input_wrapper"><input type="text" id="' + sFilterId + '" placeholder="' + sFilterPlaceholder + '" autocomplete="off" /><span class="sff_picto sff_reset fas fa-times"></span></span>')
				.appendTo(oOpContentElem);

			// - Values wrapper
			var oValuesWrapperElem = $('<div></div>')
				.addClass('sfc_opc_mc_items_wrapper')
				.appendTo(oOpContentElem);
			// - Allowed values
			var oAllowedValuesElem = $('<div></div>')
				.addClass('sfc_opc_mc_items')
				.appendTo(oValuesWrapperElem);
			// - Static values: Always there no matter the field constraints
			var oStaticListElem = $('<div></div>')
				.addClass('sfc_opc_mc_items_list')
				.addClass('sfc_opc_mc_items_static')
				.appendTo(oAllowedValuesElem);
			// - Dynamic values: Depends on the field constraints
			var oDynamicListElem = $('<div></div>')
				.addClass('sfc_opc_mc_items_list')
				.addClass('sfc_opc_mc_items_dynamic')
				.appendTo(oAllowedValuesElem);

			//   - Null value if allowed
			//   Note: null values is NOT put among the allowed values for two reasons:
			//     - It must be the first value of the list
			//     - It is not give by neither the autocomplete or the pre-filled values, so we would need to manually add it in both cases, all operations.
			if(this.options.field.is_null_allowed === true)
			{
				var sValCode = this.options.null_value.code;
				var sValLabel = this.options.null_value.label;
				var oValueElem = this._makeListItemElement(sValLabel, sValCode);
				oValueElem.appendTo(oStaticListElem);
			}

			// Events
			// - Filter
			oFilterElem.find('.sff_reset').on('click', function(){
				oFilterElem.find('input')
					.val('')
					// Focus the input for improved UX
					.trigger('focus')
					// Submit autocomplete with new value
					.trigger('itop.search.criteria_enum.autocomplete.submit');
			});



			if(this._hasAutocompleteAllowedValues())
			{
				this._prepareInOperatorWithAutocomplete(oOpElem, sOpIdx, oOp);
			}
			else
			{
				this._prepareInOperatorWithoutAutocomplete(oOpElem, sOpIdx, oOp);
			}
		},
		_prepareInOperatorWithoutAutocomplete: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;


			var oOpContentElem = oOpElem.find('.sfc_opc_multichoices');
			var oTogglerElem = oOpContentElem.find('.sfc_opc_mc_toggler');
			var oFilterElem = oOpContentElem.find('.sf_filter');
			var oAllowedValuesElem = oOpContentElem.find('.sfc_opc_mc_items');
			var oDynamicListElem = oOpContentElem.find('.sfc_opc_mc_items_dynamic');

			// - Check / Uncheck all toggler
			oTogglerElem.on('click', function(oEvent){
				// Check / uncheck all allowed values
				var bChecked = $(this).closest('.sfc_opc_mc_toggler').find('input:checkbox').prop('checked');
				oOpContentElem.find('.sfc_opc_mc_item:visible input:checkbox').prop('checked', bChecked);
			});


			// DOM elements
			// - Filter
			oFilterElem.find('.sff_input_wrapper')
				.append('<span class="sff_picto sff_filter fas fa-filter"></span>');

			// - Allowed values
			var aSortedValues = this._sortValuesByLabel(this._getPreloadedAllowedValues());
			for (var i in aSortedValues)
			{
				var sValCode = aSortedValues[i][0];
				var sValLabel = $('<div/>').html(aSortedValues[i][1]).text();
				//_makeListItemElement: function(sLabel, sValue, bInitChecked, bInitHidden,bObsolete, sAdditionalField)
				var oValueElem = this._makeListItemElement(sValLabel, sValCode, false, false, aSortedValues[i][2], aSortedValues[i][3]);
				oValueElem.appendTo(oDynamicListElem);

				if (this._isSelectedValues(sValCode))
				{
					oValueElem.find(':checkbox').prop('checked', true);
				}
			}

			// Events
			// - Filter
			// Note: "keyup" event is use instead of "keydown", otherwise, the input value would not be set yet.
			oFilterElem.find('input').on('keyup focus', function(oEvent){
				// TODO: Move on values with up and down arrow keys; select with space or enter.
				var sQuery = $(this).val();
				if(sQuery === '')
				{
					oOpContentElem.find('.sfc_opc_mc_item').show();
					oFilterElem.find('.sff_filter').show();
					oFilterElem.find('.sff_reset').hide();
					oFilterElem.find()
				}
				else
				{
					oOpContentElem.find('.sfc_opc_mc_item').each(function(){
						var oRegExp = new RegExp(sQuery.latinise(), 'ig');
						var sValue = $(this).find('input').val();
						var sLabel = $(this).text();

						if( (sValue.latinise().match(oRegExp) !== null) || (sLabel.latinise().match(oRegExp) !== null) )
						{
							$(this).show();
						}
						else
						{
							$(this).hide();
						}
					});
					oFilterElem.find('.sff_filter').hide();
					oFilterElem.find('.sff_reset').show();
				}

				me._updateTogglerLabel();
			});
			oFilterElem.find('.sff_filter').on('click', function(){
				oFilterElem.find('input').trigger('focus');
			});

			// - Apply on check
			oAllowedValuesElem.on('click', '.sfc_opc_mc_item input', function(oEvent){
				// Prevent propagation, otherwise there will be multiple "_apply()"
				oEvent.stopPropagation();

				// Uncheck toggler
				oTogglerElem.find('input:checkbox').prop('checked', false);

				// Apply criteria
				//me._apply();
			});
		},
		_prepareInOperatorWithAutocomplete: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;

			var oOpContentElem = oOpElem.find('.sfc_opc_multichoices');
			var oTogglerElem = oOpContentElem.find('.sfc_opc_mc_toggler');
			var oFilterElem = oOpContentElem.find('.sf_filter');
			var oValuesWrapperElem = oOpContentElem.find('.sfc_opc_mc_items_wrapper');
			var oAllowedValuesElem = oValuesWrapperElem.find('.sfc_opc_mc_items');

			// - Check / Uncheck all toggler
			oTogglerElem.on('click', function(oEvent){
				// Check / uncheck all allowed values
				var bChecked = $(this).closest('.sfc_opc_mc_toggler').find('input:checkbox').prop('checked');
				if(bChecked)
				{
					// - Apply on check
					oAllowedValuesElem.find('.sfc_opc_mc_item input').each(function(){
						if(!$(this).find("input[type=\"checkbox\"]").is(":checked"))
						{
							var oItemElem = $(this).closest('.sfc_opc_mc_item');

							// Hide item
							oItemElem.hide();

							// Copy item to selected items list
							var oValues = {};
							oValues[oItemElem.find('input:checkbox').val()] = oItemElem.text();
							me._addSelectedValues(oValues);
						}
					});
				}
				else
				{
					// - Apply on uncheck
					oAllowedValuesElem.find('.sfc_opc_mc_item').each(function(){
						if(!$(this).is(":visible"))
						{
							// Show item among allowed values (if still there, could have been removed by another search needle)
							var oAllowedValueElem = oAllowedValuesElem.find('.sfc_opc_mc_item[data-value-code="'+$(this).attr('data-value-code')+'"]');
							if (oAllowedValueElem.length > 0)
							{
								oAllowedValuesElem.find('.sfc_opc_mc_item[data-value-code="'+$(this).attr('data-value-code')+'"]')
									.show()
									.find('input:checkbox')
									.prop('checked', false);
							}

							// Remove item from selected values
							$(this).closest(".sfc_opc_mc_items_wrapper").find('.sfc_opc_mc_items_selected').find('.sfc_opc_mc_item[data-value-code="'+$(this).attr('data-value-code')+'"]').remove();
						}
					});
					oAllowedValuesElem.closest(".sfc_opc_mc_items_wrapper").find('.sfc_opc_mc_items_selected').find('.sfc_opc_mc_item').each(function(){
						//$(this).add(oAllowedValuesElem);
						$(this).remove();
					});

					me._refreshSelectedValues();
				}

			});
			// DOM
			// - Hide toggler for now
			oTogglerElem.hide();
			//but when there are values under the search, this checkbox must come back


			// - Set typing hint
			this._setACTypingHint();

			// - Add search dialog button
			oFilterElem
				.append('<button type="button" class="sff_search_dialog"><span class=" fas fa-search"></span></button>')
				.addClass('sf_with_buttons');

			// - Prepare "selected" values area
			var oSelectedValuesElem = $('<div></div>')
				.addClass('sfc_opc_mc_items')
				.append('<div class="sfc_opc_mc_items_list sfc_opc_mc_items_selected"></div>')
				.appendTo(oValuesWrapperElem);
			this._refreshSelectedValues();

			// - External classes
			var oFilterIconElem = oFilterElem.find('.sff_search_dialog').uniqueId();
			oFilterIconElem.attr('id', oFilterIconElem.attr('id').replace(/-/g, '_'));
			var oForeignKeysWidgetCurrent = new SearchFormForeignKeys(
				oFilterIconElem.attr('id'), 	// id
				me.options.field.target_class, 	// sTargetClass
				me.options.field.code,			// sAttCode
				me.element,						// oSearchWidgetElmt
				'',								// sFilter  //TODO
				me.options.field.label			// sTitle
			);
			window['oForeignKeysWidget'+oFilterIconElem.attr('id')] = oForeignKeysWidgetCurrent;
			oForeignKeysWidgetCurrent.Init();

			// Events
			// - Autocomplete
			var oACXHR = null;
			var oACTimeout = null;

            oFilterElem.find('input').on('keydown', function(event) {
                //this filter should not close the criteria when the enter button is triggered
            	event.stopPropagation();
                // return false;
			});

			oFilterElem.find('input').on('keyup itop.search.criteria_enum.autocomplete.submit', function(oEvent){
				// TODO: Move on values with up and down arrow keys; select with space or enter.
				if(me._isFilteredKey(oEvent.keyCode))
				{
					return false;
				}

				var sQuery = $(this).val();
				if( (sQuery === '') || (sQuery.length < me.options.autocomplete.min_autocomplete_chars) )
				{
					me._setACTypingHint();
					oFilterElem.find('.sff_reset').hide();
				}
				else
				{
					// Show loader
					me._setACWaitHint();

					clearTimeout(oACTimeout);
					oACTimeout = setTimeout(function(){

						if(oACXHR !== null)
						{
							oACXHR.abort();
						}
						oACXHR = $.post(
							AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'),
							{
								sTargetClass: me.options.field.target_class,
								sFilter: 'SELECT ' + me.options.field.target_class,
								q: sQuery,
								bSearchMode: 'true',
								sOutputFormat: 'json',
								operation: 'ac_extkey',
							}
							)
							.done(function(oResponse, sStatus, oXHR){
								me._onACSearchSuccess(oResponse);

								if (Object.keys(oResponse).length >= 150)
								{
                                    me._emptyACTempHint();

									return;
								}
							})
							.fail(function(oResponse, sStatus, oXHR){  me._onACSearchFail(oResponse, sStatus); })
							.always(function(oResponse, sStatus, oXHR){
								me._onACSearchAlways();
								if (me.element.find('.sfc_opc_mc_item').length>0)
								{
									oTogglerElem.show();
								}
								else
								{
									oTogglerElem.hide();
								}
							});

						oFilterElem.find('.sff_reset').show();
					}, me.options.autocomplete.xhr_throttle);
				}
			});

			// - Apply on check
			oAllowedValuesElem.on('click', '.sfc_opc_mc_item input', function(oEvent){
				// Prevent propagation, otherwise there will be multiple "_apply()"
				oEvent.stopPropagation();

				var oItemElem = $(this).closest('.sfc_opc_mc_item');

				// Hide item
				oItemElem.hide();

				// Copy item to selected items list
				var oValues = {};
				oValues[oItemElem.find('input:checkbox').val()] = oItemElem.text();
				me._addSelectedValues(oValues);

				// Apply criteria
				//me._apply();
			});

			// - Apply on uncheck
			oSelectedValuesElem.on('click', '.sfc_opc_mc_item', function(oEvent){
				// Prevent propagation, otherwise there will be multiple "_apply()"
				oEvent.stopPropagation();

				// Show item among allowed values (if still there, could have been removed bu another search needle)
				var oAllowedValueElem = oAllowedValuesElem.find('.sfc_opc_mc_item[data-value-code="' + $(this).attr('data-value-code') + '"]');
				if(oAllowedValueElem.length > 0)
				{
					oAllowedValuesElem.find('.sfc_opc_mc_item[data-value-code="' + $(this).attr('data-value-code') + '"]')
						.show()
						.find('input:checkbox')
						.prop('checked', false);
				}

				// Remove item from selected values
				$(this).remove();
				me._refreshSelectedValues();

				// Apply criteria
				//me._apply();
			});

			// - Open search dialog
			oFilterElem.find('.sff_search_dialog').on('click', function(){
				oForeignKeysWidgetCurrent.ShowModalSearchForeignKeys();
			});

			var oTogglerTextElem = this.element.find('.sfc_opc_mc_toggler label span');
			var sFilterVal = this.element.find('.sf_filter input[type="text"]').val();

			oTogglerTextElem.text( oTogglerTextElem.attr('data-label-filtered'));
		},
		_computeTitle: function(sTitle)
		{
			var iValLimit = 3;
			var iValCount = Object.keys(this.options.values).length;
			var iAllowedValuesCount = Object.keys(this._getPreloadedAllowedValues()).length;

			// Manually increase allowed values count if null is allowed
			if( (this.options.field.is_null_allowed === true) && (this._hasAutocompleteAllowedValues() === false) )
			{
				iAllowedValuesCount++;
			}

			// Making right title regarding the number of selected values
			if( (iValCount === 0) || (iValCount === iAllowedValuesCount) )
			{
				sTitle = Dict.Format('UI:Search:Criteria:Title:Enum:In:All', this.options.field.label);
			}
			else if(iValCount > iValLimit)
			{
				var aFirstValues = [];
				for(var i=0; i<iValLimit-1; i++)
				{
					aFirstValues.push(this.options.values[i].label);
				}

				sTitle = Dict.Format(
					'UI:Search:Criteria:Title:Enum:In:Many',
					this.options.field.label, '<span class="sfc_values">'+aFirstValues.join(', ')+'</span>',
					'<span class="sfc_values">'+(iValCount - iValLimit+1)+'</span>'
				);
			}

			return this._super(sTitle);
		},

		// Operators helpers
		// Reset operator's state
		_resetInOperator: function(oOpElem)
		{
			// Uncheck toggler
			oOpElem.find('sfc_opc_mc_toggler input').prop('checked', false);

			// Clear filter
			oOpElem.find('sfc_opc_mc_filter input').val('');
		},
		// Get operator's values
		_getInOperatorValues: function(oOpElem)
		{
			var aValues = [];

			var sValuesSelector = this._getSelectedValuesWrapperSelector();
			oOpElem.find(sValuesSelector).find('.sfc_opc_mc_item input:checked').each(function(iIdx, oElem){
				var sValue = $(oElem).val();
				var sLabel = $(oElem).parent().text();
				aValues.push({value: sValue, label: sLabel});
			});

			return aValues;
		},
		// Set operator's values
		_setInOperatorValues: function(oOpElem, aValues)
		{
			if(aValues.length === 0)
			{
				return false;
			}

			// Uncheck all allowed values
			oOpElem.find('.sfc_opc_mc_item').show();
            oOpElem.find('.sfc_opc_mc_item input').prop('checked', false);

			// Re-check allowed values from param
			for(var iIdx in aValues)
			{
			    var oItemElem = oOpElem.find(this._getAllowedValuesWrapperSelector()).find('.sfc_opc_mc_item[data-value-code="' + aValues[iIdx].value + '"]');

			    // Add value to dynamic items if not existing (undefined should have already been put in static items)
                if(oItemElem.length == 0)
                {
                    oItemElem = this._makeListItemElement(aValues[iIdx].label, aValues[iIdx].value, true);
                    oItemElem.appendTo(oOpElem.find(this._getDynamicValuesWrapperSelector()));
                }

                // Check item
				oItemElem.find('input')
						.prop('checked', true);

                // In case of autocomplete, clone item into selected items
				if(this._hasAutocompleteAllowedValues() === true)
				{
                    var oSelectedItemElem = oOpElem.find(this._getSelectedValuesWrapperSelector()).find('.sfc_opc_mc_item[data-value-code="' + aValues[iIdx].value + '"]');
				    if(oSelectedItemElem.length == 0)
                    {
                        oSelectedItemElem = oItemElem.clone();
                        oSelectedItemElem.appendTo(oOpElem.find(this._getSelectedValuesWrapperSelector()));
                    }

                    oSelectedItemElem.show()
                        .find('input')
                        .prop('checked', true);
                    oItemElem.hide();
				}
			}

			this._refreshSelectedValues();

			return true;
		},
		_updateTogglerLabel: function()
		{
			var oTogglerTextElem = this.element.find('.sfc_opc_mc_toggler label span');
			var sFilterVal = this.element.find('.sf_filter input[type="text"]').val();

			oTogglerTextElem.text( oTogglerTextElem.attr('data-label-' + (sFilterVal === '' ? 'regular' : 'filtered')) );
		},


		// Autocomplete helpers
		_setACHint: function(sHint)
		{;
			this.element.find('.sfc_opc_mc_items_dynamic').html('<div class="sfc_opc_mc_placeholder">' + sHint + '</div>');
		},
		_setACTypingHint: function()
		{
			this._setACHint(Dict.S('UI:Search:Value:Autocomplete:StartTyping'));
		},
		_setACWaitHint: function()
		{
			this._setACHint(Dict.S('UI:Search:Value:Autocomplete:Wait'));
		},
		_setACNoResultHint: function()
		{
			this._setACHint(Dict.S('UI:Search:Value:Autocomplete:NoResult'));
		},
        _setACWaitTempHint: function() {
            this.element.find('.sfc_opc_mc_items_dynamic').append('<div class="sfc_opc_mc_placeholder">'+Dict.S('UI:Search:Value:Autocomplete:Wait')+'</div>');
        },
        _emptyACTempHint: function() {
            this.element.find('.sfc_opc_mc_items_dynamic .sfc_opc_mc_placeholder').remove();
        },
		// Autocomplete callbacks
		_onACSearchSuccess: function(oResponse)
		{
			if(typeof oResponse !== 'object')
			{
				this._onACSearchFail(oResponse, 'unexcepted');
				return false;
			}

			var oDynamicListElem = this.element.find('.sfc_opc_mc_items_dynamic')
				.html('');
			if(Object.keys(oResponse).length > 0)
			{
				// Note: Response is indexed by labels from server so the JSON is always ordered on decoding.
				for(var skey in oResponse)
				{
					var sValue = oResponse[skey].value;
				    var sLabel =  $('<div/>').html(oResponse[skey].label).text();
					// Note: We don't use the _isSelectedValue() method here as it only returns "applied" values; at this moment will could have a checked value that is not among selected (me.options.values) yet. The result would be an hidden item from the AC results.
					var bSelected = (this.element.find(this._getSelectedValuesWrapperSelector() + ' .sfc_opc_mc_item[data-value-code="' + sValue + '"]').length > 0);
					var bInitChecked = bSelected;
					var bInitHidden = bSelected;
					var oValueElem = this._makeListItemElement(sLabel, sValue, bInitChecked, bInitHidden,oResponse[skey].obsolescence_flag,oResponse[skey].additional_field);
					oValueElem.appendTo(oDynamicListElem);
				}
			}
            this._setACWaitTempHint();

			if (oDynamicListElem.find('.sfc_opc_mc_item').length == 0)
			{
				this._setACNoResultHint();
			}
			else
			{
				this._emptyACTempHint();
			}
		},
		_onACSearchFail: function(oResponse, sStatus)
		{
			if(sStatus !== 'abort')
			{
				var sErrorMessage = Dict.Format('Error:XHR:Fail', '');

				this._setACHint(sErrorMessage);
				this.handler.triggerHandler('itop.search.criteria.error_occured', sErrorMessage);
			}
		},
		_onACSearchAlways: function()
		{
		},


		// Value helpers
        // - Return the selector for the element containing the static and dynamic values
        _getAllowedValuesWrapperSelector: function()
        {
            return '.sfc_opc_mc_items_static, .sfc_opc_mc_items_dynamic';
        },
        // - Return the selector for the element containing the dynamic values
        _getDynamicValuesWrapperSelector: function()
        {
            return '.sfc_opc_mc_items_dynamic';
        },
        // - Return the selector for the element containing the selected values
        _getSelectedValuesWrapperSelector: function()
        {
            return (this._hasAutocompleteAllowedValues()) ? '.sfc_opc_mc_items_selected' : '.sfc_opc_mc_items_static, .sfc_opc_mc_items_dynamic';
        },
		// - Return true if sValue is among the selected values "codes" (this.options.values). Note that those values are updated only on _apply(), not on checkbox change.
		_isSelectedValues: function(sValue)
		{
			var bFound = false;

			for(var iValIdx in this.options.values)
			{
				// Note: Mind the double "=" instead of triple as the .value can be either string or integer whereas the sValue is always string
				if(this.options.values[iValIdx].value == sValue)
				{
					bFound = true;
					break;
				}
			}

			return bFound;
		},
		// - Return true if sLabel is among the selected values "labels" (this.options.values). Note that those values are updated only on _apply(), not on checkbox change.
		_isSelectedLabels: function(sLabel)
		{
			var bFound = false;

			for(var iValIdx in this.options.values)
			{
				// Note: Mind the double "=" instead of triple as the .label can be either string or integer whereas the sLabel is always string
				if(this.options.values[iValIdx].label == sLabel)
				{
					bFound = true;
					break;
				}
			}

			return bFound;
		},
		// - Add oValues to the list of selected values
		_addSelectedValues: function(oValues)
		{
			var oSelectedValuesElem = this.element.find(this._getSelectedValuesWrapperSelector());
			for(var sValue in oValues)
			{
				// // Add only if not already selected
				// if(this._isSelectedValues(sValue))
				// {
				// 	// continue;
				// }

				var oItemElem = oSelectedValuesElem.find('.sfc_opc_mc_item[data-value-code="' + sValue + '"]');

				if(oItemElem.length == 0)
				{
                    oItemElem = this._makeListItemElement(oValues[sValue], sValue, true);
                    oItemElem
                        .appendTo(oSelectedValuesElem)
						.show()
                        .effect('highlight', {}, 500);
				}

				oItemElem.find('input')
                         .prop('checked', true);

				// if(oSelectedValuesElem.find('.sfc_opc_mc_item[data-value-code="' + sValue + '"]').length > 0)
				// {
				// 	oSelectedValuesElem.find('.sfc_opc_mc_item[data-value-code="' + sValue + '"] input')
				// 		.prop('checked', true);
				// }
				// else
				// {
				// 	var oItemElem = this._makeListItemElement(oValues[sValue], sValue, true);
				// 	oItemElem
				// 		.appendTo(oSelectedValuesElem)
				// 		.effect('highlight', {}, 500);
				// }
			}
			this._refreshSelectedValues();
		},
		_refreshSelectedValues: function()
		{
			// No sense to make this in preloaded mode
			if(this._hasAutocompleteAllowedValues() === false)
			{
				return false;
			}

			// Show / hide
			var oSelectedValuesElem = this.element.find(this._getSelectedValuesWrapperSelector());
			if(oSelectedValuesElem.find('.sfc_opc_mc_item').length > 0)
			{
				oSelectedValuesElem.show();
			}
			else
			{
				oSelectedValuesElem.hide();
			}

			var oTogglerElem= this.element.find('.sfc_opc_multichoices').find('.sfc_opc_mc_toggler');
			if (this.element.find('.sfc_opc_mc_item').length>0)
			{
				oTogglerElem.show();
			}
			else
			{
				oTogglerElem.hide();
			}
			// TODO: Reorder
			// oSelectedValuesElem.html('');
			// var aSortedValues = this._sortValuesByLabel(this.options.values);
			// for(var iIdx in aSortedValues)
			// {
			// 	var oItemElem = this._makeListItemElement(aSortedValues[iIdx][1], aSortedValues[iIdx][0], true);
			// 	oItemElem.appendTo(oSelectedValuesElem);
			// }
		},
		// - Return an array of allowed values sorted by labels
		_sortValuesByLabel: function(oSource)
		{
			var aSortable = [];
			var bSourceAsObject = $.isPlainObject(oSource);
			for (var sKey in oSource) {
				// eg. [{value: "3", label: "Demo"}, {value: "2", label: "IT Department"}] in autocomplete mode
				if(bSourceAsObject === false)
				{
					aSortable.push([oSource[sKey].value, oSource[sKey].label]);
				}
				// eg. {2: "IT Department", 3: "Demo"} in regular mode
				else
				{
					if(oSource[sKey]["label"]) {
						aSortable.push([sKey, oSource[sKey]["label"], oSource[sKey]["obsolescence_flag"], oSource[sKey]["additional_field"]]);
					} else {
						aSortable.push([sKey, oSource[sKey]]);
					}
				}
			}

			aSortable.sort(function(a, b) {
				if(a[1].toLowerCase() < b[1].toLowerCase())
				{
					return -1;
				}
				else if(a[1].toLowerCase() > b[1].toLowerCase())
				{
					return 1;
				}

				return 0;
			});

			return aSortable;
		},
		// - Make a jQuery element for a list item
		_makeListItemElement: function(sLabel, sValue, bInitChecked, bInitHidden,bObsolete, sAdditionalField)
		{
			var sEscapedLabel = CombodoSanitizer.EscapeHtml(sLabel, false);
			if (bObsolete == 1)	{
				sEscapedLabel = '<span class="object-ref-icon text_decoration"><span class="fas fa-eye-slash object-obsolete fa-1x fa-fw"></span></span>'+sEscapedLabel;
			}

			if (sAdditionalField != undefined )	{
				sEscapedLabel = sEscapedLabel+'<br><i>'+CombodoSanitizer.EscapeHtml(sAdditionalField, false)+'</i>';
			}

			var oItemElem = $('<div></div>')
				.addClass('sfc_opc_mc_item')
				.attr('data-value-code', sValue)
				.append('<label><input type="checkbox" value="'+sValue+'"/>'+sEscapedLabel+'</label>');

			if(bInitChecked === true)
			{
				oItemElem.find('input:checkbox').prop('checked', true);
			}
			if(bInitHidden === true)
			{
				oItemElem.hide();
			}

			return oItemElem;
		},
	});
});
