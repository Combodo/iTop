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
 * Description of CreateBrick
 *
 * @package Combodo\iTop\Portal\Brick
 * @since   2.4.0
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class CreateBrick extends PortalBrick
{
	// Overloaded constants
	const DEFAULT_DECORATION_CLASS_HOME = 'fas fa-plus';
	const DEFAULT_DECORATION_CLASS_NAVIGATION_MENU = 'fas fa-plus fa-2x';
	const DEFAULT_PAGE_TEMPLATE_PATH = 'itop-portal-base/portal/templates/bricks/create/modal.html.twig';

	/** @var string DEFAULT_CLASS */
	const DEFAULT_CLASS = '';

	// Overloaded variables
	public static $sRouteName = 'p_create_brick';

	/** @var string $sClass */
	protected $sClass;
	/** @var array $aRules */
	protected $aRules;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->sClass = static::DEFAULT_CLASS;
		$this->aRules = array();
	}

	/**
	 * Returns the brick class
	 *
	 * @return string
	 */
	public function GetClass()
	{
		return $this->sClass;
	}

	/**
	 * Sets the class of the brick
	 *
	 * @param string $sClass
	 *
	 * @return \Combodo\iTop\Portal\Brick\CreateBrick
	 */
	public function SetClass($sClass)
	{
		$this->sClass = $sClass;

		return $this;
	}

	/**
	 * Returns the brick rules
	 *
	 * @return array
	 */
	public function GetRules()
	{
		return $this->aRules;
	}

	/**
	 * Sets the rules of the brick
	 *
	 * @param array $aRules
	 *
	 * @return \Combodo\iTop\Portal\Brick\CreateBrick
	 */
	public function SetRules($aRules)
	{
		$this->aRules = $aRules;

		return $this;
	}

	/**
	 * Load the brick's data from the xml passed as a ModuleDesignElement.
	 * This is used to set all the brick attributes at once.
	 *
	 * @param \Combodo\iTop\DesignElement $oMDElement
	 *
	 * @return \Combodo\iTop\Portal\Brick\CreateBrick
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
				case 'class':
					$this->SetClass($oBrickSubNode->GetText(self::DEFAULT_CLASS));
					break;

				case 'rules':
					/** @var \Combodo\iTop\DesignElement $oRuleNode */
					foreach ($oBrickSubNode->GetNodes('*') as $oRuleNode)
					{
						if ($oRuleNode->hasAttribute('id') && $oRuleNode->getAttribute('id') !== '')
						{
							$this->aRules[] = $oRuleNode->getAttribute('id');
						}
						else
						{
							throw new DOMFormatException('CreateBrick:  /rules/rule tag must have an "id" attribute and it must not be empty',
								null, null, $oRuleNode);
						}
					}
					break;
			}
		}

		return $this;
	}

}
