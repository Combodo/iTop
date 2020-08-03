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
	// 'breadcrumbs' the widget name
	$.widget( 'itop.popover-menu',
		{
			// default options
			options:
				{

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
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function()
			{
				this.element.removeClass('ibo-quick-create');
			},
			_bindEvents: function()
			{
				const me = this;
				const oBodyElem = $('body');

				this.element.find(this.js_selectors.item).on('click', function(oEvent){
					me._closePopup();
				});
			},

			// Methods
			_isDrawerPopup: function()
			{
				return this.element.hasClass(this.css_classes.opened);
			},
			_openPopup: function()
			{
				this.element.addClass(this.css_classes.opened);
			},
			_closePopup: function()
			{
				this.element.removeClass(this.css_classes.opened);
			},
		});
});
