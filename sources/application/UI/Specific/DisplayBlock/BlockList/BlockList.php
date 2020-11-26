<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Specific\DisplayBlock\BlockList;


use Combodo\iTop\Application\UI\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\tBlockParams;

/**
 * Class BlockList
 *
 * @package Combodo\iTop\Application\UI\Specific\DisplayBlock\BlockList
 */
class BlockList extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-blocklist';
	public const HTML_TEMPLATE_REL_PATH = 'specific/displayblock/blocklist/layout';
	public const JS_TEMPLATE_REL_PATH = 'specific/displayblock/blocklist/layout';

	use tBlockParams;
}