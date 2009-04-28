(function($) {

	
	//Web Forms 2.0
	window.webforms = 1;
	if(window['webforms']) {
		$(document).ready(function() {
			
			$("input").each(function() {
				if(this.getAttribute("type") == "range") {
					var cur = $(this);
					var slider = $("<div class='ui-slider'></div>").css({ width: cur.innerWidth()+"px", height: cur.innerHeight()+"px" }).insertAfter(cur);
					var handle = $("<div class='ui-slider-handle'></div>").appendTo(slider);


					slider.css({
						"position": cur.css("position") == "absolute" ? "absolute" : "relative",
						"left": cur.css("left"),
						"right": cur.css("right"),
						"zIndex": cur.css("zIndex"),
						"float": cur.css("float"),
						"clear": cur.css("clear")
					});
					cur.css({ position: "absolute", opacity: 0, top: "-1000px", left: "-1000px" });
					
					slider.slider({
						maxValue: cur.attr("max"),
						minValue: cur.attr("min"),
						startValue: this.getAttribute("value"),
						stepping: cur.attr("step"),
						change: function(e, ui) { cur[0].value = ui.value; cur[0].setAttribute("value", ui.value); },
					});
					
					slider = slider.sliderInstance();
					
					cur.bind("keydown", function(e) {
						var o = slider.interaction.options;
						switch(e.keyCode) {
							case 37:
								slider.moveTo(slider.interaction.curValue+o.minValue-(o.stepping || 1));
								break;
							case 39:
								slider.moveTo(slider.interaction.curValue+o.minValue+(o.stepping || 1));
								break;	
						}
						if(e.keyCode != 9) return false;
					});
					
				};	
			});
				
		});
	}

	//Make nodes selectable by expression
	$.extend($.expr[':'], { slider: "(' '+a.className+' ').indexOf(' ui-slider ')" });
	
	$.fn.slider = function(o) {
		return this.each(function() {
			new $.ui.slider(this, o);
		});
	}
	
	//Macros for external methods that support chaining
	var methods = "destroy,enable,disable,moveTo".split(",");
	for(var i=0;i<methods.length;i++) {
		var cur = methods[i], f;
		eval('f = function() { var a = arguments; return this.each(function() { if(jQuery(this).is(".ui-slider")) jQuery.data(this, "ui-slider")["'+cur+'"](a); }); }');
		$.fn["slider"+cur.substr(0,1).toUpperCase()+cur.substr(1)] = f;
	};
	
	//get instance method
	$.fn.sliderInstance = function() {
		if($(this[0]).is(".ui-slider")) return $.data(this[0], "ui-slider");
		return false;
	};
	
	$.ui.slider = function(el, o) {
		
		var options = {};
		o = o || {};
		$.extend(options, o);
		$.extend(options, {
			axis: o.axis || (el.offsetWidth < el.offsetHeight ? 'vertical' : 'horizontal'),
			maxValue: parseInt(o.maxValue) || 100,
			minValue: parseInt(o.minValue) || 0,
			startValue: parseInt(o.startValue) || 0,
			_start: function(h, p, c, t, e) {
				self.start.apply(t, [self, e]); // Trigger the start callback				
			},
			_beforeStop: function(h, p, c, t, e) {
				self.stop.apply(t, [self, e]); // Trigger the start callback
			},
			_drag: function(h, p, c, t, e) {
				self.drag.apply(t, [self, e]); // Trigger the start callback
			},
			startCondition: function() {
				return !self.disabled;
			}			
		});

		var self = this;
		var o = options;
		$.data(el, "ui-slider", this);
		o.stepping = parseInt(o.stepping) || (o.steps ? o.maxValue/o.steps : 0);
		o.realValue = (o.maxValue - o.minValue);


		this.handle = options.handle ? $(options.handle, el) : $('.ui-slider-handle', el);
		if(this.handle.length == 1) {
			this.interaction = new $.ui.mouseInteraction(this.handle[0], options);
			this.multipleHandles = false;
		} else {
			this.interactions = [];
			this.handle.each(function() {
				self.interactions.push(new $.ui.mouseInteraction(this, options));
			});
			this.multipleHandles = true;
		}
		
		this.element = el;
		$(this.element).addClass("ui-slider");
		
		
		if(o.axis == 'horizontal') {
			this.parentSize = $(this.element).outerWidth() - this.handle.outerWidth();
			this.prop = 'left';
		}
		
		if(o.axis == 'vertical') {
			this.parentSize = $(this.element).outerHeight() - this.handle.outerHeight();
			this.prop = 'top';
		}
		
		if(!this.multipleHandles) {
			$(el).bind('click', function(e) { self.click.apply(self, [e]); });
			if(!isNaN(o.startValue)) this.moveTo(o.startValue,options.realValue, null, false);
		}
		
	}
	
	$.extend($.ui.slider.prototype, {
		currentTarget: null,
		lastTarget: null,
		destroy: function() {
			$(this.element).removeClass("ui-slider").removeClass("ui-slider-disabled");
			this.interaction.destroy();
		},
		enable: function() {
			$(this.element).removeClass("ui-slider-disabled");
			this.disabled = false;
		},
		disable: function() {
			$(this.element).addClass("ui-slider-disabled");
			this.disabled = true;
		},
		nonvalidRange: function(self) {

			for(var i=0;i<this.interactions.length;i++) {
				if(self == this.interactions[i]) {
					if(this.interactions[i-1]) {
						if(this.interactions[i-1].curValue > this.interactions[i].curValue) return this.interactions[i-1].curValue;
					}
					
					if(this.interactions[i+1]) {
						if(this.interactions[i+1].curValue < this.interactions[i].curValue) return this.interactions[i+1].curValue;
					}
				}
			}
			
			return false;
			
		},
		prepareCallbackObj: function(self,m) {
			
			var cur = this;
			var func = function() {
				var retVal = [];
				for(var i=0;i<cur.interactions.length;i++) {
					retVal.push((cur.interactions[i].curValue || 0)+self.options.minValue);
				}
				return retVal;
			};
			
			return {
				handle: self.helper,
				pixel: m,
				value: self.curValue+self.options.minValue,
				values: this.multipleHandles ? func() : self.curValue+self.options.minValue,
				slider: self	
			}			
		},
		click: function(e) {
			var o = this.interaction.options;
			var pointer = [e.pageX,e.pageY];
			var offset = $(this.interaction.element).offsetParent().offset({ border: false });
			if(this.interaction.element == e.target || this.disabled) return;
			
			this.interaction.pickValue = this.interaction.curValue;
			this.drag.apply(this.interaction, [this, e, [pointer[0]-offset.left-this.handle[0].offsetWidth/2,pointer[1]-offset.top-this.handle[0].offsetHeight/2]]);
			
			if(this.interaction.pickValue != this.interaction.curValue)
				$(this.element).triggerHandler("slidechange", [e, this.prepareCallbackObj(this.interaction)], o.change);
				
		},
		start: function(that, e) {
			
			var o = this.options;
			$(that.element).triggerHandler("slidestart", [e, that.prepareCallbackObj(this)], o.start);
			this.pickValue = this.curValue;
			
			return false;
						
		},
		stop: function(that, e) {			
			
			var o = this.options;
			$(that.element).triggerHandler("slidestop", [e, that.prepareCallbackObj(this)], o.stop);
			if(this.pickValue != this.curValue) $(that.element).triggerHandler("slidechange", [e, that.prepareCallbackObj(this)], o.change);

			return false;
			
		},
		drag: function(that, e, pos) {

			var o = this.options;
			this.pos = pos || [this.pos[0]-this.element.offsetWidth/2, this.pos[1]-this.element.offsetHeight/2];
			
			if(o.axis == 'horizontal') var m = this.pos[0];
			if(o.axis == 'vertical')   var m = this.pos[1];
			
			
			var p = that.parentSize;
			var prop = that.prop;

			if(m < 0) m = 0;
			if(m > p) m = p;

			this.curValue = (Math.round((m/p)*o.realValue));
			if(o.stepping) {
				this.curValue = Math.round(this.curValue/o.stepping)*o.stepping;
				m = ((this.curValue)/o.realValue) * p;
			}
			
			if(that.interactions) {
				nonvalidRange = that.nonvalidRange(this);
				if(nonvalidRange) {
					this.curValue = nonvalidRange;
					m = ((this.curValue)/o.realValue) * p;
				}
			}
			
			$(this.element).css(prop, m+'px');
			$(that.element).triggerHandler("slide", [e, that.prepareCallbackObj(this,m)], o.slide);
			return false;
			
		},
		moveTo: function(value,scale,changeslide,p) {	// renamed from goto to moveTo as goto is reserved javascript word
			
			if(this.multipleHandles) return false; //TODO: Multiple handle moveTo function
			
			var o = this.interaction.options;
			var offset = $(this.interaction.element).offsetParent().offset({ border: false });
			this.interaction.pickValue = this.interaction.curValue;
			value = value-o.minValue;
			
			var modifier = scale || o.realValue;
			
			var p = this.parentSize;
			var prop = this.prop;
			
			m = Math.round(((value)/modifier) * p);

			if(m < 0) m = 0;
			if(m > p) m = p;
			
			this.interaction.curValue = (Math.round((m/p)*o.realValue));
			if(o.stepping) {
				this.interaction.curValue = Math.round(this.interaction.curValue/o.stepping)*o.stepping;
				m = ((this.interaction.curValue)/o.realValue) * p;
			}

			$(this.interaction.element).css(prop, m+'px');
			
			if(!changeslide && this.interaction.pickValue != this.interaction.curValue && !p)
				$(this.element).triggerHandler("slidechange", [e, this.prepareCallbackObj(this.interaction)], o.change);
			
			if(changeslide)
				$(this.element).triggerHandler("slide", [e, this.prepareCallbackObj(this.interaction)], o.slide);

		}
	});

})($);
