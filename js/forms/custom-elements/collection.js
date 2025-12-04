class CollectionElement extends HTMLElement {

	#eBtnAdd;

	// register the custom element
	static {
		customElements.define('collection-element', CollectionElement);
	}

	static addFormToCollection(e) {
		const collectionHolder = document.querySelector('.'+e.currentTarget.dataset.collectionHolderClass);
		const item = document.createElement('div');

		item.innerHTML = collectionHolder
			.dataset
			.prototype
			.replace(
				/__name__/g,
				collectionHolder.dataset.index
			);

		collectionHolder.appendChild(item);
		collectionHolder.dataset.index++;
	}

	/** connectedCallback **/
	connectedCallback() {
		this.#eBtnAdd = this.querySelector('.add_item_link');
		this.#eBtnAdd.addEventListener('click', CollectionElement.addFormToCollection);
	}

	#removeCollectionItem() {

	}
}
