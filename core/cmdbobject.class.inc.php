<?php

/**
 * cmdbObjectClass
 * the file to include, then the core is yours
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

require_once('coreexception.class.inc.php');

require_once('config.class.inc.php');

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

require_once('cmdbchange.class.inc.php');
require_once('cmdbchangeop.class.inc.php');

require_once('csvparser.class.inc.php');
require_once('bulkchange.class.inc.php');

require_once('userrights.class.inc.php');

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
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
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
		$iId = $oMyChangeOp->DBInsert();
	}
	private function RecordObjDeletion(CMDBChange $oChange, $objkey)
	{
		$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpDelete");
		$oMyChangeOp->Set("change", $oChange->GetKey());
		$oMyChangeOp->Set("objclass", get_class($this));
		$oMyChangeOp->Set("objkey", $objkey);
		$iId = $oMyChangeOp->DBInsert();
	}
	private function RecordAttChanges(CMDBChange $oChange, array $aValues = array())
	{
		// $aValues is an array of $sAttCode => $value
		// ... some values...
		//
		if (empty($aValues))
		{
			// ... or every object values
			foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
			{
				if ($oAttDef->IsLinkSet()) continue; // #@# temporary
				$aValues[$sAttCode] = $this->Get($sAttCode); 
			}
		}
		foreach ($aValues as $sAttCode=> $value)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			if ($oAttDef->IsLinkSet()) continue; // #@# temporary
			$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttribute");
			$oMyChangeOp->Set("change", $oChange->GetKey());
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);
			$oMyChangeOp->Set("newvalue", $value);
			$iId = $oMyChangeOp->DBInsert();
		}
	}

	public function DBInsert()
	{
		if(!is_object(self::$m_oCurrChange))
		{
			trigger_error("DBInsert() could not be used here, please use DBInsertTracked() instead", E_USER_ERROR);
		}
		return $this->DBInsertTracked_Internal();
	}

	public function DBInsertTracked(CMDBChange $oChange)
	{
		self::$m_oCurrChange = $oChange;
		$this->DBInsertTracked_Internal();
		self::$m_oCurrChange = null;
	}

	protected function DBInsertTracked_Internal()
	{
		$ret = parent::DBInsert();
		$this->RecordObjCreation(self::$m_oCurrChange);
		$this->RecordAttChanges(self::$m_oCurrChange);
		return $ret;
	}

	public function DBClone($newKey = null)
	{
		if(!self::$m_oCurrChange)
		{
			trigger_error("DBClone() could not be used here, please use DBCloneTracked() instead", E_USER_ERROR);
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
		$oClone->RecordAttChanges(self::$m_oCurrChange);
		return $newKey;
	}

	public function DBUpdate()
	{
		if(!self::$m_oCurrChange)
		{
			trigger_error("DBUpdate() could not be used here, please use DBUpdateTracked() instead", E_USER_ERROR);
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
			trigger_error("Attempting to update an unchanged object", E_USER_NOTICE);
			return;
		}

		$ret = parent::DBUpdate();
		$this->RecordAttChanges(self::$m_oCurrChange, $aChanges);
		return $ret;
	}

	public function DBDelete()
	{
		if(!self::$m_oCurrChange)
		{
			trigger_error("DBDelete() could not be used here, please use DBDeleteTracked() instead", E_USER_ERROR);
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
			trigger_error("BulkDelete() could not be used here, please use BulkDeleteTracked() instead", E_USER_ERROR);
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
		trigger_error("Change tracking not tested for bulk operations", E_USER_WARNING);

		// Get the list of objects to delete (and record data before deleting the DB records)
		$oObjSet = new CMDBObjectSet($oFilter);
		$aObjAndKeys = array(); // array of pkey=>object
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
			trigger_error("BulkUpdate() could not be used here, please use BulkUpdateTracked() instead", E_USER_ERROR);
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

		// Update in one single efficient query
		$ret = parent::BulkUpdate($oFilter, $aValues);

		// Record... in many queries !!!
		while ($oItem = $oObjSet->Fetch())
		{
			$aChangedValues = $oItem->ListChangedValues($aValues);
			$oItem->RecordAttChanges(self::$m_oCurrChange, $aChangedValues);
		}
		return $ret;
	}
}



/**
 * TODO: investigate how to get rid of this class that was made to workaround some language limitation... or a poor design!
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
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
	
}

/**
 * TODO: investigate how to get rid of this class that was made to workaround some language limitation... or a poor design!
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class CMDBSearchFilter extends DBObjectSearch
{
	// this is the public interface (?)
}


?>
