<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\Links;

use AttributeExternalKey;
use AttributeLinkedSet;
use AttributeLinkedSetIndirect;
use Exception;
use MetaModel;

/**
 * Class LinkSetModel
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Controller
 */
class LinkSetModel
{
	/**
	 * GetTargetClass.
	 *
	 * @param AttributeLinkedSet $oAttDef
	 *
	 * @return string
	 */
	static public function GetTargetClass(AttributeLinkedSet $oAttDef): string
	{
		try {
			if ($oAttDef instanceof AttributeLinkedSetIndirect) {
				/** @var AttributeExternalKey $oLinkingAttDef */
				$oLinkingAttDef = MetaModel::GetAttributeDef($oAttDef->GetLinkedClass(), $oAttDef->GetExtKeyToRemote());

				return $oLinkingAttDef->GetTargetClass();
			} else {
				return $oAttDef->GetLinkedClass();
			}
		}
		catch (Exception $e) {
			return 'unknown';
		}
	}

	/**
	 * GetLinkedClass.
	 *
	 * @param AttributeLinkedSet $oAttDef
	 *
	 * @return string
	 */
	static public function GetLinkedClass(AttributeLinkedSet $oAttDef): string
	{
		return $oAttDef->GetLinkedClass();
	}

	/**
	 * GetTargetField.
	 *
	 * @param AttributeLinkedSet $oAttDef
	 *
	 * @return string|null
	 */
	static public function GetTargetField(AttributeLinkedSet $oAttDef): ?string
	{
		if ($oAttDef instanceof AttributeLinkedSetIndirect) {
			return $oAttDef->GetExtKeyToRemote();
		} else {
			return null;
		}
	}
}