<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Compiler;

use Combodo\iTop\DesignDocument;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\PropertyTree\PropertyTreeFactory;
use Combodo\iTop\Service\Cache\DataModelDependantCache;
use DOMFormatException;

class FormsCompiler
{
	private static FormsCompiler $oInstance;
	private DataModelDependantCache $oCacheService;
	public const CACHE_POOL = 'Forms';

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
	 * @return string
	 * @throws \Combodo\iTop\Forms\Compiler\FormsCompilerException
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
		$oPropertyTree = PropertyTreeFactory::GetInstance()->CreateNodeFromDom($oRoot);
		$sPHP = $oPropertyTree->ToPHP();
		return $sPHP;
	}

	public function StoreFormFromContent(string $sId, string $sPHPContent): void
	{
		$this->oCacheService->StorePhpContent(self::CACHE_POOL, $sId, $sPHPContent);
	}
}
