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
			this.$table = $('table', this.element);
			this.$tableSettingsDialog = $('#datatable_dlg_' + this.$table.attr('id'));
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
			LinkSetWorker.DeleteLinkedObject(this.options.link_class, sLinkedObjectKey, function (data) {
				if (data.data.success === true) {
					oTrElement.remove();
				} else {
					CombodoModal.OpenInformativeModal(data.data.error_message, 'error');
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
			LinkSetWorker.DetachLinkedObject(this.options.link_class, sLinkedObjectKey, this.options.external_key_to_me,  function (data) {
				if (data.data.success === true) {
					oTrElement.remove();
				} else {
					CombodoModal.OpenInformativeModal(data.data.error_message, 'error');
				}
			});
		},

		/**
		 * CreateLinkedObject.
		 *
		 */
		CreateLinkedObject: function () {

			const me = this;

			// retrieve context parameters
			const sClass = this.$table.closest('[data-role="ibo-block-links-table"]').attr('data-link-class');
			const sAttCode = this.$table.closest('[data-role="ibo-block-links-table"]').attr('data-link-attcode');
			const sHostObjectClass = this.$table.closest('[data-role="ibo-object-details"]').attr('data-object-class');
			const sHostObjectId = this.$table.closest('[data-role="ibo-object-details"]').attr('data-object-id');

			// link object creation
			LinkSetWorker.CreateLinkedObject(sClass, sAttCode, sHostObjectClass, sHostObjectId, function(){
				$(this).find("form").remove();
				$(this).dialog('destroy');
			},function (event, data) {
				if(data.success){
					me.$tableSettingsDialog.DataTableSettings('DoRefresh');
				}
			});
		},

		/**
		 * ModifyLinkedObject.
		 *
		 * @param {string} sLinkedObjectKey
		 */
		ModifyLinkedObject: function (sLinkedObjectKey) {

			const me = this;

			// link object modification
			ObjectWorker.ModifyObject(this.options.link_class, sLinkedObjectKey, function () {
				$(this).find("form").remove();
				$(this).dialog('destroy');
			}, function(event, data){
				if(data.success) {
					me.$tableSettingsDialog.DataTableSettings('DoRefresh');
				}
			});
		},

	});
});