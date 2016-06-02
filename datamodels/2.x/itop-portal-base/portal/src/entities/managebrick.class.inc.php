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

use \Combodo\iTop\DesignElement;
use \Combodo\iTop\Portal\Brick\PortalBrick;
use DOMFormatException;
use DBSearch;
use MetaModel;

/**
 * Description of ManageBrick
 * 
 * @author Guillaume Lajarige
 */
class ManageBrick extends PortalBrick
{
	const DEFAULT_HOME_ICON_CLASS = 'fa fa-pencil-square';
	const DEFAULT_NAVIGATION_MENU_ICON_CLASS = 'fa fa-pencil-square fa-2x';
	const ENUM_ACTION_VIEW = 'view';
	const ENUM_ACTION_EDIT = 'edit';
	const DEFAULT_PAGE_TEMPLATE_PATH = 'itop-portal-base/portal/src/views/bricks/manage/layout.html.twig';
	const DEFAULT_OQL = '';
	const DEFAULT_OPENING_MODE = self::ENUM_ACTION_EDIT;
	const DEFAULT_DATA_LOADING = self::ENUM_DATA_LOADING_LAZY;
	const DEFAULT_COUNT_PER_PAGE_LIST = 20;
	const DEFAULT_ZLIST_FIELDS = 'list';

	static $sRouteName = 'p_manage_brick';
	protected $sOql;
	protected $sOpeningMode;
	protected $aGrouping;
	protected $aFields;

	public function __construct()
	{
		parent::__construct();

		$this->sOql = static::DEFAULT_OQL;
		$this->sOpeningMode = static::DEFAULT_OPENING_MODE;
		$this->aGrouping = array();
		$this->aFields = array();

		// This is hardcoded for now, we might allow area grouping on another attribute in the futur
		$this->AddGrouping('areas', array('attribute' => 'finalclass'));
	}

	/**
	 * Returns the brick oql
	 *
	 * @return string
	 */
	public function GetOql()
	{
		return $this->sOql;
	}

	/**
	 * Returns the brick's objects opening mode (edit or view)
	 *
	 * @return string
	 */
	public function GetOpeningMode()
	{
		return $this->sOpeningMode;
	}

	/**
	 * Returns the brick grouping
	 *
	 * @return array
	 */
	public function GetGrouping()
	{
		return $this->aGrouping;
	}

	/**
	 * Returns the brick fields to display in the table
	 *
	 * @return array
	 */
	public function GetFields()
	{
		return $this->aFields;
	}

	/**
	 * Sets the oql of the brick
	 *
	 * @param string $sOql
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function SetOql($sOql)
	{
		$this->sOql = $sOql;
		return $this;
	}

	/**
	 * Sets the brick's objects opening mode
	 *
	 * @param string $sOpeningMode
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function SetOpeningMode($sOpeningMode)
	{
		$this->sOpeningMode = $sOpeningMode;
		return $this;
	}

	/**
	 * Sets the grouping of the brick
	 *
	 * @param array $aGrouping
	 */
	public function SetGrouping($aGrouping)
	{
		$this->aGrouping = $aGrouping;
		return $this;
	}

	/**
	 * Sets the fields of the brick
	 *
	 * @param array $aFields
	 */
	public function SetFields($aFields)
	{
		$this->aFields = $aFields;
		return $this;
	}

	/**
	 * Adds a grouping.
	 *
	 * Grouping "tabs" must be of form array("attribute" => value)
	 *
	 * @param string $sName (Must be "tabs" or -Not implemented yet, implicit grouping on y axis-)
	 * @param array $aGrouping
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function AddGrouping($sName, $aGrouping)
	{
		$this->aGrouping[$sName] = $aGrouping;

		// Sorting
		if (!$this->IsGroupingByDistinctValues($sName))
		{
			usort($this->aGrouping[$sName]['groups'], function($a, $b)
			{
				return $a['rank'] > $b['rank'];
			});
		}

		return $this;
	}

	/**
	 * Removes a grouping by its name
	 *
	 * @param string $sName
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function RemoveGrouping($sName)
	{
		if (isset($this->aGrouping[$sName]))
		{
			unset($this->aGrouping[$sName]);
		}
		return $this;
	}

	/**
	 * Adds a field to display from its attribute_code.
	 *
	 * @param string $sAttCode
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function AddField($sAttCode)
	{
		if (!in_array($sAttCode, $this->aFields))
		{
			$this->aFields[] = $sAttCode;
		}

		return $this;
	}

	/**
	 * Removes a field
	 *
	 * @param string $sAttCode
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function RemoveField($sAttCode)
	{
		if (isset($this->aFields[$sAttCode]))
		{
			unset($this->aFields[$sAttCode]);
		}
		return $this;
	}

	/**
	 * Returns if the brick has grouping tabs or not.
	 *
	 * @return boolean
	 */
	public function HasGroupingTabs()
	{
		return (isset($this->aGrouping['tabs']) && !empty($this->aGrouping['tabs']));
	}

	/**
	 * Returns the grouping tabs properties if exists, else returns false.
	 *
	 * @return mixed false if there is no grouping named 'tabs', otherwise the array
	 */
	public function GetGroupingTabs()
	{
		return (isset($this->aGrouping['tabs'])) ? $this->aGrouping['tabs'] : false;
	}

	/**
	 * Returns if the brick has grouping areas or not.
	 *
	 * @return boolean
	 */
	public function HasGroupingAreas()
	{
		return (isset($this->aGrouping['areas']) && !empty($this->aGrouping['areas']));
	}

	/**
	 * Returns the grouping areas properties if exists, else returns false.
	 *
	 * @return mixed false if there is no grouping named 'areas', otherwise the array
	 */
	public function GetGroupingAreas()
	{
		return (isset($this->aGrouping['areas'])) ? $this->aGrouping['areas'] : false;
	}

	/**
	 * Returns true is the groupings $sGroupingName properties exists and is of the form attribute => attribute_code.
	 * This is supposed to be called by the IsGroupingTabsByDistinctValues / IsGroupingAreasByDistinctValues function.
	 *
	 * @param string $sGroupingName
	 * @return boolean
	 */
	public function IsGroupingByDistinctValues($sGroupingName)
	{
		return (isset($this->aGrouping[$sGroupingName]) && isset($this->aGrouping[$sGroupingName]['attribute']) && $this->aGrouping[$sGroupingName]['attribute'] !== '');
	}

	/**
	 * Returns true is the groupings tabs properties exists and is of the form attribute => attribute_code.
	 * This is mostly used to know if the tabs are grouped by attribute distinct values or by meta-groups (eg : status in ('accepted', 'opened')).
	 *
	 * @return boolean
	 */
	public function IsGroupingTabsByDistinctValues()
	{
		return $this->IsGroupingByDistinctValues('tabs');
	}

	/**
	 * Returns true is the groupings areas properties exists and is of the form attribute => attribute_code.
	 * This is mostly used to know if the areas are grouped by attribute distinct values or by meta-groups (eg : finalclass in ('Server', 'Router')).
	 *
	 * @return boolean
	 */
	public function IsGroupingAreasByDistinctValues()
	{
		return $this->IsGroupingByDistinctValues('areas');
	}

	/**
	 * Load the brick's data from the xml passed as a ModuleDesignElement.
	 * This is used to set all the brick attributes at once.
	 *
	 * @param \Combodo\iTop\DesignElement $oMDElement
	 * @return ManageBrick
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
					$sClass = $oBrickSubNode->GetText();
					if ($sClass === '')
					{
						throw new DOMFormatException('ManageBrick : class tag is empty. Must contain Classname', null, null, $oBrickSubNode);
					}

					$this->SetOql('SELECT ' . $sClass);
					break;

				case 'oql':
					$sOql = $oBrickSubNode->GetText();
					if ($sOql === '')
					{
						throw new DOMFormatException('ManageBrick : oql tag is empty. Must contain OQL statement', null, null, $oBrickSubNode);
					}

					$this->SetOql($sOql);
					break;

				case 'opening_mode':
					$sOpeningMode = $oBrickSubNode->GetText(static::DEFAULT_OPENING_MODE);
					if (!in_array($sOpeningMode, array(static::ENUM_ACTION_VIEW, static::ENUM_ACTION_EDIT)))
					{
						throw new DOMFormatException('ManageBrick : opening_mode tag value must be edit|view ("' . $sOpeningMode . '" given)', null, null, $oBrickSubNode);
					}

					$this->SetOpeningMode($sOpeningMode);
					break;

				case 'fields':
					foreach ($oBrickSubNode->GetNodes('./field') as $oFieldNode)
					{
						if (!$oFieldNode->hasAttribute('id'))
						{
							throw new DOMFormatException('ManageBrick : Field must have a unique ID attribute', null, null, $oFieldNode);
						}
						$this->AddField($oFieldNode->getAttribute('id'));
					}
					break;

				case 'grouping':
					// Tabs grouping
					foreach ($oBrickSubNode->GetNodes('./tabs/*') as $oGroupingNode)
					{
						switch ($oGroupingNode->nodeName)
						{
							case 'attribute':
								$sAttribute = $oGroupingNode->GetText();
								if ($sAttribute !== '')
								{
									$this->AddGrouping('tabs', array('attribute' => $sAttribute));
								}
								break;
							case 'groups':
								$aGroups = array();
								foreach ($oGroupingNode->GetNodes('./group') as $oGroupNode)
								{
									if (!$oGroupNode->hasAttribute('id'))
									{
										throw new DOMFormatException('ManageBrick : Group must have a unique ID attribute', null, null, $oGroupNode);
									}
									$sGroupId = $oGroupNode->getAttribute('id');

									$aGroup = array();
									$aGroup['id'] = $sGroupId; // We don't put the group id as the $aGroups key because the array will be sorted later in AddGrouping, which replace array keys by integer ordered keys
									foreach ($oGroupNode->childNodes as $oGroupProperty)
									{
										switch ($oGroupProperty->nodeName)
										{
											case 'rank':
												$aGroup[$oGroupProperty->nodeName] = (int) $oGroupProperty->GetText(0);
												break;
											case 'title':
											case 'condition':
												$aGroup[$oGroupProperty->nodeName] = $oGroupProperty->GetText();
												break;
										}
									}

									// Checking constitancy
									if (!isset($aGroup['title']) || $aGroup['title'] === '')
									{
										throw new DOMFormatException('ManageBrick : Group must have a title tag and it must not be empty', null, null, $oGroupNode);
									}
									if (!isset($aGroup['condition']) || $aGroup['condition'] === '')
									{
										throw new DOMFormatException('ManageBrick : Group must have a condition tag and it must not be empty', null, null, $oGroupNode);
									}
									$aGroups[] = $aGroup;
								}
								$this->AddGrouping('tabs', array('groups' => $aGroups));
								break;
						}
					}
					break;
			}
		}

		// Checking if has an oql
		if ($this->GetOql() === '')
		{
			throw new DOMFormatException('BrowseBrick : must have a valid <class|oql> tag', null, null, $oMDElement);
		}

		// Checking if specified fields, if not we put those from the details zlist
		if (empty($this->aFields))
		{
			$sClass = DBSearch::FromOQL($this->GetOql());
			$aFields = MetaModel::FlattenZList(MetaModel::GetZListItems($sClass->GetClass(), static::DEFAULT_ZLIST_FIELDS));
			$this->SetFields($aFields);
		}

		return $this;
	}

}

?>