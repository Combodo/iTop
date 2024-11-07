<?php
// Copyright (C) 2015-2024 Combodo SAS
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
 * Module specific customizations:
 * The customizations are done in XML, within a module_design section (itop_design/module_designs/module_design)
 * The module reads the cusomtizations by the mean of the ModuleDesign API
 * @package Core
 */

require_once(APPROOT.'application/utils.inc.php');
require_once(APPROOT.'core/designdocument.class.inc.php');


/**
 * Class ModuleDesign
 *
 * Usage from within a module:
 *
 * // Fetch the design
 * $oDesign = new ModuleDesign('tagada');
 *
 * // Read data from the root node
 * $oRoot = $oDesign->documentElement;
 * $oProperties = $oRoot->GetUniqueElement('properties');
 * $prop1 = $oProperties->GetChildText('property1');
 * $prop2 = $oProperties->GetChildText('property2');
 *
 * // Read data by searching the entire DOM
 * foreach ($oDesign->GetNodes('/module_design/bricks/brick') as $oBrickNode)
 * {
 *   $sId = $oBrickNode->getAttribute('id');
 *   $sType = $oBrickNode->getAttribute('xsi:type');
 * }
 *
 * // Search starting a given node
 * $oBricks = $oDesign->documentElement->GetUniqueElement('bricks');
 * foreach ($oBricks->GetNodes('brick') as $oBrickNode)
 * {
 *   ...
 * }
 */
class ModuleDesign extends \Combodo\iTop\DesignDocument
{
	/**
	 * @param string|null $sDesignSourceId Identifier of the section module_design (generally a module name), null to build an empty design
	 * @throws Exception
	 */
	public function __construct($sDesignSourceId = null)
	{
		parent::__construct();

		if (!is_null($sDesignSourceId))
		{
			$this->LoadFromCompiledDesigns($sDesignSourceId);
		}
	}

	/**
	 * Gets the data where the compiler has left them...
	 * @param $sDesignSourceId String Identifier of the section module_design (generally a module name)
	 * @throws Exception
	 */
	protected function LoadFromCompiledDesigns($sDesignSourceId)
	{
		$sDesignDir = APPROOT.'env-'.utils::GetCurrentEnvironment().'/core/module_designs/';
		$sFile = $sDesignDir.$sDesignSourceId.'.xml';
		if (!file_exists($sFile))
		{
			$aFiles = glob($sDesignDir.'/*.xml');
			if (count($aFiles) == 0)
			{
				$sAvailable = 'none!';
			}
			else
			{
			    $aAvailable = array();
				foreach ($aFiles as $sFile)
				{
					$aAvailable[] = "'".basename($sFile, '.xml')."'";
				}
				$sAvailable = implode(', ', $aAvailable);
			}
			throw new Exception("Could not load module design '$sDesignSourceId'. Available designs: $sAvailable");
		}

		// Silently keep track of errors
		libxml_use_internal_errors(true);
		libxml_clear_errors();
		$this->load($sFile);
		//$bValidated = $oDocument->schemaValidate(APPROOT.'setup/itop_design.xsd');
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0)
		{
			$aDisplayErrors = array();
			foreach($aErrors as $oXmlError)
			{
				$aDisplayErrors[] = 'Line '.$oXmlError->line.': '.$oXmlError->message;
			}

			throw new Exception("Invalid XML in '$sFile'. Errors: ".implode(', ', $aDisplayErrors));
		}
	}
}
