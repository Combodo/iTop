class OqlElement extends HTMLTextAreaElement {

	// register the custom element
	static{
		customElements.define('oql-element', OqlElement, {extends: 'textarea'});
	}

	// variables
	#url = '../pages/ajax.render.php?route=oql.validate_query';
	#iconValid = 'fa-check-double';
	#iconNotValid = 'fa-exclamation-triangle';
	#debounceTimer = null;
	#debounce = 300;

	/** connectedCallback **/
	connectedCallback() {
		this.addEventListener('input', this.#onInput.bind(this));
		this.#callValidateQuery();
	}

	/**
	 * Call oql verification with debounce when input event is fired.
	 */
	#onInput() {
		if (this.#debounceTimer) clearTimeout(this.#debounceTimer);
		this.#debounceTimer = setTimeout(() => {
			this.#callValidateQuery(true);
		}, this.#debounce);
	}

	/**
	 * Call the ajax to validate the query.
	 *
	 * @param fireChange flag to handle change event
	 */
	#callValidateQuery(fireChange = false) {

		fetch(this.#url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-Combodo-Ajax': true
			},
			body: JSON.stringify({
				query: this.value
			})
		})
			.then(response => response.json())
			.then(response => {
				// fire change event only if the query is valid
				if (fireChange && response.is_valid){
					this.#fireChangeEvent();
				}
				// update the icon color
				const fieldEl = this.closest('.ibo-field');
				const marqueeEl = fieldEl.querySelector('[role="marquee"]');
				marqueeEl.style.color = response.is_valid ? 'green' : 'orange';
				marqueeEl.classList.toggle(this.#iconNotValid, !response.is_valid);
				marqueeEl.classList.toggle(this.#iconValid, response.is_valid);
			});
	}

	/**
	 * Fire a change event.
	 */
	#fireChangeEvent() {
		const changeEvent = new Event('change', { bubbles: true, cancelable: true });
		this.dispatchEvent(changeEvent);
	}
}
