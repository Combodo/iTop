class FormElement extends HTMLFormElement
{
	static #TURBO_REFRESHING_CLASS = 'turbo-refreshing';
	static #TURBO_TRIGGER_FIELD = '_turbo_trigger';

	#aFormBlockDataTransmittedData = {};

	// register the custom element
	static {
		customElements.define('itop-form-element', FormElement, {extends: 'form'});
	}

	TriggerTurbo(oElement) {

		// Get the name and id of the element triggering turbo
		const sName = oElement.getAttribute('name');
		const sId = oElement.getAttribute('id');

		if(FormElement.IsCheckbox(oElement) || this.#aFormBlockDataTransmittedData[sName] !== oElement.value) {

			// Refresh UI
			this.#StartRefreshingUI(sId);

			// Pre Submit
			this.#PreSubmitTurboForm(sName);

			// Submit
			oElement.form.requestSubmit();

			// Post Submit
			this.#PostSubmitTurboForm(sName)

			this.#aFormBlockDataTransmittedData[sName] = oElement.value;
		}

	}

	/**
	 * Start refreshing UI.
	 *
	 * @param sId
	 * @constructor
	 */
	#StartRefreshingUI(sId)
	{
		Array.from(this.querySelectorAll(`.ibo-content-block`)).forEach(block => {
			if(block.dataset.impactedBy !== undefined){
				const aImpactedBy = block.dataset.impactedBy.split(',');
				if(aImpactedBy.includes(sId)){
					block.classList.add(FormElement.#TURBO_REFRESHING_CLASS);
				}
			}
		});
	}

	/**
	 * Pre submit the form.
	 * Set the turbo trigger field in the form and disable validation
	 *
	 * @param sName
	 * @constructor
	 */
	#PreSubmitTurboForm(sName)
	{
		this.querySelector(`[name="${this.getAttribute("name")}[${FormElement.#TURBO_TRIGGER_FIELD}]"]`).value = sName;
		this.setAttribute('novalidate', true);
	}

	/**
	 * Post submit the form.
	 * Reset the turbo trigger field and restore form validation.
	 *
	 * @param sName
	 * @constructor
	 */
	#PostSubmitTurboForm(sName)
	{
		this.querySelector(`[name="${this.getAttribute("name")}[${FormElement.#TURBO_TRIGGER_FIELD}]"]`).value = null;
		this.removeAttribute('novalidate');
	}

	/**
	 *
	 * @param oElement
	 * @returns {boolean}
	 */
	static IsCheckbox (oElement)
	{
		return oElement instanceof HTMLInputElement
			&& oElement.getAttribute('type') === 'checkbox'
	}

}


