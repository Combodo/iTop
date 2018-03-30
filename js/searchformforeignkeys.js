// Copyright (C) 2010-2018 Combodo SARL
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

/**
 *
 * @param id String the dom identifier of the source input
 * @param sTargetClass
 * @param sAttCode
 * @param sFilter
 * @param sTitle
 * @constructor
 */
function SearchFormForeignKeys(id, sTargetClass, sAttCode, sFilter, sTitle)
{
	this.id = id;
	this.sOriginalTargetClass = sTargetClass;
	this.sTargetClass = sTargetClass;
	this.sFilter = sFilter;
	this.sTitle = sTitle;
	this.sAttCode = sAttCode;
	this.emptyHtml = ''; // content to be displayed when the search results are empty (when opening the dialog)
	this.emptyOnClose = true; // Workaround for the JQuery dialog being very slow when opening and closing if the content contains many INPUT tags
	this.ajax_request = null;
	// this.bSelectMode = bSelectMode; // true if the edited field is a SELECT, false if it's an autocomplete
	// this.bSearchMode = bSearchMode; // true if selecting a value in the context of a search form
	var me = this;

	this.Init = function()
	{
		// make sure that the form is clean
		$('#linkedset_'+this.id+' .selection').each( function() { this.checked = false; });
		$('#'+this.id+'_btnRemove').prop('disabled', false);


		var dialog = $('<div id="dlg_'+me.id+'"></div>').appendTo(document.body);

		// me.trace(dialog);

		//TODO : check and remove all unneded code bellow this line!!

		$('#'+this.id+'_linksToRemove').val('');

		$('#linkedset_'+me.id).on('remove', function() {
			// prevent having the dlg div twice
			$('#dlg_'+me.id).remove();
		});

		// $('#linkedset_'+me.id+' :input').off('change').on('change', function() {
		// 	if (!($(this).hasClass('selection')) && !($(this).hasClass('select_all'))) {
		// 		var oCheckbox = $(this).closest('tr').find('.selection');
		// 		var iLink = oCheckbox.attr('data-link-id');
		// 		var iUniqueId = oCheckbox.attr('data-unique-id');
		// 		var sAttCode = $(this).closest('.attribute-edit').attr('data-attcode');
		// 		var value = $(this).val();
		// 		return me.OnValueChange(iLink, iUniqueId, sAttCode, value);
		// 	}
		// 	return true;
		// });

		$('#'+this.iInputId).closest('form').submit(function() {
			return me.OnFormSubmit();
		});
	};

	this.StopPendingRequest = function()
	{
		if (me.ajax_request)
		{
			me.ajax_request.abort();
			me.ajax_request = null;
		}
	};

	this.ShowModalSearchForeignKeys = function()
	{
		// // Query the server to get the form to search for target objects
		// if (me.bSelectMode)
		// {
		// 	$('#fstatus_'+me.id).html('<img src="../images/indicator.gif" />');
		// }
		// else
		// {
		// 	$('#label_'+me.id).addClass('dlg_loading');
		// }
		$('#label_'+me.id).addClass('dlg_loading');
		var theMap = {
			sAttCode: me.sAttCode,
			iInputId: me.id,
			sTitle: me.sTitle,
			sAttCode: me.sAttCode,
			sTargetClass: me.sTargetClass,
			// bSearchMode: me.bSearchMode,
			operation: 'ShowModalSearchForeignKeys'
		};



		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();

		// Run the query and get the result back directly in HTML
		me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
			function(data)
			{
				// $('#dlg_'+me.id).html(data);
				$('#dlg_'+me.id).empty().append($(data)); // $(data).filter(':not(script)'));
				$('#dlg_'+me.id).dialog('open');
				me.UpdateSizes();
				me.UpdateButtons();
				me.ajax_request = null;
				FixSearchFormsDisposition();
				me.ListResultsSearchForeignKeys();
			},
			'html'
		);
	};

	this.UpdateSizes = function()
	{
		var dlg = $('#dlg_'+me.id);
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
			okBtn.prop('disabled', 'disabled');
		}
	};

	this.ListResultsSearchForeignKeys = function(id)
	{
		var theMap = {
			sTargetClass: me.sTargetClass,
			iInputId: me.id,
			sFilter: me.sFilter
			// bSearchMode: me.bSearchMode
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



		theMap['sRemoteClass'] = theMap['class'];  // swap 'class' (defined in the form) and 'remoteClass'
		theMap.operation = 'ListResultsSearchForeignKeys'; // Override what is defined in the form itself
		theMap.sAttCode = me.sAttCode;
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
		$('#dlg_'+this.id).dialog('close');
		$('#label_'+this.id).addClass('dlg_loading');

		// Query the server again to get the display name of the selected object
		var theMap = { sTargetClass: me.sTargetClass,
			iInputId: me.id,
			iObjectId: iObjectId,
			sAttCode: me.sAttCode,
			// bSearchMode: me.bSearchMode,
			operation: 'getObjectName'
		};

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();

		// Run the query and get the result back directly in JSON
		me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
			function(data)
			{
				var oTemp = $('<div>'+data.name+'</div>');
				var txt = oTemp.text(); // this causes HTML entities to be interpreted
				$('#label_'+me.id).val(txt);
				$('#label_'+me.id).removeClass('dlg_loading');
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
		$('#label_'+me.id).removeClass('dlg_loading');
		$('#label_'+me.id).focus();
		me.ajax_request = null;
	};



	this.DoSelectObjectClass = function()
	{
		// Retrieving selected value
		var oSelectedClass = $('#ac_create_'+me.id+' select');
		if(oSelectedClass.length !== 1) return;

		// Setting new target class
		me.sTargetClass = oSelectedClass.val();

		// Opening real creation form
		$('#ac_create_'+me.id).dialog('close');
		me.CreateObject();
	};




	this.Update = function()
	{
		if ($('#'+me.id).prop('disabled'))
		{
			$('#v_'+me.id).html('');
			$('#label_'+me.id).prop('disabled', 'disabled');
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



	this.OnFormSubmit = function()
	{
		var oDiv = $('#linkedset_'+me.id);

		var aToBeCreated = [];
		me.aAdded.forEach(function(oAdded){
			if (oAdded != null)
			{
				aToBeCreated.push(oAdded);
			}
		});
		var sToBeCreated = JSON.stringify(aToBeCreated);
		$('<input type="hidden" name="attr_'+me.sAttCode+'_tbc">').val(sToBeCreated).appendTo(oDiv);
	};
	// this.HKDisplay = function()
	// {
	// 	var theMap = { sTargetClass: me.sTargetClass,
	// 		sInputId: me.id,
	// 		sFilter: me.sFilter,
	//// 		bSearchMode: me.bSearchMode,
	// 		sAttCode: me.sAttCode,
	// 		value: $('#'+me.id).val()
	// 	};
	//
	//// 	if (me.bSelectMode)
	////	{
	//// 		$('#fstatus_'+me.id).html('<img src="../images/indicator.gif" />');
	//// 	}
	//// 	else
	//// 	{
	//// 		$('#label_'+me.id).addClass('dlg_loading');
	//// 	}
	//	$('#label_'+me.id).addClass('dlg_loading');
	//
	// 	theMap['sRemoteClass'] = me.sTargetClass;
	// 	theMap.operation = 'displayHierarchy';
	//
	// 	// Make sure that we cancel any pending request before issuing another
	// 	// since responses may arrive in arbitrary order
	// 	me.StopPendingRequest();
	//
	// 	// Run the query and display the results
	// 	me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
	// 		function(data)
	// 		{
	// 			$('#ac_tree_'+me.id).html(data);
	// 			var maxHeight = $(window).height()-110;
	// 			$('#tree_'+me.id).css({maxHeight: maxHeight});
	// 		},
	// 		'html'
	// 	);
	// };
	//
	// this.OnHKResize = function(event, ui)
	// {
	// 	var dh = ui.size.height - ui.originalSize.height;
	// 	if (dh != 0)
	// 	{
	// 		var dlg_content = $('#dlg_tree_'+me.id+' .wizContainer');
	// 		var h = dlg_content.height();
	// 		dlg_content.height(h + dh);
	// 		var tree = $('#tree_'+me.id);
	// 		var h = tree.height();
	// 		tree.height(h + dh - 1);
	// 	}
	// };
	//
	// this.OnHKClose = function()
	// {
	// 	if (me.bSelectMode)
	// 	{
	// 		$('#fstatus_'+me.id).html('');
	// 	}
	// 	else
	// 	{
	// 		$('#label_'+me.id).removeClass('dlg_loading');
	// 	}
	// 	$('#label_'+me.id).focus();
	// 	$('#dlg_tree_'+me.id).dialog("destroy");
	// 	$('#dlg_tree_'+me.id).remove();
	// };
	//
	// this.DoHKOk = function()
	// {
	// 	iObjectId = $('#tree_'+me.id+' input[name=selectObject]:checked').val();
	//
	// 	$('#dlg_tree_'+me.id).dialog('close');
	//
	// 	// Query the server again to get the display name of the selected object
	// 	var theMap = { sTargetClass: me.sTargetClass,
	// 		iInputId: me.id,
	// 		iObjectId: iObjectId,
	// 		sAttCode: me.sAttCode,
	//// 		bSearchMode: me.bSearchMode,
	// 		operation: 'getObjectName'
	// 	};
	//
	// 	// Make sure that we cancel any pending request before issuing another
	// 	// since responses may arrive in arbitrary order
	// 	me.StopPendingRequest();
	//
	// 	// Run the query and get the result back directly in JSON
	// 	me.ajax_request = $.post( AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
	// 		function(data)
	// 		{
	// 			var oTemp = $('<div>'+data.name+'</div>');
	// 			var txt = oTemp.text(); // this causes HTML entities to be interpreted
	// 			$('#label_'+me.id).val(txt);
	// 			$('#label_'+me.id).removeClass('dlg_loading');
	// 			var prevValue = $('#'+me.id).val();
	// 			$('#'+me.id).val(iObjectId);
	// 			if (prevValue != iObjectId)
	// 			{
	// 				$('#'+me.id).trigger('validate');
	// 				$('#'+me.id).trigger('extkeychange');
	// 				$('#'+me.id).trigger('change');
	// 			}
	// 			if ( $('#'+me.id).hasClass('multiselect'))
	// 			{
	// 				$('#'+me.id+' option').each(function() { this.selected = ($(this).attr('value') == iObjectId); });
	// 				$('#'+me.id).multiselect('refresh');
	// 			}
	// 			$('#label_'+me.id).focus();
	// 			me.ajax_request = null;
	// 		},
	// 		'json'
	// 	);
	//
	// 	return false; // Do NOT submit the form in case we are called by OnSubmit...
	// };


}