/*
 *  Copyright (c) 2010-2018 Combodo SARL
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
 * In the future we could use a solution like this :
 * https://www.safaribooksonline.com/library/view/learning-javascript-design/9781449334840/ch13s09.html
 */
$.widget('itop.tagset_widget',
	{
		// default options
		options: {isDebug: false},

		POSSIBLE_VAL_KEY: 'possible_values',
		PARTIAL_VAL_KEY: "partial_values",
		ORIG_VAL_KEY: "orig_value",
		ADDED_VAL_KEY: "added",
		REMOVED_VAL_KEY: "removed",
		STATUS_ADDED: "added",
		STATUS_REMOVED: "removed",
        STATUS_NEUTRAL: "unchanged",
        MAX_TAGS_ALLOWED: "max_tags_allowed",

		possibleValues: null,
		partialValues: null,
		originalValue: null,
		/** will hold all interactions done : code as key and one of STATUS_* constant as value */
		tagSetCodesStatus: null,

		selectizeWidget: null,
		maxTagsAllowed: null,

		// the constructor
		_create: function () {
			var $this = this.element;

			this._initWidgetData($this.val());
			this._generateTagSetField($this);
		},

		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function () {
			this.refresh();
		},


		_initWidgetData: function (originalFieldValue) {
			var dataArray = JSON.parse(originalFieldValue);
			this.possibleValues = dataArray[this.POSSIBLE_VAL_KEY];
			this.partialValues = ($.isArray(dataArray[this.PARTIAL_VAL_KEY])) ? dataArray[this.PARTIAL_VAL_KEY] : [];
			this.originalValue = dataArray[this.ORIG_VAL_KEY];
			this.maxTagsAllowed = dataArray[this.MAX_TAGS_ALLOWED];
			this.tagSetCodesStatus = {};
		},

		_generateTagSetField: function ($widgetElement) {
			var $parentElement = $widgetElement.parent(),
				inputId = $widgetElement.attr("id") + "-tagset-values";

			$parentElement.append("<input id='" + inputId + "' value='" + this.originalValue.join(" ") + "'>");
			var $inputWidget = $("#" + inputId);

			// create closure to have both tagset widget and Selectize instances available in callbacks
			// selectize instance could also be retrieve on the source input DOM node (selectize property)
			// I think this is much clearer this way !
			var tagSetWidget = this;

			$inputWidget.selectize({
				plugins: ['remove_button'],
				delimiter: ' ',
				maxItems: this.maxTagsAllowed,
				hideSelected: true,
				valueField: 'code',
				labelField: 'label',
				searchField: 'label',
				options: this.possibleValues,
				create: false,
				placeholder: Dict.S("Core:AttributeTagSet:placeholder"),
				onInitialize: function () {
					var selectizeWidget = this;
					tagSetWidget._onInitialize(selectizeWidget);
				},
				onItemAdd: function (value, $item) {
					var selectizeWidget = this;
					tagSetWidget._onTagAdd(value, $item, selectizeWidget);
				},
				onItemRemove: function (value) {
					var selectizeWidget = this;
					tagSetWidget._onTagRemove(value, selectizeWidget);
				}
			});

			this.selectizeWidget = $inputWidget[0].selectize; // keeping this for widget public methods
		},

		refresh: function () {
			if (this.options.isDebug) {
				console.debug("refresh");
			}
			var widgetPublicData = {}, addedValues = [], removedValues = [];

			widgetPublicData[this.POSSIBLE_VAL_KEY] = this.possibleValues;
			widgetPublicData[this.PARTIAL_VAL_KEY] = this.partialValues;
			widgetPublicData[this.ORIG_VAL_KEY] = this.originalValue;

			for (var tagSetCode in this.tagSetCodesStatus) {
				var tagSetCodeStatus = this.tagSetCodesStatus[tagSetCode];
				switch (tagSetCodeStatus) {
					case this.STATUS_ADDED:
						addedValues.push(tagSetCode);
						break;
					case this.STATUS_REMOVED:
						removedValues.push(tagSetCode);
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
		 * Updating items to have a specific rendering for partial codes.<br>
		 *     At first I was thinking about using the Selectize render callback, but it is called before onItemAdd/onItemRemove :(<br>
		 *     Indeed as we only need to have partial items on first display, this callback is the right place O:)
		 * @param inputWidget Selectize object
		 * @private
		 */
		_onInitialize: function (inputWidget) {
			if (this.options.isDebug) {
				console.debug("onInit", this);
			}
			var tagSetWidget = this;
			inputWidget.items.forEach(function (tagSetCode) {
				if (tagSetWidget._isCodeInPartialValues(tagSetCode)) {
					inputWidget.getItem(tagSetCode).addClass("partial-code");
				}
			});
		},

		_onTagAdd: function (tagSetCode, $item, inputWidget) {
			if (this.options.isDebug) {
				console.debug("tagAdd");
			}
			this.tagSetCodesStatus[tagSetCode] = this.STATUS_ADDED;

			if (this._isCodeInPartialValues(tagSetCode)) {
				this.partialValues = this.partialValues.filter(item => (item !== tagSetCode));
			} else {
				if (this.originalValue.indexOf(tagSetCode) !== -1) {
					// do not add if was present initially and removed
					this.tagSetCodesStatus[tagSetCode] = this.STATUS_NEUTRAL;
				}
			}

			this.refresh();
		},

		_onTagRemove: function (tagSetCode, inputWidget) {
			this.tagSetCodesStatus[tagSetCode] = this.STATUS_REMOVED;

			if (this._isCodeInPartialValues(tagSetCode)) {
				// force rendering items again, otherwise partial class will be kept
				// can'be in the onItemAdd callback as it is called after the render callback...
				inputWidget.clearCache("item");
			}

			if (this.originalValue.indexOf(tagSetCode) === -1) {
				// do not remove if wasn't present initially
				this.tagSetCodesStatus[tagSetCode] = this.STATUS_NEUTRAL;
			}

			this.refresh();
		},

		_isCodeInPartialValues: function (tagSetCode) {
			return (this.partialValues.indexOf(tagSetCode) >= 0);
		}
	});