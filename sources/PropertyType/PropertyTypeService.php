<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType;

use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\Block\FormBlockService;
use Combodo\iTop\PropertyType\Compiler\PropertyTypeCompiler;
use Combodo\iTop\PropertyType\Compiler\PropertyTypeCompilerException;
use Combodo\iTop\Service\Cache\DataModelDependantCache;
use utils;

class PropertyTypeService
{
	public const FORM_CACHE_POOL = 'Forms';

	private DataModelDependantCache $oCacheService;

	private static PropertyTypeService $oInstance;

	protected function __construct()
	{
		$this->oCacheService = DataModelDependantCache::GetInstance();
	}

	final public static function GetInstance(): PropertyTypeService
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new PropertyTypeService();
		}

		return static::$oInstance;
	}

	/**
	 * @param string $sId name of the form to retrieve
	 * @param string $sType
	 *
	 * @return FormBlock
	 * @throws FormBlockException
	 * @throws PropertyTypeCompilerException
	 * @throws PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function GetFormBlockById(string $sId, string $sType): FormBlock
	{
		$sFilteredId = $this->SanitizeId($sId);
		$sCacheKey = $this->GetCacheKey($sType, $sFilteredId);

		if (!$this->oCacheService->HasEntry(self::FORM_CACHE_POOL, $sCacheKey) || utils::IsDevelopmentEnvironment()) {
			// Cache not found, compile the form
			$sPHPContent = PropertyTypeCompiler::GetInstance()->CompileForm($sFilteredId, $sType);
			$this->oCacheService->StorePhpContent(self::FORM_CACHE_POOL, $sCacheKey, "<?php\n\n$sPHPContent");
		}
		$this->oCacheService->FetchPHP(self::FORM_CACHE_POOL, $sCacheKey);
		$sFormBlockClass = 'FormFor__'.$sFilteredId;

		return new $sFormBlockClass($sFilteredId);
	}

	/**
	 * List all property types for a given type.
	 *
	 * @param string $sType
	 *
	 * @return array
	 * @throws PropertyTypeCompilerException
	 */
	public function ListPropertyTypesByType(string $sType): array
	{
		return PropertyTypeCompiler::GetInstance()->ListPropertyTypesByType($sType);
	}

	/**
	 * @param string $sId
	 *
	 * @return string
	 * @throws FormBlockException
	 */
	private function SanitizeId(string $sId): string
	{
		$sFilteredId = preg_replace('/[^0-9a-zA-Z_]/', '', $sId);
		if (strlen($sFilteredId) === 0 || $sFilteredId !== $sId) {
			throw new FormBlockException('Malformed name for block: '.json_encode($sId));
		}

		return $sFilteredId;
	}

	/**
	 * @param string $sType
	 * @param string $sFilteredId
	 *
	 * @return string
	 */
	private function GetCacheKey(string $sType, string $sFilteredId): string
	{
		return $sType.'/'.$sFilteredId;
	}
}
