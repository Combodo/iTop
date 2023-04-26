<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Links;

use Dict;
use MetaModel;

/**
 * Class LinkSetHelper
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Service\Links
 */
class LinkSetHelper
{

	/**
	 * FormatLinkDictEntry.
	 *
	 * @param string $sClass Host object class
	 * @param string $sAttCode Linkset attribute
	 * @param string $sDictKey Dict entry key
	 * @param ...$aArgs Dict::Format arguments
	 *
	 * @return string
	 */
	public static function FormatLinkDictEntry(string $sClass, string $sAttCode, string $sDictKey, ...$aArgs): string
	{
		$sNextClass = $sClass;

		do {
			$sKey = "class:{$sNextClass}/Attribute:{$sAttCode}/{$sDictKey}";
			if (Dict::S($sKey, null, true) !== $sKey) {
				return Dict::Format($sKey, ...$aArgs);
			}
			$sNextClass = MetaModel::GetParentClass($sNextClass);
		} while ($sNextClass !== null);

		return Dict::Format($sDictKey, ...$aArgs);
	}

}