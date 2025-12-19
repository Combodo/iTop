<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\Serializer\XMLFormat;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\Serializer\SerializerException;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;
use utils;

class XMLFormatFactory
{
	private static XMLFormatFactory $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): XMLFormatFactory
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new XMLFormatFactory();
		}

		return static::$oInstance;
	}

	/**
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 *
	 * @return \Combodo\iTop\PropertyType\Serializer\XMLFormat\AbstractXMLFormat
	 * @throws \Combodo\iTop\PropertyType\Serializer\SerializerException
	 */
	public function CreateXMLFormatFromDomNode(DesignElement $oDomNode): AbstractXMLFormat
	{
		$sNodeType = $oDomNode->getAttribute('xsi:type');

		if (utils::IsNullOrEmptyString($sNodeType)) {
			throw new SerializerException("Missing xsi:type in node specification", $oDomNode);
		}

		if (is_a($sNodeType, AbstractXMLFormat::class, true)) {
			$oNode = new $sNodeType();
			$oNode->InitFromDomNode($oDomNode);

			return $oNode;
		}

		throw new SerializerException("Unknown type node class: ".json_encode($sNodeType), $oDomNode);
	}

}
