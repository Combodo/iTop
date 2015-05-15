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
				refresh: 'Refresh'
			},
			export_as_document: null,
			drill_down: null,
			grouping_threshold: 10,
			excluded_classes: []
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
			
			this.oPaper = Raphael(this.element.get(0), this.element.width(), this.element.height());

			this.element
			.addClass('panel-resized')
			.addClass('itop-simple-graph')
			.addClass('graph');
			
			this._create_toolkit_menu();
			this._build_context_menus();
			$(window).bind('resized', function() { var that = me; window.setTimeout(function() { that._on_resize(); }, 50); } );
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
		},
		_draw_node: function(oNode)
		{
			var iWidth = oNode.width;
			var iHeight = 32;
			var iFontSize = 10;
			var xPos = Math.round(oNode.x * this.fZoom + this.xOffset);
			var yPos = Math.round(oNode.y * this.fZoom + this.yOffset);
			oNode.tx = 0;
			oNode.ty = 0;
			switch(oNode.shape)
			{
				case 'disc':
				oNode.aElements.push(this.oPaper.circle(xPos, yPos, iWidth*this.fZoom / 2).attr(oNode.disc_attr));
				var oText = this.oPaper.text(xPos, yPos, oNode.label);
				oNode.text_attr['font-size'] = iFontSize * this.fZoom;
				oText.attr(oNode.text_attr);
				//oText.transform('s'+this.fZoom);
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
				oNode.text_attr['font-size'] = iFontSize * this.fZoom;
				oText.attr(oNode.text_attr);
				//oText.transform('s'+this.fZoom);
				var oBB = oText.getBBox();
				var dy = iHeight/2*this.fZoom + oBB.height/2;
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
					oNode.aElements.push(this.oPaper.image(oNode.icon_url, xPos - iWidth * this.fZoom/2, yPos - iHeight * this.fZoom/2, iWidth*this.fZoom, iHeight*this.fZoom).colorShift('#fff', 1));					
				}
				oNode.aElements.push(this.oPaper.image(oNode.icon_url, xPos - iWidth * this.fZoom/2, yPos - iHeight * this.fZoom/2, iWidth*this.fZoom, iHeight*this.fZoom).attr(oNode.icon_attr));
				var oText = this.oPaper.text( xPos, yPos, oNode.label);
				oNode.text_attr['font-size'] = iFontSize * this.fZoom;
				oText.attr(oNode.text_attr);
				//oText.transform('S'+this.fZoom);
				var oBB = oText.getBBox();
				var dy = iHeight/2*this.fZoom + oBB.height/2;
				oText.remove();
				oText = this.oPaper.text( xPos, yPos + dy, oNode.label);
				oText.attr(oNode.text_attr);
				//oText.transform('S'+this.fZoom);
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
				$(oNode.aElements[k].node).attr({'data-type': oNode.shape, 'data-id': oNode.id} ).attr('class', 'popupMenuTarget');
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
				this.options.xmin = Math.min(this.aNodes[k].x + this.aNodes[k].tx, this.options.xmin);
				this.options.xmax = Math.max(this.aNodes[k].x + this.aNodes[k].tx, this.options.xmax);
				this.options.ymin = Math.min(this.aNodes[k].y + this.aNodes[k].ty, this.options.ymin);
				this.options.ymax = Math.max(this.aNodes[k].y + this.aNodes[k].ty, this.options.ymax);
			}
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
			sHtml += this.options.labels.grouping_threshold+'&nbsp;<input type="text" name="g" value="'+this.options.grouping_threshold+'" id="'+sId+'_grouping_threshold" size="2">&nbsp;<button type="button" id="'+sId+'_refresh_btn">'+this.options.labels.refresh+'</button>';
			sHtml += '<div class="itop_popup toolkit_menu graph" style="font-size: 12px;" id="'+sPopupMenuId+'"><ul><li><img src="../images/toolkit_menu.png"><ul>';
			if (this.options.export_as_pdf != null)
			{
				sHtml += '<li><a href="#" id="'+sPopupMenuId+'_pdf">'+this.options.export_as_pdf.label+'</a></li>';			
			}
			if (this.options.export_as_document != null)
			{
				sHtml += '<li><a href="#" id="'+sPopupMenuId+'_document">'+this.options.export_as_document.label+'</a></li>';
			}
			//sHtml += '<li><a href="#" id="'+sPopupMenuId+'_reload">Refresh</a></li>';
			sHtml += '</ul></li></ul></div>';
			sHtml += '</div>';
			
			this.element.before(sHtml);
			$('#'+sPopupMenuId+'>ul').popupmenu();
			
			
			var me = this;
			$('#'+sPopupMenuId+'_pdf').click(function() { me.export_as_pdf(); });
			$('#'+sPopupMenuId+'_document').click(function() { me.export_as_document(); });
			$('#'+sId+'_grouping_threshold').spinner({ min: 2});
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
			var oPositions = {};
			for(k in this.aNodes)
			{
				oPositions[this.aNodes[k].id] = {x: this.aNodes[k].x, y: this.aNodes[k].y };
			}
			var sHtmlForm = '<div id="PDFExportDlg'+this.element.attr('id')+'"><form id="graph_'+this.element.attr('id')+'_export_as_pdf" target="_blank" action="'+this.options.export_as_pdf.url+'" method="post">';
			sHtmlForm += '<input type="hidden" name="g" value="'+this.options.grouping_threshold+'">';
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
			sHtmlForm += '<tr><td>'+this.options.labels.title+'</td><td><input name="title"  style="width: 20em;"/></td></tr>';
			sHtmlForm += '<tr><td>'+this.options.labels.comments+'</td><td><textarea style="width: 20em; height:5em;" name="comments"/></textarea></td></tr>';
			sHtmlForm += '<tr><td colspan=2><input type="checkbox" checked id="include_list_checkbox" name="include_list" value="1"><label for="include_list_checkbox">&nbsp;'+this.options.labels.include_list+'</label></td></tr>';
			sHtmlForm += '<table>';
			sHtmlForm += '</form></div>';
			
			$('body').append(sHtmlForm);
			$('#graph_'+this.element.attr('id')+'_export_as_pdf input[name="positions"]').val(JSON.stringify(oPositions));
			var me = this;
			$('#PDFExportDlg'+this.element.attr('id')).dialog({
				width: 'auto',
				modal: true,
				title: this.options.labels.export_pdf_title,
				close: function() { $(this).remove(); },
				buttons: [
				          {text: this.options.labels['cancel'], click: function() { $(this).dialog('close');} },
				          {text: this.options.labels['export'], click: function() { $('#graph_'+me.element.attr('id')+'_export_as_pdf').submit(); $(this).dialog('close');} },
				]
			});
			//$('#graph_'+this.element.attr('id')+'_export_as_pdf').submit();
		},
		_on_resize: function()
		{
			this.element.closest('.ui-tabs').tabs({ heightStyle: "fill" });
			this.auto_scale();
			this.oPaper.setSize(this.element.width(), this.element.height());
			this.draw();
		},
		load: function(oData)
		{
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
			this._updateBBox();
			this.auto_scale();
			this.oPaper.setSize(this.element.width(), this.element.height());
			this.draw();
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
			this.element.closest('.ui-tabs').tabs({ heightStyle: "fill" });
			this.oPaper.rect(0, 0, this.element.width(), this.element.height()).attr({fill: '#000', opacity: 0.4, 'stroke-width': 0});
			$.post(sUrl, {excluded_classes: this.options.excluded_classes, g: this.options.grouping_threshold, sources: this.options.sources, excluded: this.options.excluded }, function(data) {
				me.load(data);
			}, 'json');
		},
		export_as_document: function()
		{
			alert('Export as document: not yet implemented');
		},
		reload: function()
		{
			this.load_from_url(this.options.load_from_url);
		}
	});	
});
