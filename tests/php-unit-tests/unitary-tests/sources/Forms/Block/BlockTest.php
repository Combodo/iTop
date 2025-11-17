<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use OutOfBoundsException;
use ReflectionException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Test forms block.
 *
 */
class BlockTest extends ItopDataTestCase
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
			$oChoiceBlock = new($sFormBlock)($sFormBlock);
			if($oChoiceBlock instanceof AbstractTypeFormBlock){
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
			$oChoiceBlock = new($sFormBlock)($sFormBlock);
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
}