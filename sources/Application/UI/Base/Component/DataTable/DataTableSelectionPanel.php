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
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Dict;
use MetaModel;

/**
 * Class DataTableSelectionPanel
 *
 */
class DataTableSelectionPanel extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-block-datatable-selection-panel';

	private \UILinksWidget $oUILinksWidget;

	private string $sElementLabel;

	public function __construct(string $sId = null, \UILinksWidget $oUILinksWidget, string $sElementLabel)
	{
		parent::__construct($sId, ['ibo-datatable--selection-panel']);

		// retrieve parameters
		$this->oUILinksWidget = $oUILinksWidget;
		$this->sElementLabel = $sElementLabel;

		// initialisation interface
		$this->InitUI();
	}

	public function InitUI()
	{
		$this->AddSubBlock(new Html("<span class=\"ibo-block-datatable-selection-panel--label\">12 $this->sElementLabel</span>"));

		$oAddButton = ButtonUIBlockFactory::MakeNeutral("Add $this->sElementLabel", 'create-link');
		$oAddButton->SetOnClickJsCode("oWidget{$this->oUILinksWidget->GetInputId()}.AddObjects();");
		$oAddButton->AddCSSClass('ibo-block-datatable-selection-panel--add-button');
		$this->AddSubBlock($oAddButton);

		$oUIButton = ButtonUIBlockFactory::MakeForDestructiveAction('Retirer', 'table-selection');
		$oUIButton->SetOnClickJsCode("oWidget{$this->oUILinksWidget->GetInputId()}.RemoveSelected();");
		$oUIButton->AddCSSClass('ibo-block-datatable-selection-panel--remove-button');
		$this->AddSubBlock($oUIButton);
	}
}