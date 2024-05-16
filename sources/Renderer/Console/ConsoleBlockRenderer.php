<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Renderer\Console;

use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Renderer\BlockRenderer;

class ConsoleBlockRenderer extends BlockRenderer
{

	/**
	 * Add blocks to the page using twig template
	 * @param WebPage $oPage
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock UIBlock containing template using UIBlock tags
	 * @param array $aContextParams
	 *
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public static function PreRenderBlockIntoPage(WebPage $oPage, iUIBlock $oBlock, array $aContextParams = [])
	{
		static::AddCssJsToPage($oPage, $oBlock, $aContextParams);
		$aContextParams['UIBlockParent'] = [$oPage];
		$aContextParams['oPage'] = $oPage;

		$oSelf = new static($oBlock, $aContextParams);
		// No output will add blocks to the page
		$oSelf->RenderHtml();
	}

	/**
	 * Helper to use directly in TWIG to render a block and its sub blocks
	 *
	 * @param WebPage $oPage
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock
	 * @param array $aContextParams
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @throws \Exception
	 */
	public static function RenderBlockTemplateInPage(WebPage $oPage, iUIBlock $oBlock, array $aContextParams = []): string
	{
		static::AddCssJsToPage($oPage, $oBlock, $aContextParams);
		static::AddDeferredBlocksToPage($oPage, $oBlock);

		$oSelf = new static($oBlock, $aContextParams);
		return $oSelf->RenderHtml();
	}

	protected static function AddDeferredBlocksToPage(WebPage $oPage, iUIBlock $oBlock)
	{
		foreach ($oBlock->GetDeferredBlocks() as $oDeferredBlock) {
			$oPage->AddDeferredBlock($oDeferredBlock);
		}
		foreach ($oBlock->GetSubBlocks() as $oSubBlock) {
			static::AddDeferredBlocksToPage( $oPage, $oSubBlock);
		}
	}

	/**
	 * @param WebPage $oPage
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock
	 * @param array $aContextParams
	 *
	 * @throws \Exception
	 */
	public static function AddCssJsToPage(WebPage $oPage, iUIBlock $oBlock, array $aContextParams = []): void
	{
		// CSS files
		foreach ($oBlock->GetCssFilesUrlRecursively(true) as $sFileAbsUrl) {
			$oPage->LinkStylesheetFromURI($sFileAbsUrl);
		}
		// JS files
		foreach ($oBlock->GetJsFilesUrlRecursively(true) as $sFileAbsUrl) {
			$oPage->LinkScriptFromURI($sFileAbsUrl);
		}
		static::AddCssJsTemplatesToPageRecursively($oPage, $oBlock, $aContextParams);
	}

	protected static function AddCssJsTemplatesToPageRecursively(WebPage $oPage, iUIBlock $oBlock, array $aContextParams = []): void
	{
		$oBlockRenderer = new static($oBlock, $aContextParams);
		$oPage->add_init_script($oBlockRenderer->RenderJsInline(iUIBlock::ENUM_JS_TYPE_ON_INIT));
		$oPage->add_script($oBlockRenderer->RenderJsInline(iUIBlock::ENUM_JS_TYPE_LIVE));
		$oPage->add_ready_script($oBlockRenderer->RenderJsInline(iUIBlock::ENUM_JS_TYPE_ON_READY));
		$oPage->add_style($oBlockRenderer->RenderCssInline());

		foreach ($oBlock->GetSubBlocks() as $oSubBlock) {
			static::AddCssJsTemplatesToPageRecursively($oPage, $oSubBlock, $aContextParams);
		}
	}

}