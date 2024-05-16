<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\FieldBadge;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use ormStyle;
use utils;

/**
 * Class FieldBadgeUIBlockFactory
 *
 * @author Eric espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @since 3.0.0
 * @internal
 */
class FieldBadgeUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIFieldBadge';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = FieldBadge::class;

	/**
	 * @param string $sValue
	 * @param \ormStyle|null $oStyle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\FieldBadge\FieldBadge
	 */
	public static function MakeForField(string $sValue, ?ormStyle $oStyle)
	{
		$oBadge = null;
		$sHtml = '';

		// N°5318 - Sanitize value manually as this UIBlock is not using a proper TWIG template 😥
		$sValueForHtml = utils::EscapeHtml($sValue);

		if ($oStyle) {
			$sStyleClass = $oStyle->GetStyleClass();
			$sPrimaryColor = $oStyle->GetMainColor();
			$sComplementaryColor = $oStyle->GetComplementaryColor();
			if (!is_null($sPrimaryColor) && !is_null($sComplementaryColor)) {
				$aCSSClasses = array_merge(explode(' ', $sStyleClass), ['ibo-field-badge']);
				$oBadge = new FieldBadge(null, $aCSSClasses);
				$sDecorationClasses = $oStyle->GetDecorationClasses();
				if (!is_null($sDecorationClasses) && !empty($sDecorationClasses)) {
					$sHtml .= "<span class=\"ibo-field-badge--decoration\"><i class=\"$sDecorationClasses\"></i></span>";
				}
				$sHtml .= "<span class=\"ibo-field-badge--label\">$sValueForHtml</span>";
			}
		}
		if (!$oBadge) {
			$oBadge = new FieldBadge();
			$sHtml .= "<span>$sValueForHtml</span>";
		}
		$oBadge->AddHtml($sHtml);
		return $oBadge;
	}
}