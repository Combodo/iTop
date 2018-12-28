// jQuery UI style "widget" for editing an image (file upload)

////////////////////////////////////////////////////////////////////////////////
//
// graph
//
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "dashboard" the widget name
	$.widget( "itop.edit_image",
		{
			// default options
			options: {
				input_name: '_image_input_',
				max_file_size: 0,
				max_width_px: 32,
				max_height_px: 32,
				current_image_url: '',
				default_image_url: '',
				labels: {
					reset_button: 'Reset',
					remove_button: 'Remove',
					upload_button: 'Upload'
				}
			},

			// the constructor
			_create: function () {
				var me = this;
				me.bLoadedEmpty = (me.options.current_image_url == '' || me.options.current_image_url == null);

				var sMarkup = '';
				sMarkup += '<input type="hidden" id="do_remove_' + me.options.input_name + '" name="' + me.options.input_name + '[remove]" value="0"/>';

				var sCssClasses = "view-image attribute-image";
				console.debug("edit_image", me.options.current_image_url);
				var sCssClassToAdd, sImageUrl;
				if (me.options.current_image_url === null)
				{
					sCssClassToAdd = "attribute-image-default";
					sImageUrl = me.options.default_image_url;
				}
				else
				{
					sCssClassToAdd = "attribute-image-custom";
					sImageUrl = me.options.current_image_url;
				}
				sCssClasses += ' '+sCssClassToAdd;
				sMarkup += '<div id="preview_'+me.options.input_name+'" class="'+sCssClasses+'" style="width: '+me.options.max_width_px+'px; height: '+me.options.max_height_px+'px;">';

				sMarkup += '<span class="helper-middle"></span>';
				sMarkup += '<img src="'+sImageUrl+'" data-original-src="'+sImageUrl+'" data-default-src="'+me.options.default_image_url+'" style="max-width: '+me.options.max_width_px+'px; max-height: '+me.options.max_height_px+'px">';
				sMarkup += '</div>';
				sMarkup += '<div id="buttons_' + me.options.input_name + '" class="edit-buttons">';
				sMarkup += '<div title="' + me.options.labels.reset_button + '" id="reset_' + me.options.input_name + '" class="button disabled"><div class="ui-icon ui-icon-arrowreturnthick-1-w"></div></div>';

				var sDisabled = me.bLoadedEmpty ? 'disabled' : '';
				var sLoadedDisabled = me.bLoadedEmpty ? 'yes' : 'no';
				sMarkup += '<div title="' + me.options.labels.remove_button + '" id="remove_' + me.options.input_name + '" data-loaded-disabled="' + sLoadedDisabled + '" class="button ' + sDisabled + '"><div class="ui-icon ui-icon-trash"></div></div>';
				sMarkup += '</div>';

				sMarkup += '<input type="hidden" name="MAX_FILE_SIZE" value="'+me.options.max_file_size+'" />';
				sMarkup += '<input class="file-input" title="' + me.options.labels.upload_button + '" name="' + me.options.input_name + '[fcontents]" type="file" id="file_' + me.options.input_name + '" />';

				this.element
					.addClass('edit-image')
					.append(sMarkup);

				$('#file_' + me.options.input_name).change(function () {

					$('#do_remove_' + me.options.input_name).val('0');

					me.previewImage(this, '#preview_' + me.options.input_name + ' img');

					var oImage = $('#preview_' + me.options.input_name + ' img');
					oImage.closest('.view-image').addClass('dirty');

					$('#reset_' + me.options.input_name).removeClass('disabled');
					$('#remove_' + me.options.input_name).removeClass('disabled');
				});
				$('#reset_' + me.options.input_name).click(function () {

					if ($(this).hasClass('disabled')) return;

					$('#do_remove_' + me.options.input_name).val('0');

					// Restore the image
					var oImage = $('#preview_' + me.options.input_name + ' img');
					oImage.attr('src', oImage.attr('data-original-src'));
					oImage.closest('.view-image').removeClass('dirty').removeClass('compat');

					// Reset the file input without losing events bound to it
					var oInput = $('#file_' + me.options.input_name);
					oInput.replaceWith(oInput.val('').clone(true));

					$('#reset_' + me.options.input_name).addClass('disabled');
					var oRemoveBtn = $('#remove_' + me.options.input_name);
					if (oRemoveBtn.attr('data-loaded-disabled') == 'yes') {
						oRemoveBtn.addClass('disabled');
					}
					else {
						oRemoveBtn.removeClass('disabled');
					}
				});
				$('#remove_' + me.options.input_name).click(function () {

					if ($(this).hasClass('disabled')) return;

					$('#do_remove_' + me.options.input_name).val('1');

					// Restore the default image
					var oImage = $('#preview_' + me.options.input_name + ' img');
					oImage.attr('src', oImage.attr('data-default-src'));
					oImage.closest('.view-image')
						.removeClass('compat')
						.addClass('dirty');

					// Reset the file input without losing events bound to it
					var oInput = $('#file_' + me.options.input_name);
					oInput.replaceWith(oInput.val('').clone(true));

					var oRemoveBtn = $('#remove_' + me.options.input_name);
					if (oRemoveBtn.attr('data-loaded-disabled') == 'yes') {
						$('#reset_' + me.options.input_name).addClass('disabled');
					}
					else {
						$('#reset_' + me.options.input_name).removeClass('disabled');
					}
					oRemoveBtn.addClass('disabled');
				});
			},
			// called when created, and later when changing options
			_refresh: function () {
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function () {
				this.element.removeClass('edit-image');
			},
			// _setOptions is called with a hash of all options that are changing
			_setOptions: function () {
				this._superApply(arguments);
			},
			// _setOption is called for each individual option that is changing
			_setOption: function (key, value) {
				this._superApply(arguments);
			},
			previewImage: function (input, sImageSelector) {
				if (input.files && input.files[0]) {
					if (window.FileReader) {
						var reader = new FileReader();

						reader.onload = function (e) {
							$(sImageSelector).attr('src', e.target.result);
						}

						reader.readAsDataURL(input.files[0]);
					}
					else {
						$(sImageSelector).closest('.view-image').addClass('compat');
					}
				}
				else {
					$(sImageSelector).closest('.view-image').addClass('compat');
				}
			}
	});
});
