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
use utils;

class FormBlockService
{
	public const CACHE_POOL = 'Forms';
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
	 * @param string $sType
	 *
	 * @return \Combodo\iTop\Forms\Block\Base\FormBlock
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 * @throws \Combodo\iTop\Forms\Compiler\FormsCompilerException
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 * @throws \DOMFormatException
	 */
	public function GetFormBlockById(string $sId, string $sType): FormBlock
	{
		$sFilteredId = preg_replace('/[^0-9a-zA-Z_]/', '', $sId);
		if (strlen($sFilteredId) === 0 || $sFilteredId !== $sId) {
			throw new FormBlockException('Malformed name for block: '.json_encode($sId));
		}
		$sCacheKey = $sType.'/'.$sFilteredId;
		if (!$this->oCacheService->HasEntry(self::CACHE_POOL, $sCacheKey) || utils::IsDevelopmentEnvironment()) {
			// Cache not found, compile the form
			$sPHPContent = FormsCompiler::GetInstance()->CompileForm($sFilteredId, $sType);
			$this->oCacheService->StorePhpContent(FormBlockService::CACHE_POOL, $sCacheKey, "<?php\n\n$sPHPContent");
		}
		$this->oCacheService->FetchPHP(self::CACHE_POOL, $sCacheKey);
		$sFormBlockClass = 'FormFor__'.$sFilteredId;

		return new $sFormBlockClass($sFilteredId);
	}

}
