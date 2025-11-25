<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Expression;

use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\IO\Format\NumberIOFormat;
use Combodo\iTop\Forms\Register\IORegister;

/**
 *
 */
class IntegerExpressionFormBlock extends AbstractExpressionFormBlock
{
	// Outputs
	public const OUTPUT_NUMBER = "number";

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_NUMBER, NumberIOFormat::class);
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
		$iVal = intval($oResult);
		$this->GetOutput(self::OUTPUT_NUMBER)->SetValue($sEventType, new NumberIOFormat($iVal));

		return $oResult;
	}

}
