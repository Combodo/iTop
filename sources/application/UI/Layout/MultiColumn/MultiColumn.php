<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Layout\MultiColumn;


use Combodo\iTop\Application\UI\Layout\Column\Column;
use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class MultiColumn
 *
 * @package Combodo\iTop\Application\UI\Layout\MultiColumn
 */
class MultiColumn extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-multicolumn';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/multicolumn/layout';

	/** @var array */
	protected $aColumns;

	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->aColumns = [];
	}

	public function AddColumn(Column $oColumn): self
	{
		$this->aColumns[] = $oColumn;
		return $this;
	}

	public function GetSubBlocks()
	{
		return $this->aColumns;
	}
}