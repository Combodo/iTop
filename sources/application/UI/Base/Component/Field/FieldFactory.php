<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Field;


use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\AbstractInput;

/**
 * @since 3.0.0
 */
class FieldFactory
{
	public static function MakeFromParams($aParams)
	{
		$oValue = new Html($aParams['value']);
		$oField = new Field($aParams['label'], $oValue);

		$aParamsMapping = [
			'layout' => 'SetLayout',
			'attcode' => 'SetAttCode',
			'atttype' => 'SetAttType',
			'attlabel' => 'SetAttLabel',
			'value_raw' => 'SetValueRaw',
			'comments' => 'SetComments',
		];
		foreach ($aParamsMapping as $sParamKey => $sFieldMethod) {
			self::UpdateFieldFromParams($oField, $sFieldMethod, $aParams, $sParamKey);
		}

		if (isset($aParams['attflags'])) {
			$aParamsFlagsMapping = [
				OPT_ATT_HIDDEN => 'SetIsHidden',
				OPT_ATT_READONLY => 'SetIsReadOnly',
				OPT_ATT_MANDATORY => 'SetIsMandatory',
				OPT_ATT_MUSTCHANGE => 'SetMustChange',
				OPT_ATT_MUSTPROMPT => 'SetMustPrompt',
				OPT_ATT_SLAVE => 'SetIsSlave',
			];
			foreach ($aParamsFlagsMapping as $sConstant => $sFieldMethod) {
				self::UpdateFlagsFieldFromParams($oField, $sFieldMethod, $aParams['attflags'], $sConstant);
			}
		}

		return $oField;
	}

	private static function UpdateFieldFromParams($oField, $sMethodName, $aParams, $sKey): void
	{
		if (isset($aParams[$sKey])) {
			$oField->$sMethodName($aParams[$sKey]);
		}
	}

	private static function UpdateFlagsFieldFromParams($oField, $sMethodName, $iParamsFlags, $iConstant): void
	{
		$oField->$sMethodName(($iParamsFlags & $iConstant) === $iConstant);
	}

	public static function MakeFromObject(string $sLabel, AbstractInput $oInput, ?string $sLayout = null)
	{
		$oField = new Field($sLabel, $oInput);

		if (!is_null($sLayout)) {
			$oField->SetLayout($sLayout);
		}

		return $oField;
	}
}