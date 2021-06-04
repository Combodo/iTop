/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//
// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
//
var numberCachePages = 5;

$.fn.dataTable.pipeline = function (opts) {
	// Configuration options
	var conf = $.extend({
		pages: numberCachePages,     // number of pages to cache
		url: '',      // script url
		data: null,   // function or object with parameters to send to the server
	                  // matching how `ajax.data` works in DataTables
		method: 'GET' // Ajax HTTP method
	}, opts );

	// Private variables for storing the cache
	var cacheLower = -1;
	var cacheUpper = null;
	var cacheLastRequest = null;
	var cacheLastJson = null;
	var	draw_number = 1;

	return function ( request, drawCallback, settings ) {
		let message = Dict.S('UI:Datatables:Language:Processing');
		if (this.find('tbody').find('td').length == 0) {
			this.find('tbody').append('<tr class="ibo-dataTables--processing"><td>&#160;</td></tr>');
			this.find('tbody').block({
				message: message,
				css: {
					border: '0px '
				}
			});
			this.find('thead').block({
				message: '',
				css: {
					border: '0px '
				}
			});
		} else {
			this.find('tbody').block({
				message: '',
				css: {
					border: '0px '
				}
			});
			this.find('thead').block({
				message: message,
				css: {
					border: '0px ',
					top: '20px',
				}
			});
		}
		var ajax = false;
		var requestStart = request.start;
		var drawStart = request.start;
		var requestLength = request.length;
		if (request.start = undefined) {
			requestStart = settings._iDisplayStart;
			drawStart = settings._iDisplayStart;
			requestLength = settings._iDisplayLength;
		}
		var requestEnd = requestStart+requestLength;

		//Manage case requestLength=-1 => all the row are display 
		if (requestLength == -1) {
			requestLength = cacheLastJson.recordsTotal;
			if (cacheLower != 0 || cacheLastJson.recordsTotal > cacheUpper) {
				//new server request is mandatory
				ajax = true;
			}
		}

		if (settings.clearCache) {
			// API requested that the cache be cleared
			ajax = true;
			settings.clearCache = false;
		} else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
			// outside cached data - need to make a request
			ajax = true;
		} else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
			JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
			JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
		) {
			// properties changed (ordering, columns, searching)
			ajax = true;
		} else if (cacheLastJson == undefined || cacheLastJson.length == 0) {
			ajax = true;
		}

		// Store the request for checking next time around
		cacheLastRequest = $.extend( true, {}, request );

		if ( ajax ) {
			// Need data from the server
			if ( requestStart < cacheLower ) {
				requestStart = requestStart - (requestLength*(conf.pages-1));

				if ( requestStart < 0 ) {
					requestStart = 0;
				}
			}

			cacheLower = requestStart;
			cacheUpper = requestStart + (requestLength * conf.pages);

			request.start = requestStart;
			request.length = requestLength*conf.pages;
			request.end = requestStart+ requestLength*conf.pages;

			// Provide the same `data` options as DataTables.
			if ( typeof conf.data === 'function' ) {
				// As a function it is executed with the data object as an arg
				// for manipulation. If an object is returned, it is used as the
				// data object to submit
				var d = conf.data( request );
				if ( d ) {
					$.extend( request, d );
				}
			} else if ( $.isPlainObject( conf.data ) ) {
				// As an object, the data given extends the default
				$.extend( request, conf.data );
			}
			return $.ajax( {
				"type":     conf.method,
				"url":      conf.url,
				"data":     request,
				"dataType": "json",
				"cache":    false,
				"success":  function ( json ) {
					cacheLastJson = $.extend(true, {}, json);

					if (cacheLower != drawStart && requestLength != -1) {
						json.data.splice(0, drawStart-cacheLower);
					}
					if (requestLength >= -1) {
						json.data.splice(requestLength, json.data.length);
					}
					drawCallback(json);
				}
			} );
		} else {
			json = $.extend( true, {}, cacheLastJson );
			json.draw = request.draw; // Update the echo for each response
			json.data.splice( 0, requestStart-cacheLower );
			json.data.splice( requestLength, json.data.length );

			drawCallback(json);
		}
	}
};

// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register( 'clearPipeline()', function () {
	return this.iterator( 'table', function ( settings ) {
		settings.clearCache = true;
	} );
} );