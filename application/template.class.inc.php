<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Class DisplayTemplate
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/application/displayblock.class.inc.php');
/**
 * This class manages the special template format used internally to build the iTop web pages
 */
class DisplayTemplate
{
	protected $m_sTemplate;
	protected $m_aTags;
	static protected $iBlockCount = 0;
	
	public function __construct($sTemplate)
	{
		$this->m_aTags = array (
			'itopblock',
			'itopcheck',
			'itoptabs',
			'itoptab',
			'itoptoggle',
			'itopstring',
			'sqlblock'
		);
		$this->m_sTemplate = $sTemplate;
	}
	
	public function Render(WebPage $oPage, $aParams = array())
	{
		$this->m_sTemplate = MetaModel::ApplyParams($this->m_sTemplate, $aParams);
		$iStart = 0;
		$iEnd = strlen($this->m_sTemplate);
		$iCount = 0;
		$iBeforeTagPos = $iStart;
		$iAfterTagPos = $iStart;
		while($sTag = $this->GetNextTag($iStart, $iEnd))
		{
			$sContent = $this->GetTagContent($sTag, $iStart, $iEnd);
			$iAfterTagPos = $iEnd + strlen('</'.$sTag.'>');
			$sOuterTag = substr($this->m_sTemplate, $iStart, $iAfterTagPos - $iStart);
			$oPage->add(substr($this->m_sTemplate, $iBeforeTagPos, $iStart - $iBeforeTagPos));
			if ($sTag == DisplayBlock::TAG_BLOCK)
			{
				try
				{
					$oBlock = DisplayBlock::FromTemplate($sOuterTag);
					if (is_object($oBlock))
					{
						$oBlock->Display($oPage, 'block_'.self::$iBlockCount, $aParams);
					}
				}
				catch(OQLException $e)
				{
					$oPage->p('Error in template (please contact your administrator) - Invalid query<!--'.$sOuterTag.'-->');
				}
				catch(Exception $e)
				{
					$oPage->p('Error in template (please contact your administrator)<!--'.$e->getMessage().'--><!--'.$sOuterTag.'-->');
				}
				
				self::$iBlockCount++;
			}
			else
			{
				$aAttributes = $this->GetTagAttributes($sTag, $iStart, $iEnd);
				//$oPage->p("Tag: $sTag - ($iStart, $iEnd)");
				$this->RenderTag($oPage, $sTag, $aAttributes, $sContent);
	
			}
			$iAfterTagPos = $iEnd + strlen('</'.$sTag.'>');
			$iBeforeTagPos = $iAfterTagPos;
			$iStart = $iEnd;
			$iEnd = strlen($this->m_sTemplate); 
			$iCount++;
		}
		$oPage->add(substr($this->m_sTemplate, $iAfterTagPos));
	}
	
	public function GetNextTag(&$iStartPos, &$iEndPos)
	{
		$iChunkStartPos = $iStartPos;
		$sNextTag = null;
		$iStartPos = $iEndPos;
		foreach($this->m_aTags as $sTag)
		{
			// Search for the opening tag
			$iOpeningPos = stripos($this->m_sTemplate, '<'.$sTag.' ', $iChunkStartPos);
			if ($iOpeningPos === false)
			{
				$iOpeningPos = stripos($this->m_sTemplate, '<'.$sTag.'>', $iChunkStartPos);
			}
			if ($iOpeningPos !== false)
			{
				$iClosingPos = stripos($this->m_sTemplate, '</'.$sTag.'>', $iOpeningPos);
			}
			if ( ($iOpeningPos !== false) && ($iClosingPos !== false))
			{
				if ($iOpeningPos < $iStartPos)
				{
					// This is the next tag
					$iStartPos = $iOpeningPos;
					$iEndPos = $iClosingPos;
					$sNextTag = $sTag;
				}
			}
		}
		return $sNextTag;
	}
	
	public function GetTagContent($sTag, $iStartPos, $iEndPos)
	{
		$sContent  = "";
		$iContentStart = strpos($this->m_sTemplate, '>', $iStartPos); // Content of tag start immediatly after the first closing bracket
		if ($iContentStart !== false)
		{
			$sContent = substr($this->m_sTemplate, 1+$iContentStart, $iEndPos - $iContentStart - 1);
		}
		return $sContent;
	}

	public function GetTagAttributes($sTag, $iStartPos, $iEndPos)
	{
		$aAttr  = array();
		$iAttrStart = strpos($this->m_sTemplate, ' ', $iStartPos); // Attributes start just after the first space
		$iAttrEnd = strpos($this->m_sTemplate, '>', $iStartPos); // Attributes end just before the first closing bracket
		if ( ($iAttrStart !== false) && ($iAttrEnd !== false) && ($iAttrEnd > $iAttrStart))
		{
			$sAttributes = substr($this->m_sTemplate, 1+$iAttrStart, $iAttrEnd - $iAttrStart - 1);
			$aAttributes = explode(' ', $sAttributes);
			foreach($aAttributes as $sAttr)
			{
				if ( preg_match('/(.+) *= *"(.+)"$/', $sAttr, $aMatches) )
				{
					$aAttr[strtolower($aMatches[1])] = $aMatches[2];
				}
			}
		}
		return $aAttr;
	}
	
	protected function RenderTag($oPage, $sTag, $aAttributes, $sContent)
	{
		static $iTabContainerCount = 0;
		switch($sTag)
		{
			case 'itoptabs':
				$oPage->AddTabContainer('Tabs_'.$iTabContainerCount);
				$oPage->SetCurrentTabContainer('Tabs_'.$iTabContainerCount);
				$iTabContainerCount++;
				//$oPage->p('Content:<pre>'.htmlentities($sContent, ENT_QUOTES, 'UTF-8').'</pre>');
				$oTemplate = new DisplayTemplate($sContent);
				$oTemplate->Render($oPage, array()); // no params to apply, they have already been applied
				$oPage->SetCurrentTabContainer('');
			break;
			
			case 'itopcheck':
				$sClassName = $aAttributes['class'];
				if (MetaModel::IsValidClass($sClassName) && UserRights::IsActionAllowed($sClassName, UR_ACTION_READ))
				{
					$oTemplate = new DisplayTemplate($sContent);
					$oTemplate->Render($oPage, array()); // no params to apply, they have already been applied
				}
				else
				{
					// Leave a trace for those who'd like to understand why nothing is displayed
					$oPage->add("<!-- class $sClassName does not exist, skipping some part of the template -->\n");
				}
			break;
			
			case 'itoptab':
				$oPage->SetCurrentTab(Dict::S(str_replace('_', ' ', $aAttributes['name'])));
				$oTemplate = new DisplayTemplate($sContent);
				$oTemplate->Render($oPage, array()); // no params to apply, they have already been applied
				//$oPage->p('iTop Tab Content:<pre>'.htmlentities($sContent, ENT_QUOTES, 'UTF-8').'</pre>');
				$oPage->SetCurrentTab('');
			break;
			
			case 'itoptoggle':
				$sName = isset($aAttributes['name']) ? $aAttributes['name'] : 'Tagada';
				$bOpen = isset($aAttributes['open']) ? $aAttributes['open'] : true;
				$oPage->StartCollapsibleSection(Dict::S($sName), $bOpen);
				$oTemplate = new DisplayTemplate($sContent);
				$oTemplate->Render($oPage, array()); // no params to apply, they have already been applied
				//$oPage->p('iTop Tab Content:<pre>'.htmlentities($sContent, ENT_QUOTES, 'UTF-8').'</pre>');
				$oPage->EndCollapsibleSection();
			break;
			
			case 'itopstring':
				$oPage->add(Dict::S($sContent));
			break;
			
			case 'sqlblock':
				$oBlock = SqlBlock::FromTemplate($sContent);
				$oBlock->RenderContent($oPage);
			break;
			
			case 'itopblock': // No longer used, handled by DisplayBlock::FromTemplate see above
				$oPage->add("<!-- Application Error: should be handled by DisplayBlock::FromTemplate -->");
			break;
			
			default:
				// Unknown tag, just ignore it or now -- output an HTML comment
				$oPage->add("<!-- unsupported tag: $sTag -->");
		}
	}
	
	/**
	 * Unit test
	 */
	static public function UnitTest()
	{
		require_once(APPROOT.'/application/startup.inc.php');
		require_once(APPROOT."/application/itopwebpage.class.inc.php");
		
		$sTemplate = '<div class="page_header">
		<div class="actions_details"><a href="#"><span>Actions</span></a></div>
		<h1>$class$: <span class="hilite">$name$</span></h1>
		<itopblock blockclass="HistoryBlock" type="toggle" encoding="text/oql">SELECT CMDBChangeOp WHERE objkey = $id$ AND objclass = \'$class$\'</itopblock>
		</div>
		<img src="../../images/connect_to_network.png" style="margin-top:-10px; margin-right:10px; float:right">
		<itoptabs>
			<itoptab name="Interfaces">
				<itopblock blockclass="DisplayBlock" type="list" encoding="text/oql">SELECT Interface AS i WHERE i.device_id = $id$</itopblock>
			</itoptab>
			<itoptab name="Contacts">
				<itopblock blockclass="DisplayBlock" type="list" encoding="text/oql">SELECT Contact AS c JOIN lnkContactToCI AS l ON l.contact_id = c.id WHERE l.ci_id = $id$</itopblock>
			</itoptab>
			<itoptab name="Documents">
				<itopblock blockclass="DisplayBlock" type="list" encoding="text/oql">SELECT Document AS d JOIN lnkDocumentToCI as l ON l.document_id = d.id WHERE l.ci_id = $id$)</itopblock>
			</itoptab>
		</itoptabs>';
		
		$oPage = new iTopWebPage('Unit Test');
		//$oPage->add("Template content: <pre>".htmlentities($sTemplate, ENT_QUOTES, 'UTF-8')."</pre>\n");
		$oTemplate = new DisplayTemplate($sTemplate);
		$oTemplate->Render($oPage, array('class'=>'Network device','pkey'=> 271, 'name' => 'deliversw01.mecanorama.fr', 'org_id' => 3));
		$oPage->output();
	}
}

/**
 * Special type of template for displaying the details of an object
 * On top of the defaut 'blocks' managed by the parent class, the following placeholders
 * are available in such a template:
 * $attribute_code$ An attribute of the object (in edit mode this is the input for the attribute)
 * $attribute_code->label()$ The label of an attribute
 * $PlugIn:plugInClass->properties()$ The ouput of OnDisplayProperties of the specified plugInClass
 */
class ObjectDetailsTemplate extends DisplayTemplate
{
	public function __construct($sTemplate, $oObj, $sFormPrefix = '')
	{
		parent::__construct($sTemplate);
		$this->m_oObj = $oObj;
		$this->m_sPrefix = $sFormPrefix;
	}
	
	public function Render(WebPage $oPage, $aParams = array(), $bEditMode = false)
	{
		$sStateAttCode = MetaModel :: GetStateAttributeCode(get_class($this->m_oObj));
		$aTemplateFields = array();
		preg_match_all('/\\$this->([a-z0-9_]+)\\$/', $this->m_sTemplate, $aMatches);
		foreach ($aMatches[1] as $sAttCode)
		{
			if (MetaModel::IsValidAttCode(get_class($this->m_oObj), $sAttCode))
			{
				$aTemplateFields[] = $sAttCode;
			}
			else
			{
				$aParams['this->'.$sAttCode] = "<!--Unknown attribute: $sAttCode-->";					
			}
		}
		preg_match_all('/\\$this->field\\(([a-z0-9_]+)\\)\\$/', $this->m_sTemplate, $aMatches);
		foreach ($aMatches[1] as $sAttCode)
		{
			if (MetaModel::IsValidAttCode(get_class($this->m_oObj), $sAttCode))
			{
				$aTemplateFields[] = $sAttCode;
			}
			else
			{
				$aParams['this->field('.$sAttCode.')'] = "<!--Unknown attribute: $sAttCode-->";
			}
		}
		$aFieldsComments = (isset($aParams['fieldsComments'])) ? $aParams['fieldsComments'] : array();
		$aFieldsMap = array();

		$sClass = get_class($this->m_oObj);
		// Renders the fields used in the template
		foreach(MetaModel::ListAttributeDefs(get_class($this->m_oObj)) as $sAttCode => $oAttDef)
		{
			$aParams['this->label('.$sAttCode.')'] = $oAttDef->GetLabel();
			$aParams['this->comments('.$sAttCode.')'] = isset($aFieldsComments[$sAttCode]) ? $aFieldsComments[$sAttCode] : '';
			$iInputId = '2_'.$sAttCode; // TODO: generate a real/unique prefix...
			if (in_array($sAttCode, $aTemplateFields))
			{
				if ($this->m_oObj->IsNew())
				{
					$iFlags = $this->m_oObj->GetInitialStateAttributeFlags($sAttCode);
				}
				else
				{
				$iFlags = $this->m_oObj->GetAttributeFlags($sAttCode);
				}
				if (($iFlags & OPT_ATT_MANDATORY) && $this->m_oObj->IsNew())
				{
					$iFlags = $iFlags & ~OPT_ATT_READONLY; // Mandatory fields cannot be read-only when creating an object
				}
				
				if ((!$oAttDef->IsWritable()) || ($sStateAttCode == $sAttCode))
				{
					$iFlags = $iFlags | OPT_ATT_READONLY;
				}

				if ($iFlags & OPT_ATT_HIDDEN)
				{
					$aParams['this->label('.$sAttCode.')'] = '';
					$aParams['this->field('.$sAttCode.')'] = '';
					$aParams['this->comments('.$sAttCode.')'] = '';
					$aParams['this->'.$sAttCode] = '';
				}
				else
				{
					if ($bEditMode && ($iFlags & (OPT_ATT_READONLY|OPT_ATT_SLAVE)))
					{
						// Check if the attribute is not read-only because of a synchro...
						$aReasons = array();
						$sSynchroIcon = '';
						if ($iFlags & OPT_ATT_SLAVE)
						{
							$iSynchroFlags = $this->m_oObj->GetSynchroReplicaFlags($sAttCode, $aReasons);
							$sSynchroIcon = "&nbsp;<img id=\"synchro_$sInputId\" src=\"../images/transp-lock.png\" style=\"vertical-align:middle\"/>";
							$sTip = '';
							foreach($aReasons as $aRow)
							{
								$sTip .= "<p>Synchronized with {$aRow['name']} - {$aRow['description']}</p>";
							}
							$oPage->add_ready_script("$('#synchro_$iInputId').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
						}
	
						// Attribute is read-only
						$sHTMLValue = "<span id=\"field_{$iInputId}\">".$this->m_oObj->GetAsHTML($sAttCode);
						$sHTMLValue .= '<input type="hidden" id="'.$iInputId.'" name="attr_'.$sAttCode.'" value="'.htmlentities($this->m_oObj->Get($sAttCode), ENT_QUOTES, 'UTF-8').'"/></span>';
						$aFieldsMap[$sAttCode] = $iInputId;
						$aParams['this->comments('.$sAttCode.')'] = $sSynchroIcon;
					}
	
					if ($bEditMode && !($iFlags & OPT_ATT_READONLY)) //TODO: check the data synchro status...
					{
						$aParams['this->field('.$sAttCode.')'] = "<span id=\"field_{$iInputId}\">".$this->m_oObj->GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef,
						$this->m_oObj->Get($sAttCode),
						$this->m_oObj->GetEditValue($sAttCode),
						$iInputId, // InputID
						'',
						$iFlags,
						array('this' => $this->m_oObj) // aArgs
					).'</span>';
					$aFieldsMap[$sAttCode] = $iInputId;
				}
				else 
				{
						$aParams['this->field('.$sAttCode.')'] = $this->m_oObj->GetAsHTML($sAttCode);
					}
					$aParams['this->'.$sAttCode] = "<table class=\"field\"><tr><td class=\"label\">".$aParams['this->label('.$sAttCode.')'].":</td><td>".$aParams['this->field('.$sAttCode.')']."</td><td>".$aParams['this->comments('.$sAttCode.')']."</td></tr></table>";					
				}
			}
		}
		
		// Renders the PlugIns used in the template
		preg_match_all('/\\$PlugIn:([A-Za-z0-9_]+)->properties\\(\\)\\$/', $this->m_sTemplate, $aMatches);
		$aPlugInProperties = $aMatches[1];
		foreach($aPlugInProperties as $sPlugInClass)
		{
			$oInstance = MetaModel::GetPlugins('iApplicationUIExtension', $sPlugInClass);
			if ($oInstance != null) // Safety check...
			{
				$offset = $oPage->start_capture();
				$oInstance->OnDisplayProperties($this->m_oObj, $oPage, $bEditMode);
				$sContent = $oPage->end_capture($offset);
				$aParams["PlugIn:{$sPlugInClass}->properties()"]= $sContent;			
			}
			else
			{
				$aParams["PlugIn:{$sPlugInClass}->properties()"]= "Missing PlugIn: $sPlugInClass";				
			}			
		}

		$offset = $oPage->start_capture();
		parent::Render($oPage, $aParams);
		$sContent = $oPage->end_capture($offset);
		// Remove empty table rows in case some attributes are hidden...
		$sContent = preg_replace('/<tr[^>]*>\s*(<td[^>]*>\s*<\\/td>)+\s*<\\/tr>/im', '', $sContent);
		$oPage->add($sContent);
		return $aFieldsMap;
	}
}

//DisplayTemplate::UnitTest();
?>
