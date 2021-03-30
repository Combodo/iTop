/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


function LinksWidget(id, sClass, sAttCode, iInputId, sSuffix, bDuplicates, oWizHelper, sExtKeyToRemote, bDoSearch) {
	this.id = id;
	this.iInputId = iInputId;
	this.sClass = sClass;
	this.sAttCode = sAttCode;
	this.sSuffix = sSuffix;
	this.bDuplicates = bDuplicates;
	this.oWizardHelper = oWizHelper;
	this.sExtKeyToRemote = sExtKeyToRemote;
	this.iMaxAddedId = 0;
	this.aAdded = [];
	this.aRemoved = [];
	this.aModified = {};
	this.bDoSearch = bDoSearch; // false if the search is not launched
	let me = this;

	this.Init = function () {
		// make sure that the form is clean
		$('#linkedset_'+this.id+' .selection').each(function () {
			this.checked = false;
		});
		$('#'+this.id+'_btnRemove').prop('disabled', true);

		$('#linkedset_'+me.id).on('remove', function () {
			// prevent having the dlg div twice
			$('#dlg_'+me.id).remove();
		});

		me.RegisterChange();

		let oInput = $('#'+this.iInputId);
		oInput.bind('update_value', function () {
			$(this).val(me.GetUpdatedValue());
		});
		oInput.closest('form').on('submit', function () {
			return me.OnFormSubmit();
		});
	};

	this.RemoveSelected = function () {
		let my_id = '#'+me.id;
		$('#datatable_'+me.id).DataTable().rows($('#datatable_'+me.id).find('tr.selected')).remove();
		$('#linkedset_'+me.id+' .selection:checked').each(function () {
			$(my_id+'_row_'+this.value).remove();
			let iLink = $(this).attr('data-link-id');
			if (iLink > 0) {
				me.aRemoved.push(iLink);
				if (me.aModified.hasOwnProperty(iLink)) {
					delete me.aModified[iLink];
				}
			}
			else
			{
				let iUniqueId = $(this).attr('data-unique-id');
				if (iUniqueId < 0)
				{
					iUniqueId = -iUniqueId;
				}
				me.aAdded[iUniqueId] = null;
			}
		});
		// Disable the button since all the selected items have been removed
		$(my_id+'_btnRemove').prop('disabled', true);
		// Re-run the zebra plugin to properly highlight the remaining lines & and take into account the removed ones
		$('#linkedset_'+this.id+' .listResults').trigger('update').trigger("applyWidgets");

		if ($('#linkedset_'+this.id+' .selection').length == 0)
		{
			// All items were removed: add a dummy hidden input to make sure that the linkset will be updated (emptied) when posted
			$('#'+me.id+'_empty_row').show();
		}
	};

	this.OnSelectChange = function () {
		let nbChecked = $('#linkedset_'+me.id+' .selection:checked').length;
		if (nbChecked > 0)
		{
			$('#'+me.id+'_btnRemove').prop('disabled', false);
		}
		else
		{
			$('#'+me.id+'_btnRemove').prop('disabled', true);
		}
	};

	this.AddObjects = function () {
		let me = this;
		$('#'+me.id+'_indicatorAdd').html('&nbsp;<img src="../images/indicator.gif"/>');
		me.oWizardHelper.UpdateWizard();
		let theMap = {
			sAttCode: me.sAttCode,
			iInputId: me.iInputId,
			sSuffix: me.sSuffix,
			bDuplicates: me.bDuplicates,
			'class': me.sClass,
			operation: 'addObjects',
			json: me.oWizardHelper.ToJSON()
		};

		// Gather the already linked target objects
		theMap.aAlreadyLinked = [];
		$('#linkedset_'+me.id+' .selection:input').each(function (i) {
			let iRemote = $(this).attr('data-remote-id');
			theMap.aAlreadyLinked.push(iRemote);
		});

		$.ajax({
				"url": GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
				"method": "POST",
				"data": theMap,
				"dataType": "html"
			})
			.done(function (data) {
				$('#dlg_'+me.id).html(data);
				$('#dlg_'+me.id).dialog('open');
				me.UpdateSizes(null, null);
				if (me.bDoSearch)
				{
					me.SearchObjectsToAdd();
				}
				else
				{
					$('#count_'+me.id).change(function () {
						let c = this.value;
						me.UpdateButtons(c);
					});
				}
				$('#'+me.id+'_indicatorAdd').html('');
			})
		;
	};

	this.SearchObjectsToAdd = function () {
		$('#count_'+me.id).change(function () {
			let c = this.value;
			me.UpdateButtons(c);
		});
		me.UpdateSizes(null, null);

		$("#fs_sf_SearchFormToAdd_"+me.id).trigger('itop.search.form.submit');

		return false; // Don't submit the form, stay in the current page !
	};

	this.UpdateButtons = function (iCount) {
		let okBtn = $('#btn_ok_'+me.id);
		if (iCount > 0)
		{
			okBtn.prop('disabled', false);
		}
		else
		{
			okBtn.prop('disabled', true);
		}
	};

	this.DoAddObjects = function () {
		let theMap = {
			sAttCode: me.sAttCode,
			iInputId: me.iInputId,
			sSuffix: me.sSuffix,
			bDuplicates: me.bDuplicates,
			'class': me.sClass
		};

		// Gather the parameters from the search form
		let context = $('#SearchResultsToAdd_'+me.id);
		let selectionMode = $(':input[name=selectionMode]', context);
		if (selectionMode.length > 0)
		{
			// Paginated table retrieve the mode and the exceptions
			let sMode = selectionMode.val();
			theMap['selectionMode'] = sMode;
			$('#fs_SearchFormToAdd_'+me.id+' :input').each(
				function (i) {
					if(this.name !="")
					{
						theMap[this.name] = this.value;
					}
				}
			);
			theMap['sRemoteClass'] = theMap['class'];  // swap 'class' (defined in the form) and 'remoteClass'
			theMap['class'] = me.sClass;
			$(' :input[name^=storedSelection]', context).each(function () {
				if (theMap[this.name] == undefined) {
					theMap[this.name] = [];
				}
				theMap[this.name].push(this.value);
				$(this).remove(); // Remove the selection for the next time the dialog re-opens
			});

			// Retrieve the 'filter' definition
			theMap['filter'] = $(':input[name=filter]', context).val();
			theMap['extra_params'] = $(':input[name=extra_params]', context).val();
		}
		// Normal table, retrieve all the checked check-boxes
		$(':checked[name^=selectObject]', context).each(
			function (i) {
				if ((this.name != '') && ((this.type != 'checkbox') || (this.checked))) {
					arrayExpr = /\[\]$/;
					if (arrayExpr.test(this.name)) {
						// Array
						if (theMap[this.name] == undefined) {
							theMap[this.name] = [];
						}
						theMap[this.name].push(this.value);
					} else {
						theMap[this.name] = this.value;
					}
				}
				$(this).parents('tr:first').remove(); // Remove the whole line, so that, next time the dialog gets displayed it's no longer there
			}
		);

		theMap['operation'] = 'doAddIndirectLinks';
		theMap['max_added_id'] = this.iMaxAddedId;
		if (me.oWizardHelper == null) {
			theMap['json'] = '';
		} else {
			// Not inside a "search form", updating a real object
			me.oWizardHelper.UpdateWizard();
			theMap['json'] = me.oWizardHelper.ToJSON();
		}
		$('#busy_'+me.iInputId).html('&nbsp;<img src="../images/indicator.gif"/>');
		// Run the query and display the results
		$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', theMap,
			function (data) {
				if (data != '') {
					$.each(data.data, function (idx, row) {
						$('#datatable_'+me.id).DataTable().row.add(row).draw();
					});


					$.each(data.scripts, function (idx, script) {
						$.globalEval(script);
					});
					$('#linkedset_'+me.id+' .listResults').trigger('update').trigger("applyWidgets"); // table is already sortable, just refresh it
					$('#linkedset_'+me.id+' :input').each(function () {
						$(this).trigger('validate', '');
					}); // Validate newly added form fields...
					$('#datatable_'+me.id).DataTable().columns.adjust().draw();
					//$('#datatable_team_list').DataTable().columns.adjust().draw();
					$('#busy_'+me.iInputId).html('');
				}
			},
			'json'
		);
		$('#dlg_'+me.id).dialog('close');
		return false;
	};

	this.AddLink = function (iAddedId, iRemote) {
		// Assumption: this identifier will be higher than the previous one
		me.iMaxAddedId = iAddedId;
		let sFormPrefix = me.iInputId;
		oAdded = {};
		oAdded['formPrefix'] = sFormPrefix;
		oAdded['attr_'+sFormPrefix+this.sExtKeyToRemote] = iRemote;
		me.aAdded[iAddedId] = oAdded;
	};

	this.OnLinkAdded = function (iAddedId, iRemote) {
		this.AddLink(iAddedId, iRemote);
		me.RegisterChange();
	};

	this.UpdateSizes = function (event, ui) {
		let dlg = $('#dlg_'+me.id);
		let searchForm = $('#SearchFormToAdd_'+me.id);
		let results = $('#SearchResultsToAdd_'+me.id);
		let padding_right = 0;
		if (dlg.css('padding-right'))
		{
			padding_right = parseInt(dlg.css('padding-right').replace('px', ''));
		}
		let padding_left = 0;
		if (dlg.css('padding-left'))
		{
			padding_left = parseInt(dlg.css('padding-left').replace('px', ''));
		}
		let padding_top = 0;
		if (dlg.css('padding-top'))
		{
			padding_top = parseInt(dlg.css('padding-top').replace('px', ''));
		}
		let padding_bottom = 0;
		if (dlg.css('padding-bottom'))
		{
			padding_bottom = parseInt(dlg.css('padding-bottom').replace('px', ''));
		}
		width = dlg.innerWidth()-padding_right-padding_left-22; // 5 (margin-left) + 5 (padding-left) + 5 (padding-right) + 5 (margin-right) + 2 for rounding !
		height = dlg.innerHeight()-padding_top-padding_bottom-22;
		wizard = dlg.find('.wizContainer:first');
		wizard.width(width);
		wizard.height(height);
		form_height = searchForm.outerHeight();
		results.height(height-form_height-40); // Leave some space for the buttons
	};

	this.GetUpdatedValue = function () {
		let sSelector = '#linkedset_'+me.id+' :input[name^=attr_'+me.id+']';
		let aIndexes = [];
		let aValues = [];
		$(sSelector).each(function () {
			let re = /\[([^\[]+)\]\[(.+)\]/;
			let aMatches = [];
			if (aMatches = this.name.match(re))
			{
				let idx = aMatches[1];
				let index = jQuery.inArray(idx, aIndexes);
				if (index == -1)
				{
					aIndexes.push(idx);
					index = jQuery.inArray(idx, aIndexes);
					aValues[index] = {};
				}
				let value = $(this).val();
				if (aMatches[2] == "id")
				{
					let iId = parseInt(aMatches[1], 10);
					if (iId < 0)
					{
						aValues[index][me.sExtKeyToRemote] = -iId;
					}
					else
					{
						aValues[index]['id'] = value;
					}
				}
				else
				{
					aValues[index][aMatches[2]] = value;
				}
			}
		});
		return JSON.stringify(aValues);
	};

	this.RegisterChange = function () {
		// Listen only used inputs
		$('#linkedset_'+me.id+' :input[name^="attr_'+me.sAttCode+'["]').off('change').on('change', function () {
			if (!($(this).hasClass('selection')))
			{
				let oCheckbox = $(this).closest('tr').find('.selection');
				let iLink = oCheckbox.attr('data-link-id');
				let iUniqueId = oCheckbox.attr('data-unique-id');
				let sAttCode = $(this).closest('.attribute-edit').attr('data-attcode');
				let value = $(this).val();
				return me.OnValueChange(iLink, iUniqueId, sAttCode, value, this);
			}
			return true;
		});
	};

	/**
	 * @param int iLink id contained in the data-link-id attribute of the .selection widget
	 * @param int iUniqueId id contained in the data-unique-id attribute of the .selection widget
	 * @param string sAttCode
	 * @param string value new value of the autocomplete
	 * @param jQuery $oSourceObject object which fires the event
	 */
	this.OnValueChange = function (iLink, iUniqueId, sAttCode, value, $oSourceObject) {
		let sFormPrefix = me.iInputId;
		if (iLink > 0)
		{
			// Modifying an existing link
			let oModified = me.aModified[iLink];
			if (oModified == undefined)
			{
				// Still not marked as modified
				oModified = {};
				oModified['formPrefix'] = sFormPrefix;
			}
			// Weird formatting, aligned with the output of the direct links widget (new links to be created)
			oModified['attr_'+sFormPrefix+sAttCode] = value;
			me.aModified[iLink] = oModified;
		}
		else
		{
			// Modifying a newly added link - the structure should already be up to date
			if (iUniqueId < 0)
			{
				iUniqueId = -iUniqueId;
			}
			me.aAdded[iUniqueId]['attr_'+sFormPrefix+sAttCode] = value;
		}
	};

	this.OnFormSubmit = function () {
		let oDiv = $('#linkedset_'+me.id);

		let sToBeDeleted = JSON.stringify(me.aRemoved);
		$('<input type="hidden" name="attr_'+me.sAttCode+'_tbd">').val(sToBeDeleted).appendTo(oDiv);


		let sToBeModified = JSON.stringify(me.aModified);
		$('<input type="hidden" name="attr_'+me.sAttCode+'_tbm">').val(sToBeModified).appendTo(oDiv);

		let aToBeCreated = [];
		me.aAdded.forEach(function (oAdded) {
			if (oAdded != null)
			{
				aToBeCreated.push(oAdded);
			}
		});
		let sToBeCreated = JSON.stringify(aToBeCreated);
		$('<input type="hidden" name="attr_'+me.sAttCode+'_tbc">').val(sToBeCreated).appendTo(oDiv);

		// Remove unused inputs
		$('#linkedset_'+me.id+' :input[name^="attr_'+me.sAttCode+'["]').prop("disabled", true);
	};
}
