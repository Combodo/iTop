//iTop Search form criteria external key
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_external_key' the widget name
	$.widget( 'itop.search_form_criteria_external_key', $.itop.search_form_criteria_enum,
	{
		// default options
		options:
		{
			// Null value
			'null_value': {
				'code': 0,
				'label': Dict.S('UI:UndefinedObject'),
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

	});
});
