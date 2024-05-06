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
final class ormTagSet extends ormSet
{
	/**
	 * ormTagSet constructor.
	 *
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param int $iLimit
	 *
	 * @throws \Exception
	 */
	public function __construct($sClass, $sAttCode, $iLimit = 12)
	{
		parent::__construct($sClass, $sAttCode, $iLimit);
	}

	/**
	 *
	 * @param array $aTagCodes
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue when a code is invalid
	 */
	public function SetValues($aTagCodes)
	{
		if (is_null($aTagCodes))
		{
			$aTagCodes = array();
		}
		if (!is_array($aTagCodes))
		{
			throw new CoreUnexpectedValue("Wrong value {$aTagCodes} for {$this->sClass}:{$this->sAttCode}");
		}

		$oTags = array();
		$iCount = 0;
		$bError = false;
		foreach($aTagCodes as $sTagCode)
		{
			$iCount++;
			if (($this->iLimit != 0) && ($iCount > $this->iLimit))
			{
				$bError = true;
				continue;
			}
			$oTag = $this->GetTagFromCode($sTagCode);
			$oTags[$sTagCode] = $oTag;
		}

		$this->aPreserved = &$oTags;
		$this->aRemoved = array();
		$this->aAdded = array();
		$this->aModified = array();
		$this->aOriginalObjects = $oTags;

		if ($bError)
		{
			throw new CoreException("Maximum number of tags ({$this->iLimit}) reached for {$this->sClass}:{$this->sAttCode}");
		}
	}

	/**
	 * @return array of tag codes
	 */
	public function GetValues()
	{
		$aValues = array();
		foreach($this->aPreserved as $sTagCode => $oTag)
		{
			$aValues[] = $sTagCode;
		}
		foreach($this->aAdded as $sTagCode => $oTag)
		{
			$aValues[] = $sTagCode;
		}

		sort($aValues);

		return $aValues;
	}

	/**
	 * @return array of tag labels indexed by code
	 */
	public function GetLabels()
	{
		$aTags = array();
		/** @var \TagSetFieldData $oTag */
		foreach($this->aPreserved as $sTagCode => $oTag)
		{
			try
			{
				$aTags[$sTagCode] = $oTag->Get('label');
			} catch (CoreException $e)
			{
				IssueLog::Error($e->getMessage());
			}
		}
		foreach($this->aAdded as $sTagCode => $oTag)
		{
			try
			{
				$aTags[$sTagCode] = $oTag->Get('label');
			} catch (CoreException $e)
			{
				IssueLog::Error($e->getMessage());
			}
		}
		ksort($aTags);

		return $aTags;
	}

	/**
	 * @return array index: code, value: corresponding {@see \TagSetFieldData}
	 */
	public function GetTags()
	{
		$aTags = array();
		foreach($this->aPreserved as $sTagCode => $oTag)
		{
			$aTags[$sTagCode] = $oTag;
		}
		foreach($this->aAdded as $sTagCode => $oTag)
		{
			$aTags[$sTagCode] = $oTag;
		}
		ksort($aTags);

		return $aTags;
	}

	/**
	 * @return array of tag labels indexed by code for only the added tags
	 */
	private function GetAddedCodes()
	{
		$aTags = array();
		foreach($this->aAdded as $sTagCode => $oTag)
		{
			$aTags[] = $sTagCode;
		}
		ksort($aTags);

		return $aTags;
	}

	/**
	 * @return array of tag labels indexed by code for only the removed tags
	 */
	private function GetRemovedCodes()
	{
		$aTags = array();
		foreach($this->aRemoved as $sTagCode => $oTag)
		{
			$aTags[] = $sTagCode;
		}
		ksort($aTags);

		return $aTags;
	}

	/**
	 * @return array of tag labels indexed by code for only the added tags
	 */
	private function GetAddedTags()
	{
		$aTags = array();
		foreach($this->aAdded as $sTagCode => $oTag)
		{
			$aTags[$sTagCode] = $oTag;
		}
		ksort($aTags);

		return $aTags;
	}

	/**
	 * @return array of tag labels indexed by code for only the removed tags
	 */
	private function GetRemovedTags()
	{
		$aTags = array();
		foreach($this->aRemoved as $sTagCode => $oTag)
		{
			$aTags[$sTagCode] = $oTag;
		}
		ksort($aTags);

		return $aTags;
	}

	/** Get the delta with another TagSet
	 *
	 *  $aDelta['added] = array of tag codes for only the added tags
	 *  $aDelta['removed'] = array of tag codes for only the removed tags
	 *
	 * @param \ormTagSet $oOtherTagSet
	 *
	 * @return array
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 */
	public function GetDelta(ormSet $oOtherTagSet)
	{
		$oTag = new ormTagSet($this->sClass, $this->sAttCode, 0);
		// Set the initial value
		$aOrigTagCodes = $this->GetValues();
		$oTag->SetValues($aOrigTagCodes);
		// now remove everything
		foreach($aOrigTagCodes as $sTagCode)
		{
			$oTag->Remove($sTagCode);
		}
		// now add the tags of the other TagSet
		foreach($oOtherTagSet->GetValues() as $sTagCode)
		{
			$oTag->Add($sTagCode);
		}
		$aDelta = array();
		$aDelta['added'] = $oTag->GetAddedCodes();
		$aDelta['removed'] = $oTag->GetRemovedCodes();

		return $aDelta;
	}

	/** Get the delta with another TagSet
	 *
	 *  $aDelta['added] = array of tag labels indexed by code for only the added tags
	 *  $aDelta['removed'] = array of tag labels indexed by code for only the removed tags
	 *
	 * @param \ormTagSet $oOtherTagSet
	 *
	 * @return array
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 */
	public function GetDeltaTags(ormTagSet $oOtherTagSet)
	{
		$oTag = new ormTagSet($this->sClass, $this->sAttCode, 0);
		// Set the initial value
		$aOrigTagCodes = $this->GetValues();
		$oTag->SetValues($aOrigTagCodes);
		// now remove everything
		foreach($aOrigTagCodes as $sTagCode)
		{
			$oTag->Remove($sTagCode);
		}
		// now add the tags of the other TagSet
		foreach($oOtherTagSet->GetValues() as $sTagCode)
		{
			$oTag->Add($sTagCode);
		}
		$aDelta = array();
		$aDelta['added'] = $oTag->GetAddedTags();
		$aDelta['removed'] = $oTag->GetRemovedTags();

		return $aDelta;
	}

	/**
	 * @return string[] list of codes for partial entries
	 */
	public function GetModified()
	{
		$aModifiedTagCodes = array_keys($this->aModified);
		sort($aModifiedTagCodes);

		return $aModifiedTagCodes;
	}

	/**
	 * @return string[] list of codes for added entries
	 */
	public function GetAdded()
	{
		$aAddedTagCodes = array_keys($this->aAdded);
		sort($aAddedTagCodes);

		return $aAddedTagCodes;
	}

	/**
	 * @return string[] list of codes for removed entries
	 */
	public function GetRemoved()
	{
		$aRemovedTagCodes = array_keys($this->aRemoved);
		sort($aRemovedTagCodes);

		return $aRemovedTagCodes;
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

		// Keep only the aModified list
		$this->aRemoved = array();
		$this->aAdded = array();
	}

	/**
	 * Check whether a tag code is valid or not for this TagSet
	 *
	 * @param string $sTagCode
	 *
	 * @return bool
	 */
	public function IsValidTag($sTagCode)
	{
		try
		{
			$this->GetTagFromCode($sTagCode);

			return true;
		} catch (Exception $e)
		{
			return false;
		}
	}

	/**
	 * @param string $sTagCode
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function Add($sTagCode)
	{
		if (($this->iLimit != 0) && ($this->Count() == $this->iLimit))
		{
			throw new CoreException("Maximum number of tags ({$this->iLimit}) reached for {$this->sClass}:{$this->sAttCode}");
		}
		if ($this->IsTagInList($this->aPreserved, $sTagCode) || $this->IsTagInList($this->aAdded, $sTagCode))
		{
			// nothing to do, already existing tag
			return;
		}
		// if removed then added again
		if (($oTag = $this->RemoveTagFromList($this->aRemoved, $sTagCode)) !== false)
		{
			// put it back into preserved
			$this->aPreserved[$sTagCode] = $oTag;
			// no need to add it to aModified : was already done when calling Remove method
		}
		else
		{
			$oTag = $this->GetTagFromCode($sTagCode);
			$this->aAdded[$sTagCode] = $oTag;
			$this->aModified[$sTagCode] = $oTag;
		}
	}

	/**
	 * @param $sTagCode
	 */
	public function Remove($sTagCode)
	{
		if ($this->IsTagInList($this->aRemoved, $sTagCode))
		{
			// nothing to do, already removed tag
			return;
		}

		$oTag = $this->RemoveTagFromList($this->aAdded, $sTagCode);
		if ($oTag !== false)
		{
			$this->aModified[$sTagCode] = $oTag;

			return; // if present in added, can't be in preserved !
		}

		$oTag = $this->RemoveTagFromList($this->aPreserved, $sTagCode);
		if ($oTag !== false)
		{
			$this->aModified[$sTagCode] = $oTag;
			$this->aRemoved[$sTagCode] = $oTag;
		}
	}

	private function IsTagInList($aTagList, $sTagCode)
	{
		return isset($aTagList[$sTagCode]);
	}

	/**
	 * @param \DBObject[] $aTagList
	 * @param string $sTagCode
	 *
	 * @return bool|\DBObject false if not found, else the removed element
	 */
	private function RemoveTagFromList(&$aTagList, $sTagCode)
	{
		if (!($this->IsTagInList($aTagList, $sTagCode)))
		{
			return false;
		}

		$oTag = $aTagList[$sTagCode];
		unset($aTagList[$sTagCode]);

		return $oTag;
	}

	/**
	 * @param $sTagCode
	 *
	 * @return DBObject tag
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreException
	 */
	private function GetTagFromCode($sTagCode)
	{
		$aAllowedTags = $this->GetAllowedTags();
		foreach($aAllowedTags as $oAllowedTag)
		{
			if ($oAllowedTag->Get('code') === $sTagCode)
			{
				return $oAllowedTag;
			}
		}
		throw new CoreUnexpectedValue("{$sTagCode} is not defined as a valid tag for {$this->sClass}:{$this->sAttCode}");
	}

	/**
	 * @param $sTagLabel
	 *
	 * @return string Tag code
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreException
	 */
	public function GetTagFromLabel($sTagLabel)
	{
		$aAllowedTags = $this->GetAllowedTags();
		foreach($aAllowedTags as $oAllowedTag)
		{
			if ($oAllowedTag->Get('label') === $sTagLabel)
			{
				return $oAllowedTag->Get('code');
			}
		}
		throw new CoreUnexpectedValue("{$sTagLabel} is not defined as a valid tag for {$this->sClass}:{$this->sAttCode}");
	}

	/**
	 * @return \TagSetFieldData[]
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	private function GetAllowedTags()
	{
		return TagSetFieldData::GetAllowedValues($this->sClass, $this->sAttCode);
	}

	/**
	 * Compare Tag Set
	 *
	 * @param \ormTagSet $other
	 *
	 * @return bool true if same tag set
	 */
	public function Equals(ormSet $other)
	{
		if (!($other instanceof ormTagSet))
		{
			return false;
		}
		if ($this->GetTagDataClass() !== $other->GetTagDataClass())
		{
			return false;
		}

		return implode(' ', $this->GetValues()) === implode(' ', $other->GetValues());
	}

	public function GetTagDataClass()
	{
		return TagSetFieldData::GetTagDataClassName($this->sClass, $this->sAttCode);
	}
}
