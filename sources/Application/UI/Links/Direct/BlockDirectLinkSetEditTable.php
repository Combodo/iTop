<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
use DBObject;
use DBObjectSet;
use Dict;
use DictExceptionMissingString;
use Exception;
use iDBObjectSetIterator;
use MetaModel;
use MySQLException;
use UILinksWidgetDirect;
use UserRights;
use utils;
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Class BlockDirectLinkSetEditTable
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Direct
 */
class BlockDirectLinkSetEditTable extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE                   = 'ibo-block-direct-linkset-edit-table';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/links/direct/block-direct-linkset-edit-table/layout';
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

	// User rights
	private bool $bIsAllowCreate;
	private bool $bIsAllowModify;
	private bool $bIsAllowDelete;

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

		$sEditWhen = $this->oAttributeLinkedSet->GetEditWhen();
		$bIsEditableBasedOnEditWhen = ($sEditWhen === LINKSET_EDITWHEN_ALWAYS || $sEditWhen === LINKSET_EDITWHEN_ON_HOST_EDITION);

		// User rights
		$this->bIsAllowCreate = UserRights::IsActionAllowed($this->oAttributeLinkedSet->GetLinkedClass(), UR_ACTION_CREATE) == UR_ALLOWED_YES && $bIsEditableBasedOnEditWhen;
		$this->bIsAllowModify = UserRights::IsActionAllowed($this->oAttributeLinkedSet->GetLinkedClass(), UR_ACTION_MODIFY) == UR_ALLOWED_YES && $bIsEditableBasedOnEditWhen;
		$this->bIsAllowDelete = UserRights::IsActionAllowed($this->oAttributeLinkedSet->GetLinkedClass(), UR_ACTION_DELETE) == UR_ALLOWED_YES && $bIsEditableBasedOnEditWhen;
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

	}

	/**
	 * @param WebPage $oPage
	 * @param \DBObjectSet $oValue
	 * @param string $sFormPrefix
	 * @param \DBObject $oCurrentObj
	 *
	 * @return void
	 */
	public function InitTable(WebPage $oPage, iDBObjectSetIterator $oValue, string $sFormPrefix, DBObject $oCurrentObj)
	{
		$this->sInputName = $sFormPrefix.'attr_'.$this->oUILinksDirectWidget->GetAttCode();
		$this->sWizHelper = 'oWizardHelper'.$sFormPrefix;

		try {
			$aAttribs = $this->oUILinksDirectWidget->GetTableConfig();
			$aRows = $this->GetTableRows($oPage, $oValue);
			$aRowActions = $this->GetRowActions($oCurrentObj);
			$oDatatable = DataTableUIBlockFactory::MakeForForm($this->oUILinksDirectWidget->GetInputId(), $aAttribs, $aRows, '', $aRowActions);
			$oDatatable->SetOptions(['select_mode' => 'custom', 'disable_hyperlinks' => true]);

			// Panel
			$oTablePanel = PanelUIBlockFactory::MakeForClass($this->oUILinksDirectWidget->GetLinkedClass(), $this->oAttributeLinkedSet->GetLabel())
				->SetSubTitle(Dict::Format('UI:Pagination:HeaderNoSelection', count($aRows)))
				->SetIcon(MetaModel::GetClassIcon($this->oUILinksDirectWidget->GetLinkedClass(), false))
				->AddCSSClass('ibo-datatable-panel');

			// - Panel description
			$sDescription = $this->oAttributeLinkedSet->GetDescription();
			if (utils::IsNotNullOrEmptyString($sDescription)) {
				$oTitleBlock = $oTablePanel->GetTitleBlock()
					->AddDataAttribute('tooltip-content', $sDescription)
					->AddDataAttribute('tooltip-max-width', 'min(600px, 90vw)') // Allow big description to be wide enough while shrinking on small screens
					->AddCSSClass('ibo-has-description');
			}

			// Toolbar and actions
			$oToolbar = $this->InitToolBar();
			$oTablePanel->AddToolbarBlock($oToolbar);
			$oTablePanel->AddSubBlock($oDatatable);
			$this->AddSubBlock($oTablePanel);
		}
		catch (\Exception $e) {
			$oAlert = AlertUIBlockFactory::MakeForDanger('error', Dict::S('UI:Datatables:Language:Error'));
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
				if ($this->bIsAllowCreate) {
					$oActionButtonCreate = ButtonUIBlockFactory::MakeNeutral(Dict::S('UI:Button:Create'));
					$oActionButtonCreate->SetTooltip(Dict::Format('UI:ClickToCreateNew', MetaModel::GetName($this->oAttributeLinkedSet->GetLinkedClass())))
						->AddDataAttribute('action', 'create')
						->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('createRow');");
					$oToolbar->AddSubBlock($oActionButtonCreate);
				}
				break;

			case LINKSET_EDITMODE_INPLACE: // The whole linkset can be edited 'in-place'
			case LINKSET_EDITMODE_ACTIONS: // Show the usual 'Actions' popup menu
			if ($this->bIsAllowCreate) {
				$oActionButtonCreate = ButtonUIBlockFactory::MakeNeutral(Dict::S('UI:Button:Create'));
				$oActionButtonCreate->SetTooltip(Dict::Format('UI:ClickToCreateNew', MetaModel::GetName($this->oAttributeLinkedSet->GetLinkedClass())))
					->AddDataAttribute('action', 'create')
					->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('createRow');");
				$oToolbar->AddSubBlock($oActionButtonCreate);
			}

			if ($this->bIsAllowDelete) {
				$oActionButtonDelete = ButtonUIBlockFactory::MakeNeutral(Dict::S('UI:Button:Delete'));
				$oActionButtonDelete->AddDataAttribute('action', 'delete')
					->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('deleteSelection');");
				$oToolbar->AddSubBlock($oActionButtonDelete);
			}
				break;

			case LINKSET_EDITMODE_ADDREMOVE: // The whole linkset can be edited 'in-place'
				if ($this->bIsAllowCreate) {
					$oActionButtonLink = ButtonUIBlockFactory::MakeNeutral(Dict::S('UI:Button:Add'));
					$oActionButtonLink->SetTooltip(Dict::Format('UI:AddLinkedObjectsOf_Class', MetaModel::GetName($this->oAttributeLinkedSet->GetLinkedClass())))
						->AddDataAttribute('action', 'add')
						->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('selectToAdd');");
					$oToolbar->AddSubBlock($oActionButtonLink);
				}

				if ($this->bIsAllowDelete) {
					$oActionButtonUnlink = ButtonUIBlockFactory::MakeNeutral(Dict::S('UI:Button:Remove'));
					$oActionButtonUnlink->AddDataAttribute('action', 'detach')
						->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('removeSelection');");
					$oToolbar->AddSubBlock($oActionButtonUnlink);
				}
				break;

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
	private function GetTableRows(WebPage $oPage, iDBObjectSetIterator $oValue): array
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
	 * Return row actions.
	 *
	 * @param \DBObject $oHostObject
	 *
	 * @return \string[][]
	 */
	private function GetRowActions(DBObject $oHostObject): array
	{
		$aRowActions = array();

		$sDeleteButtonTooltip = $this->oAttributeLinkedSet->SearchSpecificLabel('UI:Links:Delete:Button+', '', true,
			MetaModel::GetName($this->oAttributeLinkedSet->GetHostClass()),
			$oHostObject->Get('friendlyname'),
			$this->oAttributeLinkedSet->GetLabel(),
			MetaModel::GetName($this->oUILinksDirectWidget->GetLinkedClass()));

		$sRemoveButtonTooltip = $this->oAttributeLinkedSet->SearchSpecificLabel('UI:Links:Remove:Button+', '', true,
			MetaModel::GetName($this->oAttributeLinkedSet->GetHostClass()),
			$oHostObject->Get('friendlyname'),
			$this->oAttributeLinkedSet->GetLabel(),
			MetaModel::GetName($this->oUILinksDirectWidget->GetLinkedClass()));

		// until a full link set refactoring (continue using edit_mode property)
		switch ($this->oAttributeLinkedSet->GetEditMode()) {
			case LINKSET_EDITMODE_NONE: // The linkset is read-only
				break;

			case LINKSET_EDITMODE_ADDONLY: // The only possible action is to open (in a new window) the form to create a new object
				break;

			case LINKSET_EDITMODE_INPLACE: // The whole linkset can be edited 'in-place'
			case LINKSET_EDITMODE_ACTIONS: // Show the usual 'Actions' popup menu
			if ($this->bIsAllowDelete) {
				$aRowActions[] = array(
					'label'         => 'UI:Links:Delete:Button',
					'tooltip'       => $sDeleteButtonTooltip,
					'icon_classes'  => 'fas fa-trash',
					'js_row_action' => "$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('Remove', $(':checkbox', oTrElement));",
				);
			}
				break;

			case LINKSET_EDITMODE_ADDREMOVE: // The whole linkset can be edited 'in-place'
				if ($this->bIsAllowModify) {
					$aRowActions[] = array(
						'label'         => 'UI:Links:Remove:Button',
						'tooltip'       => $sRemoveButtonTooltip,
						'icon_classes'  => 'fas fa-minus',
						'js_row_action' => "$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('Remove', $(':checkbox', oTrElement));",
					);
				}
				break;

			default:

		}

		return $aRowActions;
	}
}