<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Helper;

use Combodo\iTop\Portal\Hook\iPortalTabExtension;
use Combodo\iTop\Portal\Hook\iPortalTabSectionExtension;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;

class ExtensibilityHelper
{
	private static ExtensibilityHelper $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): ExtensibilityHelper
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	public function GetPortalTabExtension(string $sTargetName): array
	{
		$aTabExtensions = [];
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iPortalTabExtension::class) as $sPortalTabExtension) {
			$oPortalTabExtension = new $sPortalTabExtension();
			if ($oPortalTabExtension->IsTabPresent() && $oPortalTabExtension->GetTarget() === $sTargetName) {
				$aTabExtensions[] = $oPortalTabExtension;
			}
		}
		usort($aTabExtensions, function (iPortalTabExtension $a, iPortalTabExtension $b) {
			return $a->GetTabRank() - $b->GetTabRank();
		});

		return $aTabExtensions;
	}

	public function GetPortalTabContentExtensions(string $sTargetName, string $sTab): array
	{
		$aTabSectionExtensions = [];
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iPortalTabSectionExtension::class) as $sPortalTabSectionExtension) {
			$oPortalTabSectionExtension = new $sPortalTabSectionExtension();
			if (!$oPortalTabSectionExtension->IsActive() || $oPortalTabSectionExtension->GetTarget() !== $sTargetName) {
				continue;
			}

			if ($oPortalTabSectionExtension->GetTabCode() !== $sTab) {
				continue;
			}
			$aTabSectionExtensions[] = $oPortalTabSectionExtension;
		}

		usort($aTabSectionExtensions, function (iPortalTabSectionExtension $a, iPortalTabSectionExtension $b) {
			return $a->GetSectionRank() - $b->GetSectionRank();
		});

		return $aTabSectionExtensions;
	}

}