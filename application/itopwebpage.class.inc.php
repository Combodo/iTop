<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Class iTopWebPage
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once(APPROOT."/application/nicewebpage.class.inc.php");
require_once(APPROOT."/application/applicationcontext.class.inc.php");
require_once(APPROOT."/application/user.preferences.class.inc.php");
/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 */
class iTopWebPage extends NiceWebPage
{
	private $m_sMenu;
//	private $m_currentOrganization;
	private $m_aTabs;
	private $m_sCurrentTabContainer;
	private $m_sCurrentTab;
	private $m_sMessage;
	private $m_sInitScript;
	
	public function __construct($sTitle)
	{
		parent::__construct($sTitle);

		ApplicationContext::SetUrlMakerClass('iTopStandardURLMaker');

		$this->m_sCurrentTabContainer = '';
		$this->m_sCurrentTab = '';
		$this->m_aTabs = array();
		$this->m_sMenu = "";
		$this->m_sMessage = '';
		$oAppContext = new ApplicationContext();
		$sExtraParams = $oAppContext->GetForLink();
//		$this->m_currentOrganization = $currentOrganization;
		$this->add_header("Content-type: text/html; charset=utf-8");
		$this->add_header("Cache-control: no-cache");
		$this->add_linked_stylesheet("../css/jquery.treeview.css");
		$this->add_linked_stylesheet("../css/jquery.autocomplete.css");
//		$this->add_linked_stylesheet("../css/date.picker.css");
		$this->add_linked_script('../js/jquery.layout.min.js');
		$this->add_linked_script('../js/jquery.ba-bbq.min.js');
//		$this->add_linked_script("../js/jquery.dimensions.js");
		$this->add_linked_script("../js/jquery.tablehover.js");
		$this->add_linked_script("../js/jquery.treeview.js");
		$this->add_linked_script("../js/jquery.autocomplete.js");
		$this->add_linked_script("../js/jquery.positionBy.js");
		$this->add_linked_script("../js/jquery.popupmenu.js");
		$this->add_linked_script("../js/date.js");
//		$this->add_linked_script("../js/jquery.date.picker.js");
		$this->add_linked_script("../js/jquery.tablesorter.min.js");
		$this->add_linked_script("../js/jquery.blockUI.js");
		$this->add_linked_script("../js/utils.js");
		$this->add_linked_script("../js/swfobject.js");
		$this->add_linked_script("../js/ckeditor/ckeditor.js");
		$this->add_linked_script("../js/ckeditor/adapters/jquery.js");
		$this->add_linked_script("../js/jquery.qtip-1.0.min.js");
		$this->add_linked_script("../js/jquery.tablesorter.pager.js");
		$this->m_sInitScript =
<<< EOF
	try
	{
		var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method
	
		// Layout
		paneSize = GetUserPreference('menu_size', 300)
		myLayout = $('body').layout({
			west : 	{
						minSize: 200, size: paneSize, spacing_open: 16, spacing_close: 16, slideTrigger_open: "mouseover", hideTogglerOnSlide: true,
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
			center:	{
					 	onresize_end: function(name, elt, state, options, layout)
					 	{
					 			$('.v-resizable').each( function() {
					 				var fixedWidth = $(this).parent().innerWidth() - 6;
					 				$(this).width(fixedWidth);
					 				// Make sure it cannot be resized horizontally
									$(this).resizable('options', { minWidth: fixedWidth, maxWidth:  fixedWidth });
									// Now adjust all the child 'items'
					 				var innerWidth = $(this).innerWidth() - 10;
					 				$(this).find('.item').width(innerWidth);
					 			});
					 	}
				
				 	}
		});
		myLayout.addPinBtn( "#tPinMenu", "west" );
		//myLayout.open( "west" );
		$('.ui-layout-resizer-west').html('<img src="../images/splitter-top-corner.png"/>');
		if (GetUserPreference('menu_pane', 'open') == 'closed')
		{
			myLayout.close('west');
		}
	
		// Accordion Menu
		$("#accordion").accordion({ header: "h3", navigation: true, autoHeight: false, collapsible: false, icons: false }); // collapsible will be enabled once the item will be selected
	
		// Tabs, using JQuery BBQ to store the history
		// The "tab widgets" to handle.
		var tabs = $('div[id^=tabbedContent]');
		    
		// This selector will be reused when selecting actual tab widget A elements.
		var tab_a_selector = 'ul.ui-tabs-nav a';
		  
		// Enable tabs on all tab widgets. The `event` property must be overridden so
		// that the tabs aren't changed on click, and any custom event name can be
		// specified. Note that if you define a callback for the 'select' event, it
		// will be executed for the selected tab whenever the hash changes.
		tabs.tabs({ event: 'change'});
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
	//add new widget called TruncatedList to properly display truncated lists when they are sorted
	$.tablesorter.addWidget({ 
	    // give the widget a id 
	    id: "truncatedList", 
	    // format is called when the on init and when a sorting has finished 
	    format: function(table)
	    { 
			// Check if there is a "truncated" line
			this.truncatedList = false;  
			if ($("tr td.truncated",table).length > 0)
			{
				this.truncatedList = true;
			}
			if (this.truncatedList)
			{
				$("tr td",table).removeClass('truncated');
				$("tr:last td",table).addClass('truncated');
			}
	    } 
	});
		
	
	$.tablesorter.addWidget({ 
	    // give the widget a id 
	    id: "myZebra", 
	    // format is called when the on init and when a sorting has finished 
	    format: function(table)
	    {
	    	// Replace the 'red even' lines by 'red_even' since most browser do not support 2 classes selector in CSS, etc..
			$("tbody tr:even",table).addClass('even');
			$("tbody tr.red:even",table).removeClass('red').removeClass('even').addClass('red_even');
			$("tbody tr.orange:even",table).removeClass('orange').removeClass('even').addClass('orange_even');
			$("tbody tr.green:even",table).removeClass('green').removeClass('even').addClass('green_even');
			// In case we sort again the table, we need to remove the added 'even' classes on odd rows
			$("tbody tr:odd",table).removeClass('even');
			$("tbody tr.red_even:odd",table).removeClass('even').removeClass('red_even').addClass('red');
			$("tbody tr.orange_even:odd",table).removeClass('even').removeClass('orange_even').addClass('orange');
			$("tbody tr.green_even:odd",table).removeClass('even').removeClass('green_even').addClass('green');
	    } 
	});

	$('.resizable').resizable(); // Make resizable everything that claims to be resizable !
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
	  
	// End of Tabs handling
	$("table.listResults").tableHover(); // hover tables
	$(".date-pick").datepicker({
			showOn: 'button',
			buttonImage: '../images/calendar.png',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			constrainInput: false,
			changeMonth: true,
			changeYear: true
		});
	// Restore the persisted sortable order, for all sortable lists... if any
	$('.sortable').each(function()
	{
		var sTemp = GetUserPreference(this.id+'_order', undefined);
		if (sTemp != undefined)
		{
			var aSerialized = sTemp.split(',');
			var sortable = $(this);
		    $.each(aSerialized, function(i,v) {
		      var item = $('#menu_'+v);
		      if (item.length >  0) // Check that the menu exists
		      {
		      		sortable.append(item);
		      }
		    });
		}
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
			if (id > 0)
			{
				window.location.href = './UI.php?operation=details&class='+sClass+'&id='+id;
			}
			else
			{
				window.location.href = sDefaultUrl;				
			}
		}

		
		function BackToList(sClass)
		{
			window.location.href = './UI.php?operation=search_oql&oql_class='+sClass+'&oql_clause=WHERE id=0';
		}

		function ShowDebug()
		{
			if ($('#rawOutput > div').html() != '')
			{
				$('#rawOutput').dialog( {autoOpen: true, modal:false});
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

EOF
);
		
    	// Build menus from module handlers
    	//
		foreach(get_declared_classes() as $sPHPClass)
		{
			if (is_subclass_of($sPHPClass, 'ModuleHandlerAPI'))
			{
				$aCallSpec = array($sPHPClass, 'OnMenuCreation');
				call_user_func($aCallSpec);
			}
		}
	}
	
	public function AddToMenu($sHtml)
	{
		$this->m_sMenu .= $sHtml;
	}

	public function GetSiloSelectionForm()
	{
		// List of visible Organizations
		$iCount = 0;
		if (MetaModel::IsValidClass('Organization'))
		{
			$oSearchFilter = new DBObjectSearch('Organization');
			$oSet = new CMDBObjectSet($oSearchFilter);
			$iCount = $oSet->Count();
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
			$sHtml .= '<form style="display:inline" action="'.$_SERVER['PHP_SELF'].'">'; //<select class="org_combo" name="c[org_id]" title="Pick an organization" onChange="this.form.submit();">';
/*
			$sSelected = ($iCurrentOrganization == '') ? ' selected' : '';
			$sHtml .= '<option value=""'.$sSelected.'>'.Dict::S('UI:AllOrganizations').'</option>';
			while($oOrg = $oSet->Fetch())
			{
				if ($iCurrentOrganization == $oOrg->GetKey())
				{
//					$oCurrentOrganization = $oOrg;
					$sSelected = " selected";
			
				}
				else
				{
					$sSelected = "";
				}
				$sHtml .= '<option title="'.$oOrg->GetName().'" value="'.$oOrg->GetKey().'"'.$sSelected.'>'.$oOrg->GetName().'</option>';
			}
			$sHtml .= '</select>';
*/
			$oAllowedValues = new DBObjectSet(DBObjectSearch::FromOQL('SELECT Organization'));
			$oWidget = new UIExtKeyWidget('Organization', 'org_id');
			$sHtml .= $oWidget->Display($this, 50, false, '', $oAllowedValues, $iCurrentOrganization, 'org_id', false, 'c[org_id]', '', array('iFieldSize' => 20, 'sDefaultValue' => Dict::S('UI:AllOrganizations')), $bSearchMode = true);
			$sHtml .= '<input type="image" src="../images/play.png" style="vertical-align:middle;"> ';
			// Add other dimensions/context information to this form
//			$oAppContext = new ApplicationContext();
			$oAppContext->Reset('org_id'); // org_id is handled above and we want to be able to change it here !
			$sHtml .= $oAppContext->GetForForm();		
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
		$sForm = $this->GetSiloSelectionForm();
		$this->DisplayMenu(); // Compute the menu

		// Put here the 'ready scripts' that must be executed after all others
    	$this->add_ready_script(
<<<EOF
	// Since the event is only triggered when the hash changes, we need to trigger
	// the event now, to handle the hash the page may have loaded with.
	$(window).trigger( 'hashchange' );

EOF
);
        foreach($this->a_headers as $s_header)
        {
            header($s_header);
        }
        $s_captured_output = ob_get_contents();
        ob_end_clean();
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
        echo "<html>\n";
        echo "<head>\n";
        // Make sure that Internet Explorer renders the page using its latest/highest/greatest standards !
        echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\n";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
        echo "<title>{$this->s_title}</title>\n";
        echo $this->get_base_tag();
        // Stylesheets MUST be loaded before any scripts otherwise
        // jQuery scripts may face some spurious problems (like failing on a 'reload')
        foreach($this->a_linked_stylesheets as $a_stylesheet)
        {
			if ($a_stylesheet['condition'] != "")
			{
				echo "<!--[if {$a_stylesheet['condition']}]>\n";
			}
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$a_stylesheet['link']}\" />\n";
			if ($a_stylesheet['condition'] != "")
			{
				echo "<![endif]-->\n";
			}
        }
        foreach($this->a_linked_scripts as $s_script)
        {
         	// Make sure that the URL to the script contains the application's version number
        	// so that the new script do NOT get reloaded from the cache when the application is upgraded
        	if (strpos('?', $s_script) === false)
        	{
        		$s_script .= "?version=".ITOP_VERSION;
        	}
        	else
        	{
        		$s_script .= "&version=".ITOP_VERSION;
        	}
            echo "<script type=\"text/javascript\" src=\"$s_script\"></script>\n";
        }
		$this->add_script("\$(document).ready(function() {\n{$this->m_sInitScript};\nwindow.setTimeout('onDelayedReady()',10)\n});");
        if (count($this->m_aReadyScripts)>0)
        {
			$this->add_script("\nonDelayedReady = function() {\n".implode("\n", $this->m_aReadyScripts)."\n}\n");
		}
        if (count($this->a_scripts)>0)
        {
            echo "<script type=\"text/javascript\">\n";
            foreach($this->a_scripts as $s_script)
            {
                echo "$s_script\n";
            }
            echo "</script>\n";
        }
        
        if (count($this->a_styles)>0)
        {
            echo "<style>\n";
            foreach($this->a_styles as $s_style)
            {
                echo "$s_style\n";
            }
            echo "</style>\n";
        }
		echo "<link rel=\"search\" type=\"application/opensearchdescription+xml\" title=\"iTop\" href=\"./opensearch.xml.php\" />\n";
        echo "</head>\n";
        echo "<body>\n";








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
		$sText = Utils::ReadParam('text', '');
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
		foreach($this->m_aTabs as $sTabContainerName => $m_aTabs)
		{
			$sTabs = '';
			$container_index = 0;
			if (count($m_aTabs) > 0)
			{
			  $sTabs = "<!-- tabs -->\n<div id=\"tabbedContent_{$container_index}\" class=\"light\">\n";
			  $sTabs .= "<ul>\n";
			  // Display the unordered list that will be rendered as the tabs
	          $i = 0;
			  foreach($m_aTabs as $sTabName => $sTabContent)
			  {
			      $sTabs .= "<li><a href=\"#tab_$i\" class=\"tab\"><span>".htmlentities($sTabName, ENT_QUOTES, 'UTF-8')."</span></a></li>\n";
			      $i++;
	          }
			  $sTabs .= "</ul>\n";
			  // Now add the content of the tabs themselves
			  $i = 0;
			  foreach($m_aTabs as $sTabName => $sTabContent)
			  {
			      $sTabs .= "<div id=\"tab_$i\">".$sTabContent."</div>\n";
			      $i++;
	          }
			  $sTabs .= "</div>\n<!-- end of tabs-->\n";
	        }
			$this->s_content = str_replace("\$Tabs:$sTabContainerName\$", $sTabs, $this->s_content);
			$container_index++;
		}
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
		
		if (utils::CanLogOff())
		{
			//$sLogOffMenu .= "<li><a href=\"../pages/UI.php?loginop=logoff\">".Dict::S('UI:LogOffMenu')."</a></li>\n";
			$sLogOffMenu .= "<li><a href=\"../pages/logoff.php\">".Dict::S('UI:LogOffMenu')."</a></li>\n";
		}
		if (UserRights::CanChangePassword())
		{
			$sLogOffMenu .= "<li><a href=\"../pages/UI.php?loginop=change_pwd\">".Dict::S('UI:ChangePwdMenu')."</a></li>\n";
		}
		$sLogOffMenu .= "</ul>\n</li>\n</ul></span>\n";

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
			$sAdminMessage = trim(MetaModel::GetConfig()->Get('access_message'));
			$sApplicationBanner = '<div id="admin-banner">';
			$sApplicationBanner .= '<img src="../images/locked.png" style="vertical-align:middle;">';
			$sApplicationBanner .= '&nbsp;<b>'.$sRestrictions.'</b>';
			if (strlen($sAdminMessage) > 0)
			{
				$sApplicationBanner .= '&nbsp;<b>'.$sAdminMessage.'</b>';
			}
			$sApplicationBanner .= '</div>';
		}
		else if(strlen($this->m_sMessage))
		{
			$sApplicationBanner = '<div id="admin-banner"><span style="padding:5px;">'.$this->m_sMessage.'<span></div>';
		}
		else
		{
			$sApplicationBanner = '';
		}

		$sOnlineHelpUrl = MetaModel::GetConfig()->Get('online_help');
		//$sLogOffMenu = "<span id=\"logOffBtn\" style=\"height:55px;padding:0;margin:0;\"><img src=\"../images/onOffBtn.png\"></span>";

		echo '<div id="left-pane" class="ui-layout-west">';
		echo '<!-- Beginning of the left pane -->';
		echo '	<div id="header-logo">';
		echo '	<div id="top-left"></div><div id="logo"><a href="http://www.combodo.com/itop"><img src="../images/itop-logo.png" title="'.$sVersionString.'" style="border:0; margin-top:16px; margin-right:40px;"/></a></div>';
		echo '	</div>';
		echo '	<div class="header-menu">';
		echo '		<div class="icon ui-state-default ui-corner-all"><span id="tPinMenu" class="ui-icon ui-icon-pin-w">pin</span></div>';
		echo '		<div style="text-align:center;">'.$sForm.'</div>';
		echo '	</div>';
		echo '	<div id="menu" class="ui-layout-content">';
		echo '		<div id="inner_menu">';
		echo '			<div id="accordion">';
		echo $this->m_sMenu;
		echo '			<!-- Beginning of the accordion menu -->';
		echo '			<!-- End of the accordion menu-->';
		echo '			</div>';
		echo '		</div> <!-- /inner menu -->';
		echo '	</div> <!-- /menu -->';
		echo '	<div class="footer"><a href="http://www.combodo.com" title="www.combodo.com" target="_blank"><img src="../images/logo-combodo.png"/></a></div>';
		echo '<!-- End of the left pane -->';
		echo '</div>';

		echo '<div class="ui-layout-center">';
		echo '	<div id="top-bar" style="width:100%">';
		echo $sApplicationBanner;
		echo '		<div id="global-search"><form action="../pages/UI.php"><table><tr><td></td><td id="g-search-input"><input type="text" name="text" value="'.$sText.'"'.$sOnClick.'/></td>';
		echo '<td><input type="image" src="../images/searchBtn.png"/></a></td>';
		echo '<td><a style="background:transparent;" href="'.$sOnlineHelpUrl.'" target="_blank"><img style="border:0;padding-left:20px;padding-right:10px;" title="'.Dict::S('UI:Help').'" src="../images/help.png"/></td>';
		echo '<td style="padding-right:20px;padding-left:10px;">'.$sLogOffMenu.'</td><td><input type="hidden" name="operation" value="full_text"/></td></tr></table></form></div>';
		//echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" name="operation" value="full_text"/></td></tr></table></form></div>';
		echo '	</div>';
		echo '	<div class="ui-layout-content">';
		echo '	<!-- Beginning of page content -->';
		echo $this->s_content;
		echo '	<!-- End of page content -->';
		echo '	</div>';
		echo '</div>';
/*		
		echo "<div class=\"iTopLogo\" title=\"$sVersionString\"><span>iTop</span></div>\n";
		//echo "<div id=\"GlobalSearch\"><div style=\"border: 1px solid #999; padding:1px; background-color:#fff;\"><img src=\"../images/magnifier.gif\"/><input style=\"border:0\" type=\"text\" size=\"15\" title=\"Global Search\"></input></div></div>\n";
		$sText = Utils::ReadParam('text', '');
		$sOnClick = "";
		if (empty($sText))
		{
			// if no search text is supplied then
			// 1) the search text is filled with "your search"
			// 2) clicking on it will erase it
			$sText = Dict::S("UI:YourSearch");
			$sOnClick = " onclick=\"this.value='';this.onclick=null;\"";
		}
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
		$sLogOffBtn = Dict::S('UI:Button:Logoff');
		$sSearchBtn = Dict::S('UI:Button:GlobalSearch');
		echo "<div id=\"Login\" style=\"position:absolute; top:18px; right:16px; width:600px;\">{$sLogonMessage}&nbsp;&nbsp;";
		echo "<form action=\"../pages/UI.php\" method=\"post\" style=\"display:inline\">\n";
		echo "<input type=\"submit\" value=\"$sLogOffBtn\" />\n";
		echo "<input type=\"hidden\" name=\"loginop\" value=\"logoff\" />\n";
		echo "</form>\n";
		echo "<form action=\"../pages/UI.php\" style=\"display:inline\"><div style=\"padding:1px; background-color:#fff;display:inline;\"><img src=\"../images/magnifier.gif\"/><input style=\"border:0\" type=\"text\" size=\"15\" title=\"Global Search\" name=\"text\" value=\"$sText\"$sOnClick></input></div><input type=\"submit\" value=\"$sSearchBtn\" />
			  <input type=\"hidden\" name=\"operation\" value=\"full_text\" /></form>\n";
		echo "</div>\n";

		echo "</div>\n";

		// Display the menu
		echo "<div id=\"MySplitter\">\n";
		echo "  <div id=\"LeftPane\">\n";
		echo $this->m_sMenu;
		echo "  </div> <!-- LeftPane -->\n";
		
		echo "<div id=\"RightPane\">\n";
    
        
		// Display the page's content
        echo $this->s_content;

*/
        // Add the captured output
        if (trim($s_captured_output) != "")
        {
            echo "<div id=\"rawOutput\" title=\"Debug Output\"><div style=\"height:500px; overflow-y:auto;\">$s_captured_output</div></div>\n";
        }
		echo "<div id=\"at_the_end\">".$this->s_deferred_content."</div>";
//		echo $this->s_deferred_content;
		echo "<div style=\"display:none\" title=\"ex2\" id=\"ex2\">Please wait...</div>\n"; // jqModal Window
		echo "<div style=\"display:none\" title=\"dialog\" id=\"ModalDlg\"></div>";
		echo "<div style=\"display:none\" id=\"ajax_content\"></div>";

		echo "</body>\n";
        echo "</html>\n";
    }
	
	public function AddTabContainer($sTabContainer)
	{
		$this->m_aTabs[$sTabContainer] = array();
		$this->add("\$Tabs:$sTabContainer\$");
	}
	
	public function AddToTab($sTabContainer, $sTabLabel, $sHtml)
	{
		if (!isset($this->m_aTabs[$sTabContainer][$sTabLabel]))
		{
			// Set the content of the tab
			$this->m_aTabs[$sTabContainer][$sTabLabel] = $sHtml;
		}
		else
		{
			// Append to the content of the tab
			$this->m_aTabs[$sTabContainer][$sTabLabel] .= $sHtml;
		}
	}

	public function SetCurrentTabContainer($sTabContainer = '')
	{
		$sPreviousTabContainer = $this->m_sCurrentTabContainer;
		$this->m_sCurrentTabContainer = $sTabContainer;
		return $sPreviousTabContainer;
	}

	public function SetCurrentTab($sTabLabel = '')
	{
		$sPreviousTab = $this->m_sCurrentTab;
		$this->m_sCurrentTab = $sTabLabel;
		return $sPreviousTab;
	}
	
	/**
	 * Make the given tab the active one, as if it were clicked
	 * DOES NOT WORK: apparently in the *old* version of jquery
	 * that we are using this is not supported... TO DO upgrade
	 * the whole jquery bundle...
	 */
	public function SelectTab($sTabContainer, $sTabLabel)
	{
		$container_index = 0;
		$tab_index = 0;
		foreach($this->m_aTabs as $sCurrentTabContainerName => $aTabs)
		{
			if ($sTabContainer == $sCurrentTabContainerName)
			{
				foreach($aTabs as $sCurrentTabLabel => $void)
				{
					if ($sCurrentTabLabel == $sTabLabel)
					{
						break;
					}
					$tab_index++;
				}	
				break;
			}
			$container_index++;
		}
		$sSelector = '#tabbedContent_'.$container_index.' > ul';
		$this->add_ready_script("$('$sSelector').tabs('select', $tab_index);");
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
        if (!empty($this->m_sCurrentTabContainer) && !empty($this->m_sCurrentTab))
        {
            $this->AddToTab($this->m_sCurrentTabContainer, $this->m_sCurrentTab, $sHtml);
        }
        else
        {
            parent::add($sHtml);
        }
    }
    
    /**
     * Set the message to be displayed in the 'admin-banner' section at the top of the page
     */
    public function SetMessage($sMessage)
    {
    	$this->m_sMessage = $sMessage;	
    }
    
    /*
    public function AddSearchForm($sClassName, $bOpen = false)
    {
    	$iSearchSectionId = 0;
    	
		$sStyle = $bOpen ? 'SearchDrawer' : 'SearchDrawer DrawerClosed';
		$this->add("<div id=\"Search_$iSearchSectionId\" class=\"$sStyle\">\n");
		$this->add("<h1>Search form for ".Metamodel::GetName($sClassName)."</h1>\n");
		$this->add_ready_script("\$(\"#LnkSearch_$iSearchSectionId\").click(function() {\$(\"#Search_$iSearchSectionId\").slideToggle('normal'); $(\"#LnkSearch_$iSearchSectionId\").toggleClass('open');});");
		$oFilter = new DBObjectSearch($sClassName);
		$sFilter = $oFilter->serialize();
		$oSet = new CMDBObjectSet($oFilter);
		cmdbAbstractObject::DisplaySearchForm($this, $oSet, array('operation' => 'search', 'filter' => $sFilter, 'search_form' => true));
 		$this->add("</div>\n");
 		$this->add("<div class=\"HRDrawer\"/></div>\n");
 		$this->add("<div id=\"LnkSearch_$iSearchSectionId\" class=\"DrawerHandle\">Search</div>\n");

    	
    	$iSearchSectionId++;
	}
	*/
}

?>
