<?php
/**
 * Copyright (c) 2010-2024 Combodo SAS
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
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

/**
 * Created by PhpStorm.
 * Date: 24/08/2018
 * Time: 14:35
 */
class ormSet
{
	protected $sClass; // class of the field
	protected $sAttCode; // attcode of the field
	protected $aOriginalObjects = null;
	protected $m_bDisplayPartial = false;

	/**
	 * Object from the original set, minus the removed objects
	 */
	protected $aPreserved = array();

	/**
	 * New items
	 */
	protected $aAdded = array();

	/**
	 * Removed items
	 */
	protected $aRemoved = array();

	/**
	 * Modified items (mass edit)
	 */
	protected $aModified = array();

	/**
	 * @var int Max number of tags in collection
	 */
	protected $iLimit;

	/**
	 * __toString magical function overload.
	 */
	public function __toString()
	{
		$aValue = $this->GetValues();
		if (!empty($aValue))
		{
			return implode(', ', $aValue);
		}
		else
		{
			return ' ';
		}
	}

	/**
	 * ormSet constructor.
	 *
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param int $iLimit
	 *
	 * @throws \Exception
	 */
	public function __construct($sClass, $sAttCode, $iLimit = 12)
	{
		$this->sAttCode = $sAttCode;

		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		if (!$oAttDef instanceof AttributeSet)
		{
			throw new Exception("ormSet: field {$sClass}:{$sAttCode} is not a set");
		}
		$this->sClass = $sClass;
		$this->iLimit = $iLimit;
	}

	/**
	 * @return string
	 */
	public function GetClass()
	{
		return $this->sClass;
	}

	/**
	 * @return string
	 */
	public function GetAttCode()
	{
		return $this->sAttCode;
	}

	/**
	 *
	 * @param string[] $aItems
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue when a code is invalid
	 */
	public function SetValues($aItems)
	{
		if (!is_array($aItems))
		{
			throw new CoreUnexpectedValue("Wrong value {$aItems} for {$this->sClass}:{$this->sAttCode}");
		}

		$aValues = array();
		$iCount = 0;
		$bError = false;
		foreach($aItems as $sItem)
		{
			$iCount++;
			if (($this->iLimit != 0) && ($iCount > $this->iLimit))
			{
				$bError = true;
				continue;
			}
			$aValues[] = $sItem;
		}

		$this->aPreserved = &$aValues;
		$this->aRemoved = array();
		$this->aAdded = array();
		$this->aModified = array();
		$this->aOriginalObjects = $aValues;

		if ($bError)
		{
			throw new CoreException("Maximum number of items ({$this->iLimit}) reached for {$this->sClass}:{$this->sAttCode}");
		}
	}

	public function Count()
	{
		return count($this->aPreserved) + count($this->aAdded) - count($this->aRemoved);
	}

	/**
	 * @return array of codes
	 */
	public function GetValues()
	{
		$aValues = array_merge($this->aPreserved, $this->aAdded);
		sort($aValues);
		return $aValues;
	}

	public function GetLabels()
	{
		$aLabels = array();
		$aValues = $this->GetValues();
		foreach ($aValues as $sValue)
		{
			$aLabels[$sValue] = $sValue;
		}
		return $aLabels;
	}

	/**
	 * @return array of tag labels indexed by code for only the added tags
	 */
	private function GetAdded()
	{
		return $this->aAdded;
	}

	/**
	 * @return array of tag labels indexed by code for only the removed tags
	 */
	private function GetRemoved()
	{
		return $this->aRemoved;
	}

	/** Get the delta with another ItemSet
	 *
	 *  $aDelta['added] = array of tag codes for only the added tags
	 *  $aDelta['removed'] = array of tag codes for only the removed tags
	 *
	 * @param \ormSet $oOtherSet
	 *
	 * @return array
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 */
	public function GetDelta(ormSet $oOtherSet)
	{
		$oSet = new ormSet($this->sClass, $this->sAttCode, $this->iLimit);
		// Set the initial value
		$aOrigItems = $this->GetValues();
		$oSet->SetValues($aOrigItems);

		// now remove everything
		foreach($aOrigItems as $oItem)
		{
			$oSet->Remove($oItem);
		}

		// now add the tags of the other ItemSet
		foreach($oOtherSet->GetValues() as $oItem)
		{
			$oSet->Add($oItem);
		}

		$aDelta = array();
		$aDelta['added'] = $oSet->GetAdded();
		$aDelta['removed'] = $oSet->GetRemoved();

		return $aDelta;
	}

	/**
	 * @return string[] list of codes for partial entries
	 */
	public function GetModified()
	{
		return $this->aModified;
	}

	/**
	 * Apply a delta to the current ItemSet
	 *  $aDelta['added] = array of added items
	 *  $aDelta['removed'] = array of removed items
	 *
	 * @param $aDelta
	 *
	 * @throws \CoreException
	 */
	public function ApplyDelta($aDelta)
	{
		if (isset($aDelta['removed']))
		{
			foreach($aDelta['removed'] as $oItem)
			{
				$this->Remove($oItem);
			}
		}
		if (isset($aDelta['added']))
		{
			foreach($aDelta['added'] as $oItem)
			{
				$this->Add($oItem);
			}
		}

		// Reset the object
		$this->SetValues($this->GetValues());
	}

	/**
	 * @param string $oItem
	 *
	 * @throws \CoreException
	 */
	public function Add($oItem)
	{
		if (($this->iLimit != 0) && ($this->Count() > $this->iLimit))
		{
			throw new CoreException("Maximum number of items ({$this->iLimit}) reached for {$this->sClass}:{$this->sAttCode}");
		}
		if ($this->IsItemInList($this->aPreserved, $oItem) || $this->IsItemInList($this->aAdded, $oItem))
		{
			// nothing to do, already existing tag
			return;
		}
		// if removed and added again
		if (($this->RemoveItemFromList($this->aRemoved, $oItem)) !== false)
		{
			// put it back into preserved
			$this->aPreserved[] = $oItem;
			// no need to add it to aModified : was already done when calling RemoveItem method
		}
		else
		{
			$this->aAdded[] = $oItem;
			$this->aModified[] = $oItem;
		}
	}

	/**
	 * @param $oItem
	 */
	public function Remove($oItem)
	{
		if ($this->IsItemInList($this->aRemoved, $oItem))
		{
			// nothing to do, already removed tag
			return;
		}

		if ($this->RemoveItemFromList($this->aAdded, $oItem) !== false)
		{
			$this->aModified[] = $oItem;

			return; // if present in added, can't be in preserved !
		}

		if ($this->RemoveItemFromList($this->aPreserved, $oItem) !== false)
		{
			$this->aModified[] = $oItem;
			$this->aRemoved[] = $oItem;
		}
	}

	private function IsItemInList($aItemList, $oItem)
	{
		return in_array($oItem, $aItemList);
	}

	/**
	 * @param \DBObject[] $aItemList
	 * @param $oItem
	 *
	 * @return bool|\DBObject false if not found, else the removed element
	 */
	private function RemoveItemFromList(&$aItemList, $oItem)
	{
		if (!($this->IsItemInList($aItemList, $oItem)))
		{
			return false;
		}
		foreach ($aItemList as $index => $value)
		{
			if ($value === $oItem)
			{
				unset($aItemList[$index]);
				return $oItem;
			}
		}

		return false;
	}

	/**
	 * Populates the added and removed arrays for bulk edit
	 *
	 * @param string[] $aItems
	 *
	 * @throws \CoreException
	 */
	public function GenerateDiffFromArray($aItems)
	{
		foreach($this->GetValues() as $oCurrentItem)
		{
			if (!in_array($oCurrentItem, $aItems))
			{
				$this->Remove($oCurrentItem);
			}
		}

		foreach($aItems as $oNewItem)
		{
			$this->Add($oNewItem);
		}
	}

	/**
	 * Compare Item Set
	 *
	 * @param \ormSet $other
	 *
	 * @return bool true if same tag set
	 */
	public function Equals(ormSet $other)
	{
		return implode(', ', $this->GetValues()) === implode(', ', $other->GetValues());
	}

	/**
	 * @return bool
	 */
	public function DisplayPartial()
	{
		return $this->m_bDisplayPartial;
	}

	/**
	 * @param bool $m_bDisplayPartial
	 */
	public function SetDisplayPartial($m_bDisplayPartial)
	{
		$this->m_bDisplayPartial = $m_bDisplayPartial;
	}
}
