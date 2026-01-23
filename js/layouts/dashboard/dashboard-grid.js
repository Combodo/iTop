class IboGrid extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {

		/** @type {number} */
		this.columnsCount = 12;
		/** @type {boolean} unused yet*/
		this.bEditable = false;
		/** @type {GridStack|null} GridStack instance */
		this.oGrid = null;
		/** @type {Array<IboGridSlot>} */
		this.aSlots = [];


		this.SetupGrid();
	}

	SetupGrid() {
		let aCandidateSlots = Array.from(this.querySelectorAll('ibo-dashboard-grid-slot'));
		aCandidateSlots.forEach(oSlot => {
			const aAttrs = ['gs-x', 'gs-y', 'gs-w', 'gs-h', 'id'];
			aAttrs.forEach(sAttr => {
				const sVal = oSlot.getAttribute(sAttr) || oSlot.getAttribute('data-'+sAttr);
				if (sVal !== null) {
					oSlot.setAttribute(sAttr, sVal);
				}
			});
		});

		this.oGrid = GridStack.init({
			column: this.columnsCount,
			marginTop: 8,
			marginLeft: 8,
			marginRight: 0,
			marginBottom: 0,
			resizable: {handles: 'all'},
			disableDrag: true,
			disableResize: true,
			float: true
		}, this);
	}

	getSlots() {
		return this.oGrid.getGridItems();
	}

	SetEditable(bIsEditable) {
		this.bEditable = bIsEditable;
		if (this.oGrid !== null) {
			this.oGrid.enableMove(bIsEditable);
			this.oGrid.enableResize(bIsEditable);
		}
	}

	GetDashletElement(sDashletId) {
		const aSlots = this.getSlots();

		for (let oSlot of aSlots) {

			if (oSlot.oDashlet && oSlot.oDashlet.sDashletId === sDashletId) {
				return oSlot.oDashlet;
			}
		}
		return null;
	}

	AddDashlet(sDashlet, aOptions = {}) {
		// Get the dashlet as an object
		const oParser = new DOMParser();
		const oDocument = oParser.parseFromString(sDashlet, 'text/html');
		const oDashlet = oDocument.body.querySelector('ibo-dashlet');
		const aScripts = oDocument.body.querySelectorAll('script');

		const oSlot = IboGridSlot.MakeNew(oDashlet);

		this.append(oSlot);

		let aDefaultOptions = {
			autoPosition: !(aOptions.hasOwnProperty('x') || aOptions.hasOwnProperty('y')),
		}
		this.oGrid.makeWidget(oSlot, Object.assign(aDefaultOptions, aOptions));

		oSlot.scrollIntoView({ behavior: "smooth"});

		aScripts.forEach( oScript => {
			if (oScript) {
				const oNewScriptElement = document.createElement('script');

				// copy attributes
				[...oScript.attributes].forEach(attr =>
					oNewScriptElement.setAttribute(attr.name, attr.value)
				);

				oNewScriptElement.text = oScript.textContent;
				oSlot.querySelector('ibo-dashlet').after(oNewScriptElement);
			}
		});

		return oDashlet.sDashletId;
	}

	RefreshDashlet (sDashlet, aOptions = {}) {
		const oParser = new DOMParser();
		const oDocument = oParser.parseFromString(sDashlet, 'text/html');
		const oNewDashlet = oDocument.body.querySelector('ibo-dashlet');
		const aNewScripts = oDocument.body.querySelectorAll('script');

		// Can't use oNewDashet.sDashletId as it's not in the DOM yet and connectedCallback hasn't been called yet
		const oExistingDashlet = this.GetDashletElement(oNewDashlet.getAttribute('data-dashlet-id') );

		// Copy attributes
		for (const sAttr of oNewDashlet.attributes) {
			oExistingDashlet.setAttribute(sAttr.name, sAttr.value);
		}

		// If we refresh a dashlet, its parent slot remains the same, we just need to update its content
		let oSlotForExistingDashlet = null;
		const aSlots = this.getSlots();
		for (let oSlot of aSlots) {
			if (oSlot.oDashlet && oSlot.oDashlet.sDashletId === oNewDashlet.getAttribute('data-dashlet-id')) {
				oSlotForExistingDashlet = oSlot;
				break;
			}
		}

		// There must be a slot for the existing dashlet
		if(oSlotForExistingDashlet !== null) {
			this.oGrid.removeWidget(oSlotForExistingDashlet);
			oSlotForExistingDashlet.innerHTML = oNewDashlet.outerHTML;
			this.oGrid.makeWidget(oSlotForExistingDashlet);
		}

		// Append scripts to body so they are executed
		aNewScripts.forEach( oScript => {
			if (oScript) {
				const oNewScriptElement = document.createElement('script');

				// copy attributes
				[...oScript.attributes].forEach(attr =>
					oNewScriptElement.setAttribute(attr.name, attr.value)
				);

				oNewScriptElement.text = oScript.textContent;
				oSlotForExistingDashlet.querySelector('ibo-dashlet').after(oNewScriptElement);
			}
		});
	}

	CloneDashlet(sDashletId) {
		const aSlots = this.getSlots();
		for (let oSlot of aSlots) {

			if (oSlot.oDashlet && oSlot.oDashlet.sDashletId === sDashletId) {
				const sWidth = oSlot.iWidth;
				const sHeight = oSlot.iHeight;

				// Ask a new rendered dashlet to avoid duplicating IDs
				// Still we'll copy width and height
				this.closest('ibo-dashboard')?.AddNewDashlet(oSlot.oDashlet.sType, oSlot.oDashlet.formData, {
					'w': sWidth,
					'h': sHeight
				});
				break;
			}
		}
	}
	RemoveDashlet(sDashletId) {
		const aSlots = this.getSlots();
		for (let oSlot of aSlots) {
			if (oSlot.oDashlet && oSlot.oDashlet.sDashletId === sDashletId) {
				this.oGrid.removeWidget(oSlot);
				break;
			}
		}
	}

	ClearGrid() {
		this.oGrid.removeAll();
	}

	Serialize() {
		const aSlots = this.getSlots();

		return aSlots.reduce((aAccumulator, oSlot) => {
			aAccumulator[oSlot.oDashlet.sDashletId] = oSlot.Serialize();
			return aAccumulator;
		}, {});
	}
}

customElements.define('ibo-dashboard-grid', IboGrid);
