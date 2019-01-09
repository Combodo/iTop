//iTop Search form criteria tag_set
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_tag_set' the widget name
	$.widget( 'itop.search_form_criteria_tag_set', $.itop.search_form_criteria_enum,
	{
		// default options
		options:
		{
			// Overload default operator
			'operator': 'MATCHES',
			// Available operators
			'available_operators': {
				'MATCHES': {
					'label': Dict.S('UI:Search:Criteria:Operator:TagSet:MATCHES'),
					'code': 'matches',
					'rank': 10,
				},
				'IN': null,
				'=': null,			// Remove this one from tag_set widget.
				'empty': null,		// Remove as it will be handle by the "null" value in the "MATCHES" operator
				'not_empty': null,	// Remove as it will be handle by the "null" value in the "MATCHES" operator
			},
            // Null value
            'null_value': {
                'code': '',
                'label': Dict.S('Enum:Undefined'),
            },
		},

   
		// the constructor
		_create: function()
		{
			var me = this;

			this._super();
			this.element.addClass('search_form_criteria_tag_set');
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria_tag_set');
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
		_prepareMatchesOperator: function(oOpElem, sOpIdx, oOp)
		{
			this._prepareInOperator(oOpElem, sOpIdx, oOp);
		},

		// Operators helpers
		// Reset operator's state
		_resetMatchesOperator: function(oOpElem)
		{
			this._resetInOperator(oOpElem);
		},
		// Get operator's values
		_getMatchesOperatorValues: function(oOpElem)
		{
			return this._getInOperatorValues(oOpElem);
		},
		// Set operator's values
		_setMatchesOperatorValues: function(oOpElem, aValues)
		{
			return this._setInOperatorValues(oOpElem, aValues);
		},
	});
});
