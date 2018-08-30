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

    private $aAllowedTags;
    private $oOriginalSet;
    private $aOriginalObjects = null;

    /**
     * @var bool
     */
    private $bHasDelta = false;

    /**
     * Object from the original set, minus the removed objects
     * @var DBObject[] array of iObjectId => DBObject
     */
    private $aPreserved = array();

    /**
     * @var DBObject[] New items
     */
    private $aAdded = array();

    /**
     * @var int[] Removed items
     */
    private $aRemoved = array();

    /**
     * __toString magical function overload.
     */
    public function __toString()
    {
        return '';
    }

    /**
     * ormTagSet constructor.
     *
     * @param string $sClass
     * @param string $sAttCode
     * @param DBObjectSet|null $oOriginalSet
     *
     * @throws \Exception
     */
    public function __construct($sClass, $sAttCode, DBObjectSet $oOriginalSet = null)
    {
        $this->sAttCode = $sAttCode;
        $this->oOriginalSet = $oOriginalSet ? clone $oOriginalSet : null;

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
        foreach ($this->aPreserved as $oTag)
        {
            try
            {
                $aValues[] = $oTag->Get('tag_code');
            } catch (CoreException $e)
            {
                IssueLog::Error($e->getMessage());
            }
        }
        foreach ($this->aAdded as $oTag)
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

    public function GetLabel()
    {
        $aLabels = array();
        $aValues = array();
        foreach ($this->aPreserved as $oTag)
        {
            try
            {
                $aValues[$oTag->Get('tag_code')] = $oTag->Get('tag_label');
            } catch (CoreException $e)
            {
                IssueLog::Error($e->getMessage());
            }
        }
        foreach ($this->aAdded as $oTag)
        {
            try
            {
                $aValues[$oTag->Get('tag_code')] = $oTag->Get('tag_label');
            } catch (CoreException $e)
            {
                IssueLog::Error($e->getMessage());
            }
        }
        ksort($aValues);
        foreach($aValues as $sLabel)
        {
            $aLabels[] = $sLabel;
        }
        return $aLabels;
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
     *
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
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
        foreach ($aTagList as $oTag)
        {
            $sCode = $oTag->Get('tag_code');
            if ($sCode === $sTagCode)
            {
                return true;
            }
        }
        return false;
    }

    private function RemoveTagFromList(&$aTagList, $sTagCode)
    {
        foreach ($aTagList as $index => $oTag)
        {
            $sCode = $oTag->Get('tag_code');
            if ($sCode === $sTagCode)
            {
                unset($aTagList[$index]);
                return $oTag;
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
     * @throws \CoreException
     * @throws \Exception
     */
    private function GetAllowedTags()
    {
        if (!$this->aAllowedTags)
        {
            $oSearch = new DBObjectSearch($this->GetTagDataClass());
            $oSearch->AddCondition('tag_class', $this->sClass);
            $oSearch->AddCondition('tag_attcode', $this->sAttCode);
            $oSet = new DBObjectSet($oSearch);
            $this->aAllowedTags = $oSet->ToArray();
        }
        return $this->aAllowedTags;
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
        return implode(' ',$this->GetValue()) === implode(' ', $other->GetValue());
    }

    public function GetTagDataClass()
    {
        return MetaModel::GetTagDataClass($this->sClass, $this->sAttCode);
    }
}