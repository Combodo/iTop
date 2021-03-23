<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\FieldBadge;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use ormStyle;

class FieldBadgeUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UIFieldBadge';
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
		if ($oStyle) {
			$sStyleClass = $oStyle->GetStyleClass();
			$sPrimaryColor = $oStyle->GetMainColor();
			$sComplementaryColor = $oStyle->GetComplementaryColor();
			if (!is_null($sPrimaryColor) && !is_null($sComplementaryColor)) {
				$aCSSClasses = array_merge(explode(' ', $sStyleClass), ['ibo-field-badge']);
				$oBadge = new FieldBadge(null, $aCSSClasses);
				$sDecorationClasses = $oStyle->GetDecorationClasses();
				if (!is_null($sDecorationClasses) && !empty($sDecorationClasses)) {
					$oBadge->AddHtml("<i class=\"$sDecorationClasses\"></i>&nbsp;");
				}
				$oBadge->AddHtml("<span>$sValue</span>");
				// Add custom style
				// TODO 3.0 To be removed when compilation supports generated CSS
				$oBadge->AddHtml(<<<HTML
<style>
.$sStyleClass {
		color: $sComplementaryColor;
		background-color: $sPrimaryColor;
	}
</style>
HTML
				);
			}
		}
		if (!$oBadge) {
			$oBadge = new FieldBadge();
			$oBadge->AddHtml("<span>$sValue</span>");
		}

		return $oBadge;
	}
}