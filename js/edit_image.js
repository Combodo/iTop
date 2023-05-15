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
					upload_button: 'Upload',
				}
			},

			// the constructor
			_create: function () {
				this.element.addClass('ibo-input-image');

				this._buildMarkup();
				this._bindEvents();
			},
			// called when created, and later when changing options
			_refresh: function () {
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function () {
				this.element.removeClass('ibo-input-image');
			},
			// _setOptions is called with a hash of all options that are changing
			_setOptions: function () {
				this._superApply(arguments);
			},
			// _setOption is called for each individual option that is changing
			_setOption: function (key, value) {
				this._superApply(arguments);
			},

			_buildMarkup: function () {
				CombodoJSConsole.Debug('edit_image: '+this.options.current_image_url);

				this.bLoadedEmpty = (this.options.current_image_url == '' || this.options.current_image_url == null);

				const sDisabled = this.bLoadedEmpty ? 'disabled' : '';
				const sLoadedDisabled = this.bLoadedEmpty ? 'yes' : 'no';
				let sCssClasses = "ibo-input-image--image-view attribute-image";
				let sCssClassToAdd, sImageUrl;

				if (this.options.current_image_url === null) {
					sCssClassToAdd = "attribute-image-default";
					sImageUrl = this.options.default_image_url;
				} else {
					sCssClassToAdd = "attribute-image-custom";
					sImageUrl = this.options.current_image_url;
				}
				sCssClasses += ' '+sCssClassToAdd;

				let sMarkup = `
				<input type="hidden" id="do_remove_${this.options.input_name}" name="${this.options.input_name}[remove]" value="0"/>
				<div id="preview_${this.options.input_name}" class="${sCssClasses}" data-role="ibo-input-image--image-view" style="max-width: ${this.options.max_width_px}px; max-height: ${this.options.max_height_px}px; aspect-ratio: ${this.options.max_width_px} / ${this.options.max_height_px};">
					<img src="${sImageUrl}" data-original-src="${sImageUrl}" data-default-src="${this.options.default_image_url}" style="max-width: min(${this.options.max_width_px}px,100%); max-height: min(${this.options.max_height_px}px,100%)">
					<input id="file_${this.options.input_name}" name="${this.options.input_name}[fcontents]" type="file" />
				</div>
				<div id="buttons_${this.options.input_name}" class="ibo-input-image--edit-buttons" data-role="ibo-input-image--edit-buttons">
					<button id="upload_${this.options.input_name}" class="ibo-button ibo-is-alternative ibo-is-neutral" data-role="ibo-button" type="button" data-tooltip-content="${this.options.labels.upload_button}" data-tooltip-placement="right">
						<span class="fas fa-cloud-upload-alt"></span>
					</button>
					<button id="reset_${this.options.input_name}" class="ibo-button ibo-is-alternative ibo-is-neutral" data-role="ibo-button" type="button" data-tooltip-content="${this.options.labels.reset_button}" data-tooltip-placement="right" disabled>
						<span class="fas fa-undo-alt"></span>
					</button>
					<button id="remove_${this.options.input_name}" class="ibo-button ibo-is-alternative ibo-is-danger" data-role="ibo-button" type="button" data-tooltip-content="${this.options.labels.remove_button}" data-tooltip-placement="right" data-loaded-disabled="${sLoadedDisabled}" ${sDisabled}>
						<span class="fas fa-trash"></span>
					</button>
				</div>
				<input type="hidden" name="MAX_FILE_SIZE" value="${this.options.max_file_size}" />
				`;

				this.element.append(sMarkup);

				CombodoTooltip.InitAllNonInstantiatedTooltips(this.element);
			},
			_bindEvents: function () {
				const me = this;

				$('#file_'+me.options.input_name).on('change', function () {
					$('#do_remove_'+me.options.input_name).val('0');

					me.previewImage(this, '#preview_'+me.options.input_name+' img');

					let oImage = $('#preview_'+me.options.input_name+' img');
					oImage.closest('.ibo-input-image--image-view').addClass('dirty');

					$('#reset_'+me.options.input_name).prop('disabled', false);
					$('#remove_'+me.options.input_name).prop('disabled', false);
				});

				$('#upload_'+me.options.input_name).on('click', function () {
					$('#file_'+me.options.input_name).trigger('click');
				});

				$('#reset_'+me.options.input_name).on('click', function () {
					if ($(this).prop('disabled')) {
						return;
					}

					$('#do_remove_'+me.options.input_name).val('0');

					// Restore the image
					let oImage = $('#preview_'+me.options.input_name+' img');
					oImage.attr('src', oImage.attr('data-original-src'));
					oImage.closest('.ibo-input-image--image-view').removeClass('dirty');

					// Reset the file input without losing events bound to it
					let oInput = $('#file_'+me.options.input_name);
					oInput.replaceWith(oInput.val('').clone(true));

					$('#reset_'+me.options.input_name).prop('disabled', true);
					let oRemoveBtn = $('#remove_'+me.options.input_name);
					if (oRemoveBtn.attr('data-loaded-disabled') == 'yes') {
						oRemoveBtn.prop('disabled', true);
					} else {
						oRemoveBtn.prop('disabled', false);
					}
				});

				$('#remove_'+me.options.input_name).on('click', function () {
					if ($(this).prop('disabled')) {
						return;
					}

					$('#do_remove_'+me.options.input_name).val('1');

					// Restore the default image
					let oImage = $('#preview_'+me.options.input_name+' img');
					oImage.attr('src', oImage.attr('data-default-src'));
					oImage.closest('.ibo-input-image--image-view')
						.addClass('dirty');

					// Reset the file input without losing events bound to it
					let oInput = $('#file_'+me.options.input_name);
					oInput.replaceWith(oInput.val('').clone(true));

					let oRemoveBtn = $('#remove_'+me.options.input_name);
					if (oRemoveBtn.attr('data-loaded-disabled') == 'yes') {
						$('#reset_'+me.options.input_name).prop('disabled', true);
					} else {
						$('#reset_'+me.options.input_name).prop('disabled', false);
					}
					oRemoveBtn.prop('disabled', true);
				});
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
				}
			}
	});
});
