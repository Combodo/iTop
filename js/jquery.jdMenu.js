/*
 * jdMenu 1.3.beta2 (2007-03-06)
 *
 * Copyright (c) 2006,2007 Jonathan Sharp (http://jdsharp.us)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://jdsharp.us/
 *
 * Built upon jQuery 1.1.1 (http://jquery.com)
 * This also requires the jQuery dimensions plugin
 */
(function($){
	// This will store an element list of all our menu objects
	var jdMenu = [];
	
	// Public methods
	$.fn.jdMenu = function(inSettings) {
		var settings = $.extend({}, arguments.callee.defaults, inSettings);
		return this.each(function() {
			jdMenu.push(this);
			$(this).addClass('jd_menu_flag_root');
			this.$settings = $.extend({}, settings, {isVerticalMenu: $(this).is('.jd_menu_vertical')});
			addEvents(this);
		});
	};
	$.fn.jdMenuShow = function() {
		return this.each(function() {
			showMenuLI.apply(this);
		});
	};
	$.fn.jdMenuHide = function() {
		return this.each(function() {
			hideMenuUL.apply(this);
		});
	};

	// Private methods and logic
	$(window)
		// Bind a click event to hide all visible menus when the document is clicked
		.bind('click', function(){
			$(jdMenu).find('ul:visible').jdMenuHide();
		})
		// Cleanup after ourself by nulling the $settings object
		.bind('unload', function() {
			$(jdMenu).each(function() {
				this.$settings = null;
			});
		});

	// These are our default settings for this plugin
	$.fn.jdMenu.defaults = {
		activateDelay: 750,
		showDelay: 150,
		hideDelay: 550,
		onShow: null,
		onHideCheck: null,
		onHide: null,
		onAnimate: null,
		onClick: null,
		offsetX: 4,
		offsetY: 2,
		iframe: $.browser.msie
	};
	
	// Our special parentsUntil method to get all parents up to and including the matched element
	$.fn.parentsUntil = function(match) {
		var a = [];
		$(this[0]).parents().each(function() {
			a.push(this);
			return !$(this).is(match);
		});
		return this.pushStack(a, arguments);
	};

	// Returns our settings object for this menu
	function getSettings(el) {
		return $(el).parents('ul.jd_menu_flag_root')[0].$settings;
	}

	// Unbind any events and then rebind them
	function addEvents(ul) {
		removeEvents(ul);
		$('> li', ul)
			.hover(hoverOverLI, hoverOutLI)
			.bind('click', itemClick)
			.find('> a.accessible')
				.bind('click', accessibleClick);
	};
	
	// Remove all events for this menu
	function removeEvents(ul) {
		$('> li', ul)
			.unbind('mouseover').unbind('mouseout')
			.unbind('click')
			.find('> a.accessible')
				.unbind('click');
	};
	
	function hoverOverLI() {
		var cls = 'jd_menu_hover' + ($(this).parent().is('.jd_menu_flag_root') ? '_menubar' : '');
		$(this).addClass(cls).find('> a').addClass(cls);
		
		if (this.$timer) {
			clearTimeout(this.$timer);
		}

		// Do we have a sub menu?
		if ($('> ul', this).size() > 0) {
			var settings = getSettings(this);
			
			// Which delay to use, the longer activate one or the shorter show delay if a menu is already visible
			var delay = ($(this).parents('ul.jd_menu_flag_root').find('ul:visible').size() == 0) 
							? settings.activateDelay : settings.showDelay;
			var t = this;
			this.$timer = setTimeout(function() {
				showMenuLI.apply(t);
			}, delay);
		}
	};
	
	function hoverOutLI() {
		// Remove both classes so we do not have to test which one we are
		$(this)	.removeClass('jd_menu_hover').removeClass('jd_menu_hover_menubar')
			.find('> a')
				.removeClass('jd_menu_hover').removeClass('jd_menu_hover_menubar');
		
		if (this.$timer) {
			clearTimeout(this.$timer);
		}

		// TODO: Possible bug with our test for visibility in that parent menus are hidden child menus are not

		// If we have a visible menu, hide it
		if ($(this).is(':visible') && $('> ul', this).size() > 0) {
			var settings = getSettings(this);
			var ul = $('> ul', this)[0];
			this.$timer = setTimeout(function() {
				hideMenuUL.apply(ul);
			}, settings.hideDelay);
		}
	};
	
	// "this" is a reference to the LI element that contains 
	// the UL that will be shown
	function showMenuLI() {
		var ul = $('> ul', this).get(0);
		// We are already visible, just return
		if ($(ul).is(':visible')) {
			return false;
		}

		// Clear our timer if it exists
		if (this.$timer) {
			clearTimeout(this.$timer);
		}

		// Get our settings object
		var settings = getSettings(this);

		// Call our callback
		if (settings.onShow != null && settings.onShow.apply(this) == false) {
			return false;
		}

		// Add hover classes, needed for accessible functionality
		var isRoot = $(this).parent().is('.jd_menu_flag_root');
		var c = 'jd_menu_active' + (isRoot ? '_menubar' : '');
		$(this).addClass(c).find('> a').addClass(c);

		if (!isRoot) {
			// Add the active class to the parent list item which maybe our menubar
			var c = 'jd_menu_active' + ($(this).parent().parent().parent().is('.jd_menu_flag_root') ? '_menubar' : '');
			$(this).parent().parent().addClass(c).find('> a').addClass(c);
		}

		// Hide any existing menues at the same level
		$(this).parent().find('> li > ul:visible').not(ul).each(function() {
			hideMenuUL.apply(this);
		});

		addEvents(ul);

		// Our range object is used in calculating menu positions
		var Range = function(x1, x2, y1, y2) {
			this.x1	= x1;
			this.x2 = x2;
			this.y1 = y1;
			this.y2 = y2;
		}
		Range.prototype.contains = function(range) {
			return 	(this.x1 <= range.x1 && range.x2 <= this.x2) 
					&& 
					(this.y1 <= range.y1 && range.y2 <= this.y2);
		}
		Range.prototype.transform = function(x, y) {
			return new Range(this.x1 + x, this.x2 + x, this.y1 + y, this.y2 + y);
		}
		Range.prototype.nudgeX = function(range) {
			if (this.x1 < range.x1) {
				return new Range(range.x1, range.x1 + (this.x2 - this.x1), this.y1, this.y2);
			} else if (this.x2 > range.x2) {
				return new Range(range.x2 - (this.x2 - this.x1), range.x2, this.y1, this.y2);
			}
			return this;
		}
		Range.prototype.nudgeY = function(range) {
			if (this.y1 < range.y1) {
				return new Range(this.x1, this.x2, range.y1, range.y1 + (this.y2 - this.y1));
			} else if (this.y2 > range.y2) {
				return new Range(this.x1, this.x2, range.y2 - (this.y2 - this.y1), range.y2);
			}
			return this;
		}

		// window width & scroll offset
		var sx = $(window).scrollLeft()
		var sy = $(window).scrollTop();
		var ww = $(window).innerWidth();
		var wh = $(window).innerHeight();

		var viewport = new Range(	sx, sx + ww, 
									sy, sy + wh);

		// "Show" our menu so we can calculate its width, set left and top so that it does not accidentally
		// go offscreen and trigger browser scroll bars
		$(ul).css({visibility: 'hidden', left: 0, top: 0}).show();

		var menuWidth		= $(ul).outerWidth();
		var menuHeight		= $(ul).outerHeight();

		// Get the LI parent UL outerwidth in case borders are applied to it
		var tp 				= $(this).parent();
		var thisWidth		= tp.outerWidth();
		var thisBorderWidth	= parseInt(tp.css('borderLeftWidth')) + parseInt(tp.css('borderRightWidth'));
		//var thisBorderTop 	= parseInt(tp.css('borderTopWidth'));
		var thisHeight		= $(this).outerHeight();
		var thisOffset 		= $(this).offset({border: false});

		$(ul).hide().css({visibility: ''});

		// We define a list of valid positions for our menu and then test against them to find one that works best
		var position = [];
	// Bottom Horizontal
		// Menu is directly below and left edges aligned to parent item
		position[0] = new Range(thisOffset.left, thisOffset.left + menuWidth, 
								thisOffset.top + thisHeight, thisOffset.top + thisHeight + menuHeight);
		// Menu is directly below and right edges aligned to parent item
		position[1] = new Range((thisOffset.left + thisWidth) - menuWidth, thisOffset.left + thisWidth,
								position[0].y1, position[0].y2);
		// Menu is "nudged" horizontally below parent item
		position[2] = position[0].nudgeX(viewport);

	// Right vertical
		// Menu is directly right and top edge aligned to parent item
		position[3] = new Range(thisOffset.left + thisWidth - thisBorderWidth, thisOffset.left + thisWidth - thisBorderWidth + menuWidth,
								thisOffset.top, thisOffset.top + menuHeight);
		// Menu is directly right and bottom edges aligned with parent item
		position[4] = new Range(position[3].x1, position[3].x2, 
								position[0].y1 - menuHeight, position[0].y1);
		// Menu is "nudged" vertically to right of parent item
		position[5] = position[3].nudgeY(viewport);

	// Top Horizontal
		// Menu is directly top and left edges aligned to parent item
		position[6] = new Range(thisOffset.left, thisOffset.left + menuWidth, 
								thisOffset.top - menuHeight, thisOffset.top);
		// Menu is directly top and right edges aligned to parent item
		position[7] = new Range((thisOffset.left + thisWidth) - menuWidth, thisOffset.left + thisWidth,
								position[6].y1, position[6].y2);
		// Menu is "nudged" horizontally to the top of parent item
		position[8] = position[6].nudgeX(viewport);
	
	// Left vertical
		// Menu is directly left and top edges aligned to parent item
		position[9] = new Range(thisOffset.left - menuWidth, thisOffset.left, 
								position[3].y1, position[3].y2);
		// Menu is directly left and bottom edges aligned to parent item
		position[10]= new Range(position[9].x1, position[9].x2, 
								position[4].y1 + thisHeight - menuHeight, position[4].y1 + thisHeight);
		// Menu is "nudged" vertically to left of parent item
		position[11]= position[10].nudgeY(viewport);

		// This defines the order in which we test our positions
		var order = [];
		if ($(this).parent().is('.jd_menu_flag_root') && !settings.isVerticalMenu) {
			order = [0, 1, 2, 6, 7, 8, 5, 11];
		} else {
			order = [3, 4, 5, 9, 10, 11, 0, 1, 2, 6, 7, 8];
		}

		// Set our default position (first position) if no others can be found
		var pos = order[0];
		for (var i = 0, j = order.length; i < j; i++) {
			// If this position for our menu is within the viewport of the browser, use this position
			if (viewport.contains(position[order[i]])) {
				pos = order[i];
				break;
			}
		}
		var menuPosition = position[pos];

		// Find if we are absolutely positioned or have an absolutely positioned parent
		$(this).add($(this).parents()).each(function() {
			if ($(this).css('position') == 'absolute') {
				var abs = $(this).offset();
				// Transform our coordinates to be relative to the absolute parent
				menuPosition = menuPosition.transform(-abs.left, -abs.top);
				return false;
			}
		});

		switch (pos) {
			case 3:
				menuPosition.y1 += settings.offsetY;
			case 4:
				menuPosition.x1 -= settings.offsetX;
				break;
			
			case 9:
				menuPosition.y1 += settings.offsetY;
			case 10:
				menuPosition.x1 += settings.offsetX;
				break;
		}

		if (settings.iframe) {
			$(ul).bgiframe();
		}

		if (settings.onAnimate) {
			$(ul).css({left: menuPosition.x1, top: menuPosition.y1});
			// The onAnimate method is expected to "show" the element it is passed
			settings.onAnimate.apply(ul, [true]);
		} else {
			$(ul).css({left: menuPosition.x1, top: menuPosition.y1}).show();
		}

		return true;
	}

	// "this" is a reference to a UL menu to be hidden
	function hideMenuUL(recurse) {
		if (!$(this).is(':visible')) {
			return false;
		}

		var settings = getSettings(this);

		// Test if this menu should get hidden
		if (settings.onHideCheck != null && settings.onHideCheck.apply(this) == false) {
			return false;
		}
		
		// Hide all of our child menus first
		$('> li ul:visible', this).each(function() {
			hideMenuUL.apply(this, [false]);
		});

		// If we are the root, do not hide ourself
		if ($(this).is('.jd_menu_flag_root')) {
			alert('We are root');
			return false;
		}

		var elms = $('> li', this).add($(this).parent());
		elms.removeClass('jd_menu_hover').removeClass('jd_menu_hover_menubar')
			.removeClass('jd_menu_active').removeClass('jd_menu_active_menubar')
			.find('> a')
				.removeClass('jd_menu_hover').removeClass('jd_menu_hover_menubar')
				.removeClass('jd_menu_active').removeClass('jd_menu_active_menubar');

		removeEvents(this);
		$(this).each(function() {
			if (settings.onAnimate != null) {
				settings.onAnimate.apply(this, [false]);
			} else {
				$(this).hide();
			}
		}).find('> .bgiframe').remove();
		// Our callback for after our menu is hidden
		if (settings.onHide != null) {
			settings.onHide.apply(this);
		}

		// Recursively hide our parent menus
		if (recurse == true) {
			$(this).parentsUntil('ul.jd_menu_flag_root')
					.removeClass('jd_menu_hover').removeClass('jd_menu_hover_menubar')
				.not('.jd_menu_flag_root').filter('ul')
					.each(function() {
						hideMenuUL.apply(this, [false]);
					});
		}

		return true;
	}

	// Prevent the default (usually following a link)
	function accessibleClick(e) {
		if ($(this).is('.accessible')) {
			// Stop the browser from the default link action allowing the 
			// click event to propagate to propagate to our LI (itemClick function)
			e.preventDefault();
		}
	}

	// Trigger a menu click
	function itemClick(e) {
		e.stopPropagation();

		var settings = getSettings(this);
		if (settings.onClick != null && settings.onClick.apply(this) == false) {
			return false;
		}

		if ($('> ul', this).size() > 0) {
			showMenuLI.apply(this);
		} else {
			if (e.target == this) {
				var link = $('> a', e.target).not('.accessible');
				if (link.size() > 0) {
					var a = link.get(0);
					if (!a.onclick) {
						window.open(a.href, a.target || '_self');
					} else {
						$(a).click();
					}
				}
			}
			
			hideMenuUL.apply($(this).parent(), [true]);
		}
	}
})(jQuery);
