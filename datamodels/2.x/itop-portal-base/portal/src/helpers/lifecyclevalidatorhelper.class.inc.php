<?php

// Copyright (C) 2010-2015 Combodo SARL
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

namespace Combodo\iTop\Portal\Helper;

use \Exception;
use \DOMNodeList;
use \DOMFormatException;
use \utils;
use \ProfilesConfig;
use \MetaModel;
use \DBSearch;
use \DBUnionSearch;
use \Combodo\iTop\DesignElement;

class LifecycleValidatorHelper
{
	const ENUM_TYPE_ALLOW = 'allow';
	const ENUM_TYPE_RESTRICT = 'restrict';
	const DEFAULT_GENERATED_CLASS = 'PortalLifecycleValues';

	protected $sCachePath;
	protected $sFilename;
	protected $sInstancePrefix;
	protected $sGeneratedClass;
	protected $aProfilesMatrix;

	public static function EnumTypeValues()
	{
		return array(static::ENUM_TYPE_ALLOW, static::ENUM_TYPE_RESTRICT);
	}

	public function __construct($sFilename, $sCachePath = null)
	{
		$this->sFilename = $sFilename;
		$this->sCachePath = $sCachePath;
		$this->sInstancePrefix = '';
		$this->sGeneratedClass = static::DEFAULT_GENERATED_CLASS;
		$this->aProfilesMatrix = array();
	}

	/**
	 * Returns the path where to cache the compiled lifecycles file
	 *
	 * @return string
	 */
	public function GetCachePath()
	{
		return $this->sCachePath;
	}

	/**
	 * Returns the name of the compiled lifecycles file
	 *
	 * @return string
	 */
	public function GetFilename()
	{
		return $this->sFilename;
	}

	/**
	 * Returns the instance prefix used for the generated lifecycles class name
	 *
	 * @return string
	 */
	public function GetInstancePrefix()
	{
		return $this->sInstancePrefix;
	}

	/**
	 * Returns the name of the generated lifecycles class
	 *
	 * @return string
	 */
	public function GetGeneratedClass()
	{
		return $this->sGeneratedClass;
	}

	/**
	 * Sets the lifecycle validator instance prefix.
	 *
	 * This is used to create a unique lifecycle values class in the cache directory (/data/cache-<ENV>) as there can be several instance of the portal.
	 *
	 * @param string $sInstancePrefix
	 * @return \Combodo\iTop\Portal\Helper\LifecycleValidatorHelper
	 */
	public function SetInstancePrefix($sInstancePrefix)
	{
		$sInstancePrefix = preg_replace('/[-_]/', ' ', $sInstancePrefix);
		$sInstancePrefix = ucwords($sInstancePrefix);
		$sInstancePrefix = str_replace(' ', '', $sInstancePrefix);

		$this->sInstancePrefix = $sInstancePrefix;
		$this->sGeneratedClass = $this->sInstancePrefix . static::DEFAULT_GENERATED_CLASS;
		return $this;
	}

	/**
	 * Initializes the LifecycleValidator by generating and caching the lifecycles compilation in the $this->sCachePath.$this->sFilename file.
	 *
	 * @param DOMNodeList $oNodes
	 * @throws DOMFormatException
	 * @throws Exception
	 */
	public function Init(DOMNodeList $oNodes)
	{
		// Checking cache path
		if ($this->sCachePath === null)
		{
			$this->sCachePath = utils::GetCachePath();
		}
		// Building full pathname for file
		$sFilePath = $this->sCachePath . $this->sFilename;

		// Creating file if not existing
		// Note : This is a temporary cache system, it should soon evolve to a cache provider (fs, apc, memcache, ...)
		if (!file_exists($sFilePath))
		{
			// - Build php array from xml
			$aProfiles = array();
			// This will be used to know which classes have been set, so we can set the missing ones.
			$aProfileClasses = array();
			// Iterating over the class nodes
			foreach ($oNodes as $oClassNode)
			{
				// retrieving mandatory class id attribute
				$sClass = $oClassNode->getAttribute('id');
				if ($sClass === '')
				{
					throw new DOMFormatException('Class tag must have an id attribute.', null, null, $oClassNode);
				}
				
				// Iterating over scope nodes of the class
				$oScopesNode = $oClassNode->GetOptionalElement('scopes');
				if ($oScopesNode !== null)
				{
					foreach ($oScopesNode->GetNodes('./scope') as $oScopeNode)
					{
						// Retrieving mandatory scope id attribute
						$sScopeId = $oScopeNode->getAttribute('id');
						if ($sScopeId === '')
						{
							throw new DOMFormatException('Scope tag must have an id attribute.', null, null, $oScopeNode);
						}

						// Retrieving the type of query
						// Note : This has been disabled as we don't want deny rules for now
						// $oOqlViewTypeNode = $oClassNode->GetOptionalElement('oql_view_type');
						// $sOqlViewType = ($oOqlViewTypeNode !== null && ($oOqlViewTypeNode->GetText() === static::ENUM_TYPE_RESTRICT)) ? static::ENUM_TYPE_RESTRICT : static::ENUM_TYPE_ALLOW;
						$sOqlViewType = static::ENUM_TYPE_ALLOW;
						// Retrieving the view query
						$oOqlViewNode = $oScopeNode->GetUniqueElement('oql_view');
						$sOqlView = $oOqlViewNode->GetText();
						if ($sOqlView === null)
						{
							throw new DOMFormatException('Scope tag in class must have a not empty oql_view tag', null, null, $oScopeNode);
						}
						// Retrieving the edit query
						$oOqlEditNode = $oScopeNode->GetOptionalElement('oql_edit');
						$sOqlEdit = ( ($oOqlEditNode !== null) && ($oOqlEditNode->GetText() !== null) ) ? $oOqlEditNode->GetText() : null;

						// Retrieving profiles for the scope
						$oProfilesNode = $oScopeNode->GetOptionalElement('allowed_profiles');
						$aProfilesNames = array();
						// If no profile is specified, we consider that it's for ALL the profiles
						if (($oProfilesNode === null) || ($oProfilesNode->GetNodes('./allowed_profile')->length === 0))
						{
							foreach (ProfilesConfig::GetProfilesValues() as $iKey => $aValue)
							{
								$aProfilesNames[] = $aValue['name'];
							}
						}
						else
						{
							foreach ($oProfilesNode->GetNodes('./allowed_profile') as $oProfileNode)
							{
								// Retrieving mandatory profile id attribute
								$sProfileId = $oProfileNode->getAttribute('id');
								if ($sProfileId === '')
								{
									throw new DOMFormatException('Scope tag must have an id attribute.', null, null, $oProfileNode);
								}
								$aProfilesNames[] = $sProfileId;
							}
						}

						//
						foreach ($aProfilesNames as $sProfileName)
						{
							// Scope profile id
							$iProfileId = $this->GetProfileIdFromProfileName($sProfileName);
							
							// Now that we have the queries infos, we are going to build the queries for that profile / class
							$sMatrixPrefix = $iProfileId . '_' . $sClass . '_';
							// - View query
							$oViewFilter = DBSearch::FromOQL($sOqlView);
							// ... We have to union the query if this profile has another scope for that class
							if (array_key_exists($sMatrixPrefix . static::ENUM_MODE_READ, $aProfiles) && array_key_exists($sOqlViewType, $aProfiles[$sMatrixPrefix . static::ENUM_MODE_READ]))
							{
								$oExistingFilter = DBSearch::FromOQL($aProfiles[$sMatrixPrefix . static::ENUM_MODE_READ][$sOqlViewType]);
								$aFilters = array($oExistingFilter, $oViewFilter);
								$oResFilter = new DBUnionSearch($aFilters);
							}
							else
							{
								$oResFilter = $oViewFilter;
							}
							$aProfiles[$sMatrixPrefix . static::ENUM_MODE_READ] = array(
								$sOqlViewType => $oResFilter->ToOQL()
							);
							// - Edit query
							if ($sOqlEdit !== null)
							{
								$oEditFilter = DBSearch::FromOQL($sOqlEdit);
								// - If the queries are the same, we don't make an intersect, we just reuse the view query
								if ($sOqlEdit === $sOqlView)
								{
									// Do not intersect, edit query is identical to view query
								}
								else
								{
									if (($oEditFilter->GetClass() === $oViewFilter->GetClass()) && $oEditFilter->IsAny())
									{
										$oEditFilter = $oViewFilter;
										// Do not intersect, edit query is identical to view query
									}
									else
									{
										// Intersect
										$oEditFilter = $oViewFilter->Intersect($oEditFilter);
									}
								}

								// ... We have to union the query if this profile has another scope for that class
								if (array_key_exists($sMatrixPrefix . static::ENUM_MODE_WRITE, $aProfiles) && array_key_exists($sOqlViewType, $aProfiles[$sMatrixPrefix . static::ENUM_MODE_WRITE]))
								{
									$oExistingFilter = DBSearch::FromOQL($aProfiles[$sMatrixPrefix . static::ENUM_MODE_WRITE][$sOqlViewType]);
									$aFilters = array($oExistingFilter, $oEditFilter);
									$oResFilter = new DBUnionSearch($aFilters);
								}
								else
								{
									$oResFilter = $oEditFilter;
								}
								$aProfiles[$sMatrixPrefix . static::ENUM_MODE_WRITE] = array(
									$sOqlViewType => $oResFilter->ToOQL()
								);
							}
						}
					}

					$aProfileClasses[] = $sClass;
				}
			}

			// Filling the array with missing classes from MetaModel, so we can have an inheritance principle on the scope
			// For each class explicitly given in the scopes, we check if its child classes were also in the scope :
			// If not, we add them with the same OQL
			foreach ($aProfileClasses as $sProfileClass)
			{
				foreach (MetaModel::EnumChildClasses($sProfileClass) as $sChildClass)
				{
					// If the child class is not in the scope, we are going to try to add it
					if (!in_array($sChildClass, $aProfileClasses))
					{
						foreach (ProfilesConfig::GetProfilesValues() as $iKey => $aValue)
						{
							$iProfileId = $iKey;
							foreach (array(static::ENUM_MODE_READ, static::ENUM_MODE_WRITE) as $sAction)
							{
								// If the current profile has scope for that class in that mode, we duplicate it
								if (isset($aProfiles[$iProfileId . '_' . $sProfileClass . '_' . $sAction]))
								{
									$aTmpProfile = $aProfiles[$iProfileId . '_' . $sProfileClass . '_' . $sAction];
									foreach ($aTmpProfile as $sType => $sOql)
									{
										$oTmpFilter = DBSearch::FromOQL($sOql);
										$oTmpFilter->ChangeClass($sChildClass);

										$aTmpProfile[$sType] = $oTmpFilter->ToOQL();
									}

									$aProfiles[$iProfileId . '_' . $sChildClass . '_' . $sAction] = $aTmpProfile;
								}
							}
						}
					}
				}
			}

			// Iterating over the scope nodes
			/* foreach ($oNodes as $oScopeNode)
			  {
			  // Retrieving mandatory id attribute
			  $sProfile = $oScopeNode->getAttribute('id');
			  if ($sProfile === '')
			  {
			  throw new DOMFormatException('Scope tag must have an id attribute.', null, null, $oScopeNode);
			  }

			  // Scope profile id
			  $iProfileId = $this->GetProfileIdFromProfileName($sProfile);
			  // This will be used to know which classes have been set, so we can set the missing ones.
			  $aProfileClasses = array();

			  // Iterating over the class nodes of the scope
			  foreach ($oScopeNode->GetUniqueElement('classes')->GetNodes('./class') as $oClassNode)
			  {
			  // Retrieving mandatory id attribute
			  $sClass = $oClassNode->getAttribute('id');
			  if ($sClass === '')
			  {
			  throw new DOMFormatException('Class tag must have an id attribute.', null, null, $oClassNode);
			  }

			  // Retrieving the type of query
			  $oOqlViewTypeNode = $oClassNode->GetOptionalElement('oql_view_type');
			  $sOqlViewType = ($oOqlViewTypeNode !== null && ($oOqlViewTypeNode->GetText() === static::ENUM_TYPE_RESTRICT)) ? static::ENUM_TYPE_RESTRICT : static::ENUM_TYPE_ALLOW;
			  // Retrieving the view query
			  $oOqlViewNode = $oClassNode->GetUniqueElement('oql_view');
			  $sOqlView = $oOqlViewNode->GetText();
			  if ($sOqlView === null)
			  {
			  throw new DOMFormatException('Class tag in scope must have a not empty oql_view tag', null, null, $oClassNode);
			  }
			  // Retrieving the edit query
			  $oOqlEditNode = $oClassNode->GetOptionalElement('oql_edit');
			  $sOqlEdit = ( ($oOqlEditNode !== null) && ($oOqlEditNode->GetText() !== null) ) ? $oOqlEditNode->GetText() : null;

			  // Now that we have the queries infos, we are going to build the queries for that profile / class
			  $sMatrixPrefix = $iProfileId . '_' . $sClass . '_';
			  // - View query
			  $oViewFilter = DBSearch::FromOQL($sOqlView);
			  $aProfiles[$sMatrixPrefix . 'r'] = array(
			  $sOqlViewType => $oViewFilter->ToOQL()
			  );
			  // - Edit query
			  if ($sOqlEdit !== null)
			  {
			  $oEditFilter = DBSearch::FromOQL($sOqlEdit);
			  // - If the queries are the same, we don't make an intersect, we just reuse the view query
			  if ($sOqlEdit === $sOqlView)
			  {
			  // Do not intersect, edit query is identical to view query
			  }
			  else
			  {
			  if (($oEditFilter->GetClass() === $oViewFilter->GetClass()) && $oEditFilter->IsAny())
			  {
			  $oEditFilter = $oViewFilter;
			  // Do not intersect, edit query is identical to view query
			  }
			  else
			  {
			  // Intersect
			  $oEditFilter = $oViewFilter->Intersect($oEditFilter);
			  }
			  }

			  $aProfiles[$sMatrixPrefix . 'w'] = array(
			  $sOqlViewType => $oEditFilter->ToOQL()
			  );
			  }

			  $aProfileClasses[] = $sClass;
			  }

			  // Filling the array with missing classes from MetaModel, so we can have an inheritance principle on the scope
			  // For each class explicitly given in the scopes, we check if its child classes were also in the scope :
			  // If not, we add them with the same OQL
			  foreach ($aProfileClasses as $sProfileClass)
			  {
			  foreach (MetaModel::EnumChildClasses($sProfileClass) as $sChildClass)
			  {
			  // If the child class is not in the scope, we are going to try to add it
			  if (!in_array($sChildClass, $aProfileClasses))
			  {
			  foreach (array('r', 'w') as $sAction)
			  {
			  // If the current profile has scope for that class in that mode, we duplicate it
			  if (isset($aProfiles[$iProfileId . '_' . $sProfileClass . '_' . $sAction]))
			  {
			  $aTmpProfile = $aProfiles[$iProfileId . '_' . $sProfileClass . '_' . $sAction];
			  foreach ($aTmpProfile as $sType => $sOql)
			  {
			  $oTmpFilter = DBSearch::FromOQL($sOql);
			  $oTmpFilter->ChangeClass($sChildClass);

			  $aTmpProfile[$sType] = $oTmpFilter->ToOQL();
			  }

			  $aProfiles[$iProfileId . '_' . $sChildClass . '_' . $sAction] = $aTmpProfile;
			  }
			  }
			  }
			  }
			  }
			  } */

			// - Build php class
			$sPHP = $this->BuildPHPClass($aProfiles);

			// - Write file on disk
			//   - Creating dir if necessary
			if (!is_dir($this->sCachePath))
			{
				mkdir($this->sCachePath, 0777, true);
			}
			//   -- Then creating the file
			$ret = file_put_contents($sFilePath, $sPHP);
			if ($ret === false)
			{
				$iLen = strlen($sPHP);
				$fFree = @disk_free_space(dirname($sFilePath));
				$aErr = error_get_last();
				throw new Exception("Failed to write '$sFilePath'. Last error: '{$aErr['message']}', content to write: $iLen bytes, available free space on disk: $fFree.");
			}
		}

		if (!class_exists($this->sGeneratedClass))
		{
			require_once $this->sCachePath . $this->sFilename;
		}
	}

	/**
	 * Returns the DBSearch for the $sProfile in $iAction for the class $sClass
	 *
	 * @param string $sProfile
	 * @param string $sClass
	 * @param integer $iAction
	 * @return DBSearch
	 */
	public function GetScopeFilterForProfile($sProfile, $sClass, $iAction = null)
	{
		return $this->GetScopeFilterForProfiles(array($sProfile), $sClass, $iAction);
	}

	/**
	 * Returns the DBSearch for the $aProfiles in $iAction for the class $sClass.
	 * Profiles are a OR condition.
	 *
	 * @param array $aProfiles
	 * @param string $sClass
	 * @param integer $iAction
	 * @return DBSearch
	 */
	public function GetScopeFilterForProfiles($aProfiles, $sClass, $iAction = null)
	{
		$oSearch = null;
		$aAllowSearches = array();
		$aRestrictSearches = array();

		// Checking the default mode
		if ($iAction === null)
		{
			$iAction = UR_ACTION_READ;
		}
		
		// Iterating on profiles to retrieving the different OQLs parts
		foreach ($aProfiles as $sProfile)
		{
			// Retrieving matrix informtions
			$iProfileId = $this->GetProfileIdFromProfileName($sProfile);
			$sMode = ($iAction === UR_ACTION_READ) ? static::ENUM_MODE_READ : static::ENUM_MODE_WRITE;

			// Retrieving profile OQLs
			$sScopeValuesClass = $this->sGeneratedClass;
			$aProfileMatrix = $sScopeValuesClass::GetProfileScope($iProfileId, $sClass, $sMode);
			if ($aProfileMatrix !== null)
			{
				if (isset($aProfileMatrix['allow']) && $aProfileMatrix['allow'] !== null)
				{
					$aAllowSearches[] = DBSearch::FromOQL($aProfileMatrix['allow']);
				}
				if (isset($aProfileMatrix['restrict']) && $aProfileMatrix['restrict'] !== null)
				{
					$aRestrictSearches[] = DBSearch::FromOQL($aProfileMatrix['restrict']);
				}
			}
		}

		// Building the real OQL from all the parts from the differents profiles
		for ($i = 0; $i < count($aAllowSearches); $i++)
		{
			foreach ($aRestrictSearches as $oRestrictSearch)
			{
				$aAllowSearches[$i] = $aAllowSearches[$i]->Intersect($oRestrictSearch);
			}
		}
		if (count($aAllowSearches) > 0)
		{
			$oSearch = new DBUnionSearch($aAllowSearches);
			$oSearch = $oSearch->RemoveDuplicateQueries();
		}

		return $oSearch;
	}

	/**
	 * Returns the profile id from a string being either a constant or its name.
	 *
	 * @param string $sProfile
	 * @return integer
	 * @throws Exception
	 */
	protected function GetProfileIdFromProfileName($sProfile)
	{
		$iProfileId = null;

		// We try to find the profile from its name in order to retrieve it's id
		// - If the regular UserRights addon is installed we check the profiles array
		if (class_exists('ProfilesConfig'))
		{
			if (defined($sProfile) && in_array($sProfile, ProfilesConfig::GetProfilesValues()))
			{
				$iProfileId = constant($sProfile);
			}
			else
			{
				foreach (ProfilesConfig::GetProfilesValues() as $iKey => $aValue)
				{
					if ($aValue['name'] === $sProfile)
					{
						$iProfileId = $iKey;
						break;
					}
				}
			}
		}
		// - Else, we can't find the id from the name as we don't know the used UserRights addon. It has to be a constant
		else
		{
			throw new Exception('Scope validator : Unknown UserRights addon, scope\'s profile must be a constant');
		}

		// If profile was not found from its name or from a constant, we throw an exception
		if ($iProfileId === null)
		{
			throw new Exception('Scope validator : Could not find "' . $sProfile . '" in the profiles list');
		}

		return $iProfileId;
	}

	/**
	 * Returns a string containing the generated PHP class for the compiled scopes
	 *
	 * @param array $aProfiles
	 * @return string
	 */
	protected function BuildPHPClass($aProfiles = array())
	{
		$sProfiles = var_export($aProfiles, true);
		$sClassName = $this->sGeneratedClass;
		$sPHP = <<<EOF
<?php

// File generated by LifeCycleValidatorHelperHelper
//
// Please do not edit manually
// List of constant scopes
// - used by the portal LifecycleValidatorHelperHelper
//
class $sClassName
{
	protected static \$aPROFILES = $sProfiles;

	/**
	* @param integer \$iProfileId
	* @param string \$sClass
	*/
	public static function GetTransitionProfileScope(\$iProfileId, \$sClass, \$sStimulusCode)
	{
		\$sQuery = null;

		\$sScopeKey = \$iProfileId.'_'.\$sClass.'_'.\$sAction;
		if (isset(self::\$aPROFILES[\$sScopeKey]))
		{
			\$sQuery = self::\$aPROFILES[\$sScopeKey];
		}

		return \$sQuery;
	}
}

EOF;
		return $sPHP;
	}

}

?>