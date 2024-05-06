/*
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

function ExportStartExport() {
	var oParams = {};
	oParams.operation = 'export_build_portal';
	oParams.format = sFormat;
	oParams.token = sToken;
	oParams.start = 1;
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function (data) {
			if (data == null) {
				ExportError('Export failed (no data provided), please contact your administrator');
			} else {
				ExportRun(data);
			}
		}, 'json')
		.fail(function (data) {
			ExportError('Export failed, please contact your administrator<br/>'+data.responseText);
		});
}

function ExportError(sMessage) {
	sDataState = 'error';
	$('#export-feedback').hide();
	$('#export-text-result').show();
	$('#export-error').html(sMessage);
}

function ExportRun(data) {
	switch (data.code) {
		case 'run':
			// Continue
			$('.progress').progressbar({value: data.percentage});
			$('.export-message').html(data.message);
			oParams = {};
			oParams.token = data.token;
			if (sDataState == 'cancelled') {
				oParams.operation = 'export_cancel';
				$('#export-cancel').hide();
				$('#export-close').show();
			} else {
				oParams.operation = 'export_build_portal';
			}

			$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function (data) {
					ExportRun(data);
				},
				'json');
			break;

		case 'done':
			sDataState = 'done';
			$('#export-cancel').hide();
			$('#export-close').show();
			$('.progress').progressbar({value: data.percentage});
			sMessage = '<a href="'+GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?operation=export_download&token='+data.token+'" target="_blank">'+data.message+'</a>';
			$('.export-message').html(sMessage);
			if (data.text_result != undefined) {
				if (data.mime_type == 'text/html') {
					$('#export-content').parent().html(data.text_result);
					$('#export-text-result').show();
				} else {
					if ($('#export-text-result').closest('ui-dialog').length == 0) {
						// not inside a dialog box, adjust the height... approximately
						var jPane = $('#export-text-result').closest('.ui-layout-content');
						var iTotalHeight = jPane.height();
						jPane.children(':visible').each(function () {
							if ($(this).attr('id') != '') {
								iTotalHeight -= $(this).height();
							}
						});
						$('#export-content').height(iTotalHeight-80);
					}
					$('#export-content').val(data.text_result);
					$('#export-text-result').show();
				}
			}
			break;

		case 'error':
			sDataState = 'error';
			$('#export-feedback').hide();
			$('#export-text-result').show();
			$('#export-error').html(data.message);
			$('#export-cancel').hide();
			$('#export-close').show();
			break;

		default:
	}
}
