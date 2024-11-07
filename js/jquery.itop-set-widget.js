/*
 *  Copyright (c) 2010-2024 Combodo SAS
 *
 *    This file is part of iTop.
 *
 *    iTop is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    iTop is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

/**
 * <p>To be applied on a field containing a JSON value. The value will be updated on every change.<br>
 * Exemple of JSON value :
 * <code>
 * {
 *   "possible_values": [
 *     {
 *       "code": "critical",
 *       "label": "Critical ticket"
 *     },
 *     {
 *       "code": "high",
 *       "label": "don't forget it !"
 *     },
 *     {
 *       "code": "normal",
 *       "label": "when time available"
 *     },
 *     {
 *       "code": "low",
 *       "label": "don't worry ;)"
 *     }
 *   ],
 *   "max_items_allowed": 20,
 *   "partial_values": [],
 *   "orig_value": [
 *     "critical"
 *   ],
 *   "added": [
 *     "normal",
 *     "high",
 *     "low"
 *   ],
 *   "removed": ["critical"]
 * }
 * </code>
 *
 * <p>Needs js/selectize.js already loaded !! (https://github.com/selectize/selectize.js)<br>
 * In the future we could use WebPack... Or a solution like this :
 * https://www.safaribooksonline.com/library/view/learning-javascript-design/9781449334840/ch13s09.html
 */
$.widget('itop.set_widget',
	{
		// default options
		options: {
			isDebug: false,
			inputWidgetIdSuffix: "-setwidget-values"
		},

		PARENT_CSS_CLASS: "attribute-set",
		ITEM_CSS_CLASS: "attribute-set-item",
		ITEM_PARTIAL_CSS_CLASS: "partial-code",

		POSSIBLE_VAL_KEY: 'possible_values',
		PARTIAL_VAL_KEY: "partial_values",
		ORIG_VAL_KEY: "orig_value",
		ADDED_VAL_KEY: "added",
		REMOVED_VAL_KEY: "removed",
		STATUS_ADDED: "added",
		STATUS_REMOVED: "removed",
        STATUS_NEUTRAL: "unchanged",
        MAX_ITEMS_ALLOWED_KEY: "max_items_allowed",

		possibleValues: null,
		partialValues: null,
		originalValue: null,
		/** will hold all interactions done : code as key and one of STATUS_* constant as value */
		setItemsCodesStatus: null,

		selectizeWidget: null,
		maxItemsAllowed: null,

		// the constructor
		_create: function () {
			var $this = this.element;

			this._initWidgetData($this.val());
			this._generateSelectionWidget($this);
			this._bindEvents($this);
		},

		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function () {
			this.refresh();
		},


		_initWidgetData: function (originalFieldValue) {
			var dataArray = JSON.parse(originalFieldValue),
			    setWidget = this;
			this.possibleValues = dataArray[this.POSSIBLE_VAL_KEY];
			this.partialValues = ($.isArray(dataArray[this.PARTIAL_VAL_KEY])) ? dataArray[this.PARTIAL_VAL_KEY] : [];
			this.originalValue = dataArray[this.ORIG_VAL_KEY];
			this.maxItemsAllowed = dataArray[this.MAX_ITEMS_ALLOWED_KEY];
			this.setItemsCodesStatus = {};

			// load existing removed codes
			//   used for example in triggers update fields selection, after switching class
			//   class A + fields a,b selected, then switch to class B : the server sends fields a,b to as removed values
			dataArray[this.REMOVED_VAL_KEY].forEach(function(setItemCode) {
                setWidget.setItemsCodesStatus[setItemCode] = setWidget.STATUS_REMOVED;
			});
		},

		_generateSelectionWidget: function ($widgetElement) {
			var $parentElement = $widgetElement.parent(),
				isWidgetElementDisabled = $widgetElement.prop("disabled"),
				inputId = $widgetElement.attr("id") + this.options.inputWidgetIdSuffix;

			$parentElement.append("<input id='" + inputId + "' value='" + this.originalValue.join(" ") + "'>");
			var $inputWidget = $("#" + inputId);
			if (isWidgetElementDisabled) {
				$inputWidget.prop("disabled", true);
			}

			// create closure to have both set widget and Selectize instances available in callbacks
			// selectize instance could also be retrieve on the source input DOM node (selectize property)
			// I think this is much clearer this way !
			var setWidget = this;

			$inputWidget.selectize({
				plugins: ['remove_button'],
				delimiter: ' ',
				maxItems: this.maxItemsAllowed,
				hideSelected: true,
				valueField: 'code',
				labelField: 'label',
				searchField: 'label',
				options: this.possibleValues,
				create: false,
				placeholder: Dict.S("Core:AttributeSet:placeholder"),
				inputClass: 'selectize-input ibo-input ibo-input-set ibo-input-selectize',
				// To avoid dropdown to be cut by the container's overflow hidden rule
				dropdownParent: 'body',
				onInitialize: function () {
					var selectizeWidget = this;
					setWidget._onInitialize(selectizeWidget);
				},
				onItemAdd: function (value, $item) {
					var selectizeWidget = this;
					setWidget._onTagAdd(value, $item, selectizeWidget);
				},
				onItemRemove: function (value) {
					var selectizeWidget = this;
					setWidget._onTagRemove(value, selectizeWidget);
				},
				onDropdownOpen: function (oDropdownElem) {
					oDropdownElem.addClass('set-dropdown');
					setWidget._updateDropdownPosition(this.$control, oDropdownElem);
				},
			});

			this.selectizeWidget = $inputWidget[0].selectize; // keeping this for set widget public methods
		},

        _bindEvents: function($widgetElement) {
            var setWidget = this;
			$widgetElement.bind("update", function() {
				if (setWidget.options.isDebug) {
					console.debug("update event in Selectize !", this);
				}
				var $this = $(this);
				if ($this.prop("disabled")) {
					setWidget.disable();
				} else {
					setWidget.enable();
				}
			});

			if (setWidget.options.isDebug)
			{
				console.debug("bindEvents", setWidget.selectizeWidget);
			}
			setWidget.selectizeWidget.$control.on('click', '.attribute-set-item.partial-code', function (event) {
				setWidget._onTagPartialClick(setWidget, this, event);
			})
		},

		refresh: function () {
			if (this.options.isDebug) {
				console.debug("refresh");
			}
			var widgetPublicData = {}, addedValues = [], removedValues = [];

			widgetPublicData[this.POSSIBLE_VAL_KEY] = this.possibleValues;
			widgetPublicData[this.PARTIAL_VAL_KEY] = this.partialValues;
			widgetPublicData[this.ORIG_VAL_KEY] = this.originalValue;

			for (var setItemCode in this.setItemsCodesStatus) {
				var setItemCodeStatus = this.setItemsCodesStatus[setItemCode];
				switch (setItemCodeStatus) {
					case this.STATUS_ADDED:
						addedValues.push(setItemCode);
						break;
					case this.STATUS_REMOVED:
						removedValues.push(setItemCode);
						break;
				}
			}
			widgetPublicData[this.ADDED_VAL_KEY] = addedValues;
			widgetPublicData[this.REMOVED_VAL_KEY] = removedValues;

			this.element.val(JSON.stringify(widgetPublicData, null, (this.options.isDebug ? 2 : null)));
		},

		disable: function () {
			this.selectizeWidget.disable();
		},

		enable: function () {
			this.selectizeWidget.enable();
		},

		/**
		 * <p>Updating selection widget :
		 * <ul>
		 *     <li>handles bulk edit disabling on widget opening
		 *     <li>adding specific CSS class to parent node
		 *     <li>adding specific CSS classes to item node
		 *     <li>items to have a specific rendering for partial codes.
		 * </ul>
		 *
		 * <p>For partial codes at first I was thinking about using the Selectize <code>render</code> callback, but it is called before <code>onItemAdd</code>/<code>onItemRemove</code> :(<br>
		 * Indeed as we only need to have partial items on first display, this callback is the right place O:)
		 *
		 * @param inputWidget Selectize object
		 * @private
		 */
		_onInitialize: function (inputWidget) {
            var setWidget = this;
			if (this.options.isDebug) {
				console.debug("onInit", inputWidget, setWidget);
			}

			if (inputWidget.$input.prop("disabled")) {
				inputWidget.disable(); // can't use this.selectizeWidget for now
			}

			inputWidget.$control.addClass(setWidget.PARENT_CSS_CLASS);

			inputWidget.items.forEach(function (setItemCode) {
				var $item = inputWidget.getItem(setItemCode);
				$item.addClass(setWidget.ITEM_CSS_CLASS);
				$item.addClass(setWidget.ITEM_CSS_CLASS + '-' + setItemCode); // no escape as codes are already pretty restrictive

				// Set text as tooltip in case it would be truncated
				$item.attr('data-tooltip-content', $item[0].childNodes[0].nodeValue.trim());

				if (setWidget._isCodeInPartialValues(setItemCode)) {
					inputWidget.getItem(setItemCode).addClass(setWidget.ITEM_PARTIAL_CSS_CLASS);
				}
			});
		},

		_onTagAdd: function (setItemCode, $item, inputWidget) {
			if (this.options.isDebug) {
				console.debug("tagAdd");
			}
			this.setItemsCodesStatus[setItemCode] = this.STATUS_ADDED;

			$item.addClass(this.ITEM_CSS_CLASS);

			// Set text as tooltip in case it would be truncated
			$item.attr('data-tooltip-content', $item[0].childNodes[0].nodeValue.trim());
			CombodoTooltip.InitTooltipFromMarkup($item);

			if (this._isCodeInPartialValues(setItemCode)) {
				this._partialCodeRemove(setItemCode);
			} else {
				if (this.originalValue.indexOf(setItemCode) !== -1) {
					// do not add if was present initially and removed
					this.setItemsCodesStatus[setItemCode] = this.STATUS_NEUTRAL;
				}
			}

			// When only one item allowed, selectize doesn't trigger the _onTagRemove callback so we have to clean ourselves.
			if((this.maxItemsAllowed === 1) && (this.originalValue.length > 0)) {
				if(setItemCode !== this.originalValue[0]) {
					this.setItemsCodesStatus[this.originalValue[0]] = this.STATUS_REMOVED;
				}
			}

			this.refresh();
		},

		_onTagRemove: function (setItemCode, inputWidget) {
			this.setItemsCodesStatus[setItemCode] = this.STATUS_REMOVED;

			if (this._isCodeInPartialValues(setItemCode)) {
				// force rendering items again, otherwise partial class will be kept
				// can'be in the onItemAdd callback as it is called after the render callback...
				inputWidget.clearCache("item");
			}

			if (this.originalValue.indexOf(setItemCode) === -1) {
				// do not remove if wasn't present initially
				this.setItemsCodesStatus[setItemCode] = this.STATUS_NEUTRAL;
			}

			this.refresh();
		},

		_onTagPartialClick: function (setWidget, inputWidgetItemNode, event) {
			var $targetNode = $(event.target),
				partialCodeClicked = $(inputWidgetItemNode).data("value");

			if (setWidget.options.isDebug)
			{
				console.debug("onTagPartialClick", setWidget, inputWidgetItemNode, event);
			}

			if (setWidget.selectizeWidget.isDisabled)
			{
				return;
			}
			if ($targetNode.is("a.remove"))
			{
				return;
			}

			this._onTagAdd(partialCodeClicked, $(inputWidgetItemNode), setWidget.selectizeWidget);
			$(inputWidgetItemNode).removeClass(setWidget.ITEM_PARTIAL_CSS_CLASS);
		},

		_partialCodeRemove: function (setItemCode) {
			this.partialValues = this.partialValues.filter(function (element, index, array) {
				var setItemCode = this.valueOf();
				return (element !== setItemCode);
			}, setItemCode);
		},

		_isCodeInPartialValues: function (setItemCode) {
			return (this.partialValues.indexOf(setItemCode) >= 0);
		},
		/**
		 * Update the dropdown's position so it always fits in the screen
		 *
		 * @param {object} oControlElem jQuery object representing the "control" input (= where the user types) of the external key
		 * @param {object} oDropdownElem jQuery object representing the results dropdown
		 * @return {void}
		 */
		_updateDropdownPosition: function (oControlElem, oDropdownElem) {
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
		}
	});