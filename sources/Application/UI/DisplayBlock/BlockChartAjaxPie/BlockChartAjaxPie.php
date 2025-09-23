<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\DisplayBlock\BlockChartAjaxPie;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class BlockChartAjaxPie
 *
 * @package Combodo\iTop\Application\UI\DisplayBlock\BlockChartAjaxPie
 */
class BlockChartAjaxPie extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-blockchartajaxpie';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/display-block/block-chart-ajax-pie/layout';

	/** @var string */
	public $sId;
	/** @var string */
	public $sJSColumns;
	/** @var string */
	public $sJSURLs;
	/** @var string */
	public $sJSNames;
	/** @var string */
	public $sURLForRefresh;
	/** @var int */
	public $iNbLinesToAddForName;
}