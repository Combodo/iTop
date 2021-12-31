<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Layout;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;

/**
 * Class UIContentBlockUIBlockFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout
 * @since 3.0.0
 */
class UIContentBlockUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIContentBlock';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = UIContentBlock::class;

	/**
	 * Make an empty UIContentBlock which can be used to embed anything or to surround another block with specific CSS classes.
	 *
	 * @param string|null $sId
	 * @param array $aContainerClasses
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 */
	public static function MakeStandard(string $sId = null, array $aContainerClasses = [])
	{
		return new UIContentBlock($sId, $aContainerClasses);
	}

	/**
	 * Used to display a block of code like <pre> but allows line break.
	 * The \n are replaced by <br>
	 *
	 * @param string $sCode
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 */
	public static function MakeForCode(string $sCode, string $sId = null)
	{
		$oCode = new UIContentBlock($sId, ['ibo-is-code']);
		$sCode = str_replace("\n", '<br>', $sCode);
		$oCode->AddSubBlock(new Html($sCode));

		return $oCode;
	}

	/**
	 * Used to display a block of preformatted text in a <pre> tag.
	 *
	 * @param string $sCode
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 */
	public static function MakeForPreformatted(string $sCode, string $sId = null)
	{
		$sCode = '<pre>'.$sCode.'</pre>';

		return static::MakeForCode($sCode, $sId);
	}
}