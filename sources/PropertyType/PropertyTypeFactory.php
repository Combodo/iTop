<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;

/**
 * Build a property type form XML DOM
 * @since 3.3.0
 */
class PropertyTypeFactory
{
	private static PropertyTypeFactory $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): PropertyTypeFactory
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new PropertyTypeFactory();
		}

		return static::$oInstance;
	}

	/**
	 * Create a property node from a design element
	 *
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 * @param \Combodo\iTop\PropertyType\ValueType\AbstractValueType|null $oParent
	 *
	 * @return \Combodo\iTop\PropertyType\PropertyType
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function CreatePropertyTypeFromDom(DesignElement $oDomNode, ?AbstractValueType $oParent = null): PropertyType
	{
		$oNode = new PropertyType();
		$oNode->InitFromDomNode($oDomNode);

		return $oNode;
	}
}
