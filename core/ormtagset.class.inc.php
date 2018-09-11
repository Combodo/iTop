<?php
/**
 * Copyright (c) 2010-2018 Combodo SARL
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
final class ormTagSet
{
	private $sClass; // class of the tag field
	private $sAttCode; // attcode of the tag field
	private $aOriginalObjects = null;

	/**
	 * @var bool
	 */
	private $bHasDelta = false;

	/**
	 * Object from the original set, minus the removed objects
	 *
	 * @var DBObject[] array of iObjectId => DBObject
	 */
	private $aPreserved = array();

	/**
	 * @var DBObject[] New items
	 */
	private $aAdded = array();

	/**
	 * @var DBObject[] Removed items
	 */
	private $aRemoved = array();

	/**
	 * __toString magical function overload.
	 */
	public function __toString()
	{
		$aValue = $this->GetValue();
		if (!empty($aValue))
		{
			return implode(' ', $aValue);
		}
		else
		{
			return ' ';
		}
	}

	/**
	 * ormTagSet constructor.
	 *
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @throws \Exception
	 */
	public function __construct($sClass, $sAttCode)
	{
		$this->sAttCode = $sAttCode;

		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		if (!$oAttDef instanceof AttributeTagSet)
		{
			throw new Exception("ormTagSet: field {$sClass}:{$sAttCode} is not a tag");
		}
		$this->sClass = $sClass;
	}

	/**
	 *
	 * @param array $aTagCodes
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue when a code is invalid
	 */
	public function SetValue($aTagCodes)
	{
		if (!is_array($aTagCodes))
		{
			throw new CoreUnexpectedValue("Wrong value {$aTagCodes} for {$this->sClass}:{$this->sAttCode}");
		}

		$oTags = array();
		foreach($aTagCodes as $sTagCode)
		{
			$oTag = $this->GetTagFromCode($sTagCode);
			$oTags[$oTag->GetKey()] = $oTag;
		}

		$this->aPreserved = &$oTags;
		$this->aRemoved = array();
		$this->aAdded = array();
		$this->aOriginalObjects = $oTags;
		$this->bHasDelta = false;
	}

	/**
	 * @return array of tag codes
	 */
	public function GetValue()
	{
		$aValues = array();
		foreach($this->aPreserved as $oTag)
		{
			try
			{
				$aValues[] = $oTag->Get('tag_code');
			} catch (CoreException $e)
			{
				IssueLog::Error($e->getMessage());
			}
		}
		foreach($this->aAdded as $oTag)
		{
			try
			{
				$aValues[] = $oTag->Get('tag_code');
			} catch (CoreException $e)
			{
				IssueLog::Error($e->getMessage());
			}
		}

		sort($aValues);

		return $aValues;
	}

	/**
	 * @return array of tag labels indexed by code
	 */
	public function GetTags()
	{
		$aTags = array();
		foreach($this->aPreserved as $oTag)
		{
			try
			{
				$aTags[$oTag->Get('tag_code')] = $oTag->Get('tag_label');
			} catch (CoreException $e)
			{
				IssueLog::Error($e->getMessage());
			}
		}
		foreach($this->aAdded as $oTag)
		{
			try
			{
				$aTags[$oTag->Get('tag_code')] = $oTag->Get('tag_label');
			} catch (CoreException $e)
			{
				IssueLog::Error($e->getMessage());
			}
		}
		ksort($aTags);

		return $aTags;
	}

	/**
	 * @return array of tag labels indexed by code for only the added tags
	 */
	public function GetAddedTags()
	{
		$aTags = array();
		foreach($this->aAdded as $oTag)
		{
			try
			{
				$aTags[$oTag->Get('tag_code')] = $oTag->Get('tag_label');
			} catch (CoreException $e)
			{
				IssueLog::Error($e->getMessage());
			}
		}
		ksort($aTags);

		return $aTags;
	}

	/**
	 * @return array of tag labels indexed by code for only the removed tags
	 */
	public function GetRemovedTags()
	{
		$aTags = array();
		foreach($this->aRemoved as $oTag)
		{
			try
			{
				$aTags[$oTag->Get('tag_code')] = $oTag->Get('tag_label');
			} catch (CoreException $e)
			{
				IssueLog::Error($e->getMessage());
			}
		}
		ksort($aTags);

		return $aTags;
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
	public function GetDelta(ormTagSet $oOtherTagSet)
	{
		$oTag = new ormTagSet($this->sClass, $this->sAttCode);
		// Set the initial value
		$aOrigTagCodes = $this->GetValue();
		$oTag->SetValue($aOrigTagCodes);
		// now remove everything
		foreach($aOrigTagCodes as $sTagCode)
		{
			$oTag->RemoveTag($sTagCode);
		}
		// now add the tags of the other TagSet
		foreach($oOtherTagSet->GetValue() as $sTagCode)
		{
			$oTag->AddTag($sTagCode);
		}
		$aDelta = array();
		$aDelta['added'] = $oTag->GetAddedTags();
		$aDelta['removed'] = $oTag->GetRemovedTags();

		return $aDelta;
	}

	/**
	 * Apply a delta to the current TagSet
	 *
	 * @param $aDelta
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function ApplyDelta($aDelta)
	{
		if (isset($aDelta['removed']))
		{
			foreach($aDelta['removed'] as $sTagCode => $aTagLabel)
			{
				$this->RemoveTag($sTagCode);
			}
		}
		if (isset($aDelta['added']))
		{
			foreach($aDelta['added'] as $sTagCode => $aTagLabel)
			{
				$this->AddTag($sTagCode);
			}
		}
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
	 * @param $sTagCode
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function AddTag($sTagCode)
	{
		if ($this->IsTagInList($this->aPreserved, $sTagCode) || $this->IsTagInList($this->aAdded, $sTagCode))
		{
			// nothing to do, already existing tag
			return;
		}
		// if removed then added again
		if (($oTag = $this->RemoveTagFromList($this->aRemoved, $sTagCode)) !== false)
		{
			// put it back into preserved
			$this->aPreserved[] = $oTag;
		}
		else
		{
			$this->aAdded[] = $this->GetTagFromCode($sTagCode);
		}
		$this->UpdateHasDeltaFlag();
	}

	/**
	 * @param $sTagCode
	 */
	public function RemoveTag($sTagCode)
	{
		if ($this->IsTagInList($this->aRemoved, $sTagCode))
		{
			// nothing to do, already removed tag
			return;
		}
		// if added then remove it
		if (($oTag = $this->RemoveTagFromList($this->aAdded, $sTagCode)) === false)
		{
			// if present then remove it
			if (($oTag = $this->RemoveTagFromList($this->aPreserved, $sTagCode)) !== false)
			{
				$this->aRemoved[] = $oTag;
			}
		}
		$this->UpdateHasDeltaFlag();
	}

	private function IsTagInList($aTagList, $sTagCode)
	{
		foreach($aTagList as $oTag)
		{
			/** @var \TagSetFieldData $oTag */
			try
			{
				$sCode = $oTag->Get('tag_code');
				if ($sCode === $sTagCode)
				{
					return true;
				}
			} catch (CoreException $e)
			{
				IssueLog::Error($e->getMessage());
			}
		}

		return false;
	}

	private function RemoveTagFromList(&$aTagList, $sTagCode)
	{
		foreach($aTagList as $index => $oTag)
		{
			/** @var \TagSetFieldData $oTag */
			try
			{
				$sCode = $oTag->Get('tag_code');
				if ($sCode === $sTagCode)
				{
					unset($aTagList[$index]);

					return $oTag;
				}
			} catch (CoreException $e)
			{
				IssueLog::Error($e->getMessage());
			}
		}

		return false;
	}

	private function UpdateHasDeltaFlag()
	{
		if ((count($this->aAdded) == 0) && (count($this->aRemoved) == 0))
		{
			$this->bHasDelta = false;
		}
		else
		{
			$this->bHasDelta = true;
		}
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
			if ($oAllowedTag->Get('tag_code') === $sTagCode)
			{
				return $oAllowedTag;
			}
		}
		throw new CoreUnexpectedValue("{$sTagCode} is not defined as a valid tag for {$this->sClass}:{$this->sAttCode}");
	}

	/**
	 * @return array
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
	public function Equals(ormTagSet $other)
	{
		if ($this->GetTagDataClass() !== $other->GetTagDataClass())
		{
			return false;
		}

		return implode(' ', $this->GetValue()) === implode(' ', $other->GetValue());
	}

	public function GetTagDataClass()
	{
		return MetaModel::GetTagDataClass($this->sClass, $this->sAttCode);
	}
}