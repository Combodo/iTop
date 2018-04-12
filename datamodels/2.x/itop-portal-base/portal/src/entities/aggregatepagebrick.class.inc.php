<?php

// Copyright (c) 2010-2018 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
//

namespace Combodo\iTop\Portal\Brick;

use Combodo\iTop\DesignElement;
use Dict;

class AggregatePageBrick extends PortalBrick
{
	const DEFAULT_DECORATION_CLASS_HOME = 'fa fa-dashboard';
	const DEFAULT_DECORATION_CLASS_NAVIGATION_MENU = 'fa fa-dashboard fa-2x';
	const DEFAULT_PAGE_TEMPLATE_PATH = 'itop-portal-base/portal/src/views/bricks/aggregate-page/layout.html.twig';

	static $sRouteName = 'p_aggregatepage_brick';

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
	 * @return \Combodo\iTop\Portal\Brick\PortalBrick|void
	 * @throws \DOMFormatException
	 */
	public function LoadFromXml(DesignElement $oMDElement)
	{
		parent::LoadFromXml($oMDElement);

		foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode)
		{
			switch ($oBrickSubNode->nodeName)
			{
				case 'aggregate_page_bricks':
					foreach ($oBrickSubNode->GetNodes('./aggregate_page_brick') as $oAggregatePageBrickNode)
					{
						if (!$oAggregatePageBrickNode->hasAttribute('id'))
						{
							throw new \DOMFormatException('AggregatePageBrick : must have an id attribute', null,
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
	}

	/**
	 * @return string[]
	 */
	public function GetAggregatePageBricks()
	{
		return $this->aAggregatePageBricks;
	}


}