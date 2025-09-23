<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\DisplayBlock\BlockChart;


use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class BlockChart
 *
 * @package Combodo\iTop\Application\UI\DisplayBlock\BlockChart
 */
class BlockChart extends UIBlock
{
	use tJSRefreshCallback;
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-blockchart';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'application/display-block/block-chart/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/display-block/block-chart/layout';

	/** @var int */
	public $iChartCounter;
	/** @var string */
	public $sChartId;
	/** @var string */
	public $sUrl;

	public function GetJSRefresh():string{
		return " 
			if (typeof(nbChar) === \"undefined\") {
			    var nbChar = 0;
			}
			else {
				nbChar++;
			}
			{ 
				let nb = nbChar;
				eval(refreshChart[nb]);
			}";
	}

}