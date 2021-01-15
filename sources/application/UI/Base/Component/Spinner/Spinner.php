<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Spinner;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class Spinner
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Spinner
 */
class Spinner extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-spinner';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/spinner/layout';

	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
	}
}