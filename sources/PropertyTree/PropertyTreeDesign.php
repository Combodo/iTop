<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree;

use Combodo\iTop\DesignDocument;
use Exception;
use ReturnTypeWillChange;
use utils;

class PropertyTreeDesign extends DesignDocument
{
	public function __construct(string $sDesignSourceId = null, string $sType = 'Default')
	{
		parent::__construct();

		if (!is_null($sDesignSourceId)) {
			$this->LoadFromCompiledDesigns($sDesignSourceId, $sType);
		}
	}

	/**
	 * Gets the data where the compiler has left them...
	 * @param $sDesignSourceId String Identifier of the section module_design (generally a module name)
	 * @throws Exception
	 */
	protected function LoadFromCompiledDesigns(string $sDesignSourceId, string $sType)
	{
		$sDesignDir = APPROOT.'env-'.utils::GetCurrentEnvironment()."/core/property_trees/$sType/";
		$sFile = $sDesignDir.$sDesignSourceId.'.xml';
		if (!file_exists($sFile)) {
			$aFiles = glob($sDesignDir.'/*/*.xml');
			if (count($aFiles) == 0) {
				$sAvailable = 'none!';
			} else {
				$aAvailable = [];
				foreach ($aFiles as $sFile) {
					$aAvailable[] = "'".basename($sFile, '.xml')."'";
				}
				$sAvailable = implode(', ', $aAvailable);
			}
			throw new Exception("Could not load property tree design '$sDesignSourceId'. Available designs: $sAvailable");
		}

		// Silently keep track of errors
		libxml_use_internal_errors(true);
		libxml_clear_errors();
		$this->load($sFile);
		//$bValidated = $oDocument->schemaValidate(APPROOT.'setup/itop_design.xsd');
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0) {
			$aDisplayErrors = [];
			foreach ($aErrors as $oXmlError) {
				$aDisplayErrors[] = 'Line '.$oXmlError->line.': '.$oXmlError->message;
			}

			throw new Exception("Invalid XML in '$sFile'. Errors: ".implode(', ', $aDisplayErrors));
		}
	}

	/**
	 * Overload of the standard API
	 *
	 * @param $filename
	 * @param int $options
	 *
	 * @return int
	 */
	// Return type union is not supported by PHP 7.4, we can remove the following PHP attribute and add the return type once iTop min PHP version is PHP 8.0+
	#[ReturnTypeWillChange]
	public function save($filename, $options = null)
	{
		$this->documentElement->setAttribute('xsi:noNamespaceSchemaLocation', 'https://www.combodo.com/itop-schema/'.ITOP_DESIGN_LATEST_VERSION);

		return parent::save($filename);
	}
}
