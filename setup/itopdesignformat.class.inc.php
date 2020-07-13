<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

define('ITOP_DESIGN_LATEST_VERSION', '1.7'); // iTop >= 2.7.0

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
		'1.3' => array(
			'previous' => '1.2',
			'go_to_previous' => 'From13To12',
			'next' => '1.4',
			'go_to_next' => 'From13To14',
		),
		'1.4' => array(
			'previous' => '1.3',
			'go_to_previous' => 'From14To13',
			'next' => '1.5',
			'go_to_next' => 'From14To15',
		),
		'1.5' => array(
			'previous' => '1.4',
			'go_to_previous' => 'From15To14',
			'next' => '1.6',
			'go_to_next' => 'From15To16',
		),
		'1.6' => array(
			'previous' => '1.5',
			'go_to_previous' => 'From16To15',
			'next' => '1.7',
			'go_to_next' => 'From16To17',
		),
		'1.7' => array(
			'previous' => '1.6',
			'go_to_previous' => 'From17To16',
			'next' => null,
			'go_to_next' => null,
		)
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
	 * An alternative to getNodePath, that gives the id of nodes instead of the position within the children
	 * @param $oNode
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
	 * @param \ModelFactory $oFactory
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
		$oXPath = new DOMXPath($this->oDocument);

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
		$oXPath = new DOMXPath($this->oDocument);

		// -- 1283 : remove "in_new_window" option for WebPageMenuNode
		$sPath = "/itop_design/menus/menu[@xsi:type='WebPageMenuNode']/in_new_window";
		$this->RemoveNodeFromXPath($sPath);

		// -- 2314 : remove "themes" nodes
		$sPath = "/itop_design/branding/themes";
		$this->RemoveNodeFromXPath($sPath);

		// -- 2746 - remove attributes Enum Set
		$sPath = "/itop_design/classes/class/class/fields/field[@xsi:type='AttributeEnumSet']";
		$this->RemoveNodeFromXPath($sPath);
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
		if ($oNode->nextSibling && ($oNode->nextSibling instanceof DOMText) && ($oNode->nextSibling->isWhitespaceInElementContent()))
		{
			$oNode->parentNode->removeChild($oNode->nextSibling);
		}
		$oNode->parentNode->removeChild($oNode);
	}
}
