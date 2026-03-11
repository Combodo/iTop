class ChoicesElement extends HTMLSelectElement {

	// register the custom element
	static {
		customElements.define('choices-element', ChoicesElement, {extends: 'select'});
	}

	plugins = [];
	connectedCallback() {

		if (this.tomselect) {
			return;
		}

		if (this.getAttribute('multiple')) {
			this.plugins.push('remove_button');
		}

		const options = {
			plugins: this.plugins,
			wrapperClass: 'ts-wrapper ibo-input-wrapper ibo-input-select-wrapper--with-buttons ibo-input-select-autocomplete-wrapper',
			controlClass: 'ts-control ibo-input ibo-input-select ibo-input-select-autocomplete',
			dropdownParent: 'body',
			render: {
				dropdown: function (data, escape) {
					return `<div class="selectize-dropdown"></div>`;
				}
			}
		};

		if (this.getAttribute('data-tom-select-disable-auto-complete')) {
			// options.controlInput = null;
		}
		if (this.getAttribute('data-tom-select-max-items-selected') && this.getAttribute('data-tom-select-max-items-selected') !== '') {
			options.maxItems = parseInt(this.getAttribute('data-tom-select-max-items-selected'));
		}
		if (this.getAttribute('data-tom-select-placehelder')) {
			options.placeholder = this.getAttribute('data-tom-select-placehelder');
		}

		new TomSelect(this, options);
	}
}


