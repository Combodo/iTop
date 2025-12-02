/**
 * Tree List Filter jQuery plugin 1.0
 *
 * Copyright (c) 2014, AIWSolutions
 * License: GPL2
 * Project Website: http://wiki.aiwsolutions.net/dOQKO
 **/

/**
 * Altered to allow filtering tree list with extra data.
 * Add an attribute data-tree-additional-search to tree-item-wrapper element with extra data you want the search to include.
 */

jQuery.fn.treeListFilter = function(list, timeout, callback) {
    var list = jQuery(list);
    var input = this;
    var keyTimeout;
    var lastFilter = '';

    // Default timeout
    if (timeout === undefined) {
        timeout = 200;
    }
    // Default callback
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
                    // Modified the search so it looks  for each parts of the search and not the entire sentance
                    // Modified the text to remove accents (latinise())
                    var text = liObject.find('.tree-item-wrapper').text();
	                text+= liObject.find('.tree-item-wrapper').attr('data-tree-additional-search');
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
        // Modified the search t remove accents (latinise())
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

