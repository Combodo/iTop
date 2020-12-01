<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Specific\DisplayBlock\BlockChart;


use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class BlockChart
 *
 * @package Combodo\iTop\Application\UI\Specific\DisplayBlock\BlockChart
 */
class BlockChart extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-blockchart';
	public const HTML_TEMPLATE_REL_PATH = 'specific/displayblock/blockchart/layout';
	public const JS_TEMPLATE_REL_PATH = 'specific/displayblock/blockchart/layout';

	/** @var int */
	public $iChartCounter;
	/** @var string */
	public $sId;
	/** @var string */
	public $sUrl;
}