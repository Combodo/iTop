<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Data Exchange - synchronization with external applications (incoming data)
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class SynchroExceptionNotStarted extends CoreException
{
}

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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("notify_contact_id", array("targetclass"=>"Contact", "jointype"=>null, "allowed_values"=>null, "sql"=>"notify_contact_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeClass("scope_class", array("class_category"=>"bizmodel,addon/authentication", "more_values"=>"", "sql"=>"scope_class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("database_table_name", array("allowed_values"=>null, "sql"=>"database_table_name", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array(), "validation_pattern" => "^[A-Za-z0-9_]*$")));
				
		// Declared here for a future usage, but ignored so far
		MetaModel::Init_AddAttribute(new AttributeString("scope_restriction", array("allowed_values"=>null, "sql"=>"scope_restriction", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		
		//MetaModel::Init_AddAttribute(new AttributeDateTime("last_synchro_date", array("allowed_values"=>null, "sql"=>"last_synchro_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Format: seconds (int)
		MetaModel::Init_AddAttribute(new AttributeDuration("full_load_periodicity", array("allowed_values"=>null, "sql"=>"full_load_periodicity", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		
//		MetaModel::Init_AddAttribute(new AttributeString("reconciliation_list", array("allowed_values"=>null, "sql"=>"reconciliation_list", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("reconciliation_policy", array("allowed_values"=>new ValueSetEnum('use_primary_key,use_attributes'), "sql"=>"reconciliation_policy", "default_value"=>"use_attributes", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_zero", array("allowed_values"=>new ValueSetEnum('create,error'), "sql"=>"action_on_zero", "default_value"=>"create", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_one", array("allowed_values"=>new ValueSetEnum('update,error'), "sql"=>"action_on_one", "default_value"=>"update", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_multiple", array("allowed_values"=>new ValueSetEnum('take_first,create,error'), "sql"=>"action_on_multiple", "default_value"=>"error", "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeEnum("delete_policy", array("allowed_values"=>new ValueSetEnum('ignore,delete,update,update_then_delete'), "sql"=>"delete_policy", "default_value"=>"ignore", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("delete_policy_update", array("allowed_values"=>null, "sql"=>"delete_policy_update", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Format: seconds (unsigned int)
		MetaModel::Init_AddAttribute(new AttributeDuration("delete_policy_retention", array("allowed_values"=>null, "sql"=>"delete_policy_retention", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSet("attribute_list", array("linked_class"=>"SynchroAttribute", "ext_key_to_me"=>"sync_source_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array(), 'tracking_level' => LINKSET_TRACKING_DETAILS)));
		// Not used yet !
		MetaModel::Init_AddAttribute(new AttributeEnum("user_delete_policy", array("allowed_values"=>new ValueSetEnum('everybody,administrators,nobody'), "sql"=>"user_delete_policy", "default_value"=>"nobody", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeURL("url_icon", array("allowed_values"=>null, "sql"=>"url_icon", "default_value"=>null, "is_null_allowed"=>true, "target"=> '_top', "depends_on"=>array())));
		// The field below is not a real URL since it can contain placeholders like $replica->primary_key$ which are not syntactically allowed in a real URL
		MetaModel::Init_AddAttribute(new AttributeString("url_application", array("allowed_values"=>null, "sql"=>"url_application", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array(
			'col:0'=> array(
				'fieldset:SynchroDataSource:Description' => array('name','description','status','scope_class','user_id','notify_contact_id','url_icon','url_application', 'database_table_name')),
			'col:1'=> array(
				'fieldset:SynchroDataSource:Reconciliation' => array('reconciliation_policy','action_on_zero','action_on_one','action_on_multiple'),
				'fieldset:SynchroDataSource:Deletion' => array('user_delete_policy','full_load_periodicity','delete_policy','delete_policy_update','delete_policy_retention'))
			)
		);		
		MetaModel::Init_SetZListItems('list', array('scope_class', 'status', 'user_id', 'full_load_periodicity')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'scope_class', 'user_id')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	public function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = array())
	{
		if (!$this->IsNew())
		{
			$this->Set('database_table_name', $this->GetDataTable());
		}
		return parent::DisplayBareProperties($oPage, $bEditMode, $sPrefix, $aExtraParams);
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
			// Columns of the form
			$aAttribs = array();
			foreach(array('attcode', 'reconciliation', 'update', 'update_policy', 'reconciliation_attcode') as $s )
			{
				$aAttribs[$s] = array( 'label' => Dict::S("Core:SynchroAtt:$s"), "description" => Dict::S("Core:SynchroAtt:$s+"));
			}
			// Rows of the form
			$aValues = array();
			foreach(MetaModel::ListAttributeDefs($this->GetTargetClass()) as $sAttCode=>$oAttDef)
			{
				if ($oAttDef->IsWritable())
				{
					if (isset($aAttributes[$sAttCode]))
					{
						$oAttribute = $aAttributes[$sAttCode];
					}
					else
					{
						if ($oAttDef->IsExternalKey())
						{
							$oAttribute = new SynchroAttExtKey();
							$oAttribute->Set('reconciliation_attcode', ''); // Blank means by pkey
						}
						elseif ($oAttDef->IsLinkSet() && $oAttDef->IsIndirect())
						{
							$oAttribute = new SynchroAttLinkSet();
							// Todo - add these settings into the form
							$oAttribute->Set('row_separator', MetaModel::GetConfig()->Get('link_set_item_separator'));
							$oAttribute->Set('attribute_separator', MetaModel::GetConfig()->Get('link_set_attribute_separator'));
							$oAttribute->Set('value_separator', MetaModel::GetConfig()->Get('link_set_value_separator'));
							$oAttribute->Set('attribute_qualifier', MetaModel::GetConfig()->Get('link_set_attribute_qualifier'));
						}
						elseif ($oAttDef->IsScalar())
						{
							$oAttribute = new SynchroAttribute();
						}
						else
						{
							$oAttribute = null;
						}

						if (!is_null($oAttribute))
						{
							$oAttribute->Set('sync_source_id', $this->GetKey());
							$oAttribute->Set('attcode', $sAttCode);
							$oAttribute->Set('reconcile', MetaModel::IsReconcKey($this->GetTargetClass(), $sAttCode) ? 1 : 0);
							$oAttribute->Set('update', 1);
							$oAttribute->Set('update_policy', 'master_locked');
						}
					}
					if (!is_null($oAttribute))
					{
						if (!$bEditMode)
						{
							// Read-only mode
							$aRow['reconciliation'] = $oAttribute->Get('reconcile') == 1 ? Dict::S('Core:SynchroReconcile:Yes') :  Dict::S('Core:SynchroReconcile:No'); 
							$aRow['update'] = $oAttribute->Get('update') == 1 ?  Dict::S('Core:SynchroUpdate:Yes') :  Dict::S('Core:SynchroUpdate:No');
							$aRow['attcode'] = MetaModel::GetLabel($this->GetTargetClass(), $oAttribute->Get('attcode')).' ('.$oAttribute->Get('attcode').')';
							$aRow['update_policy'] = $oAttribute->GetAsHTML('update_policy');
							if ($oAttDef->IsExternalKey())
							{
								$aRow['reconciliation_attcode'] = $oAttribute->GetAsHTML('reconciliation_attcode');
							}
							else
							{
								$aRow['reconciliation_attcode'] = '&nbsp;';
							}
						}
						else
						{
							// Edit mode
							$sAttCode = $oAttribute->Get('attcode');
							$sChecked = $oAttribute->Get('reconcile') == 1 ? 'checked' : '';
							$aRow['reconciliation'] = "<input type=\"checkbox\" name=\"reconciliation[$sAttCode]\" $sChecked/>"; 
							$sChecked = $oAttribute->Get('update') == 1 ? 'checked' : '';
							$aRow['update'] = "<input type=\"checkbox\" name=\"update[$sAttCode]\" $sChecked/>"; 
							$aRow['attcode'] = MetaModel::GetLabel($this->GetTargetClass(), $oAttribute->Get('attcode')).' ('.$oAttribute->Get('attcode').')';
							$oUpdateAttDef = MetaModel::GetAttributeDef(get_class($oAttribute), 'update_policy'); 
							$aRow['update_policy'] = cmdbAbstractObject::GetFormElementForField($oPage, get_class($oAttribute), 'update_policy', $oUpdateAttDef, $oAttribute->Get('update_policy'), '', 'update_policy_'.$sAttCode, "[$sAttCode]");
							if ($oAttDef->IsExternalKey())
							{
								$aRow['reconciliation_attcode'] = $oAttribute->GetReconciliationFormElement($oAttDef->GetTargetClass(), "attr_reconciliation_attcode[$sAttCode]");
							}
							else
							{
								$aRow['reconciliation_attcode'] = '&nbsp;';
							}
						}
						$aValues[] = $aRow;
					}
				}
			}
			$oPage->p(Dict::Format('Class:SynchroDataSource:DataTable', $this->GetDataTable()));
			$oPage->Table($aAttribs, $aValues);
			$this->DisplayStatusTab($oPage);
		}
		parent::DisplayBareRelations($oPage, $bEditMode);
	}
	
	/**
	 * Displays the status (SynchroLog) of the datasource in a graphical manner
	 * @param $oPage WebPage
	 * @return void
	 */
	protected function DisplayStatusTab(WebPage $oPage)
	{
		$oPage->SetCurrentTab(Dict::S('Core:SynchroStatus'));
		
		$sSelectSynchroLog = 'SELECT SynchroLog WHERE sync_source_id = :source_id';
		$oSetSynchroLog = new CMDBObjectSet(DBObjectSearch::FromOQL($sSelectSynchroLog), array('start_date' => false) /* order by*/, array('source_id' => $this->GetKey()));
		$oSetSynchroLog->SetLimit(100); // Display only the 100 latest runs
		
		if ($oSetSynchroLog->Count() > 0)
		{
			$oLastLog = $oSetSynchroLog->Fetch();
			$sStartDate = $oLastLog->Get('start_date');
			$oLastLog->Get('stats_nb_replica_seen');
			$iLastLog = 0;
			$iDSid = $this->GetKey();
			if ($oLastLog->Get('status') == 'running')
			{
				// Still running !
				$oPage->p('<h2>'.Dict::Format('Core:Synchro:SynchroRunningStartedOn_Date', $sStartDate).'</h2>');
			}
			else
			{
				$sEndDate = $oLastLog->Get('end_date');
				$iLastLog = $oLastLog->GetKey();
				$oPage->p('<h2>'.Dict::Format('Core:Synchro:SynchroEndedOn_Date', $sEndDate).'</h2>');
				$sOQL = "SELECT SynchroReplica WHERE sync_source_id=$iDSid";
				$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
				$iCountAllReplicas = $oSet->Count();
				$sAllReplicas = "<a href=\"../synchro/replica.php?operation=oql&datasource=$iDSid&oql=$sOQL\">$iCountAllReplicas</a>";
				$sOQL = "SELECT SynchroReplica WHERE sync_source_id=$iDSid AND status_last_error !=''";
				$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
				$iCountAllErrors = $oSet->Count();
				$sAllErrors = "<a href=\"../synchro/replica.php?operation=oql&datasource=$iDSid&oql=$sOQL\">$iCountAllErrors</a>";
				$sOQL = "SELECT SynchroReplica WHERE sync_source_id=$iDSid AND status_last_warning !=''";
				$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
				$iCountAllWarnings = $oSet->Count();
				$sAllWarnings = "<a href=\"../synchro/replica.php?operation=oql&datasource=$iDSid&oql=$sOQL\">$iCountAllWarnings</a>";
				$oPage->p('<h2>'.Dict::Format('Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings', $sAllReplicas, $sAllErrors, $sAllWarnings).'</h2>');
			}

			$oPage->add('<table class="synoptics"><tr><td style="color:#333;vertical-align:top">');

			// List all the log entries for the user to select
			$oPage->add('<h2 style="line-height:55px;">'.Dict::S('Core:Synchro:History').'</h2>');
			$oSetSynchroLog->Rewind();
			$oPage->add('<select size="25" onChange="UpdateSynoptics(this.value);">');
			$sSelected = ' selected'; // First log is selected by default
			$sScript = "var aSynchroLog = {\n";
			while($oLog = $oSetSynchroLog->Fetch())
			{
				$sLogTitle = Dict::Format('Core:SynchroLogTitle', $oLog->Get('status'), $oLog->Get('start_date'));
				$oPage->add('<option value="'.$oLog->GetKey().'"'.$sSelected.'>'.$sLogTitle.'</option>');
				$sSelected = ''; // only the first log is selected by default
				$aData = $this->ProcessLog($oLog);
				$sScript .= '"'.$oLog->GetKey().'": '.json_encode($aData).",\n";
			}
			$sScript .= "end: 'Done'";
			$sScript .= "};\n";
			$sScript .= <<<EOF
			var sLastLog = '$iLastLog';
	function ToggleSynoptics(sId, bShow)
	{
		if (bShow)
		{
			$(sId).show();
		}
		else
		{
			$(sId).hide();
		}
	}
	
	function UpdateSynoptics(id)
	{
		var aValues = aSynchroLog[id];
		if (aValues == undefined) return;
		
		for (var sKey in aValues)
		{
			$('#c_'+sKey).html(aValues[sKey]);
			var fOpacity = (aValues[sKey] == 0) ? 0.3 : 1;
			$('#'+sKey).fadeTo("slow", fOpacity);
		}
		//alert('id = '+id+', lastLog='+sLastLog+', id==sLastLog: '+(id==sLastLog)+' obj_updated_errors:  '+aValues['obj_updated_errors']);
		if ( (id == sLastLog) && (aValues['obj_new_errors'] > 0) )
		{
			$('#new_errors_link').show();
		}
		else
		{
			$('#new_errors_link').hide();
		}
		
		if ( (id == sLastLog) && (aValues['obj_updated_errors'] > 0) )
		{
			$('#updated_errors_link').show();
		}
		else
		{
			$('#updated_errors_link').hide();
		}
		
		if ( (id == sLastLog) && (aValues['obj_disappeared_errors'] > 0) )
		{
			$('#disappeared_errors_link').show();
		}
		else
		{
			$('#disappeared_errors_link').hide();
		}
		
		ToggleSynoptics('#cw_obj_created_warnings', aValues['obj_created_warnings'] > 0);
		ToggleSynoptics('#cw_obj_new_updated_warnings', aValues['obj_new_updated_warnings'] > 0);
		ToggleSynoptics('#cw_obj_new_unchanged_warnings', aValues['obj_new_unchanged_warnings'] > 0);
		ToggleSynoptics('#cw_obj_updated_warnings', aValues['obj_updated_warnings'] > 0);
		ToggleSynoptics('#cw_obj_unchanged_warnings', aValues['obj_unchanged_warnings'] > 0);
	}
EOF
;
			$oPage->add_script($sScript);
			$oPage->add('</select>');
			
			$oPage->add('</td><td style="vertical-align:top;">');
			
			// Now build the big "synoptics" view
			$aData = $this->ProcessLog($oLastLog);

			$sNbReplica = $this->GetIcon()."&nbsp;".Dict::Format('Core:Synchro:Nb_Replica', "<span id=\"c_nb_replica_total\">{$aData['nb_replica_total']}</span>");
			$sNbObjects = MetaModel::GetClassIcon($this->GetTargetClass())."&nbsp;".Dict::Format('Core:Synchro:Nb_Class:Objects', $this->GetTargetClass(), "<span id=\"c_nb_obj_total\">{$aData['nb_obj_total']}</span>");
			$oPage->add(
<<<EOF
	<table class="synoptics">
	<tr class="synoptics_header">
	<td>$sNbReplica</td><td>&nbsp;</td><td>$sNbObjects</td>
	</tr>
	<tr>
EOF
);
			$sBaseOQL = "SELECT SynchroReplica WHERE sync_source_id=".$this->GetKey()." AND status_last_error!=''";
			$oPage->add($this->HtmlBox('repl_ignored', $aData, '#999').'<td colspan="2">&nbsp;</td>');
			$oPage->add("</tr>\n<tr>");
			$oPage->add($this->HtmlBox('repl_disappeared', $aData, '#630', 'rowspan="4"').'<td rowspan="4" class="arrow">=&gt;</td>'.$this->HtmlBox('obj_disappeared_no_action', $aData, '#333'));
			$oPage->add("</tr>\n<tr>");
			$oPage->add($this->HtmlBox('obj_deleted', $aData, '#000'));
			$oPage->add("</tr>\n<tr>");
			$oPage->add($this->HtmlBox('obj_obsoleted', $aData, '#630'));
			$oPage->add("</tr>\n<tr>");
			$sOQL = urlencode($sBaseOQL." AND status='obsolete'");
			$oPage->add($this->HtmlBox('obj_disappeared_errors', $aData, '#C00', '', " <a style=\"color:#fff\" href=\"../synchro/replica.php?operation=oql&datasource=$iDSid&oql=$sOQL\" id=\"disappeared_errors_link\">Show</a>"));
			$oPage->add("</tr>\n<tr>");
			$oPage->add($this->HtmlBox('repl_existing', $aData, '#093', 'rowspan="3"').'<td rowspan="3" class="arrow">=&gt;</td>'.$this->HtmlBox('obj_unchanged', $aData, '#393'));
			$oPage->add("</tr>\n<tr>");
			$oPage->add($this->HtmlBox('obj_updated', $aData, '#3C3'));
			$oPage->add("</tr>\n<tr>");
			$sOQL = urlencode($sBaseOQL." AND status='modified'");
			$oPage->add($this->HtmlBox('obj_updated_errors', $aData, '#C00', '', " <a style=\"color:#fff\" href=\"../synchro/replica.php?operation=oql&datasource=$iDSid&oql=$sOQL\" id=\"updated_errors_link\">Show</a>"));
			$oPage->add("</tr>\n<tr>");
			$oPage->add($this->HtmlBox('repl_new', $aData, '#339', 'rowspan="4"').'<td rowspan="4" class="arrow">=&gt;</td>'.$this->HtmlBox('obj_new_unchanged', $aData, '#393'));
			$oPage->add("</tr>\n<tr>");
			$oPage->add($this->HtmlBox('obj_new_updated', $aData, '#3C3'));
			$oPage->add("</tr>\n<tr>");
			$oPage->add($this->HtmlBox('obj_created', $aData, '#339'));
			$oPage->add("</tr>\n<tr>");
			$sOQL = urlencode($sBaseOQL." AND status='new'");
			$oPage->add($this->HtmlBox('obj_new_errors', $aData, '#C00', '', " <a style=\"color:#fff\" href=\"../synchro/replica.php?operation=oql&datasource=$iDSid&oql=$sOQL\" id=\"new_errors_link\">Show</a>"));
			$oPage->add("</tr>\n</table>\n");
			$oPage->add('</td></tr></table>');
			$oPage->add_ready_script("UpdateSynoptics('$iLastLog')");
		}
		else
		{
			$oPage->p('<h2>'.Dict::S('Core:Synchro:NeverRun').'</h2>');
		}
	}
	
	protected function HtmlBox($sId, $aData, $sColor, $sHTMLAttribs = '', $sErrorLink = '')
	{
		$iCount = $aData[$sId];
		$sCount = "<span id=\"c_{$sId}\">$iCount</span>";
		$sLabel = Dict::Format('Core:Synchro:label_'.$sId, $sCount);
		$sOpacity = ($iCount==0) ? "opacity:0.3;" : "";
		if (isset($aData[$sId.'_warnings']))
		{
			$sLabel .= " <span id=\"cw_{$sId}_warnings\"><img src=\"../images/error.png\" style=\"vertical-align:middle\"/>  (<span id=\"c_{$sId}_warnings\">".$aData[$sId.'_warnings']."</span>)</span>";
		}

		return "<td id=\"$sId\" style=\"background-color:$sColor;$sOpacity;\" {$sHTMLAttribs}>{$sLabel}{$sErrorLink}</td>";
	}
	
	protected function ProcessLog($oLastLog)
	{
		$aData = array(
			'obj_deleted' => $oLastLog->Get('stats_nb_obj_deleted'),
			'obj_obsoleted' => $oLastLog->Get('stats_nb_obj_obsoleted'),
			'obj_disappeared_errors' => $oLastLog->Get('stats_nb_obj_obsoleted_errors') + $oLastLog->Get('stats_nb_obj_deleted_errors'),
			'obj_disappeared_no_action' => $oLastLog->Get('stats_nb_replica_disappeared_no_action'),
			'obj_updated' => $oLastLog->Get('stats_nb_obj_updated'),
			'obj_updated_warnings' => $oLastLog->Get('stats_nb_obj_updated_warnings'),
			'obj_updated_errors' => $oLastLog->Get('stats_nb_obj_updated_errors'),
			'obj_new_updated' => $oLastLog->Get('stats_nb_obj_new_updated'),
			'obj_new_updated_warnings' => $oLastLog->Get('stats_nb_obj_new_updated_warnings'),
			'obj_new_unchanged' => $oLastLog->Get('stats_nb_obj_new_unchanged'),
			'obj_created' => $oLastLog->Get('stats_nb_obj_created'),
			'obj_created_warnings' => $oLastLog->Get('stats_nb_obj_created_warnings'),
			'obj_created_errors' => $oLastLog->Get('stats_nb_obj_created_errors'),
			'obj_unchanged_warnings' => $oLastLog->Get('stats_nb_obj_unchanged_warnings'),
		);
		$iReconciledErrors = $oLastLog->Get('stats_nb_replica_reconciled_errors');
		$iDisappeared = $aData['obj_disappeared_errors'] + $aData['obj_obsoleted'] + $aData['obj_deleted'] + $aData['obj_disappeared_no_action'];
		$aData['repl_disappeared'] = $iDisappeared;
		$iNewErrors = $aData['obj_created_errors'] + $oLastLog->Get('stats_nb_replica_reconciled_errors');
		$aData['obj_new_errors'] = $iNewErrors;
		$iNew = $aData['obj_created'] + $iNewErrors + $aData['obj_new_updated'] + $aData['obj_new_unchanged'];
		$aData['repl_new'] = $iNew;
		$iExisting = $oLastLog->Get('stats_nb_replica_seen') - $iNew;
		$aData['repl_existing'] = $iExisting;
		$aData['obj_unchanged'] = $iExisting - $aData['obj_updated'] - $aData['obj_updated_errors'];
		$iIgnored = $oLastLog->Get('stats_nb_replica_total') - $iNew - $iExisting - $iDisappeared;
		$aData['repl_ignored'] = $iIgnored;
		$aData['nb_obj_total'] = $iNew + $iExisting + $iDisappeared;
		$aData['nb_replica_total'] = $aData['nb_obj_total'] + $iIgnored;
		return $aData;
	}
	
	public function GetIcon($bImgTag = true, $sMoreStyles = '')
	{
		if ($this->Get('url_icon') == '') return MetaModel::GetClassIcon(get_class($this), $bImgTag);
		if ($bImgTag)
		{
			return 	"<img src=\"".$this->Get('url_icon')."\" style=\"vertical-align:middle;$sMoreStyles\"/>";
			
		}
		return $this->Get('url_icon');
	}
	
	/**
	 * Get the actual hyperlink to the remote application for the given replica and dest object
	 */
	public function GetApplicationUrl(DBObject $oDestObj, SynchroReplica $oReplica)
	{
		if ($this->Get('url_application') == '') return '';
		$aSearches = array();
		$aReplacements = array();
		foreach(MetaModel::ListAttributeDefs($this->GetTargetClass()) as $sAttCode=>$oAttDef)
		{
			if ($oAttDef->IsScalar())
			{
				$aSearches[] = '$this->'.$sAttCode.'$';
				$aReplacements[] = $oDestObj->Get($sAttCode);
			}
		}
		$aData = $oReplica->LoadExtendedDataFromTable($this->GetDataTable());

		foreach($aData as $sColumn => $value)
		{
			$aSearches[] = '$replica->'.$sColumn.'$';
			$aReplacements[] = $value;
		}
		return str_replace($aSearches, $aReplacements, $this->Get('url_application'));
	}
	
	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		if ( (($sAttCode == 'scope_class') || ($sAttCode == 'database_table_name')) && (!$this->IsNew()))
		{
			return OPT_ATT_READONLY;
		}
		return parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
	}
		
	public function UpdateObjectFromPostedForm($sFormPrefix = '', $sAttList = null, $sTargetState = '')
	{
		parent::UpdateObjectFromPostedForm($sFormPrefix, $sAttList, $sTargetState);
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
		$aReconciliation = utils::ReadPostedParam('attr_reconciliation_attcode', array());
		// update_policy cannot be empty, so there is one entry per attribute, use this to iterate
		// through all the writable attributes
		foreach($aUpdatePolicy as $sAttCode => $sValue)
		{
			if(!isset($aAttributes[$sAttCode]))
			{
				$oAttribute = $this->CreateSynchroAtt($sAttCode);
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
			if ($oAttribute instanceof SynchroAttExtKey)
			{
				$oAttribute->Set('reconciliation_attcode', $aReconciliation[$sAttCode]);
			}
			elseif ($oAttribute instanceof SynchroAttLinkSet)
			{
			}
			$oAttributeSet->AddObject($oAttribute);
		}
		$this->Set('attribute_list', $oAttributeSet);
	}
	
	/**
	 * Creates a new SynchroAttXXX object in memory with the default values
	 */
	protected function CreateSynchroAtt($sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef($this->GetTargetClass(), $sAttCode);
		if ($oAttDef->IsExternalKey())
		{
			$oAttribute = new SynchroAttExtKey();
			$oAttribute->Set('reconciliation_attcode', ''); // Blank means by pkey
		}
		elseif ($oAttDef->IsLinkSet() && $oAttDef->IsIndirect())
		{
			$oAttribute = new SynchroAttLinkSet();
			// Todo - set those value from the form
			$oAttribute->Set('row_separator', MetaModel::GetConfig()->Get('link_set_item_separator'));
			$oAttribute->Set('attribute_separator', MetaModel::GetConfig()->Get('link_set_attribute_separator'));
			$oAttribute->Set('value_separator', MetaModel::GetConfig()->Get('link_set_value_separator'));
			$oAttribute->Set('attribute_qualifier', MetaModel::GetConfig()->Get('link_set_attribute_qualifier'));
		}
		else
		{
			$oAttribute = new SynchroAttribute();
		}
		$oAttribute->Set('sync_source_id', $this->GetKey());
		$oAttribute->Set('attcode', $sAttCode);
		$oAttribute->Set('reconcile', 0);
		$oAttribute->Set('update', 0);
		$oAttribute->Set('update_policy', 'master_locked');
		return $oAttribute;
	}
	/**
	 * Overload the standard behavior
	 */	
	public function ComputeValues()
	{
		parent::ComputeValues();

		if ($this->IsNew())
		{
			// Compute the database_table_name
			$sDataTable = $this->Get('database_table_name');
			if (!empty($sDataTable))
			{
				$this->Set('database_table_name', $this->ComputeDataTableName());
			}
			
			// When inserting a new datasource object, also create the SynchroAttribute objects
			// for each field of the target class
			// Create all the SynchroAttribute records
			$oAttributeSet = $this->Get('attribute_list');
			if ($oAttributeSet->Count() == 0)
			{
				foreach(MetaModel::ListAttributeDefs($this->GetTargetClass()) as $sAttCode=>$oAttDef)
				{
					if ($oAttDef->IsWritable())
					{
						$oAttDef = MetaModel::GetAttributeDef($this->GetTargetClass(), $sAttCode);
						if ($oAttDef->IsExternalKey())
						{
							$oAttribute = new SynchroAttExtKey();
							$oAttribute->Set('reconciliation_attcode', ''); // Blank means by pkey
						}
						elseif ($oAttDef->IsLinkSet() && $oAttDef->IsIndirect())
						{
							$oAttribute = new SynchroAttLinkSet();
							// Todo - set those value from the form
							$oAttribute->Set('row_separator', MetaModel::GetConfig()->Get('link_set_item_separator'));
							$oAttribute->Set('attribute_separator', MetaModel::GetConfig()->Get('link_set_attribute_separator'));
							$oAttribute->Set('value_separator', MetaModel::GetConfig()->Get('link_set_value_separator'));
							$oAttribute->Set('attribute_qualifier', MetaModel::GetConfig()->Get('link_set_attribute_qualifier'));
						}
						elseif ($oAttDef->IsScalar())
						{
							$oAttribute = new SynchroAttribute();
						}
						else
						{
							$oAttribute = null;
						}

						if (!is_null($oAttribute))
						{
							$oAttribute->Set('sync_source_id', $this->GetKey());
							$oAttribute->Set('attcode', $sAttCode);
							$oAttribute->Set('reconcile', MetaModel::IsReconcKey($this->GetTargetClass(), $sAttCode) ? 1 : 0);
							$oAttribute->Set('update', 1);
							$oAttribute->Set('update_policy', 'master_locked');
							$oAttributeSet->AddObject($oAttribute);
						}
					}
				}
				$this->Set('attribute_list', $oAttributeSet);
			}
		}
		else
		{
			$sDataTable = $this->Get('database_table_name');
			if (empty($sDataTable))
			{
				$this->Set('database_table_name', $this->ComputeDataTableName());
			}
		}
	}
	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();

		// Check that there is at least one reconciliation key defined
		if ($this->Get('reconciliation_policy') == 'use_attributes')
		{
			$oSet = $this->Get('attribute_list');
			$oSynchroAttributeList = $oSet->ToArray();
			$bReconciliationKey = false;
			foreach($oSynchroAttributeList as $oSynchroAttribute)
			{
				if ($oSynchroAttribute->Get('reconcile') == 1)
				{
					$bReconciliationKey = true; // At least one key is defined
					break;
				}
			}
			if (!$bReconciliationKey)
			{
				$this->m_aCheckIssues[] = Dict::Format('Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified');			
			}
		}
		
		// If 'update_then_delete' is specified there must be a delete_retention_period
		if (($this->Get('delete_policy') == 'update_then_delete') && ($this->Get('delete_policy_retention') == 0))
		{
			$this->m_aCheckIssues[] = Dict::Format('Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified');			
		}

		// If update is specified, then something to update must be defined
		if ((($this->Get('delete_policy') == 'update_then_delete') ||  ($this->Get('delete_policy') == 'update'))
		    && ($this->Get('delete_policy_update') == ''))
		{
			$this->m_aCheckIssues[] = Dict::Format('Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified');			
		}
		
		// When creating the data source with a specified database_table_name, this table must NOT exist
		if ($this->IsNew())
		{
			$sDataTable = $this->GetDataTable();
			if (!empty($sDataTable) && CMDBSource::IsTable($this->GetDataTable()))
			{
				// Hmm, the synchro_data_xxx table already exists !!
				$this->m_aCheckIssues[] = Dict::Format('Class:SynchroDataSource/Error:DataTableAlreadyExists', $this->GetDataTable());			
			}
		}
	}
	
	public function GetTargetClass()
	{
		return $this->Get('scope_class');
	}

	public function GetDataTable()
	{
		$sTable = $this->Get('database_table_name');
		if (empty($sTable))
		{
			$sTable = $this->ComputeDataTableName();
		}
		return $sTable;
	}
	
	protected function ComputeDataTableName()
	{
		$sDBTableName = $this->Get('database_table_name');
		if (empty($sDBTableName))
		{
			$sDBTableName = strtolower($this->GetTargetClass());
			$sDBTableName = preg_replace('/[^A-za-z0-9_]/', '_', $sDBTableName); // Remove forbidden characters from the table name
			$sDBTableName .= '_'.$this->GetKey(); // Add a suffix for unicity
		}
		else
		{
			$sDBTableName = preg_replace('/[^A-za-z0-9_]/', '_', $sDBTableName); // Remove forbidden characters from the table name
		}
		$sPrefix = MetaModel::GetConfig()->GetDBSubName()."synchro_data_";
		if (strpos($sDBTableName, $sPrefix) !== 0)
		{
			$sDBTableName = $sPrefix.$sDBTableName;
		}
		return $sDBTableName;		
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
		$sReplicaTable = MetaModel::DBGetTable('SynchroReplica');

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

		$sCreateTable = "CREATE TABLE `$sTable` ($sFieldDefs) ENGINE = ".MYSQL_ENGINE." CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		CMDBSource::Query($sCreateTable);

		$aTriggers = $this->GetTriggersDefinition();
		foreach($aTriggers as $key => $sTriggerSQL)
		{
			CMDBSource::Query($sTriggerSQL);
		}
		
		$sDataTable = $this->Get('database_table_name');
		if (empty($sDataTable))
		{
			$this->Set('database_table_name', $this->ComputeDataTableName());
			$this->DBUpdate();
		}
		
	}

	/**
	 * Gets the definitions of the 3 triggers: before insert, before update and after delete
	 * @return array An array with 3 entries, one for each of the SQL queries
	 */
	protected function GetTriggersDefinition()
	{
		$sTable = $this->GetDataTable();
		$sReplicaTable = MetaModel::DBGetTable('SynchroReplica');
		$aColumns = $this->GetSQLColumns();
		$aResult = array();

		$sTriggerInsert = "CREATE TRIGGER `{$sTable}_bi` BEFORE INSERT ON `$sTable`";
		$sTriggerInsert .= "   FOR EACH ROW";
		$sTriggerInsert .= "   BEGIN";
		$sTriggerInsert .= "      INSERT INTO `{$sReplicaTable}` (`sync_source_id`, `status_last_seen`, `status`) VALUES ({$this->GetKey()}, NOW(), 'new');";
		$sTriggerInsert .= "      SET NEW.id = LAST_INSERT_ID();";
		$sTriggerInsert .= "   END;";
		$aResult['bi'] = $sTriggerInsert;

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
		$sTriggerUpdate = "CREATE TRIGGER `{$sTable}_bu` BEFORE UPDATE ON `$sTable`";
		$sTriggerUpdate .= "   FOR EACH ROW";
		$sTriggerUpdate .= "   BEGIN";
		$sTriggerUpdate .= "      IF @itopuser is null THEN";
		$sTriggerUpdate .= "         UPDATE `{$sReplicaTable}` SET status_last_seen = NOW(), `status` = IF(`status` = 'obsolete', IF(`dest_id` IS NULL, 'new', 'modified'), IF(`status` IN ('synchronized') AND ($sIsModified), 'modified', `status`)) WHERE sync_source_id = {$this->GetKey()} AND id = OLD.id;";
		$sTriggerUpdate .= "         SET NEW.id = OLD.id;"; // make sure this id won't change
		$sTriggerUpdate .= "      END IF;";
		$sTriggerUpdate .= "   END;";
		$aResult['bu'] = $sTriggerUpdate;

		$sTriggerDelete = "CREATE TRIGGER `{$sTable}_ad` AFTER DELETE ON `$sTable`";
		$sTriggerDelete .= "   FOR EACH ROW";
		$sTriggerDelete .= "   BEGIN";
		$sTriggerDelete .= "      DELETE FROM `{$sReplicaTable}` WHERE id = OLD.id;";
		$sTriggerDelete .= "   END;";
		$aResult['ad'] = $sTriggerDelete;
		return $aResult;
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
	 * Checks if the data source definition is consistent with the schema of the target class
	 * @param $bDiagnostics boolean True to only diagnose the consistency, false to actually apply some changes
	 * @param $bVerbose boolean True to get some information in the std output (echo)
	 * @return bool Whether or not the database needs fixing for this data source
	 */
	public function CheckDBConsistency($bDiagnostics, $bVerbose, $oChange = null)
	{
		$bFixNeeded = false;
		$bTriggerRebuildNeeded = false;
		$aMissingFields = array();
		$oAttributeSet = $this->Get('attribute_list');
		$aAttributes = array();

		while($oAttribute = $oAttributeSet->Fetch())
		{
			$sAttCode = $oAttribute->Get('attcode');
			if (MetaModel::IsValidAttCode($this->GetTargetClass(), $sAttCode))
			{ 
				$aAttributes[$sAttCode] = $oAttribute;
			}
			else
			{
				// Old field remaining
				if ($bVerbose)
				{
					echo "Irrelevant field description for the field '$sAttCode', for the data synchro task ".$this->GetName()." (".$this->GetKey()."), will be removed.\n";
				}
				$bFixNeeded = true;
				if (!$bDiagnostics)
				{
					// Fix the issue
					$oAttribute->DBDelete();
				}
			}
		}

		$sTable = $this->GetDataTable();
		foreach($this->ListTargetAttributes() as $sAttCode=>$oAttDef)
		{
			if (!isset($aAttributes[$sAttCode]))
			{
				$bFixNeeded = true;
				$aMissingFields[] = $sAttCode;
				// New field missing...
				if ($bVerbose)
				{
					echo "Missing field description for the field '$sAttCode', for the data synchro task ".$this->GetName()." (".$this->GetKey()."), will be created with default values.\n";
				}
				if (!$bDiagnostics)
				{
					// Fix the issue
					$oAttribute = $this->CreateSynchroAtt($sAttCode);
					$oAttribute->DBInsert();
				}
			}
			else
			{
				$aColumns = $this->GetSQLColumns(array($sAttCode));
				foreach($aColumns as $sColName => $sColumnDef)
				{
					$bOneColIsMissing = false;
					if (!CMDBSource::IsField($sTable, $sColName))
					{
						$bFixNeeded = true;
						$bOneColIsMissing = true;
						if ($bVerbose)
						{
							if (count($aColumns) > 1)
							{
								echo "Missing column '$sColName', in the table '$sTable' for the data synchro task ".$this->GetName()." (".$this->GetKey()."). The columns '".implode("', '", $aColumns )." will be re-created.'.\n";
							}
							else
							{
								echo "Missing column '$sColName', in the table '$sTable' for the data synchro task ".$this->GetName()." (".$this->GetKey()."). The column '$sColName' will be added.\n";
							}
						}
					}
					else if (strcasecmp(CMDBSource::GetFieldType($sTable, $sColName), $sColumnDef) != 0)
					{
						$bFixNeeded = true;
						$bOneColIsMissing = true;
						if (count($aColumns) > 1)
						{
							echo "Incorrect column '$sColName' (".CMDBSource::GetFieldType($sTable, $sColName)." instead of ".$sColumnDef."), in the table '$sTable' for the data synchro task ".$this->GetName()." (".$this->GetKey()."). The columns '".implode("', '", $aColumns )." will be re-created.'.\n";
						}
						else
						{
							echo "Incorrect column '$sColName' (".CMDBSource::GetFieldType($sTable, $sColName)." instead of ".$sColumnDef."), in the table '$sTable' for the data synchro task ".$this->GetName()." (".$this->GetKey()."). The column '$sColName' will be added.\n";
						}
					}
					if ($bOneColIsMissing)
					{
						$bTriggerRebuildNeeded = true;
						$aMissingFields[] = $sAttCode;
					}
				}
			}
		}

		$sDBName = MetaModel::GetConfig()->GetDBName();
		try
		{
			// Note: as per the MySQL documentation, using information_schema behaves exactly like SHOW TRIGGERS (user privileges)
			//       and this is in fact the recommended way for better portability
			$iTriggerCount = CMDBSource::QueryToScalar("select count(*) from information_schema.triggers where EVENT_OBJECT_SCHEMA='$sDBName' and EVENT_OBJECT_TABLE='$sTable'");
		}
		catch (Exception $e)
		{
			if ($bVerbose)
			{
				echo "Failed to investigate on the synchro triggers (skipping the check): ".$e->getMessage().".\n";
			}
			// Ignore this error: consider that the trigger are there
			$iTriggerCount = 3;
		}
		if ($iTriggerCount < 3)
		{
			$bFixNeeded = true;
			$bTriggerRebuildNeeded = true;
			if ($bVerbose)
			{
				echo "Missing trigger(s) for the data synchro task ".$this->GetName()." (table {$sTable}).\n";
			}
		}

		$aRepairQueries = array();

		if (count($aMissingFields) > 0)
		{
			// The structure of the table needs adjusting
			$aColumns = $this->GetSQLColumns($aMissingFields);
			$aFieldDefs = array();
			foreach($aColumns as $sAttCode => $sColumnDef)
			{
				if (CMDBSource::IsField($sTable, $sAttCode))
				{
					$aRepairQueries[] = "ALTER TABLE `$sTable` CHANGE `$sAttCode` `$sAttCode` $sColumnDef";
				}
				else
				{
					$aFieldDefs[] = "`$sAttCode` $sColumnDef";
				}

			}
			if (count($aFieldDefs) > 0)
			{
				$aRepairQueries[] = "ALTER TABLE `$sTable` ADD (".implode(',', $aFieldDefs).");";
			}

			if ($bDiagnostics)
			{
				if ($bVerbose)
				{
					echo "The structure of the table $sTable for the data synchro task ".$this->GetName()." (".$this->GetKey().") must be altered (missing or incorrect fields: ".implode(',', $aMissingFields).").\n";
				}
			}
		}

		// Repair the triggers
		// Must be done after updating the columns because MySQL does check the validity of the query found into the procedure!
		if ($bTriggerRebuildNeeded)
		{
			// The triggers as well must be adjusted
			$aTriggersDefs = $this->GetTriggersDefinition();
			$aTriggerRepair = array();
			$aTriggerRepair[] = "DROP TRIGGER IF EXISTS `{$sTable}_bi`;";
			$aTriggerRepair[] = $aTriggersDefs['bi'];
			$aTriggerRepair[] = "DROP TRIGGER IF EXISTS `{$sTable}_bu`;";
			$aTriggerRepair[] = $aTriggersDefs['bu'];
			$aTriggerRepair[] = "DROP TRIGGER IF EXISTS `{$sTable}_ad`;";
			$aTriggerRepair[] = $aTriggersDefs['ad'];
			
			if ($bDiagnostics)
			{
				if ($bVerbose)
				{
					echo "The triggers {$sTable}_bi, {$sTable}_bu, {$sTable}_ad for the data synchro task ".$this->GetName()." (".$this->GetKey().") must be re-created.\n";
					echo implode("\n", $aTriggerRepair)."\n";
				}
			}
			$aRepairQueries = array_merge($aRepairQueries, $aTriggerRepair); // The order matters!
		}

		// Execute the repair statements
		//
		if (!$bDiagnostics && (count($aRepairQueries) > 0))
		{
			// Fix the issue
			foreach($aRepairQueries as $sSQL)
			{
				CMDBSource::Query($sSQL);
				if ($bVerbose)
				{
					echo "$sSQL\n";
				}
			}
		}
		return $bFixNeeded;
	}

	public function SendNotification($sSubject, $sBody)
	{
		$iContact = $this->Get('notify_contact_id');
		if ($iContact == 0)
		{
			// Leave silently...
			return;
		}
		$oContact = MetaModel::GetObject('Contact', $iContact);

		// Determine the email attribute (the first one will be our choice)
		$sEmailAttCode = null;
		foreach (MetaModel::ListAttributeDefs(get_class($oContact)) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeEmailAddress)
			{
				$sEmailAttCode = $sAttCode;
				// we've got one, exit the loop
				break;
			}
		}
		if (is_null($sEmailAttCode))
		{
			// Leave silently...
			return;
		}

		$sTo = $oContact->Get($sEmailAttCode);
		$sFrom = $sTo;
		$sBody = '<p>Data synchronization: '.$this->GetHyperlink().'</p>'.$sBody;

		$sSubject = 'iTop Data Sync - '.$this->GetName().' - '.$sSubject;

		$oEmail = new Email();
		$oEmail->SetRecipientTO($sTo);
		$oEmail->SetRecipientFrom($sFrom);
		$oEmail->SetSubject($sSubject);
		$oEmail->SetBody($sBody);
		if ($oEmail->Send($aIssues) == EMAIL_SEND_ERROR)
		{
			// mmmm, what can I do?
		}
	}

	/**
	 * Get the list of attributes eligible to the synchronization	 
	 */
	public function ListTargetAttributes()
	{
		$aRet = array();
		foreach(MetaModel::ListAttributeDefs($this->GetTargetClass()) as $sAttCode => $oAttDef)
		{
			if ($sAttCode == 'finalclass') continue;
			if (!$oAttDef->IsWritable()) continue;
			if ($oAttDef->IsLinkSet() && !$oAttDef->IsIndirect()) continue;

			$aRet[$sAttCode] = $oAttDef;
		}
		return $aRet;
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
			foreach($this->ListTargetAttributes() as $sAttCode => $oAttDef)
			{
				$aAttributeCodes[] = $sAttCode;
			}
		}

		foreach($aAttributeCodes as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			
			if ($oAttDef->IsExternalKey())
			{
				// The pkey might be used as well as any other key column
				$aColumns[$sAttCode] = 'VARCHAR(255)';
			}
			else
			{
				foreach($oAttDef->GetImportColumns() as $sField => $sDBFieldType)
				{
					$aColumns[$sField] = $sDBFieldType;
				}
			}
		}
		return $aColumns;
	}
	
	/**
	 * Get the list of Date and Datetime SQL columns
	 */
	public function GetDateSQLColumns()
	{
		$aDateAttributes = array();
		
		$sClass = $this->GetTargetClass();
		foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeDateTime)
			{
				$aDateAttributes[] = $sAttCode;
			}
		}
		return $this->GetSQLColumns($aDateAttributes);
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
			"name_attcode" => "attcode",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_sync_att",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("sync_source_id", array("targetclass"=>"SynchroDataSource", "jointype"=> "", "allowed_values"=>null, "sql"=>"sync_source_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_SILENT, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("sync_source_name", array("allowed_values"=>null, "extkey_attcode"=> 'sync_source_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("attcode", array("allowed_values"=>null, "sql"=>"attcode", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("update", array("allowed_values"=>null, "sql"=>"update", "default_value"=>true, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("reconcile", array("allowed_values"=>null, "sql"=>"reconcile", "default_value"=>false, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("update_policy", array("allowed_values"=>new ValueSetEnum('master_locked,master_unlocked,write_if_empty'), "sql"=>"update_policy", "default_value"=>"master_locked", "is_null_allowed"=>false, "depends_on"=>array())));

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
			"name_attcode" => "attcode",
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
	
	public function GetReconciliationFormElement($sTargetClass, $sFieldName)
	{
		$sHtml = "<select name=\"$sFieldName\">\n";
		$sSelected = (''== $this->Get('reconciliation_attcode')) ? ' selected' : '';
		$sHtml .= "<option value=\"\" $sSelected>".Dict::S('Core:SynchroAttExtKey:ReconciliationById')."</option>\n";
		foreach(MetaModel::ListAttributeDefs($sTargetClass) as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsScalar())
			{
				$sSelected = ($sAttCode == $this->Get('reconciliation_attcode')) ? ' selected' : '';
				$sHtml .= "<option value=\"$sAttCode\" $sSelected>".MetaModel::GetLabel($sTargetClass, $sAttCode)."</option>\n";	
			}
		}
		$sHtml .= "</select>\n";
		return $sHtml;
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
			"name_attcode" => "attcode",
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
		MetaModel::Init_AddAttribute(new AttributeString("value_separator", array("allowed_values"=>null, "sql"=>"value_separator", "default_value"=>':', "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attribute_qualifier", array("allowed_values"=>null, "sql"=>"attribute_qualifier", "default_value"=>'\'', "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'attcode', 'update', 'reconcile', 'update_policy', 'row_separator', 'attribute_separator', 'value_separator', 'attribute_qualifier')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'attcode', 'update', 'reconcile', 'update_policy')); // Attributes to be displayed for a list

		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

}

//class SynchroLog extends Event
class SynchroLog extends DBObject
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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("sync_source_id", array("targetclass"=>"SynchroDataSource", "jointype"=> "", "allowed_values"=>null, "sql"=>"sync_source_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_SILENT, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("start_date", array("allowed_values"=>null, "sql"=>"start_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("end_date", array("allowed_values"=>null, "sql"=>"end_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('running,completed,error'), "sql"=>"status", "default_value"=>"running", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("status_curr_job", array("allowed_values"=>null, "sql"=>"status_curr_job", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("status_curr_pos", array("allowed_values"=>null, "sql"=>"status_curr_pos", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_replica_seen", array("allowed_values"=>null, "sql"=>"stats_nb_replica_seen", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_replica_total", array("allowed_values"=>null, "sql"=>"stats_nb_replica_total", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_deleted", array("allowed_values"=>null, "sql"=>"stats_nb_obj_deleted", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_deleted_errors", array("allowed_values"=>null, "sql"=>"stats_deleted_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_obsoleted", array("allowed_values"=>null, "sql"=>"stats_nb_obj_obsoleted", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_obsoleted_errors", array("allowed_values"=>null, "sql"=>"stats_nb_obj_obsoleted_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_created", array("allowed_values"=>null, "sql"=>"stats_nb_obj_created", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_created_errors", array("allowed_values"=>null, "sql"=>"stats_nb_obj_created_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_created_warnings", array("allowed_values"=>null, "sql"=>"stats_nb_obj_created_warnings", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_updated", array("allowed_values"=>null, "sql"=>"stats_nb_obj_updated", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_updated_errors", array("allowed_values"=>null, "sql"=>"stats_nb_obj_updated_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_updated_warnings", array("allowed_values"=>null, "sql"=>"stats_nb_obj_updated_warnings", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_unchanged_warnings", array("allowed_values"=>null, "sql"=>"stats_nb_obj_unchanged_warnings", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		//		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_replica_reconciled", array("allowed_values"=>null, "sql"=>"stats_nb_replica_reconciled", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_replica_reconciled_errors", array("allowed_values"=>null, "sql"=>"stats_nb_replica_reconciled_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_replica_disappeared_no_action", array("allowed_values"=>null, "sql"=>"stats_nb_replica_disappeared_no_action", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_new_updated", array("allowed_values"=>null, "sql"=>"stats_nb_obj_new_updated", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_new_updated_warnings", array("allowed_values"=>null, "sql"=>"stats_nb_obj_new_updated_warnings", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_new_unchanged", array("allowed_values"=>null, "sql"=>"stats_nb_obj_new_unchanged", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_new_unchanged_warnings", array("allowed_values"=>null, "sql"=>"stats_nb_obj_new_unchanged_warnings", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeText("last_error", array("allowed_values"=>null, "sql"=>"last_error", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLongText("traces", array("allowed_values"=>null, "sql"=>"traces", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeInteger("memory_usage_peak", array("allowed_values"=>null, "sql"=>"memory_usage_peak", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'start_date', 'end_date', 'status', 'stats_nb_replica_total', 'stats_nb_replica_seen', 'stats_nb_obj_created', /*'stats_nb_replica_reconciled',*/ 'stats_nb_obj_updated', 'stats_nb_obj_obsoleted', 'stats_nb_obj_deleted',
														'stats_nb_obj_created_errors', 'stats_nb_replica_reconciled_errors', 'stats_nb_replica_disappeared_no_action', 'stats_nb_obj_updated_errors', 'stats_nb_obj_obsoleted_errors', 'stats_nb_obj_deleted_errors', 'stats_nb_obj_new_unchanged', 'stats_nb_obj_new_updated', 'traces')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'start_date', 'end_date', 'status', 'stats_nb_replica_seen')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
	
	/**
	 * Helper
	 */
	 function GetErrorCount()
	 {
	 	return $this->Get('stats_nb_obj_deleted_errors')
	 			+ $this->Get('stats_nb_obj_obsoleted_errors')
	 			+ $this->Get('stats_nb_obj_created_errors')
	 			+ $this->Get('stats_nb_obj_updated_errors')
	 			+ $this->Get('stats_nb_replica_reconciled_errors');
	 }

	/**
	 * Increments a statistics counter
	 */
	function Inc($sCode)
	{
		$this->Set($sCode, 1+$this->Get($sCode));
	}


	/**
	 * Implement traces management
	 */
	protected $m_aTraces = array();
	public function AddTrace($sMsg, $oReplica = null)
	{
		if (MetaModel::GetConfig()->Get('synchro_trace') == 'none')
		{
			return;
		}

		if ($oReplica)
		{
			$sDestClass = $oReplica->Get('dest_class');
			if (!empty($sDestClass))
			{
				$sPrefix = $oReplica->GetKey().','.$sDestClass.'::'.$oReplica->Get('dest_id').',';
			}
			else
			{
				$sPrefix = $oReplica->GetKey().',,';
			}
		}
		else
		{
			$sPrefix = ',,';
		}
		$this->m_aTraces[] = $sPrefix.$sMsg;
	}

	public function GetTraces()
	{
		return $this->m_aTraces;
	}

	protected function TraceToText()
	{
		if (MetaModel::GetConfig()->Get('synchro_trace') != 'save')
		{
			// none, or display only
			return;
		}

		$sPrevTrace = $this->Get('traces');

		$oAttDef = MetaModel::GetAttributeDef(get_class($this), 'traces');
		$iMaxSize = $oAttDef->GetMaxSize();
		if (strlen($sPrevTrace) > 0)
		{
			$sTrace = $sPrevTrace."\n".implode("\n", $this->m_aTraces);
		}
		else
		{
			$sTrace = implode("\n", $this->m_aTraces);
		}
		if (strlen($sTrace) >= $iMaxSize)
		{
			$sTrace = substr($sTrace, 0, $iMaxSize - 255)."...\nTruncated (size: ".strlen($sTrace).')';
		}
		$this->Set('traces', $sTrace);

		//DBUpdate may be called many times... the operation should not be repeated
		$this->m_aTraces = array();
	}

	protected function OnInsert()
	{
		$this->TraceToText();
		parent::OnInsert();
	}

	protected function OnUpdate()
	{
		$this->TraceToText();
		$sMemPeak = max($this->Get('memory_usage_peak'), ExecutionKPI::memory_get_peak_usage());
		$this->Set('memory_usage_peak', $sMemPeak);
		parent::OnUpdate();
	}
}


class SynchroReplica extends DBObject implements iDisplay
{
	static $aSearches = array(); // Cache of OQL queries used for reconciliation (per data source)
	protected $aWarnings;
	
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

		MetaModel::Init_AddAttribute(new AttributeExternalKey("sync_source_id", array("targetclass"=>"SynchroDataSource", "jointype"=> "", "allowed_values"=>null, "sql"=>"sync_source_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_SILENT, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("base_class", array("allowed_values"=>null, "extkey_attcode"=> 'sync_source_id', "target_attcode"=>"scope_class")));

		MetaModel::Init_AddAttribute(new AttributeInteger("dest_id", array("allowed_values"=>null, "sql"=>"dest_id", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeClass("dest_class", array("class_category"=>"", "more_values"=>"", "sql"=>"dest_class", "default_value"=>'Organization', "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDateTime("status_last_seen", array("allowed_values"=>null, "sql"=>"status_last_seen", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('new,synchronized,modified,orphan,obsolete'), "sql"=>"status", "default_value"=>"new", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("status_dest_creator", array("allowed_values"=>null, "sql"=>"status_dest_creator", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("status_last_error", array("allowed_values"=>null, "sql"=>"status_last_error", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("status_last_warning", array("allowed_values"=>null, "sql"=>"status_last_warning", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeDateTime("info_creation_date", array("allowed_values"=>null, "sql"=>"info_creation_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("info_last_modified", array("allowed_values"=>null, "sql"=>"info_last_modified", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('' .
			'col:0'=> array(
				'fieldset:SynchroDataSource:Definition' => array('sync_source_id','dest_id','dest_class'),
				'fieldset:SynchroDataSource:Status' => array('status','status_last_seen','status_dest_creator','status_last_error','status_last_warning'),
				'fieldset:SynchroDataSource:Information' => array('info_creation_date','info_last_modified'))
			)
		);
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'dest_id', 'dest_class', 'status_last_seen', 'status', 'status_dest_creator', 'status_last_error', 'status_last_warning')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('sync_source_id', 'status_last_seen', 'status', 'status_dest_creator', 'dest_class', 'dest_id', 'status_last_error', 'status_last_warning')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
	
	public function __construct($aRow = null, $sClassAlias = '', $aAttToLoad = null, $aExtendedDataSpec = null)
	{
		parent::__construct($aRow, $sClassAlias, $aAttToLoad, $aExtendedDataSpec);
		$this->aWarnings = array();
	}

 	protected function AddWarning($sWarningMessage)
	{
		$this->aWarnings[] = $sWarningMessage;
	}

 	protected function ResetWarnings()
	{
		$this->aWarnings = array();
	}

	protected function HasWarnings()
	{
		return (count($this->aWarnings) > 0);
	}

	protected function RecordWarnings()
	{
		$sWarningMessage = '';
		$MAX_WARNING_LENGTH = 255;
		switch(count($this->aWarnings))
		{
			case 0:
			$sWarningMessage = '';
			break;
			
			case 1:
			$sWarningMessage = $this->aWarnings[0];
			break;
			
			default:
			$sWarningMessage = count($this->aWarnings)." warnings: ".implode(' ', $this->aWarnings);
			break;
		}

		if (strlen($sWarningMessage) > $MAX_WARNING_LENGTH)
		{
			$sWarningMessage = substr($sWarningMessage, 0, $MAX_WARNING_LENGTH - 3).'...';
		}
		
		$this->Set('status_last_warning', $sWarningMessage);
	}

	public function DBInsert()
	{
		throw new CoreException('A synchronization replica must be created only by the mean of triggers');
	}

	// Overload the deletion -> the replica has been created by the mean of a trigger,
	//                          it will be deleted by the mean of a trigger too
	protected function DBDeleteSingleObject()
	{
		$this->OnDelete();

		if (!MetaModel::DBIsReadOnly())
		{
			$oDataSource = MetaModel::GetObject('SynchroDataSource', $this->Get('sync_source_id'), false);
			if ($oDataSource)
			{
				$sTable = $oDataSource->GetDataTable();
	
				$sSQL = "DELETE FROM `$sTable` WHERE id = '{$this->GetKey()}'";
				CMDBSource::Query($sSQL);
			}
			// else the whole datasource has probably been already deleted
		}

		$this->AfterDelete();

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

	
	public function Synchro($oDataSource, $aReconciliationKeys, $aAttributes, $oChange, &$oStatLog)
	{
		$this->ResetWarnings();
		switch($this->Get('status'))
		{
			case 'new':
			$this->Set('status_dest_creator', false);
			// If needed, construct the query used for the reconciliation
			if (!isset(self::$aSearches[$oDataSource->GetKey()]))
			{
				$aCriterias = array();
				foreach($aReconciliationKeys as $sFilterCode => $oSyncAtt)
				{
					$aCriterias[] = ($sFilterCode == 'primary_key' ? 'id' : $sFilterCode).' = :'.$sFilterCode;
				}
				$sOQL = "SELECT ".$oDataSource->GetTargetClass()." WHERE ".implode(' AND ', $aCriterias);
				self::$aSearches[$oDataSource->GetKey()] = DBObjectSearch::FromOQL($sOQL);
			}
			// Get the criterias for the search
			$aFilterValues = array();
			foreach($aReconciliationKeys as $sFilterCode => $oSyncAtt)
			{
				$value = $this->GetValueFromExtData($sFilterCode, $oSyncAtt, $oStatLog);
				if (!is_null($value))
				{
					$aFilterValues[$sFilterCode] = $value;
				}
				else
				{
					// TO DO: can we retry this ??
					// Reconciliation could not be performed - log and EXIT
					$oStatLog->AddTrace("Could not reconcile on null value for attribute '$sFilterCode'", $this);
					$this->SetLastError("Could not reconcile on null value for attribute '$sFilterCode'");
					$oStatLog->Inc('stats_nb_replica_reconciled_errors');
					return;
				}
			}
			$oDestSet = new DBObjectSet(self::$aSearches[$oDataSource->GetKey()], array(), $aFilterValues);
			$iCount = $oDestSet->Count();
			$aConditions = array();
			foreach($aFilterValues as $sCode => $sValue)
			{
				$aConditions[] = $sCode.'='.$sValue;
			}
			$sConditionDesc = implode(' AND ', $aConditions);
			// How many objects match the reconciliation criterias
			switch($iCount)
			{
				case 0:
				$oStatLog->AddTrace("Nothing found on: $sConditionDesc", $this);
				if ($oDataSource->Get('action_on_zero') == 'create')
				{
					$bCreated = $this->CreateObjectFromReplica($oDataSource->GetTargetClass(), $aAttributes, $oChange, $oStatLog);
					if ($bCreated)
					{
						if ($this->HasWarnings())
						{
							$oStatLog->Inc('stats_nb_obj_created_warnings');
						}
					}
					else
					{
						// Creation error has precedence over any warning
						$this->ResetWarnings();
					}
				}
				else // assumed to be 'error'
				{
					$oStatLog->AddTrace("Failed to reconcile (no match)", $this);
					// Recoverable error
					$this->SetLastError('Could not find a match for reconciliation');
					$oStatLog->Inc('stats_nb_replica_reconciled_errors');
				}
				break;
				
				case 1:
				$oStatLog->AddTrace("Found 1 object on: $sConditionDesc", $this);
				if ($oDataSource->Get('action_on_one') == 'update')
				{
					$oDestObj = $oDestSet->Fetch();
					$bModified = $this->UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, $oStatLog, 'stats_nb_obj_new', 'stats_nb_replica_reconciled_errors');
					$this->Set('dest_id', $oDestObj->GetKey());
					$this->Set('dest_class', get_class($oDestObj));
					if ($this->HasWarnings())
					{
						if ($bModified)
						{
							$oStatLog->Inc('stats_nb_obj_new_updated_warnings');
						}
						else
						{
							$oStatLog->Inc('stats_nb_obj_new_unchanged_warnings');
						}
					}
				}
				else
				{
					// assumed to be 'error'
					$oStatLog->AddTrace("Failed to reconcile (1 match)", $this);
					// Recoverable error
					$this->SetLastError('Found a match while expecting several');
					$oStatLog->Inc('stats_nb_replica_reconciled_errors');
				}
				break;
				
				default:
				$oStatLog->AddTrace("Found $iCount objects on: $sConditionDesc", $this);
				if ($oDataSource->Get('action_on_multiple') == 'error')
				{
					$oStatLog->AddTrace("Failed to reconcile (N>1 matches)", $this);
					// Recoverable error
					$this->SetLastError($iCount.' destination objects match the reconciliation criterias: '.$sConditionDesc);
					$oStatLog->Inc('stats_nb_replica_reconciled_errors');
				}
				elseif ($oDataSource->Get('action_on_multiple') == 'create')
				{
					$bCreated = $this->CreateObjectFromReplica($oDataSource->GetTargetClass(), $aAttributes, $oChange, $oStatLog);
					if ($bCreated)
					{
						if ($this->HasWarnings())
						{
							$oStatLog->Inc('stats_nb_obj_created_warnings');
						}
					}
					else
					{
						// Creation error has precedence over any warning
						$this->ResetWarnings();
					}
				}
				else
				{
					// assumed to be 'take_first'
					$oDestObj = $oDestSet->Fetch();
					$bModified = $this->UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, $oStatLog, 'stats_nb_obj_new', 'stats_nb_replica_reconciled_errors');
					$this->Set('dest_id', $oDestObj->GetKey());
					$this->Set('dest_class', get_class($oDestObj));
					if ($this->HasWarnings())
					{
						if ($bModified)
						{
							$oStatLog->Inc('stats_nb_obj_new_updated_warnings');
						}
						else
						{
							$oStatLog->Inc('stats_nb_obj_new_unchanged_warnings');
						}
					}
				}
			}
			$this->RecordWarnings();
			break;
			
			case 'synchronized': // try to recover synchronized replicas with warnings
			case 'modified':
				$oDestObj = MetaModel::GetObject($oDataSource->GetTargetClass(), $this->Get('dest_id'));
			if ($oDestObj == null)
			{
				$this->Set('status', 'orphan'); // The destination object has been deleted !
				$this->SetLastError('Destination object deleted unexpectedly');
				$oStatLog->Inc('stats_nb_obj_updated_errors');
			}
			else
			{
				$bModified = $this->UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, $oStatLog, 'stats_nb_obj', 'stats_nb_obj_updated_errors');
				if ($this->HasWarnings())
				{
					if ($bModified)
					{
						$oStatLog->Inc('stats_nb_obj_updated_warnings');
					}
					else
					{
						$oStatLog->Inc('stats_nb_obj_unchanged_warnings');
					}
				}
			}
			$this->RecordWarnings();
			break;
			
			default: // Do nothing in all other cases
		}
	}
	
	/**
	 * Updates the destination object with the Extended data found in the synchro_data_XXXX table
	 */	
	protected function UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, &$oStatLog, $sStatsCode, $sStatsCodeError)
	{
		$aValueTrace = array();
		$bModified = false;
		try
		{
			foreach($aAttributes as $sAttCode => $oSyncAtt)
			{
				$value = $this->GetValueFromExtData($sAttCode, $oSyncAtt, $oStatLog);
				if (!is_null($value))
				{
					if ($oSyncAtt->Get('update_policy') == 'write_if_empty')
					{
						$oAttDef = MetaModel::GetAttributeDef(get_class($oDestObj), $sAttCode);
						if ($oAttDef->IsNull($oDestObj->Get($sAttCode)))
						{
							// The value is still "empty" in the target object, we are allowed to write the new value
							$oDestObj->Set($sAttCode, $value);
							$aValueTrace[] = "$sAttCode: $value";
						}
					}
					else
					{
						$oDestObj->Set($sAttCode, $value);
						$aValueTrace[] = "$sAttCode: $value";
					}
				}
			}
			// Really modified ?
			if ($oDestObj->IsModified())
			{
				$oDestObj->DBUpdateTracked($oChange);
				$bModified = true;
				$oStatLog->AddTrace('Updated object - Values: {'.implode(', ', $aValueTrace).'}', $this);
				if (($sStatsCode != '') &&(MetaModel::IsValidAttCode(get_class($oStatLog), $sStatsCode.'_updated')))
				{
					$oStatLog->Inc($sStatsCode.'_updated');
				}
				$this->Set('info_last_modified', date('Y-m-d H:i:s'));
			}
			else
			{
				$oStatLog->AddTrace('Unchanged object', $this);
				if (($sStatsCode != '') &&(MetaModel::IsValidAttCode(get_class($oStatLog), $sStatsCode.'_unchanged')))
				{
					$oStatLog->Inc($sStatsCode.'_unchanged');
				}
			}

			$this->Set('status_last_error', '');
			$this->Set('status', 'synchronized');
		}
		catch(Exception $e)
		{
			$oStatLog->AddTrace("Failed to update destination object: {$e->getMessage()}", $this);
			$this->SetLastError('Unable to update destination object: ', $e);
			$oStatLog->Inc($sStatsCodeError);
		}
		return $bModified;
	}

	/**
	 * Creates the destination object populating it with the Extended data found in the synchro_data_XXXX table
	 * @return bool Whether or not the object was created
	 */	
	protected function CreateObjectFromReplica($sClass, $aAttributes, $oChange, &$oStatLog)
	{
		$bCreated = false;
		$oDestObj = MetaModel::NewObject($sClass);
		try
		{
			$aValueTrace = array();
			foreach($aAttributes as $sAttCode => $oSyncAtt)
			{
				$value = $this->GetValueFromExtData($sAttCode, $oSyncAtt, $oStatLog);
				if (!is_null($value))
				{
					$oDestObj->Set($sAttCode, $value);
					$aValueTrace[] = "$sAttCode: $value";
				}
			}
			$iNew = $oDestObj->DBInsertTracked($oChange);

			$this->Set('dest_id', $oDestObj->GetKey());
			$this->Set('dest_class', get_class($oDestObj));
			$this->Set('status_dest_creator', true);
			$this->Set('status_last_error', '');
			$this->Set('status', 'synchronized');
			$this->Set('info_creation_date', date('Y-m-d H:i:s'));
			$bCreated = true;

			$oStatLog->AddTrace("Created (".implode(', ', $aValueTrace).")", $this);
			$oStatLog->Inc('stats_nb_obj_created');
		}
		catch(Exception $e)
		{
			$oStatLog->AddTrace("Failed to create $sClass ({$e->getMessage()})", $this);
			$this->SetLastError('Unable to create destination object: ', $e);
			$oStatLog->Inc('stats_nb_obj_created_errors');
		}
		return $bCreated;
	}
	
	/**
	 * Update the destination object with given values
	 */	
	public function UpdateDestObject($aValues, $oChange, &$oStatLog)
	{
		try
		{
			if ($this->Get('dest_class') == '')
			{
				$this->SetLastError('No destination object to update');
				$oStatLog->Inc('stats_nb_obj_obsoleted_errors');
			}
			else
			{
				$oDestObj = MetaModel::GetObject($this->Get('dest_class'), $this->Get('dest_id'));
				foreach($aValues as $sAttCode => $value)
				{
					if (!MetaModel::IsValidAttCode(get_class($oDestObj), $sAttCode))
					{
						throw new Exception("Unknown attribute code '$sAttCode'");
					} 
					$oDestObj->Set($sAttCode, $value);
				}
				$this->Set('info_last_modified', date('Y-m-d H:i:s'));
				$oDestObj->DBUpdateTracked($oChange);
				$oStatLog->AddTrace("Replica marked as obsolete", $this);
				$oStatLog->Inc('stats_nb_obj_obsoleted');
			}
		}
		catch(Exception $e)
		{
			$this->SetLastError('Unable to update the destination object: ', $e);
			$oStatLog->Inc('stats_nb_obj_obsoleted_errors');
		}
	}

	/**
	 * Delete the destination object
	 */	
	public function DeleteDestObject($oChange, &$oStatLog)
	{
		if($this->Get('status_dest_creator'))
		{
			try
			{
				$oDestObj = MetaModel::GetObject($this->Get('dest_class'), $this->Get('dest_id'));
				$oCheckDeletionPlan = new DeletionPlan();
				if ($oDestObj->CheckToDelete($oCheckDeletionPlan))
				{
					$oActualDeletionPlan = new DeletionPlan();
					$oDestObj->DBDeleteTracked($oChange, null, $oActualDeletionPlan);
					$this->DBDeleteTracked($oChange);
					$oStatLog->Inc('stats_nb_obj_deleted');
				}
				else
				{
					$sIssues = implode("\n", $oCheckDeletionPlan->GetIssues());
					throw(new Exception($sIssues));
				}
			}
			catch(Exception $e)
			{
				$this->SetLastError('Unable to delete the destination object: ', $e);
				$this->Set('status', 'obsolete');
				$this->DBUpdateTracked($oChange);
				$oStatLog->Inc('stats_nb_obj_deleted_errors');
			}
		}
		else
		{
			$this->DBDeleteTracked($oChange);
			$oStatLog->Inc('stats_nb_replica_disappeared_no_action');
		}
	}

	/**
	 * Get the value from the 'Extended Data' located in the synchro_data_xxx table for this replica
	 * Note: sExtAttCode could be a standard attcode, or 'primary_key'	 
	 */
	protected function GetValueFromExtData($sExtAttCode, $oSyncAtt, &$oStatLog)
	{
		// $aData should contain attributes defined either for reconciliation or create/update
		$aData = $this->GetExtendedData();

		if ($sExtAttCode == 'primary_key')
		{
			return $aData['primary_key'];
		}

		// $sExtAttCode is a valid attribute code
		// 
		$sClass = $this->Get('base_class');

		$oAttDef = MetaModel::GetAttributeDef($sClass, $sExtAttCode);

		if (!is_null($oSyncAtt) && ($oSyncAtt instanceof SynchroAttExtKey))
		{
			$rawValue = $aData[$sExtAttCode];
			if (is_null($rawValue))
			{
				// Null means "ignore" this attribute
				return null;
			}

			$sReconcAttCode = $oSyncAtt->Get('reconciliation_attcode');
			if (!empty($sReconcAttCode))
			{
				$sRemoteClass = $oAttDef->GetTargetClass();
				$oObj = MetaModel::GetObjectByColumn($sRemoteClass, $sReconcAttCode, $rawValue, false);
				if ($oObj)
				{
					 $retValue = $oObj->GetKey();
				}
				else
				{
					if ($rawValue != '')
					{
						// Note: differs from null (in which case the value would be left unchanged)
						$oStatLog->AddTrace("Could not find [unique] object for '$sExtAttCode': searched on $sReconcAttCode = '$rawValue'", $this);
						$this->AddWarning("Could not find [unique] object for '$sExtAttCode': searched on $sReconcAttCode = '$rawValue'");
					}
					$retValue = 0;
				}
			}
			else
			{
				$retValue = $rawValue;
			}
		}
		elseif (!is_null($oSyncAtt) && ($oSyncAtt instanceof SynchroAttLinkSet))
		{
			$rawValue = $aData[$sExtAttCode];
			if (is_null($rawValue))
			{
				// Null means "ignore" this attribute
				return null;
			}
			// MakeValueFromString() throws an exception in case of failure
			$bLocalizedValue = false;
			$retValue = $oAttDef->MakeValueFromString($rawValue, $bLocalizedValue, $oSyncAtt->Get('row_separator'), $oSyncAtt->Get('attribute_separator'), $oSyncAtt->Get('value_separator'), $oSyncAtt->Get('attribute_qualifier'));
		}
		else
		{
			$aColumns = $oAttDef->GetImportColumns();
			foreach($aColumns as $sColumn => $sFormat)
			{
				// In any case, a null column means "ignore this attribute"
				//
				if (is_null($aData[$sColumn]))
				{
					return null;
				}
			}
			$retValue = $oAttDef->FromImportToValue($aData, $sExtAttCode);
		}

		return $retValue;
	}
	
	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 * @param string $sContextParam Name of the context parameter, i.e. 'org_id'
	 * @return string Filter code, i.e. 'customer_id'
	 */
	public static function MapContextParam($sContextParam)
	{
		if ($sContextParam == 'menu')
		{
			return null;
		}
		else
		{
			return $sContextParam;
		}
	}

	/**
	 * This function returns a 'hilight' CSS class, used to hilight a given row in a table
	 * There are currently (i.e defined in the CSS) 4 possible values HILIGHT_CLASS_CRITICAL,
	 * HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE
	 * To Be overridden by derived classes
	 * @param void
	 * @return String The desired higlight class for the object/row
	 */
	public function GetHilightClass()
	{
		// Possible return values are:
		// HILIGHT_CLASS_CRITICAL, HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE	
		return HILIGHT_CLASS_NONE; // Not hilighted by default
	}

	public static function GetUIPage()
	{
		return '../synchro/replica.php';
	}

	function DisplayDetails(WebPage $oPage, $bEditMode = false)
	{
		// Object's details
		//$this->DisplayBareHeader($oPage, $bEditMode);
		$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTab(Dict::S('UI:PropertiesTab'));
		$this->DisplayBareProperties($oPage, $bEditMode);
	}
	
	function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = array())
	{
		if ($bEditMode) return; // Not editable
		
		$oPage->add('<table style="vertical-align:top"><tr style="vertical-align:top"><td>');
		$aDetails = array();
		$sClass = get_class($this);
		$oPage->add('<fieldset>');
		$oPage->add('<legend>'.Dict::S('Core:SynchroReplica:PrivateDetails').'</legend>');
		$aZList = MetaModel::FlattenZlist(MetaModel::GetZListItems($sClass, 'details'));
		foreach( $aZList as $sAttCode)
		{
			$sDisplayValue = $this->GetAsHTML($sAttCode);	
			$aDetails[] = array('label' => '<span title="'.MetaModel::GetDescription($sClass, $sAttCode).'">'.MetaModel::GetLabel($sClass, $sAttCode).'</span>', 'value' => $sDisplayValue);
		}
		$oPage->Details($aDetails);
		$oPage->add('</fieldset>');
		if (strlen($this->Get('dest_class')) > 0)
		{
			$oDestObj = MetaModel::GetObject($this->Get('dest_class'), $this->Get('dest_id'), false);
			if (is_object($oDestObj))
			{
				$oPage->add('<fieldset>');
				$oPage->add('<legend>'.Dict::Format('Core:SynchroReplica:TargetObject', $oDestObj->GetHyperlink()).'</legend>');
					$oDestObj->DisplayBareProperties($oPage, false, $sPrefix, $aExtraParams);
				$oPage->add('<fieldset>');
			}
		}
		$oPage->add('</td><td>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>'.Dict::S('Core:SynchroReplica:PublicData').'</legend>');
		$oSource = MetaModel::GetObject('SynchroDataSource', $this->Get('sync_source_id'));
		
		$sSQLTable = $oSource->GetDataTable();
		$aData = $this->LoadExtendedDataFromTable($sSQLTable);

		$aHeaders = array('attcode' => array('label' => 'Attribute Code', 'description' => ''),
						  'data'    => array('label' => 'Value', 'description' => ''));
		$aRows = array();
		foreach($aData as $sKey => $value)
		{
			$aRows[] = array('attcode' => $sKey, 'data' => $value);
		}
		$oPage->Table($aHeaders, $aRows);
		$oPage->add('</fieldset>');
		$oPage->add('</td></tr></table>');
		
	}
	
	public function LoadExtendedDataFromTable($sSQLTable)
	{
		$sSQL = "SELECT * FROM $sSQLTable WHERE id=".$this->GetKey();

		$rQuery = CMDBSource::Query($sSQL);
		return CMDBSource::FetchArray($rQuery);
	}
}

/**
 * Context of an ongoing synchronization
 * Two usages:
 * 1) Public usage: execute the synchronization
 *    $oSynchroExec = new SynchroExecution($oDataSource[, $iLastFullLoad]);
 *    $oSynchroExec->Process($iMaxChunkSize); 
 *      
 * 2) Internal usage: continue the synchronization (split into chunks, each performed in a separate process)
 *    This is implemented in the page priv_sync_chunk.php 
 *    $oSynchroExec = SynchroExecution::Resume($oDataSource, $iLastFullLoad, $iSynchroLog, $iChange, $iMaxToProcess, $iJob, $iNextInJob);    
 *    $oSynchroExec->Process() 
 */	
class SynchroExecution
{
	protected $m_oDataSource = null;
	protected $m_oLastFullLoadStartDate = null;

	protected $m_oChange = null;
	protected $m_oStatLog = null;

	// Context computed one for optimization and report inconsistencies ASAP
	protected $m_aExtDataSpec = array();
	protected $m_aReconciliationKeys = array();
	protected $m_aAttributes = array();
	protected $m_iCountAllReplicas = 0;

	/**
	 * Constructor
	 * @param SynchroDataSource $oDataSource Synchronization task
	 * @param DateTime $oLastFullLoadStartDate Date of the last full load (start date/time), if known
	 * @return void
	 */
	public function __construct($oDataSource, $oLastFullLoadStartDate = null)
	{
		$this->m_oDataSource = $oDataSource;
		$this->m_oLastFullLoadStartDate = $oLastFullLoadStartDate;
	}

	/**
	* Create the persistant information records, for the current synchronization
	* In fact, those records ARE defining what is the "current" synchronization	
	*/	
	protected function PrepareLogs()
	{
		if (!is_null($this->m_oChange))
		{
			return;
		}

		// Create a change used for logging all the modifications/creations happening during the synchro
		$this->m_oChange = MetaModel::NewObject("CMDBChange");
		$this->m_oChange->Set("date", time());
		$sUserString = CMDBChange::GetCurrentUserName();
		$this->m_oChange->Set("userinfo", $sUserString.' '.Dict::S('Core:SyncDataExchangeComment'));
		$this->m_oChange->Set("origin", 'synchro-data-source');
		$iChangeId = $this->m_oChange->DBInsert();

		// Start logging this execution (stats + protection against reentrance)
		//
		$this->m_oStatLog = new SynchroLog();
		$this->m_oStatLog->Set('sync_source_id', $this->m_oDataSource->GetKey());
		$this->m_oStatLog->Set('start_date', time());
		$this->m_oStatLog->Set('status', 'running');
		$this->m_oStatLog->Set('stats_nb_replica_seen', 0);
		$this->m_oStatLog->Set('stats_nb_replica_total', 0);
		$this->m_oStatLog->Set('stats_nb_obj_deleted', 0);
		$this->m_oStatLog->Set('stats_nb_obj_deleted_errors', 0);
		$this->m_oStatLog->Set('stats_nb_obj_obsoleted', 0);
		$this->m_oStatLog->Set('stats_nb_obj_obsoleted_errors', 0);
		$this->m_oStatLog->Set('stats_nb_obj_created', 0);
		$this->m_oStatLog->Set('stats_nb_obj_created_errors', 0);
		$this->m_oStatLog->Set('stats_nb_obj_created_warnings', 0);
		$this->m_oStatLog->Set('stats_nb_obj_updated', 0);
		$this->m_oStatLog->Set('stats_nb_obj_updated_warnings', 0);
		$this->m_oStatLog->Set('stats_nb_obj_updated_errors', 0);
		$this->m_oStatLog->Set('stats_nb_obj_unchanged_warnings', 0);
		//		$this->m_oStatLog->Set('stats_nb_replica_reconciled', 0);
		$this->m_oStatLog->Set('stats_nb_replica_reconciled_errors', 0);
		$this->m_oStatLog->Set('stats_nb_replica_disappeared_no_action', 0);
		$this->m_oStatLog->Set('stats_nb_obj_new_updated', 0);
		$this->m_oStatLog->Set('stats_nb_obj_new_updated_warnings', 0);
		$this->m_oStatLog->Set('stats_nb_obj_new_unchanged',0);
		$this->m_oStatLog->Set('stats_nb_obj_new_unchanged_warnings',0);
		
		$sSelectTotal  = "SELECT SynchroReplica WHERE sync_source_id = :source_id";
		$oSetTotal = new DBObjectSet(DBObjectSearch::FromOQL($sSelectTotal), array() /* order by*/, array('source_id' => $this->m_oDataSource->GetKey()));
		$this->m_iCountAllReplicas = $oSetTotal->Count();
		$this->m_oStatLog->Set('stats_nb_replica_total', $this->m_iCountAllReplicas);

		$this->m_oStatLog->DBInsertTracked($this->m_oChange);
	}

	/**
	* Prevent against the reentrance... or allow the current task to do things forbidden by the others !
	*/	
	public static $m_oCurrentTask = null;
	public static function GetCurrentTaskId()
	{
		if (is_object(self::$m_oCurrentTask))
		{
			return self::$m_oCurrentTask->GetKey();
		}
		else
		{
			return null;
		}
	}

	/**
	* Prepare structures in memory, to speedup the processing of a given replica
	*/	
	public function PrepareProcessing($bFirstPass = true)
	{
		if ($this->m_oDataSource->Get('status') == 'obsolete')
		{
			throw new SynchroExceptionNotStarted(Dict::S('Core:SyncDataSourceObsolete'));
		}
		if (!UserRights::IsAdministrator() && $this->m_oDataSource->Get('user_id') != UserRights::GetUserId())
		{
			throw new SynchroExceptionNotStarted(Dict::S('Core:SyncDataSourceAccessRestriction'));
		}

		// Get the list of SQL columns
		$sClass = $this->m_oDataSource->GetTargetClass();
		$aAttCodesExpected = array();
		$aAttCodesToReconcile = array();
		$aAttCodesToUpdate = array();
		$sSelectAtt  = "SELECT SynchroAttribute WHERE sync_source_id = :source_id AND (update = 1 OR reconcile = 1)";
		$oSetAtt = new DBObjectSet(DBObjectSearch::FromOQL($sSelectAtt), array() /* order by*/, array('source_id' => $this->m_oDataSource->GetKey()) /* aArgs */);
		while ($oSyncAtt = $oSetAtt->Fetch())
		{
			if ($oSyncAtt->Get('update'))
			{
				$aAttCodesToUpdate[$oSyncAtt->Get('attcode')] = $oSyncAtt;
			}
			if ($oSyncAtt->Get('reconcile'))
			{
				$aAttCodesToReconcile[$oSyncAtt->Get('attcode')] = $oSyncAtt;
			}
			$aAttCodesExpected[$oSyncAtt->Get('attcode')] = $oSyncAtt;
		}
		$aColumns = $this->m_oDataSource->GetSQLColumns(array_keys($aAttCodesExpected));
		$aExtDataFields = array_keys($aColumns);
		$aExtDataFields[] = 'primary_key';

		$this->m_aExtDataSpec = array(
			'table' => $this->m_oDataSource->GetDataTable(),
			'join_key' => 'id',
			'fields' => $aExtDataFields
		);

		// Get the list of attributes, determine reconciliation keys and update targets
		//
		if ($this->m_oDataSource->Get('reconciliation_policy') == 'use_attributes')
		{
			$this->m_aReconciliationKeys = $aAttCodesToReconcile;
		}
		elseif ($this->m_oDataSource->Get('reconciliation_policy') == 'use_primary_key')
		{
			// Override the settings made at the attribute level !
			$this->m_aReconciliationKeys = array("primary_key" => null);
		}

		if ($bFirstPass)
		{
			$this->m_oStatLog->AddTrace("Update of: {".implode(', ', array_keys($aAttCodesToUpdate))."}");
			$this->m_oStatLog->AddTrace("Reconciliation on: {".implode(', ', array_keys($this->m_aReconciliationKeys))."}");
		}

		if (count($aAttCodesToUpdate) == 0)
		{
			$this->m_oStatLog->AddTrace("No attribute to update");
			throw new SynchroExceptionNotStarted('There is no attribute to update');
		}
		if (count($this->m_aReconciliationKeys) == 0)
		{
			$this->m_oStatLog->AddTrace("No attribute for reconciliation");
			throw new SynchroExceptionNotStarted('No attribute for reconciliation');
		}
		
		$this->m_aAttributes = array();
		foreach($aAttCodesToUpdate as $sAttCode => $oSyncAtt)
		{
			$oAttDef = MetaModel::GetAttributeDef($this->m_oDataSource->GetTargetClass(), $sAttCode);
			if ($oAttDef->IsWritable())
			{
				$this->m_aAttributes[$sAttCode] = $oSyncAtt;
			}
		}

		// Compute and keep track of the limit date taken into account for obsoleting replicas
		//
		if ($this->m_oLastFullLoadStartDate == null)
		{
			// No previous import known, use the full_load_periodicity value... and the current date
			$this->m_oLastFullLoadStartDate = new DateTime(); // Now
			$iLoadPeriodicity = $this->m_oDataSource->Get('full_load_periodicity'); // Duration in seconds
			if ($iLoadPeriodicity > 0)
			{
				$sInterval = "-$iLoadPeriodicity seconds";
				$this->m_oLastFullLoadStartDate->Modify($sInterval);
			}
			else
			{
				$this->m_oLastFullLoadStartDate = new DateTime('1970-01-01');
			}
		}
		if ($bFirstPass)
		{
			$this->m_oStatLog->AddTrace("Limit Date: ".$this->m_oLastFullLoadStartDate->Format('Y-m-d H:i:s'));
		}
	}


	/**
	 * Perform a synchronization between the data stored in the replicas (&synchro_data_xxx_xx table)
	 * and the iTop objects. If the lastFullLoadStartDate is NOT specified then the full_load_periodicity
	 * is used to determine which records are obsolete.
	 * @return void
	 */
	public function Process()
	{
		$this->PrepareLogs();

		self::$m_oCurrentTask = $this->m_oDataSource;

		$oMutex = new iTopMutex('synchro_process_'.$this->m_oDataSource->GetKey().'_'.MetaModel::GetConfig()->GetDBName().'_'.MetaModel::GetConfig()->GetDBSubname());
		try
		{
			$oMutex->Lock();
			$this->DoSynchronize();
			$oMutex->Unlock();
				
			$this->m_oStatLog->Set('end_date', time());
			$this->m_oStatLog->Set('status', 'completed');
			$this->m_oStatLog->DBUpdateTracked($this->m_oChange);

			$iErrors = $this->m_oStatLog->GetErrorCount();
			if ($iErrors > 0)
			{
				$sIssuesOQL = "SELECT SynchroReplica WHERE sync_source_id=".$this->m_oDataSource->GetKey()." AND status_last_error!=''";
				$sAbsoluteUrl = utils::GetAbsoluteUrlAppRoot();
				$sIssuesURL = "{$sAbsoluteUrl}synchro/replica.php?operation=oql&datasource=".$this->m_oDataSource->GetKey()."&oql=".urlencode($sIssuesOQL);
				$sSeeIssues = "<p></p>";

				$sStatistics = "<h1>Statistics</h1>\n";
				$sStatistics .= "<ul>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('start_date').": ".$this->m_oStatLog->Get('start_date')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('end_date').": ".$this->m_oStatLog->Get('end_date')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_replica_seen').": ".$this->m_oStatLog->Get('stats_nb_replica_seen')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_replica_total').": ".$this->m_oStatLog->Get('stats_nb_replica_total')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_obj_deleted').": ".$this->m_oStatLog->Get('stats_nb_obj_deleted')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_obj_deleted_errors').": ".$this->m_oStatLog->Get('stats_nb_obj_deleted_errors')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_obj_obsoleted').": ".$this->m_oStatLog->Get('stats_nb_obj_obsoleted')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_obj_obsoleted_errors').": ".$this->m_oStatLog->Get('stats_nb_obj_obsoleted_errors')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_obj_created').": ".$this->m_oStatLog->Get('stats_nb_obj_created')." (".$this->m_oStatLog->Get('stats_nb_obj_created_warnings')." warnings)"."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_obj_created_errors').": ".$this->m_oStatLog->Get('stats_nb_obj_created_errors')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_obj_updated').": ".$this->m_oStatLog->Get('stats_nb_obj_updated')." (".$this->m_oStatLog->Get('stats_nb_obj_updated_warnings')." warnings)"."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_obj_updated_errors').": ".$this->m_oStatLog->Get('stats_nb_obj_updated_errors')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_replica_reconciled_errors').": ".$this->m_oStatLog->Get('stats_nb_replica_reconciled_errors')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_replica_disappeared_no_action').": ".$this->m_oStatLog->Get('stats_nb_replica_disappeared_no_action')."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_obj_new_updated').": ".$this->m_oStatLog->Get('stats_nb_obj_new_updated')." (".$this->m_oStatLog->Get('stats_nb_obj_new_updated_warnings')." warnings)"."</li>\n";
				$sStatistics .= "<li>".$this->m_oStatLog->GetLabel('stats_nb_obj_new_unchanged').": ".$this->m_oStatLog->Get('stats_nb_obj_new_unchanged')." (".$this->m_oStatLog->Get('stats_nb_obj_new_unchanged_warnings')." warnings)"."</li>\n";
				$sStatistics .= "</ul>\n";

				$this->m_oDataSource->SendNotification("errors ($iErrors)", "<p>The synchronization has been executed, $iErrors errors have been encountered. Click <a href=\"$sIssuesURL\">here</a> to see the records being currently in error.</p>".$sStatistics);
			}
			else
			{
				//$this->m_oDataSource->SendNotification('success', '<p>The synchronization has been successfully executed.</p>');
			}
		}
		catch (SynchroExceptionNotStarted $e)
		{
			$oMutex->Unlock();
			// Set information for reporting... but delete the object in DB
			$this->m_oStatLog->Set('end_date', time());
			$this->m_oStatLog->Set('status', 'error');
			$this->m_oStatLog->Set('last_error', $e->getMessage());
			$this->m_oStatLog->DBDeleteTracked($this->m_oChange);
			$this->m_oDataSource->SendNotification('fatal error', '<p>The synchronization could not start: \''.$e->getMessage().'\'</p><p>Please check its configuration</p>');
		}
		catch (Exception $e)
		{
			$oMutex->Unlock();
			$this->m_oStatLog->Set('end_date', time());
			$this->m_oStatLog->Set('status', 'error');
			$this->m_oStatLog->Set('last_error', $e->getMessage());
			$this->m_oStatLog->DBUpdateTracked($this->m_oChange);
			$this->m_oDataSource->SendNotification('exception', '<p>The synchronization has been interrupted: \''.$e->getMessage().'\'</p><p>Please contact the application support team</p>');
		}
		self::$m_oCurrentTask = null;

		return $this->m_oStatLog;
	}

	/**
	 * Do the entire synchronization job
	 */
	protected function DoSynchronize()
	{
		$this->m_oStatLog->Set('status_curr_job', 1);
		$this->m_oStatLog->Set('status_curr_pos', -1);

		$iMaxChunkSize = utils::ReadParam('max_chunk_size', 0, true /* allow CLI */);
		if ($iMaxChunkSize > 0)
		{
			// Split the execution into several processes
			// Each process will call DoSynchronizeChunk()
			// The loop will end when a process does not reply "continue" on the last line of its output
			if (!utils::IsModeCLI())
			{
				throw new SynchroExceptionNotStarted(Dict::S('Core:SyncSplitModeCLIOnly'));
			}
			$aArguments = array();
			$aArguments['source'] = $this->m_oDataSource->GetKey();
			$aArguments['log'] = $this->m_oStatLog->GetKey();
			$aArguments['change'] = $this->m_oChange->GetKey();
			$aArguments['chunk'] = $iMaxChunkSize;
			if ($this->m_oLastFullLoadStartDate)
			{
				$aArguments['last_full_load'] = $this->m_oLastFullLoadStartDate->Format('Y-m-d H:i:s');
			}
			else
			{
				$aArguments['last_full_load'] = '';
			}

			$this->m_oStatLog->DBUpdate($this->m_oChange);

			$iStepCount = 0;
			do
			{
				$aArguments['step_count'] = $iStepCount;
				$iStepCount++;

				list ($iRes, $aOut) = utils::ExecITopScript('synchro/priv_sync_chunk.php', $aArguments);

				// Reload the log that has been modified by the processes
				$this->m_oStatLog->Reload();

				$sLastRes = strtolower(trim(end($aOut)));
				switch($sLastRes)
				{
				case 'continue':
					$bContinue = true;
					break;

				case 'finished':
					$bContinue = false;
					break;

				default:
					$this->m_oStatLog->AddTrace("The script did not reply with the expected keywords:");
					$aIndentedOut = array();
					foreach ($aOut as $sOut)
					{
						$aIndentedOut[] = "-> $sOut";
						$this->m_oStatLog->AddTrace(">>> $sOut");
					}
					throw new Exception("Encountered an error in an underspinned process:\n".implode("\n", $aIndentedOut));
				}
			}
			while ($bContinue);
		}
		else
		{
			$this->PrepareProcessing(/* first pass */);
			$this->DoJob1();
			$this->DoJob2();
			$this->DoJob3();
		}
	}

	/**
	 * Do the synchronization job, limited to some amount of work
	 * This verb has been designed to be called from within a separate process	 
	 * @return true if the process has to be continued
	 */
	public function DoSynchronizeChunk($oLog, $oChange, $iMaxChunkSize)
	{
		// Initialize the structures...
		self::$m_oCurrentTask = $this->m_oDataSource;
		$this->m_oStatLog = $oLog;
		$this->m_oChange = $oChange;

		// Prepare internal structures (not the first pass)
		$this->PrepareProcessing(false);

		$iCurrJob = $this->m_oStatLog->Get('status_curr_job');
		$iCurrPos = $this->m_oStatLog->Get('status_curr_pos');

		$this->m_oStatLog->AddTrace("Synchronizing chunk - curr_job:$iCurrJob, curr_pos:$iCurrPos, max_chunk_size:$iMaxChunkSize");

		$bContinue = false;
		switch ($iCurrJob)
		{
			case 1:
			default:
				$this->DoJob1($iMaxChunkSize, $iCurrPos);
				$bContinue = true;
				break;

			case 2:
				$this->DoJob2($iMaxChunkSize, $iCurrPos);
				$bContinue = true;
				break;

			case 3:
				$bContinue = $this->DoJob3($iMaxChunkSize, $iCurrPos);
				break;
		}
		$this->m_oStatLog->DBUpdate($this->m_oChange);
		self::$m_oCurrentTask = null;
		return $bContinue;
	}

	/**
	 * Do the synchronization job #1: Obsolete replica "untouched" for some time
	 * @param integer $iMaxReplica Limit the number of replicas to process 
	 * @param integer $iCurrPos Current position where to resume the processing 
	 * @return true if the process must be continued
	 */
	protected function DoJob1($iMaxReplica = null, $iCurrPos = -1)
	{
		$sLimitDate = $this->m_oLastFullLoadStartDate->Format('Y-m-d H:i:s');

		// Get all the replicas that were not seen in the last import and mark them as obsolete
		$sDeletePolicy = $this->m_oDataSource->Get('delete_policy');
		if ($sDeletePolicy != 'ignore')
		{
			$sSelectToObsolete  = "SELECT SynchroReplica WHERE id > :curr_pos AND sync_source_id = :source_id AND status IN ('new', 'synchronized', 'modified', 'orphan') AND status_last_seen < :last_import";
			$oSetScope = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToObsolete), array() /* order by*/, array('source_id' => $this->m_oDataSource->GetKey(), 'last_import' => $sLimitDate, 'curr_pos' => $iCurrPos));
			$iCountScope = $oSetScope->Count();
			if (($this->m_iCountAllReplicas > 10) && ($this->m_iCountAllReplicas == $iCountScope) && MetaModel::GetConfig()->Get('synchro_prevent_delete_all'))
			{
				throw new SynchroExceptionNotStarted(Dict::S('Core:SyncTooManyMissingReplicas'));
			} 

			if ($iMaxReplica)
			{
				// Consider a given subset, starting from replica iCurrPos, limited to the count of iMaxReplica
				// The replica have to be ordered by id
				$oSetToProcess = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToObsolete), array('id'=>true) /* order by*/, array('source_id' => $this->m_oDataSource->GetKey(), 'last_import' => $sLimitDate, 'curr_pos' => $iCurrPos));
				$oSetToProcess->SetLimit($iMaxReplica);
			}
			else
			{
				$oSetToProcess = $oSetScope;
			}

			$iLastReplicaProcessed = -1;
			while($oReplica = $oSetToProcess->Fetch())
			{
				$iLastReplicaProcessed = $oReplica->GetKey();
				switch ($sDeletePolicy)
				{
				case 'update':
				case 'update_then_delete':
					$this->m_oStatLog->AddTrace("Destination object to be updated", $oReplica);
					$aToUpdate = array();
					$aToUpdateSpec = explode(';', $this->m_oDataSource->Get('delete_policy_update')); //ex: 'status:obsolete;description:stopped',
					foreach($aToUpdateSpec as $sUpdateSpec)
					{
						$aUpdateSpec = explode(':', $sUpdateSpec);
						if (count($aUpdateSpec) == 2)
						{
							$sAttCode = $aUpdateSpec[0];
							$sValue = $aUpdateSpec[1];
							$aToUpdate[$sAttCode] = $sValue;
						}
					}
					$oReplica->Set('status_last_error', '');
					if ($oReplica->Get('dest_id') == '')
					{
						$oReplica->Set('status', 'obsolete');
						$this->m_oStatLog->Inc('stats_nb_replica_disappeared_no_action');
					}
					else
					{
						$oReplica->UpdateDestObject($aToUpdate, $this->m_oChange, $this->m_oStatLog);
						if ($oReplica->Get('status_last_error') == '')
						{
							// Change the status of the replica IIF
							$oReplica->Set('status', 'obsolete');
						}
					}
					$oReplica->DBUpdateTracked($this->m_oChange);
					break;
	
	         	case 'delete':
	         	default:
					$this->m_oStatLog->AddTrace("Destination object to be DELETED", $oReplica);
					$oReplica->DeleteDestObject($this->m_oChange, $this->m_oStatLog);
				}
			}
			if ($iMaxReplica)
			{
				if ($iMaxReplica < $iCountScope)
				{
					// Continue with this job!
					$this->m_oStatLog->Set('status_curr_pos', $iLastReplicaProcessed);
					return true;
				}
			}
		} // if ($sDeletePolicy != 'ignore'

		//Count "seen" objects
		$sSelectSeen  = "SELECT SynchroReplica WHERE sync_source_id = :source_id AND status IN ('new', 'synchronized', 'modified', 'orphan') AND status_last_seen >= :last_import";
		$oSetSeen = new DBObjectSet(DBObjectSearch::FromOQL($sSelectSeen), array() /* order by*/, array('source_id' => $this->m_oDataSource->GetKey(), 'last_import' => $sLimitDate));
		$this->m_oStatLog->Set('stats_nb_replica_seen', $oSetSeen->Count());


		// Job complete!
		$this->m_oStatLog->Set('status_curr_job', 2);
		$this->m_oStatLog->Set('status_curr_pos', -1);
		return false;
	}

	/**
	 * Do the synchronization job #2: Create and modify object for new/modified replicas
	 * @param integer $iMaxReplica Limit the number of replicas to process 
	 * @param integer $iCurrPos Current position where to resume the processing 
	 * @return true if the process must be continued
	 */
	protected function DoJob2($iMaxReplica = null, $iCurrPos = -1)
	{
		$sLimitDate = $this->m_oLastFullLoadStartDate->Format('Y-m-d H:i:s');

		// Get all the replicas that are 'new' or modified or synchronized with a warning
		//
		$sSelectToSync  = "SELECT SynchroReplica WHERE id > :curr_pos AND (status = 'new' OR status = 'modified' OR (status = 'synchronized' AND status_last_warning != '')) AND sync_source_id = :source_id AND status_last_seen >= :last_import";
		$oSetScope = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToSync), array() /* order by*/, array('source_id' => $this->m_oDataSource->GetKey(), 'last_import' => $sLimitDate, 'curr_pos' => $iCurrPos), $this->m_aExtDataSpec);
		$iCountScope = $oSetScope->Count();

		if ($iMaxReplica)
		{
			// Consider a given subset, starting from replica iCurrPos, limited to the count of iMaxReplica
			// The replica have to be ordered by id
			$oSetToProcess = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToSync), array('id'=>true) /* order by*/, array('source_id' => $this->m_oDataSource->GetKey(), 'last_import' => $sLimitDate, 'curr_pos' => $iCurrPos), $this->m_aExtDataSpec);
			$oSetToProcess->SetLimit($iMaxReplica);
		}
		else
		{
			$oSetToProcess = $oSetScope;
		}

		$iLastReplicaProcessed = -1;
		while($oReplica = $oSetToProcess->Fetch())
		{
			$iLastReplicaProcessed = $oReplica->GetKey();
			$oReplica->Synchro($this->m_oDataSource, $this->m_aReconciliationKeys, $this->m_aAttributes, $this->m_oChange, $this->m_oStatLog);
			$oReplica->DBUpdateTracked($this->m_oChange);			
		}
		
		if ($iMaxReplica)
		{
			if ($iMaxReplica < $iCountScope)
			{
				// Continue with this job!
				$this->m_oStatLog->Set('status_curr_pos', $iLastReplicaProcessed);
				return true;
			}
		}

		// Job complete!
		$this->m_oStatLog->Set('status_curr_job', 3);
		$this->m_oStatLog->Set('status_curr_pos', -1);
		return false;
	}

	/**
	 * Do the synchronization job #3: Delete replica depending on the obsolescence scheme
	 * @param integer $iMaxReplica Limit the number of replicas to process 
	 * @param integer $iCurrPos Current position where to resume the processing 
	 * @return true if the process must be continued
	 */
	protected function DoJob3($iMaxReplica = null, $iCurrPos = -1)
	{
		$sDeletePolicy = $this->m_oDataSource->Get('delete_policy');
		if ($sDeletePolicy != 'update_then_delete')
		{
			// Job complete!
			$this->m_oStatLog->Set('status_curr_job', 0);
			$this->m_oStatLog->Set('status_curr_pos', -1);
			return false;
		}

		$bFirstPass = ($iCurrPos == -1);

		// Get all the replicas that are to be deleted
		//
		$oDeletionDate = $this->m_oLastFullLoadStartDate;
		$iDeleteRetention = $this->m_oDataSource->Get('delete_policy_retention'); // Duration in seconds
		if ($iDeleteRetention > 0)
		{
			$sInterval = "-$iDeleteRetention seconds";
			$oDeletionDate->Modify($sInterval);
		}
		$sDeletionDate = $oDeletionDate->Format('Y-m-d H:i:s');	
		if ($bFirstPass)
		{
			$this->m_oStatLog->AddTrace("Deletion date: $sDeletionDate");
		}
		$sSelectToDelete = "SELECT SynchroReplica WHERE id > :curr_pos AND sync_source_id = :source_id AND status IN ('obsolete') AND status_last_seen < :last_import";
		$oSetScope = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToDelete), array() /* order by*/, array('source_id' => $this->m_oDataSource->GetKey(), 'last_import' => $sDeletionDate, 'curr_pos' => $iCurrPos));
		$iCountScope = $oSetScope->Count();

		if ($iMaxReplica)
		{
			// Consider a given subset, starting from replica iCurrPos, limited to the count of iMaxReplica
			// The replica have to be ordered by id
			$oSetToProcess = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToDelete), array('id'=>true) /* order by*/, array('source_id' => $this->m_oDataSource->GetKey(), 'last_import' => $sDeletionDate, 'curr_pos' => $iCurrPos));
			$oSetToProcess->SetLimit($iMaxReplica);
		}
		else
		{
			$oSetToProcess = $oSetScope;
		}

		$iLastReplicaProcessed = -1;
		while($oReplica = $oSetToProcess->Fetch())
		{
			$iLastReplicaProcessed = $oReplica->GetKey();
			$this->m_oStatLog->AddTrace("Destination object to be DELETED", $oReplica);
			$oReplica->DeleteDestObject($this->m_oChange, $this->m_oStatLog);
		}

		if ($iMaxReplica)
		{
			if ($iMaxReplica < $iCountScope)
			{
				// Continue with this job!
				$this->m_oStatLog->Set('status_curr_pos', $iLastReplicaProcessed);
				return true;
			}
		}
		// Job complete!
		$this->m_oStatLog->Set('status_curr_job', 0);
		$this->m_oStatLog->Set('status_curr_pos', -1);
		return false;
	}
}

	$oAdminMenu = new MenuGroup('AdminTools', 80 /* fRank */, 'SynchroDataSource', UR_ACTION_MODIFY, UR_ALLOWED_YES);
	new OQLMenuNode('DataSources', 'SELECT SynchroDataSource', $oAdminMenu->GetIndex(), 12 /* fRank */, true, 'SynchroDataSource', UR_ACTION_MODIFY, UR_ALLOWED_YES);
//	new OQLMenuNode('Replicas', 'SELECT SynchroReplica', $oAdminMenu->GetIndex(), 12 /* fRank */, true, 'SynchroReplica', UR_ACTION_MODIFY, UR_ALLOWED_YES);
//	new WebPageMenuNode('Test:RunSynchro', '../synchro/synchro_exec.php', $oAdminMenu->GetIndex(), 13 /* fRank */, 'SynchroDataSource');
?>