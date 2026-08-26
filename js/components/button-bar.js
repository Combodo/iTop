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

class ButtonBar extends HTMLElement {
	constructor() {
		super();
		// Guard against duplicate setup if the node is re-attached.
		this._initialized = false;
		// Retry counter for delayed jQuery plugin availability.
		this._popoverInitAttempts = 0;
		// Recompute distribution on resize.
		this._onResize = this.refresh.bind(this);
	}

	connectedCallback() {
		if (this._initialized) {
			return;
		}
		this._initialized = true;

		// Core DOM references rendered by the Twig template.
		this._track = this.querySelector('[data-role="ibo-button-bar--track"]') || this.querySelector('[data-role="ibo-overflow-line--track"]');
		this._extra = this.querySelector('[data-role="ibo-button-bar--extra"]') || this.querySelector('[data-role="ibo-overflow-line--extra"]');
		this._popover = this.querySelector('[data-role="ibo-popover-menu"]');
		// Toggler id is deterministic: <overflow-id>--toggler.
		const sTogglerId = `${this.id}--toggler`;
		const oToggler = document.getElementById(sTogglerId);
		this._toggler = oToggler && this.contains(oToggler) ? oToggler : null;

		if (!this._track || !this._extra || !this._popover || !this._toggler) {
			return;
		}

		this._bindEvents();

		if (window.ResizeObserver) {
			this._resizeObserver = new ResizeObserver(this._onResize);
			this._resizeObserver.observe(this);
			this._resizeObserver.observe(this._track);
		} else {
			window.addEventListener("resize", this._onResize);
		}

		// Observe both source actions and popover entries (both can be updated dynamically).
		this._mutationObserver = new MutationObserver(() => this.refresh());
		this._mutationObserver.observe(this._track, { childList: true, subtree: true, characterData: true });
		this._mutationObserver.observe(this._popover, { childList: true, subtree: true });

		this.refresh();
	}

	disconnectedCallback() {
		this._resizeObserver?.disconnect();
		this._mutationObserver?.disconnect();
		if (this._popoverInitTimer) {
			clearTimeout(this._popoverInitTimer);
		}
		this._popover?.removeEventListener("click", this._onPopoverClick);
		window.removeEventListener("resize", this._onResize);
	}

	_bindEvents() {
		this._onPopoverClick = (event) => {
			// Popover entries map to source actions through data-overflow-item-id.
			const oMenuItem = event.target.closest('[data-role="ibo-popover-menu--item"][data-overflow-item-id]');
			if (!oMenuItem) {
				return;
			}

			event.preventDefault();
			const sItemId = oMenuItem.dataset.overflowItemId;
			const oSource = this._itemsById[sItemId];
			if (!oSource) {
				return;
			}

			// Forward click to the original UI action.
			const oClickable = oSource.querySelector('a[href], button, [role="tab"], [data-role="ibo-tab-container--tab-toggler"]');
			(oClickable || oSource).click();
		};

		this._popover.addEventListener("click", this._onPopoverClick);
	}

	_refreshCollections() {
		// Source actions are direct children of the track.
		this._items = Array.from(this._track.children).filter((oItem) => Boolean(oItem.dataset.overflowItemId));
		this._itemsById = {};
		this._items.forEach((oItem) => {
			if (oItem.dataset.overflowItemId) {
				this._itemsById[oItem.dataset.overflowItemId] = oItem;
			}
		});

		// Popover entries are generated server-side with the same mapping id.
		this._menuItems = Array.from(this._popover.querySelectorAll('[data-role="ibo-popover-menu--item"][data-overflow-item-id]'));
		this._menuItemsById = {};
		this._menuItems.forEach((oItem) => {
			this._menuItemsById[oItem.dataset.overflowItemId] = oItem;
		});
	}

	_outerWidth(oElem) {
		const oRect = oElem.getBoundingClientRect();
		const oStyle = getComputedStyle(oElem);
		return oRect.width + (parseFloat(oStyle.marginLeft) || 0) + (parseFloat(oStyle.marginRight) || 0);
	}

	_flexGap(oElem) {
		const oStyle = getComputedStyle(oElem);
		const iGap = parseFloat(oStyle.columnGap);
		return Number.isFinite(iGap) ? iGap : 0;
	}

	_closePopoverIfOpen() {
		if (window.jQuery && window.jQuery(this._popover).data("itop-popover_menu")) {
			window.jQuery(this._popover).popover_menu("closePopup");
		}
	}

	refresh() {
		this._refreshCollections();
		this.layout();
	}

	layout() {
		if (!this._items || this._items.length === 0) {
			this._extra.hidden = true;
			return;
		}

		// 1) Reset visibility before computing overflow.
		this._items.forEach((oItem) => {
			oItem.hidden = false;
		});
		this._menuItems.forEach((oItem) => {
			oItem.hidden = true;
		});

		// 2) No mapping => keep source actions visible and hide overflow controls.
		if (this._menuItems.length === 0) {
			this._extra.hidden = true;
			this._closePopoverIfOpen();
			return;
		}

		const iHostWidth = this.clientWidth;
		if (iHostWidth <= 0) {
			return;
		}

		const aWidths = this._items.map((oItem) => this._outerWidth(oItem));
		const iTrackGap = this._flexGap(this._track);
		const iTotalWidth = aWidths.reduce((iSum, iWidth) => iSum + iWidth, 0) + Math.max(0, this._items.length - 1) * iTrackGap;

		// 3) Everything fits: hide the overflow button.
		if (iTotalWidth <= iHostWidth) {
			this._extra.hidden = true;
			this._closePopoverIfOpen();
			return;
		}
		this._extra.hidden = false;

		// 4) Keep items while there is space, move overflowing ones to popover.
		const iHostGap = this._flexGap(this);
		const iAvailableWidth = Math.max(0, iHostWidth - this._outerWidth(this._extra) - iHostGap);
		let iUsedWidth = 0;
		let bHasHiddenItems = false;
		let bOverflowStarted = false;
		for (let i = 0; i < this._items.length; i++) {
			const oItem = this._items[i];
			const iWidth = aWidths[i];
			const iGapBeforeItem = i > 0 ? iTrackGap : 0;

			if (!bOverflowStarted && iUsedWidth + iGapBeforeItem + iWidth <= iAvailableWidth) {
				iUsedWidth += iGapBeforeItem + iWidth;
				continue;
			}

			bOverflowStarted = true;
			oItem.hidden = true;

			const sItemId = oItem.dataset.overflowItemId;
			const oMenuItem = sItemId ? this._menuItemsById[sItemId] : null;
			if (!oMenuItem) {
				continue;
			}

			oMenuItem.hidden = false;
			bHasHiddenItems = true;
		}

		this._extra.hidden = !bHasHiddenItems;
		if (!bHasHiddenItems) {
			this._closePopoverIfOpen();
		}
	}
}

if (!customElements.get("ibo-button-bar")) {
	customElements.define("ibo-button-bar", ButtonBar);
}
