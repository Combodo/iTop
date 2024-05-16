<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
	public static function GetTargetClass(AttributeLinkedSet $oAttDef): string
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
	public static function GetLinkedClass(AttributeLinkedSet $oAttDef): string
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
	public static function GetTargetField(AttributeLinkedSet $oAttDef): ?string
	{
		if ($oAttDef instanceof AttributeLinkedSetIndirect) {
			return $oAttDef->GetExtKeyToRemote();
		} else {
			return null;
		}
	}

	/**
	 * Return true if we're allowed to create a remote object from this linkset.
	 *
	 * @param AttributeLinkedSet $oAttDef
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function IsRemoteCreationAllowed(AttributeLinkedSet $oAttDef): bool
	{
		if ($oAttDef instanceof AttributeLinkedSetIndirect) {
			return MetaModel::GetAttributeDef($oAttDef->GetLinkedClass(), $oAttDef->GetExtKeyToRemote())->AllowTargetCreation();
		} else {
			return in_array($oAttDef->GetEditMode(), [LINKSET_EDITMODE_ADDREMOVE, LINKSET_EDITMODE_ADDONLY, LINKSET_EDITMODE_INPLACE, LINKSET_EDITMODE_ACTIONS], true);
		}
	}

}