<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Title;


use Combodo\iTop\Application\UI\Helper\UIHelper;
use DBObject;
use MetaModel;

class TitleFactory
{

	public static function MakeForPage(string $sTitle, ?string $sId = null)
	{
		return new Title($sTitle, 1, $sId);
	}

	public static function MakeForObjectDetails(DBObject $oObject, ?string $sId = null)
	{
		// TODO 3.0.0: Refactor all of this
		$sObjClass = get_class($oObject);
		$sObjClassName = MetaModel::GetName($sObjClass);
		$sObjName = $oObject->GetName();

		// Object icon
		// - Default icon is the class icon
		$sObjIconUrl = $oObject->GetIcon(false);
		// Note: Class icons are a square image with no margin around, so they need to be zoomed out in the medallion
		$sIconCoverMethod = Title::ENUM_ICON_COVER_METHOD_ZOOMOUT;
		// - Use object image from semantic attribute only if it's not the default image
		if(!$oObject->IsNew() && MetaModel::HasImageAttributeCode($sObjClass)){
			$sImageAttCode = MetaModel::GetImageAttributeCode($sObjClass);
			if(!empty($sImageAttCode)){
				/** @var \ormDocument $oImage */
				$oImage = $oObject->Get($sImageAttCode);
				if(!$oImage->IsEmpty()){
					$sObjIconUrl = $oImage->GetDisplayURL($sObjClass, $oObject->GetKey(), $sImageAttCode);
					$sIconCoverMethod = Title::ENUM_ICON_COVER_METHOD_COVER;
				}
			}

		}

		$oTitle = new TitleForObjectDetails($sObjClassName, $sObjName, $sId);

		if(!empty($sObjIconUrl)) {
			$oTitle->SetIcon($sObjIconUrl, $sIconCoverMethod);
		}

		$sStatusAttCode = MetaModel::GetStateAttributeCode($sObjClass);
		if (!empty($sStatusAttCode)) {
			$sStateCode = $oObject->GetState();
			$sStatusLabel = $oObject->GetStateLabel();
			$sStatusColor = UIHelper::GetColorFromStatus(get_class($oObject), $sStateCode);
			$oTitle->SetStatus($sStatusAttCode, $sStatusLabel, $sStatusColor);
		}

		return $oTitle;
	}
}