<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\IO\Format\AttributeIOFormat;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;
use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use Exception;

/**
 * A block to choose some values from attribute of a given class.
 *
 * @package Combodo\iTop\Forms\Block\DataModel
 * @since 3.3.0
 */
class AttributeValueChoiceFormBlock extends ChoiceFormBlock
{
	// inputs
	public const INPUT_CLASS_NAME = 'class';
	public const INPUT_ATTRIBUTE  = 'attribute';

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOptionArrayValue('attr', 'size', 5);
		$oOptionsRegister->SetOptionArrayValue('attr', 'style', 'height: auto;');
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
		$oIORegister->AddInput(self::INPUT_ATTRIBUTE, AttributeIOFormat::class, true);
	}

	/**
	 * @inheritdoc
	 *
	 * @throws FormBlockException
	 */
	public function UpdateOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::UpdateOptions($oOptionsRegister);

		$sClass = strval($this->GetInputValue(self::INPUT_CLASS_NAME));
		$sAttCode = strval($this->GetInputValue(self::INPUT_ATTRIBUTE));

		try {
			/** @var \ModelReflection $oModelReflection */
			$oModelReflection = ServiceLocator::GetInstance()->get('ModelReflection');
			$aValues = $oModelReflection->GetAllowedValues_att($sClass, $sAttCode);

			$oOptionsRegister->SetOption('choices', array_flip($aValues ?? []));
		} catch (Exception $e) {
			//			throw new FormBlockException('Update option failed for '.json_encode($this->GetName()), 0, $e);
		}
	}

}
