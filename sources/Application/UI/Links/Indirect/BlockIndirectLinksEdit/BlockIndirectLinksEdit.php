<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Indirect\BlockIndirectLinksEdit;


use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use MetaModel;

/**
 * Class BlockIndirectLinksEdit
 *
 * @package Combodo\iTop\Application\UI\Links\Indirect\BlockIndirectLinksEdit
 */
class BlockIndirectLinksEdit extends Panel
{
	// Overloaded constants
	public const BLOCK_CODE                   = 'ibo-block-indirect-links-edit';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/links/indirect/block-indirect-links-edit/layout';

	/** @var \UILinksWidget */
	public \UILinksWidget $oUILinksWidget;

	/** @var string */
	public string $sDuplicates;

	/** @var string containing a js object name */
	public string $sWizHelper;

	/** @var string */
	public string $sJSDoSearch;

	/** @var int */
	public int $iMaxAddedId = 0;

	/** @var array */
	public array $aRemoved = [];

	/** @var string */
	public string $sFormPrefix;

	/**
	 * Constructor.
	 *
	 * @param \UILinksWidget $oUILinksWidget
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function __construct(\UILinksWidget $oUILinksWidget)
	{
		parent::__construct($oUILinksWidget->GetRemoteClass(), [], Self::DEFAULT_COLOR_SCHEME, "linkedset_{$oUILinksWidget->GetLinkedSetId()}");

		// Retrieve parameters
		$this->oUILinksWidget = $oUILinksWidget;

		// Compute
		$this->sDuplicates = ($oUILinksWidget->IsDuplicatesAllowed()) ? 'true' : 'false';
		$this->sJSDoSearch = \utils::IsHighCardinality($oUILinksWidget->GetRemoteClass()) ? 'false' : 'true'; // Don't automatically launch the search if the table is huge

		// Initialize UI
		$this->InitUI();
	}

	/**
	 * Initialize UI.
	 *
	 * @return void
	 * @throws \CoreException
	 */
	private function InitUI()
	{
		// Panel
		$this->SetCSSClasses(["ibo-block-indirect-links--edit"]);
		$this->SetSubTitle(MetaModel::GetAttributeDef($this->oUILinksWidget->GetClass(), $this->oUILinksWidget->GetAttCode())->GetDescription());
		$this->SetColorFromClass($this->oUILinksWidget->GetRemoteClass());
		$this->SetIcon(MetaModel::GetClassIcon($this->oUILinksWidget->GetRemoteClass(), false));

		// table information alert
		$this->AddSubBlock($this->CreateTableInformationAlert());

		// Toolbar
//		$this->InitToolBar();

		// To prevent adding forms inside the main form
		$oDeferredBlock = new UIContentBlock("dlg_{$this->oUILinksWidget->GetLinkedSetId()}", ['ibo-block-indirect-links--edit--dialog']);
		$this->AddDeferredBlock($oDeferredBlock);
	}

	/**
	 * InitToolBar.
	 *
	 * @return void
	 */
	private function InitToolBar()
	{
		// Add button
		$oAddButton = ButtonUIBlockFactory::MakeNeutral("Add {$this->oUILinksWidget->GetRemoteClass()}", 'create-link');
		$oAddButton->SetOnClickJsCode("oWidget{$this->oUILinksWidget->GetInputId()}.AddObjects();");
		$this->AddToolbarBlock($oAddButton);
	}

	/**
	 * CreateTableInformationAlert.
	 *
	 * @return void
	 */
	private function CreateTableInformationAlert()
	{
		// Selection alert
		$oAlert = AlertUIBlockFactory::MakeNeutral('', '', "linkedset_{$this->oUILinksWidget->GetInputId()}_alert_information");
		$oAlert->AddCSSClasses([
			'ibo-table--alert-information',
		]);
		$oAlert->SetIsClosable(false);
		$oAlert->SetIsCollapsible(false);
		$oAlert->AddSubBlock(new Html('<span class="ibo-table--alert-information--label" data-role="ibo-datatable-selection-value"></span>'));

		// Delete button
		$oUIButton = ButtonUIBlockFactory::MakeForDestructiveAction("Détacher les {$this->oUILinksWidget->GetRemoteClass()}", 'table-selection');
		$oUIButton->SetOnClickJsCode("oWidget{$this->oUILinksWidget->GetInputId()}.RemoveSelected();");
		$oUIButton->AddCSSClass('ibo-table--alert-information--delete-button');
		$oAlert->AddSubBlock($oUIButton);

		// Add button
		$oUIAddButton = ButtonUIBlockFactory::MakeForPrimaryAction("Attacher des {$this->oUILinksWidget->GetRemoteClass()}", 'table-selection');
		$oUIAddButton->AddCSSClass('ibo-table--alert-information--add-button');
		$oUIAddButton->SetOnClickJsCode("oWidget{$this->oUILinksWidget->GetInputId()}.AddObjects();");
		$oAlert->AddSubBlock($oUIAddButton);


		//	$oAlert = new DataTableSelectionPanel('dd', $this->oUILinksWidget, 'contact');

		return $oAlert;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Links\Indirect\BlockIndirectLinksEdit\WebPage $oPage
	 * @param $oValue
	 * @param $aArgs
	 * @param $sFormPrefix
	 * @param $oCurrentObj
	 * @param $aTableConfig
	 *
	 * @return void
	 */
	public function InitTable(\WebPage $oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj, $aTableConfig)
	{
		$this->AddSubBlock(InputUIBlockFactory::MakeForHidden("{$sFormPrefix}{$this->oUILinksWidget->GetInputId()}", '', "{$sFormPrefix}{$this->oUILinksWidget->GetInputId()}"));
		$this->sWizHelper = 'oWizardHelper'.$sFormPrefix;
		$oValue->Rewind();
		$aForm = array();
		$iMaxAddedId = 0;
		$iAddedId = -1; // Unique id for new links
		$this->aRemoved = json_decode(\utils::ReadPostedParam("attr_{$sFormPrefix}{$this->oUILinksWidget->GetAttCode()}_tbd", '[]', 'raw_data'));
		while ($oCurrentLink = $oValue->Fetch()) {
			// We try to retrieve the remote object as usual
			if (!in_array($oCurrentLink->GetKey(), $this->aRemoved)) {
				$oLinkedObj = MetaModel::GetObject($this->oUILinksWidget->GetRemoteClass(), $oCurrentLink->Get($this->oUILinksWidget->GetExternalKeyToRemote()), false /* Must not be found */);
				// If successful, it means that we can edit its link
				if ($oLinkedObj !== null) {
					$bReadOnly = false;
				} // Else we retrieve it without restrictions (silos) and will display its link as readonly
				else {
					$bReadOnly = true;
					$oLinkedObj = MetaModel::GetObject($this->oUILinksWidget->GetRemoteClass(), $oCurrentLink->Get($this->oUILinksWidget->GetExternalKeyToRemote()), false /* Must not be found */, true);
				}

				if ($oCurrentLink->IsNew()) {
					$key = $iAddedId--;
				} else {
					$key = $oCurrentLink->GetKey();
				}

				$iMaxAddedId = max($iMaxAddedId, $key);
				$aForm[$key] = $this->GetFormRow($oPage, $oLinkedObj, $oCurrentLink, $aArgs, $oCurrentObj, $key, $bReadOnly);
			}
		}
		$this->iMaxAddedId = (int)$iMaxAddedId;

		// Row actions
		$aRow_actions = [
			[
				'tooltip'       => 'Edit',
				'icon_classes'  => 'fas fa-edit',
				'js_row_action' => "alert('edit link');",
			],
			[
				'tooltip'       => 'Unlink',
				'icon_classes'  => 'fas fa-unlink',
				'js_row_action' => "oWidget{$this->oUILinksWidget->GetInputId()}.Remove(oTrElement);",
			],
		];

		// Datatable
		$oDataTable = DataTableUIBlockFactory::MakeForForm("{$this->oUILinksWidget->GetAttCode()}{$this->oUILinksWidget->GetNameSuffix()}", $aTableConfig, $aForm, '', $aRow_actions);
		$oDataTable->SetOptions([
			'select_mode'        => 'custom',
			'disable_hyperlinks' => true,
		]);
		$this->AddSubBlock($oDataTable);
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
	public function GetFormRow(\WebPage $oP, \DBObject $oLinkedObj, $linkObjOrId, $aArgs, $oCurrentObj, $iUniqueId, $bReadOnly = false)
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
				foreach ($this->m_aEditableFields as $sFieldCode) {
					$sDisplayValue = $linkObjOrId->GetEditValue($sFieldCode);
					$aRow[$sFieldCode] = $sDisplayValue;
				}
			} else {
				$aRow['form::checkbox'] = "<input class=\"selection\" data-remote-id=\"$iRemoteObjKey\" data-link-id=\"$iKey\" data-unique-id=\"$iUniqueId\" type=\"checkbox\" onClick=\"oWidget{$this->oUILinksWidget->GetInputId()}.OnSelectChange();\" value=\"$iKey\">";
				foreach ($this->oUILinksWidget->GetEditableFields() as $sFieldCode) {
					$sSafeFieldId = $this->GetFieldId($linkObjOrId->GetKey(), $sFieldCode);
					$this->AddRowForFieldCode($aRow, $sFieldCode, $aArgs, $linkObjOrId, $oP, $sNameSuffix, $sSafeFieldId);
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
				$sSafeFieldId = $this->GetFieldId($iUniqueId, $sFieldCode);
				$this->AddRowForFieldCode($aRow, $sFieldCode, $aArgs, $oNewLinkObj, $oP, $sNameSuffix, $sSafeFieldId);
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

	private function AddRowForFieldCode(&$aRow, $sFieldCode, &$aArgs, $oLnk, $oP, $sNameSuffix, $sSafeFieldId): void
	{
		if (($sFieldCode === $this->oUILinksWidget->GetExternalKeyToRemote())) {
			// current field is the lnk extkey to the remote class
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

		$aRow[$sRowFieldCode] = '<div class="field_container" style="border:none;"><div class="field_data"><div class="field_value">'
			.\cmdbAbstractObject::GetFormElementForField(
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
			)
			.'</div></div></div>';
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

}