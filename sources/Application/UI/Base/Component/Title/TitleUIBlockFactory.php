<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Title;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Text\Text;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class TitleUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 */
class TitleUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UITitle';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Title::class;

	/**
	 * @api
	 * @param string $sTitle
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Title\Title
	 */
	public static function MakeForPage(string $sTitle, ?string $sId = null)
	{
		return new Title(new Text($sTitle), 1, $sId);
	}

	/**
	 * @api
	 * @param string $sTitle
	 * @param string $sIconUrl
	 * @param string $sIconCoverMethod
	 * @param bool $bIsMedallion
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Title\Title
	 */
	public static function MakeForPageWithIcon(
		string $sTitle, string $sIconUrl, string $sIconCoverMethod = Title::DEFAULT_ICON_COVER_METHOD, bool $bIsMedallion = true,
		?string $sId = null
	)
	{
		$oTitle = new Title(new Text($sTitle), 1, $sId);
		$oTitle->SetIcon($sIconUrl, $sIconCoverMethod, $bIsMedallion);

		return $oTitle;
	}

	/**
	 * @api
	 * @param string $sTitle
	 * @param int $iLevel
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Title\Title
	 */
	public static function MakeNeutral(string $sTitle, int $iLevel = 1, ?string $sId = null)
	{
		return new Title(new Text($sTitle), $iLevel, $sId);
	}

	/**
	 * @api
	 * @param \Combodo\iTop\Application\UI\Base\UIBlock $oTitle
	 * @param int $iLevel
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Title\Title
	 */
	public static function MakeStandard(UIBlock $oTitle, int $iLevel = 1, ?string $sId = null)
	{
		return new Title($oTitle, $iLevel, $sId);
	}
}