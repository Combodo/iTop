//iTop Search form criteria hierarchical key
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_hierarchical_key' the widget name
	$.widget( 'itop.search_form_criteria_hierarchical_key', $.itop.search_form_criteria_external_key,
	{
		// default options
		options:
		{
			// True if the widget should also retrieve children of the selected objects.
			'is_hierarchical': true,
		},

   
		// the constructor
		_create: function()
		{
			var me = this;

			this._super();
			this.element.addClass('search_form_criteria_hierarchical_key');
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria_hierarchical_key');
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

		// Event callbacks
        // - External events
        _onGetData: function(oData)
        {
            var oCriteriaData = this._super(oData);

            if (null != oCriteriaData)
			{
                oCriteriaData.is_hierarchical = this.options.is_hierarchical;
            }

            return oCriteriaData;
        },

		// DOM element helpers
        _prepareInOperator: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;

			this._super(oOpElem, sOpIdx, oOp);

			if(this.options.is_hierarchical === true)
			{
                var oChildrenHintElem = $('<div></div>')
                    .addClass('sfc_opc_mc_items_hint')
                    .append('<span class="fas fa-info"></span>')
                    .append(Dict.S('UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint'))
                    .appendTo(oOpElem.find('.sfc_opc_mc_items_wrapper'));
			}
		},
	});
});
