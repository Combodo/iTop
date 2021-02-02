/*
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

;
$(function()
{
	$.widget( 'itop.activity_panel',
		{
			// default options
			options:
			{
				datetime_format: null,
				datetimes_reformat_limit: 14,   // In days
			},
			css_classes:
			{
				is_expanded: 'ibo-is-expanded',
				is_opened: 'ibo-is-opened',
				is_closed: 'ibo-is-closed',
				is_active: 'ibo-is-active',
				is_visible: 'ibo-is-visible',
				is_hidden: 'ibo-is-hidden',
				is_draft: 'ibo-is-draft',
			},
			js_selectors:
			{
				panel_size_toggler: '[data-role="ibo-activity-panel--size-toggler"]',
				tab_toggler: '[data-role="ibo-activity-panel--tab-toggler"]',
				tab_title: '[data-role="ibo-activity-panel--tab-title"]',
				tab_toolbar: '[data-role="ibo-activity-panel--tab-toolbar"]',
				tab_toolbar_action: '[data-role="ibo-activity-panel--tab-toolbar-action"]',
				caselog_tab_open_all: '[data-role="ibo-activity-panel--caselog-open-all"]',
				caselog_tab_close_all: '[data-role="ibo-activity-panel--caselog-close-all"]',
				activity_filter: '[data-role="ibo-activity-panel--filter"]',
				activity_filter_options: '[data-role="ibo-activity-panel--filter-options"]',
				activity_filter_options_toggler: '[data-role="ibo-activity-panel--filter-options-toggler"]',
				activity_filter_option_input: '[data-role="ibo-activity-panel--filter-option-input"]',
				authors_count: '[data-role="ibo-activity-panel--tab-toolbar-info-authors-count"]',
				messages_count: '[data-role="ibo-activity-panel--tab-toolbar-info-messages-count"]',
				compose_button: '[data-role="ibo-activity-panel--add-caselog-entry-button"]',
				caselog_entry_form: '[data-role="ibo-caselog-entry-form"]',
				entry_group: '[data-role="ibo-activity-panel--entry-group"]',
				entry: '[data-role="ibo-activity-entry"]',
				entry_medallion: '[data-role="ibo-activity-entry--medallion"]',
				entry_main_information: '[data-role="ibo-activity-entry--main-information"]',
				entry_datetime: '[data-role="ibo-activity-entry--datetime"]',
				edits_entry_long_description: '[data-role="ibo-edits-entry--long-description"]',
				edits_entry_long_description_toggler: '[data-role="ibo-edits-entry--long-description-toggler"]',
			},
			enums: {
				tab_types: {
					caselog: 'caselog',
					activity: 'activity',
				},
				entry_types: {
					caselog: 'caselog',
					transition: 'transition',
					edits: 'edits',
				}
			},

			// the constructor
			_create: function()
			{
				this.element.addClass('ibo-activity-panel');
				this._bindEvents();
				this._UpdateMessagesCounters();
				this._UpdateFiltersCheckboxesFromOptions();
				this._ReformatDateTimes();

				// TODO 3.0.0: Modify PopoverMenu so we can pass it the ID of the block triggering the open/close
				//$(this.element).find(this.js_selectors.send_choices_picker).popover_menu({toggler: this.js_selectors.send_button});
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function()
			{
				this.element.removeClass('ibo-activity-panel');
			},
			_bindEvents: function()
			{
				const me = this;
				const oBodyElem = $('body');

				// Tabs title
				// - Click on the panel collapse/expand toggler
				this.element.find(this.js_selectors.panel_size_toggler).on('click', function(oEvent){
					me._onPanelSizeTogglerClick(oEvent);
				});
				// - Click on a tab title
				this.element.find(this.js_selectors.tab_title).on('click', function(oEvent){
					me._onTabTitleClick(oEvent, $(this));
				});

				// Tabs toolbar
				// - Change on a filter
				this.element.find(this.js_selectors.activity_filter).on('change', function(){
					me._onFilterChange($(this));
				});
				// - Click on a filter options toggler
				this.element.find(this.js_selectors.activity_filter_options_toggler).on('click', function(oEvent){
					me._onFilterOptionsTogglerClick(oEvent, $(this));
				})
				// - Change on a filter option
				this.element.find(this.js_selectors.activity_filter_option_input).on('change', function(){
					me._onFilterOptionChange($(this));
				});
				// - Click on open all case log messages
				this.element.find(this.js_selectors.caselog_tab_open_all).on('click', function(){
					me._onCaseLogOpenAllClick($(this));
				});
				// - Click on close all case log messages
				this.element.find(this.js_selectors.caselog_tab_close_all).on('click', function(){
					me._onCaseLogCloseAllClick($(this));
				});

				// Entry form
				// - Click on the compose button
				this.element.find(this.js_selectors.compose_button).on('click', function(oEvent){
					me._onComposeButtonClick(oEvent);
				});
				// - Draft value ongoing
				this.element.on('draft.caselog_entry_form.itop', function(oEvent, oData){
					me._onDraftEntryForm(oData.attribute_code);
				});
				// - Empty value
				this.element.on('emptied.caselog_entry_form.itop', function(oEvent, oData){
					me._onEmptyEntryForm(oData.attribute_code);
				});
				// - Entry form cancelled
				this.element.on('cancelled_form.caselog_entry_form.itop', function(){
					me._onCancelledEntryForm();
				});
				// - Entry form submission request
				this.element.on('request_submission.caselog_entry_form.itop', function(){
					me._onRequestSubmission();
				});

				// Entries
				// - Click on a closed case log message
				this.element.find(this.js_selectors.entry_group).on('click', '.'+this.css_classes.is_closed + ' ' + this.js_selectors.entry_main_information, function(oEvent){
					me._onCaseLogClosedMessageClick($(this).closest(me.js_selectors.entry));
				});
				// - Click on an edits entry's long description toggler
				this.element.find(this.js_selectors.edits_entry_long_description_toggler).on('click', function(oEvent){
					me._onEditsLongDescriptionTogglerClick(oEvent, $(this).closest(me.js_selectors.entry));
				});

				// Mostly for outside clicks that should close elements
				oBodyElem.on('click', function(oEvent){
					me._onBodyClick(oEvent);
				});
				// Mostly for hotkeys
				oBodyElem.on('keyup', function(oEvent){
					me._onBodyKeyUp(oEvent);
				});
			},

			// Events callbacks
			_onPanelSizeTogglerClick: function(oEvent)
			{
				// Avoid anchor glitch
				oEvent.preventDefault();

				// Toggle menu
				this.element.toggleClass(this.css_classes.is_expanded);
			},
			_onTabTitleClick: function(oEvent, oTabTitleElem)
			{
				// Avoid anchor glitch
				oEvent.preventDefault();

				const oTabTogglerElem = oTabTitleElem.closest(this.js_selectors.tab_toggler);
				const sTabType = oTabTogglerElem.attr('data-tab-type');

				// Show tab toggler
				this.element.find(this.js_selectors.tab_toggler).removeClass(this.css_classes.is_active);
				oTabTogglerElem.addClass(this.css_classes.is_active);

				// Show toolbar and entries
				this.element.find(this.js_selectors.tab_toolbar).removeClass(this.css_classes.is_active);
				if(sTabType === 'caselog')
				{
					const sCaselogAttCode = oTabTogglerElem.attr('data-caselog-attribute-code');

					this.element.find(this.js_selectors.tab_toolbar + '[data-tab-type="caselog"][data-caselog-attribute-code="' + sCaselogAttCode + '"]').addClass(this.css_classes.is_active);
					this._ShowCaseLogTab(sCaselogAttCode);
				}
				else
				{
					this.element.find(this.js_selectors.tab_toolbar + '[data-tab-type="activity"]').addClass(this.css_classes.is_active);
					this._ShowActivityTab();
				}
			},
			/**
			 * @param oInputElem {Object} jQuery object representing the filter's input
			 * @private
			 */
			_onFilterChange: function(oInputElem)
			{
				// Propagate on filter options
				if ('caselogs' === oInputElem.attr('name')) {
					oInputElem.closest(this.js_selectors.tab_toolbar_action).find(this.js_selectors.activity_filter_option_input).prop('checked', oInputElem.prop('checked'));
				}

				this._ApplyEntriesFilters();
			},
			/**
			 * @param oEvent {Object} jQuery event
			 * @param oElem {Object} jQuery object representing the filter's options toggler
			 * @private
			 */
			_onFilterOptionsTogglerClick: function(oEvent, oElem)
			{
				oEvent.preventDefault();

				this._ToggleFilterOptions(oElem.closest(this.js_selectors.tab_toolbar_action).find(this.js_selectors.activity_filter));
			},
			/**
			 * @param oInputElem {Object} jQuery object representing the filter option's input
			 * @private
			 */
			_onFilterOptionChange: function(oInputElem)
			{
				const oFilterOptionsElem = oInputElem.closest(this.js_selectors.activity_filter_options);
				const oFilterInputElem = oInputElem.closest(this.js_selectors.tab_toolbar_action).find(this.js_selectors.activity_filter);

				this._UpdateFiltersCheckboxesFromOptions();
				this._ApplyEntriesFilters();
			},
			_onCaseLogOpenAllClick: function(oIconElem)
			{
				const sCaseLogAttCode = oIconElem.closest(this.js_selectors.tab_toggler).attr('data-caselog-attribute-code');
				this._OpenAllMessages(sCaseLogAttCode);
			},
			_onCaseLogCloseAllClick: function(oIconElem)
			{
				const sCaseLogAttCode = oIconElem.closest(this.js_selectors.tab_toggler).attr('data-caselog-attribute-code');
				this._CloseAllMessages(sCaseLogAttCode);
			},
			/**
			 * @param oEvent {Object}
			 * @return {void}
			 * @private
			 */
			_onComposeButtonClick: function(oEvent)
			{
				oEvent.preventDefault();

				const oActiveTabData = this._GetActiveTabData();
				// If on a caselog tab, open its form
				if (this.enums.tab_types.caselog === oActiveTabData.type) {
					this._ShowCaseLogTab(oActiveTabData.att_code);
					this._ShowCaseLogsEntryForms();
				}
				// Else if on the activity tab, check which case log tab to go to
				else {
					// TODO 3.0.0: Make a tab popover menu selection
					console.log('TO IMPLEMENT');

					// If only 1 editbale case log, open this one
					// Else, open a popover menu to choose one
				}
			},
			/**
			 * @param sCaseLogAttCode {string} Attribute code of the case log entry form being draft
			 * @private
			 */
			_onDraftEntryForm: function(sCaseLogAttCode)
			{
				this.element.find(this.js_selectors.tab_toggler + '[data-tab-type="' + this.enums.tab_types.caselog + '"][data-caselog-attribute-code="' + sCaseLogAttCode + '"]').addClass(this.css_classes.is_draft);
			},
			/**
			 * @param sCaseLogAttCode {string} Attribute code of the case log entry form being emptied
			 * @private
			 */
			_onEmptyEntryForm: function(sCaseLogAttCode)
			{
				this.element.find(this.js_selectors.tab_toggler + '[data-tab-type="' + this.enums.tab_types.caselog + '"][data-caselog-attribute-code="' + sCaseLogAttCode + '"]').removeClass(this.css_classes.is_draft);
			},
			_onCancelledEntryForm: function()
			{
				this._HideCaseLogsEntryForms();
			},
			_onRequestSubmission: function()
			{
				// TODO 3.0.0
				// Retrieve current value from each entry form
				let oEntries = {};
				this.element.find(this.js_selectors.caselog_entry_form).each(function(){
					const oEntryFormElem = $(this);
					const sEntryFormValue = oEntryFormElem.triggerHandler('get_entry.caselog_entry_form.itop');

					if('' !== sEntryFormValue) {
						oEntries[oEntryFormElem.attr('data-attribute-code')] = sEntryFormValue;
					}
				});
				console.log(oEntries);
				// If several entry forms filled, show a confirmation message
				// Push data to the server
				// Put entries in the feed
				// Renew transaction ID for inline images

			},
			_onCaseLogClosedMessageClick: function(oEntryElem)
			{
				this._OpenMessage(oEntryElem);
			},
			_onEditsLongDescriptionTogglerClick: function(oEvent, oEntryElem)
			{
				// Avoid anchor glitch
				oEvent.preventDefault();

				oEntryElem.toggleClass(this.css_classes.is_opened);
			},
			/**
			 * Callback for mouse clicks that should interact with the activity panel (eg. Clic outside a dropdown should close it, ...)
			 *
			 * @param oEvent {Object} The jQuery event
			 * @private
			 */
			_onBodyClick: function(oEvent)
			{
				// Hide all filters' options only if click wasn't on one of them
				if(($(oEvent.target).closest(this.js_selectors.activity_filter_options_toggler).length === 0)
				&& $(oEvent.target).closest(this.js_selectors.activity_filter_options).length === 0) {
					this._HideAllFiltersOptions();
				}
			},
			/**
			 * Callback for key hits that should interact with the activity panel (eg. "Esc" to close all dropdowns, ...)
			 *
			 * @param oEvent {Object} The jQuery event
			 * @private
			 */
			_onBodyKeyUp: function(oEvent)
			{
				// On "Esc" key
				if(oEvent.key === 'Escape') {
					// Hide all filters's options
					this._HideAllFiltersOptions();
				}
			},

			// Methods
			// - Helpers on host object
			_GetHostObjectClass: function()
			{
				return this.element.attr('data-object-class');
			},
			_GetHostObjectID: function()
			{
				return this.element.attr('data-object-id');
			},

			// - Helpers on dates
			/**
			 * Reformat date times to be relative (only if they are not too far in the past)
			 * @private
			 */
			_ReformatDateTimes: function()
			{
				const me = this;

				this.element.find(this.js_selectors.entry_datetime).each(function(){
					const oEntryDateTime = moment($(this).text(), me.options.datetime_format);
					const oNowDateTime = moment();

					// Reformat date time only if it is not too far in the past (eg. "2 years ago" is not easy to interpret)
					const fDays = moment.duration(oNowDateTime.diff(oEntryDateTime)).asDays();
					if(fDays < me.options.datetimes_reformat_limit)
					{
						$(this).text( moment($(this).text(), me.options.datetime_format).fromNow() );
					}
				});
			},

			// - Helpers on tabs
			/**
			 * @returns {Object} Data on the active tab:
			 *
			 * - Its type
			 * - Optionally, its attribute code
			 * - Optionally, its rank
			 * @private
			 */
			_GetActiveTabData: function()
			{
				const oTabTogglerElem = this.element.find(this.js_selectors.tab_toggler + '.' + this.css_classes.is_active);

				// Consistency check
				if(oTabTogglerElem.length === 0) {
					throw 'No active tab, this should not be possible.';
				}

				const sTabType = oTabTogglerElem.attr('data-tab-type');
				let oTabData = {
					type: sTabType,
				};

				// Additional data for caselog tab
				if (this.enums.tab_types.caselog === sTabType) {
					oTabData.att_code = oTabTogglerElem.attr('data-caselog-attribute-code');
					oTabData.rank = oTabTogglerElem.attr('data-caselog-rank');
				}

				return oTabData;
			},
			/**
			 * @returns {Object} Active tab toolbar jQuery element
			 * @private
			 */
			_GetActiveTabToolbarElement: function()
			{
				const oActiveTabData = this._GetActiveTabData();
				let sSelector = this.js_selectors.tab_toolbar + '[data-tab-type="' + oActiveTabData.type + '"]';

				if (this.enums.tab_types.caselog === oActiveTabData.type) {
					sSelector += '[data-caselog-attribute-code="' + oActiveTabData.att_code + '"]';
				}

				return this.element.find(sSelector);
			},
			_ShowCaseLogTab: function(sCaseLogAttCode)
			{
				// Show only entries from this case log
				// this._HideAllEntries();
				//this.element.find(this.js_selectors.entry+'[data-entry-caselog-attribute-code="'+sCaseLogAttCode+'"]').removeClass(this.css_classes.is_hidden);
				this._ShowAllEntries();
				this._ApplyEntriesFilters();
			},
			_ShowActivityTab: function()
			{
				// Show all entries but regarding the current filters
				//this._OpenAllMessages();
				this._ShowAllEntries();
				this._ApplyEntriesFilters();
			},
			/**
			 * Show all case logs entry forms.
			 * Event is triggered on the corresponding elements.
			 *
			 * @return {void}
			 * @private
			 */
			_ShowCaseLogsEntryForms: function()
			{
				this.element.find(this.js_selectors.caselog_entry_form).trigger('show_form.caselog_entry_form.itop');
				this.element.find(this.js_selectors.compose_button).addClass(this.css_classes.is_hidden);
			},
			/**
			 * Hide all case logs entry forms.
			 * Event is triggered on the corresponding elements.
			 *
			 * @return {void}
			 * @private
			 */
			_HideCaseLogsEntryForms: function()
			{
				this.element.find(this.js_selectors.caselog_entry_form).trigger('hide_form.caselog_entry_form.itop');
				this.element.find(this.js_selectors.compose_button).removeClass(this.css_classes.is_hidden);

				// TODO 3.0.0: Release lock
			},
			GetCaseLogRank: function(sCaseLog)
			{
				let iIdx = 0;
				let oCaselogTab = this.element.find(this.js_selectors.tab_toggler +
					'[data-tab-type="caselog"]' +
					'[data-caselog-attribute-code="'+ sCaseLog +'"]'
				);
				if(oCaselogTab.length > 0 && oCaselogTab.attr('data-caselog-rank'))
				{
					iIdx = parseInt(oCaselogTab.attr('data-caselog-rank'));
				}
				return iIdx;
			},
			/**
			 * Update the main filters checkboxes depending on the state of their filter's options.
			 * The main goal is to have an "indeterminated" state.
			 *
			 * @return {void}
			 * @private
			 */
			_UpdateFiltersCheckboxesFromOptions: function()
			{
				const me = this;

				this.element.find(this.js_selectors.activity_filter_options).each(function(){
					const oFilterOptionsElem = $(this);
					const iTotalOptionsCount = oFilterOptionsElem.find(me.js_selectors.activity_filter_option_input).length;
					const iCheckedOptionsCount = oFilterOptionsElem.find(me.js_selectors.activity_filter_option_input + ':checked').length;

					let bChecked = false;
					let bIndeterminate = false;
					if (iCheckedOptionsCount === iTotalOptionsCount) {
						bChecked = true;
					}
					else if ((0 < iCheckedOptionsCount) && (iCheckedOptionsCount < iTotalOptionsCount)) {
						bIndeterminate = true;
					}

					oFilterOptionsElem.closest(me.js_selectors.tab_toolbar_action).find(me.js_selectors.activity_filter).prop({
						indeterminate: bIndeterminate,
						checked: bChecked
					});
				});
			},
			/**
			 * Show the oFilterElem's options
			 *
			 * @param oFilterElem {Object}
			 * @private
			 */
			_ShowFilterOptions: function(oFilterElem)
			{
				oFilterElem.parent().find(this.js_selectors.activity_filter_options_toggler).removeClass(this.css_classes.is_closed);
			},
			/**
			 * Hide the oFilterElem's options
			 *
			 * @param oFilterElem {Object}
			 * @private
			 */
			_HideFilterOptions: function(oFilterElem)
			{
				oFilterElem.parent().find(this.js_selectors.activity_filter_options_toggler).addClass(this.css_classes.is_closed);
			},
			/**
			 * Toggle the visibility of the oFilterElem's options
			 *
			 * @param oFilterElem {Object}
			 * @private
			 */
			_ToggleFilterOptions: function(oFilterElem)
			{
				oFilterElem.parent().find(this.js_selectors.activity_filter_options_toggler).toggleClass(this.css_classes.is_closed);
			},
			/**
			 * Hide all the filters' options from all toolbars
			 *
			 * @private
			 */
			_HideAllFiltersOptions: function()
			{
				const me = this;
				this.element.find(this.js_selectors.activity_filter_options_toggler).each(function(){
					me._HideFilterOptions($(this));
				});
			},

			// - Helpers on messages
			_OpenMessage: function(oEntryElem)
			{
				oEntryElem.removeClass(this.css_classes.is_closed);
			},
			_OpenAllMessages: function(sCaseLogAttCode = null)
			{
				this._SwitchAllMessages('open', sCaseLogAttCode);
			},
			_CloseAllMessages: function(sCaseLogAttCode = null)
			{
				this._SwitchAllMessages('close', sCaseLogAttCode);
			},
			_SwitchAllMessages: function(sMode, sCaseLogAttCode = null)
			{
				const sExtraSelector = (sCaseLogAttCode === null) ? '' : '[data-entry-caselog-attribute-code="' + sCaseLogAttCode+'"]';
				const sCallback = (sMode === 'open') ? 'removeClass' : 'addClass';

				this.element.find(this.js_selectors.entry + sExtraSelector)[sCallback](this.css_classes.is_closed);
			},
			/**
			 * Update the messages and users counters in the tabs toolbar
			 *
			 * @return {void}
			 * @private
			 */
			_UpdateMessagesCounters: function()
			{
				const me = this;
				let iMessagesCount = 0;
				let iUsersCount = 0;
				let oUsers = {};

				// Compute counts
				this.element.find(this.js_selectors.entry + ':visible').each(function(){
					// Increase messages count
					if (me.enums.entry_types.caselog === $(this).attr('data-entry-type')) {
						iMessagesCount++;
					}

					// Feed authors array so we can count them later
					try {
						oUsers[$(this).attr('data-entry-author-login')] = true;
					}
					catch (sError) {
						// Do nothing, this is just in case the user's login has special chars that would break the object key
					}
				});
				iUsersCount = Object.keys(oUsers).length;

				// Update elements
				this.element.find(this.js_selectors.messages_count).text(iMessagesCount);
				this.element.find(this.js_selectors.authors_count).text(iUsersCount);
			},

			// - Helpers on entries
			_ApplyEntriesFilters: function()
			{
				const me = this;

				// For each filter, show/hide corresponding entries
				this._GetActiveTabToolbarElement().find(this.js_selectors.activity_filter).each(function(){
					const aTargetEntryTypes = $(this).attr('data-target-entry-types').split(' ');
					const sCallbackMethod = ($(this).prop('checked')) ? '_ShowEntries' : '_HideEntries';

					let aFilterOptions = [];
					$(this).closest(me.js_selectors.tab_toolbar_action).find(me.js_selectors.activity_filter_option_input + ':checked').each(function(){
						aFilterOptions.push($(this).val());
					});

					for(let sTargetEntryType of aTargetEntryTypes)
					{
						me[sCallbackMethod](sTargetEntryType, aFilterOptions);
					}
				});

				// Show only the last visible entry's medallion of a group (cannot be done through CSS yet 😕)
				this.element.find(this.js_selectors.entry_group).each(function(){
					$(this).find(me.js_selectors.entry_medallion).removeClass(me.css_classes.is_visible);
					$(this).find(me.js_selectors.entry + ':visible:last').find(me.js_selectors.entry_medallion).addClass(me.css_classes.is_visible);
				});

				this._UpdateEntryGroupsVisibility();
				this._UpdateMessagesCounters();
			},
			_ShowAllEntries: function()
			{
				this.element.find(this.js_selectors.entry).removeClass(this.css_classes.is_hidden);
				this._UpdateEntryGroupsVisibility();
			},
			_HideAllEntries: function()
			{
				this.element.find(this.js_selectors.entry).addClass(this.css_classes.is_hidden);
				this._UpdateEntryGroupsVisibility();
			},
			/**
			 * Show entries of type sEntryType but do not hide the others
			 *
			 * @param sEntryType {string}
			 * @private
			 */
			_ShowEntries: function(sEntryType)
			{
				let sEntrySelector = this.js_selectors.entry+'[data-entry-type="'+sEntryType+'"]';

				// Note: Unlike, the _HideEntries() method, we don't have a special case for caselogs options. This is because this
				// method is called when the main filter is checked, which means that all options are checked as well, so there is no
				// need for a special treatment.

				this.element.find(sEntrySelector).removeClass(this.css_classes.is_hidden);
				this._UpdateEntryGroupsVisibility();
			},
			/**
			 * Hide entries of type sEntryType but do not hide the others
			 *
			 * @param sEntryType {string}
			 * @param aOptions {Array} Options for the sEntryType, used differently depending on the sEntryType
			 * @private
			 */
			_HideEntries: function(sEntryType, aOptions = [])
			{
				let sEntrySelector = this.js_selectors.entry+'[data-entry-type="'+sEntryType+'"]';

				// Special case for options
				if ((this.enums.entry_types.caselog === sEntryType) && (aOptions.length > 0)) {
					// Hide all caselogs...
					this._HideEntries(sEntryType);

					// ... except the selected
					for (let sCaseLogAttCode of aOptions) {
						this.element.find(sEntrySelector + '[data-entry-caselog-attribute-code="' + sCaseLogAttCode + '"]').removeClass(this.css_classes.is_hidden);
					}
				}
				// General case
				else {
					this.element.find(sEntrySelector).addClass(this.css_classes.is_hidden);
				}

				this._UpdateEntryGroupsVisibility();
			},
			_UpdateEntryGroupsVisibility: function()
			{
				const me = this;

				this.element.find(this.js_selectors.entry_group).each(function(){
					if($(this).find(me.js_selectors.entry + ':not(.' + me.css_classes.is_hidden + ')').length === 0)
					{
						$(this).addClass(me.css_classes.is_hidden);
					}
					else
					{
						$(this).removeClass(me.css_classes.is_hidden);
					}
				});
			},
			_GetNewEntryGroup: function()
			{
				let AjaxNewEntryGroupDeferred = jQuery.Deferred();
				const me = this;
				var oParams = {
					'operation' : 'new_entry_group',
					'caselog_new_entry': sData,
					'caselog_attcode' : sCaselog,
				}
				$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data){
					AjaxNewEntryGroupDeferred.resolve(data);
				});	
				return AjaxNewEntryGroupDeferred.promise();
			},
			AddEntry: function(sEntry, sOrigin)
			{
				let aEntryGroup = this.element.find(this.js_selectors.entry_group)
				let sAuthorLogin = $(sEntry).attr('data-entry-author-login');
				if (aEntryGroup.length > 0 && $(aEntryGroup[0]).attr('data-entry-group-author-login') === sAuthorLogin && $(aEntryGroup[0]).attr('data-entry-group-origin') === sOrigin)
				{
					$(aEntryGroup[0]).prepend(sEntry);
					this._ReformatDateTimes();
				}
				else
				{
					// TODO 3.0.0 Create a new entry group
					window.location.reload();
				}
			}
		});
});
