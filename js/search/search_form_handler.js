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
			'criterion_outer_selector': null,
			'result_list_outer_selector': null,
			'data_config_list_selector': null,
			'submit_button_selector': null,
			'hide_initial_criterion': false, // TODO: What is that?
			'endpoint': null,
			'search': {
				'base_oql': '',
				'criterion': [
					// Structure
					// {
					// 	'or': [
					// 		{
					// 			'and': [
					// 				{
					// 					'ref': 'alias.code',
					// 					'operator': 'contains',
					// 					'values': [
					// 						{
					// 							'value': 'foo',
					// 							'label': 'bar',
					// 						}
					// 					],
					// 					'is_removable': true,
					// 					'oql': '',
					// 				},
					// 			]
					// 		},
					// 	]
					// },
				],
				'fields': [
					// Structure
					// 'zlist': {
					// 	'alias.code': {
					// 		'class_alias': '',
					// 			'class': '',
					// 			'code': '',
					// 			'label': '',
					// 			'type': '',
					// 			'allowed_values': {...},
					// 	},
					// },
					// 'others': {
					// 	'alias.code': {
					// 		'class_alias': '',
					// 			'class': '',
					// 			'code': '',
					// 			'label': '',
					// 			'type': '',
					// 			'allowed_values': {...},
					// 	},
					// },
				],
			},
			'supported_criterion_types': ['raw', 'string'],
			'default_criteria_type': 'raw',
		},

		// jQuery elements
		elements:
		{
			active_criterion: null,
			more_criterion: null,
			results_area: null,
		},

		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element.addClass('search_form_handler');

			// Prepare DOM elements
			this._prepareFormArea();
			this._prepareCriterionArea();
			this._prepareResultsArea();

			// Binding buttons
			if(this.options.submit_button_selector !== null)
			{
				$(this.options.submit_button_selector).off('click').on('click', function(oEvent){ me._onSubmitClick(oEvent); });
			}

			// Binding events (eg. from search_form_criteria widgets)
			this._bindEvents();
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


		//
		_bindEvents: function()
		{
			var me = this;

			// Form events
			// - Prevent regular form submission (eg. hitting "Enter" in inputs)
			this.element.on('submit', function(oEvent){
				oEvent.preventDefault();
			});
			// - Submit the search form
			this.element.on('itop.search.form.submit', function(oEvent, oData){
				me._onSubmit();
			});

			// Criteria events
			this.element.on('itop.search.criteria.opening', function(oEvent, oData){
				me._onCriteriaOpening(oData);
			});
			this.element.on('itop.search.criteria.value_changed', function(oEvent, oData){
				me._onCriteriaValueChanged(oData);
			});
			this.element.on('itop.search.criteria.removed', function(oEvent, oData){
				me._onCriteriaRemoved(oData);
			});
		},
		// - Update search option of the widget
		_updateSearch: function()
		{
			var me = this;

			// Criterion
			// - Note: As of today, only a "or" level with a "and" is supported, so the following part
			//         will need some refactoring when introducing new stuff.
			var oCriterion = {
				'or': [{
					'and': []
				}]
			};
			// - Retrieve criterion
			this.elements.active_criterion.find('.search_form_criteria').each(function(){
				var oCriteriaData = $(this).triggerHandler('itop.search.criteria.get_data');
				oCriterion['or'][0]['and'].push(oCriteriaData);
			});
			// - Update search
			this.options.search.criterion = oCriterion;

			// No need to update base OQL and fields
		},
		// - Open / Close more criterion menu
		_openMoreCriterion: function()
		{
			// Close all criterion
			this.elements.active_criterion.find('.search_form_criteria').each(function(){
				$(this).triggerHandler('itop.search.criteria.close');
			});

			// Open more criterion menu
			// - Open it first
			this.elements.more_criterion.addClass('opened');
			// - Then only check if more menu is to close to the right side (otherwise we might not have the right element's position)
			var iFormWidth = this.element.outerWidth();
			var iFormLeftPos = this.element.offset().left;
			var iMenuWidth = this.elements.more_criterion.find('.sfm_content').outerWidth();
			var iMenuLeftPos = this.elements.more_criterion.find('.sfm_content').offset().left;
			if( (iMenuWidth + iMenuLeftPos) > (iFormWidth + iFormLeftPos - 10 /* Security margin */) )
			{
				this.elements.more_criterion.addClass('opened_left');
			}
		},
		_closeMoreCriterion: function()
		{
			this.elements.more_criterion.removeClass('opened_left');
			this.elements.more_criterion.removeClass('opened');
		},
		_toggleMoreCriterion: function()
		{
			// Calling methods instead of toggling the class so additional processing are done.
			if(this.elements.more_criterion.hasClass('opened'))
			{
				this._closeMoreCriterion();
			}
			else
			{
				this._openMoreCriterion();
			}
		},

		// DOM helpers
		// - Prepare form area
		_prepareFormArea: function()
		{
			var me = this;

			// TODO: UX Improvment
			// Note: Would be better to toggle by clicking on the whole title, but we have an issue with <select> on abstract classes.
			this.element.find('.sft_toggler').on('click', function(oEvent){
				oEvent.preventDefault();
				me.element.find('.sf_criterion_area').slideToggle('fast');
				me.element.toggleClass('opened');
			});
		},
		// - Prepare criterion area
		_prepareCriterionArea: function()
		{
			var oCriterionAreaElem;

			// Build area element
			if(this.options.criterion_outer_selector !== null && $(this.options.criterion_outer_selector).length > 0)
			{
				oCriterionAreaElem = $(this.options.criterion_outer_selector);
			}
			else
			{
				oCriterionAreaElem = $('<div></div>').appendTo(this.element);
			}
			oCriterionAreaElem.addClass('sf_criterion_area');

			// Clean area
			oCriterionAreaElem
				.html('')
				.append('<div class="sf_active_criterion"></div>')
				.append('<div class="sf_more_criterion"></div>');
			this.elements.active_criterion = oCriterionAreaElem.find('.sf_active_criterion');
			this.elements.more_criterion = oCriterionAreaElem.find('.sf_more_criterion');

			// Prepare content
			this._prepareExistingCriterion();
			this._prepareMoreCriterionMenu();
		},
		// - Prepare existing criterion
		_prepareExistingCriterion: function()
		{
			// - OR conditions
			var aORs = (this.options.search.criterion['or'] !== undefined) ? this.options.search.criterion['or'] : [];
			for(var iORIdx in aORs)
			{
				// Note: We might want to create a OR container here when handling several OR conditions.

				var aANDs = (aORs[iORIdx]['and'] !== undefined) ? aORs[iORIdx]['and'] : [];
				for(var iANDIdx in aANDs)
				{
					var oCriteriaData = aANDs[iANDIdx];
					this._addCriteria(oCriteriaData);
				}
			}
		},
		// - Prepare "more" button
		_prepareMoreCriterionMenu: function()
		{
			var me = this;

			// Header part
			var oHeaderElem = $('<div class="sfm_header"></div>')
				.append('<a class="sfm_toggler" href="#"><span class="sfm_tg_title">' + Dict.S('UI:Search:Criterion:MoreMenu:AddCriteria') + '</span><span class="sfm_tg_icon fa fa-plus"></span></a>')
				.appendTo(this.elements.more_criterion);

			// Content part
			var oContentElem = $('<div class="sfm_content"></div>')
				.appendTo(this.elements.more_criterion);
			// - Add list
			var oListElem = $('<ul class="sfm_list"></ul>')
				.appendTo(oContentElem);
			// - Add fields
			// TODO: Find a widget to handle dropdown menu
			// - From "search" zlist
			for(var sFieldRef in this.options.search.fields.zlist)
			{
				var oField = this.options.search.fields.zlist[sFieldRef];
				var oFieldElem = $('<li></li>')
					.addClass('sfm_field')
					.attr('data-field-ref', sFieldRef)
					.text(oField.label);
				oListElem.append(oFieldElem);
			}
			// - Others
			if(this.options.search.fields.others !== undefined)
			{
				oListElem.append('<li>==================</li>');
				oListElem.append('<li>|| TODO: Better separation ||</li>');
				oListElem.append('<li>==================</li>');
				for(var sFieldRef in this.options.search.fields.others)
				{
					var oField = this.options.search.fields.others[sFieldRef];
					var oFieldElem = $('<li></li>')
						.addClass('sfm_field')
						.attr('data-field-ref', sFieldRef)
						.text(oField.label);
					oListElem.append(oFieldElem);
				}
			}

			// Bind events
			// - Open / close menu
			this.elements.more_criterion.find('.sfm_header').on('click', function(oEvent){
				oEvent.preventDefault();
				me._toggleMoreCriterion();
			});
			// - Add criteria
			this.elements.more_criterion.find('.sfm_field').on('click', function(oEvent){
				oEvent.preventDefault();
				// Prepare new criterion data (as already opened to increase UX)
				var oData = {
					'ref': $(this).attr('data-field-ref'),
					'init_opened': true,
				};

				// Add criteria but don't submit form as the user has not specified the value yet.
				me._addCriteria(oData);
				me._closeMoreCriterion();
			});
		},
		// - Prepare results area
		_prepareResultsArea: function()
		{
			var oResultAreaElem;

			// Build area element
			if(this.options.result_list_outer_selector !== null && $(this.options.result_list_outer_selector).length > 0)
			{
				oResultAreaElem = $(this.options.result_list_outer_selector);
			}
			else
			{
				// TODO: Change this so it appears after the search drawer.
				oResultAreaElem = $('<div></div>').appendTo(this.element);
			}
			oResultAreaElem.addClass('sf_results_area');

			this.elements.results_area = oResultAreaElem;
		},


		// Criteria helpers
		// - Add a criteria to the form
		_addCriteria: function(oData)
		{
			var sRef = oData.ref;
			var sType = this._getCriteriaTypeFromFieldRef(sRef);

			if(sType !== null)
			{
				// Retrieve widget class
				var sWidgetName = this._getCriteriaWidgetNameFromType(sType);

				// Add some informations from the field
				if(this._hasFieldDefinition(sRef))
				{
					var oFieldDef = this._getFieldDefinition(sRef);
					oData.field = {
						label: oFieldDef.label,
						class: oFieldDef.class,
						class_alias: oFieldDef.class_alias,
						code: oFieldDef.code,
						widget: oFieldDef.widget,
					};
				}

				// Create DOM element
				var oCriteriaElem = $('<div></div>')
					.addClass('sf_criteria')
					.appendTo(this.elements.active_criterion);

				// Instanciate widget
				$.itop[sWidgetName](oData, oCriteriaElem);
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

			for(var sListIdx in this.options.search.fields)
			{
				if(this.options.search.fields[sListIdx][sRef] !== undefined)
				{
					sType = this.options.search.fields[sListIdx][sRef].widget.toLowerCase();

					// Make sure the criteria type is supported, otherwise we might try to initialize a unknown widget.
					if(this.options.supported_criterion_types.indexOf(sType) < 0)
					{
						sType = this.options.default_criteria_type;
					}

					break;
				}
			}

			// Fallback for unknown widget types or unknown field refs
			if(sType === null)
			{
				sType = this.options.default_criteria_type;
			}

			return sType;
		},
		// - Find a criteria's widget name from a criteria's type
		_getCriteriaWidgetNameFromType: function(sType)
		{
			return 'search_form_criteria' + '_' + (($.itop['search_form_criteria_'+sType] !== undefined) ? sType : 'raw');
		},
		// Criteria handlers
		_onCriteriaOpening: function(oData)
		{
			this._closeMoreCriterion();
		},
		_onCriteriaValueChanged: function(oData)
		{
			this._updateSearch();
			this._submit();
		},
		_onCriteriaRemoved: function(oData)
		{
			this._updateSearch();
			this._submit();
		},

		// Field helpers
		_hasFieldDefinition: function(sRef)
		{
			var bFound = false;

			for(var sListIdx in this.options.search.fields)
			{
				if(this.options.search.fields[sListIdx][sRef] !== undefined)
				{
					bFound = true;
					break;
				}
			}

			return bFound;
		},
		_getFieldDefinition: function(sRef)
		{
			var oFieldDef = null;

			for(var sListIdx in this.options.search.fields)
			{
				if(this.options.search.fields[sListIdx][sRef] !== undefined)
				{
					oFieldDef = this.options.search.fields[sListIdx][sRef];
					break;
				}
			}

			return oFieldDef;
		},

		// Button handlers
		_onSubmitClick: function(oEvent)
		{
			// Assertion: the search is already up to date
			this._submit();
		},


		// Submit handlers
		// - External event callback
		_onSubmit: function()
		{
			this._submit();
		},
		// - Do the submit
		_submit: function()
		{
			var me = this;

			// Data
			// - Regular params
			var oData = {
				'params': JSON.stringify({
					'base_oql': this.options.search.base_oql,
					'criterion': this.options.search.criterion,
				}),
			};
			// - List params (pass through for the server), merge data_config with list_params if present.
			var oListParams = {};
			if(this.options.data_config_list_selector !== null)
			{
				var sExtraParams = $(this.options.data_config_list_selector).data('sExtraParams');
				if(sExtraParams !== undefined)
				{
					oListParams = JSON.parse(sExtraParams);
				}
			}
			$.extend(oListParams, this.options.list_params);
			oData.list_params = JSON.stringify(oListParams);

			// Show loader
			this._showLoader();

			// TODO: Make a throttle mecanism or cancel previous call when a newer is made.

			// Do submit
			$.post(
				this.options.endpoint,
				oData
			)
				.done(function(oResponse, sStatus, oXHR){ me._onSubmitSuccess(oResponse); })
				.fail(function(oResponse, sStatus, oXHR){ me._onSubmitFailure(oResponse); })
				.always(function(oResponse, sStatus, oXHR){ me._onSubmitAlways(oResponse); });
		},
		// - Called on form submit successes
		_onSubmitSuccess: function(oData)
		{
			this.elements.results_area.html(oData);
		},
		// - Called on form submit failures
		_onSubmitFailure: function(oData)
		{
			// TODO: onSubmitFailure callback. Show oData in a debug or error div.
		},
		// - Called after form submits
		_onSubmitAlways: function(oData)
		{
			this._hideLoader();
		},


		// Global helpers
		// - Show loader
		_showLoader: function()
		{
			// TODO: Show loader
			this._trace('Show loader');
		},
		// - Hide loader
		_hideLoader: function()
		{
			// TODO: Hide loader
			this._trace('Hide loader');
		},
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
			this._trace('Options', this.options);
		}
	});
});
