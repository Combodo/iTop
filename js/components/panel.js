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
	$.widget('itop.panel', $.itop.ui_block,
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
					sticky_sentinel: 'ibo-sticky-sentinel',
					sticky_sentinel_top: 'ibo-sticky-sentinel-top',
				},
			js_selectors: {
				global: {
					fullscreen_elements: '.ibo-is-fullscreen',
				},
				block: {
					panel_header: '[data-role="ibo-panel--header"]:first',
					panel_header_sticky_sentinel_top: '[data-role="ibo-panel--header--sticky-sentinel-top"]:first',
					panel_body: '[data-role="ibo-panel--body"]:first',
					tab_container: '[data-role="ibo-tab-container"]:first',
					tab_container_tabs_list: '[data-role="ibo-tab-container--tabs-list"]:first',
				}
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

					// Observe the panel resizes in order to adjust the tabs list; only necessary when header is sticky for now
					if(window.ResizeObserver) {
						const oPanelRO = new ResizeObserver(function(){
							me._updateTabsListPosition();
						});
						oPanelRO.observe(this.element[0]);
					}
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

				// If viewport hasn't changed, there is no need to refresh the SM controller
				let oNewViewportElem = this.element.scrollParent()[0];
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
					// Traduction: As soon as the header's top sentinel...
					triggerElement: this.element.find(this.js_selectors.block.panel_header_sticky_sentinel_top)[0],
					//  ... leaves the viewport...
					triggerHook: 0,
					offset: this.element.find(this.js_selectors.block.panel_header_sticky_sentinel_top).outerHeight()
				})
					// ... we consider the header as sticking...
					.on('enter', function () {
						// N°4631 - If a non-intersecting element is fullscreen, we do nothing
						if ($(me.js_selectors.global.fullscreen_elements).length > 0) {
							return;
						}
						me._onHeaderBecomesSticky();
					})
					// ... and when it comes back in the viewport, it stops.
					.on('leave', function () {
						// N°4631 - If a non-intersecting element is fullscreen, we do nothing
						if ($(me.js_selectors.global.fullscreen_elements).length > 0) {
							return;
						}
						me._onHeaderStopsBeingSticky();
					})
					.addTo(this.sticky_header_controller);
			},
			_onHeaderBecomesSticky: function () {
				this.element.find(this.js_selectors.block.panel_header).addClass(this.css_classes.is_sticking);
				if (this._hasTabContainer()) {
					this._updateTabsListPosition(false /* Need to wait for the header transition to end */);
				}
			},
			_onHeaderStopsBeingSticky: function () {
				const fPanelBottomPosition = this.element.position().top + this.element.find(this.js_selectors.block.panel_header_sticky_sentinel_top).outerHeight();
				const fViewportVerticalScrollPosition = this.options.viewport_elem.scrollHeight - this.options.viewport_elem.clientHeight;

				// Test to prevent the screen from flashing (cf bug 4124)
				if (fPanelBottomPosition < fViewportVerticalScrollPosition) {
					this.element.find(this.js_selectors.block.panel_header).removeClass(this.css_classes.is_sticking);
					if (this._hasTabContainer()) {
						this._updateTabsListPosition(false /* Need to wait for the header transition to end */);
					}
				}
			},
			/**
			 * Update the position of the tabs list so it is consistent with the header, which is important when the header is sticky
			 *
			 * @param bImmediate {boolean} Should the position be updated immediatly or delayed (typically if we have to wait for a transition to end)
			 * @private
			 */
			_updateTabsListPosition: function(bImmediate = true) {
				// Vertical tab container is not supported yet
				if(this._isTabContainerVertical()) {
					return;
				}

				const me = this;
				const oTabsListElem = this.element.find(this.js_selectors.block.tab_container_tabs_list);

				if(this._isHeaderSticking()){
					// Unfortunately for now the timeout is hardcoded as we don't know how to get notified when the *CSS* transition is done.
					const iTimeout = bImmediate ? 0 : 300;
					setTimeout(function(){
						const oHeaderElem = me.element.find(me.js_selectors.block.panel_header);
						const oHeaderOffset = oHeaderElem.offset();
						const iHeaderWidth = oHeaderElem.outerWidth();
						const iHeaderHeight = oHeaderElem.outerHeight();
						const iPanelBorderWidth = parseInt(me.element.find(me.js_selectors.block.panel_body).css('border-left-width'));

						oTabsListElem
							.css('top', oHeaderOffset.top + iHeaderHeight)
							.css('left', oHeaderOffset.left + iPanelBorderWidth)
							.css('width', iHeaderWidth - (2 * iPanelBorderWidth))
							.addClass(me.css_classes.is_sticking);
					}, iTimeout);
				} else {
					// Reset to default style
					oTabsListElem
						.css('top', '')
						.css('left', '')
						.css('width', '')
						.removeClass(me.css_classes.is_sticking);
				}
			},

			// Helpers
			/**
			 * @return {boolean} True if the panel should have its header visible during scroll
			 * @private
			 */
			_isHeaderVisibleOnScroll: function () {
				return this.options.is_header_visible_on_scroll;
			},
			/**
			 * @return {boolean} True if the header is currently sticking
			 * @private
			 */
			_isHeaderSticking: function () {
				return this.element.find(this.js_selectors.block.panel_header).hasClass(this.css_classes.is_sticking);
			},
			/**
			 * @return {boolean} True if the panel has a tab container
			 * @private
			 */
			_hasTabContainer: function () {
				return this.element.find(this.js_selectors.block.tab_container).length > 0;
			},
			/**
			 * @return {boolean} True if the panel has a tab container and it is vertical, false otherwise
			 * @private
			 */
			_isTabContainerVertical: function () {
				if(!this._hasTabContainer()) {
					return false;
				}
				return this.element.find(this.js_selectors.block.tab_container).hasClass(this.css_classes.is_vertical);
			},
		});
});
