<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Links;

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
 * @package Combodo\iTop\Service\Links
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


	/**
	 * Convert edit_mode to relation type.
	 *
	 * @return string|null
	 */
	static public function ConvertEditModeToRelationType(AttributeLinkedSet $oAttDef): ?string
	{
		switch ($oAttDef->GetEditMode()) {
			case LINKSET_EDITMODE_INPLACE:
				return LINKSET_RELATIONTYPE_PROPERTY;
			case LINKSET_EDITMODE_ADDREMOVE:
				return LINKSET_RELATIONTYPE_LINK;
			default:
				return null;
		}
	}

	/**
	 * Convert edit_mode to read only.
	 *
	 * @return bool
	 */
	static public function ConvertEditModeToReadOnly(AttributeLinkedSet $oAttDef): bool
	{
		switch ($oAttDef->GetEditMode()) {
			case LINKSET_EDITMODE_NONE:
			case LINKSET_EDITMODE_ADDONLY:
				return true;

			default:
				return false;
		}
	}
}