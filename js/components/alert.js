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
	// the widget definition, where 'itop' is the namespace,
	// 'breadcrumbs' the widget name
	$.widget( 'itop.alert',
		{
			// default options
			options:
				{
					bOpenedByDefault: true,
					collapsibleStateStorageKey: null, // if non null will save collapsible state
				},
			css_classes:
				{
					opened: 'ibo-is-opened',
				},
			js_selectors:
				{
					close_button: '[data-role="ibo-alert--close-button"]',
					collapse_toggler: '[data-role="ibo-alert--collapse-toggler"]',
				},

			// the constructor
			_create: function () {
				this._bindEvents();

				let bIsSectionOpenedInitially = GetUserPreferenceAsBoolean(
					this.options.collapsibleStateStorageKey,
					this.options.bOpenedByDefault
				);
				if (bIsSectionOpenedInitially) {
					this.element.addClass(this.css_classes.opened);
				} else {
					this.element.removeClass(this.css_classes.opened);
				}
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
				this.element.find(this.js_selectors.collapse_toggler).on('click', function (oEvent) {
					me._onCollapseTogglerClick(oEvent);
				});
			},
			_onCloseButtonClick: function (oEvent) {
				this.element.hide();
			},
			_onCollapseTogglerClick: function (oEvent) {
				this.element.toggleClass(this.css_classes.opened);

				if (this.options.collapsibleStateStorageKey) {
					SetUserPreference(
						this.options.collapsibleStateStorageKey,
						this.element.hasClass(this.css_classes.opened),
						true
					);
				}
			}
		})
});
