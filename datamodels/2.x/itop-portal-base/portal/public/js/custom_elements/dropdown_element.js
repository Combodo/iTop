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
		const container = this.getAttribute('data-container') || 'parent';

		menu.style.position = 'absolute';
		menu.style.zIndex = '1000';
		
		const checkBounds = (value, min, max) => Math.max(min, Math.min(max, value));


		if(container === 'body') {
			switch (placement) {
				case 'top':
					menu.style.top = `${checkBounds(rect.top + window.scrollY - menu.offsetHeight, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.left + window.scrollX + rect.width / 2 - menu.offsetWidth / 2, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
				case 'top-left':
					menu.style.top = `${checkBounds(rect.top + window.scrollY - menu.offsetHeight, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.left + window.scrollX, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
				case 'top-right':
					menu.style.top = `${checkBounds(rect.top + window.scrollY - menu.offsetHeight, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.right + window.scrollX - menu.offsetWidth, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
				case 'left':
					menu.style.top = `${checkBounds(rect.top + window.scrollY + rect.height / 2 - menu.offsetHeight / 2, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.left + window.scrollX - menu.offsetWidth, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
				case 'left-top':
					menu.style.top = `${checkBounds(rect.top + window.scrollY, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.left + window.scrollX - menu.offsetWidth, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
				case 'left-bottom':
					menu.style.top = `${checkBounds(rect.bottom + window.scrollY - menu.offsetHeight, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.left + window.scrollX - menu.offsetWidth, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
				case 'right':
					menu.style.top = `${checkBounds(rect.top + window.scrollY + rect.height / 2 - menu.offsetHeight / 2, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.right + window.scrollX, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
				case 'right-top':
					menu.style.top = `${checkBounds(rect.top + window.scrollY, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.right + window.scrollX, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
				case 'right-bottom':
					menu.style.top = `${checkBounds(rect.bottom + window.scrollY - menu.offsetHeight, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.right + window.scrollX, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
				case 'bottom':
				default:
					menu.style.top = `${checkBounds(rect.bottom + window.scrollY, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.left + window.scrollX + rect.width / 2 - menu.offsetWidth / 2, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
				case 'bottom-left':
					menu.style.top = `${checkBounds(rect.bottom + window.scrollY, 0, window.innerHeight - menu.offsetHeight)}px`;
					menu.style.left = `${checkBounds(rect.left + window.scrollX, 0, window.innerWidth - menu.offsetWidth)}px`;
					break;
			}
		}
		else {
			switch (placement) {
				case 'top':
					menu.style.top = `-${menu.offsetHeight}px`;
					menu.style.left = `-${menu.offsetWidth/2 - rect.width/2}px`;
					break;
					case 'top-left':
					menu.style.top = `-${menu.offsetHeight}px`;
						menu.style.left = `-${menu.offsetWidth - rect.width/2}px`;
					break;
				case 'top-right':
					menu.style.top = `-${menu.offsetHeight}px`;
					menu.style.left = `${rect.width/2}px`;
					break;
				case 'left':
					menu.style.top = `-${rect.height}px`;
					menu.style.left = `-${menu.offsetWidth}px`;
					break;
				case 'left-top':
					menu.style.bottom = `${rect.height/2}px`;
					menu.style.left = `-${menu.offsetWidth}px`;
					break;
				case 'left-bottom':
					menu.style.top = `${rect.height/2}px`;
					menu.style.left = `-${menu.offsetWidth}px`;
					break;
				case 'right':
					menu.style.top = `-${rect.height}px`;
					menu.style.right = `-${menu.offsetWidth}px`;
					break;
				case 'right-top':
					menu.style.bottom = `${rect.height/2}px`;
					menu.style.left = `${rect.width}px`;
					break;
				case 'right-bottom':
					menu.style.top = `${rect.height/2}px`;
					menu.style.left = `${rect.width}px`;
					break;
				case 'bottom':
				default:
					menu.style.top = `${rect.height}px`;
					menu.style.left = `-${menu.offsetWidth/2 - rect.width/2}px`;
					break;
				case 'bottom-left':
					menu.style.top = `${rect.height}px`;
					menu.style.left = `-${menu.offsetWidth - rect.width/2}px`;
					break
				case 'bottom-right':
					menu.style.top = `${rect.height}px`;
					menu.style.left = `${rect.width/2}px`;
			}
		}
	}
	changeZIndex(menu, button) {
		const zIndex = button.style.zIndex || '30';
		menu.style.zIndex = zIndex + 1;
	}
}

customElements.define('ipb-dropdown', IpbDropdown);