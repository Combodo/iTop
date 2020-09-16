<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Layout;

use Combodo\iTop\Application\UI\iUIBlock;

interface iUIContentBlock
{
	public function AddHtml(string $sHtml): iUIBlock;

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array;

	/**
	 * @param string $sId
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock|null
	 */
	public function GetSubBlock(string $sId): ?iUIBlock;

	/**
	 * Set all sub blocks at once, replacing all existing ones
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aSubBlocks
	 *
	 * @return $this
	 */
	public function SetSubBlocks(array $aSubBlocks): iUIContentBlock;

	/**
	 * Add $oSubBlock, replacing any block with the same ID
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oSubBlock
	 *
	 * @return $this
	 */
	public function AddSubBlock(iUIBlock $oSubBlock): iUIContentBlock;

	/**
	 * Remove the sub block identified by $sId.
	 * Note that if no sub block matches the ID, it proceeds silently.
	 *
	 * @param string $sId ID of the sub block to remove
	 *
	 * @return $this
	 */
	public function RemoveSubBlock(string $sId): iUIContentBlock;

	public function HasSubBlock(string $sId): bool;
}
