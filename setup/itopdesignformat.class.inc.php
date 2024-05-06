<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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


/**
 * Utility to upgrade the format of a given XML datamodel to the latest version
 * The datamodel is supplied as a loaded DOMDocument and modified in-place.
 *
 * To test migration methods check {@link \Combodo\iTop\Test\UnitTest\Setup\TestForITopDesignFormatClass}
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
class iTopDesignFormat
{
	/**
	 * @var array{
	 *     string,
	 *     array{
	 *          previous: string,
	 *          go_to_previous: string,
	 *          next: string,
	 *          go_to_next: string
	 *     }
     *  }
	 */
	public static $aVersions = array(
		'1.0' => array(
			'previous' => null,
			'go_to_previous' => null,
			'next' => '1.1',
			'go_to_next' => 'From10To11',
		),
		'1.1' => array(
			'previous' => '1.0',
			'go_to_previous' => 'From11To10',
			'next' => '1.2',
			'go_to_next' => 'From11To12',
		),
		'1.2' => array(
			'previous' => '1.1',
			'go_to_previous' => 'From12To11',
			'next' => '1.3',
			'go_to_next' => 'From12To13',
		),
		'1.3' => array( // iTop >= 2.2.0
			'previous' => '1.2',
			'go_to_previous' => 'From13To12',
			'next' => '1.4',
			'go_to_next' => 'From13To14',
		),
		'1.4' => array( // iTop >= 2.4.0
			'previous' => '1.3',
			'go_to_previous' => 'From14To13',
			'next' => '1.5',
			'go_to_next' => 'From14To15',
		),
		'1.5' => array( // iTop >= 2.5.0
			'previous' => '1.4',
			'go_to_previous' => 'From15To14',
			'next' => '1.6',
			'go_to_next' => 'From15To16',
		),
		'1.6' => array( // iTop >= 2.6.0
			'previous' => '1.5',
			'go_to_previous' => 'From16To15',
			'next' => '1.7',
			'go_to_next' => 'From16To17',
		),
		'1.7' => array( // iTop >= 2.7.0
			'previous' => '1.6',
			'go_to_previous' => 'From17To16',
			'next' => '3.0',
			'go_to_next' => 'From17To30',
		),
		'3.0' => array(
			'previous' => '1.7',
			'go_to_previous' => 'From30To17',
			'next' => '3.1',
			'go_to_next' => 'From30To31',
		),
		'3.1' => array(
			'previous' => '3.0',
			'go_to_previous' => 'From31To30',
			'next' => '3.2',
			'go_to_next' => 'From31To32',
		),
		'3.2' => array(
			'previous' => '3.1',
			'go_to_previous' => 'From32To31',
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
			'msg' => $sMessage,
		);
		$this->bStatus = false;
	}

	/**
	 * Helper to fill the log structure
	 * @param string $sMessage The error description
	 */
	protected function LogWarning($sMessage)
	{
		$this->aLog[] = array(
			'severity' => 'Warning',
			'msg' => $sMessage,
		);
	}

	/**
	 * Helper to fill the log structure
	 * @param string $sMessage The message
	 */
	protected function LogInfo($sMessage)
	{
		$this->aLog[] = array(
			'severity' => 'Info',
			'msg' => $sMessage,
		);
	}

	/**
	 * @param string $sCurrentDesignVersion A design version like 3.0
	 *
	 * @return ?string the previous design version from the one passed, null if passed version unknown or 1.0
	 * @since 3.1.0 N°5779
	 */
	public static function GetPreviousDesignVersion(string $sCurrentDesignVersion): ?string
	{
		$aDesignVersions = array_keys(self::$aVersions);

		$iCurrentDesignVersionIndex = array_search($sCurrentDesignVersion, $aDesignVersions, true);
		if (false === $iCurrentDesignVersionIndex) {
			return null;
		}

		$iPreviousDesignVersionIndex = $iCurrentDesignVersionIndex - 1;
		if ($iPreviousDesignVersionIndex < 0) {
			return null;
		}

		return $aDesignVersions[$iPreviousDesignVersionIndex];
	}

	/**
	 * Get all the errors in one single array
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
	 * Get all the warnings in one single array
	 */
	public function GetWarnings()
	{
		$aErrors = array();
		foreach ($this->aLog as $aLogEntry)
		{
			if ($aLogEntry['severity'] == 'Warning')
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
	 * @throws \iTopXmlException if root node not found
	 */
	public function GetITopDesignNode(): DOMNode
	{
		$oXPath = new DOMXPath($this->oDocument);
		// Retrieve the version number
		$oNodeList = $oXPath->query('/itop_design');
		if ($oNodeList->length === 0) {
			throw new iTopXmlException('File format: no root <itop_design> tag found');
		}

		return $oNodeList->item(0);
	}

	/**
	 * @return string
	 * @throws \iTopXmlException
	 */
	public function GetVersion()
	{
		$oITopDesignNode = $this->GetITopDesignNode();

		$sVersion = $oITopDesignNode->getAttribute('version');
		if (utils::IsNullOrEmptyString($sVersion)) {
			// Originally, the information was missing: default to 1.0
			$sVersion = '1.0';
		}

		return $sVersion;
	}

	/**
	 * An alternative to getNodePath, that gives the id of nodes instead of the position within the children
	 *
	 * @param $oNode
	 *
	 * @return string
	 */
	public static function GetItopNodePath($oNode)
	{
		if ($oNode instanceof DOMDocument) return '';

		$sId = $oNode->getAttribute('id');
		$sNodeDesc = ($sId != '') ? $oNode->nodeName.'['.$sId.']' : $oNode->nodeName;
		return self::GetItopNodePath($oNode->parentNode).'/'.$sNodeDesc;
	}	 	

	/**
	 * Test the conversion without altering the DOM
	 * 	 
	 * @param string $sTargetVersion The desired version (or the latest possible version if not specified)
	 * @param object $oFactory Full data model (not yet used, aimed at allowing conversion that could not be performed without knowing the
	 *     whole data model)
	 *
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
	 * @param object $oFactory Full data model (not yet used, aimed at allowing conversion that could not be performed without knowing the
	 *     whole data model)
	 *
	 * @return bool True on success, False if errors have been encountered (still the DOM may be altered!)
	 */
	public function Convert($sTargetVersion = ITOP_DESIGN_LATEST_VERSION, $oFactory = null)
	{
		$this->aLog = array();
		$this->bStatus = true;

		try {
			$sVersion = $this->GetVersion();
		}
		catch (iTopXmlException $e) {
			$this->LogError($e->getMessage());

			return $this->bStatus;
		}

		$this->LogInfo("Converting from $sVersion to $sTargetVersion");
		try {
			$this->DoConvert($sVersion, $sTargetVersion, $oFactory);
		}
		catch (Exception|Error $e) {
			$this->LogError($e->getMessage());

			return false;
		}

		if ($this->bStatus) {
			/** @noinspection PhpUnhandledExceptionInspection already called earlier so should not crash */
			$oITopDesignNode = $this->GetITopDesignNode();
			// Update the version number
			$oITopDesignNode->setAttribute('version', $sTargetVersion);
		}

		return $this->bStatus;
	}

	/**
	 * Does the conversion, eventually in a recursive manner
	 * 	 
	 * @param string $sFrom The source format version
	 * @param string $sTo The desired format version
	 * @param object $oFactory Full data model (not yet used, aimed at allowing conversion that could not be performed without knowing the
	 *     whole data model)
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
		try {
			call_user_func($aCallSpec, $oFactory);

			// Recurse
			$this->DoConvert($sIntermediate, $sTo, $oFactory);
		}
		catch (Exception $e) {
			$this->LogError($e->getMessage());
		}
	}

	/**
	 * @param \DOMNode|null $node
	 * @param bool $bFormatOutput
	 * @param bool $bPreserveWhiteSpace
	 *
	 * @return false|string
	 *
	 * @uses \DOMDocument::saveXML()
	 */
	public function GetXmlAsString($node = null, $bFormatOutput = true, $bPreserveWhiteSpace = false)
	{
		$this->oDocument->formatOutput = $bFormatOutput;
		$this->oDocument->preserveWhiteSpace = $bPreserveWhiteSpace;

		return $this->oDocument->saveXML($node = null);
	}

	/**
	 * Upgrade the format from version 1.0 to 1.1
	 *
	 * @param \ModelFactory $oFactory
	 *
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
	 * @param \ModelFactory $oFactory
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
				$this->LogError('Alterations have been defined under the node: '.self::GetItopNodePath($oNode));
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
				$this->LogError('Alterations have been defined under the node: '.self::GetItopNodePath($oNode));
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
				$this->LogError('Alterations have been defined under the node: '.self::GetItopNodePath($oNode));
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
				$this->LogError('Alterations have been defined under the node: '.self::GetItopNodePath($oNode));
			}
			$oNode->removeAttribute('id');
		}
	}

	/**
	 * Upgrade the format from version 1.1 to 1.2
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From11To12($oFactory)
	{
	}

	/**
	 * Downgrade the format from version 1.2 to 1.1
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From12To11($oFactory)
	{
		$oXPath = new DOMXPath($this->oDocument);

		// Transform ObjectKey attributes into Integer
		//
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field[@xsi:type='AttributeObjectKey']");
		foreach ($oNodeList as $oNode)
		{
			$oNode->setAttribute('xsi:type', 'AttributeInteger');
			// The property class_attcode is left there (doing no harm)
			$this->LogWarning('The attribute '.self::GetItopNodePath($oNode).' has been degraded into an integer attribute. Any OQL query using this attribute will fail.');
		}

		// Remove Redundancy settings attributes (no redundancy could be defined in the previous format)
		//
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field[@xsi:type='AttributeRedundancySettings']");
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('The attribute '.self::GetItopNodePath($oNode).' is of no use and must be removed.');
			$this->DeleteNode($oNode);
		}

		// Later: transform the relations into code (iif defined as an SQL query)
		$oNodeList = $oXPath->query('/itop_design/classes//class/relations');
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('The relations defined in '.self::GetItopNodePath($oNode).' will be lost.');
			$this->DeleteNode($oNode);
		}

		$oNodeList = $oXPath->query('/itop_design/portal');
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('Portal definition will be lost.');
			$this->DeleteNode($oNode);
		}

		$oNodeList = $oXPath->query('/itop_design/module_parameters');
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('Module parameters will be lost.');
			$this->DeleteNode($oNode);
		}

		$oNodeList = $oXPath->query('/itop_design/snippets');
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('Code snippets will be lost.');
			$this->DeleteNode($oNode);
		}
	}

	/**
	 * Upgrade the format from version 1.2 to 1.3
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From12To13($oFactory)
	{
	}

	/**
	 * Downgrade the format from version 1.3 to 1.2
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From13To12($oFactory)
	{
		$oXPath = new DOMXPath($this->oDocument);

		$oNodeList = $oXPath->query('/itop_design/module_designs/module_design');
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('The module design defined in '.self::GetItopNodePath($oNode).' will be lost.');
			$this->DeleteNode($oNode);
		}

		// Remove MetaEnum attributes
		//
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field[@xsi:type='AttributeMetaEnum']");
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('The attribute '.self::GetItopNodePath($oNode).' is irrelevant and must be removed.');
			$this->DeleteNode($oNode);
		}

		// Remove CustomFields attributes
		//
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field[@xsi:type='AttributeCustomFields']");
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('The attribute '.self::GetItopNodePath($oNode).' is irrelevant and must be removed.');
			$this->DeleteNode($oNode);
		}

		// Remove Image attributes
		//
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field[@xsi:type='AttributeImage']");
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('The attribute '.self::GetItopNodePath($oNode).' is irrelevant and must be removed.');
			$this->DeleteNode($oNode);
		}

		// Discard _delta="if_exists"
		//
		$oNodeList = $oXPath->query("//*[@_delta='if_exists']");
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('The flag _delta="if_exists" on '.self::GetItopNodePath($oNode).' is irrelevant and must be replaced by _delta="must_exist".');
			$oNode->setAttribute('_delta', 'must_exist');
		}
	}

	/**
	 * Upgrade the format from version 1.3 to 1.4
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From13To14($oFactory)
	{
	}

	/**
	 * Downgrade the format from version 1.4 to 1.3
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From14To13($oFactory)
	{
		$oXPath = new DOMXPath($this->oDocument);
		
		// Transform _delta="force" into _delta="define"
		//
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field[@_delta='force']");
		$iCount = 0;
		foreach ($oNodeList as $oNode)
		{
			$oNode->setAttribute('_delta', 'define');
			$iCount++;
		}
		if ($iCount > 0)
		{
			$this->LogWarning('The attribute _delta="force" is not supported, converted to _delta="define" ('.$iCount.' instances processed).');
		}

        // Remove attribute flags on transitions
        //
        $oNodeList = $oXPath->query("/itop_design/classes//class/lifecycle/states/state/transitions/transition/flags");
        $this->LogWarning('Before removing flags nodes');
        foreach ($oNodeList as $oNode)
        {
            $this->LogWarning('Attribute flags '.self::GetItopNodePath($oNode).' is irrelevant on transition and must be removed.');
            $this->DeleteNode($oNode);
        }
	}

	/**
	 * Downgrade the format from version 1.5 to 1.4
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From15To14($oFactory)
	{
		$oXPath = new DOMXPath($this->oDocument);

		// Remove nodes on some menus
		//
		$sPath = "/itop_design/menus/menu[@xsi:type!='MenuGroup' and @xsi:type!='TemplateMenuNode']";
		$oNodeList = $oXPath->query("$sPath/enable_class | $sPath/enable_action | $sPath/enable_permission | $sPath/enable_stimulus");
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('Node '.self::GetItopNodePath($oNode).' is irrelevant in this version, it will be ignored. Use enable_admin_only instead.');
		}
	}

	/**
	 * Upgrade the format from version 1.4 to 1.5
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From14To15($oFactory)
	{
	}

	/**
	 * Upgrade the format from version 1.5 to 1.6
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From15To16($oFactory)
	{
		// nothing changed !
	}

	/**
	 * Downgrade the format from version 1.6 to 1.5
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From16To15($oFactory)
	{
		// Remove AttributeTagSet nodes
		//
		$sPath = "/itop_design/classes/class/fields/field[@xsi:type='AttributeTagSet']";
		$this->RemoveNodeFromXPath($sPath);

		// Remove uniqueness rules nodes
		//
		$sPath = "/itop_design/classes/class/properties/uniqueness_rules";
		$this->RemoveNodeFromXPath($sPath);
	}

	/**
	 * Upgrade the format from version 1.6 to 1.7
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From16To17($oFactory)
	{
		// N°2275 Clean branding node (move "define" from collection to logos or theme, noe that any other nodes will be seen as "merge")
		$this->CleanDefineOnCollectionNode('/itop_design/branding',
			'*[self::main_logo or self::login_logo or self::portal_logo]|themes/theme');

		// N°2275 Clean portal form properties node (move "define" from collection to each property)
		$this->CleanDefineOnCollectionNode('/itop_design/module_designs/module_design[@id="itop-portal"]/forms/form/properties', '*');

		// N°2806 Clean legacy portal constants
		$aConstantsIDsToRemove = array(
			'PORTAL_POWER_USER_PROFILE',
			'PORTAL_SERVICECATEGORY_QUERY',
			'PORTAL_SERVICE_SUBCATEGORY_QUERY',
			'PORTAL_VALIDATE_SERVICECATEGORY_QUERY',
			'PORTAL_VALIDATE_SERVICESUBCATEGORY_QUERY',
			'PORTAL_ALL_PARAMS',
			'PORTAL_SET_TYPE_FROM',
			'PORTAL_TYPE_TO_CLASS',
			'PORTAL_TICKETS_SEARCH_CRITERIA',
			'PORTAL_TICKETS_SEARCH_FILTER_service_id',
			'PORTAL_TICKETS_SEARCH_FILTER_caller_id',
			'PORTAL_INCIDENT_PUBLIC_LOG',
			'PORTAL_INCIDENT_USER_COMMENT',
			'PORTAL_INCIDENT_FORM_ATTRIBUTES',
			'PORTAL_INCIDENT_TYPE',
			'PORTAL_INCIDENT_LIST_ZLIST',
			'PORTAL_INCIDENT_CLOSED_ZLIST',
			'PORTAL_INCIDENT_DETAILS_ZLIST',
			'PORTAL_INCIDENT_DISPLAY_QUERY',
			'PORTAL_INCIDENT_DISPLAY_POWERUSER_QUERY',
			'PORTAL_USERREQUEST_PUBLIC_LOG',
			'PORTAL_USERREQUEST_USER_COMMENT',
			'PORTAL_USERREQUEST_FORM_ATTRIBUTES',
			'PORTAL_USERREQUEST_TYPE',
			'PORTAL_USERREQUEST_LIST_ZLIST',
			'PORTAL_USERREQUEST_CLOSED_ZLIST',
			'PORTAL_USERREQUEST_DETAILS_ZLIST',
			'PORTAL_USERREQUEST_DISPLAY_QUERY',
			'PORTAL_USERREQUEST_DISPLAY_POWERUSER_QUERY',
		);
		foreach($aConstantsIDsToRemove as $sConstantIDToRemove)
		{
			$sXPath = '/itop_design/constants/constant[@id="'.$sConstantIDToRemove.'"]';
			$this->RemoveNodeFromXPath($sXPath);
		}

		// N°2806 Clean legacy portal "portal" node
		$sXPath = '/itop_design/portals/portal[@id="legacy_portal"]';
		$this->RemoveNodeFromXPath($sXPath);
	}

	/**
	 * Upgrade the format from version 1.4 to 1.5
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From17To16($oFactory)
	{
		// N°1283 - remove "in_new_window" option for WebPageMenuNode
		$sPath = "/itop_design/menus/menu[@xsi:type='WebPageMenuNode']/in_new_window";
		$this->RemoveNodeFromXPath($sPath);

		// N°2314 - remove "themes" nodes
		$sPath = "/itop_design/branding/themes";
		$this->RemoveNodeFromXPath($sPath);

		// N°2746 - remove attributes Enum Set
		$sPath = "/itop_design/classes/class/class/fields/field[@xsi:type='AttributeEnumSet']";
		$this->RemoveNodeFromXPath($sPath);
	}

	/**
	 * Upgrade the format from version 1.7 to 3.0
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From17To30($oFactory)
	{
		$oXPath = new DOMXPath($this->oDocument);

		// N°3233 - Remove "display template" feature from MetaModel
		$sPath = "/itop_design//class/properties/display_template";
		$this->RemoveNodeFromXPath($sPath);

		// N°3203 - Datamodel: Add semantic for image & state attributes
		// - Move lifecycle attribute declaration to the semantic node
		$oNodeList = $oXPath->query("/itop_design//class/lifecycle/attribute");
		/** @var \DOMElement $oNode */
		foreach ($oNodeList as $oNode) {
			// Find semantic node or create it
			$oPropertiesNode = $oXPath->query("../../properties", $oNode)->item(0);
			$oFieldsSemanticNodeList = $oXPath->query("fields_semantic", $oPropertiesNode);
			if ($oFieldsSemanticNodeList->length > 0) {
				$oSemanticNode = $oFieldsSemanticNodeList->item(0);
			} else {
				if (is_null($oPropertiesNode)) {
					// No properties node found, create it
					$oClassNode = $oXPath->query("../..", $oNode)->item(0);
					$oPropertiesNode = $oClassNode->ownerDocument->createElement("properties");
					$oClassNode->appendChild($oPropertiesNode);
				}
				$oSemanticNode = $oPropertiesNode->ownerDocument->createElement("fields_semantic");
				$oPropertiesNode->appendChild($oSemanticNode);
			}

			// Move to state_attribute node
			$this->MoveNode($oNode, $oSemanticNode, "state_attribute");
		}

		// New field format, value contains code
		// Note: In the XPath there is no filter on the xsi:type as this (XML) attribute is not present on fields overloads. The main drawback is that it will convert any custom AttributeXXX with the same syntax.
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field/values/value");
		foreach ($oNodeList as $oNode) {
			$sCode = $oNode->textContent;
			// N°6562 textContent is readonly, see https://www.php.net/manual/en/class.domnode.php#95545
			// $oNode->textContent = '';
			// N°6562 to update text node content we must use the node methods !
			if ($oNode->firstChild) {
				$oNode->removeChild($oNode->firstChild);
			}
			$oCodeNode = $oNode->ownerDocument->createElement("code", $sCode);
			$oNode->appendChild($oCodeNode);
		}

		// Update test-red theme
		$oNodeList = $oXPath->query('/itop_design/branding/themes/theme[@id="test-red"]/variables/variable[@id="backoffice-environment-banner-background-color"]');
		foreach ($oNodeList as $oNode) {
			$oNode->setAttribute('id', 'ibo-page-banner--background-color');
		}

		$oNodeList = $oXPath->query( '/itop_design/branding/themes/theme[@id="test-red"]/variables/variable[@id="backoffice-environment-banner-text-color"]');
		foreach ($oNodeList as $oNode) {
			$oNode->setAttribute('id', 'ibo-page-banner--text-color');
		}

		$oNodeList = $oXPath->query( '/itop_design/branding/themes/theme[@id="test-red"]/variables/variable[@id="backoffice-environment-banner-text-content"]');
		foreach ($oNodeList as $oNode) {
			$oNode->setAttribute('id', 'ibo-page-banner--text-content');
		}

		// Add new attribute to theme import nodes
		$oNodeList = $oXPath->query('/itop_design/branding/themes/theme/imports/import');
		foreach ($oNodeList as $oNode) {
			$oNode->setAttribute('xsi:type', 'utilities');
		}

		// Add Class Style
		$oNodeList = $oXPath->query("/itop_design/classes//class/properties");
		foreach ($oNodeList as $oNode) {
			// Move "icon" node under "style" node
			$oIconNode = $oXPath->query('icon', $oNode)->item(0);
			if ($oIconNode) {
				$oStyleNode = $oNode->ownerDocument->createElement("style");
				$oNode->appendChild($oStyleNode);
				$oStyleNode->appendChild($oIconNode);
			}
		}
	}

	/**
	 * Downgrade the format from version 3.0 to 1.7
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From30To17($oFactory)
	{
		$oXPath = new DOMXPath($this->oDocument);

		// N°3182 - Remove style node from MenuGroup
		$sPath = "/itop_design/menus/menu[@xsi:type='MenuGroup']/style";
		$this->RemoveNodeFromXPath($sPath);

		// N°3185 - Remove main_logo_compact node from branding
		$sPath = "/itop_design/branding/main_logo_compact";
		$this->RemoveNodeFromXPath($sPath);

		// N°2982 - Speed up SCSS themes compilation during setup
		$sPath = "/itop_design/branding/themes/theme/precompiled_stylesheet";
		$this->RemoveNodeFromXPath($sPath);

		// N°3203 - Datamodel: Add semantic for image & state attributes
		// - Move state_attribute back to the lifecycle node if it has one
		$oNodeList = $oXPath->query("/itop_design//class/properties/fields_semantic/state_attribute");
		/** @var \DOMElement $oNode */
		foreach ($oNodeList as $oNode) {
			// Move node under lifecycle only if there is such a node
			$oLifecycleNode = $oXPath->query("../../../lifecycle", $oNode)->item(0);
			if ($oLifecycleNode !== null) {
				// Move to attribute node
				$this->MoveNode($oNode, $oLifecycleNode, "attribute");
			}
		}
		// - Remove semantic node
		$sPath = "/itop_design//class/properties/fields_semantic";
		$this->RemoveNodeFromXPath($sPath);

		// New field format
		// Note: In the XPath there is no filter on the xsi:type as this (XML) attribute is not present on fields overloads. The main drawback is that it will convert any custom AttributeXXX with the same syntax.
		// - Values
		$oNodeList = $oXPath->query("/itop_design/classes//class/fields/field/values/value");
		foreach ($oNodeList as $oNode) {
			$oCodeNode = $oXPath->query('code', $oNode)->item(0);
			if ($oCodeNode) {
				$sCode = $oCodeNode->textContent;
				$this->DeleteNode($oCodeNode);
				$oStyleNode = $oXPath->query('style', $oNode)->item(0);
				if ($oStyleNode) {
					$this->DeleteNode($oStyleNode);
				}

				// N°6562 textContent is readonly, see https://www.php.net/manual/en/class.domnode.php#95545
				// $oNode->textContent = $sCode;
				// N°6562 to update text node content we must use the node methods !
				// we are using DOMDocument::createTextNode instead of new DOMText because elements created using the constructor are read only
				// see https://www.php.net/manual/en/domelement.construct.php
				$oTextContentNode = $this->oDocument->createTextNode($sCode);
				$oNode->appendChild($oTextContentNode);
			}
		}
		// - Style
		$sPath = "/itop_design/classes//class/fields/field/default_style";
		$this->RemoveNodeFromXPath($sPath);

		// N°3516 Bring back legacy themes
		// Update test-red theme

		if (!$oXPath->query('/itop_design/branding/themes/theme[@id="test-red"]/variables/variable[@id="backoffice-environment-banner-background-color"]')->item(0)) {
			$oNodeList = $oXPath->query('/itop_design/branding/themes/theme[@id="test-red"]/variables/variable[@id="ibo-page-banner--background-color"]');
			foreach ($oNodeList as $oNode) {
				$oNode->setAttribute('id', 'backoffice-environment-banner-background-color');
			}
		}

		if (!$oXPath->query('/itop_design/branding/themes/theme[@id="test-red"]/variables/variable[@id="backoffice-environment-banner-text-color"]')->item(0)) {
			$oNodeList = $oXPath->query('/itop_design/branding/themes/theme[@id="test-red"]/variables/variable[@id="ibo-page-banner--text-color"]');
			foreach ($oNodeList as $oNode) {
				$oNode->setAttribute('id', 'backoffice-environment-banner-text-color');
			}
		}

		if (!$oXPath->query('/itop_design/branding/themes/theme[@id="test-red"]/variables/variable[@id="backoffice-environment-banner-text-content"]')->item(0)) {
			$oNodeList = $oXPath->query('/itop_design/branding/themes/theme[@id="test-red"]/variables/variable[@id="ibo-page-banner--text-content"]');
			foreach ($oNodeList as $oNode) {
				$oNode->setAttribute('id', 'backoffice-environment-banner-text-content');
			}
		}

		// Add new attribute to theme import nodes
		
		$oNodeList = $oXPath->query('/itop_design/branding/themes/theme/imports/import');
		foreach ($oNodeList as $oNode) {
			$oNode->removeAttribute('xsi:type');
		}
		
		// Remove class style
		$oNodeList = $oXPath->query("/itop_design/classes//class/properties");
		foreach ($oNodeList as $oNode) {
			$oStyleNode = $oXPath->query('style', $oNode)->item(0);
			if ($oStyleNode) {
				$oIconNode = $oXPath->query('icon', $oStyleNode)->item(0);
				if ($oIconNode) {
					// Move back the "icon" node to the class
					$oNode->appendChild($oIconNode);
				}
				$this->DeleteNode($oStyleNode);
			}
		}
	}
	/**
	 * Upgrade the format from version 3.0 to 3.1
	 *
	 * @param \ModelFactory $oFactory
	 *
	 * @return void (Errors are logged)
	 */
	protected function From30To31($oFactory)
	{

	}
	/**
	 * Downgrade the format from version 3.1 to 3.0
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From31To30($oFactory)
	{
		$oXPath = new DOMXPath($this->oDocument);

		// N°4756 - Ease extensibility for CRUD operations : Event Service
		$this->RemoveNodeFromXPath('/itop_design/events');
		$this->RemoveNodeFromXPath('/itop_design/event_listeners');
		$this->RemoveNodeFromXPath('/itop_design/classes//class/event_listeners');

		// N°3190 - Edit n:n LinkedSetIndirect in object details using a tagset-like widget
		// - Remove display style
		$this->RemoveNodeFromXPath("/itop_design/classes//class/fields/field[@xsi:type='AttributeLinkedSet']/display_style");
		$this->RemoveNodeFromXPath("/itop_design/classes//class/fields/field[@xsi:type='AttributeLinkedSetIndirect']/display_style");

		// N°2783 - Custom zlists
		$this->RemoveNodeFromXPath("/itop_design/classes//class/presentation/custom_presentations");
		$this->RemoveNodeFromXPath("/itop_design/meta/presentation/custom_presentations");

		// N°1646 - Enum: logical ordering defined in datamodel (dashlet, list, transition menu...)
		// - Remove sort type
		$this->RemoveNodeFromXPath("/itop_design/classes//class/fields/field/sort_type");
		// - Remove rank in values
		$this->RemoveNodeFromXPath("/itop_design/classes//class/fields/field/values/value/rank");
	}

	/**
	 * Upgrade the format from version 3.1 to 3.2
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From31To32($oFactory)
	{
		// Nothing for now...
	}

	/**
	 * Downgrade the format from version 3.2 to 3.1
	 * @param \ModelFactory $oFactory
	 * @return void (Errors are logged)
	 */
	protected function From32To31($oFactory)
	{
		// N°3363 - Add favicon in branding
		$this->RemoveNodeFromXPath('/itop_design/branding/main_favicon');
		$this->RemoveNodeFromXPath('/itop_design/branding/portal_favicon');
		$this->RemoveNodeFromXPath('/itop_design/branding/login_favicon');
	}

	/**
	 * @param string $sPath
	 *
	 * @return void
	 */
	private function RemoveNodeFromXPath($sPath)
	{
		$oXPath = new DOMXPath($this->oDocument);

		$oNodeList = $oXPath->query($sPath);
		foreach ($oNodeList as $oNode)
		{
			$this->LogWarning('Node '.self::GetItopNodePath($oNode).' is irrelevant in this version, it will be removed.');
				$this->DeleteNode($oNode);
			}
		}

	/**
	 * Clean a collection node by removing the _delta="define" on it and moving it to the item nodes.
	 *
	 * @param string $sCollectionXPath Absolute XPath to the collection node
	 * @param string $sItemsRelativeXPath *Relative* XPath to the item nodes from the collection node
	 *
	 * @return void
	 * @since 2.7.0
	 */
	private function CleanDefineOnCollectionNode($sCollectionXPath, $sItemsRelativeXPath)
	{
		$oXPath = new DOMXPath($this->oDocument);

		// Iterate over collections
		$oCollectionNodeList = $oXPath->query($sCollectionXPath);
		/** @var \DOMElement $oCollectionNode */
		foreach ($oCollectionNodeList as $oCollectionNode)
		{
			// Move _delta="define" from collection to items
			if (($oCollectionNode->hasAttribute('_delta')) && ($oCollectionNode->getAttribute('_delta') === "define"))
			{
				$oCollectionNode->removeAttribute('_delta');

				$oItemNodeList = $oXPath->query($sItemsRelativeXPath, $oCollectionNode);
				/** @var \DOMElement $oItemNode */
				foreach ($oItemNodeList as $oItemNode)
				{
					$oItemNode->setAttribute('_delta', 'define');
				}
			}
		}
	}

	/**
	 * Delete a node from the DOM and make sure to also remove the immediately following line break (DOMText), if any.
	 * This prevents generating empty lines in the middle of the XML
	 *
	 * @param DOMNode $oNode
	 */
	protected function DeleteNode($oNode)
	{
		if ($oNode->nextSibling && ($oNode->nextSibling instanceof DOMText) && ($oNode->nextSibling->isWhitespaceInElementContent())) {
			$oNode->parentNode->removeChild($oNode->nextSibling);
		}
		$oNode->parentNode->removeChild($oNode);
	}

	/**
	 * Move $oNode under $oParentNode and adds the correct _delta flag to it depending on its original flag (or its ancestors') and its destination parent flag (or its ancestors')
	 *
	 * +----------------------+-----------------+---------------+--------------+
	 * |\  Dest. parent node  |                 |               |              |
	 * | -------------------- |  In definition  |  In deletion  |  Structural  |
	 * |    Node to move     \|                 |               |              |
	 * +----------------------+-----------------+---------------+--------------+
	 * |                      |  Remove _delta  |  Remove node  |  Set _delta  |
	 * |     In definition    |  flag from node |  completely   |  flag from   |
	 * |                      |                 |               |  self or anc.|
	 * +----------------------+-----------------+---------------+--------------+
	 * |                      |  Remove node    |  Remove node  |  Set _delta  |
	 * |     In deletion      |  completely     |  completely   |  flag from   |
	 * |                      |                 |               |  self        |
	 * +----------------------+-----------------+---------------+--------------+
	 * |                      |  Remove _delta  |  Remove node  |  Set _delta  |
	 * |      Structural      |  from all child |  completely   |  flag from   |
	 * |                      |  nodes          |               |  self        |
	 * +----------------------+-----------------+---------------+--------------+
	 *
	 * @param \DOMElement $oNode
	 * @param \DOMElement $oDestParentNode
	 * @param string|null $sNewNodeName New name for the moved node (eg. "<foo>bar</foo>" => "<shiny_name>bar</shiny_name>"
	 *
	 * @since 3.0.0
	 */
	protected function MoveNode(DOMElement $oNode, DOMElement $oDestParentNode, ?string $sNewNodeName = null)
	{
		// Check if node / dest. parent are currently in definition / deletion from the delta
		$bIsNodeInDeltaDefinition = $this->IsNodeInDeltaDefinition($oNode);
		$bIsNodeInDeltaDeletion = $this->IsNodeInDeltaDeletion($oNode);
		$bIsDestParentNodeInDeltaDefinition = $this->IsNodeInDeltaDefinition($oDestParentNode);
		$bIsDestParentNodeInDeltaDeletion = $this->IsNodeInDeltaDeletion($oDestParentNode);

		// Prepare the new node
		if (is_null($sNewNodeName)) {
			$sNewNodeName = $oNode->nodeName;
		}
		$oNewNode = $oDestParentNode->ownerDocument->createElement($sNewNodeName, $oNode->nodeValue);

		// Compute new _delta flag
		$sNewDeltaFlag = null;
		$bAppendNodeToDestParentNode = true;
		if ((false === $bIsDestParentNodeInDeltaDefinition) && (false === $bIsDestParentNodeInDeltaDeletion)) {
			if ($bIsNodeInDeltaDefinition) {
				$sNewDeltaFlag = $this->GetDeltaFlagFromSelfOrAncestors($oNode);
			} else {
				$sCurrentDeltaFlag = $oNode->getAttribute('_delta');
				if (!empty($sCurrentDeltaFlag)) {
					$sNewDeltaFlag = $sCurrentDeltaFlag;
				}
			}

		} elseif ($bIsDestParentNodeInDeltaDefinition) {
			if ($bIsNodeInDeltaDefinition) {
				// Do nothing, there is no need for a flag
			} elseif ($bIsNodeInDeltaDeletion) {
				$bAppendNodeToDestParentNode = false;
			} else {
				// Clean _delta flag from all child nodes
				// TODO
			}
		} elseif ($bIsDestParentNodeInDeltaDeletion) {
			$bAppendNodeToDestParentNode = false;
		}

		// Update flag
		if (!is_null($sNewDeltaFlag)) {
			$oNewNode->setAttribute('_delta', $sNewDeltaFlag);
		}

		// Move newly created under destination parent
		if ($bAppendNodeToDestParentNode) {
			$oDestParentNode->appendChild($oNewNode);
		}

		// Remove current node from source parent
		$this->DeleteNode($oNode);
	}

	/**
	 * @see \ModelFactory::DELTA_FLAG_IN_DEFINITION_VALUES
	 *
	 * @param \DOMElement $oNode
	 *
	 * @return bool True if $oNode or one of its ancestors is "in the *delta* definition"
	 * @since 3.0.0
	 */
	protected function IsNodeInDeltaDefinition(DOMElement $oNode): bool
	{
		$bIsInDefinition = false;
		for ($oParent = $oNode; $oParent instanceof DOMElement; $oParent = $oParent->parentNode) {
			if (in_array($this->GetDeltaFlagFromSelfOrAncestors($oParent), ModelFactory::DELTA_FLAG_IN_DEFINITION_VALUES)) {
				$bIsInDefinition = true;
				break;
			}
		}

		return $bIsInDefinition;
	}

	/**
	 * @see \ModelFactory::DELTA_FLAG_IN_DELETION_VALUES
	 *
	 * @param \DOMElement $oNode
	 *
	 * @return bool True if $oNode or one of its ancestors is "in the *delta* deletion"
	 * @since 3.0.0
	 */
	protected function IsNodeInDeltaDeletion(DOMElement $oNode): bool
	{
		$bIsInDefinition = false;
		for ($oParent = $oNode; $oParent instanceof DOMElement; $oParent = $oParent->parentNode) {
			if (in_array($this->GetDeltaFlagFromSelfOrAncestors($oParent), ModelFactory::DELTA_FLAG_IN_DELETION_VALUES)) {
				$bIsInDefinition = true;
				break;
			}
		}

		return $bIsInDefinition;
	}

	/**
	 * @param \DOMElement $oNode
	 *
	 * @return string|null The _delta flag of the $oNode or from the closest ancestor with one; if none found null will be returned
	 * @since 3.0.0
	 */
	protected function GetDeltaFlagFromSelfOrAncestors(DOMElement $oNode): ?string
	{
		for ($oParent = $oNode; $oParent instanceof DOMElement; $oParent = $oParent->parentNode) {
			if ($oParent->hasAttribute('_delta')) {
				return $oParent->getAttribute('_delta');
			}
		}

		return null;
	}
}
