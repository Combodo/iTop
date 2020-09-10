<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\iUIBlock;
use Combodo\iTop\Renderer\BlockRenderer;


class UIBlockManager
{
	/** @var iUIBlock[] */
	private $aMainBlocks; // Top blocks to render
	/** @var iUIBlock[] */
	private $aAllBlocks; // All blocks recursively
	private $sCurrentBlockId; // Current block ('' for no current block)

	public function __construct()
	{
		$this->aMainBlocks = [];
		$this->aAllBlocks = [];
		$this->sCurrentBlockId = '';
	}

	/**
	 * Add a block to render
	 *
	 * @param iUIBlock $oUIBlock
	 */
	public function AddBlock(iUIBlock $oUIBlock)
	{
		$sId = $oUIBlock->GetId();
		$this->aMainBlocks[$sId] = $oUIBlock;
		$this->aAllBlocks[$sId] = $oUIBlock;
		$this->sCurrentBlockId = $sId;

		$aSubBlocks = $oUIBlock->GetSubBlocks();
		$this->aAllBlocks = array_merge($this->aAllBlocks, $aSubBlocks);
	}

	public function AddHtml(string $sHTML)
	{
		if ($this->sCurrentBlockId == '') {
			return;
		}
		$this->aAllBlocks[$this->sCurrentBlockId]->AddExtraHtmlContent($sHTML);
	}

	/**
	 * Set the current UIBlock to write into
	 *
	 * @param string $sId
	 */
	public function SetCurrentUIBlock(string $sId = '')
	{
		$this->sCurrentBlockId = $sId;
	}

	/**
	 * Indicates if an UIBlock is the current target to write into
	 *
	 * @return bool
	 */
	public function HasCurrentBlock(): bool
	{
		return isset($this->aAllBlocks[$this->sCurrentBlockId]);
	}

	/**
	 * Get the current UIBlock
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock|null
	 */
	public function GetCurrentUIBlock(): ?iUIBlock
	{
		return $this->GetBlock($this->sCurrentBlockId);
	}

	/**
	 * Get UIBlock from Id
	 *
	 * @param string $sId
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock|null
	 */
	public function GetBlock(string $sId): ?iUIBlock
	{
		if (isset($this->aAllBlocks[$sId])) {
			return $this->aAllBlocks[$sId];
		}
		return null;
	}

	/**
	 * Render the blocks into the page and return the HTML to add
	 *
	 * @param string $sContent
	 * @param \WebPage $oPage
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function RenderIntoContent(string &$sContent, WebPage $oPage): string
	{
		foreach ($this->aMainBlocks as $oBlock) {
			$oBlockRenderer = new BlockRenderer($oBlock);

			// Add HTML
			$sContent .= $oBlockRenderer->RenderHtml();

			// Add inline CSS and JS
			$oPage->add_style($oBlockRenderer->RenderCssInline());
			$oPage->add_ready_script($oBlockRenderer->RenderJsInline());

			// Add external files
			foreach ($oBlockRenderer->GetCssFiles() as $sFile) {
				$oPage->add_linked_stylesheet($sFile);
			}
			foreach ($oBlockRenderer->GetJsFiles() as $sFile) {
				$oPage->add_linked_script($sFile);
			}
		}
		return $sContent;
	}
}
