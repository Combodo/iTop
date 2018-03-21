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
				'=': null,	// Remove this one from enum widget.
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
			var oOpContentElem = $('<div class="sfc_opc_multichoices"></div>');

			// - Check / Uncheck all togglers
			var sTogglerId = 'toggle_' + sOpId;
			var oTogglersElem = $('<div class="sfc_opc_mc_toggler"></div>')
				.append('<label for="' + sTogglerId + '"><input type="checkbox" id="' + sTogglerId + '" />' + Dict.S('UI:Search:Value:Toggler:CheckAllNone') + '</label>')
				.appendTo(oOpContentElem);

			// - Filter
			var sFilterId = 'filter_' + sOpId;
			var oFilterElem = $('<div class="sfc_opc_mc_filter"></div>')
				.append('<input type="text" id="' + sFilterId + '" placeholder="' + Dict.S('UI:Search:Value:Filter:Placeholder') + '" /><span class="sfc_opc_mcf_picto sfc_opc_mcf_filter fa fa-filter"></span><span class="sfc_opc_mcf_picto sfc_opc_mcf_reset fa fa-times"></span>')
				.appendTo(oOpContentElem);

			// - Allowed values
			var oAllowedValuesElem = $('<div class="sfc_opc_mc_items"></div>');
			if(this.options.field.allowed_values.values !== undefined)
			{
				var aSortedValues = this._sortValuesByLabel(this.options.field.allowed_values.values);

				var iValCounter = 0;
				for(var i in aSortedValues)
				{
					var sItemId = 'value_' + sOpId + '_' + iValCounter;
					var sValCode = aSortedValues[i][0];
					var sValLabel = aSortedValues[i][1];
					var oValueElem = $('<div class="sfc_opc_mc_item" data-value-code="' + sValCode + '"></div>')
						.append('<label for="' + sItemId + '"><input type="checkbox" id="' + sItemId + '" value="' + sValCode + '"/>' + sValLabel + '</label>')
						.appendTo(oAllowedValuesElem);

					if(this._isSelectedValues(sValCode))
					{
						oValueElem.find(':checkbox').prop('checked', true);
					}

					iValCounter++;
				}
			}
			oAllowedValuesElem.appendTo(oOpContentElem);

			// Events
			// - Check / Uncheck all toggler
			oTogglersElem.on('click', function(oEvent){
				// Check / uncheck all allowed values
				var bChecked = $(this).closest('.sfc_opc_mc_toggler').find('input:checkbox').prop('checked');
				oOpContentElem.find('.sfc_opc_mc_item input:checkbox').prop('checked', bChecked);

				// Apply criteria
				me._apply();
			});

			// - Filter
			oFilterElem.find('input').on('keyup focus', function(oEvent){
				// TODO: Move on values with up and down arrow keys; select with space or enter.

				var sFilter = $(this).val();

				if(sFilter === '')
				{
					oOpContentElem.find('.sfc_opc_mc_item').show();
					oFilterElem.find('.sfc_opc_mcf_filter').show();
					oFilterElem.find('.sfc_opc_mcf_reset').hide();
				}
				else
				{
					oOpContentElem.find('.sfc_opc_mc_item').each(function(){
						var oRegExp = new RegExp(sFilter, 'ig');
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
					oFilterElem.find('.sfc_opc_mcf_filter').hide();
					oFilterElem.find('.sfc_opc_mcf_reset').show();
				}
			});
			oFilterElem.find('.sfc_opc_mcf_filter').on('click', function(){
				oFilterElem.find('input').trigger('focus');
			});
			oFilterElem.find('.sfc_opc_mcf_reset').on('click', function(){
				oFilterElem.find('input')
					.val('')
					.trigger('focus');
			});

			// - Apply on check
			oAllowedValuesElem.find('.sfc_opc_mc_item').on('click', function(oEvent){
				// Uncheck toggler
				oTogglersElem.find('input:checkbox').prop('checked', false);

				// Apply criteria
				me._apply();
			});

			oOpElem.find('.sfc_op_content').append(oOpContentElem);
		},
		_setTitle: function(sTitle)
		{
			var iValLimit = 3;
			var iValCount = Object.keys(this.options.values).length;
			if(iValCount > iValLimit)
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
		}
	});
});
