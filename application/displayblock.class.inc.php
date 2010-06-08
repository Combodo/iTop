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
 * DisplayBlock and derived class
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

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
		$iEndTag = stripos($sTemplate, '>', $iStartPos);
		$aParams = array();
		
		if (($iStartPos === false) || ($iEndPos === false)) return null; // invalid template		
		$sITopBlock = substr($sTemplate,$iStartPos, $iEndPos-$iStartPos+strlen('</'.self::TAG_BLOCK.'>'));
		$sITopData = substr($sTemplate, 1+$iEndTag, $iEndPos - $iEndTag - 1);
		$sITopTag = substr($sTemplate, $iStartPos + strlen('<'.self::TAG_BLOCK), $iEndTag - $iStartPos - strlen('<'.self::TAG_BLOCK));

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
		if (preg_match('/ link_attr="(.*)"/U',$sITopTag, $aMatches))
		{
			// The list to display is a list of links to the specified object
			$aParams['link_attr'] = $aMatches[1]; // Name of the Ext. Key that makes this linkage
		}
		if (preg_match('/ target_attr="(.*)"/U',$sITopTag, $aMatches))
		{
			// The list to display is a list of links to the specified object
			$aParams['target_attr'] = $aMatches[1]; // Name of the Ext. Key that make this linkage
		}
		if (preg_match('/ object_id="(.*)"/U',$sITopTag, $aMatches))
		{
			// The list to display is a list of links to the specified object
			$aParams['object_id'] = $aMatches[1]; // Id of the object to be linked to
		}
		// Parameters contains a list of extra parameters for the block
		// the syntax is param_name1:value1;param_name2:value2;...
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
		if (!empty($aParams['link_attr']))
		{
			// Check that all mandatory parameters are present:
			if(empty($aParams['object_id']))
			{
				// if 'links' mode is requested the d of the object to link to must be specified
				throw new ApplicationException(Dict::S('UI:Error:MandatoryTemplateParameter_object_id'));
			}
			if(empty($aParams['target_attr']))
			{
				// if 'links' mode is requested the id of the object to link to must be specified
				throw new ApplicationException(Dict::S('UI:Error:MandatoryTemplateParameter_target_attr'));
			}

		}
		switch($sEncoding)
		{
			case 'text/serialize':
			$oFilter = CMDBSearchFilter::unserialize($sITopData);
			break;
			
			case 'text/oql':
			$oFilter = CMDBSearchFilter::FromOQL($sITopData);
			break;
		}
		return new $sBlockClass($oFilter, $sBlockType, $bAsynchronous, $aParams);		
	}
	
	public function Display(WebPage $oPage, $sId, $aExtraParams = array())
	{
		$oPage->add($this->GetDisplay($oPage, $sId, $aExtraParams));
		/*
		$aExtraParams = array_merge($aExtraParams, $this->m_aParams);
		$aExtraParams['block_id'] = $sId;
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
		*/
	}
	
	public function GetDisplay(WebPage $oPage, $sId, $aExtraParams = array())
	{
		$sHtml = '';
		$aExtraParams = array_merge($aExtraParams, $this->m_aParams);
		$aExtraParams['block_id'] = $sId;
		$sExtraParams = addslashes(str_replace('"', "'", json_encode($aExtraParams))); // JSON encode, change the style of the quotes and escape them
		
		$bAutoReload = false;
		if (isset($aExtraParams['auto_reload']))
		{
			switch($aExtraParams['auto_reload'])
			{
				case 'fast':
				$bAutoReload = true;
				$iReloadInterval = utils::GetConfig()->GetFastReloadInterval()*1000;
				break;
				
				case 'standard':
				case 'true':
				case true:
				$bAutoReload = true;
				$iReloadInterval = utils::GetConfig()->GetStandardReloadInterval()*1000;
				break;
				
				default:
				if (is_numeric($aExtraParams['auto_reload']))
				{
					$bAutoReload = true;
					$iReloadInterval = $aExtraParams['auto_reload']*1000;
				}
				else
				{
					// incorrect config, ignore it
					$bAutoReload = false;
				}
			}
		}

		$sFilter = $this->m_oFilter->serialize(); // Used either for asynchronous or auto_reload
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
			$sHtml .= "<div id=\"$sId\" class=\"display_block loading\">\n";
			$sHtml .= $oPage->GetP("<img src=\"../images/indicator_arrows.gif\"> ".Dict::S('UI:Loading'));
			$sHtml .= "</div>\n";
			$sHtml .= '
			<script language="javascript">
			$.get("ajax.render.php?filter='.$sFilter.'&style='.$this->m_sStyle.'",
			   { operation: "ajax", extra_params: "'.$sExtraParams.'" },
			   function(data){
				 $("#'.$sId.'").empty();
				 $("#'.$sId.'").append(data);
				 $("#'.$sId.'").removeClass("loading");
				 $("#'.$sId.' .listResults").tablesorter( { headers: { 0:{sorter: false }}, widgets: [\'zebra\']} ); // sortable and zebra tables
				}
			 );
			 </script>';
		}
		if ($bAutoReload)
		{
			$sHtml .= '
			<script language="javascript">
			setInterval("ReloadBlock(\''.$sId.'\', \''.$this->m_sStyle.'\', \''.$sFilter.'\', \"'.$sExtraParams.'\")", '.$iReloadInterval.');
			 </script>';
		}
		return $sHtml;
	}
	
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		$oPage->add($this->GetRenderContent($oPage, $aExtraParams));
	}
	
	public function GetRenderContent(WebPage $oPage, $aExtraParams = array())
	{
		$sHtml = '';
		// Add the extra params into the filter if they make sense for such a filter
		$bDoSearch = utils::ReadParam('dosearch', false);
		if ($this->m_oSet == null)
		{
			if ($this->m_sStyle != 'links')
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
				$sLabels = array();
				while($oObj = $this->m_oSet->Fetch())
				{
					$sValue = $oObj->Get($sGroupByField);
					$aGroupBy[$sValue] = isset($aGroupBy[$sValue]) ? $aGroupBy[$sValue]+1 : 1;
					$sLabels[$sValue] = $oObj->GetAsHtml($sGroupByField);
				}
				$sFilter = urlencode($this->m_oFilter->serialize());
				$aData = array();
				$oAppContext = new ApplicationContext();
				$sParams = $oAppContext->GetForLink();
				foreach($aGroupBy as $sValue => $iCount)
				{
					$aData[] = array ( 'group' => $sLabels[$sValue],
									  'value' => "<a href=\"./UI.php?operation=search&dosearch=1&$sParams&filter=$sFilter&$sGroupByField=".urlencode($sValue)."\">$iCount</a>"); // TO DO: add the context information
				}
				$sHtml .= $oPage->GetTable(array('group' => array('label' => MetaModel::GetLabel($this->m_oFilter->GetClass(), $sGroupByField), 'description' => ''), 'value' => array('label'=> Dict::S('UI:GroupBy:Count'), 'description' => Dict::S('UI:GroupBy:Count+'))), $aData);
			}
			else
			{
				// Simply count the number of elements in the set
				$iCount = $oSet->Count();
				$sHtml .= $oPage->GetP(Dict::Format('UI:CountOfObjects', $iCount));
			}
			
			break;
			
			case 'join':
			$aDisplayAliases = isset($aExtraParams['display_aliases']) ? explode(',', $aExtraParams['display_aliases']): array();
			if (!isset($aExtraParams['group_by']))
			{
				$sHtml .= $oPage->GetP(Dict::S('UI:Error:MandatoryTemplateParameter_group_by'));
			}
			else
			{
				$aGroupByFields = array();
				$aGroupBy = explode(',', $aExtraParams['group_by']);
				foreach($aGroupBy as $sGroupBy)
				{
					$aMatches = array();
					if (preg_match('/^(.+)\.(.+)$/', $sGroupBy, $aMatches) > 0)
					{
						$aGroupByFields[] = array('alias' => $aMatches[1], 'att_code' => $aMatches[2]);
					}
				}
				if (count($aGroupByFields) == 0)
				{
					$sHtml .= $oPage->GetP(Dict::Format('UI:Error:InvalidGroupByFields', $aExtraParams['group_by']));
				}
				else
				{
					$aResults = array();
					$aCriteria = array();
					while($aObjects = $this->m_oSet->FetchAssoc())
					{
						$aKeys = array();
						foreach($aGroupByFields as $aField)
						{
							$aKeys[$aField['alias'].'.'.$aField['att_code']] = $aObjects[$aField['alias']]->Get($aField['att_code']);
						}
						$sCategory = implode($aKeys, ' ');
						$aResults[$sCategory][] = $aObjects;
						$aCriteria[$sCategory] = $aKeys;						
					}

					$sHtml .= "<table>\n";
					// Construct a new (parametric) query that will return the content of this block
					$oBlockFilter = clone $this->m_oFilter;
					$aExpressions = array();
					$index = 0;
					foreach($aGroupByFields as $aField)
					{
						$aExpressions[] = '`'.$aField['alias'].'`.`'.$aField['att_code'].'` = :param'.$index++;
					}
					$sExpression = implode(' AND ', $aExpressions);
					$oExpression = Expression::FromOQL($sExpression);
					$oBlockFilter->AddConditionExpression($oExpression);
					$aExtraParams['menu'] = false;
					foreach($aResults as $sCategory => $aObjects)
					{
						$sHtml .= "<tr><td><h1>$sCategory</h1></td></tr>\n";
						if (count($aDisplayAliases) == 1)
						{
							$aSimpleArray = array();
							foreach($aObjects as $aRow)
							{
								$aSimpleArray[] = $aRow[$aDisplayAliases[0]];
							}
							$oSet = CMDBObjectSet::FromArray($this->m_oFilter->GetClass(), $aSimpleArray);
							$sHtml .= "<tr><td>".cmdbAbstractObject::GetDisplaySet($oPage, $oSet, $aExtraParams)."</td></tr>\n";
						}
						else
						{
							$index = 0;
							$aArgs = array();
							foreach($aGroupByFields as $aField)
							{
								$aArgs['param'.$index] = $aCriteria[$sCategory][$aField['alias'].'.'.$aField['att_code']];
								$index++;
							}
							$oSet = new CMDBObjectSet($oBlockFilter, array(), $aArgs);
							$sHtml .= "<tr><td>".cmdbAbstractObject::GetDisplayExtendedSet($oPage, $oSet, $aExtraParams)."</td></tr>\n";
						}
					}				
					$sHtml .= "</table>\n";
				}
			}
			break;

			case 'list':
			$aClasses = $this->m_oSet->GetSelectedClasses();
			$aAuthorizedClasses = array();
			if (count($aClasses) > 1)
			{
				// Check the classes that can be read (i.e authorized) by this user...
				foreach($aClasses as $sAlias => $sClassName)
				{
					if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES)
					{
						$aAuthorizedClasses[$sAlias] = $sClassName;
					}
				}
				if (count($aAuthorizedClasses) > 0)
				{
					if($this->m_oSet->Count() > 0)
					{
						$sHtml .= cmdbAbstractObject::GetDisplayExtendedSet($oPage, $this->m_oSet, $aExtraParams);
					}
					else
					{
						// Empty set	
						$sHtml .= $oPage->GetP(Dict::S('UI:NoObjectToDisplay'));					
					}
				}
				else
				{
					// Not authorized
					$sHtml .= $oPage->GetP(Dict::S('UI:NoObjectToDisplay'));					
				}
			}
			else
			{
				// The list is made of only 1 class of objects, actions on the list are possible
				if ( ($this->m_oSet->Count()> 0) && (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES) )
				{
					$sHtml .= cmdbAbstractObject::GetDisplaySet($oPage, $this->m_oSet, $aExtraParams);
				}
				else
				{
					$sHtml .= $oPage->GetP(Dict::S('UI:NoObjectToDisplay'));
					$sClass = $this->m_oFilter->GetClass();
					$bDisplayMenu = isset($aExtraParams['menu']) ? $aExtraParams['menu'] == true : true; 
					if ($bDisplayMenu)
					{
						if ((UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
							&& !MetaModel::IsReadOnlyClass($sClass))
						{
							$oAppContext = new ApplicationContext();
							$sParams = $oAppContext->GetForLink();
							// 1:n links, populate the target object as a default value when creating a new linked object
							if (isset($aExtraParams['target_attr']))
							{
								$aExtraParams['default'][$aExtraParams['target_attr']] = $aExtraParams['object_id'];
							}
							$sDefault = '';
							if (!empty($aExtraParams['default']))
							{
								foreach($aExtraParams['default'] as $sKey => $sValue)
								{
									$sDefault.= "&default[$sKey]=$sValue";
								}
							}
							
							$sHtml .= $oPage->GetP("<a href=\"./UI.php?operation=new&class=$sClass&$sParams{$sDefault}\">".Dict::Format('UI:ClickToCreateNew', Metamodel::GetName($sClass))."</a>\n");
						}
					}
				}
			}
			break;
			
			case 'links':
			//$bDashboardMode = isset($aExtraParams['dashboard']) ? ($aExtraParams['dashboard'] == 'true') : false;
			//$bSelectMode = isset($aExtraParams['select']) ? ($aExtraParams['select'] == 'true') : false;
			if ( ($this->m_oSet->Count()> 0) && (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES) )
			{
				//$sLinkage = isset($aExtraParams['linkage']) ? $aExtraParams['linkage'] : '';
				$sHtml .= cmdbAbstractObject::GetDisplaySet($oPage, $this->m_oSet, $aExtraParams);
			}
			else
			{
				$sClass = $this->m_oFilter->GetClass();
				$oAttDef = MetaModel::GetAttributeDef($sClass, $this->m_aParams['target_attr']);
				$sTargetClass = $oAttDef->GetTargetClass();
				$sHtml .= $oPage->GetP(Dict::Format('UI:NoObject_Class_ToDisplay', MetaModel::GetName($sTargetClass)));
				$bDisplayMenu = isset($this->m_aParams['menu']) ? $this->m_aParams['menu'] == true : true; 
				if ($bDisplayMenu)
				{
					if ((UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)
						&& (!MetaModel::IsReadOnlyClass($sClass)))
					{
						$oAppContext = new ApplicationContext();
						$sParams = $oAppContext->GetForLink();
						$sHtml .= $oPage->GetP("<a href=\"../pages/UI.php?operation=modify_links&class=$sClass&sParams&link_attr=".$aExtraParams['link_attr']."&id=".$aExtraParams['object_id']."&target_class=$sTargetClass&addObjects=true\">".Dict::Format('UI:ClickToCreateNew', Metamodel::GetName($sClass))."</a>\n");
					}
				}
			}
			break;
			
			case 'details':
			if (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES)
			{
				while($oObj = $this->m_oSet->Fetch())
				{
					$sHtml .= $oObj->GetDetails($oPage); // Still used ???
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
				$sHtml .= "<textarea style=\"width:95%;height:98%\">\n";
				$sHtml .= cmdbAbstractObject::GetSetAsCSV($this->m_oSet);
				$sHtml .= "</textarea>\n";
			}
			break;

			case 'modify':
			if ((UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_MODIFY, $this->m_oSet) == UR_ALLOWED_YES)
				&& !MetaModel::IsReadOnlyClass($this->m_oSet->GetClass()))
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
			$oPage->add_ready_script("\$(\"#LnkSearch_$iSearchSectionId\").click(function() {\$(\"#Search_$iSearchSectionId\").slideToggle('normal'); $(\"#LnkSearch_$iSearchSectionId\").toggleClass('open');});");
			$sHtml .= cmdbAbstractObject::GetSearchForm($oPage, $this->m_oSet, $aExtraParams);
	 		$sHtml .= "</div>\n";
	 		$sHtml .= "<div class=\"HRDrawer\"></div>\n";
	 		$sHtml .= "<div id=\"LnkSearch_$iSearchSectionId\" class=\"DrawerHandle\">".Dict::S('UI:SearchToggle')."</div>\n";
			break;
			
			case 'open_flash_chart':
			static $iChartCounter = 0;
			$sChartType = isset($aExtraParams['chart_type']) ? $aExtraParams['chart_type'] : 'pie';
			$sTitle = isset($aExtraParams['chart_title']) ? $aExtraParams['chart_title'] : '';
			$sGroupBy = isset($aExtraParams['group_by']) ? $aExtraParams['group_by'] : '';
			$sFilter = $this->m_oFilter->ToOQL();
			$sHtml .= "<div id=\"my_chart_{$iChartCounter}\">If the chart does not display, <a href=\"http://get.adobe.com/flash/\" target=\"_blank\">install Flash</a></div>\n";
			$oPage->add_script("function ofc_resize(left, width, top, height) { /* do nothing special */ }");
			$oPage->add_ready_script("swfobject.embedSWF(\"../images/open-flash-chart.swf\", \"my_chart_{$iChartCounter}\", \"100%\", \"300\",\"9.0.0\", \"expressInstall.swf\",
			{\"data-file\":\"".urlencode("../pages/ajax.render.php?operation=open_flash_chart&params[group_by]=$sGroupBy&params[chart_type]=$sChartType&params[chart_title]=$sTitle&encoding=oql&filter=".urlencode($sFilter))."\"});\n");
			$iChartCounter++;
			break;
			
			case 'open_flash_chart_ajax':
			include '../pages/php-ofc-library/open-flash-chart.php';
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
				$oChartElement->set_colours( array('#FF8A00', '#909980', '#2C2B33', '#CCC08D', '#596664') );
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
						$aData[] = new pie_value($iValue, $sValue); //@@ BUG: not passed via ajax !!!
					}
	
	
					$oChartElement->set_values( $aData );
					$oChart->x_axis = null;
				}
			}				
			if (isset($aExtraParams['chart_title']))
			{
				$oTitle = new title( Dict::S($aExtraParams['chart_title']) );
				$oChart->set_title( $oTitle );
			}
			$oChart->set_bg_colour('#FFFFFF');
			$oChart->add_element( $oChartElement );
			
			$sHtml = $oChart->toPrettyString();
			break;
			
			default:
			// Unsupported style, do nothing.
			$sHtml .= Dict::format('UI:Error:UnsupportedStyleOfBlock', $this->m_sStyle);
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
	public function GetRenderContent(WebPage $oPage, $aExtraParams = array())
	{
		$sHtml = '';
		$oSet = new CMDBObjectSet($this->m_oFilter, array('date'=>false));
		$sHtml .= "<!-- filter: ".($this->m_oFilter->ToOQL())."-->\n";
		switch($this->m_sStyle)
		{
			case 'toggle':
			// First the latest change that the user is allowed to see
			do
			{
				$oLatestChangeOp = $oSet->Fetch();
			}
			while(is_object($oLatestChangeOp) && ($oLatestChangeOp->GetDescription() == ''));
			
			if (is_object($oLatestChangeOp))
			{
				global $oContext; // User Context.. should be statis instead of global...
				// There is one change in the list... only when the object has been created !
				$sDate = $oLatestChangeOp->GetAsHTML('date');
				$oChange = $oContext->GetObject('CMDBChange', $oLatestChangeOp->Get('change'));
				$sUserInfo = $oChange->GetAsHTML('userinfo');
				$oSet->Rewind(); // Reset the pointer to the beginning of the set
				$sHtml .= $oPage->GetStartCollapsibleSection(Dict::Format('UI:History:LastModified_On_By', $sDate, $sUserInfo));
				//$sHtml .= cmdbAbstractObject::GetDisplaySet($oPage, $oSet);
				$aChanges = array();
				while($oChangeOp = $oSet->Fetch())
				{
					$sChangeDescription = $oChangeOp->GetDescription();
					if ($sChangeDescription != '')
					{
						// The change is visible for the current user
						$changeId = $oChangeOp->Get('change');
						$aChanges[$changeId]['date'] = $oChangeOp->Get('date');
						$aChanges[$changeId]['userinfo'] = $oChangeOp->Get('userinfo');
						if (!isset($aChanges[$changeId]['log']))
						{
							$aChanges[$changeId]['log'] = array();
						}
						$aChanges[$changeId]['log'][] = $sChangeDescription;
					}
				}
				$aAttribs = array('date' => array('label' => Dict::S('UI:History:Date'), 'description' => Dict::S('UI:History:Date+')),
								  'userinfo' => array('label' => Dict::S('UI:History:User'), 'description' => Dict::S('UI:History:User+')),
								  'log' => array('label' => Dict::S('UI:History:Changes'), 'description' => Dict::S('UI:History:Changes+')),
								 );
				$aValues = array();
				foreach($aChanges as $aChange)
				{
					$aValues[] = array('date' => $aChange['date'], 'userinfo' => $aChange['userinfo'], 'log' => "<ul><li>".implode('</li><li>', $aChange['log'])."</li></ul>");
				}
				$sHtml .= $oPage->GetTable($aAttribs, $aValues);		
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
	public function GetRenderContent(WebPage $oPage, $aExtraParams = array())
	{
		$sHtml = '';
		$oAppContext = new ApplicationContext();
		$sContext = $oAppContext->GetForLink();
		$sClass = $this->m_oFilter->GetClass();
		$oSet = new CMDBObjectSet($this->m_oFilter);
		$sFilter = $this->m_oFilter->serialize();
		$aActions = array();
		$sUIPage = cmdbAbstractObject::ComputeUIPage($sClass);
		// 1:n links, populate the target object as a default value when creating a new linked object
		if (isset($aExtraParams['target_attr']))
		{
			$aExtraParams['default'][$aExtraParams['target_attr']] = $aExtraParams['object_id'];
		}
		$sDefault = '';
		if (!empty($aExtraParams['default']))
		{
			foreach($aExtraParams['default'] as $sKey => $sValue)
			{
				$sDefault.= "&default[$sKey]=$sValue";
			}
		}
		switch($oSet->Count())
		{
			case 0:
			// No object in the set, the only possible action is "new"
			$bIsModifyAllowed = (!MetaModel::IsAbstract($sClass)) && (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES) && !MetaModel::IsReadOnlyClass($sClass);
			if ($bIsModifyAllowed) { $aActions[] = array ('label' => Dict::S('UI:Menu:New'), 'url' => "../page/$sUIPage?operation=new&class=$sClass&$sContext{$sDefault}"); }
			break;
			
			case 1:
			$oObj = $oSet->Fetch();
			$id = $oObj->GetKey();
			$bIsModifyAllowed = (!MetaModel::IsAbstract($sClass)) && (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_YES) && !MetaModel::IsReadOnlyClass($sClass);
			$bIsDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, $oSet) && !MetaModel::IsReadOnlyClass($sClass);
			$bIsBulkModifyAllowed = (!MetaModel::IsAbstract($sClass)) && UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY, $oSet) && !MetaModel::IsReadOnlyClass($sClass);
			$bIsBulkDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, $oSet) && !MetaModel::IsReadOnlyClass($sClass);
			// Just one object in the set, possible actions are "new / clone / modify and delete"
			if (isset($aExtraParams['link_attr']))
			{
				$id = $aExtraParams['object_id'];
				$sTargetAttr = $aExtraParams['target_attr'];
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sTargetAttr);
				$sTargetClass = $oAttDef->GetTargetClass();
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => Dict::S('UI:Menu:Add'), 'url' => "../pages/$sUIPage?operation=modify_links&class=$sClass&link_attr=".$aExtraParams['link_attr']."&target_class=$sTargetClass&id=$id&addObjects=true&$sContext"); }
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => Dict::S('UI:Menu:Manage'), 'url' => "../pages/$sUIPage?operation=modify_links&class=$sClass&link_attr=".$aExtraParams['link_attr']."&target_class=$sTargetClass&id=$id&sContext"); }
				//if ($bIsDeleteAllowed) { $aActions[] = array ('label' => 'Remove All', 'url' => "#"); }
			}
			else
			{
				$sUrl = utils::GetAbsoluteUrl();
				$aActions[] = array ('label' => Dict::S('UI:Menu:EMail'), 'url' => "mailto:?subject=".$oSet->GetFilter()->__DescribeHTML()."&body=".urlencode("$sUrl?operation=search&filter=$sFilter&$sContext"));
				$aActions[] = array ('label' => Dict::S('UI:Menu:CSVExport'), 'url' => "../pages/$sUIPage?operation=search&filter=$sFilter&format=csv&$sContext");
				//$aActions[] = array ('label' => 'Bookmark...', 'url' => "../pages/ajax.render.php?operation=create&class=$sClass&filter=$sFilter", 'class' => 'jqmTrigger');
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => Dict::S('UI:Menu:New'), 'url' => "../pages/$sUIPage?operation=new&class=$sClass&$sContext{$sDefault}"); }
				//if ($bIsModifyAllowed) { $aActions[] = array ('label' => 'Clone...', 'url' => "../pages/$sUIPage?operation=clone&class=$sClass&id=$id&$sContext"); }
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => Dict::S('UI:Menu:Modify'), 'url' => "../pages/$sUIPage?operation=modify&class=$sClass&id=$id&$sContext"); }
				if ($bIsDeleteAllowed) { $aActions[] = array ('label' => Dict::S('UI:Menu:Delete'), 'url' => "../pages/$sUIPage?operation=delete&class=$sClass&id=$id&$sContext"); }
			}
			$aTransitions = $oObj->EnumTransitions();
			$aStimuli = Metamodel::EnumStimuli($sClass);
			foreach($aTransitions as $sStimulusCode => $aTransitionDef)
			{
				$iActionAllowed = UserRights::IsStimulusAllowed($sClass, $sStimulusCode, $oSet);
				switch($iActionAllowed)
				{
					case UR_ALLOWED_YES:
					$aActions[] = array('label' => $aStimuli[$sStimulusCode]->GetLabel(), 'url' => "../pages/UI.php?operation=stimulus&stimulus=$sStimulusCode&class=$sClass&id=$id&$sContext");
					break;
					
					case UR_ALLOWED_DEPENDS:
					$aActions[] = array('label' => $aStimuli[$sStimulusCode]->GetLabel().' (*)', 'url' => "../pages/UI.php?operation=stimulus&stimulus=$sStimulusCode&class=$sClass&id=$id&$sContext");
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
			$bIsModifyAllowed = (!MetaModel::IsAbstract($sClass)) && UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) && !MetaModel::IsReadOnlyClass($sClass);
			$bIsBulkModifyAllowed = (!MetaModel::IsAbstract($sClass)) && UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY, $oSet) && !MetaModel::IsReadOnlyClass($sClass);
			$bIsBulkDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, $oSet) && !MetaModel::IsReadOnlyClass($sClass);
			if (isset($aExtraParams['link_attr']))
			{
				$id = $aExtraParams['object_id'];
				$sTargetAttr = $aExtraParams['target_attr'];
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sTargetAttr);
				$sTargetClass = $oAttDef->GetTargetClass();
				$bIsDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, $oSet) && !MetaModel::IsReadOnlyClass($sClass);
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => Dict::S('UI:Menu:Add'), 'url' => "../pages/$sUIPage?operation=modify_links&class=$sClass&link_attr=".$aExtraParams['link_attr']."&target_class=$sTargetClass&id=$id&addObjects=true&$sContext"); }
				//if ($bIsBulkModifyAllowed) { $aActions[] = array ('label' => 'Add...', 'url' => "../pages/$sUIPage?operation=modify_links&class=$sClass&linkage=".$aExtraParams['linkage']."&id=$id&addObjects=true&$sContext"); }
				if ($bIsBulkModifyAllowed) { $aActions[] = array ('label' => Dict::S('UI:Menu:Manage'), 'url' => "../pages/$sUIPage?operation=modify_links&class=$sClass&link_attr=".$aExtraParams['link_attr']."&target_class=$sTargetClass&id=$id&sContext"); }
				//if ($bIsBulkDeleteAllowed) { $aActions[] = array ('label' => 'Remove All...', 'url' => "#"); }
			}
			else
			{
				// many objects in the set, possible actions are: new / modify all / delete all
				$sUrl = utils::GetAbsoluteUrl();
				$aActions[] = array ('label' => Dict::S('UI:Menu:EMail'), 'url' => "mailto:?subject=".$oSet->GetFilter()->__DescribeHTML()."&body=".urlencode("$sUrl?operation=search&filter=$sFilter&$sContext"));
				$aActions[] = array ('label' => Dict::S('UI:Menu:CSVExport'), 'url' => "../pages/$sUIPage?operation=search&filter=$sFilter&format=csv&$sContext");
				//$aActions[] = array ('label' => 'Bookmark...', 'url' => "../pages/ajax.render.php?operation=create&class=$sClass&filter=$sFilter", 'class' => 'jqmTrigger');
				if ($bIsModifyAllowed) { $aActions[] = array ('label' => Dict::S('UI:Menu:New'), 'url' => "../pages/$sUIPage?operation=new&class=$sClass&$sContext{$sDefault}"); }
				//if ($bIsBulkModifyAllowed) { $aActions[] = array ('label' => 'Modify All...', 'url' => "../pages/$sUIPage?operation=modify_all&filter=$sFilter&$sContext"); }
				if ($bIsBulkDeleteAllowed) { $aActions[] = array ('label' => Dict::S('UI:Menu:BulkDelete'), 'url' => "../pages/$sUIPage?operation=select_for_deletion&filter=$sFilter&$sContext"); }
			}
		}
		$sHtml .= "<div class=\"itop_popup\"><ul>\n<li>".Dict::S('UI:Menu:Actions')."\n<ul>\n";
		foreach ($aActions as $aAction)
		{
			$sClass = isset($aAction['class']) ? " class=\"{$aAction['class']}\"" : "";
			$sHtml .= "<li><a href=\"{$aAction['url']}\"$sClass>{$aAction['label']}</a></li>\n";
		}
		$sHtml .= "</ul>\n</li>\n</ul></div>\n";
		$oPage->add_ready_script("$(\"div.itop_popup>ul\").popupmenu();\n");
		return $sHtml;
	}	
}
?>
