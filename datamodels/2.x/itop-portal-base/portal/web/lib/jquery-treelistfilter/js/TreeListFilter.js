/**
 * Tree List Filter jQuery plugin 1.0
 * 
 * Copyright (c) 2014, AIWSolutions
 * License: GPL2
 * Project Website: http://wiki.aiwsolutions.net/dOQKO
 **/

// WARNING : GLA 2016-02-19 : We have altered the lib as it was no longer maintained nor flexible enough
// - Added callback
// - Added latinisation of the search (accents removing)

jQuery.fn.treeListFilter = function(list, timeout, callback) {
	var list = jQuery(list);
	var input = this;
	var keyTimeout;
	var lastFilter = '';
	
	// Default timeout	
	if (timeout === undefined) {
		timeout = 200;
	}
	// GLA : Default callback	
	if (callback === undefined) {
		callback = function(){ return null; };
	}
	
	function filterList(ulObject, filterValue) {
		if (!ulObject.is('ul') && !ulObject.is('ol')) {
			return false;
		}
		var children = ulObject.children();
		var result = false;
		for (var i = 0; i < children.length; i++) {
			var liObject = jQuery(children[i]);
			if(liObject.is('li')) {
				var display = false;
				if (liObject.children().length > 0) {
					for (var j = 0; j < liObject.children().length; j++) {
						var subDisplay = filterList(jQuery(liObject.children()[j]), filterValue);
						display = display || subDisplay;
					}
				}
				if (!display) {
					// GLA : Modified the search so it looks  for each parts of the search and not the entire sentance
					// GLA : Modified the text to remove accents (latinise())
					var text = liObject.find('a.tree-item').text();
					var textLC = text.toLowerCase().latinise();
					var filterValues = filterValue.split(' ');
					var display = true;

					for(var k in filterValues)
					{
						if(textLC.indexOf(filterValues[k]) < 0)
						{
							display = false;
							break;
						}
					}
				}			
				liObject.css('display', display ? '' : 'none');
				result = result || display;
			} 
		}
		return result;
	}
		
	input.on('change', function(event) {
		// GLA : Modified the search t remove accents (latinise())
		var filter = input.val().toLowerCase().trim().latinise();
		//var startTime = new Date().getTime();
		filterList(list, filter);
		//var endTime = new Date().getTime();
		//console.log('Search for ' + filter + ' took: ' + (endTime - startTime) + 'ms');
		callback();
		return false;
	}).keydown(function() {
		clearTimeout(keyTimeout);
		keyTimeout = setTimeout(function() {
			if( input.val() === lastFilter ) return;
			lastFilter = input.val();
			input.change();
		}, timeout);
	});
	return this;
}

