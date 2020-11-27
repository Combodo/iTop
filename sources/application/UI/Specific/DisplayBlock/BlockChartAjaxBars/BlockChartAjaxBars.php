<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Specific\DisplayBlock\BlockChartAjaxBars;


use Combodo\iTop\Application\UI\tBlockParams;
use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class BlockChartAjaxBars
 *
 * @package Combodo\iTop\Application\UI\Specific\DisplayBlock\BlockChartAjaxBars
 */
class BlockChartAjaxBars extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-blockchartajaxbars';
	public const JS_TEMPLATE_REL_PATH = 'specific/displayblock/blockchartajaxbars/layout';

	use tBlockParams;
}