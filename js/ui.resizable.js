(function($) {

	//Make nodes selectable by expression
	$.extend($.expr[':'], { resizable: "(' '+a.className+' ').indexOf(' ui-resizable ')" });

	
	$.fn.resizable = function(o) {
		return this.each(function() {
			if(!$(this).is(".ui-resizable")) new $.ui.resizable(this,o);	
		});
	}

	//Macros for external methods that support chaining
	var methods = "destroy,enable,disable".split(",");
	for(var i=0;i<methods.length;i++) {
		var cur = methods[i], f;
		eval('f = function() { var a = arguments; return this.each(function() { if(jQuery(this).is(".ui-resizable")) jQuery.data(this, "ui-resizable")["'+cur+'"](a); if(jQuery(this.parentNode).is(".ui-resizable")) jQuery.data(this, "ui-resizable")["'+cur+'"](a); }); }');
		$.fn["resizable"+cur.substr(0,1).toUpperCase()+cur.substr(1)] = f;
	};
	
	//get instance method
	$.fn.resizableInstance = function() {
		if($(this[0]).is(".ui-resizable") || $(this[0].parentNode).is(".ui-resizable")) return $.data(this[0], "ui-resizable");
		return false;
	};
	
	
	$.ui.resizable = function(el,o) {
		
		var options = {}; o = o || {}; $.extend(options, o); //Extend and copy options
		this.element = el; var self = this; //Do bindings
		$.data(this.element, "ui-resizable", this);
		
		if(options.proxy) {
			var helper = function(e,that) {
				var helper = $('<div></div>').css({
					width: $(this).width(),
					height: $(this).height(),
					position: 'absolute',
					left: that.options.co.left,
					top: that.options.co.top
				}).addClass(that.options.proxy);
				return helper;
			}	
		} else {
			var helper = "original";	
		}
		
		//Destructive mode wraps the original element
		if(el.nodeName.match(/textarea|input|select|button|img/i)) options.destructive = true;
		if(options.destructive) {
			
			$(el).wrap('<div class="ui-wrapper"  style="position: relative; width: '+$(el).outerWidth()+'px; height: '+$(el).outerHeight()+';"></div>');
			var oel = el;
			el = el.parentNode; this.element = el;
			
			//Move margins to the wrapper
			$(el).css({ marginLeft: $(oel).css("marginLeft"), marginTop: $(oel).css("marginTop"), marginRight: $(oel).css("marginRight"), marginBottom: $(oel).css("marginBottom")});
			$(oel).css({ marginLeft: 0, marginTop: 0, marginRight: 0, marginBottom: 0});
			
			o.proportionallyResize = o.proportionallyResize || [];
			o.proportionallyResize.push(oel);
			
			var b = [parseInt($(oel).css('borderTopWidth')),parseInt($(oel).css('borderRightWidth')),parseInt($(oel).css('borderBottomWidth')),parseInt($(oel).css('borderLeftWidth'))];
		} else {
			var b = [0,0,0,0];	
		}
		
		if(options.destructive || !$(".ui-resizable-handle",el).length) {
			//Adding handles (disabled not so common ones)
			var t = function(a,b) { $(el).append("<div class='ui-resizable-"+a+" ui-resizable-handle' style='"+b+"'></div>"); };
			//t('n','top: '+b[0]+'px;');
			t('e','right: '+b[1]+'px;'+(options.zIndex ? 'z-index: '+options.zIndex+';' : ''));
			t('s','bottom: '+b[1]+'px;'+(options.zIndex ? 'z-index: '+options.zIndex+';' : ''));
			//t('w','left: '+b[3]+'px;');
			t('se','bottom: '+b[2]+'px; right: '+b[1]+'px;'+(options.zIndex ? 'z-index: '+options.zIndex+';' : ''));
			//t('sw','bottom: '+b[2]+'px; left: '+b[3]+'px;');
			//t('ne','top: '+b[0]+'px; right: '+b[1]+'px;');
			//t('nw','top: '+b[0]+'px; left: '+b[3]+'px;');
		}
		
		
		
		//If other elements should be modified, we have to copy that array
		options.modifyThese = [];
		if(o.proportionallyResize) {
			options.proportionallyResize = o.proportionallyResize.slice(0);
			var propRes = options.proportionallyResize;

			for(var i in propRes) {
				
				if(propRes[i].constructor == String)
					propRes[i] = $(propRes[i], el);
				
				if(!$(propRes[i]).length) continue;
				
				
				var x = $(propRes[i]).width() - $(el).width();
				var y = $(propRes[i]).height() - $(el).height();
				options.modifyThese.push([$(propRes[i]),x,y]);
			}

		}
		
		options.handles = {};
		if(!o.handles) o.handles = { n: '.ui-resizable-n', e: '.ui-resizable-e', s: '.ui-resizable-s', w: '.ui-resizable-w', se: '.ui-resizable-se', sw: '.ui-resizable-sw', ne: '.ui-resizable-ne', nw: '.ui-resizable-nw' };
		
		for(var i in o.handles) { options.handles[i] = o.handles[i]; } //Copying the object
		
		for(var i in options.handles) {
			
			if(options.handles[i].constructor == String)
				options.handles[i] = $(options.handles[i], el);
			
			if(!$(options.handles[i]).length) continue;
				
			$(options.handles[i]).bind('mousedown', function(e) {
				self.interaction.options.axis = this.resizeAxis;
			})[0].resizeAxis = i;
			
		}
		
		//If we want to auto hide the elements
		if(o.autohide)
			$(this.element).addClass("ui-resizable-autohide").hover(function() { $(this).removeClass("ui-resizable-autohide"); }, function() { if(self.interaction.options.autohide && !self.interaction.init) $(this).addClass("ui-resizable-autohide"); });
	

		$.extend(options, {
			helper: helper,
			nonDestructive: true,
			dragPrevention: 'input,button,select',
			minHeight: options.minHeight || 50,
			minWidth: options.minWidth || 100,
			startCondition: function(e) {
				if(self.disabled) return false;
				for(var i in options.handles) {
					if($(options.handles[i])[0] == e.target) return true;
				}
				return false;
			},
			_start: function(h,p,c,t,e) {
				self.start.apply(t, [self, e]); // Trigger the start callback				
			},
			_beforeStop: function(h,p,c,t,e) {
				self.stop.apply(t, [self, e]); // Trigger the stop callback
			},
			_drag: function(h,p,c,t,e) {
				self.drag.apply(t, [self, e]); // Trigger the start callback
			}			
		});
		
		//Initialize mouse interaction
		this.interaction = new $.ui.mouseInteraction(el,options);
		
		//Add the class for themeing
		$(this.element).addClass("ui-resizable");
		
	}
	
	$.extend($.ui.resizable.prototype, {
		plugins: {},
		prepareCallbackObj: function(self) {
			return {
				helper: self.helper,
				resizable: self,
				axis: self.options.axis,
				options: self.options
			}			
		},
		destroy: function() {
			$(this.element).removeClass("ui-resizable").removeClass("ui-resizable-disabled");
			this.interaction.destroy();
		},
		enable: function() {
			$(this.element).removeClass("ui-resizable-disabled");
			this.disabled = false;
		},
		disable: function() {
			$(this.element).addClass("ui-resizable-disabled");
			this.disabled = true;
		},
		start: function(that, e) {
			this.options.originalSize = [$(this.element).width(),$(this.element).height()];
			this.options.originalPosition = $(this.element).css("position");
			this.options.originalPositionValues = $(this.element).position();

			this.options.modifyThese.push([$(this.helper),0,0]);
			
			$(that.element).triggerHandler("resizestart", [e, that.prepareCallbackObj(this)], this.options.start);			
			return false;
		},
		stop: function(that, e) {			
			
			var o = this.options;

			$(that.element).triggerHandler("resizestop", [e, that.prepareCallbackObj(this)], this.options.stop);	

			if(o.proxy) {
				$(this.element).css({
					width: $(this.helper).width(),
					height: $(this.helper).height()
				});
				
				if(o.originalPosition == "absolute" || o.originalPosition == "fixed") {
					$(this.element).css({
						top: $(this.helper).css("top"),
						left: $(this.helper).css("left")
					});					
				}
			}
			return false;
			
		},
		drag: function(that, e) {

			var o = this.options;
			var rel = (o.originalPosition != "absolute" && o.originalPosition != "fixed");
			var co = rel ? o.co : this.options.originalPositionValues;
			var p = o.originalSize;

			this.pos = rel ? [this.rpos[0]-o.cursorAt.left, this.rpos[1]-o.cursorAt.top] : [this.pos[0]-o.cursorAt.left, this.pos[1]-o.cursorAt.top];

			var nw = p[0] + (this.pos[0] - co.left);
			var nh = p[1] + (this.pos[1] - co.top);
		
			if(o.axis) {
				switch(o.axis) {
					case 'e':
						nh = p[1];
						break;
					case 's':
						nw = p[0];
						break;
					case 'n':
					case 'ne':

						
						if(!o.proxy && (o.originalPosition != "absolute" && o.originalPosition != "fixed"))
							return false;
						
						if(o.axis == 'n') nw = p[0];
						var mod = (this.pos[1] - co.top); nh = nh - (mod*2);
						mod = nh <= o.minHeight ? p[1] - o.minHeight : (nh >= o.maxHeight ? 0-(o.maxHeight-p[1]) : mod);
						$(this.helper).css('top', co.top + mod);
						break;
						
					case 'w':
					case 'sw':

						if(!o.proxy && (o.originalPosition != "absolute" && o.originalPosition != "fixed"))
							return false;
						
						if(o.axis == 'w') nh = p[1];
						var mod = (this.pos[0] - co.left); nw = nw - (mod*2);
						mod = nw <= o.minWidth ? p[0] - o.minWidth : (nw >= o.maxWidth ? 0-(o.maxWidth-p[0]) : mod);
						$(this.helper).css('left', co.left + mod);
						break;
						
					case 'nw':
						
						if(!o.proxy && (o.originalPosition != "absolute" && o.originalPosition != "fixed"))
							return false;
	
						var modx = (this.pos[0] - co.left); nw = nw - (modx*2);
						modx = nw <= o.minWidth ? p[0] - o.minWidth : (nw >= o.maxWidth ? 0-(o.maxWidth-p[0]) : modx);
						
						var mody = (this.pos[1] - co.top); nh = nh - (mody*2);
						mody = nh <= o.minHeight ? p[1] - o.minHeight : (nh >= o.maxHeight ? 0-(o.maxHeight-p[1]) : mody);

						$(this.helper).css({
							left: co.left + modx,
							top: co.top + mody
						});
						
						break;
				}	
			}

			if(e.shiftKey) nh = nw * (p[1]/p[0]);
			
			if(o.minWidth) nw = nw <= o.minWidth ? o.minWidth : nw;
			if(o.minHeight) nh = nh <= o.minHeight ? o.minHeight : nh;
			
			if(o.maxWidth) nw = nw >= o.maxWidth ? o.maxWidth : nw;
			if(o.maxHeight) nh = nh >= o.maxHeight ? o.maxHeight : nh;
			
			if(e.shiftKey) nh = nw * (p[1]/p[0]);

			var modifier = $(that.element).triggerHandler("resize", [e, that.prepareCallbackObj(this)], o.resize);
			if(!modifier) modifier = {};
			
			for(var i in this.options.modifyThese) {
				var c = this.options.modifyThese[i];
				c[0].css({
					width: modifier.width ? modifier.width+c[1] : nw+c[1],
					height: modifier.height ? modifier.height+c[2] : nh+c[2]
				});
			}
			return false;
			
		}
	});

})($);
