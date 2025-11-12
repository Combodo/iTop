<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\ItopSdkFormDemonstrator\Form\Block;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\Block\IO\Format\RawFormat;
use Combodo\iTop\Forms\FormsException;
use IssueLog;
use Symfony\Component\Form\FormEvents;

class ExpressionFormBlock extends AbstractFormBlock
{
	const OUTPUT_RESULT = "result";

	public function InitBlockOptions(array &$aUserOptions): void
	{
		parent::InitBlockOptions($aUserOptions);
	}

	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(self::OUTPUT_RESULT, BooleanIOFormat::class);
//		$this->AddOutput(self::OUTPUT_RAW, RawFormat::class);
	}

//	public function InputHasChanged()
//	{
//		if (!$this->IsInputsDataReady()) {
//			return;
//		}
//		$sExpression = $this->GetOptions()['expression'];
//		$sValue = preg_replace_callback(
//			"/\[\[(?<input>[^\]]+)]]/",
//			function(array $aMatches): string {
//				return $this->GetInput($aMatches['input'])->GetValue();
//			},
//			$sExpression);
//
//		foreach ($this->GetInputs() as $oFormInput) {
//			IssueLog::Info($oFormInput->GetName().' = '.$oFormInput->GetValue());
//		}
//		IssueLog::Info("Result of [$sExpression] is [$sValue]");
//
//		$result  = '';
//		eval('$result = '.$sValue.';');
//		IssueLog::Info("Result of [$sExpression] is eval to [$result]");
//
//		$this->GetOutput(self::OUTPUT_RESULT)->SetValue(FormEvents::POST_SUBMIT, new BooleanIOFormat($result));
//		$this->GetOutput(self::OUTPUT_VALUE)->SetValue(FormEvents::POST_SUBMIT, new RawFormat($result));
//	}



	public function InputHasChanged()
	{
		if (!$this->IsInputsDataReady()) {
			return;
		}
		$sExpression = $this->GetOptions()['expression'];

		$this->Compute($sExpression, FormEvents::POST_SET_DATA);
		$this->Compute($sExpression, FormEvents::POST_SUBMIT);
	}

	public function Compute(string $sExpression, string $sEventType): void
	{
		try{
			$sValue = preg_replace_callback(
				"/\[\[(?<input>[^\]]+)]]/",
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
			$this->GetOutput(self::OUTPUT_VALUE)->SetValue($sEventType, new RawFormat($result));
		}
		catch(\Exception $e){
			IssueLog::Exception('Compute expression block issue', $e);
		}
	}

}