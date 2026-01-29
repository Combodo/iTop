<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\Compiler;

use Combodo\iTop\DesignDocument;
use Combodo\iTop\PropertyType\PropertyType;
use Combodo\iTop\PropertyType\PropertyTypeFactory;
use utils;

/**
 * XML to PHP Forms compiler.
 *
 * @package Combodo\iTop\PropertyType\Compiler
 * @since 3.3.0
 */
class PropertyTypeCompiler
{
	public function __construct()
	{
	}

	/**
	 * @param string $sXMLContent
	 *
	 * @return \Combodo\iTop\PropertyType\PropertyType
	 * @throws \Combodo\iTop\PropertyType\Compiler\PropertyTypeCompilerException
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function CompilePropertyTypeFromXML(string $sXMLContent): PropertyType
	{
		if (!utils::StartsWith($sXMLContent, '<?xml version="1.0" encoding="UTF-8"?>')) {
			throw new PropertyTypeCompilerException('Property types definition should be XML file');
		}
		$oDoc = new DesignDocument();
		libxml_clear_errors();
		$oDoc->loadXML($sXMLContent);
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0) {
			throw new PropertyTypeCompilerException('Property types definition not correctly formatted!');
		}

		/** @var \Combodo\iTop\DesignElement $oRoot */
		$oRoot = $oDoc->firstChild;

		if (!$oRoot) {
			throw new PropertyTypeCompilerException('Property types definition not correctly formatted!');
		}

		return PropertyTypeFactory::GetInstance()->CreatePropertyTypeFromDom($oRoot);
	}

	/**
	 * @param string $sId
	 * @param string $sType
	 *
	 * @return string
	 * @throws \Combodo\iTop\PropertyType\Compiler\PropertyTypeCompilerException
	 */
	public function GetXMLContent(string $sId, string $sType): string
	{
		$sPath = utils::GetAbsoluteModulePath('core')."property_types/$sType/$sId.xml";
		if (!file_exists($sPath)) {
			throw new PropertyTypeCompilerException("Properties definition $sType/$sId not present");
		}

		return file_get_contents($sPath);
	}

	public function ListPropertyTypesByType(string $sType)
	{
		$sPath = utils::GetAbsoluteModulePath('core')."property_types/$sType";
		if (!is_dir($sPath)) {
			throw new PropertyTypeCompilerException("Properties types folder $sType not present");
		}

		$aFiles = scandir($sPath);
		$aPropertyTypes = [];
		foreach ($aFiles as $sFile) {
			if (is_file("$sPath/$sFile") && pathinfo($sFile, PATHINFO_EXTENSION) === 'xml') {
				$aPropertyTypes[] = basename($sFile, ".xml");
			}
		}

		return $aPropertyTypes;
	}

	/**
	 * Compile XML property tree into PHP to create the configuration form
	 *
	 * @param string $sXMLContent property tree structure in xml
	 *
	 * @return string Generated PHP
	 * @throws \Combodo\iTop\PropertyType\Compiler\PropertyTypeCompilerException
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function CompileFormFromXML(string $sXMLContent): string
	{
		$oPropertyType = $this->CompilePropertyTypeFromXML($sXMLContent);

		return $oPropertyType->ToPHPFormBlock();
	}

	/**
	 * @param string $sId
	 * @param string $sType
	 *
	 * @return string Generated PHP
	 * @throws \Combodo\iTop\PropertyType\Compiler\PropertyTypeCompilerException
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function CompileForm(string $sId, string $sType): string
	{
		$sXMLContent = $this->GetXMLContent($sId, $sType);

		return $this->CompileFormFromXML($sXMLContent);
	}

}
