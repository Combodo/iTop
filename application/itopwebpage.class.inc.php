<?php
require_once("../application/nicewebpage.class.inc.php");
require_once("../application/usercontext.class.inc.php");
require_once("../application/applicationcontext.class.inc.php");
/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 */
class iTopWebPage extends nice_web_page
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
		$this->add_header("Content-type: text/html; charset=utf-8");
		$this->add_header("Cache-control: no-cache");
		$this->m_currentOrganization = $currentOrganization;
		$this->add_linked_script("../js/jquery.dimensions.js");
		$this->add_linked_script("../js/splitter.js");
		$this->add_linked_script("../js/jquery.tablehover.js");
		$this->add_linked_script("../js/jquery.treeview.js");
		$this->add_linked_script("../js/jquery.autocomplete.js");
		$this->add_linked_script("../js/jquery.bgiframe.js");
		$this->add_linked_script("../js/jquery.jdMenu.js");
		$this->add_linked_script("../js/date.js");
		$this->add_linked_script("../js/jquery.date.picker.js");
		$this->add_linked_script("../js/jquery.tablesorter.min.js");
		//$this->add_linked_script("../js/jquery-ui-personalized-1.5.3.js");
		$this->add_linked_script("../js/swfobject.js");
		$this->add_linked_stylesheet("../css/jquery.treeview.css");
		$this->add_linked_stylesheet("../css/jquery.autocomplete.css");
		$this->add_linked_stylesheet("../css/date.picker.css");
		$this->add_ready_script(
<<<EOF
	// Vertical splitter. The min/max/starting sizes for the left (A) pane
	// are set here. All values are in pixels.
	$("#MySplitter").splitter({
		type: "v", 
		minA: 100, initA: 250, maxA: 500,
		accessKey: "|"
	});

	// Horizontal splitter, nested in the right pane of the vertical splitter.
	if ( $("#TopPane").length > 0)
	{
		$("#RightPane").splitter({
			type: "h" //,
			//minA: 100, initA: 150, maxA: 500,
			//accessKey: "_"
		});
	}
	
	// Manually set the outer splitter's height to fill the browser window.
	// This must be re-done any time the browser window is resized.
	$(window).bind("resize", function(){
		var ms = $("#MySplitter");
		var top = ms.offset().top;		// from dimensions.js
		var wh = $(window).height();
		// Account for margin or border on the splitter container
		var mrg = parseInt(ms.css("marginBottom")) || 0;
		var brd = parseInt(ms.css("borderBottomWidth")) || 0;
		ms.css("height", (wh-top-mrg-brd)+"px");

		// IE fires resize for splitter; others don't so do it here
		if ( !jQuery.browser.msie )
			ms.trigger("resize");

		
	}).trigger("resize");
	
	var ms = $("#MySplitter");
	ms.trigger("resize");

	if ( $("#TopPane").length > 0)
	{
		$("#RightPane").trigger("resize");
	}
	
	$("#tabbedContent > ul").tabs( 1, { fxFade: true, fxSpeed: 'fast' } ); // tabs
	$("table.listResults").tableHover(); // hover tables
	$(".listResults").tablesorter( { headers: { 0:{sorter: false }}, widgets: ['zebra']} ); // sortable and zebra tables
	$(".date-pick").datePicker( {clickInput: false, createButton: true, startDate: '2000-01-01'} ); // Date picker
	$('#ModalDlg').jqm({ajax: '@href', trigger: 'a.jqmTrigger', overlay:70, modal:true, toTop:true}); // jqModal Window
	
	//$('.display_block').draggable(); // make the blocks draggable
EOF
);
		$this->add_script("
		// For automplete
		function findValue(li) {
			if( li == null ) return alert(\"No match!\");
			
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
		");
		$this->DisplayMenu();
	}
	
	public function AddToMenu($sHtml)
	{
		$this->m_sMenu .= $sHtml;
	}

    public function DisplayMenu()
    {
        // Combo box to select the organization
		$this->AddToMenu("<div id=\"OrganizationSelection\">
			  <form style=\"display:inline\" action=\"./UI.php\"><select style=\"width:150px;font-size:x-small\" name=\"org_id\" title=\"Pick an organization\" onChange=\"this.form.submit();\">\n");
		// List of visible Organizations
		$oContext = new UserContext();
		$oSearchFilter = $oContext->NewFilter("bizOrganization");
		$oSet = new CMDBObjectSet($oSearchFilter);
		$sSelected = ($this->m_currentOrganization == '') ? ' selected' : '';
		$this->AddToMenu("<option value=\"\"$sSelected> All Organizations </option>");
		if ($oSet->Count() > 0)
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
			$this->AddToMenu("<option value=\"".$oOrg->GetKey()."\"$sSelected>".$oOrg->Get('name')."</option>\n");
		}
		$this->AddToMenu("</select></form>\n");
		$this->AddToMenu("</div>\n");
		$this->AddToMenu("<ul id=\"browser\" class=\"dir\">\n");

		// Display the menu
		$oAppContext = new ApplicationContext();
		// 1) Application defined menus
		$oSearchFilter = $oContext->NewFilter("menuNode");
		$oSearchFilter->AddCondition('parent_id', 0, '=');
		$oSearchFilter->AddCondition('type', 'application', '=');
		// There may be more criteria added later to have a specific menu based on the user's profile
		$oSet = new CMDBObjectSet($oSearchFilter, array('rank' => true));
		while ($oRootMenuNode = $oSet->Fetch())
		{
			$oRootMenuNode->DisplayMenu($this, 'application', $oAppContext->GetAsHash());
		}
		// 2) User defined menus (Bookmarks)
		$oSearchFilter = $oContext->NewFilter("menuNode");
		$oSearchFilter->AddCondition('parent_id', 0, '=');
		$oSearchFilter->AddCondition('type', 'user', '=');
		$oSearchFilter->AddCondition('user_id', UserRights::GetUserId(), '=');
		// There may be more criteria added later to have a specific menu based on the user's profile
		$oSet = new CMDBObjectSet($oSearchFilter, array('rank' => true));
		while ($oRootMenuNode = $oSet->Fetch())
		{
			$oRootMenuNode->DisplayMenu($this, 'user', $oAppContext->GetAsHash());
		}
		// 3) Administrator menu
		if (userRights::IsAdministrator())
		{
			$oSearchFilter = $oContext->NewFilter("menuNode");
			$oSearchFilter->AddCondition('parent_id', 0, '=');
			$oSearchFilter->AddCondition('type', 'administrator', '=');
			// There may be more criteria added later to have a specific menu based on the user's profile
			$oSet = new CMDBObjectSet($oSearchFilter, array('rank' => true));
			while ($oRootMenuNode = $oSet->Fetch())
			{
				$oRootMenuNode->DisplayMenu($this, 'administrator', $oAppContext->GetAsHash());
			}
		}

		$this->AddToMenu("</ul>\n");
    }

	/**
	 * Outputs (via some echo) the complete HTML page by assembling all its elements
	 */
    public function output()
    {
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

		// Display the header
		echo "<div id=\"Header\">\n";
		echo "<div class=\"iTopLogo\"><span>iTop</span></div>\n";
		//echo "<div id=\"GlobalSearch\"><div style=\"border: 1px solid #999; padding:1px; background-color:#fff;\"><img src=\"../images/magnifier.gif\"/><input style=\"border:0\" type=\"text\" size=\"15\" title=\"Global Search\"></input></div></div>\n";
		$sText = Utils::ReadParam('text', '');
		$sOnClick = "";
		if (empty($sText))
		{
			// if no search text is supplied then
			// 1) the search text is filled with "your search"
			// 2) clicking on it will erase it
			$sText = "Your search";
			$sOnClick = " onclick=\"this.value='';this.onclick=null;\"";
		}
		$sUserName = UserRights::GetUser();
		$sIsAdmin = UserRights::IsAdministrator() ? '(Administrator)' : '';
		echo "<div id=\"Login\" style=\"position:absolute; top:18px; right:16px; width:500px;\">Logged in as '$sUserName'&nbsp;$sIsAdmin&nbsp;&nbsp;";
		echo "<form action=\"../pages/UI.php\" method=\"post\" style=\"display:inline\">\n";
		echo "<input type=\"submit\" value=\"Log off\" />\n";
		echo "<input type=\"hidden\" name=\"operation\" value=\"logoff\" />\n";
		echo "</form>\n";
		echo "<form action=\"../pages/UI.php\" style=\"display:inline\"><div style=\"padding:1px; background-color:#fff;display:inline;\"><img src=\"../images/magnifier.gif\"/><input style=\"border:0\" type=\"text\" size=\"15\" title=\"Global Search\" name=\"text\" value=\"$sText\"$sOnClick></input></div><input type=\"submit\" value=\"Search\" />
			  <input type=\"hidden\" name=\"operation\" value=\"full_text\" /></form>\n";
		echo "</div>\n";

		echo "</div>\n";

		// Display the menu
		echo "<div id=\"MySplitter\">\n";
		echo "  <div id=\"LeftPane\">\n";
		echo $this->m_sMenu;
		echo "  </div> <!-- LeftPane -->\n";
		
		echo "<div id=\"RightPane\">\n";
        
		// Render the tabs in the page (if any)
		foreach($this->m_aTabs as $sTabContainerName => $m_aTabs)
		{
			$sTabs = '';
			if (count($m_aTabs) > 0)
			{
			  $sTabs = "<!-- tabs -->\n<div id=\"tabbedContent\" class=\"light\">\n";
			  $sTabs .= "<ul>\n";
			  // Display the unordered list that will be rendered as the tabs
	          $i = 0;
			  foreach($m_aTabs as $sTabName => $sTabContent)
			  {
			      $sTabs .= "<li><a href=\"#fragment_$i\" class=\"tab\"><span>".htmlentities($sTabName)."</span></a></li>\n";
			      $i++;
	          }
			  $sTabs .= "</ul>\n";
			  // Now add the content of the tabs themselves
			  $i = 0;
			  foreach($m_aTabs as $sTabName => $sTabContent)
			  {
			      $sTabs .= "<div id=\"fragment_$i\">".$sTabContent."</div>\n";
			      $i++;
	          }
			  $sTabs .= "</div>\n<!-- end of tabs-->\n";
	        }
			$this->s_content = str_replace("\$Tabs:$sTabContainerName\$", $sTabs, $this->s_content);
		}
        
		// Display the page's content
        echo $this->s_content;

        // Add the captured output
        if (trim($s_captured_output) != "")
        {
            echo "<div class=\"raw_output\">$s_captured_output</div>\n";
        }
		echo "<div class=\"jqmWindow\" id=\"ex2\">Please wait...</div>\n"; // jqModal Window
		echo "</div> <!-- RightPane -->\n";
		echo "</div> <!-- Splitter -->\n";
		echo "<div class=\"jqmWindow\" id=\"ModalDlg\"></div>";
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
	
	public function StartCollapsibleSection($sSectionLabel, $bOpen = false)
	{
		$this->add($this->GetStartCollapsibleSection($sSectionLabel, $bOpen));
	}

	public function GetStartCollapsibleSection($sSectionLabel, $bOpen = false)
	{
		$sHtml = '';
		static $iSectionId = 0;
		$sHtml .= "<a id=\"LnkCollapse_$iSectionId\" class=\"CollapsibleLabel\" href=\"#\">$sSectionLabel</a></br>\n";
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
