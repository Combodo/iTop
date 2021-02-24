<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
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
	/** @var \DBSearch */
	protected $m_oFilter;
	protected $m_aConditions; // Conditions added to the filter -> avoid duplicate conditions
	protected $m_sStyle;
	protected $m_bAsynchronous;
	protected $m_aParams;
	protected $m_oSet;
	protected $m_bShowObsoleteData = null;
	
	public function __construct(DBSearch $oFilter, $sStyle = 'list', $bAsynchronous = false, $aParams = array(), $oSet = null)
	{
		$this->m_oFilter = $oFilter->DeepClone();
		$this->m_aConditions = array();
		$this->m_sStyle = $sStyle;
		$this->m_bAsynchronous = $bAsynchronous;
		$this->m_aParams = $aParams;
		$this->m_oSet = $oSet;
		if (array_key_exists('show_obsolete_data', $aParams))
		{
			$this->m_bShowObsoleteData = $aParams['show_obsolete_data'];
		}
		if ($this->m_bShowObsoleteData === null)
		{
			// User defined
			$this->m_bShowObsoleteData = utils::ShowObsoleteData();
		}
	}
	
	public function GetFilter()
	{
		return $this->m_oFilter;
	}

	/**
	 * Constructs a DisplayBlock object from a DBObjectSet already in memory
	 *
	 * @param DBObjectSet $oSet
	 * @param string $sStyle
	 * @param array $aParams
	 *
	 * @return DisplayBlock The DisplayBlock object, or null if the creation failed
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function FromObjectSet(DBObjectSet $oSet, $sStyle, $aParams = array())
	{
		$oDummyFilter = new DBObjectSearch($oSet->GetClass());
		$aKeys = array();
		$oSet->OptimizeColumnLoad(array($oSet->GetClassAlias() => array())); // No need to load all the columns just to get the id
		while($oObject = $oSet->Fetch())
		{
			$aKeys[] = $oObject->GetKey();	
		}
		$oSet->Rewind();
		if (count($aKeys) > 0)
		{
			$oDummyFilter->AddCondition('id', $aKeys, 'IN');
		}
		else
		{
			$oDummyFilter->AddCondition('id', 0, '=');
		}
		$oBlock = new DisplayBlock($oDummyFilter, $sStyle, false, $aParams); // DisplayBlocks built this way are synchronous
		return $oBlock;
	}

	/**
	 * Constructs a DisplayBlock object from an XML template
	 *
	 * @param $sTemplate string The XML template
	 *
	 * @return DisplayBlock The DisplayBlock object, or null if the template is invalid
	 * @throws \ApplicationException
	 * @throws \OQLException
	 */
	public static function FromTemplate($sTemplate)
	{
		$iStartPos = stripos($sTemplate, '<'.self::TAG_BLOCK.' ',0);
		$iEndPos = stripos($sTemplate, '</'.self::TAG_BLOCK.'>', $iStartPos); 
		$iEndTag = stripos($sTemplate, '>', $iStartPos);
		$aParams = array();
		
		if (($iStartPos === false) || ($iEndPos === false)) return null; // invalid template		
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
		$oFilter = null;
		switch($sEncoding)
		{
			case 'text/serialize':
			$oFilter = DBSearch::unserialize($sITopData);
			break;
			
			case 'text/oql':
			$oFilter = DBSearch::FromOQL($sITopData);
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
		
		if (isset($aExtraParams['query_params']))
		{
			$aQueryParams = $aExtraParams['query_params'];
		}
		else
		{
			if (isset($aExtraParams['this->id']) && isset($aExtraParams['this->class']))
			{
				$sClass = $aExtraParams['this->class'];
				$iKey = $aExtraParams['this->id'];
				$oObj = MetaModel::GetObject($sClass, $iKey);
				$aQueryParams = array('this->object()' => $oObj);
			}
			else
			{
				$aQueryParams = array();
			}
		}

		$sFilter = addslashes($this->m_oFilter->serialize(false, $aQueryParams)); // Used either for asynchronous or auto_reload
		if (!$this->m_bAsynchronous)
		{
			// render now
			$sHtml .= "<div id=\"$sId\" class=\"display_block\" >\n";
			try
			{
				$sHtml .= $this->GetRenderContent($oPage, $aExtraParams, $sId);
			} catch (Exception $e)
			{
				IssueLog::Error('Exception during GetDisplay: ' . $e->getMessage());
			}
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
				 $("#'.$sId.'")
				    .empty()
				    .append(data)
				    .removeClass("loading")
                 ;
				}
			 );
			 ');
		}


        if ($this->m_sStyle == 'list') // Search form need to extract result list extra data, the simplest way is to expose this configuration
        {

            $listJsonExtraParams = json_encode(json_encode($aExtraParams));
            $oPage->add_ready_script("
            $('#$sId').data('sExtraParams', ".$listJsonExtraParams.");
//            console.debug($('#$sId').data());
//            console.debug($('#$sId'));
//            console.debug('#$sId'); 
            ");




//            $oPage->add_ready_script("console.debug($('#Menu_UserRequest_OpenRequests').data());");

        }


		return $sHtml;
	}

	/**
	 * @param \WebPage $oPage
	 * @param array $aExtraParams
	 *
	 * @throws \ApplicationException
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 */
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

	/**
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 * @param $sId
	 * @return string
	 * @throws ApplicationException
	 * @throws CoreException
	 * @throws CoreWarning
	 * @throws DictExceptionMissingString
	 * @throws MySQLException
	 * @throws Exception
	 */
	public function GetRenderContent(WebPage $oPage, $aExtraParams, $sId)
	{
		$sHtml = '';
		// Add the extra params into the filter if they make sense for such a filter
		$bDoSearch = utils::ReadParam('dosearch', false);
		$aQueryParams = array();
		if (isset($aExtraParams['query_params']))
		{
			$aQueryParams = $aExtraParams['query_params'];
		}
		else
		{
			if (isset($aExtraParams['this->id']) && isset($aExtraParams['this->class']))
			{
				$sClass = $aExtraParams['this->class'];
				$iKey = $aExtraParams['this->id'];
				$oObj = MetaModel::GetObject($sClass, $iKey);
				$aQueryParams = array('this->object()' => $oObj);
			}
		}
		if ($this->m_oSet == null)
		{

			// In case of search, the context filtering is done by the search itself
			if (($this->m_sStyle != 'links') && ($this->m_sStyle != 'search') && ($this->m_sStyle != 'list_search'))
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
					$bParseSearchString = true;
					if (isset($aExtraParams[$sFilterCode]))
					{
						$bParseSearchString = false;
						$condition = $aExtraParams[$sFilterCode];
					}
					if ($bDoSearch && $externalFilterValue != "")
					{
						// Search takes precedence over context params...
						$bParseSearchString = true;
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

						$this->AddCondition($sFilterCode, $condition, $sOpCode, $bParseSearchString);
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
		$this->m_oSet->SetShowObsoleteData($this->m_bShowObsoleteData);

		switch($this->m_sStyle) {
			case 'list_search':
			case 'list':
				break;
			default:
				// N°3473: except for 'list_search' and 'list' (which have more granularity, see the other switch below),
				// refuse to render if the user is not allowed to see the class.
				if (! UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES) {
					$sHtml .= $oPage->GetP(Dict::Format('UI:Error:ReadNotAllowedOn_Class', $this->m_oSet->GetClass()));
					return $sHtml;
				}
		}

		switch ($this->m_sStyle) {
			case 'count':
			if (isset($aExtraParams['group_by']))
			{
				$this->MakeGroupByQuery($aExtraParams, $oGroupByExp, $sGroupByLabel, $aGroupBy, $sAggregationFunction, $sFctVar, $sAggregationAttr, $sSql);

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
					$aGroupBy[$iRow] = (int) $aRow[$sFctVar];
					$iTotalCount += $aRow['_itop_count_'];
				}


				$aData = array();
				$oAppContext = new ApplicationContext();
				$sParams = $oAppContext->GetForLink();
				foreach($aGroupBy as $iRow => $iCount)
				{
					// Build the search for this subset
					$oSubsetSearch = $this->m_oFilter->DeepClone();
					$oCondition = new BinaryExpression($oGroupByExp, '=', new ScalarExpression($aValues[$iRow]));
					$oSubsetSearch->AddConditionExpression($oCondition);
					if (isset($aExtraParams['query_params']))
					{
						$aQueryParams = $aExtraParams['query_params'];
					}
					else
					{
						$aQueryParams = array();
					}
					$sFilter = rawurlencode($oSubsetSearch->serialize(false, $aQueryParams));

					$aData[] = array ('group' => $aLabels[$iRow],
									  'value' => "<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search&dosearch=1&$sParams&filter=$sFilter\">$iCount</a>"); // TO DO: add the context information
				}
				$aAttribs =array(
					'group' => array('label' => $sGroupByLabel, 'description' => ''),
					'value' => array(
						'label' => Dict::S('UI:GroupBy:'.$sAggregationFunction),
						'description' => Dict::Format('UI:GroupBy:'.$sAggregationFunction.'+', $sAggregationAttr),
					),
				);
				$sFormat = isset($aExtraParams['format']) ? $aExtraParams['format'] : 'UI:Pagination:HeaderNoSelection';
				$sHtml .= $oPage->GetP(Dict::Format($sFormat, $iTotalCount));
				$sHtml .= $oPage->GetTable($aAttribs, $aData);
				
				$oPage->add_ready_script("LoadGroupBySortOrder('$sId');\n$('#{$sId} table.listResults').unbind('sortEnd.group_by').bind('sortEnd.group_by', function() { SaveGroupBySortOrder('$sId', $(this)[0].config.sortList); })");
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
					$oBlockFilter = $this->m_oFilter->DeepClone();
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

			case 'list_search':
			case 'list':
			$aClasses = $this->m_oSet->GetSelectedClasses();
			$aAuthorizedClasses = array();
			if (count($aClasses) > 1)
			{
				// Check the classes that can be read (i.e authorized) by this user...
				foreach($aClasses as $sAlias => $sClassName)
				{
					if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ,  $this->m_oSet) != UR_ALLOWED_NO)
					{
						$aAuthorizedClasses[$sAlias] = $sClassName;
					}
				}
				if (count($aAuthorizedClasses) > 0)
				{
					if($this->m_oSet->CountWithLimit(1) > 0)
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
				if ( ($this->m_oSet->CountWithLimit(1)> 0) && (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES) )
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

				if (isset($aExtraParams['update_history']) && true == $aExtraParams['update_history'])
				{
					$sSearchFilter = $this->m_oSet->GetFilter()->serialize();
					// Limit the size of the URL (N°1585 - request uri too long)
					if (strlen($sSearchFilter) < SERVER_MAX_URL_LENGTH)
					{
						$seventAttachedData = json_encode(array(
							'filter' => $sSearchFilter,
							'breadcrumb_id' => "ui-search-".$this->m_oSet->GetClass(),
							'breadcrumb_label' => MetaModel::GetName($this->m_oSet->GetClass()),
							'breadcrumb_max_count' => utils::GetConfig()->Get('breadcrumb.max_count'),
							'breadcrumb_instance_id' => MetaModel::GetConfig()->GetItopInstanceid(),
							'breadcrumb_icon' => utils::GetAbsoluteUrlAppRoot().'images/breadcrumb-search.png',
						));

						$oPage->add_ready_script("$('body').trigger('update_history.itop', [$seventAttachedData])");
					}
				}
			}
			break;
			
			case 'links':
			//$bDashboardMode = isset($aExtraParams['dashboard']) ? ($aExtraParams['dashboard'] == 'true') : false;
			//$bSelectMode = isset($aExtraParams['select']) ? ($aExtraParams['select'] == 'true') : false;
			if ( ($this->m_oSet->CountWithLimit(1) > 0) && (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES) )
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
			if ($bContextFilter && is_null($this->m_oSet))
			{
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
				$this->m_oSet->SetShowObsoleteData($this->m_bShowObsoleteData);
			}
			$iCount = $this->m_oSet->Count();
			$sHyperlink = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&'.$oAppContext->GetForLink().'&filter='.rawurlencode($this->m_oFilter->serialize());
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
			$sHtml .= "<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search_form&do_search=0&class={$sClass}&$sParams\">".Dict::Format('UI:SearchFor_Class', MetaModel::GetName($sClass))."</a>\n";
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
				$this->m_oSet->SetShowObsoleteData($this->m_bShowObsoleteData);
			}
			// Summary details
			$aCounts = array();
			$aStateLabels = array();
			if (!empty($sStateAttrCode) && !empty($sStatesList))
			{
				$aStates = explode(',', $sStatesList);
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sStateAttrCode);

				// Generate one count + group by query [#1330]
				$sClassAlias = $this->m_oFilter->GetClassAlias();
				$oGroupByExpr = Expression::FromOQL($sClassAlias.'.'.$sStateAttrCode);
				$aGroupBy = array('group1' => $oGroupByExpr);
				$oGroupBySearch = $this->m_oFilter->DeepClone();
				if (isset($this->m_bShowObsoleteData))
				{
					$oGroupBySearch->SetShowObsoleteData($this->m_bShowObsoleteData);
				}
				$sCountGroupByQuery = $oGroupBySearch->MakeGroupByQuery(array(), $aGroupBy, false);
				$aCountGroupByResults = CMDBSource::QueryToArray($sCountGroupByQuery);
				$aCountsQueryResults = array();
				foreach ($aCountGroupByResults as $aCountGroupBySingleResult)
				{
					$aCountsQueryResults[$aCountGroupBySingleResult[0]] = $aCountGroupBySingleResult[1];
				}

				foreach($aStates as $sStateValue)
				{
					$sHtmlValue=$aGroupBy['group1']->MakeValueLabel($this->m_oFilter, $sStateValue, $sStateValue);
					$aStateLabels[$sStateValue] = html_entity_decode(strip_tags($sHtmlValue), ENT_QUOTES, 'UTF-8');

					$aCounts[$sStateValue] = (array_key_exists($sStateValue, $aCountsQueryResults))
						? $aCountsQueryResults[$sStateValue]
						: 0;

					if ($aCounts[$sStateValue] == 0)
					{
						$aCounts[$sStateValue] = '-';
					}
					else
					{
						$oSingleGroupByValueFilter = $this->m_oFilter->DeepClone();
						$oSingleGroupByValueFilter->AddCondition($sStateAttrCode, $sStateValue, '=');
						if (isset($this->m_bShowObsoleteData))
						{
							$oSingleGroupByValueFilter->SetShowObsoleteData($this->m_bShowObsoleteData);
						}
						$sHyperlink = utils::GetAbsoluteUrlAppRoot()
							.'pages/UI.php?operation=search&'.$oAppContext->GetForLink()
							.'&filter='.rawurlencode($oSingleGroupByValueFilter->serialize());
						$aCounts[$sStateValue] = "<a href=\"$sHyperlink\">{$aCounts[$sStateValue]}</a>";
					}
				}
			}
			$sHtml .= '<div class="summary-details"><table><tr><th>'.implode('</th><th>', $aStateLabels).'</th></tr>';
			$sHtml .= '<tr><td>'.implode('</td><td>', $aCounts).'</td></tr></table></div>';
			// Title & summary
			$iCount = $this->m_oSet->Count();
			$sHyperlink = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&'.$oAppContext->GetForLink().'&filter='.rawurlencode($this->m_oFilter->serialize());
			$sHtml .= '<h1>'.Dict::S(str_replace('_', ':', $sTitle)).'</h1>';
			$sHtml .= '<a class="summary" href="'.$sHyperlink.'">'.Dict::Format(str_replace('_', ':', $sLabel), $iCount).'</a>';
			$sHtml .= '<div style="clear:both;"></div>';
			break;
			
			case 'csv':
			$bAdvancedMode = utils::ReadParam('advanced', false);

			$sCsvFile = strtolower($this->m_oFilter->GetClass()).'.csv'; 
			$sDownloadLink = utils::GetAbsoluteUrlAppRoot().'webservices/export.php?expression='.urlencode($this->m_oFilter->ToOQL(true)).'&format=csv&filename='.urlencode($sCsvFile);
			$sLinkToToggle = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&'.$oAppContext->GetForLink().'&filter='.rawurlencode($this->m_oFilter->serialize()).'&format=csv';
			// Pass the parameters via POST, since expression may be very long
			$aParamsToPost = array(
				'expression' => $this->m_oFilter->ToOQL(true),
				'format' => 'csv',
				'filename' => $sCsvFile,
				'charset' => 'UTF-8',
			);
			if ($bAdvancedMode)
			{
				$sDownloadLink .= '&fields_advanced=1';
				$aParamsToPost['fields_advance'] = 1;
				$sChecked = 'CHECKED';
			}
			else
			{
				$sLinkToToggle = $sLinkToToggle.'&advanced=1';
				$sChecked = '';
			}
			$sAjaxLink = utils::GetAbsoluteUrlAppRoot().'webservices/export.php';
				
			$sCharsetNotice = false;
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

			$sHtml .= "<div id=\"csv_content_loading\"><div style=\"width: 250px; height: 20px; background: url(../setup/orange-progress.gif); border: 1px #999 solid; margin-left:auto; margin-right: auto; text-align: center;\">".Dict::S('UI:Loading')."</div></div><textarea id=\"csv_content\" style=\"display:none;\">\n";
			//$sHtml .= htmlentities($sCSVData, ENT_QUOTES, 'UTF-8');
			$sHtml .= "</textarea>\n";
			$sJsonParams = json_encode($aParamsToPost);
			$oPage->add_ready_script("$.post('$sAjaxLink', $sJsonParams, function(data) { $('#csv_content').html(data); $('#csv_content_loading').hide(); $('#csv_content').show();} );");
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
			if (!$oPage->IsPrintableVersion())
			{
				$sHtml .= "<div id=\"ds_$sId\" class=\"search_box\">\n";
				$aExtraParams['currentId'] = $sId;
				$sHtml .= cmdbAbstractObject::GetSearchForm($oPage, $this->m_oSet, $aExtraParams);
		 		$sHtml .= "</div>\n";
		 	}
			break;
			
			case 'chart':
			static $iChartCounter = 0;
			$iChartCounter++;
	
			$sChartType = isset($aExtraParams['chart_type']) ? $aExtraParams['chart_type'] : 'pie';
			$sTitle = isset($aExtraParams['chart_title']) ? '<div class="main_header"><h1>&#160;'.htmlentities(Dict::S($aExtraParams['chart_title']), ENT_QUOTES, 'UTF-8').'</h1></div>' : '';
			$sHtml = "$sTitle<div style=\"height:200px;width:100%\" class=\"dashboard_chart\" id=\"my_chart_$sId{$iChartCounter}\"><div style=\"height:200px;line-height:200px;vertical-align:center;text-align:center;width:100%\"><img src=\"../images/indicator.gif\"></div></div>\n";
			$sGroupBy = isset($aExtraParams['group_by']) ? $aExtraParams['group_by'] : '';
			$sGroupByExpr = isset($aExtraParams['group_by_expr']) ? '&params[group_by_expr]='.$aExtraParams['group_by_expr'] : '';
			$sFilter = $this->m_oFilter->serialize(false, $aQueryParams);
			$oContext = new ApplicationContext();
			$sContextParam = $oContext->GetForLink();
			$sAggregationFunction = isset($aExtraParams['aggregation_function']) ? $aExtraParams['aggregation_function'] : '';
			$sAggregationAttr = isset($aExtraParams['aggregation_attribute']) ? $aExtraParams['aggregation_attribute'] : '';
			$sLimit = isset($aExtraParams['limit']) ? $aExtraParams['limit'] : '';
			$sOrderBy = isset($aExtraParams['order_by']) ? $aExtraParams['order_by'] : '';
			$sOrderDirection = isset($aExtraParams['order_direction']) ? $aExtraParams['order_direction'] : '';

			if (isset($aExtraParams['group_by_label']))
			{
				$sUrl = json_encode(utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=chart&params[group_by]=$sGroupBy{$sGroupByExpr}&params[group_by_label]={$aExtraParams['group_by_label']}&params[chart_type]=$sChartType&params[currentId]=$sId{$iChartCounter}&params[order_direction]=$sOrderDirection&params[order_by]=$sOrderBy&params[limit]=$sLimit&params[aggregation_function]=$sAggregationFunction&params[aggregation_attribute]=$sAggregationAttr&id=$sId{$iChartCounter}&filter=".rawurlencode($sFilter).'&'.$sContextParam);
			}
			else
			{
				$sUrl = json_encode(utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=chart&params[group_by]=$sGroupBy{$sGroupByExpr}&params[chart_type]=$sChartType&params[currentId]=$sId{$iChartCounter}&params[order_direction]=$sOrderDirection&params[order_by]=$sOrderBy&params[limit]=$sLimit&params[aggregation_function]=$sAggregationFunction&params[aggregation_attribute]=$sAggregationAttr&id=$sId{$iChartCounter}&filter=".rawurlencode($sFilter).'&'.$sContextParam);
			}

			$oPage->add_ready_script(
<<<EOF
$.post($sUrl, {}, function(data) {
	$('body').append(data);
});
EOF
			);
			break;
			
			case 'chart_ajax':
			$sHtml = '';	
			$sChartType = isset($aExtraParams['chart_type']) ? $aExtraParams['chart_type'] : 'pie';
			$sId = utils::ReadParam('id', '');

			if (isset($aExtraParams['group_by']))
			{
				$this->MakeGroupByQuery($aExtraParams, $oGroupByExp, $sGroupByLabel, $aGroupBy, $sAggregationFunction, $sFctVar, $sAggregationAttr, $sSql);
				$aRes = CMDBSource::QueryToArray($sSql);
				$oContext = new ApplicationContext();
				$sContextParam = $oContext->GetForLink();

				$aGroupBy = array();
				$iTotalCount = 0;
				$aValues = array();
				$aURLs = array();
				foreach ($aRes as $iRow => $aRow)
				{
					$sValue = $aRow['grouped_by_1'];
					$sHtmlValue = $oGroupByExp->MakeValueLabel($this->m_oFilter, $sValue, $sValue);
					$aGroupBy[(int)$iRow] = (int) $aRow[$sFctVar];
					$iTotalCount += $aRow['_itop_count_'];
					$aValues[] = array('label' => html_entity_decode(strip_tags($sHtmlValue), ENT_QUOTES, 'UTF-8'), 'label_html' => $sHtmlValue, 'value' => (int) $aRow[$sFctVar]);
					
					// Build the search for this subset
					$oSubsetSearch = $this->m_oFilter->DeepClone();
					$oCondition = new BinaryExpression($oGroupByExp, '=', new ScalarExpression($sValue));
					$oSubsetSearch->AddConditionExpression($oCondition);
					$aURLs[] = utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search&format=html&filter=".rawurlencode($oSubsetSearch->serialize()).'&'.$sContextParam;
				}
				$sJSURLs = json_encode($aURLs);
			}
			
			switch($sChartType)
			{
				case 'bars':
				$aNames = array();
				foreach($aValues as $idx => $aValue)
				{
					$aNames[$idx] = $aValue['label'];
				}
				$sJSNames = json_encode($aNames);
				
				$sJson = json_encode($aValues);
				$oPage->add_ready_script(
<<<EOF

var chart = c3.generate({
    bindto: d3.select('#my_chart_$sId'),
    data: {
   	  json: $sJson,
      keys: {
      	x: 'label',
      	value: ["value"]
	  },
	  onclick: function (d, element) {
		var aURLs = $sJSURLs;
	    window.location.href = aURLs[d.index];
	  },
	  selection: {
		enabled: true
	  },
      type: 'bar'
    },
    axis: {
        x: {
			tick: {
				culling: {max: 25}, // Maximum 24 labels on x axis (2 years).
				centered: true,
				rotate: 90,
				multiline: false
			},
            type: 'category'   // this needed to load string x value
        }
    },
	grid: {
		y: {
			show: true
		}
	},
    legend: {
      show: false,
    },
	tooltip: {
	  grouped: false,
	  format: {
		title: function() { return '' },
	    name: function (name, ratio, id, index) {
			var aNames = $sJSNames;
			return aNames[index];
		}
	  }
	}
});

if (typeof(charts) === "undefined")
{
  charts = [];
}
charts.push(chart);
EOF
				);
				break;

				case 'pie':
				$aColumns = array();
				$aNames = array();
				foreach($aValues as $idx => $aValue)
				{
					$aColumns[] = array('series_'.$idx, (int)$aValue['value']);
					$aNames['series_'.$idx] = $aValue['label'];
				}
				$sJSColumns = json_encode($aColumns);
				$sJSNames = json_encode($aNames);
				$oPage->add_ready_script(
<<<EOF
var chart = c3.generate({
    bindto: d3.select('#my_chart_$sId'),
    data: {
    	columns: $sJSColumns,
      	type: 'pie',
		names: $sJSNames,
	    onclick: function (d, element) {
			var aURLs = $sJSURLs;
		    window.location.href= aURLs[d.index];
		},
		order: null,
    },
    legend: {
      show: true,
	  position: 'right',
    },
	tooltip: {
	  format: {
	    value: function (value, ratio, id) { return value; }
	  }
	}
});

if (typeof(charts) === "undefined")
{
  charts = [];
}
charts.push(chart);
EOF
				);
				break;				
			}
			break;
			
			default:
			// Unsupported style, do nothing.
			$sHtml .= Dict::format('UI:Error:UnsupportedStyleOfBlock', $this->m_sStyle);
		}


		$bAutoReload = false;
		if (isset($aExtraParams['auto_reload']))
		{
			if ($aExtraParams['auto_reload'] === true)
			{
				// Note: does not work in the switch (case true) because a positive number evaluates to true!!!
				$aExtraParams['auto_reload'] = 'standard';
			}
			switch($aExtraParams['auto_reload'])
			{
				case 'fast':
					$bAutoReload = true;
					$iReloadInterval = MetaModel::GetConfig()->GetFastReloadInterval()*1000;
					break;

				case 'standard':
				case 'true':
					$bAutoReload = true;
					$iReloadInterval = MetaModel::GetConfig()->GetStandardReloadInterval()*1000;
					break;

				default:
					if (is_numeric($aExtraParams['auto_reload']) && ($aExtraParams['auto_reload'] > 0))
					{
						$bAutoReload = true;
						$iReloadInterval = max(MetaModel::GetConfig()->Get('min_reload_interval'), $aExtraParams['auto_reload'])*1000;
					}
					else
					{
						// incorrect config, ignore it
						$bAutoReload = false;
					}
			}
		}
		if (($bAutoReload) && ($this->m_sStyle != 'search')) // Search form do NOT auto-reload
		{
			// Used either for asynchronous or auto_reload
			// does a json_encode twice to get a string usable as function parameter
			$sFilterBefore = $this->m_oFilter->serialize();
			$sFilter = json_encode($sFilterBefore);
			$sExtraParams = json_encode(json_encode($aExtraParams));

			$oPage->add_script(
				<<<JS
if (typeof window.oAutoReloadBlock == "undefined") {
    window.oAutoReloadBlock = {};
}
if (typeof window.oAutoReloadBlock['$sId'] != "undefined") {
    clearInterval(window.oAutoReloadBlock['$sId']);
}

window.oAutoReloadBlock['$sId'] = setInterval(function() {
	ReloadBlock('$sId', '{$this->m_sStyle}', $sFilter, $sExtraParams);
}, '$iReloadInterval');
JS
			);
		}

		return $sHtml;
	}

	/**
	 * Add a condition (restriction) to the current DBSearch on which the display block is based
	 * taking into account the hierarchical keys for which the condition is based on the 'below' operator
	 *
	 * @param string $sFilterCode
	 * @param array $condition
	 * @param string $sOpCode
	 * @param bool $bParseSearchString
	 *
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \Exception
	 */
	protected function AddCondition($sFilterCode, $condition, $sOpCode = null, $bParseSearchString = false)
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
			$this->m_oFilter->AddCondition($sFilterCode, $condition, null); // Use the default 'loose' operator
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

	/**
	 * For the result to be meaningful, this function must be called AFTER GetRenderContent() (or Display())
	 * @return int
	 */
	public function GetDisplayedCount()
	{
		return $this->m_oSet->Count();
	}

	/**
	 * @param $aExtraParams
	 * @param $oGroupByExp
	 * @param $sGroupByLabel
	 * @param $aGroupBy
	 * @param $sAggregationFunction
	 * @param $sFctVar
	 * @param $sAggregationAttr
	 * @param $sSql
	 *
	 * @throws \Exception
	 */
	protected function MakeGroupByQuery(&$aExtraParams, &$oGroupByExp, &$sGroupByLabel, &$aGroupBy, &$sAggregationFunction, &$sFctVar, &$sAggregationAttr, &$sSql)
	{
		$sAlias = $this->m_oFilter->GetClassAlias();
		if (isset($aExtraParams['group_by_label']))
		{
			$oGroupByExp = Expression::FromOQL($aExtraParams['group_by']);
			$sGroupByLabel = $aExtraParams['group_by_label'];
		}
		else
		{
			// Backward compatibility: group_by is simply a field id
			$oGroupByExp = new FieldExpression($aExtraParams['group_by'], $sAlias);
			$sGroupByLabel = MetaModel::GetLabel($this->m_oFilter->GetClass(), $aExtraParams['group_by']);
		}

		// Security filtering
		$aFields = $oGroupByExp->ListRequiredFields();
		foreach($aFields as $sFieldAlias)
		{
			$aMatches = array();
			if (preg_match('/^([^.]+)\\.([^.]+)$/', $sFieldAlias, $aMatches))
			{
				$sFieldClass = $this->m_oFilter->GetClassName($aMatches[1]);
				$oAttDef = MetaModel::GetAttributeDef($sFieldClass, $aMatches[2]);
				if ($oAttDef instanceof AttributeOneWayPassword)
				{
					throw new Exception('Grouping on password fields is not supported.');
				}
			}
		}

		$aGroupBy = array();
		$aGroupBy['grouped_by_1'] = $oGroupByExp;
		$aQueryParams = array();
		if (isset($aExtraParams['query_params']))
		{
			$aQueryParams = $aExtraParams['query_params'];
		}
		$aFunctions = array();
		$sAggregationFunction = 'count';
		$sFctVar = '_itop_count_';
		$sAggregationAttr = '';
		if (isset($aExtraParams['aggregation_function']) && !empty($aExtraParams['aggregation_attribute']))
		{
			$sAggregationFunction = $aExtraParams['aggregation_function'];
			$sAggregationAttr = $aExtraParams['aggregation_attribute'];
			$oAttrExpr = Expression::FromOQL('`'.$sAlias.'`.`'.$sAggregationAttr.'`');
			$oFctExpr = new FunctionExpression(strtoupper($sAggregationFunction), array($oAttrExpr));
			$sFctVar = '_itop_'.$sAggregationFunction.'_';
			$aFunctions = array($sFctVar => $oFctExpr);
		}

		if (!empty($sAggregationAttr))
		{
			$sClass = $this->m_oFilter->GetClass();
			$sAggregationAttr = MetaModel::GetLabel($sClass, $sAggregationAttr);
		}
		$iLimit = 0;
		if (isset($aExtraParams['limit']))
		{
			$iLimit = intval($aExtraParams['limit']);
		}
		$aOrderBy = array();
		if (isset($aExtraParams['order_direction']) && isset($aExtraParams['order_by']))
		{
			switch ($aExtraParams['order_by'])
			{
				case 'attribute':
					$aOrderBy = array('grouped_by_1' => ($aExtraParams['order_direction'] === 'asc'));
					break;
				case 'function':
					$aOrderBy = array($sFctVar => ($aExtraParams['order_direction'] === 'asc'));
					break;
			}
		}

		$sSql = $this->m_oFilter->MakeGroupByQuery($aQueryParams, $aGroupBy, true, $aFunctions, $aOrderBy, $iLimit);
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
	protected $iLimitCount;
	protected $iLimitStart;
	
	public function __construct(DBSearch $oFilter, $sStyle = 'list', $bAsynchronous = false, $aParams = array(), $oSet = null)
	{
		parent::__construct($oFilter, $sStyle, $bAsynchronous, $aParams, $oSet);
		$this->iLimitStart = 0;
		$this->iLimitCount = 0;
	}
	
	public function SetLimit($iCount, $iStart = 0)
	{
		$this->iLimitStart = $iStart;
		$this->iLimitCount = $iCount;
	}
	
	public function GetRenderContent(WebPage $oPage, $aExtraParams = array(), $sId)
	{
		$sHtml = '';
		$bTruncated = false;
		$oSet = new CMDBObjectSet($this->m_oFilter, array('date'=>false));
		if (!$oPage->IsPrintableVersion())
		{
			if (($this->iLimitStart > 0) || ($this->iLimitCount > 0))
			{
				$oSet->SetLimit($this->iLimitCount, $this->iLimitStart);
				if (($this->iLimitCount - $this->iLimitStart) < $oSet->Count())
				{
					$bTruncated = true;
				}
			}
		}
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
			if ($bTruncated)
			{
				$sFilter = htmlentities($this->m_oFilter->serialize(), ENT_QUOTES, 'UTF-8');
				$sHtml .= '<div id="history_container"><p>';
				$sHtml .= Dict::Format('UI:TruncatedResults', $this->iLimitCount, $oSet->Count());
				$sHtml .= ' ';
				$sHtml .= '<a href="#" onclick="DisplayHistory(\'#history_container\', \''.$sFilter.'\', 0, 0); return false;">'.Dict::S('UI:DisplayAll').'</a>';
				$sHtml .= $this->GetHistoryTable($oPage, $oSet);
				$sHtml .= '</p></div>';
				$oPage->add_ready_script("$('#{$sId} table.listResults tr:last td').addClass('truncated');");
			}
			else
			{
				$sHtml .= $this->GetHistoryTable($oPage, $oSet);
			}
			$oPage->add_ready_script(InlineImage::FixImagesWidth());
		
			$oPage->add_ready_script("$('.case-log-history-entry-toggle').on('click', function () { $(this).closest('.case-log-history-entry').toggleClass('expanded');});");
			$oPage->add_ready_script(
<<<EOF
$('.history_entry').each(function() {
	var jMe = $(this);
	var oContent = $(this).find('.history_html_content');
	if (jMe.height() < oContent.height())
	{
			jMe.prepend('<span class="history_truncated_toggler"></span>');
			jMe.find('.history_truncated_toggler').on('click', function() {
				jMe.toggleClass('history_entry_truncated');
			});
	}
});
EOF
			);
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
						  'log' => array('label' => Dict::S('UI:History:Changes') , 'description' => Dict::S('UI:History:Changes+')),
						 );
		$aValues = array();
		foreach($aChanges as $aChange)
		{
			$aValues[] = array('date' => AttributeDateTime::GetFormat()->Format($aChange['date']), 'userinfo' => htmlentities($aChange['userinfo'], ENT_QUOTES, 'UTF-8'), 'log' => "<ul><li>".implode('</li><li>', $aChange['log'])."</li></ul>");
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
	 *
	 * @param \WebPage $oPage
	 * @param array $aExtraParams
	 * @param string $sId
	 *
	 * @return string
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
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
		$oReflectionClass = new ReflectionClass($sClass);
		$oSet = new CMDBObjectSet($this->m_oFilter);
		$sFilter = $this->m_oFilter->serialize();
		$aActions = array();
		$sUIPage = cmdbAbstractObject::ComputeStandardUIPage($sClass);
		$sRootUrl = utils::GetAbsoluteUrlAppRoot();
		// Common params that will be applied to actions
        $aActionParams = array();
		if(isset($aExtraParams['menu_actions_target']))
        {
            $aActionParams['target'] = $aExtraParams['menu_actions_target'];
        }
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
		$bIsCreationAllowed = (UserRights::IsActionAllowed($sClass, UR_ACTION_CREATE) == UR_ALLOWED_YES) && ($oReflectionClass->IsSubclassOf('cmdbAbstractObject'));
		$sRefreshAction = '';
		switch($oSet->Count())
		{
			case 0:
			// No object in the set, the only possible action is "new"
			if ($bIsCreationAllowed) { $aActions['UI:Menu:New'] = array ('label' => Dict::S('UI:Menu:New'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=new&class=$sClass{$sContext}{$sDefault}") + $aActionParams; }
			break;
			
			case 1:
			$oObj = $oSet->Fetch();
			if (is_null($oObj))
			{
				if (!isset($aExtraParams['link_attr']))
				{
					if ($bIsCreationAllowed) { $aActions['UI:Menu:New'] = array ('label' => Dict::S('UI:Menu:New'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=new&class=$sClass{$sContext}{$sDefault}") + $aActionParams; }
				}
			}
			else
			{
				$id = $oObj->GetKey();
				if (utils::ReadParam('operation') == 'details')
				{
					if ($_SERVER['REQUEST_METHOD'] == 'GET')
					{
						$sRefreshAction = "window.location.reload();";
					}
					else
					{
						$sRefreshAction = "window.location.href='".ApplicationContext::MakeObjectUrl(get_class($oObj), $id)."';";
					}
				}
				
				$bLocked = false;
				if (MetaModel::GetConfig()->Get('concurrent_lock_enabled'))
				{
					$aLockInfo = iTopOwnershipLock::IsLocked(get_class($oObj), $id);
					if ($aLockInfo['locked'])
					{
						$bLocked = true;
						//$this->AddMenuSeparator($aActions);
						//$aActions['concurrent_lock_unlock'] = array ('label' => Dict::S('UI:Menu:ReleaseConcurrentLock'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=kill_lock&class=$sClass&id=$id{$sContext}");
					}
				}
				$bRawModifiedAllowed = (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_YES) && ($oReflectionClass->IsSubclassOf('cmdbAbstractObject'));
				$bIsModifyAllowed = !$bLocked && $bRawModifiedAllowed;
				$bIsDeleteAllowed = !$bLocked && UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, $oSet);
				// Just one object in the set, possible actions are "new / clone / modify and delete"
				if (!isset($aExtraParams['link_attr']))
				{
					if ($bIsModifyAllowed) { $aActions['UI:Menu:Modify'] = array ('label' => Dict::S('UI:Menu:Modify'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=modify&class=$sClass&id=$id{$sContext}#") + $aActionParams; }
					if ($bIsCreationAllowed) { $aActions['UI:Menu:New'] = array ('label' => Dict::S('UI:Menu:New'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=new&class=$sClass{$sContext}{$sDefault}") + $aActionParams; }
					if ($bIsDeleteAllowed) { $aActions['UI:Menu:Delete'] = array ('label' => Dict::S('UI:Menu:Delete'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=delete&class=$sClass&id=$id{$sContext}") + $aActionParams; }
					// Transitions / Stimuli
					if (!$bLocked)
					{
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
									$aActions[$sStimulusCode] = array('label' => $aStimuli[$sStimulusCode]->GetLabel(), 'url' => "{$sRootUrl}pages/UI.php?operation=stimulus&stimulus=$sStimulusCode&class=$sClass&id=$id{$sContext}") + $aActionParams;
									break;
									
									default:
									// Do nothing
								}
							}
						}
					}
					// Relations...
					$aRelations = MetaModel::EnumRelationsEx($sClass);
					if (count($aRelations))
					{
						$this->AddMenuSeparator($aActions);
						foreach($aRelations as $sRelationCode => $aRelationInfo)
						{
							if (array_key_exists('down', $aRelationInfo))
							{
								$aActions[$sRelationCode.'_down'] = array ('label' => $aRelationInfo['down'], 'url' => "{$sRootUrl}pages/$sUIPage?operation=swf_navigator&relation=$sRelationCode&direction=down&class=$sClass&id=$id{$sContext}") + $aActionParams;
							}
							if (array_key_exists('up', $aRelationInfo))
							{
								$aActions[$sRelationCode.'_up'] = array ('label' => $aRelationInfo['up'], 'url' => "{$sRootUrl}pages/$sUIPage?operation=swf_navigator&relation=$sRelationCode&direction=up&class=$sClass&id=$id{$sContext}") + $aActionParams;
							}
						}
					}
					if ($bLocked && $bRawModifiedAllowed)
					{
						// Add a special menu to kill the lock, but only to allowed users who can also modify this object
						/** @var array $aAllowedProfiles */
						$aAllowedProfiles = MetaModel::GetConfig()->Get('concurrent_lock_override_profiles');
						$bCanKill = false;
					
						$oUser = UserRights::GetUserObject();
						$aUserProfiles = array();
						if (!is_null($oUser))
						{
							$oProfileSet = $oUser->Get('profile_list');
							while ($oProfile = $oProfileSet->Fetch())
							{
								$aUserProfiles[$oProfile->Get('profile')] = true;
							}
						}
		
						foreach($aAllowedProfiles as $sProfile)
						{
							if (array_key_exists($sProfile, $aUserProfiles))
							{	
								$bCanKill = true;
								break;
							}
						}
						
						if ($bCanKill)
						{		
							$this->AddMenuSeparator($aActions);
							$aActions['concurrent_lock_unlock'] = array ('label' => Dict::S('UI:Menu:KillConcurrentLock'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=kill_lock&class=$sClass&id=$id{$sContext}");
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
				/** @var \iApplicationUIExtension $oExtensionInstance */
				foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
				{
					$oSet->Rewind();
					foreach($oExtensionInstance->EnumAllowedActions($oSet) as $sLabel => $sUrl)
					{
						$aActions[$sLabel] = array ('label' => $sLabel, 'url' => $sUrl) + $aActionParams;
					}
				}
			}
			break;
			
			default:
			// Check rights
			// New / Modify
			$bIsModifyAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) && ($oReflectionClass->IsSubclassOf('cmdbAbstractObject'));
			$bIsBulkModifyAllowed = (!MetaModel::IsAbstract($sClass)) && UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY, $oSet) && ($oReflectionClass->IsSubclassOf('cmdbAbstractObject'));
			$bIsBulkDeleteAllowed = UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_DELETE, $oSet);
			if (isset($aExtraParams['link_attr']))
			{
				$id = $aExtraParams['object_id'];
				$sTargetAttr = $aExtraParams['target_attr'];
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sTargetAttr);
				$sTargetClass = $oAttDef->GetTargetClass();
				if ($bIsModifyAllowed) { $aActions['UI:Menu:Add'] = array ('label' => Dict::S('UI:Menu:Add'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=modify_links&class=$sClass&link_attr=".$aExtraParams['link_attr']."&target_class=$sTargetClass&id=$id&addObjects=true{$sContext}") + $aActionParams; }
				if ($bIsBulkModifyAllowed) { $aActions['UI:Menu:Manage'] = array ('label' => Dict::S('UI:Menu:Manage'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=modify_links&class=$sClass&link_attr=".$aExtraParams['link_attr']."&target_class=$sTargetClass&id=$id{$sContext}") + $aActionParams; }
				//if ($bIsBulkDeleteAllowed) { $aActions[] = array ('label' => 'Remove All...', 'url' => "#") + $aActionParams; }
			}
			else
			{
				// many objects in the set, possible actions are: new / modify all / delete all
				if ($bIsCreationAllowed) { $aActions['UI:Menu:New'] = array ('label' => Dict::S('UI:Menu:New'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=new&class=$sClass{$sContext}{$sDefault}") + $aActionParams; }
				if ($bIsBulkModifyAllowed) { $aActions['UI:Menu:ModifyAll'] = array ('label' => Dict::S('UI:Menu:ModifyAll'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=select_for_modify_all&class=$sClass&filter=".urlencode($sFilter)."{$sContext}") + $aActionParams; }
				if ($bIsBulkDeleteAllowed) { $aActions['UI:Menu:BulkDelete'] = array ('label' => Dict::S('UI:Menu:BulkDelete'), 'url' => "{$sRootUrl}pages/$sUIPage?operation=select_for_deletion&filter=".urlencode($sFilter)."{$sContext}") + $aActionParams; }

				// Stimuli
				$aStates = MetaModel::EnumStates($sClass);
				// Do not perform time consuming computations if there are too may objects in the list
				$iLimit = MetaModel::GetConfig()->Get('complex_actions_limit');
				
				if ((count($aStates) > 0) && (($iLimit == 0) || ($oSet->CountWithLimit($iLimit + 1) < $iLimit)))
				{
					// Life cycle actions may be available... if all objects are in the same state
					//
					// Group by <state>
					$oGroupByExp = new FieldExpression(MetaModel::GetStateAttributeCode($sClass), $this->m_oFilter->GetClassAlias());
					$aGroupBy = array('__state__' => $oGroupByExp);
					$aQueryParams = array();
					if (isset($aExtraParams['query_params']))
					{
						$aQueryParams = $aExtraParams['query_params'];
					}
					$sSql = $this->m_oFilter->MakeGroupByQuery($aQueryParams, $aGroupBy);
					$aRes = CMDBSource::QueryToArray($sSql);
					if (count($aRes) == 1)
					{
						// All objects are in the same state...
						$sState = $aRes[0]['__state__'];
						$aTransitions = Metamodel::EnumTransitions($sClass, $sState);
						if (count($aTransitions))
						{
							$this->AddMenuSeparator($aActions);
							$aStimuli = Metamodel::EnumStimuli($sClass);
							foreach($aTransitions as $sStimulusCode => $aTransitionDef)
							{
								$oSet->Rewind();
								// As soon as the user rights implementation will browse the object set,
								// then we might consider using OptimizeColumnLoad() here
								$iActionAllowed = UserRights::IsStimulusAllowed($sClass, $sStimulusCode, $oSet);
								$iActionAllowed = (get_class($aStimuli[$sStimulusCode]) == 'StimulusUserAction') ? $iActionAllowed : UR_ALLOWED_NO;
								switch($iActionAllowed)
								{
									case UR_ALLOWED_YES:
									case UR_ALLOWED_DEPENDS:
									$aActions[$sStimulusCode] = array('label' => $aStimuli[$sStimulusCode]->GetLabel(), 'url' => "{$sRootUrl}pages/UI.php?operation=select_bulk_stimulus&stimulus=$sStimulusCode&state=$sState&class=$sClass&filter=".urlencode($sFilter)."{$sContext}") + $aActionParams;
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
		/** @var \iApplicationUIExtension $oExtensionInstance */
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
					$aActions[$sLabel] = array ('label' => $sLabel, 'url' => $data) + $aActionParams;
				}
			}
		}
		$param  =  null;
		$iMenuId = null;
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

		if (!$oPage->IsPrintableVersion())
		{
			if (count($aFavoriteActions) > 0)
			{
				$sHtml .= "<div class=\"itop_popup actions_menu\"><ul>\n<li>".Dict::S('UI:Menu:OtherActions')."<i class=\"fas fa-caret-down\"></i>"."\n<ul>\n";
			}
			else
			{
				$sHtml .= "<div class=\"itop_popup actions_menu\"><ul>\n<li>".Dict::S('UI:Menu:Actions')."<i class=\"fas fa-caret-down\"></i>"."\n<ul>\n";
			}

			$sHtml .= $oPage->RenderPopupMenuItems($aActions, $aFavoriteActions);

			if ($this->m_sStyle == 'details')
			{
				$sSearchAction = "window.location=\"{$sRootUrl}pages/UI.php?operation=search_form&do_search=0&class=$sClass{$sContext}\"";
				$sHtml .= "<div class=\"actions_button icon_actions_button\" title=\"".htmlentities(Dict::Format('UI:SearchFor_Class',
						MetaModel::GetName($sClass)), ENT_QUOTES, 'UTF-8')."\"><span class=\"search-button fas fa-search\" onclick='$sSearchAction'></span></div>";
			}


            if (empty($sRefreshAction) && $this->m_sStyle == 'list')
            {
                //for the detail page this var is defined way beyond this line
                $sRefreshAction = "window.location.reload();";
            }
			if (!$oPage->IsPrintableVersion() && ($sRefreshAction!=''))
			{
				$sHtml .= "<div class=\"actions_button icon_actions_button\" title=\"".htmlentities(Dict::S('UI:Button:Refresh'),
						ENT_QUOTES, 'UTF-8')."\"><span class=\"refresh-button fas fa-sync\" onclick=\"$sRefreshAction\"></span></div>";
			}


		}

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
	 * @param array $aActions The current actions list
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
