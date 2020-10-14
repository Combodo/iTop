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

/**
 * Class BlockRenderer
 *
 * Used to render any block of the UI (layouts, components)
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Renderer\Component
 * @since 3.0.0
 */
class BlockRenderer
{
	/** @var string TWIG_BASE_PATH base path of the templates */
	public const TWIG_BASE_PATH = APPROOT.'templates/';
	/** @var string[] TWIG_ADDITIONAL_PATHS Additional paths for resources to be loaded either as a template or as an image, ... */
	public const TWIG_ADDITIONAL_PATHS = [APPROOT.'images/'];

	/** @var \Twig_Environment $oTwigEnv Singleton used during rendering */
	protected static $oTwigEnv;

	/**
	 * BlockRenderer constructor.
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oBlock
	 * @param array $aContextParams
	 *
	 * @throws \Twig\Error\LoaderError
	 */
	public function __construct(iUIBlock $oBlock, array $aContextParams = [])
	{
		if (null === static::$oTwigEnv) {
			static::$oTwigEnv = TwigHelper::GetTwigEnvironment(static::TWIG_BASE_PATH, static::TWIG_ADDITIONAL_PATHS);
		}

		$this->oBlock = $oBlock;
		$this->aContextParams = $aContextParams;
		$this->ResetRenderingOutput();
	}

	/**
	 * Helper to use directly in TWIG to render a block and its sub blocks
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oBlock
	 * @param array $aContextParams
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public static function RenderBlockTemplates(iUIBlock $oBlock, array $aContextParams = [])
	{
		$oSelf = new static($oBlock, $aContextParams);

		return $oSelf->RenderHtml();
	}

	/** @var \Combodo\iTop\Application\UI\iUIBlock $oBlock */
	protected $oBlock;
	/** @var array $aContextParams Optional context parameters to pass to the template during rendering */
	protected $aContextParams;
	/** @var \Combodo\iTop\Renderer\RenderingOutput $oRenderingOutput */
	protected $oRenderingOutput;

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
				$this->GetTemplateParameters(),
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
				$this->GetTemplateParameters(),
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
				$this->GetTemplateParameters(),
				$this->oBlock::GetCssTemplateRelPath(),
				TwigHelper::ENUM_FILE_TYPE_CSS
			);
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
	 * Return an associative array of parameters for the template.
	 * Contains oUIBlock for the current block and optionally some others.
	 *
	 * @return array
	 */
	protected function GetTemplateParameters()
	{
		return array_merge(['oUIBlock' => $this->oBlock], $this->aContextParams, $this->oBlock->GetParameters());
	}
}
