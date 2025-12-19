<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block;

use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\PropertyType\Compiler\PropertyTypeCompiler;
use Combodo\iTop\PropertyType\PropertyTypeService;
use Combodo\iTop\Service\Cache\DataModelDependantCache;
use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use ModelReflection;
use ModelReflectionRuntime;
use utils;

class FormBlockService
{
	private static FormBlockService $oInstance;

	protected function __construct(ModelReflection $oModelReflection = null)
	{
		ServiceLocator::GetInstance()->RegisterService('ModelReflection', $oModelReflection ?? new ModelReflectionRuntime());
	}

	final public static function GetInstance(ModelReflection $oModelReflection = null): FormBlockService
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new FormBlockService($oModelReflection);
		}

		return static::$oInstance;
	}

	/**
	 * @param string $sId name of the form to retrieve
	 * @param string $sType
	 *
	 * @return \Combodo\iTop\Forms\Block\Base\FormBlock
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 * @throws \Combodo\iTop\PropertyType\Compiler\PropertyTypeCompilerException
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function GetFormBlockById(string $sId, string $sType): FormBlock
	{
		return PropertyTypeService::GetInstance()->GetFormBlockById($sId, $sType);
	}

}
