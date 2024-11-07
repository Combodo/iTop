<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Twig;

use utils;

/**
 * Entity used to store a twig template and the associated data
 *
 * @api
 *
 * @since iTop 3.2.1
 */
class PortalBlockExtension
{
	private string $sTwig;
	private array $aData;

	/**
	 * Create a new twig extension block.
	 * The given twig template can be HTML, CSS or JavaScript.
	 *   * CSS goes to the block named 'css' and is inline in the page.
	 *   * JavaScript goes to the blocks named 'script' or 'ready_script' and are inline in the page.
	 *   * HTML goes everywhere else.
	 *
	 * @api
	 *
	 * @param array $aData Data given to the twig template (into the variable {{ aData }})
	 * @param string $sTwig name of the twig file to the absolute path given to the PortalTwigContext
	 *
	 * @since iTop 3.2.1
	 */
	function __construct(string $sTwig, array $aData = [])
	{
		$this->sTwig = $sTwig;
		$this->aData = $aData;
	}

	/**
	 * Used by twig templates to get the name of the template to render.
	 *
	 * @return string twig template to render
	 *
	 * @api
	 *
	 * @since iTop 3.2.1
	 */
	public function GetTwig(): string
	{
		return $this->sTwig;
	}

	/**
	 * Used by twig templates to get the data for the template to render.
	 *
	 * @return array Data used to render the template
	 *
	 * @api
	 *
	 * @since iTop 3.2.1
	 */
	public function GetData(): array
	{
		$this->aData['sTransactionId'] = utils::GetNewTransactionId();
		return $this->aData;
	}
}
