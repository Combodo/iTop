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

class UIDataTableNode extends Node
{
	public function __construct($sType, $oParams, $lineno = 0, $tag = null)
	{
		parent::__construct([], ['type' => $sType, 'params' => $oParams], $lineno, $tag);
	}

	public function compile(Compiler $compiler)
	{
		$sBlockVar = UIBlockHelper::GetBlockVarName('oDataTable');
		$oParams = $this->getAttribute('params');
		$sType = $this->getAttribute('type');
		$compiler
			->addDebugInfo($this)
			->write("\$aParams = ")
			->subcompile($oParams)
			->raw(";\n");

		switch ($sType) {
			case 'ForResult':
				$compiler
					->write("\$sListId = \$aParams['list_id'] ?? '';\n")
					->write("\$oSet = \$aParams['object_set'] ?? null;\n")
					->write("\${$sBlockVar} = Combodo\\iTop\\Application\\UI\\Base\\Component\\DataTable\\DataTableFactory::Make{$sType}(\$context['UIBlockParent'][0], \$sListId, \$oSet);\n")
					->write(UIBlockHelper::AddToParentBlock($sBlockVar));
				break;
			// TODO 3.0 add other Factory methods

			default:
				throw new SyntaxError(sprintf('%s: Bad type "%s" for %s at line %d', $this->getTemplateName(), $sType, $this->tag, $this->lineno), $this->lineno, $this->getSourceContext());
		}
	}
}
