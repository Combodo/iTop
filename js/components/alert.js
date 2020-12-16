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
	$.widget( 'itop.alert',
		{
			// default options
			options:
				{},
			css_classes:
				{
					opened: 'ibo-is-opened',
				},
			js_selectors:
				{
					close_button: '[data-role="ibo-alert--close-button"]',
					minimize_button: '[data-role="ibo-alert--minimize-button"]',
					maximize_button: '[data-role="ibo-alert--maximize-button"]',
					title: '[data-role="ibo-alert--title"]',
				},

			// the constructor
			_create: function () {
				this._bindEvents();
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function () {
			},
			_bindEvents: function () {
				const me = this;
				const oBodyElem = $('body');

				this.element.find(this.js_selectors.close_button).on('click', function (oEvent) {
					me._onCloseButtonClick(oEvent);
				});
				this.element.find(this.js_selectors.minimize_button).on('click', function (oEvent) {
					me._onMinimizeButtonClick(oEvent);
				});
				this.element.find(this.js_selectors.maximize_button).on('click', function (oEvent) {
					me._onMaximizeButtonClick(oEvent);
				});
				this.element.find(this.js_selectors.title).on('click', function (oEvent) {
					me._onToggleVisibility(oEvent);
				});
			},
			_onCloseButtonClick: function (oEvent) {
				this.element.hide();
			},
			_onMinimizeButtonClick: function (oEvent) {
				this.element.removeClass(this.css_classes.opened);
			},
			_onMaximizeButtonClick: function (oEvent) {
				this.element.addClass(this.css_classes.opened);
			},
			_onToggleVisibility: function (oEvent) {
				this.element.toggleClass(this.css_classes.opened);
			}
		})
});
