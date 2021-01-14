<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI\Component;


use Combodo\iTop\Application\TwigBase\UI\UIBlockHelper;
use Twig\Compiler;
use Twig\Error\SyntaxError;
use Twig\Node\Node;

class UIFieldNode extends Node
{
	public function __construct($sType, $oParams, $oBody, $lineno = 0, $tag = null)
	{
		parent::__construct(['body' => $oBody], ['type' => $sType, 'params' => $oParams], $lineno, $tag);
	}

	public function compile(Compiler $compiler)
	{
		$sBlockVar = UIBlockHelper::GetBlockVarName('oField');
		$oParams = $this->getAttribute('params');
		$compiler
			->addDebugInfo($this)
			->write("\$aParams = ")
			->subcompile($oParams)
			->raw(";\n");

		$sType = $this->getAttribute('type');
		switch ($sType) {
			case 'Small':
			case 'Large':
				$compiler
					->write("\$sLabel = \$aParams['label'] ?? '';\n")
					->write("\$sValueId = \$aParams['value_id'] ?? null;\n")
					->write("ob_start();\n")
					->subcompile($this->getNode('body'))
					->write("\$sValue = ob_get_contents();\n")
					->write("ob_end_clean();\n")
					->write("\${$sBlockVar} = Combodo\\iTop\\Application\\UI\\Base\\Component\\Field\\FieldFactory::Make{$sType}(\$sLabel, \$sValue);\n")
					->write("\${$sBlockVar}->SetValueId(\$sValueId);\n");
				break;
			// TODO 3.0 add other Factory methods

			default:
				throw new SyntaxError(sprintf('%s: Bad type "%s" for %s at line %d', $this->getTemplateName(), $sType, $this->tag, $this->lineno), $this->lineno, $this->getSourceContext());

		}
		$compiler->write(UIBlockHelper::AddToParentBlock($sBlockVar));

	}
}
