<?php

namespace Combodo\iTop\Application\Helper;


use ArchivedObjectException;
use CMDBChangeOpSetAttributeLongText;
use CoreException;
use CoreUnexpectedValue;
use DBObjectSearch;
use DBObjectSet;
use MetaModel;
use MySQLException;
use OQLException;

class CMDBChangeHelper {
	/**
	 * @returns mixed
	 *
	 * @throws CoreException
	 * @throws MySQLException
	 * @throws CoreUnexpectedValue
	 * @throws OQLException
	 * @throws ArchivedObjectException
	 */
	public static function GetAttributeNewValueFromChangeOp(CMDBChangeOpSetAttributeLongText $oChangeOp)
	{
		$sObjectClass = $oChangeOp->Get('objclass');
		$sObjectKey = $oChangeOp->Get('objkey');
		$sAttCode = $oChangeOp->Get('attcode');
		$sChangeOpDate = $oChangeOp->Get('date');

		$sObjectFollowingChangeOp = 'SELECT ' . CMDBChangeOpSetAttributeLongText::class . ' WHERE objclass = :objclass AND objkey = :objkey AND attcode = :attcode AND date >= :ChangeOpDate AND id > :OrigChangeOpId';
		$oObjectFollowingChangeOpFilter = DBObjectSearch::FromOQL($sObjectFollowingChangeOp, [
			'objclass'=> $sObjectClass,
			'objkey'=> $sObjectKey,
			'attcode'=>$sAttCode,
			'ChangeOpDate' => $sChangeOpDate,
			'OrigChangeOpId' => $oChangeOp->GetKey(),
		]);
		$oSet = new DBObjectSet($oObjectFollowingChangeOpFilter, ['date'=>true]);
		$oChangeOpNew = $oSet->Fetch();

		if (\is_null($oChangeOpNew)) {
			try {
				$oObject = MetaModel::GetObject($sObjectClass, $sObjectKey, false);
			} catch (ArchivedObjectException |CoreException $e) {
				$oObject = null;
			}
			if (\is_null($oObject)) {
				throw new CoreUnexpectedValue("Cannot load object $sObjectClass:$sObjectKey referenced in the ".CMDBChangeOpSetAttributeLongText::class.':'.$oChangeOp->GetKey().' instance');
			}
			return $oObject->Get($sAttCode);
		}

		return $oChangeOpNew->Get('prevdata');
	}
}