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

use DOMFormatException;
use Exception;
use UserRights;
use ModuleDesign;
use Combodo\iTop\Portal\Helper\ApplicationHelper;

/**
 * Class BrickCollection
 *
 * @package Combodo\iTop\Portal\Brick
 * @author  Bruno Da Silva <bruno.dasilva@combodo.com>
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.7.0
 */
class BrickCollection
{
	/** @var \ModuleDesign $oModuleDesign */
	private $oModuleDesign;
	/** @var array|null $aAllowedBricks Lazily computed */
	private $aAllowedBricks;
	/** @var int $iDisplayedInHome Lazily computed */
	private $iDisplayedInHome;
	/** @var int $iDisplayedInNavigationMenu Lazily computed */
	private $iDisplayedInNavigationMenu;
	/** @var array $aHomeOrdering */
	private $aHomeOrdering;
	/** @var array $aNavigationMenuOrdering */
	private $aNavigationMenuOrdering;

	/**
	 * BrickCollection constructor.
	 *
	 * @param \ModuleDesign $oModuleDesign
	 *
	 * @throws \Exception
	 */
	public function __construct(ModuleDesign $oModuleDesign)
	{
		$this->oModuleDesign = $oModuleDesign;
		$this->aAllowedBricks = null;
		$this->iDisplayedInHome = 0;
		$this->iDisplayedInNavigationMenu = 0;
		$this->aHomeOrdering = array();
		$this->aNavigationMenuOrdering = array();

		$this->Load();
	}

	/**
	 * @param $method
	 * @param $arguments
	 *
	 * @return array|\Combodo\iTop\Portal\Brick\PortalBrick[]|null
	 * @throws \Combodo\iTop\Portal\Brick\PropertyNotFoundException
	 * @throws \Exception
	 */
	public function __call($method, $arguments)
	{
		// Made for cleaner/easier access from twig (eg. app['brick_collection'].bricks)
		switch ($method)
		{
			case 'bricks':
				return $this->GetBricks();
				break;
			case 'home_ordering':
				return $this->GetHomeOrdering();
				break;
			case 'navigation_menu_ordering':
				return $this->GetNavigationMenuOrdering();
				break;
			default:
				throw new PropertyNotFoundException("The property '$method' do not exists in BricksCollection");
		}
	}

	/**
	 * @return \Combodo\iTop\Portal\Brick\PortalBrick[]|null
	 * @throws \Exception
	 */
	public function GetBricks()
	{
		return $this->aAllowedBricks;
	}

	public function GetHomeOrdering()
	{
		return $this->aHomeOrdering;
	}

	public function GetNavigationMenuOrdering()
	{
		return $this->aNavigationMenuOrdering;
	}

	/**
	 * @param string $sId
	 *
	 * @return \Combodo\iTop\Portal\Brick\PortalBrick
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \Exception
	 */
	public function GetBrickById($sId)
	{
		foreach ($this->GetBricks() as $oBrick)
		{
			if ($oBrick->GetId() === $sId)
			{
				return $oBrick;
			}
		}

		throw new BrickNotFoundException('Brick with id = "'.$sId.'" was not found among loaded bricks.');
	}

	/**
	 * @throws \Exception
	 */
	private function Load()
	{
		$aRawBrickList = $this->GetRawBrickList();

		foreach ($aRawBrickList as $oBrick)
		{
			ApplicationHelper::LoadBrickSecurity($oBrick);

			if ($oBrick->GetActive() && $oBrick->IsGrantedForProfiles(UserRights::ListProfiles()))
			{
				$this->aAllowedBricks[] = $oBrick;
				if ($oBrick->GetVisibleHome())
				{
					$this->iDisplayedInHome++;
				}
				if ($oBrick->GetVisibleNavigationMenu())
				{
					$this->iDisplayedInNavigationMenu++;
				}
			}
		}

		// - Sorting bricks by rank
		//   - Home
		$this->aHomeOrdering = $this->aAllowedBricks;
		usort($this->aHomeOrdering, function (PortalBrick $a, PortalBrick $b) {
			if ($a->GetRankHome() === $b->GetRankHome()) {
				return 0;
			}

			return $a->GetRankHome() > $b->GetRankHome() ? 1 : -1;
		});
		//    - Navigation menu
		$this->aNavigationMenuOrdering = $this->aAllowedBricks;
		usort($this->aNavigationMenuOrdering, function (PortalBrick $a, PortalBrick $b) {
			if ($a->GetRankNavigationMenu() === $b->GetRankNavigationMenu()) {
				return 0;
			}

			return $a->GetRankNavigationMenu() > $b->GetRankNavigationMenu() ? 1 : -1;
		});
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	private function GetRawBrickList()
	{
		$aBricks = array();
		/** @var \Combodo\iTop\DesignElement $oBrickNode */
		foreach ($this->oModuleDesign->GetNodes('/module_design/bricks/brick') as $oBrickNode)
		{
			$sBrickClass = $oBrickNode->getAttribute('xsi:type');
			try
			{
				if (class_exists($sBrickClass))
				{
					/** @var \Combodo\iTop\Portal\Brick\PortalBrick $oBrick */
					$oBrick = new $sBrickClass();
					$oBrick->LoadFromXml($oBrickNode);

					$aBricks[] = $oBrick;
				}
				else
				{
					throw new DOMFormatException('Unknown brick class "'.$sBrickClass.'" from xsi:type attribute', null,
						null, $oBrickNode);
				}
			}
			catch (DOMFormatException $e)
			{
				throw new Exception('Could not create brick ('.$sBrickClass.') from XML because of a DOM problem : '.$e->getMessage());
			}
			catch (Exception $e)
			{
				throw new Exception('Could not create brick ('.$sBrickClass.') from XML : '.$oBrickNode->Dump().' '.$e->getMessage());
			}
		}

		return $aBricks;
	}

}