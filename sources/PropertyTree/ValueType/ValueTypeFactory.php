<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyTree\AbstractProperty;
use Combodo\iTop\PropertyTree\PropertyTreeException;
use DOMElement;

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
	 * @param \Combodo\iTop\PropertyTree\AbstractProperty $oParent
	 *
	 * @return \Combodo\iTop\PropertyTree\ValueType\AbstractValueType
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 * @throws \DOMFormatException
	 */
	public function CreateValueTypeFromDomNode(DesignElement $oDomNode, AbstractProperty $oParent): AbstractValueType
	{
		$sNodeType = $oDomNode->getAttribute('xsi:type');

		if (is_a($sNodeType, AbstractValueType::class, true)) {
			$oNode = new $sNodeType();
			$oNode->InitFromDomNode($oDomNode, $oParent);

			return $oNode;
		}

		throw new PropertyTreeException('Unknown value-type node class: '.json_encode($sNodeType));
	}
}
