function sprintf(format, etc) {
    var arg = arguments;
    var i = 1;
    return format.replace(/%((%)|s)/g, function (m) { return m[2] || arg[i++] })
}


(function($) {
	$.extend({
		tablesorterPager: new function() {
			
			function updatePageDisplay(c) {
				var s = $(c.cssPageDisplay,c.container).val((c.page+1) + c.seperator + c.totalPages);	
			}
			
			function setPageSize(table,size) {
				var c = table.config;
				if (size == -1)
				{
					size = c.totalRows;
				}
				c.size = size;
				c.totalPages = Math.ceil(c.totalRows / c.size);
				c.pagerPositionSet = false;
				moveToPage(table);
				fixPosition(table);
			}
			
			function fixPosition(table) {
				var c = table.config;
				if(!c.pagerPositionSet && c.positionFixed) {
					var c = table.config, o = $(table);
					if(o.offset) {
						c.container.css({
							top: o.offset().top + o.height() + 'px',
							position: 'absolute'
						});
					}
					c.pagerPositionSet = true;
				}
			}
			
			function moveToFirstPage(table) {
				var c = table.config;
				c.page = 0;
				moveToPage(table);
			}
			
			function moveToLastPage(table) {
				var c = table.config;
				c.page = (c.totalPages-1);
				moveToPage(table);
			}
			
			function moveToNextPage(table) {
				var c = table.config;
				c.page++;
				if(c.page >= (c.totalPages-1)) {
					c.page = (c.totalPages-1);
				}
				moveToPage(table);
			}
			
			function moveToPrevPage(table) {
				var c = table.config;
				c.page--;
				if(c.page <= 0) {
					c.page = 0;
				}
				moveToPage(table);
			}
						
			
			function moveToPage(table) {
				var c = table.config;
				if(c.page < 0 || c.page > (c.totalPages-1)) {
					c.page = 0;
				}
				
				renderTable(table,c.rowsCopy);
			}

			function checkAll(table, pager, value)
			{
				// Mark all the displayed items as check or unchecked depending on the value
				$(table).find(':checkbox[name^=selectObj]').attr('checked', value);
				// Set the 'selectionMode' for the future objects to load
				if (value)
				{
					table.config.selectionMode = 'negative';
				}
				else
				{
					table.config.selectionMode = 'positive';
				}
				$(pager).find(':input[name=selectionMode]').val(table.config.selectionMode);
				// Reset the list of saved selection...
				resetStoredSelection(pager);
				updateCounter(table, pager);
				return true;
			}
			
			function resetStoredSelection(pager)
			{
				$(':input[name^=storedSelection]', pager).remove();
			}
			
			function updateSelection(table, pager, id, value)
			{
				var valueToStore = value;
				if (table.config.selectionMode == 'negative')
				{
					valueToStore = !(valueToStore);
				}
				if (valueToStore)
				{
					if (table.config.select_mode == 'single')
					{
						$(':input[name^=storedSelection]', pager).remove(); // Remove any previous selection
					}
					if ($('#'+id, pager).length ==0)
					{
						$(pager).append($('<input type="hidden" id="'+id+'" name="storedSelection[]" value="'+id+'"></input>'));
					}
				}	
				else
				{
					if ($('#'+id, pager).length !=0)
					{
						$('#'+id, pager).remove();
					}
				}
				updateCounter(table, pager);
			}
			
			function updateCounter(table, pager)
			{
				var ex = $(':input[name^=storedSelection]', pager).length;
				var s = ex;
				if (table.config.selectionMode == 'negative')
				{
					s = table.config.totalRows - ex;
				}
				$('.selectedCount',pager).text(s);
				if (table.config.cssCount != '')
				{
					$(table.config.cssCount).val(s);
					$(table.config.cssCount).trigger('change');
				}
			}
			
			function getData(table, start, end)
			{
				if (table.ajax_request)
				{
					table.ajax_request.abort();
					table.ajax_request = null;
				}

				var c = table.config;
				var s = c.sortList[0];
				var s_col = null;
				var s_order = null;
				if (s != undefined)
				{
					s_col = s[0];
					s_order = (s[1] == 0) ? 'asc' : 'desc';
				}
				$('#loading', table.config.container).html('<img src="../images/indicator.gif" />');
				table.ajax_request = $.post(GetAbsoluteUrlAppRoot()+"pages/ajax.render.php",
						{ operation: 'pagination',
						  filter: c.filter,
						  extra_param: c.extra_params,
						  start: start,
						  end: end,
						  sort_col: s_col,
						  sort_order: s_order,
						  select_mode: c.select_mode,
						  display_key: c.displayKey,
						  display_list: c.displayList
						},
					    function(data)
					    {
							table.ajax_request = null; // Ajax request completed
							oData = $(data);
							var tableBody = $(table.tBodies[0]);
							
							// clear the table body
							
							$.tablesorter.clearTableBody(table);
							
							for(var i = 0; i < end-start; i++) {
								
								//tableBody.append(rows[i]);
								
								//var o = rows[i];
								var r = $(oData[i]);
								var l = r.length;
								for(var j=0; j < l; j++) {
									
									//tableBody[0].appendChild(r);
									tableBody[0].appendChild(r[j]);

								}
							}
							
							fixPosition(table,tableBody);
							if (c.selectionMode == 'negative')
							{
								$(table).find(':checkbox[name^=selectObj]').attr('checked', true);
							}
							
							if (table.config.select_mode == 'multiple')
							{
								$(table).find(':checkbox[name^=selectObj]').each(function() {
									var id = parseInt(this.value, 10);
									if ($('#'+id, table.config.container).length > 0)
									{
										if (c.selectionMode == 'positive')
										{
											$(this).attr('checked', true);
										}
										else
										{
											$(this).attr('checked', false);
										}
									}
								});
	
								$(table).find(':checkbox[name^=selectObj]').change(function() {
									updateSelection(table, table.config.container, this.value, this.checked);
								});
							}
							else if (table.config.select_mode == 'single')
							{
								$(table).find('input[name^=selectObject]:radio').each(function() {
									var id = parseInt(this.value, 10);
									if ($('#'+id, table.config.container).length > 0)
									{
										if (c.selectionMode == 'positive')
										{
											$(this).attr('checked', true);
										}
										else
										{
											$(this).attr('checked', false);
										}
									}
								});
	
								$(table).find('input[name^=selectObject]:radio').change(function() {
									updateSelection(table, table.config.container, this.value, this.checked);
								});
							}
							
							$(table).trigger("applyWidgets");
							
							if( c.page >= c.totalPages ) {
			        			moveToLastPage(table);
							}
							
							updatePageDisplay(c);
							updateCounter(table, table.config.container);
							renderPager(table, table.config.container);
							$(table).tableHover();
							$('#loading', table.config.container).empty();
					   });
			}
			
			function renderPager(table, pager)
			{
				var c = table.config;
				var s = c.page - 2;
				var nb = Math.ceil(c.totalRows / c.size);
				if (s < 0)
				{
					s = 0;
				}
				var e = s +5;
				if (e > nb)
				{
					e = nb;
					s = e - 5;
					if (s < 0) s = 0;
				}
				txt = '';
				for(var i=s; i<e; i++)
				{
					var page = 1+i;
					var link = ' '+page+' ';
					if (i != c.page)
					{
						link = ' <a href="#" class="no-arrow" page="'+i+'" id="gotopage_'+i+'">'+page+'</a> ';
					}
					else
					{
						link = ' <a href="#" class="no-arrow curr_page" page="'+i+'">'+page+'</a> ';
					}
					txt += link;
				}
				txt += '';
				$('#total', pager).text(c.totalRows);
				$('#index', pager).html(txt);
				for(var j=s; j<e; j++)
				{
					$('#gotopage_'+j, pager).click(function(){
						var idx = $(this).attr('page');
						table.config.page = idx;
						moveToPage(table);
					});
				}
			}
			
			function renderTable(table) {
				
				var c = table.config;
				//var l = rows.length;
				var s = (c.page * c.size);
				var e = (s + c.size);
				if(e > c.totalRows ) {
					e = c.totalRows;
				}
				
				getData(table, s, e);				
			}
			
			this.appender = function(table,rows) {
				
				var c = table.config;
				
				if (c.totalRows == 0)
				{
					c.totalRows = rows.length;
				}
				c.totalPages = Math.ceil(c.totalRows / c.size);
				
				renderTable(table,rows);
			};
						
			this.defaults = {
				size: 10,
				offset: 0,
				page: 0,
				totalRows: 0,
				totalPages: 0,
				container: null,
				cssNext: '.next',
				cssPrev: '.prev',
				cssFirst: '.first',
				cssLast: '.last',
				cssPageDisplay: '.pagedisplay',
				cssPageSize: '.pagesize',
				cssCount: '',
				seperator: "/",
				positionFixed: false,
				appender: this.appender,
				filter: '',
				extra_params: '',
				select_mode: '',
				totalSelected: 0,
				selectionMode: 'positive',
				displayKey: true,
				displayList: []
			};
			
			this.construct = function(settings) {
				
				return this.each(function() {	
					
					config = $.extend(this.config, $.tablesorterPager.defaults, settings);
					
					var table = this, pager = config.container;
				
					this.ajax_request = null;
					
					$(this).trigger("appendCache");
					
					config.size = parseInt($(".pagesize",pager).val());
					
					$(config.cssFirst,pager).click(function() {
						moveToFirstPage(table);
						return false;
					});
					$(config.cssNext,pager).click(function() {
						moveToNextPage(table);
						return false;
					});
					$(config.cssPrev,pager).click(function() {
						moveToPrevPage(table);
						return false;
					});
					$(config.cssLast,pager).click(function() {
						moveToLastPage(table);
						return false;
					});
					$(config.cssPageSize,pager).change(function() {
						setPageSize(table,parseInt($(this).val()));
						return false;
					});
					$(table).find(':checkbox.checkAll').removeAttr('onclick').click(function() {
						return checkAll(table, pager, this.checked);
					});
				});
			};
			
		}
	});
	// extend plugin scope
	$.fn.extend({
        tablesorterPager: $.tablesorterPager.construct
	});
	
})(jQuery);
