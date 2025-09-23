<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Toolbar\Separator;


/**
 * Class ToolbarSpacer
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Toolbar\Separator
 * @since 3.0.0
 */
class VerticalSeparator extends AbstractSeparator
{
	// Overloaded constants
	/** @inheritDoc */
	public const BLOCK_CODE = 'ibo-toolbar-vertical-separator';
	/** @inheritDoc */
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/toolbar/separator/vertical-separator/layout';

	/** @inheritDoc */
	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
	}
}