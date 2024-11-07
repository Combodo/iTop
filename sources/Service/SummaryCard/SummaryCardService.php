<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\SummaryCard;

use appUserPreferences;
use Combodo\iTop\Core\MetaModel\FriendlyNameType;
use Combodo\iTop\Service\Router\Router;
use MetaModel;
use UserRights;

/**
 * Class SummaryCardService
 *
 * Service containing methods to call SummaryCards functionalities
 * 
 * @since 3.1.0
 */
class SummaryCardService {

	/**
	 * @param $sObjClass
	 * @param $sObjKey
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetHyperlinkMarkup(string $sObjClass, $sObjKey): string
	{
		$oRouter = Router::GetInstance();
		$sRoute = $oRouter->GenerateUrl("object.summary", ["obj_class" => $sObjClass, "obj_key" => $sObjKey]);
		return 
	<<<HTML
data-tooltip-content="$sRoute" 
data-tooltip-interaction-enabled="true" 
data-tooltip-is-async="true" 
data-tooltip-html-enabled="true" 
data-tooltip-sanitizer-skipped="false" 
data-tooltip-show-delay="800" 
data-tooltip-hide-delay="500" 
data-tooltip-max-width="600px" 
data-tooltip-theme="object-summary" 
data-tooltip-append-to="body"
HTML;
		
}

	/**
	 * Check if the user is allowed to see this class and if the class is allowed to display its summary
	 * 
	 * @param string $sClass
	 *
	 * @return bool
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public static function IsAllowedForClass(string $sClass): bool
	{
		// - User can read this object, otherwise end here
		if (UserRights::IsActionAllowed($sClass, UR_ACTION_READ) === UR_ALLOWED_NO) {
			return false;
		}

		// - User doesn't want to see summary cards
		if (appUserPreferences::GetPref('show_summary_cards', true) === false) {
			return false;
		}
			
		// - This class has a summary zlist
		$aDetailsList = MetaModel::GetZListItems($sClass, 'summary');
		if(count($aDetailsList) > 0) {
			return true;
		}
		
		// - Then maybe this class has complementary attributes
		$aComplementAttributeSpec = MetaModel::GetNameSpec($sClass, FriendlyNameType::COMPLEMENTARY);
		$aAdditionalField = $aComplementAttributeSpec[1];
		if (count($aAdditionalField) > 0) {
			return true;
		}
		
		return false;	
	}
}