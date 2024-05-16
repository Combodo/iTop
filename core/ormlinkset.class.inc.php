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

require_once('dbobjectiterator.php');


/**
 * The value for an attribute representing a set of links between the host object and "remote" objects
 *
 * @package     iTopORM
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class ormLinkSet implements iDBObjectSetIterator, Iterator, SeekableIterator
{
	public const LINK_ALIAS = 'Link';
	public const REMOTE_ALIAS = 'Remote';

	protected $sHostClass; // subclass of DBObject
	protected $sAttCode; // xxxxxx_list
	protected $sClass; // class of the links

	/**
	 * @var DBObjectSet
	 */
	protected $oOriginalSet;

	/**
	 * @var DBObject[] array of iObjectId => DBObject
	 */
	protected $aOriginalObjects = null;

	/**
	 * @var bool
	 */
	protected $bHasDelta = false;

	/**
	 * Object from the original set, minus the removed objects
	 * @var DBObject[] array of iObjectId => DBObject
	 */
	protected $aPreserved = array();

	/**
	 * @var DBObject[] New items
	 */
	protected $aAdded = array();

	/**
	 * @var DBObject[] Modified items (could also be found in aPreserved)
	 */
	protected $aModified = array();

	/**
	 * @var int[] Removed items
	 */
	protected $aRemoved = array();

	/**
	 * @var int Position in the collection
	 */
	protected $iCursor = 0;

	/**
	 * __toString magical function overload.
	 */
	public function __toString()
	{
		return '';
	}

	/**
	 * ormLinkSet constructor.
	 * @param $sHostClass
	 * @param $sAttCode
	 * @param DBObjectSet|null $oOriginalSet
	 * @throws Exception
	 */
	public function __construct($sHostClass, $sAttCode, DBObjectSet $oOriginalSet = null)
	{
		$this->sHostClass = $sHostClass;
		$this->sAttCode = $sAttCode;
		$this->oOriginalSet = $oOriginalSet ? clone $oOriginalSet : null;

		$oAttDef = MetaModel::GetAttributeDef($sHostClass, $sAttCode);
		if (!$oAttDef instanceof AttributeLinkedSet)
		{
			throw new Exception("ormLinkSet: $sAttCode is not a link set");
		}
		$this->sClass = $oAttDef->GetLinkedClass();
		if ($oOriginalSet && ($oOriginalSet->GetClass() != $this->sClass))
		{
			throw new Exception("ormLinkSet: wrong class for the original set, found {$oOriginalSet->GetClass()} while expecting {$oAttDef->GetLinkedClass()}");
		}
	}

	/**
	 * @return \DBObjectSearch
	 * @throws \CoreException
	 */
	public function GetFilter()
	{
		return clone $this->oOriginalSet->GetFilter();
	}

	/**
	 * Specify the subset of attributes to load (for each class of objects) before performing the SQL query for retrieving the rows from the DB
	 *
	 * @param array $aAttToLoad Format: alias => array of attribute_codes
	 *
	 * @return void
	 * @throws \CoreException
	 */
	public function OptimizeColumnLoad($aAttToLoad)
	{
		$this->oOriginalSet->OptimizeColumnLoad($aAttToLoad);
	}

	/**
	 * @param DBObject $oLink
	 */
	public function AddItem(DBObject $oLink)
	{
		assert($oLink instanceof $this->sClass);
		// No impact on the iteration algorithm
        $iObjectId = $oLink->GetKey();
		$this->aAdded[$iObjectId] = $oLink;
		$this->bHasDelta = true;
	}

    /**
     * @param DBObject $oObject
     * @param string $sClassAlias
     *
     * @deprecated Since iTop 2.4, use {@link \ormLinkSet::AddItem()} instead.
     */
	public function AddObject(DBObject $oObject, $sClassAlias = '')
    {
	    DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use \ormLinkSet::AddItem() instead');
	    $this->AddItem($oObject);
    }

	/**
	 * @param $iObjectId
	 */
	public function RemoveItem($iObjectId)
	{
		if (array_key_exists($iObjectId, $this->aPreserved))
		{
			unset($this->aPreserved[$iObjectId]);
			$this->aRemoved[$iObjectId] = $iObjectId;
			$this->bHasDelta = true;
		}
		else
        {
            if (array_key_exists($iObjectId, $this->aAdded))
            {
                unset($this->aAdded[$iObjectId]);
            }
        }
	}

	/**
	 * @param DBObject $oLink
	 */
	public function ModifyItem(DBObject $oLink)
	{
		assert($oLink instanceof $this->sClass);

		$iObjectId = $oLink->GetKey();
        if (array_key_exists($iObjectId, $this->aPreserved))
        {
            unset($this->aPreserved[$iObjectId]);
            $this->aModified[$iObjectId] = $oLink;
            $this->bHasDelta = true;
        }
	}

	/**
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	protected function LoadOriginalIds()
	{
		if ($this->aOriginalObjects === null)
		{
			if ($this->oOriginalSet)
			{
				$this->aOriginalObjects = $this->GetArrayOfIndex();
				$this->aPreserved = $this->aOriginalObjects; // Copy (not effective until aPreserved gets modified)
                foreach ($this->aRemoved as $iObjectId)
                {
                    if (array_key_exists($iObjectId, $this->aPreserved))
                    {
                        unset($this->aPreserved[$iObjectId]);
                    }
                }
                foreach ($this->aModified as $iObjectId => $oLink)
                {
                    if (array_key_exists($iObjectId, $this->aPreserved))
                    {
                        unset($this->aPreserved[$iObjectId]);
                    }
                }
			}
			else
			{

				// Nothing to load
				$this->aOriginalObjects = array();
				$this->aPreserved = array();
			}
		}
	}

	/**
	 * Note: After calling this method, the set cursor will be at the end of the set. You might want to rewind it.
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	protected function GetArrayOfIndex()
	{
		$aRet = array();
		$this->oOriginalSet->Rewind();
		$iRow = 0;
		while ($oObject = $this->oOriginalSet->Fetch())
		{
			$aRet[$oObject->GetKey()] = $iRow++;
		}
		return $aRet;
	}

    /**
     * @param bool $bWithId
     * @return array
     * @deprecated Since iTop 2.4, use foreach($this as $oItem){} instead
     */
    public function ToArray($bWithId = true)
    {
	    DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use foreach($this as $oItem){} instead');
	    $aRet = array();
	    foreach ($this as $oItem) {
		    if ($bWithId) {
			    $aRet[$oItem->GetKey()] = $oItem;
		    } else {
			    $aRet[] = $oItem;
            }
        }
        return $aRet;
    }

    /**
     * @param string $sAttCode
     * @param bool $bWithId
     * @return array
     */
    public function GetColumnAsArray($sAttCode, $bWithId = true)
    {
        $aRet = array();
        foreach($this as $oItem)
        {
            if ($bWithId)
            {
                $aRet[$oItem->GetKey()] = $oItem->Get($sAttCode);
            }
            else
            {
                $aRet[] = $oItem->Get($sAttCode);
            }
        }
        return $aRet;
    }

    /**
	 * The class of the objects of the collection (at least a common ancestor)
	 *
	 * @return string
	 */
	public function GetClass()
	{
		return $this->sClass;
	}

	/**
	 * The total number of objects in the collection
	 *
	 * @return int
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function Count(): int
	{
		$this->LoadOriginalIds();
		$iRet = count($this->aPreserved) + count($this->aAdded) + count($this->aModified);
		return $iRet;
	}

	/**
	 * Position the cursor to the given 0-based position
	 *
	 * @param int $iPosition
	 *
	 * @throws Exception
	 * @internal param int $iRow
	 */
	public function Seek($iPosition): void
	{
		$this->LoadOriginalIds();

		$iCount = $this->Count();
		if ($iPosition >= $iCount)
		{
			throw new Exception("Invalid position $iPosition: the link set is made of $iCount items.");
		}
		$this->rewind();
		for($iPos = 0 ; $iPos < $iPosition ; $iPos++)
		{
			$this->next();
		}
	}

	/**
	 * Fetch the object at the current position in the collection and move the cursor to the next position.
	 *
	 * @return DBObject|null The fetched object or null when at the end
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function Fetch()
	{
		$this->LoadOriginalIds();

		$ret = $this->current();
		if ($ret === false)
		{
			$ret = null;
		}
		$this->next();
		return $ret;
	}

	/**
	 * Return the current element
	 *
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	// Return type mixed is not supported by PHP 7.4, we can remove the following PHP attribute and add the return type once iTop min PHP version is PHP 8.0+
	#[\ReturnTypeWillChange]
	public function current()
	{
		$this->LoadOriginalIds();

		$iPreservedCount = count($this->aPreserved);
		if ($this->iCursor < $iPreservedCount)
		{
			$sId = key($this->aPreserved);
			$oRet = MetaModel::GetObject($this->sClass, $sId);
		}
		else
		{
		    $iModifiedCount = count($this->aModified);
		    if($this->iCursor < $iPreservedCount + $iModifiedCount)
            {
                $oRet = current($this->aModified);
            }
            else
            {
                $oRet = current($this->aAdded);
            }
		}
		return $oRet;
	}

	/**
	 * Move forward to next element
	 *
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function next(): void
	{
		$this->LoadOriginalIds();

		$iPreservedCount = count($this->aPreserved);
		if ($this->iCursor < $iPreservedCount)
		{
			next($this->aPreserved);
		}
		else
		{
		    $iModifiedCount = count($this->aModified);
		    if($this->iCursor < $iPreservedCount + $iModifiedCount)
            {
                next($this->aModified);
            }
            else
            {
                next($this->aAdded);
            }
		}
		// Increment AFTER moving the internal cursors because when starting aModified / aAdded, we must leave it intact
		$this->iCursor++;
	}

	/**
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 */
	// Return type mixed is not supported by PHP 7.4, we can remove the following PHP attribute and add the return type once iTop min PHP version is PHP 8.0+
	#[\ReturnTypeWillChange]
	public function key()
	{
		return $this->iCursor;
	}

	/**
	 * Checks if current position is valid
	 *
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function valid(): bool
	{
		$this->LoadOriginalIds();

		$iCount = $this->Count();
		$bRet = ($this->iCursor < $iCount);
		return $bRet;
	}

	/**
	 * Rewind the Iterator to the first element
	 *
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function rewind(): void
	{
	    $this->LoadOriginalIds();

	    $this->iCursor = 0;
		reset($this->aPreserved);
        reset($this->aAdded);
        reset($this->aModified);
	}

	/**
	 * @return bool
	 */
	public function HasDelta()
	{
		return $this->bHasDelta;
	}

	/**
	 * This method has been designed specifically for AttributeLinkedSet:Equals and as such it assumes that the passed argument is a clone of this.
	 *
	 * @param \ormLinkSet $oFellow
	 *
	 * @return bool|null
	 * @throws Exception
	 */
	public function Equals(ormLinkSet $oFellow)
	{
		$bRet = null;
		if ($this === $oFellow)
		{
			$bRet = true;
		}
		else
		{
			if ( ($this->oOriginalSet !== $oFellow->oOriginalSet)
			&& ($this->oOriginalSet->GetFilter()->ToOQL() != $oFellow->oOriginalSet->GetFilter()->ToOQL()) )
			{
				throw new Exception('ormLinkSet::Equals assumes that compared link sets have the same original scope');
			}
			if ($this->HasDelta())
			{
				throw new Exception('ormLinkSet::Equals assumes that left link set had no delta');
			}
			$bRet = !$oFellow->HasDelta();
		}
		return $bRet;
	}

	/**
	 * @param \iDBObjectSetIterator $oFellow
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function UpdateFromCompleteList(iDBObjectSetIterator $oFellow)
	{
		if ($oFellow === $this)
		{
			throw new Exception('ormLinkSet::UpdateFromCompleteList assumes that the passed link set is at least a clone of the current one');
		}
		$bUpdateFromDelta = false;
		if ($oFellow instanceof ormLinkSet)
		{
			if ( ($this->oOriginalSet === $oFellow->oOriginalSet)
				|| ($this->oOriginalSet->GetFilter()->ToOQL() == $oFellow->oOriginalSet->GetFilter()->ToOQL()) )
			{
				$bUpdateFromDelta = true;
			}
		}

		if ($bUpdateFromDelta)
		{
			// Same original set -> simply update the delta
			$this->iCursor = 0;
			$this->aAdded = $oFellow->aAdded;
			$this->aRemoved = $oFellow->aRemoved;
			$this->aModified = $oFellow->aModified;
			$this->aPreserved = $oFellow->aPreserved;
			$this->bHasDelta = $oFellow->bHasDelta;
		}
		else
		{
			// For backward compatibility reasons, let's rebuild a delta...

			// Reset the delta
			$this->iCursor = 0;
			$this->aAdded = array();
			$this->aRemoved = array();
			$this->aModified = array();
			$this->aPreserved = ($this->aOriginalObjects === null) ? array() : $this->aOriginalObjects;
			$this->bHasDelta = false;

			/** @var \AttributeLinkedSet|\AttributeLinkedSetIndirect $oAttDef */
			$oAttDef = MetaModel::GetAttributeDef($this->sHostClass, $this->sAttCode);
			$sExtKeyToMe = $oAttDef->GetExtKeyToMe();
			$sAdditionalKey = null;
			if ($oAttDef->IsIndirect() && !$oAttDef->DuplicatesAllowed())
			{
				$sAdditionalKey = $oAttDef->GetExtKeyToRemote();
			}
			// Compare both collections by iterating the whole sets, order them, a build a fingerprint based on meaningful data (what make the difference)
			/** @var \DBObject $oLink */
			$oComparator = new DBObjectSetComparator($this, $oFellow, array($sExtKeyToMe), $sAdditionalKey);
			$aChanges = $oComparator->GetDifferences();
			foreach ($aChanges['added'] as $oLink)
			{
				$this->AddItem($oLink);
			}

			foreach ($aChanges['modified'] as $oLink)
			{
				$this->ModifyItem($oLink);
			}

			foreach ($aChanges['removed'] as $oLink)
			{
				$this->RemoveItem($oLink->GetKey());
			}
		}
	}

	/**
	 * Get the list of all modified (added, modified and removed) links
	 *
	 * @return array of link objects
	 * @throws \Exception
	 */
	public function ListModifiedLinks()
	{
		$aAdded = $this->aAdded;
		$aModified = $this->aModified;
		$aRemoved = array();
		if (count($this->aRemoved) > 0)
		{
			$oSearch = new DBObjectSearch($this->sClass);
			$oSearch->AddCondition('id', $this->aRemoved, 'IN');
			$oSet = new DBObjectSet($oSearch);
			$aRemoved = $oSet->ToArray();
		}
		return array_merge($aAdded, $aModified, $aRemoved);
	}

	/**
	 * @param DBObject $oHostObject
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DBWrite(DBObject $oHostObject)
	{
		/** @var \AttributeLinkedSet|\AttributeLinkedSetIndirect $oAttDef */
		$oAttDef = MetaModel::GetAttributeDef(get_class($oHostObject), $this->sAttCode);
		$sExtKeyToMe = $oAttDef->GetExtKeyToMe();
		$sExtKeyToRemote = $oAttDef->IsIndirect() ? $oAttDef->GetExtKeyToRemote() : 'n/a';

		$aCheckLinks = array();
		$aCheckRemote = array();
		foreach ($this->aAdded as $oLink)
		{
			if ($oLink->IsNew())
			{
				if ($oAttDef->IsIndirect() && !$oAttDef->DuplicatesAllowed())
				{
					//todo: faire un test qui passe dans cette branche !
					$aCheckRemote[] = $oLink->Get($sExtKeyToRemote);
				}
			}
			else
			{
				//todo: faire un test qui passe dans cette branche !
				$aCheckLinks[] = $oLink->GetKey();
			}
		}
		foreach ($this->aRemoved as $iLinkId)
		{
			$aCheckLinks[] = $iLinkId;
		}
		foreach ($this->aModified as $iLinkId => $oLink)
		{
			$aCheckLinks[] = $oLink->GetKey();
		}

		// Critical section : serialize any write access to these links
		//
		$oMtx = new iTopMutex('Write-'.$this->sClass);
		$oMtx->Lock();

		// Check for the existing links
		//
		/** @var DBObject[] $aExistingLinks */
		$aExistingLinks = array();
		/** @var Int[] $aExistingRemote */
		$aExistingRemote = array();
		if (count($aCheckLinks) > 0)
		{
			$oSearch = new DBObjectSearch($this->sClass);
			$oSearch->AddCondition('id', $aCheckLinks, 'IN');
			$oSet = new DBObjectSet($oSearch);
			$aExistingLinks = $oSet->ToArray();
		}

		// Check for the existing remote objects
		//
		if (count($aCheckRemote) > 0)
		{
			$oSearch = new DBObjectSearch($this->sClass);
			$oSearch->AddCondition($sExtKeyToMe, $oHostObject->GetKey(), '=');
			$oSearch->AddCondition($sExtKeyToRemote, $aCheckRemote, 'IN');
			$oSet = new DBObjectSet($oSearch);
			$aExistingRemote = $oSet->GetColumnAsArray($sExtKeyToRemote, true);
		}

		// Write the links according to the existing links
		//
		foreach ($this->aAdded as $oLink)
		{
			// Make sure that the objects in the set point to "this"
			$oLink->Set($sExtKeyToMe, $oHostObject->GetKey());

			if ($oLink->IsNew())
			{
				if (count($aCheckRemote) > 0)
				{
				    $bIsDuplicate = false;
				    foreach($aExistingRemote as $sLinkKey => $sExtKey)
                    {
                        if ($sExtKey == $oLink->Get($sExtKeyToRemote))
                        {
                            // Do not create a duplicate
                            // + In the case of a remove action followed by an add action
                            // of an existing link,
                            // the final state to consider is add action,
                            // so suppress the entry in the removed list.
                            if (array_key_exists($sLinkKey, $this->aRemoved))
                            {
                                unset($this->aRemoved[$sLinkKey]);
                            }
	                        $bIsDuplicate = true;
	                        break;
                        }
                    }
					if ($bIsDuplicate) {
						continue;
					}
				}

			} else {
				if (!array_key_exists($oLink->GetKey(), $aExistingLinks)) {
					$oLink->DBClone();
				}
			}
			$oLink->SetLinkHostObject($oHostObject);
			$oLink->DBWrite();

			$this->aPreserved[$oLink->GetKey()] = $oLink;
			$this->aOriginalObjects[$oLink->GetKey()] = $oLink;
		}
		$this->aAdded = [];

		foreach ($this->aRemoved as $iLinkId) {
			if (array_key_exists($iLinkId, $aExistingLinks)) {
				$oLink = $aExistingLinks[$iLinkId];
				if ($oAttDef->IsIndirect()) {
					$oLink->DBDelete();
				} else {
					$oExtKeyToRemote = MetaModel::GetAttributeDef($this->sClass, $sExtKeyToMe);
					if ($oExtKeyToRemote->IsNullAllowed()) {
						if ($oLink->Get($sExtKeyToMe) == $oHostObject->GetKey()) {
							// Detach the link object from this
							$oLink->Set($sExtKeyToMe, 0);
							$oLink->DBUpdate();
						}
					} else {
						$oLink->DBDelete();
					}
				}
				unset($this->aPreserved[$oLink->GetKey()], $this->aOriginalObjects[$oLink->GetKey()]);
			}
		}
		$this->aRemoved = [];

		// Note: process modifications at the end: if a link to remove has also been listed as modified, then it will be gracefully ignored
		foreach ($this->aModified as $iLinkId => $oLink) {
			if (array_key_exists($oLink->GetKey(), $aExistingLinks)) {
				$oLink->DBUpdate();
			} else {
				$oLink->DBClone();
			}
			$this->aPreserved[$oLink->GetKey()] = $oLink;
			$this->aOriginalObjects[$oLink->GetKey()] = $oLink;
		}
		$this->aModified = [];

		// End of the critical section
		//
		$oMtx->Unlock();

		// we updated the instance (original/preserved/added/modified/removed arrays) all along the way
		$this->bHasDelta = false;
		$this->oOriginalSet->GetFilter()->SetInternalParams(['id', $oHostObject->GetKey()]);
	}

	/**
	 * @param bool $bShowObsolete
	 *
	 * @return \DBObjectSet indirect relations will get `SELECT L,R ...` (l = lnk class, R = remote)
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \Exception
	 *
	 * @since 3.0.0 N°2334 returns both lnk and remote classes for indirect relations
	 */
	public function ToDBObjectSet($bShowObsolete = true)
	{
		/** @var \AttributeLinkedSet|\AttributeLinkedSetIndirect $oAttDef */
		$oAttDef = MetaModel::GetAttributeDef($this->sHostClass, $this->sAttCode);
		$oLinkSearch = $this->GetFilter();
		if ($oAttDef->IsIndirect())
		{
			$oLinkSearch->RenameAlias($oLinkSearch->GetClassAlias(), self::LINK_ALIAS);
			$sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
			/** @var \AttributeExternalKey $oLinkingAttDef */
			$oLinkingAttDef = MetaModel::GetAttributeDef($this->sClass, $sExtKeyToRemote);

			// N°2334 add pointed class (SELECT L,R) to have all fields (lnk + remote) in display
			// the pointed class is always present in the search, as generated by \AttributeLinkedSet::GetDefaultValue
			$sTargetClass = $oLinkingAttDef->GetTargetClass();
			$oRemoteClassSearch = new DBObjectSearch($sTargetClass, self::REMOTE_ALIAS);

			if (!$bShowObsolete && MetaModel::IsObsoletable($sTargetClass))
			{
				$oNotObsolete = new BinaryExpression(
					new FieldExpression('obsolescence_flag', self::REMOTE_ALIAS),
					'=',
					new ScalarExpression(0)
				);
				$oRemoteClassSearch->AddConditionExpression($oNotObsolete);
			}

			if (!utils::IsArchiveMode() && MetaModel::IsArchivable($sTargetClass))
			{
				$oNotArchived = new BinaryExpression(
					new FieldExpression('archive_flag', self::REMOTE_ALIAS),
					'=',
					new ScalarExpression(0)
				);

				$oRemoteClassSearch->AddConditionExpression($oNotArchived);
			}

			$aReAliasingMap = [];
			$oLinkSearch->AddCondition_PointingTo($oRemoteClassSearch, $sExtKeyToRemote, TREE_OPERATOR_EQUALS, $aReAliasingMap);
			if (array_key_exists(self::REMOTE_ALIAS, $aReAliasingMap)) {
				// If 'Remote' alias has been renamed, change it back.
				if ($aReAliasingMap[self::REMOTE_ALIAS][0] != self::REMOTE_ALIAS) {
					$oLinkSearch->RenameAlias($aReAliasingMap[self::REMOTE_ALIAS][0], self::REMOTE_ALIAS);
				}
			}
			$oLinkSearch->SetSelectedClasses([self::LINK_ALIAS, self::REMOTE_ALIAS]);
		}
		if (count($this->aRemoved) !== 0) {
			$sConditionExpr = '`'.self::LINK_ALIAS.'`.id NOT IN ('.implode(',', $this->aRemoved).')';
			$oRemovedExpression = Expression::FromOQL($sConditionExpr);
			$oLinkSearch->AddConditionExpression($oRemovedExpression);
		}
		$oLinkSet = new DBObjectSet($oLinkSearch);
		$oLinkSet->SetShowObsoleteData($bShowObsolete);
		if ($this->HasDelta()) {
			$oLinkSet->AddObjectArray($this->aAdded);
		}

		return $oLinkSet;
	}

	/**
	 * GetValues.
	 *
	 * @return array of tag codes
	 */
	public function GetValues()
	{
		$aValues = array();
		foreach ($this->aPreserved as $sTagCode => $oTag) {
			$aValues[] = $sTagCode;
		}
		foreach ($this->aAdded as $sTagCode => $oTag) {
			$aValues[] = $sTagCode;
		}

		sort($aValues);

		return $aValues;
	}

	/**
	 * @return \DBObjectSet|null
	 */
	public function GetOriginalSet(): ?DBObjectSet
	{
		return $this->oOriginalSet;
	}
}
