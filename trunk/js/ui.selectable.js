(function($)
{
	
	//Make nodes selectable by expression
	$.extend($.expr[':'], { selectable: "(' '+a.className+' ').indexOf(' ui-selectable ')" });
	$.extend($.expr[':'], { selectee: "(' '+a.className+' ').indexOf(' ui-selectee ')" });

	$.fn.selectable = function(o) {
		return this.each(function() {
			if (!$(this).is(".ui-selectable")) new $.ui.selectable(this, o);
		});
	}

	$.ui.selectable = function(el, o) {
		
		var options = {
			filter: '*'
		};
		var o = o || {}; $.extend(options, o); //Extend and copy options
		this.element = el; var self = this; //Do bindings
		self.dragged = false;

		$.extend(options, {
			helper: function() { return $(document.createElement('div')).css({border:'1px dotted black'}); },
			_start: function(h,p,c,t,e) {
				self.start.apply(t, [self, e]); // Trigger the start callback
			},
			_drag: function(h,p,c,t,e) {
				self.dragged = true;
				self.drag.apply(t, [self, e]); // Trigger the drag callback
			},
			_stop: function(h,p,c,t,e) {
				self.stop.apply(t, [self, e]); // Trigger the end callback
				self.dragged = false;
			}
		});

		//Initialize mouse interaction
		this.mouse = new $.ui.mouseInteraction(el, options);

		//Add the class for themeing
		$(this.element).addClass("ui-selectable");
		$(this.element).children(options.filter).addClass("ui-selectee");

	}

	$.extend($.ui.selectable.prototype, {
		plugins: {},
		start: function(self, ev) {
			$(self.mouse.helper).css({'z-index': 100, position: 'absolute', left: ev.clientX, top: ev.clientY, width:0, height: 0});
			if (ev.ctrlKey) {
				if ($(ev.target).is('.ui-selected')) {
					$(ev.target).removeClass('ui-selected').addClass('ui-unselecting');
					$(self.element).triggerHandler("selectableunselecting", [ev, {
						selectable: self.element,
						unselecting: ev.target,
						options: this.options
					}], this.options.unselecting);
				}
			} else {
				self.unselecting(self, ev, this.options);
				self.selectingTarget(self, ev, this.options);
			}
		},
		drag: function(self, ev) {
			var x1 = self.mouse.opos[0], y1 = self.mouse.opos[1], x2 = ev.pageX, y2 = ev.pageY;
			if (x1 > x2) { var tmp = x2; x2 = x1; x1 = tmp; }
			if (y1 > y2) { var tmp = y2; y2 = y1; y1 = tmp; }
			$(self.mouse.helper).css({left: x1, top: y1, width: x2-x1, height: y2-y1});
			self.selectingTarget(self, ev, this.options);
		},
		stop: function(self, ev) {
			var options = this.options;
			$('.ui-selecting', self.element).each(function() {
				$(this).removeClass('ui-selecting').addClass('ui-selected');
				$(self.element).triggerHandler("selectableselected", [ev, {
					selectable: self.element,
					selected: this,
					options: options
				}], options.selected);
			});
			$('.ui-unselecting', self.element).each(function() {
				$(this).removeClass('ui-unselecting');
				$(self.element).triggerHandler("selectableunselected", [ev, {
					selectable: self.element,
					unselected: this,
					options: options
				}], options.unselected);
			});
		},
		unselecting: function(self, ev, options) {
			$('.ui-selected', self.element).each(function() {
				if (this != ev.target) {
					$(this).removeClass('ui-selected').addClass('ui-unselecting');
					$(self.element).triggerHandler("selectableunselecting", [ev, {
						selectable: self.element,
						unselecting: this,
						options: options
					}], options.unselecting);
				}
			});
		},
		selectingTarget: function(self, ev, options) {
			var target = $(ev.target);
			if (target.is('.ui-selectee:not(.ui-selecting)')) {
				target.removeClass('ui-selected').removeClass('ui-unselecting').addClass('ui-selecting');
				$(self.element).triggerHandler("selectableselecting", [ev, {
					selectable: self.element,
					selecting: ev.target,
					options: options
				}], options.selecting);
			}
		}
	});
	
})(jQuery);