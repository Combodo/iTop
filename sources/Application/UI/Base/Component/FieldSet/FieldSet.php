<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\FieldSet;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * Class FieldSet
 *
 * @package Combodo\iTop\Application\UI\Base\Component\FieldSet
 */
class FieldSet extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-fieldset';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/fieldset/layout';

	/** @var string */
	protected $sLegend;

	/**
	 * FieldSet constructor.
	 *
	 * @param string $sLegend
	 * @param string|null $sId
	 */
	public function __construct(string $sLegend, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sLegend = $sLegend;
	}

	/**
	 * @return string
	 */
	public function GetLegend(): string
	{
		return $this->sLegend;
	}
	
}