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
 * Description of UserProfileBrick
 * 
 * @author Guillaume Lajarige
 */
class UserProfileBrick extends PortalBrick
{
    const DEFAULT_PAGE_TEMPLATE_PATH = 'itop-portal-base/portal/src/views/bricks/user-profile/layout.html.twig';
	const DEFAULT_TILE_TEMPLATE_PATH = 'itop-portal-base/portal/src/views/bricks/user-profile/tile.html.twig';
	const DEFAULT_VISIBLE_NAVIGATION_MENU = false;
	const DEFAULT_VISIBLE_HOME = false;
	const DEFAUT_TITLE = 'Brick:Portal:UserProfile:Title';
	const DEFAULT_HOME_ICON_CLASS = 'glyphicon glyphicon-user';
	const DEFAULT_NAVIGATION_MENU_ICON_CLASS = 'glyphicon glyphicon-user';

	static $sRouteName = 'p_user_profile_brick';
	protected $aForm;

	public function __construct()
	{
		parent::__construct();

		$this->aForm = array(
			'id' => 'default-user-profile',
			'type' => 'zlist',
			'fields' => 'details',
			'layout' => null
		);
	}

	/**
	 *
	 * @return array
	 */
	public function GetForm()
	{
		return $this->aForm;
	}

	/**
	 *
	 * @param array $aForm
	 * @return \Combodo\iTop\Portal\Brick\UserProfileBrick
	 */
	public function SetForm($aForm)
	{
		$this->aForm = $aForm;
		return $this;
	}

	/**
	 * Load the brick's data from the xml passed as a ModuleDesignElement.
	 * This is used to set all the brick attributes at once.
	 *
	 * @param \Combodo\iTop\DesignElement $oMDElement
	 * @return UserProfileBrick
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
				case 'form':
					// Note : This is inspired by Combodo\iTop\Portal\Helper\ApplicationHelper::LoadFormsConfiguration()
					// Enumerating fields
					if ($oBrickSubNode->GetOptionalElement('fields') !== null)
					{
						$this->aForm['type'] = 'custom_list';
						$this->aForm['fields'] = array();

						foreach ($oBrickSubNode->GetOptionalElement('fields')->GetNodes('field') as $oFieldNode)
						{
							$sFieldId = $oFieldNode->getAttribute('id');
							if ($sFieldId !== '')
							{
								$aField = array();
								// Parsing field options like read_only, hidden and mandatory
								if ($oFieldNode->GetOptionalElement('read_only'))
								{
									$aField['readonly'] = ($oFieldNode->GetOptionalElement('read_only')->GetText('true') === 'true') ? true : false;
								}
								if ($oFieldNode->GetOptionalElement('mandatory'))
								{
									$aField['mandatory'] = ($oFieldNode->GetOptionalElement('mandatory')->GetText('true') === 'true') ? true : false;
								}
								if ($oFieldNode->GetOptionalElement('hidden'))
								{
									$aField['hidden'] = ($oFieldNode->GetOptionalElement('hidden')->GetText('true') === 'true') ? true : false;
								}

								$this->aForm['fields'][$sFieldId] = $aField;
							}
							else
							{
								throw new DOMFormatException('Field tag must have an id attribute', null, null, $oFormNode);
							}
						}
					}
					// Parsing presentation
					if ($oBrickSubNode->GetOptionalElement('twig') !== null)
					{
						// Extracting the twig template and removing the first and last lines (twig tags)
						$sXml = $oBrickSubNode->GetOptionalElement('twig')->Dump(true);
						//$sXml = $oMDElement->saveXML($oBrickSubNode->GetOptionalElement('twig'));
						$sXml = preg_replace('/^.+\n/', '', $sXml);
						$sXml = preg_replace('/\n.+$/', '', $sXml);

						$this->aForm['layout'] = array(
							'type' => (preg_match('/\{\{|\{\#|\{\%/', $sXml) === 1) ? 'twig' : 'xhtml',
							'content' => $sXml
						);
					}
					break;
			}
		}

		return $this;
	}

}

?>