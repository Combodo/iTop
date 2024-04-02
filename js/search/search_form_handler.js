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
			'endpoint': null,
			'init_opened': false,
			/* Submit the search form automatically on criteria change */
			'auto_submit': true,
			/* Submit the search form when the page is first loaded */
			'submit_on_load': true,
			'show_obsolete_data': true,
			'search': {
				'base_oql': '',
				'class_name': null,
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
			'default_criteria_type': 'raw',
			'conf_parameters': {
				'min_autocomplete_chars': 2,
				'datepicker': {
					'dayNamesMin': ['Su','Mo','Tu','We','Th','Fr','Sa'],
					'monthNamesShort': ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
					'firstDay': 0,
				},
			},
		},

		// jQuery elements
		elements: null,

		// Submit properties (XHR, throttle, ...)
		submit: null,

		/** @var {ScrollMagic.Controller} SM controller for the sticky header */
		sticky_header_controller: null,

		// the constructor
		_create: function()
		{
			var me = this;

			this.element.addClass('search_form_handler');

			// Init properties (complexe type properties would be static if not initialized with a simple type variable...)
			this.elements = {
				message_area: null,
				criterion_area: null,
				more_criterion: null,
				submit_button: null,
				results_area: null,
			};
			this.submit = {
				xhr: null,
			};

			//init others widgets :
			this.element.search_form_handler_history({'itop_root_class': me.options.search.class_name});


			// Prepare DOM elements
			this._prepareFormArea();
			this._prepareCriterionArea();
			this._prepareResultsArea();
			// - Sticky header
			this._updateStickyHeaderHandler();

			// Binding events (eg. from search_form_criteria widgets)
			this._bindEvents();

			//memorize the initial state so on first criteria close, we do not trigger a refresh if nothing has changed
			this._updateSearch();
			this.oPreviousAjaxParams = JSON.stringify({
				'base_oql': this.options.search.base_oql,
				'criterion': this.options.search.criterion,
			});

			// If auto submit is enabled, also submit on first display
			if (this.options.auto_submit === true && this.options.submit_on_load === true) {
				this._submit();
			}

		},
		// called when created, and later when changing options
		_refresh: function()
		{
			this._updateStickyHeaderHandler();
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			// Remove ScrollMagic controller, typicaly useful when the search form is loaded to display one for another class
			if(this.sticky_header_controller !== null) {
				this.sticky_header_controller.destroy(true)
			}

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
			// - Search form has been reloaded by the page
			this.element.on('itop.search.form.reloaded', function(){
				if(me.options.auto_submit === true)
				{
					me._submit();
				}
			});

			// Criteria events
			this.element.on('itop.search.criteria.value_changed', function(oEvent, oData){
				me._onCriteriaValueChanged(oData);
			});
			this.element.on('itop.search.criteria.removed', function(oEvent, oData){
				me._onCriteriaRemoved(oData);
			});
			this.element.on('itop.search.criteria.error_occured', function(oEvent, oData){
				me._onCriteriaErrorOccured(oData);
			});

			$('body').on('update_history.itop', function(oEvent, oData) {

				if (me.element.parents('.ui-dialog').length !== 0)
				{
					//search form in modal are forbidden to update history!
					return;
				}

				var sNewUrl = GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=search';
				sNewUrl = sNewUrl + '&filter='+encodeURI(oData['filter']);
                sNewUrl = sNewUrl + '&c[menu]='+me._extractURLParameter(window.location.href, "c[menu]");
                sNewUrl = sNewUrl + '&c[org_id]='+me._extractURLParameter(window.location.href, "c[org_id]");
				if ('' != me._extractURLParameter(window.location.href, "debug"))
				{
					sNewUrl = sNewUrl + '&debug='+me._extractURLParameter(window.location.href, "debug");
				}

                if (typeof history.replaceState != "undefined")
				{
                    history.replaceState(null, null, sNewUrl);
				}


				$('#ibo-breadcrumbs')
					.breadcrumbs('destroy')
					.breadcrumbs({
					itop_instance_id: oData['breadcrumb_instance_id'],
					max_count: oData['breadcrumb_max_count'],
					new_entry: {
						"id": oData['breadcrumb_id'],
						"label": oData['breadcrumb_label'],
						"url": sNewUrl,
						'icon': oData['breadcrumb_icon'],
						'icon_type': oData['breadcrumb_icon_type'],
						'description': ''
					}
				});
			});

			// Refresh handler when the list has changed
			// - Initialization
			// - Destroy / reinitialization (changing the DM class of the search form)
			this.element.scrollParent().on('init.dt', function(oEvent) {
				me._updateStickyHeaderHandler();
			});
			// Refresh sticky positions when results are redrawn
			// - AJAX pagination, filtering
			// - Page length changes
			this.element.scrollParent().on('draw.dt column-sizing.dt', function(oEvent) {
				me._updateStickyPositions();
			});

			// Refresh handler when resising:
			// - The window
			// - The search form when the numerous criteria wrap on a new line
			if(window.ResizeObserver) {
				const oPanelRO = new ResizeObserver(function(){
					me._updateStickyPositions();
				});
				oPanelRO.observe(this.element[0]);
			}

		},
		// - Update search option of the widget
		_updateSearch: function()
		{
			var me = this;

			// Criterion
			var oCriterion = {
				'or': [{
					'and': []
				}]
			};
			// - Retrieve criterion
			var iCurrentCriterionRow = 0;
			this.elements.criterion_area.find('.sf_criterion_row').each(function (iDomCriterionRowIdx) {
				var isFirstRow = (iDomCriterionRowIdx === 0),
					oCriterionRowElem = $(this),
					oCriteriaRowCriterias = oCriterionRowElem.find('.search_form_criteria');

				if (oCriteriaRowCriterias.length === 0)
				{
					if (!isFirstRow)
					{
						$(this).remove();
					}
				}
				else
				{
					oCriteriaRowCriterias.each(function () {
						var oCriteriaData = $(this).triggerHandler('itop.search.criteria.get_data');

						if (null != oCriteriaData)
						{
							if (!oCriterion['or'][iCurrentCriterionRow])
							{
								oCriterion['or'][iCurrentCriterionRow] = {'and': []};
							}
							oCriterion['or'][iCurrentCriterionRow]['and'].push(oCriteriaData);
						}
						else
						{
							$(this).remove();
						}
					});
					iCurrentCriterionRow++;
				}
			});
			// - Update search
			this.options.search.criterion = oCriterion;

			// No need to update base OQL and fields
		},
		// - Open / Close more criterion menu
		_openMoreCriterion: function()
		{
			// Open more criterion menu
			// - Open it first
			this.elements.more_criterion.addClass('opened');
			// - Focus filter
			this.elements.more_criterion.find('.sf_filter:first input[type="text"]')
				.val('')
				.focus();
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
		// - Close all criterion
		_closeAllCriterion: function()
		{
			this.elements.criterion_area.find('.search_form_criteria.opened').each(function(){
				$(this).triggerHandler('itop.search.criteria.close');
			});
		},

		// DOM helpers
		// - Prepare form area
		_prepareFormArea: function()
		{
			var me = this;

			// Build DOM elements
			// - Autosubmit option
			if(this.options.auto_submit === false)
			{
				this.element.addClass('no_auto_submit');
			}
			// - Show obsolete data option
			if(this.options.show_obsolete_data === false)
			{
				this.element.addClass('hide_obsolete_data');
			}

			// - Message area
			this.elements.message_area = this.element.find('.sf_message');
			this._cleanMessageArea();

			// Events
			// - Refresh icon
			this.element.find('.sft_refresh').on('click', function(oEvent){
				// Prevent anchor
				oEvent.preventDefault();
				// Prevent form toggling
				oEvent.stopPropagation();

				me._submit();
			});
			// - Toggle icon
			this.element.find('.ibo-panel--header').on('click', function(oEvent){
				// Prevent anchors
				oEvent.preventDefault();

				// Prevent toggle on <select>
				if(oEvent.target.nodeName.toLowerCase() !== 'select' && oEvent.target.nodeName.toLowerCase() !== 'option')
				{
					me.element.find('ibo-panel--body').toggle();
				}
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
			oCriterionAreaElem.addClass('sf_criterion_area ibo-criterion-area');
			this.elements.criterion_area = oCriterionAreaElem;

			// Clean area
			oCriterionAreaElem
				.html('')
				.append('<div class="sf_criterion_row ibo-criterion-row"></div>');

			// Prepare content
			this._prepareExistingCriterion();
			this._prepareMoreCriterionMenu();
			this._prepareSubmitButton();
		},
		// - Prepare "more" button
		_prepareMoreCriterionMenu: function()
		{
			var me = this;

			// DOM
			this.elements.more_criterion = $('<div></div>')
				.addClass('sf_more_criterion')
				.appendTo(this.elements.criterion_area.find('.sf_criterion_row:first'));

			// Header part
			var oHeaderElem = $('<div class="sfm_header"></div>')
				.append('<a class="sfm_toggler" aria-label="' + Dict.S('UI:Search:Criterion:MoreMenu:AddCriteria') + '" data-tooltip-content="' + Dict.S('UI:Search:Criterion:MoreMenu:AddCriteria') + '" href="#"><span class="sfm_tg_title">' + Dict.S('UI:Search:Criterion:MoreMenu:AddCriteria') + '</span><span class="sfm_tg_icon fas fa-filter"><span class="sfm_tg_icon fas fa-plus fa-xs"></span></a>')
				.appendTo(this.elements.more_criterion);

			// Content part
			var oContentElem = $('<div class="sfm_content"></div>')
				.appendTo(this.elements.more_criterion);

			// - Filter
			var oFilterElem = $('<div></div>')
				.addClass('sf_filter')
				.addClass('sfm_filter')
				.append('<span class="sff_input_wrapper"><input type="text" placeholder="' + Dict.S('UI:Search:Value:Filter:Placeholder') + '" /><span class="sff_picto sff_filter fas fa-filter"></span><span class="sff_picto sff_reset fas fa-times"></span></span>')
				.appendTo(oContentElem);

			// - Lists container
			var oListsElem = $('<div></div>')
				.addClass('sfm_lists')
				.appendTo(oContentElem);

			// - Recently used list
			var oRecentsElem = $('<div></div>')
				.addClass('sf_list')
				.addClass('sf_list_recents')
				.appendTo(oListsElem);

			$('<div class="sfl_title"></div>')
				.text(Dict.S('UI:Search:AddCriteria:List:RecentlyUsed:Title'))
				.appendTo(oRecentsElem);

			var oRecentsItemsElem = $('<ul class="sfl_items"></ul>')
				.append('<li class="sfl_i_placeholder">' + Dict.S('UI:Search:AddCriteria:List:RecentlyUsed:Placeholder') + '</li>')
				.appendTo(oRecentsElem);

			me._refreshRecentlyUsed();

			// - Search zlist list
			var oZlistElem = $('<div></div>')
				.addClass('sf_list')
				.addClass('sf_list_zlist')
				.appendTo(oListsElem);

			$('<div class="sfl_title"></div>')
				.text(Dict.S('UI:Search:AddCriteria:List:MostPopular:Title'))
				.appendTo(oZlistElem);

			var oZListItemsElem = $('<ul class="sfl_items"></ul>')
				.appendTo(oZlistElem);

			for(var sFieldRef in this.options.search.fields.zlist)
			{
				var oFieldElem = me._getHtmlLiFromFieldRef(sFieldRef, ['zlist']);
				oFieldElem.appendTo(oZListItemsElem);
			}

			// - Remaining fields list
			if(this.options.search.fields.others !== undefined)
			{
				var oOthersElem = $('<div></div>')
					.addClass('sf_list')
					.addClass('sf_list_others')
					.appendTo(oListsElem);

				$('<div class="sfl_title"></div>')
					.text(Dict.S('UI:Search:AddCriteria:List:Others:Title'))
					.appendTo(oOthersElem);

				var oOthersItemsElem = $('<ul class="sfl_items"></ul>')
					.appendTo(oOthersElem);

				for(var sFieldRef in this.options.search.fields.others)
				{
					var oFieldElem = me._getHtmlLiFromFieldRef(sFieldRef, ['others']);
					oFieldElem.appendTo(oOthersItemsElem);
				}
			}

			// - Buttons
			var oButtonsElem = $('<div></div>')
				.addClass('sfm_buttons')
				.append('<button type="button" class="ibo-button ibo-is-regular ibo-is-neutral" name="apply">' + Dict.S('UI:Button:Apply') + '</button>')
				.append('<button type="button" class="ibo-button ibo-is-regular ibo-is-neutral" name="cancel">' + Dict.S('UI:Button:Cancel') + '</button>')
				.appendTo(oContentElem);

			// Bind events
			// - Close menu on click anywhere else
			// - Intercept click to avoid propagation (mostly used for closing it when clicking outside of it)
			$('body').on('click', function(oEvent){
				oEventTargetElem = $(oEvent.target);

				// If not more menu, close all criterion
				if(oEventTargetElem.closest('.sf_more_criterion').length > 0)
				{
					me._closeAllCriterion();
				}
				else
				{
					// TODO: Try to put this back in the date widget as it introduced a non necessary coupling.
					// If using the datetimepicker, do not close anything
					if (oEventTargetElem.closest('#ui-datepicker-div, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-current').length > 0 )
					{
						// No closing in this edge-case introduced by the use of css3's insertion on content using ::before and ::after that pop directly at the body instead of bubbling normally (and passing by their DOM parents)
					}
					// If criteria, close more menu & all criterion but me
					else if(oEventTargetElem.closest('.search_form_criteria').length > 0)
					{
						me._closeMoreCriterion();
						// All criterion but me is already handle by the criterion, no callback needed.
					}
					// If not criteria, close more menu & all criterion
					else
					{
						me._closeMoreCriterion();
						me._closeAllCriterion();
					}
				}
			});

			// - More criteria toggling
			this.elements.more_criterion.find('.sfm_header').on('click', function(oEvent){
				// Prevent anchor
				oEvent.preventDefault();

				me._toggleMoreCriterion();
			});

			// - Filter
			// Note: "keyup" event is use instead of "keydown", otherwise, the inpu value would not be set yet.
			oFilterElem.find('input').on('keyup focus', function(oEvent){
				// TODO: Move on values with up and down arrow keys; select with space or enter.
				// TODO: Hide list if no result on filter.

				var sFilter = $(this).val();

				// Show / hide items
				if(sFilter === '')
				{
					oListsElem.find('.sfl_items > li').show();
					oFilterElem.find('.sff_filter').show();
					oFilterElem.find('.sff_reset').hide();
				}
				else
				{
					oListsElem.find('.sfl_items > li:not(.sfl_i_placeholder)').each(function(){
						var oRegExp = new RegExp(sFilter.latinize(), 'ig');
						var sValue = $(this).find('input').val();
						var sLabel = $(this).text();

						// We don't check the sValue as it contains the class alias.
						if(sLabel.latinise().match(oRegExp) !== null)
						{
							$(this).show();
						}
						else
						{
							$(this).hide();
						}
					});
					oFilterElem.find('.sff_filter').hide();
					oFilterElem.find('.sff_reset').show();
				}

				// Show / hide lists with no visible items
				oListsElem.find('.sf_list').each(function(){
					$(this).show();
					if($(this).find('.sfl_items > li:visible').length === 0)
					{
						$(this).hide();
					}
				});
			});
			oFilterElem.find('.sff_filter').on('click', function(){
				oFilterElem.find('input').trigger('focus');
			});
			oFilterElem.find('.sff_reset').on('click', function(){
				oFilterElem.find('input')
					.val('')
					.trigger('focus');
			});

			// - Add one criteria
			this.elements.more_criterion.on('click', '.sfm_field', function(oEvent){
				// Prevent anchor
				oEvent.preventDefault();
				// Prevent propagation to not close the opening criteria
				oEvent.stopPropagation();

				// If no checkbox checked, add criteria right away, otherwise we "queue" it we other checkboxed.
				if(me.elements.more_criterion.find('.sfm_field input[type="checkbox"]:checked').length === 0)
				{
					var sFieldRef = $(this).attr('data-field-ref');

					// Prepare new criterion data (as already opened to increase UX)
					var oData = {
						'ref': sFieldRef,
						'init_opened': (oEvent.ctrlKey) ? false : true,
					};

					// Add criteria but don't submit form as the user has not specified the value yet.
					me.element.search_form_handler_history('setLatest', sFieldRef);
					me._refreshRecentlyUsed();
					me._addCriteria(oData);
				}
				else
				{
					$(this).find('input[type="checkbox"]').prop('checked', !$(this).find('input[type="checkbox"]').prop('checked'));
				}
			});

			// - Add several criterion
			this.elements.more_criterion.on('click', '.sfm_field input[type="checkbox"]', function(oEvent){
				// Prevent propagation to field and instant add of the criteria
				oEvent.stopPropagation();

				if(me.elements.more_criterion.find('.sfm_field input[type="checkbox"]:checked').length === 0)
				{
					oButtonsElem.hide();
				}
				else
				{
					oButtonsElem.show();
				}

				// Put focus back to filter to improve UX.
				oFilterElem.find('input').trigger('focus');
			});
			oButtonsElem.find('button').on('click', function(){
				// Add criterion on apply
				if($(this).attr('name') === 'apply')
				{
					me.elements.more_criterion.find('.sfm_field input[type="checkbox"]:checked').each(function(iIdx, oElem){
						var sFieldRef = $(oElem).closest('.sfm_field').attr('data-field-ref');
						var oData = {
							'ref': sFieldRef,
							'init_opened': false,
						};

						me.element.search_form_handler_history('setLatest', sFieldRef);
						me._addCriteria(oData);
					});

					me._refreshRecentlyUsed();
					me._closeMoreCriterion();
				}

				// Clear all
				// - Checkboxes
				me.elements.more_criterion.find('.sfm_field input[type="checkbox"]:checked').prop('checked', false);
				// - Filter
				oFilterElem.find('input')
					.val('')
					.trigger('focus');

				// Hide buttons
				oButtonsElem.hide();
			});
		},
		// - Prepare "submit" button
		_prepareSubmitButton: function()
		{
			var me = this;

			// DOM
			this.elements.submit_button = $('<div></div>')
				.addClass('sf_button')
				.addClass('sf_submit')
				.appendTo(this.elements.criterion_area.find('.sf_criterion_row:first'));

			var sButtonText = (this.options.auto_submit === true) ? Dict.S('UI:Button:Refresh') : Dict.S('UI:Button:Search');
			var sButtonIcon = (this.options.auto_submit === true) ? 'fas fa-sync-alt' : 'fas fa-search';
			var oButtonElem = $('<div class="sfb_header"></div>')
				.append('<a aria-label="' + sButtonText + '" data-tooltip-content="' + sButtonText + '" href="#"><span class="fa-fw ' + sButtonIcon + '"></span></a>')
				.appendTo(this.elements.submit_button);

			// Bind events
			// - Add one criteria
			this.elements.submit_button.on('click', function(oEvent){
				// Prevent anchor
				oEvent.preventDefault();

				me._onSubmitClick();
			});
		},
		// - Prepare existing criterion
		_prepareExistingCriterion: function()
		{
			// - OR conditions
			var iORCount = 0;
			var aORs = (this.options.search.criterion['or'] !== undefined) ? this.options.search.criterion['or'] : [];
			for(var iORIdx in aORs)
			{
				if(this.elements.criterion_area.find('.sf_criterion_row:nth-of-type(' + (iORCount+1) + ')').length > 0)
				{
					var oCriterionRowElem = this.elements.criterion_area.find('.sf_criterion_row:nth-of-type(' + (iORCount+1) + ')');
				}
				else
				{
					var oCriterionRowElem = $('<div></div>')
						.addClass('sf_criterion_row ibo-criterion-row')
						.appendTo(this.elements.criterion_area);
				}

				if(oCriterionRowElem.find('.sf_criterion_group').length > 0)
				{
					var oCriterionGroupElem = oCriterionRowElem.find('.sf_criterion_group');
				}
				else
				{
					var oCriterionGroupElem = $('<div></div>')
						.addClass('sf_criterion_group ibo-criterion-group')
						.appendTo(oCriterionRowElem);
				}

				var aANDs = (aORs[iORIdx]['and'] !== undefined) ? aORs[iORIdx]['and'] : [];
                var aANDsStringified = [];//used in order to deduplicate the crterions


				for(var iANDIdx in aANDs)
				{
					var oCriteriaData = aANDs[iANDIdx];

					var sCriteriaData = JSON.stringify(oCriteriaData);

					if (aANDsStringified.indexOf(sCriteriaData) == -1)
					{
                        aANDsStringified.push(sCriteriaData);
                        this._addCriteria(oCriteriaData, oCriterionGroupElem);
					}
				}

				iORCount++;
			}
		},
		// - Prepare results area
		_prepareResultsArea: function()
		{
			var me = this;

			var oResultAreaElem;

			// Build area element
			if(this.options.result_list_outer_selector !== null && $(this.options.result_list_outer_selector).length > 0)
			{
				oResultAreaElem = $(this.options.result_list_outer_selector);
			}
			else
			{
				// Reusing previously created DOM element
				if(this.element.closest('.display_block').parent().find('.sf_results_area').length > 0)
				{
					oResultAreaElem = this.element.closest('.display_block').parent().find('.sf_results_area');
				}
				else
				{
					oResultAreaElem = $('<div class="display_block sf_results_area" data-target="search_results"></div>').insertAfter(this.element.closest('.display_block'));
				}
			}
			// Make placeholder if nothing yet
			if(oResultAreaElem.html() === '')
			{
				oResultAreaElem.html('<div class="sf_results_placeholder"><p>' + Dict.S('UI:Search:NoAutoSubmit:ExplainText') + '</p><p><button type="button" class="ibo-button ibo-is-primary ibo-is-regular"><span class="fas fa-search"></span>' + Dict.S('UI:Button:Search') + '</button></p></div>');
				oResultAreaElem.find('button').on('click', function(){
					// TODO: Bug: Open "Search for CI", change child classe in the dropdown, click the search button. It submit the search for the original child classe, not the current one; whereas a click on the upper right pictogram does. This might be due to the form reloading.
					me._onSubmitClick();
				});

				if (me.element.find('.search_form_criteria').length == 0)
				{
					me.elements.more_criterion.find('.sfm_header').trigger('click');
				}
			}

			this.elements.results_area = oResultAreaElem;
		},

		/**
		 *  "add new criteria" <li /> markup
		 *   - with checkbox, label, data-* ...
		 *   - without event binding
		 *
		 * @private
		 *
		 * @param sFieldRef
		 * @param aFieldCollectionsEligible
		 *
		 * @return jQuery detached <li />
		 */
		_getHtmlLiFromFieldRef: function(sFieldRef, aFieldCollectionsEligible) {
			var me = this;
			var oFieldElem = undefined;

			aFieldCollectionsEligible.forEach(function (sFieldCollection)
			{
				if (typeof me.options.search.fields[sFieldCollection][sFieldRef] == 'undefined')
				{
					return true;//if this field is not present in the Collection, let's try the next
				}

				var oField = me.options.search.fields[sFieldCollection][sFieldRef];
				var sFieldTitleAttr = (oField.description !== undefined) ? 'title="' + oField.description + '"' : '';
				oFieldElem = $('<li></li>')
					.addClass('sfm_field')
					.attr('data-field-ref', sFieldRef)
					.append('<label ' + sFieldTitleAttr + '><input type="checkbox" value="' + sFieldRef + '" />' + oField.label + '</label>')
			});

			if (undefined == oFieldElem)
			{
				this._trace('No sFieldRef "' + sFieldRef + '" in given collections', {"aFieldCollectionsEligible":aFieldCollectionsEligible});
				return $('<!-- no sFieldRef in given collection -->');
			}

			return oFieldElem;
		},


		// Criteria helpers
		// - Add a criteria to the form
		_addCriteria: function(oData, oCriterionGroupElem)
		{
			var sRef = oData.ref;
			var sType = sType = (oData.widget !== undefined) ? oData.widget : this._getCriteriaTypeFromFieldRef(sRef);

			// Force to raw for non removable criteria
			if( (oData.is_removable !== undefined) && (oData.is_removable === false) )
			{
				sType = 'raw';
			}

			// Add to first OR condition if not specified
			if(oCriterionGroupElem === undefined)
			{
				oCriterionGroupElem = this.elements.criterion_area.find('.sf_criterion_row:first .sf_criterion_group');
			}

			// Protection against bad initialization data
			if(sType === null)
			{
				this._trace('Could not add criteria as we could not retrieve type for ref "'+sRef+'".');
				return false;
			}

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
                    has_index: oFieldDef.has_index,
					target_class: oFieldDef.target_class,
					widget: oFieldDef.widget,
					allowed_values: oFieldDef.allowed_values,
					is_null_allowed: oFieldDef.is_null_allowed,
				};
			}

			// Add widget specific data
			if( (sType === 'date') || (sType === 'date_time') )
			{
				oData.datepicker = this.options.conf_parameters.datepicker;
			}
			if( (sType === 'enum') || (sType === 'external_key') )
			{
				oData.autocomplete = {
					'min_autocomplete_chars': this.options.conf_parameters.min_autocomplete_chars,
				};
			}

			// Create DOM element
			var oCriteriaElem = $('<div></div>')
				.addClass('sf_criteria')
				.appendTo(oCriterionGroupElem);

			// Instanciate widget
			$.itop[sWidgetName](oData, oCriteriaElem);

			return true;
		},
		// - Find a criteria's type from a field's ref (usually <CLASS_ALIAS>.<ATT_CODE>)
		_getCriteriaTypeFromFieldRef: function(sRef)
		{
			// Fallback for unknown widget types or unknown field refs
			var sType = this.options.default_criteria_type;

			for(var sListIdx in this.options.search.fields)
			{
				if(this.options.search.fields[sListIdx][sRef] !== undefined)
				{
					sType = this.options.search.fields[sListIdx][sRef].widget.toLowerCase();
					break;
				}
			}

			return sType;
		},
		// - Find a criteria's widget name from a criteria's type
		_getCriteriaWidgetNameFromType: function(sType)
		{
			return 'search_form_criteria' + '_' + (($.itop['search_form_criteria_'+sType] !== undefined) ? sType : 'raw');
		},
		// Criteria handlers
		_onCriteriaValueChanged: function(oData)
		{
			this._updateSearch();
			if(this.options.auto_submit === true)
			{
				this._submit(true);
			}
		},
		_onCriteriaRemoved: function(oData)
		{
			this._updateSearch();
            if( (this.options.auto_submit === true) && (oData.had_values === true) )
            {
                this._submit();
            }
		},
		_onCriteriaErrorOccured: function(oData)
		{
			this._setErrorMessage(oData);
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

		// Message helper
		_cleanMessageArea: function()
		{
			this.elements.message_area
				.hide()
				.html('')
				.removeClass('message_error');
		},
		_setErrorMessage: function(sMessage)
		{
			this.elements.message_area
				.addClass('message_error')
				.html(sMessage)
				.show();
		},

		// Button handlers
		_onSubmitClick: function(oEvent)
		{

            //if there is an opened criteria let's get it's new value before processing
            if (this.elements.criterion_area.find('.sf_criteria.opened').length > 0)
            {
                this.elements.criterion_area.find('.sf_criteria.opened').trigger('itop.search.criteria.close');
                setTimeout(this._submit.call(this), 300);
            }
            else
            {
                this._submit();
			}
		},


		// Submit handlers
		// - External event callback
		_onSubmit: function()
		{
			this._submit();
		},
		// - Do the submit
		_submit: function(bAbortIfNoChange)
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
            if (me.element.parents('.ui-dialog').length !== 0)
            {
                oListParams.update_history = false;
            }
			oData.list_params = JSON.stringify(oListParams);

			if (true === bAbortIfNoChange)
			{
				if (typeof me.oPreviousAjaxParams == "undefined")
				{
                    me.oPreviousAjaxParams = oData.params;
					return;
				}

                if (me.oPreviousAjaxParams == oData.params)
                {
                    return;
                }
			}
            me.oPreviousAjaxParams = oData.params;

			// Abort pending request
			if(this.submit.xhr !== null)
			{
				this.submit.xhr.abort();
			}

			// Remove sticky state as we want to return at the beginning of the results
			this._exitStickyState();
			// Show loader
			this._showLoader();
			this._cleanMessageArea();

			// Do submit
			this.submit.xhr = $.post(
				this.options.endpoint,
				oData
			)
				.done(function(oResponse, sStatus, oXHR){ me._onSubmitSuccess(oResponse); })
				.fail(function(oResponse, sStatus, oXHR){ me._onSubmitFailure(oResponse, sStatus); })
				.always(function(oResponse, sStatus, oXHR){ me._onSubmitAlways(oResponse); });
		},
		// - Called on form submit successes
		_onSubmitSuccess: function(oData)
		{
			this.elements.results_area.html(oData);
		},
		// - Called on form submit failures
		_onSubmitFailure: function(oData, sStatus)
		{
			if(sStatus === 'abort')
			{
				return false;
			}

			// Fallback message in case the server send back only HTML markup.
			var oErrorElem = $(oData.responseText);
			var sErrorMessage = (oErrorElem.text() !== '') ? oErrorElem.text() : Dict.Format('Error:XHR:Fail', '');

			this._setErrorMessage(sErrorMessage);
		},
		// - Called after form submits
		_onSubmitAlways: function(oData)
		{
			this._hideLoader();
		},

		//---------------------------
		// Sticky header helpers
		//---------------------------
		/**
		 * Main function for the sticky header, it creates or recreates all the mechanism (viewport, trigger points, sizing/positioning of fixed elements)
		 * Must be called whenever the reference elements (viewport, trigger elements, ...) change to ensure that everything is well positioned.
		 *
		 * @return {void}
		 * @private
		 */
		_updateStickyHeaderHandler: function ()
		{
			const me = this;

			// Update the reference viewport
			this.options.viewport_elem = this.element.scrollParent()[0];

			// Clean SM controller if there was already one
			if (null !== this.sticky_header_controller) {
				this.sticky_header_controller.destroy(true);
			}

			// Prepare SM controller
			this.sticky_header_controller = new ScrollMagic.Controller({
				container: this.options.viewport_elem,
			});

			this._addScrollSceneForForm();
			this._addScrollSceneForResults();
		},
		/**
		 * Observe when the search form reaches/leaves the viewport's top
		 * @private
		 */
		_addScrollSceneForForm: function()
		{
			const me = this;
			const oFormPanelHeaderElem = this._getFormPanelHeaderElem();

			new ScrollMagic.Scene({
				triggerElement: oFormPanelHeaderElem[0],
				triggerHook: 0,
				offset: oFormPanelHeaderElem.outerHeight(),
			})
				.on('enter', function () {
					me._onFormBecomesSticky();
				})
				.on('leave', function () {
					me._onFormStopsBeingSticky();
				})
				.addTo(this.sticky_header_controller);
		},
		/**
		 * Callback for the form element SM Scene
		 * @return {void}
		 * @private
		 */
		_onFormBecomesSticky: function()
		{
			this._getFormPanelBodyElem().addClass('ibo-is-sticking');
			this._updateStickyPositions();
		},
		/**
		 * Callback for the form element SM Scene
		 * @return {void}
		 * @private
		 */
		_onFormStopsBeingSticky: function()
		{
			this._getFormPanelBodyElem().removeClass('ibo-is-sticking');
			this._updateStickyPositions();
		},
		/**
		 * Observer when the results' top toolbar reaches/leaves the search form's bottom
		 * @private
		 */
		_addScrollSceneForResults: function()
		{
			const me = this;
			const oFormPanelHeaderElem = this._getFormPanelHeaderElem();
			const oResultsPanelBodyElem = this._getResultsPanelElem().find('.ibo-panel--body:first');

			// Ensure result body panel has been created
			if (oResultsPanelBodyElem.length === 0) {
				return;
			}

			// Note: As offset() starts from the very top of the window, we need to take into account the top container height!
			let fOffset = oResultsPanelBodyElem.offset().top - $('#ibo-top-container').outerHeight() - this._getFormPanelBodyElem().outerHeight();
			if (this._isInAModal()) {
				fOffset = fOffset - this.element.closest('[role="dialog"]').offset().top;
			}

			new ScrollMagic.Scene({
				triggerElement: oFormPanelHeaderElem[0],
				triggerHook: 0,
				// Careful, this won't get updated dynamically, meaning that if the elements move or resize, it won't be exact anymore
				offset: fOffset,
			})
				.on('enter', function () {
					me._onResultsBecomesSticky();
				})
				.on('leave', function () {
					me._onResultsStopsBeingSticky();
				})
				.addTo(this.sticky_header_controller);
		},
		/**
		 * Callback for the results element SM Scene
		 * @return {void}
		 * @private
		 */
		_onResultsBecomesSticky: function()
		{
			this._getResultsPanelElem().addClass('ibo-is-sticking');
			this._getResultsToolbarTopElem().addClass('ibo-is-sticking');
			this._getResultsTableHeaders().addClass('ibo-is-sticking');
			this._updateStickyPositions();
		},
		/**
		 * Callback for the results element SM Scene
		 * @return {void}
		 * @private
		 */
		_onResultsStopsBeingSticky: function()
		{
			this._getResultsPanelElem().removeClass('ibo-is-sticking');
			this._getResultsToolbarTopElem().removeClass('ibo-is-sticking');
			this._getResultsTableHeaders().removeClass('ibo-is-sticking');
			this._updateStickyPositions();
		},
		/**
		 * Update all the concerned elements position / size
		 *
		 * @param bImmediate {boolean} Set to true if the update of the positions should have a small delay. This can be useful when ahving CSS transitions that needs to be done before computing positions.
		 * @private
		 */
		_updateStickyPositions: function(bImmediate = true)
		{
			const me = this;

			if(!bImmediate) {
				setTimeout(function() {
					me._updateStickyPositions(true);
				}, 300);
				return;
			}

			// Update the sticky elements positions
			this._updateFormPosition();
			this._updateResultsToolbarTopPosition();
			this._updateResultsTableHeadersPosition();

			// Update the scrolling element's top padding to avoid having a visual glitch when the results panel elements becomes sticky and changes the result table vertical position
			// Note: The initial "-8" offset is there because we don't know yet how to retrieve the results panel body :before height, therefore this will not work well with custom themes... 😕
			const iInitialOffset = -8;
			let iResultsPanelOffset = iInitialOffset;
			const aStickableElems = [
				this._getFormPanelBodyElem(),
				this._getResultsToolbarTopElem(),
				this._getResultsTableHeaders()
			];
			for(let oElem of aStickableElems){
				if(oElem.hasClass('ibo-is-sticking')){
					iResultsPanelOffset += parseInt(oElem.outerHeight() + parseInt(oElem.css('margin-top')) + parseInt(oElem.css('margin-bottom')));
				}
			}

			// If computed offset is the same as the initial, we should reset the padding.
			if(iInitialOffset === iResultsPanelOffset) {
				this._getResultsPanelElem().css('padding-top', '');
			} else {
				this._getResultsPanelElem().css('padding-top', iResultsPanelOffset);
			}
		},
		/**
		 * Update only the search form position
		 * @private
		 */
		_updateFormPosition: function()
		{
			const oFormPanelBodyElem = this._getFormPanelBodyElem();
			if(this._isElementSticking(oFormPanelBodyElem)) {
				const oFormPanelElem = this._getFormPanelElem();
				oFormPanelBodyElem.css({
					'top': $(this.options.viewport_elem).offset()?.top, // N°7402 - In case viewport is the document, offset() will return undefined
					'left': oFormPanelElem.offset().left,
					'width': oFormPanelElem.outerWidth(),
				});
			} else {
				oFormPanelBodyElem.css({
					'top': '',
					'left': '',
					'width': '',
				});
			}
		},
		/**
		 * Update only the results top toolbar position
		 * @private
		 */
		_updateResultsToolbarTopPosition: function()
		{
			if(this._isElementSticking(this._getResultsToolbarTopElem())){
				const oFormPanelBodyElem = this._getFormPanelBodyElem();

				this._getResultsToolbarTopElem().css({
						'top': oFormPanelBodyElem.offset().top + oFormPanelBodyElem.outerHeight(),
						'left': oFormPanelBodyElem.offset().left,
						'width': oFormPanelBodyElem.outerWidth(),
						'padding-top': this._getResultsToolbarTopElem().css('margin-top'),
						'padding-bottom': this._getResultsToolbarTopElem().css('margin-bottom'),
					});
			}
			else {
				this._getResultsToolbarTopElem().css({
						'top': '',
						'left': '',
						'width': '',
						'padding-top': '',
						'padding-bottom': '',
					});
			}
		},
		/**
		 * Update only the results table headers position
		 * @private
		 */
		_updateResultsTableHeadersPosition: function()
		{
			if(this._isElementSticking(this._getResultsTableHeaders())){
				const oFormPanelElem = this._getFormPanelElem();
				const oResultsToolbarTopElem = this._getResultsToolbarTopElem();

				this._getResultsTableHeaders().css({
						'top': oResultsToolbarTopElem.offset().top + oResultsToolbarTopElem.outerHeight(),
						'left': oFormPanelElem.offset().left,
						'width': oFormPanelElem.outerWidth(),
						'padding-top': this._getResultsTableHeaders().css('margin-top'),
						'padding-bottom': this._getResultsTableHeaders().css('margin-bottom'),
					});
			}else{
				this._getResultsTableHeaders().css({
						'top': '',
						'left': '',
						'width': '',
						'padding-top': '',
						'padding-bottom': '',
					});
			}
		},
		/**
		 * Exit the sticky state for the whole search, returning to the top of the results
		 * @return {void}
		 */
		_exitStickyState: function()
		{
			this._onFormStopsBeingSticky();
			this._onResultsStopsBeingSticky();
			this.element.scrollParent().scrollTop();
		},
		/**
		 * @param oElem {Object} jQuery object representing the element to test
		 * @return {boolean} True if oElem is currently sticking
		 * @private
		 */
		_isElementSticking: function(oElem)
		{
			return oElem.closest('.ibo-is-sticking').length > 0;
		},
		/**
		 * @return {Object} The jQuery object representing the search form panel element (where the criteria are)
		 * @private
		 */
		_getFormPanelElem: function()
		{
			return this.element.closest('.ibo-search-form-panel');
		},
		/**
		 * @return {null|Object} The jQuery object representing the header of the search form panel; or null if none found
		 * @private
		 */
		_getFormPanelHeaderElem: function()
		{
			const oFormPanelElem =  this._getFormPanelElem();
			if(oFormPanelElem.length === 0){
				return null;
			}

			return oFormPanelElem.find('[data-role="ibo-panel--header"]:first');
		},
		/**
		 * @return {null|Object} The jQuery object representing the body of the search form panel; or null if none found
		 * @private
		 */
		_getFormPanelBodyElem: function()
		{
			const oFormPanelElem =  this._getFormPanelElem();
			if(oFormPanelElem.length === 0){
				return null;
			}

			return oFormPanelElem.find('[data-role="ibo-panel--body"]:first');
		},
		/**
		 * @return {Object} The jQuery object representing the complete results panel
		 * @private
		 */
		_getResultsPanelElem: function()
		{
			return this.elements.results_area === null ? null : this.elements.results_area.find('[data-role="ibo-panel"]:first')
		},
		/**
		 * @return {Object} The jQuery object representing the top toolbar of the results (pagination, ...)
		 * @private
		 */
		_getResultsToolbarTopElem: function()
		{
			return this.elements.results_area === null ? null : this.elements.results_area.find('.ibo-datatable--toolbar:first');
		},
		/**
		 * @return {Object} The jQuery object representing the columns headers of the results
		 * @private
		 */
		_getResultsTableHeaders: function()
		{
			return this.elements.results_area === null ? null : this.elements.results_area.find('.dataTables_scrollHead:first');
		},


		//---------------------------
		// Global helpers
		//---------------------------
		_refreshRecentlyUsed: function()
		{
			me = this;

			var aHistory = me.element.search_form_handler_history("getHistory");
			var oRecentsItemsElem = me.element.find('.sf_list_recents .sfl_items');

			if (aHistory.length == 0)
			{
				return;
			}
			oRecentsItemsElem.empty();


			aHistory.forEach(function(sFieldRef) {
				var oFieldElem = me._getHtmlLiFromFieldRef(sFieldRef, ['zlist', 'others']);
				oRecentsItemsElem.append(oFieldElem);
			});

		},
		// - Show loader
		_showLoader: function()
		{
			this.elements.results_area.block();
		},
		// - Hide loader
		_hideLoader: function()
		{
			this.elements.results_area.unblock();
		},
		/**
		 * @return {boolean} True if the search form is in a modal window
		 * @private
		 * @since 3.0.0
		 */
		_isInAModal: function()
		{
			return this.element.closest('[role="dialog"]').length > 0;
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
		// - Extract sParameter from sUrl
		_extractURLParameter: function(sUrl, sParameter) {
			//prefer to use l.search if you have a location/link object
			var urlparts= sUrl.split('?');
			if (urlparts.length>=2) {

				var prefix = [
					sParameter+'=',
					encodeURIComponent(sParameter)+'='
				];
				var pars = urlparts[1].split(/[&;]/g);

				for (var i = 0; i < pars.length; i++) {
					for (var j = 0; j < prefix.length; j++) {
						var pos = pars[i].lastIndexOf(prefix[j], 0);
						if (pos !== -1) {
							return pars[i].substring(pos + prefix[j].length);
						}
					}
				}
			}
			return '';
		},


		//---------------------------
		// Debug helpers
		//---------------------------
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
