//iTop Search form criteria string
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_string' the widget name
	$.widget( 'itop.search_form_criteria_string', $.itop.search_form_criteria,
	{
		// default options
		options:
		{
			// Overload default operator
			'operator': 'contains',
			// Available operators
			'available_operators': {
				'contains': {
					'label': Dict.S('UI:Search:Criteria:Operator:String:Contains'),
					'code': 'contains',
					'rank': 10,
				},
				'starts_with': {
					'label': Dict.S('UI:Search:Criteria:Operator:String:StartsWith'),
					'code': 'starts_with',
					'rank': 20,
				},
				'ends_with': {
					'label': Dict.S('UI:Search:Criteria:Operator:String:EndsWith'),
					'code': 'ends_with',
					'rank': 30,
				},
				'=': {
					'rank': 40,//pre-existing, reordered
				},
				'REGEXP': {
					'label': Dict.S('UI:Search:Criteria:Operator:String:RegExp'),
					'code': 'reg_exp',
					'rank': 50,
				},
			},
		},
		_getOperatorValues: function(oOpElem)
		{
			var aValues = [];
			oOpElem.find('.sfc_op_content input').each(function(){
				var sValue = $(this).val();
				aValues.push({value: sValue.replace('_','\\_'), label: sValue});
			});
			return aValues;
		},
		_setOperatorValues: function(oOpElem, aValues)
		{
			if(aValues.length === 0)
			{
				return false;
			}
			oOpElem.find('.sfc_op_content input').each(function(){
				$(this).val(aValues[0].value.replace('\\_','_')).trigger('non_interactive_change');
			});
			return true;
		},
		// the constructor
		_create: function()
		{
			var me = this;

			this._super();
			this.element.addClass('search_form_criteria_string');
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria_string');
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
