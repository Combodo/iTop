<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base;


/**
 * Interface UIBlockNode
 *
 * @package Combodo\iTop\Application\UI\Base
 * @author  Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 */
interface iUIBlockFactory
{
	/**
	 * @return string TWIG tag name that will be associated with this factory
	 */
	public static function GetTwigTagName(): string;

	/**
	 * @return string FQCN of the UIBlock produced by this factory
	 */
	public static function GetUIBlockClassName(): string;
}