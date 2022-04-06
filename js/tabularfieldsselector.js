/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// jQuery UI style "widget" for managing the "xlsx-exporter"
$(function () {
	// the widget definition, where "itop" is the namespace,
	// "tabularfieldsselector" the widget name
	$.widget("itop.tabularfieldsselector",
		{
			// default options
			options:
				{
					fields: [],
					value_holder: '#tabular_fields',
					sample_data: [],
					total_count: 0,
					preview_limit: 3,
					labels: {
						preview_header: "Drag and drop the columns to change their order. Preview of %1$s lines. Total number of lines to export: %2$s",
						empty_preview: "Select the columns to be exported from the list above",
						columns_order: "Columns order",
						columns_selection: 'Available columns from %1$s',
						check_all: 'Check all',
						uncheck_all: 'Uncheck all',
						no_field_selected: 'Select at least one column to be exported'
					}
				},

			// the constructor
			_create: function () {
				var me = this;
				this._flatten_fields(this.options.fields);
				this.sId = this.element.attr('id');
				this.element.addClass('itop-tabularfieldsselector');
				this.element.parent().bind('form-part-activate', function () {
					me._update_from_holder();
					me._update_preview();
				});
				this.element.parent().bind('validate', function () {
					me.validate();
				});

				this.aSelected = [];

				for (var i in this.options.fields) {
					var sContent = `<div class="ibo-panel ibo-is-blue ibo-is-opened">
				<div class="ibo-panel--header">
					<div class="ibo-panel--header-left">
						<div class= "ibo-panel--title" > `+this._format(this.options.labels.columns_selection, i)+`</div>
					</div>`;
					sContent += `
					<div class="ibo-panel--header-right">
						<div class="ibo-panel--toolbar">
						<button class="check_all ibo-button ibo-is-regular ibo-is-neutral action" type="button"><span class=""ibo-button-label">`+this.options.labels.check_all+`</span></button>
					    <button class="uncheck_all ibo-button ibo-is-regular ibo-is-neutral action" type="button"><span class=""ibo-button-label">`+this.options.labels.uncheck_all+`</span></button>
				</div>
				</div>`;
					sContent += `</div>
				<div class="ibo-panel--body">`;
					for (var j in this.options.fields[i]) {
						sContent += this._get_field_checkbox(this.options.fields[i][j].code, this.options.fields[i][j].label, (this.options.fields[i][j].subattr.length > 0), false, null);
					}
					sContent += `</div>
				</div>`;
					this.element.append(sContent);
				}
				sContent = `<div class="ibo-panel ibo-is-blue ibo-is-opened">
				<div class="ibo-panel--header">
					<div class="ibo-panel--header-left">
						<div class="ibo-panel--title">`+this.options.labels.columns_order+`</div>
					</div>
					</div>
				<div class="ibo-panel--body">	`;


				sContent += '<div class="ibo-preview-header">'+this._format(this.options.labels.preview_header, Math.min(this.options.preview_limit, this.options.total_count), this.options.total_count)+'</div>';
				sContent += '<div class="ibo-table-preview"></div>';
				sContent += '</div></div>';
				this.element.append(sContent);

				this._update_from_holder();

				$('body').on('click change', '.tfs_checkbox', function () {
					var sInstanceId = $(this).attr('data-instance-id');
					if (sInstanceId != me.sId) {
						return;
					}
					me._on_click($(this));
				});

				var maxWidth = 0;
				$('#'+this.sId+' .tfs_checkbox, #'+this.sId+' .tfs_checkbox_multi').each(function () {
					maxWidth = Math.max(maxWidth, $(this).parent().width());
				});
				$('#'+this.sId+' .tfs_checkbox, #'+this.sId+' .tfs_checkbox_multi').each(function () {
					$(this).parent().parent().width(maxWidth).css({display: 'inline-block'});
				});

				$('#'+this.sId+' .tfs_checkbox_multi').on('click', function () {
					me._on_multi_click($(this).val(), this.checked);
				});
				$('#'+this.sId+' .check_all').on('click', function () {
					me._on_check_all($(this).closest('.ibo-panel'), true);
				});
				$('#'+this.sId+' .uncheck_all').on('click', function () {
					me._on_check_all($(this).closest('.ibo-panel'), false);
				});


				this._update_preview();
				this._make_tooltips();
			},
			_on_click: function (jItemClicked) {

				var bChecked = jItemClicked.prop('checked');
				var sValue = jItemClicked.val();
				this._mark_as_selected(sValue, bChecked);
				this._update_holder();
				this._update_preview();
				var sDataParent = jItemClicked.attr('data-parent');
				if (sDataParent != '') {
					this._update_tristate(sDataParent+'_multi');
				}
			},
			_on_multi_click: function (sMultiFieldCode, bChecked) {
				var me = this;
				var oField = this._get_main_field_by_code(sMultiFieldCode);
				if (oField != null) {
					var sPrefix = '#tfs_'+me.sId+'_';
					for (var k in oField.subattr) {
						me._mark_as_selected(oField.subattr[k].code, bChecked);
						// In case the tooltip is visible, also update the checkboxes
						sElementId = (sPrefix+oField.subattr[k].code).replace('.', '_');
						$(sElementId).prop('checked', bChecked);
					}
					me._update_tooltips(sMultiFieldCode);
					me._update_holder();
					me._update_preview();
				}
			},
			_on_check_all: function (jSelector, bChecked) {
				var me = this;
				jSelector.find('.tfs_checkbox').each(function () {
					$(this).prop('checked', bChecked);
					me._mark_as_selected($(this).val(), bChecked);
				});
				jSelector.find('.tfs_checkbox_multi').each(function () {
					var sMultiFieldCode = $(this).val();
					var oField = me._get_main_field_by_code(sMultiFieldCode);
					if (oField != null) {
						$(this).prop('checked', bChecked);
						$(this).prop('indeterminate', false);
						var sPrefix = '#tfs_'+this.sId+'_';
						for (var k in oField.subattr) {
							me._mark_as_selected(oField.subattr[k].code, bChecked);
							// In case the tooltip is visible, also update the checkboxes
							sElementId = (sPrefix+oField.subattr[k].code).replace('.', '_');
							$(sElementId).prop('checked', bChecked);
						}
						me._update_tooltips(sMultiFieldCode);
					}
				});
				this._update_holder();
				this._update_preview();
			},
			_update_tristate: function (sParentId) {
				// Check if the parent is checked, unchecked or indeterminate
				var sParentId = sParentId.replace('.', '_');
				var sAttCode = $('#'+sParentId).val();
				var oField = this._get_main_field_by_code(sAttCode);
				if (oField != null) {
					var iNbChecked = 0;
					var aDebug = [];
					for (var j in oField.subattr) {
						if ($.inArray(oField.subattr[j].code, this.aSelected) != -1) {
							aDebug.push(oField.subattr[j].code);
							iNbChecked++;
						}
					}
					if (iNbChecked == oField.subattr.length) {
						$('#'+sParentId).prop('checked', true);
						$('#'+sParentId).prop('indeterminate', false);
					} else if (iNbChecked == 0) {
						$('#'+sParentId).prop('checked', false);
						$('#'+sParentId).prop('indeterminate', false);
					} else {
						$('#'+sParentId).prop('checked', false);
						$('#'+sParentId).prop('indeterminate', true);
					}
				}
			},
			_mark_as_selected: function (sValue, bSelected) {
				if (bSelected) {
					if ($.inArray(sValue, this.aSelected) == -1) {
						this.aSelected.push(sValue);
					}
				} else {
					aSelected = [];
					for (var k in this.aSelected) {
						if (this.aSelected[k] != sValue) {
							aSelected.push(this.aSelected[k]);
						}
					}
					this.aSelected = aSelected;
				}
			},
			_update_holder: function () {
				$(this.options.value_holder).val(this.aSelected.join(','));
			},
			_update_from_holder: function () {
				var sFields = $(this.options.value_holder).val();
				var bAdvanced = parseInt($(this.options.advanced_holder).val(), 10);

				if (sFields != '') {
					this.aSelected = sFields.split(',');
					var safeSelected = [];
					var me = this;
					var bModified = false;
					for (var k in this.aSelected) {
						var oField = this._get_field_by_code(this.aSelected[k])
						if (oField == null) {
							// Invalid field code supplied, don't copy it
							bModified = true;
						} else {
							safeSelected.push(this.aSelected[k]);
						}
					}
					if (bModified) {
						this.aSelected = safeSelected;
						this._update_holder();
					}
					$('#'+this.sId+' .tfs_checkbox').each(function () {
						if ($.inArray($(this).val(), me.aSelected) != -1) {
							$(this).prop('checked', true);
						} else {
							$(this).prop('checked', false);
						}
					});
				}
				var me = this;
				$('#'+this.sId+' .tfs_checkbox_multi').each(function () {
					me._update_tristate($(this).attr('id'));
				});

			},
			_update_preview: function () {
				var sHtml = '';
				if (this.aSelected.length > 0) {
					sHtml += '<table><thead><tr>';
					for (var k in this.aSelected) {
						var sField = this.aSelected[k];
						if ($.inArray(sField, this.aSelected) != -1) {
							var sRemoveBtn = '<span class="export-field-close ibo-table-preview--remove-column" data-attcode="'+sField+'"><span class="fas fa-times"></span></span>';
							sHtml += '<th data-attcode="'+sField+'"><span class="drag-handle">'+this.aFieldsByCode[sField].unique_label+'</span>'+sRemoveBtn+'</th>';
						}
					}
					sHtml += '</tr></thead><tbody>';

					for (var i = 0; i < Math.min(this.options.preview_limit, this.options.total_count); i++) {
						sHtml += '<tr>';
						for (var k in this.aSelected) {
							var sField = this.aSelected[k];
							sHtml += '<td>'+this.options.sample_data[i][sField]+'</td>';
						}
						sHtml += '</tr>';
					}

					sHtml += '</tbody></table>';

					$('#'+this.sId+' .preview_header').show();
					$('#'+this.sId+' .ibo-table-preview').html(sHtml);
					var me = this;
					$('#'+this.sId+' .ibo-table-preview table').dragtable({
						persistState: function (table) {
							me._on_drag_columns(table);
						}, dragHandle: '.drag-handle'
					});
					$('#'+this.sId+' .ibo-table-preview table .export-field-close').on('click', function (event) {
						me._on_remove_column($(this).attr('data-attcode'));
						event.preventDefault();
						return false;
					});
				} else {
					$('#'+this.sId+' .preview_header').hide();
					$('#'+this.sId+' .ibo-table-preview').html('<div class="export_empty_preview">'+this.options.labels.empty_preview+'</div>');
				}
				$('.form_part:visible').trigger('preview_updated');
			},
			_get_field_by_code: function (sFieldCode) {
				for (var k in this.aFieldsByCode) {
					if (k == sFieldCode) {
						return this.aFieldsByCode[k];
					}
				}
				return null;
			},
			_get_main_field_by_code: function (sFieldCode) {
				for (var i in this.options.fields) {
					for (var j in this.options.fields[i]) {
						if (this.options.fields[i][j].code == sFieldCode) {
							return this.options.fields[i][j];
						}
					}
				}
				return null;
			},
			_on_drag_columns: function (table) {
				var me = this;
				me.aSelected = [];
				table.el.find('th').each(function (i) {
					me.aSelected.push($(this).attr('data-attcode'));
				});
				this._update_holder();
			},
			_on_remove_column: function (sField) {
				var sElementId = this.sId+'_'+sField;
				sElementId = '#tfs_'+sElementId.replace('.', '_');
				$(sElementId).prop('checked', false);

				this._mark_as_selected(sField, false);
				this._update_holder();
				this._update_preview();
				var me = this;
				$('#'+this.sId+' .tfs_checkbox_multi').each(function () {
					me._update_tristate($(this).attr('id'));
					me._update_tooltips($(this).val());
				});
			},
			_format: function () {
				var s = arguments[0];
				for (var i = 0; i < arguments.length-1; i++) {
					var reg = new RegExp("%"+(i+1)+"\\$s", "gm");
					s = s.replace(reg, arguments[i+1]);
				}
				return s;
			},
			validate: function () {
				if (this.aSelected.length == 0) {
					var aMessages = $('#export-form').data('validation_messages');
					aMessages.push(this.options.labels.no_field_selected);
					$('#export-form').data('validation_messages', aMessages);
				}
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			destroy: function () {
				this.element
					.removeClass('itop-tabularfieldsselector');

				this.element.parent().off('activate');
				this.element.parent().off('validate');
			},
			// _setOptions is called with a hash of all options that are changing
			_setOptions: function () {
				this._superApply(arguments);
			},
			// _setOption is called for each individual option that is changing
			_setOption: function (key, value) {
				if (key == 'fields') {
					this._flatten_fields(value);
				}
				this._superApply(arguments);
			},
			_flatten_fields: function (aFields) {
				// Update the "flattened" via of the fields
				this.aFieldsByCode = {}; // Must be an object since indexes are non-numeric
				for (var k in aFields) {
					for (var i in aFields[k]) {
						this.aFieldsByCode[aFields[k][i].code] = aFields[k][i];
						for (var j in aFields[k][i].subattr) {
							this.aFieldsByCode[aFields[k][i].subattr[j].code] = aFields[k][i].subattr[j];
						}
					}
				}
			},
			_make_tooltips: function () {
				var me = this;
				$('#'+this.sId+' .tfs_advanced').each(function (index, elt) {
					var sDataAttcode = $(elt).attr('data-attcode');
					var sTooltipContent = me._get_tooltip_content(sDataAttcode);
					tippy(elt, {
						appendTo: document.body,
						'content': sTooltipContent,
						allowHTML: true,
						interactive: true,
						zIndex: 9999,
						role: 'tooltip',
						popperOptions: {
							strategy: 'fixed'
						}
					});
				});
			},
			_update_tooltips: function (sDataAttCode) {
				let sTooltipContent = this._get_tooltip_content(sDataAttCode);
				$('#'+this.sId+'  label[data-attcode="'+sDataAttCode+'"]').each(function (index, elt) {
					let tippyInstance = elt._tippy;
					tippyInstance.setContent(sTooltipContent);
				});
			},
			_get_tooltip_content: function (sDataAttCode) {
				var oField = this._get_main_field_by_code(sDataAttCode);
				var sContent = '';
				if (oField != null) {
					sContent += '<div display:block;">'+oField.label+'</div>';
					for (var k in oField.subattr) {
						bChecked = ($.inArray(oField.subattr[k].code, this.aSelected) != -1);
						sContent += this._get_field_checkbox(oField.subattr[k].code, oField.subattr[k].label, false, bChecked, sDataAttCode);
					}
				}
				return sContent;
			},
			_get_field_checkbox: function (sCode, sLabel, bHasTooltip, bChecked, sParentId) {
				var sPrefix = 'tfs_'+this.sId+'_';
				sParentId = (sPrefix+sParentId).replace('.', '_');
				sElementId = (sPrefix+sCode).replace('.', '_');
				var aClasses = [];
				if (bHasTooltip) {
					aClasses.push('tfs_advanced');
					sLabel += ' [+]';
				}
				var sChecked = '';
				if (bChecked) {
					sChecked = ' checked ';
				}
				var sDataParent = '';
				if (sParentId != null) {
					sDataParent = ' data-parent="'+sParentId+'" ';
				}
				if (bHasTooltip) {
					sContent = '<div style="display:block; clear:both;"><span style="white-space: nowrap;"><input data-instance-id="'+this.sId+'" class="tfs_checkbox_multi" type="checkbox" id="'+sElementId+'_multi" value="'+sCode+'"'+sChecked+sDataParent+'><label data-attcode="'+sCode+'" class="'+aClasses.join(' ')+'" title="'+sCode+'">'+sLabel+'</label></div>';
				} else {
					sContent = '<div style="display:block; clear:both;"><span style="white-space: nowrap;"><input data-instance-id="'+this.sId+'" class="tfs_checkbox" type="checkbox" id="'+sElementId+'" value="'+sCode+'"'+sChecked+sDataParent+'><label data-attcode="'+sCode+'" class="'+aClasses.join(' ')+'" title="'+sCode+'" for="'+sElementId+'">'+sLabel+'</label></div>';
				}
				return sContent;
			}
		});
});
