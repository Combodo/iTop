<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Application\UI\Base\Component\Modal;

use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Dict;

/**
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Modal
 * @since 3.1.0
 */
class DoNotShowAgainOptionBlock extends UIContentBlock
{
	/**
	 * Constructor.
	 *
	 * @param string|null $sId
	 */
	public function __construct(string $sId = null)
	{
		parent::__construct($sId, ['ibo-modal-option--do-not-show-again']);

		// initialize UI
		$this->InitUI();
	}

	/**
	 * Initialize UI.
	 *
	 * @return void
	 */
	private function InitUI()
	{
		// Create checkbox
		$oCheckBox = InputUIBlockFactory::MakeStandard('checkbox', 'do_not_show_again', false);
		$oCheckBox->AddCSSClass('ibo-modal-option--do-not-show-again--checkbox');
		$oCheckBox->SetLabel(Dict::S('UI:UserPref:DoNotShowAgain'));
		$this->AddSubBlock($oCheckBox);
	}
}