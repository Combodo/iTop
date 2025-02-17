/*
 * Copyright (C) 2013-2025 Combodo SAS
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
 * Dropdown element.
 *
 * @since 3.3.0
 */

class IpbDropdown extends HTMLElement {
	connectedCallback() {
		this.setupDropdown();
	}

	setupDropdown() {
		const menu = this;
		const container = this.getAttribute('data-container') || 'parent';
		let button = this.findSiblingToggler() || this.closest('[data-toggle="ipb-dropdown"]');

		if (!button){
			return;
		}

		button.addEventListener('click', (event) => {
			event.stopPropagation();
			const isOpen = menu.classList.contains('show');
			document.querySelectorAll('ipb-dropdown.show').forEach(m => m.classList.remove('show'));

			if (!isOpen) {
				menu.classList.add('show');
				if (container === 'body') {
					this.moveToBody(menu);
				}
				this.changePlacement(menu, button);
				this.changeZIndex(menu, button);
			}
		});
		
		let me = this;
		document.addEventListener('click', (event) => {
			if (!this.contains(event.target) && !menu.contains(event.target)) {
				menu.classList.remove('show');
			}
		});
	}

	findSiblingToggler() {
		let parent = this.parentElement;
		if (!parent) return null;
		return [...parent.children].find(el => el.matches('[data-toggle="ipb-dropdown"]')) || null;
	}

	moveToBody(menu) {
		if (!menu._moved) {
			document.body.appendChild(menu);
			menu._moved = true;
		}
	}
	
	changePlacement(menu, button) {

		const rect = button.getBoundingClientRect();
		const placement = this.getAttribute('data-placement') || 'bottom';
		menu.style.position = 'absolute';
		menu.style.zIndex = '1000';
		if( (this.getAttribute('data-container') || 'parent') === 'body') {
			switch (placement) {
				case 'top':
					menu.style.top = `${rect.top+window.scrollY-menu.offsetHeight}px`;
					menu.style.left = `${rect.left+window.scrollX+rect.width / 2-menu.offsetWidth / 2}px`;
					break;
				case 'bottom':
					menu.style.top = `${rect.bottom+window.scrollY}px`;
					menu.style.left = `${rect.left+window.scrollX+rect.width / 2-menu.offsetWidth / 2}px`;
					break;
				case 'left':
					menu.style.top = `${rect.top+window.scrollY+rect.height / 2-menu.offsetHeight / 2}px`;
					menu.style.left = `${rect.left+window.scrollX-menu.offsetWidth}px`;
					break;
				case 'right':
					menu.style.top = `${rect.top+window.scrollY+rect.height / 2-menu.offsetHeight / 2}px`;
					menu.style.left = `${rect.right+window.scrollX}px`;
					break;
			}
		}
		else {
			switch (placement) {
				case 'top':
					menu.style.top = `-${menu.offsetHeight}px`;
					menu.style.left = `0px`;
					break;
				case 'bottom':
					menu.style.top = `${rect.height}px`;
					menu.style.left = `0px`;
					break;
				case 'left':
					menu.style.top = `-${rect.height}px`;
					menu.style.left = `-${rect.width + menu.offsetWidth / 2}px`;
					break;
				case 'right':
					menu.style.bottom = `0px`;
					menu.style.right = `-${menu.offsetWidth}px`;
					break;
			}
		}
	}
	changeZIndex(menu, button) {
		const zIndex = button.style.zIndex || '30';
		menu.style.zIndex = zIndex + 1;
	}
}

customElements.define('ipb-dropdown', IpbDropdown);