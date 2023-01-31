<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Direct;

use ApplicationContext;
use ArchivedObjectException;
use AttributeLinkedSet;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\Toolbar;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use ConfigException;
use CoreException;
use CoreUnexpectedValue;
use DBObjectSet;
use Dict;
use DictExceptionMissingString;
use Exception;
use MetaModel;
use MySQLException;
use UILinksWidgetDirect;
use utils;
use WebPage;

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
	public const DEFAULT_JS_FILES_REL_PATH    = [
		'js/links/links_direct_widget.js',
	];

	/** @var UILinksWidgetDirect $oUILinksDirectWidget */
	public UILinksWidgetDirect $oUILinksDirectWidget;

	/** @var AttributeLinkedSet $oAttributeLinkedSet */
	private AttributeLinkedSet $oAttributeLinkedSet;

	/** @var string $sInputName */
	public string $sInputName;

	/** @var array $aLabels */
	public array $aLabels;

	/** @var string $sSubmitUrl */
	public string $sSubmitUrl;

	/** @var string $sWizHelper */
	public string $sWizHelper;

	/** @var string $sJSDoSearch */
	public string $sJSDoSearch;

	/**
	 * Constructor.
	 *
	 * @param UILinksWidgetDirect $oUILinksDirectWidget
	 * @param string $sId
	 *
	 * @throws ConfigException
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 * @throws Exception
	 */
	public function __construct(UILinksWidgetDirect $oUILinksDirectWidget, string $sId)
	{
		parent::__construct($sId, ["ibo-block-direct-links--edit-in-place"]);

		// Retrieve parameters
		$this->oUILinksDirectWidget = $oUILinksDirectWidget;

		// compute
		$this->aLabels = array(
			'creation_title'  => Dict::Format('UI:CreationTitle_Class', MetaModel::GetName($this->oUILinksDirectWidget->GetLinkedClass())),
			'selection_title' => Dict::Format('UI:SelectionOf_Class', MetaModel::GetName($this->oUILinksDirectWidget->GetLinkedClass())),
		);
		$oContext = new ApplicationContext();
		$this->sSubmitUrl = utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?'.$oContext->GetForLink();

		// Don't automatically launch the search if the table is huge
		$bDoSearch = !utils::IsHighCardinality($this->oUILinksDirectWidget->GetLinkedClass());
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
	 * @throws Exception
	 */
	private function Init()
	{
		$this->oAttributeLinkedSet = MetaModel::GetAttributeDef($this->oUILinksDirectWidget->GetClass(), $this->oUILinksDirectWidget->GetAttCode());
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
		// Linkset description as an informative alert
		$sDescription = $this->oAttributeLinkedSet->GetDescription();
		if (utils::IsNotNullOrEmptyString($sDescription)) {
			$oAlert = AlertUIBlockFactory::MakeForInformation('', $sDescription);
			$this->AddSubBlock($oAlert);
		}
	}

	/**
	 * @param WebPage $oPage
	 * @param DBObjectSet $oValue
	 * @param string $sFormPrefix
	 *
	 * @return void
	 */
	public function InitTable(WebPage $oPage, DBObjectSet $oValue, string $sFormPrefix)
	{
		$this->sInputName = $sFormPrefix.'attr_'.$this->oUILinksDirectWidget->GetAttCode();
		$this->sWizHelper = 'oWizardHelper'.$sFormPrefix;

		try {
			$aAttribs = $this->oUILinksDirectWidget->GetTableConfig();
			$aRows = $this->GetTableRows($oPage, $oValue);
			$aRowActions = $this->GetRowActions();
			$oDatatable = DataTableUIBlockFactory::MakeForForm($this->oUILinksDirectWidget->GetInputId(), $aAttribs, $aRows, '', $aRowActions);
			$oDatatable->SetOptions(['select_mode' => 'custom', 'disable_hyperlinks' => true]);

			// Panel
			$oTablePanel = PanelUIBlockFactory::MakeForClass($this->oUILinksDirectWidget->GetLinkedClass(), $this->oAttributeLinkedSet->GetLabel())
				->SetSubTitle(sprintf('Total: %d objects.', count($aRows)))
				->SetIcon(MetaModel::GetClassIcon($this->oUILinksDirectWidget->GetLinkedClass(), false))
				->AddCSSClass('ibo-datatable-panel');

			// Toolbar and actions
			$oToolbar = $this->InitToolBar();
			$oTablePanel->AddToolbarBlock($oToolbar);
			$oTablePanel->AddSubBlock($oDatatable);
			$this->AddSubBlock($oTablePanel);
		}
		catch (\Exception $e) {
			$oAlert = AlertUIBlockFactory::MakeForDanger('error', 'error while trying to load datatable');
			$oAlert->SetIsClosable(false);
			$oAlert->SetIsCollapsible(false);
			$this->AddSubBlock($oAlert);
		}
	}

	/**
	 * InitToolBar.
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Toolbar\Toolbar
	 */
	private function InitToolBar(): Toolbar
	{
		$oToolbar = ToolbarUIBlockFactory::MakeForButton();

		// until a full link set refactoring (continue using edit_mode property)
		switch ($this->oAttributeLinkedSet->GetEditMode()) {
			case LINKSET_EDITMODE_NONE: // The linkset is read-only
				break;

			case LINKSET_EDITMODE_ADDONLY: // The only possible action is to open (in a new window) the form to create a new object
				$oActionButtonLink = ButtonUIBlockFactory::MakeNeutral('Link', 'link');
				$oActionButtonLink->AddDataAttribute('action', 'add');
				$oActionButtonLink->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('selectToAdd');");
				$oToolbar->AddSubBlock($oActionButtonLink);
				break;

			case LINKSET_EDITMODE_INPLACE: // The whole linkset can be edited 'in-place'
				$oActionButtonCreate = ButtonUIBlockFactory::MakeNeutral('Create', 'create');
				$oActionButtonCreate->AddDataAttribute('action', 'create');
				$oActionButtonCreate->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('createRow');");
				$oToolbar->AddSubBlock($oActionButtonCreate);
				$oActionButtonDelete = ButtonUIBlockFactory::MakeNeutral('Delete', 'delete');
				$oActionButtonDelete->AddDataAttribute('action', 'delete');
				$oActionButtonDelete->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('deleteSelection');");
				$oToolbar->AddSubBlock($oActionButtonDelete);
				break;

			case LINKSET_EDITMODE_ADDREMOVE: // The whole linkset can be edited 'in-place'
				$oActionButtonUnlink = ButtonUIBlockFactory::MakeNeutral('Unlink', 'unlink');
				$oActionButtonUnlink->AddDataAttribute('action', 'detach');
				$oActionButtonUnlink->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('removeSelection');");
				$oToolbar->AddSubBlock($oActionButtonUnlink);
				$oActionButtonLink = ButtonUIBlockFactory::MakeNeutral('Link', 'link');
				$oActionButtonLink->AddDataAttribute('action', 'add');
				$oActionButtonLink->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('selectToAdd');");
				$oToolbar->AddSubBlock($oActionButtonLink);
				break;

			case LINKSET_EDITMODE_ACTIONS: // Show the usual 'Actions' popup menu
			default:

		}

		return $oToolbar;
	}


	/**
	 * Return table rows.
	 *
	 * @param DBObjectSet $oValue
	 *
	 * @return array
	 * @throws ArchivedObjectException
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @throws DictExceptionMissingString
	 * @throws MySQLException
	 * @throws Exception
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
	 * Convert edit_mode to relation type.
	 *
	 * @return string|null
	 */
	private function ConvertEditModeToRelationType(): ?string
	{
		switch ($this->oAttributeLinkedSet->GetEditMode()) {
			case LINKSET_EDITMODE_INPLACE:
				return LINKSET_RELATIONTYPE_PROPERTY;
			case LINKSET_EDITMODE_ADDREMOVE:
				return LINKSET_RELATIONTYPE_LINK;
			default:
				return null;
		}
	}

	/**
	 * Convert edit_mode to read only.
	 *
	 * @return bool
	 */
	private function ConvertEditModeToReadOnly(): bool
	{
		switch ($this->oAttributeLinkedSet->GetEditMode()) {
			case LINKSET_EDITMODE_NONE:
			case LINKSET_EDITMODE_ADDONLY:
			case LINKSET_EDITMODE_ACTIONS:
				return true;

			default:
				return false;
		}
	}

	/**
	 * Return row actions.
	 *
	 * @return \string[][]
	 */
	private function GetRowActions(): array
	{
		$aRowActions = array();

		if (!$this->ConvertEditModeToReadOnly()) {

			switch ($this->ConvertEditModeToRelationType()) {

				case LINKSET_RELATIONTYPE_LINK:
					$aRowActions[] = array(
						'label'         => 'UI:Links:ActionRow:Detach',
						'tooltip'       => 'UI:Links:ActionRow:Detach+',
						'icon_classes'  => 'fas fa-minus',
						'js_row_action' => "$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('Remove', $(':checkbox', oTrElement));",
					);
					break;

				case LINKSET_RELATIONTYPE_PROPERTY:
					$aRowActions[] = array(
						'label'         => 'UI:Links:ActionRow:Delete',
						'tooltip'       => 'UI:Links:ActionRow:Delete+',
						'icon_classes'  => 'fas fa-trash',
						'js_row_action' => "$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('Remove', $(':checkbox', oTrElement));",
					);
					break;
			}
		}

		return $aRowActions;
	}
}