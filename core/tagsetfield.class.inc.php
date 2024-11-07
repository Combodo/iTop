<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

use Combodo\iTop\Application\WebPage\WebPage;


/**
 * <p>Stores data for {@link AttributeTagSet} fields
 *
 * <p>We will have an implementation for each class/field to be able to customize rights (generated in
 * \MFCompiler::CompileClass).<br> Only this abstract class will exists in the DB : the implementations won't had any
 * new field.
 *
 * @since 2.6.0 N°931 tag fields
 */
abstract class TagSetFieldData extends cmdbAbstractObject
{
	private static $m_aAllowedValues = array();

	/**
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'bizmodel',
			'key_type' => 'autoincrement',
			'name_attcode' => array('label'),
			'state_attcode' => '',
			'reconc_keys' => array('code'),
			'db_table' => 'priv_tagfielddata',
			'db_key_field' => 'id',
			'db_finalclass_field' => 'finalclass',
		);

		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("code", array(
			"allowed_values" => null,
			"sql" => 'code',
			"default_value" => '',
			"is_null_allowed" => false,
			"depends_on" => array(),
			"validation_pattern" => '^[a-zA-Z][a-zA-Z0-9]{2,}$',
		)));
		MetaModel::Init_AddAttribute(new AttributeString("label", array(
			"allowed_values" => null,
			"sql" => 'label',
			"default_value" => '',
			"is_null_allowed" => false,
			"depends_on" => array()
		)));
		MetaModel::Init_AddAttribute(new AttributeHTML("description", array(
			"allowed_values" => null,
			"sql" => 'description',
			"default_value" => '',
			"is_null_allowed" => true,
			"depends_on" => array()
		)));
		MetaModel::Init_AddAttribute(new AttributeString("obj_class", array(
			"allowed_values" => null,
			"sql" => 'obj_class',
			"default_value" => '',
			"is_null_allowed" => false,
			"depends_on" => array()
		)));
		MetaModel::Init_AddAttribute(new AttributeString("obj_attcode", array(
			"allowed_values" => null,
			"sql" => 'obj_attcode',
			"default_value" => '',
			"is_null_allowed" => false,
			"depends_on" => array()
		)));


		MetaModel::Init_SetZListItems('details', array('code', 'label', 'description'));
		MetaModel::Init_SetZListItems('standard_search', array('code', 'label', 'description'));
		MetaModel::Init_SetZListItems('list', array('code', 'label', 'description'));
	}

	public function ComputeValues()
	{
		$sClassName = get_class($this);
		$aRes = static::ExtractTagFieldName($sClassName);
		$this->_Set('obj_class', $aRes['obj_class']);
		$this->_Set('obj_attcode', $aRes['obj_attcode']);
	}

	public static function GetTagDataClassName($sClass, $sAttCode)
	{
		$sTagSuffix = $sClass.'__'.$sAttCode;

		return 'TagSetFieldDataFor_'.$sTagSuffix;
	}

	/**
	 * Extract Tag class and attcode from the TagFieldData class name
	 *
	 * @param $sClassName
	 *
	 * @return string[]
	 * @throws \CoreException
	 */
	public static function ExtractTagFieldName($sClassName)
	{
		$aRes = array();
		// Extract class and attcode from class name using pattern  TagSetFieldDataFor_<class>_<attcode>>;
		if (preg_match('@^TagSetFieldDataFor_(?<class>\w+)__(?<attcode>\w+)$@', $sClassName, $aMatches))
		{
			$aRes['obj_class'] = $aMatches['class'];
			$aRes['obj_attcode'] = $aMatches['attcode'];
		}
		else
		{
			throw new CoreException("Bad Class name format: $sClassName");
		}
		return $aRes;
	}

	/**
	 * @param \DeletionPlan $oDeletionPlan
	 *
	 * @throws \CoreException
	 */
	public function DoCheckToDelete(&$oDeletionPlan)
	{
		parent::DoCheckToDelete($oDeletionPlan);

		$sTagCode = $this->Get('code');
		if ($this->IsCodeUsed($sTagCode))
		{
			$this->m_aDeleteIssues[] = Dict::S('Core:TagSetFieldData:ErrorDeleteUsedTag');
		}
		// Clear cache
		$sClass = $this->Get('obj_class');
		$sAttCode = $this->Get('obj_attcode');
		$sTagDataClass = self::GetTagDataClassName($sClass, $sAttCode);
		unset(self::$m_aAllowedValues[$sTagDataClass]);
	}

	/**
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DoCheckToWrite()
	{
		$this->ComputeValues();
		$sClass = $this->Get('obj_class');
		$sAttCode = $this->Get('obj_attcode');
		$iMaxLen = 20;
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		if ($oAttDef instanceof AttributeTagSet)
		{
			$iMaxLen = $oAttDef->GetTagCodeMaxLength();
		}

		$sTagCode = $this->Get('code');
		// Check code syntax
		$iMax = $iMaxLen - 1;
		if (!preg_match("@^[a-zA-Z][a-zA-Z0-9]{2,$iMax}$@", $sTagCode))
		{
			$this->m_aCheckIssues[] = Dict::Format('Core:TagSetFieldData:ErrorTagCodeSyntax', $iMaxLen);
		}

		// Check that the code is not a MySQL stop word
		$sSQL = "SELECT value FROM information_schema.INNODB_FT_DEFAULT_STOPWORD";
		try
		{
			$aResults = CMDBSource::QueryToArray($sSQL);
		} catch (MySQLException $e)
		{
			IssueLog::Warning($e->getMessage());
			$aResults = array();
		}

		foreach($aResults as $aResult)
		{
			if ($aResult['value'] == $sTagCode)
			{
				$this->m_aCheckIssues[] = Dict::S('Core:TagSetFieldData:ErrorTagCodeReservedWord');
				break;
			}
		}

		$sTagLabel = $this->Get('label');
		$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
		if (empty($sTagLabel) || (strpos($sTagLabel, $sSepItem) !== false))
		{
			// Label must not contain | character
			$this->m_aCheckIssues[] = Dict::Format('Core:TagSetFieldData:ErrorTagLabelSyntax', $sSepItem);
		}

		// Check that code and labels are uniques
		$id = $this->GetKey();
		$sClassName = get_class($this);
		if (empty($id))
		{
			$oSearch = DBSearch::FromOQL("SELECT $sClassName WHERE (code = :tag_code OR label = :tag_label)");
		}
		else
		{
			$oSearch = DBSearch::FromOQL("SELECT $sClassName WHERE id != :id AND (code = :tag_code OR label = :tag_label)");
		}
		$aArgs = array('id' => $id, 'tag_code' => $sTagCode, 'tag_label' => $sTagLabel);
		$oSet = new DBObjectSet($oSearch, array(), $aArgs);
		if ($oSet->CountExceeds(0))
		{
			$this->m_aCheckIssues[] = Dict::S('Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel');
		}
		// Clear cache
		$sTagDataClass = self::GetTagDataClassName($sClass, $sAttCode);
		unset(self::$m_aAllowedValues[$sTagDataClass]);

		parent::DoCheckToWrite();
	}

	/**
	 * @throws \CoreException
	 */
	public function OnUpdate()
	{
		parent::OnUpdate();
		$aChanges = $this->ListChanges();
		if (array_key_exists('code', $aChanges))
		{
			$sTagCode = $this->GetOriginal('code');
			if ($this->IsCodeUsed($sTagCode))
			{
				throw new CoreException(Dict::S('Core:TagSetFieldData:ErrorCodeUpdateNotAllowed'));
			}
		}
		if (array_key_exists('obj_class', $aChanges))
		{
			throw new CoreException(Dict::S('Core:TagSetFieldData:ErrorClassUpdateNotAllowed'));
		}
		if (array_key_exists('obj_attcode', $aChanges))
		{
			throw new CoreException(Dict::S('Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed'));
		}
	}

	private function IsCodeUsed($sTagCode)
	{
		try
		{
			$sClass = $this->Get('obj_class');
			$sAttCode = $this->Get('obj_attcode');
			$oSearch = DBSearch::FromOQL("SELECT $sClass WHERE $sAttCode MATCHES '$sTagCode'");
			$oSet = new DBObjectSet($oSearch);
			if ($oSet->CountExceeds(0))
			{
				return true;
			}
		}
		catch (Exception $e)
		{
			IssueLog::Warning($e->getMessage());
		}
		return false;
	}

	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		if ($sAttCode == 'code')
		{
			if ((!$this->IsNew()) && ($this->IsCodeUsed($this->Get('code'))))
			{
				return OPT_ATT_READONLY;
			}
		}

		return parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
	}

	/**
	 * Display Tag Usage
	 *
	 * @param WebPage $oPage
	 * @param bool $bEditMode
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		parent::DisplayBareRelations($oPage, $bEditMode);
		if (!$bEditMode)
		{
			$sClass = $this->Get('obj_class');
			$sAttCode = $this->Get('obj_attcode');
			$sTagCode = $this->Get('code');


			$oFilter = DBSearch::FromOQL("SELECT $sClass WHERE $sAttCode MATCHES '$sTagCode'");
			$oSet = new DBObjectSet($oFilter);
			$iCount = $oSet->Count();
			$oPage->SetCurrentTab('Core:TagSetFieldData:WhereIsThisTagTab', Dict::Format('Core:TagSetFieldData:WhereIsThisTagTab', $iCount));

			if ($iCount === 0)
			{
				$sNoEntries = Dict::S('Core:TagSetFieldData:NoEntryFound');
				$oPage->add("<p>$sNoEntries</p>");

				return;
			}

			$oFilter = DBSearch::FromOQL("SELECT $sClass WHERE $sAttCode MATCHES '$sTagCode'");
			$oSet = new DBObjectSet($oFilter);
			if ($oSet->CountExceeds(0))
			{
				$sClassLabel = MetaModel::GetName($sClass);
				$oPage->add("<h2>$sClassLabel</h2>");
				$oResultBlock = new DisplayBlock($oFilter, 'list', false);
				$oResultBlock->Display($oPage, 1);
			}
		}
	}

	public static function GetClassName($sClass)
	{
		if ($sClass == 'TagSetFieldData')
		{
			$aWords = preg_split('/(?=[A-Z]+)/', $sClass);
			return trim(implode(' ', $aWords));
		}
		try
		{
			$aTagFieldInfo = self::ExtractTagFieldName($sClass);
		} catch (CoreException $e)
		{
			return $sClass;
		}
		$sClassDesc = MetaModel::GetName($aTagFieldInfo['obj_class']);
		$sAttDesc = MetaModel::GetAttributeDef($aTagFieldInfo['obj_class'], $aTagFieldInfo['obj_attcode'])->GetLabel();
		if (Dict::Exists("Class:$sClass"))
		{
			$sName = Dict::Format("Class:$sClass", $sClassDesc, $sAttDesc);
		}
		else
		{
			$sName = Dict::Format('Class:TagSetFieldData', $sClassDesc, $sAttDesc);
		}
		return $sName;
	}

	/**
	 * @param $sClass
	 * @param $sAttCode
	 *
	 * @return \TagSetFieldData[]
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function GetAllowedValues($sClass, $sAttCode)
	{
		$sClass = MetaModel::GetAttributeOrigin($sClass, $sAttCode);
		$sTagDataClass = self::GetTagDataClassName($sClass, $sAttCode);
		if (!isset(self::$m_aAllowedValues[$sTagDataClass]))
		{
			$oSearch = new DBObjectSearch($sTagDataClass);
			$oSearch->AddCondition('obj_class', $sClass);
			$oSearch->AddCondition('obj_attcode', $sAttCode);
			$oSet = new DBObjectSet($oSearch);
			self::$m_aAllowedValues[$sTagDataClass] = $oSet->ToArray();
		}

		return self::$m_aAllowedValues[$sTagDataClass];
	}
}