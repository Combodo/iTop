// Copyright (C) 2010-2024 Combodo SAS
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
 * @param oSearchWidgetElmt
 * @param sFilter
 * @param sTitle
 * @constructor
 */
function SearchFormForeignKeys(id, sTargetClass, sAttCode, oSearchWidgetElmt, sFilter, sTitle)
{
	this.id = id;
	//this.sOriginalTargetClass = sTargetClass;
	this.sTargetClass = sTargetClass;
	this.sFilter = sFilter;
	this.sTitle = sTitle;
	this.sAttCode = sAttCode;
	this.oSearchWidgetElmt = oSearchWidgetElmt;
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

		$('<div id="dlg_'+me.id+'"></div>').appendTo(document.body);

		// me.trace(dialog);

		//TODO : check and remove all unneded code bellow this line!!

		$('#'+this.id+'_linksToRemove').val('');

		$('#linkedset_'+me.id).on('remove', function() {
			// prevent having the dlg div twice
			$('#dlg_'+me.id).remove();
		});

		$('#'+this.iInputId).closest('form').on('submit', function() {
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
		var results = $('#SearchResultsToAdd_'+me.id);
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
		//var width = dlg.innerWidth() - oPadding['right'] - oPadding['left'] - 22; // 5 (margin-left) + 5 (padding-left) + 5 (padding-right) + 5 (margin-right) + 2 for rounding !
		var height = dlg.innerHeight()-oPadding['top']-oPadding['bottom']-22;
		var form_height = searchForm.outerHeight();
		results.height(height - form_height - 40); // Leave some space for the buttons
	};

	this.UpdateButtons = function()
	{
		var okBtn = $('#btn_ok_'+me.id);
		if ($('#count_'+me.id).val() > 0)
		{
			okBtn.prop('disabled', false);
		}
		else
		{
			okBtn.prop('disabled', true);
		}
	};

	/**
	 * @return {boolean}
	 */
	this.ListResultsSearchForeignKeys = function ()
	{
		var theMap = {
			sTargetClass: me.sTargetClass,
			iInputId: me.id,
			sFilter: me.sfilter,
			// bSearchMode: me.bSearchMode
		};

		// Gather the parameters from the search form
		$('#fs_'+me.id+' :input').each( function() {
			if (this.name !== '')
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
		var sSearchAreaId = '#SearchResultsToAdd_'+me.id;
		//$(sSearchAreaId).html('<div style="text-align:center;width:100%;height:24px;vertical-align:middle;"><img src="../images/indicator.gif" /></div>');
		$(sSearchAreaId).block();
		me.UpdateButtons();

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();

		// Run the query and display the results
		me.ajax_request = $.post(AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
			function(data)
			{
				$(sSearchAreaId).html(data);
				$('#fr_'+me.id+' input:radio').on('click', function() { me.UpdateButtons(); });
				me.UpdateButtons();
				me.ajax_request = null;
				$('#count_'+me.id).on('change', function(){
					me.UpdateButtons();
				});
				me.UpdateSizes();
			},
			'html'
		);

		return false; // Don't submit the form, stay in the current page !
	};

	/**
	 * @return {boolean}
	 */
	this.DoAddObjects = function () {
		// Gather the parameters from the search form
		var theMap = {};
		var context = $('#SearchResultsToAdd_'+me.id);
		var selectionMode = $(':input[name="selectionMode"]', context);
		if (selectionMode.length > 0) {
			// Paginated table retrieve the mode and the exceptions
			theMap['selectionMode'] = (selectionMode.val() == 'negative') ? 'negative' : 'positive';
			$('#fs_SearchFormToAdd_'+me.id+' :input').each(function () {
				theMap[this.name] = this.value;
			});

			$(':input[name="storedSelection[]"]', context).each(function () {
				if (typeof theMap[this.name] === "undefined") {
					theMap[this.name] = [];
				}
				theMap[this.name].push(this.value);
				$(this).remove(); // Remove the selection for the next time the dialog re-opens
			});
		}

		// Normal table, retrieve all the checked check-boxes
		$(':checked[name="selectObject[]"]', context).each(
			function () {
				if ((this.name !== '') && ((this.type !== 'checkbox') || (this.checked))) {
					var arrayExpr = /\[\]$/;
					if (arrayExpr.test(this.name)) {
						// Array
						if (typeof theMap[this.name] === "undefined") {
							theMap[this.name] = [];
						}
						theMap[this.name].push(this.value);
					}
					else {
						theMap[this.name] = this.value;
					}
				}
				$(this).parents('tr:first').remove(); // Remove the whole line, so that, next time the dialog gets displayed it's no longer there
			}
		);
		theMap["sFilter"] = $('#datatable_ResultsToAdd_'+me.id+' [name="filter"]').val();
		theMap["class"] = me.sTargetClass;
		theMap['operation'] = 'GetFullListForeignKeysFromSelection';
		$('#busy_'+me.iInputId).html('&nbsp;<img src="../images/indicator.gif"/>');
		// Run the query and display the results
		$.ajax(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {
				"data": theMap,
				"method": "POST"
			})
			.done(function (data) {
				if (Object.keys(data).length > 0) {
					me.oSearchWidgetElmt.trigger("itop.search.criteria_enum.add_selected_values", data);
				}
			})
			.fail(function (data) {
				try {
					console.error(data);
				} catch (e) {
				}
			})
		;

		$('#dlg_'+me.id).dialog('close');

		return false;
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
			$('#SearchResultsToAdd_'+me.id).html(me.emptyHtml);
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
			$('#label_'+me.id).prop('disabled', true);
			$('#label_'+me.id).css({'background': 'transparent'});
			$('#mini_add_'+me.id).hide();
			$('#mini_tree_'+me.id).hide();
			$('#mini_search_'+me.id).hide();
		}
		else
		{
			$('#label_'+me.id).prop('disabled', false);
			$('#label_'+me.id).css({'background': '#fff url(../images/ac-background.gif) no-repeat right'});
			$('#mini_add_'+me.id).show();
			$('#mini_tree_'+me.id).show();
			$('#mini_search_'+me.id).show();
		}
	};
}