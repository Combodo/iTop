<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Compiler;

use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\ItopSdkFormDemonstrator\Helper\ItopSdkFormDemonstratorHelper;
use Combodo\iTop\Service\Cache\DataModelDependantCache;
use Combodo\iTop\Service\DependencyInjection\DIService;
use ModelReflection;
use ModelReflectionRuntime;

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

	public function CompileFormFromFile(string $filePath): ?FormBlock
	{
		return null;
	}

	public function StoreFormFromContent(string $sId, string $sPHPContent): void
	{
		$this->oCacheService->StorePhpContent(self::CACHE_POOL, $sId, $sPHPContent);
	}
}
