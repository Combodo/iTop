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
	public const OUTPUT_RESULT = "result";
	public const OUTPUT_NOT_RESULT = "not_result";

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_RESULT, BooleanIOFormat::class);
		$oIORegister->AddOutput(self::OUTPUT_NOT_RESULT, BooleanIOFormat::class);
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
		$this->GetOutput(self::OUTPUT_RESULT)->SetValue($sEventType, new BooleanIOFormat($bResult));
		$this->GetOutput(self::OUTPUT_NOT_RESULT)->SetValue($sEventType, new BooleanIOFormat(!$bResult));

		return $oResult;
	}

}
