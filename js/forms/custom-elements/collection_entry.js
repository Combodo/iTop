class CollectionEntryElement extends HTMLElement {

	#eBtnDelete;

	// register the custom element
	static {
		customElements.define('collection-entry-element', CollectionEntryElement);
	}

	/** connectedCallback **/
	connectedCallback() {
		this.#eBtnDelete = this.querySelector('.remove_item_link');
		this.#eBtnDelete.addEventListener('click', this.#removeCollectionItem.bind(this));
	}

	#removeCollectionItem(e) {
		this.remove();
	}
}
