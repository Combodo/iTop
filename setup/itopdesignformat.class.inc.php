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
 * $oFormat = new iTopDesignFormat($oDocument);
 * if ($oFormat->Convert())
 * {
 *     $oDocument->save($sXMLFile);
 * }
 * else
 * {
 *     echo "Error, failed to upgrade the format, reason(s):\n".implode("\n", $oFormat->GetErrors());
 * }
 */
 
define('ITOP_DESIGN_LATEST_VERSION', '1.1');
 
class iTopDesignFormat
{
	protected static $aVersions = array(
		'1.0' => array(
			'previous' => null,
			'go_to_previous' => null,
			'next' => '1.1',
			'go_to_next' => 'From10To11',
		),
		'1.1' => array(
			'previous' => '1.0',
			'go_to_previous' => 'From11To10',
			'next' => null,
			'go_to_next' => null,
		),
	);

	/**
	 * The Document to work on
	 * @var DOMDocument
	 */
	protected $oDocument;

	/**
	 * The log for the ongoing operation
	 * @var DOMDocument
	 */
	protected $aLog;
	protected $bStatus;
	
	/**
	 * Creation from a loaded DOMDocument
	 * @param DOMDocument $oDocument The document to transform
	 */
	public function __construct(DOMDocument $oDocument)
	{
		$this->oDocument = $oDocument;
	}

	/**
	 * Helper to fill the log structure
	 * @param string $sMessage The error description
	 */
	protected function LogError($sMessage)
	{
		$this->aLog[] = array(
			'severity' => 'Error',
			'msg' => $sMessage
		);
		$this->bStatus = false;
	}

	/**
	 * Helper to fill the log structure
	 * @param string $sMessage The message
	 */
	protected function LogInfo($sMessage)
	{
		$this->aLog[] = array(
			'severity' => 'Info',
			'msg' => $sMessage
		);
	}

	/**
	 * Get all the errors in one single line
	 */
	public function GetErrors()
	{
		$aErrors = array();
		foreach ($this->aLog as $aLogEntry)
		{
			if ($aLogEntry['severity'] == 'Error')
			{
				$aErrors[] = $aLogEntry['msg'];
			}
		}
		return $aErrors;
	}

	/**
	 * Get the whole log
	 */
	public function GetLog()
	{
		return $this->aLog;
	}

	/**
	 * Test the conversion without altering the DOM
	 * 	 
	 * @param string $sTargetVersion The desired version (or the latest possible version if not specified)
	 * @param object $oFactory Full data model (not yet used, aimed at allowing conversion that could not be performed without knowing the whole data model)
	 * @return bool True on success	 
	 */
	public function CheckConvert($sTargetVersion = ITOP_DESIGN_LATEST_VERSION, $oFactory = null)
	{
		// Clone the document
		$this->oDocument = $this->oDocument->cloneNode(true);		
		return $this->Convert($sTargetVersion, $oFactory);
	}

	/**
	 * Make adjustements to the DOM to migrate it to the specified version (default is latest)
	 * For now only the conversion from version 1.0 to 1.1 is supported.
	 * 	 
	 * @param string $sTargetVersion The desired version (or the latest possible version if not specified)
	 * @param object $oFactory Full data model (not yet used, aimed at allowing conversion that could not be performed without knowing the whole data model)
	 * @return bool True on success, False if errors have been encountered (still the DOM may be altered!)
	 */
	public function Convert($sTargetVersion = ITOP_DESIGN_LATEST_VERSION, $oFactory = null)
	{
		$this->aLog = array();
		$this->bStatus = true;

		$oXPath = new DOMXPath($this->oDocument);
		// Retrieve the version number
		$oNodeList = $oXPath->query('/itop_design');
		if ($oNodeList->length == 0)
		{
			// Hmm, not an iTop Data Model file...
			$this->LogError('File format, no root <itop_design> tag found');
		}
		else
		{
			$sVersion = $oNodeList->item(0)->getAttribute('version');
			if ($sVersion == '')
			{
				// Originaly, the information was missing: default to 1.0
				$sVersion = '1.0';
			}
			$this->LogInfo("Converting from $sVersion to $sTargetVersion");
			$this->DoConvert($sVersion, $sTargetVersion, $oFactory);
			if ($this->bStatus)
			{
				// Update the version number
				$oNodeList->item(0)->setAttribute('version', $sTargetVersion);
			}
		}
		return $this->bStatus;
	}



	/**
	 * Does the conversion, eventually in a recursive manner
	 * 	 
	 * @param string $sFrom The source format version
	 * @param string $sTo The desired format version
	 * @param object $oFactory Full data model (not yet used, aimed at allowing conversion that could not be performed without knowing the whole data model)
	 * @return bool True on success	 
	 */
	protected function DoConvert($sFrom, $sTo, $oFactory = null)
	{
		if ($sFrom == $sTo)
		{
			return;
		}
		if (!array_key_exists($sFrom, self::$aVersions))
		{
			$this->LogError("Unknown source format version: $sFrom");
			return;
		}
		if (!array_key_exists($sTo, self::$aVersions))
		{
			$this->LogError("Unknown target format version: $sTo");
			return; // unknown versions are not supported
		}
		
		$aVersionIds = array_keys(self::$aVersions);
		$iFrom = array_search($sFrom, $aVersionIds);
		$iTo = array_search($sTo, $aVersionIds);
		if ($iFrom < $iTo)
		{
			// This is an upgrade
			$sIntermediate = self::$aVersions[$sFrom]['next'];
			$sTransform = self::$aVersions[$sFrom]['go_to_next'];
			$this->LogInfo("Upgrading from $sFrom to $sIntermediate ($sTransform)");
		}
		else
		{
			// This is a downgrade
			$sIntermediate = self::$aVersions[$sFrom]['previous'];
			$sTransform = self::$aVersions[$sFrom]['go_to_previous'];
			$this->LogInfo("Downgrading from $sFrom to $sIntermediate ($sTransform)");
		}
		// Transform to the intermediate format
		$aCallSpec = array($this, $sTransform);
		try
		{
			call_user_func($aCallSpec, $oFactory);

			// Recurse
			$this->DoConvert($sIntermediate, $sTo, $oFactory);
		}
		catch (Exception $e)
		{
			$this->LogError($e->getMessage());
		}
		return;
	}

	/**
	 * Upgrade the format from version 1.0 to 1.1
	 * @return void (Errors are logged)
	 */
	protected function From10To11($oFactory)
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
	}
	
	/**
	 * Downgrade the format from version 1.1 to 1.0
	 * @return void (Errors are logged)
	 */
	protected function From11To10($oFactory)
	{
		// Move the id down to a stimulus node on all life-cycle transitions
		$oXPath = new DOMXPath($this->oDocument);
		$oNodeList = $oXPath->query('/itop_design/classes//class/lifecycle/states/state/transitions/transition[@id]');
		foreach ($oNodeList as $oNode)
		{
			if ($oXPath->query('descendant-or-self::*[@_delta or @_rename_from]', $oNode)->length > 0)
			{
				$this->LogError('Alterations have been defined under the node: '.MFDocument::GetItopNodePath($oNode));
			}
			$oStimulus = $oNode->ownerDocument->createElement('stimulus', $oNode->getAttribute('id'));
			$oNode->appendChild($oStimulus);
			$oNode->removeAttribute('id');
		}
		
		// Move the id down to a percent node on all thresholds
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field[@xsi:type='AttributeStopWatch']/thresholds/threshold[@id]");
		foreach ($oNodeList as $oNode)
		{
			if ($oXPath->query('descendant-or-self::*[@_delta or @_rename_from]', $oNode)->length > 0)
			{
				$this->LogError('Alterations have been defined under the node: '.MFDocument::GetItopNodePath($oNode));
			}
			$oStimulus = $oNode->ownerDocument->createElement('percent', $oNode->getAttribute('id'));
			$oNode->appendChild($oStimulus);
			$oNode->removeAttribute('id');
		}
		
		// Restore the type and id on profile/actions 
		$oNodeList = $oXPath->query('/itop_design/user_rights/profiles/profile/groups/group/actions/action');
		foreach ($oNodeList as $oNode)
		{
			if ($oXPath->query('descendant-or-self::*[@_delta or @_rename_from]', $oNode)->length > 0)
			{
				$this->LogError('Alterations have been defined under the node: '.MFDocument::GetItopNodePath($oNode));
			}
			if (substr($oNode->getAttribute('id'), 0, strlen('action')) == 'action')
			{
				// The id has the form 'action:<action_code>'
				$sActionCode = substr($oNode->getAttribute('id'), strlen('action:'));
				$oNode->removeAttribute('id');
				$oNode->setAttribute('xsi:type', $sActionCode);
			}
			else
			{
				// The id has the form 'stimulus:<stimulus_code>'
				$sStimulusCode = substr($oNode->getAttribute('id'), strlen('stimulus:'));
				$oNode->setAttribute('id', $sStimulusCode);
				$oNode->setAttribute('xsi:type', 'stimulus');
			}
		}
		
		// Remove the id on all enum values
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field[@xsi:type='AttributeEnum']/values/value[@id]");
		foreach ($oNodeList as $oNode)
		{
			if ($oXPath->query('descendant-or-self::*[@_delta or @_rename_from]', $oNode)->length > 0)
			{
				$this->LogError('Alterations have been defined under the node: '.MFDocument::GetItopNodePath($oNode));
			}
			$oNode->removeAttribute('id');
		}
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