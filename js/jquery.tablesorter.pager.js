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
			
			function setPageSize(table,size, bReload) {
				var c = table.config;
				c.selectedSize = size;
				if (size == -1)
				{
					size = c.totalRows;
				}
				c.size = size;
				c.totalPages = Math.ceil(c.totalRows / c.size);
				c.pagerPositionSet = false;
				if (bReload)
				{
					moveToPage(table);
				}
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
			
			function storeSelection(table, pager, id, value)
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
			
			function loadSelection(table, pager)
			{
				table.config.selectionMode = $(pager).find(':input[name=selectionMode]').val();
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
				pager.parent().closest('table').find('.selectedCount').text(s);
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

				var oDataTable = $(table).closest('table.itop-datatable');
				var oConfig = {
					sort_index: s_col,
					sort_order: s_order,
					page_size: table.config.selectedSize
				};
				oDataTable.datatable('UpdateState', oConfig);

				$('#loading', table.config.container).html('<img src="../images/indicator.gif" />');
				table.ajax_request = $.post(AddAppContext(GetAbsoluteUrlAppRoot()+"pages/ajax.render.php"),
						{ operation: 'pagination',
						  filter: c.filter,
						  extra_param: c.extra_params,
						  start: start,
						  end: end,
						  sort_col: s_col,
						  sort_order: s_order,
						  select_mode: c.select_mode,
						  display_key: c.displayKey,
						  columns: c.columns,
						  class_aliases: c.class_aliases
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
							applySelection(table);
							
							$(table).trigger("applyWidgets");
							
							if( c.page >= c.totalPages ) {
			        			moveToLastPage(table);
							}
							
							updatePageDisplay(c);
							updateCounter(table, table.config.container);
							renderPager(table, table.config.container);
							$(table).tableHover();
							$('#loading', table.config.container).empty();

							saveParams(table.config);
					   });
			}
			
			function applySelection(table)
			{
				var c = table.config;
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
						storeSelection(table, table.config.container, this.value, this.checked);
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
						storeSelection(table, table.config.container, this.value, this.checked);
					});
				}
			}
			
			function renderPager(table, pager)
			{
				var c = table.config;
				var aPages = [0]; // first page
				var s = c.page - 1;
				var nb = Math.ceil(c.totalRows / c.size);
				if (s < 1)
				{
					s = 1;
				}
				var e = s +3;
				if (e >= nb)
				{
					e = nb;
					if ((e - 4) > 1)
					{
						s = e - 4;
					}
				}
				for(var i=s; i<e; i++)
				{
					aPages.push(i);
				}
				if ((nb > 1) && (nb > i))
				{
					aPages.push(nb - 1); // very last page					
				}
				
				txt = '';
				for(i=0; i<aPages.length; i++)
				{
					var page = 1+aPages[i];
					var link = '';
					var sDotsAfter = '';
					var sDotsBefore = '';
					if ((i == 0) && (aPages.length > 1) && (aPages[i+1] != aPages[i]+1))
					{
						sDotsAfter = '...'; // Gap between the last 2 page numbers
					}
					if ((i == aPages.length-1) && (aPages.length > 1) && (aPages[i-1] != aPages[i]-1))
					{
						sDotsBefore = '...'; // Gap between the first 2 page numbers
					}
					if (aPages[i] != c.page)
					{
						link = ' <span page="'+aPages[i]+'" id="gotopage_'+aPages[i]+'">'+sDotsBefore+page+sDotsAfter+'</span> ';
					}
					else
					{
						link = ' <span class="curr_page" page="'+aPages[i]+'">'+sDotsBefore+page+sDotsAfter+'</span> ';
					}
					txt += link;
				}
				txt += '';
				$('#total', pager).text(c.totalRows);
				$('#index', pager).html(txt);
				for(i=0; i<aPages.length; i++)
				{
					$('#gotopage_'+aPages[i], pager).click(function(){
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
			
			function saveParams(config) {
				
				var sPagerId = config.container.attr('id');

				var params = { size: config.selectedSize, page: config.page, sortList: config.sortList };
				if (window.pager_params == undefined)
				{
					window.pager_params = {};
				}
				window.pager_params[sPagerId] = params;
			};

			function restoreParams(table) {
				
				var sPagerId = config.container.attr('id');
				if (window.pager_params != undefined)
				{
					params = window.pager_params[sPagerId];

					if (params != undefined)
					{
						$(table.config.cssPageSize, table.config.container).val(params.size);
						setPageSize(table, params.size, false); // false => don't trigger a reload
						if (table.config.sortList != params.sortList)
						{
							$(table).trigger("sorton", [params.sortList]); // triggers a reload anyway
						}
					}
				}
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
				columns: {},
				class_aliases: {}
			};
			
			this.construct = function(settings) {
				
				return this.each(function() {

					try
					{
						config = $.extend(this.config, $.tablesorterPager.defaults, settings);

						var table = this, pager = config.container;
				
						this.ajax_request = null;
					
						config.selectedSize = parseInt($(".pagesize",pager).val());

						setPageSize(table,config.selectedSize, false);
						restoreParams(table, config);
					
						//$(this).trigger("appendCache"); // Load the data
						//console.log($.tablesorterPager);
						applySelection(table);

						$('.gotopage',pager).click(function() {
							var idx = $(this).attr('page');
							table.config.page = idx;
							moveToPage(table);
						});

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
							setPageSize(table,parseInt($(this).val()), true);
							return false;
						});
						$(table).find(':checkbox.checkAll').removeAttr('onclick').click(function() {
							return checkAll(table, pager, this.checked);
						});
					
						$(table).bind('load_selection', function() {
							loadSelection(table, pager);
							applySelection(table);
						});
						$(table).bind('check_all', function() {
							checkAll(table, pager, true);
						});
					}
					catch(err)
					{
						if (console && console.log)
						{
							console.log(err);
						}
					}
				});
			};
		}
	});
	// extend plugin scope
	$.fn.extend({
        tablesorterPager: $.tablesorterPager.construct
	});
	
})(jQuery);
