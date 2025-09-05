/*
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

;
$(function() {
	$.widget('itop.caselog_entry_form',
		{
			// default options
			options:
			{
				object_class: null,
				object_id: null,
				attribute_code: null,
				submit_mode: 'autonomous',
				text_input_id: '',
			},
			css_classes:
			{
				is_opened: 'ibo-is-opened',
				is_closed: 'ibo-is-closed',
				is_hidden: 'ibo-is-hidden',
			},
			js_selectors:
			{
				activity_panel: '[data-role="ibo-activity-panel"]',
				activity_panel_toolbar: '[data-role="ibo-activity-panel--tab-toolbar"]',
				form: '[data-role="ibo-caselog-entry-form"]', // Any caselog entry form
				main_actions: '[data-role="ibo-caselog-entry-form--action-buttons--main-actions"]',
				cancel_button: '[data-role="ibo-caselog-entry-form--action-buttons--main-actions"] [data-role="ibo-button"][name="cancel"]',
				save_button: '[data-role="ibo-caselog-entry-form--action-buttons--main-actions"] [data-role="ibo-button"][name="save"]',
				save_choices_picker: '[data-role="ibo-caselog-entry-form--action-buttons--main-actions"] [data-role="ibo-button"][name="save"] + [data-role="ibo-button"]',
			},
			enums:
			{
				submit_mode:
				{
					autonomous: 'autonomous',
					bridged: 'bridged',
				}
			},
			is_draft: false,
			
			// the constructor
			_create: function () {
				const me = this;
				const aMandatoryOptions = ['object_class', 'object_id', 'attribute_code'];
				for (let sOption of aMandatoryOptions) {
					if (null === this.options[sOption]) {
						CombodoJSConsole.Error('CaseLogEntryForm: Could not initialize widget, make sure that the following options'+
							' are passed: '+aMandatoryOptions.join(' / '), 'error');
						return false;
					}
				}

				// Ensure the CKEditor instance is ready before proceding
				this._GetCKEditorInstance(true).then((oCKEditorInstance) => {
					me._UpdateState();
					if (me._IsSubmitAutonomous()) {
						me._ShowMainActions();
					} else {
						me._AddBridgeInput();
						me._HideMainActions();
					}

					me._bindEvents();

					me.element.trigger('ready.caselog_entry_form.itop');
				});
			},
			_bindEvents: function () {
				let me = this;
				let CKEditorInstance = this._GetCKEditorInstance();
				// Handlers for the CKEditor itself
				// Handle only the current CKEditor instance
				// if (oEvent.editor.name !== me.options.text_input_id) {
				// 	return;
				// }

				// Update depending elements on change
				// Note: That when images are uploaded, the "change" event is triggered before the image upload is complete, meaning that we don't have the <img> tag yet.
				CKEditorInstance.model.document.on('change:data', async function () {
					const bWasDraftBefore = me.is_draft;
					const bIsDraftNow = !(me._IsInputEmpty());
					if (bWasDraftBefore !== bIsDraftNow) {
						me.is_draft = bIsDraftNow;
						me._UpdateEditingVisualHint();
						// Note: We must not call me._UpdateSubmitButtonState() as it will be updated by the disable_submission/enable_submission events
					}
				});

				// Dispatch submission to the right pipeline on submit
				$(me.element).on('submit', function (oSubmitEvent) {
					oSubmitEvent.preventDefault();
					if (me._IsSubmitAutonomous()) {
						me._RequestSubmission();
					} else {
						me._GetGeneralFormElement().trigger('submit');
					}
				});

				if (false === this._IsSubmitAutonomous()) {
					// Update the general form input on submit.
					// This cannot be "completely" done in the "change" handler above because we don't have an event for when
					// the image has been uploaded and its HTML markup added to the data. The "change" event occurs too early.
					if (null === this._GetGeneralFormElement()) {
						CombodoJSConsole.Error('CaseLogEntryForm: Could not find the general form element, image upload will NOT work in CKEditor');
					} else {
						this._GetGeneralFormElement().on('submit', function () {
							me._UpdateBridgeInput();
						});
					}
				}

				// Form buttons
				this.element.find(this.js_selectors.cancel_button).on('click', function (oEvent) {
					me.element.trigger('cancelled_form.caselog_entry_form.itop');
				});
				this.element.find(this.js_selectors.save_button).on('click', function (oEvent) {
					// Avoid form being submitted
					oEvent.preventDefault();

					me._RequestSubmission();
				});

				// Form show/hide
				this.element.on('show_form.caselog_entry_form.itop', function () {
					me._ShowEntryForm();
				});
				this.element.on('hide_form.caselog_entry_form.itop', function () {
					me._HideEntryForm();
				});

				// Form submission
				this.element.on('save_entry.caselog_entry_form.itop', function (oEvent, oData) {
					me._RequestSubmission(oData.stimulus_code);
				});

				// Form enable/disable submission
				this.element.on('disable_submission.caselog_entry_form.itop', function () {
					me._DisableSubmission();
				});
				this.element.on('enable_submission.caselog_entry_form.itop', function () {
					me._EnableSubmission();
				});

				// Form pending submission states
				this.element.on('enter_pending_submission_state.caselog_entry_form.itop', function () {
					me._EnterPendingSubmissionState();
				});
				this.element.on('leave_pending_submission_state.caselog_entry_form.itop', function () {
					me._LeavePendingSubmissionState();
				});

				// Get the entry value
				this.element.on('get_entry.caselog_entry_form.itop', function () {
					return me._GetInputData();
				});
				this.element.on('get_extra_inputs.caselog_entry_form.itop', function () {
					return me._GetExtraInputs();
				});
				// Clear the entry value
				this.element.on('clear_entry.caselog_entry_form.itop', function () {
					me._EmptyInput();
				});
				// Set focus in the input
				this.element.on('set_focus.caselog_entry_form.itop', function () {
					CKEditorInstance.focus();
				});
			},

			// Helpers
			_IsSubmitAutonomous: function () {
				return this.options.submit_mode === this.enums.submit_mode.autonomous;
			},
			/**
			 * @param sStimulusCode {string} Optional stimulus code to apply after submission
			 * @return {void}
			 * @private
			 */
			_RequestSubmission: function (sStimulusCode = null) {
				let oData = {};

				if (null !== sStimulusCode) {
					oData['stimulus_code'] = sStimulusCode;
				}

				this.element.trigger('requested_submission.caselog_entry_form.itop', oData);
			},
			// - Form
			_GetCKEditorInstance: function (bAsync = false) {
				return bAsync ? CombodoCKEditorHandler.GetInstance('#'+this.options.text_input_id) : CombodoCKEditorHandler.GetInstanceSynchronous('#'+this.options.text_input_id);
			},
			_ShowEntryForm: function () {
				this.element.closest(this.js_selectors.activity_panel).find(this.js_selectors.form).removeClass(this.css_classes.is_closed);
			},
			_HideEntryForm: function () {
				this.element.closest(this.js_selectors.activity_panel).find(this.js_selectors.form).addClass(this.css_classes.is_closed);

				// TODO 3.0.0: This should also clear the form (input, lock, send button, ...)
			},
			_DisableSubmission: function () {
				this.element.find(this.js_selectors.save_button+', '+this.js_selectors.save_choices_picker).prop('disabled', true);
			},
			_EnableSubmission: function () {
				this.element.find(this.js_selectors.save_button+', '+this.js_selectors.save_choices_picker).prop('disabled', false);
			},
			_EnterPendingSubmissionState: function () {
				this._GetCKEditorInstance().enableReadOnlyMode('hi');
				this.element.find(this.js_selectors.cancel_button).prop('disabled', true);
				this._DisableSubmission();
			},
			_LeavePendingSubmissionState: function () {
				this._GetCKEditorInstance().disableReadOnlyMode('hi');
				this.element.find(this.js_selectors.cancel_button).prop('disabled', false);
				this._EnableSubmission();
			},
			// - Bridged form input
			/**
			 * Return the general object form element.
			 * Only used for caselog tabs in bridged mode.
			 *
			 * @returns {null|jQuery.fn.init|jQuery|HTMLElement}
			 * @private
			 */
			_GetGeneralFormElement: function () {
				const oActivityPanelElem = this.element.closest(this.js_selectors.activity_panel);
				const sHostObjClass = oActivityPanelElem.attr('data-object-class');
				const sHostObjId = oActivityPanelElem.attr('data-object-id');
				const oGeneralFormElem = $('.ibo-object-details[data-object-class="'+sHostObjClass+'"][data-object-id="'+sHostObjId+'"]').closest('form');

				// Protection in case this is called with non editable general form
				if (oGeneralFormElem.length === 0) {
					return null;
				}

				return oGeneralFormElem;
			},
			/**
			 * Add a bridge input for the caselog to the general object form.
			 * Only used for caselog tabs in bridged mode.
			 *
			 * @returns {boolean}|{void}
			 * @private
			 */
			_AddBridgeInput: function() {
				const sCaseLogAttCode = this.element.closest(this.js_selectors.activity_panel_toolbar).attr('data-caselog-attribute-code');
				const oGeneralFormElem = this._GetGeneralFormElement();

				if(oGeneralFormElem === null) {
					CombodoJSConsole.Error('CaseLogEntryForm: Could not add bridge input as there is no general form');
					return false;
				}

				$('<input type="hidden" name="attr_'+sCaseLogAttCode+'" />').appendTo(oGeneralFormElem);
				this._UpdateBridgeInput();
			},
			/**
			 * Update the bridge input for the caselog in the general object form.
			 * Only used for caselog tabs in bridged mode.
			 *
			 * @returns {void}
			 * @private
			 */
			_UpdateBridgeInput: function () {
				const sCaseLogAttCode = this.element.closest(this.js_selectors.activity_panel_toolbar).attr('data-caselog-attribute-code');
				let oBridgeInputElem = this._GetGeneralFormElement().find('input[name="attr_'+sCaseLogAttCode+'"]');

				oBridgeInputElem.val(this._GetInputData());
			},
			// - Input zone
			_EmptyInput: function() {
				this._GetCKEditorInstance().setData('');
				this._UpdateEditingVisualHint();
			},
			/**
			 * @returns {boolean} True if the input has no text
			 * @private
			 */
			_IsInputEmpty: function () {
				let sCKEditorValue = this._GetInputData();
				return sCKEditorValue === '';
			},
			_GetInputData: function () {
				let oCKEditorInstance = this._GetCKEditorInstance()
				return (oCKEditorInstance === undefined) ? '' : oCKEditorInstance.getData();
			},
			_GetExtraInputs: function() {
				let aExtraInputs = {};
				const aFormInputs = this.element.serializeArray();
				// Iterate across all values that would be sent if we submit current form
				for (const aExtraInput of aFormInputs) {
					// If we don't already have a value with the same name, add it
					// Otherwise we'll consider that we need to return this value as an array of values
					if(aExtraInputs[aExtraInput.name] === undefined) {
						aExtraInputs[aExtraInput.name] = aExtraInput.value;
					}
					else {
						if(Array.isArray(aExtraInputs[aExtraInput.name])){
							aExtraInputs[aExtraInput.name].push(aExtraInput.value);
						}
						else{
							aExtraInputs[aExtraInput.name] = [aExtraInputs[aExtraInput.name], aExtraInput.value];
						}
					}
				};
				return aExtraInputs;
			},
			// - Main actions
			_ShowMainActions: function() {
				this.element.find(this.js_selectors.main_actions).removeClass(this.css_classes.is_hidden);
			},
			_HideMainActions: function() {
				this.element.find(this.js_selectors.main_actions).addClass(this.css_classes.is_hidden);
			},
			_UpdateState: function() {
				this._UpdateEditingVisualHint();
				this._UpdateSubmitButtonState();
			},
			_UpdateSubmitButtonState: function() {
				if (this._IsInputEmpty()) {
					this._DisableSubmission();
				} else {
					this._EnableSubmission();
				}
			},
			_UpdateEditingVisualHint: function () {
				const sEvent = this._IsInputEmpty() ? 'emptied' : 'draft';
				this.element.trigger(sEvent+'.caselog_entry_form.itop', {attribute_code: this.options.attribute_code});
			}
		});
});