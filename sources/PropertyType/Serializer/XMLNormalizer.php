<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\Serializer;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\Compiler\PropertyTypeCompiler;

class XMLNormalizer
{
	private static XMLNormalizer $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): XMLNormalizer
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new XMLNormalizer();
		}

		return static::$oInstance;
	}

	public function Normalize(mixed $value, string $sId, string $sType): mixed
	{
		$sPropertyTypeXML = PropertyTypeCompiler::GetInstance()->GetXMLContent($sId, $sType);

		return $this->NormalizeForPropertyType($value, $sPropertyTypeXML);
	}

	public function Denormalize(mixed $normalizedValue, string $sId, string $sType): mixed
	{
		$sPropertyTypeXML = PropertyTypeCompiler::GetInstance()->GetXMLContent($sId, $sType);

		return $this->DenormalizeForPropertyType($normalizedValue, $sPropertyTypeXML);
	}

	public function NormalizeForPropertyType(mixed $normalizedValue, string $sPropertyTypeXML): mixed
	{
		$oPropertyType = PropertyTypeCompiler::GetInstance()->CompilePropertyTypeFromXML($sPropertyTypeXML);

		return $oPropertyType->Normalize($normalizedValue);
	}

	public function DenormalizeForPropertyType(mixed $normalizedValue, string $sPropertyTypeXML): mixed
	{
		$oPropertyType = PropertyTypeCompiler::GetInstance()->CompilePropertyTypeFromXML($sPropertyTypeXML);

		return $oPropertyType->Denormalize($normalizedValue);
	}

}
