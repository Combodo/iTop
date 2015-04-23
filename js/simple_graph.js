// jQuery UI style "widget" for displaying a graph

////////////////////////////////////////////////////////////////////////////////
//
// graph
//
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "dashboard" the widget name
	$.widget( "itop.simple_graph",
	{
		// default options
		options:
		{
			xmin: 0,
			xmax: 0,
			ymin: 0,
			ymax: 0,
			align: 'center',
			'vertical-align': 'middle'
		},
	
		// the constructor
		_create: function()
		{
			var me = this;
			this.aNodes = [];
			this.aEdges = [];
			this.fZoom = 1.0;
			this.xOffset = 0;
			this.yOffset = 0;
			this.iTextHeight = 12;
			//this.element.height(this.element.parent().height());
			this.oPaper = Raphael(this.element.get(0), this.element.width(), this.element.height());
			
			this.auto_scale();

			this.element
			.addClass('itop-simple-graph');
			
			this._create_toolkit_menu();
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
			this.draw();
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			var sId = this.element.attr('id');
			this.element
			.removeClass('itop-simple-graph');
			
			$('#tk_graph'+sId).remove();
			
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			this._superApply(arguments);
		},
		draw: function()
		{
			this.oPaper.clear();
			for(var k in this.aNodes)
			{
				this._draw_node(this.aNodes[k]);
			}
			for(var k in this.aEdges)
			{
				this._draw_edge(this.aEdges[k]);
			}
		},
		_draw_node: function(oNode)
		{
			var iWidth = oNode.width;
			var iHeight = 32;
			var xPos = Math.round(oNode.x * this.fZoom + this.xOffset);
			var yPos = Math.round(oNode.y * this.fZoom + this.yOffset);
			oNode.tx = 0;
			oNode.ty = 0;
			switch(oNode.shape)
			{
				case 'disc':
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, iWidth*this.fZoom / 2).attr(oNode.disc_attr));
				var oText = this.oPaper.text(xPos, yPos, oNode.label);
				oText.attr(oNode.text_attr);
				oText.transform('s'+this.fZoom);
				oNode.aElements.push(oText);
				break;
					
				case 'group':
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, iWidth*this.fZoom / 2).attr({fill: '#fff', 'stroke-width':0}));
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, iWidth*this.fZoom / 2).attr(oNode.disc_attr));
				var xIcon = xPos - 18 * this.fZoom;
				var yIcon = yPos - 18 * this.fZoom;
				oNode.aElements.push(this.oPaper.image(oNode.icon_url, xIcon, yIcon, 16*this.fZoom, 16*this.fZoom).attr(oNode.icon_attr));
				oNode.aElements.push(this.oPaper.image(oNode.icon_url, xIcon + 18*this.fZoom, yIcon, 16*this.fZoom, 16*this.fZoom).attr(oNode.icon_attr));
				oNode.aElements.push(this.oPaper.image(oNode.icon_url, xIcon + 9*this.fZoom, yIcon + 18*this.fZoom, 16*this.fZoom, 16*this.fZoom).attr(oNode.icon_attr));
				var oText = this.oPaper.text(xPos, yPos +2, oNode.label);
				oText.attr(oNode.text_attr);
				oText.transform('s'+this.fZoom);
				var oBB = oText.getBBox();
				var dy = iHeight/2*this.fZoom + oBB.height/2;
				oText.remove();
				oText = this.oPaper.text(xPos, yPos +dy +2, oNode.label);
				oText.attr(oNode.text_attr);
				oText.transform('s'+this.fZoom);
				oNode.aElements.push(oText);
				oNode.aElements.push(this.oPaper.rect( xPos - oBB.width/2 -2, yPos - oBB.height/2 + dy, oBB.width +4, oBB.height).attr({fill: '#fff', stroke: '#fff', opacity: 0.9}));
				oText.toFront();
				break;
					
				case 'icon':
				if(Raphael.svg)
				{
					// the colorShift plugin works only in SVG
					oNode.aElements.push(this.oPaper.image(oNode.icon_url, xPos - iWidth * this.fZoom/2, yPos - iHeight * this.fZoom/2, iWidth*this.fZoom, iHeight*this.fZoom).colorShift('#fff', 1));					
				}
				oNode.aElements.push(this.oPaper.image(oNode.icon_url, xPos - iWidth * this.fZoom/2, yPos - iHeight * this.fZoom/2, iWidth*this.fZoom, iHeight*this.fZoom).attr(oNode.icon_attr));
				var oText = this.oPaper.text( xPos, yPos, oNode.label);
				oText.attr(oNode.text_attr);
				oText.transform('s'+this.fZoom);
				var oBB = oText.getBBox();
				var dy = iHeight/2*this.fZoom + oBB.height/2;
				oText.remove();
				oText = this.oPaper.text( xPos, yPos + dy, oNode.label);
				oText.attr(oNode.text_attr);
				oText.transform('s'+this.fZoom);
				oNode.aElements.push(oText);
				oNode.aElements.push(this.oPaper.rect( xPos - oBB.width/2 -2, yPos - oBB.height/2 + dy, oBB.width +4, oBB.height).attr({fill: '#fff', stroke: '#fff', opacity: 0.9}).toBack());
				break;
			}
			if (oNode.source)
			{
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, 1.25*iWidth*this.fZoom / 2).attr({stroke: '#c33', 'stroke-width': 3*this.fZoom }).toBack());
			}
			if (oNode.sink)
			{
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, 1.25*iWidth*this.fZoom / 2).attr({stroke: '#33c', 'stroke-width': 3*this.fZoom }).toBack());
			}
			
			var me = this;
			for(k in oNode.aElements)
			{
				var sNodeId = oNode.id;
				oNode.aElements[k].drag(function(dx, dy, x, y, event) { me._move(sNodeId, dx, dy, x, y, event); }, function(x, y, event) { me._drag_start(sNodeId, x, y, event); }, function (event) { me._drag_end(sNodeId, event); });
			}
		},
		_move: function(sNodeId, dx, dy, x, y, event)
		{
			var origDx = dx / this.fZoom;
			var origDy = dy / this.fZoom;
			
			var oNode = this._find_node(sNodeId);
			oNode.x = oNode.xOrig + origDx;
			oNode.y = oNode.yOrig + origDy;
			
			for(k in oNode.aElements)
			{
				oNode.aElements[k].transform('t'+(oNode.tx + dx)+', '+(oNode.ty + dy));
				
				for(j in this.aEdges)
				{
					var oEdge = this.aEdges[j];
					if ((oEdge.source_node_id == sNodeId) || (oEdge.sink_node_id == sNodeId))
					{
						var sPath = this._get_edge_path(oEdge);
						oEdge.aElements[0].attr({path: sPath});
					}
				}
			}
		},
		_drag_start: function(sNodeId, x, y, event)
		{
			var oNode = this._find_node(sNodeId);
			oNode.xOrig = oNode.x;
			oNode.yOrig = oNode.y;
			
		},
		_drag_end: function(sNodeId, event)
		{
			var oNode = this._find_node(sNodeId);
			oNode.tx += (oNode.x - oNode.xOrig) * this.fZoom;
			oNode.ty += (oNode.y - oNode.yOrig) * this.fZoom;
			oNode.xOrig = oNode.x;
			oNode.yOrig = oNode.y;
		},
		_get_edge_path: function(oEdge)
		{
			var oStart = this._find_node(oEdge.source_node_id);
			var oEnd = this._find_node(oEdge.sink_node_id);
			var iArrowSize = 5;
			
			if ((oStart == null) || (oEnd == null)) return '';
			
			var xStart = Math.round(oStart.x * this.fZoom + this.xOffset);
			var yStart = Math.round(oStart.y * this.fZoom + this.yOffset);
			var xEnd = Math.round(oEnd.x * this.fZoom + this.xOffset);
			var yEnd = Math.round(oEnd.y  * this.fZoom + this.yOffset);

			var sPath = Raphael.format('M{0},{1}L{2},{3}', xStart, yStart, xEnd, yEnd);
			var vx = (xEnd - xStart);
			var vy = (yEnd - yStart);
			var l = Math.sqrt(vx*vx+vy*vy);
			vx = vx / l;
			vy = vy / l;
			var ux = -vy;
			var uy = vx;
			var lPos = Math.max(l/2, l - 40*this.fZoom);
			var xArrow = xStart + vx * lPos;
			var yArrow = yStart + vy * lPos;
			sPath += Raphael.format('M{0},{1}l{2},{3}M{4},{5}l{6},{7}', xArrow, yArrow, this.fZoom * iArrowSize *(-vx + ux),  this.fZoom * iArrowSize *(-vy + uy), xArrow, yArrow, this.fZoom * iArrowSize *(-vx - ux),  this.fZoom * iArrowSize *(-vy - uy));
			return sPath;
		},
		_draw_edge: function(oEdge)
		{
			var fStrokeSize = Math.max(1, 2 * this.fZoom);			
			var sPath = this._get_edge_path(oEdge);
			var oAttr = $.extend(oEdge.attr);
			oAttr['stroke-linecap'] = 'round';
			oAttr['stroke-width'] = fStrokeSize;		
			oEdge.aElements.push(this.oPaper.path(sPath).attr(oAttr).toBack());
		},
		_find_node: function(sId)
		{
			for(var k in this.aNodes)
			{
				if (this.aNodes[k].id == sId) return this.aNodes[k];
			}
			return null;
		},
		auto_scale: function()
		{
			var fMaxZoom = 1.5;
			iMargin = 10;
			xmin = this.options.xmin - iMargin;
			xmax = this.options.xmax + iMargin;
			ymin = this.options.ymin - iMargin;
			ymax = this.options.ymax + iMargin;
			var xScale = this.element.width() / (xmax - xmin);
			var yScale = this.element.height() / (ymax - ymin + this.iTextHeight);
			
			this.fZoom = Math.min(xScale, yScale, fMaxZoom);
			switch(this.options.align)
			{
				case 'left':
				this.xOffset = -xmin * this.fZoom;
				break;
				
				case 'right':
				this.xOffset = (this.element.width() - (xmax - xmin) * this.fZoom);
				break;
				
				case 'center':
				this.xOffset = (this.element.width() - (xmax - xmin) * this.fZoom) / 2;
				break;			
			}
			switch(this.options['vertical-align'])
			{
				case 'top':
				this.yOffset = -ymin * this.fZoom;
				break;
				
				case 'bottom':
				this.yOffset = this.element.height() - (ymax + this.iTextHeight) * this.fZoom;
				break;
				
				case 'middle':
				this.yOffset = (this.element.height() - (ymax - ymin + this.iTextHeight) * this.fZoom) / 2;
				break;			
			}
			
			
		},
		add_node: function(oNode)
		{
			oNode.aElements = [];
			this.aNodes.push(oNode);
		},
		add_edge: function(oEdge)
		{
			oEdge.aElements = [];
			this.aEdges.push(oEdge);
		},
		_create_toolkit_menu: function()
		{
			var sPopupMenuId = 'tk_graph'+this.element.attr('id');
			var sHtml = '<div class="itop_popup toolkit_menu" style="font-size: 12px;" id="'+sPopupMenuId+'"><ul><li><img src="../images/toolkit_menu.png"><ul>';
			sHtml += '<li><a href="#" id="'+sPopupMenuId+'_pdf">Export as PDF</a></li>';
			sHtml += '<li><a href="#" id="'+sPopupMenuId+'_document">Export as document...</a></li>';
			sHtml += '<li><a href="#" id="'+sPopupMenuId+'_reload">Refresh</a></li>';
			sHtml += '</ul></li></ul></div>';
			
			this.element.before(sHtml);
			$('#'+sPopupMenuId).popupmenu();
			
			var me = this;
			$('#'+sPopupMenuId+'_pdf').click(function() { me.export_as_pdf(); });
			$('#'+sPopupMenuId+'_document').click(function() { me.export_as_document(); });
			$('#'+sPopupMenuId+'_reload').click(function() { me.reload(); });
			
		},
		export_as_pdf: function()
		{
			alert('Export as PDF: not yet implemented');
		},
		export_as_document: function()
		{
			alert('Export as document: not yet implemented');
		},
		reload: function()
		{
			alert('Reload: not yet implemented');
		}
	});	
});
