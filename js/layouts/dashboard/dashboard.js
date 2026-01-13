class IboDashboard extends HTMLElement {
	constructor() {
		super();

		/** @type {string} */
		this.sId = "";
		/** @type {string} */
		this.sTitle = "";
		/** @type {boolean} unused yet */
		this.isEditable = false;
		/** @type {boolean} */
		this.bEditMode = false;
		/** @type {boolean}  unused yet */
		this.bAutoRefresh = false;
		/** @type {number} unused yet */
		this.iRefreshRate = 0;
		/** @type {IboGrid|null} */
		this.oGrid = null;
		/** @type {string|null} unused yet */
		this.refreshUrl = null;
		/** @type {string|null} unused yet */
		this.csrfToken = null;
		/** @type {number} Payload schema version */
		this.schemaVersion = 2;

		/** @type {object|null} Last saved state for cancel functionality, unused yet */
		this.aLastSavedState = null;
	}

	connectedCallback() {
		this.sId = this.getAttribute("id");
		this.bEditMode = (this.getAttribute("data-edit-mode") === "edit");
		this.SetupGrid();

		this.BindEvents();
	}

	BindEvents() {
		document.getElementById('ibo-dashboard-menu-edit-'+this.sId)?.addEventListener('click', (e) => {
			e.preventDefault();
			this.ToggleEditMode();
			document.getElementById('ibo-dashboard-menu-edit-'+this.sId)?.classList.toggle('active', this.GetEditMode());
		});

		this.querySelector('[data-role="ibo-button"][name="save"]')?.addEventListener('click', (e) => {
			e.preventDefault();
			this.Save()
		});

		this.querySelector('[data-role="ibo-button"][name="cancel"]')?.addEventListener('click', (e) => {
			e.preventDefault();
			// TODO 3.3: Implement cancel functionality (reload last saved state)
			this.SetEditMode(false)
		});
	}
	SetupGrid() {
		if(this.oGrid !== null){
			return;
		}

		this.oGrid = this.querySelector('ibo-dashboard-grid');
	}


	GetEditMode() {
		return this.bEditMode;
	}

	ToggleEditMode(){
		this.SetEditMode(!this.bEditMode);
	}

	SetEditMode(bEditMode) {
		this.bEditMode = bEditMode;

		this.oGrid.SetEditable(this.bEditMode);
		if(this.bEditMode){
			this.aLastSavedState = this.Serialize();
			this.setAttribute("data-edit-mode", "edit");
		}
		else{
			this.setAttribute("data-edit-mode", "view");
		}
	}

	AddNewDashlet(sDashletClass, sDashletValues, aDashletOptions = {}) {
		const sNewDashletUrl = GetAbsoluteUrlAppRoot() + '/pages/UI.php?route=dashboard.get_dashlet&dashlet_class='+encodeURIComponent(sDashletClass);
		fetch(sNewDashletUrl)
			.then(async data => {

				const sDashletId = this.oGrid.AddDashlet(await data.text(), aDashletOptions);

				// TODO 3.3 Either open the dashlet form right away, or just enter edit mode
				this.EditDashlet(sDashletId);
			})

	}

	RefreshDashlet(sDashletId) {
		const oDashletElem = this.oGrid.GetDashletElement(sDashletId);

		let sGetDashletUrl = GetAbsoluteUrlAppRoot() + '/pages/UI.php?route=dashboard.get_dashlet&dashlet_class=' + encodeURIComponent(oDashletElem.sType)
			+'&dashlet_id=' + encodeURIComponent(oDashletElem.sDashletId);
		if(oDashletElem.formData.length > 0) {
			sGetDashletUrl += '&values=' + encodeURIComponent(oDashletElem.formData);
		}

		return fetch(sGetDashletUrl)
			.then(async data => {

				const sDashletId = this.oGrid.RefreshDashlet(await data.text());
		});
	}

	HideDashletTogglers() {
		const aTogglers = document.querySelector('.ibo-dashlet-panel--entries');
		aTogglers.classList.add('ibo-is-hidden');
	}

	ShowDashletTogglers() {
		const aTogglers = document.querySelector('.ibo-dashlet-panel--entries');
		aTogglers.classList.remove('ibo-is-hidden');
	}

	SetDashletForm(sFormData) {
		const oFormContainer = document.querySelector('.ibo-dashlet-panel--form-container');
		oFormContainer.innerHTML = sFormData;
		oFormContainer.classList.remove('ibo-is-hidden');
	}

	ClearDashletForm() {
		const oFormContainer = document.querySelector('.ibo-dashlet-panel--form-container');
		oFormContainer.innerHTML = '';
		oFormContainer.classList.add('ibo-is-hidden');
	}

	EditDashlet(sDashletId) {
		console.log(this.oGrid);
		const oDashlet = this.oGrid.GetDashletElement(sDashletId);
		const me = this;
		// TODO 3.3: Implement dashlet editing when forms are ready
		console.log("Edit dashlet: "+sDashletId);

		// Create backdrop to block interactions with other dashlets
		if(this.oGrid.querySelector('.ibo-dashboard--grid--backdrop') === null) {
			const oBackdrop = document.createElement("div");
			oBackdrop.classList.add('ibo-dashboard--grid--backdrop');
			this.oGrid.append(oBackdrop);
		}

		this.querySelector('ibo-dashlet[data-dashlet-id="'+sDashletId+'"]').setAttribute('data-edit-mode', 'edit');

		// Disable dashboard buttons so we need to finish this edition first

		// Fetch dashlet form from server
		let sGetashletFormUrl = GetAbsoluteUrlAppRoot() + '/pages/UI.php?route=dashboard.get_dashlet_form&dashlet_class='+encodeURIComponent(oDashlet.sType);

		if(oDashlet.formData.length > 0) {
			sGetashletFormUrl += '&values=' + encodeURIComponent(oDashlet.formData);
		}

		fetch(sGetashletFormUrl)
			.then(async formData => {
				const sFormData = await formData.text();

				this.HideDashletTogglers();
				this.SetDashletForm(sFormData);
				// Listen to form submission event to display
				document.addEventListener('itop:TurboStreamEvent', function (event) {
					console.log(event);
					if(event.detail.id === oDashlet.sType + '-turbo-stream-event' && event.detail.valid === "1") {
						// Notify it all went well
						CombodoToast.OpenToast('Dashlet created/updated');

						// Clean edit mode
						me.querySelector('ibo-dashlet[data-dashlet-id="'+sDashletId+'"]').setAttribute('data-edit-mode', 'view');
						me.ShowDashletTogglers();
						me.ClearDashletForm();

						// Update local dashlet and refresh it
						oDashlet.formData = event.detail.view_data;
						me.RefreshDashlet(oDashlet.sDashletId);

						// TODO 3.3 Remove both event listener by making this code a dedicated function and call removeEventListener on it
					}
				});

				document.querySelector('.ibo-dashlet-panel--form-container button[name="dashboard_cancel"]').addEventListener('click', function (event) {
					// TODO 3.3 Remove both event listener by making this code a dedicated function and call removeEventListener on it

					// Clean edit mode
					me.querySelector('ibo-dashlet[data-dashlet-id="'+sDashletId+'"]').setAttribute('data-edit-mode', 'view');
					me.ShowDashletTogglers();
					me.ClearDashletForm();

					// TODO 3.3 If this is an addition, remove the previewed dashlet
					// TODO 3.3 If this is an edition, revert the dashlet to its initial state
				})
			});
	}

	CloneDashlet(sDashletId) {
		this.oGrid.CloneDashlet(sDashletId);
	}

	RemoveDashlet(sDashletId) {
		this.oGrid.RemoveDashlet(sDashletId);
	}

	Serialize() {
		const sDashboardTitle = this.querySelector('.ibo-dashboard--form--inputs input[name="dashboard_title"]').value;
		const sDashboardRefreshRate = this.querySelector('.ibo-dashboard--form--inputs select[name="refresh_interval"]').value;

		const aSerializedGrid = this.oGrid.Serialize();
		return {
			schema_version: this.schemaVersion,
			id: this.sId,
			title: sDashboardTitle,
			refresh: sDashboardRefreshRate,
			dashlets_list: aSerializedGrid,
			_token: ":)"
		};
	}

	Save() {
		const aPayload = this.Serialize();
		// TODO 3.3: Implement saving dashboard state to server when backend is ready
		// May try to save as serialized PHP if XML format is not yet decided
		console.log(aPayload);
		// Fetch dashlet form from server
		let sSaveUrl = GetAbsoluteUrlAppRoot() + '/pages/UI.php?route=dashboard.save&values='+encodeURIComponent(JSON.stringify(aPayload));
		fetch(sSaveUrl)
			.then(async data => {
				// TODO 3.3 What's returned ?
			})

		this.SetEditMode(false);
		this.aLastSavedState = aPayload;
	}


	async Load(aSaveState) {
		// TODO 3.3: Implement loading dashboard state either from server or local payload
		// aSaveState expected shape (example):
		// { schema_version: 1, id: "...", title: "...", refresh_rate: "...", dashlets: [ { x, y, w, h, id, type, formData, content? }, ... ] }

		if (!aSaveState || typeof aSaveState !== 'object') {
			this.DisplayError('Invalid dashboard save state payload');
			return;
		}

		if (aSaveState.schema_version !== this.schemaVersion) {
			this.DisplayError('Load schema version mismatch');
		}

		// set basic props
		if (aSaveState.id) {
			this.sId = aSaveState.id;
			// keep element id attribute in sync
			this.setAttribute('id', this.sId);
		}
		if (typeof aSaveState.title !== 'undefined') {
			this.sTitle = aSaveState.title;
			const titleInput = this.querySelector('.ibo-dashboard--form--inputs input[name="dashboard_title"]');
			if (titleInput) titleInput.value = this.sTitle;
		}
		if (typeof aSaveState.refresh_rate !== 'undefined') {
			this.iRefreshRate = Number(aSaveState.refresh_rate) || 0;
			const sel = this.querySelector('.ibo-dashboard--form--inputs select[name="refresh_interval"]');
			if (sel) sel.value = aSaveState.refresh_rate;
		}

		// -- clear existing grid --
		this.SetupGrid();
		if (this.oGrid) {
			this.oGrid.Clear();
		}

		// -- iterate dashlets and re-create them --
		const aDashlets = Array.isArray(aSaveState.dashlets) ? aSaveState.dashlets : [];
		for (const slotPayload of aDashlets) {
			// ensure minimal shape
			const x = Number(slotPayload.x) || undefined;
			const y = Number(slotPayload.y) || undefined;
			const w = Number(slotPayload.w) || undefined;
			const h = Number(slotPayload.h) || undefined;

			// If the payload includes the full HTML markup for the dashlet, use it.
			// Otherwise, attempt to fetch markup from server based on dashlet type.
			let sDashletHtml = null;
			if (slotPayload.content) {
				sDashletHtml = slotPayload.content;
			} else if (slotPayload.type) {
				try {
					// Use the same endpoint you use for new dashlet creation as a fallback.
					// Your server may provide an endpoint that accepts type and returns pre-rendered HTML.
					const sUrl = GetAbsoluteUrlAppRoot() + '/pages/UI.php?route=dashboard.new_dashlet&dashlet_class=' + encodeURIComponent(slotPayload.type);
					const res = await fetch(sUrl);
					if (res.ok) {
						sDashletHtml = await res.text();
					} else {
						console.warn('Failed to fetch dashlet HTML for type', slotPayload.type, res.status);
					}
				} catch (e) {
					console.warn('Error fetching dashlet HTML for type', slotPayload.type, e);
				}
			}

			// if still missing markup, use a placeholder
			if (!sDashletHtml) {
				sDashletHtml = `<div class="ibo-dashlet--placeholder">Missing dashlet content for type: ${slotPayload.type || 'unknown'}</div>`;
			}

			// Add dashlet at requested position/size
			const aOptions = {};
			if (typeof x !== 'undefined') aOptions.x = x;
			if (typeof y !== 'undefined') aOptions.y = y;
			if (typeof w !== 'undefined') aOptions.w = w;
			if (typeof h !== 'undefined') aOptions.h = h;

			const oSlot = this.oGrid.AddDashlet(sDashletHtml, aOptions);

			// If payload contains dashlet metadata (id/type/formData) — apply it
			if (slotPayload.id || slotPayload.type) {
				const dashletElem = oSlot.oDashlet || oSlot.querySelector('ibo-dashlet');
				if (dashletElem) {
					if (slotPayload.id) dashletElem.sDashletId = slotPayload.id;
					if (slotPayload.type) dashletElem.type = slotPayload.type;
					if (slotPayload.formData) {
						dashletElem.ApplyFormData(slotPayload.formData);
					}
				}
			}
		}

		// Save loaded state as last saved so cancel can restore it
		this.aLastSavedState = aSaveState;

		// Exit edit-mode: loading a previous state implies not being in edit mode by default
		this.SetEditMode(false);
	}

	DisplayError(sMessage, sSeverity = 'error') {
		// TODO 3.3: Make this real
		this.setAttribute("data-edit-mode", "error");
	}
}

customElements.define('ibo-dashboard', IboDashboard);
