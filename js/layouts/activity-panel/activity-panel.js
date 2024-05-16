/*
 * Copyright (C) 2013-2024 Combodo SAS
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
					datetimes_reformat_limit: 7,    // In days
					transaction_id: null,           // Null until the user gets the lock on the object
					lock_enabled: false,            // Should only be true when object mode is set to "view" and the "concurrent_lock_enabled" config. param. enabled
					lock_status: null,
					lock_token: null,
					lock_watcher_period: 30,        // Period (in seconds) between lock status update, uses the "activity_panel.lock_watcher_period" config. param.
					lock_endpoint: null,
					show_multiple_entries_submit_confirmation: true,
					save_state_endpoint: null,
					last_loaded_entries_ids: {},
					load_more_entries_endpoint: null,
				},
			css_classes:
				{
					is_expanded: 'ibo-is-expanded',
					is_reduced: 'ibo-is-reduced',
					is_opened: 'ibo-is-opened',
					is_closed: 'ibo-is-closed',
					is_active: 'ibo-is-active',
					is_visible: 'ibo-is-visible',
					is_hidden: 'ibo-is-hidden',
					is_draft: 'ibo-is-draft',
					is_current_user: 'ibo-is-current-user',
				},
			js_selectors:
				{
					panel_togglers: '[data-role="ibo-activity-panel--togglers"]',
					panel_size_expand: '[data-role="ibo-activity-panel--expand-icon"]',
					panel_size_reduce: '[data-role="ibo-activity-panel--reduce-icon"]',
					panel_size_close: '[data-role="ibo-activity-panel--close-icon"]',
					panel_size_open: '[data-role="ibo-activity-panel--closed-cover"]',
					tab_toggler: '[data-role="ibo-activity-panel--tab-toggler"]',
					tab_title: '[data-role="ibo-activity-panel--tab-title"]',
					tabs_toolbars: '[data-role="ibo-activity-panel--tabs-toolbars"]',
					tab_toolbar: '[data-role="ibo-activity-panel--tab-toolbar"]',
					tab_toolbar_action: '[data-role="ibo-activity-panel--tab-toolbar-action"]',
					lock_hint: '[data-role="ibo-caselog-entry-form--lock-indicator"]',
					lock_message: '[data-role="ibo-caselog-entry-form--lock-message"]',
					caselog_tab_open_all: '[data-role="ibo-activity-panel--caselog-open-all"]',
					caselog_tab_close_all: '[data-role="ibo-activity-panel--caselog-close-all"]',
					activity_filter: '[data-role="ibo-activity-panel--filter"]',
					activity_filter_options: '[data-role="ibo-activity-panel--filter-options"]',
					activity_filter_options_toggler: '[data-role="ibo-activity-panel--filter-options-toggler"]',
					activity_filter_option_input: '[data-role="ibo-activity-panel--filter-option-input"]',
					authors_count: '[data-role="ibo-activity-panel--tab-toolbar-info-authors-count"]',
					messages_count: '[data-role="ibo-activity-panel--tab-toolbar-info-messages-count"]',
					compose_button: '[data-role="ibo-activity-panel--add-caselog-entry-button"]',
					compose_menu: '#ibo-activity-panel--compose-menu',
					compose_menu_item: '#ibo-activity-panel--compose-menu [data-role="ibo-popover-menu--item"]',
					caselog_entry_form: '[data-role="ibo-caselog-entry-form"]',
					caselog_entry_forms_confirmation_dialog: '[data-role="ibo-activity-panel--entry-forms-confirmation-dialog"]',
					caselog_entry_forms_confirmation_preference_input: '[data-role="ibo-activity-panel--entry-forms-confirmation-preference-input"]',
					body: '[data-role="ibo-activity-panel--body"]',
					entry_group: '[data-role="ibo-activity-panel--entry-group"]',
					entry: '[data-role="ibo-activity-entry"]',
					entry_medallion: '[data-role="ibo-activity-entry--medallion"]',
					entry_main_information: '[data-role="ibo-activity-entry--main-information"]',
					entry_author_name: '[data-role="ibo-activity-entry--author-name"]',
					entry_datetime: '[data-role="ibo-activity-entry--datetime"]',
					edits_entry_long_description: '[data-role="ibo-edits-entry--long-description"]',
					edits_entry_long_description_toggler: '[data-role="ibo-edits-entry--long-description-toggler"]',
					notification_entry_long_description: '[data-role="ibo-notification-entry--long-description"]',
					notification_entry_long_description_toggler: '[data-role="ibo-notification-entry--long-description-toggler"]',
					load_more_entries_container: '[data-role="ibo-activity-panel--load-more-entries-container"]',
					load_more_entries: '[data-role="ibo-activity-panel--load-more-entries"]',
					load_more_entries_icon: '[data-role="ibo-activity-panel--load-more-entries-icon"]',
					load_all_entries: '[data-role="ibo-activity-panel--load-all-entries"]',
					load_all_entries_icon: '[data-role="ibo-activity-panel--load-all-entries-icon"]',
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
				},
				lock_status: {
					// Default, we can't be sure an object is unlocked as we only check from time to time
					unknown: 'unknown',
					// Current user wants the lock, we are trying to get it
					request_pending: 'request_pending',
					// Current user does not need the lock anymore
					release_pending: 'release_pending',
					// Current user has the lock
					locked_by_myself: 'locked_by_myself',
					// Object is locked by another user
					locked_by_someone_else: 'locked_by_someone_else',
				},
			},
			release_lock_promise_resolve: null,	// N°4494 - Resolve callback of the Promise used for the action following the log entry send, which must be done only once the lock is released

			// the constructor
			_create: function () {
				this.element.addClass('ibo-activity-panel');

				this._bindEvents();

				// Lock
				if (null === this.options.lock_status) {
					this.options.lock_status = this.enums.lock_status.unknown;
				}
				if (true === this.options.lock_enabled) {
					this._InitializeLockWatcher();
				}

				this._InitializeCurrentTab();
				this._ApplyEntriesFilters();
				this._UpdateMessagesCounters();
				this._UpdateFiltersCheckboxesFromOptions();
				this._ReformatDateTimes();
				this._PrepareEntriesSubmitConfirmationDialog();

				this.element.trigger('ready.activity_panel.itop');
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function () {
				this.element.removeClass('ibo-activity-panel');
			},
			_bindEvents: function () {
				const me = this;
				const oBodyElem = $('body');

				// Tabs title
				// - Click on the panel reduce/expand togglers
				this.element.find(this.js_selectors.panel_size_expand+', '+this.js_selectors.panel_size_reduce).on('click', function (oEvent) {
					me._onPanelSizeIconClick(oEvent);
				});
				// - Click on the panel close/open togglers
				this.element.find(this.js_selectors.panel_size_close+', '+this.js_selectors.panel_size_open).on('click', function (oEvent) {
					me._onPanelDisplayIconClick(oEvent);
				});
				// - Click on a tab title
				this.element.find(this.js_selectors.tab_title).on('click', function (oEvent) {
					me._onTabTitleClick(oEvent, $(this));
				});

				// Tabs toolbar
				// - Change on a filter
				this.element.find(this.js_selectors.activity_filter).on('change', function () {
					me._onFilterChange($(this));
				});
				// - Click on a filter options toggler
				this.element.find(this.js_selectors.activity_filter_options_toggler).on('click', function (oEvent) {
					me._onFilterOptionsTogglerClick(oEvent, $(this));
				})
				// - Change on a filter option
				this.element.find(this.js_selectors.activity_filter_option_input).on('change', function () {
					me._onFilterOptionChange($(this));
				});
				// - Click on open all case log messages
				this.element.find(this.js_selectors.caselog_tab_open_all).on('click', function () {
					me._onOpenAllEntriesClick();
				});
				// - Click on close all case log messages
				this.element.find(this.js_selectors.caselog_tab_close_all).on('click', function () {
					me._onCloseAllEntriesClick();
				});

				// Entry form
				// - Click on the compose button
				this.element.find(this.js_selectors.compose_button).on('click', function (oEvent) {
					me._onComposeButtonClick(oEvent);
				});
				// - Click on the compose menu items
				this.element.find(this.js_selectors.compose_menu_item).on('click', function (oEvent) {
					me._onComposeMenuItemClick(oEvent, $(this));
				});
				// - Draft value ongoing
				this.element.on('draft.caselog_entry_form.itop', function (oEvent, oData) {
					me._onDraftEntryForm(oData.attribute_code);
				});
				// - Empty value
				this.element.on('emptied.caselog_entry_form.itop', function (oEvent, oData) {
					me._onEmptyEntryForm(oData.attribute_code);
				});
				// - Entry form cancelled
				this.element.on('cancelled_form.caselog_entry_form.itop', function () {
					me._onCancelledEntryForm();
				});
				// - Entry form submission request
				this.element.on('requested_submission.caselog_entry_form.itop', function (oEvent, oData) {
					me._onRequestSubmission(oEvent, oData);
				});

				// Entries
				// - Click on a closed case log message
				this.element.on('click', this.js_selectors.entry+'.'+this.css_classes.is_closed+' '+this.js_selectors.entry_main_information, function (oEvent) {
					me._onClosedEntryClick($(this).closest(me.js_selectors.entry));
				});
				// - Click on an edits entry's long description toggler
				this.element.on('click', this.js_selectors.edits_entry_long_description_toggler, function (oEvent) {
					me._onEntryLongDescriptionTogglerClick(oEvent, $(this).closest(me.js_selectors.entry));
				});
				// - Click on an notification entry's long description toggler
				this.element.on('click', this.js_selectors.notification_entry_long_description_toggler, function (oEvent) {
					me._onEntryLongDescriptionTogglerClick(oEvent, $(this).closest(me.js_selectors.entry));
				});
				// - Click on load more entries button
				this.element.find(this.js_selectors.load_more_entries).on('click', function (oEvent) {
					me._onLoadMoreEntriesButtonClick(oEvent);
				});
				// - Click on load all entries button
				this.element.find(this.js_selectors.load_all_entries).on('click', function (oEvent) {
					me._onLoadAllEntriesButtonClick(oEvent);
				});

				// Processing / cleanup when the leaving page
				$(window).on('unload', function() {
					if (true === me._HasDraftEntries()) {
						return me._onUnload();
					}
				});

				// Mostly for outside clicks that should close elements
				oBodyElem.on('click', function (oEvent) {
					me._onBodyClick(oEvent);
				});
				// Mostly for hotkeys
				oBodyElem.on('keyup', function (oEvent) {
					me._onBodyKeyUp(oEvent);
				});
			},

			// Events callbacks
			_onPanelSizeIconClick: function (oEvent) {
				// Avoid anchor glitch
				oEvent.preventDefault();

				// Toggle menu
				this.element.toggleClass(this.css_classes.is_expanded);
				this._SaveStatePreferences();
			},
			_onPanelDisplayIconClick: function (oEvent) {
				// Avoid anchor glitch
				oEvent.preventDefault();

				// Toggle menu
				this.element.toggleClass(this.css_classes.is_closed);
				this._SaveStatePreferences();
			},
			_onTabTitleClick: function (oEvent, oTabTitleElem) {
				// Avoid anchor glitch
				oEvent.preventDefault();
				let oState = {};
				const sId = this.element.attr('id');

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
					this._ShowCaseLogTab(sCaselogAttCode);
					oState[sId] = "caselog-"+sCaselogAttCode;
				}
				else
				{
					this.element.find(this.js_selectors.tab_toolbar + '[data-tab-type="activity"]').addClass(this.css_classes.is_active);
					this._ShowActivityTab();
					oState[sId] = "activity";
				}

				// Add current activity tab to url hash
				$.bbq.pushState(oState);
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
			_onOpenAllEntriesClick: function()
			{
				this._OpenAllEntries();
			},
			_onCloseAllEntriesClick: function()
			{
				this._CloseAllEntries();
			},
			/**
			 * @param oEvent {Object}
			 * @return {void}
			 * @private
			 */
			_onComposeButtonClick: function (oEvent) {
				oEvent.preventDefault();

				const oActiveTabData = this._GetActiveTabData();
				// If on a caselog tab, open its form if it has one
				if ((this.enums.tab_types.caselog === oActiveTabData.type) && this._HasCaseLogEntryFormForTab(oActiveTabData.att_code)) {
					// Note: Stop propagation to avoid the menu to be opened automatically by the popover handler
					oEvent.stopImmediatePropagation();

					this._ShowCaseLogTab(oActiveTabData.att_code);
					this._ShowCaseLogsEntryForms();
					this._SetFocusInCaseLogEntryForm(oActiveTabData.att_code);
				}
				// Else (activity tab) if only 1 clog tab, open it directly
				else if (this._GetCaseLogEntryFormCount() === 1) {
					// Note: Stop propagation to avoid the menu to be opened automatically by the popover handler
					oEvent.stopImmediatePropagation();

					// Simulate click on the only menu item
					this.element.find(this.js_selectors.compose_menu_item+':first').trigger('click');
				}

				// Else, the compose menu will open automatically
			},
			/**
			 * @param oEvent {Object}
			 * @param oItemElem {Object} jQuery object representing the clicked item
			 * @return {void}
			 * @private
			 */
			_onComposeMenuItemClick: function (oEvent, oItemElem) {
				oEvent.preventDefault();

				// Change tab
				this.element.find(this.js_selectors.tab_toggler+'[data-tab-type="'+this.enums.tab_types.caselog+'"][data-caselog-attribute-code="'+oItemElem.attr('data-caselog-attribute-code')+'"]')
					.find(this.js_selectors.tab_title)
					.trigger('click');

				// Then open editor
				this.element.find(this.js_selectors.compose_button).trigger('click');
			},
			/**
			 * @param oEvent {Object}
			 * @return {void}
			 * @private
			 */
			_onLoadMoreEntriesButtonClick: function (oEvent) {
				oEvent.preventDefault();

				this._LoadMoreEntries();
			},
			/**
			 * @param oEvent {Object}
			 * @return {void}
			 * @private
			 */
			_onLoadAllEntriesButtonClick: function (oEvent) {
				oEvent.preventDefault();

				this._LoadMoreEntries(false);
			},
			/**
			 * Indicate that there is a draft entry and will request lock on the object
			 *
			 * @param sCaseLogAttCode {string} Attribute code of the case log entry form being draft
			 * @private
			 */
			_onDraftEntryForm: function (sCaseLogAttCode) {
				// Put draft indicator
				this.element.find(this.js_selectors.tab_toggler+'[data-tab-type="'+this.enums.tab_types.caselog+'"][data-caselog-attribute-code="'+sCaseLogAttCode+'"]').addClass(this.css_classes.is_draft);

				// Register leave handler blockers
				this._RegisterLeaveHandlerBlockers();

				if (this.options.lock_enabled === true) {
					// Request lock
					this._RequestLock();
				} else {
					// Only enable buttons
					this.element.find(this.js_selectors.caselog_entry_form + '[data-attribute-code="' + sCaseLogAttCode + '"]').trigger('enable_submission.caselog_entry_form.itop');
				}
			},
			/**
			 * Remove indication of a draft entry and will cancel the lock (acquired or pending) if no draft entry left
			 *
			 * @param sCaseLogAttCode {string} Attribute code of the case log entry form being emptied
			 * @private
			 */
			_onEmptyEntryForm: function (sCaseLogAttCode) {
				// Remove draft indicator
				this.element.find(this.js_selectors.tab_toggler+'[data-tab-type="'+this.enums.tab_types.caselog+'"][data-caselog-attribute-code="'+sCaseLogAttCode+'"]').removeClass(this.css_classes.is_draft);

				// Unregister leave handler blockers (only in view mode, otherwise it would remove blocker on main form fields as well)
				if (this._GetHostObjectMode() === 'view') {
					this._UnregisterLeaveHandlerBlockers();
				}

				if (this.options.lock_enabled === true) {
					// Cancel lock if all forms empty
					if (false === this._HasDraftEntries()) {
						this._CancelLock();
					}
				} else {
					// Only disable buttons
					this.element.find(this.js_selectors.caselog_entry_form + '[data-attribute-code="' + sCaseLogAttCode + '"]').trigger('disable_submission.caselog_entry_form.itop');
				}
			},
			_onCancelledEntryForm: function () {
				this._EmptyCaseLogsEntryForms();
				this._HideCaseLogsEntryForms();
			},
			/**
			 * Called on submission request from a case log entry form, will display a confirmation dialog if multiple case logs have
			 * been edited and the user hasn't dismiss the dialog.
			 * @private
			 */
			_onRequestSubmission: async function (oEvent, oData) {
				// Check lock state
				if ((this.options.lock_enabled === true) && (this.enums.lock_status.locked_by_myself !== this.options.lock_status)) {
					CombodoJSConsole.Debug('ActivityPanel: Could not submit entries, current user does not have the lock on the object');
					return;
				}

				let sStimulusCode = (undefined !== oData.stimulus_code) ? oData.stimulus_code : null
				// If several entry forms filled, show a confirmation message
				if ((true === this.options.show_multiple_entries_submit_confirmation) && (Object.keys(await this._GetEntriesFromAllForms()).length > 1)) {
					this._ShowEntriesSubmitConfirmation(sStimulusCode);
				}
				// Else push data directly to the server
				else {
					this._SendEntriesToServer(sStimulusCode);
				}
			},
			_onClosedEntryClick: function (oEntryElem) {
				this._OpenEntry(oEntryElem);
			},
			_onEntryLongDescriptionTogglerClick: function (oEvent, oEntryElem) {
				// Avoid anchor glitch
				oEvent.preventDefault();

				oEntryElem.toggleClass(this.css_classes.is_closed);
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
			_onBodyKeyUp: function (oEvent) {
				// On "Esc" key
				if (oEvent.key === 'Escape') {
					// Hide all filters's options
					this._HideAllFiltersOptions();
				}
			},
			/**
			 * Called when the user leave the page, will remove the current lock if any draft entries
			 * @private
			 */
			_onUnload: function() {
				return OnUnload(this.options.transaction_id, this.element.attr('data-object-class'), this.element.attr('data-object-id'), this.options.lock_token);
			},

			// Methods
			// - Helpers on host object
			_GetHostObjectClass: function () {
				return this.element.attr('data-object-class');
			},
			_GetHostObjectID: function () {
				return this.element.attr('data-object-id');
			},
			_GetHostObjectMode: function () {
				return this.element.attr('data-object-mode');
			},
			/**
			 * Save to the user pref. the expanded and closed states the host object class / mode
			 *
			 * @return {void}
			 * @private
			 */
			_SaveStatePreferences: function () {
				$.post(
					this.options.save_state_endpoint,
					{
						'operation': 'activity_panel.save_state',
						'object_class': this._GetHostObjectClass(),
						'object_mode': this._GetHostObjectMode(),
						'is_expanded': this.element.hasClass(this.css_classes.is_expanded),
						'is_closed': this.element.hasClass(this.css_classes.is_closed),
					}
				);
			},

			// - Helpers on dates
			/**
			 * Reformat date times to be relative (only if they are not too far in the past)
			 * @private
			 */
			_ReformatDateTimes: function () {
				const me = this;

				this.element.find(this.js_selectors.entry_datetime).each(function () {
					const oEntryDateTime = moment($(this).attr('data-formatted-datetime'), me.options.datetime_format);
					const oNowDateTime = moment();

					// Reformat date time only if it is not too far in the past (eg. "2 years ago" is not easy to interpret)
					const fDays = moment.duration(oNowDateTime.diff(oEntryDateTime)).asDays();
					if (fDays < me.options.datetimes_reformat_limit) {
						$(this).text(moment($(this).attr('data-formatted-datetime'), me.options.datetime_format).fromNow());
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
			 * Set a tab active if it's specified in the url
			 * @returns {void}
			 * @private
			 */
			_InitializeCurrentTab : function(){
				const sTabId = $.bbq.getState(this.element.attr('id'), true);
				if(sTabId !== undefined){
					if(sTabId.startsWith("caselog-")){
						this._GetTabTogglerFromCaseLogAttCode(sTabId.replace("caselog-", "")).find(this.js_selectors.tab_title).trigger('click')
					}
					else if(sTabId === "activity"){
						this.element.find(this.js_selectors.tab_toggler + '[data-tab-type="activity"]').find(this.js_selectors.tab_title).trigger('click')
					}
				}
			},
			/**
			 * @returns {Object} Active tab toolbar jQuery element
			 * @private
			 */
			_GetActiveTabToolbarElement: function() {
				const oActiveTabData = this._GetActiveTabData();
				let sSelector = this.js_selectors.tab_toolbar+'[data-tab-type="'+oActiveTabData.type+'"]';

				if (this.enums.tab_types.caselog === oActiveTabData.type) {
					sSelector += '[data-caselog-attribute-code="'+oActiveTabData.att_code+'"]';
				}

				return this.element.find(sSelector);
			},
			/**
			 * Show the case log tab of sCaseLogAttCode and applies its filters
			 * Note: It doesn't open the entry form
			 *
			 * @param sCaseLogAttCode {string}
			 * @return {void}
			 * @private
			 */
			_ShowCaseLogTab: function (sCaseLogAttCode) {
				this.element.find(this.js_selectors.tab_toolbar+'[data-tab-type="caselog"][data-caselog-attribute-code="'+sCaseLogAttCode+'"]').addClass(this.css_classes.is_active);

				// Show only entries from this case log
				this._ShowAllEntries();
				this._ApplyEntriesFilters();
			},
			_ShowActivityTab: function () {
				// Show all entries but regarding the current filters
				this._ShowAllEntries();
				this._ApplyEntriesFilters();
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

			// - Helpers on toolbars
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
			_HideAllFiltersOptions: function () {
				const me = this;
				this.element.find(this.js_selectors.activity_filter_options_toggler).each(function () {
					me._HideFilterOptions($(this));
				});
			},

			// - Helpers on case logs entry forms
			/**
			 * @returns {integer} The number of caselog entry forms
			 * @private
			 * @since 3.1.0
			 */
			_GetCaseLogEntryFormCount: function () {
				return this.element.find(this.js_selectors.caselog_entry_form).length;
			},
			/**
			 * @param sCaseLogAttCode {string}
			 * @returns {boolean} Return true if there is a case log for entry for the sCaseLogAttCode tab
			 * @private
			 */
			_HasCaseLogEntryFormForTab: function (sCaseLogAttCode) {
				return (this.element.find(this.js_selectors.tab_toolbar+'[data-tab-type="'+this.enums.tab_types.caselog+'"][data-caselog-attribute-code="'+sCaseLogAttCode+'"]').find(this.js_selectors.caselog_entry_form).length > 0);
			},
			_SetFocusInCaseLogEntryForm: function (sCaseLogAttCode) {
				this.element.find(this.js_selectors.caselog_entry_form+'[data-attribute-code="'+sCaseLogAttCode+'"]').trigger('set_focus.caselog_entry_form.itop');
			},
			/**
			 * Show all case logs entry forms.
			 * Event is triggered on the corresponding elements.
			 *
			 * @return {void}
			 * @private
			 */
			_ShowCaseLogsEntryForms: function () {
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
			_HideCaseLogsEntryForms: function () {
				this.element.find(this.js_selectors.caselog_entry_form).trigger('hide_form.caselog_entry_form.itop');
				this.element.find(this.js_selectors.compose_button).removeClass(this.css_classes.is_hidden);
			},
			/**
			 * Empty all case logs entry forms
			 * Event is triggered on the corresponding elements.
			 *
			 * @return {void}
			 * @private
			 */
			_EmptyCaseLogsEntryForms: function () {
				this.element.find(this.js_selectors.caselog_entry_form).trigger('clear_entry.caselog_entry_form.itop');
			},
			_FreezeCaseLogsEntryForms: function () {
				this.element.find(this.js_selectors.caselog_entry_form).trigger('enter_pending_submission_state.caselog_entry_form.itop');
			},
			_UnfreezeCaseLogsEntryForms: function () {
				this.element.find(this.js_selectors.caselog_entry_form).trigger('leave_pending_submission_state.caselog_entry_form.itop');
			},
			/**
			 * @returns {Object} The case logs having a new entry and their values, format is {<ATT_CODE_1>: <HTML_VALUE_1>, <ATT_CODE_2>: <HTML_VALUE_2>}
			 * @private
			 */
			_GetEntriesFromAllForms: async function () {
				const me = this;
				let oEntries = {};
				// this.element.find(this.js_selectors.caselog_entry_form).each(async function () {
				// 	const oEntryFormElem = $(this);
				// 	const sEntryFormValue = await oEntryFormElem.triggerHandler('get_entry.caselog_entry_form.itop');
				// 	console.log('huhu');
				//
				// 	if ('' !== sEntryFormValue) {
				// 		const sCaseLogAttCode = oEntryFormElem.attr('data-attribute-code');
				// 		oEntries[sCaseLogAttCode] = {
				// 			value: sEntryFormValue,
				// 			rank: me.element.find(me.js_selectors.tab_toggler+'[data-tab-type="caselog"][data-caselog-attribute-code="'+sCaseLogAttCode+'"]').attr('data-caselog-rank'),
				// 		};
				// 	}
				// });
				
				const aFormElements = this.element.find(this.js_selectors.caselog_entry_form);

				// Create an array of promises for each form element
				const aEntryPromises = aFormElements.map(async (index, element) => {
					const oEntryFormElem = $(element);
					const sEntryFormValue = await oEntryFormElem.triggerHandler('get_entry.caselog_entry_form.itop');

					if ('' !== sEntryFormValue) {
						const sCaseLogAttCode = oEntryFormElem.attr('data-attribute-code');
						oEntries[sCaseLogAttCode] = {
							value: sEntryFormValue,
							rank: this.element.find(this.js_selectors.tab_toggler + '[data-tab-type="caselog"][data-caselog-attribute-code="' + sCaseLogAttCode + '"]').attr('data-caselog-rank'),
						};
					}
				}).get(); // convert jQuery object to array

				// Wait for all promises to resolve
				await Promise.all(aEntryPromises);

				return oEntries;
			},
			/**
			 * @returns {Object} The case logs having a new entry and their values, format is {<ATT_CODE_1>: <HTML_VALUE_1>, <ATT_CODE_2>: <HTML_VALUE_2>}
			 * @private
			 */
			_GetExtraInputsFromAllForms: function () {
				const me = this;

				let oExtraInputs = {};
				this.element.find(this.js_selectors.caselog_entry_form).each(function () {
					const oEntryFormElem = $(this);
					oExtraInputs = $.extend(oExtraInputs, oEntryFormElem.triggerHandler('get_extra_inputs.caselog_entry_form.itop'));
				});

				return oExtraInputs;
			},

			/**
			 * @return {boolean} True if at least 1 of the entry form is draft (has some text in it)
			 * @private
			 */
			_HasDraftEntries: function () {
				return Object.keys(this._GetEntriesFromAllForms()).length > 0;
			},
			/**
			 * Prepare the dialog for confirmation before submission when several case log entries have been edited.
			 * @private
			 */
			_PrepareEntriesSubmitConfirmationDialog: function () {
				const me = this;

				this.element.find(this.js_selectors.caselog_entry_forms_confirmation_dialog).dialog({
					autoOpen: false,
					minWidth: 400,
					modal: true,
					position: {my: "center center", at: "center center", of: this.js_selectors.tabs_toolbars},
					close: function () { me._HideEntriesSubmitConfirmation(); },
					buttons: [
						{
							text: Dict.S('UI:Button:Cancel'),
							class: 'ibo-is-alternative',
							click: function () {
								me._HideEntriesSubmitConfirmation();
							}
						},
						{
							text: Dict.S('UI:Button:Send'),
							class: 'ibo-is-primary',
							click: function () {
								const bDoNotShowAgain = $(this).find(me.js_selectors.caselog_entry_forms_confirmation_preference_input).prop('checked');
								if (bDoNotShowAgain) {
									me._SaveSubmitConfirmationPref();
								}

								// Needs to be retrieved before hiding the dialog as it will wipe out the value in the process
								const sStimulusCode = $(this).attr('data-stimulus-code');
								me._HideEntriesSubmitConfirmation();
								me._SendEntriesToServer(sStimulusCode);
							}
						},
					],
				});
			},
			/**
			 * Show the confirmation dialog when multiple case log entries have been editied
			 * @param sStimulusCode {string|null} Code of the stimulus to apply if confirmation is given
			 * @private
			 */
			_ShowEntriesSubmitConfirmation: function(sStimulusCode = null)
			{
				$(this.js_selectors.caselog_entry_forms_confirmation_dialog)
					.dialog('open')
					.attr('data-stimulus-code', sStimulusCode);
			},
			/**
			 * Hide the confirmation dialog for multiple edited case log entries
			 * @private
			 */
			_HideEntriesSubmitConfirmation: function()
			{
				$(this.js_selectors.caselog_entry_forms_confirmation_dialog)
					.dialog('close')
					.attr('data-stimulus-code', '');
			},
			/**
			 * Save that the user don't want the confirmation dialog to be shown in the future
			 * @private
			 */
			_SaveSubmitConfirmationPref: function()
			{
				// Note: We have to send the value as a string because of the API limitation
				SetUserPreference('activity_panel.show_multiple_entries_submit_confirmation', 'false', true);
			},
			/**
			 * Send the edited case logs entries to the server
			 * @param sStimulusCode {string|null} Stimulus code to apply after the entries are saved
			 * @return {void}
			 * @private
			 */
			_SendEntriesToServer: async function (sStimulusCode = null) {
				const me = this;
				const oEntries = await this._GetEntriesFromAllForms();
				const oExtraInputs = this._GetExtraInputsFromAllForms();

				// Proceed only if entries to send
				if (Object.keys(oEntries).length === 0) {
					return false;
				}

				// Prepare parameters
				let oParams = $.extend(oExtraInputs, {
					operation: 'activity_panel.add_caselog_entries',
					object_class: this._GetHostObjectClass(),
					object_id: this._GetHostObjectID(),
					transaction_id: this.options.transaction_id,
					entries: oEntries,
				});

				// Freeze case logs
				this._FreezeCaseLogsEntryForms();

				// Send request to server
				$.post(
						GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
						oParams,
						'json'
					)
					.fail(function (oXHR, sStatus, sErrorThrown) {
						CombodoModal.OpenErrorModal(sErrorThrown);
					})
					.done(function (oData) {
						if (false === oData.data.success) {
							CombodoModal.OpenErrorModal(oData.data.error_message);
							return false;
						}

						// Update the feed and tab toggler message counter
						for (let sCaseLogAttCode in oData.data.entries) {
							me._AddEntry(oData.data.entries[sCaseLogAttCode], 'start');
							me._IncreaseTabTogglerMessagesCounter(sCaseLogAttCode);
						}
						me._ApplyEntriesFilters();

						// Try to fix inline images width
						CombodoInlineImage.FixImagesWidth();

						// For now, we don't hide the forms as the user may want to add something else
						me.element.find(me.js_selectors.caselog_entry_form).trigger('clear_entry.caselog_entry_form.itop');
						// Redirect to stimulus
						// - Convert undefined, null and empty string to null
						sStimulusCode = ((sStimulusCode ?? '') === '') ? null : sStimulusCode;
						if (null !== sStimulusCode) {
							if (me.options.lock_enabled) {
								// Use a Promise to ensure that we redirect to the stimulus page ONLY when the lock is released, otherwise we might lock ourselves
								const oPromise = new Promise(function (resolve) {
									// Store the resolve callback so we can call it later from outside
									me.release_lock_promise_resolve = resolve;
								});
								oPromise.then(function () {
									window.location.href = GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=stimulus&class='+me._GetHostObjectClass()+'&id='+me._GetHostObjectID()+'&stimulus='+sStimulusCode;
									// Resolve callback is reinitialized in case the redirection fails for any reason and we might need to retry
									me.release_lock_promise_resolve = null;
								});
							} else {
								window.location.href = GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=stimulus&class='+me._GetHostObjectClass()+'&id='+me._GetHostObjectID()+'&stimulus='+sStimulusCode;
							}
						}
					})
					.always(function () {
						// Always, unfreeze case logs
						me._UnfreezeCaseLogsEntryForms();
					});
			},
			/**
			 * Increase a tab toggler number of messages indicator given a caselog attribute code
			 *
			 * @param sCaseLogAttCode {string} A caselog attribute code
			 * @return {void}
			 * @private
			 */
			_IncreaseTabTogglerMessagesCounter: function(sCaseLogAttCode){
				let oTabTogglerCounter = this._GetTabTogglerFromCaseLogAttCode(sCaseLogAttCode).find('[data-role="ibo-activity-panel--tab-title-messages-count"]');
				let iNewCounterValue = parseInt(oTabTogglerCounter.attr('data-messages-count')) + 1;

				oTabTogglerCounter.attr('data-messages-count', iNewCounterValue).text(iNewCounterValue);
			},
			/**
			 * Return tab toggler given a caselog attribute code
			 *
			 * @param sCaseLogAttCode {string} A caselog attribute code
			 * @return {Object}
			 * @private
			 */
			_GetTabTogglerFromCaseLogAttCode: function(sCaseLogAttCode)
			{
				return this.element.find(this.js_selectors.tab_toggler+'[data-tab-type="caselog"][data-caselog-attribute-code="'+sCaseLogAttCode+'"]')
			},

			// - Helpers on leave handler
			/**
			 * Register leave handler blockers for the activity panel
			 * @see js/leave_handler.js
			 * @since 3.1.0
			 */
			_RegisterLeaveHandlerBlockers: function () {
				const sBlockerId = this._GetLeaveHandlerBlockerID();

				// On page leave
				$('body').trigger('register_blocker.itop', {
					'sBlockerId': sBlockerId,
					'sTargetElemSelector': 'document',
					'oTargetElemSelector': document,
					'sEventName': 'beforeunload'
				});

				// On modal close if we are in one
				const oModalElem = this.element.closest('[data-role="ibo-modal"]');
				if (oModalElem.length !== 0) {
					$('body').trigger('register_blocker.itop', {
						'sBlockerId': sBlockerId,
						'sTargetElemSelector': '#' + oModalElem.attr('id'),
						'oTargetElemSelector': '#' + oModalElem.attr('id'),
						'sEventName': 'dialogbeforeclose'
					});
				}
			},
			/**
			 * Unregister leave handler blockers for the activity panel
			 * @see js/leave_handler.js
			 * @since 3.1.0
			 */
			_UnregisterLeaveHandlerBlockers: function () {
				$('body').trigger('unregister_blocker.itop', {
					'sBlockerId': this._GetLeaveHandlerBlockerID()
				});
			},
			/**
			 * @returns {String} The leave blocker identifier to use with {@see leave_handler.js} for the activity panel
			 * @since 3.1.0
			 */
			_GetLeaveHandlerBlockerID: function () {
				return this._GetHostObjectClass() + ':' + this._GetHostObjectID();
			},

			// - Helpers on object lock
			/**
			 * Initialize the lock watcher on a regular basis
			 *
			 * @return {void}
			 * @private
			 */
			_InitializeLockWatcher: function () {
				const me = this;
				setInterval(function () {
					me._UpdateLock();
				}, this.options.lock_watcher_period * 1000);
			},
			/**
			 * Request lock on the object for the current user
			 *
			 * @return {void}
			 * @private
			 */
			_RequestLock: function () {
				// Abort lock request if it is not enabled
				if (this.options.lock_enabled === false) {
					return;
				}

				// Abort lock request if we already have it or a request is already pending
				// Note: This can happen when we write in several case logs
				if ([this.enums.lock_status.request_pending, this.enums.lock_status.locked_by_myself].indexOf(this.options.lock_status) !== -1) {
					return;
				}

				this.options.lock_status = this.enums.lock_status.request_pending;
				this._UpdateLock();
			},
			/**
			 * Cancel the lock on the object for the current user
			 *
			 * @return {void}
			 * @private
			 */
			_CancelLock: function () {
				// Abort lock request if it is not enabled
				if (this.options.lock_enabled === false) {
					return;
				}

				if (this.enums.lock_status.locked_by_myself === this.options.lock_status) {
					this.options.lock_status = this.enums.lock_status.release_pending;
				} else {
					this.options.lock_status = this.enums.lock_status.unknown;
				}
				this._UpdateLock();
			},
			/**
			 * Update the lock status every now and then to inform the user that he/she can submit or not yet.
			 *
			 * This is to prevent scenario where the user has the lock, puts its computer in standby, opens it again after a few days
			 * (eg. the weekend). We have to check if he/she still has the lock or not.
			 *
			 * @return {void}
			 * @private
			 */
			_UpdateLock: function () {
				const me = this;
				let oParams = {
					obj_class: this._GetHostObjectClass(),
					obj_key: this._GetHostObjectID(),
				};

				// Try to acquire it if requested...
				if (this.enums.lock_status.request_pending === this.options.lock_status) {
					oParams.operation = 'acquire_lock';
				}
				// ... or extend lock if locked by current user...
				else if (this.enums.lock_status.locked_by_myself === this.options.lock_status) {
					oParams.operation = 'extend_lock';
					oParams.token = this.options.lock_token;
				}
				// ... or release lock if current user does not want it anymore...
				else if (this.enums.lock_status.release_pending === this.options.lock_status) {
					oParams.operation = 'release_lock';
					oParams.token = this.options.lock_token;
				}
				// ... otherwise, just check if locked by someone else
				else {
					oParams.operation = 'check_lock_state';
				}
				$.post(
						this.options.lock_endpoint,
						oParams,
						'json'
					)
					.fail(function (oXHR, sStatus, sErrorThrown) {
						// In case of HTTP request failure (not lock request), put the details in the JS console
						CombodoJSConsole.Error('Activity panel - Error on lock status check: '+sErrorThrown);
						CombodoJSConsole.Debug('Response status: '+sStatus);
						CombodoJSConsole.Debug('Response object: ', oXHR);
					})
					.done(function (oData) {
						let sNewLockStatus = me.enums.lock_status.unknown;
						let sMessage = null;

						// Tried to acquire lock
						if ('acquire_lock' === oParams.operation) {
							// Status true means that we acquired the lock...
							if (true === oData.success) {
								me.options.lock_token = oData.token
								sNewLockStatus = me.enums.lock_status.locked_by_myself;
							}
							// ... otherwise we will retry later
							else {
								sNewLockStatus = me.enums.lock_status.request_pending;
								if (oData.message) {
									sMessage = oData.message;
								}
							}
						}

						// Tried to extend our lock
						else if ('extend_lock' === oParams.operation) {
							// Status false means that we don't have the lock anymore
							if (false === oData.status) {
								sMessage = oData.message;

								// If it was lost, means that someone else has it, else it expired
								if ('lost' === oData.operation) {
									sNewLockStatus = me.enums.lock_status.locked_by_someone_else;
								} else if ('expired' === oData.operation) {
									sNewLockStatus = me.enums.lock_status.unknown;
									CombodoModal.OpenErrorModal(oData.popup_message);
								}
							} else {
								sNewLockStatus = me.enums.lock_status.locked_by_myself;
							}
						}

						// Tried to release our lock
						else if ('release_lock' === oParams.operation) {
							sNewLockStatus = me.enums.lock_status.unknown;
							if (me.release_lock_promise_resolve !== null) {
								me.release_lock_promise_resolve();
							}
						}

						// Just checked if object was locked
						else if ('check_lock_state' === oParams.operation) {
							if (true === oData.locked) {
								sNewLockStatus = me.enums.lock_status.locked_by_someone_else;
								sMessage = oData.message;
							}
						}

						me._UpdateLockDependencies(sNewLockStatus, sMessage);
					});
			},
			/**
			 * Update the lock dependencies (status, message, case logs form entries, ...)
			 *
			 * @param sNewLockStatus {string} See this.enums.lock_status
			 * @param sMessage {null|string}
			 * @return {bool} Whether the dependencies have been updated or not
			 * @private
			 */
			_UpdateLockDependencies: function (sNewLockStatus, sMessage) {
				const sOldLockStatus = this.options.lock_status;

				// Update lock indicator
				this.options.lock_status = sNewLockStatus;
				this.element.find(this.js_selectors.lock_message).text(sMessage);

				const sCallback = ([this.enums.lock_status.request_pending, this.enums.lock_status.locked_by_someone_else].indexOf(sNewLockStatus) !== -1) ? 'removeClass' : 'addClass';
				this.element.find(this.js_selectors.lock_hint)[sCallback](this.css_classes.is_hidden);

				// Update case logs entry forms
				const sEvent = (this.enums.lock_status.locked_by_myself === this.options.lock_status) ? 'enable_submission.caselog_entry_form.itop' : 'disable_submission.caselog_entry_form.itop';
				this.element.find(this.js_selectors.caselog_entry_form).trigger(sEvent);

				return true;
			},

			// - Helpers on messages
			_OpenEntry: function (oEntryElem) {
				oEntryElem.removeClass(this.css_classes.is_closed);
			},
			_OpenAllEntries: function () {
				this._SwitchAllEntries('open');
			},
			_CloseAllEntries: function () {
				this._SwitchAllEntries('close');
			},
			/**
			 *
			 * @param sMode {string} Which way to switch the entries, can be either "open" or "close".
			 * @private
			 */
			_SwitchAllEntries: function (sMode) {
				const sCallback = (sMode === 'open') ? 'removeClass' : 'addClass';
				this.element.find(this.js_selectors.entry)[sCallback](this.css_classes.is_closed);
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

					for (let sTargetEntryType of aTargetEntryTypes) {
						me[sCallbackMethod](sTargetEntryType, aFilterOptions);
					}
				});

				// Show only the last visible entry's medallion of a group (cannot be done through CSS yet 😕)
				this.element.find(this.js_selectors.entry_group).each(function () {
					// Reset everything
					$(this).find(me.js_selectors.entry_medallion).removeClass(me.css_classes.is_visible);
					$(this).find(me.js_selectors.entry_author_name).addClass(me.css_classes.is_hidden);

					// Then show only necessary
					$(this).find(me.js_selectors.entry+':visible:last')
						.find(me.js_selectors.entry_medallion).addClass(me.css_classes.is_visible)
						.end()
						.find(me.js_selectors.entry_author_name).removeClass(me.css_classes.is_hidden);
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
						this.element.find(sEntrySelector+'[data-entry-caselog-attribute-code="'+sCaseLogAttCode+'"]').removeClass(this.css_classes.is_hidden);
					}
				}
				// General case
				else {
					this.element.find(sEntrySelector).addClass(this.css_classes.is_hidden);
				}

				this._UpdateEntryGroupsVisibility();
			},
			/**
			 * Update the entry groups visibility regarding if they have visible entries themself
			 *
			 * @private
			 * @return {void}
			 */
			_UpdateEntryGroupsVisibility: function () {
				const me = this;

				this.element.find(this.js_selectors.entry_group).each(function () {
					if ($(this).find(me.js_selectors.entry+':not(.'+me.css_classes.is_hidden+')').length === 0) {
						$(this).addClass(me.css_classes.is_hidden);
					} else {
						$(this).removeClass(me.css_classes.is_hidden);
					}
				});
			},
			/**
			 * Load the next entries and append them to the current ones
			 *
			 * IMPORTANT: For now the logic is naive, the entries come from 3 different sources : case logs, CMDB change ops and notifications.
			 * We load all the case logs and notifications entries, but only the 'max_history_length' first from the CMDB change ops.
			 *
			 * When we load the remaining history entries (CMDB change ops) and append them to the activity panel, some of them should actually
			 * be placed between already present entries (case logs, notifications) to keep the chronological order. This is a known limitation
			 * and might be worked on in a future version.
			 *
			 * @param {boolean} bLimitResultsLength True to limit the results length to the X previous entries, false to retrieve them all
			 * @private
			 * @return {void}
			 */
			_LoadMoreEntries: function (bLimitResultsLength = true) {
				const me = this;

				// Change icon to spinning
				// - Hide second button
				this.element.find(this.js_selectors.load_all_entries).addClass(this.css_classes.is_hidden);
				// - Transform first button
				this.element.find(this.js_selectors.load_more_entries_icon)
					.removeClass('fas fa-angle-double-down')
					.addClass('fas fa-sync-alt fa-spin');

				// Send XHR request
				let oParams = {
					operation: 'activity_panel.load_more_entries',
					object_class: this._GetHostObjectClass(),
					object_id: this._GetHostObjectID(),
					last_loaded_entries_ids: this.options.last_loaded_entries_ids,
					limit_results_length: bLimitResultsLength,
				};
				$.post(
						this.options.load_more_entries_endpoint,
						oParams,
						'json'
					)
					.fail(function (oXHR, sStatus, sErroThrown) {
						CombodoModal.OpenErrorModal(sErrorThrown);
					})
					.done(function (oData) {
						if (false === oData.data.success) {
							CombodoModal.OpenErrorModal(oData.data.error_message);
							return false;
						}

						// Update the feed
						for (let oEntry of oData.data.entries) {
							me._AddEntry(oEntry, 'end');
						}
						me._ApplyEntriesFilters();

						// Check if more entries to load
						// - Update metadata
						me.options.last_loaded_entries_ids = oData.data.last_loaded_entries_ids;
						// - Update button state
						if (Object.keys(me.options.last_loaded_entries_ids).length === 0) {
							me.element.find(me.js_selectors.load_more_entries).remove();
							me.element.find(me.js_selectors.load_all_entries).remove();
						}
					})
					.always(function () {
						// IF is a protection against cases when the button have be removed from the DOM (when no more entries to load)
						if (me.element.find(me.js_selectors.load_more_entries_icon).length > 0) {
							// Restore second button
							me.element.find(me.js_selectors.load_all_entries).removeClass(me.css_classes.is_hidden);

							// Change first button icon back to original (whether it should be displayed or not will be handle by thes other callbacks)
							// - fail => keep displayed for retry
							// - done => display only if more entries to load
							me.element.find(me.js_selectors.load_more_entries_icon)
								.removeClass('fas fa-sync-alt fa-spin')
								.addClass('fas fa-angle-double-down');
						}
					});
			},
			/**
			 * Add an entry represented by its oData to the feed
			 *
			 * @param oData {Object} Structured data of the entry: {html_rendering: <HTML_DATA>}
			 * @param sPosition {string} Whether the entry should be added at the 'start' or 'end' of the feed
			 * @private
			 */
			_AddEntry: function (oData, sPosition = 'start') {
				// Info about the new entry
				const oNewEntryElem = $(oData.html_rendering);
				const sNewEntryAuthorLogin = oNewEntryElem.attr('data-entry-author-login');
				const sNewEntryOrigin = oNewEntryElem.attr('data-entry-group-origin');

				// Info about the last entry group to see the entry to add should be in this one or a new one
				const sEntryGroupPosition = (sPosition === 'start') ? 'first' : 'last';
				const oLastEntryGroupElem = this.element.find(this.js_selectors.entry_group+':'+sEntryGroupPosition);
				const sLastEntryAuthorLogin = oLastEntryGroupElem.length > 0 ? oLastEntryGroupElem.attr('data-entry-author-login') : null;
				const sLastEntryOrigin = oLastEntryGroupElem.length > 0 ? oLastEntryGroupElem.attr('data-entry-group-origin') : null;

				let oTargetEntryGroup = null;
				if ((sLastEntryAuthorLogin === sNewEntryAuthorLogin) && (sLastEntryOrigin && sNewEntryOrigin)) {
					oTargetEntryGroup = oLastEntryGroupElem;
				} else {
					oTargetEntryGroup = this._CreateEntryGroup(sNewEntryAuthorLogin, sNewEntryOrigin, sPosition);
				}

				const sInsertFunction = (sPosition === 'start') ? 'prepend' : 'append';
				oTargetEntryGroup.prepend(oNewEntryElem);

				this._ReformatDateTimes();
			},
			/**
			 * Create an entry group and add it to the activity panel
			 *
			 * @param sAuthorLogin {string}
			 * @param sOrigin {string}
			 * @param sPosition {string} Whether the entry group should be added at the start or the end of the feed
			 * @returns {Object} jQuery object representing the created entry group
			 * @private
			 */
			_CreateEntryGroup: function (sAuthorLogin, sOrigin, sPosition = 'start') {
				// Note: When using the ActivityPanel, there should always be at least one entry group already, the one from the object creation
				let oEntryGroupElem = this.element.find(this.js_selectors.entry_group+':first')
					.clone()
					.attr('data-entry-author-login', sAuthorLogin)
					.attr('data-entry-group-origin', sOrigin)
					.addClass(this.css_classes.is_current_user)
					.html('');

				if ('start' === sPosition) {
					oEntryGroupElem.prependTo(this.element.find(this.js_selectors.body));
				} else {
					oEntryGroupElem.insertBefore(this.element.find(this.js_selectors.load_more_entries_container));
				}

				return oEntryGroupElem;
			}
		});
});
