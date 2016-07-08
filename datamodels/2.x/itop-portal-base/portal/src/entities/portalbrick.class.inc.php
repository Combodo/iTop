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
	const DEFAULT_DECORATION_CLASS_HOME = '';
	const DEFAULT_DECORATION_CLASS_NAVIGATION_MENU = '';
	const DEFAULT_TILE_TEMPLATE_PATH = 'itop-portal-base/portal/src/views/bricks/tile.html.twig';
	const DEFAULT_TILE_CONTROLLER_ACTION = null;

	static $sRouteName = null;
	protected $iWidth;
	protected $iHeight;
	protected $bModal;
	protected $bVisibleHome;
	protected $bVisibleNavigationMenu;
	protected $sDecorationClassHome;
	protected $sDecorationClassNavigationMenu;
	protected $sTileTemplatePath;
	protected $sTileControllerAction;
	// Vars below are itemization from parent class
	protected $fRankHome;
	protected $fRankNavigationMenu;
	protected $sTitleHome;
	protected $sTitleNavigationMenu;

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
		$this->sDecorationClassHome = static::DEFAULT_DECORATION_CLASS_HOME;
		$this->sDecorationClassNavigationMenu = static::DEFAULT_DECORATION_CLASS_NAVIGATION_MENU;
		$this->sTileTemplatePath = static::DEFAULT_TILE_TEMPLATE_PATH;
		$this->sTileControllerAction = static::DEFAULT_TILE_CONTROLLER_ACTION;
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
	 * Returns if the brick's rank on the portal's home page
	 *
	 * @return int
	 */
	public function GetRankHome()
	{
		return $this->fRankHome;
	}

	/**
	 * Returns if the brick's rank on the portal's navigation menu
	 *
	 * @return int
	 */
	public function GetRankNavigationMenu()
	{
		return $this->fRankNavigationMenu;
	}

	/**
	 * Return the css class that will be applied to the brick's decoration in its home tile
	 *
	 * @return string
	 */
	public function GetDecorationClassHome()
	{
		return $this->sDecorationClassHome;
	}

	/**
	 * Return the css class that will be applied to the brick's decoration in its navigation menu item
	 *
	 * @return string
	 */
	public function GetDecorationClassNavigationMenu()
	{
		return $this->sDecorationClassNavigationMenu;
	}

	/**
	 * Return the brick's title on the home page
	 *
	 * @return string
	 */
	public function GetTitleHome()
	{
		return $this->sTitleHome;
	}

	/**
	 * Return the brick's title on the navigation menu
	 *
	 * @return string
	 */
	public function GetTitleNavigationMenu()
	{
		return $this->sTitleNavigationMenu;
	}

	/**
	 * Returns the brick tile controller action
	 *
	 * @return string
	 */
	public function GetTileControllerAction()
	{
		return $this->sTileControllerAction;
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
	 * Sets if the brick's rank on the portal's home
	 *
	 * @param boolean $fRank
	 */
	public function SetRankHome($fRankHome)
	{
		$this->fRankHome = $fRankHome;
		return $this;
	}

	/**
	 * Sets if the brick's rank on the portal's navigation menu
	 *
	 * @param boolean $fRank
	 */
	public function SetRankNavigationMenu($fRankNavigationMenu)
	{
		$this->fRankNavigationMenu = $fRankNavigationMenu;
		return $this;
	}

	/**
	 * Sets if the brick's decoration class on the portal's home
	 *
	 * @param boolean $sDecorationClassHome
	 */
	public function SetDecorationClassHome($sDecorationClassHome)
	{
		$this->sDecorationClassHome = $sDecorationClassHome;
		return $this;
	}

	/**
	 * Sets if the brick's decoration class on the portal's navigation menu
	 *
	 * @param boolean $sDecorationClassNavigationMenu
	 */
	public function SetDecorationClassNavigationMenu($sDecorationClassNavigationMenu)
	{
		$this->sDecorationClassNavigationMenu = $sDecorationClassNavigationMenu;
		return $this;
	}

	/**
	 * Sets if the brick's title on the portal's home
	 *
	 * @param boolean $sTitleHome
	 */
	public function SetTitleHome($sTitleHome)
	{
		$this->sTitleHome = $sTitleHome;
		return $this;
	}

	/**
	 * Sets if the brick's title on the portal's navigation menu
	 *
	 * @param boolean $sTitleNavigationMenu
	 */
	public function SetTitleNavigationMenu($sTitleNavigationMenu)
	{
		$this->sTitleNavigationMenu = $sTitleNavigationMenu;
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
	 * Sets the brick tile controller action
	 *
	 * @param boolean $sTileControllerAction
	 */
	public function SetTileControllerAction($sTileControllerAction)
	{
		$this->sTileControllerAction = $sTileControllerAction;
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
				case 'visible':
					// Default value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('default');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = ($oOptionalNode->GetText() === 'false') ? false : true;
						$this->SetVisibleHome($optionalNodeValue);
						$this->SetVisibleNavigationMenu($optionalNodeValue);
					}
					// Home value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('home');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = ($oOptionalNode->GetText() === 'false') ? false : true;
						$this->SetVisibleHome($optionalNodeValue);
					}
					// Navigation menu value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('navigation_menu');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = ($oOptionalNode->GetText() === 'false') ? false : true;
						$this->SetVisibleNavigationMenu($optionalNodeValue);
					}
					break;
				case 'templates':
					$oTemplateNodeList = $oBrickSubNode->GetNodes('template[@id=' . ModuleDesign::XPathQuote('tile') . ']');
					if ($oTemplateNodeList->length > 0)
					{
						$this->SetTileTemplatePath($oTemplateNodeList->item(0)->GetText(static::DEFAULT_TILE_TEMPLATE_PATH));
					}
					break;
				case 'rank':
					// Setting value from parent attribute
					$this->SetRankHome($this->fRank);
					$this->SetRankNavigationMenu($this->fRank);
					// Default value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('default');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = $oOptionalNode->GetText(static::DEFAULT_RANK);
						$this->SetRankHome($optionalNodeValue);
						$this->SetRankNavigationMenu($optionalNodeValue);
					}
					// Home value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('home');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = $oOptionalNode->GetText(static::DEFAULT_RANK);
						$this->SetRankHome($optionalNodeValue);
					}
					// Navigation menu value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('navigation_menu');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = $oOptionalNode->GetText(static::DEFAULT_RANK);
						$this->SetRankNavigationMenu($optionalNodeValue);
					}
					break;
				case 'title':
					// Setting value from parent attribute
					$this->SetTitleHome($this->sTitle);
					$this->SetTitleNavigationMenu($this->sTitle);
					// Default value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('default');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = $oOptionalNode->GetText(static::DEFAULT_TITLE);
						$this->SetTitleHome($optionalNodeValue);
						$this->SetTitleNavigationMenu($optionalNodeValue);
					}
					// Home value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('home');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = $oOptionalNode->GetText(static::DEFAULT_TITLE);
						$this->SetTitleHome($optionalNodeValue);
					}
					// Navigation menu value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('navigation_menu');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = $oOptionalNode->GetText(static::DEFAULT_TITLE);
						$this->SetTitleNavigationMenu($optionalNodeValue);
						$this->SetTitle($optionalNodeValue);
					}
					break;
				case 'decoration_class':
					// Setting value from parent attribute
					$this->SetDecorationClassHome(static::DEFAULT_DECORATION_CLASS_HOME);
					$this->SetDecorationClassNavigationMenu(static::DEFAULT_DECORATION_CLASS_NAVIGATION_MENU);
					// Default value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('default');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = $oOptionalNode->GetText(static::DEFAULT_DECORATION_CLASS_NAVIGATION_MENU);
						$this->SetDecorationClassHome($optionalNodeValue);
						$this->SetDecorationClassNavigationMenu($optionalNodeValue);
					}
					// Home value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('home');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = $oOptionalNode->GetText(static::DEFAULT_DECORATION_CLASS_HOME);
						$this->SetDecorationClassHome($optionalNodeValue);
					}
					// Navigation menu value
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('navigation_menu');
					if ($oOptionalNode !== null)
					{
						$optionalNodeValue = $oOptionalNode->GetText(static::DEFAULT_DECORATION_CLASS_NAVIGATION_MENU);
						$this->SetDecorationClassNavigationMenu($optionalNodeValue);
					}
					break;
				case 'tile_controller_action':
					$this->SetTileControllerAction($oBrickSubNode->GetText(static::DEFAULT_TILE_CONTROLLER_ACTION));
					break;
			}
		}

		return $this;
	}

}

?>