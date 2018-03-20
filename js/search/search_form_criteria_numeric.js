//iTop Search form criteria numeric
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria_numeric' the widget name
	$.widget( 'itop.search_form_criteria_numeric', $.itop.search_form_criteria,
	{
		// default options
		options:
		{
			// Overload default operator
			'operator': '=',
			// Available operators
			'available_operators': {
				'=': {
					'label': Dict.S('UI:Search:Criteria:Operator:Numeric:Equals'),//pre-existing, label changed
					'dropdown_group':1,
				},
				'>': {
					'label': Dict.S('UI:Search:Criteria:Operator:Numeric:GreaterThan'),
					'code': 'greater_than',
					'rank': 100,
					'dropdown_group':1,
				},
				'>=': {
					'label': Dict.S('UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals'),
					'code': 'greater_than_or_equals',
					'rank': 200,
					'dropdown_group':1,
				},
				'<': {
					'label': Dict.S('UI:Search:Criteria:Operator:Numeric:LessThan'),
					'code': 'less_than',
					'rank': 300,
					'dropdown_group':1,
				},
				'<=': {
					'label': Dict.S('UI:Search:Criteria:Operator:Numeric:LessThanOrEquals'),
					'code': 'less_than_or_equals',
					'rank': 400,
					'dropdown_group':1,
				},
				'!=': {
					'label': Dict.S('UI:Search:Criteria:Operator:Numeric:DifferentThan'),
					'code': 'different',
					'rank': 500,
					'dropdown_group':1,
				},
				'between': {
					'label': Dict.S('UI:Search:Criteria:Operator:Numeric:Between'),
					'code': 'between',
					'rank': 600,
				},
				'empty': {
					'rank': 700,//pre-existing, reordered
				},
				'not_empty': {
					'rank': 800,//pre-existing, reordered
				},
			},
		},

   
		// the constructor
		_create: function()
		{
			var me = this;

			this._super();
			this.element.addClass('search_form_criteria_numeric');
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria_numeric');
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


		// // Prepare operator's DOM element
		// _prepareBetweenOperator: function(oOpElem, sOpIdx, oOp)
		// {
		// 	var me = this;
		//
		// 	// DOM element
		// 	var oOpContentElem = $('<input type="text" />');
		// 	oOpContentElem.val(this._getValuesAsText());
		//
		// 	// Events
		// 	// - Focus input on click (radio, label, ...)
		// 	oOpElem.on('click', ':not(input[type="text"])', function(){
		// 		oOpContentElem.focus();
		// 	});
		// 	// - Apply on "enter" key hit
		// 	oOpContentElem.on('keyup', function(oEvent){
		// 		// Check operator's radio if not already (typically when focusing in input via "tab" key)
		// 		if(oOpElem.find('.sfc_op_radio').prop('checked') === false)
		// 		{
		// 			oOpElem.find('.sfc_op_radio').prop('checked', true)
		// 		}
		//
		// 		me._markAsDraft();
		//
		// 		// Apply if enter key
		// 		if(oEvent.key === 'Enter')
		// 		{
		// 			me._apply();
		// 		}
		// 	});
		//
		// 	oOpElem.find('.sfc_op_content').append(oOpContentElem);
		// },

		//------------------
		// Inherited methods
		//------------------


		// Prepare operator's DOM element
		// - Base preparation, always called
		_prepareOperator: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;
			if (typeof oOp.dropdown_group == 'undefined')
			{
				return this._super(oOpElem, sOpIdx, oOp);
			}

			this._super(oOpElem, sOpIdx, oOp);
			oOpElem.addClass('force_hide')

			// DOM element
			oDropdown = this.element.find('select.dropdown_group_'+oOp.dropdown_group);
			if (oDropdown.length == 0)
			{
				oDropdown = $('<select class="dropdown_group_'+oOp.dropdown_group+'" data-dropdown-group="'+oOp.dropdown_group+'"></select>');

				oDropdown.on('change', function(){
					$option = $(this);
					me.element.find('.sfc_op_radio').val($option.val());

					oOptionOp = $option.data('oOp');
					$option.attr('data-operator-code', oOptionOp.code);
				});


				// Create DOM element from template
				var oOpElemDropdown = $(this._getOperatorTemplate()).uniqueId();

				oOpElemDropdown
					.addClass('sfc_fg_operator_dropdown_group')
					.attr('data-operator-code', 'dropdown_group')
					.find('.sfc_op_name')
						.append(oDropdown)
					.end()
					.find('.sfc_op_radio')
						.val(sOpIdx)
					.end()
					.on('click', function(){
						var bIsChecked = oOpElemDropdown.find('.sfc_op_radio').prop('checked');

						if(bIsChecked === false)
						{
							oOpElemDropdown.find('.sfc_op_radio').prop('checked', true);
							me._markAsDraft();
						}
					})
					.appendTo(this.element.find('.sfc_fg_operators'))
				;

				this._prepareDefaultOperator(oOpElemDropdown, sOpIdx, oOp);
			}

			oDropdown
				.append('<option value="'+sOpIdx+'" >'+oOp.label+'</option>')
				.data('oOp', oOp)
			;
		},


	});
});
