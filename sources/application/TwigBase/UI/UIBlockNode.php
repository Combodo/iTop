<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI;


use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Twig\Compiler;
use Twig\Error\SyntaxError;
use Twig\Node\Node;
use utils;

class UIBlockNode extends Node
{
	/** @var string */
	protected $sFactoryClass;
	/** @var string */
	protected $sBlockClass;

	public function __construct($sFactoryClass, $sBlockClass, $sType, $oParams, $oBody, $lineno = 0, $tag = null)
	{
		parent::__construct(['body' => $oBody], ['type' => $sType, 'params' => $oParams], $lineno, $tag);
		$this->sFactoryClass = $sFactoryClass;
		$this->sBlockClass = $sBlockClass;
	}

	public function compile(Compiler $compiler)
	{
		$aClassPath = explode("\\", $this->sBlockClass);
		$sClassName = end($aClassPath);
		$sBlockVar = str_replace('.', '', uniqid('o'.$sClassName.'_', true));
		$oParams = $this->getAttribute('params');
		$compiler
			->addDebugInfo($this)
			->write("\$sHtml = trim(ob_get_contents());\n")
			->write("ob_end_clean();\n")
			->write("if (strlen(\$sHtml) > 0) {\n")
			->indent()->write("end(\$context['UIBlockParent'])->AddSubBlock(new \Combodo\iTop\Application\UI\Base\Component\Html\Html(\$sHtml));\n")->outdent()
			->write("}\n")			->write("\$aParams = ")
			->subcompile($oParams)
			->raw(";\n");

		// Get factory and method to call
		$sType = $this->getAttribute('type');
		$oReflectionClass = new ReflectionClass($this->sFactoryClass);
		try {
			$oMethod = $oReflectionClass->getMethod("Make{$sType}");
		} catch (ReflectionException $e) {
			throw new SyntaxError(sprintf('%s: Bad type "%s" for %s at line %d', $this->getTemplateName(), $sType, $this->getNodeTag(), $this->getTemplateLine()), $this->getTemplateLine(), $this->getSourceContext());
		}
		if (!$oMethod->isPublic() || !$oMethod->isStatic()) {
			throw new SyntaxError(sprintf('%s: Bad type "%s" for %s at line %d', $this->getTemplateName(), $sType, $this->getNodeTag(), $this->getTemplateLine()), $this->getTemplateLine(), $this->getSourceContext());
		}
		$aParameters = $oMethod->getParameters();
		foreach ($aParameters as $oParameter) {
			$sName = $oParameter->getName();
			if ($oParameter->isOptional()) {
				$sDefault = $oParameter->getDefaultValue();
				$sDefault = var_export($sDefault, true);
				$compiler->write("\${$sName} = \$aParams['{$sName}'] ?? {$sDefault};\n");
			} else {
				$compiler
					->write("if (!isset(\$aParams['{$sName}'])) {\n")
					->indent()->write("throw new Exception('{$this->getTemplateName()}: Missing parameter {$sName} for {$this->getNodeTag()} at line {$this->getTemplateLine()}');\n")->outdent()
					->write("}\n")
					->write("\${$sName} = \$aParams['{$sName}'];\n");
			}
		}

		// Call the factory
		$compiler->write("\${$sBlockVar} = {$this->sFactoryClass}::Make{$sType}(");
		$bIsFirst = true;
		foreach ($aParameters as $oParameter) {
			$sName = $oParameter->getName();
			if ($bIsFirst) {
				$bIsFirst = false;
			} else {
				$compiler->write(", ");
			}
			$compiler->write("\${$sName}");
		}
		$compiler->write(");\n");

		// Call the setters if exists
		$aSetters = [];
		$oRefClass = new ReflectionClass($this->sBlockClass);
		$aMethods = $oRefClass->getMethods(ReflectionMethod::IS_PUBLIC);
		foreach ($aMethods as $oMethod) {
			if (!$oMethod->isStatic() && utils::StartsWith($oMethod->getName(), 'Set')) {
				$aSetters[] = substr($oMethod->getName(), 3); // remove 'Set' to get the variable name
			}
		}
		foreach ($aSetters as $sSetter) {
			$compiler
				->write("if (isset(\$aParams['{$sSetter}'])) {\n")
				->indent()->write("\${$sBlockVar}->Set{$sSetter}(\$aParams['{$sSetter}']);\n")->outdent()
				->write("}\n");
		}

		// Attach to parent UIBlock
		$compiler->write("end(\$context['UIBlockParent'])->AddSubBlock(\${$sBlockVar});\n");

		// Add sub UIBlocks
		$oSubNode = $this->getNode('body');
		if ($oSubNode) {
			$compiler
				->write("array_push(\$context['UIBlockParent'], \${$sBlockVar});\n")
				->write("ob_start();\n")
				->subcompile($oSubNode)
				->write("\$sHtml = trim(ob_get_contents());\n")
				->write("ob_end_clean();\n")
				->write("if (strlen(\$sHtml) > 0) {\n")
				->indent()->write("end(\$context['UIBlockParent'])->AddSubBlock(new \Combodo\iTop\Application\UI\Base\Component\Html\Html(\$sHtml));\n")->outdent()
				->write("}\n")
				->write("array_pop(\$context['UIBlockParent']);\n");
		}
		$compiler->write("ob_start();\n");
	}
}