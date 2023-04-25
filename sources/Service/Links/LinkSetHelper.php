<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Links;

use AttributeExternalKey;
use AttributeLinkedSet;
use AttributeLinkedSetIndirect;
use Dict;
use Exception;
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
	 * @param $sClass
	 * @param $sAttCode
	 * @param $sStringCode
	 * @param ...$aArgs
	 *
	 * @return string
	 */
	public static function FormatWithFallback($sClass, $sAttCode, $sStringCode, ...$aArgs)
	{
		$sNextClass = $sClass;

		do {
			$sKey = "class:{$sNextClass}/Attribute:{$sAttCode}/{$sStringCode}";
			if (Dict::S($sKey, null, true) !== $sKey) {
				return Dict::Format($sKey, ...$aArgs);
			}
			$sNextClass = MetaModel::GetParentClass($sNextClass);
		} while ($sNextClass !== null);

		return Dict::Format($sStringCode, ...$aArgs);
	}


}