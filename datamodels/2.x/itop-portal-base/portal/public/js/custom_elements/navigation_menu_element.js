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

/**
 * Navigation menu element.
 *
 * note: JQuery and Tippy libraries are used for tooltip initialization.
 *
 * @since 3.3.0
 */
class NavigationMenuElement extends HTMLElement {

	static CLASS_NAV_HORIZONTAL = 'ipb-nav-horizontal';
	static CLASS_NAV_EXPANDED = 'ipb-is-expanded';
	static CLASS_MOBILE_OPENED = 'ipb-is-opened';
	static CLASS_HIDDEN = 'ipb-hidden';

	static {
		BaseElement.PageReady(() => {
			customElements.define("ipb-navigation-menu", NavigationMenuElement, {extends: 'nav'});
		});
	}

	// properties
	eOverlay = null;
	eExpandToggle = null;
	eMobileToggle = null;
	eMiddlePart = null;
	eMenuEntries = null;
	eMoreMenuItemsButton = null;

	connectedCallback() {

		// get elements
		this.eOverlay = this.querySelector('[data-role="navigation-menu-overlay"]');
		this.eExpandToggle = this.querySelector('[data-role="ipb-navigation-menu--expand-toggle"]');
		this.eMobileToggle = this.querySelector('[data-role="ipb-navigation-menu--mobile--toggle"]');
		this.eMiddlePart = this.querySelector('.ipb-navigation-menu--middle-part');
		this.eMenuEntries = this.querySelector('.ipb-navigation-menu--menu-entries');
		this.aMenuEntries = this.eMenuEntries.querySelectorAll('.brick_menu_item');
		this.eMoreMenuItemsButton = this.querySelector('.ipb-navigation-menu--menu-entry--more');

		// click on expand toggle
		this.eExpandToggle.addEventListener('click', () => {
			let bIsExpanded = this.classList.contains(NavigationMenuElement.CLASS_NAV_EXPANDED);
			bIsExpanded ? this.Compress() : this.Expand();

			// save user preference
			SetUserPreference('portal.navigation_menu.expanded', bIsExpanded ? 'expanded' : 'collapsed', true);
		});

		// click on mobile open toggle
		this.eMobileToggle.addEventListener('click', () => {
			let bIsOpened = this.classList.contains(NavigationMenuElement.CLASS_MOBILE_OPENED);
			bIsOpened ? this.Close() : this.Open();
		});


		// observe middle part resize
		new ResizeObserver(() => this.MiddlePartResizeCallback()).observe(this.eMiddlePart);

		// store menu entries flex gap
		this.StoreMenuEntriesFlexColumnGap();

		// hide mobile menu when clicking on overlay
		this.eOverlay.addEventListener('click', () => {
			this.eOverlay.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, true);
			this.Close();
		});
	}

	/**
	 * Store the gap between menu entries.
	 * Used to compute the visible menu entries in horizontal mode.
	 *
	 * @constructor
	 */
	StoreMenuEntriesFlexColumnGap() {
		let style = window.getComputedStyle(this.eMenuEntries);
		let regex = /(\d)+px/g;
		let match = regex.exec(style.columnGap);
		this.gap = match !== null ? parseInt(match[1]) : 10;
	}

	/**
	 * Callback triggered when the middle part is resized.
	 * @constructor
	 */
	MiddlePartResizeCallback() {

		let style = window.getComputedStyle(this.eMenuEntries);
		if (style.flexDirection === 'row') {
			console.log('horizontal mode ResizeCallback');
			this.UpdateMenuVisibleMenuEntriesInHorizontalMode();
		}
	}

	/**
	 * Update the visible menu entries in horizontal mode.
	 * @constructor
	 */
	UpdateMenuVisibleMenuEntriesInHorizontalMode() {

		let viewportWidth = this.eMiddlePart.offsetWidth;
		let iTotalWidth = 0;

		// rest menu entries visibility
		this.ResetMenuEntriesVisibility();

		// hide elements outside the viewport and show them inside remainder dropdown
		this.aMenuEntries.forEach((li) => {
			iTotalWidth += li.offsetWidth+this.gap;
			li.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, iTotalWidth > viewportWidth);

			let brickId = li.getAttribute('data-brick-id');
			if (brickId !== null) {
				this.querySelector(`.dropdown-menu--items-remainder .brick_menu_item[data-brick-id="${brickId}"]`).classList.toggle(NavigationMenuElement.CLASS_HIDDEN, iTotalWidth <= viewportWidth);
			}
		});

		// show/hide more button
		this.eMoreMenuItemsButton.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, iTotalWidth < viewportWidth);
	}

	/**
	 * Reset the navigation menu.
	 * @constructor
	 */
	ResetMenuEntriesVisibility() {

		// restore menu items visibility...
		this.aMenuEntries.forEach((li) => {
			li.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, false);
		});

		// hide more button
		this.eMoreMenuItemsButton.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, true);
	}

	InstallMenuEntriesTooltip(tooltipPlacement = null) {

		// for each item...
		this.aMenuEntries.forEach((li) => {

			// reset tooltip
			if (tooltipPlacement !== null) {
				li.setAttribute('data-tooltip-placement', tooltipPlacement);
				CombodoTooltip.InitTooltipFromMarkup($(li), true);
			} else {
				li.removeAttribute('data-tooltip-placement');
				if (li._tippy !== undefined) {
					li._tippy.destroy();
				}
			}
		});
	}

	Expand() {
		this.classList.toggle(NavigationMenuElement.CLASS_NAV_EXPANDED, true);
		window.dispatchEvent(new Event('resize')); // do layout
	}

	Compress() {
		this.classList.toggle(NavigationMenuElement.CLASS_NAV_EXPANDED, false);
		window.dispatchEvent(new Event('resize')); // do layout
	}

	Horizontal() {
		this.InstallMenuEntriesTooltip(null);
		document.querySelector('body').classList.toggle(NavigationMenuElement.CLASS_NAV_HORIZONTAL, true);
		this.UpdateMenuVisibleMenuEntriesInHorizontalMode();
		window.dispatchEvent(new Event('resize')); // do layout
	}

	Vertical() {
		this.InstallMenuEntriesTooltip('right');
		document.querySelector('body').classList.toggle(NavigationMenuElement.CLASS_NAV_HORIZONTAL, false);
		this.ResetMenuEntriesVisibility();
		window.dispatchEvent(new Event('resize')); // do layout
	}

	Open() {
		this.classList.toggle(NavigationMenuElement.CLASS_MOBILE_OPENED, true);
		this.eOverlay.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, false);
	}

	Close() {
		this.classList.toggle(NavigationMenuElement.CLASS_MOBILE_OPENED, false);
		this.eOverlay.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, true);
	}

}
