<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel;


use appUserPreferences;
use cmdbAbstractObject;
use Exception;
use MetaModel;

/**
 * Class ActivityPanelHelper
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.0.0
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel
 */
class ActivityPanelHelper
{
	/**
	 * Save in the user pref. if the activity panel should be expanded or not for $sObjectClass in $sObjectMode
	 *
	 * @param string $sObjectClass
	 * @param string $sObjectMode
	 * @param bool $bIsExpanded
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public static function SaveExpandedStateForClass(string $sObjectClass, string $sObjectMode, bool $bIsExpanded): void
	{
		if (false === MetaModel::IsValidClass($sObjectClass)) {
			throw new Exception('"'.$sObjectClass.'" must be a valid class.');
		}

		if (false === in_array($sObjectMode, cmdbAbstractObject::EnumObjectModes())) {
			throw new Exception('Wrong object mode "'.$sObjectMode.'", must be among '.implode(' / ', cmdbAbstractObject::EnumObjectModes()));
		}

		$aStates = appUserPreferences::GetPref('activity_panel.is_expanded', []);
		$aStates[$sObjectClass.'::'.$sObjectMode] = $bIsExpanded;
		appUserPreferences::SetPref('activity_panel.is_expanded', $aStates);
	}

	/**
	 * Save in the user pref. if the activity panel should be expanded or not for $sObjectClass in $sObjectMode
	 *
	 * @param string $sObjectClass
	 * @param string $sObjectMode
	 * @param bool $bIsClosed
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public static function SaveClosedStateForClass(string $sObjectClass, string $sObjectMode, bool $bIsClosed)
	{
		if (false === MetaModel::IsValidClass($sObjectClass)) {
			throw new Exception('"'.$sObjectClass.'" must be a valid class.');
		}

		if (false === in_array($sObjectMode, cmdbAbstractObject::EnumObjectModes())) {
			throw new Exception('Wrong object mode "'.$sObjectMode.'", must be among '.implode(' / ', cmdbAbstractObject::EnumObjectModes()));
		}

		$aStates = appUserPreferences::GetPref('activity_panel.is_closed', []);
		$aStates[$sObjectClass.'::'.$sObjectMode] = $bIsClosed;
		appUserPreferences::SetPref('activity_panel.is_closed', $aStates);
	}
}