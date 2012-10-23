<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * data generator
 * helps the consultants in creating dummy data sets, for various test purposes (validation, usability, scalability) 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Data Generator helper class
 *
 * This class is useful to generate a lot of sample data that look consistent
 * for a given organization in order to simulate a real CMDB
 */
class cmdbDataGenerator
{
	protected $m_sOrganizationKey;
	protected $m_sOrganizationCode;
	protected $m_sOrganizationName;
	protected $m_OrganizationDomains;
	
	/**
	 * Constructor
	 */
	public function __construct($sOrganizationId = "")
	{
		global $aCompanies, $aCompaniesCode;
		if ($sOrganizationId == '')
		{
			// No organization provided, pick a random and unused one from our predefined list
			$retries = 5*count($aCompanies);
			while ( ($retries > 0) && !isset($this->m_sOrganizationCode)) // Stupid algorithm, but I'm too lazy to do something bulletproof tonight
			{
				$index = rand(0, count($aCompanies) - 1);
				if (!$this->OrganizationExists($aCompanies[$index]['code']))
				{
					$this->m_sOrganizationCode = $aCompanies[$index]['code'];
					$this->m_sOrganizationName = $aCompanies[$index]['name'];
					$this->m_OrganizationDomains = $aCompanies[$index]['domain'];
				}
				$retries--;
			}
		}
		else
		{
			// A code has been provided, let's take the information we need from the organization itself
			$this->m_sOrganizationId = $sOrganizationId;
			$oOrg = $this->GetOrganization($sOrganizationId);
			if ($oOrg == null)
			{
				echo "Unable to find the organization '$sOrganisationCode' in the database... can not add objects into this organization.<br/>\n";
				exit();
			}
			$this->m_sOrganizationCode = $oOrg->Get('code');
			$this->m_sOrganizationName = $oOrg->Get('name');
			if (!isset($aCompaniesCode[$this->m_sOrganizationCode]['domain']))
			{
				// Generate some probable domain names for this organization
				$this->m_OrganizationDomains = array(strtolower($this->m_sOrganizationCode).".com", strtolower($this->m_sOrganizationCode).".org", strtolower($this->m_sOrganizationCode)."corp.net",);
			}
			else
			{
				// Pick the domain names for this organization from the predefined list
				$this->m_OrganizationDomains = $aCompaniesCode[$this->m_sOrganizationCode]['domain'];
			}
		}
		
		if (!isset($this->m_sOrganizationCode))
		{
			echo "Unable to find an organization code which is not already used... can not create a new organization. Enhance the list of fake organizations (\$aCompanies in data_sample.inc.php).<br/>\n";
			exit();
		}
	}
	
	/**
	 * Get the current organization id used by the generator
	 *
	 * @return string The organization id
	 */
	public function GetOrganizationId()
	{
		return $this->m_sOrganizationId;
	}
	
	/**
	 * Get the current organization id used by the generator
	 *
	 * @param string The organization id
	 * @return none
	 */
	public function SetOrganizationId($sId)
	{
		$this->m_sOrganizationId = $sId;
	}
	
	/**
	 * Get the current organization code used by the generator
	 *
	 * @return string The organization code
	 */
	public function GetOrganizationCode()
	{
		return $this->m_sOrganizationCode;
	}

	/**
	 * Get the current organization name used by the generator
	 *
	 * @return string The organization name
	 */
	function GetOrganizationName()
	{
		return $this->m_sOrganizationName;
	}
	
	/**
	 * Get a pseudo random first name taken from a (big) prefedined list
	 *
	 * @return string A random first name
	 */
	function GenerateFirstName()
	{
		global $aFirstNames;
		return $aFirstNames[rand(0, count($aFirstNames) - 1)];
	}
	
	/**
	 * Get a pseudo random last name taken from a (big) prefedined list
	 *
	 * @return string A random last name
	 */
	function GenerateLastName()
	{
		global $aNames;
		return $aNames[rand(0, count($aNames) - 1)];
	}
	
	/**
	 * Get a pseudo random country name taken from a prefedined list
	 *
	 * @return string A random city name
	 */
	function GenerateCountryName()
	{
		global $aCountries;
		return $aCountries[rand(0, count($aCountries) - 1)];
	}
	
	/**
	 * Get a pseudo random city name taken from a (big) prefedined list
	 *
	 * @return string A random city name
	 */
	function GenerateCityName()
	{
		global $aCities;
		return $aCities[rand(0, count($aCities) - 1)];
	}
	
	/**
	 * Get a pseudo random email address made of the first name, last name and organization's domain
	 *
	 * @return string A random email address
	 */
	function GenerateEmail($sFirstName, $sLastName)
	{
		if (rand(1, 20) > 18)
		{
			// some people (let's say 5~10%) have an irregular email address
			$sEmail = strtolower($this->CleanForEmail($sLastName))."@".strtolower($this->GenerateDomain());
		}
		else
		{
			$sEmail = strtolower($this->CleanForEmail($sFirstName)).".".strtolower($this->CleanForEmail($sLastName))."@".strtolower($this->GenerateDomain());
		}
		return $sEmail;
	}

	/**
	 * Generate (pseudo) random strings that follow a given pattern
	 *
	 *	The template is made of any number of 'parts' separated by pipes '|'
	 *  Each part is either:
	 *  - domain() => returns a domain name for the current organization
	 *  - enum(aaa,bb,c,dddd) => returns randomly one of aaa,bb,c or dddd with the same
	 *    probability of occurence. If you want to change the probability you can repeat some values
	 *    i.e enum(most probable,most probable,most probable,most probable,most probable,rare) 
	 *	- number(xxx-yyy) => a random number between xxx and yyy (bounds included)
	 *   note that if the first number (xxx) begins with a zero, then the result will zero padded
	 *   to the same number of digits as xxx.
	 *  All other 'part' that does not follow one of the above mentioned pattern is returned as is
	 *
	 * Example: GenerateString("enum(sw,rtr,gw)|number(00-99)|.|domain()")
	 *          will produce strings like "sw01.netcmdb.com" or "rtr45.itop.org"
	 *
	 * @param string $sTemplate The template used for generating the string
	 * @return string The generated pseudo random the string
	 */
	function GenerateString($sTemplate)
	{
		$sResult = "";
		$aParts = explode("\|", $sTemplate);
		foreach($aParts as $sPart)
		{
			if (preg_match("/domain\(\)/", $sPart, $aMatches))
			{
				$sResult .= strtolower($this->GenerateDomain());
			}
			elseif (preg_match("/enum\((.+)\)/", $sPart, $aMatches))
			{
				$sEnumValues = $aMatches[1];
				$aEnumValues = explode(",", $sEnumValues);
				$sResult .= $aEnumValues[rand(0, count($aEnumValues) - 1)];
			}
			elseif (preg_match("/number\((\d+)-(\d+)\)/", $sPart, $aMatches))
			{
				$sStartNumber = $aMatches[1];
				if ($sStartNumber[0] == '0')
				{
					// number must be zero padded
					$sFormat = "%0".strlen($sStartNumber)."d";
				}
				else
				{
					$sFormat = "%d";
				}
				$sEndNumber = $aMatches[2];
				$sResult .= sprintf($sFormat, rand($sStartNumber, $sEndNumber));
			}
			else
			{
				$sResult .= $sPart;
			}
		}
		return $sResult;
	}

	/**
	 * Generate a foreign key by picking a random element of the given class in a set limited by the given search criteria
	 *
	 * Example: GenerateKey("bizLocation", array('org_id', $oGenerator->GetOrganizationId());
	 *          will produce the foreign key of a Location object picked at random in the same organization
	 *
	 * @param string $sClass The name of the class to search for
	 * @param string $aFilterCriteria A hash array of filterCOde => FilterValue (the strict operator '=' is used )
	 * @return mixed The key to an object of the given class, or null if none are found
	 */
	function GenerateKey($sClass, $aFilterCriteria)
	{
		$retKey = null;
		$oFilter = new CMDBSearchFilter($sClass);
		foreach($aFilterCriteria as $sFilterCode => $filterValue)
		{
			$oFilter->AddCondition($sFilterCode, $filterValue, '=');
		}
		$oSet = new CMDBObjectSet($oFilter);
		if ($oSet->Count() > 0)
		{
			$max_count = $index = rand(1, $oSet->Count());
			do
			{
				$oObj = $oSet->Fetch();
				$index--;
			}
			while($index > 0);
			
			if (!is_object($oObj))
			{
				echo "<pre>";
				echo "ERROR: non empty set, but invalid object picked! class='$sClass'\n";
				echo "Index chosen: $max_count\n";
				echo "The set is supposed to contain ".$oSet->Count()." object(s)\n";
				echo "Filter criteria:\n";
				print_r($aFilterCriteria);
				echo "</pre>";
			}
			else
			{
				$retKey = $oObj->GetKey();
			}
		}
		return $retKey;
	}
	///////////////////////////////////////////////////////////////////////////////
	//
	//  Protected methods
	//
	///////////////////////////////////////////////////////////////////////////////
		
	/**
	 * Generate a (random) domain name consistent with the organization name & code
	 *
	 * The values are pulled from a (limited) predefined list. Note that a given
	 * organization may have several domain names, so the result may be random
	 *
	 * @return string A domain name (like netcnmdb.com)
	 */
	protected function GenerateDomain()
	{
		if (is_array($this->m_OrganizationDomains))
		{
			$sDomain = $this->m_OrganizationDomains[rand(0, count($this->m_OrganizationDomains)-1)];
		}
		else
		{
			$sDomain = $this->m_OrganizationDomains;
		}
		return $sDomain;
	}
	
	/**
	 * Strips accented characters from a string in order to produce a suitable email address
	 *
	 * @param string The text string to clean
	 * @return string The cleanified text string
	 */
	protected function CleanForEmail($sText)
	{
		return str_replace(array("'", "é", "è", "ê", "ç", "à", "â", "ñ", "ö", "ä"), array("", "e", "e", "e", "c", "a", "a", "n", "oe", "ae"), $sText);
	}

	/**
	 * Check if an organization with the given code already exists in the database
	 *
	 * @param string $sCode The code to look for
	 * @return boolean true if the given organization exists, false otherwise
	 */
	protected function OrganizationExists($sCode)
	{
		$oFilter = new CMDBSearchFilter('bizOrganization');
		$oFilter->AddCondition('code', $sCode, '=');
		$oSet = new CMDBObjectSet($oFilter);
		return ($oSet->Count() > 0);
	}

	/**
	 * Search for an organization with the given code in the database
	 *
	 * @param string $Id The organization Id to look for
	 * @return cmdbOrganization the organization if it exists, null otherwise
	 */
	protected function GetOrganization($sId)
	{
		$oOrg = null;
		$oFilter = new CMDBSearchFilter('bizOrganization');
		$oFilter->AddCondition('id', $sId, '=');
		$oSet = new CMDBObjectSet($oFilter);
		if ($oSet->Count() > 0)
		{
			$oOrg = $oSet->Fetch(); // Let's take the first one found
		}
		return $oOrg;
	}
}
?>
