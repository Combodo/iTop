<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration;

use DOMFormatException;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class Lists
 *
 * @package Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.7.0
 */
class Lists extends AbstractConfiguration
{
	/**
	 * @param \Symfony\Component\DependencyInjection\Container $oContainer
	 *
	 * @throws \DOMFormatException
	 */
	public function Process(Container $oContainer)
	{
		$iDefaultItemRank = 0;
		$aClassesLists = array();

		// Parsing XML file
		// - Each classes
		/** @var \MFElement $oClassNode */
		foreach ($this->GetModuleDesign()->GetNodes('/module_design/classes/class') as $oClassNode)
		{
			$aClassLists = array();
			$sClassId = $oClassNode->getAttribute('id');
			if ($sClassId === null)
			{
				throw new DOMFormatException('Class tag must have an id attribute', null, null, $oClassNode);
			}

			// - Each lists
			/** @var \MFElement $oListNode */
			foreach ($oClassNode->GetNodes('./lists/list') as $oListNode)
			{
				$aListItems = array();
				$sListId = $oListNode->getAttribute('id');
				if ($sListId === null)
				{
					throw new DOMFormatException('List tag of "'.$sClassId.'" class must have an id attribute', null,
						null, $oListNode);
				}

				// - Each items
				/** @var \MFElement $oItemNode */
				foreach ($oListNode->GetNodes('./items/item') as $oItemNode)
				{
					$sItemId = $oItemNode->getAttribute('id');
					if ($sItemId === null)
					{
						throw new DOMFormatException('Item tag of "'.$sItemId.'" list must have an id attribute', null,
							null, $oItemNode);
					}

					$aItem = array(
						'att_code' => $sItemId,
						'rank' => $iDefaultItemRank,
					);

					$oRankNode = $oItemNode->GetOptionalElement('rank');
					if ($oRankNode !== null)
					{
						$aItem['rank'] = $oRankNode->GetText($iDefaultItemRank);
					}

					$aListItems[] = $aItem;
				}
				// - Sorting list items by rank
				usort($aListItems, function ($a, $b) {
					if ($a['rank'] == $b['rank']) {
						return 0;
					}
					return $a['rank'] > $b['rank'] ? 1 : -1;
				});
				$aClassLists[$sListId] = $aListItems;
			}

			// - Adding class only if it has at least one list
			if (!empty($aClassLists))
			{
				$aClassesLists[$sClassId] = $aClassLists;
			}
		}
		$aPortalConf = $oContainer->getParameter('combodo.portal.instance.conf');
		$aPortalConf['lists'] = $aClassesLists;
		$oContainer->setParameter('combodo.portal.instance.conf', $aPortalConf);
	}

}