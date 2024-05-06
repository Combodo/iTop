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
	$.widget( 'itop.global_search',
	{
		// default options
		options:
		{
			init_opened: false,
		},
		css_classes:
		{
			opened: 'ibo-is-opened',
			hidden: 'ibo-is-hidden',
		},
		js_selectors:
		{
			icon: '[data-role="ibo-global-search--icon"]',
			form: '[data-role="ibo-global-search--head"]',
			input: '[data-role="ibo-global-search--input"]',
			compartment_element: '[data-role="ibo-global-search--compartment-element"]',
		},
   
		// the constructor
		_create: function()
		{
			this.element.addClass('ibo-global-search');

			if(this.options.init_opened === true) {
				// Make sure the class is there
				this.element.addClass(this.css_classes.opened);

				// Solution for the focus at the end of the input found here https://stackoverflow.com/questions/4609405/set-focus-after-last-character-in-text-box
				this.element.find(this.js_selectors.input).trigger('focus');
				const sTmpVal = this.element.find(this.js_selectors.input).val();
				this.element.find(this.js_selectors.input)
					.val('')
					.val(sTmpVal);
			}

			this._bindEvents();
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('ibo-global-search');
		},
		_bindEvents: function()
		{
			const me = this;
			const oBodyElem = $('body');

			this.element.find(this.js_selectors.icon).on('click', function(oEvent){
				me._onIconClick(oEvent);
			});
			this.element.find(this.js_selectors.form).on('submit', function(oEvent){
				me._onFormSubmit(oEvent);
			});
			this.element.find(this.js_selectors.compartment_element).on('click', function(oEvent){
				me._onCompartmentElementClick(oEvent, $(this));
			});
			this.element.on('open_drawer', function(oEvent){
				me._onIconClick(oEvent);
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
		_onIconClick: function(oEvent)
		{
			// Avoid anchor glitch
			oEvent.preventDefault();

			if(this._isDrawerOpened())
			{
				this._closeDrawer();
			}
			else
			{
				this._openDrawer();
				// Focus in the input for a better UX
				this._setFocusOnInput();
			}
		},
		_onFormSubmit: function(oEvent)
		{
			const sSearchValue = this.element.find(this.js_selectors.input).val();

			// Submit form only if something in the input
			if(sSearchValue === '')
			{
				oEvent.preventDefault();
			}
		},
		_onCompartmentElementClick: function(oEvent, oElementElem)
		{
			// Avoid anchor glitch
			oEvent.preventDefault();

			const sElementQuery = oElementElem.attr('data-query-raw');
			this.element.find(this.js_selectors.input)
				.val(sElementQuery)
				.closest(this.js_selectors.form).trigger('submit');
		},
		_onBodyClick: function(oEvent)
		{
			if($(oEvent.target.closest('.ibo-global-search')).length === 0)
			{
				this._closeDrawer();
			}
		},
		_onBodyKeyUp: function(oEvent)
		{
		},

		// Methods
		_isDrawerOpened: function()
		{
			return this.element.hasClass(this.css_classes.opened);
		},
		_openDrawer: function()
		{
			this.element.find(this.js_selectors.compartment_element).removeClass(this.css_classes.hidden);
			this.element.addClass(this.css_classes.opened);
		},
		_closeDrawer: function()
		{
			this.element.removeClass(this.css_classes.opened);
			//Note: Elements are hidden to avoid having the keyboard navigation "TAB" passing throught them when they are not displayed
			this.element.find(this.js_selectors.compartment_element).addClass(this.css_classes.hidden);
		},
		_setFocusOnInput: function()
		{
			this.element.find(this.js_selectors.input).trigger('focus');
		}
	});
});
