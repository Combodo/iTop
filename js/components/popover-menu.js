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
	// the widget definition, where 'itop' is the namespace,
	// 'popover-menu' the widget name
	$.widget( 'itop.popover_menu',
		{
			// default options
			options:
			{
				toggler: '',
			},
			css_classes:
			{
				opened: 'ibo-is-opened',
			},
			js_selectors:
			{
				menu: '[data-role="ibo-popover-menu"]',
				section: '[data-role="ibo-popover-menu--section"]',
				item: '[data-role="ibo-popover-menu--item"]',
			},

			// the constructor
			_create: function()
			{
				this._bindEvents();
				this._closePopup();
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function()
			{
			},
			_bindEvents: function()
			{
				const me = this;
				const oBodyElem = $('body');

				this.element.find(this.js_selectors.item).on('click', function(oEvent){
					me._closePopup();
				});
				
				// Mostly for outside clicks that should close elements
				oBodyElem.on('click', function(oEvent){
					me._onBodyClick(oEvent);
				});
			},

			// Methods
			_onBodyClick: function(oEvent)
			{
				if($(oEvent.target.closest(this.js_selectors.menu)).length === 0 && $(oEvent.target.closest(this.options.toggler)).length === 0)
				{
					this._closePopup();
				}
			},
			_openPopup: function()
			{
				this.element.addClass(this.css_classes.opened);
			},
			_closePopup: function()
			{
				this.element.removeClass(this.css_classes.opened);
			},
			openPopup: function()
			{
				this._openPopup();
			},
			closePopup: function()
			{
				this._closePopup();
			},
		});
});
