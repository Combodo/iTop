class CollectionElement extends HTMLElement {

	#eBtn;

	// register the custom element
	static {
		customElements.define('collection-element', CollectionElement);
	}

	static addFormToCollection(e) {
		const collectionHolder = document.querySelector('.'+e.currentTarget.dataset.collectionHolderClass);
		const item = document.createElement('div');

		item.style.marginTop = '20px';
		item.innerHTML = collectionHolder
			.dataset
			.prototype
			.replace(
				/__name__/g,
				collectionHolder.dataset.index
			);

		collectionHolder.appendChild(item);
		collectionHolder.dataset.index++;
		console.log(collectionHolder.dataset.index);
	}

	/** connectedCallback **/
	connectedCallback() {
		this.#eBtn = this.querySelector('.add_item_link');
		this.#eBtn.addEventListener('click', CollectionElement.addFormToCollection);
	}

}
