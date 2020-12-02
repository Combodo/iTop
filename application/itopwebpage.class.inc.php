<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

require_once(APPROOT."/application/nicewebpage.class.inc.php");
require_once(APPROOT."/application/applicationcontext.class.inc.php");
require_once(APPROOT."/application/user.preferences.class.inc.php");

/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 */
class iTopWebPage extends NiceWebPage implements iTabbedPage
{
	private $m_sMenu;
	//	private $m_currentOrganization;
	private $m_aMessages;
	private $m_aInitScript = array();
	protected $m_oTabs;
	protected $bBreadCrumbEnabled;
	protected $sBreadCrumbEntryId;
	protected $sBreadCrumbEntryLabel;
	protected $sBreadCrumbEntryDescription;
	protected $sBreadCrumbEntryUrl;
	protected $sBreadCrumbEntryIcon;
	protected $oCtx;

	/**
	 * iTopWebPage constructor.
	 *
	 * @param string $sTitle
	 * @param bool $bPrintable
	 *
	 * @throws \Exception
	 */
	public function __construct($sTitle, $bPrintable = false)
	{
		parent::__construct($sTitle, $bPrintable);
		$this->m_oTabs = new TabManager();
		$this->oCtx = new ContextTag(ContextTag::TAG_CONSOLE);

		ApplicationContext::SetUrlMakerClass('iTopStandardURLMaker');

		if ((count($_POST) == 0) || (array_key_exists('loginop', $_POST)))
		{
			// Create a breadcrumb entry for the current page, but get its title as late as possible (page title could be changed later)
			$this->bBreadCrumbEnabled = true;
		}
		else {
			$this->bBreadCrumbEnabled = false;
		}

		utils::InitArchiveMode();

		$this->m_sMenu = "";
		$this->m_aMessages = array();
		$this->SetRootUrl(utils::GetAbsoluteUrlAppRoot());
		$this->add_header("Content-type: text/html; charset=".self::PAGES_CHARSET);
		$this->no_cache();
		$this->add_xframe_options();
		$this->add_linked_stylesheet("../css/jquery.treeview.css");
		$this->add_linked_stylesheet("../css/jquery.autocomplete.css");
		$this->add_linked_stylesheet("../css/jquery-ui-timepicker-addon.css");
		$this->add_linked_stylesheet("../css/jquery.multiselect.css");
		$this->add_linked_stylesheet("../css/magnific-popup.css");
		$this->add_linked_stylesheet("../css/c3.min.css");
		$this->add_linked_stylesheet("../css/font-awesome/css/all.min.css");
		$this->add_linked_stylesheet("../css/font-awesome/css/v4-shims.min.css");
		$this->add_linked_stylesheet("../js/ckeditor/plugins/codesnippet/lib/highlight/styles/obsidian.css");

		$this->add_linked_script('../js/jquery.layout.min.js');
		$this->add_linked_script('../js/jquery.ba-bbq.min.js');
		$this->add_linked_script("../js/jquery.treeview.js");
		$this->add_linked_script("../js/jquery.autocomplete.js");
		$this->add_linked_script("../js/date.js");
		$this->add_linked_script("../js/jquery-ui-timepicker-addon.js");
		$this->add_linked_script("../js/jquery-ui-timepicker-addon-i18n.min.js");
		$this->add_linked_script("../js/jquery.blockUI.js");
		$this->add_linked_script("../js/utils.js");
		$this->add_linked_script("../js/swfobject.js");
		$this->add_linked_script("../js/ckeditor/ckeditor.js");
		$this->add_linked_script("../js/ckeditor/adapters/jquery.js");
		$this->add_linked_script("../js/ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js");
		$this->add_linked_script("../js/jquery.qtip-1.0.min.js");
		$this->add_linked_script('../js/property_field.js');
		$this->add_linked_script('../js/icon_select.js');
		$this->add_linked_script('../js/raphael-min.js');
		$this->add_linked_script('../js/d3.js');
		$this->add_linked_script('../js/c3.js');
		$this->add_linked_script('../js/jquery.multiselect.js');
		$this->add_linked_script('../js/ajaxfileupload.js');
		$this->add_linked_script('../js/jquery.mousewheel.js');
		$this->add_linked_script('../js/jquery.magnific-popup.min.js');
		$this->add_linked_script('../js/breadcrumb.js');
		$this->add_linked_script('../js/moment-with-locales.min.js');
		$this->add_linked_script('../js/showdown.min.js');
		$this->add_linked_script('../js/newsroom_menu.js');

		$this->add_dict_entry('UI:FillAllMandatoryFields');

		$this->add_dict_entries('Error:');
		$this->add_dict_entries('UI:Button:');
		$this->add_dict_entries('UI:Search:');
		$this->add_dict_entry('UI:UndefinedObject');
		$this->add_dict_entries('Enum:Undefined');


		if (!$this->IsPrintableVersion())
		{
			$this->PrepareLayout();
			$this->add_script(
				<<<EOF
function ShowAboutBox()
{
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'about_box'}, function(data){
		$('body').append(data);
	});
	return false;
}
function ArchiveMode(bEnable)
{
	var sPrevUrl = StripArchiveArgument(window.location.search);
	if (bEnable)
	{
		window.location.search = sPrevUrl + '&with-archive=1';
	}
	else
	{
		window.location.search = sPrevUrl + '&with-archive=0';
	}
}
function StripArchiveArgument(sUrl)
{
	var res = sUrl.replace(/&with-archive=[01]/g, '');
	return res;
}
EOF
			);
		}
	}

	/**
	 * @return bool
	 */
	protected function IsMenuPaneVisible()
	{
		$bLeftPaneOpen = true;
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			// Leave the pane opened
		}
		else
		{
			if (utils::ReadParam('force_menu_pane', null) === 0)
			{
				$bLeftPaneOpen = false;
			}
			elseif (appUserPreferences::GetPref('menu_pane', 'open') == 'closed')
			{
				$bLeftPaneOpen = false;
			}
		}

		return $bLeftPaneOpen;
	}

	/**
	 *
	 */
	protected function PrepareLayout()
	{
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			// No pin button
			$sConfigureWestPane = '';
		}
		else
		{
			$sConfigureWestPane =
				<<<EOF
                if (typeof myLayout !== "undefined")
                {
                    myLayout.addPinBtn( "#tPinMenu", "west" );
                }
EOF;
		}
		$sInitClosed = $this->IsMenuPaneVisible() ? '' : 'initClosed: true,';

		$sJSDisconnectedMessage = json_encode(Dict::S('UI:DisconnectedDlgMessage'));
		$sJSTitle = json_encode(Dict::S('UI:DisconnectedDlgTitle'));
		$sJSLoginAgain = json_encode(Dict::S('UI:LoginAgain'));
		$sJSStayOnThePage = json_encode(Dict::S('UI:StayOnThePage'));
		$aDaysMin = array(
			Dict::S('DayOfWeek-Sunday-Min'),
			Dict::S('DayOfWeek-Monday-Min'),
			Dict::S('DayOfWeek-Tuesday-Min'),
			Dict::S('DayOfWeek-Wednesday-Min'),
			Dict::S('DayOfWeek-Thursday-Min'),
			Dict::S('DayOfWeek-Friday-Min'),
			Dict::S('DayOfWeek-Saturday-Min'),
		);
		$aMonthsShort = array(
			Dict::S('Month-01-Short'),
			Dict::S('Month-02-Short'),
			Dict::S('Month-03-Short'),
			Dict::S('Month-04-Short'),
			Dict::S('Month-05-Short'),
			Dict::S('Month-06-Short'),
			Dict::S('Month-07-Short'),
			Dict::S('Month-08-Short'),
			Dict::S('Month-09-Short'),
			Dict::S('Month-10-Short'),
			Dict::S('Month-11-Short'),
			Dict::S('Month-12-Short'),
		);
		$sTimeFormat = AttributeDateTime::GetFormat()->ToTimeFormat();
		$oTimeFormat = new DateTimeFormat($sTimeFormat);

		// Date picker options
		$aPickerOptions = array(
			'showOn' => 'button',
			'buttonImage' => '../images/calendar.png',
			'buttonImageOnly' => true,
			'dateFormat' => AttributeDate::GetFormat()->ToDatePicker(),
			'constrainInput' => false,
			'changeMonth' => true,
			'changeYear' => true,
			'dayNamesMin' => $aDaysMin,
			'monthNamesShort' => $aMonthsShort,
			'firstDay' => (int)Dict::S('Calendar-FirstDayOfWeek'),
		);
		$sJSDatePickerOptions = json_encode($aPickerOptions);

		// Time picker additional options
		$sUserLang = Dict::GetUserLanguage();
		$sUserLangShort = strtolower(
			substr($sUserLang, 0, 2)
		);
		// PR #40 :  we are picking correct values for specific cases in dict files
		// some languages are using codes like zh-CN or pt-BR
		$sTimePickerLang = json_encode(
			Dict::S('INTERNAL:JQuery-DatePicker:LangCode', $sUserLangShort)
		);
		$aPickerOptions['showOn'] = '';
		$aPickerOptions['buttonImage'] = null;
		$aPickerOptions['timeFormat'] = $oTimeFormat->ToDatePicker();
		$aPickerOptions['controlType'] = 'select';
		$aPickerOptions['closeText'] = Dict::S('UI:Button:Ok');
		$sJSDateTimePickerOptions = json_encode($aPickerOptions);
		if ($sTimePickerLang != '"en"')
		{
			// More options that cannot be passed via json_encode since they must be evaluated client-side
			$aMoreJSOptions = ",
				'timeText': $.timepicker.regional[$sTimePickerLang].timeText,
				'hourText': $.timepicker.regional[$sTimePickerLang].hourText,
				'minuteText': $.timepicker.regional[$sTimePickerLang].minuteText,
				'secondText': $.timepicker.regional[$sTimePickerLang].secondText,
				'currentText': $.timepicker.regional[$sTimePickerLang].currentText
			}";
			$sJSDateTimePickerOptions = substr($sJSDateTimePickerOptions, 0, -1).$aMoreJSOptions;
		}
		$this->add_script(
			<<< JS
	function GetUserLanguage()
	{
		return $sTimePickerLang;
	}
	function PrepareWidgets()
	{
		// note: each action implemented here must be idempotent,
		//       because this helper function might be called several times on a given page 
	
	    // Note: Trigger image is wrapped in a span so we can display it we want 
		$(".date-pick").datepicker($sJSDatePickerOptions)
		    .next("img").wrap("<span>");
	
		// Hack for the date and time picker addon issue on Chrome (see #1305)
		// The workaround is to instantiate the widget on demand
		// It relies on the same markup, thus reverting to the original implementation should be straightforward
		$(".datetime-pick:not(.is-widget-ready)").each(function(){
			var oInput = this;
			$(oInput).addClass('is-widget-ready');
			$('<span><img class="datetime-pick-button" src="../images/calendar.png"></span>')
				.insertAfter($(this))
				.on('click', function(){
					$(oInput)
						.datetimepicker($sJSDateTimePickerOptions)
						.datetimepicker('show')
						.datetimepicker('option', 'onClose', function(dateText,inst){
							$(oInput).datetimepicker('destroy');
						})
						.on('click keypress', function(){
							$(oInput).datetimepicker('hide');
						});
				});
		});
	}
JS
		);

		// Attribute set tooltip on items
		$this->add_ready_script(
			<<<JS
	$('.attribute-set-item').each(function(){
		// Encoding only title as the content is already sanitized by the HTML attribute.
        var sLabel = $('<div/>').text($(this).attr('data-label')).html();
		var sDescription = $(this).attr('data-description');
		
		var oContent = {};
		
		// Make nice tooltip if item has a description, otherwise just make a title attribute so the truncated label can be read.
		if(sDescription !== '')
		{
			oContent.title = { text: sLabel };
			oContent.text = sDescription;
	    }
	    else
	    {
	    	oContent.text = sLabel;
	    }
	    
	    $(this).qtip({
	       content: oContent,
	       show: { delay: 300, when: 'mouseover' },
	       hide: { delay: 140, when: 'mouseout', fixed: true },
	       style: { name: 'dark', tip: 'bottomLeft' },
	       position: { corner: { target: 'topMiddle', tooltip: 'bottomLeft' }}
	    });
	});
JS
		);
		// Make image attributes zoomable
		$this->add_ready_script(
			<<<JS
		$('.view-image img').each(function(){
			$(this).attr('href', $(this).attr('src'))
		})
		.magnificPopup({type: 'image', closeOnContentClick: true });
JS
		);
		
		// Highlight code content created with CKEditor
		$this->add_ready_script(
			<<<JS
		// Highlight code content for HTML AttributeText
        $("[data-attribute-type='AttributeText'] .HTML pre").each(function(i, block) {
            hljs.highlightBlock(block);
        });        
		// Highlight code content for CaseLogs
		$("[data-attribute-type='AttributeCaseLog'] .caselog_entry_html pre").each(function(i, block) {
            hljs.highlightBlock(block);
        });
JS
		);

		$this->add_init_script(
			<<< JS
	try
	{
		var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method

		// Layout
		paneSize = GetUserPreference('menu_size', 300);
		if ($('body').length > 0)
		{
            myLayout = $('body').layout({
                west :	{
                            $sInitClosed minSize: 200, size: paneSize, spacing_open: 16, spacing_close: 16, slideTrigger_open: "click", hideTogglerOnSlide: true, enableCursorHotkey: false,
                            onclose_end: function(name, elt, state, options, layout)
                            {
                                    if (state.isSliding == false)
                                    {
                                        $('.menu-pane-exclusive').show();
                                        SetUserPreference('menu_pane', 'closed', true);
                                    }
                            },
                            onresize_end: function(name, elt, state, options, layout)
                            {
                                    if (state.isSliding == false)
                                    {
                                        SetUserPreference('menu_size', state.size, true);
                                    }
                            },
                                        
                            onopen_end: function(name, elt, state, options, layout)
                            {
                                if (state.isSliding == false)
                                {
                                    $('.menu-pane-exclusive').hide();
                                    SetUserPreference('menu_pane', 'open', true);
                                }
                            }
                        },
                center: {
                            onresize_end: function(name, elt, state, options, layout)
                            {
                                    $('.v-resizable').each( function() {
                                        var fixedWidth = $(this).parent().innerWidth() - 6;
                                        $(this).width(fixedWidth);
                                        // Make sure it cannot be resized horizontally
                                        $(this).resizable('options', { minWidth: fixedWidth, maxWidth:	fixedWidth });
                                        // Now adjust all the child 'items'
                                        var innerWidth = $(this).innerWidth() - 10;
                                        $(this).find('.item').width(innerWidth);
                                    });
                                    $('.panel-resized').trigger('resized');
                            }
                    
                        }
            });
        }
		window.clearTimeout(iPaneVisWatchDog);
		//myLayout.open( "west" );
		$('.ui-layout-resizer-west .ui-layout-toggler').css({background: 'transparent'});
		$sConfigureWestPane
		if ($('#left-pane').length > 0)
		{
		    $('#left-pane').layout({ resizable: false, spacing_open: 0, south: { size: 94 }, enableCursorHotkey: false });
		}
		// Tabs, using JQuery BBQ to store the history
		// The "tab widgets" to handle.
		var tabs = $('div[id^=tabbedContent]');
			
		// This selector will be reused when selecting actual tab widget A elements.
		var tab_a_selector = 'ul.ui-tabs-nav a';
		
		// Ugly patch for a change in the behavior of jQuery UI:
		// Before jQuery UI 1.9, tabs were always considered as "local" (opposed to Ajax)
		// when their href was beginning by #. Starting with 1.9, a <base> tag in the page
		// is taken into account and causes "local" tabs to be considered as Ajax
		// unless their URL is equal to the URL of the page...
		$('div[id^=tabbedContent] > ul > li > a').each(function() {
			var sHash = location.hash;
			var sHref = $(this).attr("href");
			if (sHref.match(/^#/))
			{
				var sCleanLocation = location.href.toString().replace(sHash, '').replace(/#$/, '');
				$(this).attr("href", sCleanLocation+$(this).attr("href"));
			}
		});

		// Enable tabs on all tab widgets. The `event` property must be overridden so
		// that the tabs aren't changed on click, and any custom event name can be
		// specified. Note that if you define a callback for the 'select' event, it
		// will be executed for the selected tab whenever the hash changes.
		tabs.tabs({
			event: 'change', 'show': function(event, ui) {
				$('.resizable', ui.panel).resizable(); // Make resizable everything that claims to be resizable !
			},
			beforeLoad: function( event, ui ) {
				if ( ui.tab.data('loaded') && (ui.tab.attr('data-cache') == 'true')) {
					event.preventDefault();
					return;
				}
				ui.panel.html('<div><img src="../images/indicator.gif"></div>');
				ui.jqXHR.done(function() {
					ui.tab.data( "loaded", true );
				});
			}
		});
	}
	catch(err)
	{
		// Do something with the error !
		alert(err);
	}
JS
		);

		$this->add_ready_script(
			<<< JS
	
	// Adjust initial size
	$('.v-resizable').each( function()
		{
			var parent_id = $(this).parent().id;
			// Restore the saved height
			var iHeight = GetUserPreference(parent_id+'_'+this.id+'_height', undefined);
			if (iHeight != undefined)
			{
				$(this).height(parseInt(iHeight, 10)); // Parse in base 10 !);
			}
			// Adjust the child 'item''s height and width to fit
			var container = $(this);
			var fixedWidth = container.parent().innerWidth() - 6;
			// Set the width to fit the parent
			$(this).width(fixedWidth);
			var headerHeight = $(this).find('.drag_handle').height();
			// Now adjust the width and height of the child 'item'
			container.find('.item').height(container.innerHeight() - headerHeight - 12).width(fixedWidth - 10);
		}
	);
	// Make resizable, vertically only everything that claims to be v-resizable !
	$('.v-resizable').resizable( { handles: 's', minHeight: $(this).find('.drag_handle').height(), minWidth: $(this).parent().innerWidth() - 6, maxWidth: $(this).parent().innerWidth() - 6, stop: function()
		{
			// Adjust the content
			var container = $(this);
			var headerHeight = $(this).find('.drag_handle').height();
			container.find('.item').height(container.innerHeight() - headerHeight - 12);//.width(container.innerWidth());
			var parent_id = $(this).parent().id;
			SetUserPreference(parent_id+'_'+this.id+'_height', $(this).height(), true); // true => persistent
		}
	} );
		
	// Tabs, using JQuery BBQ to store the history
	// The "tab widgets" to handle.
	var tabs = $('div[id^=tabbedContent]');
		
	// This selector will be reused when selecting actual tab widget A elements.
	var tab_a_selector = 'ul.ui-tabs-nav a';
	  
	// Define our own click handler for the tabs, overriding the default.
	tabs.find( tab_a_selector ).click(function()
	{
		var state = {};
				  
		// Get the id of this tab widget.
		var id = $(this).closest( 'div[id^=tabbedContent]' ).attr( 'id' );
		  
		// Get the index of this tab.
		var idx = $(this).parent().prevAll().length;
		
		// Set the state!
		state[ id ] = idx;
		$.bbq.pushState( state );
	});
	
	// refresh the hash when the tab is changed (from a JS script)
	$('body').on( 'tabsactivate', '.ui-tabs', function(event, ui) {
		var state = {};
			
		// Get the id of this tab widget.
		var id = $(ui.newTab).closest( 'div[id^=tabbedContent]' ).attr( 'id' );
		
		// Get the index of this tab.
		var idx = $(ui.newTab).prevAll().length;
			
		// Set the state!
		state[ id ] = idx;
		$.bbq.pushState( state );
	});
	
	// Bind an event to window.onhashchange that, when the history state changes,
	// iterates over all tab widgets, changing the current tab as necessary.
	$(window).bind( 'hashchange', function(e)
	{
		// Iterate over all tab widgets.
		tabs.each(function()
		{  
			// Get the index for this tab widget from the hash, based on the
			// appropriate id property. In jQuery 1.4, you should use e.getState()
			// instead of $.bbq.getState(). The second, 'true' argument coerces the
			// string value to a number.
			var idx = $.bbq.getState( this.id, true ) || 0;
			  
			// Select the appropriate tab for this tab widget by triggering the custom
			// event specified in the .tabs() init above (you could keep track of what
			// tab each widget is on using .data, and only select a tab if it has
			// changed).
			$(this).find( tab_a_selector ).eq( idx ).triggerHandler( 'change' );
		});

		// Iterate over all truncated lists to find whether they are expanded or not
		$('a.truncated').each(function()
		{
			var state = $.bbq.getState( this.id, true ) || 'close';
			if (state == 'open')
			{
				$(this).trigger('open');
			}
			else
			{
				$(this).trigger('close');	
			}
		});
	});
	
	// Shortcut menu actions
	$('.actions_button a').click( function() {
		aMatches = /#(.*)$/.exec(window.location.href);
		if (aMatches != null)
		{
			currentHash = aMatches[1];
			if ( /#(.*)$/.test(this.href))
			{
				this.href = this.href.replace(/#(.*)$/, '#'+currentHash);
			}
		}
	});

	// End of Tabs handling

	PrepareWidgets();

	// Make sortable, everything that claims to be sortable
	$('.sortable').sortable( {axis: 'y', cursor: 'move', handle: '.drag_handle', stop: function()
		{
			if ($(this).hasClass('persistent'))
			{
				// remember the sort order for next time the page is loaded...
				sSerialized = $(this).sortable('serialize', {key: 'menu'});
				var sTemp = sSerialized.replace(/menu=/g, '');
				SetUserPreference(this.id+'_order', sTemp.replace(/&/g, ','), true); // true => persistent !
			}
		}
	});
	docWidth = $(document).width();
	$('#ModalDlg').dialog({ autoOpen: false, modal: true, width: 0.8*docWidth, height: 'auto', maxHeight: $(window).height() - 50 }); // JQuery UI dialogs
	ShowDebug();
	$('#logOffBtn>ul').popupmenu();
	
	$('.caselog_header').click( function () { $(this).toggleClass('open').next('.caselog_entry,.caselog_entry_html').toggle(); });
	
	$(document).ajaxSend(function(event, jqxhr, options) {
		jqxhr.setRequestHeader('X-Combodo-Ajax', 'true');
	});
	$(document).ajaxError(function(event, jqxhr, options) {
		if (jqxhr.status == 401)
		{
			$('<div>'+$sJSDisconnectedMessage+'</div>').dialog({
				modal:true,
				title: $sJSTitle,
				close: function() { $(this).remove(); },
				minWidth: 400,
				buttons: [
					{ text: $sJSLoginAgain, click: function() { window.location.href= GetAbsoluteUrlAppRoot()+'pages/UI.php' } },
					{ text: $sJSStayOnThePage, click: function() { $(this).dialog('close'); } }
				]
			});
		}
	});
JS
		);
		$this->add_ready_script(InlineImage::FixImagesWidth());
		/*
		 * Not used since the sorting of the tables is always performed server-side
		AttributeDateTime::InitTableSorter($this, 'custom_date_time');
		AttributeDate::InitTableSorter($this, 'custom_date');
		*/

		$sUserPrefs = appUserPreferences::GetAsJSON();
		$this->add_script(
			<<<JS
//		// for JQuery history
//		function history_callback(hash)
//		{
//			// do stuff that loads page content based on hash variable
//			var aMatches = /^tab_(.*)$/.exec(hash);
//			if (aMatches != null)
//			{
//				var tab = $('#'+hash);
//				tab.parents('div[id^=tabbedContent]:first').tabs('select', aMatches[1]);
//			}
//		}

		function goBack()
		{
			window.history.back();
		}
		
		function BackToDetails(sClass, id, sDefaultUrl, sOwnershipToken)
		{
			window.bInCancel = true;
			if (id > 0)
			{
				sToken = '';
				if (sOwnershipToken != undefined)
				{
					sToken = '&token='+sOwnershipToken;
				}
				window.location.href = AddAppContext(GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=release_lock_and_details&class='+sClass+'&id='+id+sToken);
			}
			else
			{
				window.location.href = sDefaultUrl; // Already contains the context...				
			}
		}

		function BackToList(sClass)
		{
			window.location.href = AddAppContext(GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=search_oql&oql_class='+sClass+'&oql_clause=WHERE id=0');
		}
		
		function ShowDebug()
		{
			if ($('#rawOutput > div').html() != '')
			{
				$('#rawOutput').dialog( {autoOpen: true, modal:false, width: '80%'});
			}
		}
		
		var oUserPreferences = $sUserPrefs;

		// For disabling the CKEditor at init time when the corresponding textarea is disabled !
		CKEDITOR.plugins.add( 'disabler',
		{
			init : function( editor )
			{
				editor.on( 'instanceReady', function(e)
				{
					e.removeListener();
					$('#'+ editor.name).trigger('update');
				});
			}
			
		});

		
		function FixPaneVis()
		{
			$('.ui-layout-center, .ui-layout-north, .ui-layout-south').css({display: 'block'});
		}
JS
		);
	}


	/**
	 * @param string $sId Identifies the item, to search after it in the current breadcrumb
	 * @param string $sLabel Label of the breadcrumb item
	 * @param string $sDescription More information, displayed as a tooltip
	 * @param string $sUrl Specify a URL if the current URL as perceived on the browser side is not relevant
	 * @param string $sIcon Icon (relative or absolute) path that will be displayed next to the label
	 */
	public function SetBreadCrumbEntry($sId, $sLabel, $sDescription, $sUrl = '', $sIcon = '')
	{
		$this->bBreadCrumbEnabled = true;
		$this->sBreadCrumbEntryId = $sId;
		$this->sBreadCrumbEntryLabel = $sLabel;
		$this->sBreadCrumbEntryDescription = $sDescription;
		$this->sBreadCrumbEntryUrl = $sUrl;
		$this->sBreadCrumbEntryIcon = $sIcon;
	}

	/**
	 * State that there will be no breadcrumb item for the current page
	 */
	public function DisableBreadCrumb()
	{
		$this->bBreadCrumbEnabled = false;
		$this->sBreadCrumbEntryId = null;
		$this->sBreadCrumbEntryLabel = null;
		$this->sBreadCrumbEntryDescription = null;
		$this->sBreadCrumbEntryUrl = null;
		$this->sBreadCrumbEntryIcon = null;
	}

	/**
	 * @param string $sHtml
	 */
	public function AddToMenu($sHtml)
	{
		$this->m_sMenu .= $sHtml;
	}

	/**
	 * @return string
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function GetSiloSelectionForm()
	{
		// List of visible Organizations
		$iCount = 0;
		$oSet = null;
		if (MetaModel::IsValidClass('Organization'))
		{
			// Display the list of *favorite* organizations... but keeping in mind what is the real number of organizations
			$aFavoriteOrgs = appUserPreferences::GetPref('favorite_orgs', null);
			$oSearchFilter = new DBObjectSearch('Organization');
			$oSearchFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
			$oSet = new CMDBObjectSet($oSearchFilter);
			$iCount = $oSet->Count(); // total number of existing Orgs

			// Now get the list of Orgs to be displayed in the menu
			$oSearchFilter = DBObjectSearch::FromOQL(ApplicationMenu::GetFavoriteSiloQuery());
			$oSearchFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
			if (!empty($aFavoriteOrgs))
			{
				$oSearchFilter->AddCondition('id', $aFavoriteOrgs, 'IN');
			}
			$oSet = new CMDBObjectSet($oSearchFilter); // List of favorite orgs
		}
		switch ($iCount)
		{
			case 0:
			case 1:
				// No such dimension/silo or only one possible choice => nothing to select
				$sHtml = '<div id="SiloSelection"><!-- nothing to select --></div>';
				break;

			default:
				$sHtml = '';
				$oAppContext = new ApplicationContext();
				$iCurrentOrganization = $oAppContext->GetCurrentValue('org_id');
				$sHtml = '<div id="SiloSelection">';
				$sHtml .= '<form style="display:inline" action="'.utils::GetAbsoluteUrlAppRoot().'pages/UI.php">'; //<select class="org_combo" name="c[org_id]" title="Pick an organization" onChange="this.form.submit();">';

				$sFavoriteOrgs = '';
				$oWidget = new UIExtKeyWidget('Organization', 'org_id', '', true /* search mode */);
				$sHtml .= $oWidget->Display($this, 50, false, '', $oSet, $iCurrentOrganization, 'org_id', false, 'c[org_id]', '',
					array(
						'iFieldSize' => 20,
						'iMinChars' => MetaModel::GetConfig()->Get('min_autocomplete_chars'),
						'sDefaultValue' => Dict::S('UI:AllOrganizations'),
					),
					null, 'select', false /* bSearchMultiple */);
				$this->add_ready_script('$("#org_id").bind("extkeychange", function() { $("#SiloSelection form").submit(); } )');
				$this->add_ready_script("$('#label_org_id').click( function() { if ($('#org_id').val() == '') { $(this).val(''); } } );\n");
				// Add other dimensions/context information to this form
				$oAppContext->Reset('org_id'); // org_id is handled above and we want to be able to change it here !
				$oAppContext->Reset('menu'); // don't pass the menu, since a menu may expect more parameters
				$sHtml .= $oAppContext->GetForForm(); // Pass what remains, if anything...
				$sHtml .= '</form>';
				$sHtml .= '</div>';
		}

		return $sHtml;
	}

	/**
	 * @throws \DictExceptionMissingString
	 */
	public function DisplayMenu()
	{
		// Display the menu
		$oAppContext = new ApplicationContext();
		$iAccordionIndex = 0;

		ApplicationMenu::DisplayMenu($this, $oAppContext->GetAsHash());
	}

	/**
	* Handles the "newsroom" menu at the top-right of the screen
	*/
	protected function InitNewsroom()
	{
		$sNewsroomInitialImage = '';
		$aProviderParams = array();

		if (MetaModel::GetConfig()->Get('newsroom_enabled') !== false)
	 	{
			$oUser = UserRights::GetUserObject();
			/**
			 * @var iNewsroomProvider[] $aProviders
			 */
			$aProviders = MetaModel::EnumPlugins('iNewsroomProvider');
			foreach($aProviders as $oProvider)
			{
				$oProvider->SetConfig(MetaModel::GetConfig());
				$bProviderEnabled = appUserPreferences::GetPref('newsroom_provider_'.get_class($oProvider),true);
				if ($bProviderEnabled && $oProvider->IsApplicable($oUser))
				{
					$aProviderParams[] = array(
						'label' => $oProvider->GetLabel(),
						'fetch_url' => $oProvider->GetFetchURL(),
						'view_all_url' => $oProvider->GetViewAllURL(),
						'mark_all_as_read_url' => $oProvider->GetMarkAllAsReadURL(),
						'placeholders' => $oProvider->GetPlaceholders(),
						'ttl' => $oProvider->GetTTL(),
					);
				}
			}
		}
		// Show newsroom only if there are some providers
		if (count($aProviderParams) > 0)
		{
			$sImageUrl= 'fas fa-comment-dots';
			$sPlaceholderImageUrl= 'far fa-envelope';
			$aParams = array(
				'image_icon' => $sImageUrl,
				'placeholder_image_icon' => $sPlaceholderImageUrl,
				'cache_uuid' => 'itop-newsroom-'.UserRights::GetUserId().'-'.md5(APPROOT),
				'providers' => $aProviderParams,
				'display_limit' => (int)appUserPreferences::GetPref('newsroom_display_size', 7),
				'labels' => array(
					'no_message' => Dict::S('UI:Newsroom:NoNewMessage'),
					'mark_all_as_read' => Dict::S('UI:Newsroom:MarkAllAsRead'),
					'view_all' => Dict::S('UI:Newsroom:ViewAllMessages'),
				),
			);
			$sParams = json_encode($aParams);
			$this->add_ready_script(
<<<EOF
	$('#top-left-newsroom-cell').newsroom_menu($sParams);
EOF
			);
			$sNewsroomInitialImage = '<i style="opacity:0.4" class="top-right-icon fas fa-comment-dots"></i>';
		}
		// else no newsroom menu
		return $sNewsroomInitialImage;
	}


	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function output()
	{
		$sAbsURLAppRoot = addslashes($this->m_sRootUrl);

		//$this->set_base($this->m_sRootUrl.'pages/');
		$sForm = $this->GetSiloSelectionForm();
		$this->DisplayMenu(); // Compute the menu

		// Call the extensions to add content to the page, so that they can also add styles or scripts
		$sBannerExtraHtml = '';
		foreach (MetaModel::EnumPlugins('iPageUIExtension') as $oExtensionInstance)
		{
			$sBannerExtraHtml .= $oExtensionInstance->GetBannerHtml($this);
		}

		$sNorthPane = '';
		foreach (MetaModel::EnumPlugins('iPageUIExtension') as $oExtensionInstance)
		{
			$sNorthPane .= $oExtensionInstance->GetNorthPaneHtml($this);
		}

		if (UserRights::IsAdministrator() && ExecutionKPI::IsEnabled())
		{
			$sNorthPane .= '<div class="app-message"><span style="padding:5px;">'.ExecutionKPI::GetDescription().'<span></div>';
		}

		//$sSouthPane = '<p>Peak memory Usage: '.sprintf('%.3f MB', memory_get_peak_usage(true) / (1024*1024)).'</p>';
		$sSouthPane = '';
		foreach (MetaModel::EnumPlugins('iPageUIExtension') as $oExtensionInstance)
		{
			$sSouthPane .= $oExtensionInstance->GetSouthPaneHtml($this);
		}

		// Render the tabs in the page (if any)
		$this->s_content = $this->m_oTabs->RenderIntoContent($this->s_content, $this);

		// Put here the 'ready scripts' that must be executed after all others
		$aMultiselectOptions = array(
			'header' => true,
			'checkAllText' => Dict::S('UI:SearchValue:CheckAll'),
			'uncheckAllText' => Dict::S('UI:SearchValue:UncheckAll'),
			'noneSelectedText' => Dict::S('UI:SearchValue:Any'),
			'selectedText' => Dict::S('UI:SearchValue:NbSelected'),
			'selectedList' => 1,
		);
		$sJSMultiselectOptions = json_encode($aMultiselectOptions);
		$this->add_ready_script(
			<<<EOF
		// Since the event is only triggered when the hash changes, we need to trigger
		// the event now, to handle the hash the page may have loaded with.
		$(window).trigger( 'hashchange' );
		
		// Some table are sort-able, some are not, let's fix this
		$('table.listResults').each( function() { FixTableSorter($(this)); } );
		
		$('.multiselect').multiselect($sJSMultiselectOptions);
EOF
		);

		$iBreadCrumbMaxCount = utils::GetConfig()->Get('breadcrumb.max_count');
		if ($iBreadCrumbMaxCount > 1)
		{
			$oConfig = MetaModel::GetConfig();
			$siTopInstanceId = json_encode($oConfig->GetItopInstanceid());
			if ($this->bBreadCrumbEnabled)
			{
				if (is_null($this->sBreadCrumbEntryId))
				{
					$this->sBreadCrumbEntryId = $this->s_title;
					$this->sBreadCrumbEntryLabel = $this->s_title;
					$this->sBreadCrumbEntryDescription = $this->s_title;
					$this->sBreadCrumbEntryUrl = '';
					$this->sBreadCrumbEntryIcon = utils::GetAbsoluteUrlAppRoot().'images/wrench.png';
				}
				$sNewEntry = json_encode(array(
					'id' => $this->sBreadCrumbEntryId,
					'url' => $this->sBreadCrumbEntryUrl,
					'label' => htmlentities($this->sBreadCrumbEntryLabel, ENT_QUOTES, self::PAGES_CHARSET),
					'description' => htmlentities($this->sBreadCrumbEntryDescription, ENT_QUOTES, self::PAGES_CHARSET),
					'icon' => $this->sBreadCrumbEntryIcon,
				));
			}
			else
			{
				$sNewEntry = 'null';
			}

			$this->add_ready_script(
				<<<EOF
		$('#itop-breadcrumb').breadcrumb({itop_instance_id: $siTopInstanceId, new_entry: $sNewEntry, max_count: $iBreadCrumbMaxCount});
EOF
			);
		}

		$sNewsRoomInitialImage = $this->InitNewsroom();

		$this->outputCollapsibleSectionInit();

		if ($this->GetOutputFormat() == 'html')
		{
			foreach ($this->a_headers as $s_header)
			{
				header($s_header);
			}
		}
		$s_captured_output = $this->ob_get_clean_safe();
		$sHtml = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
		$sHtml .= "<html>\n";
		$sHtml .= "<head>\n";
		// Make sure that Internet Explorer renders the page using its latest/highest/greatest standards !
		$sHtml .= "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\n";
		$sPageCharset = self::PAGES_CHARSET;
		$sHtml .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$sPageCharset\" />\n";
		$sHtml .= "<title>".htmlentities($this->s_title, ENT_QUOTES, $sPageCharset)."</title>\n";
		$sHtml .= $this->get_base_tag();
		// Stylesheets MUST be loaded before any scripts otherwise
		// jQuery scripts may face some spurious problems (like failing on a 'reload')
		foreach ($this->a_linked_stylesheets as $a_stylesheet)
		{
			if (strpos($a_stylesheet['link'], '?') === false)
			{
				$s_stylesheet = $a_stylesheet['link']."?t=".utils::GetCacheBusterTimestamp();
			}
			else
			{
				$s_stylesheet = $a_stylesheet['link']."&t=".utils::GetCacheBusterTimestamp();
			}
			if ($a_stylesheet['condition'] != "")
			{
				$sHtml .= "<!--[if {$a_stylesheet['condition']}]>\n";
			}
			$sHtml .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$s_stylesheet}\" />\n";
			if ($a_stylesheet['condition'] != "")
			{
				$sHtml .= "<![endif]-->\n";
			}
		}
		// special stylesheet for printing, hides the navigation gadgets
		$sHtml .= "<link rel=\"stylesheet\" media=\"print\" type=\"text/css\" href=\"../css/print.css?t=".utils::GetCacheBusterTimestamp()."\" />\n";

		if ($this->GetOutputFormat() == 'html')
		{
			$sHtml .= $this->output_dict_entries(true); // before any script so that they can benefit from the translations
			foreach ($this->a_linked_scripts as $s_script)
			{
				// Make sure that the URL to the script contains the application's version number
				// so that the new script do NOT get reloaded from the cache when the application is upgraded
				if (strpos($s_script, '?') === false)
				{
					$s_script .= "?t=".utils::GetCacheBusterTimestamp();
				}
				else
				{
					$s_script .= "&t=".utils::GetCacheBusterTimestamp();
				}
				$sHtml .= "<script type=\"text/javascript\" src=\"$s_script\"></script>\n";
			}
			if (!$this->IsPrintableVersion())
			{
				$this->add_script("var iPaneVisWatchDog  = window.setTimeout('FixPaneVis()',5000);");
			}
			$sInitScripts = "";
			if (count($this->m_aInitScript) > 0)
			{
				foreach ($this->m_aInitScript as $m_sInitScript)
				{
					$sInitScripts .= "$m_sInitScript\n";
				}
			}
			$this->add_script("\$(document).ready(function() {\n{$sInitScripts};\nwindow.setTimeout('onDelayedReady()',10)\n});");
			if ($this->IsPrintableVersion())
			{
				$this->add_ready_script(
					<<<EOF
var sHiddeableChapters = '<div class="light ui-tabs ui-widget ui-widget-content ui-corner-all">';
sHiddeableChapters += '<ul role="tablist" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">';
for (sId in oHiddeableChapters)
{
	sHiddeableChapters += '<li tabindex="-1" role="tab" class="ui-state-default ui-corner-top hideable-chapter" chapter-id="'+sId+'"><span class="tab ui-tabs-anchor">' + oHiddeableChapters[sId] + '</span></li>';
	//alert(oHiddeableChapters[sId]);
}
sHiddeableChapters += '</ul></div>';
$('#hiddeable_chapters').html(sHiddeableChapters);
$('.hideable-chapter').click(function(){
	var sChapterId = $(this).attr('chapter-id');
	$('#'+sChapterId).toggle();
	$(this).toggleClass('strikethrough');
});
$('fieldset').each(function() {
	var jLegend = $(this).find('legend');
	jLegend.remove();
	$(this).wrapInner('<span></span>').prepend(jLegend);
});
$('legend').css('cursor', 'pointer').click(function(){
		$(this).parent('fieldset').toggleClass('not-printable strikethrough');
	});
EOF
				);
			}
			if (count($this->m_aReadyScripts) > 0)
			{
				$this->add_script("\nonDelayedReady = function() {\n".implode("\n", $this->m_aReadyScripts)."\n}\n");
			}
			if (count($this->a_scripts) > 0)
			{
				$sHtml .= "<script type=\"text/javascript\">\n";
				foreach ($this->a_scripts as $s_script)
				{
					$sHtml .= "$s_script\n";
				}
				$sHtml .= "</script>\n";
			}
		}

		if (count($this->a_styles) > 0)
		{
			$sHtml .= "<style>\n";
			foreach ($this->a_styles as $s_style)
			{
				$sHtml .= "$s_style\n";
			}
			$sHtml .= "</style>\n";
		}
		$sHtml .= "<link rel=\"search\" type=\"application/opensearchdescription+xml\" title=\"iTop\" href=\"".utils::GetAbsoluteUrlAppRoot()."pages/opensearch.xml.php\" />\n";
		$sHtml .= "<link rel=\"shortcut icon\" href=\"".utils::GetAbsoluteUrlAppRoot()."images/favicon.ico?t=".utils::GetCacheBusterTimestamp()."\" />\n";

		$sHtml .= "</head>\n";
		$sBodyClass = "";
		if ($this->IsPrintableVersion())
		{
			$sBodyClass = 'printable-version';
		}
		$sHtml .= "<body class=\"$sBodyClass\" data-gui-type=\"backoffice\">\n";
		if ($this->IsPrintableVersion())
		{
			$sHtml .= "<div class=\"explain-printable not-printable\">";
			$sHtml .= '<p>'.Dict::Format('UI:ExplainPrintable',
					'<img src="../images/eye-open-555.png" style="vertical-align:middle">').'</p>';
			$sHtml .= "<div id=\"hiddeable_chapters\"></div>";
			$sHtml .= '<button onclick="window.print()">'.htmlentities(Dict::S('UI:Button:GoPrint'), ENT_QUOTES,
					self::PAGES_CHARSET).'</button>';
			$sHtml .= '&nbsp;';
			$sHtml .= '<button onclick="window.close()">'.htmlentities(Dict::S('UI:Button:Cancel'), ENT_QUOTES,
					self::PAGES_CHARSET).'</button>';
			$sHtml .= '&nbsp;';

			$sDefaultResolution = '27.7cm';
			$aResolutionChoices = array(
				'100%' => Dict::S('UI:PrintResolution:FullSize'),
				'19cm' => Dict::S('UI:PrintResolution:A4Portrait'),
				'27.7cm' => Dict::S('UI:PrintResolution:A4Landscape'),
				'19.6cm' => Dict::S('UI:PrintResolution:LetterPortrait'),
				'25.9cm' => Dict::S('UI:PrintResolution:LetterLandscape'),
			);
			$sHtml .=
				<<<EOF
<select name="text" onchange='$(".printable-content").width(this.value); $(charts).each(function(i, chart) { $(chart).trigger("resize"); });'>
EOF;
			foreach ($aResolutionChoices as $sValue => $sText)
			{
				$sHtml .= '<option value="'.$sValue.'" '.(($sValue === $sDefaultResolution) ? 'selected' : '').'>'.$sText.'</option>';
			}
			$sHtml .= "</select>";

			$sHtml .= "</div>";
			$sHtml .= "<div class=\"printable-content\" style=\"width: $sDefaultResolution;\">";
		}

		// Render the revision number
		if (ITOP_REVISION == 'svn')
		{
			// This is NOT a version built using the buil system, just display the main version
			$sVersionString = Dict::Format('UI:iTopVersion:Short', ITOP_APPLICATION, ITOP_VERSION);
		}
		else
		{
			// This is a build made from SVN, let display the full information
			$sVersionString = Dict::Format('UI:iTopVersion:Long', ITOP_APPLICATION, ITOP_VERSION, ITOP_REVISION, ITOP_BUILD_DATE);
		}

		// Render the text of the global search form
		$sText = htmlentities(utils::ReadParam('text', '', false, 'raw_data'), ENT_QUOTES, self::PAGES_CHARSET);
		$sOnClick = " onclick=\"if ($('#global-search-input').val() != '') { $('#global-search form').submit();  } \"";
		$sDefaultPlaceHolder = Dict::S("UI:YourSearch");

		if ($this->IsPrintableVersion())
		{
			$sHtml .= ' <!-- Beginning of page content -->';
			$sHtml .= self::FilterXSS($this->s_content);
			$sHtml .= ' <!-- End of page content -->';
		}
		elseif ($this->GetOutputFormat() == 'html')
		{
			$oAppContext = new ApplicationContext();

			$sUserName = UserRights::GetUser();
			$sIsAdmin = UserRights::IsAdministrator() ? '(Administrator)' : '';
			if (UserRights::IsAdministrator())
			{
				$sLogonMessage = Dict::Format('UI:LoggedAsMessage+Admin', $sUserName);
			}
			else
			{
				$sLogonMessage = Dict::Format('UI:LoggedAsMessage', $sUserName);
			}
			$sLogOffMenu = "<span id=\"logOffBtn\"><ul><li><i class=\"top-right-icon icon-additional-arrow fas fa-power-off\"></i><ul>";
			$sLogOffMenu .= "<li><span>$sLogonMessage</span></li>\n";
			$aActions = array();

			$aAllowedPortals = UserRights::GetAllowedPortals();
			if (count($aAllowedPortals) > 1)
			{
				// Adding portals
				foreach ($aAllowedPortals as $aAllowedPortal)
				{
					if ($aAllowedPortal['id'] !== 'backoffice')
					{
						$oPortalMenuItem = new URLPopupMenuItem('portal:'.$aAllowedPortal['id'], Dict::S($aAllowedPortal['label']),
							$aAllowedPortal['url'], '_blank');
						$aActions[$oPortalMenuItem->GetUID()] = $oPortalMenuItem->GetMenuItem();
					}
				}
				// Adding a separator
				$oPortalSeparatorMenuItem = new SeparatorPopupMenuItem();
				$aActions[$oPortalSeparatorMenuItem->GetUID()] = $oPortalSeparatorMenuItem->GetMenuItem();
			}

			$oPrefs = new URLPopupMenuItem('UI:Preferences', Dict::S('UI:Preferences'),
				utils::GetAbsoluteUrlAppRoot()."pages/preferences.php?".$oAppContext->GetForLink());
			$aActions[$oPrefs->GetUID()] = $oPrefs->GetMenuItem();

			if (utils::IsArchiveMode())
			{
				$oExitArchive = new JSPopupMenuItem('UI:ArchiveModeOff', Dict::S('UI:ArchiveModeOff'), 'return ArchiveMode(false);');
				$aActions[$oExitArchive->GetUID()] = $oExitArchive->GetMenuItem();

				$sIcon = '<span class="fas fa-lock fa-1x"></span>';
				$this->AddApplicationMessage(Dict::S('UI:ArchiveMode:Banner'), $sIcon, Dict::S('UI:ArchiveMode:Banner+'));
			}
			elseif (UserRights::CanBrowseArchive())
			{
				$oBrowseArchive = new JSPopupMenuItem('UI:ArchiveModeOn', Dict::S('UI:ArchiveModeOn'), 'return ArchiveMode(true);');
				$aActions[$oBrowseArchive->GetUID()] = $oBrowseArchive->GetMenuItem();
			}
			if (utils::CanLogOff())
			{
				$oLogOff = new URLPopupMenuItem('UI:LogOffMenu', Dict::S('UI:LogOffMenu'),
					utils::GetAbsoluteUrlAppRoot().'pages/logoff.php?operation=do_logoff');
				$aActions[$oLogOff->GetUID()] = $oLogOff->GetMenuItem();
			}
			if (UserRights::CanChangePassword())
			{
				$oChangePwd = new URLPopupMenuItem('UI:ChangePwdMenu', Dict::S('UI:ChangePwdMenu'),
					utils::GetAbsoluteUrlAppRoot().'pages/UI.php?loginop=change_pwd');
				$aActions[$oChangePwd->GetUID()] = $oChangePwd->GetMenuItem();
			}
			utils::GetPopupMenuItems($this, iPopupMenuExtension::MENU_USER_ACTIONS, null, $aActions);

			$oAbout = new JSPopupMenuItem('UI:AboutBox', Dict::S('UI:AboutBox'), 'return ShowAboutBox();');
			$aActions[$oAbout->GetUID()] = $oAbout->GetMenuItem();

			$sLogOffMenu .= $this->RenderPopupMenuItems($aActions);


			$sRestrictions = '';
			if (!MetaModel::DBHasAccess(ACCESS_ADMIN_WRITE))
			{
				if (!MetaModel::DBHasAccess(ACCESS_ADMIN_WRITE))
				{
					$sRestrictions = Dict::S('UI:AccessRO-All');
				}
			}
			elseif (!MetaModel::DBHasAccess(ACCESS_USER_WRITE))
			{
				$sRestrictions = Dict::S('UI:AccessRO-Users');
			}

			if (strlen($sRestrictions) > 0)
			{
				$sIcon =
					<<<EOF
<span class="fa-stack fa-sm">
  <i class="fas fa-pencil-alt fa-flip-horizontal fa-stack-1x"></i>
  <i class="fas fa-ban fa-stack-2x text-danger"></i>
</span>
EOF;

				$sAdminMessage = trim(MetaModel::GetConfig()->Get('access_message'));
				if (strlen($sAdminMessage) > 0)
				{
					$sRestrictions .= '&nbsp;'.$sAdminMessage;
				}
				$this->AddApplicationMessage($sRestrictions, $sIcon);
			}

			$sApplicationMessages = '';
			foreach ($this->m_aMessages as $aMessage)
			{
				$sHtmlIcon = $aMessage['icon'] ? $aMessage['icon'] : '';
				$sHtmlMessage = $aMessage['message'];
				$sTitleAttr = $aMessage['tip'] ? 'title="'.htmlentities($aMessage['tip'], ENT_QUOTES, self::PAGES_CHARSET).'"' : '';
				$sApplicationMessages .= '<div class="app-message" '.$sTitleAttr.'><span class="app-message-icon">'.$sHtmlIcon.'</span><span class="app-message-body">'.$sHtmlMessage.'</div></span>';
			}

			$sApplicationBanner = "<div class=\"app-banner ui-helper-clearfix\">$sApplicationMessages$sBannerExtraHtml</div>";

			if (!empty($sNorthPane))
			{
				$sNorthPane = '<div id="top-pane" class="ui-layout-north">'.$sNorthPane.'</div>';
			}

			if (!empty($sSouthPane))
			{
				$sSouthPane = '<div id="bottom-pane" class="ui-layout-south">'.$sSouthPane.'</div>';
			}

			$sIconUrl = Utils::GetConfig()->Get('app_icon_url');
			$sOnlineHelpUrl = MetaModel::GetConfig()->Get('online_help');
			//$sLogOffMenu = "<span id=\"logOffBtn\" style=\"height:55px;padding:0;margin:0;\"><img src=\"../images/onOffBtn.png\"></span>";

			$sDisplayIcon = utils::GetAbsoluteUrlAppRoot().'images/itop-logo.png?t='.utils::GetCacheBusterTimestamp();
			if (file_exists(MODULESROOT.'branding/main-logo.png'))
			{
				$sDisplayIcon = utils::GetAbsoluteUrlModulesRoot().'branding/main-logo.png?t='.utils::GetCacheBusterTimestamp();
			}

			$sHtml .= $sNorthPane;
			$sHtml .= '<div id="left-pane" class="ui-layout-west">';
			$sHtml .= '<!-- Beginning of the left pane -->';
			$sHtml .= ' <div class="ui-layout-north">';
			$sHtml .= ' <div id="header-logo">';
			$sHtml .= ' <div id="top-left"></div><div id="logo"><a href="'
				.htmlentities($sIconUrl, ENT_QUOTES, self::PAGES_CHARSET)
				.'"><img src="'.$sDisplayIcon.'" title="'
				.htmlentities($sVersionString, ENT_QUOTES, self::PAGES_CHARSET)
				.'" style="border:0; margin-top:16px; margin-right:40px;"/></a></div>';
			$sHtml .= ' </div>';
			$sHtml .= ' <div class="header-menu">';
			if (!MetaModel::GetConfig()->Get('demo_mode'))
			{
				$sHtml .= '		<div class="icon ui-state-default ui-corner-all"><span id="tPinMenu" class="ui-icon ui-icon-pin-w">pin</span></div>';
			}
			$sHtml .= '		<div style="text-align:center;">'.self::FilterXSS($sForm).'</div>';
			$sHtml .= ' </div>';
			$sHtml .= ' </div>';
			$sHtml .= ' <div id="menu" class="ui-layout-center">';
			$sHtml .= '		<div id="inner_menu">';
			$sHtml .= '			<div id="accordion">';
			$sHtml .= self::FilterXSS($this->m_sMenu);
			$sHtml .= '			<!-- Beginning of the accordion menu -->';
			$sHtml .= '			<!-- End of the accordion menu-->';
			$sHtml .= '			</div>';
			$sHtml .= '		</div> <!-- /inner menu -->';
			$sHtml .= ' </div> <!-- /menu -->';
			$sHtml .= ' <div class="footer ui-layout-south"><div id="combodo_logo"><a href="http://www.combodo.com" title="www.combodo.com" target="_blank"><img src="../images/logo-combodo.png?t='.utils::GetCacheBusterTimestamp().'"/></a></div></div>';
			$sHtml .= '<!-- End of the left pane -->';
			$sHtml .= '</div>';

			$sHtml .= '<div class="ui-layout-center">';
			$sHtml .= ' <div id="top-bar" class="ui-helper-clearfix" style="width:100%">';
			$sHtml .= self::FilterXSS($sApplicationBanner);

			$GoHomeInitialStyle = $this->IsMenuPaneVisible() ? 'display: none;' : '';

			$sHtml .= ' <table id="top-bar-table">';
			$sHtml .= ' <tr>';
			$sHtml .= ' <td id="open-left-pane"  class="menu-pane-exclusive" style="'.$GoHomeInitialStyle.'" onclick="$(\'body\').layout().open(\'west\');">';
			$sHtml .= ' <i class="fas fa-bars"></i>';
			$sHtml .= ' </td>';
			$sHtml .= ' <td id="go-home" class="menu-pane-exclusive" style="'.$GoHomeInitialStyle.'">';
			$sHtml .= ' <a href="'.utils::GetAbsoluteUrlAppRoot().'pages/UI.php"><i class="fas fa-home"></i></a>';
			$sHtml .= ' </td>';
			$sHtml .= ' <td class="top-bar-spacer menu-pane-exclusive" style="'.$GoHomeInitialStyle.'">';
			$sHtml .= ' </td>';
			$sHtml .= ' <td id="top-bar-table-breadcrumb">';
			$sHtml .= ' <div id="itop-breadcrumb"></div>';
			$sHtml .= ' </td>';
			$sHtml .= ' <td id="top-bar-table-search">';
			$sHtml .= '		<div id="global-search"><form action="'.utils::GetAbsoluteUrlAppRoot().'pages/UI.php">';
			$sHtml .= '		<table id="top-left-buttons-area"><tr>';
			$sHtml .= '			<td id="top-left-global-search-cell"><div id="global-search-area"><input id="global-search-input" type="text" name="text" placeholder="'.$sDefaultPlaceHolder.'" value="'.$sText.'"></input><div '.$sOnClick.' id="global-search-image"><i class="top-right-icon fa-flip-horizontal fas fa-search"></i><input type="hidden" name="operation" value="full_text"/></div></div></td>';
			$sHtml .= '     	<td id="top-left-help-cell"><a id="help-link" href="'.$sOnlineHelpUrl.'" target="_blank" title="'.Dict::S('UI:Help').'"><i class="top-right-icon fas fa-question-circle"></i></a></td>';
			$sHtml .= '		<td id="top-left-newsroom-cell">'.$sNewsRoomInitialImage.'</td>';
			$sHtml .= '     	<td id="top-left-logoff-cell">'.self::FilterXSS($sLogOffMenu).'</td>';
			$sHtml .= '     </tr></table></form></div>';
			$sHtml .= ' </td>';
			$sHtml .= ' </tr>';
			$sHtml .= ' </table>';

//			$sHtml .= '		<div id="global-search"><form action="'.utils::GetAbsoluteUrlAppRoot().'pages/UI.php"><table><tr><td></td><td><div id="global-search-area"><input id="global-search-input" type="text" name="text" placeholder="'.$sText.'"></input><div '.$sOnClick.' id="global-search-image"></div></div></td>';
//			$sHtml .= '<td><a id="help-link" href="'.$sOnlineHelpUrl.'" target="_blank"><img title="'.Dict::S('UI:Help').'" src="../images/help.png?t='.utils::GetCacheBusterTimestamp().'"/></td>';
//			$sHtml .= '<td>'.self::FilterXSS($sLogOffMenu).'</td><td><input type="hidden" name="operation" value="full_text"/></td></tr></table></form></div>';
//			$sHtml .= ' <div id="itop-breadcrumb"></div>';

			$sHtml .= ' </div>';
			$sHtml .= ' <div class="ui-layout-content" style="overflow:auto;">';
			$sHtml .= ' <!-- Beginning of page content -->';
			$sHtml .= self::FilterXSS($this->s_content);
			$sHtml .= ' <!-- End of page content -->';
			$sHtml .= ' </div>';
			$sHtml .= '</div>';
			$sHtml .= $sSouthPane;

			// Add the captured output
			if (trim($s_captured_output) != "")
			{
				$sHtml .= "<div id=\"rawOutput\" title=\"Debug Output\"><div style=\"height:500px; overflow-y:auto;\">".self::FilterXSS($s_captured_output)."</div></div>\n";
			}
			$sHtml .= "<div id=\"at_the_end\">".self::FilterXSS($this->s_deferred_content)."</div>";
			$sHtml .= "<div style=\"display:none\" title=\"ex2\" id=\"ex2\">Please wait...</div>\n"; // jqModal Window
			$sHtml .= "<div style=\"display:none\" title=\"dialog\" id=\"ModalDlg\"></div>";
			$sHtml .= "<div style=\"display:none\" id=\"ajax_content\"></div>";
		}
		else
		{
			$sHtml .= self::FilterXSS($this->s_content);
		}

		if ($this->IsPrintableVersion())
		{
			$sHtml .= '</div>';
		}

		$sHtml .= "</body>\n";
		$sHtml .= "</html>\n";

		if ($this->GetOutputFormat() == 'html')
		{
			$oKPI = new ExecutionKPI();
			echo $sHtml;
			$oKPI->ComputeAndReport('Echoing ('.round(strlen($sHtml) / 1024).' Kb)');
		}
		else
		{
			if ($this->GetOutputFormat() == 'pdf' && $this->IsOutputFormatAvailable('pdf'))
			{
				// Note: Apparently this was a demand from ITOMIG a while back, so it's not "dead code" per say.
				// The last trace we got is in R-007989. Do not remove this without checking before with the concerned parties if it is still used!
				if (@is_readable(APPROOT.'lib/MPDF/mpdf.php'))
				{
					require_once(APPROOT.'lib/MPDF/mpdf.php');
					/** @noinspection PhpUndefinedClassInspection Check above comment */
					$oMPDF = new mPDF('c');
					$oMPDF->mirroMargins = false;
					if ($this->a_base['href'] != '')
					{
						$oMPDF->setBasePath($this->a_base['href']); // Seems that the <BASE> tag is not recognized by mPDF...
					}
					$oMPDF->showWatermarkText = true;
					if ($this->GetOutputOption('pdf', 'template_path'))
					{
						$oMPDF->setImportUse(); // Allow templates
						$oMPDF->SetDocTemplate($this->GetOutputOption('pdf', 'template_path'), 1);
					}
					$oMPDF->WriteHTML($sHtml);
					$sOutputName = $this->s_title.'.pdf';
					if ($this->GetOutputOption('pdf', 'output_name'))
					{
						$sOutputName = $this->GetOutputOption('pdf', 'output_name');
					}
					$oMPDF->Output($sOutputName, 'I');
				}
			}
		}
		DBSearch::RecordQueryTrace();
		ExecutionKPI::ReportStats();
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function AddTabContainer($sTabContainer, $sPrefix = '')
	{
		$this->add($this->m_oTabs->AddTabContainer($sTabContainer, $sPrefix));
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function AddToTab($sTabContainer, $sTabCode, $sHtml)
	{
		$this->add($this->m_oTabs->AddToTab($sTabContainer, $sTabCode, $sHtml));
	}

	/**
	 * @inheritDoc
	 */
	public function SetCurrentTabContainer($sTabContainer = '')
	{
		return $this->m_oTabs->SetCurrentTabContainer($sTabContainer);
	}

	/**
	 * @inheritDoc
	 */
	public function SetCurrentTab($sTabCode = '', $sTabTitle = null)
	{
		return $this->m_oTabs->SetCurrentTab($sTabCode, $sTabTitle);
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 * @since 2.0.3
	 */
	public function AddAjaxTab($sTabCode, $sUrl, $bCache = true, $sTabTitle = null)
	{
		$this->add($this->m_oTabs->AddAjaxTab($sTabCode, $sUrl, $bCache, $sTabTitle));
	}

	/**
	 * @inheritDoc
	 */
	public function GetCurrentTab()
	{
		return $this->m_oTabs->GetCurrentTab();
	}

	/**
	 * @inheritDoc
	 */
	public function RemoveTab($sTabCode, $sTabContainer = null)
	{
		$this->m_oTabs->RemoveTab($sTabCode, $sTabContainer);
	}

	/**
	 * @inheritDoc
	 */
	public function FindTab($sPattern, $sTabContainer = null)
	{
		return $this->m_oTabs->FindTab($sPattern, $sTabContainer);
	}

	/**
	 * Make the given tab the active one, as if it were clicked
	 * DOES NOT WORK: apparently in the *old* version of jquery
	 * that we are using this is not supported... TO DO upgrade
	 * the whole jquery bundle...
	 *
	 * @param string $sTabContainer
	 * @param string $sTabCode
	 */
	public function SelectTab($sTabContainer, $sTabCode)
	{
		$this->add_ready_script($this->m_oTabs->SelectTab($sTabContainer, $sTabCode));
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function add($sHtml)
	{
		if (($this->m_oTabs->GetCurrentTabContainer() != '') && ($this->m_oTabs->GetCurrentTab() != ''))
		{
			$this->m_oTabs->AddToCurrentTab($sHtml);
		}
		else
		{
			parent::add($sHtml);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function start_capture()
	{
		$sCurrentTabContainer = $this->m_oTabs->GetCurrentTabContainer();
		$sCurrentTab = $this->m_oTabs->GetCurrentTab();

		if (!empty($sCurrentTabContainer) && !empty($sCurrentTab))
		{
			$iOffset = $this->m_oTabs->GetCurrentTabLength();

			return array('tc' => $sCurrentTabContainer, 'tab' => $sCurrentTab, 'offset' => $iOffset);
		}
		else
		{
			return parent::start_capture();
		}
	}

	/**
	 * @inheritDoc
	 */
	public function end_capture($offset)
	{
		if (is_array($offset))
		{
			if ($this->m_oTabs->TabExists($offset['tc'], $offset['tab']))
			{
				$sCaptured = $this->m_oTabs->TruncateTab($offset['tc'], $offset['tab'], $offset['offset']);
			}
			else
			{
				$sCaptured = '';
			}
		}
		else
		{
			$sCaptured = parent::end_capture($offset);
		}

		return $sCaptured;
	}

	/**
	 * Set the message to be displayed in the 'app-banner' section at the top of the page
	 *
	 * @param string $sHtmlMessage
	 */
	public function SetMessage($sHtmlMessage)
	{
		$sHtmlIcon = '<span class="fas fa-comment fa-1x"></span>';
		$this->AddApplicationMessage($sHtmlMessage, $sHtmlIcon);
	}

	/**
	 * Add message to be displayed in the 'app-banner' section at the top of the page
	 *
	 * @param string $sHtmlMessage
	 * @param string|null $sHtmlIcon
	 * @param string|null $sTip
	 */
	public function AddApplicationMessage($sHtmlMessage, $sHtmlIcon = null, $sTip = null)
	{
		if (strlen($sHtmlMessage))
		{
			$this->m_aMessages[] = array(
				'icon' => $sHtmlIcon,
				'message' => $sHtmlMessage,
				'tip' => $sTip,
			);
		}
	}


	/**
	 * Adds in the page a container with the header_message CSS class
	 *
	 * @param string $sContent
	 * @param string $sCssClasses CSS classes to add to the container
	 *
	 * @throws \Exception
	 * @since 2.6.0
	 */
	public function AddHeaderMessage($sContent, $sCssClasses = 'message_info')
	{
		$this->add(<<<EOF
<div class="header_message $sCssClasses">$sContent</div>
EOF
		);
	}

	/**
	 * Adds a script to be executed when the DOM is ready (typical JQuery use), right before add_ready_script
	 *
	 * @param string $sScript
	 *
	 * @return void
	 */
	public function add_init_script($sScript)
	{
		$this->m_aInitScript[] = $sScript;
	}
}
