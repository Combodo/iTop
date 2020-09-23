<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Component\Field;


use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class Field
 *
 * @package Combodo\iTop\Application\UI\Component\Field
 */
class Field extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-field';
	public const HTML_TEMPLATE_REL_PATH = 'components/field/layout';
	public const JS_TEMPLATE_REL_PATH = 'components/field/layout';

	/** @var array */
	protected $aParams;

	public function __construct(array $aParams, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->aParams = $aParams;
	}

	/**
	 * @return array
	 */
	public function GetParams(): array
	{
		return $this->aParams;
	}

}