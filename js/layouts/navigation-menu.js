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
	$.widget( 'itop.navigation_menu',
		{
			// default options
			options:
			{
				init_expanded: false,
				active_menu_group: null,
			},
			css_classes:
			{
				menu_expanded: 'ibo-navigation-menu--is-expanded',
				menu_active: 'ibo-navigation-menu--is-active',
				menu_group_active: 'ibo-navigation-menu--menu-group--is-active',
				menu_nodes_active: 'ibo-navigation-menu--menu-nodes--is-active'
			},

			// the constructor
			_create: function()
			{
				this.element.addClass('ibo-navigation-menu');
				this._bindEvents();
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function()
			{
				this.element.removeClass('ibo-navigation-menu');
			},
			_bindEvents: function()
			{
				var me = this;
				var oBodyElem = $('body');

				// Click on collapse/expand toggler
				this.element.find('[data-role="ibo-navigation-menu--toggler"]').on('click', function(oEvent){
					me._onTogglerClick(oEvent);
				});
				// Click on menu group
				this.element.find('[data-role="ibo-navigation-menu--menu-group"]').on('click', function(oEvent){
					me._onMenuGroupClick(oEvent, $(this))
				});
				// Mostly for outside clicks that should close elements
				oBodyElem.on('click', function(oEvent){
					me._onBodyClick(oEvent);
				});
			},

			// Events callbacks
			_onTogglerClick: function(oEvent)
			{
				// Avoid anchor glitch
				oEvent.preventDefault();

				this.element.toggleClass(this.css_classes.menu_expanded);
				// TODO: Save preference
			},
			_onMenuGroupClick: function(oEvent, oMenuGroupElem)
			{
				// Avoid anchor glitch
				oEvent.preventDefault();

				var sMenuGroupId = oMenuGroupElem.attr('data-menu-group-id');
				this._openDrawer(sMenuGroupId);
			},
			_onBodyClick: function(oEvent)
			{
				if(this._checkIfClickShouldCloseDrawer(oEvent))
				{
					this._closeDrawer();
				}
			},

			// Methods
			_checkIfClickShouldCloseDrawer: function(oEvent)
			{
				if(
					$(oEvent.target.closest('[data-role="ibo-navigation-menu--drawer"]')).length === 0
					&& $(oEvent.target.closest('[data-role="ibo-navigation-menu--menu-group"]')).length === 0
					&& $(oEvent.target.closest('[data-role="ibo-navigation-menu--toggler"]')).length === 0
				)
				{
					this._closeDrawer();
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
			_openDrawer: function(sMenuGroupId)
			{
				this._clearActiveMenuGroup();

				// Set new active group
				this.element.find('[data-role="ibo-navigation-menu--menu-group"][data-menu-group-id="'+sMenuGroupId+'"]').addClass(this.css_classes.menu_group_active);
				this.element.find('[data-role="ibo-navigation-menu--menu-nodes"][data-menu-group-id="'+sMenuGroupId+'"]').addClass(this.css_classes.menu_nodes_active);

				// Set menu as active
				this.element.addClass(this.css_classes.menu_active);
			},
			/**
			 * Close the drawer after clearing the active menu group
			 * @private
			 */
			_closeDrawer: function()
			{
				this._clearActiveMenuGroup();

				// Set menu as non active
				this.element.removeClass(this.css_classes.menu_active);
			}
		});
});
