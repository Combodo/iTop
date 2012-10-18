<?php
// Copyright (C) 2012 Combodo SARL
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
		$aZList = MetaModel::FlattenZList(MetaModel::GetZListItems($this->sLinkedClass, 'list'));
		foreach($aZList as $sLinkedAttCode)
		{
			if ($sLinkedAttCode != $sExtKeyToMe)
			{
				$oAttDef = MetaModel::GetAttributeDef($this->sLinkedClass, $sLinkedAttCode);
				
				if (!$oAttDef->IsExternalField() || ($oAttDef->GetKeyAttCode() != $sExtKeyToMe) )
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
				$oPage->p("<a target=\"_blank\" href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=new&class=$sTargetClass&$sParams{$sDefault}\">".Dict::Format('UI:ClickToCreateNew', Metamodel::GetName($sTargetClass))."</a>\n");
			}
			$this->DisplayAsBlock($oPage, $oValue, $aArgs = array(), $sFormPrefix, $oCurrentObj, false /* bDisplayMenu*/);
			break;
			
			case LINKSET_EDITMODE_INPLACE: // The whole linkset can be edited 'in-place'
			$this->DisplayEditInPlace($oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj);
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
	
	protected function DisplayEditInPlace(WebPage $oPage, DBObjectSet $oValue, $aArgs = array(), $sFormPrefix, $oCurrentObj)
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
		$oPage->add_ready_script("$('#{$this->sInputid}').directlinks({class_name: '$this->sClass', att_code: '$this->sAttCode', input_name:'$sInputName' });");
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
		$aAttribs = $this->GetTableConfig();
		if ($sRealClass == '')
		{
			$sRealClass = $this->sLinkedClass;
		}
		$oLinkObj = new $sRealClass();
		$oLinkObj->UpdateObjectFromPostedForm($this->sInputid);
		
		$aRow = array();
		$aRow['form::select'] = '<input type="checkbox" class="selectList'.$this->sInputid.'" value="'.(-$iTempId).'"/>';
		foreach($this->aZlist as $sLinkedAttCode)
		{
			$aRow[$sLinkedAttCode] = $oLinkObj->GetAsHTML($sLinkedAttCode);
		}
		return $oPage->GetTableRow($aRow, $aAttribs);
	}
	
	public function UpdateFromArray($oObj, $aData)
	{
		
	}
}
