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

use \DOMFormatException;
use \Combodo\iTop\DesignElement;
use \Combodo\iTop\Portal\Brick\PortalBrick;

/**
 * Description of CreateBrick
 * 
 * @author Guillaume Lajarige
 */
class CreateBrick extends PortalBrick
{
	const DEFAULT_HOME_ICON_CLASS = 'fa fa-plus';
	const DEFAULT_NAVIGATION_MENU_ICON_CLASS = 'fa fa-plus fa-2x';
	const DEFAULT_CLASS = '';

	static $sRouteName = 'p_create_brick';
	protected $sClass;
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
	 * @return CreateBrick
	 */
	public function LoadFromXml(DesignElement $oMDElement)
	{
		parent::LoadFromXml($oMDElement);

		// Checking specific elements
		foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode)
		{
			switch ($oBrickSubNode->nodeName)
			{
				case 'class':
					$this->SetClass($oBrickSubNode->GetText(self::DEFAULT_CLASS));
					break;

				case 'rules':
					foreach ($oBrickSubNode->childNodes as $oRuleNode)
					{
						if ($oRuleNode->hasAttribute('id') && $oRuleNode->getAttribute('id') !== '')
						{
							$this->aRules[] = $oRuleNode->getAttribute('id');
						}
						else
						{
							throw new DOMFormatException('CreateBrick:  /rules/rule tag must have an "id" attribute and it must not be empty', null, null, $oRuleNode);
						}
					}
					break;
			}
		}

		return $this;
	}

}

?>