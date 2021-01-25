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
			},
			js_selectors:
			{
				panel_size_toggler: '[data-role="ibo-activity-panel--size-toggler"]',
				tab_toggler: '[data-role="ibo-activity-panel--tab-toggler"]',
				tab_title: '[data-role="ibo-activity-panel--tab-title"]',
				tab_toolbar: '[data-role="ibo-activity-panel--tab-toolbar"]',
				activity_filter: '[data-role="ibo-activity-panel--activity-filter"]',
				caselog_tab_open_all: '[data-role="ibo-activity-panel--caselog-open-all"]',
				caselog_tab_close_all: '[data-role="ibo-activity-panel--caselog-close-all"]',
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
				}
			},

			// the constructor
			_create: function()
			{
				this.element.addClass('ibo-activity-panel');
				this._bindEvents();
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
				// - Change on an activity filter
				this.element.find(this.js_selectors.activity_filter).on('change', function(){
					me._onActivityFilterChange($(this));
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
				// - Cancelled form
				this.element.on('cancelled_form.caselog_entry_form.itop', function(){
					me._onCancelledEntryForm();
				});
				// - Submitted form

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
			_onActivityFilterChange: function(oInputElem)
			{
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
			_onCancelledEntryForm: function()
			{
				this._HideCaseLogsEntryForms();
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
			_onBodyClick: function(oEvent)
			{

			},
			_onBodyKeyUp: function(oEvent)
			{

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

			// - Helpers on entries
			_ApplyEntriesFilters: function()
			{
				const me = this;

				// For each filter, show/hide corresponding entries
				this._GetActiveTabToolbarElement().find(this.js_selectors.activity_filter).each(function(){
					const aTargetEntryTypes = $(this).attr('data-target-entry-types').split(' ');
					const sCallbackMethod = ($(this).prop('checked')) ? '_ShowEntries' : '_HideEntries';

					for(let iIdx in aTargetEntryTypes)
					{
						me[sCallbackMethod](aTargetEntryTypes[iIdx]);
					}
				});

				// Show only the last visible entry's medallion of a group (can be done through CSS yet 😕)
				this.element.find(this.js_selectors.entry_group).each(function(){
					$(this).find(me.js_selectors.entry_medallion).removeClass(me.css_classes.is_visible);
					$(this).find(me.js_selectors.entry + ':visible:last').find(me.js_selectors.entry_medallion).addClass(me.css_classes.is_visible);
				});

				this._UpdateEntryGroupsVisibility();
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
			 * @param sEntryType string
			 * @private
			 */
			_ShowEntries: function(sEntryType)
			{
				this.element.find(this.js_selectors.entry+'[data-entry-type="'+sEntryType+'"]').removeClass(this.css_classes.is_hidden);
				this._UpdateEntryGroupsVisibility();
			},
			/**
			 * Hide entries of type sEntryType but do not hide the others
			 *
			 * @param sEntryType string
			 * @private
			 */
			_HideEntries: function(sEntryType)
			{
				this.element.find(this.js_selectors.entry+'[data-entry-type="'+sEntryType+'"]').addClass(this.css_classes.is_hidden);
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
