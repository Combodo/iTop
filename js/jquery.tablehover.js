/*
 * jQuery tableHover plugin
 * Version: 0.1.3
 *
 * Copyright (c) 2007 Roman Weich
 * http://p.sohei.org
 *
 * Dual licensed under the MIT and GPL licenses 
 * (This means that you can choose the license that best suits your project, and use it accordingly):
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Changelog: 
 * v 0.1.3 - 2007-09-04
 *	- fix: highlight did not work when the hovered table cell had child elements inside
 * v 0.1.2 - 2007-08-13
 *	- fix/change: changed event binding routine, as is got really slow with jquery 1.1.3.1
 *	-change: added new option "ignoreCols", through which columns can be excluded from the highlighting process
 * v 0.1.1 - 2007-06-05
 *	- fix: errors when using the plugin on a table not having a theader or tfoot
 * v 0.1.0 - 2007-05-31
 */

(function($)
{
	/**
	 * Calculates the actual cellIndex value of all cells in the table and stores it in the realCell property of each cell.
	 * Thats done because the cellIndex value isn't correct when colspans or rowspans are used.
	 * Originally created by Matt Kruse for his table library - Big Thanks! (see http://www.javascripttoolbox.com/)
	 * @param {element} table	The table element.
	 */
	var fixCellIndexes = function(table) 
	{
		var rows = table.rows;
		var len = rows.length;
		var matrix = [];
		for ( var i = 0; i < len; i++ )
		{
			var cells = rows[i].cells;
			var clen = cells.length;
			for ( var j = 0; j < clen; j++ )
			{
				var c = cells[j];
				var rowSpan = c.rowSpan || 1;
				var colSpan = c.colSpan || 1;
				var firstAvailCol = -1;
				if ( !matrix[i] )
				{ 
					matrix[i] = []; 
				}
				var m = matrix[i];
				// Find first available column in the first row
				while ( m[++firstAvailCol] ) {}
				c.realIndex = firstAvailCol;
				for ( var k = i; k < i + rowSpan; k++ )
				{
					if ( !matrix[k] )
					{ 
						matrix[k] = []; 
					}
					var matrixrow = matrix[k];
					for ( var l = firstAvailCol; l < firstAvailCol + colSpan; l++ )
					{
						matrixrow[l] = 1;
					}
				}
			}
		}
	};

	/**
	 * Sets the rowIndex of each row in the table. 
	 * Opera seems to get that wrong using document order instead of logical order on the tfoot-tbody part.
	 * @param {element} table	The table element.
	 */
	var fixRowIndexes = function(tbl) 
	{
		var v = 0, i, k, r = ( tbl.tHead ) ? tbl.tHead.rows : 0;
		if ( r )
		{
			for ( i = 0; i < r.length; i++ )
			{
				r[i].realRIndex = v++;
			}
		}
		for ( k = 0; k < tbl.tBodies.length; k++ )
		{
			r = tbl.tBodies[k].rows;
			if ( r )
			{
				for ( i = 0; i < r.length; i++ )
				{
					r[i].realRIndex = v++;
				}
			}
		}
		r = ( tbl.tFoot ) ? tbl.tFoot.rows : 0;
		if ( r )
		{
			for ( i = 0; i < r.length; i++ )
			{
				r[i].realRIndex = v++;
			}
		}
	};

	/**
	 * Highlights table rows and/or columns on mouse over.
	 * Fixes the highlight of the currently highlighted rows/columns on click.
	 * Works on tables with rowspans and colspans.
	 *
	 * @param {map} options			An object for optional settings (options described below).
	 *
	 * @option {boolean} allowHead		Allow highlighting when hovering over the table header.
	 *							Default value: true
	 * @option {boolean} allowBody		Allow highlighting when hovering over the table body.
	 *							Default value: true
	 * @option {boolean} allowFoot		Allow highlighting when hovering over the table footer.
	 *							Default value: true
	 *
	 * @option {boolean} headRows		If true the rows in the table header will be highlighted when hovering over them.
	 *							Default value: false
	 * @option {boolean} bodyRows		If true the rows in the table body will be highlighted when hovering over them.
	 *							Default value: true
	 * @option {boolean} footRows		If true the rows in the table footer will be highlighted when hovering over them.
	 *							Default value: false
	 * @option {boolean} spanRows		When hovering over a cell spanning over more than one row, highlight all spanned rows.
	 *							Default value: true
	 *
	 * @option {boolean} headCols		If true the cells in the table header (matching the currently hovered column) will be highlighted.
	 *							Default value: false
	 * @option {boolean} bodyCols		If true the cells in the table body (matching the currently hovered column) will be highlighted.
	 *							Default value: true
	 * @option {boolean} footCols		If true the cells in the table footer (matching the currently hovered column) will be highlighted.
	 *							Default value: false
	 * @option {boolean} spanCols		When hovering over a cell spanning over more than one column, highlight all spanned columns.
	 *							Default value: true
	 * @option {array} ignoreCols		An array of numbers. Each column with the matching column index won't be included in the highlighting process.
	 *							Index starting at 1!
	 *							Default value: [] (empty array)
	 *
	 * @option {boolean} headCells		Set a special highlight class to the cell the mouse pointer is currently pointing at (inside the table header only).
	 *							Default value: false
	 * @option {boolean} bodyCells		Set a special highlight class to the cell the mouse pointer is currently pointing at (inside the table body only).
	 *							Default value: true
	 * @option {boolean} footCells		Set a special highlight class to the cell the mouse pointer is currently pointing at (inside the table footer only).
	 *							Default value: false
	 *
	 * @option {string} rowClass			The css class set to the currently highlighted row.
	 *							Default value: 'hover'
	 * @option {string} colClass			The css class set to the currently highlighted column.
	 *							Default value: '' (empty string)
	 * @option {string} cellClass			The css class set to the currently highlighted cell.
	 *							Default value: '' (empty string)
	 * @option {string} clickClass		The css class set to the currently highlighted row and column on mouse click.
	 *							Default value: '' (empty string)
	 *
	 * @example $('#table').tableHover({});
	 * @desc Add simple row highlighting to #table with default settings.
	 *
	 * @example $('#table').tableHover({rowClass: "someclass", colClass: "someotherclass"});
	 * @desc Add row and columnhighlighting to #table and set the specified css classes to the highlighted cells.
	 *
	 * @example $('#table').tableHover({clickClass: "someclickclass"});
	 * @desc Add simple row highlighting to #table and set the specified css class on the cells when clicked.
	 *
	 * @example $('#table').tableHover({allowBody: false, allowFoot: false, allowHead: true, colClass: "someclass"});
	 * @desc Add column highlighting on #table only highlighting the cells when hovering over the table header.
	 *
	 * @example $('#table').tableHover({bodyCols: false, footCols: false, headCols: true, colClass: "someclass"});
	 * @desc Add column highlighting on #table only for the cells in the header.
	 *
	 * @type jQuery
	 *
	 * @name tableHover
	 * @cat Plugins/tableHover
	 * @author Roman Weich (http://p.sohei.org)
	 */
	$.fn.tableHover = function(options)
	{
		var settings = $.extend({
				allowHead : true,
				allowBody : true,
				allowFoot : true,

				headRows : false,
				bodyRows : true,
				footRows : false,
				spanRows : true,

				headCols : false,
				bodyCols : true,
				footCols : false,
				spanCols : true,
				ignoreCols : [],

				headCells : false,
				bodyCells : true,
				footCells : false,
				//css classes,,
				rowClass : 'hover',
				colClass : '',
				cellClass : '',
				clickClass : ''
			}, options);

		return this.each(function() 
        {
			var colIndex = [], rowIndex = [], tbl = this, r, rCnt = 0, lastClick = [-1, -1];

			if ( !tbl.tBodies || !tbl.tBodies.length )
			{
				return;
			}

			/**
			 * Adds all rows and each of their cells to the row and column indexes.
			 * @param {array} rows		An array of table row elements to add.
			 * @param {string} nodeName	Defines whether the rows are in the header, body or footer of the table.
			 */
			var addToIndex = function(rows, nodeName)
			{
				var c, row, rowI, cI, rI, s;
				//loop through the rows
				for ( rowI = 0; rowI < rows.length; rowI++, rCnt++ )
				{
					row = rows[rowI];
					//each cell
					for ( cI = 0; cI < row.cells.length; cI++ )
					{
						c = row.cells[cI];
						//add to rowindex
						if ( (nodeName == 'TBODY' && settings.bodyRows) 
							|| (nodeName == 'TFOOT' && settings.footRows) 
							|| (nodeName == 'THEAD' && settings.headRows) )
						{
							s = c.rowSpan;
							while ( --s >= 0 )
							{
								rowIndex[rCnt + s].push(c);
							}
						}
						//add do colindex
						if ( (nodeName == 'TBODY' && settings.bodyCols)
								|| (nodeName == 'THEAD' && settings.headCols) 
								|| (nodeName == 'TFOOT' && settings.footCols) )
						{
							s = c.colSpan;
							while ( --s >= 0 )
							{
								rI = c.realIndex + s;
								if ( $.inArray(rI + 1, settings.ignoreCols) > -1 )
								{
									break;//dont highlight the columns in the ignoreCols array
								}
								if ( !colIndex[rI] )
								{
									colIndex[rI] = [];
								}
								colIndex[rI].push(c);
							}
						}
						//allow hover for the cell?
						if ( (nodeName == 'TBODY' && settings.allowBody) 
								|| (nodeName == 'THEAD' && settings.allowHead) 
								|| (nodeName == 'TFOOT' && settings.allowFoot) )
						{
							c.thover = true;
						}
					}
				}
			};

			/**
			 * Mouseover event handling. Set the highlight to the rows/cells.
			 */
			var over = function(e)
			{
				var p = e.target;
				while ( p != this && p.thover !== true )
				{
					p = p.parentNode;
				}
				if ( p.thover === true )
				{
					highlight(p, true);
				}
			};

			/**
			 * Mouseout event handling. Remove the highlight from the rows/cells.
			 */
			var out = function(e)
			{
				var p = e.target;
				while ( p != this && p.thover !== true )
				{
					p = p.parentNode;
				}
				if ( p.thover === true )
				{
					highlight(p, false);
				}
			};
			
			/**
			 * Mousedown event handling. Sets or removes the clickClass css style to the currently highlighted rows/cells.
			 */
			var click = function(e)
			{
				if ( e.target.thover && settings.clickClass != '' )
				{
					var x = e.target.realIndex, y = e.target.parentNode.realRIndex, s = '';
					//unclick
					$('td.' + settings.clickClass + ', th.' + settings.clickClass, tbl).removeClass(settings.clickClass);
					if ( x != lastClick[0] || y != lastClick[1] )
					{
						//click..
						if ( settings.rowClass != '' )
						{
							s += ',.' + settings.rowClass;
						}
						if ( settings.colClass != '' )
						{
							s += ',.' + settings.colClass;
						}
						if ( settings.cellClass != '' )
						{
							s += ',.' + settings.cellClass;
						}
						if ( s != '' )
						{
							$('td, th', tbl).filter(s.substring(1)).addClass(settings.clickClass);
						}
						lastClick = [x, y];
					}
					else
					{
						lastClick = [-1, -1];
					}
				}
			};
			
			/**
			 * Adds or removes the highlight to/from the columns and rows.
			 * @param {element} cell	The cell with the mouseover/mouseout event.
			 * @param {boolean} on		Defines whether the style will be set or removed.
			 */
			var highlight = function(cell, on)
			{
				if ( on ) //create dummy funcs - dont want to test for on==true all the time
				{
					$.fn.tableHoverHover = $.fn.addClass;
				}
				else
				{
					$.fn.tableHoverHover = $.fn.removeClass;
				}
				//highlight columns
				var h = colIndex[cell.realIndex] || [], rH = [], i = 0, rI, nn;
				if ( settings.colClass != '' )
				{
					while ( settings.spanCols && ++i < cell.colSpan && colIndex[cell.realIndex + i] )
					{
						h = h.concat(colIndex[cell.realIndex + i]);
					}
					$(h).tableHoverHover(settings.colClass);
				}
				//highlight rows
				if ( settings.rowClass != '' )
				{
					rI = cell.parentNode.realRIndex;
					if ( rowIndex[rI] )
					{
						rH = rH.concat(rowIndex[rI]);
					}
					i = 0;
					while ( settings.spanRows && ++i < cell.rowSpan )
					{
						if ( rowIndex[rI + i] )
						{
							rH = rH.concat(rowIndex[rI + i]);
						}
					}
					$(rH).tableHoverHover(settings.rowClass);
				}
				//highlight cell
				if ( settings.cellClass != '' )
				{
					nn = cell.parentNode.parentNode.nodeName.toUpperCase();
					if ( (nn == 'TBODY' && settings.bodyCells)
							|| (nn == 'THEAD' && settings.headCells)
							|| (nn == 'TFOOT' && settings.footCells) )
					{
						$(cell).tableHoverHover(settings.cellClass);
					}
				}
			};

			fixCellIndexes(tbl);
			fixRowIndexes(tbl);

			//init rowIndex
			for ( r = 0; r < tbl.rows.length; r++ )
			{
				rowIndex[r] = [];
			}
			//add header cells to index
			if ( tbl.tHead )
			{
				addToIndex(tbl.tHead.rows, 'THEAD');
			}
			//create index - loop through the bodies
			for ( r = 0; r < tbl.tBodies.length; r++ )
			{
				addToIndex(tbl.tBodies[r].rows, 'TBODY');
			}
			//add footer cells to index
			if ( tbl.tFoot )
			{
				addToIndex(tbl.tFoot.rows, 'TFOOT');
			}
			$(this).bind('mouseover', over).bind('mouseout', out).click(click);
		});
	};
})(jQuery); 
