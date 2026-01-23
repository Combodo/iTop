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
			if (this.aLastSavedState) {
				this.Load(this.aLastSavedState);
			}
			this.SetEditMode(false);
		});

		// TODO 3.3 Add event listener to dashboard toggler to get custom/default dashboard switching
		// TODO 3.3 require load method that's not finished yet

		// Bind IboDashboard object to these listener so we can access current instance
		this._ListenToDashletFormSubmission = this._ListenToDashletFormSubmission.bind(this);
		this._ListenToDashletFormCancellation = this._ListenToDashletFormCancellation.bind(this);
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
			// TODO 3.3 If we are in default dashboard display, change to custom to allow editing
			// TODO 3.3 Get the custom dashboard and load it, show a tooltip on the dashboard toggler to explain that we switched to custom mode
			this.aLastSavedState = this.Serialize();
			this.setAttribute("data-edit-mode", "edit");
		}
		else{
			this.setAttribute("data-edit-mode", "view");
		}
	}

	AddNewDashlet(sDashletClass, sDashletValues, aDashletOptions = {}) {
		let oGetDashletPromise = this.GetDashlet(sDashletClass, '', sDashletValues);

		oGetDashletPromise.then(async data => {

				const sDashletId = this.oGrid.AddDashlet(await data.text(), aDashletOptions);

				// Specify that this dashlet is new
				this.EditDashlet(sDashletId, true);
			})

	}

	GetDashlet(sDashletClass, sDashletId = '', sDashletValues = '') {
		let sGetDashletUrl = GetAbsoluteUrlAppRoot() + '/pages/UI.php?route=dashboard.get_dashlet&dashlet_class='+encodeURIComponent(sDashletClass);

		if(sDashletId.length > 0) {
			sGetDashletUrl += '&dashlet_id=' + encodeURIComponent(sDashletId);
		}

		if(sDashletValues.length > 0) {
			sGetDashletUrl += '&values=' + encodeURIComponent(sDashletValues);
		}

		return fetch(sGetDashletUrl);
	}
	RefreshDashlet(oDashlet) {
		let oGetDashletPromise = this.GetDashlet(oDashlet.sType, oDashlet.sDashletId, oDashlet.formData);


		return oGetDashletPromise.then(async data => {

				this.oGrid.RefreshDashlet(await data.text());
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

	DisableFormButtons() {
		const aButtons = this.querySelectorAll('.ibo-dashboard--form--actions button');
		aButtons.forEach( (oButton) => {
			oButton.setAttribute('disabled', 'disabled');
		});
	}

	EnableFormButtons() {
		const aButtons = this.querySelectorAll('.ibo-dashboard--form--actions button');
		aButtons.forEach( (oButton) => {
			oButton.removeAttribute('disabled');
		});
	}

	EditDashlet(sDashletId, bIsNew = false) {
		const oDashlet = this.oGrid.GetDashletElement(sDashletId);
		const me = this;

		// Create backdrop to block interactions with other dashlets
		if(this.oGrid.querySelector('.ibo-dashboard--grid--backdrop') === null) {
			const oBackdrop = document.createElement("div");
			oBackdrop.classList.add('ibo-dashboard--grid--backdrop');
			this.oGrid.append(oBackdrop);
		}

		this.querySelector('ibo-dashlet[data-dashlet-id="'+sDashletId+'"]').setAttribute('data-edit-mode', 'edit');

		const oPanelElement = document.querySelector('.ibo-dashlet-panel');
		// Choose what we'll write as title
		// Also store this information in a data attribute to be able to differentiate between addition and edition on form submission/cancellation
		if(bIsNew) {
			this.SetDashletPanelTitle('Add a dashlet ' + oDashlet.sType);
			oPanelElement.setAttribute('data-dashlet-form-mode', 'add');
		}
		else {
			this.SetDashletPanelTitle('Edit dashlet ' + oDashlet.sType);
			oPanelElement.setAttribute('data-dashlet-form-mode', 'edit');
		}

		// Disable dashboard buttons so we need to finish this edition first
		this.DisableFormButtons();

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

				// Listen to form submission event and cancellation
				document.addEventListener('itop:TurboStreamEvent:Complete', me._ListenToDashletFormSubmission);
				document.querySelector('.ibo-dashlet-panel--form-container button[name="dashboard_cancel"]').addEventListener('click', me._ListenToDashletFormCancellation);
			});
	}

	_ListenToDashletFormSubmission(event) {
		const oDashlet = this.querySelector('ibo-dashlet[data-edit-mode="edit"]');
		const sDashletId = oDashlet.GetDashletId();

		if(event.detail.id === oDashlet.sType + '-turbo-stream-event' && event.detail.valid === "1") {
			// Remove events
			document.addEventListener('itop:TurboStreamEvent:Complete', this._ListenToDashletFormSubmission);
			document.querySelector('.ibo-dashlet-panel--form-container button[name="dashboard_cancel"]').removeEventListener('click', this._ListenToDashletFormCancellation);

			// Notify it all went well
			CombodoToast.OpenToast('Dashlet created/updated', 'success');

			// Clean edit mode
			this.querySelector('ibo-dashlet[data-dashlet-id="'+sDashletId+'"]').setAttribute('data-edit-mode', 'view');
			document.querySelector('.ibo-dashlet-panel').removeAttribute('data-dashlet-form-mode');
			this.ShowDashletTogglers();
			this.ClearDashletForm();
			this.SetDashletPanelTitle();

			// Update local dashlet and refresh it
			oDashlet.formData = event.detail.view_data;
			this.RefreshDashlet(oDashlet);

			// Re-enable dashboard buttons
			this.EnableFormButtons();
		}
	}

	_ListenToDashletFormCancellation(event) {
		const oDashlet = this.querySelector('ibo-dashlet[data-edit-mode="edit"]');
		const sDashletId = oDashlet.GetDashletId();

		// If we are cancelling an addition, remove the dashlet from the grid
		const oPanelElement = document.querySelector('.ibo-dashlet-panel');
		const sDashletFormMode = oPanelElement.getAttribute('data-dashlet-form-mode');

		if(sDashletFormMode === 'add') {
			this.oGrid.RemoveDashlet(sDashletId);
		}
		else if(sDashletFormMode === 'edit') {
			// Just exit edit mode
			this.querySelector('ibo-dashlet[data-dashlet-id="'+sDashletId+'"]').setAttribute('data-edit-mode', 'view');

			// TODO 3.3 If we refresh dashlet view in edit mode, we should restore previous form data + rendering
		}

		// Remove events
		document.addEventListener('itop:TurboStreamEvent:Complete', this._ListenToDashletFormSubmission);
		document.querySelector('.ibo-dashlet-panel--form-container button[name="dashboard_cancel"]').removeEventListener('click', this._ListenToDashletFormCancellation);

		// Clean edit mode
		this.ShowDashletTogglers();
		this.ClearDashletForm();
		this.SetDashletPanelTitle();

		// Re-enable dashboard buttons
		this.EnableFormButtons();
	}

	SetDashletPanelTitle(sTitle = '') {
		const oTitleElement = document.querySelector('.ibo-dashlet-panel .ibo-dashlet-panel--title');

		if (sTitle === '') {
			sTitle = 'Add a dashlet';
		}
		if (oTitleElement) {
			oTitleElement.innerText = sTitle;
		}
	}

	CloneDashlet(sDashletId) {
		this.oGrid.CloneDashlet(sDashletId);
	}

	RemoveDashlet(sDashletId) {
		this.oGrid.RemoveDashlet(sDashletId);
	}

	RefreshFromBackend(bCustomDashboard = false) {
		const sLoadDashboardUrl = GetAbsoluteUrlAppRoot() + `/pages/UI.php?route=dashboard.load&id=${this.sId}&custom=${bCustomDashboard ? 'true' : 'false'}`;
		fetch(sLoadDashboardUrl)
			.then(async oResponse => {
				const oDashletData = await oResponse.json();
				this.Load(oDashletData);
			}
		)
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
			pos_dashlets: aSerializedGrid,
			_token: ":)"
		};
	}

	Save() {
		// This payload shape is expected by the server
		const aPayload = this.Serialize();

		let sSaveUrl = GetAbsoluteUrlAppRoot() + '/pages/UI.php?route=dashboard.save&values='+encodeURIComponent(JSON.stringify(aPayload));
		fetch(sSaveUrl)
			.then(async data => {
				const res = await data.json();
				if(res.status === 'ok') {
					CombodoToast.OpenToast(res.message, 'success');
					this.aLastSavedState = this.Serialize();
					this.SetEditMode(false);
				} else {
					CombodoToast.OpenToast(res.message, 'error');
				}
			})
	}


	Load(aSaveState) {
		try {
			// Validate schema version
			if (aSaveState.schema_version !== this.schemaVersion) {
				CombodoToast.OpenToast('Somehow, we got an incompatible dashboard schema version.', 'error');
				return false;
			}

			// Update dashboard data
			this.sId = aSaveState.id;
			this.sTitle = aSaveState.title || "";
			this.iRefreshRate = parseInt(aSaveState.refresh, 10) || 0;

			// Update form inputs if they exist
			const oTitleInput = this.querySelector('.ibo-dashboard--form--inputs input[name="dashboard_title"]');
			if (oTitleInput) {
				oTitleInput.value = this.sTitle;
			}

			const oRefreshSelect = this.querySelector('.ibo-dashboard--form--inputs select[name="refresh_interval"]');
			if (oRefreshSelect) {
				oRefreshSelect.value = aSaveState.refresh;
			}

			// Clear existing grid
			this.ClearGrid();

			// Load dashlets
			const aDashletSlots = aSaveState.pos_dashlets || {};

			for (const [sDashletId, aDashletData] of Object.entries(aDashletSlots)) {
				const iPosX = aDashletData.position_x;
				const iPosY = aDashletData.position_y;
				const iWidth = aDashletData.width;
				const iHeight = aDashletData.height;
				const aDashlet = aDashletData.dashlet;

				// We need to fetch dashlet HTML from server as scripts need to be executed again
				let oGetDashletPromise = this.GetDashlet(aDashlet.type, aDashlet.id, JSON.stringify(aDashlet.properties));


				oGetDashletPromise.then(async data => {
					let sDashletHtml = await data.text();
					// Add dashlet to grid with its position and size
					this.oGrid.AddDashlet(sDashletHtml, {
						x: iPosX,
						y: iPosY,
						w: iWidth,
						h: iHeight,
						autoPosition: false
					});
				});
			}

			// Update last saved state
			this.aLastSavedState = aSaveState;

			return true;

		} catch (error) {
			console.error('Error loading dashboard state:', error);
			CombodoToast.OpenToast('Error loading dashboard state', 'error');
			return false;
		}
	}

	ClearGrid() {
		this.oGrid.ClearGrid();
	}

	DisplayError(sMessage, sSeverity = 'error') {
		// TODO 3.3: Make this real
		this.setAttribute("data-edit-mode", "error");
	}
}

customElements.define('ibo-dashboard', IboDashboard);
