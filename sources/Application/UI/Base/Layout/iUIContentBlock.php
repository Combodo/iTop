<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout;

use Combodo\iTop\Application\UI\Base\iUIBlock;

/**
 * Interface iUIContentBlock
 *
 * @package Combodo\iTop\Application\UI\Base\Layout
 * @author  Eric Espie <eric.espie@combodo.com>
 * @author  Anne-Catherine Cognet <anne-catherine.cognet@combodo.com>
 * @internal
 * @since   3.0.0
 */
interface iUIContentBlock {
	/**
	 * Add raw HTML to the block
	 *
	 * @param string $sHtml HTML to add to the block (must have been sanitized)
	 *
	 * @return $this
	 */
	public function AddHtml(string $sHtml);

	/**
	 * Add $oSubBlock, replacing any block with the same ID
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock|null $oSubBlock Sub-block to add into the current block
	 *
	 * @return $this
	 */
	public function AddSubBlock(?iUIBlock $oSubBlock);

	/**
	 * Remove the sub block identified by $sId.
	 * Note that if no sub block matches the ID, it proceeds silently.
	 *
	 * @param string $sId ID of the sub block to remove
	 *
	 * @return $this
	 */
	public function RemoveSubBlock(string $sId);

	/**
	 * Return true if there is a sub block identified by $sId
	 *
	 * @param string $sId
	 *
	 * @return bool
	 */
	public function HasSubBlock(string $sId): bool;

	/**
	 * Return the sub block identified by $sId or null if not found
	 *
	 * @param string $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock|null
	 */
	public function GetSubBlock(string $sId): ?iUIBlock;

	/**
	 * @return bool True if there is at least 1 sub-block
	 */
	public function HasSubBlocks(): bool;

	/**
	 * Return an array of all the sub blocks
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 */
	public function GetSubBlocks(): array;

	/**
	 * Set all sub blocks at once, replacing all existing ones
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aSubBlocks Array of blocks to set into the current block
	 *
	 * @return $this
	 */
	public function SetSubBlocks(array $aSubBlocks);


	/**
	 * Add $oDeferredBlock, replacing any block with the same ID
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oDeferredBlock Block added at the end of the page
	 *
	 * @return $this
	 */
	public function AddDeferredBlock(iUIBlock $oDeferredBlock);

	/**
	 * Remove the sub block identified by $sId.
	 * Note that if no sub block matches the ID, it proceeds silently.
	 *
	 * @param string $sId ID of the sub block to remove
	 *
	 * @return $this
	 */
	public function RemoveDeferredBlock(string $sId);

	/**
	 * Return true if there is a sub block identified by $sId
	 *
	 * @param string $sId
	 *
	 * @return bool
	 */
	public function HasDeferredBlock(string $sId): bool;

	/**
	 * Return the sub block identified by $sId or null if not found
	 *
	 * @param string $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock|null
	 */
	public function GetDeferredBlock(string $sId): ?iUIBlock;

	/**
	 * Return an array of all the sub blocks
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 */
	public function GetDeferredBlocks(): array;

	/**
	 * Set all sub blocks at once, replacing all existing ones
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aDeferredBlocks Array of blocks set at the end of the page
	 *
	 * @return $this
	 */
	public function SetDeferredBlocks(array $aDeferredBlocks);

}
