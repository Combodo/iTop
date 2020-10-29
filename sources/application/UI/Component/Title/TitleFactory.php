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
		$sObjIconUrl = $oObject->GetIcon(false);
		$sObjClass = get_class($oObject);
		$sObjClassName = MetaModel::GetName($sObjClass);
		$sObjName = $oObject->GetName();

		$oTitle = new TitleForObjectDetails($sObjClassName, $sObjName, $sId);
		$oTitle->SetIcon($sObjIconUrl);

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