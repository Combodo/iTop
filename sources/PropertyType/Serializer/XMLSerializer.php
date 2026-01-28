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
	private PropertyTypeCompiler  $oCompiler;

	public function __construct()
	{
		$this->oCompiler = \MetaModel::GetService('PropertyTypeCompiler');
	}

	public function Serialize(mixed $value, DesignElement $oParentNode, string $sId, string $sType): void
	{
		$sPropertyTypeXML = $this->oCompiler->GetXMLContent($sId, $sType);

		$this->SerializeForPropertyType($value, $oParentNode, $sPropertyTypeXML);
	}

	public function Deserialize(DesignElement $oDOMNode, string $sId, string $sType): mixed
	{
		$sPropertyTypeXML = $this->oCompiler->GetXMLContent($sId, $sType);

		return $this->DeserializeForPropertyType($oDOMNode, $sPropertyTypeXML);
	}

	public function SerializeForPropertyType(mixed $value, DesignElement $oParentNode, string $sPropertyTypeXML): void
	{
		$oPropertyType = $this->oCompiler->CompilePropertyTypeFromXML($sPropertyTypeXML);

		$oPropertyType->SerializeToDOMNode($value, $oParentNode);
	}

	public function DeserializeForPropertyType(DesignElement $oParentNode, string $sPropertyTypeXML): mixed
	{
		$oPropertyType = $this->oCompiler->CompilePropertyTypeFromXML($sPropertyTypeXML);

		return $oPropertyType->DeserializeFromDOMNode($oParentNode);
	}
}
