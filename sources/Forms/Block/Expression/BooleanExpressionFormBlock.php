<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Expression;

use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\Register\IORegister;

/**
 *
 */
class BooleanExpressionFormBlock extends AbstractExpressionFormBlock
{
	// Outputs
	public const OUTPUT_CONDITION_MET = "condition_met";
	public const OUTPUT_CONDITION_UNMET = "condition_unmet";

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_CONDITION_MET, BooleanIOFormat::class);
		$oIORegister->AddOutput(self::OUTPUT_CONDITION_UNMET, BooleanIOFormat::class);
	}

	/**
	 * Compute the expression and set the output values.
	 *
	 * @param string $sEventType
	 *
	 * @return mixed
	 * @throws FormBlockException
	 */
	public function ComputeExpression(string $sEventType): mixed
	{
		$oResult = parent::ComputeExpression($sEventType);

		// Update output
		$bResult = boolval($oResult);
		$this->GetOutput(self::OUTPUT_CONDITION_MET)->SetValue($sEventType, new BooleanIOFormat($bResult));
		$this->GetOutput(self::OUTPUT_CONDITION_UNMET)->SetValue($sEventType, new BooleanIOFormat(!$bResult));

		return $oResult;
	}

}
