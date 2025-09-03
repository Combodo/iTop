<?php

namespace Combodo\iTop\PhpParser\Evaluation;

abstract class AbstractExprEvaluator implements iExprEvaluator {
	public function GetHandledExpressionTypes(): ?array {
		return null;
	}
}
