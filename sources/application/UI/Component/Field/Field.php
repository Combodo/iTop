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
 * @author Eric Espie <eric.espie@combodo.com>
 * @author Anne-Catherine Cognet <annecatherine.cognet@combodo.com>
 * @since 3.0.0
 */
class Field extends UIBlock
{
	// Overloaded constants
	/** @inheritdoc  */
	public const BLOCK_CODE = 'ibo-field';
	/** @inheritdoc  */
	public const HTML_TEMPLATE_REL_PATH = 'components/field/layout';

	/** @var array Array of various parameters of the field. This should be exploded in dedicated properties instead of a grey array. */
	protected $aParams;

	public function __construct(array $aParams, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->aParams = $aParams;
	}

	/**
	 * @return array
	 * @internal
	 */
	public function GetParams(): array
	{
		return $this->aParams;
	}

}