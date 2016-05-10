<?php

// Copyright (C) 2010-2015 Combodo SARL
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

namespace Combodo\iTop\Portal\Brick;

use \ModuleDesign;
use \Combodo\iTop\DesignElement;
use \Combodo\iTop\Portal\Brick\AbstractBrick;

/**
 * Description of PortalBrick
 * 
 * Classes that will be used only in the portal, not the console.
 *
 * @author Guillaume Lajarige
 */
abstract class PortalBrick extends AbstractBrick
{
	const DEFAULT_WIDTH = 1;
	const DEFAULT_HEIGHT = 1;
	const DEFAULT_MODAL = false;
	const DEFAULT_VISIBLE_HOME = true;
	const DEFAULT_VISIBLE_NAVIGATION_MENU = true;
	const DEFAULT_TILE_TEMPLATE_PATH = 'itop-portal-base/portal/src/views/bricks/tile.html.twig';

	static $sRouteName = null;
	protected $iWidth;
	protected $iHeight;
	protected $bModal;
	protected $bVisibleHome;
	protected $bVisibleNavigationMenu;
	protected $sTileTemplatePath;

	static function GetRouteName()
	{
		return static::$sRouteName;
	}

	/**
	 * Default attributes values of AbstractBrick are specified in the definition, not the constructor.
	 */
	function __construct()
	{
		parent::__construct();

		$this->iWidth = static::DEFAULT_WIDTH;
		$this->iHeight = static::DEFAULT_HEIGHT;
		$this->bModal = static::DEFAULT_MODAL;
		$this->bVisibleHome = static::DEFAULT_VISIBLE_HOME;
		$this->bVisibleNavigationMenu = static::DEFAULT_VISIBLE_NAVIGATION_MENU;
		$this->sTileTemplatePath = static::DEFAULT_TILE_TEMPLATE_PATH;
	}

	/**
	 * Returns width of the brick
	 *
	 * @return int
	 */
	public function GetWidth()
	{
		return $this->iWidth;
	}

	/**
	 * Returns height of the brick
	 *
	 * @return int
	 */
	public function GetHeight()
	{
		return $this->iHeight;
	}

	/**
	 * Returns if the brick will show in a modal dialog or not
	 *
	 * @return boolean
	 */
	public function GetModal()
	{
		return $this->bModal;
	}

	/**
	 * Returns if the brick is visible on the portal's home page
	 *
	 * @return int
	 */
	public function GetVisibleHome()
	{
		return $this->bVisibleHome;
	}

	/**
	 * Returns if the brick is visible on the portal's navigation menu
	 *
	 * @return int
	 */
	public function GetVisibleNavigationMenu()
	{
		return $this->bVisibleNavigationMenu;
	}

	/**
	 * Returns the brick tile template path
	 *
	 * @return string
	 */
	public function GetTileTemplatePath()
	{
		return $this->sTileTemplatePath;
	}

	/**
	 * Sets the width of the brick
	 *
	 * @param boolean $iWidth
	 */
	public function SetWidth($iWidth)
	{
		$this->iWidth = $iWidth;
		return $this;
	}

	/**
	 * Sets the width of the brick
	 *
	 * @param boolean $iWidth
	 */
	public function SetHeight($iHeight)
	{
		$this->iHeight = $iHeight;
		return $this;
	}

	/**
	 * Sets if the brick will show in a modal dialog or not
	 *
	 * @param boolean $bModal
	 */
	public function SetModal($bModal)
	{
		$this->bModal = $bModal;
		return $this;
	}

	/**
	 * Sets if the brick is visible on the portal's home
	 *
	 * @param boolean $iWidth
	 */
	public function SetVisibleHome($bVisibleHome)
	{
		$this->bVisibleHome = $bVisibleHome;
		return $this;
	}

	/**
	 * Sets if the brick is visible on the portal's navigation menu
	 *
	 * @param boolean $iWidth
	 */
	public function SetVisibleNavigationMenu($bVisibleNavigationMenu)
	{
		$this->bVisibleNavigationMenu = $bVisibleNavigationMenu;
		return $this;
	}

	/**
	 * Sets the brick tile template path
	 *
	 * @param boolean $sTileTemplatePath
	 */
	public function SetTileTemplatePath($sTileTemplatePath)
	{
		$this->sTileTemplatePath = $sTileTemplatePath;
		return $this;
	}

	/**
	 * Load the brick's data from the xml passed as a ModuleDesignElement.
	 * This is used to set all the brick attributes at once.
	 *
	 * @param \Combodo\iTop\DesignElement $oMDElement
	 * @return PortalBrick
	 */
	public function LoadFromXml(DesignElement $oMDElement)
	{
		parent::LoadFromXml($oMDElement);

		// Checking specific elements
		foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode)
		{
			switch ($oBrickSubNode->nodeName)
			{
				case 'width':
					$this->SetWidth((int) $oBrickSubNode->GetText(static::DEFAULT_WIDTH));
					break;
				case 'height':
					$this->SetHeight((int) $oBrickSubNode->GetText(static::DEFAULT_HEIGHT));
					break;
				case 'modal':
					$bModal = ($oBrickSubNode->GetText(static::DEFAULT_MODAL) === 'true');
					$this->SetModal($bModal);
					break;
				case 'visible_home':
					$this->SetVisibleHome(($oBrickSubNode->GetText() === 'false') ? false : true );
					break;
				case 'visible_navigation_menu':
					$this->SetVisibleNavigationMenu(($oBrickSubNode->GetText() === 'false') ? false : true );
					break;
				case 'templates':
					$oTemplateNodeList = $oBrickSubNode->GetNodes('template[@id=' . ModuleDesign::XPathQuote('tile') . ']');
					if ($oTemplateNodeList->length > 0)
					{
						$this->SetTileTemplatePath($oTemplateNodeList->item(0)->GetText(static::DEFAULT_TILE_TEMPLATE_PATH));
					}
					break;
			}
		}

		return $this;
	}

}

?>