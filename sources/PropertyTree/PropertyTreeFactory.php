<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree;

use Combodo\iTop\DesignDocument;
use Combodo\iTop\DesignElement;

class PropertyTreeFactory
{
	private static PropertyTreeFactory $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): PropertyTreeFactory
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new PropertyTreeFactory();
		}

		return static::$oInstance;
	}

	/**
	 * Create a property node from a design element
	 *
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 *
	 * @return \Combodo\iTop\PropertyTree\AbstractProperty
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 */
	public function CreateNodeFromDom(DesignElement $oDomNode): AbstractProperty
	{
		$sNodeType = $oDomNode->getAttribute('xsi:type');

		if (is_a($sNodeType, AbstractProperty::class, true)) {
			$oNode = new $sNodeType();
			$oNode->InitFromDomNode($oDomNode);

			return $oNode;
		}

		throw new PropertyTreeException('Unknown property node class: '.json_encode($sNodeType).' from xpath: '.DesignDocument::GetItopNodePath($oDomNode));
	}
}
