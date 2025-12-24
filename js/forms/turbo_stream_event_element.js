class TurboStreamEvent extends HTMLElement {

	// register the custom element
	static {
		customElements.define('turbo-stream-event', TurboStreamEvent);
	}

	constructor() {
		super();

		this.style.display = 'none';

		const event = new CustomEvent("itop:TurboStreamEvent", {
			detail: {
				id: this.getAttribute('id'),
				form_id: this.dataset.formId,
				block_class: this.dataset.formBlockClass,
				view_data: this.dataset.viewData,
				valid: this.dataset.valid,
			},
		});

		document.dispatchEvent(event);
	}

}
