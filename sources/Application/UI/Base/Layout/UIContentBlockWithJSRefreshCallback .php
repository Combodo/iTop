<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout;

use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;

/**
 * Class UIContentBlock
 * Base block containing sub-blocks
 *
 * @internal
 * @author  Anne-Catherine Cognet <anne-catherine.cognet@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout
 * @since   3.0.0
 */
class UIContentBlockWithJSRefreshCallback extends UIContentBlock
{
	use tJSRefreshCallback;
}
