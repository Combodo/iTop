<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Helper;

use Combodo\iTop\Portal\Hook\iAbstractPortalTabContentExtension;
use Combodo\iTop\Portal\Hook\iAbstractPortalTabExtension;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;

/**
 * This helper is used by the bricks to manage the tab extensibility by retrieving
 * the classes implementing the corresponding interfaces
 *
 * @api
 * @see \Combodo\iTop\Portal\Hook\iAbstractPortalTabExtension
 * @see \Combodo\iTop\Portal\Hook\iAbstractPortalTabContentExtension
 * @since iTop 3.2.1
 */
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

	/**
	 * Instantiate all the classes implementing the given interface
	 *
	 * @api
	 * @see \Combodo\iTop\Portal\Hook\iAbstractPortalTabExtension
	 *
	 * @param string $sPortalTabExtensionInterface Extensibility interface to search for (derived from iAbstractPortalTabExtension)
	 *
	 * @return array[iAbstractPortalTabExtension] array of objects implementing the given interface
	 *
	 * @since iTop 3.2.1
	 */
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
	 * Instantiate all the classes implementing the given interface for the given tab
	 *
	 * @param string $sPortalTabSectionExtensionInterface Extensibility interface to search for (derived from iAbstractPortalTabContentExtension)
	 * @param string $sTab Tab code
	 *
	 * @return array[iPortalTabContentExtension] array of objects implementing the given interface
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