<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Twig;

class PortalTwigContext
{

	/** @var array */
	private array $aBlockExtension;

	public function __construct()
	{
		$this->aBlockExtension = [];
	}

	/**
	 * Add a Twig block extension
	 *
	 * @param string $sBlockName
	 * @param PortalBlockExtension $oBlockExtension
	 */
	public function AddBlockExtension($sBlockName, $oBlockExtension)
	{
		$this->aBlockExtension[$sBlockName] = $oBlockExtension;
	}

	/**
	 * @param string $sBlockName
	 *
	 * @return PortalBlockExtension
	 */
	public function GetBlockExtension($sBlockName)
	{
		/** @var PortalBlockExtension $oBlockExtension */
		$oBlockExtension = isset($this->aBlockExtension[$sBlockName]) ? $this->aBlockExtension[$sBlockName] : null;
		return $oBlockExtension;
	}
}