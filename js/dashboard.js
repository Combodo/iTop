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
			new_dashlet_parameters: {}
		},
	
		// the constructor
		_create: function()
		{
			var me = this; 

			this.element
			.addClass('itop-dashboard')
			.bind('add_dashlet.itop_dashboard', function(event, oParams){
				me.add_dashlet(oParams);
			});

			this.ajax_div = $('<div></div>');
			this.element.after(this.ajax_div);
			this._make_draggable();
			
			// Make sure we don't click on something we'll regret
			$('.itop-dashboard').on('click', 'a', function(e) { e.preventDefault(); });

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
			.removeClass('itop-dashboard');

			this.ajax_div.remove();
			$(document).unbind('keyup.dashboard_editor');			
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			// in 1.9 would use _super
			this._superApply(arguments);
		},
		_get_state: function(oMergeInto)
		{
			var oState = oMergeInto;
			oState.cells = [];
			this.element.find('.layout_cell').each(function() {
				var aList = [];
				$(this).find(':itop-dashlet').each(function() {
					var oDashlet = $(this).data('itopDashlet');
					if(oDashlet)
					{
						var oDashletParams = oDashlet.get_params();
						var sId = oDashletParams.dashlet_id;
						oState[sId] = oDashletParams;				
						aList.push({dashlet_id: sId, dashlet_class: oDashletParams.dashlet_class} );
					}
				});
				
				if (aList.length == 0)
				{
					oState[0] = {dashlet_id: 0, dashlet_class: 'DashletEmptyCell'};
					aList.push({dashlet_id: 0, dashlet_class: 'DashletEmptyCell'});
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
		_get_new_id: function()
		{
			var iMaxId = 0;
			this.element.find(':itop-dashlet').each(function() {
				var oDashlet = $(this).data('itopDashlet');
				if(oDashlet)
				{
					var oDashletParams = oDashlet.get_params();
					var id = parseInt(oDashletParams.dashlet_id, 10);
					if (id > iMaxId) iMaxId = id;
				}
			});
			return 1 + iMaxId;			
		},
		_make_draggable: function()
		{
			var me = this;
			this.element.find('.dashlet').draggable({
				revert: 'invalid', appendTo: 'body', zIndex: 9999, distance: 10,
				helper: function() {
					var oDragItem = $(this).dashlet('get_drag_icon');
					return oDragItem;
				},
				cursorAt: { top: 16, left: 16 }
			});
			this.element.find('table td').droppable({
				accept: '.dashlet,.dashlet_icon',
				drop: function(event, ui) {
					$( this ).find( ".placeholder" ).remove();
					var bRefresh = $(this).hasClass('layout_extension');
					var oDropped = ui.draggable;
					if (oDropped.hasClass('dashlet'))
					{
						// moving around a dashlet
						oDropped.detach();
						oDropped.css({top: 0, left: 0});
						oDropped.appendTo($(this));

						var oDashlet = ui.draggable.data('itopDashlet');
						me.on_dashlet_moved(oDashlet, $(this), bRefresh);
					}
					else
					{
						// inserting a new dashlet
						var sDashletClass = ui.draggable.attr('dashlet_class');
						$('.itop-dashboard').trigger('add_dashlet', {dashlet_class: sDashletClass, container: $(this), refresh: bRefresh });
					}
				}
			});	
		},
		add_dashlet: function(options)
		{
			// 1) Create empty divs for the dashlet and its properties
			//
			var sDashletId = this._get_new_id();
			var oDashlet = $('<div class="dashlet" id="dashlet_'+sDashletId+'"/>');
			oDashlet.appendTo(options.container);
			var oDashletProperties = $('<div class="dashlet_properties" id="dashlet_properties_'+sDashletId+'"/>');
			oDashletProperties.appendTo($('#dashlet_properties'));

			// 2) Ajax call to fill the divs with default values
			//    => in return, it must call add_dashlet_finalize
			//
			this.add_dashlet_ajax(options, sDashletId);
		},
		add_dashlet_finalize: function(options, sDashletId, sDashletClass)
		{
			$('#dashlet_'+sDashletId)
			.dashlet({dashlet_id: sDashletId, dashlet_class: sDashletClass})
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
			.bind('mark_as_modified.itop-dashboard', function(){me.mark_as_modified();} );

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
		mark_as_modified: function()
		{
			this.bModified = true;
		},
		is_modified: function()
		{
			return this.bModified;
		},
		// Dirty means: at least one change has not been committed yet
		is_dirty: function()
		{
			if ($('#dashboard_editor .ui-layout-east .itop-property-field-modified').size() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		},
		// Force the changes of all the properties being "dirty"
		apply_changes: function()
		{
			$('#dashboard_editor .ui-layout-east .itop-property-field-modified').trigger('apply_changes');
		},
		save: function()
		{
			var oParams = this._get_state(this.options.submit_parameters);
			var me = this;
			$.post(this.options.submit_to, oParams, function(data){
				me.ajax_div.html(data);
			});
		},
		add_dashlet_ajax: function(options, sDashletId)
		{
			var oParams = this.options.new_dashlet_parameters;
			var sDashletClass = options.dashlet_class;
			oParams.dashlet_class = sDashletClass;
			oParams.dashlet_id = sDashletId;
			var me = this;
			$.post(this.options.render_to, oParams, function(data){
				me.ajax_div.html(data);
				me.add_dashlet_finalize(options, sDashletId, sDashletClass);
				me.mark_as_modified();
			});
		},
		on_dashlet_moved: function(oDashlet, oReceiver, bRefresh)
		{
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
				url: me.options.submit_to+'&id='+me.options.dashboard_id,
		        dataType: 'json',
				pasteZone: null, // Don't accept files via Chrome's copy/paste
		        done: function (e, data) {
					if(typeof(data.result.error) != 'undefined')
					{
						if(data.result.error != '')
						{
							alert(data.result.error);
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
