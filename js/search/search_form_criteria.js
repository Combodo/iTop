//iTop Search form criteria
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria' the widget name
	$.widget( 'itop.search_form_criteria',
	{
		// default options
		options:
		{
			// Default values for the criteria
			ref: '',
			operator: '=',
			values: [],
			oql: '',
			is_removable: true,

			field: {
				label: '',
			},

			// Available operators (merged with derived widgets, ordered and then copied to this.operators)
			available_operators: {
				'=': {
					'label': Dict.S('UI:Search:Criteria:Operator:Default:Equals'),
					'code': 'equals',
					'rank': 10,
				},
				'empty': {
					'label': Dict.S('UI:Search:Criteria:Operator:Default:Empty'),
					'code': 'empty',
					'rank': 90,
				},
				'not_empty': {
					'label': Dict.S('UI:Search:Criteria:Operator:Default:NotEmpty'),
					'code': 'not_empty',
					'rank': 100,
				},
			},

			is_modified: false, // TODO: change this on value change and remove oql property value
		},

		// Operators
		operators: {},

		// Form handler
		handler: null,
   
		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element.addClass('search_form_criteria');

			this._orderOperators();

			// Link search form handler
			this.handler = this.element.closest('.search_form_handler');

			// Bind events
			this._bindEvents();

			this._prepareElement();
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria');
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


		// Protected methods
		// - Order available operators
		_orderOperators: function()
		{
			console.log(this.options.available_operators);

		},
		// - Bind external events
		_bindEvents: function()
		{
			var me = this;

			// Get criteria data
			this.element.bind('itop.search.criteria.get_data', function(oEvent, oData){
				return me._onGetData(oData);
			});

			// Get/SetCurrentValues callbacks handler
			this.element.bind('itop.search.criteria.get_current_values itop.search.criteria.set_current_values', function(oEvent, oData){
				oEvent.stopPropagation();

				var callback = me.options[oEvent.type+'_callback'];

				if(typeof callback === 'string')
				{
					return me[callback](oEvent, oData);
				}
				else if(typeof callback === 'function')
				{
					return callback(me, oEvent, oData);
				}
				else
				{
					console.log('search form criteria: callback type must be a function or a existing function name of the widget');
					return false;
				}
			});
		},
		_remove: function()
		{
			this.element.remove();
			this.handler.triggerHandler('itop.search.criteria.removed');
		},


		// Event callbacks
		// - Internal events
		_onButtonApply: function()
		{
			this._trace('TODO: Apply button');
			this.handler.triggerHandler('itop.search.criteria.value_changed');
		},
		_onButtonCancel: function()
		{
			this._trace('TODO: Cancel button');
		},
		_onButtonMore: function()
		{
			this.element.find('.sfc_form_group').addClass('advanced');
		},
		_onButtonLess: function()
		{
			this.element.find('.sfc_form_group').removeClass('advanced');
		},
		// - External events
		_onGetData: function(oData)
		{
			var oCriteriaData = {
				'ref': this.options.ref,
				'operator': this.options.operator,
				'values': this.options.values,
				'is_removable': this.options.is_removable,
				'oql': this.options.oql,
			};
			return oCriteriaData;
		},


		// DOM element helpers
		// - Prepare element DOM structure
		_prepareElement: function()
		{
			var me = this;

			// Prepare base DOM structure
			this.element
				.append('<div class="sfc_title"></div>')
				.append('<div class="sfc_form_group"><div class="sfc_fg_operators"></div><div class="sfc_fg_buttons"></div></div>')
				.append('<span class="sfc_toggle"><a class="fa fa-caret-down" href="#"></a></span>');

			// Bind events
			// - Toggler
			this.element.find('.sfc_toggle, .sfc_title').on('click', function(){
				me.element.find('.sfc_form_group').toggle();
				me.element.find('.sfc_toggle').toggleClass('opened');
			});

			// Removable / locked decoration
			if(this.options.is_removable === true)
			{
				this.element.append('<span class="sfc_close"><a class="fa fa-times" href="#"></a></span>');
				this.element.find('.sfc_close').on('click', function(){
					me._remove();
				});
			}
			else
			{
				this.element.append('<div class="sfc_locked"><span class="fa fa-lock"></span></div>');
			}

			// Form group
			this._prepareOperators();
			this._prepareButtons();

			// Fill criteria
			// - Title
			this._setTitle();
		},
		// - Prepare the available operators for the criteria
		//   Meant for overloading.
		_prepareOperators: function()
		{
			for(var sOpIdx in this.options.available_operators)
			{
				var oOp = this.options.available_operators[sOpIdx];
				var sMethod = '_prepare' + this._toCamelCase(oOp.code) + 'Operator';

				// Create DOM element from template
				var oOpElem = $(this._getOperatorTemplate()).uniqueId();

				// Prepare operator's base elements
				this._prepareOperator(oOpElem, oOp);

				// Prepare operator's specific elements
				if(this[sMethod] !== undefined)
				{
					this[sMethod](oOpElem, oOp);
				}
				else
				{
					this._prepareDefaultOperator(oOpElem, oOp);
				}

				// Append to form group
				oOpElem.appendTo(this.element.find('.sfc_fg_operators'));
			}
		},
		// - Prepare the buttons (DOM and events) for a criteria
		_prepareButtons: function()
		{
			var me = this;

			// DOM elements
			this.element.find('.sfc_fg_buttons')
				.append('<button type="button" name="apply" class="sfc_fg_button sfc_fg_apply">' + Dict.S('UI:Button:Apply') + '</button>')
				.append('<button type="button" name="cancel" class="sfc_fg_button sfc_fg_cancel">' + Dict.S('UI:Button:Cancel') + '</button>')
				.append('<button type="button" name="more" class="sfc_fg_button sfc_fg_more">' + Dict.S('UI:Button:More') + '</button>')
				.append('<button type="button" name="less" class="sfc_fg_button sfc_fg_less">' + Dict.S('UI:Button:Less') + '</button>');

			// Events
			this.element.find('.sfc_fg_button').on('click', function(oEvent){
				oEvent.preventDefault();
				oEvent.stopPropagation();

				var sCallback = '_onButton' + me._toCamelCase($(this).attr('name'));
				me[sCallback]();
			});
		},
		// - Set the title element
		_setTitle: function(sTitle)
		{
			if(sTitle === undefined)
			{
				// TODO: Make nice label
				sTitle = this.options.field.label + ': ' + this._getValuesAsText();
			}
			this.element.find('.sfc_title').text(sTitle);
		},
		// - Return a HTML template for operators
		_getOperatorTemplate: function()
		{
			return '<div class="sfc_fg_operator"><label><input type="radio" class="sfc_op_radio" name="operator" value="" /><span class="sfc_op_name"></span><span class="sfc_op_content"></span></label></div>';
		},

		// Operators helpers
		_prepareOperator: function(oOpElem, oOp)
		{
			var sInputId = oOp.code + '_' + oOpElem.attr('id');

			// Set label
			oOpElem.find('.sfc_op_name').text(oOp.label);
			oOpElem.find('> label').attr('for', sInputId);

			// Set value
			oOpElem.find('.sfc_op_radio').val(oOpElem.id);
			oOpElem.find('.sfc_op_radio').attr('id', sInputId);

			// Bind events
			// - Check radio button on click
			oOpElem.on('click', function(){
				oOpElem.find('.sfc_op_radio').prop('checked', true);
			});
		},
		_prepareDefaultOperator: function(oOpElem, oOp)
		{
			var me = this;

			// DOM element
			var oOpContentElem = $('<input type="text" />');
			oOpContentElem.val(this._getValuesAsText());

			oOpElem.append(oOpContentElem);
		},
		_prepareEmptyOperator: function(oOpElem, oOp)
		{
			// Do nothing as only the label is necessary
		},
		_prepareNotEmptyOperator: function(oOpElem, oOp)
		{
			// Do nothing as only the label is necessary
		},

		// Values helpers
		// - Convert values to a standard string
		_getValuesAsText: function()
		{
			var aValues = [];
			for(var iValueIdx in this.options.values)
			{
				aValues.push(this.options.values[iValueIdx].label);
			}

			return aValues.join(', ');
		},
		// - Make an OQL expression from the criteria values and operator
		_makeOQLExpression: function()
		{
			var aValues = [];
			var sOQL = '';

			for(var iValueIdx in this.options.values)
			{
				aValues.push( '\'' + this.options.values[iValueIdx].value + '\'' );
			}
			sOQL += '(`' + this.options.ref + '`) ' + this.options.operator + ' ' + aValues.join(', ') + ')';

			return sOQL;
		},


		// Global helpers
		// - Converts a snake_case string to CamelCase
		_toCamelCase: function(sString)
		{
			var aParts = sString.split('_');

			for(var i in aParts)
			{
				aParts[i] = aParts[i].charAt(0).toUpperCase() + aParts[i].substr(1);
			}

			return aParts.join('');
		},


		// Debug helpers
		// - Show a trace in the javascript console
		_trace: function(sMessage, oData)
		{
			if(window.console)
			{
				if(oData !== undefined)
				{
					console.log('Search form criteria: ' + sMessage, oData);
				}
				else
				{
					console.log('Search form criteria: ' + sMessage);
				}
			}
		},
		// - Show current options
		showOptions: function()
		{
			this._trace('Options', this.options);
		}
	});
});
