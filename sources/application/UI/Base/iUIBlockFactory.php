<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base;


interface iUIBlockFactory
{
	public static function GetTwigTagName(): string;

	public static function GetUIBlockClassName(): string;
}