<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Renderer;

use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\UI\iUIBlock;
use ReflectionClass;

/**
 * Class BlockRenderer
 *
 * Used to render any block of the UI (layouts, components)
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Renderer\Component
 * @since 2.8.0
 */
class BlockRenderer
{
	/** @var \Twig_Environment $oTwigEnv Singleton used during rendering */
	protected static $oTwigEnv;

	/**
	 * Helper to use directly in TWIG to render a block and its sub blocks
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oBlock
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public static function RenderBlockTemplates(iUIBlock $oBlock)
	{
		$oSelf = new static($oBlock);
		return $oSelf->RenderTemplates();
	}

	/** @var \Combodo\iTop\Application\UI\iUIBlock $oBlock */
	protected $oBlock;
	/** @var \Combodo\iTop\Renderer\RenderingOutput $oRenderingOutput */
	protected $oRenderingOutput;

	/**
	 * BlockRenderer constructor.
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oBlock
	 */
	public function __construct(iUIBlock $oBlock)
	{
		if(null === static::$oTwigEnv)
		{
			static::$oTwigEnv = TwigHelper::GetTwigEnvironment(APPROOT.'templates/');
		}

		$this->oBlock = $oBlock;
		$this->ResetRenderingOutput();
	}

	/**
	 * Reset the rendering output so it can be computed again
	 *
	 * @return $this
	 */
	protected function ResetRenderingOutput()
	{
		$this->oRenderingOutput = new RenderingOutput();
		return $this;
	}

	/**
	 * Return the processed rendering output.
	 *
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @throws \Exception
	 */
	public function GetRenderingOutput()
	{
		$this->ResetRenderingOutput();

		$this->oRenderingOutput->AddHtml($this->RenderHtml())
			->AddCss($this->RenderCssInline())
			->AddJs($this->RenderJsInline())
			->SetCssFiles($this->GetCssFiles())
			->SetJsFiles($this->GetJsFiles());

		return $this->oRenderingOutput;
	}

	/**
	 * Return the raw output of the HTML template
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function RenderHtml()
	{
		$sOutput = '';
		if(!empty($this->oBlock::GetHtmlTemplateRelPath()))
		{
			$sOutput = TwigHelper::RenderTemplate(
				static::$oTwigEnv,
				[$this->GetBlockParameterNameForTemplate() => $this->oBlock],
				$this->oBlock::GetHtmlTemplateRelPath(),
				TwigHelper::ENUM_FILE_TYPE_HTML
			);
		}

		return $sOutput;
	}

	/**
	 * Return the raw output of the JS template
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function RenderJsInline()
	{
		$sOutput = '';
		if(!empty($this->oBlock::GetJsTemplateRelPath()))
		{
			$sOutput = TwigHelper::RenderTemplate(
				static::$oTwigEnv,
				[$this->GetBlockParameterNameForTemplate() => $this->oBlock],
				$this->oBlock::GetJsTemplateRelPath(),
				TwigHelper::ENUM_FILE_TYPE_JS
			);
		}

		return $sOutput;
	}

	/**
	 * Return the raw output of the CSS template
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function RenderCssInline()
	{
		$sOutput = '';
		if(!empty($this->oBlock::GetCssTemplateRelPath()))
		{
			$sOutput = TwigHelper::RenderTemplate(
				static::$oTwigEnv,
				[$this->GetBlockParameterNameForTemplate() => $this->oBlock],
				$this->oBlock::GetCssTemplateRelPath(),
				TwigHelper::ENUM_FILE_TYPE_CSS
			);
		}

		return $sOutput;
	}

	/**
	 * Return the cumulated HTML output of the CSS, HTML and JS templates
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function RenderTemplates()
	{
		$sOutput = '';

		// CSS first to avoid visual glitches
		$sCssOutput = $this->RenderCssInline();
		if(!empty($sCssOutput))
		{
			$sOutput .= <<<HTML
<style>
{$sCssOutput}
</style>
HTML;
		}

		$sOutput .= $this->RenderHtml();

		// JS last so all markup is build and ready
		$sJsOutput = $this->RenderJsInline();
		if(!empty($sJsOutput))
		{
			$sOutput .= <<<HTML
<script type="text/javascript">
{$sJsOutput}
</script>
HTML;
		}

		return $sOutput;
	}

	/**
	 * Return an array of the absolute URL of the block JS files
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function GetJsFiles()
	{
		return $this->oBlock->GetJsFilesUrlRecursively(true);
	}

	/**
	 * Return an array of the absolute URL of the block CSS files
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function GetCssFiles()
	{
		return $this->oBlock->GetCssFilesUrlRecursively(true);
	}

	/**
	 * Return the name of the parameter used in the template to access the block object (class short name  = without namespace)
	 *
	 * @return string
	 * @throws \ReflectionException
	 */
	protected function GetBlockParameterNameForTemplate()
	{
		return 'oUIBlock';
	}
}