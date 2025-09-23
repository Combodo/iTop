<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\MultiColumn;


use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\Column;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * Class MultiColumn
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\MultiColumn
 * @internal
 * @since   3.0.0
 */
class MultiColumn extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-multi-column';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/multi-column/layout';

	/**
	 * @inheritDoc
	 */
	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\Column $oColumn
	 *
	 * @return $this
	 */
	public function AddColumn(Column $oColumn)
	{
		$this->AddSubBlock($oColumn);

		return $this;
	}
}