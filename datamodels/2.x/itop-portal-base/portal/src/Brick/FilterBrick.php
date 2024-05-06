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
use Combodo\iTop\DesignElement;

/**
 * Description of FilterBrick
 *
 * @package Combodo\iTop\Portal\Brick
 * @since   2.4.0
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class FilterBrick extends PortalBrick
{
	// Overloaded constants
	const DEFAULT_VISIBLE_NAVIGATION_MENU = false;
	const DEFAULT_TILE_TEMPLATE_PATH = 'itop-portal-base/portal/templates/bricks/filter/tile.html.twig';
    const DEFAULT_DECORATION_CLASS_HOME = 'fas fa-search';
    const DEFAULT_DECORATION_CLASS_NAVIGATION_MENU = 'fas fa-search fa-2x';

    /** @var string DEFAULT_TARGET_BRICK_CLASS */
	const DEFAULT_TARGET_BRICK_CLASS = 'Combodo\\iTop\\Portal\\Brick\\BrowseBrick';
	/** @var string DEFAULT_SEARCH_PLACEHOLDER_VALUE */
	const DEFAULT_SEARCH_PLACEHOLDER_VALUE = 'Brick:Portal:Filter:SearchInput:Placeholder';
	/** @var string DEFAULT_SEARCH_SUBMIT_LABEL */
	const DEFAULT_SEARCH_SUBMIT_LABEL = 'Brick:Portal:Filter:SearchInput:Submit';
	/** @var string DEFAULT_SEARCH_SUBMIT_CLASS */
	const DEFAULT_SEARCH_SUBMIT_CLASS = '';

	/** @var string $sTargetBrickId */
	protected $sTargetBrickId;
	/** @var string $sTargetBrickClass */
	protected $sTargetBrickClass;
	/** @var string $sTargetBrickTab */
	protected $sTargetBrickTab;
	/** @var string $sSearchPlaceholderValue */
	protected $sSearchPlaceholderValue;
	/** @var string $sSearchSubmitLabel */
	protected $sSearchSubmitLabel;
	/** @var string $sSearchSubmitClass */
	protected $sSearchSubmitClass;

	/**
	 * FilterBrick constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->sTargetBrickClass = static::DEFAULT_TARGET_BRICK_CLASS;
		$this->sSearchPlaceholderValue = static::DEFAULT_SEARCH_PLACEHOLDER_VALUE;
		$this->sSearchSubmitLabel = static::DEFAULT_SEARCH_SUBMIT_LABEL;
		$this->sSearchSubmitClass = static::DEFAULT_SEARCH_SUBMIT_CLASS;
	}

	/**
	 * @return string
	 */
	public function GetTargetBrickId()
	{
		return $this->sTargetBrickId;
	}

	/**
	 * @return string
	 */
	public function GetTargetBrickClass()
    {
        return $this->sTargetBrickClass;
    }

	/**
	 * @return string
	 */
	public function GetTargetBrickTab()
	{
		return $this->sTargetBrickTab;
	}

	/**
	 * @return string
	 */
	public function GetSearchPlaceholderValue()
	{
		return $this->sSearchPlaceholderValue;
	}

	/**
	 * @return string
	 */
	public function GetSearchSubmitLabel()
	{
		return $this->sSearchSubmitLabel;
	}

	/**
	 * @return string
	 */
	public function GetSearchSubmitClass()
	{
		return $this->sSearchSubmitClass;
	}

	/**
	 * @param string $sTargetBrickId
	 *
	 * @return $this
	 */
	public function SetTargetBrickId($sTargetBrickId)
	{
		$this->sTargetBrickId = $sTargetBrickId;
		return $this;
	}

	/**
	 * @param string $sTargetBrickClass
	 */
	public function SetTargetBrickClass($sTargetBrickClass)
    {
        $this->sTargetBrickClass = $sTargetBrickClass;
    }

	/**
	 * @param string $sTargetBrickTab
	 *
	 * @return $this
	 */
	public function SetTargetBrickTab($sTargetBrickTab)
	{
		$this->sTargetBrickTab = $sTargetBrickTab;
		return $this;
	}

	/**
	 * @param string $sSearchPlaceholderValue
	 *
	 * @return $this
	 */
	public function SetSearchPlaceholderValue($sSearchPlaceholderValue)
	{
		$this->sSearchPlaceholderValue = $sSearchPlaceholderValue;
		return $this;
	}

	/**
	 * @param string $sSearchSubmitLabel
	 *
	 * @return $this
	 */
	public function SetSearchSubmitLabel($sSearchSubmitLabel)
	{
		$this->sSearchSubmitLabel = $sSearchSubmitLabel;
		return $this;
	}

	/**
	 * @param string $sSearchSubmitClass
	 *
	 * @return $this
	 */
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
     *
     * @return \Combodo\iTop\Portal\Brick\FilterBrick
     *
     * @throws \DOMFormatException
     */
	public function LoadFromXml(DesignElement $oMDElement)
	{
		parent::LoadFromXml($oMDElement);

		// Checking specific elements
		/** @var \Combodo\iTop\DesignElement $oBrickSubNode */
		foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode)
		{
			switch ($oBrickSubNode->nodeName)
			{
				case 'target_brick':
					/** @var \Combodo\iTop\DesignElement $oTargetBrickNode */
					foreach ($oBrickSubNode->GetNodes('*') as $oTargetBrickNode)
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
