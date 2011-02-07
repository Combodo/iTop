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
 * Class DisplayTemplate
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
		$this->m_aTags = array('itopblock', 'itopcheck', 'itoptabs', 'itoptab', 'itoptoggle', 'itopstring');
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
				//$oPage->p('Content:<pre>'.htmlentities($sContent).'</pre>');
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
				//$oPage->p('iTop Tab Content:<pre>'.htmlentities($sContent).'</pre>');
				$oPage->SetCurrentTab('');
			break;
			
			case 'itoptoggle':
				$sName = isset($aAttributes['name']) ? $aAttributes['name'] : 'Tagada';
				$bOpen = isset($aAttributes['open']) ? $aAttributes['open'] : true;
				$oPage->StartCollapsibleSection(Dict::S($sName), $bOpen);
				$oTemplate = new DisplayTemplate($sContent);
				$oTemplate->Render($oPage, array()); // no params to apply, they have already been applied
				//$oPage->p('iTop Tab Content:<pre>'.htmlentities($sContent).'</pre>');
				$oPage->EndCollapsibleSection();
			break;
			
			case 'itopstring':
				$oPage->add(Dict::S($sContent));
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
		<itopblock blockclass="DisplayBlock" asynchronous="false" type="bare_details" encoding="text/oql">SELECT NetworkDevice AS d WHERE d.id = $id$</itopblock>
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
		//$oPage->add("Template content: <pre>".htmlentities($sTemplate)."</pre>\n");
		$oTemplate = new DisplayTemplate($sTemplate);
		$oTemplate->Render($oPage, array('class'=>'Network device','pkey'=> 271, 'name' => 'deliversw01.mecanorama.fr', 'org_id' => 3));
		$oPage->output();
	}
}

//DisplayTemplate::UnitTest();

?>
