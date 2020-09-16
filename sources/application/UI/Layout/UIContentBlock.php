<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Layout;


use Combodo\iTop\Application\UI\Component\Html\Html;
use Combodo\iTop\Application\UI\iUIBlock;
use Combodo\iTop\Application\UI\UIBlock;

class UIContentBlock extends UIBlock implements iUIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-contentblock';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/contentblock/layout';
	public const JS_TEMPLATE_REL_PATH = 'layouts/contentblock/layout';

	protected $aSubBlocks;

	/**
	 * UIContentBlock constructor.
	 *
	 * @param string|null $sName
	 */
	public function __construct(string $sName = null)
	{
		parent::__construct($sName);

		$this->aSubBlocks = [];
	}

	public function AddHtml(string $sHtml): iUIBlock
	{
		$oBlock = new Html($sHtml);
		$this->AddSubBlock($oBlock);

		return $oBlock;
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array
	{
		return $this->aSubBlocks;
	}

	/**
	 * @param string $sId
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock|null
	 */
	public function GetSubBlock(string $sId): ?iUIBlock
	{
		return isset($this->aSubBlocks[$sId]) ? $this->aSubBlocks[$sId] : null;
	}

	/**
	 * Set all sub blocks at once, replacing all existing ones
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aSubBlocks
	 *
	 * @return iUIContentBlock
	 */
	public function SetSubBlocks(array $aSubBlocks): iUIContentBlock
	{
		foreach ($aSubBlocks as $oSubBlock) {
			$this->AddSubBlock($oSubBlock);
		}

		return $this;
	}

	/**
	 * Add $oSubBlock, replacing any block with the same ID
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oSubBlock
	 *
	 * @return iUIContentBlock
	 */
	public function AddSubBlock(iUIBlock $oSubBlock): iUIContentBlock
	{
		$this->aSubBlocks[$oSubBlock->GetId()] = $oSubBlock;

		return $this;
	}

	/**
	 * Remove the sub block identified by $sId.
	 * Note that if no sub block matches the ID, it proceeds silently.
	 *
	 * @param string $sId ID of the sub block to remove
	 *
	 * @return iUIContentBlock
	 */
	public function RemoveSubBlock(string $sId): iUIContentBlock
	{
		if ($this->HasSubBlock($sId)) {
			unset($this->aSubBlocks[$sId]);
		}

		return $this;
	}

	public function HasSubBlock(string $sId): bool
	{
		return array_key_exists($sId, $this->aSubBlocks);
	}
}
