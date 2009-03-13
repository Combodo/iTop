<?php
require_once('../application/displayblock.class.inc.php');
/**
 * This class manages the special template format used internally to build the iTop web pages
 */
class DisplayTemplate
{
	protected $m_sTemplate;
	protected $m_aTags;
	
	public function __construct($sTemplate)
	{
		$this->m_aTags = array('itopblock', 'itoptabs', 'itoptab', 'itoptoggle');
		$this->m_sTemplate = $sTemplate;
	}
	
	public function Render(web_page $oPage, $aParams = array())
	{
		$this->ApplyParams($aParams);
		$iStart = 0;
		$iEnd = strlen($this->m_sTemplate);
		$iCount = 0;
		$iBeforeTagPos = $iStart;
		$iAfterTagPos = $iStart;
		while($sTag = $this->GetNextTag($iStart, $iEnd))
		{
			$sContent = $this->GetTagContent($sTag, $iStart, $iEnd);
			$aAttributes = $this->GetTagAttributes($sTag, $iStart, $iEnd);
			//$oPage->p("Tag: $sTag - ($iStart, $iEnd)");
			$oPage->add(substr($this->m_sTemplate, $iBeforeTagPos, $iStart - $iBeforeTagPos));
			$this->RenderTag($oPage, $sTag, $aAttributes, $sContent);

			$iAfterTagPos = $iEnd + strlen('</'.$sTag.'>');
			$iBeforeTagPos = $iAfterTagPos;
			$iStart = $iEnd;
			$iEnd = strlen($this->m_sTemplate); 
			$iCount++;
			if ($iCount > 10) break;
		}
		$oPage->add(substr($this->m_sTemplate, $iAfterTagPos));
	}
	
	/**
	 * Replaces all the parameters by the values passed in the hash array
	 */
	public function ApplyParams($aParams)
	{
		$aSearches = array();
		$aReplacements = array();
		foreach($aParams as $sSearch => $sReplace)
		{
			$aSearches[] = '$'.$sSearch.'$';
			$aReplacements[] = $sReplace;
		}
		$this->m_sTemplate = str_replace($aSearches, $aReplacements, $this->m_sTemplate);
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
		static $iBlockCount = 0;
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
			
			case 'itoptab':
				$oPage->SetCurrentTab($aAttributes['name']);
				$oTemplate = new DisplayTemplate($sContent);
				$oTemplate->Render($oPage, array()); // no params to apply, they have already been applied
				//$oPage->p('iTop Tab Content:<pre>'.htmlentities($sContent).'</pre>');
				$oPage->SetCurrentTab('');
			break;
			
			case 'itoptoggle':
				$oPage->StartCollapsibleSection($aAttributes['name']);
				$oTemplate = new DisplayTemplate($sContent);
				$oTemplate->Render($oPage, array()); // no params to apply, they have already been applied
				//$oPage->p('iTop Tab Content:<pre>'.htmlentities($sContent).'</pre>');
				$oPage->EndCollapsibleSection();
			break;
			
			case 'itopblock':
				$sBlockClass = $aAttributes['blockclass'];
				$sBlockType = $aAttributes['type'];
				$aExtraParams = array();
				if (isset($aAttributes['linkage']))
				{
					$aExtraParams['linkage'] = $aAttributes['linkage'];
				}

				switch($aAttributes['encoding'])
				{
					case 'text/sibusql':
					$oFilter = CMDBSearchFilter::FromSibusQL($sContent);
					break;

					case 'text/serialize':
					default:
					$oFilter = CMDBSearchFilter::unserialize($sContent);
					break;
				}
				$oBlock = new $sBlockClass($oFilter, $sBlockType, false, $aExtraParams);
				$oBlock->Display($oPage, 'block_'.$iBlockCount);
				$iBlockCount++;
			break;
			
			default:
				// Unknown tab, just ignore it or now -- output an HTML comment
				$oPage->add("<!-- unsupported tag: $sTag -->");
		}
	}
	
	/**
	 * Unit test
	 */
	static public function UnitTest()
	{
		require_once('../application/startup.inc.php');
		require_once("../application/itopwebpage.class.inc.php");
		
		$sTemplate = '<div class="page_header">
		<div class="actions_details"><a href="#"><span>Actions</span></a></div>
		<h1>$class$: <span class="hilite">$name$</span></h1>
		<itopblock blockclass="HistoryBlock" type="toggle" encoding="text/sibusql">CMDBChangeOpSetAttribute: objkey = $pkey$</itopblock>
		</div>
		<img src="../../images/connect_to_network.png" style="margin-top:-10px; margin-right:10px; float:right">
		<itopblock blockclass="DisplayBlock" asynchronous="true" type="bare_details" encoding="text/sibusql">bizNetworkDevice: pkey = $pkey$</itopblock>
		<itoptabs>
			<itoptab name="Interfaces">
				<itopblock blockclass="DisplayBlock" type="list" encoding="text/sibusql">bizInterface: device_id = $pkey$</itopblock>
			</itoptab>
			<itoptab name="Contacts">
				<itopblock blockclass="DisplayBlock" type="list" encoding="text/sibusql">bizContact: PKEY IS contact_id IN (ContactsLinks: object_id = $pkey$)</itopblock>
			</itoptab>
			<itoptab name="Documents">
				<itopblock blockclass="DisplayBlock" type="list" encoding="text/sibusql">bizDocument: PKEY IS doc_id IN (lnkDocumentRealObject: object_id = $pkey$)</itopblock>
			</itoptab>
		</itoptabs>';
		
		$oPage = new iTopWebPage('Unit Test', 3);
		//$oPage->add("Template content: <pre>".htmlentities($sTemplate)."</pre>\n");
		$oTemplate = new DisplayTemplate($sTemplate);
		$oTemplate->Render($oPage, array('class'=>'Network device','pkey'=> 271, 'name' => 'deliversw01.mecanorama.fr', 'org_id' => 3));
		$oPage->output();
	}
}

//DisplayTemplate::UnitTest();

?>
