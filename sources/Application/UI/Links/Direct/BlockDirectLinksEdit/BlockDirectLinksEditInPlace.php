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
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
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
	public string $sLabels;

	/** @var string */
	public string $sSubmitUrl;

	/** @var string */
	public string $sButtons;

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
		$this->sLabels = json_encode($this->aLabels);
		$this->sButtons = json_encode($this->GetButtons());

		// Don't automatically launch the search if the table is huge
		$bDoSearch = !\utils::IsHighCardinality($this->oUILinksDirectWidget->GetLinkedClass());
		$this->sJSDoSearch = $bDoSearch ? 'true' : 'false';

		// Initialize UI
		$this->InitUI();
	}


	/**
	 * @return array|string[]
	 */
	private function GetButtons()
	{
		switch ($this->sType) {
			case Self::TYPE_ACTION_ADD:
				return array('add');
			case Self::TYPE_ACTION_ADD_REMOVE:
				return array('add', 'remove');
			case Self::TYPE_ACTION_NONE:
			default:
				return array();
		}
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
		$this->SetSubTitle(MetaModel::GetAttributeDef($this->oUILinksDirectWidget->GetClass(), $this->oUILinksDirectWidget->GetAttCode())->GetDescription());
		$this->SetColorFromClass($this->oUILinksDirectWidget->GetLinkedClass());
		$this->SetIcon(MetaModel::GetClassIcon($this->oUILinksDirectWidget->GetLinkedClass(), false));

		// table information alert
		$this->AddSubBlock($this->CreateTableInformationAlert());

		// Toolbar
//		$this->InitToolBar();
	}

	/**
	 * InitToolBar.
	 *
	 * @return void
	 */
	private function InitToolBar()
	{
		// Add button
		$oAddButton = ButtonUIBlockFactory::MakeNeutral("Add {$this->oUILinksDirectWidget->GetLinkedClass()}", 'create-link');
		$oAddButton->SetOnClickJsCode("oWidget{$this->oUILinksDirectWidget->GetInputId()}.AddObjects();");
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
		$oAlert = AlertUIBlockFactory::MakeNeutral('', '', "linkedset_{$this->oUILinksDirectWidget->GetInputId()}_alert_information");
		$oAlert->AddCSSClasses([
			'ibo-table--alert-information',
		]);
		$oAlert->SetIsClosable(false);
		$oAlert->SetIsCollapsible(false);
		$oAlert->AddSubBlock(new Html('<span class="ibo-table--alert-information--label" data-role="ibo-datatable-selection-value"></span>'));

		// Delete button
		$oUIButton = ButtonUIBlockFactory::MakeForDestructiveAction("Détacher les {$this->oUILinksDirectWidget->GetLinkedClass()}", 'table-selection');
		$oUIButton->SetOnClickJsCode("oWidget{$this->oUILinksDirectWidget->GetInputId()}.RemoveSelected();");
		$oUIButton->AddCSSClass('ibo-table--alert-information--delete-button');
		$oAlert->AddSubBlock($oUIButton);

		// Add button
		$oUIAddButton = ButtonUIBlockFactory::MakeForPrimaryAction("Attacher des {$this->oUILinksDirectWidget->GetLinkedClass()}", 'table-selection');
		$oUIAddButton->AddCSSClass('ibo-table--alert-information--add-button');
		$oUIAddButton->SetOnClickJsCode("$('#{$this->oUILinksDirectWidget->GetInputId()}').directlinks('instance')._selectToAdd();");
		$oAlert->AddSubBlock($oUIAddButton);


		//	$oAlert = new DataTableSelectionPanel('dd', $this->oUILinksWidget, 'contact');

		return $oAlert;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Links\Indirect\BlockIndirectLinksEdit\WebPage $oPage
	 * @param $oValue
	 * @param $sFormPrefix
	 *
	 * @return void
	 */
	public function InitTable(\WebPage $oPage, $oValue, $sFormPrefix)
	{
		$this->sInputName = $sFormPrefix.'attr_'.$this->oUILinksDirectWidget->GetAttCode();
		$this->sWizHelper = 'oWizardHelper'.$sFormPrefix;

		$aAttribs = $this->oUILinksDirectWidget->GetTableConfig();
		$oValue->Rewind();
		$aData = array();
		while ($oLinkObj = $oValue->Fetch()) {
			$aRow = array();
			$aRow['form::select'] = '<input type="checkbox" class="selectList'.$this->oUILinksDirectWidget->GetInputId().'" value="'.$oLinkObj->GetKey().'"/>';
			foreach ($this->oUILinksDirectWidget->GetZList() as $sLinkedAttCode) {
				$aRow[$sLinkedAttCode] = $oLinkObj->GetAsHTML($sLinkedAttCode);
			}
			$aData[] = $aRow;
		}


		$aRow_actions = [
			[
				'tooltip'       => 'Edit',
				'icon_classes'  => 'fas fa-edit',
				'js_row_action' => "alert('edit link');",
			],
			[
				'tooltip'       => 'displayblock.class.inc.php :: RenderList()',
				'icon_classes'  => 'fas fa-minus',
				'js_row_action' => 'console.log("Action ID:");console.log(iActionId);',
			],
		];
		$oDatatable = DataTableUIBlockFactory::MakeForForm($this->oUILinksDirectWidget->GetInputId(), $aAttribs, $aData, '', $aRow_actions);
		$oDatatable->SetOptions(['select_mode' => 'custom', 'disable_hyperlinks' => true]);
		$this->AddSubBlock($oDatatable);

	}

}