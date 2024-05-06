/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
/*
* Plugin to change the behaviour of enter and backspace buttons
* if the inputText is null when iti push on enter, the field is put to null
* when we push on backspace, it clean the input text, in order to autocmplete
* */
Selectize.define('custom_itop', function(aOptions) {
	var KEY_BACKSPACE = 8;
	var KEY_RETURN = 13;
	var self = this;

	aOptions.text = aOptions.text || function (aOptions) {
		return aOptions[this.settings.labelField];
	};

	self.onKeyDown = (function () {
		var original = self.onKeyDown;
		return function (e) {
			var iIndex;
			switch (e.keyCode) {
				case KEY_BACKSPACE:
					if (this.$control_input.val() === '' && !this.$activeItems.length) {
						iIndex = this.caretPos-1;
						if (iIndex >= 0 && iIndex < this.items.length) {
							let sPreviousValue = this.options[this.items[iIndex]].search_label;
							this.clear(true);
							e.preventDefault();
							this.setTextboxValue(sPreviousValue.slice(0, -1));
							return;
						}
					}
				case KEY_RETURN:
					if (self.isOpen) {
						//case nothing selected ->delete selection
						if (!self.$activeOption || (self.currentResults.query === '' && !this.$control_input.val() === '')) {
							self.deleteSelection(e);
							self.setValue("");
							return;
						}
					}
			}
			return original.apply(this, arguments);
		};
	})();

	self.open = (function () {
		let original = self.open;
		return function () {
			ManageScroll(self);
			original.apply(self);
		}
	})();
	self.close = (function () {
		let original = self.close;
		return function () {
			StopManageScroll(self);
			original.apply(self);
		}
	})();

	ManageScroll = function (self) {
		let id = self.$input[0].id;
		if (self.$input.scrollParent()[0].tagName != 'HTML') {
			self.$input.scrollParent().on(['scroll.'+id, 'resize.'+id].join(" "), function () {
				setTimeout(function () {
					ManageScrollInElement(self);
				}, 50);

			});
			if (self.$input.scrollParent().scrollParent()[0].tagName != 'HTML') {
				self.$input.scrollParent().scrollParent().on(['scroll.'+id, 'resize.'+id].join(" "), function () {
					setTimeout(function () {
						ManageScrollInElement(self);
					}, 50);
				});
			}
		}
	};
	StopManageScroll = function (self) {
		let id = self.$input[0].id;
		if (self.$input.scrollParent()[0].tagName != 'HTML') {
			self.$input.scrollParent().off('scroll.'+id);
			self.$input.scrollParent().off('resize.'+id);
			if (self.$input.scrollParent().scrollParent()[0].tagName != 'HTML') {
				self.$input.scrollParent().scrollParent().off('scroll.'+id);
				self.$input.scrollParent().scrollParent().off('resize.'+id);
			}
		}
	};
	ManageScrollInElement = function (self) {
		if (self.isOpen) {
			if (self.$input.closest('.ibo-panel') != 'undefined' && self.$input.closest('.ibo-panel').find('.ibo-panel--header').first().outerHeight()+self.$input.closest('.ibo-panel').find('.ibo-panel--header').first().offset().top > self.$control_input.offset().top) {
				//field is not visible
				self.close();
			} else {
				self.positionDropdown.apply(self, arguments);
			}
		}
	};
});


function ExtKeyWidget(id, sTargetClass, sFilter, sTitle, bSelectMode, oWizHelper, sAttCode, bSearchMode, bDoSearch, sFormAttCode) {
	this.id = id;
	this.sOriginalTargetClass = sTargetClass;
	this.sTargetClass = sTargetClass;
	this.sFilter = sFilter;
	this.sTitle = sTitle;
	this.sAttCode = sAttCode;
	this.emptyHtml = ''; // content to be displayed when the search results are empty (when opening the dialog) 
	this.emptyOnClose = true; // Workaround for the JQuery dialog being very slow when opening and closing if the content contains many INPUT tags
	this.oWizardHelper = oWizHelper;
	this.ajax_request = null;
	this.bSelectMode = bSelectMode; // true if the edited field is a SELECT, false if it's an autocomplete
	this.bSearchMode = bSearchMode; // true if selecting a value in the context of a search form
	this.bDoSearch = bDoSearch; // false if the search is not launched
	this.sFormAttCode = sFormAttCode;

	var me = this;

	this.Init = function () {
		// make sure that the form is clean
		$('#'+this.id+'_btnRemove').prop('disabled', true);
		$('#'+this.id+'_linksToRemove').val('');

	}
	this.AddSelectize = function (options, initValue) {
		let $select = $('#'+me.id).selectize({
			plugins:['custom_itop', 'selectize-plugin-a11y'],
			render: {
				item: function (item) {
					if (item.obsolescence_flag == 1) {
						val = '<span class="object-ref-icon text_decoration"><span class="fas fa-eye-slash object-obsolete fa-1x fa-fw"></span></span>'+item.label;
					} else {
						val = item.label;
					}
					return $("<div title ='"+item.label+"'>").append(val);
				},
				option: function(item) {
					val = '';
					if (item.initials != undefined) {
						if (item.picture_url != undefined) {
							val = '<span class="ibo-input-select--autocomplete-item-image" style="background-image: url('+item.picture_url+');">'+item.initials+'</span>';
						} else {
							val = '<span class="ibo-input-select--autocomplete-item-image">'+item.initials+'</span>';
						}
					}
					val = val+'<span class="ibo-input-select--autocomplete-item-txt" title="'+item.label+'">';
					if (item.obsolescence_flag == 1) {
						val = val+'<span class="object-ref-icon text_decoration"><span class="fas fa-eye-slash object-obsolete fa-1x fa-fw"></span></span>'+item.label;
					} else {
						val = val+item.label;
					}
					if (item.additional_field != undefined) {
						val = val+'<br><i>'+item.additional_field+'</i>';
					}
					val = val+'</span>';
					return $("<div class=\"option ibo-input-select--autocomplete-item\" role=\"option\" id=\"${$item.text.replace(' ', '')}\">g").append(val);
				}
			},
			valueField: 'value',
			labelField: 'label',
			searchField: 'search_label',
			options: JSON.parse(options),
			maxItems: 1,
			copyClassesToDropdown: false,
			inputClass: 'ibo-input ibo-input-select ibo-input-selectize',
			// To avoid dropdown to be cut by the container's overflow hidden rule
			dropdownParent: 'body',
			onDropdownOpen: function (oDropdownElem) {
				me.UpdateDropdownPosition(this.$control, oDropdownElem);
			},
		});
		let $selectize = $select[0].selectize; // This stores the selectize object to a variable (with name 'selectize')
		$selectize.setValue(initValue, true);
		var iPaddingRight = $('#'+this.id).parent().find('.ibo-input-select--action-buttons')[0].childElementCount*20+15;
		 $('#'+this.id).parent().find('.ibo-input-select').css('padding-right',iPaddingRight);

	}
	this.AddAutocomplete = function(iMinChars, sWizHelperJSON)
	{
		var hasFocus = 0;
		var cache = {};
		$('#label_'+me.id).data('selected_value', $('#label_'+me.id).val());
		$('#label_'+me.id).attr('title', $('#label_'+me.id).val());
		$('#label_'+me.id).autocomplete({
				source: function (request, response) {
					term = request.term.toLowerCase().latinise().replace(/[\u0300-\u036f]/g, "");

					if (term in cache) {
						response(cache[term]);
						return;
					}
					if (term.indexOf(this.previous) >= 0 && cache[this.previous] != null && cache[this.previous].length < 120) {
						//we have already all the possibility in cache
						var data = [];
						$.each(cache[this.previous], function (key, value) {
							if (value.label.toLowerCase().latinise().replace(/[\u0300-\u036f]/g, "").indexOf(term) >= 0) {
								data.push(value);
							}
						});
						cache[term] = data;
						response(data);
					} else {
						$.post({
							url: GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
							dataType: "json",
							data: {
								q: request.term,
								operation: 'ac_extkey',
								sTargetClass: me.sTargetClass,
								sFilter: me.sFilter,
								bSearchMode: me.bSearchMode,
								sOutputFormat: 'json',
								json: function () {
									return sWizHelperJSON;
								}
							},
							success: function (data) {
								cache[term] = data;
								response(data);
							}
						});

					}
				},
				autoFocus: true,
				minLength: iMinChars,
				focus: function (event, ui) {
					return false;
				},
				select: function (event, ui) {
					$('#'+me.id).val(ui.item.value);
					let labelValue = $('<div>').html(ui.item.label).text();
					$('#label_'+me.id).val(labelValue);
					$('#label_'+me.id).data('selected_value', labelValue);
					$('#label_'+me.id).attr('title',labelValue);
					$('#'+me.id).trigger('validate');
					$('#'+me.id).trigger('extkeychange');
					$('#'+me.id).trigger('change');
					return false;
				},
				open: function (event, ui) {
					// dialog tries to move above every .ui-front with _moveToTop(), we want to be above our parent dialog
					var dialog = $(this).closest('.ui-dialog');
					if (dialog.length > 0) {
						$('.ui-autocomplete.ui-front').css('z-index', parseInt(dialog.css("z-index"))+1);
					}
					me.UpdateDropdownPosition($(this), $('.ui-autocomplete.selectize-dropdown:visible'));
					me.ManageScroll();
				},
				close: function (event, ui) {
					me.StopManageScroll();
				}
			})
		.autocomplete("instance")._renderItem = function (ul, item) {
			$(ul).addClass('selectize-dropdown');
			let term = this.term.replace("/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi", "\\$1");
			let val = '';
			if (item.initials != undefined) {
				if (item.picture_url != undefined) {
					val = '<span class="ibo-input-select--autocomplete-item-image" style="background-image: url('+item.picture_url+');">'+item.initials+'</span>';
				} else {
					val = '<span class="ibo-input-select--autocomplete-item-image");">'+item.initials+'</span>';
				}
			}
			val = val+'<div class="ibo-input-select--autocomplete-item-txt" title="'+item.label+'">';
			if (item.obsolescence_flag == '1') {
				val = val+' <span class="object-ref-icon text_decoration"><span class="fas fa-eye-slash object-obsolete fa-1x fa-fw"></span></span>';
			}
			let labelValue = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)("+term+")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
			val = val+labelValue;
			if (item.additional_field != undefined) {
				val = val+'<br><i>'+item.additional_field+'</i>';
			}
			val = val+'</div>';
			return $("<li>")
				.append("<div data-selectable=\"\" class=\"ibo-input-select--autocomplete-item\">"+val+"</div>")
				.appendTo(ul);
		};

		$('#label_'+me.id).on('focus', function () {
			// track whether the field has focus, we shouldn't process any
			// results if the field no longer has focus
			hasFocus++;
		}).on('blur', function () {
			hasFocus = 0;
			if ($('#label_'+me.id).val().length == 0) {
				eval('oACWidget_'+me.id).Clear();
			} else {
				$('#label_'+me.id).val($('#label_'+me.id).data('selected_value'));
			}
		}).on('click',
			function () {
				hasFocus++;
				$('#label_'+me.id).autocomplete("search");
			}).on('keyup',function () {
			if ($('#label_'+me.id).val().length == 0) {
				if (!$('#label_'+me.id).parent().find('.ibo-input-select--action-button--clear').hasClass('ibo-is-hidden')) {
					$('#label_'+me.id).parent().find('.ibo-input-select--action-button--clear').addClass('ibo-is-hidden');
				}
			} else {
				if ($('#label_'+me.id).parent().find('.ibo-input-select--action-button--clear').hasClass('ibo-is-hidden')) {
					$('#label_'+me.id).parent().find('.ibo-input-select--action-button--clear').removeClass('ibo-is-hidden');
				}
			}
		});

		var iPaddingRight = $('#'+this.id).parent().find('.ibo-input-select--action-buttons')[0].childElementCount * 20+15;
		$('#'+this.id).parent().find('.ibo-input-select').css('padding-right', iPaddingRight);
	};

	/**
	 * Update the dropdown's position so it always fits in the screen
	 *
	 * @param {object} oControlElem jQuery object representing the "control" input (= where the user types) of the external key
	 * @param {object} oDropdownElem jQuery object representing the results dropdown
	 * @return {void}
	 */
	this.UpdateDropdownPosition = function (oControlElem, oDropdownElem) {
		// First fix width to ensure it's not too long
		const fControlWidth = oControlElem.outerWidth();
		oDropdownElem.css('width', fControlWidth);

		// Then, fix height / position to ensure it's within the viewport
		const fWindowHeight = window.innerHeight;

		const fControlTopY = oControlElem.offset().top;
		const fControlHeight = oControlElem.outerHeight();

		const fDropdownTopY = oDropdownElem.offset().top;
		// This one is "let" as it might be updated if necessary
		let fDropdownHeight = oDropdownElem.outerHeight();
		const fDropdownBottomY = fDropdownTopY + fDropdownHeight;

		if (fDropdownBottomY > fWindowHeight) {
			// Set dropdown max-height to 1/3 of the screen, this way we are sure the dropdown will fit in either the top / bottom half of the screen
			oDropdownElem.css('max-height', '30vh');
			fDropdownHeight = oDropdownElem.outerHeight();

			// Position dropdown above input if not enough space on the bottom part of the screen
			if ((fDropdownTopY / fWindowHeight) > 0.6) {
				oDropdownElem.css('top', fDropdownTopY - fDropdownHeight - fControlHeight);
			}
		}
	};
	this.ManageScroll = function () {
		if ($('#label_'+me.id).scrollParent()[0].tagName != 'HTML') {
			$('#label_'+me.id).scrollParent().on(['scroll.'+me.id, 'resize.'+me.id].join(" "), function () {
				setTimeout(function () {
					me.ManageScrollInElement();
				}, 50);
			});
			if ($('#label_'+me.id).scrollParent().scrollParent()[0].tagName != 'HTML') {
				$('#label_'+me.id).scrollParent().scrollParent().on(['scroll.'+me.id, 'resize.'+me.id].join(" "), function () {
					setTimeout(function () {
						me.ManageScrollInElement();
					}, 50);
				});
			}
		}
	};

	this.StopManageScroll = function () {
		if ($('#label_'+me.id).scrollParent()[0].tagName != 'HTML') {
			$('#label_'+me.id).scrollParent().off('scroll.'+me.id);
			$('#label_'+me.id).scrollParent().off('resize.'+me.id);
			if ($('#label_'+me.id).scrollParent().scrollParent()[0].tagName != 'HTML') {
				$('#label_'+me.id).scrollParent().scrollParent().off('scroll.'+me.id);
				$('#label_'+me.id).scrollParent().scrollParent().off('resize.'+me.id);
			}
		}
	};
	this.ManageScrollInElement = function () {
		if ($('#label_'+me.id).data('ui-autocomplete').widget()[0].style.display === 'block') {
			if ($('#label_'+me.id).closest('.ibo-panel') != 'undefined' && $('#label_'+me.id).closest('.ibo-panel').find('.ibo-panel--header').first().outerHeight()+$('#label_'+me.id).closest('.ibo-panel').find('.ibo-panel--header').first().offset().top > $('#label_'+me.id).offset().top) {
				//field is not visible
				$('#label_'+me.id).autocomplete("close");
			} else {
				$('#label_'+me.id).autocomplete("search");
			}
		}
	};
	this.StopPendingRequest = function () {
		if (me.ajax_request) {
			me.ajax_request.abort();
			me.ajax_request = null;
		}
	};

	this.Search = function () {
		if ($('#'+me.id).prop('disabled')) {
			return;
		} // Disabled, do nothing
		var value = $('#'+me.id).val(); // Current value

		// Query the server to get the form to search for target objects
		if (me.bSelectMode) {
			$('#fstatus_'+me.id).html('<img src="../images/indicator.gif" />');
		} else {
			$('#label_'+me.id).addClass('ac_dlg_loading');
		}

		let sPromiseId = 'ajax_promise_'+me.id;
		let theMap = {
			sAttCode: me.sAttCode,
			iInputId: me.id,
			sTitle: me.sTitle,
			sAttCode: me.sAttCode,
			sTargetClass: me.sTargetClass,
			sFilter: me.sFilter,
			bSearchMode: me.bSearchMode,
			operation: 'objectSearchForm',
			ajax_promise_id: sPromiseId
		};

		if (me.oWizardHelper == null) {
			theMap['json'] = '';
		} else {
			// Not inside a "search form", updating a real object
			me.oWizardHelper.UpdateWizard();
			theMap['json'] = me.oWizardHelper.ToJSON();
		}

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();

		// Run the query and get the result back directly in HTML
		me.ajax_request = $.post(AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
			function (data) {
				$('#ac_dlg_'+me.id).html(data);
				window[sPromiseId].then(function () {
					$('#ac_dlg_'+me.id).dialog('open');
					me.UpdateSizes();
					me.UpdateButtons();
					me.ajax_request = null;
					$('#count_'+me.id+'_results').on('change', function () {
						me.UpdateButtons();
					});
					if (me.bDoSearch) {
						me.DoSearchObjects();
					}
				});
			},
			'html'
		);
	};

	/**
	 * Update the dialog size to fit into the screen
	 * @constructor
	 */
	this.UpdateSizes = function () {
		var dlg = $('#ac_dlg_'+me.id);
		if (dlg.width() > ($(window).width()-40)) {
			dlg.width($(window).width()-40);
		}
		if (dlg.height() > ($(window).height()-70)) {
			dlg.height($(window).height()-70);
		}
		var searchForm = dlg.find('div.display_block:first'); // Top search form, enclosing display_block
		var results = $('#dr_'+me.id);
		var oPadding = {};
		var aKeys = ['top', 'right', 'bottom', 'left'];
		for (k in aKeys) {
			oPadding[aKeys[k]] = 0;
			if (dlg.css('padding-'+aKeys[k])) {
				oPadding[aKeys[k]] = parseInt(dlg.css('padding-'+aKeys[k]).replace('px', ''));
			}
		}
		width = dlg.innerWidth()-oPadding['right']-oPadding['left']-22; // 5 (margin-left) + 5 (padding-left) + 5 (padding-right) + 5 (margin-right) + 2 for rounding !
		height = dlg.innerHeight()-oPadding['top']-oPadding['bottom']-22;
		form_height = searchForm.outerHeight();
		results.height(height-form_height-40); // Leave some space for the buttons
	};

	this.UpdateButtons = function () {
		var okBtn = $('#btn_ok_'+me.id+'_results');
		if ($('#count_'+me.id+'_results').val() > 0) {
			okBtn.prop('disabled', false);
		} else {
			okBtn.prop('disabled', true);
		}
	};

	this.DoSearchObjects = function (id) {
		var theMap = {
			sTargetClass: me.sTargetClass,
			iInputId: me.id,
			sFilter: me.sFilter,
			bSearchMode: me.bSearchMode
		};

		// Gather the parameters from the search form
		$('#fs_'+me.id+' :input').each(function () {
			if (this.name != '') {
				var val = $(this).val(); // supports multiselect as well
				if (val !== null) {
					theMap[this.name] = val;
				}
			}
		});

		if (me.oWizardHelper == null) {
			theMap['json'] = '';
		} else {
			// Not inside a "search form", updating a real object
			me.oWizardHelper.UpdateWizard();
			theMap['json'] = me.oWizardHelper.ToJSON();
		}

		theMap['sRemoteClass'] = theMap['class'];  // swap 'class' (defined in the form) and 'remoteClass'
		theMap.operation = 'searchObjectsToSelect'; // Override what is defined in the form itself
		theMap.sAttCode = me.sAttCode,

			sSearchAreaId = '#dr_'+me.id;
		$(sSearchAreaId).block();
		me.UpdateButtons();

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();

		// Run the query and display the results
		me.ajax_request = $.post(AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
			function (data) {
				$(sSearchAreaId).html(data);
				$('#fr_'+me.id+' input:radio').on('click', function () {
					me.UpdateButtons();
				});
				me.UpdateButtons();
				me.ajax_request = null;
				me.UpdateSizes();
			},
			'html'
		);

		return false; // Don't submit the form, stay in the current page !
	};

	this.DoOk = function () {
		var iObjectId = window['oSelectedItems'+me.id+'_results'][0];
		$('#ac_dlg_'+this.id).dialog('close');
		$('#label_'+this.id).addClass('ac_dlg_loading');


		// Query the server again to get the display name of the selected object
		var theMap = {
			sTargetClass: me.sTargetClass,
			iInputId: me.id,
			iObjectId: iObjectId,
			sAttCode: me.sAttCode,
			sFormAttCode: me.sFormAttCode,
			bSearchMode: me.bSearchMode,
			operation: 'getObjectName'
		};

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();

		// Run the query and get the result back directly in JSON
		me.ajax_request = $.post(AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
			function (data) {
				var oTemp = $('<div>'+data.name+'</div>');
				var txt = oTemp.text(); // this causes HTML entities to be interpreted

				var prevValue = $('#'+me.id).val();
				var newValue;
				if ($('#label_'+me.id).length) {
					newValue = iObjectId;
					$('#'+me.id).val(iObjectId);
					$('#label_'+me.id).val(txt);
					$('#label_'+me.id).data('selected_value', txt);
					$('#label_'+me.id).removeClass('ac_dlg_loading');
				} else {
					// N°3227 if no label_* field present, we just want to pick the attribute value !
					newValue = txt;
				}

				$('#'+me.id).val(newValue);
				if (prevValue != newValue) {
					// dependent fields will be updated using the WizardHelper JS object
					$('#'+me.id).trigger('validate');
					$('#'+me.id).trigger('extkeychange');
					$('#'+me.id).trigger('change');
				}

				if ($('#label_'+me.id).length) {
					$('#label_'+me.id).focus();
				} else {
					$('#'+me.id).focus();
				}

				me.ajax_request = null;
			},
			'json'
		);

		return false; // Do NOT submit the form in case we are called by OnSubmit...
	};

	this.Clear = function () {
		if (me.bSelectMode) {
			$('#'+me.id)[0].selectize.clear();
		} else {
			$('#'+me.id).val('');
			$('#label_'+me.id).val('');
			$('#label_'+me.id).data('selected_value', '');
			$('#'+me.id).trigger('validate');
			$('#'+me.id).trigger('extkeychange');
			$('#'+me.id).trigger('change');
		}
	};

// Workaround for a ui.jquery limitation: if the content of
// the dialog contains many INPUTs, closing and opening the
// dialog is very slow. So empty it each time.
	this.OnClose = function () {
		me.StopPendingRequest();
		if (me.bSelectMode) {
			$('#fstatus_'+me.id).html('');
		} else {
			$('#label_'+me.id).removeClass('ac_dlg_loading');
		}

		// called by the dialog, so in the context 'this' points to the jQueryObject
		if (me.emptyOnClose) {
			$('#dr_'+me.id).html(me.emptyHtml);
		}
		$('#label_'+me.id).removeClass('ac_dlg_loading');
		$('#label_'+me.id).focus();
		me.ajax_request = null;
	};

	this.SelectObjectClass = function (oWizHelper) {
		// Resetting target class to its original value
		// (If not done, closing the dialog and trying to create a object again
		// will force it be of the same class as the previous call)
		me.sTargetClass = me.sOriginalTargetClass;

		me.CreateObject();
	};

	this.DoSelectObjectClass = function () {
		// Retrieving selected value
		var oSelectedClass = $('#ac_create_'+me.id+' select');
		if (oSelectedClass.length !== 1) {
			return;
		}

		// Setting new target class
		me.sTargetClass = oSelectedClass.val();
		// Opening real creation form
		me.CreateObject(true);
		$('#ac_create_'+me.id).dialog('close');
	};

	/**
	 * Extract transaction id of the root object edited.
	 * When create/update a new object via external key,
	 * this transaction id reflects the root form transaction id an not the current form transaction id.
	 *
	 * @constructor
	 */
	this.GetRootTransactionId = function(){
		// Retrieve the object form
		const oForm = $(`#${me.id}`).closest('form');
		// If root transaction id exist, then use it
		let oFieldTransaction = $('input[name=root_transaction_id]', oForm);
		if(oFieldTransaction.length === 0){
			// otherwise, use the object form transaction id
			oFieldTransaction = $('input[name=transaction_id]', oForm);
		}
		return oFieldTransaction.val();
	}

	this.CreateObject = function (bTargetClassSelected) {
		if ($('#'+me.id).prop('disabled')) {
			return;
		} // Disabled, do nothing
		// Query the server to get the form to create a target object
		if (me.bSelectMode) {
			$('#fstatus_'+me.id).html('<img src="../images/indicator.gif" />');
		} else {
			$('#label_'+me.id).addClass('ac_dlg_loading');
		}
		me.oWizardHelper.UpdateWizard();
		var sPromiseId = 'ajax_promise_'+me.id;
		var theMap = {
			sTargetClass: me.sTargetClass,
			iInputId: me.id,
			sAttCode: me.sAttCode,
			'json': me.oWizardHelper.ToJSON(),
			operation: 'objectCreationForm',
			ajax_promise_id: sPromiseId,
			bTargetClassSelected: bTargetClassSelected
		};

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();

		// Run the query and get the result back directly in HTML
		var sLocalTargetClass = me.sTargetClass; // Remember the target class since it will be reset when closing the dialog

		// Handle transaction id
		const sRootFormTransactionId = me.GetRootTransactionId();

		me.ajax_request = $.post(AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
			function (data) {
				$('#ajax_'+me.id).html(data);
				window[sPromiseId].then(function () {
					$('#ac_create_'+me.id).dialog('open');
					$('#ac_create_'+me.id).dialog("option", "close", me.OnCloseCreateObject);
					// Modify the action of the cancel button
					$('#ac_create_'+me.id+' button.cancel').off('click').on('click', me.CloseCreateObject);
					me.ajax_request = null;
					me.sTargetClass = sLocalTargetClass;
					// Adjust the dialog's size to fit into the screen
					if ($('#ac_create_'+me.id).width() > ($(window).width()-40)) {
						$('#ac_create_'+me.id).width($(window).width()-40);
					}
					if ($('#ac_create_'+me.id).height() > ($(window).height()-70)) {
						$('#ac_create_'+me.id).height($(window).height()-70);
					}
					// Add root_transaction_id
					$('#ac_create_'+me.id+' form').append(`<input type="hidden" name="root_transaction_id" value="${sRootFormTransactionId}"/>`)
				});
			},
			'html'
		);
	};

	this.CloseCreateObject = function () {
		$('#ac_create_'+me.id).dialog("close");
	};

	this.OnCloseCreateObject = function () {
		if (me.bSelectMode) {
			$('#fstatus_'+me.id).html('');
		} else {
			$('#label_'+me.id).removeClass('ac_dlg_loading');
		}
		$('#label_'+me.id).focus();
		$('#ac_create_'+me.id).dialog("destroy");
		$('#ac_create_'+me.id).remove();
		$('#ajax_'+me.id).html('');
		// Resetting target class to its original value
		// (If not done, closing the dialog and trying to create a object again
		// will force it be of the same class as the previous call)
		me.sTargetClass = me.sOriginalTargetClass;
	};

	this.DoCreateObject = function () {
		var sFormId = $('#dcr_'+me.id+' form').attr('id');
		if (CheckFields(sFormId, true)) {
			$('#'+sFormId).block();
			var theMap = {
				sTargetClass: me.sTargetClass,
				iInputId: me.id,
				sAttCode: me.sAttCode,
				'json': me.oWizardHelper.ToJSON()
			};

			// Gather the values from the form
			// Gather the parameters from the search form
			$('#'+sFormId+' :input').each(
				function (i) {
					if (this.name != '') {
						if ($(this).hasClass('htmlEditor')) {
							var sId = $(this).attr('id');
							CombodoCKEditorHandler.DeleteInstance(sId);
							if ($('#'+sId).data('timeout_validate') != undefined) {
								clearInterval($('#'+sId).data('timeout_validate'));
							}
						}

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
			me.StopPendingRequest();

			// Run the query and get the result back directly in JSON
			me.ajax_request = $.post(AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
				function (data) {
					$('#fstatus_'+me.id).html('');
					if (data.id == 0) {
						$('#label_'+me.id).removeClass('ac_dlg_loading');
						CombodoModal.OpenErrorModal(data.error);
					} else if (me.bSelectMode) {
						// Add the newly created object to the drop-down list and select it
						/*$('<option/>', { value : data.id }).html(data.name).appendTo('#'+me.id);
						$('#'+me.id+' option[value="'+data.id+'"]').attr('selected', 'selected');
						$('#'+me.id).focus();*/
						var select = $('#'+me.id)[0].selectize;
						select.addOption({label: data.name, value: data.id});
						select.setValue(data.id);
					} else {
						// Put the value corresponding to the newly created object in the autocomplete
						var oTemp = $('<div>'+data.name+'</div>');
						var txt = oTemp.text(); // this causes HTML entities to be interpreted
						$('#'+me.id).val(data.id);
						$('#label_'+me.id).val(txt);
						$('#label_'+me.id).data('selected_value',txt);
						$('#label_'+me.id).removeClass('ac_dlg_loading');
						$('#label_'+me.id).focus();
					}
					$('#'+me.id).trigger('validate');
					$('#'+me.id).trigger('extkeychange');
					$('#'+me.id).trigger('change');
					me.ajax_request = null;
				},
				'json'
			);
		}
		return false; // do NOT submit the form
	};

	this.Update = function () {
		if ($('#'+me.id).prop('disabled')) {
			$('#v_'+me.id).html('');
			$('#label_'+me.id).prop('disabled', 'disabled');
			$('#label_'+me.id).css({'background': 'transparent'});
			$('#mini_add_'+me.id).hide();
			$('#mini_tree_'+me.id).hide();
			$('#mini_search_'+me.id).hide();
		} else {
			$('#label_'+me.id).prop('disabled', false);
			$('#label_'+me.id).css({'background': '#fff url(../images/ac-background.gif) no-repeat right'});
			$('#mini_add_'+me.id).show();
			$('#mini_tree_'+me.id).show();
			$('#mini_search_'+me.id).show();
		}
	};

	this.HKDisplay = function () {
		var theMap = {
			sTargetClass: me.sTargetClass,
			sInputId: me.id,
			sFilter: me.sFilter,
			bSearchMode: me.bSearchMode,
			sAttCode: me.sAttCode,
			value: $('#'+me.id).val()
		};

		if (me.bSelectMode) {
			$('#fstatus_'+me.id).html('<img src="../images/indicator.gif" />');
		} else {
			$('#label_'+me.id).addClass('ac_dlg_loading');
		}
		if (me.oWizardHelper == null) {
			theMap['json'] = '';
		} else {
			// Not inside a "search form", updating a real object
			me.oWizardHelper.UpdateWizard();
			theMap['json'] = me.oWizardHelper.ToJSON();
		}

		theMap['sRemoteClass'] = me.sTargetClass;
		theMap.operation = 'displayHierarchy';

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();

		// Run the query and display the results
		me.ajax_request = $.post(AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
			function (data) {
				$('#ac_tree_'+me.id).html(data);
				var maxHeight = $(window).height()-110;
				$('#tree_'+me.id).css({maxHeight: maxHeight});
			},
			'html'
		);
	};

	this.OnHKResize = function (event, ui) {
		var dh = ui.size.height-ui.originalSize.height;
		if (dh != 0) {
			var dlg_content = $('#dlg_tree_'+me.id+' .wizContainer');
			var h = dlg_content.height();
			dlg_content.height(h+dh);
			var tree = $('#tree_'+me.id);
			var h = tree.height();
			tree.height(h+dh-1);
		}
	};

	this.OnHKClose = function () {
		if (me.bSelectMode) {
			$('#fstatus_'+me.id).html('');
		} else {
			$('#label_'+me.id).removeClass('ac_dlg_loading');
		}
		$('#label_'+me.id).focus();
		$('#dlg_tree_'+me.id).dialog("destroy");
		$('#dlg_tree_'+me.id).remove();
	};

	this.DoHKOk = function () {
		iObjectId = $('#tree_'+me.id+' input[name=selectObject]:checked').val();

		$('#dlg_tree_'+me.id).dialog('close');

		// Query the server again to get the display name of the selected object
		var theMap = {
			sTargetClass: me.sTargetClass,
			iInputId: me.id,
			iObjectId: iObjectId,
			sAttCode: me.sAttCode,
			bSearchMode: me.bSearchMode,
			operation: 'getObjectName'
		};

		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		me.StopPendingRequest();
		if ($('#label_'+me.id).size() == 0) {
			var prevValue = $('#'+me.id)[0].selectize.getValue();
			$('#'+me.id)[0].selectize.setValue(iObjectId);
		} else {
			// Run the query and get the result back directly in JSON
			me.ajax_request = $.post(AddAppContext(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php'), theMap,
				function (data) {
					var oTemp = $('<div>'+data.name+'</div>');
					var txt = oTemp.text(); // this causes HTML entities to be interpreted

					$('#label_'+me.id).val(txt);
					$('#label_'+me.id).removeClass('ac_dlg_loading');
					$('#label_'+me.id).data('selected_value',txt);

					var prevValue = $('#'+me.id).val();
					$('#'+me.id).val(iObjectId);
					if (prevValue != iObjectId) {
						$('#'+me.id).trigger('validate');
						$('#'+me.id).trigger('extkeychange');
						$('#'+me.id).trigger('change');
					}
					if ($('#'+me.id).hasClass('multiselect')) {
						$('#'+me.id+' option').each(function () {
							this.selected = ($(this).attr('value') == iObjectId);
						});
						$('#'+me.id).multiselect('refresh');
					}
					$('#label_'+me.id).focus();
					me.ajax_request = null;
				},
				'json'
			);
		}
		return false; // Do NOT submit the form in case we are called by OnSubmit...
	};

}