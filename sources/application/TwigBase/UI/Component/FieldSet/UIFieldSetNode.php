<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI\Component;


use Combodo\iTop\Application\TwigBase\UI\UIBlockHelper;
use Twig\Compiler;
use Twig\Node\Node;

class UIFieldSetNode extends Node
{
	public function __construct($oParams, $oBody, $lineno = 0, $tag = null)
	{
		parent::__construct(['body' => $oBody], ['params' => $oParams], $lineno, $tag);
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
			->write("\$sLegend = \$aParams['legend'] ?? '';\n")
			->write("\$sName = \$aParams['value'] ?? null;\n")
			->write("\${$sBlockVar} = new Combodo\\iTop\\Application\\UI\\Base\\Component\\FieldSet\\FieldSet(\$sLegend, \$sName);\n")
			->write(UIBlockHelper::AddToParentBlock($sBlockVar))
			->write(UIBlockHelper::PushParentBlock($sBlockVar))
			->subcompile($this->getNode('body'))
			->write(UIBlockHelper::PopParentBlock());
	}
}
