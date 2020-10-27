<?php
// Copyright (C) 2010-2017 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Class cmdbObject
 *
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * cmdbObjectClass
 * the file to include, then the core is yours
 *
 * @package     iTopORM
 */

require_once('coreexception.class.inc.php');

require_once('config.class.inc.php');
require_once('log.class.inc.php');
require_once('kpi.class.inc.php');

require_once('dict.class.inc.php');

require_once('attributedef.class.inc.php');
require_once('filterdef.class.inc.php');
require_once('stimulus.class.inc.php');
require_once('valuesetdef.class.inc.php');
require_once('MyHelpers.class.inc.php');

require_once('oql/expression.class.inc.php');
require_once('oql/oqlquery.class.inc.php');
require_once('oql/oqlexception.class.inc.php');
require_once('oql/oql-parser.php');
require_once('oql/oql-lexer.php');
require_once('oql/oqlinterpreter.class.inc.php');

require_once('cmdbsource.class.inc.php');
require_once('sqlquery.class.inc.php');
require_once('sqlobjectquery.class.inc.php');
require_once('sqlunionquery.class.inc.php');

require_once('dbobject.class.php');
require_once('dbobjectset.class.php');

require_once('backgroundprocess.inc.php');
require_once('asynctask.class.inc.php');
require_once('dbproperty.class.inc.php');

// db change tracking data model
require_once('cmdbchange.class.inc.php');
require_once('cmdbchangeop.class.inc.php');

// customization data model
// Romain: temporary moved into application.inc.php (see explanations there)
//require_once('trigger.class.inc.php');
//require_once('action.class.inc.php');

// application log
// Romain: temporary moved into application.inc.php (see explanations there)
//require_once('event.class.inc.php');

require_once('templatestring.class.inc.php');
require_once('csvparser.class.inc.php');
require_once('bulkchange.class.inc.php');

/**
 * A persistent object, which changes are accurately recorded
 *
 * @package     iTopORM
 */
abstract class CMDBObject extends DBObject
{
	protected $m_datCreated;
	protected $m_datUpdated;
	// Note: this value is static, but that could be changed because it is sometimes a real issue (see update of interfaces / connected_to
	protected static $m_oCurrChange = null;
	protected static $m_sInfo = null; // null => the information is built in a standard way
	protected static $m_sOrigin = null; // null => the origin is 'interactive'

	/**
	 * Specify the change to be used by the API to attach any CMDBChangeOp* object created
	 *
	 * @see SetTrackInfo if CurrentChange is null, then a new one will be create using trackinfo
	 *
	 * @param CMDBChange|null $oChange use null so that the API will recreate a new CMDBChange using TrackInfo & TrackOrigin
	 *     If providing a CMDBChange, you should persist it first ! Indeed the API will automatically create CMDBChangeOp (see
	 *     \CMDBObject::RecordObjCreation / RecordAttChange / RecordObjDeletion for example) and link them to the current change : in
	 *     consequence this CMDBChange must have a key set !
	 *
	 * @since 2.7.2 N°3219 can now reset CMDBChange by passing null
	 * @since 2.7.2 N°3218 PHPDoc about persisting the $oChange parameter first
	 */
	public static function SetCurrentChange($oChange)
	{
		self::$m_oCurrChange = $oChange;
	}

	//
	// Todo: simplify the APIs and do not pass the current change as an argument anymore
	//       SetTrackInfo to be invoked in very few cases (UI.php, CSV import, Data synchro)
	//       SetCurrentChange is an alternative to SetTrackInfo (csv ?)
	//			GetCurrentChange to be called ONCE (!) by CMDBChangeOp::OnInsert ($this->Set('change', ..GetCurrentChange())
	//			GetCurrentChange to create a default change if not already done in the current context
	//
	/**
	 * @param bool $bAutoCreate if true calls {@link CreateChange} to get a new persisted object
	 *
	 * @return \CMDBChange
	 *
	 * @uses CreateChange
	 */
	public static function GetCurrentChange($bAutoCreate = true)
	{
		if ($bAutoCreate && is_null(self::$m_oCurrChange))
		{
			self::CreateChange();
		}
		return self::$m_oCurrChange;
	}

	/**
	 * Override the additional information (defaulting to user name)
	 * A call to this verb should replace every occurence of
	 *    $oMyChange = MetaModel::NewObject("CMDBChange");
	 *    $oMyChange->Set("date", time());
	 *    $oMyChange->Set("userinfo", 'this is done by ... for ...');
	 *    $iChangeId = $oMyChange->DBInsert();
	 *
	 * @see SetCurrentChange to specify a CMDBObject instance instead
	 *
	 * @param string $sInfo
	 */
	public static function SetTrackInfo($sInfo)
	{
		self::$m_sInfo = $sInfo;
	}

	/**
	 * Provides information about the origin of the change
	 *
	 * @see SetTrackInfo
	 * @see SetCurrentChange to specify a CMDBObject instance instead
	 *
	 * @param $sOrigin String: one of: interactive, csv-interactive, csv-import.php, webservice-soap, webservice-rest, syncho-data-source,
	 *     email-processing, custom-extension
	 */
	public static function SetTrackOrigin($sOrigin)
	{
		self::$m_sOrigin = $sOrigin;
	}
	
	/**
	 * Get the additional information (defaulting to user name)
	 */	 	
	protected static function GetTrackInfo()
	{
		if (is_null(self::$m_sInfo))
		{
			return CMDBChange::GetCurrentUserName();
		}
		else
		{
			return self::$m_sInfo;
		}
	}
	
	/**
	 * Get the 'origin' information (defaulting to 'interactive')
	 */	 	
	protected static function GetTrackOrigin()
	{
		if (is_null(self::$m_sOrigin))
		{
			return 'interactive';
		}
		else
		{
			return self::$m_sOrigin;
		}
	}

	/**
	 * Set to {@link $m_oCurrChange} a standard change record (done here 99% of the time, and nearly once per page)
	 *
	 * The CMDBChange is persisted so that it has a key > 0, and any new CMDBChangeOp can link to it
	 */
	protected static function CreateChange()
	{
		self::$m_oCurrChange = MetaModel::NewObject("CMDBChange");
		self::$m_oCurrChange->Set("date", time());
		self::$m_oCurrChange->Set("userinfo", self::GetTrackInfo());
		self::$m_oCurrChange->Set("origin", self::GetTrackOrigin());
		self::$m_oCurrChange->DBInsert();
	}

	/**
	 * @inheritdoc
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	protected function RecordObjCreation()
	{
		// Delete any existing change tracking about the current object (IDs can be reused due to InnoDb bug; see TRAC #886)
		//
		// 1 - remove the deletion record(s)
		// Note that objclass contain the ROOT class
		$oFilter = new DBObjectSearch('CMDBChangeOpDelete');
		$oFilter->AddCondition('objclass', MetaModel::GetRootClass(get_class($this)), '=');
		$oFilter->AddCondition('objkey', $this->GetKey(), '=');
		MetaModel::PurgeData($oFilter);
		// 2 - any other change tracking information left prior to 2.0.3 (when the purge of the history has been implemented in RecordObjDeletion
		// In that case, objclass is the final class of the object
		$oFilter = new DBObjectSearch('CMDBChangeOp');
		$oFilter->AddCondition('objclass', get_class($this), '=');
		$oFilter->AddCondition('objkey', $this->GetKey(), '=');
		MetaModel::PurgeData($oFilter);

		parent::RecordObjCreation();

		$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpCreate");
		$oMyChangeOp->Set("objclass", get_class($this));
		$oMyChangeOp->Set("objkey", $this->GetKey());
		$iId = $oMyChangeOp->DBInsertNoReload();
	}

	protected function RecordObjDeletion($objkey)
	{
		$sRootClass = MetaModel::GetRootClass(get_class($this));

		// Delete any existing change tracking about the current object
		$oFilter = new DBObjectSearch('CMDBChangeOp');
		$oFilter->AddCondition('objclass', get_class($this), '=');
		$oFilter->AddCondition('objkey', $objkey, '=');
		MetaModel::PurgeData($oFilter);

		parent::RecordObjDeletion($objkey);
		$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpDelete");
		$oMyChangeOp->Set("objclass", MetaModel::GetRootClass(get_class($this)));
		$oMyChangeOp->Set("objkey", $objkey);
		$oMyChangeOp->Set("fclass", get_class($this));
		$oMyChangeOp->Set("fname", substr($this->GetRawName(), 0, 255)); // Protect against very long friendly names
		$iId = $oMyChangeOp->DBInsertNoReload();
	}

	/**
	 * @param string $sAttCode
	 * @param $original Original value
	 * @param $value Current value
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	protected function RecordAttChange($sAttCode, $original, $value)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		if ($oAttDef->IsExternalField()) return;
		if ($oAttDef->IsLinkSet()) return;
		if ($oAttDef->GetTrackingLevel() == ATTRIBUTE_TRACKING_NONE) return;

		if ($oAttDef instanceOf AttributeOneWayPassword)
		{
			// One Way encrypted passwords' history is stored -one way- encrypted
			$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeOneWayPassword");
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);

			if (is_null($original))
			{
				$original = '';
			}
			$oMyChangeOp->Set("prev_pwd", $original);
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
		elseif ($oAttDef instanceOf AttributeEncryptedString)
		{
			// Encrypted string history is stored encrypted
			$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeEncrypted");
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);

			if (is_null($original))
			{
				$original = '';
			}
			$oMyChangeOp->Set("prevstring", $original);
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
		elseif ($oAttDef instanceOf AttributeBlob)
		{
			// Data blobs
			$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeBlob");
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);

			if (is_null($original))
			{
				$original = new ormDocument();
			}
			$oMyChangeOp->Set("prevdata", $original);
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
		elseif ($oAttDef instanceOf AttributeStopWatch)
		{
			// Stop watches - record changes for sub items only (they are visible, the rest is not visible)
			//
			foreach ($oAttDef->ListSubItems() as $sSubItemAttCode => $oSubItemAttDef)
			{
				$item_value = $oAttDef->GetSubItemValue($oSubItemAttDef->Get('item_code'), $value, $this);
				$item_original = $oAttDef->GetSubItemValue($oSubItemAttDef->Get('item_code'), $original, $this);

				if ($item_value != $item_original)
				{
					$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeScalar");
					$oMyChangeOp->Set("objclass", get_class($this));
					$oMyChangeOp->Set("objkey", $this->GetKey());
					$oMyChangeOp->Set("attcode", $sSubItemAttCode);

					$oMyChangeOp->Set("oldvalue", $item_original);
					$oMyChangeOp->Set("newvalue", $item_value);
					$iId = $oMyChangeOp->DBInsertNoReload();
				}
			}
		}
		elseif ($oAttDef instanceOf AttributeCaseLog)
		{
			$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeCaseLog");
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);

			$oMyChangeOp->Set("lastentry", $value->GetLatestEntryIndex());
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
		elseif ($oAttDef instanceOf AttributeLongText)
		{
			// Data blobs
			if ($oAttDef->GetFormat() == 'html')
			{
				$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeHTML");
			}
			else
			{
				$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeLongText");
			}
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);

			if (!is_null($original) && ($original instanceof ormCaseLog))
			{
				$original = $original->GetText();
			}
			$oMyChangeOp->Set("prevdata", $original);
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
		elseif ($oAttDef instanceOf AttributeText)
		{
			// Data blobs
			if ($oAttDef->GetFormat() == 'html')
			{
				$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeHTML");
			}
			else
			{
				$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeText");
			}
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);

			if (!is_null($original) && ($original instanceof ormCaseLog))
			{
				$original = $original->GetText();
			}
			$oMyChangeOp->Set("prevdata", $original);
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
		elseif ($oAttDef instanceOf AttributeBoolean)
		{
			$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeScalar");
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);
			$oMyChangeOp->Set("oldvalue", $original ? 1 : 0);
			$oMyChangeOp->Set("newvalue", $value ? 1 : 0);
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
		elseif ($oAttDef instanceOf AttributeHierarchicalKey)
		{
			// Hierarchical keys
			//
			$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeScalar");
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);
			$oMyChangeOp->Set("oldvalue", $original);
			$oMyChangeOp->Set("newvalue", $value[$sAttCode]);
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
		elseif ($oAttDef instanceOf AttributeCustomFields)
		{
			// Custom fields
			//
			$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeCustomFields");
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);
			$oMyChangeOp->Set("prevdata", json_encode($original->GetValues()));
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
		elseif ($oAttDef instanceOf AttributeURL)
		{
			// URLs
			//
			$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeURL");
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);
			$oMyChangeOp->Set("oldvalue", $original);
			$oMyChangeOp->Set("newvalue", $value);
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
        elseif ($oAttDef instanceOf AttributeSet)
        {
            // Tag Set
            //
            $oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeTagSet");
            $oMyChangeOp->Set("objclass", get_class($this));
            $oMyChangeOp->Set("objkey", $this->GetKey());
            $oMyChangeOp->Set("attcode", $sAttCode);
            $oMyChangeOp->Set("oldvalue", implode(' ', $original->GetValues()));
            $oMyChangeOp->Set("newvalue", implode(' ', $value->GetValues()));
            $iId = $oMyChangeOp->DBInsertNoReload();
        }
        else
		{
			// Scalars
			//
			$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpSetAttributeScalar");
			$oMyChangeOp->Set("objclass", get_class($this));
			$oMyChangeOp->Set("objkey", $this->GetKey());
			$oMyChangeOp->Set("attcode", $sAttCode);
			$oMyChangeOp->Set("oldvalue", $original);
			$oMyChangeOp->Set("newvalue", $value);
			$iId = $oMyChangeOp->DBInsertNoReload();
		}
	}

	/**
	 * @param array $aValues
	 * @param array $aOrigValues
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	protected function RecordAttChanges(array $aValues, array $aOrigValues)
	{
		parent::RecordAttChanges($aValues, $aOrigValues);

		// $aValues is an array of $sAttCode => $value
		//
		foreach ($aValues as $sAttCode=> $value)
		{
			if (array_key_exists($sAttCode, $aOrigValues))
			{
				$original = $aOrigValues[$sAttCode];
			}
			else
			{
				$original = null;
			}
			$this->RecordAttChange($sAttCode, $original, $value);
		}
	}

	/**
	 * Helper to ultimately check user rights before writing (Insert, Update or Delete)
	 * The check should never fail, because the UI should prevent from such a usage
	 * Anyhow, if the user has found a workaround... the security gets enforced here	 	 
	 */
	protected function CheckUserRights($bSkipStrongSecurity, $iActionCode)
	{
		if (is_null($bSkipStrongSecurity))
		{
			// This is temporary
			// We have implemented this safety net right before releasing iTop 1.0
			// and we decided that it was too risky to activate it
			// Anyhow, users willing to have a very strong security could set
			// skip_strong_security = 0, in the config file
			$bSkipStrongSecurity = MetaModel::GetConfig()->Get('skip_strong_security');
		}
		if (!$bSkipStrongSecurity)
		{
			$sClass = get_class($this);
			$oSet = DBObjectSet::FromObject($this);
			if (!UserRights::IsActionAllowed($sClass, $iActionCode, $oSet))
			{
				// Intrusion detected
				throw new SecurityException('You are not allowed to modify objects of class: '.$sClass);
			}
		}
	}

	/**
	 * @deprecated 2.7.0 N°2361 simply use {@link DBInsert} instead, that will automatically create and persist a CMDBChange object.
	 *    If you need to persist your own, call {@link CMDBObject::SetCurrentChange} before.
	 *
	 * @param \CMDBChange $oChange
	 * @param null $bSkipStrongSecurity
	 *
	 * @return int|null
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \SecurityException
	 */
	public function DBInsertTracked(CMDBChange $oChange, $bSkipStrongSecurity = null)
	{
		self::SetCurrentChange($oChange);
		$this->CheckUserRights($bSkipStrongSecurity, UR_ACTION_MODIFY);
		$ret = $this->DBInsertTracked_Internal();
		return $ret;
	}

	/**
	 * @deprecated 2.7.0 N°2361 simply use {@link DBInsertNoReload} instead, that will automatically create and persist a CMDBChange object.
	 *    If you need to persist your own, call {@link CMDBObject::SetCurrentChange} before.
	 *
	 * @param \CMDBChange $oChange
	 * @param null $bSkipStrongSecurity
	 *
	 * @return int
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \SecurityException
	 */
	public function DBInsertTrackedNoReload(CMDBChange $oChange, $bSkipStrongSecurity = null)
	{
		self::SetCurrentChange($oChange);
		$this->CheckUserRights($bSkipStrongSecurity, UR_ACTION_MODIFY);
		$ret = $this->DBInsertTracked_Internal(true);
		return $ret;
	}

	/**
	 * @deprecated 2.7.0 N°2361 simply use {@link DBInsert} or {@link DBInsertNoReload} instead
	 *
	 * @param bool $bDoNotReload
	 *
	 * @return integer Identifier of the created object
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	protected function DBInsertTracked_Internal($bDoNotReload = false)
	{
		if ($bDoNotReload)
		{
			$ret = $this->DBInsertNoReload();
		}
		else
		{
			$ret = $this->DBInsert();
		}
		return $ret;
	}

	public function DBClone($newKey = null)
	{
		return $this->DBCloneTracked_Internal();
	}

	public function DBCloneTracked(CMDBChange $oChange, $newKey = null)
	{
		self::SetCurrentChange($oChange);
		$this->DBCloneTracked_Internal($newKey);
	}

	protected function DBCloneTracked_Internal($newKey = null)
	{
		$newKey = parent::DBClone($newKey);
		$oClone = MetaModel::GetObject(get_class($this), $newKey); 

		return $newKey;
	}

	public function DBUpdate()
	{
		// Copy the changes list before the update (the list should be reset afterwards)
		$aChanges = $this->ListChanges();
		if (count($aChanges) == 0)
		{
			return;
		}
		
		$ret = parent::DBUpdate();
		return $ret;
	}

	/**
	 * @deprecated 2.7.0 N°2361 simply use {@link DBUpdate} instead, that will automatically create and persist a CMDBChange object.
	 *    If you need to persist your own, call {@link CMDBObject::SetCurrentChange} before.
	 *
	 * @param \CMDBChange $oChange
	 * @param null $bSkipStrongSecurity
	 *
	 * @return int|void
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \SecurityException
	 */
	public function DBUpdateTracked(CMDBChange $oChange, $bSkipStrongSecurity = null)
	{
		self::SetCurrentChange($oChange);
		$this->CheckUserRights($bSkipStrongSecurity, UR_ACTION_MODIFY);
		$this->DBUpdate();
	}

	/**
	 * @param null $oDeletionPlan
	 *
	 * @return \DeletionPlan|null
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function DBDelete(&$oDeletionPlan = null)
	{
		return $this->DBDeleteTracked_Internal($oDeletionPlan);
	}

	/**
	 * @deprecated 2.7.0 N°2361 simply use {@link DBDelete} instead, that will automatically create and persist a CMDBChange object.
	 *    If you need to persist your own, call {@link CMDBObject::SetCurrentChange} before.
	 *
	 * @param \CMDBChange $oChange
	 * @param null $bSkipStrongSecurity
	 * @param null $oDeletionPlan
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \SecurityException
	 */
	public function DBDeleteTracked(CMDBChange $oChange, $bSkipStrongSecurity = null, &$oDeletionPlan = null)
	{
		self::SetCurrentChange($oChange);
		$this->CheckUserRights($bSkipStrongSecurity, UR_ACTION_DELETE);
		$this->DBDeleteTracked_Internal($oDeletionPlan);
	}

	/**
	 * @param null $oDeletionPlan
	 *
	 * @return \DeletionPlan|null
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	protected function DBDeleteTracked_Internal(&$oDeletionPlan = null)
	{
		$ret = parent::DBDelete($oDeletionPlan);
		return $ret;
	}

	public static function BulkUpdate(DBSearch $oFilter, array $aValues)
	{
		return static::BulkUpdateTracked_Internal($oFilter, $aValues);
	}

	public static function BulkUpdateTracked(CMDBChange $oChange, DBSearch $oFilter, array $aValues)
	{
		self::SetCurrentChange($oChange);
		static::BulkUpdateTracked_Internal($oFilter, $aValues);
	}

	protected static function BulkUpdateTracked_Internal(DBSearch $oFilter, array $aValues)
	{
		// $aValues is an array of $sAttCode => $value

		// Get the list of objects to update (and load it before doing the change)
		$oObjSet = new CMDBObjectSet($oFilter);
		$oObjSet->Load();

		// Keep track of the previous values (will be overwritten when the objects are synchronized with the DB)
		$aOriginalValues = array();
		$oObjSet->Rewind();
		while ($oItem = $oObjSet->Fetch())
		{
			$aOriginalValues[$oItem->GetKey()] = $oItem->m_aOrigValues;
		}

		// Update in one single efficient query
		$ret = parent::BulkUpdate($oFilter, $aValues);

		// Record... in many queries !!!
		$oObjSet->Rewind();
		while ($oItem = $oObjSet->Fetch())
		{
			$aChangedValues = $oItem->ListChangedValues($aValues);
			$oItem->RecordAttChanges($aChangedValues, $aOriginalValues[$oItem->GetKey()]);
		}
		return $ret;
	}

	public function DBArchive()
	{
		// Note: do the job anyway, so as to repair any DB discrepancy
		$bOriginal = $this->Get('archive_flag');
		parent::DBArchive();

		if (!$bOriginal)
		{
			utils::PushArchiveMode(false);
			$this->RecordAttChange('archive_flag', false, true);
			utils::PopArchiveMode();
		}
	}

	public function DBUnarchive()
	{
		// Note: do the job anyway, so as to repair any DB discrepancy
		$bOriginal = $this->Get('archive_flag');
		parent::DBUnarchive();

		if ($bOriginal)
		{
			utils::PushArchiveMode(false);
			$this->RecordAttChange('archive_flag', true, false);
			utils::PopArchiveMode();
		}
	}
}



/**
 * TODO: investigate how to get rid of this class that was made to workaround some language limitation... or a poor design!
 *
 * @package     iTopORM
 *
 * @internal
 */
class CMDBObjectSet extends DBObjectSet
{
	// this is the public interface (?)
	
	// I have to define those constructors here... :-(
	// just to get the right object class in return.
	// I have to think again to those things: maybe it will work fine if a have a constructor define here (?)
	
	static public function FromScratch($sClass)
	{
		$oFilter = new DBObjectSearch($sClass);
		$oFilter->AddConditionExpression(new FalseExpression());
		$oRetSet = new self($oFilter);
		// NOTE: THIS DOES NOT WORK IF m_bLoaded is private in the base class (and you will not get any error message)
		$oRetSet->m_bLoaded = true; // no DB load
		return $oRetSet;
	} 

	// create an object set ex nihilo
	// input = array of objects
	static public function FromArray($sClass, $aObjects)
	{
		$oRetSet = self::FromScratch($sClass);
		$oRetSet->AddObjectArray($aObjects, $sClass);
		return $oRetSet;
	} 

	static public function FromArrayAssoc($aClasses, $aObjects)
	{
		// In a perfect world, we should create a complete tree of DBObjectSearch,
		// but as we lack most of the information related to the objects,
		// let's create one search definition
		$sClass = reset($aClasses);
		$sAlias = key($aClasses);
		$oFilter = new DBObjectSearch($sClass, $sAlias);

		$oRetSet = new CMDBObjectSet($oFilter);
		$oRetSet->m_bLoaded = true; // no DB load

		foreach($aObjects as $rowIndex => $aObjectsByClassAlias)
		{
			$oRetSet->AddObjectExtended($aObjectsByClassAlias);
		}
		return $oRetSet;
	}
}
