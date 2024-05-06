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
$(function () {
	// the widget definition, where 'itop' is the namespace,
	// 'panel' the widget name
	$.widget('itop.ui_block',
		{
			// default options
			options: {},
			css_classes: {
				is_sticking: 'ibo-is-sticking',
				is_vertical: 'ibo-is-vertical',
			},
			js_selectors: {
				// Selectors that target any elements in the DOM
				global: {
					modal: '.ui-dialog',
					modal_content: '.ui-dialog-content',
				},
				// Selectors that target only the elements of this block
				block: {}
			},

			// the constructor
			_create: function () {
				this._initializeMarkup();
				this._bindEvents();
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function () {
			},

			/**
			 * Initialize some markup dynamically when the UIBlock needs it
			 * @return {void}
			 * @private
			 */
			_initializeMarkup: function () {
				// Meant for overloading
			},
			/**
			 * Bind events relative to the UIBlock
			 * @return {void}
			 * @private
			 */
			_bindEvents: function () {
				// Meant for overloading
			},
		});
});
