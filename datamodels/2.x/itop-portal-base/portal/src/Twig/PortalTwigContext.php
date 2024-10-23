<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Twig;

/**
 * Twig context to add additional twigs and data to extensible twigs.
 * The additional twigs are ailed at blocks defined in the extensible twig.
 *
 * @api
 *
 * @since iTop 3.2.1
 */
class PortalTwigContext
{

	private array $aBlockExtension;

	public function __construct()
	{
		$this->aBlockExtension = [];
	}

	/**
	 * Add a Twig block extension.
	 * This method is used by extensions to provide templates.
	 *
	 * @api
	 *
	 * @param PortalBlockExtension $oBlockExtension Entity containing a twig template and associated data
	 * @param string $sBlockName Name of the block where to add the twig
	 *
	 * @since iTop 3.2.1
	 */
	function AddBlockExtension(string $sBlockName, PortalBlockExtension $oBlockExtension): void
	{
		$this->aBlockExtension[$sBlockName] = $oBlockExtension;
	}

	/**
	 * Get all the templates to render for a given block.
	 * This method is used by twig templates to render extensions.
	 *
	 * @api
	 *
	 * @param string $sBlockName Name of the block currently rendered
	 *
	 * @return \Combodo\iTop\Portal\Twig\PortalBlockExtension|null
	 *
	 * @since iTop 3.2.1
	 */
	public function GetBlockExtension(string $sBlockName): ?PortalBlockExtension
	{
		/** @var PortalBlockExtension $oBlockExtension */
		$oBlockExtension = $this->aBlockExtension[$sBlockName] ?? null;
		return $oBlockExtension;
	}
}