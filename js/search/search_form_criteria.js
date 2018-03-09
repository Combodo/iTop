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
			ref: '',
			operator: '=',
			values: [],
			oql: '',
			is_removable: true,

			field: {
				label: '',
			},
			is_modified: false, // TODO: change this on value change and remove oql property value
		},

		handler: null,
   
		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element.addClass('search_form_criteria');

			// Link search form handler
			this.handler = this.element.closest('.search_form_handler');

			// GetData
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
		_remove: function()
		{
			this.element.remove();
			this.handler.triggerHandler('itop.search.criteria.removed');
		},


		// Event callbacks
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
			//this.options.ref+' '+this.options.operator+' '+this.options.values
			this.element
				.append('<div class="sfc_title"></div>')
				.append('<div class="sfc_form_group"></div>')
				.append('<span class="sfc_toggle"><a class="fa fa-caret-down" href="#"></a></span>');

			// Bind events
			// - Toggler
			this.element.find('.sfc_toggle, .sfc_title').on('click', function(){
				me.element.find('.sfc_form_group').toggle();
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

			// Fill criteria
			this._setTitle();
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
