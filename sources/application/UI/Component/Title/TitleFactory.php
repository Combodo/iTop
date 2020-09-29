<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Title;


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
		// TODO 2.8.0: Refactor all of this
		$sObjIconUrl = $oObject->GetIcon(false);
		$sObjClass = get_class($oObject);
		$sObjClassName = MetaModel::GetName($sObjClass);
		$sObjName = $oObject->GetName();

		$oTitle = new TitleForObjectDetails($sObjClassName, $sObjName, $sId);
		$oTitle->SetIcon($sObjIconUrl);

		$sStatusAttCode = MetaModel::GetStateAttributeCode($sObjClass);
		if(!empty($sStatusAttCode))
		{
			$sStatusLabel = $oObject->GetStateLabel();
			// TODO 2.8.0 : Dehardcode this
			$sStatusColor = 'green';

			$oTitle->SetStatus($sStatusAttCode, $sStatusLabel, $sStatusColor);
		}

		return $oTitle;
	}
}