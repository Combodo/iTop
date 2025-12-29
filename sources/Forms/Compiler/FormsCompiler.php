<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Compiler;

use Combodo\iTop\DesignDocument;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Block\FormBlockService;
use Combodo\iTop\PropertyTree\PropertyTreeFactory;
use Combodo\iTop\Service\Cache\DataModelDependantCache;
use DOMFormatException;
use utils;

/**
 * XML to PHP Forms compiler.
 *
 * @package Combodo\iTop\Forms\Compiler
 * @since 3.3.0
 */
class FormsCompiler
{
	private static FormsCompiler $oInstance;
	private DataModelDependantCache $oCacheService;

	protected function __construct()
	{
		$this->oCacheService = DataModelDependantCache::GetInstance();
	}

	final public static function GetInstance(): FormsCompiler
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new FormsCompiler();
		}

		return static::$oInstance;
	}

	/**
	 * Compile XML property tree into PHP to create the configuration form
	 *
	 * @param string $sXMLContent property tree structure in xml
	 *
	 * @return string Generated PHP
	 * @throws \Combodo\iTop\Forms\Compiler\FormsCompilerException
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 * @throws \DOMFormatException
	 */
	public function CompileFormFromXML(string $sXMLContent): string
	{
		$oDoc = new DesignDocument();
		libxml_clear_errors();
		$oDoc->loadXML($sXMLContent);
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0) {
			throw new FormsCompilerException('Dashlet properties definition not correctly formatted!');
		}

		/** @var \Combodo\iTop\DesignElement $oRoot */
		$oRoot = $oDoc->firstChild;
		$oPropertyTree = PropertyTreeFactory::GetInstance()->CreateTreeFromDom($oRoot);

		return $oPropertyTree->ToPHPFormBlock();
	}

	/**
	 * @param string $sId
	 * @param string $sType
	 *
	 * @return string Generated PHP
	 * @throws \Combodo\iTop\Forms\Compiler\FormsCompilerException
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 * @throws \DOMFormatException
	 */
	public function CompileForm(string $sId, string $sType): string
	{
		$sPath = utils::GetAbsoluteModulePath('core')."property_trees/$sType/$sId.xml";
		if (!file_exists($sPath)) {
			throw new FormsCompilerException("Properties definition $sType/$sId not present");
		}

		$sXMLContent = file_get_contents($sPath);

		return $this->CompileFormFromXML($sXMLContent);
	}
}
