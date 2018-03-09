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

		// DOM element helpers
		_prepareElement: function()
		{
			var me = this;

			this._super();

			// TODO: Refactor this after UI mockups
			var oInputElem = $('<input type="text" />');
			oInputElem.on('change', function(){
				var sValue = $(this).val();

				me.options.values = [{
					value: sValue,
					label: sValue,
				}];
				me._setTitle();

				me.handler.triggerHandler('itop.search.criteria.value_changed');
			})
				.appendTo(this.element.find('.sfc_form_group'));
		},
		_setTitle: function(sTitle)
		{
			if(sTitle === undefined)
			{
				sTitle = this.options.field.label + ': ' + this._getValuesAsText();
			}
			this._super(sTitle);
		},
	});
});
