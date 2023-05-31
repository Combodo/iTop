$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "links_view_table" the widget name
	$.widget( "itop.links_view_table",
	{

		// default options
		options:
		{
			link_class: null,
			external_key_to_me: null
		},

		// the constructor
		_create: function () {
			$Table = $('table', this.element);
			this.$tableSettingsDialog = $('#datatable_dlg_' + $Table.attr('id'));
		},

		// the destructor
		_destroy: function () {
		},

		/**
		 * DeleteLinkedObject.
		 *
		 * @param sLinkedObjectKey
		 * @param oTrElement
		 * @constructor
		 */
		DeleteLinkedObject: function (sLinkedObjectKey, oTrElement) {

			const me = this;

			// link object deletion
			iTopLinkSetWorker.DeleteLinkedObject(this.options.link_class, sLinkedObjectKey, function (data) {
				if (data.data.success === true) {
					me.$tableSettingsDialog.DataTableSettings('DoRefresh');
				} else {
					CombodoModal.OpenErrorModal(data.data.error_message);
				}
			});
		},

		/**
		 * DetachLinkedObject.
		 *
		 * @param sLinkedObjectKey
		 * @param oTrElement
		 * @constructor
		 */
		DetachLinkedObject: function (sLinkedObjectKey, oTrElement) {

			const me = this;

			// link object unlink
			iTopLinkSetWorker.DetachLinkedObject(this.options.link_class, sLinkedObjectKey, this.options.external_key_to_me,  function (data) {
				if (data.data.success === true) {
					me.$tableSettingsDialog.DataTableSettings('DoRefresh');
				} else {
					CombodoModal.OpenErrorModal(data.data.error_message);
				}
			});
		},

		/**
		 * CreateLinkedObject.
		 *
		 */
		CreateLinkedObject: function () {

			const me = this;

			// retrieve table
			const $Table = $('table', this.element);

			// retrieve new button
			const $NewButton = $('[name="UI:Links:New"]', this.element);
			const sButtonTooltipContent = $NewButton.attr('data-tooltip-content');
			let sButtonTitleContent = $NewButton.attr('data-modal-title');

			let aParams = {
				form_title: sButtonTitleContent
			}

			// retrieve context parameters
			const sClass = $Table.closest('[data-role="ibo-block-links-table"]').attr('data-link-class');
			const sAttCode = $Table.closest('[data-role="ibo-block-links-table"]').attr('data-link-attcode');
			const sHostObjectClass = $Table.closest('[data-role="ibo-object-details"]').attr('data-object-class');
			const sHostObjectId = $Table.closest('[data-role="ibo-object-details"]').attr('data-object-id');

			// link object creation
			iTopLinkSetWorker.CreateLinkedObject(sButtonTooltipContent, sClass, sAttCode, sHostObjectClass, sHostObjectId, function(){
				$(this).find("form").remove();
				$(this).dialog('destroy');
			},function (event, data) {
				if(data.success){
					me.$tableSettingsDialog.DataTableSettings('DoRefresh');
				}
			},
				aParams);
		},

		/**
		 * ModifyLinkedObject.
		 *
		 * @param {string} sLinkedObjectKey
		 * @param {Element} $TRElement
		 * @param {string} sRemoteFriendlyname
		 */
		ModifyLinkedObject: function (sLinkedObjectKey, $TRElement, sRemoteFriendlyname) {

			const me = this;

			// retrieve modify button and extract modal title
			const $ModifyButton = $('[name="ModifyButton"]', $TRElement);
			const sButtonTooltipContent = $ModifyButton.attr('data-tooltip-content');
			let sButtonTitleContent = $ModifyButton.attr('data-modal-title');
			sButtonTitleContent = sButtonTitleContent.replaceAll('{item}', sRemoteFriendlyname);

			// Specify that external key to host object will be readonly
			let aParams = {
				'readonly': {
				}

			}
			aParams['readonly'][this.options.external_key_to_me] = 1;
			aParams['form_title'] = sButtonTitleContent;

			// link object modification
			iTopObjectWorker.ModifyObject(sButtonTooltipContent, this.options.link_class, sLinkedObjectKey, function () {
				$(this).find("form").remove();
				$(this).dialog('destroy');
			}, function(event, data){
				if(data.success) {
					me.$tableSettingsDialog.DataTableSettings('DoRefresh');
				}
			},
				aParams);
		},

	});
});