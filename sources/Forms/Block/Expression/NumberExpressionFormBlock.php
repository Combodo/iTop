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
 * A block to manage an number expression.
 * This block expose a number output: the result of the expression.
 *
 * @package Combodo\iTop\Forms\Block\Expression
 * @since 3.3.0
 */
class NumberExpressionFormBlock extends AbstractExpressionFormBlock
{
	// Outputs
	public const OUTPUT_RESULT = "result";

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_RESULT, NumberIOFormat::class);
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
		$this->GetOutput(self::OUTPUT_RESULT)->SetValue($sEventType, new NumberIOFormat($oResult));

		return $oResult;
	}

}
