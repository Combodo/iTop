<?php
// Copyright (C) 2010-2014 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Class iTopWebPage
 *
 * @copyright   Copyright (C) 2010-2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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
	private $m_sMessage;
	private $m_sInitScript;
	protected $m_oTabs;

	public function __construct($sTitle)
	{
		parent::__construct($sTitle);
		$this->m_oTabs = new TabManager();

		ApplicationContext::SetUrlMakerClass('iTopStandardURLMaker');

		$this->m_sMenu = "";
		$this->m_sMessage = '';
		$this->SetRootUrl(utils::GetAbsoluteUrlAppRoot());
		$this->add_header("Content-type: text/html; charset=utf-8");
		$this->add_header("Cache-control: no-cache");
		$this->add_linked_stylesheet("../css/jquery.treeview.css");
		$this->add_linked_stylesheet("../css/jquery.autocomplete.css");
		$this->add_linked_stylesheet("../css/fg.menu.css");
		$this->add_linked_stylesheet("../css/jquery.multiselect.css");
		$this->add_linked_script('../js/jquery.layout.min.js');
		$this->add_linked_script('../js/jquery.ba-bbq.min.js');
		$this->add_linked_script("../js/jquery.treeview.js");
		$this->add_linked_script("../js/jquery.autocomplete.js");
		$this->add_linked_script("../js/date.js");
		$this->add_linked_script("../js/jquery.blockUI.js");
		$this->add_linked_script("../js/utils.js");
		$this->add_linked_script("../js/swfobject.js");
		$this->add_linked_script("../js/ckeditor/ckeditor.js");
		$this->add_linked_script("../js/ckeditor/adapters/jquery.js");
		$this->add_linked_script("../js/jquery.qtip-1.0.min.js");
		$this->add_linked_script('../js/property_field.js');
		$this->add_linked_script('../js/fg.menu.js');
		$this->add_linked_script('../js/icon_select.js');
		$this->add_linked_script('../js/raphael-min.js');
		$this->add_linked_script('../js/g.raphael.js');
		$this->add_linked_script('../js/g.pie.js');
		$this->add_linked_script('../js/g.dot.js');
		$this->add_linked_script('../js/charts.js');
		$this->add_linked_script('../js/jquery.multiselect.min.js');
		$this->add_linked_script('../js/ajaxfileupload.js');
		
		$aMultiselectOptions = array(
			'header' => true,
			'checkAllText' => Dict::S('UI:SearchValue:CheckAll'),
			'uncheckAllText' => Dict::S('UI:SearchValue:UncheckAll'),
			'noneSelectedText' => Dict::S('UI:SearchValue:Any'),
			'selectedText' => Dict::S('UI:SearchValue:NbSelected'),
			'selectedList' => 1,
		);
		$sJSMultiselectOptions = json_encode($aMultiselectOptions);
		$sSearchAny = addslashes(Dict::S('UI:SearchValue:Any'));
		$sSearchNbSelected = addslashes(Dict::S('UI:SearchValue:NbSelected'));
		$this->add_dict_entry('UI:FillAllMandatoryFields');
		
		$bForceMenuPane = utils::ReadParam('force_menu_pane', null);
		$sInitClosed = '';
		if (($bForceMenuPane !== null) && ($bForceMenuPane == 0))
		{
			$sInitClosed = 'initClosed: true,';
		}

		$this->add_script(
<<<EOF
function ShowAboutBox()
{
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'about_box'}, function(data){
		$('body').append(data);
	});
	return false;
}
EOF
		);

		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			// Leave the pane opened
			$sConfigureWestPane = '';
		}
		else
		{
			$sConfigureWestPane =
<<<EOF
				if (GetUserPreference('menu_pane', 'open') == 'closed')
				{
					myLayout.close('west');
				}
				myLayout.addPinBtn( "#tPinMenu", "west" );
EOF;
		}
		
		$this->m_sInitScript =
<<< EOF
	try
	{
		var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method
	
		// Layout
		paneSize = GetUserPreference('menu_size', 300)
		myLayout = $('body').layout({
			west :	{
						$sInitClosed minSize: 200, size: paneSize, spacing_open: 16, spacing_close: 16, slideTrigger_open: "mouseover", hideTogglerOnSlide: true, enableCursorHotkey: false,
						onclose_end: function(name, elt, state, options, layout)
						{
								if (state.isSliding == false)
								{
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
						}
				
					}
		});
		window.clearTimeout(iPaneVisWatchDog);
		//myLayout.open( "west" );
		$('.ui-layout-resizer-west .ui-layout-toggler').css({background: 'transparent'});
		$sConfigureWestPane
		
		$('#left-pane').layout({ resizable: false, spacing_open: 0, south: { size: 94 }, enableCursorHotkey: false });
	
		// Accordion Menu
		$("#accordion").accordion({ header: "h3", navigation: true, heightStyle: "content", collapsible: false, icons: false }); // collapsible will be enabled once the item will be selected
	
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
				ui.jqXHR.success(function() {
					ui.tab.data( "loaded", true );
				});
			}
		});
		
		$('.multiselect').multiselect($sJSMultiselectOptions);
		
		$('.resizable').filter(':visible').resizable();
	}
	catch(err)
	{
		// Do something with the error !
		alert(err);
	}
EOF
		;

		$this->add_ready_script(
<<< EOF
	
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

	$(".date-pick").datepicker({
			showOn: 'button',
			buttonImage: '../images/calendar.png',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			constrainInput: false,
			changeMonth: true,
			changeYear: true
		});
	$(".datetime-pick").datepicker({
			showOn: 'button',
			buttonImage: '../images/calendar.png',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd 00:00:00',
			constrainInput: false,
			changeMonth: true,
			changeYear: true
		});

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
	$('#ModalDlg').dialog({ autoOpen: false, modal: true, width: 0.8*docWidth }); // JQuery UI dialogs
	ShowDebug();
	$('#logOffBtn>ul').popupmenu();
	
	$('.caselog_header').click( function () { $(this).toggleClass('open').next('.caselog_entry').toggle(); });
EOF
		);
		$sUserPrefs = appUserPreferences::GetAsJSON();
		$this->add_script(
<<<EOF
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
		
		function BackToDetails(sClass, id, sDefaultUrl)
		{
			window.bInCancel = true;
			if (id > 0)
			{
				window.location.href = AddAppContext(GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=details&class='+sClass+'&id='+id);
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
EOF
		);
	}

	public function AddToMenu($sHtml)
	{
		$this->m_sMenu .= $sHtml;
	}

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
		switch($iCount)
		{
			case 0:
			// No such dimension/silo => nothing to select
			$sHtml = '<div id="SiloSelection"><!-- nothing to select --></div>';
			break;
					
			case 1:
			// Only one possible choice... no selection, but display the value
			$oOrg = $oSet->Fetch();
			$sHtml = '<div id="SiloSelection">'.$oOrg->GetName().'</div>';
			$sHtml .= '';
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
										array('iFieldSize' => 20, 'iMinChars' => MetaModel::GetConfig()->Get('min_autocomplete_chars'), 'sDefaultValue' => Dict::S('UI:AllOrganizations')),
										null, 'select', false /* bSearchMultiple */);
			$this->add_ready_script('$("#org_id").bind("extkeychange", function() { $("#SiloSelection form").submit(); } )');
			$this->add_ready_script("$('#label_org_id').click( function() { $(this).val(''); $('#org_id').val(''); return true; } );\n");
			// Add other dimensions/context information to this form
			$oAppContext->Reset('org_id'); // org_id is handled above and we want to be able to change it here !
			$oAppContext->Reset('menu'); // don't pass the menu, since a menu may expect more parameters
			$sHtml .= $oAppContext->GetForForm(); // Pass what remains, if anything...
			$sHtml .= '</form>';
			$sHtml .= '</div>';
		}
		return $sHtml;
	}

	public function DisplayMenu()
	{
		// Display the menu
		$oAppContext = new ApplicationContext();
		$iAccordionIndex = 0;

		ApplicationMenu::DisplayMenu($this, $oAppContext->GetAsHash());
	}

	/**
	 * Outputs (via some echo) the complete HTML page by assembling all its elements
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
			$sNorthPane .= '<div id="admin-banner"><span style="padding:5px;">'.ExecutionKPI::GetDescription().'<span></div>';
		}
		
		//$sSouthPane = '<p>Peak memory Usage: '.sprintf('%.3f MB', memory_get_peak_usage(true) / (1024*1024)).'</p>';
		$sSouthPane = '';
		foreach (MetaModel::EnumPlugins('iPageUIExtension') as $oExtensionInstance)
		{
			$sSouthPane .= $oExtensionInstance->GetSouthPaneHtml($this);
		}
		
		// Put here the 'ready scripts' that must be executed after all others
		$this->add_ready_script(
<<<EOF
		// Since the event is only triggered when the hash changes, we need to trigger
		// the event now, to handle the hash the page may have loaded with.
		$(window).trigger( 'hashchange' );
		
		// Some table are sort-able, some are not, let's fix this
		$('table.listResults').each( function() { FixTableSorter($(this)); } );
EOF
		);
		if ($this->GetOutputFormat() == 'html')
		{
			foreach($this->a_headers as $s_header)
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
		$sHtml .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
		$sHtml .= "<title>".htmlentities($this->s_title, ENT_QUOTES, 'UTF-8')."</title>\n";
		$sHtml .= $this->get_base_tag();
		// Stylesheets MUST be loaded before any scripts otherwise
		// jQuery scripts may face some spurious problems (like failing on a 'reload')
		foreach($this->a_linked_stylesheets as $a_stylesheet)
		{
			if ($a_stylesheet['condition'] != "")
			{
				$sHtml .= "<!--[if {$a_stylesheet['condition']}]>\n";
			}
			$sHtml .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$a_stylesheet['link']}\" />\n";
			if ($a_stylesheet['condition'] != "")
			{
				$sHtml .= "<![endif]-->\n";
			}
		}
		// special stylesheet for printing, hides the navigation gadgets
		$sHtml .= "<link rel=\"stylesheet\" media=\"print\" type=\"text/css\" href=\"../css/print.css\" />\n";

		if ($this->GetOutputFormat() == 'html')
		{
			$sHtml .= $this->output_dict_entries(true); // before any script so that they can benefit from the translations
			foreach($this->a_linked_scripts as $s_script)
			{
					// Make sure that the URL to the script contains the application's version number
					// so that the new script do NOT get reloaded from the cache when the application is upgraded
					if (strpos($s_script, '?') === false)
					{
						$s_script .= "?itopversion=".ITOP_VERSION;
					}
					else
					{
						$s_script .= "&itopversion=".ITOP_VERSION;
					}
					$sHtml .= "<script type=\"text/javascript\" src=\"$s_script\"></script>\n";
			}
			$this->add_script("var iPaneVisWatchDog  = window.setTimeout('FixPaneVis()',5000);\n\$(document).ready(function() {\n{$this->m_sInitScript};\nwindow.setTimeout('onDelayedReady()',10)\n});");
			if (count($this->m_aReadyScripts)>0)
			{
				$this->add_script("\nonDelayedReady = function() {\n".implode("\n", $this->m_aReadyScripts)."\n}\n");
			}
			if (count($this->a_scripts)>0)
			{
				$sHtml .= "<script type=\"text/javascript\">\n";
				foreach($this->a_scripts as $s_script)
				{
					$sHtml .= "$s_script\n";
				}
				$sHtml .= "</script>\n";
			}
		}
	
		if (count($this->a_styles)>0)
		{
			$sHtml .= "<style>\n";
			foreach($this->a_styles as $s_style)
			{
				$sHtml .= "$s_style\n";
			}
			$sHtml .= "</style>\n";
		}
		$sHtml .= "<link rel=\"search\" type=\"application/opensearchdescription+xml\" title=\"iTop\" href=\"".utils::GetAbsoluteUrlAppRoot()."pages/opensearch.xml.php\" />\n";
		$sHtml .= "<link rel=\"shortcut icon\" href=\"".utils::GetAbsoluteUrlAppRoot()."images/favicon.ico\" />\n";
	
		$sHtml .= "</head>\n";
		$sHtml .= "<body>\n";

		// Render the revision number
		if (ITOP_REVISION == '$WCREV$')
		{
			// This is NOT a version built using the buil system, just display the main version
			$sVersionString = Dict::Format('UI:iTopVersion:Short', ITOP_VERSION);
		}
		else
		{
			// This is a build made from SVN, let display the full information
			$sVersionString = Dict::Format('UI:iTopVersion:Long', ITOP_VERSION, ITOP_REVISION, ITOP_BUILD_DATE);
		}

		// Render the text of the global search form
		$sText = htmlentities(utils::ReadParam('text', '', false, 'raw_data'), ENT_QUOTES, 'UTF-8');
		$sOnClick = "";
		if (empty($sText))
		{
			// if no search text is supplied then
			// 1) the search text is filled with "your search"
			// 2) clicking on it will erase it
			$sText = Dict::S("UI:YourSearch");
			$sOnClick = " onclick=\"this.value='';this.onclick=null;\"";
		}
		// Render the tabs in the page (if any)
		$this->s_content = $this->m_oTabs->RenderIntoContent($this->s_content);

		if ($this->GetOutputFormat() == 'html')
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
			$sLogOffMenu = "<span id=\"logOffBtn\"><ul><li><img src=\"../images/onOffBtn.png\"><ul>";
			$sLogOffMenu .= "<li><span>$sLogonMessage</span></li>\n";
			$aActions = array();

			$oPrefs = new URLPopupMenuItem('UI:Preferences', Dict::S('UI:Preferences'), utils::GetAbsoluteUrlAppRoot()."pages/preferences.php?".$oAppContext->GetForLink());
			$aActions[$oPrefs->GetUID()] = $oPrefs->GetMenuItem();
				
			if (utils::CanLogOff())
			{
				$oLogOff = new URLPopupMenuItem('UI:LogOffMenu', Dict::S('UI:LogOffMenu'), utils::GetAbsoluteUrlAppRoot().'pages/logoff.php');
				$aActions[$oLogOff->GetUID()] = $oLogOff->GetMenuItem();
			}
			if (UserRights::CanChangePassword())
			{
				$oChangePwd = new URLPopupMenuItem('UI:ChangePwdMenu', Dict::S('UI:ChangePwdMenu'), utils::GetAbsoluteUrlAppRoot().'pages/UI.php?loginop=change_pwd');
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

			$sApplicationBanner = '';
			if (strlen($sRestrictions) > 0)
			{
				$sAdminMessage = trim(MetaModel::GetConfig()->Get('access_message'));
				$sApplicationBanner .= '<div id="admin-banner">';
				$sApplicationBanner .= '<img src="../images/locked.png" style="vertical-align:middle;">';
				$sApplicationBanner .= '&nbsp;<b>'.$sRestrictions.'</b>';
				if (strlen($sAdminMessage) > 0)
				{
					$sApplicationBanner .= '&nbsp;<b>'.$sAdminMessage.'</b>';
				}
				$sApplicationBanner .= '</div>';
			}

			if(strlen($this->m_sMessage))
			{
				$sApplicationBanner .= '<div id="admin-banner"><span style="padding:5px;">'.$this->m_sMessage.'<span></div>';
			}

			$sApplicationBanner .= $sBannerExtraHtml;
			
			if (!empty($sNorthPane))
			{
				$sNorthPane = '<div id="bottom-pane" class="ui-layout-north">'.$sNorthPane.'</div>';
			}
			
			if (!empty($sSouthPane))
			{
				$sSouthPane = '<div id="bottom-pane" class="ui-layout-south">'.$sSouthPane.'</div>';
			}
			
			$sIconUrl = Utils::GetConfig()->Get('app_icon_url');
			$sOnlineHelpUrl = MetaModel::GetConfig()->Get('online_help');
			//$sLogOffMenu = "<span id=\"logOffBtn\" style=\"height:55px;padding:0;margin:0;\"><img src=\"../images/onOffBtn.png\"></span>";

			$sDisplayIcon = utils::GetAbsoluteUrlAppRoot().'images/itop-logo.png';
			if (file_exists(MODULESROOT.'branding/main-logo.png'))
			{
				$sDisplayIcon = utils::GetAbsoluteUrlModulesRoot().'branding/main-logo.png';
			}

			$sHtml .= $sNorthPane;
			$sHtml .= '<div id="left-pane" class="ui-layout-west">';
			$sHtml .= '<!-- Beginning of the left pane -->';
			$sHtml .= ' <div class="ui-layout-north">';
			$sHtml .= ' <div id="header-logo">';
			$sHtml .= ' <div id="top-left"></div><div id="logo"><a href="'.htmlentities($sIconUrl, ENT_QUOTES, 'UTF-8').'"><img src="'.$sDisplayIcon.'" title="'.htmlentities($sVersionString, ENT_QUOTES, 'UTF-8').'" style="border:0; margin-top:16px; margin-right:40px;"/></a></div>';
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
			$sHtml .= ' <div class="footer ui-layout-south"><div id="combodo_logo"><a href="http://www.combodo.com" title="www.combodo.com" target="_blank"><img src="../images/logo-combodo.png"/></a></div></div>';
			$sHtml .= '<!-- End of the left pane -->';
			$sHtml .= '</div>';

			$sHtml .= '<div class="ui-layout-center">';
			$sHtml .= ' <div id="top-bar" style="width:100%">';
			$sHtml .= self::FilterXSS($sApplicationBanner);
			$sHtml .= '		<div id="global-search"><form action="'.utils::GetAbsoluteUrlAppRoot().'pages/UI.php"><table><tr><td></td><td id="g-search-input"><input type="text" name="text" value="'.$sText.'"'.$sOnClick.'/></td>';
			$sHtml .= '<td><input type="image" src="../images/searchBtn.png"/></a></td>';
			$sHtml .= '<td><a style="background:transparent;" href="'.$sOnlineHelpUrl.'" target="_blank"><img style="border:0;padding-left:20px;padding-right:10px;" title="'.Dict::S('UI:Help').'" src="../images/help.png"/></td>';
			$sHtml .= '<td style="padding-right:20px;padding-left:10px;">'.self::FilterXSS($sLogOffMenu).'</td><td><input type="hidden" name="operation" value="full_text"/></td></tr></table></form></div>';
			//echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" name="operation" value="full_text"/></td></tr></table></form></div>';
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

		$sHtml .= "</body>\n";
		$sHtml .= "</html>\n";

		if ($this->GetOutputFormat() == 'html')
		{
				$oKPI = new ExecutionKPI();
				echo $sHtml;
				$oKPI->ComputeAndReport('Echoing ('.round(strlen($sHtml) / 1024).' Kb)');
		}
		else if ($this->GetOutputFormat() == 'pdf' && $this->IsOutputFormatAvailable('pdf') )
		{
			require_once(APPROOT.'lib/MPDF/mpdf.php');
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
					$oMPDF->SetDocTemplate ($this->GetOutputOption('pdf', 'template_path'), 1);
			}
			$oMPDF->WriteHTML($sHtml);
			$sOutputName = $this->s_title.'.pdf';
			if ($this->GetOutputOption('pdf', 'output_name'))
			{
				$sOutputName = $this->GetOutputOption('pdf', 'output_name');
			}
			$oMPDF->Output($sOutputName, 'I');
		}
		MetaModel::RecordQueryTrace();
		ExecutionKPI::ReportStats();
	}

	public function AddTabContainer($sTabContainer, $sPrefix = '')
	{
		$this->add($this->m_oTabs->AddTabContainer($sTabContainer, $sPrefix));
	}

	public function AddToTab($sTabContainer, $sTabLabel, $sHtml)
	{
		$this->add($this->m_oTabs->AddToTab($sTabContainer, $sTabLabel, $sHtml));
	}

	public function SetCurrentTabContainer($sTabContainer = '')
	{
		return $this->m_oTabs->SetCurrentTabContainer($sTabContainer);
	}

	public function SetCurrentTab($sTabLabel = '')
	{
		return $this->m_oTabs->SetCurrentTab($sTabLabel);
	}
	
	/**
	 * Add a tab which content will be loaded asynchronously via the supplied URL
	 * 
	 * Limitations:
	 * Cross site scripting is not not allowed for security reasons. Use a normal tab with an IFRAME if you want to pull content from another server.
	 * Static content cannot be added inside such tabs.
	 * 
	 * @param string $sTabLabel The (localised) label of the tab
	 * @param string $sUrl The URL to load (on the same server)
	 * @param boolean $bCache Whether or not to cache the content of the tab once it has been loaded. flase will cause the tab to be reloaded upon each activation.
	 * @since 2.0.3
	 */
	public function AddAjaxTab($sTabLabel, $sUrl, $bCache = true)
	{
		$this->add($this->m_oTabs->AddAjaxTab($sTabLabel, $sUrl, $bCache));
	}
	
	public function GetCurrentTab()
	{
		return $this->m_oTabs->GetCurrentTab();
	}

	public function RemoveTab($sTabLabel, $sTabContainer = null)
	{
		$this->m_oTabs->RemoveTab($sTabLabel, $sTabContainer);
	}

	/**
	 * Finds the tab whose title matches a given pattern
	 * @return mixed The name of the tab as a string or false if not found
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
	 */
	public function SelectTab($sTabContainer, $sTabLabel)
	{
		$this->add_ready_script($this->m_oTabs->SelectTab($sTabContainer, $sTabLabel));
	}

	public function StartCollapsibleSection($sSectionLabel, $bOpen = false)
	{
		$this->add($this->GetStartCollapsibleSection($sSectionLabel, $bOpen));
	}

	public function GetStartCollapsibleSection($sSectionLabel, $bOpen = false)
	{
		$sHtml = '';
		static $iSectionId = 0;
		$sImgStyle = $bOpen ? ' open' : '';
		$sHtml .= "<a id=\"LnkCollapse_$iSectionId\" class=\"CollapsibleLabel{$sImgStyle}\" href=\"#\">$sSectionLabel</a></br>\n";
		$sStyle = $bOpen ? '' : 'style="display:none" ';
		$sHtml .= "<div id=\"Collapse_$iSectionId\" $sStyle>";
		$this->add_ready_script("\$(\"#LnkCollapse_$iSectionId\").click(function() {\$(\"#Collapse_$iSectionId\").slideToggle('normal'); $(\"#LnkCollapse_$iSectionId\").toggleClass('open');});");
		//$this->add_ready_script("$('#LnkCollapse_$iSectionId').hide();");
		$iSectionId++;
		return $sHtml;
	}

	public function EndCollapsibleSection()
	{
		$this->add($this->GetEndCollapsibleSection());
	}

	public function GetEndCollapsibleSection()
	{
		return "</div>";
	}

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
	 * Records the current state of the 'html' part of the page output
	 * @return mixed The current state of the 'html' output
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
	 * Returns the part of the html output that occurred since the call to start_capture
	 * and removes this part from the current html output
	 * @param $offset mixed The value returned by start_capture
	 * @return string The part of the html output that was added since the call to start_capture
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
	 * Set the message to be displayed in the 'admin-banner' section at the top of the page
	 */
	public function SetMessage($sMessage)
	{
			$this->m_sMessage = $sMessage;
	}
}
