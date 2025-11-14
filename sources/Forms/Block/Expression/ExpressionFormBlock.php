<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Expression;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\IO\Format\RawFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Expression;
use Symfony\Component\Form\FormEvents;

/**
 *
 */
class ExpressionFormBlock extends AbstractFormBlock
{
	public const EXPRESSION_PATTERN = "/:(?<input>\w+)/";

	// Outputs
	const OUTPUT_RESULT = "result";
	const OUTPUT_RESULT_INVERT = "result_invert";

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_RESULT, BooleanIOFormat::class);
		$oIORegister->AddOutput(self::OUTPUT_RESULT_INVERT, BooleanIOFormat::class);
	}

	/** @inheritdoc
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 */
	public function AllInputsReadyEvent(): void
	{
		parent::AllInputsReadyEvent();
		$this->ComputeExpression(FormEvents::POST_SET_DATA);
		$this->ComputeExpression(FormEvents::POST_SUBMIT);
	}

	/**
	 * Compute the expression and set the output values.
	 *
	 * @param string $sEventType
	 *
	 * @return void
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 */
	public function ComputeExpression(string $sEventType): void
	{
		$sExpression = '';
		try{
			$sExpression = $this->GetOption('expression');

			$oExpression = Expression::FromOQL($sExpression);
			$aParamsToResolve = $oExpression->GetParameters();
			$aResolvedParams = [];
			foreach ($aParamsToResolve as $sParamToResolve) {
				$aResolvedParams[$sParamToResolve] = $this->GetInputValue($sParamToResolve);
			}
			$result = $oExpression->Evaluate($aResolvedParams);

			$bResult = boolval($result);
			$this->GetOutput(self::OUTPUT_RESULT)->SetValue($sEventType, new BooleanIOFormat($bResult));
			$this->GetOutput(self::OUTPUT_RESULT_INVERT)->SetValue($sEventType, new BooleanIOFormat(!$bResult));
			$this->GetOutput(self::OUTPUT_VALUE)->SetValue($sEventType, new RawFormat($result));
		}
		catch(\Exception $e){
			throw new FormBlockException('Compute expression '.json_encode($sExpression).' block issue', 0, $e);
		}
	}

}