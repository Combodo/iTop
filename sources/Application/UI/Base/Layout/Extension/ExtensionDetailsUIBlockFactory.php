<?php

namespace Combodo\iTop\Application\UI\Base\Layout\Extension;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Badge\BadgeUIBlockFactory;
use Dict;

class ExtensionDetailsUIBlockFactory extends AbstractUIBlockFactory
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
		$oBadgeInstalled = BadgeUIBlockFactory::MakeGreen(Dict::S('UI:Layout:ExtensionsDetails:BadgeInstalled'));
		$oBadgeInstalled->AddCSSClass('checked');
		$aBadges[] = $oBadgeInstalled;
		$oBadgeToBeUninstalled = BadgeUIBlockFactory::MakeRed(Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeUninstalled'));
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
		$oBadgeInstalled = BadgeUIBlockFactory::MakeGrey(Dict::S('UI:Layout:ExtensionsDetails:BadgeNotInstalled'));
		$oBadgeInstalled->AddCSSClass('unchecked');
		$aBadges[] = $oBadgeInstalled;
		$oBadgeToBeUninstalled = BadgeUIBlockFactory::MakeCyan(Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeInstalled'));
		$oBadgeToBeUninstalled->AddCSSClass('checked');
		$aBadges[] = $oBadgeToBeUninstalled;

		return new ExtensionDetails($sCode, $sLabel, $sDescription, $aMetaData, $aBadges, $sAbout);
	}

	private static function AddExtraBadges(array &$aBadges, bool $bUninstallable, bool $bMissingFromDisk)
	{
		if (!$bUninstallable) {
			$aBadges[] = BadgeUIBlockFactory::MakeOrange(Dict::S('UI:Layout:ExtensionsDetails:BadgeNotUninstallable'));
		}
		if ($bMissingFromDisk) {
			$aBadges[] = BadgeUIBlockFactory::MakeRed(Dict::S('UI:Layout:ExtensionsDetails:BadgeMissingFromDisk'));
		}
	}
}
