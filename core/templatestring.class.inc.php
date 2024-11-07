<?php
// Copyright (C) 2010-2024 Combodo SAS
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
 * Simple helper class to interpret and transform a template string
 *
 * Usage:
 *     $oString = new TemplateString("Blah $this->friendlyname$ is in location $this->location_id->name$ ('$this->location_id->org_id->name$)");
 *     echo $oString->Render(array('this' => $oContact));

 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Helper class
 */
class TemplateStringPlaceholder
{
	public $sToken;
	public $sAttCode;
	public $sFunction;
	public $sParamName;
	public $bIsValid;

	public function __construct($sToken)
	{
		$this->sToken = $sToken;
		$this->sAttCode = '';
		$this->sFunction = '';
		$this->sParamName = '';
		$this->bIsValid = false; // Validity may be false in general, but it can work anyway (thanks to specialization) when rendering
	}
}

/**
 * Class TemplateString
 */
class TemplateString
{
	protected $m_sRaw;
	protected $m_aPlaceholders;

	public function __construct($sRaw)
	{
		$this->m_sRaw = $sRaw;
		$this->m_aPlaceholders = null;
	}

	/**
	 * Split the string into placholders
	 *
	 * @param array $aParamTypes Class of the expected parameters: hash array of '<param_id>' => '<class_name>'
	 *
	 * @throws \Exception
	 */
	protected function Analyze($aParamTypes = array())
	{
		if (!is_null($this->m_aPlaceholders)) return;

		$this->m_aPlaceholders = array();
		if (preg_match_all('/\\$([a-z0-9_]+(->[a-z0-9_]+)*)\\$/', $this->m_sRaw, $aMatches))
		{
			foreach($aMatches[1] as $sPlaceholder)
			{
				$oPlaceholder = new TemplateStringPlaceholder($sPlaceholder);
				$oPlaceholder->bIsValid = false;
				foreach ($aParamTypes as $sParamName => $sClass)
				{
					$sParamPrefix = $sParamName.'->';
					if (substr($sPlaceholder, 0, strlen($sParamPrefix)) == $sParamPrefix)
					{
						// Todo - detect functions (label...)
						$oPlaceholder->sFunction = '';

						$oPlaceholder->sParamName = $sParamName;
						$sAttCode = substr($sPlaceholder, strlen($sParamPrefix));
						$oPlaceholder->sAttCode = $sAttCode;
						$oPlaceholder->bIsValid = MetaModel::IsValidAttCode($sClass, $sAttCode, true /* extended */);
					}
				}

				$this->m_aPlaceholders[] = $oPlaceholder;
			}
		}
	}

	/**
	 * Return the placeholders (for reporting purposes)
	 *
	 * @return array
	 */
	public function GetPlaceholders()
	{
		return $this->m_aPlaceholders;
	}

	/**
	* Check the format when possible
	 *
	 * @param array $aParamTypes Class of the expected parameters: hash array of '<param_id>' => '<class_name>'
	 *
	 * @return boolean
	 */
	public function IsValid($aParamTypes = array())
	{
		$this->Analyze($aParamTypes);

		foreach($this->m_aPlaceholders as $oPlaceholder)
		{
			if (!$oPlaceholder->bIsValid)
			{
				if (count($aParamTypes) == 0)
				{
					return false;
				}
				if (array_key_exists($oPlaceholder->sParamName, $aParamTypes))
				{
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Apply the given parameters to replace the placeholders
	 *
	 * @param array $aParamValues Value of the expected parameters: hash array of '<param_id>' => '<value>'
	 *
	 * @return string
	 */
	public function Render($aParamValues = array())
	{
		$aParamTypes = array();
		foreach($aParamValues as $sParamName => $value)
		{
			$aParamTypes[$sParamName] = get_class($value);
		}
		$this->Analyze($aParamTypes);

		$aSearch = array();
		$aReplace = array();
		foreach($this->m_aPlaceholders as $oPlaceholder)
		{
			if (array_key_exists($oPlaceholder->sParamName, $aParamValues))
			{
				$oRef = $aParamValues[$oPlaceholder->sParamName];
				try
				{
					$value = $oRef->Get($oPlaceholder->sAttCode);
					$aSearch[] = '$'.$oPlaceholder->sToken.'$';
					$aReplace[] = $value;
					$oPlaceholder->bIsValid = true;
				}
				catch(Exception $e)
				{
					$oPlaceholder->bIsValid = false;
				}
			}
			else
			{
				$oPlaceholder->bIsValid = false;
			}
		}
		return str_replace($aSearch, $aReplace, $this->m_sRaw);
	}
}