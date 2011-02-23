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
			"icon" => "../images/synchro.png",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('implementation,production,obsolete'), "sql"=>"status", "default_value"=>"implementation", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("user_id", array("targetclass"=>"User", "jointype"=>null, "allowed_values"=>null, "sql"=>"user_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeClass("scope_class", array("class_category"=>"bizmodel", "more_values"=>"", "sql"=>"scope_class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("scope_restriction", array("allowed_values"=>null, "sql"=>"scope_restriction", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		
		//MetaModel::Init_AddAttribute(new AttributeDateTime("last_synchro_date", array("allowed_values"=>null, "sql"=>"last_synchro_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Format: '1 hour', '2 weeks', '3 hoursABCDEF'... Cf DateTime->Modify()
		MetaModel::Init_AddAttribute(new AttributeString("full_load_periodicity", array("allowed_values"=>null, "sql"=>"full_load_periodicity", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		
//		MetaModel::Init_AddAttribute(new AttributeString("reconciliation_list", array("allowed_values"=>null, "sql"=>"reconciliation_list", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("reconciliation_policy", array("allowed_values"=>new ValueSetEnum('use_primary_key,use_attributes'), "sql"=>"reconciliation_policy", "default_value"=>"use_attributes", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_zero", array("allowed_values"=>new ValueSetEnum('create,error'), "sql"=>"action_on_zero", "default_value"=>"create", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_one", array("allowed_values"=>new ValueSetEnum('update,error,delete'), "sql"=>"action_on_one", "default_value"=>"update", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_multiple", array("allowed_values"=>new ValueSetEnum('take_first,create,error'), "sql"=>"action_on_multiple", "default_value"=>"error", "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeEnum("delete_policy", array("allowed_values"=>new ValueSetEnum('ignore,delete,update,update_then_delete'), "sql"=>"delete_policy", "default_value"=>"ignore", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("delete_policy_update", array("allowed_values"=>null, "sql"=>"delete_policy_update", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Format: '1 hour', '2 weeks', '3 hoursABCDEF'... Cf DateTime->Modify()
		MetaModel::Init_AddAttribute(new AttributeString("delete_policy_retention", array("allowed_values"=>null, "sql"=>"delete_policy_retention", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSet("attribute_list", array("linked_class"=>"SynchroAttribute", "ext_key_to_me"=>"sync_source_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("status_list", array("linked_class"=>"SynchroLog", "ext_key_to_me"=>"sync_source_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'scope_class', 'scope_restriction', 'status', 'user_id', 'full_load_periodicity', 'reconciliation_policy', 'action_on_zero', 'action_on_one', 'action_on_multiple', 'delete_policy', 'delete_policy_update', 'delete_policy_retention' /*'attribute_list'*/, 'status_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('scope_class', 'status', 'user_id', 'full_load_periodicity')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'scope_class', 'user_id')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	public function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		if (!$this->IsNew())
		{
			$oPage->SetCurrentTab(Dict::S('Core:SynchroAttributes'));
			$oAttributeSet = $this->Get('attribute_list');
			$aAttributes = array();
			while($oAttribute = $oAttributeSet->Fetch())
			{
				$aAttributes[$oAttribute->Get('attcode')] = $oAttribute;
			}
			$aAttribs = array(
				'attcode' => array('label'=>'Attribute', 'description' => 'Field of the object'),
				'reconciliation' => array('label'=>'Reconciliation ?', 'description' => 'Used for searching'),
				'update' => array('label'=>'Update ?', 'description' => 'Used to update the object'),
				'update_policy' => array('label'=>'Update Policy', 'description' => 'Behavior of the updated field'),
			);
			$aValues = array();
			foreach(MetaModel::ListAttributeDefs($this->GetTargetClass()) as $sAttCode=>$oAttDef)
			{
				if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
				{
					if (isset($aAttributes[$sAttCode]))
					{
						$oAttribute = $aAttributes[$sAttCode];
					}
					else
					{
						$oAttribute = new SynchroAttribute();
						$oAttribute->Set('sync_source_id', $this->GetKey());
						$oAttribute->Set('attcode', $sAttCode);
						$oAttribute->Set('reconcile', MetaModel::IsReconcKey($this->GetTargetClass(), $sAttCode) ? 1 : 0);
						$oAttribute->Set('update', 1);
						$oAttribute->Set('update_policy', 'master_locked');
					}
					if (!$bEditMode)
					{
						// Read-only mode
						$aRow['reconciliation'] = $oAttribute->Get('reconcile') == 1 ? Dict::S('Core:SynchroReconcile:Yes') :  Dict::S('Core:SynchroReconcile:No'); 
						$aRow['update'] = $oAttribute->Get('update') == 1 ?  Dict::S('Core:SynchroUpdate:Yes') :  Dict::S('Core:SynchroUpdate:No');
						$aRow['attcode'] = MetaModel::GetLabel($this->GetTargetClass(), $oAttribute->Get('attcode')); 
						$aRow['update_policy'] = $oAttribute->GetAsHTML('update_policy'); 
					}
					else
					{
						// Read-only mode
						$sAttCode = $oAttribute->Get('attcode');
						$sChecked = $oAttribute->Get('reconcile') == 1 ? 'checked' : '';
						$aRow['reconciliation'] = "<input type=\"checkbox\" name=\"reconciliation[$sAttCode]\" $sChecked/>"; 
						$sChecked = $oAttribute->Get('update') == 1 ? 'checked' : '';
						$aRow['update'] = "<input type=\"checkbox\" name=\"update[$sAttCode]\" $sChecked/>"; 
						$aRow['attcode'] = MetaModel::GetLabel($this->GetTargetClass(), $oAttribute->Get('attcode'));
						$oAttDef = MetaModel::GetAttributeDef(get_class($oAttribute), 'update_policy'); 
						$aRow['update_policy'] = cmdbAbstractObject::GetFormElementForField($oPage, get_class($oAttribute), 'update_policy', $oAttDef, $oAttribute->Get('update_policy'), '', 'update_policy_'.$sAttCode, "[$sAttCode]");
					}
					$aValues[] = $aRow;
				}
			}
			$oPage->Table($aAttribs, $aValues);
			$oPage->SetCurrentTab(Dict::S('Core:SynchroStatus'));
			
			$sSelectSynchroLog = 'SELECT SynchroLog WHERE sync_source_id = :source_id';
			$oSetSynchroLog = new CMDBObjectSet(DBObjectSearch::FromOQL($sSelectSynchroLog), array('start_date' => false) /* order by*/, array('source_id' => $this->GetKey()));
			
			if ($oSetSynchroLog->Count() > 0)
			{
				$oLastLog = $oSetSynchroLog->Fetch();
				$sStartDate = $oLastLog->Get('start_date');
				$oLastLog->Get('stats_nb_seen');
				$iModified = $oLastLog->Get('stats_nb_modified');
				$iErrors = $oLastLog->Get('stats_nb_errors');
				$iCreated = $oLastLog->Get('stats_nb_created');
				$iDeleted = $oLastLog->Get('stats_nb_deleted');
				$oLastLog->Get('stats_nb_reconciled');
				if ($oLastLog->Get('status') == 'running')
				{
					// Still running !
					$oPage->p('<h2>'.Dict::Format('Core:Synchro:SynchroRunningStartedOn_Date', $sStartDate).'</h2>');
				}
				else
				{
					$sEndDate = $oLastLog->Get('end_date');
					$oPage->p('<h2>'.Dict::Format('Core:Synchro:SynchroEndedOn_Date', $sEndDate).'</h2>');
				}

				$iIgnored = 0;
				$iDisappeared = 0;
				$iDeleted = 0;
				$iObsoleted = 0;
				$iDisappearedErrors = 0;
				$iExisting = 0;
				$iUnchanged = 0;
				$iUpdated = 0;
				$iUpdatedErrors = 0;
				$iNew = 0;
				$iNewErrors = 0;
				$iReconciled = 0;
				$iCreated = 0;

				$oPage->add(
<<<EOF
	<style>
	.synoptics, .synoptics tr td { background: transparent; padding:10px; font-size:1em; vertical-align:middle; color:#fff; text-align:center;}
	.synoptics tr td.arrow { color:#333; border-top: 1px dashed #333; width:100px; }
	</style>
	<table class="synoptics">
	<tr>
	<td style="background-color:#999;">Ignored ($iIgnored)</td><td colspan="2">&nbsp;</td>
	</tr>
	<tr>
	<td style="background-color:#630;" rowspan="3">Disappeared ($iDisappeared)</td><td rowspan="3" class="arrow">=&gt;</td><td style="background-color:#300;">Deleted ($iDeleted)</td>
	</tr>
	<tr>
	<td style="background-color:#630;">Obsoleted ($iObsoleted)</td>
	</tr>
	<tr>
	<td style="background-color:#C00;">Errors ($iDisappearedErrors)</td>
	</tr>
	<tr>
	<td style="background-color:#093;" rowspan="3">Existing ($iExisting)</td><td rowspan="3" class="arrow">=&gt;</td><td style="background-color:#393;">Unchanged ($iUnchanged)</td>
	</tr>
	<tr>
	<td style="background-color:#3C3;">Updated ($iUpdated)</td>
	</tr>
	<tr>
	<td style="background-color:#C00;">Errors ($iUpdatedErrors)</td>
	</tr>
	<tr>
	<td  style="background-color:#039;"rowspan="3">New ($iNew)</td><td rowspan="3" class="arrow">=&gt;</td><td style="background-color:#C00;">Errors ($iNewErrors)</td>
	</tr>
	<tr>
	<td style="background-color:#33F;">Reconciled ($iReconciled)</td>
	</tr>
	<tr>
	<td style="background-color:#339;">Created ($iCreated)</td>
	</tr>
	</table>
EOF
				);
			}
			else
			{
				$oPage->p('<h2>'.Dict::S('Core:Synchro:NeverRun').'</h2>');
			}
			
		}
		parent::DisplayBareRelations($oPage, $bEditMode);
	}

	public function GetAttributeFlags($sAttCode)
	{
		if (($sAttCode == 'scope_class') && (!$this->IsNew()))
		{
			return OPT_ATT_READONLY;
		}
		return parent::GetAttributeFlags($sAttCode);
	}
		
	public function UpdateObject($sFormPrefix = '')
	{
		parent::UpdateObject($sFormPrefix);
		// And now read the other post parameters...
		$oAttributeSet = $this->Get('attribute_list');
		$aAttributes = array();
		while($oAttribute = $oAttributeSet->Fetch())
		{
			$aAttributes[$oAttribute->Get('attcode')] = $oAttribute;
		}
		$aReconcile = utils::ReadPostedParam('reconciliation', array());
		$aUpdate = utils::ReadPostedParam('update', array());
		$aUpdatePolicy = utils::ReadPostedParam('attr_update_policy', array());
		// update_policy cannot be empty, so there is one entry per attribute, use this to iterate
		// through all the writable attributes
		foreach($aUpdatePolicy as $sAttCode => $sValue)
		{
			if(!isset($aAttributes[$sAttCode]))
			{
				$oAttribute = new SynchroAttribute();
				$oAttribute->Set('sync_source_id', $this->GetKey());
				$oAttribute->Set('attcode', $sAttCode);
			}
			else
			{
				$oAttribute = $aAttributes[$sAttCode];
			}
			$bReconcile = 0;
			if (isset($aReconcile[$sAttCode]))
			{
				$bReconcile = $aReconcile[$sAttCode] == 'on' ? 1 : 0;
			}
			$bUpdate =  0 ; // Default / initial value
			if (isset($aUpdate[$sAttCode]))
			{
				$bUpdate = $aUpdate[$sAttCode] == 'on' ? 1 : 0;
			}
			$oAttribute->Set('reconcile', $bReconcile);
			$oAttribute->Set('update', $bUpdate);
			$oAttribute->Set('update_policy', $sValue);
			$oAttributeSet->AddObject($oAttribute);
		}
		$this->Set('attribute_list', $oAttributeSet);
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

	/**
	 * When inserting a new datasource object, also create the SynchroAttribute objects
	 * for each field of the target class
	 */
	protected function OnInsert()
	{
		// Create all the SynchroAttribute records
		$oAttributeSet = $this->Get('attribute_list');
		foreach(MetaModel::ListAttributeDefs($this->GetTargetClass()) as $sAttCode=>$oAttDef)
		{
			if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
			{
				$oAttribute = new SynchroAttribute();
				$oAttribute->Set('sync_source_id', $this->GetKey());
				$oAttribute->Set('attcode', $sAttCode);
				$oAttribute->Set('reconcile', MetaModel::IsReconcKey($this->GetTargetClass(), $sAttCode) ? 1 : 0);
				$oAttribute->Set('update', 1);
				$oAttribute->Set('update_policy', 'master_locked');
				$oAttributeSet->AddObject($oAttribute);
			}
		}
		$this->Set('attribute_list', $oAttributeSet);
	}
	/**
	 * When the new datasource has been created, let's create the synchro_data table
	 * that will hold the data records and the correspoding triggers which will maintain
	 * both tables in sync
	 */
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

		$sTriggerInsert = "CREATE TRIGGER `{$sTable}_ad` AFTER DELETE ON $sTable";
		$sTriggerInsert .= "   FOR EACH ROW";
		$sTriggerInsert .= "   BEGIN";
		$sTriggerInsert .= "      DELETE FROM priv_sync_replica WHERE id = OLD.id;";
		$sTriggerInsert .= "   END;";
		CMDBSource::Query($sTriggerInsert);
	}
	
	protected function AfterDelete()
	{
		parent::AfterDelete();

		$sTable = $this->GetDataTable();

		$sDropTable = "DROP TABLE `$sTable`";
		CMDBSource::Query($sDropTable);
		// TO DO - check that triggers get dropped with the table
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

		// Start logging this execution (stats + protection against reentrance)
		//
		$oStatLog = new SynchroLog();
		$oStatLog->Set('sync_source_id', $this->GetKey());
		$oStatLog->Set('start_date', time());
		$oStatLog->Set('status', 'running');
		$oStatLog->Set('stats_nb_seen', 0);
		$oStatLog->Set('stats_nb_modified', 0);
		$oStatLog->Set('stats_nb_errors', 0);
		$oStatLog->Set('stats_nb_created', 0);
		$oStatLog->Set('stats_nb_deleted', 0);
		$oStatLog->Set('stats_nb_reconciled', 0);
		$oStatLog->DBInsertTracked($oMyChange);

		try
		{
			$this->DoSynchronize($aDataToReplica, $oLastFullLoadStartDate, $oMyChange, $oStatLog);

			$oStatLog->Set('end_date', time());
			$oStatLog->Set('status', 'completed');
			$oStatLog->DBUpdateTracked($oMyChange);
		}
		catch (Exception $e)
		{
			$oStatLog->Set('end_date', time());
			$oStatLog->Set('status', 'completed');
			$oStatLog->DBUpdateTracked($oMyChange);
		}
	}

	protected function DoSynchronize(&$aDataToReplica, $oLastFullLoadStartDate, $oMyChange, &$oStatLog)
	{
		// Get all the replicas that were not seen in the last import and mark them as obsolete
		if ($oLastFullLoadStartDate == null)
		{
			// No previous import known, use the full_load_periodicity value... and the current date
			$oLastFullLoadStartDate = new DateTime(); // Now
			// TO DO: how do we support localization here ??
			$sLoadPeriodicity = trim($this->Get('full_load_periodicity'));
			if (strlen($sLoadPeriodicity) > 0)
			{
				$sInterval = '-'.$sLoadPeriodicity;
				// Note: the PHP doc states that Modify return FALSE in case of error
				//       but, this is actually NOT the case
				//       Therefore, I do compare before and after, considering that the
				//       format is incorrect when the datetime remains unchanged
				$sBefore = $oLastFullLoadStartDate->Format('Y-m-d H:i:s');
				$oLastFullLoadStartDate->Modify($sInterval);
				$sAfter = $oLastFullLoadStartDate->Format('Y-m-d H:i:s');
				if ($sBefore == $sAfter)
				{
					throw new CoreException("Data exchange: Wrong interval specification", array('interval' => $sInterval, 'source_id' => $this->GetKey()));
				}
			}
		}
		$sLimitDate = $oLastFullLoadStartDate->Format('Y-m-d H:i:s');	
		// TO DO: remove trace
		echo "<p>sLimitDate: $sLimitDate</p>\n";
		$sSelectToObsolete  = "SELECT SynchroReplica WHERE sync_source_id = :source_id AND status IN ('new', 'synchronized', 'modified', 'orphan') AND status_last_seen < :last_import";
		$oSetToObsolete = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToObsolete), array() /* order by*/, array('source_id' => $this->GetKey(), 'last_import' => $sLimitDate));
		while($oReplica = $oSetToObsolete->Fetch())
		{
			// TO DO: take the appropriate action based on the 'delete_policy' field
			$sUpdateOnObsolete = $this->Get('delete_policy');
			if ( ($sUpdateOnObsolete == 'update') || ($sUpdateOnObsolete == 'update_then_delete') )
			{
				// TO DO: remove trace
				echo "<p>Destination object: (dest_id:".$oReplica->Get('dest_id').") to be updated.</p>";
				$aToUpdate = array();
				$aToUpdate = explode(';', $this->Get('delete_policy_update')); //ex: 'status:obsolete;description:stopped',
				foreach($aToUpdate as $sUpdateSpec)
				{
					$aUpdateSpec = explode(':', $sUpdateSpec);
					if (count($aUpdateSpec) == 2)
					{
						$sAttCode = $aUpdateSpec[0];
						$sValue = $aUpdateSpec[1];
						$aToUpdate[$sAttCode] = $sValue;
					}
				}
				$oReplica->UpdateDestObject($aToUpdate, $oMyChange, $oStatLog);
			}
			// TO DO: remove trace
			echo "<p>Replica id:".$oReplica->GetKey()." (dest_id:".$oReplica->Get('dest_id').") marked as obsolete</p>";
			$oReplica->Set('status', 'obsolete');
			$oReplica->DBUpdateTracked($oMyChange);
		}

		//Count "seen" objects
		$sSelectSeen  = "SELECT SynchroReplica WHERE sync_source_id = :source_id AND status IN ('new', 'synchronized', 'modified', 'orphan') AND status_last_seen >= :last_import";
		$oSetSeen = new DBObjectSet(DBObjectSearch::FromOQL($sSelectSeen), array() /* order by*/, array('source_id' => $this->GetKey(), 'last_import' => $sLimitDate));
		$oStatLog->Set('stats_nb_seen', $oSetSeen->Count());
		
		// Get all the replicas that are 'new' or modified
		//
		// Get the list of SQL columns
		$sClass = $this->GetTargetClass();
		// TO DO: remove trace
		echo "<p>TargetClass: $sClass</p>";
		$aAttCodes = array();
		$sSelectAtt  = "SELECT SynchroAttribute WHERE sync_source_id = :source_id AND update = 1";
		$oSetAtt = new DBObjectSet(DBObjectSearch::FromOQL($sSelectAtt), array() /* order by*/, array('source_id' => $this->GetKey()) /* aArgs */);
		while ($oSyncAtt = $oSetAtt->Fetch())
		{
			$aAttCodes[] = $oSyncAtt->Get('attcode');
		}
		$aColumns = $this->GetSQLColumns($aAttCodes);
		$aExtDataFields = array_keys($aColumns);
		$aExtDataFields[] = 'primary_key';
		$aExtDataSpec = array(
			'table' => $this->GetDataTable(),
			'join_key' => 'id',
			'fields' => $aExtDataFields
		);

		// Get the list of reconciliation keys
		$aReconciliationKeys = array();
		if ($this->Get('reconciliation_policy') == 'use_attributes')
		{
			$sSelectAtt  = "SELECT SynchroAttribute WHERE sync_source_id = :source_id AND reconcile = 1";
			$oAttSet = new DBObjectSet(DBObjectSearch::FromOQL($sSelectAtt), array() /* order by*/, array('source_id' => $this->GetKey()) /* aArgs */);
			while ($oSyncAtt = $oAttSet->Fetch())
			{
				$aReconciliationKeys[] = $oSyncAtt->Get('attcode');
			}
		}
		elseif ($this->Get('reconciliation_policy') == 'use_primary_key')
		{
			$aReconciliationKeys[] = "primary_key";
		}
		// TO DO: remove trace
		echo "Reconciliation on: {".implode(', ', $aReconciliationKeys)."}<br/>\n";
		
		$aAttributes = array();
		foreach($aAttCodes as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($this->GetTargetClass(), $sAttCode);
			if ($oAttDef->IsWritable() && $oAttDef->IsScalar())
			{
				$aAttributes[] = $sAttCode;
			}
		}
		
		$sSelectToSync  = "SELECT SynchroReplica WHERE (status = 'new' OR status = 'modified') AND sync_source_id = :source_id";
		$oSetToSync = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToSync), array() /* order by*/, array('source_id' => $this->GetKey()) /* aArgs */, $aExtDataSpec, 0 /* limitCount */, 0 /* limitStart */);

		while($oReplica = $oSetToSync->Fetch())
		{
			if ($oReplica->Get('status') == 'modified')
			{
				$oStatLog->Set('stats_nb_modified', $oStatLog->Get('stats_nb_modified') + 1);
			}
			$oReplica->Synchro($this, $aReconciliationKeys, $aAttributes, $oMyChange, $oStatLog);
		}
		
		// Get all the replicas that are to be deleted
		//
		$oDeletionDate = $oLastFullLoadStartDate;
		$sDeleteRetention = trim($this->Get('delete_policy_retention'));
		if (strlen($sDeleteRetention) > 0)
		{
			$sInterval = '-'.$sDeleteRetention;
			// Note: the PHP doc states that Modify return FALSE in case of error
			//       but, this is actually NOT the case
			//       Therefore, I do compare before and after, considering that the
			//       format is incorrect when the datetime remains unchanged
			$sBefore = $oDeletionDate->Format('Y-m-d H:i:s');
			$oDeletionDate->Modify($sInterval);
			$sAfter = $oDeletionDate->Format('Y-m-d H:i:s');
			if ($sBefore == $sAfter)
			{
				throw new CoreException("Data exchange: Wrong interval specification", array('interval' => $sInterval, 'source_id' => $this->GetKey()));
			}
		}
		$sDeletionDate = $oDeletionDate->Format('Y-m-d H:i:s');	
		// TO DO: remove trace
		echo "<p>sDeletionDate: $sDeletionDate</p>\n";
		
		$sSelectToDelete  = "SELECT SynchroReplica WHERE sync_source_id = :source_id AND status IN ('obsolete') AND status_last_seen < :last_import";
		$oSetToDelete = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToDelete), array() /* order by*/, array('source_id' => $this->GetKey(), 'last_import' => $sDeletionDate));
		while($oReplica = $oSetToDelete->Fetch())
		{
			$oStatLog->Set('stats_nb_deleted', $oStatLog->Get('stats_nb_deleted') + 1);

			$sUpdateOnObsolete = $this->Get('delete_policy');
			if ( ($sUpdateOnObsolete == 'delete') || ($sUpdateOnObsolete == 'update_then_delete') )
			{
				// TO DO: remove trace
				echo "<p>Destination object: (dest_id:".$oReplica->Get('dest_id').") to be DELETED.</p>";
				// TO DO: delete the dest object for real...
				$oReplica->DeleteDestObject($oMyChange, $oStatLog);
			}
			// TO DO: remove trace
			echo "<p>Replica id:".$oReplica->GetKey()." (dest_id:".$oReplica->Get('dest_id').") to be deleted</p>";
			$oReplica->DBDeleteTracked($oMyChange);
		}
	}
	
	/**
	 * Get the list of SQL columns corresponding to a particular list of attribute codes
	 * Defaults to the whole list of columns for the current class	 
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
	
	// TO DO: remove if still unused
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
			// TO DO: remove trace
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
		MetaModel::Init_AddAttribute(new AttributeBoolean("update", array("allowed_values"=>null, "sql"=>"update", "default_value"=>true, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("reconcile", array("allowed_values"=>null, "sql"=>"reconcile", "default_value"=>false, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("update_policy", array("allowed_values"=>new ValueSetEnum('master_locked,master_unlocked,write_once'), "sql"=>"update_policy", "default_value"=>"master_locked", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'attcode', 'update', 'reconcile', 'update_policy')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'attcode', 'update', 'reconcile', 'update_policy')); // Attributes to be displayed for a list
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
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'attcode', 'update', 'reconcile', 'update_policy', 'reconciliation_attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'attcode', 'update', 'reconcile', 'update_policy')); // Attributes to be displayed for a list

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
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'attcode', 'update', 'reconcile', 'update_policy', 'row_separator', 'attribute_separator')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'attcode', 'update', 'reconcile', 'update_policy')); // Attributes to be displayed for a list

		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

}

//class SynchroLog extends Event
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
//		MetaModel::Init_AddAttribute(new AttributeString("userinfo", array("allowed_values"=>null, "sql"=>"userinfo", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
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
		MetaModel::Init_SetZListItems('preview', array('start_date', 'end_date', 'status', 'stats_nb_seen', 'stats_nb_errors')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}


class SynchroReplica extends DBObject
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
		MetaModel::Init_AddAttribute(new AttributeClass("dest_class", array("class_category"=>"bizmodel", "more_values"=>"", "sql"=>"dest_class", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

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

  	public function DBInsert()
	{
		throw new CoreException('A synchronization replica must be created only by the mean of triggers');
	}

	// Overload the deletion -> the replica has been created by the mean of a trigger,
	//                          it will be deleted by the mean of a trigger too
	public function DBDelete()
	{
		$oDataSource = MetaModel::GetObject('SynchroDataSource', $this->Get('sync_source_id'));
		$sTable = $oDataSource->GetDataTable();

		$sSQL = "DELETE FROM `$sTable` WHERE id = '{$this->GetKey()}'";
		CMDBSource::Query($sSQL);

		$this->m_bIsInDB = false;
		$this->m_iKey = null;
	}

	public function SetLastError($sMessage, $oException = null)
	{
		if ($oException)
		{
			$sText = $sMessage.$oException->getMessage();
		}
		else
		{
			$sText = $sMessage;
		}
		if (strlen($sText) > 255)
		{
			$sText = substr($sText, 0, 200).'...('.strlen($sText).' chars)...';
		}
		$this->Set('status_last_error', $sText);
	}

	
	public function Synchro($oDataSource, $aReconciliationKeys, $aAttributes, $oChange, $oStatLog)
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
				//echo "<p>Nothing found for: ".self::$aSearches[$oDataSource->GetKey()]->ToOQL(true, $aFilterValues)."</p>";
				$this->CreateObjectFromReplica($oDataSource->GetTargetClass(), $aAttributes, $oChange, $oStatLog);
				break;
				
				case 1:
				//echo "<p>Found 1 for: ".self::$aSearches[$oDataSource->GetKey()]->ToOQL(true, $aFilterValues)."</p>";
				$oDestObj = $oDestSet->Fetch();
				$this->UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, $oStatLog);
				$this->Set('dest_id', $oDestObj->GetKey());
				$this->Set('status_dest_creator', false);
				$this->Set('dest_class', get_class($oDestObj));

				$oStatLog->Set('stats_nb_reconciled', $oStatLog->Get('stats_nb_reconciled') + 1);
				break;
				
				default:
				$aConditions = array();
				foreach($aFilterValues as $sCode => $sValue)
				{
					$aConditions[] = $sCode.'='.$sValue;
				}
				$sCondition = implode(' AND ', $aConditions);
				//echo "<p>Found N for: ".self::$aSearches[$oDataSource->GetKey()]->ToOQL(true, $aFilterValues)."</p>";
				$this->SetLastError($iCount.' destination objects match the reconciliation criterias: '.$sCondition);
				$oStatLog->Set('stats_nb_errors', $oStatLog->Get('stats_nb_errors') + 1);
			}
			break;
			
			case 'modified':
			$oDestObj = MetaModel::GetObject($oDataSource->GetTargetClass(), $this->Get('dest_id'));
			if ($oDestObj == null)
			{
				$this->Set('status', 'orphan'); // The destination object has been deleted !
				$this->SetLastError('Destination object deleted unexpectedly');
				$oStatLog->Set('stats_nb_errors', $oStatLog->Get('stats_nb_errors') + 1);
			}
			else
			{
				$this->UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, $oStatLog);
			}
			break;
			
			default: // Do nothing in all other cases
		}
		$this->DBUpdateTracked($oChange);
	}
	
	/**
	 * Updates the destination object with the Extended data found in the synchro_data_XXXX table
	 */	
	protected function UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, &$oStatLog)
	{
		// TO DO: remove trace
		echo "<p>Update object ".$oDestObj->GetHyperLink()."</p>";
		foreach($aAttributes as $sAttCode)
		{
			$value = $this->GetValueFromExtData($sAttCode);
			$oDestObj->Set($sAttCode, $value);
			// TO DO: remove trace
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
			$this->SetLastError('Unable to update destination object: ', $e);
			$oStatLog->Set('stats_nb_errors', $oStatLog->Get('stats_nb_errors') + 1);
		}
	}

	/**
	 * Creates the destination object populating it with the Extended data found in the synchro_data_XXXX table
	 */	
	protected function CreateObjectFromReplica($sClass, $aAttributes, $oChange, $oStatLog)
	{
		// TO DO: remove trace
		echo "<p>Creating new $sClass</p>";
		$oDestObj = MetaModel::NewObject($sClass);
		foreach($aAttributes as $sAttCode)
		{
			$value = $this->GetValueFromExtData($sAttCode);
			$oDestObj->Set($sAttCode, $value);
			// TO DO: remove trace
			echo "<p>&nbsp;&nbsp;&nbsp;Setting $sAttCode to $value</p>";
		}
		try
		{
			$iNew = $oDestObj->DBInsertTracked($oChange);
			// TO DO: remove trace
			echo "<p>Created: $iNew</p>";

			$this->Set('dest_id', $oDestObj->GetKey());
			$this->Set('dest_class', get_class($oDestObj));
			$this->Set('status_dest_creator', true);
			$this->Set('status_last_error', '');
			$this->Set('status', 'synchronized');

			$oStatLog->Set('stats_nb_created', $oStatLog->Get('stats_nb_created') + 1);
		}
		catch(Exception $e)
		{
			$this->SetLastError('Unable to create destination object: ', $e);
			$oStatLog->Set('stats_nb_errors', $oStatLog->Get('stats_nb_errors') + 1);
		}
	}
	
	/**
	 * Update the destination object with given values
	 */	
	public function UpdateDestObject($aValues, $oChange, &$oStatLog)
	{
		try
		{
			$oDestObj = MetaModel::GetObject($this->Get('dest_class'), $this->Get('dest_id'));
			foreach($aValues as $sAttCode => $value)
			{
				$oDestObj->Set($sAttCode, $value);
			}
			$oDestObj->DBUpdateTracked($oChange);
		}
		catch(Exception $e)
		{
			$this->SetLastError('Unable to update the destination object: ', $e);
			$oStatLog->Set('stats_nb_errors', $oStatLog->Get('stats_nb_errors') + 1);
		}
	}

	/**
	 * Delete the destination object
	 */	
	public function DeleteDestObject($oChange, &$oStatLog)
	{
		if($this->Get('status_dest_creator'))
		{
			$oDestObj = MetaModel::GetObject($this->Get('dest_class'), $this->Get('dest_id'));
			try
			{
				$oDestObj->DBDeleteTracked($oChange);
			}
			catch(Exception $e)
			{
				$this->SetLastError('Unable to delete the destination object: ', $e);
				$oStatLog->Set('stats_nb_errors', $oStatLog->Get('stats_nb_errors') + 1);
			}
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

// TO DO: finalize.... admins only ? which options ? troubleshoot WebPageMenuNode::__construct(.... sEnableClass...) ?
//if (UserRights::IsAdministrator())
{
	$oAdminMenu = new MenuGroup('AdminTools', 80 /* fRank */);
	new OQLMenuNode('DataSources', 'SELECT SynchroDataSource', $oAdminMenu->GetIndex(), 12 /* fRank */, true, 'SynchroDataSource', UR_ACTION_MODIFY, UR_ALLOWED_YES);
	new WebPageMenuNode('Test:RunSynchro', '../synchro/synchro_exec.php', $oAdminMenu->GetIndex(), 13 /* fRank */, 'SynchroDataSource');
}	
?>