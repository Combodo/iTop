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
			"name_attcode" => "",
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
		MetaModel::Init_AddAttribute(new AttributeString("scope", array("allowed_values"=>null, "sql"=>"scope", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeDateTime("last_synchro_date", array("allowed_values"=>null, "sql"=>"last_synchro_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeString("reconciliation_list", array("allowed_values"=>null, "sql"=>"reconciliation_list", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_zero", array("allowed_values"=>new ValueSetEnum('create,error'), "sql"=>"action_on_zero", "default_value"=>"create", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_one", array("allowed_values"=>new ValueSetEnum('update,error'), "sql"=>"action_on_one", "default_value"=>"update", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_multiple", array("allowed_values"=>new ValueSetEnum('take_first,create,error'), "sql"=>"action_on_multiple", "default_value"=>"error", "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeEnum("delete_policy", array("allowed_values"=>new ValueSetEnum('ignore,delete,update'), "sql"=>"delete_policy", "default_value"=>"ignore", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("delete_policy_update", array("allowed_values"=>null, "sql"=>"delete_policy_update", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("delete_policy_retention", array("allowed_values"=>null, "sql"=>"delete_policy_retention", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'user_id', 'scope', 'last_synchro_date', 'reconciliation_list', 'action_on_zero', 'action_on_one', 'action_on_multiple', 'delete_policy', 'delete_policy_update', 'delete_policy_retention')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'scope')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	public function GetTargetClass()
	{
		// target_class could be the name of a class, or an OQL
		$sScope = trim($this->Get('scope'));
		if (substr($sScope, 0, 6) == 'SELECT')
		{
			$oFilter = DBObjectSearch::FromOQL($sScope);
			$sClass = $oFilter->GetClass();
		}
		else
		{
			$sClass = $sScope;
		}
		return $sClass;
	}

	public function GetDataTable()
	{
		$sName = trim(strtolower($this->Get('name')));
		$sName = str_replace(' ', '_', $sName);
		$sTable = "synchro_data_$sName";
		return $sTable;
	}

	protected function AfterInsert()
	{
		parent::AfterInsert();

		$sTable = $this->GetDataTable();

      $sClass = $this->GetTargetClass();
		foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			if ($sAttCode == 'finalclass') continue;

			foreach($oAttDef->GetSQLColumns() as $sField => $sDBFieldType)
			{
				$aColumns[$sField] = $sDBFieldType;
			}
		}
		
		$aModified = array();
		foreach($aColumns as $sColumn => $ColSpec)
		{
			// <=> is a null-safe 'EQUALS' operator (there is no equivalent for "DIFFERS FROM")
			$aModified[] = "NOT(NEW.`$sColumn` <=> OLD.`$sColumn`)";
		}
		$sIsModified = '('.implode(') OR (', $aModified).')';

		$aFieldDefs = array();
		// Allow '0', otherwise mysql will render an error when the id is not given
		// (the trigger is expected to set the value, but it is not executed soon enough)
		$aFieldDefs[] = "id INTEGER(11) NOT NULL DEFAULT 0 PRIMARY KEY";
		$aFieldDefs[] = "`primary_key` VARCHAR(255) NULL DEFAULT NULL";
		foreach($aColumns as $sColumn => $ColSpec)
		{
			$aFieldDefs[] = "`$sColumn` $ColSpec NULL DEFAULT NULL";
		}
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

		$sTriggerUpdate = "CREATE TRIGGER `{$sTable}_bu` BEFORE UPDATE ON $sTable";
		$sTriggerUpdate .= "   FOR EACH ROW";
		$sTriggerUpdate .= "   BEGIN";
		$sTriggerUpdate .= "      IF @itopuser is null THEN";
		$sTriggerUpdate .= "         UPDATE priv_sync_replica SET status_last_seen = NOW(), `status` = IF($sIsModified AND (`status` IN ('synchronized')), 'modified', `status`) WHERE sync_source_id = {$this->GetKey()} AND id = OLD.id;";
		$sTriggerUpdate .= "         SET NEW.id = OLD.id;"; // make sure this id won't change
		$sTriggerUpdate .= "      END IF;";
		$sTriggerUpdate .= "   END;";
		CMDBSource::Query($sTriggerUpdate);
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

class SynchroLog extends cmdbAbstractObject
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
		MetaModel::Init_AddAttribute(new AttributeString("status", array("allowed_values"=>null, "sql"=>"status", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));
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

		MetaModel::Init_AddAttribute(new AttributeDateTime("status_last_seen", array("allowed_values"=>null, "sql"=>"status_last_seen", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('new,synchronized,modified,orphan,deleted,obsolete'), "sql"=>"status", "default_value"=>"new", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("status_dest_creator", array("allowed_values"=>null, "sql"=>"status_dest_creator", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("status_last_error", array("allowed_values"=>null, "sql"=>"status_last_error", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDateTime("info_creation_date", array("allowed_values"=>null, "sql"=>"info_creation_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("info_last_modified", array("allowed_values"=>null, "sql"=>"info_last_modified", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("info_last_synchro", array("allowed_values"=>null, "sql"=>"info_last_synchro", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'dest_id', 'status_last_seen', 'status', 'status_dest_creator', 'status_last_error', 'info_creation_date', 'info_last_modified', 'info_last_synchro')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'dest_id', 'status_last_seen', 'status', 'status_dest_creator', 'status_last_error')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

?>
