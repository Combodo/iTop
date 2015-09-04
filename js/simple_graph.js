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
			align: 'center',
			'vertical-align': 'middle',
			source_url: null,
			sources: {},
			excluded: {},
			export_as_pdf: null,
			page_format: { label: 'Page Format:', values: { A3: 'A3', A4: 'A4', Letter: 'Letter' }, 'default': 'A4'},
			page_orientation: { label: 'Page Orientation:', values: { P: 'Portait', L: 'Landscape' }, 'default': 'L' },
			labels: {
				export_pdf_title: 'PDF Export Options',
				cancel: 'Cancel', 'export': 'Export',
				title: 'Document Title',
				include_list: 'Include the list of objects',
				comments: 'Comments',
				grouping_threshold: 'Grouping Threshold',
				additional_context_info: 'Additional Context Info',
				refresh: 'Refresh',
				check_all: 'Check All',
				uncheck_all: 'Uncheck All',
				none_selected: 'None',
				nb_selected: '# selected',
				zoom: 'Zoom',
				loading: 'Loading...'
			},
			export_as_document: null,
			drill_down: null,
			grouping_threshold: 10,
			excluded_classes: [],
			attachment_obj_class: null,
			attachment_obj_key: null,
			additional_contexts: [],
			context_key: ''
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
			this.xPan = 0;
			this.yPan = 0;
			this.iTextHeight = 12;
			this.fSliderZoom = 1.0;
			this.bInUpdateSliderZoom = false;
			this.bRedrawNeeded = false;
			
			this.oPaper = Raphael(this.element.get(0), 16*this.element.width(), 16*this.element.height());

			this.element
			.addClass('panel-resized')
			.addClass('itop-simple-graph')
			.addClass('graph');
			
			this._create_toolkit_menu();
			this._build_context_menus();
			this.sTabId = null;
			var jTabPanel = this.element.closest('.ui-tabs-panel');
			if (jTabPanel.length > 0)
			{
				// We are inside a tab, find out which one and hook its activation
				this.sTabId = jTabPanel.attr('id');
				var jTabs = this.element.closest('.ui-tabs');
				jTabs.on( "tabsactivate", function( event, ui ) {
					me._on_tabs_activate(ui);
				});					
			}
			$(window).bind('resized', function() { var that = me; window.setTimeout(function() { that._on_resize(); }, 50); } );
			$('#dh_flash').bind('toggle_complete', function() { var that = me; window.setTimeout(function() { that._on_resize(); }, 50); } );
			this.element.bind('mousewheel', function(event, delta, deltaX, deltaY) {
			    return me._on_mousewheel(event, delta, deltaX, deltaY);
			});
			if (this.options.source_url != null)
			{
				this.load_from_url(this.options.source_url);
			}
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
			.removeClass('itop-simple-graph')
			.removeClass('graph');
			
			$('#tk_graph'+sId).remove();
			$('#graph_'+sId+'_export_as_pdf').remove();
			
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
			this._updateBBox();
			this.auto_scale();
			this.oPaper.clear();
			this._reset
			this.oPaper.setViewBox(this.xPan, this.yPan, this.element.width(),  this.element.height(), false);
			for(var k in this.aNodes)
			{
				this.aNodes[k].aElements = [];
				this._draw_node(this.aNodes[k]);
			}
			for(var k in this.aEdges)
			{
				this.aEdges[k].aElements = [];
				this._draw_edge(this.aEdges[k]);
			}
			var me = this;
			this.oBackground = this.oPaper
			.rect(-10000, -10000, 20000, 20000)
			.attr({fill: '#fff', opacity: 0, cursor: 'move'})
			.toBack()
			.drag(function(dx, dy, x, y, event) { me._on_background_move(dx, dy, x, y, event); }, function(x, y, event) { me._on_background_drag_start(x, y, event); }, function (event) { me._on_background_drag_end(event); });
			this._make_tooltips();
		},
		_draw_node: function(oNode)
		{
			var iWidth = oNode.width;
			var iHeight = 32;
			var iFontSize = 10;
			var fTotalZoom = this.fZoom * this.fSliderZoom;
			var xPos = Math.round(oNode.x * fTotalZoom + this.xOffset);
			var yPos = Math.round(oNode.y * fTotalZoom + this.yOffset);
			oNode.tx = 0;
			oNode.ty = 0;
			switch(oNode.shape)
			{
				case 'disc':
				oScaledAttr = {};
				for(k in oNode.disc_attr)
				{
					value = oNode.disc_attr[k]
					switch(k)
					{
						// Scalable attributes
						case 'stroke-width':
						value = value * fTotalZoom;
						break;
					}
					oScaledAttr[k] = value;
				}
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, iWidth*fTotalZoom / 2).attr(oScaledAttr));
				var oText = this.oPaper.text(xPos, yPos, oNode.label);
				oNode.text_attr['font-size'] = iFontSize * fTotalZoom;
				oText.attr(oNode.text_attr);
				//oText.transform('s'+this.fZoom);
				oNode.aElements.push(oText);
				break;
					
				case 'group':
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, iWidth*fTotalZoom / 2).attr({fill: '#fff', 'stroke-width':0}));
				oScaledAttr = {};
				for(k in oNode.disc_attr)
				{
					value = oNode.disc_attr[k]
					switch(k)
					{
						// Scalable attributes
						case 'stroke-width':
						value = value * fTotalZoom;
						break;
					}
					oScaledAttr[k] = value;
				}
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, iWidth*fTotalZoom / 2).attr(oScaledAttr));
				var xIcon = xPos - 18 * fTotalZoom;
				var yIcon = yPos - 18 * fTotalZoom;
				oNode.aElements.push(this.oPaper.image(oNode.icon_url, xIcon, yIcon, 16*fTotalZoom, 16*fTotalZoom).attr(oNode.icon_attr));
				oNode.aElements.push(this.oPaper.image(oNode.icon_url, xIcon + 18*fTotalZoom, yIcon, 16*fTotalZoom, 16*fTotalZoom).attr(oNode.icon_attr));
				oNode.aElements.push(this.oPaper.image(oNode.icon_url, xIcon + 9*fTotalZoom, yIcon + 18*fTotalZoom, 16*fTotalZoom, 16*fTotalZoom).attr(oNode.icon_attr));
				var oText = this.oPaper.text(xPos, yPos +2, oNode.label);
				oNode.text_attr['font-size'] = iFontSize * fTotalZoom;
				oText.attr(oNode.text_attr);
				//oText.transform('s'+this.fZoom);
				var oBB = oText.getBBox();
				var dy = iHeight/2*fTotalZoom + oBB.height/2;
				oText.remove();
				oText = this.oPaper.text(xPos, yPos +dy +2, oNode.label);
				oText.attr(oNode.text_attr);
				//oText.transform('s'+this.fZoom);
				oNode.aElements.push(oText);
				oNode.aElements.push(this.oPaper.rect( xPos - oBB.width/2 -2, yPos - oBB.height/2 + dy, oBB.width +4, oBB.height).attr({fill: '#fff', stroke: '#fff', opacity: 0.9}));
				oText.toFront();
				break;
					
				case 'icon':
				if(Raphael.svg)
				{
					// the colorShift plugin works only in SVG
					oNode.aElements.push(this.oPaper.image(oNode.icon_url, xPos - iWidth * fTotalZoom/2, yPos - iHeight * fTotalZoom/2, iWidth*fTotalZoom, iHeight*fTotalZoom).colorShift('#fff', 1));					
				}
				oNode.aElements.push(this.oPaper.image(oNode.icon_url, xPos - iWidth * fTotalZoom/2, yPos - iHeight * fTotalZoom/2, iWidth*fTotalZoom, iHeight*fTotalZoom).attr(oNode.icon_attr));
				
				var idx = 0;
				for(var i in oNode.context_icons)
				{
					var sgn = 2*(idx % 2) -1; // Suite: -1, 1, -1, 1, -1, 1, -1, etc.
					var coef = Math.floor((1+idx)/2) * sgn; // Suite: 0, 1, -1, 2, -2, 3, -3, etc.
					var alpha = coef*Math.PI/4 - Math.PI/2;						
					var x = xPos + Math.cos(alpha) * 1.25*iWidth * fTotalZoom / 2;
					var y = yPos + Math.sin(alpha) * 1.25*iWidth * fTotalZoom / 2;
					var l = iWidth/3 * fTotalZoom;
					oNode.aElements.push(this.oPaper.image(oNode.context_icons[i], x - l/2, y - l/2, l , l).attr(oNode.icon_attr));
					idx++;
				}
				var oText = this.oPaper.text( xPos, yPos, oNode.label);
				oNode.text_attr['font-size'] = iFontSize * fTotalZoom;
				oText.attr(oNode.text_attr);
				//oText.transform('S'+fTotalZoom);
				var oBB = oText.getBBox();
				var dy = iHeight/2*fTotalZoom + oBB.height/2;
				oText.remove();
				oText = this.oPaper.text( xPos, yPos + dy, oNode.label);
				oText.attr(oNode.text_attr);
				//oText.transform('S'+fTotalZoom);
				oNode.aElements.push(oText);
				oNode.aElements.push(this.oPaper.rect( xPos - oBB.width/2 -2, yPos - oBB.height/2 + dy, oBB.width +4, oBB.height).attr({fill: '#fff', stroke: '#fff', opacity: 0.9}).toBack());
				break;
			}
			if (oNode.source)
			{
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, 1.25*iWidth*fTotalZoom / 2).attr({stroke: '#c33', 'stroke-width': 3*fTotalZoom }).toBack());
			}
			if (oNode.sink)
			{
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, 1.25*iWidth*fTotalZoom / 2).attr({stroke: '#33c', 'stroke-width': 3*fTotalZoom }).toBack());
			}
			
			var me = this;
			for(k in oNode.aElements)
			{
				var sNodeId = oNode.id;
				$(oNode.aElements[k].node).attr({'data-type': oNode.shape, 'data-id': oNode.id} ).attr('class', 'popupMenuTarget');
				oNode.aElements[k].drag(
					function(dx, dy, x, y, event) {
						clearTimeout($(this.node).data('openTimeoutId'));
						me._move(sNodeId, dx, dy, x, y, event);
					},
					function(x, y, event) { 
						me._drag_start(sNodeId, x, y, event);
					},
					function (event) {
						me._drag_end(sNodeId, event);
					}
				);
			}
		},
		_move: function(sNodeId, dx, dy, x, y, event)
		{
			var fTotalZoom = this.fZoom * this.fSliderZoom;
			var origDx = dx / fTotalZoom;
			var origDy = dy / fTotalZoom;
			
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
			var fTotalZoom = this.fZoom * this.fSliderZoom;
			var oNode = this._find_node(sNodeId);
			oNode.tx += (oNode.x - oNode.xOrig) * fTotalZoom;
			oNode.ty += (oNode.y - oNode.yOrig) * fTotalZoom;
			oNode.xOrig = oNode.x;
			oNode.yOrig = oNode.y;
			this._updateBBox();
		},
		_updateBBox: function()
		{
			this.options.xmin = 9999;
			this.options.xmax = -9999;
			this.options.ymin = 9999;
			this.options.ymax = -9999;
			for(var k in this.aNodes)
			{
				this.options.xmin = Math.min(this.aNodes[k].x + this.aNodes[k].tx - this.aNodes[k].width/2, this.options.xmin);
				this.options.xmax = Math.max(this.aNodes[k].x + this.aNodes[k].tx + this.aNodes[k].width/2, this.options.xmax);
				this.options.ymin = Math.min(this.aNodes[k].y + this.aNodes[k].ty - this.aNodes[k].width/2, this.options.ymin);
				this.options.ymax = Math.max(this.aNodes[k].y + this.aNodes[k].ty + this.aNodes[k].width/2, this.options.ymax);
			}
		},
		_get_edge_path: function(oEdge)
		{
			var fTotalZoom = this.fZoom * this.fSliderZoom;
			var oStart = this._find_node(oEdge.source_node_id);
			var oEnd = this._find_node(oEdge.sink_node_id);
			var iArrowSize = 5;
			
			if ((oStart == null) || (oEnd == null)) return '';
			
			var xStart = Math.round(oStart.x * fTotalZoom + this.xOffset);
			var yStart = Math.round(oStart.y * fTotalZoom + this.yOffset);
			var xEnd = Math.round(oEnd.x * fTotalZoom + this.xOffset);
			var yEnd = Math.round(oEnd.y  * fTotalZoom + this.yOffset);

			var sPath = Raphael.format('M{0},{1}L{2},{3}', xStart, yStart, xEnd, yEnd);
			var vx = (xEnd - xStart);
			var vy = (yEnd - yStart);
			var l = Math.sqrt(vx*vx+vy*vy);
			vx = vx / l;
			vy = vy / l;
			var ux = -vy;
			var uy = vx;
			var lPos = Math.max(l/2, l - 40*fTotalZoom);
			var xArrow = xStart + vx * lPos;
			var yArrow = yStart + vy * lPos;
			sPath += Raphael.format('M{0},{1}l{2},{3}M{4},{5}l{6},{7}', xArrow, yArrow, fTotalZoom * iArrowSize *(-vx + ux),  fTotalZoom * iArrowSize *(-vy + uy), xArrow, yArrow, fTotalZoom * iArrowSize *(-vx - ux),  fTotalZoom * iArrowSize *(-vy - uy));
			return sPath;
		},
		_draw_edge: function(oEdge)
		{
			var fTotalZoom = this.fZoom * this.fSliderZoom;
			var fStrokeSize = Math.max(1, 2 * fTotalZoom);			
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
		adjust_height: function()
		{
			var maxHeight = this.element.parent().height();
			// Compute the available height
			var element = this.element;
			this.element.parent().children().each(function() {
				if($(this).is(':visible') && !$(this).hasClass('graph') && ($(this).attr('id') != element.attr('id')))
				{
					maxHeight = maxHeight - $(this).height();
				}
			});
			
			this.element.height(maxHeight - 20);
			this.oPaper.setSize(this.element.width(), this.element.height());
		},
		auto_scale: function()
		{
			var fMaxZoom = 1.5;
			this.adjust_height();
			
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
				this.xOffset = -xmin * this.fZoom + (this.element.width() - (xmax - xmin) * this.fZoom) / 2;
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
				this.yOffset = -ymin * this.fZoom + (this.element.height() - (ymax - ymin + this.iTextHeight) * this.fZoom) / 2;
				break;			
			}			
		},
		add_node: function(oNode)
		{
			oNode.aElements = [];
			oNode.tx = 0;
			oNode.ty = 0;
			this.aNodes.push(oNode);
		},
		add_edge: function(oEdge)
		{
			oEdge.aElements = [];
			this.aEdges.push(oEdge);
		},
		show_group: function(sGroupId)
		{
			this._close_all_tooltips();
			// Activate the 3rd tab
			this.element.closest('.ui-tabs').tabs("option", "active", 2);
			// Scroll into view the group
			if ($('#'+sGroupId).length > 0)
			{
				$('#'+sGroupId)[0].scrollIntoView();				
			}
		},
		_create_toolkit_menu: function()
		{
			var sPopupMenuId = 'tk_graph'+this.element.attr('id');
			var sHtml = '<div class="graph_config">';
			var sId = this.element.attr('id');
			sHtml += this.options.labels.grouping_threshold+'&nbsp;<input type="text" name="g" value="'+this.options.grouping_threshold+'" id="'+sId+'_grouping_threshold" size="2">';
			if (this.options.additional_contexts.length > 0)
			{
				sHtml += '&nbsp;'+this.options.labels.additional_context_info+' <select id="'+sId+'_contexts" name="contexts" class="multiselect" multiple size="1">';
				for(var k in this.options.additional_contexts)
				{
					sSelected = (this.options.additional_contexts[k]['default']) ? 'selected' : '';
					sHtml += '<option value="'+k+'" '+sSelected+'>'+this.options.additional_contexts[k].label+'</option>';
				}
				sHtml += '</select>'
			}
			sHtml += '&nbsp;<button type="button" id="'+sId+'_refresh_btn">'+this.options.labels.refresh+'</button>';
			sHtml += '<div class="itop_popup toolkit_menu graph" style="font-size: 12px;" id="'+sPopupMenuId+'"><ul><li><img src="../images/toolkit_menu.png"><ul>';
			if (this.options.export_as_pdf != null)
			{
				sHtml += '<li><a href="#" id="'+sPopupMenuId+'_pdf">'+this.options.export_as_pdf.label+'</a></li>';			
			}
			if (this.options.export_as_attachment != null)
			{
				sHtml += '<li><a href="#" id="'+sPopupMenuId+'_attachment">'+this.options.export_as_attachment.label+'</a></li>';
			}
			//sHtml += '<li><a href="#" id="'+sPopupMenuId+'_reload">Refresh</a></li>';
			sHtml += '</ul></li></ul></div>';
			sHtml += '<span class="graph_zoom"><span>'+this.options.labels.zoom+'</span>';
			sHtml += '<div id="'+sId+'_zoom_minus" class="graph_zoom_minus ui-icon ui-icon-circle-minus"></div>';
			sHtml += '<div id="'+sId+'_zoom" class="graph_zoom_slider"></div>';
			sHtml += '<div id="'+sId+'_zoom_plus" class="graph_zoom_plus ui-icon ui-icon-circle-plus"></div>';
			sHtml += '</span>';
			sHtml += '</div>';

			
			this.element.before(sHtml);
			$('#'+sPopupMenuId+'>ul').popupmenu();
			
			
			var me = this;
			$('#'+sPopupMenuId+'_pdf').click(function() { me.export_as_pdf(); });
			$('#'+sPopupMenuId+'_attachment').click(function() { me.export_as_attachment(); });
			$('#'+sId+'_grouping_threshold').spinner({ min: 2});
			$('#'+sId+'_zoom').slider({ min: 0, max: 5, value: 1, step: 0.25, change: function() { me._on_zoom_change( $(this).slider('value')); } });
			$('#'+sId+'_zoom_plus').click(function() { $('#'+sId+'_zoom').slider('value', 0.25 + $('#'+sId+'_zoom').slider('value')); return false; });
			$('#'+sId+'_zoom_minus').click(function() { $('#'+sId+'_zoom').slider('value', $('#'+sId+'_zoom').slider('value') - 0.25); return false; });
			$('#'+sId+'_contexts').multiselect({header: true, checkAllText: this.options.labels.check_all, uncheckAllText: this.options.labels.uncheck_all, noneSelectedText: this.options.labels.none_selected, selectedText: this.options.labels.nb_selected, selectedList: 1});
			$('#'+sId+'_refresh_btn').button().click(function() { me.reload(); });
		},
		_build_context_menus: function()
		{
			var sId = this.element.attr('id');
			var me = this;
			
			$.contextMenu({
			    selector: '#'+sId+' .popupMenuTarget',  
		        build: function(trigger, e) {
		            // this callback is executed every time the menu is to be shown
		            // its results are destroyed every time the menu is hidden
		            // e is the original contextmenu event, containing e.pageX and e.pageY (amongst other data)
		        	var sType = trigger.attr('data-type');
		        	var sNodeId = trigger.attr('data-id');
		        	var oNode = me._find_node(sNodeId);
		        	clearTimeout(trigger.data('openTimeoutId'));
		        	
		        	/*
		        	var sObjName = trigger.attr('data-class');
		        	var sIndex = trigger.attr('data-index');
		        	var originalEvent = e;
		        	var bHasItems = false;
		        	*/
		        	var oResult = {callback: null, items: {}};
		        	switch(sType)
		        	{
		        		case 'group':
		        		var sGroupIndex = oNode.group_index;
		        		if( $('#relation_group_'+sGroupIndex).length > 0)
		        		{
			        		oResult = {
			        				callback: function(key, options) {
			        					var me = $('.itop-simple-graph').data('itopSimple_graph'); // need a live value
			        					me.show_group('relation_group_'+sGroupIndex);
			        				},
			        				items: { 'show': {name: me.options.drill_down.label } }
			        			};		        			
		        		}
						break;
						
		        		case 'icon':
			        	var sObjClass = oNode.obj_class;
		        		var sObjKey = oNode.obj_key;
		        		oResult = {
	        				callback: function(key, options) {
	        					var me = $('.itop-simple-graph').data('itopSimple_graph'); // need a live value
	        					var sURL = me.options.drill_down.url.replace('%1$s', sObjClass).replace('%2$s', sObjKey);
	        					window.location.href = sURL;
	        				},
	        				items: { 'details': {name: me.options.drill_down.label } }
	        			};
		        		break;
		        		
		        		default:
						oResult = false; // No context menu
		        	}
		        	return oResult;
		        }
			});
			
		},
		export_as_pdf: function()
		{
			this._export_dlg(this.options.labels.export_pdf_title, this.options.export_as_pdf.url, 'download_pdf');
		},
		_export_dlg: function(sTitle, sSubmitUrl, sOperation)
		{
			var sId = this.element.attr('id');
			var me = this;
			var oPositions = {};
			for(k in this.aNodes)
			{
				oPositions[this.aNodes[k].id] = {x: this.aNodes[k].x, y: this.aNodes[k].y };
			}
			var sHtmlForm = '<div id="GraphExportDlg'+this.element.attr('id')+'"><form id="graph_'+this.element.attr('id')+'_export_dlg" target="_blank" action="'+sSubmitUrl+'" method="post">';
			sHtmlForm += '<input type="hidden" name="g" value="'+this.options.grouping_threshold+'">';
			sHtmlForm += '<input type="hidden" name="context_key" value="'+this.options.context_key+'">';
			$('#'+sId+'_contexts').multiselect('getChecked').each(function() {
				sHtmlForm += '<input type="hidden" name="contexts['+$(this).val()+']" value="'+me.options.additional_contexts[$(this).val()].oql+'">';				
			});

			sHtmlForm += '<input type="hidden" name="positions" value="">';
			for(k in this.options.excluded_classes)
			{
				sHtmlForm += '<input type="hidden" name="excluded_classes[]" value="'+this.options.excluded_classes[k]+'">';				
			}
			for(var k1 in this.options.sources)
			{
				for(var k2 in this.options.sources[k1])
				{
					sHtmlForm += '<input type="hidden" name="sources['+k1+'][]" value="'+this.options.sources[k1][k2]+'">';									
				}
			}
			for(var k1 in this.options.excluded)
			{
				for(var k2 in this.options.excluded[k1])
				{
					sHtmlForm += '<input type="hidden" name="excluded['+k1+'][]" value="'+this.options.excluded[k1][k2]+'">';									
				}
			}
			if (sOperation == 'attachment')
			{
				sHtmlForm += '<input type="hidden" name="obj_class" value="'+this.options.export_as_attachment.obj_class+'">';									
				sHtmlForm += '<input type="hidden" name="obj_key" value="'+this.options.export_as_attachment.obj_key+'">';								
			}
			sHtmlForm += '<table>';
			sHtmlForm += '<tr><td>'+this.options.page_format.label+'</td><td><select name="p">';
			for(k in this.options.page_format.values)
			{
				var sSelected = (k == this.options.page_format['default']) ? ' selected' : '';
				sHtmlForm += '<option value="'+k+'"'+sSelected+'>'+this.options.page_format.values[k]+'</option>';
			}
			sHtmlForm += '</select></td></tr>';
			sHtmlForm += '<tr><td>'+this.options.page_orientation.label+'</td><td><select name="o">';
			for(k in this.options.page_orientation.values)
			{
				var sSelected = (k == this.options.page_orientation['default']) ? ' selected' : '';
				sHtmlForm += '<option value="'+k+'"'+sSelected+'>'+this.options.page_orientation.values[k]+'</option>';
			}
			sHtmlForm += '</select></td></tr>';
			sHtmlForm += '<tr><td>'+this.options.labels.title+'</td><td><input name="title" value="'+this.options.labels.untitled+'" style="width: 20em;"/></td></tr>';
			sHtmlForm += '<tr><td>'+this.options.labels.comments+'</td><td><textarea style="width: 20em; height:5em;" name="comments"/></textarea></td></tr>';
			sHtmlForm += '<tr><td colspan=2><input type="checkbox" checked id="include_list_checkbox" name="include_list" value="1"><label for="include_list_checkbox">&nbsp;'+this.options.labels.include_list+'</label></td></tr>';
			sHtmlForm += '<table>';
			sHtmlForm += '</form></div>';
			
			$('body').append(sHtmlForm);
			$('#graph_'+this.element.attr('id')+'_export_dlg input[name="positions"]').val(JSON.stringify(oPositions));
			var me = this;
			if (sOperation == 'attachment')
			{
				$('#GraphExportDlg'+this.element.attr('id')+' form').submit(function() { return me._on_export_as_attachment(); });
			}
			$('#GraphExportDlg'+this.element.attr('id')).dialog({
				width: 'auto',
				modal: true,
				title: sTitle,
				close: function() { $(this).remove(); },
				buttons: [
				          {text: this.options.labels['cancel'], click: function() { $(this).dialog('close');} },
				          {text: this.options.labels['export'], click: function() { $('#graph_'+me.element.attr('id')+'_export_dlg').submit(); $(this).dialog('close');} },
				]
			});			
		},
		_on_zoom_change: function(sliderValue)
		{
			if(!this.bInUpdateSliderZoom)
			{
				var Z0 = this.fSliderZoom;
				var X = this.xOffset - this.element.width()/2;
				var Y = this.yOffset - this.element.height()/2; 

				this.fSliderZoom = Math.pow(2 , (sliderValue - 1));
				
				var Z1 = this.fSliderZoom = Math.pow(2 , (sliderValue - 1));
				var dx = X * (1 - Z1/Z0);
				var dy = Y * (1 - Z1/Z0);
				this.xPan += dx;
				this.yPan += dy;
				this._close_all_tooltips();
				this.oPaper.setViewBox(this.xPan, this.yPan, this.element.width(),  this.element.height(), false);
				this.draw();				
			}
		},
		_on_mousewheel: function(event, delta, deltaX, deltaY)
		{
			var fStep = 0.25*delta;
			var sId = this.element.attr('id');
			$('#'+sId+'_zoom').slider('value', fStep + $('#'+sId+'_zoom').slider('value'));
		},
		_on_resize: function()
		{
			this.element.closest('.ui-tabs').tabs({ heightStyle: "fill" });
			this.auto_scale();
			this._close_all_tooltips();
			this.draw();
		},
		_on_tabs_activate: function(ui)
		{
			if (ui.newPanel.selector == ('#'+this.sTabId))
			{
				if (this.bRedrawNeeded)
				{
					this._updateBBox();
					this.auto_scale();
					this.oPaper.setSize(this.element.width(), this.element.height());
					this._reset_pan_and_zoom();
					this.draw();
					bRedrawNeeded = false;
				}
			}
		},
		load: function(oData)
		{
			var me = this;
			var sId = this.element.attr('id');
			this.aNodes = [];
			this.aEdges = [];
			for(k in oData.nodes)
			{
				this.add_node(oData.nodes[k]);
			}
			for(k in oData.edges)
			{
				this.add_edge(oData.edges[k]);
			}
			if (oData.groups)
			{
				this.refresh_groups(oData.groups);
			}
			if (this.element.is(':visible'))
			{
				this._updateBBox();
				this.auto_scale();
				this._reset_pan_and_zoom();
				this.draw();
			}
			else
			{
				this.bRedrawNeeded = true;
			}
		},
		refresh_groups: function(aGroups)
		{
			if ($('#impacted_groups').length > 0)
			{
				
				// The "Groups" tab is present, refresh it
				if (aGroups.length == 0)
				{
					this.element.closest('.ui-tabs').tabs("disable", 2);
					$('#impacted_groups').html('');
				}
				else
				{
					this.element.closest('.ui-tabs').tabs("enable", 2);
					$('#impacted_groups').html('<img src="../images/indicator.gif">');
					var sUrl = GetAbsoluteUrlAppRoot()+'pages/ajax.render.php';
					$.post(sUrl, { operation: 'relation_groups', groups: aGroups }, function(data) {
						$('#impacted_groups').html(data);
					});
				}
			}
		},
		_reset_pan_and_zoom: function()
		{
			this.xPan = 0;
			this.yPan = 0;
			var sId = this.element.attr('id');
			this.bInUpdateSliderZoom = true;
			$('#'+sId+'_zoom').slider('value', 1);
			this.fSliderZoom = 1.0;
			this.bInUpdateSliderZoom = false;
			this.oPaper.setViewBox(this.xPan, this.yPan, this.element.width(),  this.element.height(), false);
		},
		load_from_url: function(sUrl)
		{
			this.options.load_from_url = sUrl;
			var me = this;
			var sId = this.element.attr('id');
			this.options.grouping_threshold = $('#'+sId+'_grouping_threshold').val();
			if (this.options.grouping_threshold < 2)
			{
				this.options.grouping_threshold = 2;
				$('#'+sId+'_grouping_threshold').val(this.options.grouping_threshold);
			}
			var aContexts = [];
			$('#'+sId+'_contexts').multiselect('getChecked').each(function() { aContexts[$(this).val()] = me.options.additional_contexts[$(this).val()].oql; });
			this.element.closest('.ui-tabs').tabs({ heightStyle: "fill" });
			this.adjust_height();
			this._close_all_tooltips();
			this.oPaper.rect(this.xPan, this.yPan, this.element.width(), this.element.height()).attr({fill: '#000', opacity: 0.4, 'stroke-width': 0});
			this.oPaper.rect(this.xPan + this.element.width()/2 - 100, this.yPan + this.element.height()/2 - 10, 200, 20)
			.attr({fill: 'url(../setup/orange-progress.gif)', stroke: '#000', 'stroke-width': 1});
			this.oPaper.text(this.xPan + this.element.width()/2, this.yPan + this.element.height()/2 - 20, this.options.labels.loading);			
			
			$('#'+sId+'_refresh_btn').button('disable'); 
			$.post(sUrl, {excluded_classes: this.options.excluded_classes, g: this.options.grouping_threshold, sources: this.options.sources, excluded: this.options.excluded, contexts: aContexts, context_key: this.options.context_key }, function(data) {
				me.load(data);
				$('#'+sId+'_refresh_btn').button('enable');
			}, 'json');
		},
		export_as_attachment: function()
		{
			this._export_dlg(this.options.labels.export_as_attachment_title, this.options.export_as_attachment.url, 'attachment');
		},
		_on_export_as_attachment: function()
		{
			var oParams = {};
			var oPositions = {};
			var jForm = $('#GraphExportDlg'+this.element.attr('id')+' form');
			for(k in this.aNodes)
			{
				oPositions[this.aNodes[k].id] = {x: this.aNodes[k].x, y: this.aNodes[k].y };
			}
			oParams.positions = JSON.stringify(oPositions);
			oParams.sources = this.options.sources;
			oParams.excluded_classes = this.options.excluded_classes;
			oParams.title = jForm.find(':input[name="title"]').val();
			oParams.comments = jForm.find(':input[name="comments"]').val();
			oParams.include_list = jForm.find(':input[name="include_list"]:checked').length;
			oParams.o = jForm.find(':input[name="o"]').val();
			oParams.p = jForm.find(':input[name="p"]').val();
			oParams.obj_class = this.options.export_as_attachment.obj_class;
			oParams.obj_key = this.options.export_as_attachment.obj_key;
			oParams.contexts = [];
			var me = this;
			$('#'+this.element.attr('id')+'_contexts').multiselect('getChecked').each(function() {
				oParams.contexts[$(this).val()] = me.options.additional_contexts[$(this).val()].oql;				
			});
			oParams.context_key = this.options.context_key;
			var sUrl = jForm.attr('action');
			var sTitle = oParams.title;
			var jPanel = $('#attachments').closest('.ui-tabs-panel');
			var jTab = null;
			var sTabText = null;
			if (jPanel.length  > 0)
			{
				var sTabId = jPanel.attr('id');
				jTab = $('li[aria-controls='+sTabId+']');
				sTabText = jTab.find('span').html();
				jTab.find('span').html(sTabText+' <img style="vertical-align:bottom" src="../images/indicator.gif">');
			}
			$.post(sUrl, oParams, function(data) {
				var sDownloadLink = GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?operation=download_document&class=Attachment&id='+data.att_id+'&field=contents';
				var sIcon = GetAbsoluteUrlModulesRoot()+'itop-attachments/icons/pdf.png';
				if (jTab != null)
				{
					var re = /^([^(]+)\(([0-9]+)\)(.*)$/;
					var aParts = re.exec(sTabText);
					if (aParts == null)
					{
						// First attachment
						$('#attachments').html('<div class="attachment" id="display_attachment_'+data.att_id+'"><a data-preview="false" href="'+sDownloadLink+'"><img src="'+sIcon+'"><br/>'+sTitle+'.pdf<input id="attachment_'+data.att_id+'" type="hidden" name="attachments[]" value="'+data.att_id+'"/></a><br/><input type="button" class="btn_hidden" value="{$sDeleteBtn}" onClick="RemoveAttachment('+data.att_id+');"/></div>');
						jTab.find('span').html(sTabText +' (1)');
					}
					else
					{
						$('#attachments').append('<div class="attachment" id="display_attachment_'+data.att_id+'"><a data-preview="false" href="'+sDownloadLink+'"><img src="'+sIcon+'"><br/>'+sTitle+'.pdf<input id="attachment_'+data.att_id+'" type="hidden" name="attachments[]" value="'+data.att_id+'"/></a><br/><input type="button" class="btn_hidden" value="{$sDeleteBtn}" onClick="RemoveAttachment('+data.att_id+');"/></div>');
						var iPrevCount = parseInt(aParts[2], 10);
						jTab.find('span').html(aParts[1]+'('+(1 + iPrevCount)+')'+aParts[3]);						
					}
				}
			}, 'json');
			return false;
		},
		reload: function()
		{
			this.load_from_url(this.options.load_from_url);
		},
		_make_tooltips: function()
		{
			var me  = this;
			$( ".popupMenuTarget" ).tooltip({
				content: function() {
					var sDataId = $(this).attr('data-id');
					var sTooltipContent = '<div class="tooltip-close-button" data-id="'+sDataId+'" style="display:inline-block; float:right; cursor:pointer; padding-left:0.25em;">×</div>';
					sTooltipContent += me._get_tooltip_content(sDataId);
					return sTooltipContent;
				},
				items: '.popupMenuTarget',
				tooltipClass: 'tooltip-simple-graph',
				position: {
					my: "center bottom-10",
					at: "center  top",	
					using: function( position, feedback ) { 
						$(this).css( position );  
						$( "<div>" )
						.addClass( "arrow" )
						.addClass( feedback.vertical )
						.appendTo( this );
						}
				}
			})
			.off( "mouseover mouseout" )
			.on( "mouseover", function(event){
				event.stopImmediatePropagation();
				var jMe = $(this);
				jMe.data('openTimeoutId', setTimeout(function() {
					var sDataId = jMe.attr('data-id');
					if ($('.tooltip-close-button[data-id="'+sDataId+'"]').length == 0)
					{
						jMe.data('openTimeoutId', 0);
						jMe.tooltip('open');						
					}
				}, 1000));					
			})
			.on( "mouseout", function(event){
				event.stopImmediatePropagation();
				clearTimeout($(this).data('openTimeoutId'));					
			});
			/* Happens at every on_drag_end !!!
			.on( "click", function(){
				var sDataId = $(this).attr('data-id');
				if ($('.tooltip-close-button[data-id="'+sDataId+'"]').length == 0)
				{
					$(this).tooltip( 'open' );							 
				}
				else
				{
					$(this).tooltip( 'close' );							 						
				}           
				$( this ).unbind( "mouseleave" );
				return false;	
			 });
			*/
			$('body').on('click', '.tooltip-close-button', function() {
				var sDataId = $(this).attr('data-id');
				$('.popupMenuTarget[data-id="'+sDataId+'"]').tooltip('close');
			});
			this.element.on("click", ":not(.tooltip-simple-graph *,.tooltip-simple-graph)", function(){
				$('.popupMenuTarget').each(function (i) {
					clearTimeout($(this).data('openTimeoutId'));
					$(this).data('openTimeoutId', 0);
					$(this).tooltip("close"); 
				});
			});
		},
		_get_tooltip_content: function(sNodeId)
		{
			var oNode = this._find_node(sNodeId);
			if (oNode !== null)
			{
				return oNode.tooltip;
			}
			return '<p>Node Id:'+sNodeId+'</p>';
		},
		_close_all_tooltips: function()
		{
			this.element.find('.popupMenuTarget').each(function() {
				clearTimeout($(this).data('openTimeoutId'));
				$(this).data('openTimeoutId', 0);
				$(this).tooltip('close');
			});
		},
		_on_background_drag_start: function(x, y, event)
		{
			this.bDragging = true;
			this.xDrag = 0;
			this.yDrag = 0;
			//this._close_all_tooltips();
		},
		_on_background_move: function(dx, dy, x, y, event)
		{
			if (this.bDragging)
			{
				this.xDrag = dx;
				this.yDrag = dy;
				this.oPaper.setViewBox(this.xPan - this.xDrag, this.yPan - this.yDrag, this.element.width(),  this.element.height(), false);
			}
		},	
		_on_background_drag_end: function(event)
		{
			if (this.bDragging)
			{
				this.xPan -= this.xDrag;
				this.yPan -= this.yDrag;
				this.xDrag = 0;
				this.yDrag = 0;
				this.bDragging = false;
			}
		},
	});	
});
