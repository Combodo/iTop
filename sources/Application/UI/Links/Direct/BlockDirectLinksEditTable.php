<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Direct;

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\MedallionIcon\MedallionIcon;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Dict;
use MetaModel;

/**
 * Class BlockDirectLinksEditTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Direct
 */
class BlockDirectLinksEditTable extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE                   = 'ibo-block-direct-links-edit-table';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/links/direct/block-direct-links-edit-table/layout';

	/** @var \UILinksWidgetDirect */
	public \UILinksWidgetDirect $oUILinksDirectWidget;

	/** @var \AttributeLinkedSet */
	private \AttributeLinkedSet $oAttributeLinkedSet;

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
	 * @param string $sId
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public function __construct(\UILinksWidgetDirect $oUILinksDirectWidget, string $sId)
	{
		parent::__construct($sId, ["ibo-block-direct-links--edit-in-place"]);

		// Retrieve parameters
		$this->oUILinksDirectWidget = $oUILinksDirectWidget;

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

		// Initialization
		$this->Init();

		// Initialize UI
		$this->InitUI();
	}

	/**
	 * Initialisation.
	 *
	 * @return void
	 * @throws \Exception
	 */
	private function Init()
	{
		$this->oAttributeLinkedSet = MetaModel::GetAttributeDef($this->oUILinksDirectWidget->GetClass(), $this->oUILinksDirectWidget->GetAttCode());
	}

	/**
	 * Initialize UI.
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \Exception
	 */
	private function InitUI()
	{
		// MedallionIcon
		$oClassIcon = new MedallionIcon(MetaModel::GetClassIcon($this->oUILinksDirectWidget->GetLinkedClass(), false));
		$oClassIcon->SetDescription($this->oAttributeLinkedSet->GetDescription());
		$oClassIcon->AddCSSClass('ibo-block-list--medallion');
		$this->AddSubBlock($oClassIcon);
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
			$aTablePanel = PanelUIBlockFactory::MakeNeutral('');
			$aTablePanel->SetSubTitle(sprintf('Total: %d objects.', count($aRows)));
			$oToolbar = ToolbarUIBlockFactory::MakeForButton();
			$oActionButtonUnlink = ButtonUIBlockFactory::MakeNeutral('Unlink');
			$oActionButtonUnlink->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('instance')._removeSelection();");
			$oToolbar->AddSubBlock($oActionButtonUnlink);
			$oActionButtonLink = ButtonUIBlockFactory::MakeNeutral('Link');
			$oActionButtonLink->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('instance')._selectToAdd();");
			$oToolbar->AddSubBlock($oActionButtonLink);
			$oActionButtonCreate = ButtonUIBlockFactory::MakeNeutral('Create');
			$oActionButtonCreate->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('instance')._createRow();");
			$oToolbar->AddSubBlock($oActionButtonCreate);
			$oActionButtonDelete = ButtonUIBlockFactory::MakeNeutral('Delete');
			$oActionButtonDelete->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('instance')._deleteSelection();");
			$oToolbar->AddSubBlock($oActionButtonDelete);
			$aTablePanel->AddToolbarBlock($oToolbar);
			$aTablePanel->AddSubBlock($oDatatable);
			$this->AddSubBlock($aTablePanel);
		}
		catch (\Exception $e) {
			$oAlert = AlertUIBlockFactory::MakeForDanger('error', 'error while trying to load datatable');
			$oAlert->SetIsClosable(false);
			$oAlert->SetIsCollapsible(false);
			$this->AddSubBlock($oAlert);
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

				// tentative d'ajout des attributs en édition
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
	 * Return row actions.
	 *
	 * @return \string[][]
	 */
	private function GetRowActions(): array
	{
		$aRowActions = array();

		if (!$this->oAttributeLinkedSet->GetReadOnly()) {

			switch ($this->oAttributeLinkedSet->GetRelationType()) {

				case LINKSET_RELATIONTYPE_LINK:
					$aRowActions[] = array(
						'action'        => 'UI:Links:ActionRow:detach',
						'tooltip'       => 'UI:Links:ActionRow:detach+',
						'icon_classes'  => 'fas fa-minus',
						'js_row_action' => "$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('Remove', $(':checkbox', oTrElement));",
					);
					break;

				case LINKSET_RELATIONTYPE_PROPERTY:
					$aRowActions[] = array(
						'action'        => 'UI:Links:ActionRow:delete',
						'tooltip'       => 'UI:Links:ActionRow:delete+',
						'icon_classes'  => 'fas fa-trash',
						'js_row_action' => "$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('Remove', $(':checkbox', oTrElement));",
					);
					break;
			}
		}

		return $aRowActions;
	}
}