<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input\FileSelect;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

class FileSelectUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UIFileSelect';
	public const UI_BLOCK_CLASS_NAME = FileSelect::class;

	public static function MakeStandard(string $sName, string $sId = null): FileSelect
	{
		return new FileSelect($sName, $sId);
	}
}