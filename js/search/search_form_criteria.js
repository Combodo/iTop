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
			get_current_values_callback: 'getCurrentValues',
			set_current_values_callback: function(me, oEvent, oData){ console.log('Search form criteria: set_current_values_callback must be overloaded, this is the default callback.'); },

			ref: '',
			operator: '=',
			values: [],
			oql: '',
			is_removable: true,
			is_modified: false, // TODO: change this on value change and remove oql property value
		},
   
		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element.addClass('search_form_criteria');

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


		// Public methods
		getCurrentValues: function()
		{
			var aValues = this.options.values;
			return aValues;
		},


		// Event callbacks
		_onGetData: function(oData)
		{
			var oData = {
				'ref': this.options.ref,
				'operator': this.options.operator,
				'values': this.options.values,
				'is_removable': this.options.is_removable,
				'oql': this.options.oql,
			};
			return oData;
		},


		// DOM element helpers
		// - Prepare element DOM structure
		_prepareElement: function()
		{
			// Prepare base DOM structure
			//this.options.ref+' '+this.options.operator+' '+this.options.values
			this.element
				.append('<div class="sfc_title"></div>')
				.append('<div class="sfc_form_group"></div>')
				.append('<div class="sfc_toggle"><a class="fa fa-caret-down" href="#"></a></div>');

			// Removable / locked decoration
			if(this.options.is_removable === true)
			{
				this.element.append('<div class="sfc_close"><a class="fa fa-times" href="#"></a></div>');
			}
			else
			{
				this.element.append('<div class="sfc_locked"><span class="fa fa-lock"></span></div>');
			}

			// Fill criteria
			this._setTitle();
		},
		_setTitle: function(sTitle)
		{
			if(sTitle === undefined)
			{
				// TODO: Make nice label
			}
			this.element.find('.sfc_title').text(sTitle);
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
