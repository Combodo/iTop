class IboGridSlot extends HTMLElement {
	constructor() {
		super();
	}
	connectedCallback() {

		/** @type {string} unique cell id */
		this.id = '';

		/** @type {number} */
		this.iPosX = this.getAttribute('gs-x') ? parseInt(this.getAttribute('gs-x'), 10) : 0;

		/** @type {number} */
		this.iPostY = this.getAttribute('gs-y') ? parseInt(this.getAttribute('gs-y'), 10) : 0;

		/** @type {number} */
		this.iWidth = this.getAttribute('gs-w') ? parseInt(this.getAttribute('gs-w'), 10) : 1;

		/** @type {number} */
		this.iHeight = this.getAttribute('gs-h') ? parseInt(this.getAttribute('gs-h'), 10) : 1;

		/** @type {IboDashlet|null} contained dashlet id */
		this.sDashletId = null;
		/** @type {IboDashlet|null} contained dashlet element */
		this.oDashlet = this.querySelector('ibo-dashlet') || null;

		/** @type {Object} freeform metadata */
		this.meta = {};
	}

	static MakeNew(oDashletElem, aOptions = {}) {
		const oSlot = document.createElement('ibo-dashboard-grid-slot');
		oDashletElem.classList.add("grid-stack-item-content");

		oSlot.appendChild(oDashletElem);
		oSlot.classList.add("grid-stack-item");
		oSlot.oDashlet = oDashletElem;

		return oSlot;
	}

	Serialize(bIncludeHtml = false) {
		const oDashlet = this.oDashlet;

		const aSlotData = {
			position_x: this.iPosX,
			position_y: this.iPostY,
			width: this.iWidth,
			height: this.iHeight
		};

		const aDashletData = oDashlet ? oDashlet.Serialize() : {};

		return {
			...aSlotData,
			dashlet: {...aDashletData}
		};
	}

	static observedAttributes = ['gs-x', 'gs-y', 'gs-w', 'gs-h'];




	attributeChangedCallback(name, oldValue, newValue) {
		switch (name) {
			case 'gs-x':
				this.iPosX = parseInt(newValue, 10) || 0;
				break;
			case 'gs-y':
				this.iPostY = parseInt(newValue, 10) || 0;
				break;
			case 'gs-w':
				this.iWidth = parseInt(newValue, 10) || 1;
				break;
			case 'gs-h':
				this.iHeight = parseInt(newValue, 10) || 1;
				break;
		}
	}
}

customElements.define('ibo-dashboard-grid-slot', IboGridSlot);
