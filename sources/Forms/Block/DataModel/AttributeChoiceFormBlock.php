<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\IO\Format\AttributeIOFormat;
use Combodo\iTop\Forms\IO\Format\AttributeTypeIOFormat;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;
use Combodo\iTop\Forms\Register\RegisterException;
use Combodo\iTop\Service\ServiceLocator\ServiceLocatorException;
use Combodo\iTop\Service\ServiceLocator\ServiceLocator;
use utils;

/**
 * A block to choose an attribute from a given class.
 *
 * @api
 * @package FormBlock
 * @since 3.3.0
 */
class AttributeChoiceFormBlock extends ChoiceFormBlock
{
	// inputs
	/**
	 * Class input reflecting the selected class
	 * @api
	 */
	public const INPUT_CLASS_NAME = 'class';
	/**
	 * String input for the category of attributes to select from
	 * @api
	 */
	public const INPUT_CATEGORY = 'category';

	// outputs
	/**
	 * String output of the selected attribute
	 * @api
	 */
	public const OUTPUT_ATTRIBUTE = 'attribute';

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOption('placeholder', 'Select an attribute...');
		$oOptionsRegister->SetOption('choices', []);
	}

	/** @inheritdoc
	 * @throws RegisterException
	 */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);

		$bMultiple = $this->GetOption('multiple');

		$oIORegister->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
		$oIORegister->AddInput(self::INPUT_CATEGORY, AttributeTypeIOFormat::class);
		// Default value
		$this->SetInputValue(self::INPUT_CATEGORY, '');
		$oIORegister->AddOutput(self::OUTPUT_ATTRIBUTE, AttributeIOFormat::class, $bMultiple);
	}

	/**
	 * @inheritdoc
	 *
	 * @throws ServiceLocatorException
	 * @throws FormBlockException
	 * @throws RegisterException
	 */
	public function UpdateOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::UpdateOptions($oOptionsRegister);

		// Get class name
		$sClass = strval($this->GetInputValue(self::INPUT_CLASS_NAME));
		$sCategory = strval($this->GetInputValue(self::INPUT_CATEGORY));

		// Empty class => no choices
		if (utils::IsNullOrEmptyString($sClass)) {
			$oOptionsRegister->SetOption('choices', []);
			return;
		}

		$aAttributeCodes = self::ListAttributeCodesByCategory($sClass, $sCategory);

		$oOptionsRegister->SetOption('choices', $aAttributeCodes);
	}

	/**
	 * @param string $sClass
	 * @param string|null $sCategory
	 *
	 * @return array
	 * @throws \Combodo\iTop\Service\ServiceLocator\ServiceLocatorException
	 */
	public static function ListAttributeCodesByCategory(string $sClass, string $sCategory = ''): array
	{
		/** @var \ModelReflection $oModelReflection */
		$oModelReflection = \MetaModel::GetService('ModelReflection');
		$aNonGroupableAttributes = [
			'AttributeLinkedSet',
			'AttributeFriendlyName',
			'iAttributeNoGroupBy', //we cannot only use iAttributeNoGroupBy since this method is also used by the designer who do not have access to the classes' PHP reflection API. So the known classes has to be listed altogether
			'AttributeOneWayPassword',
			'AttributeEncryptedString',
			'AttributePassword',
		];
		$aAttributeCodes = [];

		foreach ($oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType) {
			$sLabel = $oModelReflection->GetLabel($sClass, $sAttCode);

			// For external fields, find the real type of the target
			$sExtFieldAttCode = $sAttCode;
			$sTargetClass = $sClass;
			while (is_a($sAttType, 'AttributeExternalField', true)) {
				$sExtKeyAttCode = $oModelReflection->GetAttributeProperty($sTargetClass, $sExtFieldAttCode, 'extkey_attcode');
				$sTargetAttCode = $oModelReflection->GetAttributeProperty($sTargetClass, $sExtFieldAttCode, 'target_attcode');
				$sTargetClass = $oModelReflection->GetAttributeProperty($sTargetClass, $sExtKeyAttCode, 'targetclass');
				$aTargetAttCodes = $oModelReflection->ListAttributes($sTargetClass);
				$sAttType = $aTargetAttCodes[$sTargetAttCode];
				$sExtFieldAttCode = $sTargetAttCode;
			}

			switch ($sCategory) {
				case 'numeric':
					if (is_a($sAttType, 'AttributeDecimal', true) ||
						is_a($sAttType, 'AttributeDuration', true) ||
						is_a($sAttType, 'AttributeInteger', true) ||
						is_a($sAttType, 'AttributePercentage', true) ||
						is_a($sAttType, 'AttributeSubItem', true)) {
						$aAttributeCodes[$sLabel] = $sAttCode;
					}
					break;

				case 'groupable':
					foreach ($aNonGroupableAttributes as $sNonGroupableAttribute) {
						if (is_a($sAttType, $sNonGroupableAttribute, true)) {
							break;
						}
					}
					$aAttributeCodes[$sLabel] = $sAttCode;
					break;

				case 'enum':
					if (is_a($sAttType, 'AttributeEnum', true)) {
						$aAttributeCodes[$sLabel] = $sAttCode;
					}
					break;

				case 'date':
					if (is_a($sAttType, 'AttributeDateTime', true)) {
						$aAttributeCodes[$sLabel] = $sAttCode;
					}
					break;

				case 'link':
					if (is_a($sAttType, 'AttributeLinkedSet', true) ||
						is_a($sAttType, 'AttributeLinkedSetIndirect', true) ||
						is_a($sAttType, 'AttributeExternalKey', true) ||
						is_a($sAttType, 'AttributeHierarchicalKey', true)) {
						$aAttributeCodes[$sLabel] = $sAttCode;
					}
					break;

				case 'string':
					if (is_a($sAttType, 'AttributeString', true)) {
						$aAttributeCodes[$sLabel] = $sAttCode;
					}
					break;

				case 'all':
				case '':
					$aAttributeCodes[$sLabel] = $sAttCode;
					break;
			}
		}

		return $aAttributeCodes;
	}
}
