<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\CollectionType;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyTree\PropertyTreeException;

class CollectionTypeFactory
{
	private static CollectionTypeFactory $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): CollectionTypeFactory
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new CollectionTypeFactory();
		}

		return static::$oInstance;
	}

	/**
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 *
	 * @return \Combodo\iTop\PropertyTree\CollectionType\AbstractCollectionType
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 */
	public function CreateCollectionTypeFromDomNode(DesignElement $oDomNode): AbstractCollectionType
	{
		$sNodeType = $oDomNode->getAttribute('xsi:type');

		if (is_a($sNodeType, AbstractCollectionType::class, true)) {
			$oNode = new $sNodeType();
			$oNode->InitFromDomNode($oDomNode);

			return $oNode;
		}

		throw new PropertyTreeException('Unknown collection-type node class: '.json_encode($sNodeType));
	}

}
