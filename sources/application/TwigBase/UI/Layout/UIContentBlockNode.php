<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI\Component;


use Combodo\iTop\Application\TwigBase\UI\UIBlockHelper;
use Twig\Compiler;
use Twig\Node\Node;

class UIContentBlockNode extends Node
{
	public function __construct($sName, $sContainerClass, $oBody, $lineno = 0, $tag = null)
	{
		parent::__construct(['body' => $oBody], ['name' => $sName, 'container_class' => $sContainerClass], $lineno, $tag);
	}

	public function compile(Compiler $compiler)
	{
		$sBlockVar = UIBlockHelper::GetBlockVarName('oContentBlock');
		$sName = empty($this->getAttribute('name')) ? 'null' : "'".$this->getAttribute('name')."'";
		$sContainerClass = $this->getAttribute('container_class');
		$compiler
			->addDebugInfo($this)
			->write("\${$sBlockVar} = new Combodo\\iTop\\Application\\UI\\Base\\Layout\\UIContentBlock({$sName}, '{$sContainerClass}');\n")
			->write(UIBlockHelper::AddToParentBlock($sBlockVar))
			->write(UIBlockHelper::PushParentBlock($sBlockVar))
			->subcompile($this->getNode('body'))
			->write(UIBlockHelper::PopParentBlock());
	}
}
