/*
 * Copyright (C) 2013-2021 Combodo SARL
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
	$.widget('itop.panel',
		{
			// default options
			options:
				{
					// The viewport element (jQuery object) to consider for the panel
					viewport_elem: null,
					// Whether the header should stay visible or not during the scroll in the "viewport_elem"
					is_header_visible_on_scroll: false,
				},
			css_classes:
				{
					has_sticky_header: 'ibo-has-sticky-header',
					is_sticking: 'ibo-is-sticking',
					sticky_sentinel: 'ibo-sticky-sentinel',
					sticky_sentinel_top: 'ibo-sticky-sentinel-top',
				},
			js_selectors:
				{
					modal: '.ui-dialog',
					modal_content: '.ui-dialog-content',
					panel_header: '[data-role="ibo-panel--header"]:first',
					panel_header_sticky_sentinel_top: '[data-role="ibo-panel--header--sticky-sentinel-top"]',
				},
			// {ScrollMagic.Controller} SM controller for the sticky header
			sticky_header_controller: null,

			// the constructor
			_create: function () {
				this._initializeMarkup();
				this._bindEvents();
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function () {
			},
			_initializeMarkup: function () {
				const me = this;

				if (this._isHeaderVisibleOnScroll()) {
					this.element.addClass(this.css_classes.has_sticky_header);

					// Add sentinel to the markup to detect when the element changes between scrolling & sticked states
					$('<div></div>')
						.addClass(this.css_classes.sticky_sentinel)
						.addClass(this.css_classes.sticky_sentinel_top)
						.attr('data-role', 'ibo-panel--header--sticky-sentinel-top')
						.prependTo(this.element);

					this._updateStickyHeaderHandler();
				}
			},
			_bindEvents: function () {
				const me = this;
				const oBodyElem = $('body');

				if (this._isHeaderVisibleOnScroll()) {
					// When a modal opens, it could have been for this panel. As the panel is moved into the modal's markup after this JS widget is instantiated
					// the viewport is not the right one and we need to update it.
					oBodyElem.on('dialogopen', function(){
						me._updateStickyHeaderHandler();
					});
				}
			},

			// Stickey header helpers
			/**
			 * Create or update an handler on the header to toggle its "sticky" state.
			 * Update is needed when the panel was moved to other DOM node.
			 * @private
			 */
			_updateStickyHeaderHandler: function () {
				const me = this;

				// Determine in which kind of container the panel is
				let oNewViewportElem = this.element.scrollParent()[0];

				// If viewport hasn't changed, there is no need to refresh the SM controller
				if (oNewViewportElem === this.options.viewport_elem) {
					return;
				}

				// Update the reference viewport
				this.options.viewport_elem = oNewViewportElem;

				// Clean SM controller if there was already one
				if (null !== this.sticky_header_controller) {
					this.sticky_header_controller.destroy(true);
				}

				// Prepare SM controller
				this.sticky_header_controller = new ScrollMagic.Controller({
					container: this.options.viewport_elem,
				});

				let oSMScene = new ScrollMagic.Scene({
					triggerElement: this.element.find(this.js_selectors.panel_header_sticky_sentinel_top)[0],
					triggerHook: 0,
					duration: this.element.outerHeight(),
					offset: this.element.find(this.js_selectors.panel_header_sticky_sentinel_top).outerHeight()
				})
					.on('enter', function(){
						me._onHeaderBecomesSticky();
					})
					.on('leave', function(){
						me._onHeaderStopsBeingSticky();
					})
					.addTo(this.sticky_header_controller);
			},
			_onHeaderBecomesSticky: function () {
				this.element.find(this.js_selectors.panel_header).addClass(this.css_classes.is_sticking);
			},
			_onHeaderStopsBeingSticky: function () {
				this.element.find(this.js_selectors.panel_header).removeClass(this.css_classes.is_sticking);
			},

			// Helpers
			/**
			 * @return {boolean} True if the panel should have its header visible during scroll
			 * @private
			 */
			_isHeaderVisibleOnScroll: function () {
				return this.options.is_header_visible_on_scroll;
			},
		});
});
