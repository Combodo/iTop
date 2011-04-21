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
		
		// Declared here for a future usage, but ignored so far
		MetaModel::Init_AddAttribute(new AttributeString("scope_restriction", array("allowed_values"=>null, "sql"=>"scope_restriction", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		
		//MetaModel::Init_AddAttribute(new AttributeDateTime("last_synchro_date", array("allowed_values"=>null, "sql"=>"last_synchro_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Format: seconds (int)
		MetaModel::Init_AddAttribute(new AttributeDuration("full_load_periodicity", array("allowed_values"=>null, "sql"=>"full_load_periodicity", "default_value"=>86400, "is_null_allowed"=>true, "depends_on"=>array())));
		
//		MetaModel::Init_AddAttribute(new AttributeString("reconciliation_list", array("allowed_values"=>null, "sql"=>"reconciliation_list", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("reconciliation_policy", array("allowed_values"=>new ValueSetEnum('use_primary_key,use_attributes'), "sql"=>"reconciliation_policy", "default_value"=>"use_attributes", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_zero", array("allowed_values"=>new ValueSetEnum('create,error'), "sql"=>"action_on_zero", "default_value"=>"create", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_one", array("allowed_values"=>new ValueSetEnum('update,error'), "sql"=>"action_on_one", "default_value"=>"update", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("action_on_multiple", array("allowed_values"=>new ValueSetEnum('take_first,create,error'), "sql"=>"action_on_multiple", "default_value"=>"error", "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeEnum("delete_policy", array("allowed_values"=>new ValueSetEnum('ignore,delete,update,update_then_delete'), "sql"=>"delete_policy", "default_value"=>"ignore", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("delete_policy_update", array("allowed_values"=>null, "sql"=>"delete_policy_update", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Format: seconds (unsigned int)
		MetaModel::Init_AddAttribute(new AttributeDuration("delete_policy_retention", array("allowed_values"=>null, "sql"=>"delete_policy_retention", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSet("attribute_list", array("linked_class"=>"SynchroAttribute", "ext_key_to_me"=>"sync_source_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		// Not used yet !
		MetaModel::Init_AddAttribute(new AttributeEnum("user_delete_policy", array("allowed_values"=>new ValueSetEnum('everybody,administrators,nobody'), "sql"=>"user_delete_policy", "default_value"=>"nobody", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeURL("url_icon", array("allowed_values"=>null, "sql"=>"url_icon", "default_value"=>null, "is_null_allowed"=>true, "target"=> '_top', "depends_on"=>array())));
		// The field below is not a real URL since it can contain placeholders like $replica->primary_key$ which are not syntactically allowed in a real URL
		MetaModel::Init_AddAttribute(new AttributeString("url_application", array("allowed_values"=>null, "sql"=>"url_application", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array(
			'col:0'=> array(
				'fieldset:SynchroDataSource:Description' => array('name','description','status','scope_class','user_id','notify_contact_id','url_icon','url_application')),
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
				if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
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
						else
						{
							$oAttribute = new SynchroAttribute();
						}
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
						$aRow['attcode'] = MetaModel::GetLabel($this->GetTargetClass(), $oAttribute->Get('attcode'));
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
	function UpdateSynoptics(id)
	{
		var aValues = aSynchroLog[id];
		
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
			'obj_updated_errors' => $oLastLog->Get('stats_nb_obj_updated_errors'),
			'obj_new_updated' => $oLastLog->Get('stats_nb_obj_new_updated'),
			'obj_new_unchanged' => $oLastLog->Get('stats_nb_obj_new_unchanged'),
			'obj_created' => $oLastLog->Get('stats_nb_obj_created'),
			'obj_created_errors' => $oLastLog->Get('stats_nb_obj_created_errors'),
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
	
	public function GetAttributeFlags($sAttCode, &$aReasons = array())
	{
		if (($sAttCode == 'scope_class') && (!$this->IsNew()))
		{
			return OPT_ATT_READONLY;
		}
		return parent::GetAttributeFlags($sAttCode, $aReasons);
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
		$aReconciliation = utils::ReadPostedParam('attr_reconciliation_attcode', array());
		// update_policy cannot be empty, so there is one entry per attribute, use this to iterate
		// through all the writable attributes
		foreach($aUpdatePolicy as $sAttCode => $sValue)
		{
			if(!isset($aAttributes[$sAttCode]))
			{
				$oAttDef = MetaModel::GetAttributeDef($this->GetTargetClass(), $sAttCode);
				if ($oAttDef->IsExternalKey())
				{
					$oAttribute = new SynchroAttExtKey();
					$oAttribute->Set('reconciliation_attcode', ''); // Blank means by pkey
				}
				else
				{
					$oAttribute = new SynchroAttribute();
				}
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
			if ($oAttribute instanceof SynchroAttExtKey)
			{
				$oAttribute->Set('reconciliation_attcode', $aReconciliation[$sAttCode]);
			}
			$oAttributeSet->AddObject($oAttribute);
		}
		$this->Set('attribute_list', $oAttributeSet);
	}
	
	/*
	* Overload the standard behavior
	*/	
	public function ComputeValues()
	{
		parent::ComputeValues();

		if ($this->IsNew())
		{
			// When inserting a new datasource object, also create the SynchroAttribute objects
			// for each field of the target class
			// Create all the SynchroAttribute records
			$oAttributeSet = $this->Get('attribute_list');
			if ($oAttributeSet->Count() == 0)
			{
				foreach(MetaModel::ListAttributeDefs($this->GetTargetClass()) as $sAttCode=>$oAttDef)
				{
					if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
					{
						$oAttDef = MetaModel::GetAttributeDef($this->GetTargetClass(), $sAttCode);
						if ($oAttDef->IsExternalKey())
						{
							$oAttribute = new SynchroAttExtKey();
							$oAttribute->Set('reconciliation_attcode', ''); // Blank means by pkey
						}
						else
						{
							$oAttribute = new SynchroAttribute();
						}
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
	 * @param Hash $aTraces Debugs/Trace information, one or more entries per replica
	 * @param DateTime $oLastFullLoadStartDate Date of the last full load (start date/time), if known
	 * @return void
	 */
	public function Synchronize($oLastFullLoadStartDate = null)
	{
		// Create a change used for logging all the modifications/creations happening during the synchro
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$sUserString = CMDBChange::GetCurrentUserName();
		$oMyChange->Set("userinfo", $sUserString.' '.Dict::S('Core:SyncDataExchangeComment'));
		$iChangeId = $oMyChange->DBInsert();

		// Start logging this execution (stats + protection against reentrance)
		//
		$oStatLog = new SynchroLog();
		$oStatLog->Set('sync_source_id', $this->GetKey());
		$oStatLog->Set('start_date', time());
		$oStatLog->Set('status', 'running');
		$oStatLog->Set('stats_nb_replica_seen', 0);
		$oStatLog->Set('stats_nb_replica_total', 0);
		$oStatLog->Set('stats_nb_obj_deleted', 0);
		$oStatLog->Set('stats_nb_obj_deleted_errors', 0);
		$oStatLog->Set('stats_nb_obj_obsoleted', 0);
		$oStatLog->Set('stats_nb_obj_obsoleted_errors', 0);
		$oStatLog->Set('stats_nb_obj_created', 0);
		$oStatLog->Set('stats_nb_obj_created_errors', 0);
		$oStatLog->Set('stats_nb_obj_updated', 0);
		$oStatLog->Set('stats_nb_obj_updated_errors', 0);
//		$oStatLog->Set('stats_nb_replica_reconciled', 0);
		$oStatLog->Set('stats_nb_replica_reconciled_errors', 0);
		$oStatLog->Set('stats_nb_replica_disappeared_no_action', 0);
		$oStatLog->Set('stats_nb_obj_new_updated', 0);
		$oStatLog->Set('stats_nb_obj_new_unchanged',0);
		
		$sSelectTotal  = "SELECT SynchroReplica WHERE sync_source_id = :source_id";
		$oSetTotal = new DBObjectSet(DBObjectSearch::FromOQL($sSelectTotal), array() /* order by*/, array('source_id' => $this->GetKey()));
		$oStatLog->Set('stats_nb_replica_total', $oSetTotal->Count());

		$oStatLog->DBInsertTracked($oMyChange);

		self::$m_oCurrentTask = $this;
		try
		{
			$this->DoSynchronize($oLastFullLoadStartDate, $oMyChange, $oStatLog);

			$oStatLog->Set('end_date', time());
			$oStatLog->Set('status', 'completed');
			$oStatLog->DBUpdateTracked($oMyChange);
		}
		catch (SynchroExceptionNotStarted $e)
		{
			// Set information for reporting... but delete the object in DB
			$oStatLog->Set('end_date', time());
			$oStatLog->Set('status', 'error');
			$oStatLog->Set('last_error', $e->getMessage());
			$oStatLog->DBDeleteTracked($oMyChange);
		}
		catch (Exception $e)
		{
			$oStatLog->Set('end_date', time());
			$oStatLog->Set('status', 'error');
			$oStatLog->Set('last_error', $e->getMessage());
			$oStatLog->DBUpdateTracked($oMyChange);
		}
		self::$m_oCurrentTask = null;
		return $oStatLog;
	}

	protected function DoSynchronize($oLastFullLoadStartDate, $oMyChange, &$oStatLog)
	{
		if ($this->Get('status') == 'obsolete')
		{
			throw new SynchroExceptionNotStarted(Dict::S('Core:SyncDataSourceObsolete'));
		}
		if (!UserRights::IsAdministrator() && $this->Get('user_id') != UserRights::GetUserId())
		{
			throw new SynchroExceptionNotStarted(Dict::S('Core:SyncDataSourceAccessRestriction'));
		}

		// Get the list of SQL columns
		$sClass = $this->GetTargetClass();
		$aAttCodesExpected = array();
		$aAttCodesToReconcile = array();
		$aAttCodesToUpdate = array();
		$sSelectAtt  = "SELECT SynchroAttribute WHERE sync_source_id = :source_id AND (update = 1 OR reconcile = 1)";
		$oSetAtt = new DBObjectSet(DBObjectSearch::FromOQL($sSelectAtt), array() /* order by*/, array('source_id' => $this->GetKey()) /* aArgs */);
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
		$aColumns = $this->GetSQLColumns(array_keys($aAttCodesExpected));
		$aExtDataFields = array_keys($aColumns);
		$aExtDataFields[] = 'primary_key';
		$aExtDataSpec = array(
			'table' => $this->GetDataTable(),
			'join_key' => 'id',
			'fields' => $aExtDataFields
		);

		// Get the list of attributes, determine reconciliation keys and update targets
		//
		if ($this->Get('reconciliation_policy') == 'use_attributes')
		{
			$aReconciliationKeys = $aAttCodesToReconcile;
		}
		elseif ($this->Get('reconciliation_policy') == 'use_primary_key')
		{
			// Override the settings made at the attribute level !
			$aReconciliationKeys = array("primary_key" => null);
		}

		$oStatLog->AddTrace("Update of: {".implode(', ', array_keys($aAttCodesToUpdate))."}");
		$oStatLog->AddTrace("Reconciliation on: {".implode(', ', array_keys($aReconciliationKeys))."}");

		if (count($aAttCodesToUpdate) == 0)
		{
			$oStatLog->AddTrace("No attribute to update");
			throw new SynchroExceptionNotStarted('There is no attribute to update');
		}
		if (count($aReconciliationKeys) == 0)
		{
			$oStatLog->AddTrace("No attribute for reconciliation");
			throw new SynchroExceptionNotStarted('No attribute for reconciliation');
		}
		
		$aAttributes = array();
		foreach($aAttCodesToUpdate as $sAttCode => $oSyncAtt)
		{
			$oAttDef = MetaModel::GetAttributeDef($this->GetTargetClass(), $sAttCode);
			if ($oAttDef->IsWritable() && $oAttDef->IsScalar())
			{
				$aAttributes[$sAttCode] = $oSyncAtt;
			}
		}

		$sDeletePolicy = $this->Get('delete_policy');

		// Count the replicas
		$sSelectAll  = "SELECT SynchroReplica WHERE sync_source_id = :source_id";
		$oSetAll = new DBObjectSet(DBObjectSearch::FromOQL($sSelectAll), array() /* order by*/, array('source_id' => $this->GetKey()));
		$iCountAllReplicas = $oSetAll->Count();
		$oStatLog->Set('stats_nb_replica_total', $iCountAllReplicas);

		// Get all the replicas that were not seen in the last import and mark them as obsolete
		if ($oLastFullLoadStartDate == null)
		{
			// No previous import known, use the full_load_periodicity value... and the current date
			$oLastFullLoadStartDate = new DateTime(); // Now
			$iLoadPeriodicity = $this->Get('full_load_periodicity'); // Duration in seconds
			if ($iLoadPeriodicity > 0)
			{
				$sInterval = "-$iLoadPeriodicity seconds";
				$oLastFullLoadStartDate->Modify($sInterval);
			}
		}
		$sLimitDate = $oLastFullLoadStartDate->Format('Y-m-d H:i:s');	
		$oStatLog->AddTrace("Limit Date: $sLimitDate");
		$sSelectToObsolete  = "SELECT SynchroReplica WHERE sync_source_id = :source_id AND status IN ('new', 'synchronized', 'modified', 'orphan') AND status_last_seen < :last_import";
		$oSetToObsolete = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToObsolete), array() /* order by*/, array('source_id' => $this->GetKey(), 'last_import' => $sLimitDate));
		if (($iCountAllReplicas > 10) && ($iCountAllReplicas == $oSetToObsolete->Count()))
		{
			throw new SynchroExceptionNotStarted(Dict::S('Core:SyncTooManyMissingReplicas'));
		} 
		while($oReplica = $oSetToObsolete->Fetch())
		{
			switch ($sDeletePolicy)
			{
			case 'update':
			case 'update_then_delete':
				$oStatLog->AddTrace("Destination object to be updated", $oReplica);
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
				$oReplica->Set('status', 'obsolete');
				$oReplica->DBUpdateTracked($oMyChange);
				break;

         	case 'delete':
         	default:
				$oStatLog->AddTrace("Destination object to be DELETED", $oReplica);
				$oReplica->DeleteDestObject($oMyChange, $oStatLog);
			}
		}

		//Count "seen" objects
		$sSelectSeen  = "SELECT SynchroReplica WHERE sync_source_id = :source_id AND status IN ('new', 'synchronized', 'modified', 'orphan') AND status_last_seen >= :last_import";
		$oSetSeen = new DBObjectSet(DBObjectSearch::FromOQL($sSelectSeen), array() /* order by*/, array('source_id' => $this->GetKey(), 'last_import' => $sLimitDate));
		$oStatLog->Set('stats_nb_replica_seen', $oSetSeen->Count());
		
		// Get all the replicas that are 'new' or modified
		//
		$sSelectToSync  = "SELECT SynchroReplica WHERE (status = 'new' OR status = 'modified') AND sync_source_id = :source_id";
		$oSetToSync = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToSync), array() /* order by*/, array('source_id' => $this->GetKey()) /* aArgs */, $aExtDataSpec, 0 /* limitCount */, 0 /* limitStart */);

		while($oReplica = $oSetToSync->Fetch())
		{
			$oReplica->Synchro($this, $aReconciliationKeys, $aAttributes, $oMyChange, $oStatLog);			
		}
		
		// Get all the replicas that are to be deleted
		//
		if ($sDeletePolicy == 'update_then_delete')
		{
			$oDeletionDate = $oLastFullLoadStartDate;
			$iDeleteRetention = $this->Get('delete_policy_retention'); // Duration in seconds
			if ($iDeleteRetention > 0)
			{
				$sInterval = "-$iDeleteRetention seconds";
				$oDeletionDate->Modify($sInterval);
			}
			$sDeletionDate = $oDeletionDate->Format('Y-m-d H:i:s');	
			$oStatLog->AddTrace("Deletion date: $sDeletionDate");
			$sSelectToDelete  = "SELECT SynchroReplica WHERE sync_source_id = :source_id AND status IN ('obsolete') AND status_last_seen < :last_import";
			$oSetToDelete = new DBObjectSet(DBObjectSearch::FromOQL($sSelectToDelete), array() /* order by*/, array('source_id' => $this->GetKey(), 'last_import' => $sDeletionDate));
			while($oReplica = $oSetToDelete->Fetch())
			{
				$oStatLog->AddTrace("Destination object to be DELETED", $oReplica);
				$oReplica->DeleteDestObject($oMyChange, $oStatLog);
			}
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
			
			if ($oAttDef->IsExternalKey())
			{
				// The pkey might be used as well as any other key column
				$aColumns[$sAttCode] = 'VARCHAR (255)';
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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("sync_source_id", array("targetclass"=>"SynchroDataSource", "jointype"=> "", "allowed_values"=>null, "sql"=>"sync_source_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_SILENT, "depends_on"=>array())));
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

		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_replica_seen", array("allowed_values"=>null, "sql"=>"stats_nb_replica_seen", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_replica_total", array("allowed_values"=>null, "sql"=>"stats_nb_replica_total", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_deleted", array("allowed_values"=>null, "sql"=>"stats_nb_obj_deleted", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_deleted_errors", array("allowed_values"=>null, "sql"=>"stats_deleted_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_obsoleted", array("allowed_values"=>null, "sql"=>"stats_nb_obj_obsoleted", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_obsoleted_errors", array("allowed_values"=>null, "sql"=>"stats_nb_obj_obsoleted_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_created", array("allowed_values"=>null, "sql"=>"stats_nb_obj_created", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_created_errors", array("allowed_values"=>null, "sql"=>"stats_nb_obj_created_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_updated", array("allowed_values"=>null, "sql"=>"stats_nb_obj_updated", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_updated_errors", array("allowed_values"=>null, "sql"=>"stats_nb_obj_updated_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
//		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_replica_reconciled", array("allowed_values"=>null, "sql"=>"stats_nb_replica_reconciled", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_replica_reconciled_errors", array("allowed_values"=>null, "sql"=>"stats_nb_replica_reconciled_errors", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_replica_disappeared_no_action", array("allowed_values"=>null, "sql"=>"stats_nb_replica_disappeared_no_action", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_new_updated", array("allowed_values"=>null, "sql"=>"stats_nb_obj_new_updated", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("stats_nb_obj_new_unchanged", array("allowed_values"=>null, "sql"=>"stats_nb_obj_new_unchanged", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("last_error", array("allowed_values"=>null, "sql"=>"last_error", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLongText("traces", array("allowed_values"=>null, "sql"=>"traces", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('sync_source_id', 'start_date', 'end_date', 'status', 'stats_nb_replica_total', 'stats_nb_replica_seen', 'stats_nb_obj_created', /*'stats_nb_replica_reconciled',*/ 'stats_nb_obj_updated', 'stats_nb_obj_obsoleted', 'stats_nb_obj_deleted',
														'stats_nb_obj_created_errors', 'stats_nb_replica_reconciled_errors', 'stats_nb_replica_disappeared_no_action', 'stats_nb_obj_updated_errors', 'stats_nb_obj_obsoleted_errors', 'stats_nb_obj_deleted_errors', 'stats_nb_obj_new_unchanged', 'stats_nb_obj_new_updated', 'traces')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('sync_source_id', 'start_date', 'end_date', 'status', 'stats_nb_replica_seen')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
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

		$oAttDef = MetaModel::GetAttributeDef(get_class($this), 'traces');
		$iMaxSize = $oAttDef->GetMaxSize();
		$sTrace = implode("\n", $this->m_aTraces);
		if (strlen($sTrace) >= $iMaxSize)
		{
			$sTrace = substr($sTrace, 0, $iMaxSize - 255)."...\nTruncated (size: ".strlen($sTrace).')';
		}
		$this->Set('traces', $sTrace);
	}

	protected function OnInsert()
	{
		$this->TraceToText();
		parent::OnInsert();
	}

	protected function OnUpdate()
	{
		$this->TraceToText();
		parent::OnUpdate();
	}
}


class SynchroReplica extends DBObject implements iDisplay
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

		MetaModel::Init_AddAttribute(new AttributeExternalKey("sync_source_id", array("targetclass"=>"SynchroDataSource", "jointype"=> "", "allowed_values"=>null, "sql"=>"sync_source_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_SILENT, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("base_class", array("allowed_values"=>null, "extkey_attcode"=> 'sync_source_id', "target_attcode"=>"scope_class")));

		MetaModel::Init_AddAttribute(new AttributeInteger("dest_id", array("allowed_values"=>null, "sql"=>"dest_id", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeClass("dest_class", array("class_category"=>"", "more_values"=>"", "sql"=>"dest_class", "default_value"=>'Organization', "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDateTime("status_last_seen", array("allowed_values"=>null, "sql"=>"status_last_seen", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('new,synchronized,modified,orphan,obsolete'), "sql"=>"status", "default_value"=>"new", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("status_dest_creator", array("allowed_values"=>null, "sql"=>"status_dest_creator", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("status_last_error", array("allowed_values"=>null, "sql"=>"status_last_error", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDateTime("info_creation_date", array("allowed_values"=>null, "sql"=>"info_creation_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("info_last_modified", array("allowed_values"=>null, "sql"=>"info_last_modified", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('' .
			'col:0'=> array(
				'fieldset:SynchroDataSource:Definition' => array('sync_source_id','dest_id','dest_class'),
				'fieldset:SynchroDataSource:Status' => array('status','status_last_seen','status_dest_creator','status_last_error'),
				'fieldset:SynchroDataSource:Information' => array('info_creation_date','info_last_modified'))
			)
		);
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
	protected function DBDeleteSingleObject()
	{
		$this->OnDelete();

		if (!MetaModel::DBIsReadOnly())
		{
			$oDataSource = MetaModel::GetObject('SynchroDataSource', $this->Get('sync_source_id'));
			$sTable = $oDataSource->GetDataTable();
	
			$sSQL = "DELETE FROM `$sTable` WHERE id = '{$this->GetKey()}'";
			CMDBSource::Query($sSQL);
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
					// Reconciliation could not be performed - log and EXIT
					$oStatLog->AddTrace("Could not reconcile on null value: ".$sFilterCode, $this);
					$this->SetLastError('Could not reconcile on null value: '.$sFilterCode);
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
					$this->CreateObjectFromReplica($oDataSource->GetTargetClass(), $aAttributes, $oChange, $oStatLog);
				}
				else // assumed to be 'error'
				{
					$oStatLog->AddTrace("Failed to reconcile (no match)", $this);
					$this->SetLastError('Could not find a match for reconciliation');
					$oStatLog->Inc('stats_nb_replica_reconciled_errors');
				}
				break;
				
				case 1:
				$oStatLog->AddTrace("Found 1 object on: $sConditionDesc", $this);
				if ($oDataSource->Get('action_on_one') == 'update')
				{
					$oDestObj = $oDestSet->Fetch();
					$this->UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, $oStatLog, 'stats_nb_obj_new', 'stats_nb_replica_reconciled_errors');
					$this->Set('dest_id', $oDestObj->GetKey());
					$this->Set('dest_class', get_class($oDestObj));
				}
				else
				{
					// assumed to be 'error'
					$oStatLog->AddTrace("Failed to reconcile (1 match)", $this);
					$this->SetLastError('Found a match while expecting several');
					$oStatLog->Inc('stats_nb_replica_reconciled_errors');
				}
				break;
				
				default:
				$oStatLog->AddTrace("Found $iCount objects on: $sConditionDesc", $this);
				if ($oDataSource->Get('action_on_multiple') == 'error')
				{
					$oStatLog->AddTrace("Failed to reconcile (N>1 matches)", $this);
					$this->SetLastError($iCount.' destination objects match the reconciliation criterias: '.$sConditionDesc);
					$oStatLog->Inc('stats_nb_replica_reconciled_errors');
				}
				elseif ($oDataSource->Get('action_on_multiple') == 'create')
				{
					$this->CreateObjectFromReplica($oDataSource->GetTargetClass(), $aAttributes, $oChange, $oStatLog);
				}
				else
				{
					// assumed to be 'take_first'
					$oDestObj = $oDestSet->Fetch();
					$this->UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, $oStatLog, 'stats_nb_obj_new', 'stats_nb_replica_reconciled_errors');
					$this->Set('dest_id', $oDestObj->GetKey());
					$this->Set('dest_class', get_class($oDestObj));
				}
			}
			break;
			
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
				$this->UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, $oStatLog, 'stats_nb_obj', 'stats_nb_obj_updated_errors');
			}
			break;
			
			default: // Do nothing in all other cases
		}
		$this->DBUpdateTracked($oChange);
	}
	
	/**
	 * Updates the destination object with the Extended data found in the synchro_data_XXXX table
	 */	
	protected function UpdateObjectFromReplica($oDestObj, $aAttributes, $oChange, &$oStatLog, $sStatsCode, $sStatsCodeError)
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
		try
		{
			// Really modified ?
			if ($oDestObj->IsModified())
			{
				$oDestObj->DBUpdateTracked($oChange);
				$oStatLog->AddTrace('Updated object - Values: {'.implode(', ', $aValueTrace).'}', $this);
				$oStatLog->Inc($sStatsCode.'_updated');
				$this->Set('info_last_modified', date('Y-m-d H:i:s'));
			}
			else
			{
				$oStatLog->AddTrace('Unchanged object', $this);
				$oStatLog->Inc($sStatsCode.'_unchanged');
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
	}

	/**
	 * Creates the destination object populating it with the Extended data found in the synchro_data_XXXX table
	 */	
	protected function CreateObjectFromReplica($sClass, $aAttributes, $oChange, &$oStatLog)
	{
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

			$oStatLog->AddTrace("Created (".implode(', ', $aValueTrace).")", $this);
			$oStatLog->Inc('stats_nb_obj_created');
		}
		catch(Exception $e)
		{
			$oStatLog->AddTrace("Failed to create $sClass ({$e->getMessage()})", $this);
			$this->SetLastError('Unable to create destination object: ', $e);
			$oStatLog->Inc('stats_nb_obj_created_errors');
		}
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
				$oDestObj->DBDeleteTracked($oChange);
				$this->DBDeleteTracked($oChange);
				$oStatLog->Inc('stats_nb_obj_deleted');
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
	 */
	protected function GetValueFromExtData($sAttCode, $oSyncAtt, &$oStatLog)
	{
		// $aData should contain attributes defined either for reconciliation or create/update
		$aData = $this->GetExtendedData();

		$sClass = $this->Get('base_class');
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);

		if (!is_null($oSyncAtt) && ($oSyncAtt instanceof SynchroAttExtKey))
		{
			$rawValue = $aData[$sAttCode];
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
					// Note: differs from null (in which case the value would be left unchanged)
					$oStatLog->AddTrace("Could not find [unique] object for '$sAttCode': searched on $sReconcAttCode = '$rawValue'", $this);
					$retValue = 0;
				}
			}
			else
			{
				$retValue = $rawValue;
			}
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
			$retValue = $oAttDef->FromImportToValue($aData, $sAttCode);
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
	
	function DisplayBareProperties(WebPage $oPage, $bEditMode = false)
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

	$oAdminMenu = new MenuGroup('AdminTools', 80 /* fRank */, 'SynchroDataSource', UR_ACTION_MODIFY, UR_ALLOWED_YES);
	new OQLMenuNode('DataSources', 'SELECT SynchroDataSource', $oAdminMenu->GetIndex(), 12 /* fRank */, true, 'SynchroDataSource', UR_ACTION_MODIFY, UR_ALLOWED_YES);
//	new OQLMenuNode('Replicas', 'SELECT SynchroReplica', $oAdminMenu->GetIndex(), 12 /* fRank */, true, 'SynchroReplica', UR_ACTION_MODIFY, UR_ALLOWED_YES);
//	new WebPageMenuNode('Test:RunSynchro', '../synchro/synchro_exec.php', $oAdminMenu->GetIndex(), 13 /* fRank */, 'SynchroDataSource');
?>