<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\Serializer;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\Compiler\PropertyTypeCompiler;
use Combodo\iTop\PropertyType\PropertyType;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;

class XMLSerializer
{
	private static XMLSerializer $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): XMLSerializer
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new XMLSerializer();
		}

		return static::$oInstance;
	}

	public function Serialize(mixed $value, DesignElement $oParentNode, string $sId, string $sType): void
	{
		$sPropertyTypeXML = PropertyTypeCompiler::GetInstance()->GetXMLContent($sId, $sType);

		$this->SerializeForPropertyType($value, $oParentNode, $sPropertyTypeXML);
	}

	public function Unserialize(DesignElement $oDOMNode, string $sId, string $sType): mixed
	{
		$sPropertyTypeXML = PropertyTypeCompiler::GetInstance()->GetXMLContent($sId, $sType);

		return $this->UnserializeForPropertyType($oDOMNode, $sPropertyTypeXML);
	}

	public function SerializeForPropertyType(mixed $value, DesignElement $oParentNode, string $sPropertyTypeXML): void
	{
		$normalizedValue = XMLNormalizer::GetInstance()->NormalizeForPropertyType($value, $sPropertyTypeXML);

		XMLEncoder::GetInstance()->EncodeForPropertyType($normalizedValue, $oParentNode, $sPropertyTypeXML);
	}

	public function UnserializeForPropertyType(DesignElement $oParentNode, string $sPropertyTypeXML): mixed
	{
		$normalizedValue = XMLEncoder::GetInstance()->DecodeForPropertyType($oParentNode, $sPropertyTypeXML);

		return XMLNormalizer::GetInstance()->DenormalizeForPropertyType($normalizedValue, $sPropertyTypeXML);
	}
}
