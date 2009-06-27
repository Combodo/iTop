<?php
require_once('../application/webpage.class.inc.php');
require_once('../application/utils.inc.php');
require_once('../core/userrights.class.inc.php');
/**
 * Helper class to manage 'blocks' of HTML pieces that are parts of a page and contain some list of cmdb objects
 *
 * Each block is actually rendered as a <div></div> tag that can be rendered synchronously
 * or as a piece of Javascript/JQuery/Ajax that will get its content from another page (ajax.render.php).
 * The list of cmdbObjects to be displayed into the block is defined by a filter
 * Right now the type of display is either: list, count, bare_details, details, csv, modify or search
 * - list produces a table listing the objects
 * - count produces a paragraphs with a sentence saying 'cont' objects found
 * - bare_details displays just the details of the attributes of the object  (best if only one)
 * - details display the full details of each object found using its template (best if only one)
 * - csv displays a textarea with the CSV export of the list of objects 
 * - modify displays the form to modify an object (best if only one)
 * - search displays a search form with the criteria of the filter set
 */
class DisplayBlock
{
	const TAG_BLOCK = 'itopblock';
	protected $m_oFilter;
	protected $m_sStyle;
	protected $m_bAsynchronous;
	protected $m_aParams;
	protected $m_oSet;
	
	public function __construct(DBObjectSearch $oFilter, $sStyle = 'list', $bAsynchronous = false, $aParams = array(), $oSet = null)
	{
		$this->m_oFilter = $oFilter;
		$this->m_sStyle = $sStyle;
		$this->m_bAsynchronous = $bAsynchronous;
		$this->m_aParams = $aParams;
		$this->m_oSet = $oSet;
	}
	/**
	 * Constructs a DisplayBlock object from a DBObjectSet already in memory
	 * @param $oSet DBObjectSet
	 * @return DisplayBlock The DisplayBlock object, or null if the creation failed
	 */
	public static function FromObjectSet(DBObjectSet $oSet, $sStyle, $aParams = array())
	{
		$oDummyFilter = new DBObjectSearch($oSet->GetClass());
		$oBlock = new DisplayBlock($oDummyFilter, $sStyle, false, $aParams, $oSet); // DisplayBlocks built this way are synchronous
		return $oBlock;
	}
	
	/**
	 * Constructs a DisplayBlock object from an XML template
	 * @param $sTemplate string The XML template
	 * @return DisplayBlock The DisplayBlock object, or null if the template is invalid
	 */
	public static function FromTemplate($sTemplate)
	{
		$iStartPos = stripos($sTemplate, '<'.self::TAG_BLOCK.' ',0);
		$iEndPos = stripos($sTemplate, '</'.self::TAG_BLOCK.'>', $iStartPos); 
		
		if (($iStartPos === false) || ($iEndPos === false)) return null; // invalid template		
		$sITopBlock = substr($sTemplate,$iStartPos, $iEndPos-$iStartPos);
		$sITopData = substr($sITopBlock, 1+stripos($sITopBlock, ">"));
		$sITopTag = substr($sITopBlock, 0, stripos($sITopBlock, ">"));
		$aMatches = array();
		$sBlockClass = "DisplayBlock";
		$bAsynchronous = false;
		$sBlockType = 'list';
		$sEncoding = 'text/serialize';
		if (preg_match('/ type="(.*)"/U',$sITopTag, $aMatches))
		{
			$sBlockType = strtolower($aMatches[1]);
		}
		if (preg_match('/ asynchronous="(.*)"/U',$sITopTag, $aMatches))
		{
			$bAsynchronous = (strtolower($aMatches[1]) == 'true');
		}
		if (preg_match('/ blockclass="(.*)"/U',$sITopTag, $aMatches))
		{
			$sBlockClass = $aMatches[1];
		}
		if (preg_match('/ objectclass="(.*)"/U',$sITopTag, $aMatches))
		{
			$sObjectClass = $aMatches[1];
		}
		if (preg_match('/ encoding="(.*)"/U',$sITopTag, $aMatches))
		{
			$sEncoding = strtolower($aMatches[1]);
		}
		if (preg_match('/ linkage="(.*)"/U',$sITopTag, $aMatches))
		{
			// The list to display is a list of links to the specified object
			$sExtKey = strtolower($aMatches[1]);
			$aParams['linkage'] = $sExtKey; // Name of the Ext. Key that make this linkage
		}
		// Parameters contains a list of extra parameters for the block
		// the syntax is param_name1:value1;param_name2:value2;...
		$aParams = array();
		if (preg_match('/ parameters="(.*)"/U',$sITopTag, $aMatches))
		{
			$sParameters = $aMatches[1];
			$aPairs = explode(';', $sParameters);
			foreach($aPairs as $sPair)
			{
				if (preg_match('/(.*)\:(.*)/',$sPair, $aMatches))
				{
					$aParams[trim($aMatches[1])] = trim($aMatches[2]);
				}
			}
		}
		switch($sEncoding)
		{
			case 'text/serialize':
			$oFilter = CMDBSearchFilter::unserialize($sITopData);
			break;
			
			case 'text/sibusql':
			$oFilter = CMDBSearchFilter::FromSibusQL($sITopData);
			break;
			
			case 'text/oql':
			$oFilter = CMDBSearchFilter::FromOQL($sITopData);
			break;
		}
		return new $sBlockClass($oFilter, $sBlockType, $bAsynchronous, $aParams);		
	}
	
	public function Display(web_page $oPage, $sId, $aExtraParams = array())
	{
		$aExtraParams = array_merge($aExtraParams, $this->m_aParams);
		if (!$this->m_bAsynchronous)
		{
			// render now
			$oPage->add("<div id=\"$sId\" class=\"display_block\">\n");
			$this->RenderContent($oPage, $aExtraParams);
			$oPage->add("</div>\n");
		}
		else
		{
			// render it as an Ajax (asynchronous) call
			$sFilter = $this->m_oFilter->serialize();
			$oPage->add("<div id=\"$sId\" class=\"display_block loading\">\n");
			$oPage->p("<img src=\"../images/indicator_arrows.gif\"> Loading...");
			$oPage->add("</div>\n");
			$oPage->add('
			<script language="javascript">
			$.get("ajax.render.php?filter='.$sFilter.'&style='.$this->m_sStyle.'",
			   { operation: "ajax" },
			   function(data){
				 $("#'.$sId.'").empty();
				 $("#'.$sId.'").append(data);
				 $("#'.$sId.'").removeClass("loading");
				}
			 );
			 </script>'); // TO DO: add support for $aExtraParams in asynchronous/Ajax mode
		}
	}
	
	public function GetDisplay(web_page $oPage, $sId, $aExtraParams = array())
	{
		$sHtml = '';
		$aExtraParams = array_merge($aExtraParams, $this->m_aParams);
		if (!$this->m_bAsynchronous)
		{
			// render now
			$sHtml .= "<div id=\"$sId\" class=\"display_block\">\n";
			$sHtml .= $this->GetRenderContent($oPage, $aExtraParams);
			$sHtml .= "</div>\n";
		}
		else
		{
			// render it as an Ajax (asynchronous) call
			$sFilter = $this->m_oFilter->serialize();
			$sHtml .= "<div id=\"$sId\" class=\"display_block loading\">\n";
			$sHtml .= $oPage->GetP("<img src=\"../images/indicator_arrows.gif\"> Loading...");
			$sHtml .= "</div>\n";
			$sHtml .= '
			<script language="javascript">
			$.get("ajax.render.php?filter='.$sFilter.'&style='.$this->m_sStyle.'",
			   { operation: "ajax" },
			   function(data){
				 $("#'.$sId.'").empty();
				 $("#'.$sId.'").append(data);
				 $("#'.$sId.'").removeClass("loading");
				}
			 );
			 </script>'; // TO DO: add support for $aExtraParams in asynchronous/Ajax mode
		}
		return $sHtml;
	}
	
	public function RenderContent(web_page $oPage, $aExtraParams = array())
	{
		$oPage->add($this->GetRenderContent($oPage, $aExtraParams));
	}
	
	public function GetRenderContent(web_page $oPage, $aExtraParams = array())
	{
		$sHtml = '';
		// Add the extra params into the filter if they make sense for such a filter
		$bDoSearch = utils::ReadParam('dosearch', false);
		if ($this->m_oSet == null)
		{
			$aFilterCodes = array_keys(MetaModel::GetClassFilterDefs($this->m_oFilter->GetClass()));
			foreach($aFilterCodes as $sFilterCode)
			{
				$sExternalFilterValue = utils::ReadParam($sFilterCode, '');
				if (isset($aExtraParams[$sFilterCode]))
				{
					$this->m_oFilter->AddCondition($sFilterCode, $aExtraParams[$sFilterCode]); // Use the default 'loose' operator
				}
				else if ($bDoSearch && $sExternalFilterValue != "")
				{
					$this->m_oFilter->AddCondition($sFilterCode, $sExternalFilterValue); // Use the default 'loose' operator
				}
			}
			$this->m_oSet = new CMDBObjectSet($this->m_oFilter);
		}
		switch($this->m_sStyle)
		{
			case 'count':
			if (isset($aExtraParams['group_by']))
			{
				$sGroupByField = $aExtraParams['group_by'];
				$aGroupBy = array();
				while($oObj = $this->m_oSet->Fetch())
				{
					$sValue = $oObj->Get($sGroupByField);
					$aGroupBy[$sValue] = isset($aGroupBy[$sValue]) ? $aGroupBy[$sValue]+1 : 1;
				}
				$sFilter = urlencode($this->m_oFilter->serialize());
				$aData = array();
				foreach($aGroupBy as $sValue => $iCount)
				{
					$aData[] = array ( 'group' => $sValue,
									  'value' => "<a href=\"./UI.php?operation=search&dosearch=1&filter=$sFilter&$sGroupByField=".urlencode($sValue)."\">$iCount</a>"); // TO DO: add the context information
				}
				$sHtml .= $oPage->GetTable(array('group' => array('label' => MetaModel::GetLabel($this->m_oFilter->GetClass(), $sGroupByField), 'description' => ''), 'value' => array('label'=>'Count', 'description' => 'Number of elements')), $aData);
			}
			else
			{
				// Simply count the number of elements in the set
				$iCount = $oSet->Count();
				$sHtml .= $oPage->GetP("$iCount objects matching the criteria.");
			}
			
			break;
			
			case 'list':
			$bDashboardMode = isset($aExtraParams['dashboard']) ? ($aExtraParams['dashboard'] == 'true') : false;
			if ( ($this->m_oSet->Count()> 0) && (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES) )
			{
				$sLinkage = isset($aExtraParams['linkage']) ? $aExtraParams['linkage'] : '';
				$sHtml .= cmdbAbstractObject::GetDisplaySet($oPage, $this->m_oSet, $sLinkage, !$bDashboardMode /* bDisplayMenu */);
			}
			else
			{
				$sHtml .= $oPage->GetP("No object to display.");
				$sClass = $this->m_oFilter->GetClass();
				if (!$bDashboardMode)
				{
					if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $this->m_oSet) == UR_ALLOWED_YES)
					{
						$sHtml .= $oPage->GetP("<a href=\"./UI.php?operation=new&class=$sClass\">Click here to create a new ".Metamodel::GetName($sClass)."</a>\n");
					}
				}
			}
			break;
			
			case 'details':
			if (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES)
			{
				while($oObj = $this->m_oSet->Fetch())
				{
					$sHtml .= $oObj->GetDetails($oPage);
				}
			}
			break;
			
			case 'bare_details':
			if (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES)
			{
				while($oObj = $this->m_oSet->Fetch())
				{
					$sHtml .= $oObj->GetBareDetails($oPage);
				}
			}
			break;
			
			case 'csv':
			if (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES)
			{
				$sHtml .= "<textarea style=\"width:100%;height:98%\">\n";
				$sHtml .= cmdbAbstractObject::GetSetAsCSV($this->m_oSet);
				$sHtml .= "</textarea>\n";
			}
			break;

			case 'modify':
			if (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_MODIFY, $this->m_oSet) == UR_ALLOWED_YES)
			{
				while($oObj = $this->m_oSet->Fetch())
				{
					$sHtml .= $oObj->GetModifyForm($oPage);
				}
			}
			break;
			
			case 'search':
			$iSearchSectionId = 1;
			$sStyle = (isset($aExtraParams['open']) && ($aExtraParams['open'] == 'true')) ? 'SearchDrawer' : 'SearchDrawer DrawerClosed';
			$sHtml .= "<div id=\"Search_$iSearchSectionId\" class=\"$sStyle\">\n";
			$sHtml .= "<h1>Search form for ".Metamodel::GetName($this->m_oSet->GetClass())."</h1>\n";
			$oPage->add_ready_script("\$(\"#LnkSearch_$iSearchSectionId\").click(function() {\$(\"#Search_$iSearchSectionId\").slideToggle('normal'); $(\"#LnkSearch_$iSearchSectionId\").toggleClass('open');});");
			$sHtml .= cmdbAbstractObject::GetSearchForm($oPage, $this->m_oSet, $aExtraParams);
	 		$sHtml .= "</div>\n";
	 		$sHtml .= "<div class=\"HRDrawer\"/></div>\n";
	 		$sHtml .= "<div id=\"LnkSearch_$iSearchSectionId\" class=\"DrawerHandle\">Search</div>\n";
			break;
			
			case 'pie_chart':
			$sGroupBy = isset($aExtraParams['group_by']) ? $aExtraParams['group_by'] : '';
			$sFilter = $this->m_oFilter->ToOQL();
			$sHtml .= "
			<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\"
				codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" 
				WIDTH=\"400\" 
				HEIGHT=\"250\" 
				id=\"charts\" 
				ALIGN=\"\">
			<PARAM NAME=movie VALUE=\"../images/charts.swf?library_path=../images/charts_library&xml_source=".urlencode("../pages/ajax.render.php?operation=pie_chart&group_by=$sGroupBy&encoding=oql&filter=".urlencode($sFilter))."\">
			<PARAM NAME=\"quality\" VALUE=\"high\">
			<PARAM NAME=\"bgcolor\" VALUE=\"#ffffff\">
			
			<EMBED src=\"../images/charts.swf?library_path=../images/charts_library&xml_source=".urlencode("../pages/ajax.render.php?operation=pie_chart&group_by=$sGroupBy&encoding=oql&filter=".urlencode($sFilter))."\" 
			       quality=\"high\"
			       bgcolor=\"#ffffff\"  
			       WIDTH=\"400\" 
			       HEIGHT=\"250\" 
			       NAME=\"charts\" 
			       ALIGN=\"\" 
			       swLiveConnect=\"true\" 
			       TYPE=\"application/x-shockwave-flash\" 
			       PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\">
			</EMBED>
			</OBJECT>
			";
			break;
			
			case 'pie_chart_ajax':
			if (isset($aExtraParams['group_by']))
			{
				$sGroupByField = $aExtraParams['group_by'];
				$aGroupBy = array();
				while($oObj = $this->m_oSet->Fetch())
				{
					$sValue = $oObj->Get($sGroupByField);
					$aGroupBy[$sValue] = isset($aGroupBy[$sValue]) ? $aGroupBy[$sValue]+1 : 1;
				}
				$sFilter = urlencode($this->m_oFilter->serialize());
				$aData = array();
				$sHtml .= "<chart>\n";
				$sHtml .= "<chart_type>3d pie</chart_type>\n";
				$sHtml .= "<chart_data>\n";
				$sHtml .= "<row>\n";
				$sHtml .= "<null/>\n";
				foreach($aGroupBy as $sValue => $void)
				{
					$sHtml .= "<string>$sValue</string>\n";
				}
				$sHtml .= "</row>\n";
				$sHtml .= "<row>\n";
				$sHtml .= "<string></string>\n";
				foreach($aGroupBy as $void => $iCount)
				{
					$sHtml .= "<number>$iCount</number>\n";
				}
				$sHtml .= "</row>\n";
				$sHtml .= "</chart_data>\n";
				$sHtml .= "
	<chart_value color='ffffff' alpha='90' font='arial' bold='true' size='10' position='inside' prefix='' suffix='' decimals='0' separator='' as_percentage='true' />

	<draw>
		<text color='000000' alpha='10' font='arial' rotation='0' bold='true' size='30' x='0' y='140' width='400' height='150' h_align='center' v_align='bottom'>|||||||||||||||||||||||||||||||||||||||||||||||</text>
	</draw>

	<legend_label layout='horizontal' bullet='circle' font='arial' bold='true' size='13' color='000000' alpha='85' />
	<legend_rect fill_color='ffffff' fill_alpha='10' line_color='ffffff' line_alpha='50' line_thickness='0' />
	<series_color>
		<color>ddaa41</color>
		<color>88dd11</color>
		<color>4e62dd</color>
		<color>ff8811</color>
		<color>4d4d4d</color>
		<color>5a4b6e</color>
		<color>1188ff</color>
	</series_color>
				";
				$sHtml .= "</chart>\n";
			}
			else
			{
				// Simply count the number of elements in the set
				$iCount = $oSet->Count();
				$sHtml .= "<chart>\n</chart>\n";
			}
			break;
			
			case 'open_flash_chart':
			static $iChartCounter = 0;
			$sChartType = isset($aExtraParams['chart_type']) ? $aExtraParams['chart_type'] : 'pie';
			$sTitle = isset($aExtraParams['chart_title']) ? $aExtraParams['chart_title'] : '';
			$sGroupBy = isset($aExtraParams['group_by']) ? $aExtraParams['group_by'] : '';
			$sFilter = $this->m_oFilter->ToOQL();
			$sHtml .= "<script>
			swfobject.embedSWF(\"../images/open-flash-chart.swf\", \"my_chart_{$iChartCounter}\", \"400\", \"400\",\"9.0.0\", \"expressInstall.swf\",
			{\"data-file\":\"".urlencode("../pages/ajax.render.php?operation=open_flash_chart&params[group_by]=$sGroupBy&params[chart_type]=$sChartType&params[chart_title]=$sTitle&encoding=oql&filter=".urlencode($sFilter))."\"});
</script>\n";
			$sHtml .= "<div id=\"my_chart_{$iChartCounter}\">Here goes the chart</div>\n";
			$iChartCounter++;
			break;
			
			case 'open_flash_chart_ajax':
			include './php-ofc-library/open-flash-chart.php';
			$sChartType = isset($aExtraParams['chart_type']) ? $aExtraParams['chart_type'] : 'pie';

			$oChart = new open_flash_chart();
			switch($sChartType)
			{
				case 'bars':
				$oChartElement = new bar_glass();

				if (isset($aExtraParams['group_by']))
				{
					$sGroupByField = $aExtraParams['group_by'];
					$aGroupBy = array();
					while($oObj = $this->m_oSet->Fetch())
					{
						$sValue = $oObj->Get($sGroupByField);
						$aGroupBy[$sValue] = isset($aGroupBy[$sValue]) ? $aGroupBy[$sValue]+1 : 1;
					}
					$sFilter = urlencode($this->m_oFilter->serialize());
					$aData = array();
					$aLabels = array();
					foreach($aGroupBy as $sValue => $iValue)
					{
						$aData[] = $iValue;
						$aLabels[] = $sValue;
					}
					$maxValue = max($aData);
					$oYAxis = new y_axis();
					$aMagicValues = array(1,2,5,10);
					$iMultiplier = 1;
					$index = 0;
					$iTop = $aMagicValues[$index % count($aMagicValues)]*$iMultiplier;
					while($maxValue > $iTop)
					{
						$index++;
						$iTop = $aMagicValues[$index % count($aMagicValues)]*$iMultiplier;
						if (($index % count($aMagicValues)) == 0)
						{
							$iMultiplier = $iMultiplier * 10;
						}
					}
					//echo "oYAxis->set_range(0, $iTop, $iMultiplier);\n";
					$oYAxis->set_range(0, $iTop, $iMultiplier);
					$oChart->set_y_axis( $oYAxis );

					$oChartElement->set_values( $aData );
					$oXAxis = new x_axis();
					$oXLabels = new x_axis_labels();
					// set them vertical
					$oXLabels->set_vertical();
					// set the label text
					$oXLabels->set_labels($aLabels);
					// Add the X Axis Labels to the X Axis
					$oXAxis->set_labels( $oXLabels );
					$oChart->set_x_axis( $oXAxis );
				}
				break;
				
				case 'pie':
				default:
				$oChartElement = new pie();
				$oChartElement->set_start_angle( 35 );
				$oChartElement->set_animate( true );
				$oChartElement->set_tooltip( '#label# - #val# (#percent#)' );
				if (isset($aExtraParams['group_by']))
				{
					$sGroupByField = $aExtraParams['group_by'];
					$aGroupBy = array();
					while($oObj = $this->m_oSet->Fetch())
					{
						$sValue = $oObj->Get($sGroupByField);
						$aGroupBy[$sValue] = isset($aGroupBy[$sValue]) ? $aGroupBy[$sValue]+1 : 1;
					}
					$sFilter = urlencode($this->m_oFilter->serialize());
					$aData = array();
					foreach($aGroupBy as $sValue => $iValue)
					{
						$aData[] = new pie_value($iValue, $sValue);
					}
	
	
					$oChartElement->set_values( $aData );
					$oChart->x_axis = null;
				}
			}				
			if (isset($aExtraParams['chart_title'])) //@@ BUG: not passed via ajax !!!
			{
				$oTitle = new title( $aExtraParams['chart_title'] );
				$oChart->set_title( $oTitle );
			}
			$oChart->set_bg_colour('#FFFFFF');
			$oChart->add_element( $oChartElement );
			
			$sHtml = $oChart->toPrettyString();
			break;
			
			default:
			// Unsupported style, do nothing.
			$sHtml .= "Error: unsupported style of block: ".$this->m_sStyle;
		}
		return $sHtml;
	}
}

/**
 * Helper class to manage 'blocks' of HTML pieces that are parts of a page and contain some list of cmdb objects
 *
 * Each block is actually rendered as a <div></div> tag that can be rendered synchronously
 * or as a piece of Javascript/JQuery/Ajax that will get its content from another page (ajax.render.php).
 * The list of cmdbObjects to be displayed into the block is defined by a filter
 * Right now the type of display is either: list, count or details
 * - list produces a table listing the objects
 * - count produces a paragraphs with a sentence saying 'cont' objects found
 * - details display (as  table) the details of each object found (best if only one)
 */
class HistoryBlock extends DisplayBlock
{
	public function GetRenderContent(web_page $oPage, $aExtraParams = array())
	{
		$sHtml = '';
		// Add the extra params into the filter if they make sense for such a filter
		$aFilterCodes = array_keys(MetaModel::GetClassFilterDefs($this->m_oFilter->GetClass()));
		foreach($aFilterCodes as $sFilterCode)
		{
			if (isset($aExtraParams[$sFilterCode]))
			{
				$this->m_oFilter->AddCondition($sFilterCode, $aExtraParams[$sFilterCode]); // Use the default 'loose' operator
			}
		}
		$oSet = new CMDBObjectSet($this->m_oFilter, array('date'=>false));
		$sHtml .= "<!-- filter: ".($this->m_oFilter->ToOQL())."-->\n";
		switch($this->m_sStyle)
		{
			case 'toggle':
			$oLatestChangeOp = $oSet->Fetch();
			if (is_object($oLatestChangeOp))
			{
				global $oContext; // User Context.. should be statis instead of global...
				// There is one change in the list... only when the object has been created !
				$sDate = $oLatestChangeOp->GetAsHTML('date');
				$oChange = $oContext->GetObject('CMDBChange', $oLatestChangeOp->Get('change'));
				$sUserInfo = $oChange->GetAsHTML('userinfo');
				$oSet->Load(); // Reset the pointer to the beginning of the set: there should be a better way to do this...
				$sHtml .= $oPage->GetStartCollapsibleSection("Last modified on $sDate by $sUserInfo.");
				$sHtml .= cmdbAbstractObject::GetDisplaySet($oPage, $oSet);			
				$sHtml .= $oPage->GetEndCollapsibleSection();
			}
			break;
						
			default:
			$sHtml .= parent::GetRenderContent($oPage, $aExtraParams);
		}
		return $sHtml;
	}
}

class MenuBlock extends DisplayBlock
{
	public function GetRenderContent(web_page $oPage, $aExtraParams = array())
	{
		$sHtml = '';
		$oAppContext = new ApplicationContext();
		$sContext = $oAppContext->GetForLink();
		$sClass = $this->m_oFilter->GetClass();
		$oSet = new CMDBObjectSet($this->m_oFilter);
		$sFilter = $this->m_oFilter->serialize();
		$aActions = array();
		$sUIPage = cmdbAbstractObject::ComputeUIPage($sClass);
		switch($oSet->Count())
		{
			case 0:
			// No object in the set, the only possible action is "new"
			$bIsModifyAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet);
			if ($bIsModifyAllowed) { $aActions[] = array ('label' => 'New', 'url' => "../page/$sUIPage?operation=new&class=$sClass&$sContext"); }
			break;
			
			case 1:
			$oObj = $oSet->Fetch();
			$id = $oObj->GetKey();
			$bIsModifyAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet);
			$bIsDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, $oSet);
			$bIsBulkModifyAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY, $oSet);
			$bIsBulkDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, $oSet);
			// Just one object in the set, possible actions are "new / clone / modify and delete"
			if (isset($aExtraParams['linkage']))
			{
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => 'New...', 'url' => "#"); }
				if ($bIsBulkModifyAllowed) { $aActions[] = array ('label' => 'Modify All...', 'url' => "#"); }
				if ($bIsBulkDeleteAllowed) { $aActions[] = array ('label' => 'Remove All', 'url' => "#"); }
				if ($bIsModifyAllowed | $bIsDeleteAllowed) { $aActions[] = array ('label' => 'Manage Links...', 'url' => "#"); }
			}
			else
			{
				// Build an absolute URL to this page on this server/port
				$sServerName = $_SERVER['SERVER_NAME'];
				$sProtocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
				if ($sProtocol == 'http')
				{
					$sPort = ($_SERVER['SERVER_PORT'] == 80) ? '' : ':'.$_SERVER['SERVER_PORT'];
				}
				else
				{
					$sPort = ($_SERVER['SERVER_PORT'] == 443) ? '' : ':'.$_SERVER['SERVER_PORT'];
				}
				$sPath = $_SERVER['REQUEST_URI'];
				
				$sUrl = "$sProtocol://{$sServerName}{$sPort}{$sPath}";
				$aActions[] = array ('label' => 'eMail', 'url' => "mailto:?subject=".$oSet->GetFilter()->__DescribeHTML()."&body=".urlencode("$sUrl?operation=search&filter=$sFilter&$sContext"));
				$aActions[] = array ('label' => 'CSV Export', 'url' => "../pages/$sUIPage?operation=search&filter=$sFilter&format=csv&$sContext");
				$aActions[] = array ('label' => 'Bookmark...', 'url' => "../pages/ajax.render.php?operation=create&class=$sClass&filter=$sFilter", 'class' => 'jqmTrigger');
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => 'New...', 'url' => "../pages/$sUIPage?operation=new&class=$sClass&$sContext"); }
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => 'Clone...', 'url' => "../pages/$sUIPage?operation=clone&class=$sClass&id=$id&$sContext"); }
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => 'Modify...', 'url' => "../pages/$sUIPage?operation=modify&class=$sClass&id=$id&$sContext"); }
				if ($bIsDeleteAllowed) { $aActions[] = array ('label' => 'Delete', 'url' => "../pages/$sUIPage?operation=delete&class=$sClass&id=$id&$sContext"); }
			}
			$aTransitions = $oObj->EnumTransitions();
			$aStimuli = Metamodel::EnumStimuli($sClass);
			foreach($aTransitions as $sStimulusCode => $aTransitionDef)
			{
				$iActionAllowed = UserRights::IsStimulusAllowed($sClass, $sStimulusCode, $oSet);
				switch($iActionAllowed)
				{
					case UR_ALLOWED_YES:
					$aActions[] = array('label' => $aStimuli[$sStimulusCode]->Get('label'), 'url' => "../pages/UI.php?operation=stimulus&stimulus=$sStimulusCode&class=$sClass&id=$id&$sContext");
					break;
					
					case UR_ALLOWED_DEPENDS:
					$aActions[] = array('label' => $aStimuli[$sStimulusCode]->Get('label').' (*)', 'url' => "../pages/UI.php?operation=stimulus&stimulus=$sStimulusCode&class=$sClass&id=$id&$sContext");
					break;
					
					default:
					// Do nothing
				}
			}
			//print_r($aTransitions);
			break;
			
			default:
			// Check rights
			// New / Modify
			$bIsModifyAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet);
			$bIsBulkModifyAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY, $oSet);
			$bIsBulkDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, $oSet);
			if (isset($aExtraParams['linkage']))
			{
				$bIsDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, $oSet);
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => 'New...', 'url' => "#"); }
				if ($bIsBulkModifyAllowed) { $aActions[] = array ('label' => 'Modify All...', 'url' => "#"); }
				if ($bIsBulkDeleteAllowed) { $aActions[] = array ('label' => 'Remove All', 'url' => "#"); }
				if ($bIsModifyAllowed | $bIsDeleteAllowed) { $aActions[] = array ('label' => 'Manage Links...', 'url' => "#"); }
			}
			else
			{
				// many objects in the set, possible actions are: new / modify all / delete all
				$aActions[] = array ('label' => 'eMail', 'url' => "mailto:?subject=".$oSet->GetFilter()->__DescribeHTML()."&body=".urlencode("http://localhost:81/pages/UI.php?operation=search&filter=$sFilter&$sContext"));
				$aActions[] = array ('label' => 'CSV Export', 'url' => "../pages/$sUIPage?operation=search&filter=$sFilter&format=csv&$sContext");
				$aActions[] = array ('label' => 'Bookmark...', 'url' => "../pages/ajax.render.php?operation=create&class=$sClass&filter=$sFilter", 'class' => 'jqmTrigger');
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => 'New...', 'url' => "../pages/$sUIPage?operation=new&class=$sClass&$sContext"); }
				if ($bIsBulkModifyAllowed) { $aActions[] = array ('label' => 'Modify All...', 'url' => "../pages/$sUIPage?operation=modify_all&filter=$sFilter&$sContext"); }
				if ($bIsBulkDeleteAllowed) { $aActions[] = array ('label' => 'Delete All', 'url' => "../pages/$sUIPage?operation=delete_all&filter=$sFilter&$sContext"); }
			}
		}
		$sHtml .= "<div class=\"jd_menu_itop\"><ul class=\"jd_menu jd_menu_itop\">\n<li>Actions\n<ul>\n";
		foreach ($aActions as $aAction)
		{
			$sClass = isset($aAction['class']) ? " class=\"{$aAction['class']}\"" : "";
			$sHtml .= "<li><a href=\"{$aAction['url']}\"$sClass>{$aAction['label']}</a></li>\n<li>\n";
		}
		$sHtml .= "</ul>\n</li>\n</ul></div>\n";
		$oPage->add_ready_script("$(\"ul.jd_menu\").jdMenu();\n");
		return $sHtml;
	}
	
}
?>
