//iTop Search form criteria raw
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_raw' the widget name
	$.widget( 'itop.search_form_criteria_raw', $.itop.search_form_criteria,
	{
		// default options
		options:
		{
			'label': '', // Computed by server
		},
   
		// the constructor
		_create: function()
		{
			var me = this;

			this._super();
			this.element.addClass('search_form_criteria_raw');
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria_raw');
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
		_prepareElement: function()
		{
			this._super();

			// Remove toggler as it's a non sense here
			this.element.find('.sfc_toggle').remove();
			this.element.find('.sfc_toggle, .sfc_title').off('click');

			// Force close as it has no sense either
			this._close();
		},
		_prepareOperators: function()
		{
			// Overloading function and doing nothing for this special kind of criteria.
		},
		_prepareButtons: function()
		{
			// Overloading function and doing nothing for this special kind of criteria.
		},
		_computeTitle: function(sTitle)
		{
			if(sTitle === undefined)
			{
				sTitle = $('<div/>').text(this.options.label).html();
			}

			return this._super(sTitle);
		},
	});
});
