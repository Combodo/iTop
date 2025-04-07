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

	static MOBILE_WIDTH_THRESHOLD = 768;
	static ROLE_NAV_MENU = 'ipb-navigation-menu';
	static DATA_EXPANDED_STATE = 'data-expanded-state';
	static DATA_POSITION = 'data-position';
	static CLASS_NAV_MENU = 'ipb-navigation-menu';
	static CLASS_NAV_HORIZONTAL = 'ipb-nav-horizontal';
	static CLASS_NAV_EXPANDED = 'ipb-is-expanded';
	static CLASS_MOBILE_OPENED = 'ipb-is-opened';
	static CLASS_HIDDEN = 'ipb-is-hidden';

	static DEFAULT_POSITION = 'vertical';
	static DEFAULT_STATE = 'expanded';

	static {
		customElements.define("ipb-navigation-menu", NavigationMenuElement, {extends: 'nav'});
	}

	static get observedAttributes() {
		return [NavigationMenuElement.DATA_EXPANDED_STATE, NavigationMenuElement.DATA_POSITION];
	}

	// properties
	bIsConnected = false;
	eOverlay = null;
	eExpandToggle = null;
	eMobileToggle = null;
	eMiddlePart = null;
	eMenuEntries = null;
	eMoreMenuItemsButton = null;
	eUserDropdown = null;

	attributeChangedCallback(name, oldValue, newValue) {

		if (!this.bIsConnected) {
			return;
		}

		switch (name) {
			case NavigationMenuElement.DATA_EXPANDED_STATE:
				// invalid values
				this.EnsureAttributeValidValue(name, ['expanded', 'collapsed'], newValue, oldValue);
				// update expanded state
				newValue === 'expanded' ? this.Expand() : this.Collapse();
				break;

			case NavigationMenuElement.DATA_POSITION:
				// invalid values
				this.EnsureAttributeValidValue(name, ['vertical', 'horizontal'], newValue, oldValue);
				// update expanded state
				newValue === 'horizontal' ? this.Horizontal() : this.Vertical();
				break;
		}
	}

	connectedCallback() {

		// element attributes
		this.setAttribute('data-role', NavigationMenuElement.ROLE_NAV_MENU);

		// element classes
		this.classList.add(NavigationMenuElement.CLASS_NAV_MENU);

		// load element template
		let template = document.getElementById("navigation-menu-template");
		this.appendChild(template.content.cloneNode(true));
		template.remove(); // not needed anymore

		// retrieve useful elements
		this.eOverlay = this.querySelector('[data-role="navigation-menu-overlay"]');
		this.eExpandToggle = this.querySelector('[data-role="ipb-navigation-menu--expand-toggle"]');
		this.eMobileToggle = this.querySelector('[data-role="ipb-navigation-menu--mobile--toggle"]');
		this.eMiddlePart = this.querySelector('.ipb-navigation-menu--middle-part');
		this.eMenuEntries = this.querySelector('.ipb-navigation-menu--menu-entries');
		this.aMenuEntries = this.eMenuEntries.querySelectorAll('.brick_menu_item');
		this.eMoreMenuItemsButton = this.querySelector('.ipb-navigation-menu--menu-entry--more');
		this.eUserDropdown = this.querySelector('ipb-dropdown[data-role="ipb-user-dropdown"]');

		// click on expand toggle
		this.eExpandToggle.addEventListener('click', () => {
			// toggle expanded state
			this.IsExpanded() ? this.Collapse(true) : this.Expand(true);
		});

		// click on mobile open toggle
		this.eMobileToggle.addEventListener('click', () => {
			// toggle open state
			let bIsOpened = this.classList.contains(NavigationMenuElement.CLASS_MOBILE_OPENED);
			bIsOpened ? this.Close() : this.Open();
		});

		// hide mobile menu when clicking on overlay
		this.eOverlay.addEventListener('click', () => {
			this.eOverlay.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, true);
			this.Close();
		});

		// close mobile menu when clicking on menu entries
		this.eMenuEntries.addEventListener('click', () => {
			this.Close();
		});

		// observe middle part resize
		new ResizeObserver(() => this.MiddlePartResizeCallback()).observe(this.eMiddlePart);

		// observe body resize
		new ResizeObserver(() => this.BodyResizeCallback()).observe(document.body);

		// store menu entries flex gap
		this.StoreMenuEntriesFlexColumnGap();

		// initial state
		this.bIsConnected = true;
		this.IsExpanded() ? this.Expand() : this.Collapse();
		this.IsHorizontal() ? this.Horizontal() : this.Vertical();
	}

	EnsureAttributeValidValue(attributeName, validValues, value, defaultValue) {
		if (!validValues.includes(value)) {
			this.setAttribute(attributeName, defaultValue);
			throw new Error(`Invalid attribute value detected: attribute=${attributeName} value=${value} allowed=${JSON.stringify(validValues)} !`);
		}
	}

	StoreMenuEntriesFlexColumnGap() {
		let style = window.getComputedStyle(this.eMenuEntries);
		let regex = /(\d)+px/g;
		let match = regex.exec(style.columnGap);
		this.gap = match !== null ? parseInt(match[1]) : 10;
	}

	BodyResizeCallback() {
		this.UpdateUserDropDownPosition();
	}

	UpdateUserDropDownPosition() {

		if (document.body.offsetWidth < NavigationMenuElement.MOBILE_WIDTH_THRESHOLD) {
			// when mobile
			this.eUserDropdown.setAttribute('data-placement', 'bottom-right');
		} else {
			if (this.IsHorizontal()) {
				// when navbar is horizontal
				this.eUserDropdown.setAttribute('data-placement', 'bottom-right');
			} else if (this.IsVertical()) {
				if (this.IsExpanded()) {
					// when navbar is vertical and expanded
					this.eUserDropdown.setAttribute('data-placement', 'top-right');
				} else {
					// when navbar is vertical and not expanded
					this.eUserDropdown.setAttribute('data-placement', 'top-left');
				}
			}
		}
	}

	MiddlePartResizeCallback() {

		let style = window.getComputedStyle(this.eMenuEntries);
		if (style.flexDirection === 'row') {
			this.UpdateMenuVisibleMenuEntriesInHorizontalMode();
		}
	}

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
				this.querySelector(`.ipb-dropdown-menu--items-remainder .brick_menu_item[data-brick-id="${brickId}"]`).classList.toggle(NavigationMenuElement.CLASS_HIDDEN, iTotalWidth <= viewportWidth);
			}
		});

		// show/hide more button
		this.eMoreMenuItemsButton.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, iTotalWidth < viewportWidth);
	}

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

	GetState() {
		return this.getAttribute(NavigationMenuElement.DATA_EXPANDED_STATE) !== null ? this.getAttribute(NavigationMenuElement.DATA_EXPANDED_STATE) : NavigationMenuElement.DEFAULT_STATE;
	}

	Expand(bSaveUserPreference = false) {
		// save user preference
		if (bSaveUserPreference) {
			oUserPreferences.setPreference('portal.navigation_menu.expanded', 'expanded');
		}
		// sync attribute
		if (this.getAttribute(NavigationMenuElement.DATA_EXPANDED_STATE) !== 'expanded') {
			this.setAttribute(NavigationMenuElement.DATA_EXPANDED_STATE, 'expanded');
			return;
		}
		// set classes
		this.classList.toggle(NavigationMenuElement.CLASS_NAV_EXPANDED, true);
		// update user dropdown position
		this.UpdateUserDropDownPosition();
		// dispatch events
		window.dispatchEvent(new Event('resize')); // do layout
		this.dispatchEvent(new CustomEvent("state", {detail: 'expanded'}));
	}

	Collapse(bSaveUserPreference = false) {
		// save user preference
		if (bSaveUserPreference) {
			oUserPreferences.setPreference('portal.navigation_menu.expanded', 'collapsed');
		}
		// sync attribute
		if (this.getAttribute(NavigationMenuElement.DATA_EXPANDED_STATE) !== 'collapsed') {
			this.setAttribute(NavigationMenuElement.DATA_EXPANDED_STATE, 'collapsed');
			return;
		}
		// set classes
		this.classList.toggle(NavigationMenuElement.CLASS_NAV_EXPANDED, false);
		// update user dropdown position
		this.UpdateUserDropDownPosition();
		// dispatch events
		window.dispatchEvent(new Event('resize')); // do layout
		this.dispatchEvent(new CustomEvent("state", {detail: 'collapsed'}));
	}

	IsExpanded() {
		return this.GetState() === 'expanded';
	}

	IsCollapsed() {
		return this.GetState() === 'collapsed';
	}

	GetPosition() {
		return this.getAttribute(NavigationMenuElement.DATA_POSITION) !== null ? this.getAttribute(NavigationMenuElement.DATA_POSITION) : NavigationMenuElement.DEFAULT_POSITION;
	}

	Vertical() {
		// sync attribute
		if (this.getAttribute(NavigationMenuElement.DATA_POSITION) !== 'vertical') {
			this.setAttribute(NavigationMenuElement.DATA_POSITION, 'vertical');
			return;
		}
		// set classes
		document.body.classList.toggle(NavigationMenuElement.CLASS_NAV_HORIZONTAL, false);
		// install tooltip
		this.InstallMenuEntriesTooltip('right');
		// reset menu entries visibility
		this.ResetMenuEntriesVisibility();
		// update user dropdown position
		this.UpdateUserDropDownPosition();
		// dispatch events
		window.dispatchEvent(new Event('resize')); // do layout
		this.dispatchEvent(new CustomEvent("position", {detail: 'vertical'}));
	}

	Horizontal() {
		// sync attribute
		if (this.getAttribute(NavigationMenuElement.DATA_POSITION) !== 'horizontal') {
			this.setAttribute(NavigationMenuElement.DATA_POSITION, 'horizontal');
			return;
		}
		// set classes
		document.body.classList.toggle(NavigationMenuElement.CLASS_NAV_HORIZONTAL, true);
		// install tooltip
		this.InstallMenuEntriesTooltip(null);
		// update menu entries visibility
		this.UpdateMenuVisibleMenuEntriesInHorizontalMode();
		// update user dropdown position
		this.UpdateUserDropDownPosition();
		// dispatch events
		window.dispatchEvent(new Event('resize')); // do layout
		this.dispatchEvent(new CustomEvent("position", {detail: 'horizontal'}));
	}

	IsHorizontal() {
		return this.GetPosition() === 'horizontal';
	}

	IsVertical() {
		return this.GetPosition() === 'vertical';
	}

	Open() {
		this.classList.toggle(NavigationMenuElement.CLASS_MOBILE_OPENED, true);
		this.eOverlay.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, false);
		this.dispatchEvent(new CustomEvent("mobile_menu", {detail: 'opened'}));
	}

	Close() {
		this.classList.toggle(NavigationMenuElement.CLASS_MOBILE_OPENED, false);
		this.eOverlay.classList.toggle(NavigationMenuElement.CLASS_HIDDEN, true);
		this.dispatchEvent(new CustomEvent("mobile_menu", {detail: 'closed'}));
	}

}
