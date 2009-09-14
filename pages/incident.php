<?php
require_once('../application/application.inc.php');
require_once('../application/startup.inc.php');

require_once('../application/itopwizardwebpage.class.inc.php');


abstract class DialogWizard
{
	protected $m_sCurrentStep;
	protected $m_aSteps;
	
	public function __construct($sStep)
	{
		$this->m_sCurrentStep = $sStep;
	}
	
	protected function GetFields($sStep = '')
	{
		if ($sStep == '')
		{
			$sStep = $this->m_sCurrentStep;
		}
		return $this->m_aSteps[$sStep];
	}

	protected function AddContextToForm(web_page $oPage)
	{
		// Store as hidden fields in the page all the variables from the previous steps
		foreach($this->m_aSteps as $sStep => $aFields)
		{
			if ($sStep == $this->m_sCurrentStep) continue;
			foreach($aFields as $sAttName => $sFieldName)
			{
				$oPage->add("<input type=\"hidden\" name=\"$sFieldName\" value=\"".htmlentities(Utils::ReadParam($sFieldName, ''))."\" />\n");
			}
		}
	}
	
	function GetObjectPicker(web_page $oPage, $sTitle, $sFieldName, $sClass)
	{
		$sScript =
<<<EOF
		function UpdateObjectList(sClass)
		{
			var sRelatedObjectIds = new String($('#related_object_ids').val());
			if (sRelatedObjectIds.length > 0)
			{
				aRelatedObjectIds = sRelatedObjectIds.split(' ');
			}
			else
			{
				aRelatedObjectIds = new Array();
				aRelatedObjectIds[0] = 0;
			}
			var sibusql = sClass+": pkey IN {" + aRelatedObjectIds.join(", ") + "}";
			$.get("ajax.render.php?filter=" + sibusql + "&style=list&encoding=sibusql",
			   { operation: "ajax" },
			   function(data){
				 $("#related_objects").empty();
				 $("#related_objects").append(data);
				 $("#related_objects").removeClass("loading");
				});
		}
		
		function AddObject(sClass)
		{
			var sRelatedObjectIds = new String($('#related_object_ids').val());
			var sCurrentObjectId = new String($('#ac_current_object_id').val());
			if (sRelatedObjectIds.length > 0)
			{
				aRelatedObjectIds = sRelatedObjectIds.split(' ');
			}
			else
			{
				aRelatedObjectIds = new Array();
			}
			// To do: check if the ID is not already in the list...
			aRelatedObjectIds[aRelatedObjectIds.length] = sCurrentObjectId;
			// Update the form & reload the list
			$('#related_object_ids').val(aRelatedObjectIds.join(' '));
			UpdateObjectList(sClass);
		}
		
		function ManageObjects(sTitle, sClass, sInputId)
		{
			$('#Manage_DlgTitle').text(sTitle);
			sObjList = new String($('#'+sInputId).val());
			if (sObjList == '')
			{
				sObjList = new String('0');
			}
			var aObjList = sObjList.split(' ');
			Manage_LoadSelect('selected_objects', sClass+': pkey IN {' + aObjList.join(', ') + '}');
			Manage_LoadSelect('available_objects', sClass);
			$('#ManageObjectsDlg').jqmShow();
		}
		
		function Manage_LoadSelect(sSelectedId, sFilter)
		{
		 	$('#'+sSelectedId).addClass('loading');
			$.get('ajax.render.php?filter=' + sFilter,
			   { operation: 'combo_options' },
			   function(data){
				 $('#'+sSelectedId).empty();
				 $('#'+sSelectedId).append(data);
				 $('#'+sSelectedId).removeClass('loading');
				}
			 );
		}
		
		function Manage_SwapSelectedObjects(oSourceSelect, oDestinationSelect)
		{
			for (i=oSourceSelect.length-1;i>=0;i--) // Count down because we are removing the indexes from the combo
			{
				if (oSourceSelect.options[i].selected)
				{
					var newOption = document.createElement('option');
					newOption.text = oSourceSelect.options[i].text;
					newOption.value = oSourceSelect.options[i].value;
					oDestinationSelect.add(newOption, null);
					oSourceSelect.remove(i);
				}
			}
			Manage_UpdateButtons();
		}
		
		function Manage_UpdateButtons()
		{
			var oSrc = document.getElementById('available_objects');
			var oAddBtn = document.getElementById('btn_add_objects')
			var oDst = document.getElementById('selected_objects');
			var oRemoveBtn = document.getElementById('btn_remove_objects')
			if (oSrc.selectedIndex == -1)
			{
				oAddBtn.disabled = true;
			}
			else
			{
				oAddBtn.disabled = false;
			}
			if (oDst.selectedIndex == -1)
			{
				oRemoveBtn.disabled = true;
			}
			else
			{
				oRemoveBtn.disabled = false;
			}
		}
		
		function Manage_AddObjects()
		{
			var oSrc = document.getElementById('available_objects');
			var oDst = document.getElementById('selected_objects');
			Manage_SwapSelectedObjects(oSrc, oDst);
		}
		
		function Manage_RemoveObjects()
		{
			var oSrc = document.getElementById('selected_objects');
			var oDst = document.getElementById('available_objects');
			Manage_SwapSelectedObjects(oSrc, oDst);
		}
		
		function Manage_Ok(sClass)
		{
			var objectsToAdd = document.getElementById('selected_objects');
			var aSelectedObjects = new Array();
			for (i=0; i<objectsToAdd.length;i++)
			{
				aSelectedObjects[aSelectedObjects.length] = objectsToAdd.options[i].value;
			}
			$('#related_object_ids').val(aSelectedObjects.join(' '));
			UpdateObjectList(sClass);
		}
		
		function FilterLeft($sClass)
		{
			alert('Not Yet Implemented');
		}
		
		function FilterRight($sClass)
		{
			alert('Not Yet Implemented');
		}
EOF;
		$sManageObjectsDlg = <<< EOF
		<div class="page_header"><h1 id="Manage_DlgTitle">Selected Objects</h1></div>
		<table width="100%">
			<tr>
				<td>
					<p>Selected objects:</p>
					<button type="button" class="action" onClick="FilterLeft('$sClass');"><span> Filter... </span></button>
					<p><select id="selected_objects" size="10" multiple onChange="Manage_UpdateButtons()" style="width:300px;">
					</select></p>
				</td>
				<td style="text-align:center; valign:middle;">
					<p><button type="button" id="btn_add_objects" onClick="Manage_AddObjects();"> &lt;&lt; Add </button></p>
					<p><button type="button" id="btn_remove_objects" onClick="Manage_RemoveObjects();"> Remove &gt;&gt; </button></p>
				</td>
				<td>
					<p>Available objects:</p>
					<button type="button" class="action" onClick="FilterRight('$sClass');"><span> Filter... </span></button>
					<p><select id="available_objects" size="10" multiple onChange="Manage_UpdateButtons()" style="width:300px;">
					</select></p>
				</td>
			</tr>
			<tr>
				<td colspan="3">
				<button type="button" class="jqmClose" onClick="Manage_Ok('$sClass')"> Ok </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="jqmClose"> Cancel</button>
				</td>
			</tr>
		</table>
EOF;
		$sHTML = '<input type="text" name="" id="current_object_id" size="30"/>
				  <input type="hidden" id="related_object_ids" name="'.$sFieldName.'" value="">
				  &nbsp;<button type="button" class="action" onClick="return AddObject(\''.$sClass.'\');"><span> Add </span></button>
				  &nbsp;<button type="button" class="action" onClick="return ManageObjects(\''.$sTitle.'\', \''.$sClass.'\', \'related_object_ids\');"><span> ... </span></button>';
		$sHTML .= '<input type="hidden" id="ac_current_object_id" name="" value="">';
		$sHTML .= '<div class="jqmWindow" id="ManageObjectsDlg">'.$sManageObjectsDlg.'</div>';
		$oPage->add_script($sScript);
		$oPage->add_ready_script("\$('#current_object_id').autocomplete('./ajax.render.php', { minChars:3, onItemSelect:selectItem, onFindValue:findValue, formatItem:formatItem, autoFill:true, keyHolder:'#ac_current_object_id', extraParams:{operation:'link', sclass:'$sClass', attCode:'name'}});");
		$oPage->add_ready_script("$('#ManageObjectsDlg').jqm({overlay:70, modal:true, toTop:true});"); // jqModal Window
		$oPage->add_ready_script("UpdateObjectList('$sClass');");
		return $sHTML;
	}
	
	function DisplayObjectPickerList(web_page $oPage, $sClass)
	{
		$oFilter = new CMDBSearchFilter($sClass);
		$oFilter->AddCondition('pkey', array(0), 'IN');
		//$oPage->p($oFilter->__DescribeHTML());
		$oBlock = new DisplayBlock($oFilter, 'list', true /* Asynchronous */);
		$oBlock->Display($oPage, 'related_objects');
	}
}

class IncidentCreationWizard extends DialogWizard
{
	public function __construct($sStep)
	{
		parent::__construct($sStep);
		$this->m_aSteps =
		array(
			'1' => array('title' => 'attr_title', 'customer_id' => 'attr_customer_id', 'initial_situation' => 'attr_initial_situation', 'severity' => 'attr_severity', 'impact' => 'attr_impact', 'workgroup_id' => 'attr_workgroup_id', 'action_log' => 'attr_action_log'),
			'2' => array('impacted_infra_ids' => 'impacted_infra_ids'),
			'3' => array('additional_impacted_object_ids' => 'additional_impacted_object_ids'),
			'4' => array('related_incident_ids' => 'related_incident_ids'),
			'5' => array('contact_ids' => 'contact_ids'),
			);
	}
	
	protected function AddContextToForm($oPage)
	{
		parent::AddContextToForm($oPage);
		$oPage->add("<input type=\"hidden\" name=\"operation\" value=\"new\" />\n");
		$oPage->add("<input type=\"hidden\" name=\"step\" value=\"".$this->m_sNextStep."\" />\n");
	}

	public function DisplayNewTicketForm(web_page $oPage)
	{
		assert($this->m_sCurrentStep == '1');
		$this->m_sNextStep = '2';
		$aFields = $this->GetFields();
		
		$oPage->add('<form method="get">');
		$aDetails = array();
		$aAttributesDef = MetaModel::ListAttributeDefs('bizIncidentTicket');
		foreach($aFields as $sAttCode => $sFieldName)
		{
			$oAttDef = $aAttributesDef[$sAttCode];
			$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oPage, 'bizIncidentTicket', $sAttCode, $oAttDef);
			$aDetails[] = array('label' => $oAttDef->GetLabel().' <span class="hilite">*</span>', 'value' => $sHTMLValue);
		}
		$oPage->details($aDetails);
		$this->AddContextToForm($oPage);
		$oPage->add("<button type=\"button\" class=\"action\" onClick=\"goBack()\"><span>Cancel</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oPage->add("<button type=\"submit\" class=\"action\"><span>Next >></span></button>\n");
		$oPage->add('</form>');
	}

	public function DisplayImpactedInfraForm(web_page $oPage)
	{
		assert($this->m_sCurrentStep == '2');
		$this->m_sNextStep = '3';
		$oPage->add('<form method="get">');
		$aDetails = array();
		$sHTML = $this->GetObjectPicker($oPage, 'Impacted Infrastructure', 'impacted_infra_ids', 'logInfra');
		$aDetails[] = array('label' => 'Impacted element:', 'value' => $sHTML);
		$oPage->details($aDetails);
		$this->DisplayObjectPickerList($oPage, 'logInfra');
		$this->AddContextToForm($oPage);
		$oPage->add("<button type=\"button\" class=\"action\" onClick=\"goBack()\"><span><< Back</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oPage->add("<button type=\"submit\" class=\"action\"><span>Next >></span></button>\n");
		$oPage->add('</form>');
	}

	public function DisplayAdditionalImpactedObjectForm(web_page $oPage)
	{
		assert($this->m_sCurrentStep == '3');
		$this->m_sNextStep = '4';
		$sImpactedInfraIds = Utils::ReadParam('impacted_infra_ids');
		
		$sImpactedInfraIds = Utils::ReadParam('impacted_infra_ids', '');
		if (!empty($sImpactedInfraIds))
		{
			$oPage->p('Impacted Infrastructure:');
			$oFilter = new CMDBSearchFilter('logRealObject');
			$oFilter->AddCondition('pkey', explode(' ', $sImpactedInfraIds), 'IN');
			$oBlock = new DisplayBlock($oFilter, 'list', false /* Synchronous */);
			$oBlock->Display($oPage, 'impacted_infra');
		}

		$aImpactedInfraIds = explode(' ', $sImpactedInfraIds);
		$oInfraSet = CMDBObjectSet::FromScratch('logRealObject');
		foreach($aImpactedInfraIds as $id)
		{
			$oObj = MetaModel::GetObject('logRealObject', $id);
			$oInfraSet->AddObject($oObj);
		}
		$aImpactedObject = $oInfraSet->GetRelatedObjects('impacts');
		$aAdditionalIds = array();
		foreach($aImpactedObject as $sRootClass => $aObjects)
		{
			foreach($aObjects as $oObj)
			{
				$aAdditionalIds[] = $oObj->GetKey();
			}
		}
		$sAdditionalIds = implode(' ', $aAdditionalIds);
		$oPage->add_ready_script('$("#related_object_ids").val("'.$sAdditionalIds.'");');
		
		$oPage->p('Additional Impact Computed:');
		$this->DisplayObjectPickerList($oPage, 'logRealObject');
		$oPage->add('<form method="get">');
		$aDetails = array();
		$sHTML = $this->GetObjectPicker($oPage, 'Additional Impacted Infrastructure', 'additional_impacted_object_ids', 'logRealObject');
		$aDetails[] = array('label' => 'Impacted element:', 'value' => $sHTML);
		$oPage->details($aDetails);
		$this->AddContextToForm($oPage);
		$oPage->add("<button type=\"button\" class=\"action\" onClick=\"goBack()\"><span><< Back</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oPage->add("<button type=\"submit\" class=\"action\"><span>Next >></span></button>\n");
		$oPage->add('</form>');
	}

	public function DisplayRelatedTicketsForm(web_page $oPage)
	{
		assert($this->m_sCurrentStep == '4');
		$this->m_sNextStep = '5';
		$oRelatedTicketsFilter = new DBObjectSearch('bizIncidentTicket');
		$sImpactedInfraIds = Utils::ReadParam('impacted_infra_ids', '');
		$sAdditionalImpactedObjectIds = Utils::ReadParam('additional_impacted_object_ids', '');
		$sIds = trim($sImpactedInfraIds.' '.$sAdditionalImpactedObjectIds);
		$aTicketIds = array();
		if (!empty($sIds))
		{
			$aIds = explode(' ', $sIds);
			$sSibusQL = "bizIncidentTicket: PKEY IS ticket_id IN (lnkInfraTicket: infra_id IN (logRealObject: pkey IN {".implode(',', $aIds)."}))";
			$oTicketSearch = DBObjectSearch::FromSibusQL($sSibusQL);
			$oRelatedTicketSet = new DBObjectSet($oTicketSearch);
			while ($oTicket = $oRelatedTicketSet->Fetch())
			{
				$aTicketIds[] = $oTicket->GetKey(); 
			}
		}
	
		$sTicketIds = implode(' ', $aTicketIds);
		$oPage->add_ready_script('$("#related_object_ids").val("'.$sTicketIds.'");');
		$oPage->p('Potentially related incidents:');
		$this->DisplayObjectPickerList($oPage, 'bizIncidentTicket');

		$oPage->add('<form method="get">');
		$sHTML = $this->GetObjectPicker($oPage, 'Related Incidents', 'related_incident_ids', 'bizIncidentTicket');
		$aDetails[] = array('label' => 'Related Incident:', 'value' => $sHTML);
		$oPage->details($aDetails);
		$this->AddContextToForm($oPage);
		$oPage->add("<button type=\"button\" class=\"action\" onClick=\"goBack()\"><span><< Back</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oPage->add("<button type=\"submit\" class=\"action\"><span>Next >></span></button>\n");
		$oPage->add('</form>');
	}
	
	public function DisplayContactsToNotifyForm(web_page $oPage)
	{
		assert($this->m_sCurrentStep == '5');
		$this->m_sNextStep = '6';
		$oPage->add('<form method="get">');
		$sHTML = $this->GetObjectPicker($oPage, 'Contacts to notify', 'contact_ids', 'bizContact');
		$aDetails[] = array('label' => 'Additional contact:', 'value' => $sHTML);
		$oPage->details($aDetails);
		$this->DisplayObjectPickerList($oPage, 'bizContact');
		$this->AddContextToForm($oPage);
		$oPage->add("<button type=\"button\" class=\"action\" onClick=\"goBack()\"><span><< Back</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oPage->add("<button type=\"submit\" class=\"action\"><span>Next >></span></button>\n");
		$oPage->add('</form>');
	}
	
	function DisplayFinalForm(web_page $oPage)
	{
		$oAppContext = new ApplicationContext();
		assert($this->m_sCurrentStep == '6');
		$this->m_sNextStep = '7';
	
		$aDetails = array();
		$aAttributesDef = MetaModel::ListAttributeDefs('bizIncidentTicket');
		$aFields = $this->GetFields('1');
		foreach($aFields as $sAttCode => $sFieldName)
		{
			$oAttDef = $aAttributesDef[$sAttCode];
			$sValue = Utils::ReadParam($sFieldName, '');
			if ($oAttDef->IsExternalKey() && isset($sValue) && ($sValue != 0))
			{
				$oTargetObj = MetaModel::GetObject($oAttDef->GetTargetClass(), $sValue);
				if (!is_object($oTargetObj))
				{
					trigger_error("Houston: could not find ".$oAttDef->GetTargetClass()."::$sValue");
				}
				$sPage = cmdbAbstractObject::ComputeUIPage($oAttDef->GetTargetClass());
				$sHint = htmlentities($oAttDef->GetTargetClass()."::".$sValue);
				$sHTMLValue = "<a href=\"$sPage?operation=details&class=".$oAttDef->GetTargetClass()."&id=$sValue&".$oAppContext->GetForLink()."\" title=\"$sHint\">".$oTargetObj->GetName()."</a>";
			}
			else
			{
				$sHTMLValue = $oAttDef->GetAsHTML($sValue);
			}
			$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $sHTMLValue);
		}
		$oPage->details($aDetails);
		
		$oPage->AddTabContainer('LinkedObjects');
		$oPage->SetCurrentTabContainer('LinkedObjects');
		
		$sImpactedInfraIds = Utils::ReadParam('impacted_infra_ids', '');
		$sImpactedInfraIds .= ' '.Utils::ReadParam('additional_impacted_object_ids', '');
		$sImpactedInfraIds = trim($sImpactedInfraIds);
		$oPage->SetCurrentTab("Infrastructure impacted");
		if (!empty($sImpactedInfraIds))
		{
			$oFilter = new CMDBSearchFilter('logRealObject');
			$oFilter->AddCondition('pkey', explode(' ', $sImpactedInfraIds), 'IN');
			$oBlock = new DisplayBlock($oFilter, 'list', false /* Synchronous */);
			$oBlock->Display($oPage, 'related_objects');
		}
		else
		{
			$oPage->p("There is no infrastructure impacted by this incident");
		}

		$sRelatedIncidentIds = Utils::ReadParam('related_incident_ids', '');
		$oPage->SetCurrentTab("Related tickets");
		if (!empty($sRelatedIncidentIds))
		{
			$oFilter = new CMDBSearchFilter('bizIncidentTicket');
			$oFilter->AddCondition('pkey', explode(' ', $sRelatedIncidentIds), 'IN');
			$oBlock = new DisplayBlock($oFilter, 'list', false /* Synchronous */);
			$oBlock->Display($oPage, 'related_incidents');
		}
		else
		{
			$oPage->p("There is no other incident related to this one");
		}
	
		$oPage->SetCurrentTab("Contacts to notify");
		$sContactIds = Utils::ReadParam('contact_ids', '');
		if (!empty($sContactIds))
		{
			$oFilter = new CMDBSearchFilter('bizContact');
			$oFilter->AddCondition('pkey', explode(' ', $sContactIds), 'IN');
			$oBlock = new DisplayBlock($oFilter, 'list', false /* Synchronous */);
			$oBlock->Display($oPage, 'contacts');
		}
		else
		{
			$oPage->p("There is no contact to notify");
		}
		$oPage->SetCurrentTab();
		
		$oPage->add('<form method="post" action="incident.php">');
		$this->AddContextToForm($oPage);
		$oPage->add("<button type=\"button\" class=\"action\" onClick=\"goBack()\"><span><< Back</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oPage->add("<button type=\"submit\" class=\"action\" value=\"create\"><span> Create Ticket</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oPage->add("<button type=\"submit\" class=\"action\" value=\"create_notify\"><span> Create Ticket and Send Notifications</span></button>\n");
		$oPage->add('</form>');
	}
	
	public function CreateIncident(web_page $oPage)
	{
		$oAppContext = new ApplicationContext();
		assert($this->m_sCurrentStep == '7');
		$this->m_sNextStep = '1';
		
	    $oIncident = MetaModel::NewObject('bizIncidentTicket');
	    $oPage->p("Creation of Incident Ticket.");

		$aFields = $this->GetFields('1');
		foreach($aFields as $sAttCode => $sFieldName)
		{
			$sValue = Utils::ReadPostedParam($sFieldName, '');
			$oIncident->Set($sAttCode, $sValue);
		}
		$oIncident->Set('ticket_status', 'New');
		$oIncident->Set('start_date', time());
		$oIncident->Set('name', 'ID not set');

		if ($oIncident->CheckToInsert())
		{
			// Create the ticket itself
			$oMyChange = MetaModel::NewObject("CMDBChange");
			$oMyChange->Set("date", time());
			$oMyChange->Set("userinfo", "Administrator");
			$iChangeId = $oMyChange->DBInsert();
			$oIncident->DBInsertTracked($oMyChange);
			
			$sName = sprintf('I-%06d', $oIncident->GetKey());
			$oIncident->Set('name', $sName);
			$oIncident->DBUpdateTracked($oMyChange);
			$oPage->p("Incident $sName created.\n");

			// Now link the objects to the Incident:
			// 1) the impacted infra
			$sImpactedInfraIds = Utils::ReadParam('impacted_infra_ids', '');
			$sImpactedInfraIds .= ' '.Utils::ReadParam('additional_impacted_object_ids', '');
			$sImpactedInfraIds = trim($sImpactedInfraIds);
			if (!empty($sImpactedInfraIds))
			{
				$aImpactedInfra = explode(' ', $sImpactedInfraIds);
				foreach($aImpactedInfra as $iInfraId)
				{
					$oLink = MetaModel::NewObject('lnkInfraTicket');
					$oLink->Set('infra_id', $iInfraId);
					$oLink->Set('ticket_id', $oIncident->GetKey());
					$oLink->Set('impact', 'automatic');
					$oLink->DBInsertTracked($oMyChange);
				}
			}
			// 2) the related incidents
			$sRelatedIncidentsIds = Utils::ReadPostedParam('related_incident_ids');
			if (!empty($sRelatedIncidentsIds))
			{
				$aRelatedIncidents = explode(' ', $sRelatedIncidentsIds);
				foreach($aRelatedIncidents as $iIncidentId)
				{
					$oLink = MetaModel::NewObject('lnkRelatedTicket');
					$oLink->Set('rel_ticket_id', $iIncidentId);
					$oLink->Set('ticket_id', $oIncident->GetKey());
					$oLink->Set('impact', 'automatic');
					$oLink->DBInsertTracked($oMyChange);
				}
			}
			// 3) the contacts to notify
			$sContactsIds = Utils::ReadPostedParam('contact_ids');
			if (!empty($sContactsIds))
			{
				$aContactsToNotify = explode(' ', $sContactsIds);
				foreach($aContactsToNotify as $iContactId)
				{
					$oLink = MetaModel::NewObject('lnkContactRealObject');
					$oLink->Set('contact_id', $iContactId);
					$oLink->Set('object_id', $oIncident->GetKey());
					$oLink->Set('role', 'notification');
					$oLink->DBInsertTracked($oMyChange);
				}
			}
			$oIncident->DisplayDetails($oPage, 'bizIncidentTicket', $oIncident->GetKey());
		}
		else
		{
			$oPage->p("<strong>Error: object can not be created!</strong>\n");
		}
	}
}


$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$currentOrganization = utils::ReadParam('org_id', '');
$operation = utils::ReadParam('operation', '');
$oP = new iTopWebPage("ITop - Incident Management", $currentOrganization);

switch($operation)
{
	case 'details':
		$sClass = utils::ReadParam('class', '');
		$id = utils::ReadParam('id', '');
		if ( empty($sClass) || empty($id)) // TO DO: check that the class name is valid !
		{
			$oP->add("<p>'class' and 'id' parameters must be specifed for this operation.</p>\n");
		}
		else
		{
			$oObj = $oContext->GetObject($sClass, $id);
			if ($oObj != null)
			{
				$oP->set_title("iTop - ".$oObj->GetName()." - $sClass details");
				$oObj->DisplayDetails($oP);
			}
			else
			{
				$oP->set_title("iTop - Error");
				$oP->add("<p>Sorry this object does not exist (or you are not allowed to view it).</p>\n");
			}
		}
	break;
	
	case 'new':
	$step = utils::ReadParam('step', '1');
	$aSteps = array(
			'Ticket Information',
			'Impacted Infrastructure',
			'Additional Impact',
			'Related Tickets',
			'Contacts to Notify',
			'Confirmation',
			'Ticket Creation'
			);
	$oWizard = new IncidentCreationWizard($step);
	$oP = new iTopWizardWebPage("ITop - Incident Management", $currentOrganization, $step, $aSteps);
	
	switch($step)
	{
		case 1:
		default:
		//$oP->add('<div class="page_header"><h1><span class="hilite">New incident</span></h1></div>');
		$oWizard->DisplayNewTicketForm($oP);
		break;
		
		case 2:
		//$oP->add('<div class="page_header"><h1>New ticket: <span class="hilite">Select the Impacted Infrastructure</span></h1></div>');
		$oWizard->DisplayImpactedInfraForm($oP);
		break;		

		case 3:
		//$oP->add('<div class="page_header"><h1>New ticket: <span class="hilite">Additional Impacted Objects</span></h1></div>');
		$oWizard->DisplayAdditionalImpactedObjectForm($oP);
		break;
		
		case 4:
		//$oP->add('<div class="page_header"><h1>New ticket: <span class="hilite">Select Related Incidents</span></h1></div>');
		$oWizard->DisplayRelatedTicketsForm($oP);
		break;
		
		case 5:
		//$oP->add('<div class="page_header"><h1>New ticket: <span class="hilite">Select the Contacts to Notify</span></h1></div>');
		$oWizard->DisplayContactsToNotifyForm($oP);
		break;
		
		case 6:
		//$oP->add('<div class="page_header"><h1>New ticket: <span class="hilite">Confirm and Create the Ticket</span></h1></div>');
		$oWizard->DisplayFinalForm($oP);
		break;
		
		case 7:
		$oWizard->CreateIncident($oP);
		break;
	}
	break;
	
	case 'modify':
	$oP->add_linked_script("../js/json.js");
	$oP->add_linked_script("../js/forms-json-utils.js");
	$oP->add_linked_script("../js/wizardhelper.js");
	$oP->add_linked_script("../js/wizard.utils.js");
	$oP->add_linked_script("../js/linkswidget.js");
	$oP->add_linked_script("../js/jquery.blockUI.js");
	$sClass = utils::ReadParam('class', '');
	$id = utils::ReadParam('id', '');
	if ( empty($sClass) || empty($id)) // TO DO: check that the class name is valid !
	{
		$oP->add("<p>'class' and 'id' parameters must be specifed for this operation.</p>\n");
	}
	else
	{
		$oObj = $oContext->GetObject($sClass, $id);
		if ($oObj != null)
		{
			$oP->set_title("iTop - ".$oObj->GetName()." - $sClass modification");
			$oP->add("<h1>".$oObj->GetName()." - $sClass modification</h1>\n");
			$oObj->DisplayModifyForm($oP);
		}
		else
		{
			$oP->set_title("iTop - Error");
			$oP->add("<p>Sorry this object does not exist (or you are not allowed to view it).</p>\n");
		}
	}
	break;

	case 'apply_modify':
	$sClass = utils::ReadPostedParam('class', '');
	$id = utils::ReadPostedParam('id', '');
	$sTransactionId = utils::ReadPostedParam('transaction_id', '');
	if ( empty($sClass) || empty($id)) // TO DO: check that the class name is valid !
	{
		$oP->add("<p>'class' and 'id' parameters must be specifed for this operation.</p>\n");
	}
	else if (!utils::IsTransactionValid($sTransactionId))
	{
		$oP->p("<strong>Error: object has already be updated!</strong>\n");
	}
	else
	{
		$oObj = $oContext->GetObject($sClass, $id);
		if ($oObj != null)
		{
			$oP->set_title("iTop - ".$oObj->GetName()." - $sClass modification");
			$oP->add("<h1>".$oObj->GetName()." - $sClass modification</h1>\n");
			$bObjectModified = false;
			foreach(MetaModel::ListAttributeDefs(get_class($oObj)) as $sAttCode=>$oAttDef)
			{
				$iFlags = $oObj->GetAttributeFlags($sAttCode);
				if ($iFlags & (OPT_ATT_HIDDEN | OPT_ATT_READONLY))
				{
					// Non-visible, or read-only attribute, do nothing
				}
				else if ($sAttCode == 'finalclass')
				{
					// This very specific field is read-only
				}
				else if (!$oAttDef->IsExternalField())
				{
					$aAttributes[$sAttCode] = trim(utils::ReadPostedParam("attr_$sAttCode", null));
					$previousValue = $oObj->Get($sAttCode);
					if (!is_null($aAttributes[$sAttCode]) && ($previousValue != $aAttributes[$sAttCode]))
					{
						$oObj->Set($sAttCode, $aAttributes[$sAttCode]);
						$bObjectModified = true;
					}
				}
			}
			if (!$bObjectModified)
			{
				$oP->p("No modification detected. ".get_class($oObj)." has <strong>not</strong> been updated.\n");
			}
			else if ($oObj->CheckToUpdate())
			{
				$oMyChange = MetaModel::NewObject("CMDBChange");
				$oMyChange->Set("date", time());
				$oMyChange->Set("userinfo", "Made by somebody"); // TO DO put the correct user info here
				$iChangeId = $oMyChange->DBInsert();
				$oObj->DBUpdateTracked($oMyChange);
		
				$oP->p(get_class($oObj)." updated.\n");
			}
			else
			{
				$oP->p("<strong>Error: object can not be updated!</strong>\n");
				//$oObj->Reload(); // restore default values!
			}
		}
		else
		{
			$oP->set_title("iTop - Error");
			$oP->add("<p>Sorry this object does not exist (or you are not allowed to edit it).</p>\n");
		}
	}
	$oP->add("<p>Alors ça roule ?</p>");
	$oObj->DisplayDetails($oP);
	break;
}
$oP->output();
?>
