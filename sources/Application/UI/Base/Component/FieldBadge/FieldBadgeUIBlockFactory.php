<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\FieldBadge;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use ormStyle;

/**
 * Class FieldBadgeUIBlockFactory
 *
 * @author Eric espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\FieldBadge
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
				$sHtml .= "<span class=\"ibo-field-badge--label\">$sValue</span>";
			}
		}
		if (!$oBadge) {
			$oBadge = new FieldBadge();
			$sHtml .= "<span>$sValue</span>";
		}
		$oBadge->AddHtml($sHtml);
		return $oBadge;
	}
}