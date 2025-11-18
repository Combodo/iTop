<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\sources\Forms;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\IO\Format\RawFormat;
use Combodo\iTop\Forms\IO\FormInput;
use Combodo\iTop\Forms\IO\FormOutput;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

abstract class AbstractFormsTest extends ItopDataTestCase
{
	public function GivenInput(string $sName, string $sType = RawFormat::class): FormInput
	{
		$oBlock = $this->GivenFormBlock($sName.'_block');

		return new FormInput($sName.'_input', $sType, $oBlock);
	}

	public function GivenOutput(string $sName, string $sType = RawFormat::class): FormOutput
	{
		$oBlock = $this->GivenFormBlock($sName.'_block');

		return new FormOutput($sName.'_output', $sType, $oBlock);
	}

	public function GivenFormBlock(string $sName, array $aOptions = [], array $aIOs = []): AbstractFormBlock
	{
		$oBlock = new FormBlock($sName, $aOptions);

		foreach ($aIOs as $aIO) {
			if ($aIO['io_type'] === FormInput::class) {
				$oBlock->AddInput($aIO['name'], $aIO['data_type']);
			} else {
				if (isset($aIO['converter_class'])) {
					$oBlock->AddOutput($aIO['name'], $aIO['data_type'], new $aIO['converter_class']());
				} else {
					$oBlock->AddOutput($aIO['name'], $aIO['data_type']);
				}
			}
		}

		return $oBlock;
	}
}
