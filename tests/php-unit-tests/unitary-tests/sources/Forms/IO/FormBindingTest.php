<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Forms\IO;

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
		$oInputIO = $this->GivenRawInput('test');
		$oOutputIO = $this->GivenRawOutput('test');

		// When Linking output to input
		new FormBinding($oOutputIO, $oInputIO);

		// Then
		$this->assertTrue($oInputIO->IsBound(), 'DestinationIO must be Bound when creating a new binding');
	}

	public function testBindingTwiceToTheSameInputIsNotPossible()
	{
		$oInputIO = $this->GivenRawInput('test');
		$oOutputIO1 = $this->GivenRawOutput('test1');
		$oOutputIO2 = $this->GivenRawOutput('test2');

		// When
		new FormBinding($oOutputIO1, $oInputIO);

		// Then
		$this->expectException(FormBlockIOException::class);
		new FormBinding($oOutputIO2, $oInputIO);
	}

	public function testBindingTwiceToTheSameOutputIsNotPossible()
	{
		$oOutputIO1 = $this->GivenRawOutput('test1');
		$oOutputIO2 = $this->GivenRawOutput('test2');
		$oOutputIO3 = $this->GivenRawOutput('test3');

		// When
		new FormBinding($oOutputIO1, $oOutputIO3);

		// Then
		$this->expectException(FormBlockIOException::class);
		new FormBinding($oOutputIO2, $oOutputIO3);

	}

	public function testOutputCanBeBoundToInputAndInputIsBoundAfterThat()
	{
		$oInputIO = $this->GivenRawInput('test');
		$oOutputIO = $this->GivenRawOutput('test1');

		// When
		$oOutputIO->BindToInput($oInputIO);

		// Then
		$this->assertTrue($oInputIO->IsBound(), 'Input must be Bound when binding from an output');
	}

	public function testInputCanBeBoundToAnotherInputAndItIsBoundAfterThat()
	{
		$oInputIO1 = $this->GivenRawInput('test1');
		$oInputIO2 = $this->GivenRawInput('test2');

		// When
		$oInputIO1->BindToInput($oInputIO2);

		// Then
		$this->assertTrue($oInputIO2->IsBound(), 'Input must be Bound when binding from an output');
	}

	public function testOutputCanBeBoundToAnotherOutputAndItIsBoundAfterThat()
	{
		$oOutputIO1 = $this->GivenRawOutput('test1');
		$oOutputIO2 = $this->GivenRawOutput('test2');

		// When
		$oOutputIO1->BindToOutput($oOutputIO2);

		// Then
		$this->assertTrue($oOutputIO2->IsBound(), 'Output must be Bound when binding from an output');
	}

	public function testOutBindingsAreStoredWhenBindToInput()
	{
		$oInputIO1 = $this->GivenRawInput('test1');
		$oInputIO2 = $this->GivenRawInput('test2');
		$oOutputIO1 = $this->GivenRawOutput('test1');

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
		$oOutputIO1 = $this->GivenRawOutput('test1');
		$oOutputIO2 = $this->GivenRawOutput('test2');
		$oOutputIO3 = $this->GivenRawOutput('test3');

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
		$oOutputIO1 = $this->GivenRawOutput('test1');
		$oInputIO1 = $this->GivenRawInput('test1');
		$oBinding = $oOutputIO1->BindToInput($oInputIO1);
		$oOutputIO1->SetValue(FormEvents::PRE_SET_DATA, 'The Value');

		// When
		$oBinding->PropagateValues();

		// Then
		$this->assertEquals('The Value', $oOutputIO1->GetValue(FormEvents::PRE_SET_DATA));

	}
}
