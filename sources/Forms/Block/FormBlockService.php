<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block;

use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Compiler\FormsCompiler;
use Combodo\iTop\Service\Cache\DataModelDependantCache;
use Combodo\iTop\Service\DependencyInjection\DIService;
use ModelReflection;
use ModelReflectionRuntime;

class FormBlockService
{
	private static FormBlockService $oInstance;
	private DataModelDependantCache $oCacheService;

	protected function __construct(ModelReflection $oModelReflection = null)
	{
		DIService::GetInstance()->RegisterService('ModelReflection', $oModelReflection ?? new ModelReflectionRuntime());
		$this->oCacheService = DataModelDependantCache::GetInstance();
	}

	final public static function GetInstance(ModelReflection $oModelReflection = null): FormBlockService
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new FormBlockService($oModelReflection);
		}

		return static::$oInstance;
	}

	public function GetFormBlockById(string $sId): FormBlock
	{
		if ($this->oCacheService->HasEntry(FormsCompiler::CACHE_POOL, $sId)) {
			$this->oCacheService->Fetch(FormsCompiler::CACHE_POOL, $sId);
		}

		return new $sId($sId);
	}

}
