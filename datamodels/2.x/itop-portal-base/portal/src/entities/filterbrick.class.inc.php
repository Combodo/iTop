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

use Combodo\iTop\DesignElement;
use Combodo\iTop\Portal\Brick\PortalBrick;
use Combodo\iTop\Portal\Brick\BrowseBrick;

/**
 * Description of FilterBrick
 * 
 * @author Guillaume Lajarige
 */
class FilterBrick extends PortalBrick
{
	const DEFAULT_VISIBLE_NAVIGATION_MENU = false;
	const DEFAULT_TILE_TEMPLATE_PATH = 'itop-portal-base/portal/src/views/bricks/filter/tile.html.twig';
    const DEFAULT_DECORATION_CLASS_HOME = 'fa fa-search';
    const DEFAULT_DECORATION_CLASS_NAVIGATION_MENU = 'fa fa-search fa-2x';

	const DEFAULT_TARGET_BRICK_CLASS = 'Combodo\\iTop\\Portal\\Brick\\BrowseBrick';
	const DEFAULT_SEARCH_PLACEHOLDER_VALUE = 'Brick:Portal:Filter:SearchInput:Placeholder';
	const DEFAULT_SEARCH_SUBMIT_LABEL = 'Brick:Portal:Filter:SearchInput:Submit';
	const DEFAULT_SEARCH_SUBMIT_CLASS = '';

	protected $sTargetBrickId;
	protected $sTargetBrickClass;
	protected $sTargetBrickTab;
	protected $sSearchPlaceholderValue;
	protected $sSearchSubmitLabel;
	protected $sSearchSubmitClass;

	public function __construct()
	{
		parent::__construct();

		$this->sTargetBrickClass = static::DEFAULT_TARGET_BRICK_CLASS;
		$this->sSearchPlaceholderValue = static::DEFAULT_SEARCH_PLACEHOLDER_VALUE;
		$this->sSearchSubmitLabel = static::DEFAULT_SEARCH_SUBMIT_LABEL;
		$this->sSearchSubmitClass = static::DEFAULT_SEARCH_SUBMIT_CLASS;
	}

	public function GetTargetBrickId()
	{
		return $this->sTargetBrickId;
	}

	public function GetTargetBrickClass()
    {
        return $this->sTargetBrickClass;
    }

	public function GetTargetBrickTab()
	{
		return $this->sTargetBrickTab;
	}

	public function GetSearchPlaceholderValue()
	{
		return $this->sSearchPlaceholderValue;
	}

	public function GetSearchSubmitLabel()
	{
		return $this->sSearchSubmitLabel;
	}

	public function GetSearchSubmitClass()
	{
		return $this->sSearchSubmitClass;
	}

	public function SetTargetBrickId($sTargetBrickId)
	{
		$this->sTargetBrickId = $sTargetBrickId;
		return $this;
	}

	public function SetTargetBrickClass($sTargetBrickClass)
    {
        $this->sTargetBrickClass = $sTargetBrickClass;
    }

	public function SetTargetBrickTab($sTargetBrickTab)
	{
		$this->sTargetBrickTab = $sTargetBrickTab;
		return $this;
	}

	public function SetSearchPlaceholderValue($sSearchPlaceholderValue)
	{
		$this->sSearchPlaceholderValue = $sSearchPlaceholderValue;
		return $this;
	}

	public function SetSearchSubmitLabel($sSearchSubmitLabel)
	{
		$this->sSearchSubmitLabel = $sSearchSubmitLabel;
		return $this;
	}

	public function SetSearchSubmitClass($sSearchSubmitClass)
	{
		$this->sSearchSubmitClass = $sSearchSubmitClass;
		return $this;
	}

	/**
	 * Load the brick's data from the xml passed as a ModuleDesignElement.
	 * This is used to set all the brick attributes at once.
	 *
	 * @param \Combodo\iTop\DesignElement $oMDElement
	 * @return BrowseBrick
	 * @throws DOMFormatException
	 */
	public function LoadFromXml(DesignElement $oMDElement)
	{
		parent::LoadFromXml($oMDElement);

		// Checking specific elements
		foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode)
		{
			switch ($oBrickSubNode->nodeName)
			{
				case 'target_brick':
					foreach ($oBrickSubNode->childNodes as $oTargetBrickNode)
					{
						switch ($oTargetBrickNode->nodeName)
						{
                            case 'id':
                                $this->SetTargetBrickId($oTargetBrickNode->GetText());
                                break;
                            case 'type':
                                $this->SetTargetBrickClass($oTargetBrickNode->GetText());
                                break;
							case 'tab':
								$this->SetTargetBrickTab($oTargetBrickNode->GetText());
								break;
						}
					}
					break;
				case 'search_placeholder_value':
				    // Note: We don't put the default value constant if the node is empty because we might actually want this to be empty
					$this->SetSearchPlaceholderValue($oBrickSubNode->GetText(''));
					break;
				case 'search_submit_label':
                    // Note: We don't put the default value constant if the node is empty because we might actually want this to be empty
                    $this->SetSearchSubmitLabel($oBrickSubNode->GetText(''));
					break;
				case 'search_submit_class':
					$this->SetSearchSubmitClass($oBrickSubNode->GetText(static::DEFAULT_SEARCH_SUBMIT_CLASS));
					break;
			}
		}

		// Checking that the brick has at least a target brick id
		if (($this->GetTargetBrickId() === null) || ($this->GetTargetBrickId() === ''))
		{
			throw new DOMFormatException('FilterBrick : Must have a target brick id', null, null, $oMDElement);
		}

		return $this;
	}

}

?>