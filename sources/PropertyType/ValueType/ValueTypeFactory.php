<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\PropertyTypeException;
use utils;

/**
 * Build Value type from XML DOM
 * @since 3.3.0
 */
class ValueTypeFactory
{
	private static ValueTypeFactory $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): ValueTypeFactory
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new ValueTypeFactory();
		}

		return static::$oInstance;
	}

	/**
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 * @param \Combodo\iTop\PropertyType\ValueType\AbstractValueType|null $oParent
	 *
	 * @return \Combodo\iTop\PropertyType\ValueType\AbstractValueType
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function CreateValueTypeFromDomNode(DesignElement $oDomNode, ?AbstractValueType $oParent = null): AbstractValueType
	{
		$sNodeType = $oDomNode->getAttribute('xsi:type');

		if (utils::IsNullOrEmptyString($sNodeType)) {
			$sId = $oDomNode->getAttribute('id');
			throw new PropertyTypeException("Missing value-type in node specification", $oDomNode);
		}

		if (is_a($sNodeType, AbstractValueType::class, true)) {
			$oNode = new $sNodeType();
			$oNode->InitFromDomNode($oDomNode, $oParent);

			return $oNode;
		}

		$sId = $oDomNode->getAttribute('id');
		throw new PropertyTypeException("Unknown value-type node class: ".json_encode($sNodeType), $oDomNode);
	}
}
