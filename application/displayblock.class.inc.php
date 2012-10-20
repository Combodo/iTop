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

require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
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
	protected $m_aConditions; // Conditions added to the filter -> avoid duplicate conditions
	protected $m_sStyle;
	protected $m_bAsynchronous;
	protected $m_aParams;
	protected $m_oSet;
	
	public function __construct(DBObjectSearch $oFilter, $sStyle = 'list', $bAsynchronous = false, $aParams = array(), $oSet = null)
	{
		$this->m_oFilter = clone $oFilter;
		$this->m_aConditions = array();
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
		$aKeys = array();
		while($oObject = $oSet->Fetch())
		{
			$aKeys[] = $oObject->GetKey();	
		}
		$oSet->Rewind();
		$oDummyFilter->AddCondition('id', $aKeys, 'IN');
		$oBlock = new DisplayBlock($oDummyFilter, $sStyle, false, $aParams); // DisplayBlocks built this way are synchronous
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
	}
	
	public function GetDisplay(WebPage $oPage, $sId, $aExtraParams = array())
	{
		$sHtml = '';
		$aExtraParams = array_merge($aExtraParams, $this->m_aParams);
		$aExtraParams['currentId'] = $sId;
		$sExtraParams = addslashes(str_replace('"', "'", json_encode($aExtraParams))); // JSON encode, change the style of the quotes and escape them
		
		$bAutoReload = false;
		if (isset($aExtraParams['auto_reload']))
		{
			switch($aExtraParams['auto_reload'])
			{
				case 'fast':
				$bAutoReload = true;
				$iReloadInterval = MetaModel::GetConfig()->GetFastReloadInterval()*1000;
				break;
				
				case 'standard':
				case 'true':
				case true:
				$bAutoReload = true;
				$iReloadInterval = MetaModel::GetConfig()->GetStandardReloadInterval()*1000;
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
			$sHtml .= $this->GetRenderContent($oPage, $aExtraParams, $sId);
			$sHtml .= "</div>\n";
		}
		else
		{
			// render it as an Ajax (asynchronous) call
			$sHtml .= "<div id=\"$sId\" class=\"display_block loading\">\n";
			$sHtml .= $oPage->GetP("<img src=\"../images/indicator_arrows.gif\"> ".Dict::S('UI:Loading'));
			$sHtml .= "</div>\n";
			$oPage->add_script('
			$.post("ajax.render.php?style='.$this->m_sStyle.'",
			   { operation: "ajax", filter: "'.$sFilter.'", extra_params: "'.$sExtraParams.'" },
			   function(data){
				 $("#'.$sId.'").empty();
				 $("#'.$sId.'").append(data);
				 $("#'.$sId.'").removeClass("loading");
				}
			 );
			 ');
		}
		if ($bAutoReload)
		{
			$oPage->add_script('setInterval("ReloadBlock(\''.$sId.'\', \''.$this->m_sStyle.'\', \''.$sFilter.'\', \"'.$sExtraParams.'\")", '.$iReloadInterval.');');
		}
		return $sHtml;
	}
	
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		if (!isset($aExtraParams['currentId']))
		{
			$sId = $oPage->GetUniqueId(); // Works only if the page is not an Ajax one !
		}
		else
		{
			$sId = $aExtraParams['currentId'];
		}
		$oPage->add($this->GetRenderContent($oPage, $aExtraParams, $sId));
	}
	
	public function GetRenderContent(WebPage $oPage, $aExtraParams = array(), $sId)
	{
		$sHtml = '';
		// Add the extra params into the filter if they make sense for such a filter
		$bDoSearch = utils::ReadParam('dosearch', false);
		if ($this->m_oSet == null)
		{
			$aQueryParams = array();
			if (isset($aExtraParams['query_params']))
			{
				$aQueryParams = $aExtraParams['query_params'];
			}
			if ($this->m_sStyle != 'links')
			{
				$oAppContext = new ApplicationContext();
				$sClass = $this->m_oFilter->GetClass();
				$aFilterCodes = array_keys(MetaModel::GetClassFilterDefs($sClass));
				$aCallSpec = array($sClass, 'MapContextParam');
				if (is_callable($aCallSpec))
				{
					foreach($oAppContext->GetNames() as $sContextParam)
					{
						$sParamCode = call_user_func($aCallSpec, $sContextParam); //Map context parameter to the value/filter code depending on the class
						if (!is_null($sParamCode))
						{
							$sParamValue = $oAppContext->GetCurrentValue($sContextParam, null);
							if (!is_null($sParamValue))
							{
								$aExtraParams[$sParamCode] = $sParamValue;
							}
						}
					}
				}
				foreach($aFilterCodes as $sFilterCode)
				{
					$externalFilterValue = utils::ReadParam($sFilterCode, '', false, 'raw_data');
					$condition = null;
					if (isset($aExtraParams[$sFilterCode]))
					{
						$condition = $aExtraParams[$sFilterCode];
					}
					if ($bDoSearch && $externalFilterValue != "")
					{
						// Search takes precedence over context params...
						unset($aExtraParams[$sFilterCode]);
						if (!is_array($externalFilterValue))
						{
							$condition = trim($externalFilterValue);
						}
						else if (count($externalFilterValue) == 1)
						{
							$condition = trim($externalFilterValue[0]);
						}
						else
						{
							$condition = $externalFilterValue;
						}
					}

					if (!is_null($condition))
					{
						$sOpCode = null; // default operator
						if (is_array($condition))
						{
							// Multiple values, add them as AND X IN (v1, v2, v3...)
							$sOpCode = 'IN';
						}

						$this->AddCondition($sFilterCode, $condition, $sOpCode);
					}
				}
				if ($bDoSearch)
				{
					// Keep the table_id identifying this table if we're performing a search
					$sTableId = utils::ReadParam('_table_id_', null, false, 'raw_data');
					if ($sTableId != null)
					{
						$aExtraParams['table_id'] = $sTableId;
					}
				}
			}
			$aOrderBy = array();
			if (isset($aExtraParams['order_by']))
			{
				// Convert the string describing the order_by parameter into an array
				// The syntax is +attCode1,-attCode2
				// attCode1 => ascending, attCode2 => descending
				$aTemp = explode(',', $aExtraParams['order_by']);
				foreach($aTemp as $sTemp)
				{
					$aMatches = array();
					if (preg_match('/^([+-])?(.+)$/', $sTemp, $aMatches))
					{
						$bAscending = true;
						if ($aMatches[1] == '-')
						{
							$bAscending  = false;
						}
						$aOrderBy[$aMatches[2]] = $bAscending;
					}					
				}
			}
			
			$this->m_oSet = new CMDBObjectSet($this->m_oFilter, $aOrderBy, $aQueryParams);
		}
		switch($this->m_sStyle)
		{
			case 'count':
			if (isset($aExtraParams['group_by']))
			{
				if (isset($aExtraParams['group_by_label']))
				{
					$oGroupByExp = Expression::FromOQL($aExtraParams['group_by']);
					$sGroupByLabel = $aExtraParams['group_by_label'];
				}
				else
				{
					// Backward compatibility: group_by is simply a field id
					$sAlias = $this->m_oFilter->GetClassAlias();
					$oGroupByExp = new FieldExpression($aExtraParams['group_by'], $sAlias);
					$sGroupByLabel = MetaModel::GetLabel($this->m_oFilter->GetClass(), $aExtraParams['group_by']);
				}

				$aGroupBy = array();
				$aGroupBy['grouped_by_1'] = $oGroupByExp;
				$sSql = MetaModel::MakeGroupByQuery($this->m_oFilter, $aQueryParams, $aGroupBy);
				$aRes = CMDBSource::QueryToArray($sSql);

				$aGroupBy = array();
				$aLabels = array();
				$aValues = array();
				$iTotalCount = 0;
				foreach ($aRes as $iRow => $aRow)
				{
					$sValue = $aRow['grouped_by_1'];
					$aValues[$iRow] = $sValue;
					$sHtmlValue = $oGroupByExp->MakeValueLabel($this->m_oFilter, $sValue, $sValue);
					$aLabels[$iRow] = $sHtmlValue;
					$aGroupBy[$iRow] = (int) $aRow['_itop_count_'];
					$iTotalCount += $aRow['_itop_count_'];
				}


				$aData = array();
				$oAppContext = new ApplicationContext();
				$sParams = $oAppContext->GetForLink();
				foreach($aGroupBy as $iRow => $iCount)
				{
					// Build the search for this subset
					$oSubsetSearch = clone $this->m_oFilter;
					$oCondition = new BinaryExpression($oGroupByExp, '=', new ScalarExpression($aValues[$iRow]));
					$oSubsetSearch->AddConditionExpression($oCondition);
					$sFilter = urlencode($oSubsetSearch->serialize());

					$aData[] = array ( 'group' => $aLabels[$iRow],
									  'value' => "<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search&dosearch=1&$sParams&filter=$sFilter\">$iCount</a>"); // TO DO: add the context information
				}
				$aAttribs =array(
					'group' => array('label' => $sGroupByLabel, 'description' => ''),
					'value' => array('label'=> Dict::S('UI:GroupBy:Count'), 'description' => Dict::S('UI:GroupBy:Count+'))
				);
				$sFormat = isset($aExtraParams['format']) ? $aExtraParams['format'] : 'UI:Pagination:HeaderNoSelection';
				$sHtml .= $oPage->GetP(Dict::Format($sFormat, $iTotalCount));
				$sHtml .= $oPage->GetTable($aAttribs, $aData);
			}
			else
			{
				// Simply count the number of elements in the set
				$iCount = $this->m_oSet->Count();
				$sFormat = 'UI:CountOfObjects';
				if (isset($aExtraParams['format']))
				{
					$sFormat = $aExtraParams['format'];
				}
				$sHtml .= $oPage->GetP(Dict::Format($sFormat, $iCount));
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
							$sAlias = $aField['alias'];
							if (is_null($aObjects[$sAlias]))
							{
								$aKeys[$sAlias.'.'.$aField['att_code']] = '';
							}
							else
							{
								$aKeys[$sAlias.'.'.$aField['att_code']] = $aObjects[$sAlias]->Get($aField['att_code']);
							}
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
								$oObj = $aRow[$aDisplayAliases[0]];
								if (!is_null($oObj))
								{
									$aSimpleArray[] = $oObj;
								}
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
					if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ,  $this->m_oSet) && (UR_ALLOWED_YES || UR_ALLOWED_DEPENDS))
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
						if ((UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES))
						{
							$sLinkTarget = '';
							$oAppContext = new ApplicationContext();
							$sParams = $oAppContext->GetForLink();
							// 1:n links, populate the target object as a default value when creating a new linked object
							if (isset($aExtraParams['target_attr']))
							{
								$sLinkTarget = ' target="_blank" ';
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
							
							$sHtml .= $oPage->GetP("<a{$sLinkTarget} href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=new&class=$sClass&$sParams{$sDefault}\">".Dict::Format('UI:ClickToCreateNew', Metamodel::GetName($sClass))."</a>\n");
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
					if ((UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES))
					{
						$oAppContext = new ApplicationContext();
						$sParams = $oAppContext->GetForLink();
						$sDefaults = '';
						if (isset($this->m_aParams['default']))
						{
							foreach($this->m_aParams['default'] as $sName => $sValue)
							{
								$sDefaults .= '&'.urlencode($sName).'='.urlencode($sValue);
							}
						}
						$sHtml .= $oPage->GetP("<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=modify_links&class=$sClass&sParams&link_attr=".$aExtraParams['link_attr']."&id=".$aExtraParams['object_id']."&target_class=$sTargetClass&addObjects=true$sDefaults\">".Dict::Format('UI:ClickToCreateNew', Metamodel::GetName($sClass))."</a>\n");
					}
				}
			}
			break;
			
			case 'details':
			while($oObj = $this->m_oSet->Fetch())
			{
				$sHtml .= $oObj->GetDetails($oPage); // Still used ???
			}
			break;
			
			case 'actions':
			$sClass = $this->m_oFilter->GetClass();
			$oAppContext = new ApplicationContext();
			$bContextFilter = isset($aExtraParams['context_filter']) ? isset($aExtraParams['context_filter']) != 0 : false;
			if ($bContextFilter)
			{
				$aFilterCodes = array_keys(MetaModel::GetClassFilterDefs($this->m_oFilter->GetClass()));
				foreach($oAppContext->GetNames() as $sFilterCode)
				{
					$sContextParamValue = $oAppContext->GetCurrentValue($sFilterCode, null);
					if (!is_null($sContextParamValue) && ! empty($sContextParamValue) && MetaModel::IsValidFilterCode($sClass, $sFilterCode))
					{
						$this->AddCondition($sFilterCode, $sContextParamValue);
					}
				}
				$aQueryParams = array();
				if (isset($aExtraParams['query_params']))
				{
					$aQueryParams = $aExtraParams['query_params'];
				}
				$this->m_oSet = new CMDBObjectSet($this->m_oFilter, array(), $aQueryParams);				
			}
			$iCount = $this->m_oSet->Count();
			$sHyperlink = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&'.$oAppContext->GetForLink().'&filter='.urlencode($this->m_oFilter->serialize());
			$sHtml .= '<p><a class="actions" href="'.$sHyperlink.'">';
			// Note: border set to 0 due to various browser interpretations (IE9 adding a 2px border)
			$sHtml .= MetaModel::GetClassIcon($sClass, true, 'float;left;margin-right:10px;border:0;');
			$sHtml .= MetaModel::GetName($sClass).': '.$iCount.'</a></p>';
			$sParams = $oAppContext->GetForLink();
			$sHtml .= '<p>';
			if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY))
			{
				$sHtml .= "<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=new&class={$sClass}&$sParams\">".Dict::Format('UI:ClickToCreateNew', MetaModel::GetName($sClass))."</a><br/>\n";
			}
			$sHtml .= "<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search_form&class={$sClass}&$sParams\">".Dict::Format('UI:SearchFor_Class', MetaModel::GetName($sClass))."</a>\n";
			$sHtml .= '</p>';
			break;

			case 'summary':
			$sClass = $this->m_oFilter->GetClass();
			$oAppContext = new ApplicationContext();
			$sTitle = isset($aExtraParams['title[block]']) ? $aExtraParams['title[block]'] : '';
			$sLabel = isset($aExtraParams['label[block]']) ? $aExtraParams['label[block]'] : '';
			$sStateAttrCode = isset($aExtraParams['status[block]']) ? $aExtraParams['status[block]'] : 'status';
			$sStatesList = isset($aExtraParams['status_codes[block]']) ? $aExtraParams['status_codes[block]'] : '';
			
			$bContextFilter = isset($aExtraParams['context_filter']) ? isset($aExtraParams['context_filter']) != 0 : false;
			if ($bContextFilter)
			{
				$aFilterCodes = array_keys(MetaModel::GetClassFilterDefs($this->m_oFilter->GetClass()));
				foreach($oAppContext->GetNames() as $sFilterCode)
				{
					$sContextParamValue = $oAppContext->GetCurrentValue($sFilterCode, null);
					if (!is_null($sContextParamValue) && ! empty($sContextParamValue) && MetaModel::IsValidFilterCode($sClass, $sFilterCode))
					{
						$this->AddCondition($sFilterCode, $sContextParamValue);
					}
				}
				$aQueryParams = array();
				if (isset($aExtraParams['query_params']))
				{
					$aQueryParams = $aExtraParams['query_params'];
				}
				$this->m_oSet = new CMDBObjectSet($this->m_oFilter, array(), $aQueryParams);				
			}
			// Summary details
			$aCounts = array();
			$aStateLabels = array();
			if (!empty($sStateAttrCode) && !empty($sStatesList))
			{
				$aStates = explode(',', $sStatesList);
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sStateAttrCode);
				foreach($aStates as $sStateValue)
				{
					$oFilter = clone($this->m_oFilter);
					$oFilter->AddCondition($sStateAttrCode, $sStateValue, '=');
					$oSet = new DBObjectSet($oFilter);
					$aCounts[$sStateValue] = $oSet->Count();
					$aStateLabels[$sStateValue] = htmlentities($oAttDef->GetValueLabel($sStateValue), ENT_QUOTES, 'UTF-8');
					if ($aCounts[$sStateValue] == 0)
					{
						$aCounts[$sStateValue] = '-';
					}
					else
					{
						$sHyperlink = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&'.$oAppContext->GetForLink().'&filter='.urlencode($oFilter->serialize());
						$aCounts[$sStateValue] = "<a href=\"$sHyperlink\">{$aCounts[$sStateValue]}</a>";
					}
				}
			}
			$sHtml .= '<div class="summary-details"><table><tr><th>'.implode('</th><th>', $aStateLabels).'</th></tr>';
			$sHtml .= '<tr><td>'.implode('</td><td>', $aCounts).'</td></tr></table></div>';
			// Title & summary
			$iCount = $this->m_oSet->Count();
			$sHyperlink = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&'.$oAppContext->GetForLink().'&filter='.urlencode($this->m_oFilter->serialize());
			$sHtml .= '<h1>'.Dict::S(str_replace('_', ':', $sTitle)).'</h1>';
			$sHtml .= '<a class="summary" href="'.$sHyperlink.'">'.Dict::Format(str_replace('_', ':', $sLabel), $iCount).'</a>';
			break;
			
			case 'csv':
			$bAdvancedMode = utils::ReadParam('advanced', false);

			$sCsvFile = strtolower($this->m_oFilter->GetClass()).'.csv'; 
			$sDownloadLink = utils::GetAbsoluteUrlAppRoot().'webservices/export.php?expression='.urlencode($this->m_oFilter->ToOQL(true)).'&format=csv&filename='.urlencode($sCsvFile);
			$sLinkToToggle = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&'.$oAppContext->GetForLink().'&filter='.urlencode($this->m_oFilter->serialize()).'&format=csv';
			if ($bAdvancedMode)
			{
				$sDownloadLink .= '&fields_advanced=1';
				$sChecked = 'CHECKED';
			}
			else
			{
				$sLinkToToggle = $sLinkToToggle.'&advanced=1';
				$sChecked = '';
			}

			$sCSVData = cmdbAbstractObject::GetSetAsCSV($this->m_oSet, array('fields_advanced' => $bAdvancedMode));
			$sCharset = MetaModel::GetConfig()->Get('csv_file_default_charset');
			if ($sCharset == 'UTF-8')
			{
				$bLostChars = false;
			}
			else
			{
				$sConverted = @iconv('UTF-8', $sCharset, $sCSVData);
				$sRestored = @iconv($sCharset, 'UTF-8', $sConverted);
				$bLostChars = ($sRestored != $sCSVData);
			}

			if ($bLostChars)
			{
				$sCharsetNotice = "&nbsp;&nbsp;<span id=\"csv_charset_issue\">";
				$sCharsetNotice .= '<img src="../images/error.png"  style="vertical-align:middle"/>';
				$sCharsetNotice .= "</span>";

				$sTip = "<p>".htmlentities(Dict::S('UI:CSVExport:LostChars'), ENT_QUOTES, 'UTF-8')."</p>";
				$sTip .= "<p>".htmlentities(Dict::Format('UI:CSVExport:LostChars+', $sCharset), ENT_QUOTES, 'UTF-8')."</p>";
				$oPage->add_ready_script("$('#csv_charset_issue').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
			}
			else
			{
				$sCharsetNotice = '';
			}

			$sHtml .= "<div>";
			$sHtml .= '<table style="width:100%" class="transparent">';
			$sHtml .= '<tr>';
			$sHtml .= '<td><a href="'.$sDownloadLink.'">'.Dict::Format('UI:Download-CSV', $sCsvFile).'</a>'.$sCharsetNotice.'</td>';
			$sHtml .= '<td style="text-align:right"><input type="checkbox" '.$sChecked.' onClick="window.location.href=\''.$sLinkToToggle.'\'">&nbsp;'.Dict::S('UI:CSVExport:AdvancedMode').'</td>';
			$sHtml .= '</tr>';
			$sHtml .= '</table>';
			if ($bAdvancedMode)
			{
				$sHtml .= "<p>";
				$sHtml .= htmlentities(Dict::S('UI:CSVExport:AdvancedMode+'), ENT_QUOTES, 'UTF-8');
				$sHtml .= "</p>";
			}
			$sHtml .= "</div>";

			$sHtml .= "<textarea style=\"width:95%;height:98%\">\n";
			$sHtml .= htmlentities($sCSVData, ENT_QUOTES, 'UTF-8');
			$sHtml .= "</textarea>\n";
			break;

			case 'modify':
			if ((UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_MODIFY, $this->m_oSet) == UR_ALLOWED_YES))
			{
				while($oObj = $this->m_oSet->Fetch())
				{
					$sHtml .= $oObj->GetModifyForm($oPage);
				}
			}
			break;
			
			case 'search':
			$sStyle = (isset($aExtraParams['open']) && ($aExtraParams['open'] == 'true')) ? 'SearchDrawer' : 'SearchDrawer DrawerClosed';
			$sHtml .= "<div id=\"ds_$sId\" class=\"$sStyle\">\n";
			$oPage->add_ready_script(
<<<EOF
	$("#dh_$sId").click( function() {
		$("#ds_$sId").slideToggle('normal', function() { $("#ds_$sId").parent().resize(); } );
		$("#dh_$sId").toggleClass('open');
	});
EOF
			);
			$aExtraParams['currentId'] = $sId;
			$sHtml .= cmdbAbstractObject::GetSearchForm($oPage, $this->m_oSet, $aExtraParams);
	 		$sHtml .= "</div>\n";
	 		$sHtml .= "<div class=\"HRDrawer\"></div>\n";
	 		$sHtml .= "<div id=\"dh_$sId\" class=\"DrawerHandle\">".Dict::S('UI:SearchToggle')."</div>\n";
			break;
			
			case 'open_flash_chart':
			static $iChartCounter = 0;
			$oAppContext = new ApplicationContext();
			$sContext = $oAppContext->GetForLink();
			if (!empty($sContext))
			{
				$sContext = '&'.$sContext;
			}
			$sChartType = isset($aExtraParams['chart_type']) ? $aExtraParams['chart_type'] : 'pie';
			$sTitle = isset($aExtraParams['chart_title']) ? $aExtraParams['chart_title'] : '';
			$sGroupBy = isset($aExtraParams['group_by']) ? $aExtraParams['group_by'] : '';
			$sGroupByExpr = isset($aExtraParams['group_by_expr']) ? '&params[group_by_expr]='.$aExtraParams['group_by_expr'] : '';
			$sFilter = $this->m_oFilter->serialize();
			$sHtml .= "<div id=\"my_chart_$sId{$iChartCounter}\">If the chart does not display, <a href=\"http://get.adobe.com/flash/\" target=\"_blank\">install Flash</a></div>\n";
			$oPage->add_script("function ofc_resize(left, width, top, height) { /* do nothing special */ }");
			if (isset($aExtraParams['group_by_label']))
			{
				$sUrl = urlencode(utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=open_flash_chart&params[group_by]=$sGroupBy{$sGroupByExpr}&params[group_by_label]={$aExtraParams['group_by_label']}&params[chart_type]=$sChartType&params[chart_title]=$sTitle&params[currentId]=$sId&id=$sId&filter=".urlencode($sFilter));
			}
			else
			{
				$sUrl = urlencode(utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=open_flash_chart&params[group_by]=$sGroupBy{$sGroupByExpr}&params[chart_type]=$sChartType&params[chart_title]=$sTitle&params[currentId]=$sId&id=$sId&filter=".urlencode($sFilter));
			}

			$oPage->add_ready_script("swfobject.embedSWF(\"../images/open-flash-chart.swf\", \"my_chart_$sId{$iChartCounter}\", \"100%\", \"300\",\"9.0.0\", \"expressInstall.swf\",
				{\"data-file\":\"".$sUrl."\"}, {wmode: 'transparent'} );\n");
			$iChartCounter++;
			if (isset($aExtraParams['group_by']))
			{
				if (isset($aExtraParams['group_by_label']))
				{
					$oGroupByExp = Expression::FromOQL($aExtraParams['group_by']);
					$sGroupByLabel = $aExtraParams['group_by_label'];
				}
				else
				{
					// Backward compatibility: group_by is simply a field id
					$sAlias = $this->m_oFilter->GetClassAlias();
					$oGroupByExp = new FieldExpression($aExtraParams['group_by'], $sAlias);
					$sGroupByLabel = MetaModel::GetLabel($this->m_oFilter->GetClass(), $aExtraParams['group_by']);
				}

				$aGroupBy = array();
				$aGroupBy['grouped_by_1'] = $oGroupByExp;
				$sSql = MetaModel::MakeGroupByQuery($this->m_oFilter, $aQueryParams, $aGroupBy);
				$aRes = CMDBSource::QueryToArray($sSql);

				$aGroupBy = array();
				$aLabels = array();
				$aValues = array();
				$iTotalCount = 0;
				foreach ($aRes as $iRow => $aRow)
				{
					$sValue = $aRow['grouped_by_1'];
					$aValues[$iRow] = $sValue;
					$sHtmlValue = $oGroupByExp->MakeValueLabel($this->m_oFilter, $sValue, $sValue);
					$aLabels[$iRow] = $sHtmlValue;
					$aGroupBy[$iRow] = (int) $aRow['_itop_count_'];
					$iTotalCount += $aRow['_itop_count_'];
				}

				$aData = array();
				$idx = 0;
				$aURLs = array();
				foreach($aGroupBy as $iRow => $iCount)
				{
					// Build the search for this subset
					$oSubsetSearch = clone $this->m_oFilter;
					$oCondition = new BinaryExpression($oGroupByExp, '=', new ScalarExpression($aValues[$iRow]));
					$oSubsetSearch->AddConditionExpression($oCondition);
					$aURLs[$idx] = $oSubsetSearch->serialize();
					$idx++;
				}
				$sURLList = '';
				foreach($aURLs as $index => $sURL)
				{
					$sURLList .= "\taURLs[$index] = '".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search&format=html{$sContext}&filter=".urlencode($sURL)."';\n";
				}
				$oPage->add_script(
<<<EOF
function ofc_drill_down_{$sId}(index)
{
	var aURLs = new Array();
{$sURLList}
	window.location.href=aURLs[index];
}
EOF
				);
			}
			break;
			
			case 'open_flash_chart_ajax':
			require_once(APPROOT.'/pages/php-ofc-library/open-flash-chart.php');
			$sChartType = isset($aExtraParams['chart_type']) ? $aExtraParams['chart_type'] : 'pie';
			$sId = utils::ReadParam('id', '');

			$oChart = new open_flash_chart();
			switch($sChartType)
			{
				case 'bars':
				$oChartElement = new bar_glass();

				if (isset($aExtraParams['group_by']))
				{
					if (isset($aExtraParams['group_by_label']))
					{
						$oGroupByExp = Expression::FromOQL($aExtraParams['group_by']);
						$sGroupByLabel = $aExtraParams['group_by_label'];
					}
					else
					{
						// Backward compatibility: group_by is simply a field id
						$sAlias = $this->m_oFilter->GetClassAlias();
						$oGroupByExp = new FieldExpression($aExtraParams['group_by'], $sAlias);
						$sGroupByLabel = MetaModel::GetLabel($this->m_oFilter->GetClass(), $aExtraParams['group_by']);
					}
	
					$aGroupBy = array();
					$aGroupBy['grouped_by_1'] = $oGroupByExp;
					$sSql = MetaModel::MakeGroupByQuery($this->m_oFilter, $aQueryParams, $aGroupBy);
					$aRes = CMDBSource::QueryToArray($sSql);
	
					$aGroupBy = array();
					$aLabels = array();
					$iTotalCount = 0;
					foreach ($aRes as $iRow => $aRow)
					{
						$sValue = $aRow['grouped_by_1'];
						$sHtmlValue = $oGroupByExp->MakeValueLabel($this->m_oFilter, $sValue, $sValue);
						$aLabels[$iRow] = strip_tags($sHtmlValue);
						$aGroupBy[$iRow] = (int) $aRow['_itop_count_'];
						$iTotalCount += $aRow['_itop_count_'];
					}
	
					$aData = array();
					$aChartLabels = array();
					$maxValue = 0;
					foreach($aGroupBy as $iRow => $iCount)
					{
						$oBarValue = new bar_value($iCount);
						$oBarValue->on_click("ofc_drill_down_$sId");
						$aData[] = $oBarValue;
						if ($iCount > $maxValue) $maxValue = $iCount;
						$aChartLabels[] = html_entity_decode($aLabels[$iRow], ENT_QUOTES, 'UTF-8');
					}
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
					$oXLabels->set_labels($aChartLabels);
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
					if (isset($aExtraParams['group_by_label']))
					{
						$oGroupByExp = Expression::FromOQL($aExtraParams['group_by']);
						$sGroupByLabel = $aExtraParams['group_by_label'];
					}
					else
					{
						// Backward compatibility: group_by is simply a field id
						$sAlias = $this->m_oFilter->GetClassAlias();
						$oGroupByExp = new FieldExpression($aExtraParams['group_by'], $sAlias);
						$sGroupByLabel = MetaModel::GetLabel($this->m_oFilter->GetClass(), $aExtraParams['group_by']);
					}
	
					$aGroupBy = array();
					$aGroupBy['grouped_by_1'] = $oGroupByExp;
					$sSql = MetaModel::MakeGroupByQuery($this->m_oFilter, $aQueryParams, $aGroupBy);
					$aRes = CMDBSource::QueryToArray($sSql);
	
					$aGroupBy = array();
					$aLabels = array();
					$iTotalCount = 0;
					foreach ($aRes as $iRow => $aRow)
					{
						$sValue = $aRow['grouped_by_1'];
						$sHtmlValue = $oGroupByExp->MakeValueLabel($this->m_oFilter, $sValue, $sValue);
						$aLabels[$iRow] = strip_tags($sHtmlValue);
						$aGroupBy[$iRow] = (int) $aRow['_itop_count_'];
						$iTotalCount += $aRow['_itop_count_'];
					}

					$aData = array();
					foreach($aGroupBy as $iRow => $iCount)
					{
						$sFlashLabel = html_entity_decode($aLabels[$iRow], ENT_QUOTES, 'UTF-8');
						$PieValue = new pie_value($iCount, $sFlashLabel); //@@ BUG: not passed via ajax !!!
						$PieValue->on_click("ofc_drill_down_$sId");
						$aData[] = $PieValue;
					}
	
					$oChartElement->set_values( $aData );
					$oChart->x_axis = null;
				}
			}				
			if (isset($aExtraParams['chart_title']))
			{
				// The title has been given in an url, and urlencoded...
				// and urlencode transforms utf-8 into something similar to ISO-8859-1
				// Example: é (C3A9 becomes %E9)
				// As a consequence, json_encode (called within open-flash-chart.php)
				// was returning 'null' and the graph was not displayed at all
				// To make sure that the graph is displayed AND to get a correct title
				// (at least for european characters) let's transform back into utf-8 !
				$sTitle = iconv("ISO-8859-1", "UTF-8//IGNORE", $aExtraParams['chart_title']);

				// If the title is a dictionnary entry, fetch it
				$sTitle = Dict::S($sTitle);

				$oTitle = new title($sTitle);
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
	
	/**
	 * Add a condition (restriction) to the current DBObjectSearch on which the display block is based
	 * taking into account the hierarchical keys for which the condition is based on the 'below' operator
	 */
	protected function AddCondition($sFilterCode, $condition, $sOpCode = null)
	{
		// Workaround to an issue revealed whenever a condition on org_id is applied twice (with a hierarchy of organizations)
		// Moreover, it keeps the query as simple as possible
		if (isset($this->m_aConditions[$sFilterCode]) && $condition == $this->m_aConditions[$sFilterCode])
		{
			// Skip
			return;
		}
		$this->m_aConditions[$sFilterCode] = $condition;

		$sClass = $this->m_oFilter->GetClass();
		$bConditionAdded = false;
		
		// If the condition is an external key with a class having a hierarchy, use a "below" criteria
		if (MetaModel::IsValidAttCode($sClass, $sFilterCode))
		{
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sFilterCode);

			if ($oAttDef->IsExternalKey())
			{
				$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($oAttDef->GetTargetClass());
				
				if ($sHierarchicalKeyCode !== false)
				{
					$oFilter = new DBObjectSearch($oAttDef->GetTargetClass());
					if (($sOpCode == 'IN') && is_array($condition))
					{
						$oFilter->AddConditionExpression(self::GetConditionIN($oFilter, 'id', $condition));						
					}
					else
					{
						$oFilter->AddCondition('id', $condition);
					}
					$oHKFilter = new DBObjectSearch($oAttDef->GetTargetClass());
					$oHKFilter->AddCondition_PointingTo($oFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW); // Use the 'below' operator by default
					$this->m_oFilter->AddCondition_PointingTo($oHKFilter, $sFilterCode);
					$bConditionAdded = true;
				}
				else if (($sOpCode == 'IN') && is_array($condition))
				{
					$this->m_oFilter->AddConditionExpression(self::GetConditionIN($this->m_oFilter, $sFilterCode, $condition));
					$bConditionAdded = true;
				}
			}
			else if (($sOpCode == 'IN') && is_array($condition))
			{
				$this->m_oFilter->AddConditionExpression(self::GetConditionIN($this->m_oFilter, $sFilterCode, $condition));
				$bConditionAdded = true;
			}
		}
		
		// In all other cases, just add the condition directly
		if (!$bConditionAdded)
		{
			$this->m_oFilter->AddCondition($sFilterCode, $condition); // Use the default 'loose' operator
		}
	}
	
	static protected function GetConditionIN($oFilter, $sFilterCode, $condition)
	{
		$oField = new FieldExpression($sFilterCode,  $oFilter->GetClassAlias());
		$sListExpr = '('.implode(', ', CMDBSource::Quote($condition)).')';
		$sOQLCondition = $oField->Render()." IN $sListExpr";
		$oNewCondition = Expression::FromOQL($sOQLCondition);
		return $oNewCondition;		
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
	public function GetRenderContent(WebPage $oPage, $aExtraParams = array(), $sId)
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
				// There is one change in the list... only when the object has been created !
				$sDate = $oLatestChangeOp->GetAsHTML('date');
				$oChange = MetaModel::GetObject('CMDBChange', $oLatestChangeOp->Get('change'));
				$sUserInfo = $oChange->GetAsHTML('userinfo');
				$sHtml .= $oPage->GetStartCollapsibleSection(Dict::Format('UI:History:LastModified_On_By', $sDate, $sUserInfo));
				$sHtml .= $this->GetHistoryTable($oPage, $oSet);		
				$sHtml .= $oPage->GetEndCollapsibleSection();
			}
			break;

			case 'table':
			default:
			$sHtml .= $this->GetHistoryTable($oPage, $oSet);		

		}
		return $sHtml;
	}
	
	protected function GetHistoryTable(WebPage $oPage, DBObjectSet $oSet)
	{
		$sHtml = '';
		// First the latest change that the user is allowed to see
		$oSet->Rewind(); // Reset the pointer to the beginning of the set
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
			$aValues[] = array('date' => $aChange['date'], 'userinfo' => htmlentities($aChange['userinfo'], ENT_QUOTES, 'UTF-8'), 'log' => "<ul><li>".implode('</li><li>', $aChange['log'])."</li></ul>");
		}
		$sHtml .= $oPage->GetTable($aAttribs, $aValues);
		return $sHtml;
	}
}

/**
 * Displays the 'Actions' menu for a given (list of) object(s)
 * The 'style' of the list (see constructor of DisplayBlock) can be either 'list' or 'details'
 * For backward compatibility 'popup' is equivalent to 'list'...
 */
class MenuBlock extends DisplayBlock
{	
	/**
	 * Renders the "Actions" popup menu for the given set of objects
	 * 
	 * Note that the menu links containing (or ending) with a hash (#) will have their fragment
	 * part (whatever is after the hash) dynamically replaced (by javascript) when the menu is
	 * displayed, to correspond to the current hash/fragment in the page. This allows modifying
	 * an object in with the same tab active by default as the tab that was active when selecting
	 * the "Modify..." action.
	 */
	public function GetRenderContent(WebPage $oPage, $aExtraParams = array(), $sId)
	{
		if ($this->m_sStyle == 'popup') // popup is a synonym of 'list' for backward compatibility
		{
			$this->m_sStyle = 'list';
		}
		$sHtml = '';
		$oAppContext = new ApplicationContext();
		$sContext = $oAppContext->GetForLink();
		if (!empty($sContext))
		{
			$sContext = '&'.$sContext;
		}
		$sClass = $this->m_oFilter->GetClass();
		$oSet = new CMDBObjectSet($this->m_oFilter);
		$sFilter = $this->m_oFilter->serialize();
		$sFilterDesc = $this->m_oFilter->ToOql(true);
		$aActions = array();
		$sUIPage = cmdbAbstractObject::ComputeStandardUIPage($sClass);
		$sRootUrl = utils::GetAbsoluteUrlAppRoot();
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
		$bIsCreationAllowed =  (UserRights::IsActionAllowed($sClass, UR_ACTION_CREATE) == UR_ALLOWED_YES);
		switch($oSet->Count())
		{
			case 0:
			// No object in the set, the only possible action is "new"
			if ($bIsCreationAllowed) { $aActions['UI:Menu:New'] = array ('label' => Dict::S('UI:Menu:New'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=new&class=$sClass{$sContext}{$sDefault}"); }
			break;
			
			case 1:
			$oObj = $oSet->Fetch();
			$id = $oObj->GetKey();
			$bIsModifyAllowed = (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_YES);
			$bIsDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, $oSet);
			$bIsBulkModifyAllowed = (!MetaModel::IsAbstract($sClass)) && UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY, $oSet);
			$bIsBulkDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, $oSet);
			// Just one object in the set, possible actions are "new / clone / modify and delete"
			if (!isset($aExtraParams['link_attr']))
			{
				if ($bIsModifyAllowed) { $aActions['UI:Menu:Modify'] = array ('label' => Dict::S('UI:Menu:Modify'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=modify&class=$sClass&id=$id{$sContext}#"); }
				if ($bIsCreationAllowed) { $aActions['UI:Menu:New'] = array ('label' => Dict::S('UI:Menu:New'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=new&class=$sClass{$sContext}{$sDefault}"); }
				if ($bIsDeleteAllowed) { $aActions['UI:Menu:Delete'] = array ('label' => Dict::S('UI:Menu:Delete'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=delete&class=$sClass&id=$id{$sContext}"); }
				// Transitions / Stimuli
				$aTransitions = $oObj->EnumTransitions();
				if (count($aTransitions))
				{
					$this->AddMenuSeparator($aActions);
					$aStimuli = Metamodel::EnumStimuli(get_class($oObj));
					foreach($aTransitions as $sStimulusCode => $aTransitionDef)
					{
						$iActionAllowed = (get_class($aStimuli[$sStimulusCode]) == 'StimulusUserAction') ? UserRights::IsStimulusAllowed($sClass, $sStimulusCode, $oSet) : UR_ALLOWED_NO;
						switch($iActionAllowed)
						{
							case UR_ALLOWED_YES:
							$aActions[$sStimulusCode] = array('label' => $aStimuli[$sStimulusCode]->GetLabel(), 'url' => "{$sRootUrl}pages/UI.php?operation=stimulus&stimulus=$sStimulusCode&class=$sClass&id=$id{$sContext}");
							break;
							
							default:
							// Do nothing
						}
					}
				}
				// Relations...
				$aRelations = MetaModel::EnumRelations($sClass);
				if (count($aRelations))
				{
					$this->AddMenuSeparator($aActions);
					foreach($aRelations as $sRelationCode)
					{
						$aActions[$sRelationCode] = array ('label' => MetaModel::GetRelationVerbUp($sRelationCode), 'url' => "{$sRootUrl}pages/$sUIPage?operation=swf_navigator&relation=$sRelationCode&class=$sClass&id=$id{$sContext}");
					}
				}
				/*
				$this->AddMenuSeparator($aActions);
				// Static menus: Email this page & CSV Export
				$sUrl = ApplicationContext::MakeObjectUrl($sClass, $id);
				$aActions['UI:Menu:EMail'] = array ('label' => Dict::S('UI:Menu:EMail'), 'url' => "mailto:?subject=".urlencode($oObj->GetRawName())."&body=".urlencode($sUrl));
				$aActions['UI:Menu:CSVExport'] = array ('label' => Dict::S('UI:Menu:CSVExport'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=search&filter=".urlencode($sFilter)."&format=csv{$sContext}");
				// The style tells us whether the menu is displayed on a list of one object, or on the details of the given object 
				if ($this->m_sStyle == 'list')
				{
					// Actions specific to the list
					$sOQL = addslashes($sFilterDesc);
					$aActions['UI:Menu:AddToDashboard'] = array ('label' => Dict::S('UI:Menu:AddToDashboard'), 'url' => "#", 'onclick' => "return DashletCreationDlg('$sOQL')");
				}
				*/
			}
			$this->AddMenuSeparator($aActions);
			foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
			{
				$oSet->Rewind();
				foreach($oExtensionInstance->EnumAllowedActions($oSet) as $sLabel => $sUrl)
				{
					$aActions[$sLabel] = array ('label' => $sLabel, 'url' => $sUrl);
				}
			}
			break;
			
			default:
			// Check rights
			// New / Modify
			$bIsModifyAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet);
			$bIsBulkModifyAllowed = (!MetaModel::IsAbstract($sClass)) && UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY, $oSet);
			$bIsBulkDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, $oSet);
			if (isset($aExtraParams['link_attr']))
			{
				$id = $aExtraParams['object_id'];
				$sTargetAttr = $aExtraParams['target_attr'];
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sTargetAttr);
				$sTargetClass = $oAttDef->GetTargetClass();
				$bIsDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, $oSet);
				if ($bIsModifyAllowed) { $aActions['UI:Menu:Add'] = array ('label' => Dict::S('UI:Menu:Add'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=modify_links&class=$sClass&link_attr=".$aExtraParams['link_attr']."&target_class=$sTargetClass&id=$id&addObjects=true{$sContext}"); }
				if ($bIsBulkModifyAllowed) { $aActions['UI:Menu:Manage'] = array ('label' => Dict::S('UI:Menu:Manage'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=modify_links&class=$sClass&link_attr=".$aExtraParams['link_attr']."&target_class=$sTargetClass&id=$id{$sContext}"); }
				//if ($bIsBulkDeleteAllowed) { $aActions[] = array ('label' => 'Remove All...', 'url' => "#"); }
			}
			else
			{
				// many objects in the set, possible actions are: new / modify all / delete all
				if ($bIsCreationAllowed) { $aActions['UI:Menu:New'] = array ('label' => Dict::S('UI:Menu:New'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=new&class=$sClass{$sContext}{$sDefault}"); }
				if ($bIsBulkModifyAllowed) { $aActions['UI:Menu:ModifyAll'] = array ('label' => Dict::S('UI:Menu:ModifyAll'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=select_for_modify_all&class=$sClass&filter=".urlencode($sFilter)."{$sContext}"); }
				if ($bIsBulkDeleteAllowed) { $aActions['UI:Menu:BulkDelete'] = array ('label' => Dict::S('UI:Menu:BulkDelete'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=select_for_deletion&filter=".urlencode($sFilter)."{$sContext}"); }

				// Stimuli
				$aStates = MetaModel::EnumStates($sClass);
				if (count($aStates) > 0)
				{
					// Life cycle actions may be available... if all objects are in the same state
					$oSet->Rewind();
					$aStates = array();
					while($oObj = $oSet->Fetch())
					{
						$aStates[$oObj->GetState()] = $oObj->GetState();
					}
					$oSet->Rewind();
					if (count($aStates) == 1)
					{
						// All objects are in the same state...
						$sState = array_pop($aStates);
						$aTransitions = Metamodel::EnumTransitions($sClass, $sState);
						if (count($aTransitions))
						{
							$this->AddMenuSeparator($aActions);
							$aStimuli = Metamodel::EnumStimuli($sClass);
							foreach($aTransitions as $sStimulusCode => $aTransitionDef)
							{
								$oChecker = new StimulusChecker($this->m_oFilter, $sState, $sStimulusCode);
								$iActionAllowed = (get_class($aStimuli[$sStimulusCode]) == 'StimulusUserAction') ? $oChecker->IsAllowed() : UR_ALLOWED_NO;
								switch($iActionAllowed)
								{
									case UR_ALLOWED_YES:
									case UR_ALLOWED_DEPENDS:
									$aActions[$sStimulusCode] = array('label' => $aStimuli[$sStimulusCode]->GetLabel(), 'url' => "{$sRootUrl}pages/UI.php?operation=select_bulk_stimulus&stimulus=$sStimulusCode&state=$sState&class=$sClass&filter=".urlencode($sFilter)."{$sContext}");
									break;
									
									default:
									// Do nothing
								}
							}
						}
					}
				}
				/*
				$this->AddMenuSeparator($aActions);
				$sUrl = utils::GetAbsoluteUrlAppRoot();
				$aActions['UI:Menu:EMail'] = array ('label' => Dict::S('UI:Menu:EMail'), 'url' => "mailto:?subject=$sFilterDesc&body=".urlencode("{$sUrl}pages/$sUIPage?operation=search&filter=".urlencode($sFilter)."{$sContext}"));
				$aActions['UI:Menu:CSVExport'] = array ('label' => Dict::S('UI:Menu:CSVExport'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=search&filter=".urlencode($sFilter)."&format=csv{$sContext}");
				$sOQL = addslashes($sFilterDesc);
				$aActions['UI:Menu:AddToDashboard'] = array ('label' => Dict::S('UI:Menu:AddToDashboard'), 'url' => "#", 'onclick' => "return DashletCreationDlg('$sOQL')");
				*/
			}
		}
		
		$this->AddMenuSeparator($aActions);
		foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$oSet->Rewind();
			foreach($oExtensionInstance->EnumAllowedActions($oSet) as $sLabel => $data)
			{
				if (is_array($data))
				{
					// New plugins can provide javascript handlers via the 'onclick' property
					//TODO: enable extension of different menus by checking the 'target' property ??
					$aActions[$sLabel] = array ('label' => $sLabel, 'url' => isset($data['url']) ? $data['url'] : '#', 'onclick' => isset($data['onclick']) ? $data['onclick'] : '');
				}
				else
				{
					// Backward compatibility with old plugins
					$aActions[$sLabel] = array ('label' => $sLabel, 'url' => $data);
				}
			}
		}
					
		// New extensions based on iPopupMenuItem interface 
		switch($this->m_sStyle)
		{
			case 'list':
			$oSet->Rewind();
			$param  = $oSet;
			$iMenuId = iPopupMenuExtension::MENU_OBJLIST_ACTIONS;
			break;
			
			case 'details':
			$oSet->Rewind();
			$param  = $oSet->Fetch();
			$iMenuId = iPopupMenuExtension::MENU_OBJDETAILS_ACTIONS;
			break;
			
		}
		utils::GetPopupMenuItems($oPage, $iMenuId, $param, $aActions);

		$aFavoriteActions = array();
		$aCallSpec = array($sClass, 'GetShortcutActions');
		if (is_callable($aCallSpec))
		{
			$aShortcutActions = call_user_func($aCallSpec, $sClass);
			foreach ($aActions as $key => $aAction)
			{
				if (in_array($key, $aShortcutActions))
				{
					$aFavoriteActions[] = $aAction;
					unset($aActions[$key]);
				}
			}
		}
		else
		{
			$aShortcutActions = array();
		}
		
		if (count($aFavoriteActions) > 0)
		{
			$sHtml .= "<div class=\"itop_popup actions_menu\"><ul>\n<li>".Dict::S('UI:Menu:OtherActions')."\n<ul>\n";
		}
		else
		{
			$sHtml .= "<div class=\"itop_popup actions_menu\"><ul>\n<li>".Dict::S('UI:Menu:Actions')."\n<ul>\n";
		}

		$sHtml .= $oPage->RenderPopupMenuItems($aActions, $aFavoriteActions);

		static $bPopupScript = false;
		if (!$bPopupScript)
		{
			// Output this once per page...
			$oPage->add_ready_script("$(\"div.itop_popup>ul\").popupmenu();\n");
			$bPopupScript = true;
		}
		return $sHtml;
	}
	
	/**
	 * Appends a menu separator to the current list of actions
	 * @param Hash $aActions The current actions list
	 * @return void
	 */
	protected function AddMenuSeparator(&$aActions)
	{
		$sSeparator = '<hr class="menu-separator"/>';
		if (count($aActions) > 0) // Make sure that the separator is not the first item in the menu
		{
			$aKeys = array_keys($aActions);
			$sLastKey = array_pop($aKeys);
			if ($aActions[$sLastKey]['label'] != $sSeparator) // Make sure there are no 2 consecutive separators
			{
				$aActions['sep_'.(count($aActions)-1)] = array('label' => $sSeparator, 'url' => '');
			}
		}
	}	
}

/**
 * Some dummy menus for testing
 */
class ExtraMenus implements iPopupMenuExtension
{
	/*
	const MENU_OBJLIST_ACTIONS = 1; 	// $param is a DBObjectSet containing the list of objects
	const MENU_OBJLIST_TOOLKIT = 2;		// $param is a DBObjectSet containing the list of objects
	const MENU_OBJDETAILS_ACTIONS = 3;	// $param is a DBObject instance: the object currently displayed
	const MENU_DASHBOARD_ACTIONS = 4;	// $param is a Dashboard instance: the dashboard currently displayed
	const MENU_USER_ACTIONS = 5;		// $param is a null ??
	*/
	
	/**
	 * Get the list of items to be added to a menu. The items will be inserted in the menu in the order of the returned array
	 * @param int $iMenuId The identifier of the type of menu, as listed by the constants MENU_xxx above
	 * @param mixed $param Depends on $iMenuId see the constants define above
	 * @return Array An array of ApplicationPopupMenuItem or an empty array if no action is to be added to the menu
	 */
	public static function EnumItems($iMenuId, $param)
	{
		switch($iMenuId)
		{
			/*
			case iPopupMenuExtension::MENU_OBJLIST_ACTIONS:
			// $param is a DBObjectSet
			$aResult = array(
				new JSPopupMenuItem('Test::Item1', 'List Test 1', "alert('Test 1')"),
				new JSPopupMenuItem('Test::Item2', 'List Test 2', "alert('Test 2')"),
			);
			break;
		
				$this->AddMenuSeparator($aActions);
				$sUrl = utils::GetAbsoluteUrlAppRoot();
				$aActions['UI:Menu:EMail'] = array ('label' => Dict::S('UI:Menu:EMail'), 'url' => "mailto:?subject=$sFilterDesc&body=".urlencode("{$sUrl}pages/$sUIPage?operation=search&filter=".urlencode($sFilter)."{$sContext}"));
				$aActions['UI:Menu:CSVExport'] = array ('label' => Dict::S('UI:Menu:CSVExport'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=search&filter=".urlencode($sFilter)."&format=csv{$sContext}");
				$sOQL = addslashes($sFilterDesc);
				$aActions['UI:Menu:AddToDashboard'] = array ('label' => Dict::S('UI:Menu:AddToDashboard'), 'url' => "#", 'onclick' => "return DashletCreationDlg('$sOQL')");
			*/
			
			case iPopupMenuExtension::MENU_OBJLIST_TOOLKIT:
			// $param is a DBObjectSet
			$oAppContext = new ApplicationContext();
			$sContext = $oAppContext->GetForLink();
			$sUIPage = cmdbAbstractObject::ComputeStandardUIPage($param->GetFilter()->GetClass());
			$sOQL = addslashes($param->GetFilter()->ToOQL(true));
			$sFilter = urlencode($param->GetFilter()->serialize());
			$sUrl = utils::GetAbsoluteUrlAppRoot()."pages/$sUIPage?operation=search&filter=".$sFilter."&{$sContext}";
			$aResult = array(
				new SeparatorPopupMenuItem(),
				// Static menus: Email this page, CSV Export & Add to Dashboard
				new URLPopupMenuItem('UI:Menu:EMail', Dict::S('UI:Menu:EMail'), "mailto:?body=".urlencode($sUrl)),
				new URLPopupMenuItem('UI:Menu:CSVExport', Dict::S('UI:Menu:CSVExport'), $sUrl."&format=csv"),
				new JSPopupMenuItem('UI:Menu:AddToDashboard', Dict::S('UI:Menu:AddToDashboard'), "DashletCreationDlg('$sOQL')"),
			);
			break;
			
			
			case iPopupMenuExtension::MENU_OBJDETAILS_ACTIONS:
			// $param is a DBObject
			$oObj = $param;
			$oFilter = DBobjectSearch::FromOQL("SELECT ".get_class($oObj)." WHERE id=".$oObj->GetKey());
			$sFilter = $oFilter->serialize();
			$sUrl = ApplicationContext::MakeObjectUrl(get_class($oObj), $oObj->GetKey());
			$sUIPage = cmdbAbstractObject::ComputeStandardUIPage(get_class($oObj));
			$oAppContext = new ApplicationContext();
			$sContext = $oAppContext->GetForLink();
			$aResult = array(
				new SeparatorPopupMenuItem(),
				// Static menus: Email this page & CSV Export
				new URLPopupMenuItem('UI:Menu:EMail', Dict::S('UI:Menu:EMail'), "mailto:?subject=".urlencode($oObj->GetRawName())."&body=".urlencode($sUrl)),
				new URLPopupMenuItem('UI:Menu:CSVExport', Dict::S('UI:Menu:CSVExport'), utils::GetAbsoluteUrlAppRoot()."pages/$sUIPage?operation=search&filter=".urlencode($sFilter)."&format=csv&{$sContext}"),
			);
			break;
			
			
			case iPopupMenuExtension::MENU_DASHBOARD_ACTIONS:
			// $param is a Dashboard
			$oAppContext = new ApplicationContext();
			$aParams = $oAppContext->GetAsHash();
			$sMenuId = ApplicationMenu::GetActiveNodeId();
			$sDlgTitle = addslashes(Dict::S('UI:ImportDashboardTitle'));
			$sDlgText = addslashes(Dict::S('UI:ImportDashboardText'));
			$sCloseBtn = addslashes(Dict::S('UI:Button:Cancel'));
			$aResult = array(
				new SeparatorPopupMenuItem(),
				new URLPopupMenuItem('UI:ExportDashboard', Dict::S('UI:ExportDashBoard'), utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?operation=export_dashboard&id='.$sMenuId),
				new JSPopupMenuItem('UI:ImportDashboard', Dict::S('UI:ImportDashBoard'), "UploadDashboard({dashboard_id: '$sMenuId', title: '$sDlgTitle', text: '$sDlgText', close_btn: '$sCloseBtn' })"),
			);
			break;
			
			/*
			case iPopupMenuExtension::MENU_USER_ACTIONS:
			// $param is null ??
			$aResult = array(
				new SeparatorPopupMenuItem(),
				new JSPopupMenuItem('Test::Item1', 'Reset preferences...', "alert('Test 1')"),
				new JSPopupMenuItem('Test::Item2', 'Do Something Stupid', "alert('Hey Dude !')"),
			);
			break;
			*/
			
			default:
			// Unknown type of menu, do nothing
			$aResult = array();
		}
		return $aResult;
	}
}
