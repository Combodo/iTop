(function($) {

	//Make nodes selectable by expression
	$.extend($.expr[':'], { droppable: "(' '+a.className+' ').indexOf(' ui-droppable ')" });

	//Macros for external methods that support chaining
	var methods = "destroy,enable,disable".split(",");
	for(var i=0;i<methods.length;i++) {
		var cur = methods[i], f;
		eval('f = function() { var a = arguments; return this.each(function() { if(jQuery(this).is(".ui-droppable")) jQuery.data(this, "ui-droppable")["'+cur+'"](a); }); }');
		$.fn["droppable"+cur.substr(0,1).toUpperCase()+cur.substr(1)] = f;
	};
	
	//get instance method
	$.fn.droppableInstance = function() {
		if($(this[0]).is(".ui-droppable")) return $.data(this[0], "ui-droppable");
		return false;
	};

	$.fn.droppable = function(o) {
		return this.each(function() {
			new $.ui.droppable(this,o);
		});
	}
	
	$.ui.droppable = function(el,o) {

		if(!o) var o = {};			
		this.element = el; if($.browser.msie) el.droppable = 1;
		$.data(el, "ui-droppable", this);
		
		this.options = {};
		$.extend(this.options, o);
		
		var accept = o.accept;
		$.extend(this.options, {
			accept: o.accept && o.accept.constructor == Function ? o.accept : function(d) {
				return $(d).is(accept);	
			},
			tolerance: o.tolerance || 'intersect'
		});
		o = this.options;
		var self = this;
		
		this.mouseBindings = [function(e) { return self.move.apply(self, [e]); },function(e) { return self.drop.apply(self, [e]); }];
		$(this.element).bind("mousemove", this.mouseBindings[0]);
		$(this.element).bind("mouseup", this.mouseBindings[1]);
		
		$.ui.ddmanager.droppables.push({ item: this, over: 0, out: 1 }); // Add the reference and positions to the manager
		$(this.element).addClass("ui-droppable");
			
	};
	
	$.extend($.ui.droppable.prototype, {
		plugins: {},
		prepareCallbackObj: function(c) {
			return {
				draggable: c,
				droppable: this,
				element: c.element,
				helper: c.helper,
				options: this.options	
			}			
		},
		destroy: function() {
			$(this.element).removeClass("ui-droppable").removeClass("ui-droppable-disabled");
			$(this.element).unbind("mousemove", this.mouseBindings[0]);
			$(this.element).unbind("mouseup", this.mouseBindings[1]);
			
			for(var i=0;i<$.ui.ddmanager.droppables.length;i++) {
				if($.ui.ddmanager.droppables[i].item == this) $.ui.ddmanager.droppables.splice(i,1);
			}
		},
		enable: function() {
			$(this.element).removeClass("ui-droppable-disabled");
			this.disabled = false;
		},
		disable: function() {
			$(this.element).addClass("ui-droppable-disabled");
			this.disabled = true;
		},
		move: function(e) {

			if(!$.ui.ddmanager.current) return;

			var o = this.options;
			var c = $.ui.ddmanager.current;
			
			/* Save current target, if no last target given */
			var findCurrentTarget = function(e) {
				if(e.currentTarget) return e.currentTarget;
				var el = e.srcElement; 
				do { if(el.droppable) return el; el = el.parentNode; } while (el); //This is only used in IE! references in DOM are evil!
			}
			if(c && o.accept(c.element)) c.currentTarget = findCurrentTarget(e);
			
			c.drag.apply(c, [e]);
			e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;
			
		},
		over: function(e) {

			var c = $.ui.ddmanager.current;
			if (!c || c.element == this.element) return; // Bail if draggable and droppable are same element
			
			var o = this.options;
			if (o.accept(c.element)) {
				$.ui.plugin.call(this, 'over', [e, this.prepareCallbackObj(c)]);
				$(this.element).triggerHandler("dropover", [e, this.prepareCallbackObj(c)], o.over);
			}
			
		},
		out: function(e) {

			var c = $.ui.ddmanager.current;
			if (!c || c.element == this.element) return; // Bail if draggable and droppable are same element

			var o = this.options;
			if (o.accept(c.element)) {
				$.ui.plugin.call(this, 'out', [e, this.prepareCallbackObj(c)]);
				$(this.element).triggerHandler("dropout", [e, this.prepareCallbackObj(c)], o.out);
			}
			
		},
		drop: function(e) {

			var c = $.ui.ddmanager.current;
			if (!c || c.element == this.element) return; // Bail if draggable and droppable are same element
			
			var o = this.options;
			if(o.accept(c.element)) { // Fire callback
				if(o.greedy && !c.slowMode) {
					if(c.currentTarget == this.element) {
						$.ui.plugin.call(this, 'drop', [e, {
							draggable: c,
							droppable: this,
							element: c.element,
							helper: c.helper	
						}]);
						$(this.element).triggerHandler("drop", [e, {
							draggable: c,
							droppable: this,
							element: c.element,
							helper: c.helper	
						}], o.drop);
					}
				} else {
					$.ui.plugin.call(this, 'drop', [e, this.prepareCallbackObj(c)]);
					$(this.element).triggerHandler("drop", [e, this.prepareCallbackObj(c)], o.drop);
				}
			}
			
		},
		activate: function(e) {
			var c = $.ui.ddmanager.current;
			$.ui.plugin.call(this, 'activate', [e, this.prepareCallbackObj(c)]);
			if(c) $(this.element).triggerHandler("dropactivate", [e, this.prepareCallbackObj(c)], this.options.activate);	
		},
		deactivate: function(e) {
			var c = $.ui.ddmanager.current;
			$.ui.plugin.call(this, 'deactivate', [e, this.prepareCallbackObj(c)]);
			if(c) $(this.element).triggerHandler("dropdeactivate", [e, this.prepareCallbackObj(c)], this.options.deactivate);
		}
	});
	
	$.ui.intersect = function(oDrag, oDrop, toleranceMode) {
		if (!oDrop.offset)
			return false;
		var x1 = oDrag.rpos[0] - oDrag.options.cursorAt.left + oDrag.options.margins.left, x2 = x1 + oDrag.helperSize.width,
		    y1 = oDrag.rpos[1] - oDrag.options.cursorAt.top + oDrag.options.margins.top, y2 = y1 + oDrag.helperSize.height;
		var l = oDrop.offset.left, r = l + oDrop.item.element.offsetWidth, 
		    t = oDrop.offset.top,  b = t + oDrop.item.element.offsetHeight;
		switch (toleranceMode) {
			case 'fit':
				return (   l < x1 && x2 < r
					&& t < y1 && y2 < b);
				break;
			case 'intersect':
				return (   l < x1 + (oDrag.helperSize.width  / 2)        // Right Half
					&&     x2 - (oDrag.helperSize.width  / 2) < r    // Left Half
					&& t < y1 + (oDrag.helperSize.height / 2)        // Bottom Half
					&&     y2 - (oDrag.helperSize.height / 2) < b ); // Top Half
				break;
			case 'pointer':
				return (   l < oDrag.rpos[0] && oDrag.rpos[0] < r
					&& t < oDrag.rpos[1] && oDrag.rpos[1] < b);
				break;
			case 'touch':
				return (   (l < x1 && x1 < r && t < y1 && y1 < b)    // Top-Left Corner
					|| (l < x1 && x1 < r && t < y2 && y2 < b)    // Bottom-Left Corner
					|| (l < x2 && x2 < r && t < y1 && y1 < b)    // Top-Right Corner
					|| (l < x2 && x2 < r && t < y2 && y2 < b) ); // Bottom-Right Corner
				break;
			default:
				return false;
				break;
		}
	}
	
})($);

