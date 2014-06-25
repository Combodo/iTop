// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

function ExtKeyWidget(id, sTargetClass, sFilter, sTitle, bSelectMode, oWizHelper, sAttCode, bSearchMode)
{
	this.id = id;
	this.sTargetClass = sTargetClass;
	this.sFilter = sFilter;
	this.sTitle = sTitle;
	this.sAttCode = sAttCode;
	this.emptyHtml = ''; // content to be displayed when the search results are empty (when opening the dialog) 
	this.emptyOnClose = true; // Workaround for the JQuery dialog being very slow when opening and closing if the content contains many INPUT tags
	this.oWizardHelper = oWizHelper;
	this.ajax_request = null;
	this.bSelectMode = bSelectMode; // true if the edited field is a SELECT, false if it's an autocomplete
	this.bSearchMode = bSearchMode; // true if selecting a value in the context of a search form
	this.v_html = '';
	var me = this;
	
	this.Init = function()
	{
		// make sure that the form is clean
		$('#'+this.id+'_btnRemove').attr('disabled','disabled');
		$('#'+this.id+'_linksToRemove').val('');
	};
	
	this.StopPendingRequest = function()
	{
		if (me.ajax_request)
		{
			me.ajax_request.abort();
			me.ajax_request = null;
		}
	};
	
	this.Search = function()
	{
		if($('#'+me.id).attr('disabled')) return; // Disabled, do nothing
		var value = $('#'+me.id).val(); // Current value
		
		// Query the server to get the form to search for target objects
		if (me.bSelectMode)
		{
			me.v_html = $('#v_'+me.id).html();
			$('#v_'+me.id).html('<img src="../images/indicator.gif" />');
		}
		else
		{
			$('#label_'+me.id).addClass('ac_dlg_loading');
		}
		var theMap = { sAttCode: me.sAttCode,
				   iInputId: me.id,
				   sTitle: me.sTitle,
				   sAttCode: me.sAttCode,
				   sTargetClass: me.sTargetClass,
				   bSearchMode: me.bSearchMode,
				   operation: 'objectSearchForm'
				 };
	
		if (me.oWizardHelper == null)
		{
			theMap['json'] = '';
		}
		else
		{
			// Not inside a "search form", updating a real object
			me.oWizardHelper.UpdateWizard();
			theMap['json'] = me.oWizardHelper.ToJSON();
		}

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();
		
		// Run the query and get the result back directly in HTML
		me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap, 
			function(data)
			{
				$('#ac_dlg_'+me.id).html(data);
				$('#ac_dlg_'+me.id).dialog('open');
				me.UpdateSizes();
				me.UpdateButtons();
				me.ajax_request = null;
				me.DoSearchObjects();
			},
			'html'
		);
	};
	
	this.UpdateSizes = function()
	{
		var dlg = $('#ac_dlg_'+me.id);
		// Adjust the dialog's size to fit into the screen
		if (dlg.width() > ($(window).width()-40))
		{
			dlg.width($(window).width()-40);
		}
		if (dlg.height() > ($(window).height()-70))
		{
			dlg.height($(window).height()-70);
		}
		var searchForm = dlg.find('div.display_block:first'); // Top search form, enclosing display_block
		var results = $('#dr_'+me.id);
		var oPadding = {};
		var aKeys = ['top', 'right', 'bottom', 'left'];
		for(k in aKeys)
		{
			oPadding[aKeys[k]] = 0;
			if (dlg.css('padding-'+aKeys[k]))
			{
				oPadding[aKeys[k]] = parseInt(dlg.css('padding-'+aKeys[k]).replace('px', ''));
			}
		}
		width = dlg.innerWidth() - oPadding['right'] - oPadding['left'] - 22; // 5 (margin-left) + 5 (padding-left) + 5 (padding-right) + 5 (margin-right) + 2 for rounding !
		height = dlg.innerHeight() - oPadding['top'] - oPadding['bottom'] -22;
		form_height = searchForm.outerHeight();
		results.height(height - form_height - 40); // Leave some space for the buttons
	};
	
	this.UpdateButtons = function()
	{
		var okBtn = $('#btn_ok_'+me.id);
		if ($('#count_'+me.id).val() > 0)
		{
			okBtn.removeAttr('disabled');
		}
		else
		{
			okBtn.attr('disabled', 'disabled');
		}
	};
	
	this.DoSearchObjects = function(id)
	{
		var theMap = { sTargetClass: me.sTargetClass,
					   iInputId: me.id,
					   sFilter: me.sFilter,
					   bSearchMode: me.bSearchMode
					 };
		
		// Gather the parameters from the search form
		$('#fs_'+me.id+' :input').each( function() {
			if (this.name != '')
			{
				var val = $(this).val(); // supports multiselect as well
				if (val !== null)
				{
					theMap[this.name] = val;					
				}
			}
		});
					
		if (me.oWizardHelper == null)
		{
			theMap['json'] = '';
		}
		else
		{
			// Not inside a "search form", updating a real object
			me.oWizardHelper.UpdateWizard();
			theMap['json'] = me.oWizardHelper.ToJSON();
		}
		
		theMap['sRemoteClass'] = theMap['class'];  // swap 'class' (defined in the form) and 'remoteClass'
		theMap.operation = 'searchObjectsToSelect'; // Override what is defined in the form itself
		theMap.sAttCode = me.sAttCode,
		
		sSearchAreaId = '#dr_'+me.id;
		//$(sSearchAreaId).html('<div style="text-align:center;width:100%;height:24px;vertical-align:middle;"><img src="../images/indicator.gif" /></div>');
		$(sSearchAreaId).block();
		me.UpdateButtons();

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();
		
		// Run the query and display the results
		me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap, 
			function(data)
			{
				$(sSearchAreaId).html(data);
				$(sSearchAreaId+' .listResults').tableHover();
				$('#fr_'+me.id+' input:radio').click(function() { me.UpdateButtons(); });
				me.UpdateButtons();
				me.ajax_request = null;
				$('#count_'+me.id).change(function(){
					me.UpdateButtons();
				});
				me.UpdateSizes();
			},
			'html'
		);

		return false; // Don't submit the form, stay in the current page !
	};
	
	this.DoOk = function()
	{
		var s = $('#'+me.id+'_results').find(':input[name^=storedSelection]');
		var iObjectId = 0;
		if (s.length > 0)
		{
			iObjectId = s.val();
		}
		else
		{
			iObjectId = $('#fr_'+me.id+' input[name=selectObject]:checked').val();
		}
		$('#ac_dlg_'+this.id).dialog('close');
		$('#label_'+this.id).addClass('ac_dlg_loading');

		// Query the server again to get the display name of the selected object
		var theMap = { sTargetClass: me.sTargetClass,
				   iInputId: me.id,
				   iObjectId: iObjectId,
				   sAttCode: me.sAttCode,
				   bSearchMode: me.bSearchMode,
				   operation: 'getObjectName'
				 };
	
		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();
		
		// Run the query and get the result back directly in JSON
		me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap, 
			function(data)
			{
				var oTemp = $('<div id="ac_temp" style="display:none">'+data.name+'</div>'); 
				var txt = oTemp.html(); // this causes HTML entities to be interpreted
				$('#label_'+me.id).val(txt);
				$('#label_'+me.id).removeClass('ac_dlg_loading');
				var prevValue = $('#'+me.id).val();
				$('#'+me.id).val(iObjectId);
				if (prevValue != iObjectId)
				{
					$('#'+me.id).trigger('validate');
					$('#'+me.id).trigger('extkeychange');
					$('#'+me.id).trigger('change');
				}	
				$('#label_'+me.id).focus();
				me.ajax_request = null;
			},
			'json'
		);
		
		return false; // Do NOT submit the form in case we are called by OnSubmit...
	};
	
	// Workaround for a ui.jquery limitation: if the content of
	// the dialog contains many INPUTs, closing and opening the
	// dialog is very slow. So empty it each time.
	this.OnClose = function()
	{
		me.StopPendingRequest();
		// called by the dialog, so in the context 'this' points to the jQueryObject
		if (me.emptyOnClose)
		{
			$('#dr_'+me.id).html(me.emptyHtml);
		}
		$('#label_'+me.id).removeClass('ac_dlg_loading');
		$('#label_'+me.id).focus();
		me.ajax_request = null;
	};
	
	this.CreateObject = function(oWizHelper)
	{
		if($('#'+me.id).attr('disabled')) return; // Disabled, do nothing
		// Query the server to get the form to create a target object
		if (me.bSelectMode)
		{
			me.v_html = $('#v_'+me.id).html();
			$('#v_'+me.id).html('<img src="../images/indicator.gif" />');
		}
		else
		{
			$('#label_'+me.id).addClass('ac_dlg_loading');
		}
		me.oWizardHelper.UpdateWizard();
		var theMap = { sTargetClass: me.sTargetClass,
				   iInputId: me.id,
				   sAttCode: me.sAttCode,
				   'json': me.oWizardHelper.ToJSON(),
				   operation: 'objectCreationForm'
				 };
	
		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();
		
		// Run the query and get the result back directly in HTML
		me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap, 
			function(data)
			{
				$('#ajax_'+me.id).html(data);
				$('#ac_create_'+me.id).dialog('open');
				$('#ac_create_'+me.id).dialog( "option", "close", me.OnCloseCreateObject );			
				// Modify the action of the cancel button
				$('#ac_create_'+me.id+' button.cancel').unbind('click').click( me.CloseCreateObject );
				me.ajax_request = null;
				// Adjust the dialog's size to fit into the screen
				if ($('#ac_create_'+me.id).width() > ($(window).width()-40))
				{
					$('#ac_create_'+me.id).width($(window).width()-40);
				}
				if ($('#ac_create_'+me.id).height() > ($(window).height()-70))
				{
					$('#ac_create_'+me.id).height($(window).height()-70);
				}
			},
			'html'
		);
	};
	
	this.CloseCreateObject = function()
	{
		$('#ac_create_'+me.id).dialog( "close" );
	};
	
	this.OnCloseCreateObject = function()
	{
		if (me.bSelectMode)
		{
			$('#v_'+me.id).html(me.v_html);
		}
		else
		{
			$('#label_'+me.id).removeClass('ac_dlg_loading');
		}
		$('#label_'+me.id).focus();
		$('#ac_create_'+me.id).dialog("destroy");
		$('#ac_create_'+me.id).remove();
		$('#ajax_'+me.id).html('');
	};
	
	this.DoCreateObject = function()
	{
		var sFormId = $('#dcr_'+me.id+' form').attr('id');
		if (CheckFields(sFormId, true))
		{
			$('#'+sFormId).block();
			var theMap = { sTargetClass: me.sTargetClass,
					   iInputId: me.id,
					   sAttCode: me.sAttCode,
					   'json': me.oWizardHelper.ToJSON()
					 };

			// Gather the values from the form
			// Gather the parameters from the search form
			$('#'+sFormId+' :input').each(
				function(i)
				{
					if (this.name != '')
					{
						theMap[this.name] = this.value;
					}
				}
			);
			// Override the 'operation' code
			theMap['operation'] = 'doCreateObject';
			theMap['class'] = me.sClass;

			$('#ac_create_'+me.id).dialog('close');
			
			// Make sure that we cancel any pending request before issuing another
			// since responses may arrive in arbitrary order
			me.StopPendingRequest();
			
			// Run the query and get the result back directly in JSON
			me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap, 
				function(data)
				{
					if (me.bSelectMode)
					{
						// Add the newly created object to the drop-down list and select it
						$('<option/>', { value : data.id }).html(data.name).appendTo('#'+me.id);
						$('#'+me.id+' option[value="'+data.id+'"]').attr('selected', 'selected');
						$('#'+me.id).focus();
					}
					else
					{
						// Put the value corresponding to the newly created object in the autocomplete
						var oTemp = $('<div id="ac_temp" style="display:none">'+data.name+'</div>'); 
						var txt = oTemp.html(); // this causes HTML entities to be interpreted
						$('#label_'+me.id).val(txt);
						$('#'+me.id).val(data.id);
						$('#label_'+me.id).removeClass('ac_dlg_loading');
						$('#label_'+me.id).focus();
					}
					$('#'+me.id).trigger('validate');
					$('#'+me.id).trigger('extkeychange');
					$('#'+me.id).trigger('change');
					me.ajax_request = null;
				},
				'json'
			);
		}
		return false; // do NOT submit the form
	};
	
	this.Update = function()
	{
		if ($('#'+me.id).attr('disabled'))
		{
			$('#v_'+me.id).html('');
			$('#label_'+me.id).attr('disabled', 'disabled');
			$('#label_'+me.id).css({'background': 'transparent'});
			$('#mini_add_'+me.id).hide();
			$('#mini_tree_'+me.id).hide();
			$('#mini_search_'+me.id).hide();
		}
		else
		{
			$('#label_'+me.id).removeAttr('disabled');
			$('#label_'+me.id).css({'background': '#fff url(../images/ac-background.gif) no-repeat right'});
			$('#mini_add_'+me.id).show();
			$('#mini_tree_'+me.id).show();
			$('#mini_search_'+me.id).show();
		}
	};
	
	this.HKDisplay = function()
	{
		var theMap = { sTargetClass: me.sTargetClass,
				   	   sInputId: me.id,
				   	   sFilter: me.sFilter,
				   	   bSearchMode: me.bSearchMode,
				   	   sAttCode: me.sAttCode,
				   	   value: $('#'+me.id).val()
					};
	
		if (me.bSelectMode)
		{
			me.v_html = $('#v_'+me.id).html();
			$('#v_'+me.id).html('<img src="../images/indicator.gif" />');
		}
		else
		{
			$('#label_'+me.id).addClass('ac_dlg_loading');
		}
		if (me.oWizardHelper == null)
		{
			theMap['json'] = '';
		}
		else
		{
			// Not inside a "search form", updating a real object
			me.oWizardHelper.UpdateWizard();
			theMap['json'] = me.oWizardHelper.ToJSON();
		}
		
		theMap['sRemoteClass'] = me.sTargetClass;
		theMap.operation = 'displayHierarchy';
		
		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();
		
		// Run the query and display the results
		me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap, 
			function(data)
			{
				$('#ac_tree_'+me.id).html(data);
				var maxHeight = $(window).height()-110;
				$('#tree_'+me.id).css({maxHeight: maxHeight});
			},
			'html'
		);
	};

	this.OnHKResize = function(event, ui)
	{
		var dh = ui.size.height - ui.originalSize.height;
		if (dh != 0)
		{
			var dlg_content = $('#dlg_tree_'+me.id+' .wizContainer');
			var h = dlg_content.height();
			dlg_content.height(h + dh);
			var tree = $('#tree_'+me.id);
			var h = tree.height();
			tree.height(h + dh - 1);
		}
	};
	
	this.OnHKClose = function()
	{
		if (me.bSelectMode)
		{
			$('#v_'+me.id).html(me.v_html);
		}
		else
		{
			$('#label_'+me.id).removeClass('ac_dlg_loading');
		}
		$('#label_'+me.id).focus();
		$('#dlg_tree_'+me.id).dialog("destroy");
		$('#dlg_tree_'+me.id).remove();
	};

	this.DoHKOk = function()
	{
		iObjectId = $('#tree_'+me.id+' input[name=selectObject]:checked').val();

		$('#dlg_tree_'+me.id).dialog('close');

		// Query the server again to get the display name of the selected object
		var theMap = { sTargetClass: me.sTargetClass,
				   iInputId: me.id,
				   iObjectId: iObjectId,
				   sAttCode: me.sAttCode,
				   bSearchMode: me.bSearchMode,
				   operation: 'getObjectName'
				 };
	
		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();
		
		// Run the query and get the result back directly in JSON
		me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap, 
			function(data)
			{
				var oTemp = $('<div id="ac_temp" style="display:none">'+data.name+'</div>'); 
				var txt = oTemp.html(); // this causes HTML entities to be interpreted
				$('#label_'+me.id).val(txt);
				$('#label_'+me.id).removeClass('ac_dlg_loading');
				var prevValue = $('#'+me.id).val();
				$('#'+me.id).val(iObjectId);
				if (prevValue != iObjectId)
				{
					$('#'+me.id).trigger('validate');
					$('#'+me.id).trigger('extkeychange');
					$('#'+me.id).trigger('change');
				}
				if ( $('#'+me.id).hasClass('multiselect'))
				{
					$('#'+me.id+' option').each(function() { this.selected = ($(this).attr('value') == iObjectId); });
					$('#'+me.id).multiselect('refresh');
				}
				$('#label_'+me.id).focus();
				me.ajax_request = null;
			},
			'json'
		);
		
		return false; // Do NOT submit the form in case we are called by OnSubmit...
	};
	
}