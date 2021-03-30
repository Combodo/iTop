<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\StaticTable\FormTableRow\FormTableRow;
use Combodo\iTop\Application\UI\Links\Indirect\BlockIndirectLinksEdit\BlockIndirectLinksEdit;
use Combodo\iTop\Application\UI\Links\Indirect\BlockObjectPickerDialog\BlockObjectPickerDialog;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;

require_once(APPROOT.'application/displayblock.class.inc.php');

class UILinksWidget 
{
	protected $m_sClass;
	protected $m_sAttCode;
	protected $m_sNameSuffix;
	protected $m_iInputId;
	protected $m_aAttributes;
	protected $m_sExtKeyToRemote;
	protected $m_sExtKeyToMe;
	protected $m_sLinkedClass;
	protected $m_sRemoteClass;
	protected $m_bDuplicatesAllowed;
	/** @var string[] list of editables attcodes */
	protected $m_aEditableFields;
	protected $m_aTableConfig;

	/**
	 * UILinksWidget constructor.
	 *
	 * @param string $sClass
	 * @param string $sAttCode AttributeLinkedSetIndirect attcode
	 * @param int $iInputId
	 * @param string $sNameSuffix
	 * @param bool $bDuplicatesAllowed
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public function __construct($sClass, $sAttCode, $iInputId, $sNameSuffix = '', $bDuplicatesAllowed = false)
	{
		$this->m_sClass = $sClass;
		$this->m_sAttCode = $sAttCode;
		$this->m_sNameSuffix = $sNameSuffix;
		$this->m_iInputId = $iInputId;
		$this->m_bDuplicatesAllowed = $bDuplicatesAllowed;
		$this->m_aEditableFields = array();

		/** @var AttributeLinkedSetIndirect $oAttDef */
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sAttCode);
		$this->m_sLinkedClass = $oAttDef->GetLinkedClass();
		$this->m_sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
		$this->m_sExtKeyToMe = $oAttDef->GetExtKeyToMe();

		/** @var AttributeExternalKey $oLinkingAttDef */
		$oLinkingAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $this->m_sExtKeyToRemote);
		$this->m_sRemoteClass = $oLinkingAttDef->GetTargetClass();

		$this->m_aEditableFields = array();
		$this->m_aTableConfig = array();
		$this->m_aTableConfig['form::checkbox'] = array(
			'label' => "<input class=\"select_all\" type=\"checkbox\" value=\"1\" onClick=\"CheckAll('#linkedset_{$this->m_sAttCode}{$this->m_sNameSuffix} .selection', this.checked); oWidget".$this->m_iInputId.".OnSelectChange();\">",
			'description' => Dict::S('UI:SelectAllToggle+'),
		);

		$aLnkAttDefsToDisplay = MetaModel::GetZListAttDefsFilteredForIndirectLinkClass($sClass, $sAttCode);
		foreach ($aLnkAttDefsToDisplay as $oLnkAttDef)
		{
			$sLnkAttCode = $oLnkAttDef->GetCode();
			$this->m_aEditableFields[] = $sLnkAttCode;
			$this->m_aTableConfig[$sLnkAttCode] = array('label' => $oLnkAttDef->GetLabel(), 'description' => $oLnkAttDef->GetDescription());
		}

		$this->m_aTableConfig['static::key'] = array(
			'label' => MetaModel::GetName($this->m_sRemoteClass),
			'description' => MetaModel::GetClassDescription($this->m_sRemoteClass),
		);
		$this->m_aEditableFields[] = $this->m_sExtKeyToRemote;

		$aRemoteAttDefsToDisplay = MetaModel::GetZListAttDefsFilteredForIndirectRemoteClass($this->m_sRemoteClass);
		foreach ($aRemoteAttDefsToDisplay as $oRemoteAttDef) {
			$sRemoteAttCode = $oRemoteAttDef->GetCode();
			$this->m_aTableConfig['static::'.$sRemoteAttCode] = array(
				'label' => $oRemoteAttDef->GetLabel(),
				'description' => $oRemoteAttDef->GetDescription(),
			);
		}
	}

	/**
	 * A one-row form for editing a link record
	 *
	 * @param WebPage $oP Web page used for the ouput
	 * @param DBObject $oLinkedObj Remote object
	 * @param DBObject|int $linkObjOrId Either the lnk object or a unique number for new link records to add
	 * @param array $aArgs Extra context arguments
	 * @param DBObject $oCurrentObj The object to which all the elements of the linked set refer to
	 * @param int $iUniqueId A unique identifier of new links
	 * @param boolean $bReadOnly Display link as editable or read-only. Default is false (editable)
	 *
	 * @return array The HTML fragment of the one-row form
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 */
	protected function GetFormRow(WebPage $oP, DBObject $oLinkedObj, $linkObjOrId, $aArgs, $oCurrentObj, $iUniqueId, $bReadOnly = false)
	{
		$sPrefix = "$this->m_sAttCode{$this->m_sNameSuffix}";
		$aRow = array();
		$aFieldsMap = array();
		$iKey = 0;

		if (is_object($linkObjOrId) && (!$linkObjOrId->IsNew()))
		{
			$iKey = $linkObjOrId->GetKey();
			$iRemoteObjKey = $linkObjOrId->Get($this->m_sExtKeyToRemote);
			$sPrefix .= "[$iKey][";
			$sNameSuffix = "]"; // To make a tabular form
			$aArgs['prefix'] = $sPrefix;
			$aArgs['wizHelper'] = "oWizardHelper{$this->m_iInputId}{$iKey}";
			$aArgs['this'] = $linkObjOrId;

			if ($bReadOnly)
			{
				$aRow['form::checkbox'] = "";
				foreach ($this->m_aEditableFields as $sFieldCode)
				{
					$sDisplayValue = $linkObjOrId->GetEditValue($sFieldCode);
					$aRow[$sFieldCode] = $sDisplayValue;
				}
			}
			else
			{
				$aRow['form::checkbox'] = "<input class=\"selection\" data-remote-id=\"$iRemoteObjKey\" data-link-id=\"$iKey\" data-unique-id=\"$iUniqueId\" type=\"checkbox\" onClick=\"oWidget".$this->m_iInputId.".OnSelectChange();\" value=\"$iKey\">";
				foreach ($this->m_aEditableFields as $sFieldCode)
				{
					$sSafeFieldId = $this->GetFieldId($linkObjOrId->GetKey(), $sFieldCode);
					$this->AddRowForFieldCode($aRow, $sFieldCode, $aArgs, $linkObjOrId, $oP, $sNameSuffix, $sSafeFieldId);
					$aFieldsMap[$sFieldCode] = $sSafeFieldId;
				}
			}

			$sState = $linkObjOrId->GetState();
			$sRemoteKeySafeFieldId = $this->GetFieldId($aArgs['this']->GetKey(), $this->m_sExtKeyToRemote);;
		}
		else
		{
			// form for creating a new record
			if (is_object($linkObjOrId))
			{
				// New link existing only in memory
				$oNewLinkObj = $linkObjOrId;
				$iRemoteObjKey = $oNewLinkObj->Get($this->m_sExtKeyToRemote);
				$oNewLinkObj->Set($this->m_sExtKeyToMe,
					$oCurrentObj); // Setting the extkey with the object also fills the related external fields
			}
			else
			{
				$iRemoteObjKey = $linkObjOrId;
				$oNewLinkObj = MetaModel::NewObject($this->m_sLinkedClass);
				$oRemoteObj = MetaModel::GetObject($this->m_sRemoteClass, $iRemoteObjKey);
				$oNewLinkObj->Set($this->m_sExtKeyToRemote,
					$oRemoteObj); // Setting the extkey with the object alsoo fills the related external fields
				$oNewLinkObj->Set($this->m_sExtKeyToMe,
					$oCurrentObj); // Setting the extkey with the object also fills the related external fields
			}
			$sPrefix .= "[-$iUniqueId][";
			$sNameSuffix = "]"; // To make a tabular form
			$aArgs['prefix'] = $sPrefix;
			$aArgs['wizHelper'] = "oWizardHelper{$this->m_iInputId}_".($iUniqueId < 0 ? -$iUniqueId : $iUniqueId);
			$aArgs['this'] = $oNewLinkObj;
			$sInputValue = $iUniqueId > 0 ? "-$iUniqueId" : "$iUniqueId";
			$aRow['form::checkbox'] = "<input class=\"selection\" data-remote-id=\"$iRemoteObjKey\" data-link-id=\"0\" data-unique-id=\"$iUniqueId\" type=\"checkbox\" onClick=\"oWidget".$this->m_iInputId.".OnSelectChange();\" value=\"$sInputValue\">";

			if ($iUniqueId > 0)
			{
				// Rows created with ajax call need OnLinkAdded call.
				//
				$oP->add_ready_script(
					<<<EOF
PrepareWidgets();
oWidget{$this->m_iInputId}.OnLinkAdded($iUniqueId, $iRemoteObjKey);
EOF
				);
			}
			else
			{
				// Rows added before loading the form don't have to call OnLinkAdded.
				// Listeners are already present and DOM is not recreated
				$iPositiveUniqueId = -$iUniqueId;
				$oP->add_ready_script(<<<EOF
oWidget{$this->m_iInputId}.AddLink($iPositiveUniqueId, $iRemoteObjKey);
EOF
				);
			}

			foreach($this->m_aEditableFields as $sFieldCode)
			{
				$sSafeFieldId = $this->GetFieldId($iUniqueId, $sFieldCode);
				$this->AddRowForFieldCode($aRow, $sFieldCode, $aArgs, $oNewLinkObj, $oP, $sNameSuffix, $sSafeFieldId);
				$aFieldsMap[$sFieldCode] = $sSafeFieldId;

				$sValue = $oNewLinkObj->Get($sFieldCode);
				$oP->add_ready_script(
					<<<JS
oWidget{$this->m_iInputId}.OnValueChange($iKey, $iUniqueId, '$sFieldCode', '$sValue');
JS
				);
			}

			$sState = '';
			$sRemoteKeySafeFieldId = $this->GetFieldId($iUniqueId, $this->m_sExtKeyToRemote);
		}

		if (!$bReadOnly)
		{
			$sExtKeyToMeId = utils::GetSafeId($sPrefix.$this->m_sExtKeyToMe);
			$aFieldsMap[$this->m_sExtKeyToMe] = $sExtKeyToMeId;
			$aRow['form::checkbox'] .= "<input type=\"hidden\" id=\"$sExtKeyToMeId\" value=\"".$oCurrentObj->GetKey()."\">";

			$sExtKeyToRemoteId = utils::GetSafeId($sPrefix.$this->m_sExtKeyToRemote);
			$aFieldsMap[$this->m_sExtKeyToRemote] = $sExtKeyToRemoteId;
			$aRow['form::checkbox'] .= "<input type=\"hidden\" id=\"$sExtKeyToRemoteId\" value=\"$iRemoteObjKey\">";
		}

		// Adding fields from remote class
		// all fields are embedded in a span + added to $aFieldsMap array so that we can refresh them after extkey change
		$aRemoteFieldsMap = [];
		foreach (MetaModel::GetZListItems($this->m_sRemoteClass, 'list') as $sFieldCode)
		{
			$sSafeFieldId = $this->GetFieldId($aArgs['this']->GetKey(), $sFieldCode);
			$aRow['static::'.$sFieldCode] = "<span id='field_$sSafeFieldId'>".$oLinkedObj->GetAsHTML($sFieldCode).'</span>';
			$aRemoteFieldsMap[$sFieldCode] = $sSafeFieldId;
		}
		// id field is needed so that remote object could be load server side
		$aRemoteFieldsMap['id'] = $sRemoteKeySafeFieldId;

		// Generate WizardHelper to update dependant fields
		$this->AddWizardHelperInit($oP, $aArgs['wizHelper'], $this->m_sLinkedClass, $sState, $aFieldsMap);
		//instantiate specific WizarHelper instance for remote class fields refresh
		$bHasExtKeyUpdatingRemoteClassFields = (
			array_key_exists('replaceDependenciesByRemoteClassFields', $aArgs)
			&& ($aArgs['replaceDependenciesByRemoteClassFields'])
		);
		if ($bHasExtKeyUpdatingRemoteClassFields)
		{
			$this->AddWizardHelperInit($oP, $aArgs['wizHelperRemote'], $this->m_sRemoteClass, $sState, $aRemoteFieldsMap);
		}

		return $aRow;
	}

	private function AddRowForFieldCode(&$aRow, $sFieldCode, &$aArgs, $oLnk, $oP, $sNameSuffix, $sSafeFieldId): void
	{
		if (($sFieldCode === $this->m_sExtKeyToRemote))
		{
			// current field is the lnk extkey to the remote class
			$aArgs['replaceDependenciesByRemoteClassFields'] = true;
			$sRowFieldCode = 'static::key';
			$aArgs['wizHelperRemote'] = $aArgs['wizHelper'].'_remote';
			$aRemoteAttDefs = MetaModel::GetZListAttDefsFilteredForIndirectRemoteClass($this->m_sRemoteClass);
			$aRemoteCodes = array_map(
				function ($value) {
					return $value->GetCode();
				},
				$aRemoteAttDefs
			);
			$aArgs['remoteCodes'] = $aRemoteCodes;
		}
		else
		{
			$aArgs['replaceDependenciesByRemoteClassFields'] = false;
			$sRowFieldCode = $sFieldCode;
		}
		$sValue = $oLnk->Get($sFieldCode);
		$sDisplayValue = $oLnk->GetEditValue($sFieldCode);
		$oAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $sFieldCode);

		$aRow[$sRowFieldCode] = '<div class="field_container" style="border:none;"><div class="field_data"><div class="field_value">'
			.cmdbAbstractObject::GetFormElementForField(
				$oP,
				$this->m_sLinkedClass,
				$sFieldCode,
				$oAttDef,
				$sValue,
				$sDisplayValue,
				$sSafeFieldId,
				$sNameSuffix,
				0,
				$aArgs
			)
			.'</div></div></div>';
	}

	private function GetFieldId($iLnkId, $sFieldCode, $bSafe = true)
	{
		$sFieldId = $this->m_iInputId.'_'.$sFieldCode.'['.$iLnkId.']';

		return ($bSafe) ? utils::GetSafeId($sFieldId) : $sFieldId;
	}

	private function AddWizardHelperInit($oP, $sWizardHelperVarName, $sWizardHelperClass, $sState, $aFieldsMap): void
	{
		$iFieldsCount = count($aFieldsMap);
		$sJsonFieldsMap = json_encode($aFieldsMap);

		$oP->add_script(
			<<<JS
var $sWizardHelperVarName = new WizardHelper('$sWizardHelperClass', '', '$sState');
$sWizardHelperVarName.SetFieldsMap($sJsonFieldsMap);
$sWizardHelperVarName.SetFieldsCount($iFieldsCount);
$sWizardHelperVarName.SetReturnNotEditableFields(true);
$sWizardHelperVarName.SetWizHelperJsVarName('$sWizardHelperVarName');
JS
		);
	}

	/**
	 * Display the table with the form for editing all the links at once
	 *
	 * @param array $aConfig The table's header configuration
	 * @param array $aData The tabular data to be displayed
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\DataTable\StaticTable\FormTable\FormTable
	 */
	protected function GetFormTableBlock($aConfig, $aData)
	{
		return DataTableUIBlockFactory::MakeForForm("{$this->m_sAttCode}{$this->m_sNameSuffix}", $aConfig, $aData);
	}


	/**
	 * Get the HTML fragment corresponding to the linkset editing widget
	 *
	 * @param WebPage $oPage
	 * @param DBObject|ormLinkSet $oValue
	 * @param array $aArgs Extra context arguments
	 * @param string $sFormPrefix prefix of the fields in the current form
	 * @param DBObject $oCurrentObj the current object to which the linkset is related
	 *
	 * @return string The HTML fragment to be inserted into the page
	 * @throws \ArchivedObjectException
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function Display(WebPage $oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj): string
	{
		$sLinkedSetId = "{$this->m_sAttCode}{$this->m_sNameSuffix}";

		$oBlock = new BlockIndirectLinksEdit("linkedset_{$sLinkedSetId}", ["ibo-block-indirect-links--edit"]);

		$oBlock->sLinkedSetId = $sLinkedSetId;
		$oBlock->sClass = $this->m_sClass;
		$oBlock->sAttCode = $this->m_sAttCode;
		$oBlock->iInputId = $this->m_iInputId;
		$oBlock->sNameSuffix = $this->m_sNameSuffix;
		$oBlock->bDuplicates = ($this->m_bDuplicatesAllowed) ? 'true' : 'false';
		$oBlock->oWizHelper = 'oWizardHelper'.$sFormPrefix;
		$oBlock->sExtKeyToRemote = $this->m_sExtKeyToRemote;
		// Don't automatically launch the search if the table is huge
		$oBlock->bJSDoSearch = utils::IsHighCardinality($this->m_sRemoteClass) ? 'false' : 'true';
		$oBlock->sFormPrefix = $sFormPrefix;
		$oBlock->sRemoteClass = $this->m_sRemoteClass;

		$oValue->Rewind();
		$aForm = array();
		$iAddedId = -1; // Unique id for new links
		while ($oCurrentLink = $oValue->Fetch())
		{
			// We try to retrieve the remote object as usual
			$oLinkedObj = MetaModel::GetObject($this->m_sRemoteClass, $oCurrentLink->Get($this->m_sExtKeyToRemote),
				false /* Must not be found */);
			// If successful, it means that we can edit its link
			if ($oLinkedObj !== null) {
				$bReadOnly = false;
			} // Else we retrieve it without restrictions (silos) and will display its link as readonly
			else {
				$bReadOnly = true;
				$oLinkedObj = MetaModel::GetObject($this->m_sRemoteClass, $oCurrentLink->Get($this->m_sExtKeyToRemote), false /* Must not be found */, true);
			}

			if ($oCurrentLink->IsNew()) {
				$key = $iAddedId--;
			} else {
				$key = $oCurrentLink->GetKey();
			}
			$aForm[$key] = $this->GetFormRow($oPage, $oLinkedObj, $oCurrentLink, $aArgs, $oCurrentObj, $key, $bReadOnly);
		}
		$oDataTable = DataTableUIBlockFactory::MakeForForm("{$this->m_sAttCode}{$this->m_sNameSuffix}", $this->m_aTableConfig, $aForm);
		$oDataTable->SetOptions(['select_mode' => 'custom']);
		$oBlock->AddSubBlock($oDataTable);

		$oBlock->AddControls();

		return ConsoleBlockRenderer::RenderBlockTemplateInPage($oPage, $oBlock);
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected static function GetTargetClass($sClass, $sAttCode)
	{
		/** @var AttributeLinkedSet $oAttDef */
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sLinkedClass = $oAttDef->GetLinkedClass();
		$sTargetClass = '';
		switch(get_class($oAttDef))
		{
			case 'AttributeLinkedSetIndirect':
			/** @var AttributeExternalKey $oLinkingAttDef */
			/** @var AttributeLinkedSetIndirect $oAttDef */
			$oLinkingAttDef = 	MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
			$sTargetClass = $oLinkingAttDef->GetTargetClass();
			break;

			case 'AttributeLinkedSet':
			$sTargetClass = $sLinkedClass;
			break;
		}
		
		return $sTargetClass;
	}

	/**
	 * @param WebPage $oPage
	 * @param DBObject $oCurrentObj
	 * @param $sJson
	 * @param array $aAlreadyLinkedIds
	 *
	 * @throws DictExceptionMissingString
	 * @throws Exception
	 */
	public function GetObjectPickerDialog($oPage, $oCurrentObj, $sJson, $aAlreadyLinkedIds = array(), $aPrefillFormParam = array())
	{
		$oAlreadyLinkedFilter = new DBObjectSearch($this->m_sRemoteClass);
		if (!$this->m_bDuplicatesAllowed && count($aAlreadyLinkedIds) > 0) {
			$oAlreadyLinkedFilter->AddCondition('id', $aAlreadyLinkedIds, 'NOTIN');
			$oAlreadyLinkedExpression = $oAlreadyLinkedFilter->GetCriteria();
			$sAlreadyLinkedExpression = $oAlreadyLinkedExpression->RenderExpression();
		} else {
			$sAlreadyLinkedExpression = '';
		}

		$oFilter = new DBObjectSearch($this->m_sRemoteClass);

		if (!empty($oCurrentObj)) {
			$this->SetSearchDefaultFromContext($oCurrentObj, $oFilter);
			$aPrefillFormParam['filter'] = $oFilter;
			$aPrefillFormParam['dest_class'] = $this->m_sRemoteClass;
			$oCurrentObj->PrefillForm('search', $aPrefillFormParam);
		}

		$sLinkedSetId = "{$this->m_sAttCode}{$this->m_sNameSuffix}";

		$oBlock = new BlockObjectPickerDialog();
		$oPage->AddUiBlock($oBlock);

		$oBlock->sLinkedSetId = $sLinkedSetId;
		$oBlock->iInputId = $this->m_iInputId;
		$oBlock->sLinkedClassName = MetaModel::GetName($this->m_sLinkedClass);
		$oBlock->sClassName = MetaModel::GetName($this->m_sClass);

		$oDisplayBlock = new DisplayBlock($oFilter, 'search', false);
		$oBlock->AddSubBlock($oDisplayBlock->GetDisplay($oPage, "SearchFormToAdd_{$sLinkedSetId}",
			array(
				'menu' => false,
				'result_list_outer_selector' => "SearchResultsToAdd_{$sLinkedSetId}",
				'table_id' => "add_{$sLinkedSetId}",
				'table_inner_id' => "ResultsToAdd_{$sLinkedSetId}",
				'selection_mode' => true,
				'json' => $sJson,
				'cssCount' => '#count_'.$this->m_sAttCode.$this->m_sNameSuffix,
				'query_params' => $oFilter->GetInternalParams(),
				'hidden_criteria' => $sAlreadyLinkedExpression,
			)));

		$oBlock->AddForm();
	}

	/**
	 * Search for objects to be linked to the current object (i.e "remote" objects)
	 *
	 * @param WebPage $oP The page used for the output (usually an AjaxWebPage)
	 * @param string $sRemoteClass Name of the "remote" class to perform the search on, must be a derived class of
	 *     m_sRemoteClass
	 * @param array $aAlreadyLinkedIds List of IDs of objects of "remote" class already linked, to be filtered out of
	 *     the search
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function SearchObjectsToAdd(WebPage $oP, $sRemoteClass = '', $aAlreadyLinkedIds = array(), $oCurrentObj = null)
	{
		if ($sRemoteClass != '')
		{
			// assert(MetaModel::IsParentClass($this->m_sRemoteClass, $sRemoteClass));
			$oFilter = new DBObjectSearch($sRemoteClass);
		}
		else
		{
			// No remote class specified use the one defined in the linkedset
			$oFilter = new DBObjectSearch($this->m_sRemoteClass);		
		}
		if (!$this->m_bDuplicatesAllowed && count($aAlreadyLinkedIds) > 0)
		{
			$oFilter->AddCondition('id', $aAlreadyLinkedIds, 'NOTIN');
		}
		$this->SetSearchDefaultFromContext($oCurrentObj, $oFilter);
		$oBlock = new DisplayBlock($oFilter, 'list', false);
		$oBlock->Display($oP, "ResultsToAdd_{$this->m_sAttCode}", array('menu' => false, 'cssCount'=> '#count_'.$this->m_sAttCode.$this->m_sNameSuffix , 'selection_mode' => true, 'table_id' => 'add_'.$this->m_sAttCode)); // Don't display the 'Actions' menu on the results
	}

	/**
	 * @param WebPage $oP
	 * @param int $iMaxAddedId
	 * @param $oFullSetFilter
	 * @param DBObject $oCurrentObj
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function DoAddObjects(WebPage $oP, $iMaxAddedId, $oFullSetFilter, $oCurrentObj)
	{
		$aLinkedObjectIds = utils::ReadMultipleSelection($oFullSetFilter);

		$iAdditionId = $iMaxAddedId + 1;
		foreach ($aLinkedObjectIds as $iObjectId) {
			$oLinkedObj = MetaModel::GetObject($this->m_sRemoteClass, $iObjectId, false);
			if (is_object($oLinkedObj)) {
				$aRow = $this->GetFormRow($oP, $oLinkedObj, $iObjectId, array(), $oCurrentObj, $iAdditionId); // Not yet created link get negative Ids
				$oRow = new FormTableRow("{$this->m_sAttCode}{$this->m_sNameSuffix}", $this->m_aTableConfig, $aRow, -$iAdditionId);
				$oP->AddUiBlock($oRow);
				$iAdditionId++;
			} else {
				$oP->p(Dict::Format('UI:Error:Object_Class_Id_NotFound', $this->m_sLinkedClass, $iObjectId));
			}
		}
	}

	/**
	 * @param WebPage $oP
	 * @param int $iMaxAddedId
	 * @param $oFullSetFilter
	 * @param DBObject $oCurrentObj
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function DoAddIndirectLinks(JsonPage $oP, $iMaxAddedId, $oFullSetFilter, $oCurrentObj)
	{
		$aLinkedObjectIds = utils::ReadMultipleSelection($oFullSetFilter);

		$iAdditionId = $iMaxAddedId + 1;
		foreach ($aLinkedObjectIds as $iObjectId) {
			$oLinkedObj = MetaModel::GetObject($this->m_sRemoteClass, $iObjectId, false);
			if (is_object($oLinkedObj)) {
				$aRow = $this->GetFormRow($oP, $oLinkedObj, $iObjectId, array(), $oCurrentObj, $iAdditionId); // Not yet created link get negative Ids
				$aData = [];
				foreach ($aRow as $item) {
					$aData[] = $item;
				}
				$oP->AddData($aData);
				$iAdditionId++;
			} else {
				$oP->p(Dict::Format('UI:Error:Object_Class_Id_NotFound', $this->m_sLinkedClass, $iObjectId));
			}
		}
	}

	/**
	 * Initializes the default search parameters based on 1) a 'current' object and 2) the silos defined by the context
	 *
	 * @param DBObject $oSourceObj
	 * @param DBSearch|DBObjectSearch $oSearch
	 *
	 * @throws \CoreException
	 * @throws \Exception
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
					// Add Hierarchical condition if hierarchical key
					$oAttDef = MetaModel::GetAttributeDef($sDestClass, $sAttCode);
					if (isset($oAttDef) && ($oAttDef->IsExternalKey()))
					{
						try
						{
							/** @var AttributeExternalKey $oAttDef */
							$sTargetClass = $oAttDef->GetTargetClass();
							$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($sTargetClass);
							if ($sHierarchicalKeyCode !== false)
							{
								$oFilter = new DBObjectSearch($sTargetClass);
								$oFilter->AddCondition('id', $defaultValue);
								$oHKFilter = new DBObjectSearch($sTargetClass);
								$oHKFilter->AddCondition_PointingTo($oFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW);
								$oSearch->AddCondition_PointingTo($oHKFilter, $sAttCode);
							}
						} catch (Exception $e)
						{
						}
					}
					else
					{
						$oSearch->AddCondition($sAttCode, $defaultValue);
					}
				}
			}
		}
	}
}
