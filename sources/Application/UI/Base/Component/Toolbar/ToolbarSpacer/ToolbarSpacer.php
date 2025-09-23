<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class ButtonToolbarSpacer
 *
 * @package Combodo\iTop\Application\UI\Base\Component\ButtonToolbarSpacer
 */
class ToolbarSpacer extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-toolbar-spacer';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/toolbar/toolbar-spacer/layout';

	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
	}
}