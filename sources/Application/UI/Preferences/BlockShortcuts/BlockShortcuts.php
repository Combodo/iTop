<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Preferences\BlockShortcuts;


use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class BlockShortcuts
 *
 * @package Combodo\iTop\Application\UI\Preferences\BlockShortcuts
 */
class BlockShortcuts extends Panel
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-block-shortcuts';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/preferences/block-shortcuts/layout';

	public $sIdShortcuts;
}