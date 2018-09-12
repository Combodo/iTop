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
 * <p>To be applyed on a field containing a JSON value. The value will be updated on every change.<br>
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
 *   "removed": []
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

		possibleValues: null,
		partialValues: null,
		originalValue: null,
		/** will hold all interactions done : code as key and "added" or "removed" as value */
		tagSetCodesStatus: null,

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
			this.tagSetCodesStatus = {};
		},

		_generateTagSetField: function ($widgetElement) {
			var $parentElement = $widgetElement.parent(),
				inputId = $widgetElement.attr("id") + "-tagset-values";

			$parentElement.append("<input id='" + inputId + "' value='" + this.originalValue.join(" ") + "'>");
			var $inputWidget = $("#" + inputId);

			var tagSetWidget = this;
			$inputWidget.selectize({
				plugins: ['remove_button'],
				delimiter: ' ',
				maxItems: null,
				hideSelected: true,
				valueField: 'code',
				labelField: 'label',
				searchField: 'label',
				options: this.possibleValues,
				create: false,
				render: {
					item: function (data, escape) {
						return tagSetWidget._onRender(data, escape);
					}
				},
				onItemAdd: function (value, $item) {
					tagSetWidget._onTagAdd(value, $item);
				},
				onItemRemove: function (value) {
					tagSetWidget._onTagRemove(value, this);
					// selectize instance could also be retrieve on the original input (selectize property)
					// I think this is much clearer this way !
				}
			});
		},

		refresh: function () {
			var finalData = {}, addedValues = [], removedValues = [];

			finalData[this.POSSIBLE_VAL_KEY] = this.possibleValues;
			finalData[this.PARTIAL_VAL_KEY] = this.partialValues;
			finalData[this.ORIG_VAL_KEY] = this.originalValue;

			for (var tagSetCode in this.tagSetCodesStatus) {
				var tagSetCodeStatus = this.tagSetCodesStatus[tagSetCode];
				switch (tagSetCodeStatus) {
					case this.STATUS_ADDED:
						if (this.originalValue.indexOf(tagSetCode) === -1) {
							addedValues.push(tagSetCode);
						}
						break;
					case this.STATUS_REMOVED:
						if (this.originalValue.indexOf(tagSetCode) !== -1) {
							removedValues.push(tagSetCode);
						}
						break;
				}
			}
			finalData[this.ADDED_VAL_KEY] = addedValues;
			finalData[this.REMOVED_VAL_KEY] = removedValues;

			this.element.val(JSON.stringify(finalData, null, (this.options.isDebug ? 2 : null)));
		},

		_onRender: function (data, escape) {
			var itemCssClass = 'item',
				isPartialCode = (this._isCodeInPartialValues(data.code));

			if (isPartialCode) {
				itemCssClass += ' partial-code';
			}

			return '<div class="' + itemCssClass + '">' + escape(data.label) + '</div>';
		},

		_onTagAdd: function (value, $item) {
			this.tagSetCodesStatus[value] = this.STATUS_ADDED;
			this.refresh();
		},

		_onTagRemove: function (value, inputWidget) {
			this.tagSetCodesStatus[value] = this.STATUS_REMOVED;

			if (this._isCodeInPartialValues(value)) {
				inputWidget.clearCache("item");
				this.originalValue = this.originalValue.filter(item => (item !== value));
				this.partialValues = this.partialValues.filter(item => (item !== value));
			}

			this.refresh();
		},

		_isCodeInPartialValues: function (tagSetCode) {
			return (this.partialValues.indexOf(tagSetCode) >= 0);
		}
	});