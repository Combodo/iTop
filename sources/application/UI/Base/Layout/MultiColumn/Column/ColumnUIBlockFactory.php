<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\UIBlock;

class ColumnUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UIColumn';
	public const UI_BLOCK_CLASS_NAME = Column::class;

	public static function MakeStandard(?string $sId = null): Column
	{
		$oInput = new Column($sId);

		return $oInput;
	}

	public static function MakeForBlock(UIBlock $oBlock, ?string $sId = null): Column
	{
		$oInput = new Column($sId);
		$oInput->AddSubBlock($oBlock);

		return $oInput;
	}
}