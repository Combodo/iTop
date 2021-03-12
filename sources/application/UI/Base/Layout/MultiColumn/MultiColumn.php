<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\MultiColumn;


use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\Column;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class MultiColumn
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\MultiColumn
 * @internal
 * @since   3.0.0
 */
class MultiColumn extends UIBlock {
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-multi-column';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/multi-column/layout';

	/** @var \Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\Column[] */
	protected $aColumns;

	/**
	 * @inheritDoc
	 */
	public function __construct(?string $sId = null) {
		parent::__construct($sId);
		$this->aColumns = [];
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\Column $oColumn
	 *
	 * @return $this
	 */
	public function AddColumn(Column $oColumn) {
		$this->aColumns[] = $oColumn;

		return $this;
	}

	/**
	 * Remove the column of $iIndex index.
	 * Note that if the column does not exists, it proceeds silently.
	 *
	 * @param int $iIndex
	 *
	 * @return $this
	 */
	public function RemoveColumn(int $iIndex) {
		if (isset($this->aColumns[$iIndex])) {
			unset($this->aColumns[$iIndex]);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks() {
		return $this->aColumns;
	}
}