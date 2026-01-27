<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Expression;

use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\Register\IORegister;

/**
 *  A block to manage a string expression.
 *  This block expose a string output: the result of the expression.
 *
 * @api
 * @package FormBlock
 * @since 3.3.0
 */
class StringExpressionFormBlock extends AbstractExpressionFormBlock
{
	// Outputs

	/**
	 * String output of the result of the expression
	 * @api
	 */
	public const OUTPUT_RESULT = 'result';

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_RESULT, StringIOFormat::class);
	}

	/**
	 * Compute the expression and set the output values.
	 *
	 * @param string $sEventType
	 *
	 * @return mixed
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 * @throws \Combodo\iTop\Forms\Register\RegisterException
	 */
	public function ComputeExpression(string $sEventType): mixed
	{
		$oResult = parent::ComputeExpression($sEventType);

		// Update output
		$this->GetOutput(self::OUTPUT_RESULT)->SetValue($sEventType, new StringIOFormat($oResult));

		return $oResult;
	}
}
