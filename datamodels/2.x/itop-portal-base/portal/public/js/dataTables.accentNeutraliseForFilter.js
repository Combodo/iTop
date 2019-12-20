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