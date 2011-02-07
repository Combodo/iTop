/* Copyright (c) 2006 Brandon Aaron (http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) 
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * $LastChangedDate: 2007-02-18 22:09:54 -0600 (Sun, 18 Feb 2007) $
 * $Rev: 1379 $
 */

/**
 * The bgiframe is chainable and applies the iframe hack to get 
 * around zIndex issues in IE6. It will only apply itself in IE 
 * and adds a class to the iframe called 'bgiframe'.
 * 
 * It does take borders into consideration but all values
 * need to be in pixels and the element needs to have
 * position relative or absolute.
 *
 * NOTICE: This plugin uses CSS expersions in order to work
 * with an element's borders, height and with and can result in poor 
 * performance when used on an element that changes properties 
 * like size and position a lot. Two of these expressions can be
 * removed if border doesn't matter and performance does.
 * See lines 39 and 40 below and set top: 0 and left: 0
 * instead of their current values.
 *
 * @example $('div').bgiframe();
 * @before <div><p>Paragraph</p></div>
 * @result <div><iframe class="bgiframe".../><p>Paragraph</p></div>
 *
 * @name bgiframe
 * @type jQuery
 * @cat Plugins/bgiframe
 * @author Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
 */
jQuery.fn.bgIframe = jQuery.fn.bgiframe = function() {
	// This is only for IE6
	if ( !(jQuery.browser.msie && typeof XMLHttpRequest == 'function') ) return this;
	var html = '<iframe class="bgiframe" src="javascript:false;document.write(\'\');" tabindex="-1" '
	 					+'style="display:block; position:absolute; '
						+'top: expression(((parseInt(this.parentNode.currentStyle.borderTopWidth)  || 0) * -1) + \'px\'); '
						+'left:expression(((parseInt(this.parentNode.currentStyle.borderLeftWidth) || 0) * -1) + \'px\'); ' 
						+'z-index:-1; filter:Alpha(Opacity=\'0\'); '
						+'width:expression(this.parentNode.offsetWidth + \'px\'); '
						+'height:expression(this.parentNode.offsetHeight + \'px\')"/>';
	return this.each(function() {
		if ( !jQuery('iframe.bgiframe', this)[0] )
			this.insertBefore( document.createElement(html), this.firstChild );
	});
};