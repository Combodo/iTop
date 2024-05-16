<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Portal\Brick;

use Combodo\iTop\DesignElement;
use Dict;
use DOMFormatException;

/**
 * Class AggregatePageBrick
 *
 * @package Combodo\iTop\Portal\Brick
 * @since   2.5.0
 * @author  Eric Espie <eric.espie@combodo.com>
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @author  Pierre Goiffon <pierre.goiffon@combodo.com>
 */
class AggregatePageBrick extends PortalBrick
{
	// Overloaded constants
	const DEFAULT_DECORATION_CLASS_HOME = 'fas fa-tachometer-alt';
	const DEFAULT_DECORATION_CLASS_NAVIGATION_MENU = 'fas fa-tachometer-alt fa-2x';
	const DEFAULT_PAGE_TEMPLATE_PATH = 'itop-portal-base/portal/templates/bricks/aggregate-page/layout.html.twig';

	// Overloaded variables
	public static $sRouteName = 'p_aggregatepage_brick';

	/**
	 * @var string[] list of bricks to use, ordered by rank (key=id, value=rank)
	 */
	private $aAggregatePageBricks = array();

	/**
	 * AggregatePageBrick constructor.
	 */
	function __construct()
	{
		parent::__construct();

		$this->SetTitle(Dict::S('Brick:Portal:AggregatePage:DefaultTitle'));
	}

	/**
	 * @param \Combodo\iTop\DesignElement $oMDElement
	 *
	 * @return \Combodo\iTop\Portal\Brick\AggregatePageBrick
	 *
	 * @throws \DOMFormatException
	 */
	public function LoadFromXml(DesignElement $oMDElement)
	{
		parent::LoadFromXml($oMDElement);

		/** @var \Combodo\iTop\DesignElement $oBrickSubNode */
		foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode)
		{
			switch ($oBrickSubNode->nodeName)
			{
				case 'aggregate_page_bricks':
					/** @var \Combodo\iTop\DesignElement $oAggregatePageBrickNode */
					foreach ($oBrickSubNode->GetNodes('./aggregate_page_brick') as $oAggregatePageBrickNode)
					{
						if (!$oAggregatePageBrickNode->hasAttribute('id'))
						{
							throw new DOMFormatException('AggregatePageBrick : must have an id attribute', null,
								null, $oAggregatePageBrickNode);
						}
						$sBrickName = $oAggregatePageBrickNode->getAttribute('id');

						$iBrickRank = static::DEFAULT_RANK;
						$oOptionalNode = $oAggregatePageBrickNode->GetOptionalElement('rank');
						if ($oOptionalNode !== null)
						{
							$iBrickRank = $oOptionalNode->GetText();
						}

						$this->aAggregatePageBricks[$sBrickName] = $iBrickRank;
					}
			}
		}

		asort($this->aAggregatePageBricks);

		return $this;
	}

	/**
	 * @return string[]
	 */
	public function GetAggregatePageBricks()
	{
		return $this->aAggregatePageBricks;
	}


}