<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\sources\Forms;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\IO\FormBlockIOException;
use Combodo\iTop\Forms\IO\FormInput;
use Combodo\iTop\Forms\IO\FormOutput;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ReflectionClass;

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

abstract class AbstractFormsTest extends ItopDataTestCase
{
	/**
	 * @throws FormBlockIOException
	 */
	public function GivenInput(string $sName, string $sType = StringIOFormat::class): FormInput
	{
		$oBlock = $this->GivenFormBlock($sName);

		$oInput = new FormInput($sName, $sType);
		$oInput->SetOwnerBlock($oBlock);

		return $oInput;
	}

	/**
	 * @throws FormBlockIOException
	 */
	public function GivenOutput(string $sName, string $sType = StringIOFormat::class): FormOutput
	{
		$oBlock = $this->GivenFormBlock($sName);

		$oOutput = new FormOutput($sName, $sType);
		$oOutput->SetOwnerBlock($oBlock);

		return $oOutput;
	}

	public function GivenFormBlock(string $sName): FormBlock
	{
		return new FormBlock($sName, []);
	}

	public function GivenSubFormBlock(FormBlock $oParent, string $sName, string $ssBlockClass = FormBlock::class): AbstractFormBlock
	{
		$oParent->Add($sName, $ssBlockClass, []);

		return $oParent->Get($sName);
	}

	public function GivenIORegister(AbstractFormBlock $oFormBlock): IORegister
	{
		$reflection = new ReflectionClass(AbstractFormBlock::class);
		$reflection_property = $reflection->getProperty('oIORegister');
		$reflection_property->setAccessible(true);
		return $reflection_property->getValue($oFormBlock);
	}
}
