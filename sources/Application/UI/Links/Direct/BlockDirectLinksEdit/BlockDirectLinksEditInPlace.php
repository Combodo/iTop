<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Indirect\BlockDirectLinksEdit;


use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Dict;
use MetaModel;

/**
 * Class BlockDirectLinksEditInPlace
 *
 * @package Combodo\iTop\Application\UI\Links\Direct\BlockDirectLinksEdit
 */
class BlockDirectLinksEditInPlace extends Panel
{
	// Overloaded constants
	public const BLOCK_CODE                   = 'ibo-block-direct-links-edit-in-place';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/links/direct/block-direct-links-edit/layout';

	// types constants
	public const TYPE_ACTION_NONE          = 'ACTION_NONE';
	public const TYPE_ACTION_ADD           = 'ACTION_ADD';
	public const TYPE_ACTION_ADD_REMOVE    = 'ACTION_ADD_REMOVE';
	public const TYPE_ACTION_CREATE_DELETE = 'ACTION_CREATE_DELETE';

	/** @var \UILinksWidgetDirect */
	public \UILinksWidgetDirect $oUILinksDirectWidget;

	/** @var string */
	public string $sType;

	/** @var string */
	public string $sInputName;

	/** @var array */
	public array $aLabels;

	/** @var string */
	public string $sSubmitUrl;

	/** @var string */
	public string $sWizHelper;

	/** @var string */
	public string $sJSDoSearch;

	/**
	 * Constructor.
	 *
	 * @param \UILinksWidgetDirect $oUILinksDirectWidget
	 * @param string $sType
	 * @param string $sId
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function __construct(\UILinksWidgetDirect $oUILinksDirectWidget, string $sType, string $sId)
	{
		parent::__construct($oUILinksDirectWidget->GetLinkedClass(), [], self::DEFAULT_COLOR_SCHEME, $sId);

		// Retrieve parameters
		$this->oUILinksDirectWidget = $oUILinksDirectWidget;
		$this->sType = $sType;

		// compute
		$this->aLabels = array(
			'delete'          => Dict::S('UI:Button:Delete'),
			'creation_title'  => Dict::Format('UI:CreationTitle_Class', MetaModel::GetName($this->oUILinksDirectWidget->GetLinkedClass())),
			'create'          => Dict::Format('UI:ClickToCreateNew', MetaModel::GetName($this->oUILinksDirectWidget->GetLinkedClass())),
			'remove'          => Dict::S('UI:Button:Remove'),
			'add'             => Dict::Format('UI:AddAnExisting_Class', MetaModel::GetName($this->oUILinksDirectWidget->GetLinkedClass())),
			'selection_title' => Dict::Format('UI:SelectionOf_Class', MetaModel::GetName($this->oUILinksDirectWidget->GetLinkedClass())),
		);
		$oContext = new \ApplicationContext();
		$this->sSubmitUrl = \utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?'.$oContext->GetForLink();

		// Don't automatically launch the search if the table is huge
		$bDoSearch = !\utils::IsHighCardinality($this->oUILinksDirectWidget->GetLinkedClass());
		$this->sJSDoSearch = $bDoSearch ? 'true' : 'false';

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
		$this->SetCSSClasses(["ibo-block-direct-links--edit-in-place"]);
		try {
			$this->SetSubTitle(MetaModel::GetAttributeDef($this->oUILinksDirectWidget->GetClass(), $this->oUILinksDirectWidget->GetAttCode())->GetDescription());
		}
		catch (\Exception $e) {
			$this->SetSubTitle('Error Direct Links Edit in Place attribute definition error.');
		}
		$this->SetColorFromClass($this->oUILinksDirectWidget->GetLinkedClass());
		$this->SetIcon(MetaModel::GetClassIcon($this->oUILinksDirectWidget->GetLinkedClass(), false));

		// table information alert
		$this->AddSubBlock($this->CreateTableInformationAlert());
	}

	/**
	 * CreateTableInformationAlert.
	 *
	 * @return iUIBlock
	 */
	private function CreateTableInformationAlert(): iUIBlock
	{
		// Selection alert
		$oAlert = AlertUIBlockFactory::MakeNeutral('', '', "linkedset_{$this->oUILinksDirectWidget->GetInputId()}_alert_information");
		$oAlert->AddCSSClasses([
			'ibo-table--alert-information',
		]);
		$oAlert->SetIsClosable(false);
		$oAlert->SetIsCollapsible(false);
		$oAlert->AddSubBlock(new Html('<span class="ibo-table--alert-information--label" data-role="ibo-datatable-selection-value"></span>'));

		// Delete button
		$oUIButton = ButtonUIBlockFactory::MakeForDestructiveAction("Détacher les {$this->oUILinksDirectWidget->GetLinkedClass()}", 'table-selection');
		$oUIButton->AddCSSClass('ibo-table--alert-information--delete-button');
		$oUIButton->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('instance')._deleteSelection();");
		$oAlert->AddSubBlock($oUIButton);

		// Add button
		$oUIAddButton = ButtonUIBlockFactory::MakeForPrimaryAction("Attacher des {$this->oUILinksDirectWidget->GetLinkedClass()}", 'table-selection');
		$oUIAddButton->AddCSSClass('ibo-table--alert-information--add-button');
		$oUIAddButton->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('instance')._selectToAdd();");
		$oAlert->AddSubBlock($oUIAddButton);

		return $oAlert;
	}

	/**
	 * @param \WebPage $oPage
	 * @param \DBObjectSet $oValue
	 * @param string $sFormPrefix
	 *
	 * @return void
	 */
	public function InitTable(\WebPage $oPage, \DBObjectSet $oValue, string $sFormPrefix)
	{
		/** @todo fields initialization */
		$this->sInputName = $sFormPrefix.'attr_'.$this->oUILinksDirectWidget->GetAttCode();
		$this->sWizHelper = 'oWizardHelper'.$sFormPrefix;

		try {
			$aAttribs = $this->oUILinksDirectWidget->GetTableConfig();
			$aRows = $this->GetTableRows($oPage, $oValue);
			$aRowActions = $this->GetRowActions();
			$oDatatable = DataTableUIBlockFactory::MakeForForm($this->oUILinksDirectWidget->GetInputId(), $aAttribs, $aRows, '', $aRowActions);
			$oDatatable->SetOptions(['select_mode' => 'custom', 'disable_hyperlinks' => true]);
			$this->AddSubBlock($oDatatable);
		}
		catch (\Exception $e) {
			$this->AddSubBlock(PanelUIBlockFactory::MakeForDanger('error', 'error while trying to load datatable'));
		}
	}

	/**
	 * Return table rows.
	 *
	 * @param \DBObjectSet $oValue
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	private function GetTableRows(\WebPage $oPage, \DBObjectSet $oValue): array
	{
		// result data
		$aRows = array();

		// set pointer to start
		$oValue->Rewind();

		// create a row table for each value...
		while ($oLinkObj = $oValue->Fetch()) {
			$aRow = array();
			$aRow['form::select'] = '<input type="checkbox" class="selectList'.$this->oUILinksDirectWidget->GetInputId().'" onClick="oWidget'.$this->oUILinksDirectWidget->GetInputId().'.directlinks(\'instance\')._onSelectChange();" value="'.$oLinkObj->GetKey().'"/>';
			foreach ($this->oUILinksDirectWidget->GetZList() as $sLinkedAttCode) {
				$aRow[$sLinkedAttCode] = $oLinkObj->GetAsHTML($sLinkedAttCode);

//				$sValue = $oLinkObj->Get($sLinkedAttCode);
//				$sDisplayValue = $oLinkObj->GetEditValue($sLinkedAttCode);
//				$oAttDef = MetaModel::GetAttributeDef($this->oUILinksDirectWidget->GetLinkedClass(), $sLinkedAttCode);
//
//				$aRow[$sLinkedAttCode] = '<div class="field_container" style="border:none;"><div class="field_data"><div class="field_value">'
//					.\cmdbAbstractObject::GetFormElementForField(
//						$oPage,
//						$this->oUILinksDirectWidget->GetLinkedClass(),
//						$sLinkedAttCode,
//						$oAttDef,
//						$sValue,
//						$sDisplayValue,
//						$this->GetFieldId($oValue, $sLinkedAttCode),
//						']',
//						0,
//						[]
//					)
//					.'</div></div></div>';
			}
			$aRows[] = $aRow;
		}

		return $aRows;
	}

	private function GetFieldId($iLnkId, $sFieldCode, $bSafe = true)
	{
		$sFieldId = $this->oUILinksDirectWidget->GetInputId().'_'.$sFieldCode.'['.$iLnkId.']';

		return ($bSafe) ? \utils::GetSafeId($sFieldId) : $sFieldId;
	}

	/**
	 * Return global buttons.
	 *
	 * @return array|string[]
	 */
	public function GetButtons(): array
	{
		switch ($this->sType) {
			case self::TYPE_ACTION_ADD:
				return array('add');
			case self::TYPE_ACTION_ADD_REMOVE:
				return array('add', 'remove');
			case self::TYPE_ACTION_CREATE_DELETE:
				return array('create', 'delete');
			case self::TYPE_ACTION_NONE:
			default:
				return array();
		}
	}

	/**
	 * Return row actions.
	 *
	 * @return \string[][]
	 */
	private function GetRowActions(): array
	{
		$aActions = array();

		if ($this->sType == self::TYPE_ACTION_ADD_REMOVE) {
			$aActions[] = [
				'tooltip'       => 'remove link',
				'icon_classes'  => 'fas fa-minus',
				'js_row_action' => "$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('instance')._deleteRow($(':checkbox', oTrElement));",
			];
		}

		return $aActions;
	}
}