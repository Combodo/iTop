/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'object_details' the widget name
	$.widget( 'itop.object_details', $.itop.panel,
	{
		// default options
		options:
		{
		},
		css_classes:
		{
		},
		js_selectors:
		{
		},
   
		// the constructor
		_create: function()
		{
			this._super();

			this._initializeSubmittingButtonsObserver();
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this._super();
		},

		_bindEvents: function ()
		{
			this._super();

			// Keep URL's hash parameters when clicking on a link of the header
			// Note: ":first" used to only target the header of the object, not what could be in the content of its body
			this.element.on('click', '[data-role="ibo-panel--header-right"]:first a', function() {
				aMatches = /#(.*)$/.exec(window.location.href);
				if (aMatches != null) {
					currentHash = aMatches[1];
					if (/#(.*)$/.test(this.href)) {
						this.href = this.href.replace(/#(.*)$/, '#'+currentHash);
					}
				}
			});
		},
		/**
		 * Initialize the observer on the submitting buttons (cancel, apply, transitions, ...) to display only the grouped button depending on the available space
		 * @private
		 */
		_initializeSubmittingButtonsObserver: function()
		{
			// This only applies in edit mode
			if (this._getObjectMode() !== 'edit') {
				return false;
			}

			// If no ResizeObserver, fallback is that transition buttons will overflow on smaller screen
			if (window.ResizeObserver === undefined) {
				return false;
			}

			// Check if transitions available
			const oHeaderElem = this.element.find('[data-role="ibo-panel--header"]:first');
			const oButtonsToolbarElem = oHeaderElem.find('[data-role="ibo-panel--header-right"] [data-role="ibo-toolbar"]');
			const oTransitionButtonsElems = oButtonsToolbarElem.find('[name="next_action"][data-role="ibo-button"]');
			if (oHeaderElem.find('[name="next_action"][data-role="ibo-button"]').length === 0) {
				return false;
			}

			let iCurrentHeaderWidth = 0;
			let iCurrentHeaderHeight = 0;
			let hTimeout = null;
			const oObserver = new ResizeObserver(function(aEntries) {
				// Throttle the processing in order to limit CPU usage
				clearTimeout(hTimeout);
				hTimeout = setTimeout(() => {
					let iNewHeaderWidth = parseInt(oHeaderElem.outerWidth());
					let iNewHeaderHeight = parseInt(oHeaderElem.outerHeight());
					if (Math.abs(iNewHeaderWidth - iCurrentHeaderWidth) < 5 && Math.abs(iNewHeaderHeight - iCurrentHeaderHeight) === 0) {
						return;
					}

					let oLastTransitionButton = oButtonsToolbarElem.find('[name="next_action"][data-role="ibo-button"]:last');

					// 1. Make transition buttons invisible BUT occuping space, so we can check where the last one would be
					oTransitionButtonsElems.css('visibility', 'hidden');
					oTransitionButtonsElems.removeClass('ibo-is-hidden');
					// 2. Measure position
					let iLastTransitionButtonBorderX = parseInt(oLastTransitionButton.offset().left + oLastTransitionButton.outerWidth());
					// 3. Make transition buttons invisible AND not occuping space again
					oTransitionButtonsElems.addClass('ibo-is-hidden');
					oTransitionButtonsElems.css('visibility', '');

					let iPanelRightBorderX = parseInt(oHeaderElem.offset().left + oHeaderElem.outerWidth());

					if (iLastTransitionButtonBorderX > iPanelRightBorderX) {
						oButtonsToolbarElem.find('.action[data-role="ibo-button"]:not([name="cancel"])').addClass('ibo-is-hidden');
						oButtonsToolbarElem.find('[data-role="ibo-button-group"]:last').removeClass('ibo-is-hidden');
					}
					else {
						oButtonsToolbarElem.find('.action[data-role="ibo-button"]:not([name="cancel"])').removeClass('ibo-is-hidden');
						oButtonsToolbarElem.find('[data-role="ibo-button-group"]:last').addClass('ibo-is-hidden');
					}

					iCurrentHeaderWidth = parseInt(oHeaderElem.outerWidth());
					iCurrentHeaderHeight = parseInt(oHeaderElem.outerHeight());

				}, 100);
			});
			// Note: ":first" used to only target the header of the object, not what could be in the content of its body
			oObserver.observe(oHeaderElem[0]);
		},

		// Helpers
		/**
		 * @return {String} The current object display mode ({@see PHP \cmdbAbstractObject for possible values})
		 * @private
		 */
		_getObjectMode: function()
		{
			return this.element.attr('data-object-mode');
		}
	});
});
