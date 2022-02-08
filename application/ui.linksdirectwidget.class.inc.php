<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;

/**
 * Class UILinksWidgetDirect
 *
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class UILinksWidgetDirect
{
	protected $sClass;
	protected $sAttCode;
	protected $sInputid;
	protected $sNameSuffix;
	protected $sLinkedClass;

	/**
	 * UILinksWidgetDirect constructor.
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param string $sInputId
	 * @param string $sNameSuffix
	 */
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

	/**
	 * @param WebPage $oPage
	 * @param DBObjectSet|ormLinkSet $oValue
	 * @param array $aArgs
	 * @param string $sFormPrefix
	 * @param DBObject $oCurrentObj
	 *
	 * @since 2.7.7 3.0.1 3.1.0 N°3129 Remove default value for $aArgs for PHP 8.0 compatibility (handling wrong values at method start)
	 */
	public function Display(WebPage $oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj)
	{
		if (empty($aArgs)) {
			$aArgs = [];
		}

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

	/**
	 * @param WebPage $oPage
	 * @param DBObjectSet $oValue
	 * @param array $aArgs
	 * @param string $sFormPrefix
	 * @param DBObject $oCurrentObj
	 * @param bool $bDisplayMenu
	 *
	 * @since 2.7.7 3.0.1 3.1.0 N°3129 Remove default value for $aArgs for PHP 8.0 compatibility (protected method, always called with default value)
	 */
	protected function DisplayAsBlock(WebPage $oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj, $bDisplayMenu)
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
                'menu_actions_target' => '_blank',
				'default' => $aDefaults,
				'table_id' => $this->sClass.'_'.$this->sAttCode,
			);

			$oBlock = new DisplayBlock($oFilter, 'list', false);
			$oBlock->Display($oPage, $this->sInputid, $aParams);
		}	
	}

	/**
	 * @param WebPage $oPage
	 * @param string $sProposedRealClass
	 */
	public function GetObjectCreationDlg(WebPage $oPage, $sProposedRealClass = '', $oSourceObj = null)
	{
		// For security reasons: check that the "proposed" class is actually a subclass of the linked class
		// and that the current user is allowed to create objects of this class
		$sRealClass = '';
		//$oPage->add('<div class="wizContainer" style="vertical-align:top;"><div>');
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
			$oLinksetDef = MetaModel::GetAttributeDef($this->sClass, $this->sAttCode);
			$sExtKeyToMe = $oLinksetDef->GetExtKeyToMe();
			$aFieldFlags = array( $sExtKeyToMe => OPT_ATT_HIDDEN);
			$oObj = DBObject::MakeDefaultInstance($sRealClass);
			$aPrefillParam = array('source_obj' => $oSourceObj);
			$oObj->PrefillForm('creation_from_editinplace', $aPrefillParam);
		 	cmdbAbstractObject::DisplayCreationForm($oPage, $sRealClass, $oObj, array(), array('formPrefix' => $this->sInputid, 'noRelations' => true, 'fieldsFlags' => $aFieldFlags));
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

	/**
	 * @param WebPage $oPage
	 * @param DBObjectSet $oValue
	 * @param array $aArgs
	 * @param string $sFormPrefix
	 * @param DBObject $oCurrentObj
	 * @param array $aButtons
	 *
	 * @since 2.7.7 3.0.1 3.1.0 N°3129 Remove default value for $aArgs for PHP 8.0 compatibility (protected method, caller already handles it)
	 */
	protected function DisplayEditInPlace(WebPage $oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj, $aButtons = array('create', 'delete'))
	{
		$aAttribs = $this->GetTableConfig();
		$oValue->Rewind();
		$aData = array();
		while ($oLinkObj = $oValue->Fetch()) {
			$aRow = array();
			$aRow['form::select'] = '<input type="checkbox" class="selectList'.$this->sInputid.'" value="'.$oLinkObj->GetKey().'"/>';
			foreach ($this->aZlist as $sLinkedAttCode) {
				$aRow[$sLinkedAttCode] = $oLinkObj->GetAsHTML($sLinkedAttCode);
			}
			$aData[] = $aRow;
		}
		$oDiv = UIContentBlockUIBlockFactory::MakeStandard($this->sInputid, ['listContainer']);
		$oPage->AddSubBlock($oDiv);
		$oDatatable = DataTableUIBlockFactory::MakeForForm($this->sInputid, $aAttribs, $aData);
		$oDatatable->SetOptions(['select_mode' => 'custom']);
		$oDiv->AddSubBlock($oDatatable);
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
		// Don't automatically launch the search if the table is huge
		$bDoSearch = !utils::IsHighCardinality($this->sLinkedClass);
		$sJSDoSearch = $bDoSearch ? 'true' : 'false';
		$oPage->add_ready_script("$('#{$this->sInputid}').directlinks({class_name: '$this->sClass', att_code: '$this->sAttCode', input_name:'$sInputName', labels: $sJSONLabels, submit_to: '$sSubmitUrl', buttons: $sJSONButtons, oWizardHelper: $sWizHelper, do_search: $sJSDoSearch});");
	}

	/**
	 * @param WebPage $oPage
	 * @param DBObject $oCurrentObj
	 * @param $aAlreadyLinked
	 *
	 * @param array $aPrefillFormParam
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \OQLException
	 */
	public function GetObjectsSelectionDlg($oPage, $oCurrentObj, $aAlreadyLinked, $aPrefillFormParam = array())
	{
		//$oPage->add("<div class=\"wizContainer\" style=\"vertical-align:top;\">\n");

		$oHiddenFilter = new DBObjectSearch($this->sLinkedClass);
		if (($oCurrentObj != null) && MetaModel::IsSameFamilyBranch($this->sLinkedClass, $this->sClass)) {
			// Prevent linking to self if the linked object is of the same family
			// and already present in the database
			if (!$oCurrentObj->IsNew()) {
				$oHiddenFilter->AddCondition('id', $oCurrentObj->GetKey(), '!=');
			}
		}
		if (count($aAlreadyLinked) > 0) {
			$oHiddenFilter->AddCondition('id', $aAlreadyLinked, 'NOTIN');
		}
		$oHiddenCriteria = $oHiddenFilter->GetCriteria();
		$aArgs = $oHiddenFilter->GetInternalParams();
		$sHiddenCriteria = $oHiddenCriteria->RenderExpression(false, $aArgs);

		$oLinkSetDef = MetaModel::GetAttributeDef($this->sClass, $this->sAttCode);
		$valuesDef = $oLinkSetDef->GetValuesDef();
		if ($valuesDef === null)
		{
			$oFilter = new DBObjectSearch($this->sLinkedClass);
		} else {
			if (!$valuesDef instanceof ValueSetObjects) {
				throw new Exception('Error: only ValueSetObjects are supported for "allowed_values" in AttributeLinkedSet ('.$this->sClass.'/'.$this->sAttCode.').');
			}
			$oFilter = DBObjectSearch::FromOQL($valuesDef->GetFilterExpression());
		}

		if ($oCurrentObj != null) {
			$this->SetSearchDefaultFromContext($oCurrentObj, $oFilter);

			$aArgs = array_merge($oCurrentObj->ToArgs('this'), $oFilter->GetInternalParams());
			$oFilter->SetInternalParams($aArgs);
			$aPrefillFormParam['filter'] = $oFilter;
			$oCurrentObj->PrefillForm('search', $aPrefillFormParam);
		}
		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$oPage->AddUiBlock($oBlock->GetDisplay($oPage, "SearchFormToAdd_{$this->sInputid}",
			array(
				'result_list_outer_selector' => "SearchResultsToAdd_{$this->sInputid}",
				'table_id' => "add_{$this->sInputid}",
				'table_inner_id' => "ResultsToAdd_{$this->sInputid}",
				'selection_mode' => true,
				'cssCount' => "#count_{$this->sInputid}",
				'query_params' => $oFilter->GetInternalParams(),
				'hidden_criteria' => $sHiddenCriteria,
			)
		));
		$sEmptyList = Dict::S('UI:Message:EmptyList:UseSearchForm');
		$sCancel = Dict::S('UI:Button:Cancel');
		$sAdd = Dict::S('UI:Button:Add');

		$oPage->add(<<<HTML
<form id="ObjectsAddForm_{$this->sInputid}">
    <div id="SearchResultsToAdd_{$this->sInputid}">
        <div style="background: #fff; border:0; text-align:center; vertical-align:middle;"><p>{$sEmptyList}</p></div>
    </div>
    <input type="hidden" id="count_{$this->sInputid}" value="0"/>
</form>
HTML
		);
	}

	/**
	 * Search for objects to be linked to the current object (i.e "remote" objects)
	 *
	 * @param WebPage $oP The page used for the output (usually an AjaxWebPage)
	 * @param string $sRemoteClass Name of the "remote" class to perform the search on, must be a derived class of $this->sLinkedClass
	 * @param array $aAlreadyLinked Array of indentifiers of objects which are already linke to the current object (or about to be linked)
	 * @param DBObject $oCurrentObj The object currently being edited... if known...
	 * @param array $aPrefillFormParam
	 *
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function SearchObjectsToAdd(WebPage $oP, $sRemoteClass = '', $aAlreadyLinked = array(), $oCurrentObj = null, $aPrefillFormParam = array())
	{
		if ($sRemoteClass == '')
		{
			$sRemoteClass = $this->sLinkedClass;
		}
		$oLinkSetDef = MetaModel::GetAttributeDef($this->sClass, $this->sAttCode);
		$valuesDef = $oLinkSetDef->GetValuesDef();
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
			// and already present in the database
			if (!$oCurrentObj->IsNew())
			{
				$oFilter->AddCondition('id', $oCurrentObj->GetKey(), '!=');
			}
		}
		if ($oCurrentObj != null)
		{
			$this->SetSearchDefaultFromContext($oCurrentObj, $oFilter);

			$aArgs = array_merge($oCurrentObj->ToArgs('this'), $oFilter->GetInternalParams());
			$oFilter->SetInternalParams($aArgs);
			
			$aPrefillFormParam['filter'] = $oFilter;
			$oCurrentObj->PrefillForm('search', $aPrefillFormParam);
		}
		if (count($aAlreadyLinked) > 0)
		{
			$oFilter->AddCondition('id', $aAlreadyLinked, 'NOTIN');
		}
		$oBlock = new DisplayBlock($oFilter, 'list', false);
		$oBlock->Display($oP, "ResultsToAdd_{$this->sInputid}", array('menu' => false, 'cssCount'=> '#count_'.$this->sInputid , 'selection_mode' => true, 'table_id' => 'add_'.$this->sInputid)); // Don't display the 'Actions' menu on the results
	}

	/**
	 * @param WebPage $oP
	 * @param $oFullSetFilter
	 */
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

	/**
	 * @param WebPage $oPage
	 * @param string $sRealClass
	 * @param array $aValues
	 * @param int $iTempId
	 * @return mixed
	 */
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

	/**
	 * @param WebPage $oPage
	 * @param $oLinkObj
	 * @param int $iTempId
	 * @return mixed
	 */
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
	 * @param WebPage $oPage
	 * @param $sRealClass
	 * @param $aValues
	 * @param int $iTempId
	 *
	 * @return array
	 */
	public function GetFormRow($oPage, $sRealClass, $aValues, $iTempId)
	{
		if ($sRealClass == '')
		{
			$sRealClass = $this->sLinkedClass;
		}
		$oLinkObj = new $sRealClass();
		$oLinkObj->UpdateObjectFromPostedForm($this->sInputid);

		$aAttribs = $this->GetTableConfig();
		$aRow = array();
		$aRow[] = '<input type="checkbox" class="selectList'.$this->sInputid.'" value="'.($iTempId).'"/>';
		foreach($this->aZlist as $sLinkedAttCode)
		{
			$aRow[] = $oLinkObj->GetAsHTML($sLinkedAttCode);
		}
		return $aRow;
	}
	
	/**
	 * Initializes the default search parameters based on 1) a 'current' object and 2) the silos defined by the context
	 * @param DBObject $oSourceObj
	 * @param DBSearch|DBObjectSearch $oSearch
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
