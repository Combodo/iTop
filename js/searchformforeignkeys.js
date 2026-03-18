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
	// this.bSelectMode = bSelectMode; // true if the edited field is a SELECT, false if it's an autocomplete
	// this.bSearchMode = bSearchMode; // true if selecting a value in the context of a search form
	var me = this;

	this.Init = function()
	{
	};

	this.ShowModalSearchForeignKeys = function()
	{
        const oModalParams = {
            content: {
                endpoint: AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'),
                data: {
                    sAttCode: me.sAttCode,
                    iInputId: me.id,
                    sTargetClass: me.sTargetClass,
                    operation: 'ShowModalSearchForeignKeys'
                },
            },
            title: me.sTitle,
            id: 'dlg_'+me.id,
            size: 'lg',
            buttons: [
                {
                    text: Dict.S('UI:Button:Cancel'),
                    callback_on_click: function() {
                        $(this).dialog("close");
                    },
                    classes: ['cancel', 'ibo-is-alternative', 'ibo-is-neutral'],
                },
                {
                    text: Dict.S('UI:Button:Add'),
                    id: "btn_ok_"+me.id,
                    classes: ['ok', 'ibo-is-regular', 'ibo-is-primary'],
                    callback_on_click: function() {
                        me.DoAddObjects();
                    }
                }
            ],
            callback_on_content_loaded: function(oModalContentElement){
                // Update initial buttons state
                me.UpdateButtons();
            },

            extra_options: {
                    callback_on_modal_close: function () {
                        $(this).remove(); // destroy then remove dialog object
                    }
                }
        }
        const oModal = CombodoModal.OpenModal(oModalParams);

        // Bind events
        oModal.on('change', '#count_'+me.id, function(){
            me.UpdateButtons();
        });
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
}