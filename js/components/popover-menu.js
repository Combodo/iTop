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
	// 'popover-menu' the widget name
	$.widget( 'itop.popover_menu',
		{
			// default options
			options:
			{
				// Valid JS selector of the DOM element toggling the menu on click
				toggler: '',
				// Container element of the menu. Can be either 'parent' (default, better performance) or 'body' (use it if the menu gets cut by the hidden overflow of its parent).
				container: 'parent',
				// Position of the menu, relative to a DOM target element. Default target is 'toggler', but any valid JS selector is also accepted
				position: {
					// DOM element used to compute the menu relative position from. Value be 'toggler' to use the 'toggler' option or any valid JS selector.
					target: 'toggler',
					// Relative vertical position of the menu from the target. Value can be 'below' or 'above' for the menu to be strictly below/above the target,
					// or a JS expression to be evaluated that must return pixels (eg. (oTargetPos.top + oTarget.outerHeight(true)) + 'px')
					vertical: 'below',
					// Relative horizontal position of the menu from the target. Value can be 'align_inner_left' or 'align_inner_right' for the menu to be aligned with the target border,
					// or a JS expression to be evaluated that must return pixels (eg. (oTargetPos.left + oTarget.outerWidth(true) - popover.width()) + 'px')
					// JS vars that can be used in the expression:
					// - oElem
					// - oTargetElem
					// - oTargetPos
					horizontal: 'align_inner_right',
				},
				add_visual_hint_to_toggler: false
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
			_create: function () {
				// Consistency checks
				// - When target position set to 'toggler', ensure that a toggler is indeed set
				if (('toggler' === this.options.position.target) && (false === this._hasToggler())) {
					CombodoJSConsole.Error('Could not instantiate menu as the position target is set to "toggler" but no toggler set');
				}

				// Build markup
				if (true === this.options.add_visual_hint_to_toggler) {
					this._addVisualHintToToggler();
				}
				if ('body' === this.options.container) {
					this.element.appendTo($('body'));
				}

				this._bindEvents();
				this._closePopup();
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function () {
			},
			_bindEvents: function () {
				const me = this;
				const oBodyElem = $('body');

				// Toggler
				if (true === this._hasToggler()) {
					oBodyElem.find(this.options.toggler).on('click', function (oEvent) {
						me._onTogglerClick(oEvent);
					});
				}

				// Force menu to close on scroll when it is positioned on the body, otherwise it will not follow it's target and it will look buggy.
				// Also, we decided not to update to position during scroll for to avoid performance drop.
				if ('body' === this.options.container) {
					// Important: This event is not bind using jQuery but the native method so we can set the "passive" option to minimize performance drops
					// as the 'scroll' event is extremely CPU consuming.
					// TODO 3.0.0: Make it work, event seems not to be triggered on user scroll
					// window.addEventListener('scroll', function () {
					// 	me._onBodyScroll();
					// }, {
					// 	passive: true
					// })
				}

				// Menu items
				this.element.find(this.js_selectors.item).on('click', function (oEvent) {
					me._closePopup();
				});

				// Mostly for outside clicks that should close elements
				oBodyElem.on('click', function (oEvent) {
					me._onBodyClick(oEvent);
				});
			},

			// Events callbacks
			_onTogglerClick: function (oEvent) {
				// Avoid anchor / link default behavior
				oEvent.preventDefault();

				// Only recompute position when the menu is closed and about to be opened
				if (false === this._isOpened()) {
					this._applyPosition();
				}

				this.togglePopup();
			},
			_onBodyScroll: function () {
				if (true === this._isOpened()) {
					this._closePopup();
				}
			},
			/**
			 * @return {void}
			 * @param oEvent
			 * @private
			 */
			_onBodyClick: function (oEvent) {
				if (false === this._isOpened()) {
					return;
				}

				if ($(oEvent.target.closest(this.js_selectors.menu)).length === 0 &&
					// Menu without a toggler cannot be closed by an "outside" click, on programatically (same way it was opened in the first place)
					((true === this._hasToggler()) && ($(oEvent.target.closest(this.options.toggler)).length === 0))) {
					this._closePopup();
				}
			},

			// Methods
			/**
			 * @return {boolean} True if there is a toggler selector for the popover menu
			 * @private
			 */
			_hasToggler: function () {
				if (('' === this.options.toggler) || (null === this.options.toggler)) {
					return false;
				}

				if ($(this.options.toggler).length === 0) {
					return false;
				}

				return true;
			},
			/**
			 * Add a visual hint (caret) on the toggler
			 *
			 * @return {boolean}
			 * @private
			 */
			_addVisualHintToToggler: function () {
				if (false === this._hasToggler()) {
					return false;
				}

				$(this.options.toggler).append($(`<span class="ibo-popover-menu--toggler-visual-hint"><span class="fas fa-caret-down"></span></span>`));

				return true;
			},
			/**
			 * @return {boolean} True if the menu is currently opened
			 * @private
			 */
			_isOpened: function () {
				return this.element.hasClass(this.css_classes.opened);
			},
			/**
			 * Compute and apply current position of the menu
			 *
			 * @return {void}
			 * @private
			 */
			_applyPosition: function () {
				const oTargetElem = ('toggler' === this.options.position.target) ? $(this.options.toggler) : $(this.options.position.target);
				const oTargetPos = ('parent' === this.options.container) ? oTargetElem.position() : oTargetElem.offset();

				let oNextCSSPosition = {
					'z-index': 1,
				};
				const sVerticalPosExp = this.options.position.vertical;
				const sHorizontalPosExp = this.options.position.horizontal;

				// Position referential
				if ('body' === this.options.container) {
					oNextCSSPosition['position'] = 'fixed';
					oNextCSSPosition['z-index'] = 30; // 30 to be above #ibo-page-container (10) and #ibo-navigation-menu (20)
				}

				// Vertical
				if ('below' === sVerticalPosExp) {
					oNextCSSPosition['top'] = (oTargetPos.top+oTargetElem.outerHeight())+'px';
				} else if ('above' === sVerticalPosExp) {
					oNextCSSPosition['top'] = (oTargetPos.top-this.element.outerHeight())+'px';
				} else {
					let oTmpFunction = eval('(oElem, oTargetElem, oTargetPos) => '+sVerticalPosExp);
					oNextCSSPosition['top'] = oTmpFunction(this.element, oTargetElem, oTargetPos);
				}

				// Horizontal
				if ('align_inner_left' === sHorizontalPosExp) {
					oNextCSSPosition['left'] = (oTargetPos.left)+'px';
				} else if ('align_outer_left' === sHorizontalPosExp) {
					oNextCSSPosition['left'] = (oTargetPos.left-this.element.width())+'px';
				} else if ('align_inner_right' === sHorizontalPosExp) {
					oNextCSSPosition['left'] = (oTargetPos.left+oTargetElem.outerWidth(true)-this.element.width())+'px';
				} else if ('align_outer_right' === sHorizontalPosExp) {
					oNextCSSPosition['left'] = (oTargetPos.left+oTargetElem.outerWidth(true))+'px';
				} else {
					let oTmpFunction = eval('(oElem, oTargetElem, oTargetPos) => '+sHorizontalPosExp);
					oNextCSSPosition['left'] = oTmpFunction(this.element, oTargetElem, oTargetPos);
				}

				this.element.css(oNextCSSPosition);
			},
			/**
			 * Open the menu
			 * @return {void}
			 * @private
			 */
			_openPopup: function () {
				this.element.addClass(this.css_classes.opened);
				let self = this;
				let oTargetElem = ('toggler' === self.options.position.target) ? $(self.options.toggler) : $(self.options.position.target);
				let id = this.element.id;
				if (oTargetElem.scrollParent()[0].tagName != 'HTML') {
					oTargetElem.scrollParent().on(['scroll.'+id, 'resize.'+id].join(" "), function () {
						setTimeout(function () {
							self._applyPosition();
						}, 50);
					});
					if (oTargetElem.scrollParent().scrollParent()[0].tagName != 'HTML') {
						oTargetElem.scrollParent().scrollParent().on(['scroll.'+id, 'resize.'+id].join(" "), function () {
							setTimeout(function () {
								self._applyPosition();
							}, 50);
						});
					}
				}
			},
			/**
			 * Close the menu
			 * @return {void}
			 * @private
			 */
			_closePopup: function () {
				this.element.removeClass(this.css_classes.opened);
				let self = this;
				let oTargetElem = ('toggler' === self.options.position.target) ? $(self.options.toggler) : $(self.options.position.target);
				let id = this.element.id;
				if (oTargetElem.scrollParent()[0].tagName != 'HTML') {
					oTargetElem.scrollParent().off('scroll.'+id);
					oTargetElem.scrollParent().off('resize.'+id);
					if (oTargetElem.scrollParent().scrollParent()[0].tagName != 'HTML') {
						oTargetElem.scrollParent().scrollParent().off('scroll.'+id);
						oTargetElem.scrollParent().scrollParent().off('resize.'+id);
					}
				}
			},
			/**
			 * @api
			 * @return {void}
			 */
			openPopup: function () {
				this._openPopup();
			},
			/**
			 * @api
			 * @return {void}
			 */
			closePopup: function () {
				this._closePopup();
			},
			/**
			 * @api
			 * @return {void}
			 */
			togglePopup: function () {
				if (this.element.hasClass(this.css_classes.opened)) {
					this._closePopup();
				} else {
					this._openPopup();
				}
			},
		});
});
