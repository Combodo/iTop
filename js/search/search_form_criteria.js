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
			handled_classes: [],
			get_current_value_callback: 'getCurrentValue',
			set_current_value_callback: function(me, oEvent, oData){ console.log('Search form criteria: set_current_value_callback must be overloaded, this is the default callback.'); },

			ref: "",
			operator: "=",
			values: [],
			oql: "",
			is_removable: true,
		},
   
		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element
			.addClass('search_form_criteria');

			// Get/SetCurrentValue callbacks handler
			this.element
				.bind('get_current_value set_current_value', function(oEvent, oData){
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
			this.element
			.removeClass('search_form_criteria');
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


		getCurrentValue: function()
		{
			// TODO
			// var value = null;
			//
			// this.element.find(':input').each(function(iIndex, oElem){
			// 	if($(oElem).is(':hidden') || $(oElem).is(':text') || $(oElem).is(':password') || $(oElem).is('textarea'))
			// 	{
			// 		value = $(oElem).val();
			// 	}
			// 	else if($(oElem).is('select'))
			// 	{
			// 		if($(oElem).is('select[multiple]'))
			// 		{
			// 			value = [];
			// 			$(oElem).find('option:selected').each(function(){
			// 				value.push($(this).val());
			// 			});
			// 		}
			// 		else
			// 		{
			// 			value = $(oElem).val();
			// 		}
			// 	}
			// 	else if($(oElem).is(':checkbox') || $(oElem).is(':radio'))
			// 	{
			// 		if(value === null)
			// 		{
			// 			value = [];
			// 		}
			// 		if($(oElem).is(':checked'))
			// 		{
			// 			value.push($(oElem).val());
			// 		}
			// 	}
			// 	else
			// 	{
			// 		console.log('Form field : Input type not handle yet.');
			// 	}
			// });
			//
			// return value;
		},


		// Prepare DOM element
		_prepareElement: function()
		{
			// Prepare base DOM structure
			//this.options.ref+' '+this.options.operator+' '+this.options.values
			this.element
				.append('<div class="sfc_title"></div>')
				.append('<div class="sfc_form_group"></div>')
				.append('<div class="sfc_toggle"><span class="fa fa-caret-down"></span></div>');

			// Removable / locked decoration
			if(this.options.is_removable === true)
			{
				this.element.append('<div class="sfc_close"><span class="fa fa-times"></span></div>');
			}
			else
			{
				this.element.append('<div class="sfc_locked"><span class="fa fa-lock"></span></div>');
			}

			// Fill
		},


		// Debug helper
		showOptions: function()
		{
			return this.options;
		}
	});
});
