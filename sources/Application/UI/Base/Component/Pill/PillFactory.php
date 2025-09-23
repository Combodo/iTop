<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Pill;


use Combodo\iTop\Application\UI\Helper\UIHelper;
use MetaModel;

/**
 * Class PillFactory
 *
 * @internal
 * @author Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 * @package Combodo\iTop\Application\UI\Base\Component\Pill
 */
class PillFactory
{
	/**
	 * @param string $sClass
	 * @param string $sStateCode
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Pill\Pill
	 * @throws \CoreException
	 */
	public static function MakeForState(string $sClass, string $sStateCode)
	{
		$oPill = new Pill();

		// First we try to apply style defined in the DM if any, otherwise we fallback on the default colors
		$oStyle = MetaModel::GetEnumStyle($sClass, MetaModel::GetStateAttributeCode($sClass), $sStateCode);
		if ($oStyle !== null) {
			$oPill->SetCSSColorClass($oStyle->GetStyleClass());
		} else {
			$oPill->SetSemanticColor(UIHelper::GetColorNameFromStatusCode($sStateCode));
		}

		return $oPill;
	}
}