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

class UIAlertNode extends Node
{
	public function __construct($sType, $oParams, $oBody, $lineno = 0, $tag = null)
	{
		parent::__construct(['body' => $oBody], ['type' => $sType, 'params' => $oParams], $lineno, $tag);
	}

	public function compile(Compiler $compiler)
	{
		$sBlockVar = UIBlockHelper::GetBlockVarName('oFieldSet');
		$oParams = $this->getAttribute('params');
		$compiler
			->addDebugInfo($this)
			->write("\$aParams = ")
			->subcompile($oParams)
			->raw(";\n")
			->write("\$sTitle = \$aParams['title'] ?? '';\n")
			->write("\$sId = \$aParams['id'] ?? null;\n")
			->write("ob_start();\n")
			->subcompile($this->getNode('body'))
			->write("\$sContent = ob_get_contents();\n")
			->write("ob_end_clean();\n");

		$sType = $this->getAttribute('type');
		switch ($sType) {
			case 'ForInformation':
			case 'Neutral':
			case 'ForSuccess':
			case 'ForWarning':
			case 'ForDanger':
			case 'ForFailure':
			case 'WithBrandingPrimaryColor':
			case 'WithBrandingSecondaryColor':
				$compiler
					->write("\${$sBlockVar} = Combodo\\iTop\\Application\\UI\\Base\\Component\\Alert\\AlertFactory::Make{$sType}(\$sTitle, \$sContent, \$sId);\n");
				break;
			// TODO 3.0 add other Factory methods

			default:
				throw new SyntaxError(sprintf('%s: Bad type "%s" for %s at line %d', $this->getTemplateName(), $sType, $this->tag, $this->lineno), $this->lineno, $this->getSourceContext());

		}
		$compiler->write(UIBlockHelper::AddToParentBlock($sBlockVar));

	}
}
