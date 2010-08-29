<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Class cmdbObject
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


/**
 * cmdbObjectClass
 * the file to include, then the core is yours
 *
 * @package     iTopORM
 */

require_once('coreexception.class.inc.php');

require_once('config.class.inc.php');
require_once('log.class.inc.php');
require_once('duration.class.inc.php');

require_once('dict.class.inc.php');

require_once('attributedef.class.inc.php');
require_once('filterdef.class.inc.php');
require_once('stimulus.class.inc.php');
require_once('valuesetdef.class.inc.php');
require_once('MyHelpers.class.inc.php');

require_once('expression.class.inc.php');

require_once('cmdbsource.class.inc.php');
require_once('sqlquery.class.inc.php');
require_once('oql/oqlquery.class.inc.php');
require_once('oql/oqlexception.class.inc.php');
require_once('oql/oql-parser.php');
require_once('oql/oql-lexer.php');
require_once('oql/oqlinterpreter.class.inc.php');

require_once('dbobject.class.php');
require_once('dbobjectsearch.class.php');
require_once('dbobjectset.class.php');

// db change tracking data model
require_once('cmdbchange.class.inc.php');
require_once('cmdbchangeop.class.inc.php');

// customization data model
// Romain: temporary moved into application.inc.php (see explanations there)
//require_once('trigger.class.inc.php');
//require_once('action.class.inc.php');

// application log
// Romain: temporary moved into application.inc.php (see explanations there)
//require_once('event.class.inc.php');

require_once('csvparser.class.inc.php');
require_once('bulkchange.class.inc.php');


//
// Error handling
// To be finalized... or removed ?
//
function cmdbErrorHandler($errno, $errstr, $errfile, $errline)
{
//		font-family: Courier-New, Courier, Arial, Helevtica;
	$sErrorStyle = "
		background-color: #ffaaaa;
		color: #000000;
		border: 1px dashed #000000;
		padding: 0.25em;
		margin-top: 1em;
	";
	$sCallStackStyle = "
		font-size: smaller;
		background-color: #ffcccc;
		color: #000000;
		border: 1px dashed #000000;
		padding: 0.25em;
		margin-top: 1em;
	";

	switch ($errno)
	{
	case E_USER_ERROR:
	case E_ERROR:
		echo "<div style=\"$sErrorStyle\">\n";
		echo "<b>Error</b> [$errno] $errstr<br />\n";
		echo "<div style=\"$sCallStackStyle\">\n";
		MyHelpers::dump_callstack(1);
		echo "</div>\n";
		echo "Hereafter the biz model internals:<br />\n";
		echo "<pre>\n";
		MetaModel::static_var_dump();
		echo "</pre>\n";
		echo "Aborting...<br />\n";
		echo "</div>\n";
		exit(1);
		break;
	case E_USER_WARNING:
	case E_WARNING:
		echo "<div style=\"background-color:#FAA;\">\n";
		echo "<b>Warning</b> [$errno] $errstr<br />\n";
		echo "<div style=\"background-color:#FCC;\">\n";
		MyHelpers::dump_callstack(1);
		echo "</div>\n";
		echo "</div>\n";
		break;
	case E_USER_NOTICE:
	case E_NOTICE:
		echo "<div style=\"background-color:#FAA;\">\n";
		echo "<b>Notice</b> [$errno] $errstr<br />\n";
		echo "<div style=\"background-color:#FCC;\">\n";
		MyHelpers::dump_callstack(1);
		echo "</div>\n";
		echo "</div>\n";
		break;
	default:
		echo "Unknown error type: [$errno] $errstr<br />\n";
		MyHelpers::dump_callstack(1);
		break;
	}
}

error_reporting(E_ALL | E_STRICT);
//set_error_handler("cmdbErrorHandler");



//
//
//


/**
 * A persistent object, which changes are accurately recorded
 *
 * @package     iTopORM
 */
abstract class CMDBObject extends DBObject
{
	protected $m_datCreated;
	protected $m_datUpdated;
	protected static $m_oCurrChange = null;


	private function RecordObjCreation(CMDBChange $oChange)
	{
		$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpCreate");
		$oMyChangeOp->Set("change", $oChange->GetKey());
		$oMyChangeOp->Set("objclass", get_class($this));
		$oMyChangeOp->Set("objkey", $this->GetKey());
		$iId = $oMyChangeOp->DBInsertNoReload();
	}
	private function RecordObjDeletion(CMDBChange $oChange, $objkey)
	{
		$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpDelete");
		$oMyChangeOp->Set("change", $oChange->GetKey());
		$oMyChangeOp->Set("objclass", get_class($this));
		$oMyChangeOp->Set("objkey", $objkey);
		$iId = $oMyChangeOp->DBInsertNoReload();
	}
	private function RecordAttChanges(CMDBChange $oChange, array $aValues, array $aOrigValues)
	{
		// $aValues is an array of $sAttCode => $value
		//
		foreach ($aValues as $sAttCode=> $value)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			if ($oAttDef->IsLinkSet()) continue; // #@# temporary

			if ($oAttDef instanceOf AttributeOneWayPassword)
			{
				// One Way encrypted passwords' history is stored -one way- encrypted
				$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeOneWayPassword");
				$oMyChangeOp->Set("change", $oChange->GetKey());
				$oMyChangeOp->Set("objclass", get_class($this));
				$oMyChangeOp->Set("objkey", $this->GetKey());
				$oMyChangeOp->Set("attcode", $sAttCode);

				if (array_key_exists($sAttCode, $aOrigValues))
				{
					$original = $aOrigValues[$sAttCode];
				}
				else
				{
					$original = '';
				}
				$oMyChangeOp->Set("prev_pwd", $original);
				$iId = $oMyChangeOp->DBInsertNoReload();
			}
			elseif ($oAttDef instanceOf AttributeEncryptedString)
			{
				// Encrypted string history is stored encrypted
				$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeEncrypted");
				$oMyChangeOp->Set("change", $oChange->GetKey());
				$oMyChangeOp->Set("objclass", get_class($this));
				$oMyChangeOp->Set("objkey", $this->GetKey());
				$oMyChangeOp->Set("attcode", $sAttCode);

				if (array_key_exists($sAttCode, $aOrigValues))
				{
					$original = $aOrigValues[$sAttCode];
				}
				else
				{
					$original = '';
				}
				$oMyChangeOp->Set("prevdata", $original);
				$iId = $oMyChangeOp->DBInsertNoReload();
			}
			elseif ($oAttDef instanceOf AttributeBlob)
			{
				// Data blobs
				$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeBlob");
				$oMyChangeOp->Set("change", $oChange->GetKey());
				$oMyChangeOp->Set("objclass", get_class($this));
				$oMyChangeOp->Set("objkey", $this->GetKey());
				$oMyChangeOp->Set("attcode", $sAttCode);

				if (array_key_exists($sAttCode, $aOrigValues))
				{
					$original = $aOrigValues[$sAttCode];
				}
				else
				{
					$original = new ormDocument();
				}
				$oMyChangeOp->Set("prevdata", $original);
				$iId = $oMyChangeOp->DBInsertNoReload();
			}
			elseif ($oAttDef instanceOf AttributeText)
			{
				// Data blobs
				$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeText");
				$oMyChangeOp->Set("change", $oChange->GetKey());
				$oMyChangeOp->Set("objclass", get_class($this));
				$oMyChangeOp->Set("objkey", $this->GetKey());
				$oMyChangeOp->Set("attcode", $sAttCode);

				if (array_key_exists($sAttCode, $aOrigValues))
				{
					$original = $aOrigValues[$sAttCode];
				}
				else
				{
					$original = null;
				}
				$oMyChangeOp->Set("prevdata", $original);
				$iId = $oMyChangeOp->DBInsertNoReload();
			}
			else
			{
				// Scalars
				//
				$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeScalar");
				$oMyChangeOp->Set("change", $oChange->GetKey());
				$oMyChangeOp->Set("objclass", get_class($this));
				$oMyChangeOp->Set("objkey", $this->GetKey());
				$oMyChangeOp->Set("attcode", $sAttCode);

				if (array_key_exists($sAttCode, $aOrigValues))
				{
					$sOriginalValue = $aOrigValues[$sAttCode];
				}
				else
				{
					$sOriginalValue = 'undefined';
				}
				$oMyChangeOp->Set("oldvalue", $sOriginalValue);
				$oMyChangeOp->Set("newvalue", $value);
				$iId = $oMyChangeOp->DBInsertNoReload();
			}
		}
	}

	public function DBInsert()
	{
		if(!is_object(self::$m_oCurrChange))
		{
			throw new CoreException("DBInsert() could not be used here, please use DBInsertTracked() instead");
		}
		return $this->DBInsertTracked_Internal();
	}

	public function DBInsertTracked(CMDBChange $oChange)
	{
		self::$m_oCurrChange = $oChange;
		$ret = $this->DBInsertTracked_Internal();
		self::$m_oCurrChange = null;
		return $ret;
	}

	public function DBInsertTrackedNoReload(CMDBChange $oChange)
	{
		self::$m_oCurrChange = $oChange;
		$ret = $this->DBInsertTracked_Internal(true);
		self::$m_oCurrChange = null;
		return $ret;
	}

	protected function DBInsertTracked_Internal($bDoNotReload = false)
	{
		if ($bDoNotReload)
		{
			$ret = parent::DBInsertNoReload();
		}
		else
		{
			$ret = parent::DBInsert();
		}
		$this->RecordObjCreation(self::$m_oCurrChange);
		return $ret;
	}

	public function DBClone($newKey = null)
	{
		if(!self::$m_oCurrChange)
		{
			throw new CoreException("DBClone() could not be used here, please use DBCloneTracked() instead");
		}
		return $this->DBCloneTracked_Internal();
	}

	public function DBCloneTracked(CMDBChange $oChange, $newKey = null)
	{
		self::$m_oCurrChange = $oChange;
		$this->DBCloneTracked_Internal($newKey);
		self::$m_oCurrChange = null;
	}

	protected function DBCloneTracked_Internal($newKey = null)
	{
		$newKey = parent::DBClone($newKey);
		$oClone = MetaModel::GetObject(get_class($this), $newKey); 

		$oClone->RecordObjCreation(self::$m_oCurrChange);
		return $newKey;
	}

	public function DBUpdate()
	{
		if(!self::$m_oCurrChange)
		{
			throw new CoreException("DBUpdate() could not be used here, please use DBUpdateTracked() instead");
		}
		return $this->DBUpdateTracked_internal();
	}

	public function DBUpdateTracked(CMDBChange $oChange)
	{
		self::$m_oCurrChange = $oChange;
		$this->DBUpdateTracked_Internal();
		self::$m_oCurrChange = null;
	}

	protected function DBUpdateTracked_Internal()
	{
		// Copy the changes list before the update (the list should be reset afterwards)
		$aChanges = $this->ListChanges();
		if (count($aChanges) == 0)
		{
			//throw new CoreWarning("Attempting to update an unchanged object");
			return;
		}
		
		// Save the original values (will be reset to the new values when the object get written to the DB)
		$aOriginalValues = $this->m_aOrigValues;
		$ret = parent::DBUpdate();
		$this->RecordAttChanges(self::$m_oCurrChange, $aChanges, $aOriginalValues);
		return $ret;
	}

	public function DBDelete()
	{
		if(!self::$m_oCurrChange)
		{
			throw new CoreException("DBDelete() could not be used here, please use DBDeleteTracked() instead");
		}
		return $this->DBDeleteTracked_Internal();
	}

	public function DBDeleteTracked(CMDBChange $oChange)
	{
		self::$m_oCurrChange = $oChange;
		$this->DBDeleteTracked_Internal();
		self::$m_oCurrChange = null;
	}

	protected function DBDeleteTracked_Internal()
	{
		$prevkey = $this->GetKey();
		$ret = parent::DBDelete();
		$this->RecordObjDeletion(self::$m_oCurrChange, $prevkey);
		return $ret;
	}

	public static function BulkDelete(DBObjectSearch $oFilter)
	{
		if(!self::$m_oCurrChange)
		{
			throw new CoreException("BulkDelete() could not be used here, please use BulkDeleteTracked() instead");
		}
		return $this->BulkDeleteTracked_Internal($oFilter);
	}

	public static function BulkDeleteTracked(CMDBChange $oChange, DBObjectSearch $oFilter)
	{
		self::$m_oCurrChange = $oChange;
		$this->BulkDeleteTracked_Internal($oFilter);
		self::$m_oCurrChange = null;
	}

	protected static function BulkDeleteTracked_Internal(DBObjectSearch $oFilter)
	{
		throw new CoreWarning("Change tracking not tested for bulk operations");

		// Get the list of objects to delete (and record data before deleting the DB records)
		$oObjSet = new CMDBObjectSet($oFilter);
		$aObjAndKeys = array(); // array of id=>object
		while ($oItem = $oObjSet->Fetch())
		{
			$aObjAndKeys[$oItem->GetKey()] = $oItem;
		}
		$oObjSet->FreeResult();

		// Delete in one single efficient query
		$ret = parent::BulkDelete($oFilter);
		// Record... in many queries !!!
		foreach($aObjAndKeys as $prevkey=>$oItem)
		{
			$oItem->RecordObjDeletion(self::$m_oCurrChange, $prevkey);
		}
		return $ret;
	}

	public static function BulkUpdate(DBObjectSearch $oFilter, array $aValues)
	{
		if(!self::$m_oCurrChange)
		{
			throw new CoreException("BulkUpdate() could not be used here, please use BulkUpdateTracked() instead");
		}
		return $this->BulkUpdateTracked_Internal($oFilter, $aValues);
	}

	public static function BulkUpdateTracked(CMDBChange $oChange, DBObjectSearch $oFilter, array $aValues)
	{
		self::$m_oCurrChange = $oChange;
		$this->BulkUpdateTracked_Internal($oFilter, $aValues);
		self::$m_oCurrChange = null;
	}

	protected static function BulkUpdateTracked_Internal(DBObjectSearch $oFilter, array $aValues)
	{
		// $aValues is an array of $sAttCode => $value

		// Get the list of objects to update (and load it before doing the change)
		$oObjSet = new CMDBObjectSet($oFilter);
		$oObjSet->Load();

		// Keep track of the previous values (will be overwritten when the objects are synchronized with the DB)
		$aOriginalValues = array();
		$oObjSet->Rewind();
		while ($oItem = $oObjSet->Fetch())
		{
			$aOriginalValues[$oItem->GetKey()] = $oItem->m_aOrigValues;
		}

		// Update in one single efficient query
		$ret = parent::BulkUpdate($oFilter, $aValues);

		// Record... in many queries !!!
		$oObjSet->Rewind();
		while ($oItem = $oObjSet->Fetch())
		{
			$aChangedValues = $oItem->ListChangedValues($aValues);
			$oItem->RecordAttChanges(self::$m_oCurrChange, $aChangedValues, $aOriginalValues[$oItem->GetKey()]);
		}
		return $ret;
	}
}



/**
 * TODO: investigate how to get rid of this class that was made to workaround some language limitation... or a poor design!
 *
 * @package     iTopORM
 */
class CMDBObjectSet extends DBObjectSet
{
	// this is the public interface (?)
	
	// I have to define those constructors here... :-(
	// just to get the right object class in return.
	// I have to think again to those things: maybe it will work fine if a have a constructor define here (?)
	
	static public function FromScratch($sClass)
	{
		$oFilter = new CMDBSearchFilter($sClass);
		$oRetSet = new CMDBObjectSet($oFilter); // THE ONLY DIFF IS HERE
		// NOTE: THIS DOES NOT WORK IF m_bLoaded is private...
		// BUT IT THAT CASE YOU DO NOT GET ANY ERROR !!!!!
		$oRetSet->m_bLoaded = true; // no DB load
		return $oRetSet;
	} 

	static public function FromArray($sClass, $aObjects)
	{
		$oFilter = new CMDBSearchFilter($sClass);
		$oRetSet = new CMDBObjectSet($oFilter); // THE ONLY DIFF IS HERE
		// NOTE: THIS DOES NOT WORK IF m_bLoaded is private...
		// BUT IT THAT CASE YOU DO NOT GET ANY ERROR !!!!!
		$oRetSet->m_bLoaded = true; // no DB load
		$oRetSet->AddObjectArray($aObjects);
		return $oRetSet;
	} 
	
	static public function FromArrayAssoc($aClasses, $aObjects)
	{
		// In a perfect world, we should create a complete tree of DBObjectSearch,
		// but as we lack most of the information related to the objects,
		// let's create one search definition
		$sClass = reset($aClasses);
		$sAlias = key($aClasses);
		$oFilter = new CMDBSearchFilter($sClass, $sAlias);

		$oRetSet = new CMDBObjectSet($oFilter);
		$oRetSet->m_bLoaded = true; // no DB load

		foreach($aObjects as $rowIndex => $aObjectsByClassAlias)
		{
			$oRetSet->AddObjectExtended($aObjectsByClassAlias);
		}
		return $oRetSet;
	} 
}

/**
 * TODO: investigate how to get rid of this class that was made to workaround some language limitation... or a poor design!
 *
 * @package     iTopORM
 */
class CMDBSearchFilter extends DBObjectSearch
{
	// this is the public interface (?)
}


?>
