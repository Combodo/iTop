<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\DisplayBlock\BlockChartAjaxBars;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class BlockChartAjaxBars
 *
 * @package Combodo\iTop\Application\UI\DisplayBlock\BlockChartAjaxBars
 */
class BlockChartAjaxBars extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-blockchartajaxbars';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/display-block/block-chart-ajax-bars/layout';

	/** @var string */
	public $sJSNames;
	/** @var string */
	public $sJson;
	/** @var string */
	public $sId;
	/** @var string */
	public $sJSURLs;
	/** @var string */
	public $sURLForRefresh;
	/** @var int */
	public $iMaxNbCharsInLabel;

}