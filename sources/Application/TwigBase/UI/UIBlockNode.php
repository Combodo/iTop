<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI;


use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Twig\Compiler;
use Twig\Error\SyntaxError;
use Twig\Node\Node;
use utils;

/**
 * Class UIBlockNode
 *
 * @package Combodo\iTop\Application\TwigBase\UI
 * @author  Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 */
class UIBlockNode extends Node
{
	/** @var string */
	protected $sFactoryClass;
	/** @var string */
	protected $sBlockClass;

	/**
	 * @inheritDoc
	 */
	public function __construct(string $sFactoryClass, string $sBlockClass, string $sType, $oParams, $oBody, int $iLineNo = 0, ?string $sTag = null)
	{
		$aNodes =  is_null($oBody) ? [] : ['body' => $oBody];
		parent::__construct($aNodes, ['type' => $sType, 'params' => $oParams], $iLineNo, $sTag);
		$this->sFactoryClass = $sFactoryClass;
		$this->sBlockClass = $sBlockClass;
	}

	/**
	 * @inheritDoc
	 */
	public function compile(Compiler $oCompiler)
	{
		$aClassPath = explode("\\", $this->sBlockClass);
		$sClassName = end($aClassPath);
		$sBlockVar = str_replace('.', '', uniqid('o'.$sClassName.'_', true));
		$oParams = $this->getAttribute('params');
		$oCompiler
			->addDebugInfo($this)
			->write("\$sHtml = trim(ob_get_contents());\n")
			->write("ob_end_clean();\n")
			->write("if (strlen(\$sHtml) > 0) {\n")
			->indent()->write("end(\$context['UIBlockParent'])->AddSubBlock(new \Combodo\iTop\Application\UI\Base\Component\Html\Html(\$sHtml));\n")->outdent()
			->write("}\n")
			->write("\$aParams = ")
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
				$oCompiler->write("\${$sName} = \$aParams['{$sName}'] ?? {$sDefault};\n");
			} else {
				$oCompiler
					->write("if (!isset(\$aParams['{$sName}'])) {\n")
					->indent()->write("throw new Exception('{$this->getTemplateName()}: Missing parameter {$sName} for {$this->getNodeTag()} at line {$this->getTemplateLine()}');\n")->outdent()
					->write("}\n")
					->write("\${$sName} = \$aParams['{$sName}'];\n");
			}
		}

		// Call the factory
		$oCompiler->write("\${$sBlockVar} = {$this->sFactoryClass}::Make{$sType}(");
		$bIsFirst = true;
		foreach ($aParameters as $oParameter) {
			$sName = $oParameter->getName();
			if ($bIsFirst) {
				$bIsFirst = false;
			} else {
				$oCompiler->write(", ");
			}
			$oCompiler->write("\${$sName}");
		}
		$oCompiler->write(");\n");

		// Call the setters if exists
		$aSetters = [];
		$aAdders = [];
		$oRefClass = new ReflectionClass($this->sBlockClass);
		$aMethods = $oRefClass->getMethods(ReflectionMethod::IS_PUBLIC);
		foreach ($aMethods as $oMethod) {
			if (!$oMethod->isStatic() && $oMethod->getNumberOfParameters() == 1) {
				if (utils::StartsWith($oMethod->getName(), 'Set')) {
					$aSetters[] = substr($oMethod->getName(), 3); // remove 'Set' to get the variable name
				}
				if (utils::StartsWith($oMethod->getName(), 'Add')) {
					$aAdders[] = $oMethod->getName();
				}
			}
		}
		if (!empty($aSetters)) {
			$oCompiler
				->write("\$aSetters = ['".implode("','", $aSetters)."'];\n")
				->write("foreach (\$aSetters as \$sSetter) {\n")->indent()
				->write("if (isset(\$aParams[\"{\$sSetter}\"])) {\n")->indent()
				->write("\$sCmd = \"Set{\$sSetter}\";\n")
				->write("call_user_func([\${$sBlockVar}, \$sCmd], \$aParams[\"{\$sSetter}\"]);\n")->outdent()
				->write("}\n")->outdent()
				->write("}\n");
		}
		if (!empty($aAdders)) {
			$oCompiler
				->write("\$aAdders = ['".implode("','", $aAdders)."'];\n")
				->write("foreach (\$aAdders as \$sAdder) {\n")->indent()
				->write("if (isset(\$aParams[\"{\$sAdder}\"])) {\n")->indent()
				->write("call_user_func([\${$sBlockVar}, \$sAdder], \$aParams[\"{\$sAdder}\"]);\n")->outdent()
				->write("}\n")->outdent()
				->write("}\n");
		}

		// Attach to parent UIBlock
		$oCompiler->write("end(\$context['UIBlockParent'])->AddSubBlock(\${$sBlockVar});\n");

		// Add sub UIBlocks
		try {
			$oSubNode = $this->getNode('body');
			$oCompiler
				->write("array_push(\$context['UIBlockParent'], \${$sBlockVar});\n")
				->write("ob_start();\n")
				->subcompile($oSubNode)
				->write("\$sHtml = trim(ob_get_contents());\n")
				->write("ob_end_clean();\n")
				->write("if (strlen(\$sHtml) > 0) {\n")
				->indent()->write("end(\$context['UIBlockParent'])->AddSubBlock(new \Combodo\iTop\Application\UI\Base\Component\Html\Html(\$sHtml));\n")->outdent()
				->write("}\n")
				->write("array_pop(\$context['UIBlockParent']);\n");
		} catch (Exception $e) {
			// Ignore errors because when a tag has no body, GetNode('body') throws an exception
		}
		$oCompiler->write("ob_start();\n");
	}
}