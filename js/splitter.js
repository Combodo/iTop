/*
 * jquery.splitter.js - two-pane splitter window plugin
 *
 * version 1.01 (01/05/2007) 
 * 
 * Dual licensed under the MIT and GPL licenses: 
 *   http://www.opensource.org/licenses/mit-license.php 
 *   http://www.gnu.org/licenses/gpl.html 
 */

/**
 * The splitter() plugin implements a two-pane resizable splitter window.
 * The selected elements in the jQuery object are converted to a splitter;
 * each element should have two child elements which are used for the panes
 * of the splitter. The plugin adds a third child element for the splitbar.
 * 
 * For more details see: http://methvin.com/jquery/splitter/
 *
 *
 * @example $('#MySplitter').splitter();
 * @desc Create a vertical splitter with default settings 
 *
 * @example $('#MySplitter').splitter({direction: 'h', accessKey: 'M'});
 * @desc Create a horizontal splitter resizable via Alt+Shift+M
 *
 * @name splitter
 * @type jQuery
 * @param String options Options for the splitter
 * @cat Plugins/Splitter
 * @return jQuery
 * @author Dave Methvin (dave.methvin@gmail.com)
 */
 jQuery.fn.splitter = function(opts){
	opts = jQuery.extend({
		type: 'v',				// v=vertical, h=horizontal split
		activeClass: 'active',	// class name for active splitter
		pxPerKey: 5,			// splitter px moved per keypress
		tabIndex: 0,			// tab order indicator
		accessKey: ''			// accelerator key for splitter
//		initA  initB			// initial A/B size (pick ONE)
//		minA maxA  minB maxB	// min/max pane sizes
	},{
		v: {					// Vertical splitters:
			keyGrowA: 39,		//	left arrow key
			keyShrinkA: 37,		//	right arrow key
			cursor: "e-resize",	//	double-arrow horizontal
			splitbarClass: "vsplitbar",
			eventPos: "pageX", set: "left", 
			adjust: "width",  offsetAdjust: "offsetWidth",  adjSide1: "Left", adjSide2: "Right",
			fixed:  "height", offsetFixed:  "offsetHeight", fixSide1: "Top",  fixSide2: "Bottom"
		},
		h: {					// Horizontal splitters:
			keyGrowA: 40,		//	down arrow key
			keyShrinkA: 38,		//	up arrow key
			cursor: "n-resize",	//	double-arrow vertical
			splitbarClass: "hsplitbar",
			eventPos: "pageY", set: "top", 
			adjust: "height", offsetAdjust: "offsetHeight", adjSide1: "Top",  adjSide2: "Bottom",
			fixed:  "width",  offsetFixed:  "offsetWidth",  fixSide1: "Left", fixSide2: "Right"
		}
	}[((opts||{}).type||'v').charAt(0).toLowerCase()], opts||{});

	return this.each(function() {
		function startSplit(e) {
			splitbar.addClass(opts.activeClass);
			if ( e.type == "mousedown" ) {
				paneA._posAdjust = paneA[0][opts.offsetAdjust] - e[opts.eventPos];
				jQuery(document)
					.bind("mousemove", doSplitMouse)
					.bind("mouseup", endSplit);
			}
			return true;	// required???
		}
		function doSplitKey(e) {
			var key = e.which || e.keyCode;
			var dir = key==opts.keyGrowA? 1 : key==opts.keyShrinkA? -1 : 0;
			if ( dir )
				moveSplitter(paneA[0][opts.offsetAdjust]+dir*opts.pxPerKey);
			return true;	// required???
		}
		function doSplitMouse(e) {
			moveSplitter(paneA._posAdjust+e[opts.eventPos]);
		}
		function endSplit(e) {
			splitbar.removeClass(opts.activeClass);
			jQuery(document)
				.unbind("mousemove", doSplitMouse)
				.unbind("mouseup", endSplit);
		}
		function moveSplitter(np) {
			// Constrain new position to fit pane size limits; 16=scrollbar fudge factor
			// TODO: enforce group width in IE6 since it lacks min/max css properties?
			np = Math.max(paneA._min+paneA._padAdjust, group._adjust - (paneB._max||9999), 16,
				Math.min(np, paneA._max||9999, group._adjust - splitbar._adjust - 
					Math.max(paneB._min+paneB._padAdjust, 16)));

			// Resize/position the two panes and splitbar
			splitbar.css(opts.set, np+"px");
			paneA.css(opts.adjust, np-paneA._padAdjust+"px");
			paneB.css(opts.set, np+splitbar._adjust+"px")
				.css(opts.adjust, group._adjust-splitbar._adjust-paneB._padAdjust-np+"px");

			// IE fires resize for us; all others pay cash
			if ( !jQuery.browser.msie ) {
				paneA.trigger("resize");
				paneB.trigger("resize");
			}
		}
		function cssCache(jq, n, pf, m1, m2) {
			// IE backCompat mode thinks width/height includes border and padding
			jq[n] = jQuery.boxModel? (parseInt(jq.css(pf+m1))||0) + (parseInt(jq.css(pf+m2))||0) : 0;
		}
		function optCache(jq, pane) {
			// Opera returns -1px for min/max dimensions when they're not there!
			jq._min = Math.max(0, opts["min"+pane] || parseInt(jq.css("min-"+opts.adjust)) || 0);
			jq._max = Math.max(0, opts["max"+pane] || parseInt(jq.css("max-"+opts.adjust)) || 0);
		}

		// Create jQuery object closures for splitter group and both panes
		var group = jQuery(this).css({position: "relative"});
		var divs = jQuery(">div", group).css({
			position: "absolute", 			// positioned inside splitter container
			margin: "0", 					// remove any stylesheet margin or ...
			border: "0", 					// ... border added for non-script situations
			"-moz-user-focus": "ignore"		// disable focusability in Firefox
		});
		var paneA = jQuery(divs[0]);		// left  or top
		var paneB = jQuery(divs[1]);		// right or bottom

		// Focuser element, provides keyboard support
		var focuser = jQuery('<a href="javascript:void(0)"></a>')
			.bind("focus", startSplit).bind("keydown", doSplitKey).bind("blur", endSplit)
			.attr({accessKey: opts.accessKey, tabIndex: opts.tabIndex});

		// Splitbar element, displays actual splitter bar
		// The select-related properties prevent unintended text highlighting
		var splitbar = jQuery('<div></div>')
			.insertAfter(paneA).append(focuser)
			.attr({"class": opts.splitbarClass, unselectable: "on"})
			.css({position: "absolute", "-khtml-user-select": "none",
				"-moz-user-select": "none", "user-select": "none"})
			.bind("mousedown", startSplit);
		if ( /^(auto|default)$/.test(splitbar.css("cursor") || "auto") )
			splitbar.css("cursor", opts.cursor);

		// Cache several dimensions for speed--assume these don't change
		splitbar._adjust = splitbar[0][opts.offsetAdjust];
		cssCache(group, "_borderAdjust", "border", opts.adjSide1+"Width", opts.adjSide2+"Width");
		cssCache(group, "_borderFixed",  "border", opts.fixSide1+"Width", opts.fixSide2+"Width");
		cssCache(paneA, "_padAdjust", "padding", opts.adjSide1, opts.adjSide2);
		cssCache(paneA, "_padFixed",  "padding", opts.fixSide1, opts.fixSide2);
		cssCache(paneB, "_padAdjust", "padding", opts.adjSide1, opts.adjSide2);
		cssCache(paneB, "_padFixed",  "padding", opts.fixSide1, opts.fixSide2);
		optCache(paneA, 'A');
		optCache(paneB, 'B');

		// Initial splitbar position as measured from left edge of splitter
		paneA._init = (opts.initA==true? parseInt(jQuery.curCSS(paneA[0],opts.adjust)) : opts.initA) || 0;
		paneB._init = (opts.initB==true? parseInt(jQuery.curCSS(paneB[0],opts.adjust)) : opts.initB) || 0;
		if ( paneB._init )
			paneB._init = group[0][opts.offsetAdjust] - group._borderAdjust - paneB._init - splitbar._adjust;

		// Set up resize event handler and trigger immediately to set initial position
		group.bind("resize", function(e,size){
			// Determine new width/height of splitter container
			group._fixed  = group[0][opts.offsetFixed]  - group._borderFixed;
			group._adjust = group[0][opts.offsetAdjust] - group._borderAdjust;
			// Bail if splitter isn't visible or content isn't there yet
			if ( group._fixed <= 0 || group._adjust <= 0 ) return;
			// Set the fixed dimension (e.g., height on a vertical splitter)
			paneA.css(opts.fixed, group._fixed-paneA._padFixed+"px");
			paneB.css(opts.fixed, group._fixed-paneB._padFixed+"px");
			splitbar.css(opts.fixed, group._fixed+"px");
			// Re-divvy the adjustable dimension; maintain size of the preferred pane
			moveSplitter(size || (!opts.initB? paneA[0][opts.offsetAdjust] :
				group._adjust-paneB[0][opts.offsetAdjust]-splitbar._adjust));
		}).trigger("resize" , [paneA._init || paneB._init || 
			Math.round((group[0][opts.offsetAdjust] - group._borderAdjust - splitbar._adjust)/2)]);
	});
};
