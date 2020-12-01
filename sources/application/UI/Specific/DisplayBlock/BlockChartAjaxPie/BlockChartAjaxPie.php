<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Specific\DisplayBlock\BlockChartAjaxPie;


use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class BlockChartAjaxPie
 *
 * @package Combodo\iTop\Application\UI\Specific\DisplayBlock\BlockChartAjaxPie
 */
class BlockChartAjaxPie extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-blockchartajaxpie';
	public const JS_TEMPLATE_REL_PATH = 'specific/displayblock/blockchartajaxpie/layout';

	/** @var string */
	public $sId;
	/** @var string */
	public $sJSColumns;
	/** @var string */
	public $sJSURLs;
	/** @var string */
	public $sJSNames;
}