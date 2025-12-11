<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Sources\Forms\IO;

use Combodo\iTop\Forms\IO\Format\AttributeIOFormat;
use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\IO\Format\NumberIOFormat;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\IO\FormBinding;
use Combodo\iTop\Forms\IO\FormBlockIOException;
use Combodo\iTop\Test\UnitTest\sources\Forms\AbstractFormsTest;
use Symfony\Component\Form\FormEvents;

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class FormBindingTest extends AbstractFormsTest
{
	public function testCreatingABinding()
	{
		$oInputIO = $this->GivenInput('test');
		$oOutputIO = $this->GivenOutput('test');

		// When Linking output to input
		new FormBinding($oOutputIO, $oInputIO);

		// Then
		$this->assertTrue($oInputIO->IsBound(), 'DestinationIO must be Bound when creating a new binding');
	}

	public function testBindingTwiceToTheSameInputIsNotPossible()
	{
		$oInputIO = $this->GivenInput('test');
		$oOutputIO1 = $this->GivenOutput('test1');
		$oOutputIO2 = $this->GivenOutput('test2');

		// When
		new FormBinding($oOutputIO1, $oInputIO);

		// Then
		$this->expectException(FormBlockIOException::class);
		new FormBinding($oOutputIO2, $oInputIO);
	}

	public function testBindingTwiceToTheSameOutputIsNotPossible()
	{
		$oOutputIO1 = $this->GivenOutput('test1');
		$oOutputIO2 = $this->GivenOutput('test2');
		$oOutputIO3 = $this->GivenOutput('test3');

		// When
		new FormBinding($oOutputIO1, $oOutputIO3);

		// Then
		$this->expectException(FormBlockIOException::class);
		new FormBinding($oOutputIO2, $oOutputIO3);

	}

	public function testOutputCanBeBoundToInputAndInputIsBoundAfterThat()
	{
		$oInputIO = $this->GivenInput('test');
		$oOutputIO = $this->GivenOutput('test1');

		$this->assertFalse($oInputIO->IsBound(), 'Input must not be Bound by default');

		// When
		$oOutputIO->BindToInput($oInputIO);

		// Then
		$this->assertTrue($oInputIO->IsBound(), 'Input must be Bound when binding from an output');
	}

	public function testInputCanBeBoundToAnotherInputAndItIsBoundAfterThat()
	{
		$oInputIO1 = $this->GivenInput('test1');
		$oInputIO2 = $this->GivenInput('test2');

		// When
		$oInputIO1->BindToInput($oInputIO2);

		// Then
		$this->assertTrue($oInputIO2->IsBound(), 'Input must be Bound when binding from an input');
	}

	public function testOutputCanBeBoundToAnotherOutputAndItIsBoundAfterThat()
	{
		$oOutputIO1 = $this->GivenOutput('test1');
		$oOutputIO2 = $this->GivenOutput('test2');

		$this->assertFalse($oOutputIO2->IsBound(), 'Output must not be bound by default');

		// When
		$oOutputIO1->BindToOutput($oOutputIO2);

		// Then
		$this->assertTrue($oOutputIO2->IsBound(), 'Output must be Bound when binding from an output');
	}

	public function testOutBindingsAreStoredWhenBindToInput()
	{
		$oInputIO1 = $this->GivenInput('test1');
		$oInputIO2 = $this->GivenInput('test2');
		$oOutputIO1 = $this->GivenOutput('test1');

		// When
		$oBindingO2ToI1 = $oOutputIO1->BindToInput($oInputIO1);

		// Then
		$this->assertTrue($oOutputIO1->HasBindingOut(), 'Must have bindings after BindToInput');
		$this->assertEquals([$oBindingO2ToI1], $oOutputIO1->GetBindingsToInputs(), 'Must have bindings after BindToInput');

		// When
		$oBindingO1ToI2 = $oOutputIO1->BindToInput($oInputIO2);

		// Then
		$this->assertEquals([$oBindingO2ToI1, $oBindingO1ToI2], $oOutputIO1->GetBindingsToInputs(), 'Must have bindings after BindToInput');
	}

	public function testOutBindingsAreStoredWhenBindToOutput()
	{
		$oOutputIO1 = $this->GivenOutput('test1');
		$oOutputIO2 = $this->GivenOutput('test2');
		$oOutputIO3 = $this->GivenOutput('test3');

		// When
		$oBindingO1ToO2 = $oOutputIO1->BindToOutput($oOutputIO2);

		// Then
		$this->assertTrue($oOutputIO1->HasBindingOut(), 'Must have bindings after BindToInput');
		$this->assertEquals([$oBindingO1ToO2], $oOutputIO1->GetBindingsToOutputs(), 'Must have bindings after BindToOutput');

		// When
		$oBindingO1ToO3 = $oOutputIO1->BindToOutput($oOutputIO3);

		// Then
		$this->assertEquals([$oBindingO1ToO2, $oBindingO1ToO3], $oOutputIO1->GetBindingsToOutputs(), 'Must have bindings after BindToOutput');
	}

	public function testSourceValueIsPropagatedToDestIO()
	{
		$oOutputIO1 = $this->GivenOutput('test1');
		$oInputIO1 = $this->GivenInput('test1');
		$oBinding = $oOutputIO1->BindToInput($oInputIO1);
		$oOutputIO1->SetValue(FormEvents::PRE_SET_DATA, 'The Value');

		// When
		$oBinding->PropagateValues();

		// Then
		$this->assertEquals('The Value', $oOutputIO1->GetValue(FormEvents::PRE_SET_DATA));
	}

	/**
	 * @dataProvider BindingIncompatibleFormatsProvider
	 *
	 * @param string $sSourceFormat
	 * @param string $sDestinationFormat
	 *
	 * @return void
	 */
	public function testBindingIncompatibleFormatsThrowsException(string $sSourceFormat, string $sDestinationFormat)
	{
		$oOutputIO = $this->GivenOutput('test', $sSourceFormat);
		$oInputIO = $this->GivenInput('test', $sDestinationFormat);

		$this->expectException(FormBlockIOException::class);
		$oOutputIO->BindToInput($oInputIO);
	}

	public function BindingIncompatibleFormatsProvider(): array
	{
		return  [
			'Attribute -> Boolean' => [AttributeIOFormat::class, BooleanIOFormat::class],
			'Attribute -> Class' => [AttributeIOFormat::class, ClassIOFormat::class],
			'Attribute -> Number' => [AttributeIOFormat::class, NumberIOFormat::class],
			'Attribute -> String' => [AttributeIOFormat::class, StringIOFormat::class],

			'Boolean => Attribute' => [BooleanIOFormat::class, AttributeIOFormat::class],
			'Boolean => Class' => [BooleanIOFormat::class, ClassIOFormat::class],
			'Boolean => Number' => [BooleanIOFormat::class, NumberIOFormat::class],
			'Boolean -> String' => [BooleanIOFormat::class, StringIOFormat::class],

			'Class => Attribute' => [ClassIOFormat::class, AttributeIOFormat::class],
			'Class => Boolean' => [ClassIOFormat::class, BooleanIOFormat::class],
			'Class => Number' => [ClassIOFormat::class, NumberIOFormat::class],
			'Class -> String' => [ClassIOFormat::class, StringIOFormat::class],

			'Number => Attribute' => [NumberIOFormat::class, AttributeIOFormat::class],
			'Number => Class' => [NumberIOFormat::class, ClassIOFormat::class],
			'Number => Boolean' => [NumberIOFormat::class, BooleanIOFormat::class],
			'Number -> String' => [NumberIOFormat::class, StringIOFormat::class],

			'String => Attribute' => [StringIOFormat::class, AttributeIOFormat::class],
			'String => Class' => [StringIOFormat::class, ClassIOFormat::class],
			'String => Boolean' => [StringIOFormat::class, BooleanIOFormat::class],
			'String -> Number' => [StringIOFormat::class, NumberIOFormat::class],
		];
	}

	/**
	 * @dataProvider BindingCompatibleFormatsProvider
	 *
	 * @param string $sSourceFormat
	 * @param string $sDestinationFormat
	 *
	 * @return void
	 */
	public function testBindingCompatibleFormatsWorks(string $sSourceFormat, string $sDestinationFormat)
	{
		$oOutputIO = $this->GivenOutput('test', $sSourceFormat);
		$oInputIO = $this->GivenInput('test', $sDestinationFormat);

		$oBinding = $oOutputIO->BindToInput($oInputIO);
		$this->assertTrue(is_a($oBinding, FormBinding::class));
	}

	public function BindingCompatibleFormatsProvider(): array
	{
		return [
			'Attribute -> Attribute' => [AttributeIOFormat::class, AttributeIOFormat::class],
			'Boolean => Boolean' => [BooleanIOFormat::class, BooleanIOFormat::class],
			'Class => Class' => [ClassIOFormat::class, ClassIOFormat::class],
			'Number => Number' => [NumberIOFormat::class, NumberIOFormat::class],
			'String => String' => [StringIOFormat::class, StringIOFormat::class],
		];
	}

}
