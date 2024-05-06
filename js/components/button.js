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

// ibo-button elements
$('body').on('enter_loading_state.button.itop', '[data-role="ibo-button"]', function(){
	$(this).addClass('ibo-is-loading').prop('disabled', true);
})
.on('leave_loading_state.button.itop', '[data-role="ibo-button"]', function(){
	$(this).removeClass('ibo-is-loading').prop('disabled', false);
});
