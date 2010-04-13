<?php

/**
 * A set of persistent objects, could be heterogeneous 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

class DBObjectSet
{
	private $m_oFilter;
	private $m_aOrderBy;
	public $m_bLoaded;
	private $m_aData;
	private $m_aId2Row;
	private $m_iCurrRow;

	public function __construct(DBObjectSearch $oFilter, $aOrderBy = array(), $aArgs = array())
	{
		$this->m_oFilter = $oFilter;
		$this->m_aOrderBy = $aOrderBy;
		$this->m_aArgs = $aArgs;

		$this->m_bLoaded = false;
		$this->m_aData = array(); // array of (row => array of (classalias) => object)
		$this->m_aId2Row = array();
		$this->m_iCurrRow = 0;
	}

	public function __destruct()
	{
	}

	public function __toString()
	{
		$sRet = '';
		$this->Rewind();
		$sRet .= "Set (".$this->m_oFilter->ToSibuSQL().")<br/>\n";
		$sRet .= "Query: <pre style=\"font-size: smaller; display:inline;\">".MetaModel::MakeSelectQuery($this->m_oFilter, array()).")</pre>\n";
		
		$sRet .= $this->Count()." records<br/>\n";
		if ($this->Count() > 0)
		{
			$sRet .= "<ul class=\"treeview\">\n";
			while ($oObj = $this->Fetch())
			{
				$sRet .= "<li>".$oObj->__toString()."</li>\n";
			}
			$sRet .= "</ul>\n";
		}
		return $sRet;
	}

	static public function FromObject($oObject)
	{
		$oRetSet = self::FromScratch(get_class($oObject));
		$oRetSet->AddObject($oObject);
		return $oRetSet;
	}

	static public function FromScratch($sClass)
	{
		$oFilter = new CMDBSearchFilter($sClass);
		$oRetSet = new self($oFilter);
		$oRetSet->m_bLoaded = true; // no DB load
		return $oRetSet;
	} 

	static public function FromArray($sClass, $aObjects)
	{
		$oFilter = new CMDBSearchFilter($sClass);
		$oRetSet = new self($oFilter);
		$oRetSet->m_bLoaded = true; // no DB load
		$oRetSet->AddObjectArray($aObjects);
		return $oRetSet;
	} 

	static public function FromLinkSet($oObject, $sLinkSetAttCode, $sExtKeyToRemote)
	{
		$oLinkAttCode = MetaModel::GetAttributeDef(get_class($oObject), $sLinkSetAttCode);
		$oExtKeyAttDef = MetaModel::GetAttributeDef($oLinkAttCode->GetLinkedClass(), $sExtKeyToRemote);
		$sTargetClass = $oExtKeyAttDef->GetTargetClass();

		$oLinkSet = $oObject->Get($sLinkSetAttCode);
		$aTargets = array();
		while ($oLink = $oLinkSet->Fetch())
		{
			$aTargets[] = MetaModel::GetObject($sTargetClass, $oLink->Get($sExtKeyToRemote));
		}

		return self::FromArray($sTargetClass, $aTargets);
	} 

	public function ToArray($bWithId = true)
	{
		$aRet = array();
		$this->Rewind();
		while ($oObject = $this->Fetch())
		{
			if ($bWithId)
			{
				$aRet[$oObject->GetKey()] = $oObject;
			}
			else
			{
				$aRet[] = $oObject;
			}
		}
		return $aRet;
	} 

	public function GetColumnAsArray($sAttCode, $bWithId = true)
	{
		$aRet = array();
		$this->Rewind();
		while ($oObject = $this->Fetch())
		{
			if ($bWithId)
			{
				$aRet[$oObject->GetKey()] = $oObject->Get($sAttCode);
			}
			else
			{
				$aRet[] = $oObject->Get($sAttCode);
			}
		}
		return $aRet;
	}

	public function GetFilter()
	{
		return $this->m_oFilter;
	}

	public function GetClass()
	{
		return $this->m_oFilter->GetClass();
	}

	public function GetSelectedClasses()
	{
		return $this->m_oFilter->GetSelectedClasses();
	}

	public function GetRootClass()
	{
		return MetaModel::GetRootClass($this->GetClass());
	}

	public function Load()
	{
		if ($this->m_bLoaded) return;

		$sSQL = MetaModel::MakeSelectQuery($this->m_oFilter, $this->m_aOrderBy, $this->m_aArgs);
		$resQuery = CMDBSource::Query($sSQL);
		if (!$resQuery) return;

		$sClass = $this->m_oFilter->GetClass();
		while ($aRow = CMDBSource::FetchArray($resQuery))
		{
			$aObjects = array();
			foreach ($this->m_oFilter->GetSelectedClasses() as $sClassAlias => $sClass)
			{
				$oObject = MetaModel::GetObjectByRow($sClass, $aRow, $sClassAlias);
				$aObjects[$sClassAlias] = $oObject;
			}
			$this->AddObjectExtended($aObjects);
		}
		CMDBSource::FreeResult($resQuery);

		$this->m_bLoaded = true;
	}

	public function Count()
	{
		if (!$this->m_bLoaded) $this->Load();
		return count($this->m_aData);
	}

	public function Fetch($sClassAlias = '')
	{
		if (!$this->m_bLoaded) $this->Load();

		if ($this->m_iCurrRow >= count($this->m_aData))
		{
			return null;
		}
	
		if (strlen($sClassAlias) == 0)
		{
			$sClassAlias = $this->m_oFilter->GetClassAlias();
		}
		$oRetObj = $this->m_aData[$this->m_iCurrRow][$sClassAlias];
		$this->m_iCurrRow++;
		return $oRetObj;
	}

	// Return the whole line if several classes have been specified in the query
	//
	public function FetchAssoc()
	{
		if (!$this->m_bLoaded) $this->Load();

		if ($this->m_iCurrRow >= count($this->m_aData))
		{
			return null;
		}
	
		$aRetObjects = $this->m_aData[$this->m_iCurrRow];
		$this->m_iCurrRow++;
		return $aRetObjects;
	}

	public function Rewind()
	{
		$this->Seek(0);
	}

	public function Seek($iRow)
	{
		if (!$this->m_bLoaded) $this->Load();

		$this->m_iCurrRow = min($iRow, count($this->m_aData));
		return $this->m_iCurrRow;
	}

	public function AddObject($oObject, $sClassAlias = '')
	{
		$sObjClass = get_class($oObject);
		if (strlen($sClassAlias) == 0)
		{
			$sClassAlias = $sObjClass;
		}

		$iNextPos = count($this->m_aData);
		$this->m_aData[$iNextPos][$sClassAlias] = $oObject;
		$this->m_aId2Row[$sObjClass][$oObject->GetKey()] = $iNextPos;
	}

	protected function AddObjectExtended($aObjectArray)
	{
		$iNextPos = count($this->m_aData);

		foreach ($aObjectArray as $sClassAlias => $oObject)
		{
			$this->m_aData[$iNextPos][$sClassAlias] = $oObject;
			$this->m_aId2Row[get_class($oObject)][$oObject->GetKey()] = $iNextPos;
		}
	}

	public function AddObjectArray($aObjects, $sClassAlias = '')
	{
		// #@# todo - add a check on the object class ?
		foreach ($aObjects as $oObj)
		{
			$this->AddObject($oObj, $sClassAlias);
		}
	}

	public function Merge($oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			throw new CoreException("Could not merge two objects sets if they don't have the same root class");
		}
		if (!$this->m_bLoaded) $this->Load();

		$oObjectSet->Seek(0);
		while ($oObject = $oObjectSet->Fetch())
		{
			$this->AddObject($oObject);
		}
	}

	public function CreateIntersect($oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			throw new CoreException("Could not 'intersect' two objects sets if they don't have the same root class");
		}
		if (!$this->m_bLoaded) $this->Load();

		$oNewSet = DBObjectSet::FromScratch($this->GetClass());

		$oObjectSet->Seek(0);
		while ($oObject = $oObjectSet->Fetch())
		{
			if (array_key_exists($oObject->GetKey(), $this->m_aId2Row))
			{
				$oNewSet->AddObject($oObject);
			}
		}
		return $oNewSet;
	}

	public function CreateDelta($oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			throw new CoreException("Could not 'delta' two objects sets if they don't have the same root class");
		}
		if (!$this->m_bLoaded) $this->Load();

		$oNewSet = DBObjectSet::FromScratch($this->GetClass());

		$oObjectSet->Seek(0);
		while ($oObject = $oObjectSet->Fetch())
		{
			if (!array_key_exists($oObject->GetKey(), $this->m_aId2Row))
			{
				$oNewSet->AddObject($oObject);
			}
		}
		return $oNewSet;
	}

	public function GetRelatedObjects($sRelCode, $iMaxDepth = 99)
	{
		$aRelatedObjs = array();

		$aVisited = array(); // optimization for consecutive calls of MetaModel::GetRelatedObjects
		$this->Seek(0);
		while ($oObject = $this->Fetch())
		{
			// #@# todo - actually merge !
			$aRelatedObjs = array_merge_recursive($aRelatedObjs, $oObject->GetRelatedObjects($sRelCode, $iMaxDepth, $aVisited));
		}
		return $aRelatedObjs;
	}
}

?>
