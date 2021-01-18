<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base;


abstract class AbstractUIBlockFactory implements iUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UIBlock';
	public const UI_BLOCK_CLASS_NAME = "Combodo\\iTop\\Application\\UI\\Base\\UIBlock";

	public static function GetTwigTagName(): string
	{
		return static::TWIG_TAG_NAME;
	}

	public static function GetUIBlockClassName(): string
	{
		return static::UI_BLOCK_CLASS_NAME;
	}
}