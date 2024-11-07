<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\DisplayBlock\BlockList;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;

/**
 * Class BlockList
 *
 * @package Combodo\iTop\Application\UI\DisplayBlock\BlockList
 */
class BlockList extends UIContentBlock
{
	use tJSRefreshCallback;

	// Overloaded constants
	public const BLOCK_CODE = 'ibo-block-list';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'application/display-block/block-list/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'application/display-block/block-list/layout';

	/** @var bool */
	public $bEmptySet = false;
	/** @var bool */
	public $bNotAuthorized = false;
	/** @var bool */
	public $bCreateNew = false;
	/** @var string */
	public $sLinkTarget = '';
	/** @var string */
	public $sClass = '';
	/** @var string */
	public $sClassLabel = '';
	/** @var string */
	public $sParams = '';
	/** @var string */
	public $sDefault = '';
	/** @var string */
	public $sEventAttachedData = '';
	/** @var string */
	public $sAbsoluteUrlAppRoot;
	/** @var array */
	public $aExtraParams;
	/** @var string */
	public $sFilter;

	public function GetJSRefresh(): string
	{
		return '$("#'.$this->sId.'").block();
			$.post("ajax.render.php?operation=refreshDashletList",
			{ style: "list", filter: '.json_encode($this->sFilter).', extra_params: '.json_encode($this->aExtraParams).' },
			function(data){
				$("#'.$this->sId.'")
				.empty()
				.append(data)
				.unblock();
			});';
	}
}