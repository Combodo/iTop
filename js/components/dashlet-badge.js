/*
 * Copyright (C) 2013-2020 Combodo SARL
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

;
$(function () {
	$.widget('itop.dashlet_badge',
		{
			options: {},
			js_selectors:
				{
					dashletContainer: '[data-role="ibo-dashlet"]',
					dashletActions: '[data-role="ibo-dashlet-badge--actions"]',
					dashletActionList: '[data-role="ibo-dashlet-badge--action-list"]'
				},
			dashletContainer: null,

			_create: function () {
				this.dashletContainer = $(
					this.element
						.parents(this.js_selectors.dashletContainer)
						.get(-1)
				);

				this._setStyle();
				this._bindEvents();
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function () {
			},
			_setStyle: function () {
				this.dashletContainer.css("cursor", "pointer");
			},
			_bindEvents: function () {
				const me = this;

				this.dashletContainer.on('click', function (oEvent) {
					me._onComponentClick(oEvent);
				});
			},
			_onComponentClick: function (oEvent) {
				let $eventTarget = $(oEvent.target);

				if ($eventTarget.is(this.js_selectors.dashletActions)) {
					return;
				}

				let $listLink = $eventTarget
					.closest(this.js_selectors.dashletContainer)
					.find(this.js_selectors.dashletActionList);
				$listLink[0].click();
			}
		})
});
