class CollectionEntryElement extends HTMLElement {

	// Button elements
	#eBtnDelete;
	#eBtnMoveUp;
	#eBtnMoveDown;

	// register the custom element
	static {
		customElements.define('collection-entry-element', CollectionEntryElement);
	}

	/** connectedCallback **/
	connectedCallback() {

		if ((this.dataset.new || this.dataset.allowDelete) && this.#eBtnDelete === undefined) {
			this.#eBtnDelete = this.#createButton('Remove', 'ibo-button ibo-is-regular ibo-is-danger');
			this.#eBtnDelete.addEventListener('click', this.#removeCollectionItem.bind(this));
			this.appendChild(this.#eBtnDelete);
		}

		if (this.dataset.allowOrdering) {
			if (this.#eBtnMoveUp === undefined) {
				this.#eBtnMoveUp = this.#createButton('Move Up', 'ibo-button ibo-is-regular');
				this.#eBtnMoveUp.addEventListener('click', this.#moveUp.bind(this));
				this.appendChild(this.#eBtnMoveUp);
			}
			if (this.#eBtnMoveDown === undefined) {
				this.#eBtnMoveDown = this.#createButton('Move Down', 'ibo-button ibo-is-regular');
				this.#eBtnMoveDown.addEventListener('click', this.#moveDown.bind(this));
				this.appendChild(this.#eBtnMoveDown);
			}
		}

		this.updateButtonStates();
	}

	/**
	 * Update the state of the buttons (enabled/disabled).
	 *
	 */
	updateButtonStates() {

		if (this.dataset.allowOrdering) {

			if (this.previousElementSibling === null) {
				this.#eBtnMoveUp.setAttribute('disabled', 'disabled');
			} else {
				this.#eBtnMoveUp.removeAttribute('disabled');
			}

			if (this.nextElementSibling === null) {
				this.#eBtnMoveDown.setAttribute('disabled', 'disabled');
			} else {
				this.#eBtnMoveDown.removeAttribute('disabled');
			}

		}

	}

	/**
	 * Create a button element.
	 *
	 * @param label
	 * @param className
	 * @returns {HTMLButtonElement}
	 */
	#createButton(label, className) {

		const btnElement = document.createElement('button');
		btnElement.type = 'button';
		btnElement.className = className;
		btnElement.textContent = label;

		return btnElement;
	}

	/**
	 * Move this collection item up.
	 *
	 */
	#moveUp() {
		const prev = this.previousElementSibling;
		if (prev) {
			this.parentNode.insertBefore(this, prev);
			this.updateButtonStates();
			prev.updateButtonStates();
		}
	}

	/**
	 * Move this collection item down.
	 *
	 */
	#moveDown() {
		const next = this.nextElementSibling;
		if (next) {
			this.parentNode.insertBefore(next, this);
			this.updateButtonStates();
			next.updateButtonStates();
		}
	}

	/**
	 * Remove this collection item.
	 *
	 */
	#removeCollectionItem() {
		this.remove();
	}
}
