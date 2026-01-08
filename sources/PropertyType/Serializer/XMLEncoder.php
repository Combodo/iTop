<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\Serializer;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\Compiler\PropertyTypeCompiler;

class XMLEncoder
{
	private static XMLEncoder $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): XMLEncoder
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new XMLEncoder();
		}

		return static::$oInstance;
	}

	public function Encode(mixed $normalizedValue, DesignElement $oParentNode, string $sId, string $sType): void
	{
		$sPropertyTypeXML = PropertyTypeCompiler::GetInstance()->GetXMLContent($sId, $sType);

		$this->EncodeForPropertyType($normalizedValue, $oParentNode, $sPropertyTypeXML);
	}

	public function Decode(DesignElement $oDOMNode, string $sId, string $sType): mixed
	{
		$sPropertyTypeXML = PropertyTypeCompiler::GetInstance()->GetXMLContent($sId, $sType);

		return $this->DecodeForPropertyType($oDOMNode, $sPropertyTypeXML);
	}

	public function EncodeForPropertyType(mixed $normalizedValue, DesignElement $oParentNode, string $sPropertyTypeXML): void
	{
		$oPropertyType = PropertyTypeCompiler::GetInstance()->CompilePropertyTypeFromXML($sPropertyTypeXML);

		$oPropertyType->EncodeToDOMNode($normalizedValue, $oParentNode);
	}

	public function DecodeForPropertyType(DesignElement $oParentNode, string $sPropertyTypeXML): mixed
	{
		$oPropertyType = PropertyTypeCompiler::GetInstance()->CompilePropertyTypeFromXML($sPropertyTypeXML);

		return $oPropertyType->DecodeFromDomNode($oParentNode);
	}

}
