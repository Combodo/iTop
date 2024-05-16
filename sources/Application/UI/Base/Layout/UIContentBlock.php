<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Layout;


use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class UIContentBlock
 * Base block containing sub-blocks
 *
 * @package Combodo\iTop\Application\UI\Base\Layout
 * @author  Eric Espie <eric.espie@combodo.com>
 * @author  Anne-Catherine Cognet <anne-catherine.cognet@combodo.com>
 * @internal
 * @since   3.0.0
 */
class UIContentBlock extends UIBlock implements iUIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-content-block';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/content-block/layout';

	/** @var array */
	protected $aSubBlocks;
	/** @var array */
	protected $aDeferredBlocks;	
	/** @var bool If set to true, the content block will have a surrounding <div> no matter its options / CSS classes / ... */
	protected $bHasForcedDiv;

	/**
	 * UIContentBlock constructor.
	 * Generates a <div> only if $aContainerClasses if not empty or block has data attributes
	 *
	 * @param string|null $sId
	 * @param array       $aContainerClasses Array of additional CSS classes
	 */
	public function __construct(string $sId = null, array $aContainerClasses = [])
	{
		parent::__construct($sId);

		$this->aSubBlocks = [];
		$this->aDeferredBlocks = [];
		$this->bHasForcedDiv = false;
		$this->SetCSSClasses($aContainerClasses);
	}

	/**
	 * @inheritDoc
	 */
	public function AddHtml(string $sHtml)
	{
		$oBlock = new Html($sHtml);
		$this->AddSubBlock($oBlock);

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function AddSubBlock(?iUIBlock $oSubBlock)
	{
		if ($oSubBlock) {
			$this->aSubBlocks[$oSubBlock->GetId()] = $oSubBlock;
		}
		return $this;
	}

	/**
	 * Add $oSubBlock as the first of the sub blocks, while preserving the array indexes
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oSubBlock
	 *
	 * @return $this
	 */
	public function PrependSubBlock(iUIBlock $oSubBlock)
	{
		$this->aSubBlocks = [$oSubBlock->GetId() => $oSubBlock] + $this->aSubBlocks;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function RemoveSubBlock(string $sId)
	{
		if ($this->HasSubBlock($sId)) {
			unset($this->aSubBlocks[$sId]);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function HasSubBlock(string $sId): bool
	{
		return array_key_exists($sId, $this->aSubBlocks);
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlock(string $sId): ?iUIBlock
	{
		return isset($this->aSubBlocks[$sId]) ? $this->aSubBlocks[$sId] : null;
	}

	/**
	 * @inheritDoc
	 */
	public function HasSubBlocks(): bool
	{
		return !empty($this->aSubBlocks);
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array
	{
		return $this->aSubBlocks;
	}

	/**
	 * @inheritDoc
	 */
	public function SetSubBlocks(array $aSubBlocks)
	{
		foreach ($aSubBlocks as $oSubBlock) {
			$this->AddSubBlock($oSubBlock);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function AddDeferredBlock(iUIBlock $oDeferredBlock)
	{
		$this->aDeferredBlocks[$oDeferredBlock->GetId()] = $oDeferredBlock;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function RemoveDeferredBlock(string $sId)
	{
		if ($this->HasDeferredBlock($sId)) {
			unset($this->aDeferredBlocks[$sId]);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function HasDeferredBlock(string $sId): bool
	{
		return array_key_exists($sId, $this->aDeferredBlocks);
	}

	/**
	 * @inheritDoc
	 */
	public function GetDeferredBlock(string $sId): ?iUIBlock
	{
		return isset($this->aDeferredBlocks[$sId]) ? $this->aDeferredBlocks[$sId] : null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetDeferredBlocks(): array
	{
		return $this->aDeferredBlocks;
	}

	/**
	 * @inheritDoc
	 */
	public function SetDeferredBlocks(array $aDeferredBlocks)
	{
		foreach ($aDeferredBlocks as $oDeferredBlock) {
			$this->AddDeferredBlock($oDeferredBlock);
		}

		return $this;
	}

	/**
	 * @see static::$bHasForcedDiv
	 * @return bool
	 */
	public function HasForcedDiv(): bool
	{
		return $this->bHasForcedDiv;
	}

	/**
	 * @param bool $bHasForcedDiv
	 * @see static::$bHasForcedDiv
	 *
	 * @return $this
	 */
	public function SetHasForcedDiv(bool $bHasForcedDiv)
	{
		$this->bHasForcedDiv = $bHasForcedDiv;
		return $this;
	}
}
