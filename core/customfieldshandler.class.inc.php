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
 * Base class to implement a handler for AttributeCustomFields
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

abstract class CustomFieldsHandler {
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
	 *     } $aValues same as {@see \ormCustomFieldsValue::$aCurrentValues}
	 */
	protected $aValues;
	/** @var \Combodo\iTop\Form\Form $oForm */
	protected $oForm;

	/**
	 * This constructor's prototype must be frozen.
	 * Any specific behavior must be implemented in BuildForm()
	 *
	 * @param $sAttCode
	 */
	final public function __construct($sAttCode) {
		$this->sAttCode = $sAttCode;
		$this->aValues = null;
	}

	abstract public function BuildForm(DBObject $oHostObject, $sFormId);

	/**
	 * @returns true|string true if no error found, error message otherwise
	 * @throws \ApplicationException if {@link static::$oForm} attribute not initialized yet
	 * @since 3.1.0 N°6322 N°1150 Add template_id checks
	 */
	public function Validate(DBObject $oHostObject) {
		if (false === isset($this->oForm)) {
			throw new ApplicationException('oForm attribute not init yet. You must call BuildForm before this method !');
		}

		try {
			$this->oForm->Validate();
			if ($this->oForm->GetValid()) {
				$ret = true;
			}
			else {
				$aMessages = array();
				foreach ($this->oForm->GetErrorMessages() as $sFieldId => $aFieldMessages) {
					$aMessages[] = $sFieldId.': '.implode(', ', $aFieldMessages);
				}
				$ret = 'Invalid value: '.implode(', ', $aMessages);
			}
		} catch (Exception $e) {
			$ret = $e->getMessage();
		}

		return $ret;
	}

	/**
	 *
	 * @return \Combodo\iTop\Form\Form
	 */
	public function GetForm() {
		return $this->oForm;
	}

	public function SetCurrentValues($aValues)
	{
		$this->aValues = $aValues;
	}

	public static function GetPrerequisiteAttributes($sClass = null) {
		return array();
	}

	/**
	 * List the available verbs for 'GetForTemplate'
	 */
	public static function EnumTemplateVerbs() {
		return array();
	}

	/**
	 * Get various representations of the value, for insertion into a template (e.g. in Notifications)
	 * @param $aValues array The current values
	 * @param $sVerb string The verb specifying the representation of the value
	 * @param $bLocalize bool Whether or not to localize the value
	 * @return string
	 */
	abstract public function GetForTemplate($aValues, $sVerb, $bLocalize = true);

	/**
	 * @param $aValues
	 * @param bool|true $bLocalize
	 * @return mixed
	 */
	abstract public function GetAsHTML($aValues, $bLocalize = true);

	/**
	 * @param $aValues
	 * @param bool|true $bLocalize
	 * @return mixed
	 */
	abstract public function GetAsXML($aValues, $bLocalize = true);

	/**
	 * @param $aValues
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param bool|true $bLocalize
	 *
	 * @return mixed
	 */
	abstract public function GetAsCSV($aValues, $sSeparator = ',', $sTextQualifier = '"', $bLocalize = true);

	/**
	 * @param $aValues
	 *
	 * @return array|null
	 *
	 * @since 3.1.0 N°1150 Method creation
	 */
	public function GetAsJSON($aValues)
	{
		// Other GetAsCSV/GetAsHTML/GetAsXML methods are abstract, but were here from the start
		// To ensure backward compatibility with older extensions, we are defining a default impl for this method
		// Older extensions might have children classes without this new method
		return null;
	}

	/**
	 * @param \stdClass|null $json
	 * @param string $sAttCode
	 *
	 * @return \ormCustomFieldsValue|null
	 *
	 * @since 3.1.0 N°1150 Method creation
	 */
	public function FromJSONToValue(?stdClass $json, string $sAttCode): ?ormCustomFieldsValue
	{
		// Default impl doing nothing, to avoid errors on children not having this method
		return null;
	}


	/**
	 * @param DBObject $oHostObject
	 *
	 * @return array Associative array id => value
	 */
	abstract public function ReadValues(DBObject $oHostObject);

	/**
	 * Record the data (currently in the processing of recording the host object)
	 * It is assumed that the data has been checked prior to calling Write()
	 * @param DBObject $oHostObject
	 * @param array Associative array id => value
	 */
	abstract public function WriteValues(DBObject $oHostObject, $aValues);

	/**
	 * Cleanup data upon object deletion (object id still available here)
	 * @param DBObject $oHostObject
	 */
	abstract public function DeleteValues(DBObject $oHostObject);

	/**
	 * @param $aValuesA
	 * @param $aValuesB
	 * @return bool
	 */
	abstract public function CompareValues($aValuesA, $aValuesB);

	/**
	 * String representation of the value, must depend solely on the semantics
	 * @return string
	 */
	abstract public function GetValueFingerprint();
}
