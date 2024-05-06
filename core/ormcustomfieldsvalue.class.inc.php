<?php
// Copyright (C) 2024 Combodo SAS
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
 * Base class to hold the value managed by {@see CustomFieldsHandler} and {@see AttributeCustomFields}
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class ormCustomFieldsValue
{
	/** @var \DBObject|null $oHostObject */
	protected $oHostObject;
	/** @var string $sAttCode */
	protected $sAttCode;
	/** @var array{
	 *          legacy: int,
	 *          extradata_id: string,
	 *          _template_name: string,
	 *          template_id: string,
	 *          template_data: string,
	 *          user_data: array<string, mixed>,
	 *          current_template_id: string,
	 *          current_template_data: string,
	 *     } $aCurrentValues Containing JSON encoded strings in template_data/current_template_data.
	 *          The user_data key contains an array with field code as key and field value as value
	 *          Warning, current_* are mandatory for data to be saved in a DBUpdate() call !
	 */
	protected $aCurrentValues;

	/**
	 * @param \DBObject|null $oHostObject
	 * @param string $sAttCode
	 * @param array $aCurrentValues
	 */
	public function __construct(?DBObject $oHostObject, $sAttCode, $aCurrentValues = null)
	{
		$this->oHostObject = $oHostObject;
		$this->sAttCode = $sAttCode;
		$this->aCurrentValues = $aCurrentValues;
	}

	/**
	 * @return \DBObject|null
	 */
	public function GetHostObject(): ?DBObject
	{
		return $this->oHostObject;
	}

	/**
	 * @param \DBObject|null $oHostObject
	 *
	 * @return void
	 */
	public function SetHostObject(?DBObject $oHostObject): void
	{
		$this->oHostObject = $oHostObject;
	}

	public function GetValues()
	{
		return $this->aCurrentValues;
	}

	/**
	 * Wrapper used when the only thing you have is the value...
	 *
	 * @return \Combodo\iTop\Form\Form
	 */
	public function GetForm($sFormPrefix = null)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this->oHostObject), $this->sAttCode);

		return $oAttDef->GetForm($this->oHostObject, $sFormPrefix);
	}

	public function GetAsHTML($bLocalize = true)
	{
		return $this->GetHandler()->GetAsHTML($this->aCurrentValues, $bLocalize);
	}

	public function GetAsXML($bLocalize = true)
	{
		return $this->GetHandler()->GetAsXML($this->aCurrentValues, $bLocalize);
	}

	public function GetAsCSV($sSeparator = ',', $sTextQualifier = '"', $bLocalize = true)
	{
		return $this->GetHandler()->GetAsCSV($this->aCurrentValues, $sSeparator, $sTextQualifier, $bLocalize);
	}

	/**
	 * @return string|array
	 * @throws \Exception
	 * @since 3.1.0 N°1150 Method creation
	 */
	public function GetForJSON()
	{
		return $this->GetHandler()->GetAsJSON($this->aCurrentValues);
	}

	/**
	 * @param string|null $json
	 * @param \AttributeDefinition $oAttDef
	 *
	 * @return \ormCustomFieldsValue
	 *
	 * @since 3.1.0 N°1150 Method creation
	 */
	public static function FromJSONToValue(?stdClass $json, AttributeCustomFields $oAttDef)
	{
		return $oAttDef->GetHandler()->FromJSONToValue($json, $oAttDef->GetCode());
	}

	/**
	 * @return \CustomFieldsHandler
	 * @throws \Exception
	 * @since 3.1.0 N°1150 Method creation
	 */
	final protected function GetHandler()
	{
		/** @var \AttributeCustomFields $oAttDef */
		$oAttDef = MetaModel::GetAttributeDef(get_class($this->oHostObject), $this->sAttCode);

		return $oAttDef->GetHandler($this->GetValues());
	}

	/**
	 * Get various representations of the value, for insertion into a template (e.g. in Notifications)
	 *
	 * @param $sVerb string The verb specifying the representation of the value
	 * @param $bLocalize bool Whether or not to localize the value
	 */
	public function GetForTemplate($sVerb, $bLocalize = true)
	{
		return $this->GetHandler()->GetForTemplate($this->aCurrentValues, $sVerb, $bLocalize);
	}

	/**
	 * @param ormCustomFieldsValue $fellow
	 * @return bool
	 */
	public function Equals(ormCustomFieldsValue $oReference)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this->oHostObject), $this->sAttCode);
		$oHandler = $oAttDef->GetHandler($this->GetValues());
		return $oHandler->CompareValues($this->aCurrentValues, $oReference->aCurrentValues);
	}
}
