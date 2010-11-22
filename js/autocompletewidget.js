// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

function AutocompleteWidget(id, sClass, sAttCode, sSuffix, oWizHelper)
{
	this.id = id;
	this.sClass = sClass;
	this.sAttCode = sAttCode;
	this.sSuffix = sSuffix;
	this.emptyHtml = ''; // content to be displayed when the search results are empty (when opening the dialog) 
	this.emptyOnClose = true; // Workaround for the JQuery dialog being very slow when opening and closing if the content contains many INPUT tags
	this.oWizardHelper = oWizHelper;
	this.ajax_request = null;
	var me = this;
	
	this.Init = function()
	{
		// make sure that the form is clean
		$('#linkedset_'+this.id+' .selection').each( function() { this.checked = false; });
		$('#'+this.id+'_btnRemove').attr('disabled','disabled');
		$('#'+this.id+'_linksToRemove').val('');
	}
	
	this.StopPendingRequest = function()
	{
		if (this.ajax_request)
		{
			this.ajax_request.Abort();
			this.ajax_request = null;
		}
	}
	
	this.Search = function()
	{
		$('#ac_dlg_'+me.id).dialog('open');
		this.UpdateSizes();
		this.UpdateButtons();
	}
	
	this.UpdateSizes = function()
	{
		var dlg = $('#ac_dlg_'+me.id);
		var searchForm = dlg.find('div.display_block:first'); // Top search form, enclosing display_block
		var results = $('#dr_'+me.id);
		padding_right = parseInt(dlg.css('padding-right').replace('px', ''));
		padding_left = parseInt(dlg.css('padding-left').replace('px', ''));
		padding_top = parseInt(dlg.css('padding-top').replace('px', ''));
		padding_bottom = parseInt(dlg.css('padding-bottom').replace('px', ''));
		width = dlg.innerWidth() - padding_right - padding_left - 22; // 5 (margin-left) + 5 (padding-left) + 5 (padding-right) + 5 (margin-right) + 2 for rounding !
		height = dlg.innerHeight() - padding_top - padding_bottom -22;
		form_height = searchForm.outerHeight();
		results.height(height - form_height - 40); // Leave some space for the buttons
	}
	
	
	this.UpdateButtons = function()
	{
		var okBtn = $('#btn_ok_'+me.id);
		if ($('#fr_'+me.id+' input[name=selectObject]:checked').length > 0)
		{
			okBtn.attr('disabled', '');
		}
		else
		{
			okBtn.attr('disabled', 'disabled');
		}
	}
	
	this.DoSearchObjects = function(id)
	{
		var theMap = { sAttCode: me.sAttCode,
					   iInputId: me.id,
					   sSuffix: me.sSuffix,
					 }
		
		// Gather the parameters from the search form
		$('#fs_'+me.id+' :input').each(
			function(i)
			{
				if (this.name != '')
				{
					theMap[this.name] = this.value;
				}
			}
		);
		
		me.oWizardHelper.UpdateWizard();
		theMap['json'] = me.oWizardHelper.ToJSON();
		
		theMap['sRemoteClass'] = theMap['class'];  // swap 'class' (defined in the form) and 'remoteClass'
		theMap['class'] = me.sClass;
		theMap.operation = 'searchObjectsToSelect'; // Override what is defined in the form itself
		
		sSearchAreaId = '#dr_'+me.id;
		//$(sSearchAreaId).html('<div style="text-align:center;width:100%;height:24px;vertical-align:middle;"><img src="../images/indicator.gif" /></div>');
		$(sSearchAreaId).block();
		me.UpdateButtons();

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		this.StopPendingRequest();
		
		// Run the query and display the results
		this.ajax_request = $.post( 'ajax.render.php', theMap, 
			function(data)
			{
				$(sSearchAreaId).html(data);
				$(sSearchAreaId+' .listResults').tableHover();
				$(sSearchAreaId+' .listResults').tablesorter( { headers: {0: {sorter: false}}, widgets: ['myZebra', 'truncatedList']} ); // sortable and zebra tables
				$('#fr_'+me.id+' input:radio').click(function() { me.UpdateButtons(); });
				me.UpdateButtons();
				me.ajax_request = null;
			},
			'html'
		);

		return false; // Don't submit the form, stay in the current page !
	}
	
	this.DoOk = function()
	{
		var iObjectId = $('#fr_'+me.id+' input[name=selectObject]:checked').val();
		$('#ac_dlg_'+this.id).dialog('close');
		$('#label_'+this.id).addClass('ac_loading');

		// Query the server again to get the display name of the selected object
		var theMap = { sAttCode: me.sAttCode,
				   iInputId: me.id,
				   iObjectId: iObjectId,
				   sSuffix: me.sSuffix,
				   'class': me.sClass,
				   operation: 'getObjectName'
				 }
	
		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		this.StopPendingRequest();
		
		// Run the query and get the result back directly in JSON
		this.ajax_request = $.post( 'ajax.render.php', theMap, 
			function(data)
			{
				$('#label_'+me.id).val(data.name);
				$('#label_'+me.id).removeClass('ac_loading');
				$('#'+me.id).val(iObjectId);
				$('#'+me.id).trigger('validate');
				$('#label_'+me.id).focus();
				me.ajax_request = null;
			},
			'json'
		);
		
		return false; // Do NOT submit the form in case we are called by OnSubmit...
	}
	
	// Workaround for a ui.jquery limitation: if the content of
	// the dialog contains many INPUTs, closing and opening the
	// dialog is very slow. So empty it each time.
	this.OnClose = function()
	{
		// called by the dialog, so in the context 'this' points to the jQueryObject
		if (me.emptyOnClose)
		{
			$('#dr_'+me.id).html(me.emptyHtml);
		}
		$('#label_'+me.id).focus();
	}
	
	this.CreateObject = function(oWizHelper)
	{
		// Query the server to get the form to create a target object
		$('#label_'+me.id).addClass('ac_loading');
		me.oWizardHelper.UpdateWizard();
		var theMap = { sAttCode: me.sAttCode,
				   iInputId: me.id,
				   sSuffix: me.sSuffix,
				   'class': me.sClass,
				   'json': me.oWizardHelper.ToJSON(),
				   operation: 'objectCreationForm'
				 }
	
		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		this.StopPendingRequest();
		
		// Run the query and get the result back directly in JSON
		this.ajax_request = $.post( 'ajax.render.php', theMap, 
			function(data)
			{
				$('#dcr_'+me.id).html(data);
				// Adjust the height of the dialog
				$('#ac_create_'+me.id).dialog('open');
				var h = $('#ac_create_'+me.id+' .wizContainer').outerHeight();
				$('#ac_create_'+me.id).dialog( "option", "height", h+55 ); // space for dialog title and padding...				
				$('#ac_create_'+me.id).dialog( "option", "close", function() { $('#label_'+me.id).removeClass('ac_loading'); $('#label_'+me.id).focus(); } );			
				// Modify the action of the cancel button
				$('#ac_create_'+me.id+' button.cancel').unbind('click').click( me.CloseCreateObject );
				me.ajax_request = null;
			},
			'html'
		);
	}
	
	this.CloseCreateObject = function()
	{
		$('#ac_create_'+me.id).dialog( "close" );
		$('#dcr_'+me.id).html('');
	}
	
	this.DoCreateObject = function()
	{
		var sFormId = $('#dcr_'+me.id+' form').attr('id');
		if (CheckFields(sFormId, true))
		{
			$('#'+sFormId).block();
			var theMap = { sAttCode: me.sAttCode,
					   iInputId: me.id,
					   sSuffix: me.sSuffix,
					   'class': me.sClass,
					   'json': me.oWizardHelper.ToJSON()
					 }

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
			this.StopPendingRequest();
			
			// Run the query and get the result back directly in JSON
			this.ajax_request = $.post( 'ajax.render.php', theMap, 
				function(data)
				{
					$('#label_'+me.id).val(data.name);
					$('#'+me.id).val(data.id);
					$('#'+me.id).trigger('validate');
					$('#label_'+me.id).removeClass('ac_loading');
					$('#label_'+me.id).focus();
					me.ajax_request = null;
				},
				'json'
			);
		}
		return false; // do NOT submit the form
	}
}