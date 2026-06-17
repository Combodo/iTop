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
		$bSelected = $aExtraFlags['selected'] ?? true;
		$bDisabled = $aExtraFlags['disabled'] ?? false;
		$bRemote = $aExtraFlags['remote'] ?? false;
		self::AddExtraBadges($aBadges, $bUninstallable, $bMissingFromDisk, $sCode);
		$oBadgeInstalled = BadgeUIBlockFactory::MakeGreen(
			Dict::S('UI:Layout:ExtensionsDetails:BadgeInstalled'),
			Dict::S('UI:Layout:ExtensionsDetails:BadgeInstalled+'),
			'badge--'.$sCode.'--installed'
		);
		$oBadgeInstalled->AddCSSClass('checked');
		$aBadges[] = $oBadgeInstalled;
		$oBadgeToBeUninstalled = BadgeUIBlockFactory::MakeRed(
			Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeUninstalled'),
			Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeUninstalled+'),
			'badge--'.$sCode.'--to-be-uninstalled'
		);
		$oBadgeToBeUninstalled->AddCSSClass('unchecked');
		$aBadges[] = $oBadgeToBeUninstalled;

		$oExtensionDetails = new ExtensionDetails($sCode, $sLabel, $sDescription, $aMetaData, $aBadges, $sAbout);
		$oExtensionDetails->GetToggler()->SetIsToggled(true);
		if ($bMissingFromDisk) {
			$oExtensionDetails->GetToggler()->SetIsToggled(false);
			$oExtensionDetails->GetToggler()->SetIsDisabled(true);
		} elseif ((!$bUninstallable || $bRemote) && !$bDisabled) {
			$oExtensionDetails->AllowForceUninstall();
			$oExtensionDetails->GetToggler()->SetIsDisabled(true);
		}

		if (!$bSelected) {
			$oExtensionDetails->GetToggler()->SetIsToggled(false);
		}
		if ($bDisabled) {
			$oExtensionDetails->GetToggler()->SetIsDisabled(true);
			$oExtensionDetails->GetToggler()->AddCSSClass('ibo-is-hidden');
		}

		return $oExtensionDetails;
	}

	public static function MakeNotInstalled(string $sCode, string $sLabel, string $sDescription = '', array $aMetaData = [], array $aExtraFlags = [], string $sAbout = '')
	{
		$aBadges = [];
		$bUninstallable = $aExtraFlags['uninstallable'] ?? true;
		$bSelected = $aExtraFlags['selected'] ?? false;
		$bDisabled = $aExtraFlags['disabled'] ?? false;
		self::AddExtraBadges($aBadges, $bUninstallable, false, $sCode);
		$oBadgeInstalled = BadgeUIBlockFactory::MakeGrey(
			Dict::S('UI:Layout:ExtensionsDetails:BadgeNotInstalled'),
			Dict::S('UI:Layout:ExtensionsDetails:BadgeNotInstalled+'),
			'badge--'.$sCode.'--not-installed'
		);
		$oBadgeInstalled->AddCSSClass('unchecked');
		$aBadges[] = $oBadgeInstalled;
		$oBadgeToBeUninstalled = BadgeUIBlockFactory::MakeCyan(
			Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeInstalled'),
			Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeInstalled+'),
			'badge--'.$sCode.'--to-be-installed'
		);
		$oBadgeToBeUninstalled->AddCSSClass('checked');
		$aBadges[] = $oBadgeToBeUninstalled;
		$oExtensionDetails = new ExtensionDetails($sCode, $sLabel, $sDescription, $aMetaData, $aBadges, $sAbout);

		if ($bSelected) {
			$oExtensionDetails->GetToggler()->SetIsToggled(true);
		}
		if ($bDisabled) {
			$oExtensionDetails->GetToggler()->SetIsDisabled(true);
			$oExtensionDetails->GetToggler()->AddCSSClass('ibo-is-hidden');
		}

		return $oExtensionDetails;
	}

	private static function AddExtraBadges(array &$aBadges, bool $bUninstallable, bool $bMissingFromDisk, string $sCode)
	{
		if (!$bUninstallable) {
			$aBadges[] = BadgeUIBlockFactory::MakeOrange(
				Dict::S('UI:Layout:ExtensionsDetails:BadgeNotUninstallable'),
				Dict::S('UI:Layout:ExtensionsDetails:BadgeNotUninstallable+'),
				'badge--'.$sCode.'--not-uninstallable'
			);
		}
		if ($bMissingFromDisk) {
			$aBadges[] = BadgeUIBlockFactory::MakeRed(
				Dict::S('UI:Layout:ExtensionsDetails:BadgeMissingFromDisk'),
				Dict::S('UI:Layout:ExtensionsDetails:BadgeMissingFromDisk+'),
				'badge--'.$sCode.'--missing-from-disk'
			);
		}
	}
}
