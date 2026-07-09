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

	private const BADGE_ID_INSTALLED = 'installed';
	private const BADGE_ID_NOT_INSTALLED = 'not-installed';
	private const BADGE_ID_TO_BE_INSTALLED = 'to-be-installed';
	private const BADGE_ID_TO_BE_UNINSTALLED = 'to-be-uninstalled';
	private const BADGE_ID_NOT_UNINSTALLABLE = 'not-uninstallable';
	private const BADGE_ID_MISSING_FROM_DISK = 'missing-from-disk';
	private const BADGE_ID_CANNOT_BE_INSTALLED = 'cannot-be-installed';

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
			self::GetBadgeId($sCode, self::BADGE_ID_INSTALLED)
		);
		$oBadgeInstalled->AddCSSClass('checked');
		$aBadges[] = $oBadgeInstalled;
		$oBadgeToBeUninstalled = BadgeUIBlockFactory::MakeRed(
			Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeUninstalled'),
			Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeUninstalled+'),
			self::GetBadgeId($sCode, self::BADGE_ID_TO_BE_UNINSTALLED)
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
		$bDependencyIssue = $aExtraFlags['dependency_issue'] ?? false;
		$bSelected = $aExtraFlags['selected'] ?? false;
		$bDisabled = $aExtraFlags['disabled'] ?? false;
		self::AddExtraBadges($aBadges, $bUninstallable, false, $sCode);
		$oBadgeInstalled = BadgeUIBlockFactory::MakeGrey(
			Dict::S('UI:Layout:ExtensionsDetails:BadgeNotInstalled'),
			Dict::S('UI:Layout:ExtensionsDetails:BadgeNotInstalled+'),
			self::GetBadgeId($sCode, self::BADGE_ID_NOT_INSTALLED)
		);
		$oBadgeInstalled->AddCSSClass('unchecked');
		$aBadges[] = $oBadgeInstalled;
		$oBadgeToBeUninstalled = BadgeUIBlockFactory::MakeCyan(
			Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeInstalled'),
			Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeInstalled+'),
			self::GetBadgeId($sCode, self::BADGE_ID_TO_BE_INSTALLED)
		);
		$oBadgeToBeUninstalled->AddCSSClass('checked');
		$aBadges[] = $oBadgeToBeUninstalled;
		if ($bDependencyIssue) {
			$bSelected = false;
			$aBadges[] = BadgeUIBlockFactory::MakeOrange(
				Dict::S('UI:Layout:ExtensionsDetails:BadgeCannotBeInstalled'),
				Dict::S('UI:Layout:ExtensionsDetails:BadgeCannotBeInstalled+'),
				self::GetBadgeId($sCode, self::BADGE_ID_CANNOT_BE_INSTALLED)
			);
		}
		$oExtensionDetails = new ExtensionDetails($sCode, $sLabel, $sDescription, $aMetaData, $aBadges, $sAbout);

		if ($bDependencyIssue) {
			$oExtensionDetails->GetToggler()->SetIsDisabled(true);
		}
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
			$aBadges[] = BadgeUIBlockFactory::MakeYellow(
				Dict::S('UI:Layout:ExtensionsDetails:BadgeNotUninstallable'),
				Dict::S('UI:Layout:ExtensionsDetails:BadgeNotUninstallable+'),
				self::GetBadgeId($sCode, self::BADGE_ID_NOT_UNINSTALLABLE)
			);
		}
		if ($bMissingFromDisk) {
			$aBadges[] = BadgeUIBlockFactory::MakeRed(
				Dict::S('UI:Layout:ExtensionsDetails:BadgeMissingFromDisk'),
				Dict::S('UI:Layout:ExtensionsDetails:BadgeMissingFromDisk+'),
				self::GetBadgeId($sCode, self::BADGE_ID_MISSING_FROM_DISK)
			);
		}
	}

	/**
	 * Generate a badge ID for an extension
	 *
	 * @param string $sExtensionCode The extension code
	 * @param string $sBadgeType The badge type (one of the BADGE_ID_* constants)
	 *
	 * @return string The badge ID in the format: badge--{extensionCode}--{badgeType}
	 */
	public static function GetBadgeId(string $sExtensionCode, string $sBadgeType): string
	{
		return 'badge--'.$sExtensionCode.'--'.$sBadgeType;
	}

}
