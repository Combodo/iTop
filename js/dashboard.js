// jQuery UI style "widget" for editing an iTop "dashboard"

////////////////////////////////////////////////////////////////////////////////
//
// dashboard
//
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "dashboard" the widget name
	$.widget( "itop.dashboard",
	{
		// default options
		// real values must be provided when instanciating the widget : $node.dashboard(...)
		options:
		{
			dashboard_id: '',
			layout_class: '',
			title: '',
			auto_reload: false,
			auto_reload_sec: 300,
			submit_to: 'index.php',
			submit_parameters: {},
			render_to: 'index.php',
			render_parameters: {},
			new_dashlet_parameters: {},
			new_dashletid_endpoint: GetAbsoluteUrlAppRoot() + 'pages/ajax.render.php',
			new_dashletid_parameters: {
				operation: 'new_dashlet_id'
			}
		},
	
		// the constructor
		_create: function()
		{
			var me = this; 

			this.element
				.addClass('itop-dashboard')
				.on('add_dashlet.itop_dashboard', function(event, oParams){
					me.add_dashlet(oParams);
				});

			this.ajax_div = $('<div></div>');
			this.element.after(this.ajax_div);
			this._make_draggable();
			
			// Make sure we don't click on something we'll regret
			$('.itop-dashboard').on('click', 'a', function (e) {
				e.preventDefault();
			});

		},

		// called when created, and later when changing options
		_refresh: function () {
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function () {
			this.element
				.removeClass('itop-dashboard');

			this.ajax_div.remove();
			$(document).off('keyup.dashboard_editor');
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function () {
			// in 1.9 would use _superApply
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function (key, value) {
			// in 1.9 would use _super
			this._superApply(arguments);
		},
		_get_state: function (oMergeInto) {
			var oState = oMergeInto;
			oState.cells = [];
			this.element.find('.layout_cell').each(function () {
				var aList = [];
				$(this).find('.itop-dashlet').each(function () {
					var oDashlet = $(this).data('itopDashlet');
					if (oDashlet) {
						var oDashletParams = oDashlet.get_params();
						var sId = oDashletParams.dashlet_id;
						oState[sId] = oDashletParams;
						aList.push({dashlet_id: sId, dashlet_class: oDashletParams.dashlet_class, dashlet_type: oDashletParams.dashlet_type});
					}
				});

				if (aList.length == 0) {
					oState[0] = {dashlet_id: 0, dashlet_class: 'DashletEmptyCell', dashlet_type: 'DashletEmptyCell'};
					aList.push({dashlet_id: 0, dashlet_class: 'DashletEmptyCell', dashlet_type: 'DashletEmptyCell'});
				}
				oState.cells.push(aList);
			});
			oState.dashboard_id = this.options.dashboard_id;
			oState.layout_class = this.options.layout_class;
			oState.title = this.options.title;
			oState.auto_reload = this.options.auto_reload;
			oState.auto_reload_sec = this.options.auto_reload_sec;

			return oState;
		},
		_make_draggable: function () {
			var me = this;
			this.element.find('.ibo-dashlet').draggable({
				revert: 'invalid', appendTo: 'body', zIndex: 9999, distance: 10,
				helper: function () {
					var oDragItem = $(this).dashlet('get_drag_icon');
					return oDragItem;
				},
				cursorAt: {top: 16, left: 16}
			});
			this.element.find('.ibo-dashboard--grid-column').droppable({
				accept: '.ibo-dashlet,.dashlet_icon',
				drop: function (event, ui) {
					$(this).find(".placeholder").remove();
					var bRefresh = true;
					var oDropped = ui.draggable;
					if (oDropped.hasClass('ibo-dashlet')) {
						// moving around a dashlet
						oDropped.detach();
						oDropped.css({top: 0, left: 0});
						oDropped.appendTo($(this));

						var oDashlet = ui.draggable.data('itopDashlet');
						me.on_dashlet_moved(oDashlet, $(this), bRefresh);
					} else {
						// inserting a new dashlet
						var sDashletClass = ui.draggable.attr('dashlet_class');
						$('.itop-dashboard').trigger('add_dashlet', {dashlet_class: sDashletClass, container: $(this), refresh: bRefresh});
					}
				}
			});
		},
		add_dashlet: function (options) {
			var $container = options.container;
			var aDashletsIds = $container.closest('[data-role="ibo-dashboard--grid"]').find('[data-role="ibo-dashlet"]').map(function () {
				// Note:
				// - At runtime a unique dashlet ID is generated (see \Dashboard::GetDashletUniqueId) to avoid JS widget collisions
				// - At design time, the dashlet ID is not touched (same as in the XML datamodel)
				var sDashletUniqueId = $(this).attr("id");
				var sDashletIdParts = sDashletUniqueId.split('_');
				var sDashletOrigId = sDashletIdParts[sDashletIdParts.length-1];
				return isNaN(parseInt(sDashletOrigId)) ? 0 : parseInt(sDashletOrigId);
			}).get();
			// avoid empty array for IE
			aDashletsIds.push(0);
			// Note: Use of .apply() to be compatible with IE10
			var iHighestDashletOrigId = Math.max.apply(null, aDashletsIds);

			this._get_dashletid_ajax(options, iHighestDashletOrigId + 1);
		},
		// Get the real dashlet ID from the temporary ID
		_get_dashletid_ajax: function (options, sTempDashletId) {
			// Do nothing, meant for overloading
		},
		add_dashlet_prepare: function (options, sFinalDashletId) {
			// 1) Create empty divs for the dashlet and its properties
			//
			var oDashlet = $('<div class="dashlet" id="dashlet_' + sFinalDashletId + '"/>');
			oDashlet.appendTo(options.container);
			var oDashletProperties = $('<div class="dashlet_properties" id="dashlet_properties_' + sFinalDashletId + '"/>');
			oDashletProperties.appendTo($('#dashlet_properties'));

			// 2) Ajax call to fill the divs with default values
			//    => in return, it must call add_dashlet_finalize
			//
			this.add_dashlet_ajax(options, sFinalDashletId);
		},
		add_dashlet_finalize: function(options, sDashletId, sDashletClass)
		{
			$('#dashlet_'+sDashletId)
			.dashlet({dashlet_id: sDashletId, dashlet_class: sDashletClass, dashlet_type: options.dashlet_type})
			.dashlet('deselect_all')
			.dashlet('select')
			.draggable({
				revert: 'invalid', appendTo: 'body', zIndex: 9999, distance: 10,
				helper: function() {
					var oDragItem = $(this).dashlet('get_drag_icon');
					return oDragItem;
				},
				cursorAt: { top: 16, left: 16 }
			});
			if (options.refresh)
			{
				this._refresh();
			}
		},
		on_dashlet_moved: function(oDashlet, oReceiver, bRefresh)
		{
			if (bRefresh)
			{
				// The layout was extended... refresh the whole dashboard
				this._refresh();
			}
		}
	});	
});

////////////////////////////////////////////////////////////////////////////////
//
// runtimedashboard (extends dashboard)
//
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "dashboard" the widget name
	$.widget( "itop.runtimedashboard", $.itop.dashboard,
	{
		// default options
		// real values must be provided when instanciating the widget : $node.runtimedashboard(...)
		options:
		{
			dashboard_id: '',
			layout_class: '',
			title: '',
			auto_reload: '',
			auto_reload_sec: '',
			submit_to: 'index.php',
			submit_parameters: {},
			render_to: 'index.php',
			render_parameters: {},
			new_dashlet_parameters: {}
		},
	
		// the constructor
		_create: function()
		{
			var me = this; 

			this._superApply(arguments);

			this.element
			.addClass('itop-runtimedashboard')
			.on('mark_as_modified.itop-dashboard', function(){me.mark_as_modified();} );

			this.bModified = false;
		},
	
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('itop-runtimedashboard');

			this._superApply(arguments);
		},

		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			this.bRefreshNeeded = false;

			this._superApply(arguments);
			if (this.bRefreshNeeded)
			{
				this._refresh();
			}
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			this._superApply(arguments);
			if ((key != 'auto_reload') && (key != 'auto_reload_sec'))
			{
				this.bRefreshNeeded = true;
			}
		},
		// called when created, and later when changing options
		_refresh: function()
		{
			this._super();

			var oParams = this._get_state(this.options.render_parameters);
			var me = this;
			$.post(this.options.render_to, oParams, function(data){
				me.element.html(data);
				me._make_draggable();
			});
		},
		// Modified means: at least one change has been applied
		mark_as_modified: function () {
			this.bModified = true;
		},
		is_modified: function () {
			return this.bModified;
		},
		// Dirty means: at least one change has not been committed yet
		is_dirty: function () {
			if ($('#dashboard_editor .ui-layout-east .itop-property-field-modified').length > 0) {
				return true;
			} else {
				return false;
			}
		},
		// Force the changes of all the properties being "dirty"
		apply_changes: function () {
			$('#dashboard_editor .ui-layout-east .itop-property-field-modified').trigger('apply_changes');
		},
		save: function (dialog) {
			var oParams = this._get_state(this.options.submit_parameters);
			var me = this;
			$.post(this.options.submit_to, oParams, function (data) {
				me.ajax_div.html(data);
				if (dialog) {
					dialog.dialog("close");
					dialog.remove();
				}
			});
		},
		// We need a unique dashlet id, we will get it using an ajax query
		_get_dashletid_ajax: function (options, sTempDashletId) {
			var me = this;
			var $container = options.container;
			var oParams = this.options.new_dashletid_parameters;
			oParams.dashboardid = me.options.dashboard_id;
			oParams.iRow = $container.closest(".ibo-dashboard--grid-row").data("dashboard-grid-row-index");
			oParams.iCol = $container.data("dashboard-grid-column-index");
			oParams.dashletid = sTempDashletId;

			$.post(this.options.new_dashletid_endpoint, oParams, function (data) {
				me.add_dashlet_prepare(options, data.trim());
			});
		},
		add_dashlet_ajax: function (options, sDashletId) {
			var oParams = this.options.new_dashlet_parameters;
			var sDashletClass = options.dashlet_class;
			oParams.dashlet_class = sDashletClass;
			oParams.dashlet_id = sDashletId;
			oParams.dashlet_type = options.dashlet_type;
			oParams.ajax_promise_id = 'ajax_promise_' + sDashletId;
			var me = this;
			$.post(this.options.render_to, oParams, function (data) {
				me.ajax_div.html(data);
				window[oParams.ajax_promise_id].then(function(){
					me.add_dashlet_finalize(options, sDashletId, sDashletClass);
					me.mark_as_modified();
				});
			});
		},
		on_dashlet_moved: function (oDashlet, oReceiver, bRefresh) {
			this._superApply(arguments);
			this.mark_as_modified();
		}
	});	
});


////////////////////////////////////////////////////////////////////////////////
//
// Helper to upload the file selected in the "import dashboard" dialog
//
function UploadDashboard(oOptions)
{
	var sFileId = 'dashboard_upload_file';

	var oDlg = $('<div id="dashboard_upload_dlg"><form><p>'+oOptions.text+'</p><p><input type="file" id="'+sFileId+'" name="dashboard_upload_file"></p></form></div>');
	$('body').append(oDlg);
	oOptions.file_id = sFileId;
	
	oDlg.dashboard_upload_dlg(oOptions);
}


////////////////////////////////////////////////////////////////////////////////
//
// dashboard_upload_dlg
//
//jQuery UI style "widget" for managing a "import dashboard" dialog (file upload)
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "dashboard-upload-dlg" the widget name
	$.widget( "itop.dashboard_upload_dlg",
	{
		// default options
		options:
		{
			dashboard_id: '',
			file_id: '',
			file: '',
			transaction: '',
			text: 'Select a dashboard file to import',
			title: 'Dahsboard Import',
			close_btn: 'Close',
			submit_to: GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?operation=import_dashboard'
		},
	
		// the constructor
		_create: function()
		{
			var me = this; 

			var oButtons = {};
			oButtons[this.options.close_btn] = function() {
				me.element.dialog('close');
				//me.onClose();
			};
			$('#'+this.options.file_id).fileupload({
				url: me.options.submit_to+'&id='+me.options.dashboard_id+'&file='+me.options.file+'&transaction_id='+me.options.transaction,
		        dataType: 'json',
				pasteZone: null, // Don't accept files via Chrome's copy/paste
		        done: function (e, data) {
					if(typeof(data.result.error) !== 'undefined')
					{
						if(data.result.error !== '')
						{
							CombodoModal.OpenErrorModal(data.result.error);
							me.element.dialog('close');
						}
						else
						{
							me.element.dialog('close');
							location.reload();
						}
					}
		        },
		        start: function() {
		        	$('#'+me.options.file_id).prop('disabled', true);
		        }
		    });
		    
			this.element
			.addClass('itop-dashboard_upload_dlg')
			.dialog({
				modal: true,
				width: 500,
				height: 'auto',
				title: this.options.title,
				close: function() { me._onClose(); },
				buttons: oButtons
			});
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('itop-dashboard_upload_dlg');		
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			this._superApply(arguments);
			this._refresh();
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			// in 1.9 would use _super
			this._superApply(arguments);
		},
		_onClose: function()
		{
			this.element.remove();
		}
	});
});
