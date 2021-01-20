<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base;


/**
 * Class AbstractUIBlockFactory
 *
 * @package Combodo\iTop\Application\UI\Base
 * @author  Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 */
abstract class AbstractUIBlockFactory implements iUIBlockFactory
{
	/**
	 * @var string
	 * @used-by static::GetTwigTagName()
	 */
	public const TWIG_TAG_NAME = 'UIBlock';
	/**
	 * @var string
	 * @useb-by static::GetUIBlockClassName()
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