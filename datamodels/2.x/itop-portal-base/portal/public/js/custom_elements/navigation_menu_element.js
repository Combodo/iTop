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
 * @since 3.3.0
 */
class NavigationMenuElement extends HTMLElement {

	static {
		BaseElement.PageReady(() => {
			customElements.define("ipb-navigation-menu", NavigationMenuElement, {extends: 'nav'});
		});
	}

	connectedCallback() {

		// click on toggle
		this.querySelector('[data-role="ipb-navigation-menu--toggler"]').addEventListener('click', (oEvent) => {
			this.classList.toggle('ipb-is-expanded');
			window.dispatchEvent(new Event('resize'));
			SetUserPreference('portal.navigation_menu.expanded', this.classList.contains('ipb-is-expanded') ? 'expanded' : 'collapsed', true);
		});

	}

}
