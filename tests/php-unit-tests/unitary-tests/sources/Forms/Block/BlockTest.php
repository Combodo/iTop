<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Sources\Forms\Block;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\Block\Base\CheckboxFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Block\Base\TextFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\Block\IFormBlock;
use Combodo\iTop\Forms\Forms;
use Combodo\iTop\ItopSdkFormDemonstrator\Form\Block\Dashboard\GenericDashlet;
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
				if (!$oChoiceBlock instanceof GenericDashlet) {
					$oClass = new \ReflectionClass($oChoiceBlock->GetFormType());
					$this->assertTrue($oClass->isSubclassOf(AbstractType::class));
				}
			}
		}
	}

	/**
	 * Pass a Symfony type instead of a FormBlock type will raise an exception
	 *
	 * @throws ReflectionException
	 */
	public function testAddChildBlockExpectFormBlockClass(): void
	{
		$oFormBlock = new FormBlock('formBlock');
		$this->expectException(FormBlockException::class);
		$oFormBlock->Add('wrong', TextType::class, []);
	}

	/**
	 * All block must contain a reference to themselves in their options
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
			->InputDependsOn(AbstractTypeFormBlock::INPUT_VISIBLE, 'allow_age', CheckboxFormBlock::OUTPUT_CHECKED);

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
			->InputDependsOn(AbstractTypeFormBlock::INPUT_VISIBLE, 'allow_age', CheckboxFormBlock::OUTPUT_CHECKED);

		// form builder
		$oFormFactoryBuilder = Forms::createFormFactoryBuilder();
		$oForm = $oFormFactoryBuilder->getFormFactory()->createNamedBuilder($oFormBlock->GetName(), $oFormBlock->GetFormType(), [], $oFormBlock->GetOptions())->getForm();

		// try to get the dependent field
		$this->expectException(OutOfBoundsException::class);
		$oForm->get('birthdate');
	}

	public function testIsRootBlock(): void
	{
		/** @var FormBlock $oFormBlock */
		$oFormBlock = $this->GivenFormBlock('OneBlock');

		$oFormBlock->Add('subform', FormBlock::class);

		$this->assertTrue($oFormBlock->IsRootBlock());
		$this->assertFalse($oFormBlock->Get('subform')->IsRootBlock());
	}
}
