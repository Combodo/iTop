<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI\Component;

use Combodo\iTop\Application\TwigBase\UI\UIBlockHelper;
use Twig\Compiler;
use Twig\Node\Node;

class UIHtmlNode extends Node
{
	public function __construct($oBody, $lineno = 0, $tag = null)
	{
		parent::__construct(['body' => $oBody], [], $lineno, $tag);
	}

	public function compile(Compiler $compiler)
	{
		$compiler
			->addDebugInfo($this)
			->write("ob_start();\n")
			->subcompile($this->getNode('body'))
			->write("\$sHtml = ob_get_contents();\n")
			->write("ob_end_clean();\n")
			->write(UIBlockHelper::GetParentBlock()."->AddSubBlock(new Combodo\\iTop\\Application\\UI\\Base\\Component\\Html\\Html(\$sHtml));\n");
	}
}