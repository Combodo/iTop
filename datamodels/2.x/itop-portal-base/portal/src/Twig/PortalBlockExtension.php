<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Twig;

use utils;

class PortalBlockExtension
{
	private string $sTwig;
	private array $aData;

	/**
	 * Create a new twig extension block
	 * The given twig template can be HTML, CSS or JavaScript.
	 * CSS goes to the block named 'css' and is inline in the page.
	 * JavaScript goes to the blocks named 'script' or 'ready_script' and are inline in the page.
	 * HTML goes everywhere else
	 *
	 * @param string $sTwig name of the twig file to the absolute path given to the PortalTwigContext
	 * @param array $aData Data given to the twig template (into the variable {{ aData }})
	 * @api
	 */
	public function __construct(string $sTwig, array $aData = [])
	{
		$this->sTwig = $sTwig;
		$this->aData = $aData;
	}

	public function GetTwig(): string
	{
		return $this->sTwig;
	}

	public function GetData(): array
	{
		$this->aData['sTransactionId'] = utils::GetNewTransactionId();
		return $this->aData;
	}
}
