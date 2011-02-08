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
 * Data Exchange - synchronization with external applications (incoming data)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

class SynchroDataSource extends cmdbAbstractObject
{	
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => array('name'),
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_sync_datasource",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('implementation,production,obsolete'), "sql"=>"status", "default_value"=>"implementation", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("user_id", array("targetclass"=>"User", "jointype"=>null, "allowed_values"=>null, "sql"=>"user_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeClass("scope_class", array("allowed_values"=>null, "sql"=>"scope_class", "default_value"=>null, "class_category" => '', "more_values" => array(), "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("scope_restriction", array("allowed_values"=>null, "sql"=>"scope_restriction", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		
		//MetaModel::Init_AddAttribute(new AttributeDateTime("last_synchro_date", array("allowed_values"=>null, "sql"=>"last_synchro_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("full_load_periodicity", array("allowed_values"=>null, "sql"=>"full_load_periodicity", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeString("reconciliation_list", array("allowed_values"=>null, "sql"=>"reconciliation_list", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_zero", array("allowed_values"=>new ValueSetEnum('create,error'), "sql"=>"action_on_zero", "default_value"=>"create", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_one", array("allowed_values"=>new ValueSetEnum('update,error,delete'), "sql"=>"action_on_one", "default_value"=>"update", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_multiple", array("allowed_values"=>new ValueSetEnum('take_first,create,error'), "sql"=>"action_on_multiple", "default_value"=>"error", "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeEnum("delete_policy", array("allowed_values"=>new ValueSetEnum('ignore,delete,update,update_then_delete'), "sql"=>"delete_policy", "default_value"=>"ignore", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("delete_policy_update", array("allowed_values"=>null, "sql"=>"delete_policy_update", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("delete_policy_retention", array("allowed_values"=>null, "sql"=>"delete_policy_retention", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'user_id', 'scope_class', 'scope_restriction', 'full_load_periodicity', 'reconciliation_list', 'action_on_zero', 'action_on_one', 'action_on_multiple', 'delete_policy', 'delete_policy_update', 'delete_policy_retention')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'scope_class', 'user_id')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'scope_class', 'user_id')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	public function GetTargetClass()
	{
		return $this->Get('scope_class');
	}

	public function GetDataTable()
	{
		$sName = strtolower($this->GetTargetClass());
		$sName = str_replace('\'"&@|\\/ ', '_', $sName); // Remove forbidden characters from the table name
		$sName .= '_'.$this->GetKey(); // Add a suffix for unicity
		$sTable = MetaModel::GetConfig()->GetDBSubName()."synchro_data_$sName"; // Add the prefix if any
		return $sTable;
	}

	protected function AfterInsert()
	{
		parent::AfterInsert();

		$sTable = $this->GetDataTable();

		$aColumns = $this->GetSQLColumns();
		
		$aFieldDefs = array();
		// Allow '0', otherwise mysql will render an error when the id is not given
		// (the trigger is expected to set the value, but it is not executed soon enough)
		$aFieldDefs[] = "id INTEGER(11) NOT NULL DEFAULT 0 ";
		$aFieldDefs[] = "`primary_key` VARCHAR(255) NULL DEFAULT NULL";
		foreach($aColumns as $sColumn => $ColSpec)
		{
			$aFieldDefs[] = "`$sColumn` $ColSpec NULL DEFAULT NULL";
		}
		$aFieldDefs[] = "INDEX (id)";
		$aFieldDefs[] = "INDEX (primary_key)";
		$sFieldDefs = implode(', ', $aFieldDefs);

		$sCreateTable = "CREATE TABLE `$sTable` ($sFieldDefs) ENGINE = innodb;";
		CMDBSource::Query($sCreateTable);

		$sTriggerInsert = "CREATE TRIGGER `{$sTable}_bi` BEFORE INSERT ON $sTable";
		$sTriggerInsert .= "   FOR EACH ROW";
		$sTriggerInsert .= "   BEGIN";
		$sTriggerInsert .= "      INSERT INTO priv_sync_replica (sync_source_id, status_last_seen, `status`) VALUES ({$this->GetKey()}, NOW(), 'new');";
		$sTriggerInsert .= "      SET NEW.id = LAST_INSERT_ID();";
		$sTriggerInsert .= "   END;";
		CMDBSource::Query($sTriggerInsert);

		$aModified = array();
		foreach($aColumns as $sColumn => $ColSpec)
		{
			// <=> is a null-safe 'EQUALS' operator (there is no equivalent for "DIFFERS FROM")
			$aModified[] = "NOT(NEW.`$sColumn` <=> OLD.`$sColumn`)";
		}
		$sIsModified = '('.implode(') OR (', $aModified).')';

		// Update the replica
		//
		// status is forced to "new" if the replica was obsoleted directly from the state "new" (dest_id = null)
		// otherwise, if status was either 'obsolete' or 'synchronized' it is turned into 'modified' or 'synchronized' depending on the changes
		// otherwise, the status is left as is
		$sTriggerUpdate = "CREATE TRIGGER `{$sTable}_bu` BEFORE UPDATE ON $sTable";
		$sTriggerUpdate .= "   FOR EACH ROW";
		$sTriggerUpdate .= "   BEGIN";
		$sTriggerUpdate .= "      IF @itopuser is null THEN";
		$sTriggerUpdate .= "         UPDATE priv_sync_replica SET status_last_seen = NOW(), `status` = IF(`status` = 'obsolete', IF(`dest_id` IS NULL, 'new', 'modified'), IF(`status` IN ('synchronized') AND ($sIsModified), 'modified', `status`)) WHERE sync_source_id = {$this->GetKey()} AND id = OLD.id;";
		$sTriggerUpdate .= "         SET NEW.id = OLD.id;"; // make sure this id won't change
		$sTriggerUpdate .= "      END IF;";
		$sTriggerUpdate .= "   END;";
		CMDBSource::Query($sTriggerUpdate);
	}
	
	/**
	 * Perform a synchronization between the data stored in the replicas (&synchro_data_xxx_xx table)
	 * and the iTop objects. If the lastFullLoadStartDate is NOT specified then the full_load_periodicity
	 * is used to determine which records are obsolete.
	 * @param Hash $aDataToReplica Debugs/Trace information, one entry per replica
	 * @param DateTime $oLastFullLoadStartDate Date of the last full load (start date/time), if known
	 * @return void
	 */
	public function Synchronize(&$aDataToReplica, $oLastFullLoadStartDate = null)
	{
		// Create a change used for logging all the modifications/creations happening during the synchro
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$sUserString = CMDBChange::GetCurrentUserName();
		$oMyChange->Set("userinfo", $sUserString);
		$iChangeId = $oMyChange->DBInsert();
	
		// Get all the replicas that were not seen in the last import and mark them as obsolete
		if ($oLastFullLoadStartDate == null)
		{
			// No previous import known, use the full_load_periodicity value... and the current date
			$oLastFullLoadStartDate = new DateTime(); // Now
			// TO DO: how do we support localization here ??
			$oInterval = DateInterval::createFromDateString($this->Get('full_load_periodicity'));
			$oLastFullLoadStartDate->sub($oInterval);
		}
		$sLimitDate = $oLastFullLoadStartDate->Format('Y-m-d H:i:s');	
		echo "<p>sLimitDate: $sLimitDate</p>\n";
		$sOQL  = "SELECT SynchroReplica WHERE status IN ('new', 'synchronized', 'modified', 'orphan') AND status_last_seen < :last_import";
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array() /* order by*/, array('last_import' => $sLimitDate));
		while($oReplica = $oSet->Fetch())
		{
			// TO DO: take the appropriate action based on the 'delete_policy' field
			$sUpdateOnObsolete = $this->Get('delete_policy');
			if ( ($sUpdateOnObsolete == 'update') || ($sUpdateOnObsolete == 'update_then_delete') )
			{
				echo "<p>Destination object: (dest_id:".$oReplica->Get('dest_id').") to be updated.</p>";
				// TO DO: update the dest object for real...
			}
			echo "<p>Replica id:".$oReplica->GetKey()." (dest_id:".$oReplica->Get('dest_id').") marked as obsolete</p>";
			$oReplica->Set('status', 'obsolete');
			$oReplica->DBUpdateTracked($oMyChange);
		}
		
		// Get all the replicas that are 'new' or modified
		// Get the list of SQL columns: TO DO: retrieve this list from the SynchroAttributes
		$sClass = $this->GetTargetClass();
		echo "<p>TargetClass: $sClass</p>";
		$aAttCodes = array();
		foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			if ($sAttCode == 'finalclass') continue;

			$aAttCodes[] = $sAttCode;
		}
		$aColumns = $this->GetSQLColumns($aAttCodes);
		$aExtDataSpec = array(
		'table' => $this->GetDataTable(),
		'join_key' => 'id',
		'fields' => array_keys($aColumns));

		$sOQL  = "SELECT SynchroReplica WHERE (status = 'new' OR status = 'modified') AND sync_source_id = :source_id";
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array() /* order by*/, array('source_id' => $this->GetKey()) /* aArgs */, $aExtDataSpec, 0 /* limitCount */, 0 /* limitStart */);
		
		// Get the list of reconciliation keys, make sure they are valid
		$aReconciliationKeys = array();
		foreach( explode(',', $this->Get('reconciliation_list')) as $sKey)
		{
			$sFilterCode = trim($sKey);
			if (MetaModel::IsValidFilterCode($this->GetTargetClass(), $sFilterCode))
			{
				$aReconciliationKeys[] = $sFilterCode;
			}
			else
			{
				throw(new Exception('Invalid reconciliation criteria: '.$sFilterCode));
			}
		}
		
		// TO DO: Get the "real" list of enabled attributes ! Not all of them !
		// for now get all scalar & writable attributes
		$aAttributes = array();
		foreach($aAttCodes as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($this->GetTargetClass(), $sAttCode);
			if ($oAttDef->IsWritable() && $oAttDef->IsScalar())
			{
				$aAttributes[] = $sAttCode;
			}
		}
		
		while($oReplica = $oSet->Fetch())
		{
			$oReplica->Synchro($this, $aReconciliationKeys, $aAttributes, $oMyChange);	
		}
		
		// Get all the replicas that are to be deleted
		$oInterval = DateInterval::createFromDateString($this->Get('delete_policy_retention'));
		$oDeletionDate = $oLastFullLoadStartDate->Sub($oInterval);
		$sDeletionDate = $oDeletionDate->Format('Y-m-d H:i:s');	
		echo "<p>sDeletionDate: $sDeletionDate</p>\n";
		
		$sOQL  = "SELECT SynchroReplica WHERE status IN ('new', 'synchronized', 'modified', 'orphan') AND status_last_seen < :last_import";
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array() /* order by*/, array('last_import' => $sDeletionDate));
		while($oReplica = $oSet->Fetch())
		{
			$sUpdateOnObsolete = $this->Get('delete_policy');
			if ( ($sUpdateOnObsolete == 'delete') || ($sUpdateOnObsolete == 'update_then_delete') )
			{
				echo "<p>Destination object: (dest_id:".$oReplica->Get('dest_id').") to be DELETED.</p>";
				// TO DO: delete the dest object for real...
			}
			echo "<p>Replica id:".$oReplica->GetKey()." (dest_id:".$oReplica->Get('dest_id').") marked as deleted</p>";
			$oReplica->Set('status', 'deleted');
			$oReplica->DBUpdateTracked($oMyChange);
		}
		return;
	}
	
	/**
	 * Get the list of SQL columns corresponding to a particular list of attribute codes
	 * Defaults to the whole list of columns for the current task	 
	 */
	public function GetSQLColumns($aAttributeCodes = null)
	{
		$aColumns = array();
		$sClass = $this->GetTargetClass();

		if (is_null($aAttributeCodes))
		{
			$aAttributeCodes = array();
			foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if ($sAttCode == 'finalclass') continue;
				$aAttributeCodes[] = $sAttCode;
			}
		}

		foreach($aAttributeCodes as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			
			foreach($oAttDef->GetSQLColumns() as $sField => $sDBFieldType)
			{
				$aColumns[$sField] = $sDBFieldType;
			}
		}
		return $aColumns;
	}
	
	public function IsRunning()
	{
		$sOQL = "SELECT SynchroLog WHERE sync_source_id = :source_id AND status='running'";
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array('start_date' => false) /* order by*/, array('source_id' => $this->GetKey()) /* aArgs */, array(), 1 /* limitCount */, 0 /* limitStart */);
		if ($oSet->Count() < 1)
		{
			$bRet = false;
		}
		else
		{
			$bRet = true;
		}
		return $bRet;
	}
	
	public function GetLatestLog()
	{
		$oLog = null;
		
		$sOQL = "SELECT SynchroLog WHERE sync_source_id = :source_id";
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array('start_date' => false) /* order by*/, array('source_id' => $this->GetKey()) /* aArgs */, array(), 1 /* limitCount */, 0 /* limitStart */);
		if ($oSet->Count() >= 1)
		{
			$oLog = $oSet->Fetch();
		}
		return $oLog;
	}
	
	/**
	 * Retrieve from the log, the date of the last completed import
	 * @return DateTime
	 */
	public function GetLastCompletedImportDate()
	{
		$date = null;
		$sOQL = "SELECT SynchroLog WHERE sync_source_id = :source_id AND status='completed'";
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array('end_date' => false) /* order by*/, array('source_id' => $this->GetKey()) /* aArgs */, array(), 0 /* limitCount */, 0 /* limitStart */);
		if ($oSet->Count() >= 1)
		{
			$oLog = $oSet->Fetch();
			$date = $oLog->Get('end_date');
		}
		else
		{
			echo "<p>No completed log found</p>\n";
		}
		return $date;
	}
}

class SynchroAttribute extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_sync_att",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("sync_source_id", array("targetclass"=>"SynchroDataSource", "jointype"=> "", "allowed_values"=>null, "sql"=>"sync_source_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attcode", array("allowed_values"=>null, "sql"=>"attcode", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("enabled", array("allowed_values"=>null, "sql"=>"enabled", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("update_policy", array("allowed_values"=>new ValueSetEnum('master_locked,master_unlocked,write_once'), "sql"=>"update_policy", "default_value"=>"master_locked", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'attcode', 'enabled', 'update_policy')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'attcode', 'enabled', 'update_policy')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class SynchroAttExtKey extends SynchroAttribute
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_sync_att_extkey",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("reconciliation_attcode", array("allowed_values"=>null, "sql"=>"reconciliation_attcode", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'attcode', 'enabled', 'update_policy', 'reconciliation_attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'attcode', 'enabled', 'update_policy')); // Attributes to be displayed for a list

		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

}

class SynchroAttLinkSet extends SynchroAttribute
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_sync_att_linkset",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("row_separator", array("allowed_values"=>null, "sql"=>"row_separator", "default_value"=>'|', "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attribute_separator", array("allowed_values"=>null, "sql"=>"attribute_separator", "default_value"=>';', "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'attcode', 'enabled', 'update_policy', 'row_separator', 'attribute_separator')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'attcode', 'enabled', 'update_policy')); // Attributes to be displayed for a list

		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

}

class SynchroLog extends CmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_sync_log",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("sync_source_id", array("targetclass"=>"SynchroDataSource", "jointype"=> "", "allowed_values"=>null, "sql"=>"sync_source_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("start_date", array("allowed_values"=>null, "sql"=>"start_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("end_date", array("allowed_values"=>null, "sql"=>"end_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('running,completed'), "sql"=>"status", "default_value"=>"running", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_seen", array("allowed_values"=>null, "sql"=>"stats_nb_seen", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_modified", array("allowed_values"=>null, "sql"=>"stats_nb_modified", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_errors", array("allowed_values"=>null, "sql"=>"stats_nb_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_created", array("allowed_values"=>null, "sql"=>"stats_nb_created", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_deleted", array("allowed_values"=>null, "sql"=>"stats_nb_deleted", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_reconciled", array("allowed_values"=>null, "sql"=>"stats_nb_reconciled", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'start_date', 'end_date', 'status', 'stats_nb_seen', 'stats_nb_modified', 'stats_nb_errors', 'stats_nb_created', 'stats_nb_deleted', 'stats_nb_reconciled')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'start_date', 'end_date', 'status', 'stats_nb_seen', 'stats_nb_modified', 'stats_nb_errors')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}


class SynchroReplica extends cmdbAbstractObject
{
	static $aSearches = array(); // Cache of OQL queries used for reconciliation (per data source)
	
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_sync_replica",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("sync_source_id", array("targetclass"=>"SynchroDataSource", "jointype"=> "", "allowed_values"=>null, "sql"=>"sync_source_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("dest_id", array("allowed_values"=>null, "sql"=>"dest_id", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeClass("dest_class", array("allowed_values"=>null, "sql"=>"dest_class", "default_value"=>null, "class_category" => '', "more_values" => array(), "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDateTime("status_last_seen", array("allowed_values"=>null, "sql"=>"status_last_seen", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('new,synchronized,modified,orphan,obsolete'), "sql"=>"status", "default_value"=>"new", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("status_dest_creator", array("allowed_values"=>null, "sql"=>"status_dest_creator", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("status_last_error", array("allowed_values"=>null, "sql"=>"status_last_error", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDateTime("info_creation_date", array("allowed_values"=>null, "sql"=>"info_creation_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("info_last_modified", array("allowed_values"=>null, "sql"=>"info_last_modified", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("info_last_synchro", array("allowed_values"=>null, "sql"=>"info_last_synchro", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'dest_id', 'dest_class', 'status_last_seen', 'status', 'status_dest_creator', 'status_last_error', 'info_creation_date', 'info_last_modified', 'info_last_synchro')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'dest_id', 'dest_class', 'status_last_seen', 'status', 'status_dest_creator', 'status_last_error')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('sync_source_id', 'status_last_seen', 'status', 'status_dest_creator', 'dest_class', 'dest_id', 'status_last_error')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
	
	public function Synchro($oDataSource, $aReconciliationKeys, $aAttributes, $oChange)
	{
		switch($this->Get('status'))
		{
			case 'new':
			// If needed, construct the query used for the reconciliation
			if (!isset(self::$aSearches[$oDataSource->GetKey()]))
			{
				foreach($aReconciliationKeys as $sFilterCode)
				{
					$aCriterias[] = ($sFilterCode == 'primary_key' ? 'id' : $sFilterCode).' = :'.$sFilterCode;
				}
				$sOQL = "SELECT ".$oDataSource->GetTargetClass()." WHERE ".implode(' AND ', $aCriterias);
				self::$aSearches[$oDataSource->GetKey()] = DBObjectSearch::FromOQL($sOQL);
			}
			// Get the criterias for the search
			$aFilterValues = array();
			foreach($aReconciliationKeys as $sFilterCode)
			{
				$aFilterValues[$sFilterCode] = $this->GetValueFromExtData($sFilterCode);
			}
			$oDestSet = new DBObjectSet(self::$aSearches[$oDataSource->GetKey()], array(), $aFilterValues);
			$iCount = $oDestSet->Count();
			// How many objects match the reconciliation criterias
			switch($iCount)
			{
				case 0:
				$this->CreateObjectFromReplica($oDataSource->GetTargetClass(), $aAttributes, $oChange);
				break;
				
				case 1:
				$oDestObj = $oDestSet->Fetch();
				$this->UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange);
				$this->Set('dest_id', $oDestObj->GetKey());
				$this->Set('status_dest_creator', false);
				$this->Set('dest_class', get_class($oDestObj));
				break;
				
				default:
				$aConditions = array();
				foreach($aFilterValues as $sCode => $sValue)
				{
					$aConditions[] = $sCode.'='.$sValue;
				}
				$sCondition = implode(' AND ', $aConditions);
				$this->Set('status_last_error', $iCount.' destination objects match the reconciliation criterias: '.$sCondition);
			}
			break;
			
			case 'modified':
			$oDestObj = MetaModel::GetObject($oDataSource->GetTargetClass(), $this->Get('dest_id'));
			if ($oDestObj == null)
			{
				$this->Set('status', 'orphan'); // The destination object has been deleted !
				$this->Set('status_last_error', 'Destination object deleted unexpectedly');
			}
			else
			{
				$this->UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange);
			}
			break;
			
			default: // Do nothing in all other cases
		}
		$this->DBUpdateTracked($oChange);
	}
	
	/**
	 * Updates the destination object with the Extended data found in the synchro_data_XXXX table
	 */	
	protected function UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange)
	{
		echo "<p>Update object ".$oDestObj->GetName()."</p>";
		foreach($aAttributes as $sAttCode)
		{
			$value = $this->GetValueFromExtData($sAttCode);
			$oDestObj->Set($sAttCode, $value);
			echo "<p>&nbsp;&nbsp;&nbsp;Setting $sAttCode to $value</p>";
		}
		try
		{
			$oDestObj->DBUpdateTracked($oChange);
			$this->Set('status_last_error', '');
			$this->Set('status', 'synchronized');
		}
		catch(Exception $e)
		{
			$this->Set('status_last_error', 'Unable to update destination object');
		}
	}

	/**
	 * Creates the destination object populating it with the Extended data found in the synchro_data_XXXX table
	 */	
	protected function CreateObjectFromReplica($sClass, $aAttributes, $oChange)
	{
		echo "<p>Creating new $sClass</p>";
		$oDestObj = MetaModel::NewObject($sClass);
		foreach($aAttributes as $sAttCode)
		{
			$value = $this->GetValueFromExtData($sAttCode);
			$oDestObj->Set($sAttCode, $value);
			echo "<p>&nbsp;&nbsp;&nbsp;Setting $sAttCode to $value</p>";
		}
		try
		{
			$oDestObj->DBInsertTracked($oChange);
			$this->Set('dest_id', $oDestObj->GetKey());
			$this->Set('dest_class', get_class($oDestObj));
			$this->Set('status_dest_creator', true);
			$this->Set('status_last_error', '');
			$this->Set('status', 'synchronized');
		}
		catch(Exception $e)
		{
			$this->Set('status_last_error', 'Unable to update destination object');
		}
	}
	
	/**
	 * Get the value from the 'Extended Data' located in the synchro_data_xxx table for this replica
	 */
	 protected function GetValueFromExtData($sColumnName)
	 {
	 	$aData = $this->GetExtendedData();
	 	return $aData[$sColumnName];
	 }
}

//if (UserRights::IsAdministrator())
{
	$oAdminMenu = new MenuGroup('AdminTools', 80 /* fRank */);
	new OQLMenuNode('DataSources', 'SELECT SynchroDataSource', $oAdminMenu->GetIndex(), 12 /* fRank */, true, 'SynchroDataSource', UR_ACTION_MODIFY, UR_ALLOWED_YES);
	new WebPageMenuNode('Test:RunSynchro', '../synchro/synchro_exec.php', $oAdminMenu->GetIndex(), 13 /* fRank */);
}	
?>