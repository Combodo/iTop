<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\sources\Forms\Block\IO;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Block\IO\Format\RawFormat;
use Combodo\iTop\Forms\Block\IO\FormInput;
use Combodo\iTop\Forms\Block\IO\FormOutput;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class AbstractFormIOTest extends ItopDataTestCase
{

	public function testRawBlockHasNoIO(): void
	{
		$oBlock = $this->GivenFormBlock('test');

		self::assertEquals([], $oBlock->GetInputs(), 'Row form block must not have input by default');
		self::assertEquals([], $oBlock->GetOutputs(), 'Row form block must not have output by default');
	}

	public function testAddingOneInputToABlock_StoresIt(): void
	{
		$oBlock = $this->GivenFormBlock('test', [], [
			['io_type' => FormInput::class, 'name' => 'input', 'data_type' => RawFormat::class,],
		]);

		self::assertCount(1, $oBlock->GetInputs(), 'Inputs must be saved in block forms');
		$aInputs = $oBlock->GetInputs();
		self::assertEquals('input', array_shift($aInputs)->getName(), 'Inputs must be saved in block forms');
		self::assertEquals(RawFormat::class, $oBlock->GetInput('input')->GetDataType(), 'Format must be kept in inputs saved in block forms');
	}

	public function testAddingOneOutputToABlock_StoresIt(): void
	{
		$oBlock = $this->GivenFormBlock('test', [], [
			['io_type' => FormOutput::class, 'name' => 'output', 'data_type' => RawFormat::class],
		]);

		self::assertCount(1, $oBlock->GetOutputs(), 'Outputs must be saved in block forms');
		$aInputs = $oBlock->GetOutputs();
		self::assertEquals('output', array_shift($aInputs)->getName(), 'Outputs must be saved in block forms');
		self::assertEquals(RawFormat::class, $oBlock->GetOutput('output')->GetDataType(), 'Format must be kept in outputs saved in block forms');
	}

	public function testAddingMultipleInputsAndOutputsToABlock_StoresThem(): void
	{
		$oBlock = $this->GivenFormBlock('test', [], [
			['io_type' => FormInput::class, 'name' => 'input1', 'data_type' => RawFormat::class,],
			['io_type' => FormInput::class, 'name' => 'input2', 'data_type' => RawFormat::class,],
			['io_type' => FormInput::class, 'name' => 'input3', 'data_type' => RawFormat::class,],
			['io_type' => FormOutput::class, 'name' => 'output1', 'data_type' => RawFormat::class],
			['io_type' => FormOutput::class, 'name' => 'output2', 'data_type' => RawFormat::class],
		]);

		self::assertCount(3, $oBlock->GetInputs(), 'Inputs must be saved in block forms');
		self::assertCount(2, $oBlock->GetOutputs(), 'Outputs must be saved in block forms');
	}


	///////////////////////
	/// GIVEN methods
	///
	private function GivenFormBlock(string $sName, array $aOptions = [], array $aIOs = []): AbstractFormBlock
	{
		$oBlock = new FormBlock($sName, $aOptions);

		foreach ($aIOs as $aIO) {
			if ($aIO['io_type'] === FormInput::class) {
				$oBlock->AddInput($aIO['name'], $aIO['data_type']);
			} else {
				if (isset($aIO['converter_class'])) {
					$oBlock->AddOutput($aIO['name'], $aIO['data_type'], new $aIO['converter_class']);
				} else {
					$oBlock->AddOutput($aIO['name'], $aIO['data_type']);
				}
			}
		}

		return $oBlock;
	}
}
