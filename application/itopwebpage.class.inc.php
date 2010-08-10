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

require_once("../application/nicewebpage.class.inc.php");
require_once("../application/usercontext.class.inc.php");
require_once("../application/applicationcontext.class.inc.php");
require_once("../application/user.preferences.class.inc.php");
/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 */
class iTopWebPage extends NiceWebPage
{
	private $m_sMenu;
	private $m_currentOrganization;
	private $m_aTabs;
	private $m_sCurrentTabContainer;
	private $m_sCurrentTab;
	
    public function __construct($sTitle, $currentOrganization)
    {
        parent::__construct($sTitle);
        $this->m_sCurrentTabContainer = '';
        $this->m_sCurrentTab = '';
		$this->m_aTabs = array();
		$this->m_sMenu = "";
		$oAppContext = new ApplicationContext();
		$sExtraParams = $oAppContext->GetForLink();
		$this->m_currentOrganization = $currentOrganization;
		$this->add_header("Content-type: text/html; charset=utf-8");
		$this->add_header("Cache-control: no-cache");
		$this->add_linked_stylesheet("../css/jquery.treeview.css");
		$this->add_linked_stylesheet("../css/jquery.autocomplete.css");
//		$this->add_linked_stylesheet("../css/date.picker.css");
		$this->add_linked_script('../js/jquery.layout.min.js');
		$this->add_linked_script('../js/jquery.history.js');
//		$this->add_linked_script("../js/jquery.dimensions.js");
		$this->add_linked_script("../js/jquery.tablehover.js");
		$this->add_linked_script("../js/jquery.treeview.js");
		$this->add_linked_script("../js/jquery.autocomplete.js");
		$this->add_linked_script("../js/jquery.bgiframe.js");
		$this->add_linked_script("../js/jquery.positionBy.js");
		$this->add_linked_script("../js/jquery.popupmenu.js");
		$this->add_linked_script("../js/date.js");
//		$this->add_linked_script("../js/jquery.date.picker.js");
		$this->add_linked_script("../js/jquery.tablesorter.min.js");
		$this->add_linked_script("../js/jquery.blockUI.js");
		$this->add_linked_script("../js/utils.js");
		$this->add_linked_script("../js/swfobject.js");
		$this->add_ready_script(
<<<EOF
	try
	{
	var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method

	$(document).ready(function () {
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
		$("#accordion").accordion({ header: "h3", navigation: true, autoHeight: false, collapsible: false, icons: false });
 	});
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
	    } 
		});
		
	// tabs
	$("div[id^=tabbedContent]").tabs( { show: function(event, ui) {
			window.location.href = ui.tab.href; // So that history can keep track of the tabs
	} });
	$("table.listResults").tableHover(); // hover tables
	$(".listResults").tablesorter( { headers: { 0:{sorter: false }}, widgets: ['myZebra', 'truncatedList']} ); // sortable and zebra tables
	$(".date-pick").datepicker({
			showOn: 'button',
			buttonImage: '../images/calendar.png',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			constrainInput: false,
			changeMonth: true,
			changeYear: true
		});
	$('.resizable').resizable(); // Make resizable everything that claims to be resizable !
	docWidth = $(document).width();
	$('#ModalDlg').dialog({ autoOpen: false, modal: true, width: 0.8*docWidth }); // JQuery UI dialogs
	ShowDebug();
	$('#logOffBtn>ul').popupmenu();
	$.history.init(history_callback);
	$("a[rel='history']").click(function()
	{
		$.history.load(this.href.replace(/^.*#/, ''));
		return false;
	});
	}
	catch(err)
	{
		// Do something with the error !
		alert(err);
	}
	
	//$('.display_block').draggable(); // make the blocks draggable
EOF
);
		$sUserPrefs = appUserPreferences::GetAsJSON();
		$this->add_script(
<<<EOF
		// for JQuery history
		function history_callback(hash)
		{
			// do stuff that loads page content based on hash variable
			var aMatches = /^tab_(.*)$/.exec(hash);
			if (aMatches != null)
			{
				var tab = $('#'+hash);
				tab.parents('div[id^=tabbedContent]:first').tabs('select', aMatches[1]);
			}
		}

		// For automplete
		function findValue(li) {
			if( li == null ) return alert("No match!");
			
			// if coming from an AJAX call, let's use the CityId as the value
			if( !!li.extra ) var sValue = li.extra[0];
			
			// otherwise, let's just display the value in the text box
			else var sValue = li.selectValue;
			
			//alert(\"The value you selected was: \" + sValue);
		}
		
		function selectItem(li) {
			findValue(li);
		}
		
		
		function formatItem(row) {
			return row[0];
		}
		
		function goBack()
		{
			window.history.back();
		}
		
		function BackToDetails(sClass, id)
		{
			window.location.href = './UI.php?operation=details&class='+sClass+'&id='+id;
		}

		function ShowDebug()
		{
			if ($('#rawOutput > div').html() != '')
			{
				$('#rawOutput').dialog( {autoOpen: true, modal:false});
			}
		}
		
		var oUserPreferences = $sUserPrefs;
EOF
);
		
		// Add the standard menus
		/*
		 * +--------------------+
		 * | Welcome            |
		 * +--------------------+
		 * 		Welcome To iTop
		 * +--------------------+
		 * | Tools              |
		 * +--------------------+
		 * 		CSV Import
		 * +--------------------+
		 * | Admin Tools        | << Only present if the user is an admin
		 * +--------------------+
		 *		User Accounts
		 *		Profiles
		 *		Notifications
		 *		Run Queries
		 *		Export
		 *		Data Model
		 *		Universal Search
		 */
		$oWelcomeMenu = new MenuGroup('WelcomeMenu', 10 /* fRank */);
		new TemplateMenuNode('WelcomeMenuPage', '../application/templates/welcome_menu.html', $oWelcomeMenu->GetIndex() /* oParent */, 1 /* fRank */);
		
		$oToolsMenu = new MenuGroup('DataAdministration', 70 /* fRank */);
		new WebPageMenuNode('CSVImportMenu', '../pages/csvimport.php', $oToolsMenu->GetIndex(), 1 /* fRank */);

		// Add the admin menus
		$oAdminMenu = new MenuGroup('AdminTools', 80 /* fRank */);
		new OQLMenuNode('UserAccountsMenu', 'SELECT User', $oAdminMenu->GetIndex(), 1 /* fRank */);
		new OQLMenuNode('ProfilesMenu', 'SELECT URP_Profiles', $oAdminMenu->GetIndex(), 2 /* fRank */);
		new TemplateMenuNode('NotificationsMenu', '../application/templates/notifications_menu.html', $oAdminMenu->GetIndex(), 3 /* fRank */);
		new WebPageMenuNode('RunQueriesMenu', '../pages/run_query.php', $oAdminMenu->GetIndex(), 8 /* fRank */);
		new WebPageMenuNode('ExportMenu', '../webservices/export.php', $oAdminMenu->GetIndex(), 9 /* fRank */);
		new WebPageMenuNode('DataModelMenu', '../pages/schema.php', $oAdminMenu->GetIndex(), 10 /* fRank */);
		new WebPageMenuNode('UniversalSearchMenu', '../pages/UniversalSearch.php', $oAdminMenu->GetIndex(), 11 /* fRank */);
	}
	
	public function AddToMenu($sHtml)
	{
		$this->m_sMenu .= $sHtml;
	}

	public function GetSiloSelectionForm()
	{
		// List of visible Organizations
		$iCount = 0;
		$oContext = new UserContext();
		if (MetaModel::IsValidClass('Organization'))
		{
			$oSearchFilter = $oContext->NewFilter('Organization');
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
			$sHtml = '<div id="SiloSelection">';
			$sHtml .= '<form style="display:inline" action="'.$_SERVER['PHP_SELF'].'"><select style="width:150px;font-size:x-small" name="org_id" title="Pick an organization" onChange="this.form.submit();">';
			$sSelected = ($this->m_currentOrganization == '') ? ' selected' : '';
			$sHtml .= '<option value=""'.$sSelected.'>'.Dict::S('UI:AllOrganizations').'</option>';
			while($oOrg = $oSet->Fetch())
			{
				if ($this->m_currentOrganization == $oOrg->GetKey())
				{
					$oCurrentOrganization = $oOrg;
					$sSelected = " selected";
			
				}
				else
				{
					$sSelected = "";
				}
				$sHtml .= '<option value="'.$oOrg->GetKey().'"'.$sSelected.'>'.$oOrg->GetName().'</option>';
			}
			$sHtml .= '</select>';
			// Add other dimensions/context information to this form
			$oAppContext = new ApplicationContext();
			$oAppContext->Reset('org_id'); // Org id is handled above and we want to be able to change it here !
			$sHtml .= $oAppContext->GetForForm();		
			$sHtml .= '</form>';
			$sHtml .= '</div>';
		}
		return $sHtml;		
	}
	
    public function DisplayMenu()
    {
		$oContext = new UserContext();
		// Display the menu
		$oAppContext = new ApplicationContext();
		$iActiveNodeId = ApplicationMenu::GetActiveNodeId();
		$iAccordionIndex = 0;

		ApplicationMenu::DisplayMenu($this, $oAppContext->GetAsHash(), $iActiveNodeId);
    }

	/**
	 * Outputs (via some echo) the complete HTML page by assembling all its elements
	 */
    public function output()
    {
		$this->DisplayMenu(); // Compute the menu
        foreach($this->a_headers as $s_header)
        {
            header($s_header);
        }
        $s_captured_output = ob_get_contents();
        ob_end_clean();
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
        echo "<html>\n";
        echo "<head>\n";
        echo "<title>{$this->s_title}</title>\n";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
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
            echo "<script type=\"text/javascript\" src=\"$s_script\"></script>\n";
        }
        if (count($this->m_aReadyScripts)>0)
        {
			$this->add_script("\$(document).ready(function() {\n".implode("\n", $this->m_aReadyScripts)."\n});");
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

		$sForm = $this->GetSiloSelectionForm();
		
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
		
		if (utils::CanLogOff() && UserRights::CanLogOff())
		{
			//$sLogOffMenu .= "<li><a href=\"../pages/UI.php?loginop=logoff\">".Dict::S('UI:LogOffMenu')."</a></li>\n";
			$sLogOffMenu .= "<li><a href=\"../pages/logoff.php\">".Dict::S('UI:LogOffMenu')."</a></li>\n";
		}
		if (UserRights::CanChangePassword())
		{
			$sLogOffMenu .= "<li><a href=\"../pages/UI.php?loginop=change_pwd\">".Dict::S('UI:ChangePwdMenu')."</a></li>\n";
		}
		$sLogOffMenu .= "</ul>\n</li>\n</ul></span>\n";

		//$sLogOffMenu = "<span id=\"logOffBtn\" style=\"height:55px;padding:0;margin:0;\"><img src=\"../images/onOffBtn.png\"></span>";

		echo '<div id="left-pane" class="ui-layout-west">';
		echo '<!-- Beginning of the left pane -->';
		echo '	<div id="header-logo">';
		echo '	<div id="top-left"></div><div id="logo"><a href="http://www.combodo.com/itop"><img src="../images/itop-logo.png" title="'.$sVersionString.'" style="border:0; margin-top:16px; margin-right:40px;"/></a></div>';
		echo '	</div>';
		echo '	<div class="header-menu">';
		echo '		<div class="icon ui-state-default ui-corner-all"><span id="tPinMenu" class="ui-icon ui-icon-pin-w">pin</span></div>';
		echo '		<div style="width:100%; text-align:center;">'.$sForm.'</div>';
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
		echo '		<div id="global-search"><form><table><tr><td id="g-search-input"><input type="text" name="text" value="'.$sText.'"'.$sOnClick.'/></td>';
		echo '<td><input type="image" src="../images/searchBtn.png"/></a></td>';
		echo '<td><a style="background:transparent;" href="http://www.combodo.com/itop-help" target="_blank"><img style="border:0;padding-left:20px;padding-right:10px;" title="'.Dict::S('UI:Help').'" src="../images/help.png"/></td>';
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
        echo $this->s_deferred_content;
		echo "<div style=\"display:none\" title=\"ex2\" id=\"ex2\">Please wait...</div>\n"; // jqModal Window
		echo "<div style=\"display:none\" title=\"dialog\" id=\"ModalDlg\"></div>";

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
