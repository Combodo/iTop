<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base;


/**
 * Interface iUIBlockFactory
 *
 * The UIBlockFactories should be prefered rathan than manually instantiating UIBlocks via their constructor for several reasons:
 *   * Factories' prototypes should be consistent over time
 *   * Factories that a block style will be consistent with the whole app. UI style over time (eg. Success messages are displayed in green in the app. If you manually create a green one for your purpose, if in the future we change success messages to be blue, yours will stay green, loosing their semantic meaning. Using the factories properly, your usages will migrate with the app. UI style seemlessly)
 *
 * @package Combodo\iTop\Application\UI\Base
 * @author  Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 * @internal
 */
interface iUIBlockFactory
{
	/**
	 * @return string TWIG tag name that will be associated with this factory
	 * @used-by TWIG tags
	 * @internal
	 */
	public static function GetTwigTagName(): string;

	/**
	 * @return string FQCN of the UIBlock produced by this factory
	 * @used-by TWIG tags
	 * @internal
	 */
	public static function GetUIBlockClassName(): string;
}