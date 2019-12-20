/*
 * Copyright (C) 2013-2019 Combodo SARL
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

//iTop Portal Form handler
//This extends itop.form_handler
;
$(function()
{
	// the widget definition, where 'itop.portal' is the namespace,
	// 'form_handler' the widget name
	$.widget( 'itop.portal_form_handler', $.itop.form_handler,
	{
		options: {
			base_url: null,     // Base URL of the application
			submit_url: null,   // Deprecated. We kept those properties to preserve compatibility with extensions
			cancel_url: null,   // but you should start using xxx_rule.url as soon as possible.
			submit_rule: {
				category: 'redirect',
				url: null,
				modal: false,
			},
			cancel_rule: {
				category: 'close',
				url: null,
				modal: false,
			},
		},
		
		// the constructor
		_create: function()
		{
			this.element.addClass('portal_form_handler');
	
			// Safe check for options
			if(this.options.submit_rule.url === '')
				this.options.submit_rule.url = null;
			if(this.options.cancel_rule.url === '')
				this.options.cancel_rule.url = null;
			// Deprecated, see this.options.submit_url
			if((this.options.submit_url !== null) && (this.options.submit_url !== ''))
				this.options.submit_rule.url = this.options.submit_url;
			if((this.options.cancel_url !== null) && (this.options.cancel_url !== ''))
				this.options.cancel_rule.url = this.options.cancel_url;
			
			this._super();
		},
   
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('portal_form_handler');
		},
		// _setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		_setOptions: function()
		{
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			this._super( key, value );
		},
		// - Callback when some fields have been touched
		_onFieldsTouched: function(oEvent)
		{
			this._super(oEvent);
			$('body').trigger('register_blocker.portal.itop', {'sBlockerId': this.element.attr('id'), 'sTargetElemSelector': '#' + this.element.closest('.modal').attr('id'), 'oTargetElemSelector': '#' + this.element.closest('.modal').attr('id'), 'sEventName': 'hide.bs.modal'});
			$('body').trigger('register_blocker.portal.itop', {'sBlockerId': this.element.attr('id'), 'sTargetElemSelector': 'document', 'oTargetElemSelector': document, 'sEventName': 'beforeunload'});
		},
		// Overload from parent class
		_onSubmitClick: function(oEvent)
		{
			oEvent.preventDefault();
			var me = this;

			// EasterEgg : Vibrate on submit
			if(window.navigator.vibrate)
			{
				window.navigator.vibrate(200);
			}

			// Validating fields prior to post (Client side)
			var bIsValid = me.options.field_set.triggerHandler('validate');
			// Retrieving stimulus name
			var sStimulusCode = null;
			if($(oEvent.currentTarget).attr('name') === 'stimulus_code')
			{
				sStimulusCode = $(oEvent.currentTarget).val();
			}

			// Submit form
			if(bIsValid)
			{
				me._disableFormBeforeLoading();
				$.post(
					me.options.endpoint,
					{
						operation: 'submit',
						stimulus_code: sStimulusCode,
						transaction_id: me.options.formmanager_data.transaction_id,
						formmanager_class: me.options.formmanager_class,
						formmanager_data: JSON.stringify(me.options.formmanager_data),
						current_values: me.getCurrentValues(),
						attachment_ids: me.getAttachmentIds()
					},
					function(oData){
						if(oData.form.validation !== undefined)
						{   
							var oValidation = oData.form.validation;
							
							// First we build the form
							me.options.field_set.field_set('option', 'fields_list', oData.form.fieldset.fields_list);
							me.options.field_set.field_set('option', 'is_valid', oValidation.valid);
							me.options.field_set.field_set('buildForm');

							// Then only we display messages from the server, otherwise they will be cleared by the HTML print
							var oMessages = oValidation.messages;

							// Cleaning help blocks
							me.element.find('.form_alerts').removeClass('has-success has-warning has-error');
							me.element.find('.form_alerts .alert').html('').hide();
							me.element.find('.form_field').removeClass('has-success has-warning has-error');
							me.element.find('.form_field .help-block').html('');

							// For each type of messages (error, warning, success)...
							for(var sMessageType in oMessages)
							{
								var sMessageClass = 'has-' + sMessageType;  
								// ... for each concerned fields ...
								for(var sFieldId in oMessages[sMessageType])
								{
									var oField = me.options.field_set.field_set('getField', sFieldId);
									var oHelpBlock = null;

									// Checking if the messages are for a field or for the whole form
									if(oField.length === 1)
									{
										oField.addClass(sMessageClass);
										oHelpBlock = oField.find('.help-block');
									}
									else
									{
										oHelpBlock = me.element.find('.form_alerts .alert.alert-' + sMessageType);
										oHelpBlock.show();
									}
									// ... add the message to its help block
									for(var i in oMessages[sMessageType][sFieldId])
									{
										oHelpBlock.append($('<p>' + oMessages[sMessageType][sFieldId][i] + '</p>'));
									}
								}
							}

							// Scrolling to top so the user can see messages
							$('body').scrollTop(0);
						
							// If everything is okay, we close the form and reload it.
							if(oValidation.valid)
							{
								var bRedirectInModal = me.options.submit_rule.modal;
								var sRedirectUrl = me.options.submit_rule.url;

								$('body').trigger('unregister_blocker.portal.itop', {'sBlockerId': me.element.attr('id')});

								// Checking if we have to redirect to another page
								// Typically this happens when applying a stimulus, we redirect to the transition form
								if(oValidation.redirection !== undefined)
								{
									var oRedirection = oValidation.redirection;
									if(oRedirection.modal !== undefined)
									{
										bRedirectInModal = oRedirection.modal;
									}
									if(oRedirection.url !== undefined)
									{
										sRedirectUrl = oRedirection.url;
									}
									me._applyRedirectRule(sRedirectUrl, bRedirectInModal);
								}
								else if(me.options.submit_rule.category === 'redirect')
								{
									me._applyRedirectRule(sRedirectUrl, bRedirectInModal);
								}
								// Close rule only needs to be applied to non modal forms (modal is always closed on submit)
								else if(me.options.submit_rule.category === 'close')
								{
									me._applyCloseRule();
								}
							}
						}
					}
				)
				.fail(function(oData){
					me._onUpdateFailure(oData);
				})
				.always(function(){
					me._enableFormAfterLoading();
				});
			}
			// Else go to the first invalid field
			else
			{
				// EasterEgg : Vibrate on submit
				if(window.navigator.vibrate)
				{
					window.navigator.vibrate([200, 100, 200]);
				}
				this.element.find('.has-error')[0].scrollIntoView();
			}
		},
		// Overload from parent class
		_onCancelClick: function(oEvent)
		{
			oEvent.preventDefault();
			oEvent.stopPropagation();
			
			var me = this;

			// When fields have been modified, we have to ask them to cancel stuff if necessary
			if(me.options.field_set.field_set('option', 'touched_fields').length > 0)
			{
				me._disableFormBeforeLoading();
				$('body').trigger('unregister_blocker.portal.itop', {'sBlockerId': me.element.attr('id')});
				$.post(
					me.options.endpoint,
					{
						operation: 'cancel',
						formmanager_class: me.options.formmanager_class,
						formmanager_data: JSON.stringify(me.options.formmanager_data),
						current_values: me.getCurrentValues()
					},
					function(oData)
					{
						if(me.options.cancel_rule.category === 'redirect')
						{
							me._applyRedirectRule(me.options.cancel_rule.url, me.options.cancel_rule.modal);
						}
						else if(me.options.cancel_rule.category === 'close')
						{
							me._applyCloseRule();
						}
					}
				)
				.always(function()
				{
					me._enableFormAfterLoading();
				});
			}
			// Otherwise we can close the modal immediately
			else
			{
				if(me.options.cancel_rule.category === 'redirect')
				{
					me._applyRedirectRule(me.options.cancel_rule.url, me.options.cancel_rule.modal);
				}
				else if(me.options.cancel_rule.category === 'close')
				{
					me._applyCloseRule();
				}
			}
		},
		// Overload from parent class
		_onUpdateFailure: function(oData)
		{
			if(oData.responseJSON !== undefined && oData.responseJSON !== null)
			{
				var oResponse = oData.responseJSON;
				// If we encounter an error
				if(oResponse.exception !== undefined)
				{
					// Note : This could be refactored for a global use
					var oModalElem = $('#modal-for-alert');
					oModalElem.find('.modal-title').html(oResponse.error_title);
					oModalElem.find('.modal-body .alert').html(oResponse.error_message)
							.removeClass('alert-success alert-info alert-warning alert-danger')
							.addClass('alert-danger');
					oModalElem.modal('show');
				}
			}
		},
		// Overload from parent class
		_onUpdateAlways: function(oData, sFormPath)
		{
			// Check all touched AFTER ajax is complete, otherwise the renderer will redraw the field in the mean time.
			this.element.find('.form_fields').trigger('validate', {touched_fields_only: true});
			this._enableFormAfterLoading();
		},
		// Place a field for which no container exists
		_addField: function(sFieldId)
		{
			$('<div ' + this.options.field_identifier_attr + '="'+sFieldId+'"></div>').appendTo(this.element.find('.form_fields'));
		},
		_disableFormBeforeLoading: function()
		{
			$('#page_overlay').fadeIn(200);
		},
		_enableFormAfterLoading: function()
		{
			$('#page_overlay').fadeOut(200);
		},
		_applyRedirectRule: function(sRedirectUrl, bRedirectInModal)
		{
			var me = this;

			// Always close current modal
			if(this.options.is_modal)
			{
				this.element.closest('.modal').modal('hide');
			}

			if(sRedirectUrl !== null)
			{
				if(bRedirectInModal === true)
				{
					// Creating a new modal
					CombodoPortalToolbox.OpenModal({
						content: {
							endpoint: sRedirectUrl,
							data: {
								// Passing form manager data to the next page, just in case it needs it (eg. when applying stimulus)
								formmanager_class: this.options.formmanager_class,
								formmanager_data: JSON.stringify(this.options.formmanager_data)
							},
						},
					});
				}
				else
				{
					// Showing loader while redirecting, otherwise user tend to click somewhere in the page.
					// Note: We use a timeout because .always() is called right after here and will hide the loader
					setTimeout(function(){ me._disableFormBeforeLoading(); }, 50);
					// Redirecting after a few ms so the user can see what happend
					setTimeout(function() { location.href = sRedirectUrl; }, 400);
				}
			}
		},
		_applyCloseRule: function()
		{
			if(this.options.is_modal)
			{
				this.element.closest('.modal').modal('hide');
			}
			else
			{
				// Try to close the window
				window.close();

				// In some browser (eg. Firefox 70), window won't close if it has NOT been open by JS. In that case, we try to redirect to homepage as a fallback.
				var sHomepageUrl = (this.options.base_url !== null) ? this.options.base_url : $('#sidebar .menu .brick_menu_item:first a').attr('href')
				window.location.href = sHomepageUrl;
			}
		},
		submit: function(oEvent)
		{
			this._onSubmitClick(oEvent);
		},
		getOptions: function()
		{
			return this.options;
		},
		getAttachmentIds: function()
		{
			var me = this;
			var aResult = {actual_attachments_ids: [], removed_attachments_ids: []};
			
			// Actual attachments
			this.element.find('.attachments_container :input[name="attachments[]"]').each(function(iIndex, oElement){
				aResult.actual_attachments_ids.push($(oElement).val());
			});
			// Removed attachments
			this.element.find('.attachments_container :input[name="removed_attachments[]"]').each(function(iIndex, oElement){
				aResult.removed_attachments_ids.push($(oElement).val());
			});
			
			return aResult;
		}
	});
});
