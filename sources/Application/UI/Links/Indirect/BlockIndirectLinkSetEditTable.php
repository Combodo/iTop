<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Indirect;

use AttributeLinkedSetIndirect;
use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use ConfigException;
use CoreException;
use DBObject;
use Dict;
use Exception;
use MetaModel;
use UILinksWidget;
use UserRights;
use utils;
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Class BlockIndirectLinkSetEditTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Indirect
 */
class BlockIndirectLinkSetEditTable extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE                   = 'ibo-block-indirect-linkset-edit-table';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/links/indirect/block-indirect-linkset-edit-table/layout';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'application/links/indirect/block-indirect-linkset-edit-table/layout';
	public const DEFAULT_JS_FILES_REL_PATH    = [
		'js/links/links_widget.js',
	];

	/** @var UILinksWidget $oUILinksWidget */
	public UILinksWidget $oUILinksWidget;

	/** @var AttributeLinkedSetIndirect $oAttributeLinkedSetIndirect */
	private AttributeLinkedSetIndirect $oAttributeLinkedSetIndirect;

	/** @var string */
	public string $sDuplicates;

	/** @var string containing a js object name */
	public string $sWizHelper;

	/** @var string */
	public string $sJSDoSearch;

	/** @var int */
	public int $iMaxAddedId = 0;

	/** @var array List of removed links id used by twig template */
	public array $aRemoved = [];

	/** @var string */
	public string $sFormPrefix;

	// User rights
	private bool $bIsAllowCreate;
	private bool $bIsAllowModify;
	private bool $bIsAllowDelete;

	/**
	 * Constructor.
	 *
	 * @param UILinksWidget $oUILinksWidget
	 *
	 * @throws ConfigException
	 * @throws CoreException
	 * @throws Exception
	 */
	public function __construct(UILinksWidget $oUILinksWidget)
	{
		parent::__construct("linkedset_{$oUILinksWidget->GetLinkedSetId()}", ["ibo-block-indirect-links--edit"]);

		// Retrieve parameters
		$this->oUILinksWidget = $oUILinksWidget;

		// Compute
		$this->sDuplicates = ($oUILinksWidget->IsDuplicatesAllowed()) ? 'true' : 'false';
		$this->sJSDoSearch = \utils::IsHighCardinality($oUILinksWidget->GetRemoteClass()) ? 'false' : 'true'; // Don't automatically launch the search if the table is huge

		// Initialization
		$this->Init();

		// Initialize UI
		$this->InitUI();
	}

	/**
	 * Initialization.
	 *
	 * @return void
	 * @throws Exception
	 */
	private function Init()
	{
		$this->oAttributeLinkedSetIndirect = MetaModel::GetAttributeDef($this->oUILinksWidget->GetClass(), $this->oUILinksWidget->GetAttCode());

		$sEditWhen = $this->oAttributeLinkedSetIndirect->GetEditWhen();
		$bIsEditableBasedOnEditWhen = ($sEditWhen === LINKSET_EDITWHEN_ALWAYS || $sEditWhen === LINKSET_EDITWHEN_ON_HOST_EDITION);

		// User rights
		$this->bIsAllowCreate = UserRights::IsActionAllowed($this->oAttributeLinkedSetIndirect->GetLinkedClass(), UR_ACTION_CREATE) == UR_ALLOWED_YES && $bIsEditableBasedOnEditWhen;
		$this->bIsAllowModify = UserRights::IsActionAllowed($this->oAttributeLinkedSetIndirect->GetLinkedClass(), UR_ACTION_MODIFY) == UR_ALLOWED_YES && $bIsEditableBasedOnEditWhen;
		$this->bIsAllowDelete = UserRights::IsActionAllowed($this->oAttributeLinkedSetIndirect->GetLinkedClass(), UR_ACTION_DELETE) == UR_ALLOWED_YES && $bIsEditableBasedOnEditWhen;
	}

	/**
	 * Initialize UI.
	 *
	 * @return void
	 * @throws CoreException
	 * @throws Exception
	 */
	private function InitUI()
	{
		// To prevent adding forms inside the main form
		$oDeferredBlock = new UIContentBlock("dlg_{$this->oUILinksWidget->GetLinkedSetId()}", ['ibo-block-indirect-links--edit--dialog']);
		$this->AddDeferredBlock($oDeferredBlock);
	}

	/**
	 * @param WebPage $oPage
	 * @param $oValue
	 * @param $aArgs
	 * @param $sFormPrefix
	 * @param $oCurrentObj
	 * @param $aTableConfig
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function InitTable(WebPage $oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj, $aTableConfig)
	{
		$this->sWizHelper = 'oWizardHelper'.$sFormPrefix;
		$oValue->Rewind();
		$bAllowRemoteExtKeyEdit = $oValue->Count() <= utils::GetConfig()->Get('link_set_max_edit_ext_key');
		$aForm = array();
		$iMaxAddedId = 0;
		$iAddedId = -1; // Unique id for new links
		$this->aRemoved = json_decode(\utils::ReadPostedParam("attr_{$sFormPrefix}{$this->oUILinksWidget->GetAttCode()}_tbd", '[]', 'raw_data'), true);
		$aModified = json_decode(\utils::ReadPostedParam("attr_{$sFormPrefix}{$this->oUILinksWidget->GetAttCode()}_tbm", '[]', 'raw_data'), true);
		while ($oCurrentLink = $oValue->Fetch()) {
			// We try to retrieve the remote object as usual
			$sCurrentLinkId = $oCurrentLink->GetKey();
			if ($oCurrentLink->IsNew()) {
				$key = $iAddedId--;
			} else {
				$key = $oCurrentLink->GetKey();
			}

			if (isset($aModified[$sCurrentLinkId])) {
				// Apply the modifications to the current link
				$aModifications = $aModified[$sCurrentLinkId];
				$sPrefix = 'attr_'.$aModifications['formPrefix'];
				foreach ($aModifications as $sName => $sValue) {
					if (!utils::StartsWith($sName, $sPrefix)) {
						continue;
					}
					$sAttCode = substr($sName, strlen($sPrefix));
					$oCurrentLink->Set($sAttCode, $sValue);
					$sEscapedValue = addslashes($sValue);
					$oPage->add_ready_script(<<<EOF
oWidget{$this->oUILinksWidget->GetInputId()}.OnValueChange($sCurrentLinkId, $iAddedId, "$sAttCode", "$sEscapedValue");
EOF
					);
				}
			}
			$oLinkedObj = MetaModel::GetObject($this->oUILinksWidget->GetRemoteClass(), $oCurrentLink->Get($this->oUILinksWidget->GetExternalKeyToRemote()), false /* Must not be found */);
			// If successful, it means that we can edit its link
			if ($oLinkedObj !== null) {
				$bReadOnly = false;
			} // Else we retrieve it without restrictions (silos) and will display its link as readonly
			else {
				$bReadOnly = true;
				$oLinkedObj = MetaModel::GetObject($this->oUILinksWidget->GetRemoteClass(), $oCurrentLink->Get($this->oUILinksWidget->GetExternalKeyToRemote()), false /* Must not be found */, true);
			}



			$iMaxAddedId = max($iMaxAddedId, $key);
			$aForm[$key] = $this->GetFormRow($oPage, $oLinkedObj, $oCurrentLink, $aArgs, $oCurrentObj, $key, $bReadOnly, $bAllowRemoteExtKeyEdit);
		}
		$this->iMaxAddedId = (int)$iMaxAddedId;

		// Datatable
		$aRowActions = $this->GetRowActions($oCurrentObj);
		$oDataTable = DataTableUIBlockFactory::MakeForForm("{$this->oUILinksWidget->GetAttCode()}{$this->oUILinksWidget->GetNameSuffix()}", $aTableConfig, $aForm, '', $aRowActions);
		$oDataTable->SetOptions([
			'select_mode'        => 'custom',
			'disable_hyperlinks' => true,
		]);

		// Panel
		$oTablePanel = PanelUIBlockFactory::MakeForClass($this->oUILinksWidget->GetRemoteClass(), $this->oAttributeLinkedSetIndirect->GetLabel())
			->SetSubTitle(Dict::Format('UI:Pagination:HeaderNoSelection', count($aForm)))
			->SetIcon(MetaModel::GetClassIcon($this->oUILinksWidget->GetRemoteClass(), false))
			->AddCSSClass('ibo-datatable-panel');

		// - Panel description
		$sDescription = $this->oAttributeLinkedSetIndirect->GetDescription();
		if (utils::IsNotNullOrEmptyString($sDescription)) {
			$oTitleBlock = $oTablePanel->GetTitleBlock()
				->AddDataAttribute('tooltip-content', $sDescription)
				->AddDataAttribute('tooltip-max-width', 'min(600px, 90vw)') // Allow big description to be wide enough while shrinking on small screens
				->AddCSSClass('ibo-has-description');
		}

		// Toolbar and actions
		$oToolbar = ToolbarUIBlockFactory::MakeForButton();

		if ($this->bIsAllowDelete) {
			$oActionButtonUnlink = ButtonUIBlockFactory::MakeNeutral(Dict::S('UI:Button:Remove'));
			$oActionButtonUnlink->SetOnClickJsCode("oWidget{$this->oUILinksWidget->GetInputId()}.RemoveSelected();")
				->AddDataAttribute('action', 'detach');
			$oToolbar->AddSubBlock($oActionButtonUnlink);
		}

		if ($this->bIsAllowCreate) {
			$oActionButtonLink = ButtonUIBlockFactory::MakeNeutral(Dict::S('UI:Button:Add'));
			$oActionButtonLink->SetTooltip(Dict::Format('UI:AddLinkedObjectsOf_Class', MetaModel::GetName($this->oAttributeLinkedSetIndirect->GetLinkedClass())))
				->SetOnClickJsCode("oWidget{$this->oUILinksWidget->GetInputId()}.AddObjects();")
				->AddDataAttribute('action', 'add');
			$oToolbar->AddSubBlock($oActionButtonLink);
		}

		$oTablePanel->AddToolbarBlock($oToolbar);
		$oTablePanel->AddSubBlock($oDataTable);

		$this->AddSubBlock($oTablePanel);
		$this->AddSubBlock(InputUIBlockFactory::MakeForHidden("{$sFormPrefix}{$this->oUILinksWidget->GetInputId()}", '', "{$sFormPrefix}{$this->oUILinksWidget->GetInputId()}"));
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
	 * @param bool $bReadOnly Display link as editable or read-only. Default is false (editable)
	 * @param bool $bAllowRemoteExtKeyEdit If true, the ext. key to the remote object can be edited, otherwise it will be read-only
	 *
	 * @return array The HTML fragment of the one-row form
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 * @since 3.1.0 3.0.4 3.0.3-1 N°6124 - Workaround performance problem on the modification of an object with an n:n relation having a large volume
	 */
	public function GetFormRow(WebPage $oP, DBObject $oLinkedObj, $linkObjOrId, $aArgs, $oCurrentObj, $iUniqueId, $bReadOnly = false, $bAllowRemoteExtKeyEdit = true)
	{
		$sPrefix = "{$this->oUILinksWidget->GetAttCode()}{$this->oUILinksWidget->GetNameSuffix()}";
		$aRow = array();
		$aFieldsMap = array();
		$iKey = 0;

		if (is_object($linkObjOrId) && (!$linkObjOrId->IsNew())) {
			$iKey = $linkObjOrId->GetKey();
			$iRemoteObjKey = $linkObjOrId->Get($this->oUILinksWidget->GetExternalKeyToRemote());
			$sPrefix .= "[$iKey][";
			$sNameSuffix = "]"; // To make a tabular form
			$aArgs['prefix'] = $sPrefix;
			$aArgs['wizHelper'] = "oWizardHelper{$this->oUILinksWidget->GetInputId()}{$iKey}";
			$aArgs['this'] = $linkObjOrId;

			if ($bReadOnly) {
				$aRow['form::checkbox'] = "";
				foreach ($this->oUILinksWidget->GetEditableFields() as $sFieldCode) {
					$sDisplayValue = $linkObjOrId->GetEditValue($sFieldCode);
					$aRow[$sFieldCode] = $sDisplayValue;
				}
			} else {
				$aRow['form::checkbox'] = "<input class=\"selection\" data-remote-id=\"$iRemoteObjKey\" data-link-id=\"$iKey\" data-unique-id=\"$iUniqueId\" type=\"checkbox\" onClick=\"oWidget{$this->oUILinksWidget->GetInputId()}.OnSelectChange();\" value=\"$iKey\">";
				foreach ($this->oUILinksWidget->GetEditableFields() as $sFieldCode) {
					// N°6124 - Force remote ext. key as read-only if too many items in the linkset
					$bReadOnlyField = ($sFieldCode === $this->oUILinksWidget->GetExternalKeyToRemote()) && (false === $bAllowRemoteExtKeyEdit);

					$sSafeFieldId = $this->GetFieldId($linkObjOrId->GetKey(), $sFieldCode);
					$this->AddRowForFieldCode($aRow, $sFieldCode, $aArgs, $linkObjOrId, $oP, $sNameSuffix, $sSafeFieldId, $bReadOnlyField);
					$aFieldsMap[$sFieldCode] = $sSafeFieldId;
				}
			}

			$sState = $linkObjOrId->GetState();
			$sRemoteKeySafeFieldId = $this->GetFieldId($aArgs['this']->GetKey(), $this->oUILinksWidget->GetExternalKeyToRemote());;
		} else {
			// form for creating a new record
			if (is_object($linkObjOrId)) {
				// New link existing only in memory
				$oNewLinkObj = $linkObjOrId;
				$iRemoteObjKey = $oNewLinkObj->Get($this->oUILinksWidget->GetExternalKeyToRemote());
				$oNewLinkObj->Set($this->oUILinksWidget->GetExternalKeyToMe(),
					$oCurrentObj); // Setting the extkey with the object also fills the related external fields
			} else {
				$iRemoteObjKey = $linkObjOrId;
				$oNewLinkObj = MetaModel::NewObject($this->oUILinksWidget->GetLinkedClass());
				$oRemoteObj = MetaModel::GetObject($this->oUILinksWidget->GetRemoteClass(), $iRemoteObjKey);
				$oNewLinkObj->Set($this->oUILinksWidget->GetExternalKeyToRemote(),
					$oRemoteObj); // Setting the extkey with the object alsoo fills the related external fields
				$oNewLinkObj->Set($this->oUILinksWidget->GetExternalKeyToMe(),
					$oCurrentObj); // Setting the extkey with the object also fills the related external fields
			}
			$sPrefix .= "[-$iUniqueId][";
			$sNameSuffix = "]"; // To make a tabular form
			$aArgs['prefix'] = $sPrefix;
			$aArgs['wizHelper'] = "oWizardHelper{$this->oUILinksWidget->GetInputId()}_".($iUniqueId < 0 ? -$iUniqueId : $iUniqueId);
			$aArgs['this'] = $oNewLinkObj;
			$sInputValue = $iUniqueId > 0 ? "-$iUniqueId" : "$iUniqueId";
			$aRow['form::checkbox'] = "<input class=\"selection\" data-remote-id=\"$iRemoteObjKey\" data-link-id=\"0\" data-unique-id=\"$iUniqueId\" type=\"checkbox\" onClick=\"oWidget{$this->oUILinksWidget->GetInputId()}.OnSelectChange();\" value=\"$sInputValue\">";

			if ($iUniqueId > 0) {
				// Rows created with ajax call need OnLinkAdded call.
				//
					$oP->add_ready_script(
						<<<EOF
PrepareWidgets();
oWidget{$this->oUILinksWidget->GetInputId()}.OnLinkAdded($iUniqueId, $iRemoteObjKey);
EOF
					);
			} else {
				// Rows added before loading the form don't have to call OnLinkAdded.
				// Listeners are already present and DOM is not recreated
				$iPositiveUniqueId = -$iUniqueId;
					$oP->add_ready_script(<<<EOF
oWidget{$this->oUILinksWidget->GetInputId()}.AddLink($iPositiveUniqueId, $iRemoteObjKey);
EOF
					);
			}

			foreach ($this->oUILinksWidget->GetEditableFields() as $sFieldCode) {
				// N°6124 - Force remote ext. key as read-only if too many items in the linkset
				$bReadOnlyField = ($sFieldCode === $this->oUILinksWidget->GetExternalKeyToRemote()) && (false === $bAllowRemoteExtKeyEdit);

				$sSafeFieldId = $this->GetFieldId($iUniqueId, $sFieldCode);
				$this->AddRowForFieldCode($aRow, $sFieldCode, $aArgs, $oNewLinkObj, $oP, $sNameSuffix, $sSafeFieldId, $bReadOnlyField);
				$aFieldsMap[$sFieldCode] = $sSafeFieldId;

				$sValue = $oNewLinkObj->Get($sFieldCode);
					$oP->add_ready_script(
						<<<JS
oWidget{$this->oUILinksWidget->GetInputId()}.OnValueChange($iKey, $iUniqueId, '$sFieldCode', '$sValue');
JS
					);
			}

			$sState = '';
			$sRemoteKeySafeFieldId = $this->GetFieldId($iUniqueId, $this->oUILinksWidget->GetExternalKeyToRemote());
		}

		if (!$bReadOnly) {
			$sExtKeyToMeId = \utils::GetSafeId($sPrefix.$this->oUILinksWidget->GetExternalKeyToMe());
			$aFieldsMap[$this->oUILinksWidget->GetExternalKeyToMe()] = $sExtKeyToMeId;
			$aRow['form::checkbox'] .= "<input type=\"hidden\" id=\"$sExtKeyToMeId\" value=\"".$oCurrentObj->GetKey()."\">";

			$sExtKeyToRemoteId = \utils::GetSafeId($sPrefix.$this->oUILinksWidget->GetExternalKeyToRemote());
			$aFieldsMap[$this->oUILinksWidget->GetExternalKeyToRemote()] = $sExtKeyToRemoteId;
			$aRow['form::checkbox'] .= "<input type=\"hidden\" id=\"$sExtKeyToRemoteId\" value=\"$iRemoteObjKey\">";
		}

		// Adding fields from remote class
		// all fields are embedded in a span + added to $aFieldsMap array so that we can refresh them after extkey change
		$aRemoteFieldsMap = [];
		foreach (MetaModel::GetZListItems($this->oUILinksWidget->GetRemoteClass(), 'list') as $sFieldCode) {
			$sSafeFieldId = $this->GetFieldId($aArgs['this']->GetKey(), $sFieldCode);
			$aRow['static::'.$sFieldCode] = "<span id='field_$sSafeFieldId'>".$oLinkedObj->GetAsHTML($sFieldCode).'</span>';
			$aRemoteFieldsMap[$sFieldCode] = $sSafeFieldId;
		}
		// id field is needed so that remote object could be load server side
		$aRemoteFieldsMap['id'] = $sRemoteKeySafeFieldId;

		// Generate WizardHelper to update dependant fields
		$this->AddWizardHelperInit($oP, $aArgs['wizHelper'], $this->oUILinksWidget->GetLinkedClass(), $sState, $aFieldsMap);
		//instantiate specific WizarHelper instance for remote class fields refresh
		$bHasExtKeyUpdatingRemoteClassFields = (
			array_key_exists('replaceDependenciesByRemoteClassFields', $aArgs)
			&& ($aArgs['replaceDependenciesByRemoteClassFields'])
		);
		if ($bHasExtKeyUpdatingRemoteClassFields) {
			$this->AddWizardHelperInit($oP, $aArgs['wizHelperRemote'], $this->oUILinksWidget->GetRemoteClass(), $sState, $aRemoteFieldsMap);
		}

		return $aRow;
	}

	/**
	 * @param $aRow
	 * @param $sFieldCode
	 * @param $aArgs
	 * @param $oLnk
	 * @param $oP
	 * @param $sNameSuffix
	 * @param $sSafeFieldId
	 * @param bool $bReadOnlyField If true, the field will be read-only, otherwise it can be edited
	 *
	 * @return void
	 * @since 3.1.0 3.0.4 3.0.3-1 N°6124 - Workaround performance problem on the modification of an object with an n:n relation having a large volume
	 */
	private function AddRowForFieldCode(&$aRow, $sFieldCode, &$aArgs, $oLnk, $oP, $sNameSuffix, $sSafeFieldId, $bReadOnlyField = false): void
	{
		if (($sFieldCode === $this->oUILinksWidget->GetExternalKeyToRemote())) {
			// Current field is the lnk extkey to the remote class
			$aArgs['replaceDependenciesByRemoteClassFields'] = true;
			$sRowFieldCode = 'static::key';
			$aArgs['wizHelperRemote'] = $aArgs['wizHelper'].'_remote';
			$aRemoteAttDefs = MetaModel::GetZListAttDefsFilteredForIndirectRemoteClass($this->oUILinksWidget->GetRemoteClass());
			$aRemoteCodes = array_map(
				function ($value) {
					return $value->GetCode();
				},
				$aRemoteAttDefs
			);
			$aArgs['remoteCodes'] = $aRemoteCodes;
		} else {
			$aArgs['replaceDependenciesByRemoteClassFields'] = false;
			$sRowFieldCode = $sFieldCode;
		}
		$sValue = $oLnk->Get($sFieldCode);
		$sDisplayValue = $oLnk->GetEditValue($sFieldCode);
		$oAttDef = MetaModel::GetAttributeDef($this->oUILinksWidget->GetLinkedClass(), $sFieldCode);

		if ($bReadOnlyField) {
			$sFieldForHtml = $oAttDef->GetAsHTML($sValue);
		} else {
			$sFieldForHtml = cmdbAbstractObject::GetFormElementForField(
					$oP,
					$this->oUILinksWidget->GetLinkedClass(),
					$sFieldCode,
					$oAttDef,
					$sValue,
					$sDisplayValue,
					$sSafeFieldId,
					$sNameSuffix,
					0,
					$aArgs
				);
		}

		$aRow[$sRowFieldCode] = <<<HTML
<div class="field_container" style="border:none;">
	<div class="field_data">
		<div class="field_value">$sFieldForHtml</div>
	</div>
</div>
HTML
		;
	}

	private function GetFieldId($iLnkId, $sFieldCode, $bSafe = true)
	{
		$sFieldId = $this->oUILinksWidget->GetInputId().'_'.$sFieldCode.'['.$iLnkId.']';

		return ($bSafe) ? \utils::GetSafeId($sFieldId) : $sFieldId;
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
	 * Return row actions.
	 *
	 * @param \DBObject $oHostObject
	 *
	 * @return \string[][]
	 */
	private function GetRowActions(DBObject $oHostObject): array
	{
		$aRowActions = array();

		$sRemoveButtonTooltip = $this->oAttributeLinkedSetIndirect->SearchSpecificLabel('UI:Links:Remove:Button+', '', true,
			MetaModel::GetName($this->oAttributeLinkedSetIndirect->GetHostClass()),
			$oHostObject->Get('friendlyname'),
			$this->oAttributeLinkedSetIndirect->GetLabel(),
			MetaModel::GetName($this->oUILinksWidget->GetRemoteClass()));

		if ($this->bIsAllowDelete) {
			$aRowActions[] = array(
				'label'         => 'UI:Links:Remove:Button',
				'tooltip'       => $sRemoveButtonTooltip,
				'icon_classes'  => 'fas fa-minus',
				'js_row_action' => "oWidget{$this->oUILinksWidget->GetInputId()}.Remove(oTrElement);",
			);
		}

		return $aRowActions;
	}

}