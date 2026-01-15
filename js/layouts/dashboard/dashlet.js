class IboDashlet extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		/** @type {string} */
		this.sDashletId = this.GetDashletId();
		/** @type {string} */
		this.sType = this.GetDashletType();
		/** @type {Object} */
		this.formData = this.GetFormData();
		/** @type {Object} unused yet */
		this.meta = {};

		this.BindEvents();
	}
	BindEvents() {
		// Bind any dashlet-specific events here
		this.querySelector('.ibo-dashlet--actions [data-role="ibo-dashlet-edit"]')?.addEventListener('click', (e) => {
			this.closest('ibo-dashboard')?.EditDashlet(this.sDashletId);
		});

		this.querySelector('.ibo-dashlet--actions [data-role="ibo-dashlet-clone"]')?.addEventListener('click', (e) => {
			this.closest('ibo-dashboard')?.CloneDashlet(this.sDashletId);
		});

		this.querySelector('.ibo-dashlet--actions [data-role="ibo-dashlet-remove"]')?.addEventListener('click', (e) => {
			this.closest('ibo-dashboard')?.RemoveDashlet(this.sDashletId);
		});
	}

	GetDashletId() {
		return this.getAttribute('data-dashlet-id');
	}

	GetDashletType() {
		return this.getAttribute("data-dashlet-type") || "";
	}

	GetFormData() {
		return this.getAttribute("data-form-view-data") || "";
	}

	static MakeNew(sDashlet) {
		const oDashlet = document.createElement('ibo-dashlet');
		oDashlet.innerHTML = sDashlet;

		return oDashlet;
	}

	Serialize(bIncludeHtml = false) {
	// TODO 3.3 Should we use getters ?
		let aDashletData = {
			id: this.sDashletId,
			type: this.sType,
			properties: JSON.parse(this.formData),
		};

		if(bIncludeHtml) {
			aDashletData.html = this.outerHTML;
		}

		return aDashletData;
	}
}

customElements.define('ibo-dashlet', IboDashlet);
