<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\DataTable;


use ApplicationContext;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;
use DataTableConfig;

/**
 * Class DataTableBasket
 *
 * @package Combodo\iTop\Application\UI\Base\Component\DataTableBasket
 * @since 3.1.0
 */
class DataTableBasket extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-datatable-basket';

	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/components/datatable/basket';

	protected $sPostedFieldsForBackUrl;


	/**
	 * Panel constructor.
	 *
	 */
	public function __construct(array $aPostedFieldsForBackUrl = [], ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sPostedFieldsForBackUrl = json_encode($aPostedFieldsForBackUrl);
	}

	/**
	 * @return string
	 */
	public function GetPostedFieldsForBackUrl(): string
	{
		return $this->sPostedFieldsForBackUrl;
	}
}
