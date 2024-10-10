<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Helper;

use Combodo\iTop\Portal\Hook\iAbstractPortalTabContentExtension;
use Combodo\iTop\Portal\Hook\iAbstractPortalTabExtension;
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

	public function GetPortalTabExtensions(string $sPortalTabExtensionInterface): array
	{
		$aTabExtensions = [];
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses($sPortalTabExtensionInterface) as $sPortalTabExtension) {
			$oPortalTabExtension = new $sPortalTabExtension();
			if ($oPortalTabExtension->IsTabPresent()) {
				$aTabExtensions[] = $oPortalTabExtension;
			}
		}
		usort($aTabExtensions, function (iAbstractPortalTabExtension $a, iAbstractPortalTabExtension $b) {
			return $a->GetTabRank() - $b->GetTabRank();
		});

		return $aTabExtensions;
	}

	/**
	 * @param string $sTargetName
	 * @param string $sTab
	 *
	 * @return array[iPortalTabContentExtension]
	 */
	public function GetPortalTabContentExtensions(string $sPortalTabSectionExtensionInterface, string $sTab): array
	{
		$aTabSectionExtensions = [];
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses($sPortalTabSectionExtensionInterface) as $sPortalTabSectionExtension) {
			$oPortalTabSectionExtension = new $sPortalTabSectionExtension();
			if (!$oPortalTabSectionExtension->IsActive()) {
				continue;
			}

			if ($oPortalTabSectionExtension->GetTabCode() !== $sTab) {
				continue;
			}
			$aTabSectionExtensions[] = $oPortalTabSectionExtension;
		}

		usort($aTabSectionExtensions, function (iAbstractPortalTabContentExtension $a, iAbstractPortalTabContentExtension $b) {
			return $a->GetSectionRank() - $b->GetSectionRank();
		});

		return $aTabSectionExtensions;
	}

}