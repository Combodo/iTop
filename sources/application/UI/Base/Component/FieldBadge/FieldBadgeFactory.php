<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\FieldBadge;


use ormStyle;

class FieldBadgeFactory
{
	/**
	 * @param string $sValue
	 * @param \ormStyle $oStyle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\FieldBadge\FieldBadge
	 */
	public static function MakeForField(string $sValue, ormStyle $oStyle)
	{
		$sPrimaryColor = $oStyle->GetMainColor();
		$oBadge = new FieldBadge();
		$sId = $oBadge->GetId();
		$sComplementaryColor = $oStyle->GetComplementaryColor();
		$sDecorationClasses = $oStyle->GetDecorationClasses();
		if ($sDecorationClasses != '') {
			$oBadge->AddHtml("<i class=\"$sDecorationClasses\"></i>&nbsp;");
		}
		$oBadge->AddHtml("<span>$sValue</span>");
		// Add custom style
		$oBadge->AddHtml(<<<HTML
<style>
	#$sId {
		color: $sComplementaryColor;
		background-color: $sPrimaryColor;
	}
</style>
HTML
		);
		return $oBadge;
	}
}