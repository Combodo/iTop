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
use Combodo\iTop\Service\DependencyInjection\DIException;
use Combodo\iTop\Service\DependencyInjection\DIService;
use utils;

/**
 * A block to choose an attribute from a given class.
 *
 * @package Combodo\iTop\Forms\Block\DataModel
 * @since 3.3.0
 */
class AttributeChoiceFormBlock extends ChoiceFormBlock
{
	// inputs
	public const INPUT_CLASS_NAME = 'class';
	public const INPUT_CATEGORY = 'category';

	// outputs
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
	 * @throws DIException
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
	 * @throws \Combodo\iTop\Service\DependencyInjection\DIException
	 */
	public static function ListAttributeCodesByCategory(string $sClass, string $sCategory = ''): array
	{
		$oModelReflection = DIService::GetInstance()->GetService('ModelReflection');
		$aAttributeCodes = [];

		switch ($sCategory) {
			case 'numeric':
				foreach ($oModelReflection->ListAttributes($sClass, 'AttributeDecimal,AttributeDuration,AttributeInteger,AttributePercentage,AttributeSubItem') as $sAttCode => $sAttType) {
					$sLabel = $oModelReflection->GetLabel($sClass, $sAttCode);
					$aAttributeCodes[$sLabel] = $sAttCode;
				}
				break;

			case 'groupable':
				$aForbiddenAttType = [
					'AttributeLinkedSet',
					'AttributeFriendlyName',
					'iAttributeNoGroupBy', //we cannot only use iAttributeNoGroupBy since this method is also used by the designer who do not have access to the classes' PHP reflection API. So the known classes has to be listed altogether
					'AttributeOneWayPassword',
					'AttributeEncryptedString',
					'AttributePassword',
				];
				foreach ($oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType) {
					foreach ($aForbiddenAttType as $sForbiddenAttType) {
						if (is_a($sAttType, $sForbiddenAttType, true)) {
							continue 2;
						}
					}
					$sLabel = $oModelReflection->GetLabel($sClass, $sAttCode);
					$aAttributeCodes[$sLabel] = $sAttCode;
				}
				break;

			case 'enum':
				foreach ($oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType) {
					if (is_a($sAttType, 'AttributeEnum', true)) {
						$sLabel = $oModelReflection->GetLabel($sClass, $sAttCode);
						$aAttributeCodes[$sLabel] = $sAttCode;
					}
				}
				break;

			case 'date':
				foreach ($oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType) {
					if (is_a($sAttType, 'AttributeDateTime', true)) {
						$sLabel = $oModelReflection->GetLabel($sClass, $sAttCode);
						$aAttributeCodes[$sLabel] = $sAttCode;
					}
				}
				break;

			case '':
				foreach ($oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType) {
					$sLabel = $oModelReflection->GetLabel($sClass, $sAttCode);
					$aAttributeCodes[$sLabel] = $sAttCode;
				}
				break;
		}

		return $aAttributeCodes;
	}
}
