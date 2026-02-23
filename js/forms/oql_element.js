class OqlElement extends HTMLTextAreaElement {

	static #DEBONCE = 400;

	// register the custom element
	static{
		customElements.define('oql-element', OqlElement, {extends: 'textarea'});
	}

	// variables
	#url = '../pages/ajax.render.php?route=oql.validate_query';
	#iconValid = 'fa-check-double';
	#iconNotValid = 'fa-exclamation-triangle';
	#debounceTimer = null;
	#debounce = OqlElement.#DEBONCE;

	/** connectedCallback **/
	connectedCallback() {
		this.addEventListener('input', this.#onInput.bind(this));
		this.#callValidateQuery();

		this.addEventListener('focus', this.#onFocus.bind(this));

		const oBtnBook = this.closest('.ibo-content-block').querySelector('[data-role="ibo-button"][data-action="book"]');
		oBtnBook.addEventListener('click', this.#search.bind(this))

		const oBtnRun = this.closest('.ibo-content-block').querySelector('[data-role="ibo-button"][data-action="run"]');
		oBtnRun.addEventListener('click', this.#run.bind(this))
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
	 * Call oql verification with debounce when focus event is fired.
	 */
	#onFocus() {
		this.#callValidateQuery();
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
				marqueeEl.setAttribute('title', response.is_valid ? Dict.S(this.dataset.validQueryText) : Dict.S(this.dataset.invalidQueryText));
			});
	}

	/**
	 * Fire a change event.
	 */
	#fireChangeEvent() {
		const changeEvent = new Event('change', { bubbles: true, cancelable: true });
		this.dispatchEvent(changeEvent);
	}

	#search(){
		const sId = this.getAttribute('id');
		const sDialogId = `ac_dlg_${sId}`;

		const sModalTitle = Dict.S(this.dataset.modalTitleText);
		const sEmptyText = Dict.S(this.dataset.emptyText);

		// Instance the widget
		const oACWidget = new ExtKeyWidget(sId, 'QueryOQL', 'SELECT QueryOQL WHERE is_template = \'yes\'', sModalTitle, true, null, null, true, true, 'oql');
		oACWidget.emptyHtml = `<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p><${sEmptyText}/p></div>`;

		// Store in window to be accessible from dialog
		window[`oACWidget_${sId}`] = oACWidget;

		// Open the dialog
		if ($(`#${sDialogId}`).length === 0)
		{
			$('body').append(`<div id="${sDialogId}"></div>`);
			$(`#${sDialogId}`).dialog({
				width: $(window).width()*0.8,
				height: $(window).height()*0.8,
				autoOpen: false,
				modal: true,
				resizeStop: oACWidget.UpdateSizes,
			});
		}

		// Start searching
		oACWidget.Search();
	}

	#run(){
		window.open('../pages/run_query.php?expression=' + encodeURI(this.value), '_blank');
	}
}
