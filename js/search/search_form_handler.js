//iTop Search form handler
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_handler' the widget name
	$.widget( 'itop.search_form_handler',
	{
		// default options
		options:
		{
			"criterion_outer_selector": null,
			"result_list_outer_selector": null,
			"submit_button_selector": null,
			"hide_initial_criterion": false, // What is that?
			"endpoint": null,
			"search": {
				"base_oql": "",
				"criterion": [
					// Structure
					// {
					// 	"or": [
					// 		{
					// 			"and": [
					// 				{
					// 					"ref": "alias.code",
					// 					"operator": "contains",
					// 					"values": [
					// 						{
					// 							"value": "foo",
					// 							"label": "bar",
					// 						}
					// 					],
					// 					"is_removable": true,
					// 					"oql": "",
					// 				},
					// 			]
					// 		},
					// 	]
					// },
				],
				"fields": [
					// Structure
					// 	"alias.code": {
					// 		"class_alias": "",
					// 		"class": "",
					// 		"code": "",
					// 		"label": "",
					// 		"type": "",
					// 		"allowed_values": {...},
					// 	},
				],
			},
		},

		// jQuery elements
		elements:
		{
			criterion_area: null,
		},

		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element
			.addClass('search_form_handler');

			// Binding events
			// this.element.bind('update_fields', function(oEvent, oData){
			// 	me._onUpdateFields(oEvent, oData);
			// });

			// Binding buttons
			if(this.options.submit_button_selector !== null)
			{
				this.options.submit_button_selector.off('click').on('click', function(oEvent){ me._onSubmitClick(oEvent); });
			}
			// if(this.options.cancel_btn_selector !== null)
			// {
			// 	this.options.cancel_btn_selector.off('click').on('click', function(oEvent){ me._onCancelClick(oEvent); });
			// }

			// Prepare criterion area
			this._prepareCriterionArea();
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
			.removeClass('search_form_handler');
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


		getCurrentValues: function()
		{
			// TODO
			// return this.options.field_set.triggerHandler('get_current_values');
		},

		// - Prepare criterion area
		_prepareCriterionArea: function()
		{
			// Build area element
			if(this.options.criterion_outer_selector !== null && $(this.options.criterion_outer_selector).length > 0)
			{
				this.elements.criterion_area = $(this.options.criterion_outer_selector);
			}
			else
			{
				var oCriterionAreaElem = $('<div></div>').appendTo(this.element);
				this.elements.criterion_area = oCriterionAreaElem;
			}
			this.elements.criterion_area.addClass('sf_criterion_area');

			// Clean area
			this.elements.criterion_area.html('');

			// Fill area with existing criterion
			for(var i in this.options.search.criterion)
			{
				console.log(i, this.options.search.criterion[i]);
			}
		},


		// Criteria handlers
		// - Add a criteria to the form
		_addCriteria: function(oData)
		{
			var sRef = oData.ref
			var sType = this._getCriteriaTypeFromFieldRef(sRef);

			if(sType !== null)
			{
				var sWidgetClass = 'search_form_criteria' + ((sType === 'raw') ? '' : '_' + sType);
				oCriteriaElem = $('<div></div>')
					.addClass('sf_criteria')
					.appendTo(this.elements.criterion_area)
					.search_form_criteria(oData);
			}
			else
			{
				this._trace('Could not add criteria as we could not retrieve type for ref "' + sRef + '".');
			}
		},
		// - Find a criteria's type from a field's ref (usually <CLASS_ALIAS>.<ATT_CODE>)
		_getCriteriaTypeFromFieldRef: function(sRef)
		{
			var sType = null;

			if(this.options.search.fields[sRef] !== undefined)
			{
				sType = this.options.search.fields[sRef].type;
			}

			return sType;
		},


		// Button handlers
		_onSubmitClick: function(oEvent)
		{
			// TODO
		},
		_onCancelClick: function(oEvent)
		{
			// TODO
		},


		// Update handlers
		// - Called on form update successes
		_onUpdateSuccess: function(oData, sFormPath)
		{
			// TODO
			// if(oData.form.updated_fields !== undefined)
			// {
			// 	this.element.find('[data-form-path="' + sFormPath + '"]').trigger('update_form', {updated_fields: oData.form.updated_fields});
			// }
		},
		// - Called on form update failures
		_onUpdateFailure: function(oData, sFormPath)
		{
			// TODO
		},
		// - Called after form updates
		_onUpdateAlways: function(oData, sFormPath)
		{
			// TODO
			// // Check all touched AFTER ajax is complete, otherwise the renderer will redraw the field in the mean time.
			// this.element.find('[data-form-path="' + sFormPath + '"]').trigger('validate');
			// this._enableFormAfterLoading();
		},


		// Helpers
		// - Show loader
		_disableFormBeforeLoading: function()
		{
			// TODO
		},
		// - Remove loader
		_enableFormAfterLoading: function()
		{
			// TODO
		},


		// Debug helpers
		// - Show a trace in the javascript console
		_trace: function(sMessage, oData)
		{
			if(window.console)
			{
				if(oData !== undefined)
				{
					console.log('Search form handler: ' + sMessage, oData);
				}
				else
				{
					console.log('Search form handler: ' + sMessage);
				}
			}
		},
		// - Show current options
		showOptions: function()
		{
			this._trace('Options', this.options, this.toto);
		}
	});
});
