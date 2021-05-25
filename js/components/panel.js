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
					sticky_sentinel_bottom: 'ibo-sticky-sentinel-bottom',
				},
			js_selectors:
				{
					modal: '.ui-dialog',
					modal_content: '.ui-dialog-content',
					panel_header: '[data-role="ibo-panel--header"]:first',
					panel_header_sticky_sentinel_top: '[data-role="ibo-panel--header--sticky-sentinel-top"]',
					panel_header_sticky_sentinel_bottom: '[data-role="ibo-panel--header--sticky-sentinel-bottom"]',
				},
			// {IntersectionObserver} Observer for the sticky header
			sticky_header_observer: null,

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

					// Add sentinels to the markup to detect when the element changes between scrolling & sticked states
					$('<div></div>')
						.addClass(this.css_classes.sticky_sentinel)
						.addClass(this.css_classes.sticky_sentinel_top)
						.attr('data-role', 'ibo-panel--header--sticky-sentinel-top')
						.prependTo(this.element);
					$('<div></div>')
						.addClass(this.css_classes.sticky_sentinel)
						.addClass(this.css_classes.sticky_sentinel_bottom)
						.attr('data-role', 'ibo-panel--header--sticky-sentinel-bottom')
						.appendTo(this.element);
				}
			},
			_bindEvents: function () {
				const me = this;
				const oBodyElem = $('body');

				if (this._isHeaderVisibleOnScroll()) {
					this._observeStickyHeaderChanges();

					// When a modal opens, it could have been for this panel. As the panel is moved into the modal's markup after this JS widget is instantiated
					// the viewport is not the right one and we need to update it.
					oBodyElem.on('dialogopen', function(){
						me._observeStickyHeaderChanges();
					});
				}
			},

			// Stickey header helpers
			/**
			 * Instantiate an observer on the header to toggle its "sticky" state and fire an event
			 * @private
			 */
			_observeStickyHeaderChanges: function () {
				const me = this;

				// Determine in which kind of container the panel is
				let oNewViewportElem = null;
				// - In a modal
				if (this.element.closest(this.js_selectors.modal_content).length > 0) {
					oNewViewportElem = this.element.closest(this.js_selectors.modal_content)[0];
				}
				// - In a standard page
				else if (this.element.closest('#ibo-center-container').length > 0) {
					oNewViewportElem = this.element.closest('#ibo-center-container')[0];
				}

				// If viewport hasn't changed, there is no need to refresh the observer
				if (oNewViewportElem === this.options.viewport_elem) {
					return;
				}

				// Update the reference viewport
				this.options.viewport_elem = oNewViewportElem;

				// Clean observer if there was already one
				if (null !== this.sticky_header_observer) {
					this.sticky_header_observer.disconnect();
				}

				// Prepare observer options
				const oOptions = {
					root: this.options.viewport_elem,
					threshold: 0,
				};

				// Instantiate observer and callback for the top sentinel
				this.sticky_header_observer = new IntersectionObserver(function (aEntries, oObserver) {
					for (const oEntry of aEntries) {
						const oSentinelInfo = oEntry.boundingClientRect;
						const oRootInfo = oEntry.rootBounds;

						// Started sticking.
						if (oSentinelInfo.bottom < oRootInfo.top) {
							me._onHeaderBecomesSticky();

						}

						// Stopped sticking.
						if (oSentinelInfo.bottom >= oRootInfo.top &&
							oSentinelInfo.bottom < oRootInfo.bottom) {
							me._onHeaderStopsBeingSticky();
						}
					}
				}, oOptions);
				this.sticky_header_observer.observe(this.element.find(this.js_selectors.panel_header_sticky_sentinel_top)[0]);
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
