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

/*
 * This NEEDS the latinise/latinise.min.js to work.
 * 
 * Sets a particular search function on the DataTables to neutralise accents while filtering
 * Works only for string|html type columns
 */

$.fn.DataTable.ext.type.search.html = function(data){
	return (!data) ? '' : ( (typeof data === 'string') ? data.latinise() : data );
};
$.fn.DataTable.ext.type.search.string = function(data){
	return (!data) ? '' : ( (typeof data === 'string') ? data.latinise() : data );
};