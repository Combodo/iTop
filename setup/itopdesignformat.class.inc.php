<?php
// Copyright (C) 2014 Combodo SARL
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
 * Utility to upgrade the format of a given XML datamodel to the latest version
 * The datamodel is supplied as a loaded DOMDocument and modified in-place.
 * 
 * Usage:
 * 
 * $oDocument = new DOMDocument();
 * $oDocument->load($sXMLFile);
 * $aLog = array();
 * $oFormat = new iTopDesignFormat($oDocument);
 * if ($oFormat->Upgrade($aLog))
 * {
 *     $oDocument->save($sXMLFile);
 * }
 * else
 * {
 *     echo "Error, failed to upgrade the format, reason(s):\n".implode("\n", $aLog);
 * }
 */
 
 define('ITOP_DESIGN_LATEST_VERSION', '1.1');
 
class iTopDesignFormat
{
	/**
	 * The Document to work on
	 * @var DOMDocument
	 */
	protected $oDocument;
	
	/**
	 * Creation from a loaded DOMDocument
	 * @param DOMDocument $oDocument The document to transform
	 */
	public function __construct(DOMDocument $oDocument)
	{
		$this->oDocument = $oDocument;
	}
	
	/**
	 * Make adjustements to the DOM to migrate it to the specified version (default is latest)
	 * For now only the conversion from version 1.0 to 1.1 is supported.
	 * @param Array $aLog Array (as a reference) to gather the log results (errors, etc)
	 * @param string $sTargetVersion The desired version (or the latest possible version if not specified)
	 */
	public function Upgrade(&$aLog, $sTargetVersion = ITOP_DESIGN_LATEST_VERSION)
	{
		$oXPath = new DOMXPath($this->oDocument);
		// Retrieve the version number
		$oNodeList = $oXPath->query('/itop_design');
		if ($oNodeList->length == 0)
		{
			// Hmm, not an iTop Data Model file...
			$aLog[] = "File format, no root <itop_design> tag found";
			return false;
		}
		else
		{
			$sVersion = $oNodeList->item(0)->getAttribute('version');
			switch($sVersion)
			{
				case '': // No version, assume 1.0 !!
				case '1.0':
				$bRet = $this->From10To11();
				if ($bRet)
				{
					// Update the version number
					$oNodeList->item(0)->setAttribute('version', '1.1');
				}
				return true;
				break;
				
				case '1.1':
				return true; // Nothing to do, the document is already at the most recent version
				break;
				
				default:
				$aLog[] = "Unknown format version: $sVersion";
				return false; // unknown versions are not supported
			}
		}
	}

	/**
	 * Upgrade the format from version 1.0 to 1.1
	 * @return boolean true on success, false otherwise
	 */
	protected function From10To11()
	{
		// Adjust the XML to transparently add an id (=stimulus) on all life-cycle transitions
		// which don't already have one
		$oXPath = new DOMXPath($this->oDocument);
		$oNodeList = $oXPath->query('/itop_design/classes//class/lifecycle/states/state/transitions/transition/stimulus');
		foreach ($oNodeList as $oNode)
		{
			$oNode->parentNode->SetAttribute('id', $oNode->textContent);
			$this->DeleteNode($oNode);
		}
		
		// Adjust the XML to transparently add an id (=percent) on all thresholds of stopwatches
		// which don't already have one
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field[@xsi:type='AttributeStopWatch']/thresholds/threshold/percent");
		foreach ($oNodeList as $oNode)
		{
			$oNode->parentNode->SetAttribute('id', $oNode->textContent);
			$this->DeleteNode($oNode);
		}
		
		// Adjust the XML to transparently add an id (=action:<type>) on all allowed actions (profiles)
		// which don't already have one
		$oNodeList = $oXPath->query('/itop_design/user_rights/profiles/profile/groups/group/actions/action');
		foreach ($oNodeList as $oNode)
		{
			if ($oNode->getAttribute('id') == '')
			{
				$oNode->SetAttribute('id', 'action:' . $oNode->getAttribute('xsi:type'));
				$oNode->removeAttribute('xsi:type');
			}
			elseif ($oNode->getAttribute('xsi:type') == 'stimulus')
			{
				$oNode->SetAttribute('id', 'stimulus:' . $oNode->getAttribute('id'));
				$oNode->removeAttribute('xsi:type');
			}
		}
		
		// Adjust the XML to transparently add an id (=value) on all values of an enum which don't already have one.
		// This enables altering an enum for just adding/removing one value, intead of redefining the whole list of values.
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field[@xsi:type='AttributeEnum']/values/value");
		foreach ($oNodeList as $oNode)
		{
			if ($oNode->getAttribute('id') == '')
			{
				$oNode->SetAttribute('id', $oNode->textContent);
			}
		}
		
		return true;
	}
	
	/**
	 * Delete a node from the DOM and make sure to also remove the immediately following line break (DOMText), if any.
	 * This prevents generating empty lines in the middle of the XML
	 * @param DOMNode $oNode
	 */
	protected function DeleteNode($oNode)
	{
		if ( $oNode->nextSibling && ($oNode->nextSibling instanceof DOMText) && ($oNode->nextSibling->isWhitespaceInElementContent()) )
		{
			$oNode->parentNode->removeChild($oNode->nextSibling);
		}
		$oNode->parentNode->removeChild($oNode);
	}
}