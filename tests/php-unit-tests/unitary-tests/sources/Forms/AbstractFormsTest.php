<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\sources\Forms;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\IO\FormInput;
use Combodo\iTop\Forms\IO\FormOutput;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

abstract class AbstractFormsTest extends ItopDataTestCase
{
	public function GivenInput(string $sName, string $sType = StringIOFormat::class): FormInput
	{
		$oBlock = $this->GivenFormBlock($sName.'_block');

		return new FormInput($sName.'_input', $sType, $oBlock);
	}

	public function GivenOutput(string $sName, string $sType = StringIOFormat::class): FormOutput
	{
		$oBlock = $this->GivenFormBlock($sName.'_block');

		return new FormOutput($sName.'_output', $sType, $oBlock);
	}

	public function GivenFormBlock(string $sName, string $sBlockClass = FormBlock::class): AbstractFormBlock
	{
		return new $sBlockClass($sName, []);
	}

	public function GivenSubFormBlock(AbstractFormBlock $oParent, string $sName, string $ssBlockClass = FormBlock::class): AbstractFormBlock
	{
		$oParent->Add($sName, $ssBlockClass, []);

		return $oParent->Get($sName);
	}
}
