//iTop Search form criteria external key
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_external_key' the widget name
	$.widget( 'itop.search_form_criteria_external_key', $.itop.search_form_criteria,
	{
		// default options
		options:
		{
			// Overload default operator
			'operator': '=',
			// Available operators
			'available_operators': {
			},
		},

   
		// the constructor
		_create: function()
		{
			var me = this;

			this._super();
			this.element.addClass('search_form_criteria_external_key');
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria_external_key');
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

		// Protected methods
		// - Cinematic
		//   - Open / close criteria
		_open: function()
		{
			this._super();

			// Focus on right input
			var oOpElemToFocus;
			if(this.element.find('.sfc_form_group').hasClass('advanced'))
			{
				oOpElemToFocus = this.element.find('.sfc_fg_operator .sfc_op_radio:checked').closest('.sfc_fg_operator');
			}
			else
			{
				oOpElemToFocus = this.element.find('.sfc_fg_operator:first');
			}
			oOpElemToFocus.find('.sfc_op_content :input:first').trigger('click').trigger('focus');
		},

		// DOM element helpers
		// TODO: Remove this function after UX tests
		_prepareOperators: function()
		{
			var me = this;

			this._super();

			if(this.options.field.allowed_values.values !== undefined)
			{
				var sSelect = '<select>';
				sSelect += '<option value="">' + Dict.S('UI:Combo:SelectValue') + '</option>';
				for(var sValCode in this.options.field.allowed_values.values)
				{
					var sValLabel = this.options.field.allowed_values.values[sValCode];
					sSelect += '<option value="' + sValCode + '">' + sValLabel + '</option>';
				}
				sSelect += '</select>';
				this.element.find('.sfc_fg_operator[data-operator-code="equals"] .sfc_op_content').html(sSelect);
			}
			else
			{
				this.element.find('.sfc_fg_operator[data-operator-code="equals"] .sfc_op_content').html('Not implemented yet.');
			}

			this.element.find('.sfc_fg_operator[data-operator-code="equals"] .sfc_op_content select').on('change', function(){
				me._apply();
			});
		},

		// Operators helpers
		// Reset operator's state
		// TODO: Reset operator's state
		// _resetEqualsOperator: function(oOpElem)
		// {
		// 	// Uncheck toggler
		// 	oOpElem.find('sfc_opc_mc_toggler input').prop('checked', false);
		//
		// 	// Clear filter
		// 	oOpElem.find('sfc_opc_mc_filter input').val('');
		// },
		// Get operator's values
		_getEqualsOperatorValues: function(oOpElem)
		{
			var aValues = [];

			var sValue = oOpElem.find('.sfc_op_content select > option:selected').val();
			var sLabel = oOpElem.find('.sfc_op_content select > option:selected').text();
			if(sValue !== "")
			{
				aValues.push({value: sValue, label: sLabel});
			}

			return aValues;
		},
		// Set operator's values
		// TODO: Set operator's values
		// _setInOperatorValues: function(oOpElem, aValues)
		// {
		// 	if(aValues.length === 0)
		// 	{
		// 		return false;
		// 	}
		//
		// 	// Uncheck all allowed values
		// 	oOpElem.find('.sfc_opc_mc_item input').prop('checked', false);
		//
		// 	// Re-check allowed values from param
		// 	for(var iIdx in aValues)
		// 	{
		// 		oOpElem.find('.sfc_opc_mc_item[data-value-code="' + aValues[iIdx].value + '"] input').prop('checked', true);
		// 	}
		//
		// 	return true;
		// },
	});
});
