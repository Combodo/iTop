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
				'code': null,
				'label': Dict.S('Enum:Undefined'),
			},

			// Autocomplete
			'autocomplete': {
				'xhr_throttle': 200,
			},
		},

   
		// the constructor
		_create: function()
		{
			var me = this;

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

		// DOM element helpers
		// - Prepare element DOM structure
		_prepareElement: function()
		{
			this._super();

			// Remove buttons
			this.element.find('.sfc_fg_buttons').remove();
		},
		_prepareInOperator: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;

			// Hide radio & name for now, until there is more than one operator
			oOpElem.find('.sfc_op_radio, .sfc_op_name').hide();

			// DOM elements
			var sOpId = oOpElem.attr('id');
			var oOpContentElem = $('<div></div>')
				.addClass('sfc_opc_multichoices');

			// - Check / Uncheck all togglers
			var sTogglerId = 'toggle_' + sOpId;
			var oTogglersElem = $('<div></div>')
				.addClass('sfc_opc_mc_toggler')
				.append('<label for="' + sTogglerId + '"><input type="checkbox" id="' + sTogglerId + '" />' + Dict.S('UI:Search:Value:Toggler:CheckAllNone') + '</label>')
				.appendTo(oOpContentElem);

			// - Filter
			var sFilterId = 'filter_' + sOpId;
			var sFilterPlaceholder = (this._hasAutocompleteAllowedValues()) ? Dict.S('UI:Search:Value:Search:Placeholder') : Dict.S('UI:Search:Value:Filter:Placeholder');
			var oFilterElem = $('<div></div>')
				.addClass('sf_filter')
				.append('<span class="sff_input_wrapper"><input type="text" id="' + sFilterId + '" placeholder="' + sFilterPlaceholder + '" autocomplete="off" /><span class="sff_picto sff_reset fa fa-times"></span></span>')
				.appendTo(oOpContentElem);

			// - Allowed values
			var oAllowedValuesElem = $('<div></div>')
				.addClass('sfc_opc_mc_items')
				.appendTo(oOpContentElem);
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
					.trigger('focus');
			});

			// - Check / Uncheck all toggler
			oTogglersElem.on('click', function(oEvent){
				// Check / uncheck all allowed values
				var bChecked = $(this).closest('.sfc_opc_mc_toggler').find('input:checkbox').prop('checked');
				oOpContentElem.find('.sfc_opc_mc_item input:checkbox').prop('checked', bChecked);

				// Apply criteria
				me._apply();
			});

			// - Apply on check
			oAllowedValuesElem.on('click', '.sfc_opc_mc_item input', function(oEvent){
				// Prevent propagation, otherwise there will be multiple "_apply()"
				oEvent.stopPropagation();

				// Uncheck toggler
				oTogglersElem.find('input:checkbox').prop('checked', false);

				// Apply criteria
				me._apply();
			});

			oOpElem.find('.sfc_op_content').append(oOpContentElem);

			this._prepareInOperatorWithoutAutocomplete(oOpElem, sOpIdx, oOp);
			this._prepareInOperatorWithAutocomplete(oOpElem, sOpIdx, oOp);
		},
		_prepareInOperatorWithoutAutocomplete: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;

			if(this._hasAutocompleteAllowedValues())
			{
				return false;
			}

			var oOpContentElem = oOpElem.find('.sfc_opc_multichoices');
			var oDynamicListElem = oOpElem.find('.sfc_opc_mc_items_dynamic');
			var oFilterElem = oOpElem.find('.sf_filter');

			// DOM elements
			// - Filter
			oFilterElem.find('.sff_input_wrapper')
				.append('<span class="sff_picto sff_filter fa fa-filter"></span>');

			// - Allowed values
			var aSortedValues = this._sortValuesByLabel(this._getPreloadedAllowedValues());
			for (var i in aSortedValues)
			{
				var sValCode = aSortedValues[i][0];
				var sValLabel = aSortedValues[i][1];
				var oValueElem = this._makeListItemElement(sValLabel, sValCode);
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
				}
				else
				{
					oOpContentElem.find('.sfc_opc_mc_item').each(function(){
						var oRegExp = new RegExp(sQuery, 'ig');
						var sValue = $(this).find('input').val();
						var sLabel = $(this).text();

						if( (sValue.match(oRegExp) !== null) || (sLabel.match(oRegExp) !== null) )
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
			});
			oFilterElem.find('.sff_filter').on('click', function(){
				oFilterElem.find('input').trigger('focus');
			});
		},
		_prepareInOperatorWithAutocomplete: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;

			if(!this._hasAutocompleteAllowedValues())
			{
				return false;
			}

			var oOpContentElem = oOpElem.find('.sfc_opc_multichoices');
			var oDynamicListElem = oOpElem.find('.sfc_opc_mc_items_dynamic');
			var oFilterElem = oOpElem.find('.sf_filter');

			// DOM
			// - Add search dialog button
			oFilterElem
				.append('<button type="button" class="sff_search_dialog"><span class=" fa fa-search"></span></button>')
				.addClass('sf_with_buttons');

			// - Prepare "selected" values area
			var oSelectedElem = $('<div></div>')
				.addClass('sfc_opc_mc_items')
				.addClass('sfc_opc_mc_items_selected')
				.appendTo(oOpContentElem);

			// Events
			// Autocomplete
			var oACXHR = null;
			var oACTimeout = null;
			oFilterElem.find('input').on('keyup focus', function(oEvent){
				// TODO: Move on values with up and down arrow keys; select with space or enter.

				var sQuery = $(this).val();

				if(sQuery === '')
				{
					// TODO: Remove items and show placeholder
					oDynamicListElem.html('TOTR: Start typing for possible values.');

					oFilterElem.find('.sff_reset').hide();
				}
				else
				{
					clearTimeout(oACTimeout);
					oACTimeout = setTimeout(function(){
						// Show loader
						oDynamicListElem.html('TOTR: Please wait...');

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
							.done(function(oResponse, sStatus, oXHR){ me._onACSearchSuccess(oResponse, oDynamicListElem); })
							.fail(function(oResponse, sStatus, oXHR){  me._onACSearchFail(oResponse, sStatus); })
							.always(function(oResponse, sStatus, oXHR){ me._onACSearchAlways(); });

						oFilterElem.find('.sff_reset').show();
					}, me.options.autocomplete.xhr_throttle);
				}
			});
			//
			// // Open search dialog
			// oFilterElem.find('.sff_search_dialog').on('click', function(){
			// 	// TODO: Open search dialog with right params
			// 	alert('Not implemented yet');
			// });
		},
		_setTitle: function(sTitle)
		{
			var iValLimit = 3;
			var iValCount = Object.keys(this.options.values).length;
			var iAllowedValuesCount = Object.keys(this._getPreloadedAllowedValues()).length;

			// Manually increase allowed values count if null is allowed
			if(this.options.field.is_null_allowed === true)
			{
				iAllowedValuesCount++;
			}

			// Making right tite regarding the number of selected values
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

				sTitle = Dict.Format('UI:Search:Criteria:Title:Enum:In:Many', this.options.field.label, aFirstValues.join(', '), (iValCount - iValLimit+1));
			}

			this._super(sTitle);
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

			oOpElem.find('.sfc_opc_mc_item input:checked').each(function(iIdx, oElem){
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
			oOpElem.find('.sfc_opc_mc_item input').prop('checked', false);

			// Re-check allowed values from param
			for(var iIdx in aValues)
			{
				oOpElem.find('.sfc_opc_mc_item[data-value-code="' + aValues[iIdx].value + '"] input').prop('checked', true);
			}

			return true;
		},


		// Autocomplete callbacks
		_onACSearchSuccess: function(oResponse, oListElem)
		{
			// TODO: Show results
			console.log('ok', oResponse);
			for(var iKey in oResponse)
			{
				var oValueElem = this._makeListItemElement(iKey, oResponse[iKey]);
				oValueElem.appendTo(oListElem);
			}

		},
		_onACSearchFail: function(oResponse, sStatus)
		{
			if(sStatus !== 'abort')
			{
				this.element.find('.sfc_opc_mc_items_dynamic').html('TOTR: Error.');
			}
		},
		_onACSearchAlways: function()
		{
			// TODO: Remove loader
			this._trace('TODO: Remove loader');
		},


		// Value helpers
		// - Return true if sValue is among the selected values "codes"
		_isSelectedValues: function(sValue)
		{
			var bFound = false;

			for(var iValIdx in this.options.values)
			{
				if(this.options.values[iValIdx].value === sValue)
				{
					bFound = true;
					break;
				}
			}

			return bFound;
		},
		// - Return true if sLabel is among the selected values "labels"
		_isSelectedLabels: function(sLabel)
		{
			var bFound = false;

			for(var iValIdx in this.options.values)
			{
				if(this.options.values[iValIdx].label === sLabel)
				{
					bFound = true;
					break;
				}
			}

			return bFound;
		},
		// - Return an array of allowed values sorted by labels
		_sortValuesByLabel: function(oSource)
		{
			var aSortable = [];
			for (var sKey in oSource) {
				aSortable.push([sKey, oSource[sKey]]);
			}

			aSortable.sort(function(a, b) {
				if(a[1] < b[1])
				{
					return -1;
				}
				else if(a[1] > b[1])
				{
					return 1;
				}

				return 0;
			});

			return aSortable;
		},
		// - Make a jQuery element for a list item
		_makeListItemElement: function(sLabel, sValue)
		{
			var oItemElem = $('<div></div>')
				.addClass('sfc_opc_mc_item')
				.attr('data-value-code', sValue)
				.append('<label><input type="checkbox" value="'+sValue+'"/>'+sLabel+'</label>');

			return oItemElem;
		},
	});
});
