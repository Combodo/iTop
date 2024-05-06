<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\ButtonGroup;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\Button;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonJS;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;

/**
 * Class ButtonGroupUIBlockFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 */
class ButtonGroupUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIButtonGroup';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = ButtonGroup::class;

	/**
	 * Make a button that has a primary action ($oButton) but also an options menu ($oMenu) on the side
	 *
	 * @api
	 * @param \Combodo\iTop\Application\UI\Base\Component\Button\Button $oButton
	 * @param \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu $oMenu
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroup
	 */
	public static function MakeButtonWithOptionsMenu(Button $oButton, PopoverMenu $oMenu)
	{
		$oButtonGroup = new ButtonGroup();

		// Add base button
		$oButtonGroup->AddButton($oButton);

		// Add options menu
		$oMenuToggler = new ButtonJS('');
		$oMenuToggler->SetIconClass('fas fa-fw, fa-caret-down')
			->AddCSSClass('ibo-button-for-options-menu')
			->SetColor($oButton->GetColor())
			->SetActionType($oButton->GetActionType());

		$oMenu->SetTogglerFromBlock($oMenuToggler);
		$oButtonGroup->AddButton($oMenuToggler)
			->AddExtraBlock($oMenu);

		return $oButtonGroup;
	}
}