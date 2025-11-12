<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Expression;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\Block\IO\Format\RawFormat;
use Combodo\iTop\Forms\FormsException;
use IssueLog;
use Symfony\Component\Form\FormEvents;

/**
 *
 */
class ExpressionFormBlock extends AbstractFormBlock
{
	public const EXPRESSION_PATTERN = "/\[\[(?<input>[^\]]+)]]/";

	// Outputs
	const OUTPUT_RESULT = "result";
	const OUTPUT_RESULT_INVERT = "result_invert";

	/** @inheritdoc */
	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(self::OUTPUT_RESULT, BooleanIOFormat::class);
		$this->AddOutput(self::OUTPUT_RESULT_INVERT, BooleanIOFormat::class);
	}


	/** @inheritdoc */
	public function AllInputsReadyEvent(): void
	{
		$this->ComputeExpression(FormEvents::POST_SET_DATA);
		$this->ComputeExpression(FormEvents::POST_SUBMIT);
	}

	/**
	 * Compute the expression and set the output values.
	 *
	 * @param string $sEventType
	 *
	 * @return void
	 */
	public function ComputeExpression(string $sEventType): void
	{
		try{
			$sExpression = $this->GetOptions()['expression'];

			$sValue = preg_replace_callback(
				self::EXPRESSION_PATTERN,
				function(array $aMatches) use ($sEventType): ?string {
					$oInput = $this->GetInput($aMatches['input']);
					if(!$oInput->HasEventValue($sEventType)){
						throw new FormsException('Unable to compute expression: input '.$aMatches['input'].' has no value for event '.$sEventType.'.');
					}
					return $oInput->GetValue($sEventType);
				},
				$sExpression);

			$result  = '';
			eval('$result = '.$sValue.';');

			$this->GetOutput(self::OUTPUT_RESULT)->SetValue($sEventType, new BooleanIOFormat($result));
			$this->GetOutput(self::OUTPUT_RESULT_INVERT)->SetValue($sEventType, new BooleanIOFormat(!$result));
			$this->GetOutput(self::OUTPUT_VALUE)->SetValue($sEventType, new RawFormat($result));
		}
		catch(\Exception $e){
			IssueLog::Exception('Compute expression block issue', $e);
		}
	}

}