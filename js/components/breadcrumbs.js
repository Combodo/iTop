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
	$.widget( 'itop.breadcrumbs',
	{
		// default options
		options:
		{
			itop_instance_id: '',
			new_entry: null,
			max_count: 8
		},
		css_classes:
		{
			is_hidden: 'ibo-is-hidden',
			is_transparent: 'ibo-is-transparent',
			is_opaque: 'ibo-is-opaque',
			is_overflowing: 'ibo-is-overflowing',
			breadcrumbs_item: 'ibo-breadcrumbs--item',
			breadcrumbs_previous_item: 'ibo-breadcrumbs--previous-item',
		},
		js_selectors:
		{
			breadcrumbs: '[data-role="ibo-breadcrumbs"]',
			item: '[data-role="ibo-breadcrumbs--item"]',
			previous_items_container: '[data-role="ibo-breadcrumbs--previous-items-container"]',
			previous_items_list_toggler: '[data-role="ibo-breadcrumbs--previous-items-list-toggler"]',
			previous_items_list: '[data-role="ibo-breadcrumbs--previous-items-list"]',
			previous_item: '[data-role="ibo-breadcrumbs--previous-item"]',
		},

		items_observer: null,
   
		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element.addClass('ibo-breadcrumbs');

			// Check that storage API is available
			if(typeof(Storage) !== 'undefined')
			{
				$(window).on('hashchange', function(e)
				{
					me.RefreshLatestEntry();
				});

				aBreadCrumb = this._readDataFromStorage();

                if (this.options.new_entry !== null) {
                    var sUrl = this.options.new_entry.url;
                    if (sUrl.length === 0) {
                        sUrl = window.location.href;
                    }
                    // Eliminate items having the same id, before appending the new item
                    var aBreadCrumb = $.grep(aBreadCrumb, function(item, ipos){
                        if (item.id == me.options.new_entry.id) return false;
                        else return true;
                    });
                    aBreadCrumb.push({
                        id: this.options.new_entry.id,
                        label: this.options.new_entry.label,
						description: this.options.new_entry.description,
                        icon: this.options.new_entry.icon,
	                    icon_type: this.options.new_entry.icon_type,
                        url: sUrl
                    });
                    // Keep only the last <max_count> items
                    aBreadCrumb = aBreadCrumb.slice(-(this.options.max_count));
                }
				this._writeDataToStorage(aBreadCrumb);

                // Build markup
				// - Add entries to the markup
				for (iEntry in aBreadCrumb)
				{
					var sBreadcrumbsItemHtml = '';
					var oEntry = aBreadCrumb[iEntry];
					if (oEntry['label'].length > 0)
					{
                        var sIconSpec = '';
                        if (oEntry['icon_type'] === 'css_classes')
                        {
	                        sIconSpec = '<span class="ibo-breadcrumbs--item-icon"><span class="'+oEntry['icon']+'"/></span></span>';
                        } else if (oEntry['icon'].length > 0) {
	                        // Mind the empty "alt" attribute https://www.w3.org/WAI/tutorials/images/decorative/
	                        sIconSpec = '<span class="ibo-breadcrumbs--item-icon"><img src="'+oEntry['icon']+'" alt=""/></span>';
                        }

						var sTitle = oEntry['description'],
							sLabel = oEntry['label'];
						if (sTitle.length === 0) {
							sTitle = sLabel;
						}
						sTitle = CombodoSanitizer.EscapeHtml(sTitle, false);
						sLabel = CombodoSanitizer.EscapeHtml(sLabel, false);

						if ((this.options.new_entry !== null) && (iEntry === aBreadCrumb.length-1)) {
							// Last entry is the current page
							sBreadcrumbsItemHtml += '<span class="ibo-is-current" data-role="" data-breadcrumb-entry-number="'+iEntry+'" title="'+sTitle+'">'+sIconSpec+'<span class="ibo-breadcrumbs--item-label">'+sLabel+'</span></span>';
						} else {
							var sSanitizedUrl = StripArchiveArgument(oEntry['url']);
							sSanitizedUrl = CombodoSanitizer.EscapeHtml(sSanitizedUrl, false);
							sBreadcrumbsItemHtml += '<a class="" data-role="" data-breadcrumb-entry-number="'+iEntry+'" href="'+sSanitizedUrl+'" title="'+sTitle+'">'+sIconSpec+'<span class="ibo-breadcrumbs--item-label">'+sLabel+'</span></a>';
						}
					}

					const oNormalItemElem = $(sBreadcrumbsItemHtml)
						.addClass(this.css_classes.breadcrumbs_item)
						.attr('data-role', 'ibo-breadcrumbs--item');
					this.element.append(oNormalItemElem);

					const oPreviousItemElem = $(sBreadcrumbsItemHtml)
						.addClass(this.css_classes.breadcrumbs_previous_item)
						.attr('data-role', 'ibo-breadcrumbs--previous-item')
					// Note: We prepend items as we want the oldest to be at the bottom of the list, like in a browser
					this.element.find(this.js_selectors.previous_items_list).prepend(oPreviousItemElem);
				}
			}

			this._updateOverflowingState();
			this._bindEvents();
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			// Remove listeners
			this.element.find(this.js_selectors.previous_items_list_toggler).off('click');

			// Remove observers
			if (this.items_observer !== null) {
				this.items_observer.disconnect();
			}

			// Clear any existing entries in the markup
			this.element.find(this.js_selectors.item).remove();
			this.element.find(this.js_selectors.previous_item).remove();

			this.element.removeClass('ibo-breadcrumbs');
		},
		_bindEvents: function ()
		{
			const me = this;

			// Enable responsiveness if more than 1 item
			if(window.IntersectionObserver && (this.element.find(this.js_selectors.item).length > 1)) {
				// Set an observer on the items
				this.items_observer = new IntersectionObserver(function(aItems, oIntersectObs){
					aItems.forEach(oItem => {
						let oItemElem = $(oItem.target);
						let bIsVisible = oItem.isIntersecting;

						// Important: We toggle "visibility" instead of "display" otherwise once they are hidden, they never trigger back the intersection.
						if(bIsVisible) {
							oItemElem.css('visibility', '');
						}
						else {
							// Here we also check if the item has an invisible left sibbling before hiding it.
							// There reason is that on initialization, the last item might be overflowing on the right BEFORE the breadcrumbs is flagged as overflowing, making it disappear
							let oLeftSiblingElem = oItemElem.prev(me.js_selectors.item);
							if (oLeftSiblingElem.length > 0 && oLeftSiblingElem.css('visibility') !== 'hidden') {
								bIsVisible = true;
							} else {
								oItemElem.css('visibility', 'hidden');
							}
						}
						me._updateItemDisplay(oItemElem, bIsVisible);
					});

					let bShouldShowPreviousItemsList = false;
					me.element.find(me.js_selectors.item).each(function() {
						if ($(this).css('visibility') === 'hidden') {
							bShouldShowPreviousItemsList = true;

							// Note: Can break a .each function loop, must return false
							return false;
						}
					});

					// Move previous items toggler before first visible item for a better UX
					if (bShouldShowPreviousItemsList) {
						let oFirstVisibleItem = me.element.find(me.js_selectors.item).first();
						me.element.find(me.js_selectors.item).each(function() {
							if ($(this).css('visibility') !== 'hidden') {
								oFirstVisibleItem = $(this);

								// Note: Can break a .each function loop, must return false
								return false;
							}
						});
						me.element.find(me.js_selectors.previous_items_container).insertBefore(oFirstVisibleItem);
					}

					me._updateOverflowingState();
					me._updatePreviousItemsList();
				}, {
					root: $(this.js_selectors.breadcrumbs)[0],
					threshold: [0.9] // N°3900 Should be completely visible, but lowering the threshold prevents a bug in the JS Observer API when the window is zoomed in/out, in which case all items respond as being hidden even when they are not.
				});
				this.element.find(this.js_selectors.item).each(function(){
					me.items_observer.observe(this);
				});

				this.element.find(this.js_selectors.previous_items_list_toggler).on('click', function (oEvent) {
					oEvent.preventDefault();
					me.element.find(me.js_selectors.previous_items_list).toggleClass(me.css_classes.is_hidden);
				});
				$('body').on('click', function (oEvent) {
					if (true === me.element.find(me.js_selectors.previous_items_list).hasClass(me.css_classes.is_hidden)) {
						return;
					}

					if ($(oEvent.target.closest(me.js_selectors.previous_items_container)).length === 0) {
						me.element.find(me.js_selectors.previous_items_list).addClass(me.css_classes.is_hidden);
					}
				});
			}
		},
		_readDataFromStorage: function()
		{
			var sBreadCrumbStorageKey = this.options.itop_instance_id + 'breadcrumb-v1';
			var aBreadCrumb = [];
			var sBreadCrumbData = sessionStorage.getItem(sBreadCrumbStorageKey);
			if (sBreadCrumbData !== null)
			{
				aBreadCrumb = JSON.parse(sBreadCrumbData);
			}
			return aBreadCrumb;
		},
		_writeDataToStorage: function(aBreadCrumb)
		{
			var sBreadCrumbStorageKey = this.options.itop_instance_id + 'breadcrumb-v1';
			sBreadCrumbData = JSON.stringify(aBreadCrumb);
			sessionStorage.setItem(sBreadCrumbStorageKey, sBreadCrumbData);
		},

		// Refresh the latest entry (navigating to a tab)
		RefreshLatestEntry: function(sRefreshHrefTo)
		{
			var aBreadCrumb = this._readDataFromStorage();
			var iDisplayableItems = aBreadCrumb.length;

			if (this.options.new_entry !== null) {
				if (sRefreshHrefTo === undefined)
				{
					sRefreshHrefTo = window.location.href;
				}

				// The current page is the last entry in the breadcrumb, let's refresh it
				aBreadCrumb[aBreadCrumb.length - 1].url = sRefreshHrefTo;
				$('#itop-breadcrumb .breadcrumb-current:last-of-type a').attr('href', sRefreshHrefTo);
			}
			this._writeDataToStorage(aBreadCrumb);
		},

		// Helpers
		/**
		 * Update item display based on its visibility to the user
		 *
		 * @param oItemElem {Object} jQuery element
		 * @param bIsVisible {boolean|null} If null, visibility will be computed automatically. Not that performance might not be great so it's preferable to pass the value when known
		 * @return {void}
		 * @private
		 */
		_updateItemDisplay(oItemElem, bIsVisible = null)
		{
			const iEntryNumber = parseInt(oItemElem.attr('data-breadcrumb-entry-number'));
			const oMatchingExtraItemElem = this.element.find(this.js_selectors.previous_items_list+' [data-breadcrumb-entry-number="'+iEntryNumber+'"]');

			// Manually check if the item is visible if the info isn't passed
			if (bIsVisible === null) {
				bIsVisible = CombodoGlobalToolbox.IsElementVisibleToTheUser(oItemElem[0], true, 2);
			}

			// Hide/show the corresponding extra item element
			if (bIsVisible) {
				oMatchingExtraItemElem.addClass(this.css_classes.is_hidden);
			} else {
				oMatchingExtraItemElem.removeClass(this.css_classes.is_hidden);
			}
		},
		/**
		 * Update previous items list
		 *
		 * @return {void}
		 * @private
		 */
		_updatePreviousItemsList: function () {
			const iVisiblePreviousItemsCount = this.element.find(this.js_selectors.previous_item+':not(.'+this.css_classes.is_hidden+')').length;
			const oPreviousItemsContainerElem = this.element.find(this.js_selectors.previous_items_container);

			if (iVisiblePreviousItemsCount > 0) {
				oPreviousItemsContainerElem.removeClass(this.css_classes.is_hidden);
			} else {
				oPreviousItemsContainerElem.addClass(this.css_classes.is_hidden);
			}
		},
		/**
		 * Update the overflowing state of the breadcrumbs by checking if the items cumulated width is greater than the breadcrumbs visible space
		 *
		 * @return {void}
		 * @private
		 */
		_updateOverflowingState: function () {
			const fBreadcrumbsWidth = this.element.outerWidth();
			let fItemsTotalWidth = 0;

			this.element.find(this.js_selectors.item).each(function () {
				fItemsTotalWidth += $(this).outerWidth();
			});

			if (fItemsTotalWidth > fBreadcrumbsWidth) {
				this.element.addClass(this.css_classes.is_overflowing);
			} else {
				this.element.removeClass(this.css_classes.is_overflowing);
			}
		}
	});
});
