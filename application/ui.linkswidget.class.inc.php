<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\StaticTable\FormTableRow\FormTableRow;
use Combodo\iTop\Application\UI\Links\Indirect\BlockIndirectLinkSetEditTable;
use Combodo\iTop\Application\UI\Links\Indirect\BlockObjectPickerDialog;
use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;

require_once(APPROOT.'application/displayblock.class.inc.php');

class UILinksWidget
{
	protected $m_sClass;
	protected $m_sClassLabel;
	protected $m_sAttCode;
	protected $m_sNameSuffix;
	protected $m_sInputId;
	protected $m_aAttributes;
	protected $m_sExtKeyToRemote;
	protected $m_sExtKeyToMe;
	protected $m_sLinkedClass;
	protected $m_sLinkedClassLabel;
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
	 * @param string $sInputId
	 * @param string $sNameSuffix
	 * @param bool $bDuplicatesAllowed
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public function __construct($sClass, $sAttCode, $sInputId, $sNameSuffix = '', $bDuplicatesAllowed = false)
	{
		$this->m_sClass = $sClass;
		$this->m_sClassLabel = MetaModel::GetName($this->m_sClass);
		$this->m_sAttCode = $sAttCode;
		$this->m_sInputId = $sInputId;
		$this->m_sNameSuffix = $sNameSuffix;
		$this->m_bDuplicatesAllowed = $bDuplicatesAllowed;

		$this->m_aEditableFields = array();

		/** @var AttributeLinkedSetIndirect $oAttDef */
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sAttCode);
		$this->m_sLinkedClass = $oAttDef->GetLinkedClass();
		$this->m_sLinkedClassLabel = MetaModel::GetName($this->m_sLinkedClass);
		$this->m_sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
		$this->m_sExtKeyToMe = $oAttDef->GetExtKeyToMe();

		/** @var AttributeExternalKey $oLinkingAttDef */
		$oLinkingAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $this->m_sExtKeyToRemote);
		$this->m_sRemoteClass = $oLinkingAttDef->GetTargetClass();

		$this->m_aEditableFields = array();
		$this->m_aTableConfig = array();
		$this->m_aTableConfig['form::checkbox'] = array(
			'label'       => "<input class=\"select_all\" type=\"checkbox\" value=\"1\" onClick=\"CheckAll('#linkedset_{$this->m_sAttCode}{$this->m_sNameSuffix} .selection', this.checked); oWidget".$this->m_sInputId.".OnSelectChange();\">",
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

	private function GetFieldId($iLnkId, $sFieldCode, $bSafe = true)
	{
		$sFieldId = $this->m_sInputId.'_'.$sFieldCode.'['.$iLnkId.']';

		return ($bSafe) ? utils::GetSafeId($sFieldId) : $sFieldId;
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
		$oBlock = new BlockIndirectLinkSetEditTable($this);
		$oBlock->InitTable($oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj, $this->m_aTableConfig);

		return ConsoleBlockRenderer::RenderBlockTemplateInPage($oPage, $oBlock);
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

		$oBlock = new BlockObjectPickerDialog($this);
		$oPage->AddUiBlock($oBlock);

		$sLinkedSetId = $oBlock->oUILinksWidget->GetLinkedSetId();

		$oDisplayBlock = new DisplayBlock($oFilter, 'search', false);
		$oBlock->AddSubBlock($oDisplayBlock->GetDisplay($oPage, "SearchFormToAdd_{$sLinkedSetId}",
			[
				'menu'                       => false,
				'result_list_outer_selector' => "SearchResultsToAdd_{$sLinkedSetId}",
				'table_id'                   => "add_{$sLinkedSetId}",
				'table_inner_id'             => "ResultsToAdd_{$sLinkedSetId}",
				'selection_mode'             => true,
				'json'                       => $sJson,
				'cssCount'                   => '#count_'.$this->m_sAttCode.$this->m_sNameSuffix,
				'query_params'               => $oFilter->GetInternalParams(),
				'hidden_criteria'            => $sAlreadyLinkedExpression,
				'submit_on_load'             => false,
			]));

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
				$oBlock = new BlockIndirectLinkSetEditTable($this);
				$aRow = $oBlock->GetFormRow($oP, $oLinkedObj, $iObjectId, array(), $oCurrentObj, $iAdditionId); // Not yet created link get negative Ids
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
		$bAllowRemoteExtKeyEdit = count($aLinkedObjectIds) <= utils::GetConfig()->Get('link_set_max_edit_ext_key');
		foreach ($aLinkedObjectIds as $iObjectId) {
			$oLinkedObj = MetaModel::GetObject($this->m_sRemoteClass, $iObjectId, false);
			if (is_object($oLinkedObj)) {
				$oBlock = new BlockIndirectLinkSetEditTable($this);
				$aRow = $oBlock->GetFormRow($oP, $oLinkedObj, $iObjectId, array(), $oCurrentObj, $iAdditionId, false /* Default value */, $bAllowRemoteExtKeyEdit); // Not yet created link get negative Ids
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
							if ($sHierarchicalKeyCode !== false) {
								$oFilter = new DBObjectSearch($sTargetClass);
								$oFilter->AddCondition('id', $defaultValue);
								$oHKFilter = new DBObjectSearch($sTargetClass);
								$oHKFilter->AddCondition_PointingTo($oFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW);
								$oSearch->AddCondition_PointingTo($oHKFilter, $sAttCode);
							}
						}
						catch (Exception $e) {
						}
					} else {
						$oSearch->AddCondition($sAttCode, $defaultValue);
					}
				}
			}
		}
	}

	public function GetLinkedSetId(): string
	{
		return "{$this->m_sAttCode}{$this->m_sNameSuffix}";
	}

	public function GetClass(): string
	{
		return $this->m_sClass;
	}

	public function GetClassLabel(): string
	{
		return $this->m_sClassLabel;
	}

	public function GetLinkedClass(): string
	{
		return $this->m_sLinkedClass;
	}

	public function GetLinkedClassLabel(): string
	{
		return $this->m_sLinkedClassLabel;
	}

	public function GetAttCode(): string
	{
		return $this->m_sAttCode;
	}

	public function GetInputId(): string
	{
		return $this->m_sInputId;
	}

	public function GetNameSuffix(): string
	{
		return $this->m_sNameSuffix;
	}

	public function IsDuplicatesAllowed(): bool
	{
		return $this->m_bDuplicatesAllowed;
	}

	public function GetExternalKeyToRemote(): string
	{
		return $this->m_sExtKeyToRemote;
	}

	public function GetExternalKeyToMe(): string
	{
		return $this->m_sExtKeyToMe;
	}

	public function GetRemoteClass(): string
	{
		return $this->m_sRemoteClass;
	}

	public function GetEditableFields(): array
	{
		return $this->m_aEditableFields;
	}

}
