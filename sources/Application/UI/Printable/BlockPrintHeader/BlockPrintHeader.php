<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Printable\BlockPrintHeader;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class BlockPrintHeader
 *
 * @package Combodo\iTop\Application\UI\Printable\BlockPrintHeader
 */
class BlockPrintHeader extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-block-print-header';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'application/printable/block-print-header/layout';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'application/printable/block-print-header/layout';
	public const DEFAULT_JS_LIVE_TEMPLATE_REL_PATH = 'application/printable/block-print-header/layout';
	public const DEFAULT_CSS_FILES_REL_PATH = ['css/print.css'];
}