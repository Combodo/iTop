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

class UIInputNode extends Node
{
	public function __construct($sType, $oParams, $lineno = 0, $tag = null)
	{
		parent::__construct([], ['type' => $sType, 'params' => $oParams], $lineno, $tag);
	}

	public function compile(Compiler $compiler)
	{
		$sBlockVar = UIBlockHelper::GetBlockVarName('oInput');
		$oParams = $this->getAttribute('params');
		$compiler
			->addDebugInfo($this)
			->write("\$aParams = ")
			->subcompile($oParams)
			->raw(";\n");

		$sFactoryType = $this->getAttribute('type');
		switch ($sFactoryType) {
			case 'ForHidden':
				$compiler
					->write("\$sName = \$aParams['name'] ?? '';\n")
					->write("\$sValue = \$aParams['value'] ?? '';\n")
					->write("\$sId = \$aParams['id'] ?? null;\n")
					->write("\${$sBlockVar} = Combodo\\iTop\\Application\\UI\\Base\\Component\\Input\\InputFactory::MakeForHidden(\$sName, \$sValue, \$sId);\n");
				break;

			case 'Standard':
				$compiler
					->write("\$sType = \$aParams['type'] ?? '';\n")
					->write("\$sName = \$aParams['name'] ?? '';\n")
					->write("\$sValue = \$aParams['value'] ?? '';\n")
					->write("\$sId = \$aParams['id'] ?? null;\n")
					->write("\${$sBlockVar} = Combodo\\iTop\\Application\\UI\\Base\\Component\\Input\\InputFactory::MakeStandard(\$sType, \$sName, \$sValue, \$sId);\n")
					->write("if (\$aParams['checked'] ?? false) {\n")
					->indent()
					->write("\${$sBlockVar}->SetChecked(true);\n")
					->outdent()
					->write("}\n");
				break;

			// TODO 3.0 add other Factory methods

			default:
				throw new SyntaxError(sprintf('%s: Bad type "%s" for %s at line %d', $this->getTemplateName(), $sFactoryType, $this->tag, $this->lineno), $this->lineno, $this->getSourceContext());

		}
		$compiler->write(UIBlockHelper::AddToParentBlock($sBlockVar));
	}
}
