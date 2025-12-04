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

	/**
	 * @param string $sId name of the form to retrieve
	 *
	 * @return \Combodo\iTop\Forms\Block\Base\FormBlock
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 */
	public function GetFormBlockById(string $sId): FormBlock
	{
		$sFilteredId = preg_replace('/[^0-9a-zA-Z_]/', '', $sId);
		if (strlen($sFilteredId) === 0) {
			throw new FormBlockException('Malformed name for block: '.json_encode($sId));
		}
		if (!$this->oCacheService->HasEntry(FormsCompiler::CACHE_POOL, $sFilteredId)) {
			throw new FormBlockException('No block found for: '.json_encode($sFilteredId).' original value asked: '.json_encode($sId));
		}
		$this->oCacheService->Fetch(FormsCompiler::CACHE_POOL, $sFilteredId);

		return new $sFilteredId($sFilteredId);
	}

}
