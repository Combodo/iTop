// jQuery UI style "widget" for charts
Raphael.fn.ball = function (x, y, r, hue)
{
	hue = hue || 0;
	return this.set(
	    this.ellipse(x, y + r - r / 3, r, r / 2).attr({fill: "rhsb(" + hue + ", 0, .25)-hsb(" + hue + ", 0, .25)", stroke: "none", opacity: 0}),
	    this.ellipse(x, y, r, r).attr({fill: "0-#000-#ccc-#000", stroke: "none"}),
	    this.ellipse(x, y, r*0.95, r*0.95 ).attr({fill: "r(.5,.9)hsb(" + hue + ", 0, .75)-hsb(" + hue + ", 0, .25)", stroke: "none"}),
	    this.ellipse(x, y, r - r / 5, r - r / 20).attr({stroke: "none", fill: "r(.5,.1)#ccc-#ccc", opacity: 0})
	);
};
    
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "pie_chart" the widget name
	$.widget( "itop.pie_chart",
	{
		// default options
		options:
		{
			chart_id: '',
			chart_label: '',
			values: [],
			labels: [],
			hrefs: []
		},
	
		// the constructor
		_create: function()
		{
			var me = this; 

			this.element
			.addClass('itop-pie_chart');
			
			this.oR = Raphael(this.element.get(0), this.element.width(), this.element.height());
			$(window).bind('resize.pie_chart', function() { me._refresh(); });
			this._refresh();
		},
		_clear_r: function()
		{
			this.oR.clear();
		},
		// called when created, and later when changing options
		_refresh: function()
		{
			this._clear_r();
			
			var me = this;
			this._compute_size();
			this.oR.ball(this.x, this.y, this.r, 0);

			var aColors = [];
			var hue = 0;
			var brightness = 1;	
			for(index = 0; index < 30; index++)
			{
				hue = (hue+137) % 360;
				brightness = 1-((Math.floor(index / 3) % 4) / 8);
				aColors.push('hsba('+(hue/360.0)+',1,'+brightness+',0.6)');
				//aColors.push('hsba('+(hue/360.0)+',0.5,0.5,0.4)');
			}


			var aVals = this.options.values.slice(0); // Clone the array since the pie function will alter it
			this.pie = this.oR.piechart(this.x, this.y, this.r, aVals, { legend: this.options.labels, legendpos: "east", href: this.options.hrefs, colors: aColors });	
			this.oR.text(this.x, 10, this.options.chart_label).attr({ font: "20px 'Fontin Sans', Fontin-Sans, sans-serif" });
			this.pie.hover(
			function ()
			{
				var positiveAngle = (360 + this.mangle) % 360;
				this.sector.attr({opacity: 0.5});
				//this.sector.stop();
				//this.sector.scale(1.1, 1.1, this.cx, this.cy);


				if (this.label)
				{
					//this.label[0].stop();
					//this.label[0].attr({ r: 7.5 });
					this.label[1].attr({ "font-weight": 800 });
				
				}
				if (this.label_highlight == undefined)
				{
					var oBBox = this.label.getBBox();
					this.label_highlight = this.label_highlight || me.oR.rect(oBBox.x - 2, oBBox.y - 2, oBBox.width + 4, oBBox.height + 4, 4).attr({'stroke': '#ccc', fill: '#ccc'}).toBack();
				}
				this.label_highlight.show();

				//this.marker = this.marker || r.label(this.mx, this.my, this.value.value, 0, 12);
				var alpha = 2*Math.PI * this.mangle / 360;
				var iDir = Math.floor(((45 +  360 + this.mangle) % 360) / 90);
				var aDirs = ['right', 'up', 'left', 'down'];
				var sDir = aDirs[iDir];
				this.marker = this.marker || me.oR.popup(this.cx + Math.cos(alpha) *(this.r), this.cy - Math.sin(alpha) *(this.r), me.options.labels[this.value.order]+': '+this.value.valueOf(), sDir);
				this.marker.show();

			},
			function ()
			{
				this.sector.attr({opacity:1});
				//this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500);
				if (this.label)
				{
					//this.label[0].animate({ r: 5 }, 200, "bounce");
					this.label[1].attr({ "font-weight": 400 });
				}
				this.marker && this.marker.hide();
				this.label_highlight && this.label_highlight.hide();

			});
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('itop-pie_chart');
			
			$(window).unbind('resize.pie_chart');			
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			// in 1.9 would use _super
			this._superApply(arguments);
		},
		_compute_size: function()
		{
			var legendWidth = 100;
			var titleHeight = 20;
			var iW = this.element.width();
			var iH = this.element.height();
			
			if (iH == 0)
			{
				iH = 0.75*iW;
				this.element.height(iH);
			}
			this.r = (6*Math.min(iW-legendWidth, iH-titleHeight)/7) / 2; // 1/6 is for the drop shadow
			
			this.x = (iW-legendWidth) / 2;
			this.y = titleHeight+(iH-titleHeight) / 2;
		},
		_draw_ball: function(x, y, r)
		{
			return this.oR.set(
			    this.oR.ellipse(x, y + r - r / 3, r, r / 2).attr({fill: "rhsb(1, 0, .25)-hsb(1, 0, .25)", stroke: "none", opacity: 0}),
			    this.oR.ellipse(x, y, r, r).attr({fill: "0-#000-#ccc-#000", stroke: "none"}),
			    this.oR.ellipse(x, y, r*0.95, r*0.95 ).attr({fill: "r(.5,.9)hsb(1, 0, .75)-hsb(1, 0, .25)", stroke: "none"}),
			    this.oR.ellipse(x, y, r - r / 5, r - r / 20).attr({stroke: "none", fill: "r(.5,.1)#ccc-#ccc", opacity: 0})
			);
		}
	});	
});

$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "heatmap_chart" the widget name
	$.widget( "itop.heatmap_chart",
	{
		// default options
		options:
		{
			chart_id: '',
			chart_label: '',
			hrefs: {},
			values: {},
			axis_x: {},
			axis_y: {}
		},
	
		// the constructor
		_create: function()
		{
			var me = this; 

			this.element
			.addClass('itop-heatmap_chart');
			
			this.oR = Raphael(this.element.get(0), this.element.width(), this.element.height());
			this._compute_size();
			this.oR.text(this.x, 10, this.options.chart_label).attr({ font: "20px 'Fontin Sans', Fontin-Sans, sans-serif" });

			var iX = 0;
			
			var xs = [];
			var axisx = [];
			var ys = [];
			var axisy = [];
			var data = [];
			var hrefs = [];
			for(var x in this.options.axis_x)
			{
				var iY = 0;
				axisx.push(this.options.axis_x[x]);
				
				for(var y in this.options.axis_y)
				{
					xs.push(iX);
					ys.push(iY);
					data.push(this.options.values[x][y]);
					// Not working yet
					//hrefs.push(this.options.hrefs[x][y]);
					iY = iY + 1;
				}
				iX = iX + 1;
			}
			for(var y in this.options.axis_y)
			{
				axisy.push(this.options.axis_y[y]);
			}
			/*
			xs = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
            ys = [7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
            data = [294, 300, 204, 255, 348, 383, 334, 217, 114, 33, 44, 26, 41, 39, 52, 17, 13, 2, 0, 2, 5, 6, 64, 153, 294, 313, 195, 280, 365, 392, 340, 184, 87, 35, 43, 55, 53, 79, 49, 19, 6, 1, 0, 1, 1, 10, 50, 181, 246, 246, 220, 249, 355, 373, 332, 233, 85, 54, 28, 33, 45, 72, 54, 28, 5, 5, 0, 1, 2, 3, 58, 167, 206, 245, 194, 207, 334, 290, 261, 160, 61, 28, 11, 26, 33, 46, 36, 5, 6, 0, 0, 0, 0, 0, 0, 9, 9, 10, 7, 10, 14, 3, 3, 7, 0, 3, 4, 4, 6, 28, 24, 3, 5, 0, 0, 0, 0, 0, 0, 4, 3, 4, 4, 3, 4, 13, 10, 7, 2, 3, 6, 1, 9, 33, 32, 6, 2, 1, 3, 0, 0, 4, 40, 128, 212, 263, 202, 248, 307, 306, 284, 222, 79, 39, 26, 33, 40, 61, 54, 17, 3, 0, 0, 0, 3, 7, 70, 199],
            axisy = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            axisx = ["12am", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12pm", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11"];
			*/
            this.oR.dotchart(10, this.y, this.width, this.height, xs, ys, data, {symbol: "o", max: 30, heat: true, axis: "0 0 1 1", axisxstep: axisx.length - 1, axisystep: axisy.length - 1, axisxlabels: axisx, axisxtype: " ", axisytype: " ", axisylabels: axisy, href: hrefs}).hover(function () {
	            this.marker = this.marker || me.oR.tag(this.x, this.y, this.value, 0, this.r + 2).insertBefore(this);
	            this.marker.show();
	        }, function () {
	            this.marker && this.marker.hide();
	        });
	        
			
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('itop-heatmap_chart');		
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			// in 1.9 would use _super
			this._superApply(arguments);
		},
		_compute_size: function()
		{
			var titleHeight = 20;
			var iW = this.element.width();
			var iH = this.element.height();
			
			if (iH == 0)
			{
				iH = 0.75*iW;
				this.element.height(iH);
			}
			this.x = (iW) / 2;
			this.y = titleHeight;
			this.width = iW;
			this.height = iH - titleHeight;
		}
	});	
});