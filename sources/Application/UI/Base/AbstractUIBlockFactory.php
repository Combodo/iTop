<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base;


/**
 * Class AbstractUIBlockFactory
 *
 * @package UIBlockExtensibilityAPI
 * @author  Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 * @internal
 */
abstract class AbstractUIBlockFactory implements iUIBlockFactory
{
	/**
	 * @api
	 * @var string
	 * @see static::GetTwigTagName()
	 */
	public const TWIG_TAG_NAME = 'UIBlock';
	/**
	 * @api
	 * @var string
	 * @see static::GetUIBlockClassName()
	 */
	public const UI_BLOCK_CLASS_NAME = UIBlock::class;

	/**
	 * @inheritDoc
	 */
	public static function GetTwigTagName(): string
	{
		return static::TWIG_TAG_NAME;
	}

	/**
	 * @inheritDoc
	 */
	public static function GetUIBlockClassName(): string
	{
		return static::UI_BLOCK_CLASS_NAME;
	}
}