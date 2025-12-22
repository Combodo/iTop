<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Expression;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Exception;
use Expression;
use Symfony\Component\Form\FormEvents;

/**
 * An abstract block to manage an expression.
 *
 * @package Combodo\iTop\Forms\Block\Expression
 * @since 3.3.0
 */
abstract class AbstractExpressionFormBlock extends AbstractFormBlock
{
	public const EXPRESSION_PATTERN = "/:(?<input>\w+)/";

	/** @inheritdoc
	 * @throws FormBlockException
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
	 * @return mixed
	 * @throws FormBlockException
	 */
	public function ComputeExpression(string $sEventType): mixed
	{
		$sExpression = $this->GetOption('expression');
		try {
			$oExpression = Expression::FromOQL($sExpression);
			$aFieldsToResolve = $oExpression->ListRequiredFields();
			$aResolvedParams = [];
			foreach ($aFieldsToResolve as $sFieldToResolve) {
				$aResolvedParams[$sFieldToResolve] = strval($this->GetInputValue($sFieldToResolve));
			}
			return $oExpression->Evaluate($aResolvedParams);
		} catch (Exception $e) {
			throw new FormBlockException('Compute expression '.json_encode($sExpression).' block issue: '.$e->getMessage(), 0, $e);
		}
	}

}
