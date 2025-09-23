<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Toolbar;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * Class Toolbar
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Toolbar
 */
class Toolbar extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-toolbar';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/toolbar/layout';

	public function __construct(string $sId = null, array $aContainerClasses = [])
	{
		parent::__construct($sId, $aContainerClasses);
	}
}