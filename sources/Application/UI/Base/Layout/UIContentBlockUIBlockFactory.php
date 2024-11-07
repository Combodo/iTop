<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Layout;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;

/**
 * Class UIContentBlockUIBlockFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package UIBlockAPI
 * @api
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
	 * @api
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
	 * @api
	 * @param string $sCode plain text code
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 */
	public static function MakeForCode(string $sCode, string $sId = null)
	{
		$sCode = str_replace("\n", '<br>', \utils::HtmlEntities($sCode));

		return self::MakeFromHTMLCode($sId, $sCode);
	}

	/**
	 * Used to display a block of preformatted text in a <pre> tag.
	 *
	 * @api
	 * @param string $sCode plain text code
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 */
	public static function MakeForPreformatted(string $sCode, string $sId = null)
	{
		$sCode = '<pre>'.\utils::HtmlEntities($sCode).'</pre>';

		return self::MakeFromHTMLCode($sId, $sCode);
	}

	/**
	 * @param string|null $sId
	 * @param string $sCode
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 */
	private static function MakeFromHTMLCode(?string $sId, string $sCode): UIContentBlock
	{
		$oCode = new UIContentBlock($sId, ['ibo-is-code']);
		$oCode->AddSubBlock(new Html($sCode));

		return $oCode;
	}
}