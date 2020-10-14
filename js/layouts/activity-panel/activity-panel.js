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
				is_hidden: 'ibo-is-hidden',
			},
			js_selectors:
			{
				panel_size_toggler: '[data-role="ibo-activity-panel--size-toggler"]',
				tab: '[data-role="ibo-activity-panel--tab"]',
				tab_title: '[data-role="ibo-activity-panel--tab-title"]',
				activity_tab_filter: '[data-role="ibo-activity-panel--activity-filter"]',
				caselog_tab_open_all: '[data-role="ibo-activity-panel--caselog-open-all"]',
				caselog_tab_close_all: '[data-role="ibo-activity-panel--caselog-close-all"]',
				entry_group: '[data-role="ibo-activity-panel--entry-group"]',
				entry: '[data-role="ibo-activity-entry"]',
				entry_main_information: '[data-role="ibo-activity-entry--main-information"]',
				entry_datetime: '[data-role="ibo-activity-entry--datetime"]',
				edits_entry_long_description: '[data-role="ibo-edits-entry--long-description"]',
				edits_entry_long_description_toggler: '[data-role="ibo-edits-entry--long-description-toggler"]',
			},

			// the constructor
			_create: function()
			{
				this.element.addClass('ibo-activity-panel');
				this._bindEvents();
				this._ReformatDateTimes();
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

				// Click on collapse/expand toggler
				this.element.find(this.js_selectors.panel_size_toggler).on('click', function(oEvent){
					me._onTogglerClick(oEvent);
				});
				// Click on tab title
				this.element.find(this.js_selectors.tab_title).on('click', function(oEvent){
					me._onTabTitleClick(oEvent, $(this));
				});
				// Change on activity filters
				this.element.find(this.js_selectors.activity_tab_filter).on('change', function(){
					me._onActivityFilterChange($(this));
				});
				// Click on open all case log messages
				this.element.find(this.js_selectors.caselog_tab_open_all).on('click', function(){
					me._onCaseLogOpenAllClick($(this));
				});
				// Click on close all case log messages
				this.element.find(this.js_selectors.caselog_tab_close_all).on('click', function(){
					me._onCaseLogCloseAllClick($(this));
				});
				// Click on a closed case log message
				this.element.find(this.js_selectors.entry_group).on('click', '.'+this.css_classes.is_closed + ' ' + this.js_selectors.entry_main_information, function(oEvent){
					me._onCaseLogClosedMessageClick($(this).closest(me.js_selectors.entry));
				});
				// Click on an edits entry long description toggler
				this.element.find(this.js_selectors.edits_entry_long_description_toggler).on('click', function(oEvent){
					me._onEditsTogglerClick(oEvent, $(this).closest(me.js_selectors.entry));
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
			_onTogglerClick: function(oEvent)
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

				const oTabElem = oTabTitleElem.closest(this.js_selectors.tab);

				this.element.find(this.js_selectors.tab).removeClass(this.css_classes.is_active);
				oTabElem.addClass(this.css_classes.is_active);

				if(oTabElem.attr('data-tab-type') === 'caselog')
				{
					this._ShowCaseLogTab(oTabElem.attr('data-caselog-attribute-code'))
				}
				else
				{
					this._ShowActivityTab();
				}
			},
			_onActivityFilterChange: function(oInputElem)
			{
				this._ApplyEntryFilters();
			},
			_onCaseLogOpenAllClick: function(oIconElem)
			{
				const sCaseLogAttCode = oIconElem.closest(this.js_selectors.tab).attr('data-caselog-attribute-code');
				this._OpenAllMessages(sCaseLogAttCode);
			},
			_onCaseLogCloseAllClick: function(oIconElem)
			{
				const sCaseLogAttCode = oIconElem.closest(this.js_selectors.tab).attr('data-caselog-attribute-code');
				this._CloseAllMessages(sCaseLogAttCode);
			},
			_onCaseLogClosedMessageClick: function(oEntryElem)
			{
				this._OpenMessage(oEntryElem);
			},
			_onEditsTogglerClick: function(oEvent, oEntryElem)
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
			_ShowCaseLogTab: function(sCaseLogAttCode)
			{
				// Show only entries from this case log
				this._HideAllEntries();
				this.element.find(this.js_selectors.entry+'[data-entry-caselog-attribute-code="'+sCaseLogAttCode+'"]').removeClass(this.css_classes.is_hidden);
				this._UpdateEntryGroupsVisibility();
				this.element.trigger('show-caselog-tab', ['caselog', sCaseLogAttCode]);
			},
			_ShowActivityTab: function()
			{
				// Show all entries but regarding the current filters
				this._OpenAllMessages();
				this._ShowAllEntries();
				this._ApplyEntryFilters();
				this.element.trigger('show-caselog-tab', 'activity');
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
			_ApplyEntryFilters: function()
			{
				const me = this;

				this.element.find(this.js_selectors.activity_tab_filter).each(function(){
					const aTargetEntryTypes = $(this).attr('data-target-entry-types').split(' ');
					const sCallbackMethod = ($(this).prop('checked')) ? '_ShowEntries' : '_HideEntries';

					for(let iIdx in aTargetEntryTypes)
					{
						me[sCallbackMethod](aTargetEntryTypes[iIdx]);
					}
				});
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
			_AddEntry: function(sEntry, sOrigin)
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
			},
			AddEntry: function(sEntry, sOrigin)
			{
				this._AddEntry(sEntry, sOrigin);
			},
			_GetCaseLogRank: function(sCaseLog)
			{
				let iIdx = 0;
				let oCaselogTab = this.element.find(this.js_selectors.tab +
					'[data-tab-type="caselog"]' +
					'[data-caselog-attribute-code="'+ sCaseLog +'"]'
				);
				if(oCaselogTab.length > 0 && oCaselogTab.attr('data-caselog-rank'))
				{
					iIdx = parseInt(oCaselogTab.attr('data-caselog-rank'));
				}
				return iIdx;
			},
			GetCaseLogRank: function(sCaseLog)
			{
				return this._GetCaseLogRank(sCaseLog);	
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
			}
		});
});
