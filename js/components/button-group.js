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
// Apply a listener to <body> element so we don't have to create one for every button on the page

// ibo-button-group elements
$('body').on('enter_loading_state.button_group.itop', '[data-role="ibo-button-group"]', function(){
		$(this).find('[data-role="ibo-button"]').each(function(){
			$(this).prop('disabled', true);
		});
		$(this).find('[data-role="ibo-button"]:first').trigger('enter_loading_state.button.itop');
})
.on('leave_loading_state.button_group.itop', '[data-role="ibo-button-group"]', function(){
	$(this).find('[data-role="ibo-button"]').each(function(){
		$(this).prop('disabled', false);
	});
	$(this).find('[data-role="ibo-button"]:first').trigger('leave_loading_state.button.itop');
});