class TomSelectElement extends HTMLSelectElement {
	connectedCallback() {
			new TomSelect(this, {

			});
	}
}

customElements.define('tom-select-element', TomSelectElement, {extends: 'select'});
