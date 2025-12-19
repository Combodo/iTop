class CollectionElement extends HTMLElement {

	#eBtnAdd;

	// register the custom element
	static {
		customElements.define('collection-element', CollectionElement);
	}

	addFormToCollection(e) {
		const collectionHolder = document.querySelector('.'+e.currentTarget.dataset.collectionHolderClass);
		const item = document.createElement('div');

		const collectionHolderList = collectionHolder.querySelector('[role="list"]');

		item.innerHTML = collectionHolder
			.dataset
			.prototype
			.replace(
				/__name__/g,
				collectionHolder.dataset.index
			);

		collectionHolderList.appendChild(item.firstChild);
		collectionHolder.dataset.index++;

		this.querySelectorAll('collection-entry-element').forEach((entry) => {
			console.log('test');
			entry.updateButtonStates();
		});
	}

	/** connectedCallback **/
	connectedCallback() {
		this.#eBtnAdd = this.querySelector('.add_item_link');
		this.#eBtnAdd.addEventListener('click', this.addFormToCollection.bind(this));
	}

}
