<?php

namespace Combodo\iTop\Application\UI\Base\Layout\Extension;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Badge\BadgeUIBlockFactory;
use Dict;
use utils;

class ExtensionDetailsFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIExtensionDetails';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = ExtensionDetails::class;

	public static function MakeInstalled(string $sCode, string $sLabel, string $sDescription = '', array $aMetaData = [], array $aExtraFlags = [], string $sAbout = '')
	{
		$aBadges = [];
		$bUninstallable = $aExtraFlags['uninstallable'] ?? true;
		$bMissingFromDisk = $aExtraFlags['missing'] ?? false;
		self::AddExtraBadges($aBadges, $bUninstallable, $bMissingFromDisk);
		$oBadgeInstalled = BadgeUIBlockFactory::MakeGreen('installed');
		$oBadgeInstalled->AddCSSClass('checked');
		$aBadges[] = $oBadgeInstalled;
		$oBadgeToBeUninstalled = BadgeUIBlockFactory::MakeRed('to be uninstalled');
		$oBadgeToBeUninstalled->AddCSSClass('unchecked');
		$aBadges[] = $oBadgeToBeUninstalled;

		$oExtensionDetails = new ExtensionDetails($sCode, $sLabel, $sDescription, $aMetaData, $aBadges, $sAbout);
		$oExtensionDetails->GetToggler()->SetIsToggled(true);
		if (!$bUninstallable) {
			$oExtensionDetails->AllowForceUninstall();
			$oExtensionDetails->GetToggler()->SetIsDisabled(true);
		}
		return $oExtensionDetails;
	}

	public static function MakeNotInstalled(string $sCode, string $sLabel, string $sDescription = '', array $aMetaData = [], array $aExtraFlags = [], string $sAbout = '')
	{
		$aBadges = [];
		$bUninstallable = $aExtraFlags['uninstallable'] ?? true;
		self::AddExtraBadges($aBadges, $bUninstallable, false);
		$oBadgeInstalled = BadgeUIBlockFactory::MakeGrey('not installed');
		$oBadgeInstalled->AddCSSClass('unchecked');
		$aBadges[] = $oBadgeInstalled;
		$oBadgeToBeUninstalled = BadgeUIBlockFactory::MakeCyan('to be installed');
		$oBadgeToBeUninstalled->AddCSSClass('checked');
		$aBadges[] = $oBadgeToBeUninstalled;

		return new ExtensionDetails($sCode, $sLabel, $sDescription, $aMetaData, $aBadges, $sAbout);
	}

	private static function AddExtraBadges(array &$aBadges, bool $bUninstallable, bool $bMissingFromDisk)
	{
		if (!$bUninstallable) {
			$aBadges[] = BadgeUIBlockFactory::MakeOrange('cannot be uninstalled');
		}
		if ($bMissingFromDisk) {
			$aBadges[] = BadgeUIBlockFactory::MakeRed('missing from disk');
		}
	}
}
