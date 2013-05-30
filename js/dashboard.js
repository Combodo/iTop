// jQuery UI style "widget" for editing an iTop "dashboard"
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
			.bind('mark_as_modified.itop-dashboard', function(){me.mark_as_modified();} );

			this.ajax_div = $('<div></div>').appendTo(this.element);
			this._make_draggable();
			this.bModified = false;
			
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
			var oParams = this._get_state(this.options.render_parameters);
			var me = this;
			$.post(this.options.render_to, oParams, function(data){
				me.element.html(data);
				me._make_draggable();
			});
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
			this._refresh();
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
			
			return oState;
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
		add_dashlet: function(options)
		{
			var sDashletId = this._get_new_id();
			var oDashlet = $('<div class="dashlet" id="dashlet_'+sDashletId+'"/>');
			oDashlet.appendTo(options.container);
			var oDashletProperties = $('<div class="dashlet_properties" id="dashlet_properties_'+sDashletId+'"/>');
			oDashletProperties.appendTo($('#dashlet_properties'));
			
			var oParams = this.options.new_dashlet_parameters;
			var sDashletClass = options.dashlet_class;
			oParams.dashlet_class = sDashletClass;
			oParams.dashlet_id = sDashletId;
			var me = this;
			$.post(this.options.render_to, oParams, function(data){
				me.ajax_div.html(data);
				$('#dashlet_'+sDashletId)
				.dashlet({dashlet_id: sDashletId, dashlet_class: sDashletClass})
				.dashlet('deselect_all')
				.dashlet('select')
				.draggable({
					revert: 'invalid', appendTo: 'body', zIndex: 9999,
					helper: function() {
						var oDragItem = $(this).dashlet('get_drag_icon');
						return oDragItem;
					},
					cursorAt: { top: 16, left: 16 }
				});
				if (options.refresh)
				{
					me._refresh();
				}
			});
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
				revert: 'invalid', appendTo: 'body', zIndex: 9999,
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
					var oDashlet = ui.draggable;
					if (oDashlet.hasClass('dashlet'))
					{
						// moving around a dashlet
						oDashlet.detach();
						oDashlet.css({top: 0, left: 0});
						oDashlet.appendTo($(this));
						if( bRefresh )
						{
							// The layout was extended... refresh the whole dashboard
							me._refresh();
						}
					}
					else
					{
						// inserting a new dashlet
						var sDashletClass = ui.draggable.attr('dashlet_class');
						$(':itop-dashboard').dashboard('add_dashlet', {dashlet_class: sDashletClass, container: $(this), refresh: bRefresh });
					}
				}
			});	
		}
	});	
});

function UploadDashboard(oOptions)
{
	var sFileId = 'dashboard_upload_file';
	var oDlg = $('<div id="dashboard_upload_dlg"><form><p>'+oOptions.text+'</p><p><input type="file" id="'+sFileId+'" name="dashboard_upload_file"></p></form></div>');
	$('body').append(oDlg);
	oOptions.file_id = sFileId;
	
	oDlg.dashboard_upload_dlg(oOptions);
}


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
			$('#'+this.options.file_id).bind('change', function() { me._doUpload(); } );
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
		},
		_doUpload: function()
		{
			var me = this;
			$.ajaxFileUpload
			(
				{
					url: me.options.submit_to+'&id='+me.options.dashboard_id, 
					secureuri:false,
					fileElementId: me.options.file_id,
					dataType: 'json',
					success: function (data, status)
					{
						if(typeof(data.error) != 'undefined')
						{
							if(data.error != '')
							{
								alert(data.error);
								me.element.dialog('close');
							}
							else
							{
								me.element.dialog('close');
								location.reload();
							}
						}
					},
					error: function (data, status, e)
					{
						alert(e);
						me.element.dialog('close');
					}
				}
			);			
		}
	});
});
