<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Indirect;

use Combodo\iTop\Application\UI\Base\Component\Form\Form;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Dict;

/**
 * Class BlockObjectPickerDialog
 *
 * @internal
 * @package Combodo\iTop\Application\UI\Links\Indirect
 */
class BlockObjectPickerDialog extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE                            = 'ibo-block-object-picker-dialog';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'application/links/indirect/block-object-picker-dialog/layout';

	/** @var \UILinksWidget */
	public \UILinksWidget $oUILinksWidget;

	/**
	 * Constructor.
	 *
	 * @param \UILinksWidget $oUILinksWidget
	 */
	public function __construct(\UILinksWidget $oUILinksWidget)
	{
		parent::__construct();

		// Retrieve parameters
		$this->oUILinksWidget = $oUILinksWidget;
	}

	public function AddForm()
	{
		$sEmptyList = Dict::S('UI:Message:EmptyList:UseSearchForm');
		$sCancel = Dict::S('UI:Button:Cancel');
		$sAdd = Dict::S('UI:Button:Add');

		$oForm = new Form("ObjectsAddForm_{$this->oUILinksWidget->GetLinkedSetId()}");
		$this->AddSubBlock($oForm);
		$oBlock = new UIContentBlock("SearchResultsToAdd_{$this->oUILinksWidget->GetLinkedSetId()}", ['ibo-block-object-picker-dialog--results']);
		$oForm->AddSubBlock($oBlock);
		$oBlock->AddHtml("<p>{$sEmptyList}</p>");

		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("count_{$this->oUILinksWidget->GetLinkedSetId()}", '0', "count_{$this->oUILinksWidget->GetLinkedSetId()}"));

	}
}