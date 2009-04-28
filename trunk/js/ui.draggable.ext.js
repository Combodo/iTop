/*
 * 'this' -> original element
 * 1. argument: browser event
 * 2.argument: ui object
 */

(function($) {

	$.ui.plugin.add("draggable", "stop", "effect", function(e,ui) {
		var t = ui.helper;
		if(ui.options.effect[1]) {
			if(t != this) {
				ui.options.beQuietAtEnd = true;
				switch(ui.options.effect[1]) {
					case 'fade':
						$(t).fadeOut(300, function() { $(this).remove(); });
						break;
					default:
						$(t).remove();
						break;	
				}
			}
		}
	});
	
	$.ui.plugin.add("draggable", "start", "effect", function(e,ui) {
		if(ui.options.effect[0]) {
			switch(ui.options.effect[0]) {
				case 'fade':
					$(ui.helper).hide().fadeIn(300);
					break;
			}
		}
	});

//----------------------------------------------------------------

	$.ui.plugin.add("draggable", "start", "cursor", function(e,ui) {
		var t = $('body');
		if (t.css("cursor")) ui.options.ocursor = t.css("cursor");
		t.css("cursor", ui.options.cursor);
	});

	$.ui.plugin.add("draggable", "stop", "cursor", function(e,ui) {
		if (ui.options.ocursor) $('body').css("cursor", ui.options.ocursor);
	});

//----------------------------------------------------------------
	
	$.ui.plugin.add("draggable", "start", "zIndex", function(e,ui) {
		var t = $(ui.helper);
		if(t.css("zIndex")) ui.options.ozIndex = t.css("zIndex");
		t.css('zIndex', ui.options.zIndex);
	});
	
	$.ui.plugin.add("draggable", "stop", "zIndex", function(e,ui) {
		if(ui.options.ozIndex) $(ui.helper).css('zIndex', ui.options.ozIndex);
	});


//----------------------------------------------------------------

	$.ui.plugin.add("draggable", "start", "opacity", function(e,ui) {
		var t = $(ui.helper);
		if(t.css("opacity")) ui.options.oopacity = t.css("opacity");
		t.css('opacity', ui.options.opacity);
	});
	
	$.ui.plugin.add("draggable", "stop", "opacity", function(e,ui) {
		if(ui.options.oopacity) $(ui.helper).css('opacity', ui.options.oopacity);
	});

//----------------------------------------------------------------

	$.ui.plugin.add("draggable", "stop", "revert", function(e,ui) {
	
		var o = ui.options;
		var rpos = { left: 0, top: 0 };
		o.beQuietAtEnd = true;

		if(ui.helper != this) {

			rpos = $(ui.draggable.sorthelper || this).offset({ border: false });

			var nl = rpos.left-o.po.left-o.margins.left;
			var nt = rpos.top-o.po.top-o.margins.top;

		} else {
			var nl = o.co.left - (o.po ? o.po.left : 0);
			var nt = o.co.top - (o.po ? o.po.top : 0);
		}
		
		var self = ui.draggable;

		$(ui.helper).animate({
			left: nl,
			top: nt
		}, 500, function() {
			
			if(o.wasPositioned) $(self.element).css('position', o.wasPositioned);
			if(o.stop) o.stop.apply(self.element, [self.helper, self.pos, [o.co.left - o.po.left,o.co.top - o.po.top],self]);
			
			if(self.helper != self.element) window.setTimeout(function() { $(self.helper).remove(); }, 0); //Using setTimeout because of strange flickering in Firefox
			
		});
		
	});

//----------------------------------------------------------------

	$.ui.plugin.add("draggable", "start", "iframeFix", function(e,ui) {

		var o = ui.options;
		if(!ui.draggable.slowMode) { // Make clones on top of iframes (only if we are not in slowMode)
			if(o.iframeFix.constructor == Array) {
				for(var i=0;i<o.iframeFix.length;i++) {
					var co = $(o.iframeFix[i]).offset({ border: false });
					$("<div class='DragDropIframeFix' style='background: #fff;'></div>").css("width", $(o.iframeFix[i])[0].offsetWidth+"px").css("height", $(o.iframeFix[i])[0].offsetHeight+"px").css("position", "absolute").css("opacity", "0.001").css("z-index", "1000").css("top", co.top+"px").css("left", co.left+"px").appendTo("body");
				}		
			} else {
				$("iframe").each(function() {					
					var co = $(this).offset({ border: false });
					$("<div class='DragDropIframeFix' style='background: #fff;'></div>").css("width", this.offsetWidth+"px").css("height", this.offsetHeight+"px").css("position", "absolute").css("opacity", "0.001").css("z-index", "1000").css("top", co.top+"px").css("left", co.left+"px").appendTo("body");
				});							
			}		
		}

	});
	
	$.ui.plugin.add("draggable","stop", "iframeFix", function(e,ui) {
		if(ui.options.iframeFix) $("div.DragDropIframeFix").each(function() { this.parentNode.removeChild(this); }); //Remove frame helpers	
	});
		
//----------------------------------------------------------------

	$.ui.plugin.add("draggable", "start", "containment", function(e,ui) {

		var o = ui.options;

		if(!o.cursorAtIgnore || o.containment.left != undefined || o.containment.constructor == Array) return;
		if(o.containment == 'parent') o.containment = this.parentNode;


		if(o.containment == 'document') {
			o.containment = [
				0-o.margins.left,
				0-o.margins.top,
				$(document).width()-o.margins.right,
				($(document).height() || document.body.parentNode.scrollHeight)-o.margins.bottom
			];
		} else { //I'm a node, so compute top/left/right/bottom
			var ce = $(o.containment)[0];
			var co = $(o.containment).offset({ border: false });

			o.containment = [
				co.left-o.margins.left,
				co.top-o.margins.top,
				co.left+(ce.offsetWidth || ce.scrollWidth)-o.margins.right,
				co.top+(ce.offsetHeight || ce.scrollHeight)-o.margins.bottom
			];
		}

	});
	
	$.ui.plugin.add("draggable", "drag", "containment", function(e,ui) {
		
		var o = ui.options;
		if(!o.cursorAtIgnore) return;
			
		var h = $(ui.helper);
		var c = o.containment;
		if(c.constructor == Array) {
			
			if((ui.draggable.pos[0] < c[0]-o.po.left)) ui.draggable.pos[0] = c[0]-o.po.left;
			if((ui.draggable.pos[1] < c[1]-o.po.top)) ui.draggable.pos[1] = c[1]-o.po.top;
			if(ui.draggable.pos[0]+h[0].offsetWidth > c[2]-o.po.left) ui.draggable.pos[0] = c[2]-o.po.left-h[0].offsetWidth;
			if(ui.draggable.pos[1]+h[0].offsetHeight > c[3]-o.po.top) ui.draggable.pos[1] = c[3]-o.po.top-h[0].offsetHeight;
			
		} else {

			if(c.left && (ui.draggable.pos[0] < c.left)) ui.draggable.pos[0] = c.left;
			if(c.top && (ui.draggable.pos[1] < c.top)) ui.draggable.pos[1] = c.top;

			var p = $(o.pp);
			if(c.right && ui.draggable.pos[0]+h[0].offsetWidth > p[0].offsetWidth-c.right) ui.draggable.pos[0] = (p[0].offsetWidth-c.right)-h[0].offsetWidth;
			if(c.bottom && ui.draggable.pos[1]+h[0].offsetHeight > p[0].offsetHeight-c.bottom) ui.draggable.pos[1] = (p[0].offsetHeight-c.bottom)-h[0].offsetHeight;
			
		}

		
	});

//----------------------------------------------------------------

	$.ui.plugin.add("draggable", "drag", "grid", function(e,ui) {
		var o = ui.options;
		if(!o.cursorAtIgnore) return;
		ui.draggable.pos[0] = o.co.left + o.margins.left - o.po.left + Math.round((ui.draggable.pos[0] - o.co.left - o.margins.left + o.po.left) / o.grid[0]) * o.grid[0];
		ui.draggable.pos[1] = o.co.top + o.margins.top - o.po.top + Math.round((ui.draggable.pos[1] - o.co.top - o.margins.top + o.po.top) / o.grid[1]) * o.grid[1];
	});

//----------------------------------------------------------------

	$.ui.plugin.add("draggable", "drag", "axis", function(e,ui) {
		var o = ui.options;
		if(!o.cursorAtIgnore) return;
		if(o.constraint) o.axis = o.constraint; //Legacy check
		o.axis ? ( o.axis == 'x' ? ui.draggable.pos[1] = o.co.top - o.margins.top - o.po.top : ui.draggable.pos[0] = o.co.left - o.margins.left - o.po.left ) : null;
	});

//----------------------------------------------------------------

	$.ui.plugin.add("draggable", "drag", "scroll", function(e,ui) {

		var o = ui.options;
		o.scrollSensitivity	= o.scrollSensitivity || 20;
		o.scrollSpeed		= o.scrollSpeed || 20;

		if(o.pp && o.ppOverflow) { // If we have a positioned parent, we only scroll in this one
			// TODO: Extremely strange issues are waiting here..handle with care
		} else {
			if((ui.draggable.rpos[1] - $(window).height()) - $(document).scrollTop() > -o.scrollSensitivity) window.scrollBy(0,o.scrollSpeed);
			if(ui.draggable.rpos[1] - $(document).scrollTop() < o.scrollSensitivity) window.scrollBy(0,-o.scrollSpeed);
			if((ui.draggable.rpos[0] - $(window).width()) - $(document).scrollLeft() > -o.scrollSensitivity) window.scrollBy(o.scrollSpeed,0);
			if(ui.draggable.rpos[0] - $(document).scrollLeft() < o.scrollSensitivity) window.scrollBy(-o.scrollSpeed,0);
		}

	});

//----------------------------------------------------------------

	$.ui.plugin.add("draggable", "drag", "wrapHelper", function(e,ui) {

		var o = ui.options;
		if(o.cursorAtIgnore) return;
		var t = ui.helper;

		if(!o.pp || !o.ppOverflow) {
			var wx = $(window).width() - ($.browser.mozilla ? 20 : 0);
			var sx = $(document).scrollLeft();
			
			var wy = $(window).height();
			var sy = $(document).scrollTop();	
		} else {
			var wx = o.pp.offsetWidth + o.po.left - 20;
			var sx = o.pp.scrollLeft;
			
			var wy = o.pp.offsetHeight + o.po.top - 20;
			var sy = o.pp.scrollTop;						
		}

		ui.draggable.pos[0] -= ((ui.draggable.rpos[0]-o.cursorAt.left - wx + t.offsetWidth+o.margins.right) - sx > 0 || (ui.draggable.rpos[0]-o.cursorAt.left+o.margins.left) - sx < 0) ? (t.offsetWidth+o.margins.left+o.margins.right - o.cursorAt.left * 2) : 0;
		
		ui.draggable.pos[1] -= ((ui.draggable.rpos[1]-o.cursorAt.top - wy + t.offsetHeight+o.margins.bottom) - sy > 0 || (ui.draggable.rpos[1]-o.cursorAt.top+o.margins.top) - sy < 0) ? (t.offsetHeight+o.margins.top+o.margins.bottom - o.cursorAt.top * 2) : 0;

	});

})(jQuery);

