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
				'=': null,	// Remove this one from string widget.
			},
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

		// DOM element helpers
		// TODO: Remove this function after UX tests
		_prepareOperators: function()
		{
			this._super();

			if(this.options.ref === 'UserRequest.ref')
			{
				// Remove starts/ends_with
				this.element.find('.sfc_fg_operator[data-operator-code*="with"]').remove();

				// Modify contains with a dropdown
				this.element.find('.sfc_fg_operator[data-operator-code="contains"] .sfc_op_name')
					.html('<select style="    padding: 1px 0px;"><option>Contains</option><option>Starts with</option><option>Ends with</option></select>')
					.css('margin-right', '5px');

				// Remove click event on dropdown
				this.element.find('.sfc_fg_operator[data-operator-code="contains"] .sfc_op_name').on('click', function(oEvent){
					oEvent.preventDefault();
					oEvent.stopPropagation();
				});

				this.element.find('.sfc_fg_operator[data-operator-code="contains"] .sfc_op_name select').on('change', function(){
					$(this).closest('.sfc_fg_operator').find('.sfc_op_content input:first').trigger('focus');
				});
			}
		},
	});
});
