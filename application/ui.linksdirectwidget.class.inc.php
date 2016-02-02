<?php
// Copyright (C) 2010-2015 Combodo SARL
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
 * Class UILinksWidgetDirect
 *  
 * @copyright   Copyright (C) 2010-2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */ 

class UILinksWidgetDirect
{
	protected $sClass;
	protected $sAttCode;
	protected $sInputid;
	protected $sNameSuffix;
	protected $sLinkedClass;
	
	public function __construct($sClass, $sAttCode, $sInputId, $sNameSuffix = '')
	{
		$this->sClass = $sClass;
		$this->sAttCode = $sAttCode;
		$this->sInputid = $sInputId;
		$this->sNameSuffix = $sNameSuffix;
		$this->aZlist = array();
		$this->sLinkedClass = '';
		
		// Compute the list of attributes visible from the given objet:
		// All the attributes from the "list" Zlist of the Link class except
		// the ExternalKey that points to the current object and its related external fields
		$oLinksetDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$this->sLinkedClass = $oLinksetDef->GetLinkedClass();
		$sExtKeyToMe = $oLinksetDef->GetExtKeyToMe();
		switch($oLinksetDef->GetEditMode())
		{
			case LINKSET_EDITMODE_INPLACE: // The whole linkset can be edited 'in-place'
			$aZList = MetaModel::FlattenZList(MetaModel::GetZListItems($this->sLinkedClass, 'details'));
			break;
			
			default:
			$aZList = MetaModel::FlattenZList(MetaModel::GetZListItems($this->sLinkedClass, 'list'));
			array_unshift($aZList, 'friendlyname');
		}
		foreach($aZList as $sLinkedAttCode)
		{
			if ($sLinkedAttCode != $sExtKeyToMe)
			{
				$oAttDef = MetaModel::GetAttributeDef($this->sLinkedClass, $sLinkedAttCode);
				
				if ((!$oAttDef->IsExternalField() || ($oAttDef->GetKeyAttCode() != $sExtKeyToMe)) &&
					(!$oAttDef->IsLinkSet()) )
				{
					$this->aZlist[] = $sLinkedAttCode;
				}
			}
		}
		
	}
	
	public function Display(WebPage $oPage, DBObjectSet $oValue, $aArgs = array(), $sFormPrefix, $oCurrentObj)
	{
		$oLinksetDef = MetaModel::GetAttributeDef($this->sClass, $this->sAttCode);
		switch($oLinksetDef->GetEditMode())
		{
			case LINKSET_EDITMODE_NONE: // The linkset is read-only
			$this->DisplayAsBlock($oPage, $oValue, $aArgs = array(), $sFormPrefix, $oCurrentObj, false /* bDisplayMenu*/);
			break;
			
			case LINKSET_EDITMODE_ADDONLY: // The only possible action is to open (in a new window) the form to create a new object
			if ($oCurrentObj && !$oCurrentObj->IsNew())
			{
				$sTargetClass = $oLinksetDef->GetLinkedClass();
				$sExtKeyToMe = $oLinksetDef->GetExtKeyToMe();
				$sDefault = "default[$sExtKeyToMe]=".$oCurrentObj->GetKey();
				$oAppContext = new ApplicationContext();
				$sParams = $oAppContext->GetForLink();
				$oPage->p("<a target=\"_blank\" href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=new&class=$sTargetClass&$sParams&{$sDefault}\">".Dict::Format('UI:ClickToCreateNew', Metamodel::GetName($sTargetClass))."</a>\n");
			}
			$this->DisplayAsBlock($oPage, $oValue, $aArgs = array(), $sFormPrefix, $oCurrentObj, false /* bDisplayMenu*/);
			break;
			
			case LINKSET_EDITMODE_INPLACE: // The whole linkset can be edited 'in-place'
			$this->DisplayEditInPlace($oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj);
			break;
			
			case LINKSET_EDITMODE_ADDREMOVE: // The whole linkset can be edited 'in-place'
			$sTargetClass = $oLinksetDef->GetLinkedClass();
			$sExtKeyToMe = $oLinksetDef->GetExtKeyToMe();
			$oExtKeyDef = MetaModel::GetAttributeDef($sTargetClass, $sExtKeyToMe);
			$aButtons = array('add');
			if ($oExtKeyDef->IsNullAllowed())
			{
				$aButtons = array('add', 'remove');
			}
			$this->DisplayEditInPlace($oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj, $aButtons);
			break;
			
			case LINKSET_EDITMODE_ACTIONS:
			default:
			$this->DisplayAsBlock($oPage, $oValue, $aArgs = array(), $sFormPrefix, $oCurrentObj, true /* bDisplayMenu*/);
		}
	}
	
	protected function DisplayAsBlock(WebPage $oPage, DBObjectSet $oValue, $aArgs = array(), $sFormPrefix, $oCurrentObj, $bDisplayMenu)
	{
		$oLinksetDef = MetaModel::GetAttributeDef($this->sClass, $this->sAttCode);
		$sTargetClass = $oLinksetDef->GetLinkedClass();
		if ($oCurrentObj && $oCurrentObj->IsNew() && $bDisplayMenu)
		{
			$oPage->p(Dict::Format('UI:BeforeAdding_Class_ObjectsSaveThisObject', MetaModel::GetName($sTargetClass)));
		}
		else
		{
			$oFilter = new DBObjectSearch($sTargetClass);
			$oFilter->AddCondition($oLinksetDef->GetExtKeyToMe(), $oCurrentObj->GetKey(),'=');

			$aDefaults = array($oLinksetDef->GetExtKeyToMe() => $oCurrentObj->GetKey());
			$oAppContext = new ApplicationContext();
			foreach($oAppContext->GetNames() as $sKey)
			{
				// The linked object inherits the parent's value for the context
				if (MetaModel::IsValidAttCode($this->sClass, $sKey) && $oCurrentObj)
				{
					$aDefaults[$sKey] = $oCurrentObj->Get($sKey);
				}
			}
			$aParams = array(
				'target_attr' => $oLinksetDef->GetExtKeyToMe(),
				'object_id' => $oCurrentObj ? $oCurrentObj->GetKey() : null,
				'menu' => $bDisplayMenu,
				'default' => $aDefaults,
				'table_id' => $this->sClass.'_'.$this->sAttCode,
			);

			$oBlock = new DisplayBlock($oFilter, 'list', false);
			$oBlock->Display($oPage, $this->sInputid, $aParams);
		}	
	}
	
	protected function DisplayEditInPlace(WebPage $oPage, DBObjectSet $oValue, $aArgs = array(), $sFormPrefix, $oCurrentObj, $aButtons = array('create', 'delete'))
	{
		$aAttribs = $this->GetTableConfig();
		
		$oValue->Rewind();
		$oPage->add('<table class="listContainer" id="'.$this->sInputid.'"><tr><td>');

		$aData = array();
		while($oLinkObj = $oValue->Fetch())
		{
			$aRow = array();
			$aRow['form::select'] = '<input type="checkbox" class="selectList'.$this->sInputid.'" value="'.$oLinkObj->GetKey().'"/>';
			foreach($this->aZlist as $sLinkedAttCode)
			{
				$aRow[$sLinkedAttCode] = $oLinkObj->GetAsHTML($sLinkedAttCode);
			}
			$aData[] = $aRow;
		}
		$oPage->table($aAttribs, $aData);
		$oPage->add('</td></tr></table>'); //listcontainer
		$sInputName = $sFormPrefix.'attr_'.$this->sAttCode;
		$aLabels = array(
			'delete' => Dict::S('UI:Button:Delete'),
			// 'modify' => 'Modify...' , 
			'creation_title' => Dict::Format('UI:CreationTitle_Class', MetaModel::GetName($this->sLinkedClass)),
			'create' => Dict::Format('UI:ClickToCreateNew', MetaModel::GetName($this->sLinkedClass)),
			'remove' => Dict::S('UI:Button:Remove'),
			'add' => Dict::Format('UI:AddAnExisting_Class', MetaModel::GetName($this->sLinkedClass)),
			'selection_title' => Dict::Format('UI:SelectionOf_Class', MetaModel::GetName($this->sLinkedClass)),
		);
		$oContext = new ApplicationContext();
		$sSubmitUrl = utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?'.$oContext->GetForLink();
		$sJSONLabels = json_encode($aLabels);
		$sJSONButtons = json_encode($aButtons);
		$sWizHelper = 'oWizardHelper'.$sFormPrefix;
		$oPage->add_ready_script("$('#{$this->sInputid}').directlinks({class_name: '$this->sClass', att_code: '$this->sAttCode', input_name:'$sInputName', labels: $sJSONLabels, submit_to: '$sSubmitUrl', buttons: $sJSONButtons, oWizardHelper: $sWizHelper });");
	}
	
	public function GetObjectCreationDlg(WebPage $oPage, $sProposedRealClass = '')
	{
		// For security reasons: check that the "proposed" class is actually a subclass of the linked class
		// and that the current user is allowed to create objects of this class
		$sRealClass = '';
		$oPage->add('<div class="wizContainer" style="vertical-align:top;"><div>');
		$aSubClasses = MetaModel::EnumChildClasses($this->sLinkedClass, ENUM_CHILD_CLASSES_ALL); // Including the specified class itself
		$aPossibleClasses = array();
		foreach($aSubClasses as $sCandidateClass)
		{
			if (!MetaModel::IsAbstract($sCandidateClass) && (UserRights::IsActionAllowed($sCandidateClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES))
			{
				if ($sCandidateClass == $sProposedRealClass)
				{
					$sRealClass = $sProposedRealClass;
				}
				$aPossibleClasses[$sCandidateClass] = MetaModel::GetName($sCandidateClass);
			}
		}
		// Only one of the subclasses can be instantiated...
		if (count($aPossibleClasses) == 1)
		{
			$aKeys = array_keys($aPossibleClasses);
			$sRealClass = $aKeys[0];
		}
		
		if ($sRealClass != '')
		{
			$oPage->add("<h1>".MetaModel::GetClassIcon($sRealClass)."&nbsp;".Dict::Format('UI:CreationTitle_Class', MetaModel::GetName($sRealClass))."</h1>\n");
			$oLinksetDef = MetaModel::GetAttributeDef($this->sClass, $this->sAttCode);
			$sExtKeyToMe = $oLinksetDef->GetExtKeyToMe();
			$aFieldFlags = array( $sExtKeyToMe => OPT_ATT_HIDDEN);
		 	cmdbAbstractObject::DisplayCreationForm($oPage, $sRealClass, null, array(), array('formPrefix' => $this->sInputid, 'noRelations' => true, 'fieldsFlags' => $aFieldFlags));	
		}
		else
		{
			$sClassLabel = MetaModel::GetName($this->sLinkedClass);
			$oPage->add('<p>'.Dict::Format('UI:SelectTheTypeOf_Class_ToCreate', $sClassLabel));
			$oPage->add('<nobr><select name="class">');
			asort($aPossibleClasses);
			foreach($aPossibleClasses as $sClassName => $sClassLabel)
			{
				$oPage->add("<option value=\"$sClassName\">$sClassLabel</option>");
			}
			$oPage->add('</select>');
			$oPage->add('&nbsp; <button type="button" onclick="$(\'#'.$this->sInputid.'\').directlinks(\'subclassSelected\');">'.Dict::S('UI:Button:Apply').'</button><span class="indicator" style="display:inline-block;width:16px"></span></nobr></p>');
		}
		$oPage->add('</div></div>');
	}
	
	public function GetObjectsSelectionDlg($oPage, $oCurrentObj)
	{
		$sHtml = "<div class=\"wizContainer\" style=\"vertical-align:top;\">\n";
		
		$oLinksetDef = MetaModel::GetAttributeDef($this->sClass, $this->sAttCode);
		$valuesDef = $oLinksetDef->GetValuesDef();				
		if ($valuesDef === null)
		{
			$oFilter = new DBObjectSearch($this->sLinkedClass);
		}
		else
		{
			if (!$valuesDef instanceof ValueSetObjects)
			{
				throw new Exception('Error: only ValueSetObjects are supported for "allowed_values" in AttributeLinkedSet ('.$this->sClass.'/'.$this->sAttCode.').');
			}
			$oFilter = DBObjectSearch::FromOQL($valuesDef->GetFilterExpression());
		}
		if ($oCurrentObj != null)
		{
			$this->SetSearchDefaultFromContext($oCurrentObj, $oFilter);
		}
		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$sHtml .= $oBlock->GetDisplay($oPage, "SearchFormToAdd_{$this->sInputid}", array('open' => true));
		$sHtml .= "<form id=\"ObjectsAddForm_{$this->sInputid}\">\n";
		$sHtml .= "<div id=\"SearchResultsToAdd_{$this->sInputid}\" style=\"vertical-align:top;background: #fff;height:100%;overflow:auto;padding:0;border:0;\">\n";
		$sHtml .= "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>".Dict::S('UI:Message:EmptyList:UseSearchForm')."</p></div>\n";
		$sHtml .= "</div>\n";
		$sHtml .= "<input type=\"hidden\" id=\"count_{$this->sInputid}\" value=\"0\"/>";
		$sHtml .= "<button type=\"button\" class=\"cancel\">".Dict::S('UI:Button:Cancel')."</button>&nbsp;&nbsp;<button type=\"button\" class=\"ok\" disabled=\"disabled\">".Dict::S('UI:Button:Add')."</button>";
		$sHtml .= "</div>\n";
		$sHtml .= "</form>\n";
		$oPage->add($sHtml);
		//$oPage->add_ready_script("$('#SearchFormToAdd_{$this->sAttCode}{$this->sNameSuffix} form').bind('submit.uilinksWizard', oWidget{$this->sInputId}.SearchObjectsToAdd);");
		//$oPage->add_ready_script("$('#SearchFormToAdd_{$this->sAttCode}{$this->sNameSuffix}').resize(oWidget{$this->siInputId}.UpdateSizes);");
	}
	
	/**
	 * Search for objects to be linked to the current object (i.e "remote" objects)
	 * @param WebPage $oP The page used for the output (usually an AjaxWebPage)
	 * @param string $sRemoteClass Name of the "remote" class to perform the search on, must be a derived class of $this->sLinkedClass
	 * @param array $aAlreadyLinked Array of indentifiers of objects which are already linke to the current object (or about to be linked)
	 * @param DBObject $oCurrentObj The object currently being edited... if known...
	 */
	public function SearchObjectsToAdd(WebPage $oP, $sRemoteClass = '', $aAlreadyLinked = array(), $oCurrentObj = null)
	{
		if ($sRemoteClass == '')
		{
			$sRemoteClass = $this->sLinkedClass;
		}
		$oLinksetDef = MetaModel::GetAttributeDef($this->sClass, $this->sAttCode);
		$valuesDef = $oLinksetDef->GetValuesDef();				
		if ($valuesDef === null)
		{
			$oFilter = new DBObjectSearch($sRemoteClass);
		}
		else
		{
			if (!$valuesDef instanceof ValueSetObjects)
			{
				throw new Exception('Error: only ValueSetObjects are supported for "allowed_values" in AttributeLinkedSet ('.$this->sClass.'/'.$this->sAttCode.').');
			}
			$oFilter = DBObjectSearch::FromOQL($valuesDef->GetFilterExpression());
		}
		
		if (($oCurrentObj != null) && MetaModel::IsSameFamilyBranch($sRemoteClass, $this->sClass))
		{
			// Prevent linking to self if the linked object is of the same family
			// and laready present in the database
			if (!$oCurrentObj->IsNew())
			{
				$oFilter->AddCondition('id', $oCurrentObj->GetKey(), '!=');
			}
		}
		if (count($aAlreadyLinked) > 0)
		{
			$oFilter->AddCondition('id', $aAlreadyLinked, 'NOTIN');
		}
		if ($oCurrentObj != null)
		{
			$aArgs = array_merge($oCurrentObj->ToArgs('this'), $oFilter->GetInternalParams());
			$oFilter->SetInternalParams($aArgs);
		}
		$oBlock = new DisplayBlock($oFilter, 'list', false);
		$oBlock->Display($oP, "ResultsToAdd_{$this->sInputid}", array('menu' => false, 'cssCount'=> '#count_'.$this->sInputid , 'selection_mode' => true, 'table_id' => 'add_'.$this->sInputid)); // Don't display the 'Actions' menu on the results
	}

	public function DoAddObjects(WebPage $oP, $oFullSetFilter)
	{
		$aLinkedObjectIds = utils::ReadMultipleSelection($oFullSetFilter);
		foreach($aLinkedObjectIds as $iObjectId)
		{
			$oLinkObj = MetaModel::GetObject($this->sLinkedClass, $iObjectId);
			$oP->add($this->GetObjectRow($oP, $oLinkObj, $oLinkObj->GetKey()));
		}
	}
	
	public function GetObjectModificationDlg()
	{
		
	}
	
	protected function GetTableConfig()
	{
		$aAttribs = array();
		$aAttribs['form::select'] = array('label' => "<input type=\"checkbox\" onClick=\"CheckAll('.selectList{$this->sInputid}:not(:disabled)', this.checked);\" class=\"checkAll\"></input>", 'description' => Dict::S('UI:SelectAllToggle+'));

		foreach($this->aZlist as $sLinkedAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($this->sLinkedClass, $sLinkedAttCode);
			$aAttribs[$sLinkedAttCode] = array('label' => MetaModel::GetLabel($this->sLinkedClass, $sLinkedAttCode), 'description' => $oAttDef->GetOrderByHint());
		}
		return $aAttribs;	
	}
	
	public function GetRow($oPage, $sRealClass, $aValues, $iTempId)
	{
		if ($sRealClass == '')
		{
			$sRealClass = $this->sLinkedClass;
		}
		$oLinkObj = new $sRealClass();
		$oLinkObj->UpdateObjectFromPostedForm($this->sInputid);
		
		return $this->GetObjectRow($oPage, $oLinkObj, $iTempId);
	}
	
	protected function GetObjectRow($oPage, $oLinkObj, $iTempId)
	{
		$aAttribs = $this->GetTableConfig();
		$aRow = array();
		$aRow['form::select'] = '<input type="checkbox" class="selectList'.$this->sInputid.'" value="'.($iTempId).'"/>';
		foreach($this->aZlist as $sLinkedAttCode)
		{
			$aRow[$sLinkedAttCode] = $oLinkObj->GetAsHTML($sLinkedAttCode);
		}
		return $oPage->GetTableRow($aRow, $aAttribs);		
	}
	
	/**
	 * Initializes the default search parameters based on 1) a 'current' object and 2) the silos defined by the context
	 * @param DBObject $oSourceObj
	 * @param DBSearch $oSearch
	 */
	protected function SetSearchDefaultFromContext($oSourceObj, &$oSearch)
	{
		$oAppContext = new ApplicationContext();
		$sSrcClass = get_class($oSourceObj);
		$sDestClass = $oSearch->GetClass();
		foreach($oAppContext->GetNames() as $key)
		{
			// Find the value of the object corresponding to each 'context' parameter
			$aCallSpec = array($sSrcClass, 'MapContextParam');
			$sAttCode = '';
			if (is_callable($aCallSpec))
			{
				$sAttCode = call_user_func($aCallSpec, $key); // Returns null when there is no mapping for this parameter					
			}

			if (MetaModel::IsValidAttCode($sSrcClass, $sAttCode))
			{
				$oAttDef = MetaModel::GetAttributeDef($sSrcClass, $sAttCode);
				$defaultValue = $oSourceObj->Get($sAttCode);

				// Find the attcode for the same 'context' parameter in the destination class
				// and sets its value as the default value for the search condition
				$aCallSpec = array($sDestClass, 'MapContextParam');
				$sAttCode = '';
				if (is_callable($aCallSpec))
				{
					$sAttCode = call_user_func($aCallSpec, $key); // Returns null when there is no mapping for this parameter					
				}
	
				if (MetaModel::IsValidAttCode($sDestClass, $sAttCode) && !empty($defaultValue))
				{
					$oSearch->AddCondition($sAttCode, $defaultValue);
				}
			}
		}
	}
}
