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
	$.widget( 'itop.navigation_menu',
		{
			// default options
			options:
				{
					active_menu_group: null,
					display_counts: false,
					filter_keyup_throttle: 200,             // In milliseconds
					org_id: ''
				},
			css_classes:
				{
					is_hidden: 'ibo-is-hidden',
					menu_expanded: 'ibo-is-expanded',
					menu_active: 'ibo-is-active',
					menu_filtered: 'ibo-is-filtered',
					menu_group_active: 'ibo-is-active',
					menu_nodes_active: 'ibo-is-active'
				},
			js_selectors:
				{
					menu_toggler: '[data-role="ibo-navigation-menu--toggler"]',
					menu_group: '[data-role="ibo-navigation-menu--menu-group"]',
					menu_drawer: '[data-role="ibo-navigation-menu--drawer"]',
					menu_filter_placeholder: '[data-role="ibo-navigation-menu--menu--placeholder"]',
					menu_filter_input: '[data-role="ibo-navigation-menu--menu-filter-input"]',
					menu_filter_clear: '[data-role="ibo-navigation-menu--menu-filter-clear"]',
					menu_filter_hint: '[data-role="ibo-navigation-menu--menu-filter-hint"]',
					menu_filter_hint_close: '[data-role="ibo-navigation-menu--menu-filter-hint-close"]',
					user_menu_toggler: '[data-role="ibo-navigation-menu--user-menu--toggler"]',
					user_menu_container: '[data-role="ibo-navigation-menu--user-menu-container"]',
					user_menu: '[data-role="ibo-navigation-menu--user-menu-container"] > [data-role="ibo-popover-menu"]',
					menu_node: '[data-role="ibo-navigation-menu--menu-node"]',
					menu_node_label: '[data-role="ibo-navigation-menu--menu-node-label"]',
				},
			filter_throttle_timeout: null,

			// the constructor
			_create: function () {
				this.element.addClass('ibo-navigation-menu');
				this._bindEvents();
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function () {
				this.element.removeClass('ibo-navigation-menu');
			},
			_bindEvents: function () {
				const me = this;
				const oBodyElem = $('body');

				// Click on collapse/expand toggler
				this.element.find(this.js_selectors.menu_toggler).on('click', function (oEvent) {
					me._onTogglerClick(oEvent);
				});
				// Click on menu group
				this.element.find(this.js_selectors.menu_group).on('click', function (oEvent) {
					me._onMenuGroupClick(oEvent, $(this))
				});
				this.element.on('filter_shortcut', function(oEvent){
					me._filterShortcut();
				});
				// Mostly for outside clicks that should close elements
				oBodyElem.on('click', function (oEvent) {
					me._onBodyClick(oEvent);
				});
				// Mostly for hotkeys
				oBodyElem.on('keyup', function (oEvent) {
					me._onBodyKeyUp(oEvent);
				});

				// Menus filter
				// - Input itself
				this.element.find(this.js_selectors.menu_filter_input).on('keyup', function (oEvent) {
					me._onFilterKeyUp(oEvent);
				});
				// - Clear icon
				this.element.find(this.js_selectors.menu_filter_clear).on('click', function (oEvent) {
					me._onFilterClearClick(oEvent);
				});
				// - Hint close
				this.element.find(this.js_selectors.menu_filter_hint_close).on('click', function (oEvent) {
					me._onFilterHintCloseClick(oEvent);
				});

				// External events
				oBodyElem.on('add_shortcut_node.navigation_menu.itop', function (oEvent, oData) {
					me._onAddShortcutNode(oData);
				});
			},

			// Events callbacks
			_onTogglerClick: function(oEvent)
			{
				// Avoid anchor glitch
				oEvent.preventDefault();

				// Toggle menu
				this.element.toggleClass(this.css_classes.menu_expanded);

				// Save state in user preferences
				const sPrefValue = this.element.hasClass(this.css_classes.menu_expanded) ? 'expanded' : 'collapsed';
				SetUserPreference('navigation_menu.expanded', sPrefValue, true);
			},
			_onMenuGroupClick: function(oEvent, oMenuGroupElem)
			{
				// Avoid anchor glitch
				oEvent.preventDefault();

				var sMenuGroupId = oMenuGroupElem.attr('data-menu-group-id');
				if(this._getActiveMenuGroupId() === sMenuGroupId) {
					this._closeDrawer();
				}
				else {
					this._openDrawer(sMenuGroupId);
				}
			},
			_onBodyClick: function(oEvent)
			{
				if(this._checkIfClickShouldCloseDrawer(oEvent))
				{
					this._closeDrawer();
				}
			},
			_onBodyKeyUp: function(oEvent)
			{
			},
			_filterShortcut: function()
			{
				if(this._getActiveMenuGroupId() === null)
				{
					const sFirstMenuGroupId = this.element.find(this.js_selectors.menu_group+':first').attr('data-menu-group-id');
					this._openDrawer(sFirstMenuGroupId);
				}

				this._focusFilter();
			},

			_onFilterKeyUp: function(oEvent)
			{
				const me = this;
				const oInputElem = this.element.find(this.js_selectors.menu_filter_input);
				const sValue = oInputElem.val();

				if((sValue === '') && (oEvent.key === 'Escape'))
				{
					this._closeDrawer();
				}
				else if((sValue === '') || (oEvent.key === 'Escape'))
				{
					this._clearFiltering();
				}
				else {
					// Reset throttle timeout on key stroke
					clearTimeout(this.filter_throttle_timeout);
					this.filter_throttle_timeout = setTimeout(function () {
						me._doFiltering(sValue);
						me.refreshCounts();
					}, this.options.filter_keyup_throttle);
				}
			},
			_onFilterClearClick: function(oEvent)
			{
				// Avoid anchor glitch
				oEvent.preventDefault();

				// Remove current filter value
				this._clearFiltering();
				// Position focus in the input for better UX
				this._focusFilter();
			},
			_onFilterHintCloseClick: function (oEvent) {
				this.element.find(this.js_selectors.menu_filter_hint).hide();

				// Save state in user preferences
				SetUserPreference('navigation_menu.show_filter_hint', false, true);
			},

			_onAddShortcutNode: function (oData) {
				this._addShortcut(oData.parent_menu_node_id, oData.new_menu_node_html_rendering, oData.new_menu_name);
			},

			// Methods
			_checkIfClickShouldCloseDrawer: function (oEvent) {
				if (
					$(oEvent.target.closest(this.js_selectors.menu_drawer)).length === 0
					&& $(oEvent.target.closest('[data-role="ibo-navigation-menu--menu-group"]')).length === 0
					&& $(oEvent.target.closest(this.js_selectors.menu_toggler)).length === 0
				) {
					this._closeDrawer();
				}
			},
			/**
			 * Return the ID of the active menu group, or null if none (typically when the drawer is closed)
			 * @returns {null|*}
			 * @private
			 */
			_getActiveMenuGroupId: function()
			{
				const oActiveMenuGroup = this.element.find('.'+this.css_classes.menu_group_active);
				if(oActiveMenuGroup.length > 0)
				{
					return oActiveMenuGroup.attr('data-menu-group-id');
				}
				else
				{
					return null;
				}
			},
			/**
			 * Clear the current active menu group but does NOT close the drawer
			 * @private
			 */
			_clearActiveMenuGroup: function()
			{
				this.element.find('[data-role="ibo-navigation-menu--menu-group"]').removeClass(this.css_classes.menu_group_active);
				this.element.find('[data-role="ibo-navigation-menu--menu-nodes"]').removeClass(this.css_classes.menu_nodes_active);
			},
			/**
			 * Open the drawer and set sMenuGroupId as the current active menu group
			 * @param sMenuGroupId string
			 * @private
			 */
			_openDrawer: function (sMenuGroupId) {
				this.refreshCounts();
				this._clearActiveMenuGroup();
				// Note: This causes the filter to be cleared event when using the hotkey to reopen a previously filled filter
				this._clearFiltering();

				// Set new active group
				this.element.find('[data-role="ibo-navigation-menu--menu-group"][data-menu-group-id="' + sMenuGroupId + '"]').addClass(this.css_classes.menu_group_active);
				this.element.find('[data-role="ibo-navigation-menu--menu-nodes"][data-menu-group-id="' + sMenuGroupId + '"]').addClass(this.css_classes.menu_nodes_active);

				// Set menu as active
				this.element.addClass(this.css_classes.menu_active);
			},
			/**
			 * Close the drawer after clearing the active menu group
			 * @private
			 */
			_closeDrawer: function () {
				this._clearActiveMenuGroup();

				// Set menu as non active
				this.element.removeClass(this.css_classes.menu_active);
			},

			// Menus filter methods
			_focusFilter: function () {
				this.element.find(this.js_selectors.menu_filter_input)
					.trigger('click')
					.trigger('focus');
			},
			/**
			 * Remove the current filter value and reset the menu nodes display
			 * @private
			 */
			_clearFiltering: function () {
				this.element.find(this.js_selectors.menu_filter_input).val('');

				// Reset display of everything
				// Note: We work on the 'display' property directly as there is a CSS rule managing the visibility of the active menu group
				this.element.find('[data-role="ibo-navigation-menu--menu-nodes"]').css('display', '');
				this.element.find('[data-role="ibo-navigation-menu--menu-node"]').css('display', '');
				
				this.element.find(this.js_selectors.menu_filter_placeholder).css('display', 'none');

				// Mark menu as unfiltered
				this.element.removeClass(this.css_classes.menu_filtered);
			},
			/**
			 * Filter the displayed menu nodes regarding the current filter value
			 * @param sRawFilterValue string
			 * @private
			 */
			_doFiltering: function(sRawFilterValue)
			{
				const me = this;
				const aFilterValueParts = this._formatValueForFilterComparison(sRawFilterValue).split(' ');
				let bHasAnyMatch = false;

				// Mark menu as filtered
				this.element.addClass(this.css_classes.menu_filtered);

				// Hide everything
				this.element.find('[data-role="ibo-navigation-menu--menu-nodes"]').hide();
				this.element.find('[data-role="ibo-navigation-menu--menu-node"]').hide();

				// Show matching menu node
				this.element.find('[data-role="ibo-navigation-menu--menu-node"]').each(function () {
					// Note: We don't filter on data-role="ibo-navigation-menu--menu-node-label" on purpose so we can also filter the counters
					const sNodeValue = me._formatValueForFilterComparison($(this).children('[data-role="ibo-navigation-menu--menu-node-title"]:first').text());
					let bMatches = true;

					// On first non matching part, we consider that the menu node is not a match
					for (let iIdx in aFilterValueParts) {
						if (sNodeValue.indexOf(aFilterValueParts[iIdx]) === -1) {
							bMatches = false;
							break;
						}
					}

					if (bMatches) {
						me.element.find(me.js_selectors.menu_filter_placeholder).css('display', 'none');
						bHasAnyMatch = true;
						// Note: Selector must be recursive
						$(this).parents('[data-role="ibo-navigation-menu--menu-nodes"], [data-role="ibo-navigation-menu--menu-node"]').show();
						$(this).show();
					}
				});
				if(!bHasAnyMatch)
				{
					this.element.find(this.js_selectors.menu_filter_placeholder).css('display', '');
				}
			},
			/**
			 * Format sOriginalValue for an easier comparison (change accents, capitalized letters, ...)
			 *
			 * @param sOriginalValue string
			 * @returns string
			 * @private
			 */
			_formatValueForFilterComparison: function (sOriginalValue) {
				return sOriginalValue.toLowerCase().latinise();
			},
			/**
			 * Refresh count badges for OQL menus
			 */
			refreshCounts: function () {
				const me = this;
				if (this.options.display_counts) {
					$.ajax({
						method: "POST",
						url: GetAbsoluteUrlAppRoot() + 'pages/ajax.render.php',
						data: {
							operation: "get_menus_count",
							c: { org_id: me.options.org_id }
						},
						dataType: "json"
					})
						.done(function (data) {
							if (data.code === "done") {
								for (const [key, value] of Object.entries(data.counts)) {
									let menuEntry = me.element.find('[data-menu-id="'+key+'"]');
									menuEntry.html(value);
									menuEntry.removeClass(me.css_classes.is_hidden);
								}
							}
						});
				}
			},
			/**
			 * @param sParentMenuNodeId {string} ID of the parent menu node the shortcut should be added to
			 * @param sNewMenuNodeHtmlRendering {string} HTML rendering of the new menu node to add
			 * @param sNewMenulabel {string} Label of the menu node to add
			 * @return {boolean}
			 */
			_addShortcut: function (sParentMenuNodeId, sNewMenuNodeHtmlRendering, sNewMenulabel) {
				const oNewMenuNodeContainerElem = this.element.find(this.js_selectors.menu_node+'[data-menu-node-id="'+sParentMenuNodeId+'"]');
				if (oNewMenuNodeContainerElem.length === 0) {
					return false;
				}
				let oNewMenuNodeContainerElemUL = oNewMenuNodeContainerElem.find('ul');
				if (oNewMenuNodeContainerElemUL.length === 0) {
					oNewMenuNodeContainerElem.append('<ul>'+sNewMenuNodeHtmlRendering+'</ul>');
				} else {
					let oChildrenElem = oNewMenuNodeContainerElem.find('li');
					let iIndex = 0;
					let bInsertToDo = true;
					while (bInsertToDo && iIndex < oChildrenElem.length) {
						let oCurrentChild = oChildrenElem.eq(iIndex);
						if (oCurrentChild.find(this.js_selectors.menu_node_label).attr('title').toUpperCase() > sNewMenulabel.toUpperCase()) {
							oCurrentChild.before(sNewMenuNodeHtmlRendering);
							bInsertToDo = false;
						}
						iIndex++;
					}
					if (bInsertToDo) {
						oNewMenuNodeContainerElemUL.append(sNewMenuNodeHtmlRendering);
					}
				}
				return true;
			}
		});
});
