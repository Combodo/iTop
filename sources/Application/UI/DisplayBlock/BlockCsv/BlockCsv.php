<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\DisplayBlock\BlockCsv;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class BlockCsv
 *
 * @package Combodo\iTop\Application\UI\DisplayBlock\BlockCsv
 */
class BlockCsv extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-block-csv';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'application/display-block/block-csv/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/display-block/block-csv/layout';

	/** @var string */
	public $sDownloadLink;
	/** @var string */
	public $sCsvFile;
	/** @var string */
	public $sCharsetNotice;
	/** @var string */
	public $sChecked;
	/** @var string */
	public $sLinkToToggle;
	/** @var bool */
	public $bAdvancedMode;
	/** @var string */
	public $sAjaxLink;
	/** @var string */
	public $sJsonParams;

}