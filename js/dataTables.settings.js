/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// jQuery UI style "widget" for selecting and sorting "fields"
$(function () {
	// the widget definition, where "itop" is the namespace,
	// "DataTableSettings" the widget name
	$.widget("itop.DataTableSettings",
		{
			// default options
			options:
				{
					sListId: '',
					oColumns: {},
					sSelectMode: '',
					sSelectedItemsName: "",
					sViewLink: 'true',
					iPageSize: -1,
					oClassAliases: {},
					sTableId: null,
					oData: {},
					sRenderUrl: 'index.php',
					oRenderParameters: {},
					oDefaultSettings: {},
					oLabels: {moveup: 'Move Up', movedown: 'Move Down'}
				},

			// the constructor
			_create: function(mydatatable, options) {
				this.aDlgStateParams = ['iDefaultPageSize', 'oColumns'];
				this.element.addClass('itop-datatable');

				var me = this;
				var bViewLink = (this.options.sViewLink == 'true');
				$('#sfl_'+me.options.sListId).fieldsorter({hasKeyColumn: bViewLink, labels: this.options.oLabels, fields: this.options.oColumns, onChange: function() { me._onSpecificSettings(); } });
				$('#datatable_dlg_'+me.options.sListId).find('input[name="page_size"]').on('click', function() { me._onSpecificSettings(); });
				$('#datatable_dlg_'+me.options.sListId).find('input[name="save_settings"]').on('click', function() { me._updateSaveScope(); });
				this._updateSaveScope();
				this._saveDlgState();
			},

			// called when created, and later when changing options
			_refresh: function() {
				oParams = this.options.oData;
				oParams.operation = 'search_and_refresh';

				oParams.start = 0;
				oParams.end = this.options.iPageSize;
				oParams.select_mode = this.options.sSelectMode;
				oParams.display_key = this.options.sViewLink;
				oParams.class_aliases = this.options.oClassAliases;
				oParams.columns = this.options.oColumns;
				var iSortCol = 0;
				var aCurrentSort = [];
				for(var k1 in oParams.columns) //Aliases
				{
					for(var k2 in oParams.columns[k1]) //Attribute codes
					{
						if (oParams.columns[k1][k2].sort != 'none') {
							oParams.sort_col = iSortCol;
							oParams.sort_order = oParams.columns[k1][k2].sort;
							aCurrentSort.push([iSortCol, (oParams.columns[k1][k2].sort == 'asc') ? 0 : 1]);
							break; //TODO make this more generic, Sort on just one column for now
						}
						iSortCol++;
					}
					break; //TODO: DBObjectSet supports only sorting on the first alias of the set
				}
				oParams.list_id = this.options.sListId;
				var me = this;
				this.element.block();

				$.post(this.options.sRenderUrl, oParams, function(data) {
					// Nasty workaround to clear the pager's state for paginated lists !!!
					// See jquery.tablesorter.pager.js / saveParams / restoreParams
					if (window.pager_params) {
						window.pager_params['pager'+me.options.sListId] = undefined;
					}
					var parentElt = $('#'+me.options.sListId).closest('.dataTables_wrapper').parent();
					var aOptions = $('#'+me.options.sListId).DataTable().context[0].oInit;
					window['bSelectAllowed'+me.options.sListId] = false;
					$('#'+me.options.sListId).DataTable().destroy(true);
					var sThead = "";
					if (me.options.sSelectMode != "") {
						sThead += "<th></th>";
					}
					aOptions = $.extend(aOptions, JSON.parse(data));
					if (aOptions.js_files) {
						$.each(aOptions.js_files, function (i, item) {
							if ($.inArray(item, aLoadedJsFilesRegister) === -1)
							{
								sFileUrl = CombodoGlobalToolbox.AddParameterToUrl(item, aOptions.js_files_param, aOptions.js_files_value);
								$.ajax({url: sFileUrl, dataType: 'script', cache: true});
								aLoadedJsFilesRegister.set(item, new Promise(function (fJsFileResolve) {
									aLoadedJsFilesResolveCallbacks.set(item, fJsFileResolve);
									// Resolve promise right away as these files are loaded immediately
									aLoadedJsFilesResolveCallbacks.get(item, fJsFileResolve)();
								}));
							}
						});
					}
					$.each(aOptions['allColumns'], function (i, item) {
						$.each(item, function (j, champs) {
							if (champs.checked == 'true') {
								sThead += "<th>"+champs.label+"</th>";
							}
						});
					});
					$.each(aOptions['columns'], function (i, item) {
						aOptions["columns"][i]["render"]["display"] = new Function("data, type, row", aOptions["columns"][i]["render"]["display"]);
						if(aOptions["columns"][i]["createdCell"] != undefined) {
							aOptions["columns"][i]["createdCell"] = new Function("td, cellData, rowData, row, col", aOptions["columns"][i]["createdCell"]);
						}
					});

					// Append row actions column
					if (me.options.bHasRowActions) {
						sThead += "<th></th>";
						let iColumnCount = aOptions['columns'].length;
						aOptions["columns"][iColumnCount] = getRowActionsColumnDefinition(oParams.list_id);
					}

					parentElt.append("<table id=\""+me.options.sListId+"\" width=\"100%\" class=\"ibo-datatable\">"+
						"<thead><tr>"+sThead+"</tr></thead></table>");
					aOptions["lengthMenu"] = [[oParams.end, oParams.end * 2, oParams.end * 3, oParams.end * 4, -1], [oParams.end, oParams.end * 2, oParams.end * 3, oParams.end * 4, aOptions["lengthMenu"]]];
					aOptions["ajax"] = eval(aOptions["ajax"]);

					$('#'+me.options.sListId).DataTable(aOptions);

					me.element.unblock();

				}, 'html' );

			},
			_useDefaultSettings: function(bResetAll) {
				var oParams = this.options.oData;
				oParams.operation = 'datatable_reset_settings';

				oParams.table_id = this.options.sTableId;
				oParams.defaults = bResetAll;
				oParams.class_aliases = this.options.oClassAliases;

				var me = this;
				$.post(this.options.sRenderUrl, oParams, function(data) {
					// Do nothing...
				}, 'html' );
			},
			_saveSettings: function(bSaveAsDefaults) {
				var oParams = this.options.oData ;
				oParams.operation = 'datatable_save_settings';
				oParams.page_size = this.options.iPageSize;
				oParams.table_id = this.options.sTableId;
				oParams.defaults = bSaveAsDefaults;
				oParams.columns = this.options.oColumns;
				var iSortCol = 0;
				var sSortOrder = '';
				for(var i in this.options.oColumns) {
					if (this.options.oColumns[i].checked) {
						if (this.options.oColumns[i].sort != 'none') {
							sSortOrder = this.options.oColumns[i].sort;
						} else {
							iSortCol++;
						}
					}
				}
				oParams.sort_col = iSortCol;
				oParams.sort_order = sSortOrder;
				var me = this;
				$.post(this.options.sRenderUrl, oParams, function(data) {
					// Do nothing...
				}, 'html' );
			},
			onDlgOk: function() {
				var oOptions = {};
				oSettings = $('#datatable_dlg_'+this.options.sListId).find('input[name="settings"]:checked');
				if (oSettings.val() == 'defaults') {
					oOptions = { iPageSize: this.options.oDefaultSettings.iDefaultPageSize,
						oColumns: this.options.oDefaultSettings.oColumns
					};
				} else {
					var oDisplayColumns = {};
					var iColIdx = 0;
					var iSortIdx = 0;
					var sSortDirection = 'asc';
					var oColumns = $('#datatable_dlg_'+this.options.sListId).find(':itop-fieldsorter').fieldsorter('get_params');
					var iPageSize = parseInt($('#datatable_dlg_'+this.options.sListId+' input[name="page_size"]').val(), 10);
					// Fallback to default page size in case of invalid number
					if (isNaN(iPageSize) || iPageSize <= 0) {
						iPageSize = this.options.oDefaultSettings.iDefaultPageSize;
						$('#datatable_dlg_'+this.options.sListId+' input[name="page_size"]').val(iPageSize);
					}

					oOptions = {oColumns: oColumns, iPageSize: iPageSize, iDefaultPageSize: iPageSize };
				}
				this._setOptions(oOptions);
				this._refresh();

				// Check if we need to save the settings or not...
				var oSaveCheck = $('#datatable_dlg_'+this.options.sListId).find('input[name="save_settings"]');
				var oSaveScope = $('#datatable_dlg_'+this.options.sListId).find('input[name="scope"]:checked');
				if (oSaveCheck.prop('checked')) {
					if (oSettings.val() == 'defaults') {
						this._useDefaultSettings((oSaveScope.val() == 'defaults'));
					} else {
						this._saveSettings((oSaveScope.val() == 'defaults'));
					}
				}
				this._saveDlgState();

				$('#datatable_dlg_'+this.options.sListId).find('[name="action"]').val("save");
				$('#datatable_dlg_'+this.options.sListId).dialog('close');
			},
			onDlgCancel: function() {
				this._restoreDlgState();
			},
			_onSpecificSettings: function() {
				$('#datatable_dlg_'+this.options.sListId).find('input.specific_settings').prop('checked', true);
			},
			_updateSaveScope: function() {
				var oSaveCheck = $('#datatable_dlg_'+this.options.sListId).find('input[name="save_settings"]');
				if (oSaveCheck.prop('checked')) {
					$('#datatable_dlg_'+this.options.sListId).find('input[name="scope"]').each(function() {
						if ($(this).attr('stay-disabled') != 'true') {
							$(this).prop('disabled', false);
						}
					});
				} else {
					$('#datatable_dlg_'+this.options.sListId).find('input[name="scope"]').prop('disabled', true);
				}
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function() {
				this.element.removeClass('itop-datatable');
				$('#sfl_'+this.options.sListId).fieldsorter('destroy');

			},
			// _setOptions is called with a hash of all options that are changing
			_setOptions: function() {
				// in 1.9 would use _superApply
				this._superApply(arguments);
			},
			// _setOption is called for each individual option that is changing
			_setOption: function( key, value ) {
				// in 1.9 would use _super
				this._superApply(arguments);
			},
			UpdateState: function( config ) {
				var iPageSize = config.page_size;
				if (iPageSize == -1) {
					iPageSize = 0;
				}
				this.options.iPageSize = iPageSize;

				var iPos = 0;
				for (alias in this.options.oColumns) {
					for (attcode in this.options.oColumns[alias]) {
						this.options.oColumns[alias][attcode]['sort'] = 'none';
						if (this.options.oColumns[alias][attcode]['checked']) {
							if (iPos == config.sort_index) {
								this.options.oColumns[alias][attcode]['sort'] = config.sort_order;
							}
							iPos++;
						}
					}
				}

				var dlgElement = $('#datatable_dlg_'+this.options.sListId);
				dlgElement.find('input[name="page_size"]').val(iPageSize);
				dlgElement.find(':itop-fieldsorter').fieldsorter('option', { fields: this.options.oColumns });
			},
			_saveDlgState: function () {
				this.originalState = {};
				for (k in this.aDlgStateParams) {
					this.originalState[this.aDlgStateParams[k]] = this.options[this.aDlgStateParams[k]];
				}
				this.originalState.iDefaultPageSize = $('#datatable_dlg_'+this.options.sListId).find('input[name="page_size"]').val();
				this.originalState.oFields = $('#datatable_dlg_'+this.options.sListId).find(':itop-fieldsorter').fieldsorter('get_params');
			},
			_restoreDlgState: function () {
				var dlgElement = $('#datatable_dlg_' + this.options.sListId);

				for (k in this.aDlgStateParams) {
					this._setOption(this.aDlgStateParams[k], this.originalState[this.aDlgStateParams[k]]);
				}

				dlgElement.find('input[name="page_size"]').val(this.originalState.iDefaultPageSize);

				dlgElement.find(':itop-fieldsorter').fieldsorter('option', {fields: this.originalState.oFields});

				dlgElement.unblock();

			},
			IsDialogOpen: function () {
				//TODO 3.0.0 voir si on accede à cette fonction. il y a de grandes chances pour qu'elle ne soit plus utilisée
				var oDlgOpen = $('#datatable_dlg_'+this.options.sListId+' :visible');

				return (oDlgOpen.length > 0);
			},
			DoRefresh: function () {
				this._refresh();
			},
			GetColumns: function () {
				return this.options.oColumns;
			}
		});
});