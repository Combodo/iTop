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

;
$(function () {
	$.widget('itop.dashlet_badge',
		{
			options: {},
			js_selectors:
				{
					dashlet_container: '[data-role="ibo-dashlet"]',
					dashlet_actions: '[data-role="ibo-dashlet-badge--actions"]',
					dashlet_action_list: '[data-role="ibo-dashlet-badge--action-list"]'
				},
			$dashlet_container: null,

			_create: function () {
				this.$dashlet_container = $(
					this.element
						.parents(this.js_selectors.dashlet_container)
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
				this.$dashlet_container.css("cursor", "pointer");
			},
			_bindEvents: function () {
				const me = this;

				this.$dashlet_container.on('click', function (oEvent) {
					me._onComponentClick(oEvent);
				});
			},
			_onComponentClick: function (oEvent) {
				let $eventTarget = $(oEvent.target);

				if ($eventTarget.is(this.js_selectors.dashlet_actions)) {
					return;
				}

				let $listLink = $eventTarget
					.closest(this.js_selectors.dashlet_container)
					.find(this.js_selectors.dashlet_action_list);
				$listLink[0].click();
			}
		})
});
