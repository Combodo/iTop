<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Sources\Forms\Register;

use Combodo\iTop\Forms\Block\Base\CheckboxFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Block\Base\TextFormBlock;
use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\RegisterException;
use Combodo\iTop\Test\UnitTest\sources\Forms\AbstractFormsTest;

class IORegisterTest extends AbstractFormsTest
{
	private FormBlock $oFormBlock;
	private IORegister $oIORegister;

	protected function setUp(): void
	{
		parent::setUp();

		$this->oFormBlock = $this->GivenFormBlock('OneBlock');
		$this->oIORegister = $this->GivenIORegister($this->oFormBlock);
	}

	public function testAddInput(): void
	{
		$this->oIORegister->AddInput('input', StringIOFormat::class);

		$this->assertTrue($this->oIORegister->HasInput('input'));
		$this->assertNotNull($this->oIORegister->GetInput('input'));
	}

	public function testGetInputs(): void
	{
		$iOriginInputCount = count($this->oIORegister->GetInputs());
		$this->oIORegister->AddInput('input_1', StringIOFormat::class);
		$this->oIORegister->AddInput('input_2', BooleanIOFormat::class);
		$this->oIORegister->AddInput('input_3', StringIOFormat::class);

		$this->assertCount(3 + $iOriginInputCount, $this->oIORegister->GetInputs());
		$this->assertArrayHasKey('input_1', $this->oIORegister->GetInputs());
	}

	public function testGetOutputs(): void
	{
		$this->oIORegister->AddOutput('output_1', StringIOFormat::class);
		$this->oIORegister->AddOutput('output_2', BooleanIOFormat::class);

		$this->assertCount(2, $this->oIORegister->GetOutputs());
		$this->assertArrayHasKey('output_1', $this->oIORegister->GetOutputs());
	}

	public function testMissingInput(): void
	{
		$this->expectException(RegisterException::class);
		$this->oIORegister->GetInput('missing_input');
	}

	public function testMissingOutput(): void
	{
		$this->expectException(RegisterException::class);
		$this->oIORegister->GetOutput('missing_output');
	}

	public function testGetBoundInputs(): void
	{
		$this->GivenSubFormBlock($this->oFormBlock, 'SubFormA', TextFormBlock::class);
		$this->GivenSubFormBlock($this->oFormBlock, 'SubFormB', CheckboxFormBlock::class);
		$this->GivenSubFormBlock($this->oFormBlock, 'SubFormC', TextFormBlock::class);

		$oSubForm = $this->GivenSubFormBlock($this->oFormBlock, 'SubForm', TextFormBlock::class);
		$oSubForm->AddInput('input_from_A', StringIOFormat::class);
		$oSubForm->AddInput('input_from_B', BooleanIOFormat::class);
		$oSubForm->AddInput('input_from_C', StringIOFormat::class);
		$oSubForm->AddInput('unbound_input', StringIOFormat::class);

		$this->GivenIORegister($oSubForm)->InputDependsOn('input_from_A', 'SubFormA', TextFormBlock::OUTPUT_TEXT);
		$this->GivenIORegister($oSubForm)->InputDependsOn('input_from_B', 'SubFormB', CheckboxFormBlock::OUTPUT_CHECKED);
		$this->GivenIORegister($oSubForm)->InputDependsOn('input_from_C', 'SubFormC', TextFormBlock::OUTPUT_TEXT);

		$aBoundInputs = $this->GivenIORegister($oSubForm)->GetBoundInputs();
		$this->assertCount(3, $aBoundInputs);
	}

	public function testGetBoundOutputs(): void
	{
		$this->oFormBlock->AddOutput('output', StringIOFormat::class);

		$oSubFormA = $this->GivenSubFormBlock($this->oFormBlock, 'SubFormA', TextFormBlock::class);
		$oIORegisterA = $this->GivenIORegister($oSubFormA);

		$oIORegisterA->OutputImpactParent(TextFormBlock::OUTPUT_TEXT, 'output');

		$this->assertCount(1, $this->oIORegister->GetBoundOutputs());
	}

	public function testAddInputDependsOn(): void
	{
		$this->GivenSubFormBlock($this->oFormBlock, 'SubFormA', TextFormBlock::class);
		$oSubFormB = $this->GivenSubFormBlock($this->oFormBlock, 'SubFormB', TextFormBlock::class);
		$oIORegisterB = $this->GivenIORegister($oSubFormB);

		$oIORegisterB->AddInputDependsOn('input', 'SubFormA', TextFormBlock::OUTPUT_TEXT);

		$this->assertNotNull($oIORegisterB->GetInput('input'));
	}

	public function testImpactParent(): void
	{
		$this->oFormBlock->AddOutput('output', StringIOFormat::class);

		$oSubFormA = $this->GivenSubFormBlock($this->oFormBlock, 'SubFormA', TextFormBlock::class);
		$oIORegisterA = $this->GivenIORegister($oSubFormA);

		$oIORegisterA->OutputImpactParent(TextFormBlock::OUTPUT_TEXT, 'output');

		$this->assertTrue($this->oFormBlock->GetOutput('output')->IsBound());
	}

	public function testAddingTwiceTheSameInputThrowsException(): void
	{
		$this->oIORegister->AddInput('test_input', StringIOFormat::class);
		$this->expectException(RegisterException::class);
		$this->oIORegister->AddInput('test_input', StringIOFormat::class);
	}

	public function testAddingTwiceTheSameOutputThrowsException(): void
	{
		$this->oIORegister->AddOutput('test_output', StringIOFormat::class);
		$this->expectException(RegisterException::class);
		$this->oIORegister->AddOutput('test_output', StringIOFormat::class);
	}

	public function testDependingOnNonExistingInputThrowsException(): void
	{
		$this->oIORegister->AddInput('test_input', StringIOFormat::class);
		$this->oIORegister->AddOutput('test_output', StringIOFormat::class);

		$this->expectException(RegisterException::class);

		$this->oIORegister->InputDependsOn('non_existing_input', 'OtherBlock', 'test_output');
	}

	public function testDependingOnNonExistingOutputThrowsException(): void
	{
		$this->oIORegister->AddInput('test_input', StringIOFormat::class);

		$this->expectException(RegisterException::class);
		$this->oIORegister->InputDependsOn('test_input', 'OtherBlock', 'non_existing_output');
	}

	public function testDependingOnNonExistingBlockThrowsException(): void
	{
		$this->oIORegister->AddInput('test_input', StringIOFormat::class);
		$this->oIORegister->AddOutput('test_output', StringIOFormat::class);

		$this->expectException(RegisterException::class);
		$this->oIORegister->InputDependsOn('test_input', 'UnknownBlock', 'test');
	}

	public function testHasDependenciesBlocks(): void
	{
		$this->GivenSubFormBlock($this->oFormBlock, 'SubFormA', TextFormBlock::class);

		$oSubForm = $this->GivenSubFormBlock($this->oFormBlock, 'SubForm', TextFormBlock::class);
		$oSubForm->AddInput('input_from_A', StringIOFormat::class);

		$this->GivenIORegister($oSubForm)->InputDependsOn('input_from_A', 'SubFormA', TextFormBlock::OUTPUT_TEXT);
		$this->assertTrue($this->GivenIORegister($oSubForm)->HasDependenciesBlocks());

		$this->assertFalse($this->oIORegister->HasDependenciesBlocks());
	}

	public function testImpactBlocks(): void
	{
		$oSubFormA = $this->GivenSubFormBlock($this->oFormBlock, 'SubFormA', TextFormBlock::class);

		$oSubForm = $this->GivenSubFormBlock($this->oFormBlock, 'SubForm', TextFormBlock::class);
		$oSubForm->AddInput('input_from_A', StringIOFormat::class);

		$this->GivenIORegister($oSubForm)->InputDependsOn('input_from_A', 'SubFormA', TextFormBlock::OUTPUT_TEXT);
		$this->assertFalse($this->GivenIORegister($oSubForm)->IsImpactingBlocks());

		$this->assertTrue($this->GivenIORegister($oSubFormA)->IsImpactingBlocks());
	}
}
