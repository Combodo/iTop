/*
 * Copyright (C) 2013-2020 Combodo SARL
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
				submit_mode: 'autonomous',
				submit_button_disabled: true,
				target_type: null,
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
				toggler: '[data-role="ibo-activity-panel--body--add-caselog-entry--toggler"]',
				right_actions: '[data-role="ibo-caselog-entry-form--action-buttons--right-actions"]',
				cancel_button: '[data-role="ibo-caselog-entry-form--action-buttons--right-actions"] [data-role="ibo-button"][name="cancel"]',
				send_button: '[data-role="ibo-caselog-entry-form--action-buttons--right-actions"] [data-role="ibo-button"][name="send"]',
				send_choices_picker: '[data-role="ibo-caselog-entry-form--action-buttons--right-actions"] [data-role="ibo-button"][name="send"] + [data-role="ibo-popover-menu"]',
			},
			enums:
			{
				submit_mode:
				{
					autonomous: 'autonomous',
					bridged: 'bridged',
				},
				target_type:
				{
					caselog: 'caselog',
					activity: 'activity',
				}
			},
			
			// the constructor
			_create: function () {
				let me = this;

				this._UpdateSubmitButtonState();
				if(this._IsSubmitAutonomous())
				{
					this._HideEntryForm();
				}
				else
				{
					this._ShowEntryForm();
				}

				this._bindEvents();

				// TODO 3.0.0: Modify PopoverMenu so we can pass it the ID of the block triggering the open/close
				$(this.element).find(this.js_selectors.send_choices_picker).popover_menu({toggler: this.js_selectors.send_button});

			},
			_bindEvents: function() {
				let me = this;

				// Composer toggle
				this.element.closest(this.js_selectors.activity_panel).find(this.js_selectors.toggler).on('click', function(oEvent){
					me._ShowEntryForm();
				});

				// Enable send button only when content
				CKEDITOR.on('instanceReady', function(oEvent){
					// Handle only the current CKEditor instance
					if(oEvent.editor.name === me.options.text_input_id) {
						CKEDITOR.instances[me.options.text_input_id].on('change', function(){
							me._UpdateSubmitButtonState();
						});
					}
				});

				// Form buttons
				this.element.find(this.js_selectors.cancel_button).on('click', function(oEvent){
					me._HideEntryForm();
				});
				this.element.find(this.js_selectors.send_button).on('click', function(oEvent){
					// Avoid form being submitted
					oEvent.preventDefault();

					if(me.options.target_type === 'caselog')
					{
						let sCaselogAttCode = me.element.closest(me.js_selectors.activity_panel_toolbar).attr('data-caselog-attribute-code');
						me._SubmitEntryToCaselog(me._GetInputData(), sCaselogAttCode);
					}
					else
					{
						// TODO 3.0.0: Modify public methods of popover_menu to open/close to match other widgets naming conventions
						me.element.find(me.js_selectors.send_choices_picker).popover_menu('openPopup');
					}
				});

				// Caselog selection
				this.element.on('add_to_caselog.caselog_entry_form.itop', function(oEvent, oData){
					const sCaseLogAttCode = oData.caselog_att_code;
					const sStimulusCode = oData.stimulus_code !== undefined ? oData.stimulus_code : null;

					me._SubmitEntryToCaselog(me._GetInputData(), sCaseLogAttCode, sStimulusCode);
				});
			},
			_SubmitEntryToCaselog: function(sEntryContent, sCaselogAttCode, sStimulusCode = null){
				const me = this;
				const sObjClass = this.element.closest(this.js_selectors.activity_panel).attr('data-object-class');
				const sObjId = this.element.closest(this.js_selectors.activity_panel).attr('data-object-id');

				let oParams = {
					'operation' : 'add_caselog_entry',
					'class' : sObjClass,
					'id' : sObjId,
					'caselog_new_entry': sEntryContent,
					'caselog_attcode' : sCaselogAttCode,
					'caselog_rank' : this.element.closest(this.js_selectors.activity_panel).activity_panel('GetCaseLogRank', sCaselogAttCode),
				}
				//TODO 3.0.0 Handle errors
				$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(sNewEntry){
					me.element.closest(me.js_selectors.activity_panel).activity_panel('AddEntry', sNewEntry, 'caselog:' + sCaselogAttCode)
					me._EmptyInput();
					me._HideEntryForm();

					// Redirect to stimulus
					if(sStimulusCode !== null){
						window.location.href = GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=stimulus&class='+sObjClass+'&id='+sObjId+'&stimulus='+sStimulusCode;
					}
				});
			},

			// Helpers
			_IsSubmitAutonomous: function() {
				return this.options.submit_mode === this.enums.submit_mode.autonomous;
			},
			_ShowEntryForm: function () {
				this.element.closest(this.js_selectors.activity_panel).find(this.js_selectors.form).removeClass(this.css_classes.is_closed);
				this.element.closest(this.js_selectors.activity_panel).find(this.js_selectors.toggler).addClass(this.css_classes.is_hidden);
			},
			_HideEntryForm: function () {
				this.element.closest(this.js_selectors.activity_panel).find(this.js_selectors.form).addClass(this.css_classes.is_closed);
				this.element.closest(this.js_selectors.activity_panel).find(this.js_selectors.toggler).removeClass(this.css_classes.is_hidden);
			},
			_EmptyInput: function() {
				CKEDITOR.instances[this.options.text_input_id].setData('');
			},
			_GetInputData: function() {
				return (CKEDITOR.instances[this.options.text_input_id] === undefined) ? '' : CKEDITOR.instances[this.options.text_input_id].getData();
			},
			_UpdateSubmitButtonState: function(){
				const bIsInputEmpty = this._GetInputData() === '';

				this.element.find(this.js_selectors.send_button).prop('disabled', bIsInputEmpty);
			}
		});
});