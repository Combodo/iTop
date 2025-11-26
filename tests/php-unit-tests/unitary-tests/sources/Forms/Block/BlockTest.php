<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Forms\Block;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\Block\Base\CheckboxFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Block\Base\TextFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\Forms;
use Combodo\iTop\Forms\IFormBlock;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\Register\RegisterException;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Combodo\iTop\Test\UnitTest\sources\Forms\AbstractFormsTest;
use OutOfBoundsException;
use ReflectionException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Test forms block.
 *
 */
class BlockTest extends AbstractFormsTest
{
	/**
	 * Block get form type must return a class derived from Symfony form AbstractType.
	 *
	 * @throws ReflectionException
	 */
	public function testGetFormTypeReturnSymfonyType(): void
	{
		$aFormBlocks = InterfaceDiscovery::GetInstance()->FindItopClasses(iFormBlock::class);
		foreach ($aFormBlocks as $sFormBlock) {
			$oChoiceBlock = new ($sFormBlock)($sFormBlock);
			if ($oChoiceBlock instanceof AbstractTypeFormBlock) {
				$oClass = new \ReflectionClass($oChoiceBlock->GetFormType());
				$this->assertTrue($oClass->isSubclassOf(AbstractType::class));
			}
		}
	}

	/**
	 * Pass a Symfony type instead of a FormBlock type will raise an exception
	 *
	 * @throws ReflectionException
	 */
	public function testAddChildBlockClass(): void
	{
		$oFormBlock = new FormBlock('formBlock');
		$this->expectException(FormBlockException::class);
		$oFormBlock->Add('wrong', TextType::class, []);
	}

	/**
	 * All block may contain a reference to themselves in their options
	 */
	public function testBlockOptionsContainsBlockReference(): void
	{
		$aFormBlocks = InterfaceDiscovery::GetInstance()->FindItopClasses(iFormBlock::class);
		foreach ($aFormBlocks as $sFormBlock) {
			$oChoiceBlock = new ($sFormBlock)($sFormBlock);
			$this->assertTrue($oChoiceBlock->GetOption('form_block') === $oChoiceBlock);
		}
	}

	/**
	 * Check that a block with dependencies return true for HasDependenciesBlocks.
	 *
	 * @return void
	 * @throws FormBlockException
	 * @throws ReflectionException
	 */
	public function testCheckDependencyState(): void
	{
		$oFormBlock = new FormBlock('formBlock');
		$oFormBlock->Add('allow_age', CheckboxFormBlock::class, []);
		$oBirthdateBlock = $oFormBlock->Add('birthdate', TextFormBlock::class, [])
			->DependsOn(AbstractTypeFormBlock::INPUT_VISIBLE, 'allow_age', CheckboxFormBlock::OUTPUT_CHECKED);

		$this->assertTrue($oBirthdateBlock->HasDependenciesBlocks());
	}

	/**
	 * Dependent fields are not added to the form directly.
	 *
	 * @return void
	 * @throws FormBlockException
	 * @throws ReflectionException
	 */
	public function testFormBlockNotContainsDependentFields(): void
	{
		// form with a dependent field
		$oFormBlock = new FormBlock('formBlock');
		$oFormBlock->Add('firstname', TextFormBlock::class, []);
		$oFormBlock->Add('lastname', TextFormBlock::class, []);
		$oFormBlock->Add('allow_age', CheckboxFormBlock::class, []);
		$oFormBlock->Add('birthdate', TextFormBlock::class, [])
			->DependsOn(AbstractTypeFormBlock::INPUT_VISIBLE, 'allow_age', CheckboxFormBlock::OUTPUT_CHECKED);

		// form builder
		$oFormFactoryBuilder = Forms::createFormFactoryBuilder();
		$oForm = $oFormFactoryBuilder->getFormFactory()->createNamedBuilder($oFormBlock->GetName(), $oFormBlock->GetFormType(), [], $oFormBlock->GetOptions())->getForm();

		// try to get the dependent field
		$this->expectException(OutOfBoundsException::class);
		$oForm->get('birthdate');
	}

	public function testAddingTwiceTheSameInputThrowsException(): void
	{
		$oFormBlock = $this->GivenFormBlock('OneBlock')
			->AddInput('test_input', StringIOFormat::class);

		$this->expectException(RegisterException::class);
		$oFormBlock->AddInput('test_input', StringIOFormat::class);
	}

	public function testAddingTwiceTheSameOutputThrowsException(): void
	{
		$oFormBlock = $this->GivenFormBlock('OneBlock')
			->AddOutput('test_output', StringIOFormat::class);

		$this->expectException(RegisterException::class);
		$oFormBlock->AddOutput('test_output', StringIOFormat::class);
	}

	public function testDependingOnNonExistingInputThrowsException(): void
	{
		$oParentBlock = $this->GivenFormBlock('ParentBlock');

		$oFormBlock = $this->GivenSubFormBlock($oParentBlock, 'OneBlock')
			->AddInput('test_input', StringIOFormat::class);

		$this->GivenSubFormBlock($oParentBlock, 'OtherBlock')
			->AddOutput('test_output', StringIOFormat::class);

		$this->expectException(RegisterException::class);
		$oFormBlock->DependsOn('non_existing_input', 'OtherBlock', 'test_output');
	}

	public function testDependingOnNonExistingOutputThrowsException(): void
	{
		$oParentBlock = $this->GivenFormBlock('ParentBlock');
		$oFormBlock = $this->GivenSubFormBlock($oParentBlock, 'OneBlock')
			->AddInput('test_input', StringIOFormat::class);
		$this->GivenSubFormBlock($oParentBlock, 'OtherBlock')
			->AddOutput('test_output', StringIOFormat::class);

		$this->expectException(RegisterException::class);
		$oFormBlock->DependsOn('test_input', 'OtherBlock', 'non_existing_output');
	}

	public function testDependingOnNonExistingBlockThrowsException(): void
	{
		$oFormBlock = $this->GivenFormBlock('OneBlock')
			->AddOutput('test_output', StringIOFormat::class);

		$this->expectException(RegisterException::class);
		$oFormBlock->DependsOn('test_input', 'UnknownBlock', 'test');
	}
}
