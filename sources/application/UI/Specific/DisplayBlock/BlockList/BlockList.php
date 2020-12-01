<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Specific\DisplayBlock\BlockList;


use Combodo\iTop\Application\UI\Layout\UIContentBlock;

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

	/** @var bool */
	public $bEmptySet = false;
	/** @var bool */
	public $bNotAuthorized = false;
	/** @var bool */
	public $bCreateNew = false;
	/** @var string */
	public $sLinkTarget = '';
	/** @var string */
	public $sClass = '';
	/** @var string */
	public $sParams = '';
	/** @var string */
	public $sDefault = '';
	/** @var string */
	public $sEventAttachedData = '';
}