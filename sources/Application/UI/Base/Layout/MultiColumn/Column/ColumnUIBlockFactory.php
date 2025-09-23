<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class ColumnUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @since 3.0.0
 * @api
 */
class ColumnUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIColumn';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Column::class;

	/**
	 * @api
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\Column
	 */
	public static function MakeStandard(?string $sId = null)
	{
		$oInput = new Column($sId);

		return $oInput;
	}

	/**
	 * @api
	 * @param \Combodo\iTop\Application\UI\Base\UIBlock $oBlock
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\Column
	 */
	public static function MakeForBlock(UIBlock $oBlock, ?string $sId = null)
	{
		$oInput = new Column($sId);
		$oInput->AddSubBlock($oBlock);

		return $oInput;
	}
}