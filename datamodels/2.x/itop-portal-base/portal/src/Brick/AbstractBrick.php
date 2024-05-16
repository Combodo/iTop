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

namespace Combodo\iTop\Portal\Brick;

require_once APPROOT.'/core/moduledesign.class.inc.php';
require_once APPROOT.'/setup/compiler.class.inc.php';

use DOMFormatException;
use ModuleDesign;
use Combodo\iTop\DesignElement;

/**
 * Description of AbstractBrick
 *
 * Bricks are used mostly in the portal for now, not the console.
 * This class defines common functionalities for the extended classes.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since  2.3.0
 */
abstract class AbstractBrick
{
	/** @var string ENUM_DATA_LOADING_LAZY */
	const ENUM_DATA_LOADING_LAZY = 'lazy';
	/** @var string ENUM_DATA_LOADING_FULL */
	const ENUM_DATA_LOADING_FULL = 'full';
	/** @var string ENUM_DATA_LOADING_AUTO */
	const ENUM_DATA_LOADING_AUTO = 'auto';

	/** @var bool DEFAULT_MANDATORY */
	const DEFAULT_MANDATORY = true;
	/** @var bool DEFAULT_ACTIVE */
	const DEFAULT_ACTIVE = true;
	/** @var bool DEFAULT_VISIBLE */
	const DEFAULT_VISIBLE = true;
	/** @var float DEFAULT_RANK */
	const DEFAULT_RANK = 1.0;
	/** @var string|null DEFAULT_PAGE_TEMPLATE_PATH */
	const DEFAULT_PAGE_TEMPLATE_PATH = null;
	/** @var string DEFAULT_TITLE */
	const DEFAULT_TITLE = '';
	/** @var string|null DEFAULT_DESCRIPTION */
	const DEFAULT_DESCRIPTION = null;
	/** @var string DEFAULT_DATA_LOADING */
	const DEFAULT_DATA_LOADING = self::ENUM_DATA_LOADING_AUTO;
	/** @var string DEFAULT_ALLOWED_PROFILES_OQL */
	const DEFAULT_ALLOWED_PROFILES_OQL = '';
	/** @var string DEFAULT_DENIED_PROFILES_OQL */
	const DEFAULT_DENIED_PROFILES_OQL = '';

	/** @var string $sId */
	protected $sId;
	/** @var bool $bMandatory */
	protected $bMandatory;
	/** @var bool $bActive */
	protected $bActive;
	/** @var bool $bVisible */
	protected $bVisible;
	/** @var float $fRank */
	protected $fRank;
	/** @var string|null $sPageTemplatePath */
	protected $sPageTemplatePath;
	/** @var string $sTitle */
	protected $sTitle;
	/** @var string|null $sDescription */
	protected $sDescription;
	/** @var string $sDataLoading */
	protected $sDataLoading;
	/** @var array $aAllowedProfiles */
	protected $aAllowedProfiles;
	/** @var array $aDeniedProfiles */
	protected $aDeniedProfiles;
	/** @var string $sAllowedProfilesOql */
	protected $sAllowedProfilesOql;
	/** @var string $sDeniedProfilesOql */
	protected $sDeniedProfilesOql;

	/**
	 * Returns all enum values for the data loading modes in an array.
	 *
	 * @return array
	 */
	static function GetEnumDataLoadingValues()
	{
		return array(self::ENUM_DATA_LOADING_LAZY, self::ENUM_DATA_LOADING_FULL, self::ENUM_DATA_LOADING_AUTO);
	}

	/**
	 * Default attributes values of AbstractBrick are specified in the definition, not the constructor.
	 */
	public function __construct()
	{
		$this->bMandatory = static::DEFAULT_MANDATORY;
		$this->bActive = static::DEFAULT_ACTIVE;
		$this->bVisible = static::DEFAULT_VISIBLE;
		$this->fRank = static::DEFAULT_RANK;
		$this->sPageTemplatePath = static::DEFAULT_PAGE_TEMPLATE_PATH;
		$this->sTitle = static::DEFAULT_TITLE;
		$this->sDescription = static::DEFAULT_DESCRIPTION;
		$this->sDataLoading = static::DEFAULT_DATA_LOADING;
		$this->aAllowedProfiles = array();
		$this->aDeniedProfiles = array();
		$this->sAllowedProfilesOql = static::DEFAULT_ALLOWED_PROFILES_OQL;
		$this->sDeniedProfilesOql = static::DEFAULT_DENIED_PROFILES_OQL;
	}

	/**
	 * Returns the brick id
	 *
	 * @return string
	 */
	public function GetId()
	{
		return $this->sId;
	}

	/**
	 * Returns if brick is mandatory
	 *
	 * @return boolean
	 */
	public function GetMandatory()
	{
		return $this->bMandatory;
	}

	/**
	 * Returns if brick is active
	 *
	 * @return boolean
	 */
	public function GetActive()
	{
		return $this->bActive;
	}

	/**
	 * Returns if brick is visible
	 *
	 * @return boolean
	 */
	public function GetVisible()
	{
		return $this->bVisible;
	}

	/**
	 * Returns the brick rank
	 *
	 * @return float
	 */
	public function GetRank()
	{
		return $this->fRank;
	}

	/**
	 * Returns the brick page template path
	 *
	 * @return string
	 */
	public function GetPageTemplatePath()
	{
		return $this->sPageTemplatePath;
	}

	/**
	 * Returns the brick title
	 *
	 * @return string
	 */
	public function GetTitle()
	{
		return $this->sTitle;
	}

	/**
	 * Returns the brick description
	 *
	 * @return string
	 */
	public function GetDescription()
	{
		return $this->sDescription;
	}

	/**
	 * Returns the brick data loading mode
	 *
	 * @return string
	 */
	public function GetDataLoading()
	{
		return $this->sDataLoading;
	}

	/**
	 * Returns allowed profiles for the brick
	 *
	 * @return array
	 */
	public function GetAllowedProfiles()
	{
		return $this->aAllowedProfiles;
	}

	/**
	 * Returns denied profiles for the brick
	 *
	 * @return array
	 */
	public function GetDeniedProfiles()
	{
		return $this->aDeniedProfiles;
	}

	/**
	 * Returns allowed profiles oql query for the brick
	 *
	 * @return string
	 */
	public function GetAllowedProfilesOql()
	{
		return $this->sAllowedProfilesOql;
	}

	/**
	 * Returns denied profiles oql query for the brick
	 *
	 * @return string
	 */
	public function GetDeniedProfilesOql()
	{
		return $this->sDeniedProfilesOql;
	}

	/**
	 * Sets the brick id
	 *
	 * @param string $sId
     *
     * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetId($sId)
	{
		$this->sId = $sId;
		return $this;
	}

	/**
	 * Sets if the brick is mandatory
	 *
	 * @param boolean $bMandatory
     *
     * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetMandatory($bMandatory)
	{
		$this->bMandatory = $bMandatory;
		return $this;
	}

	/**
	 * Sets if the brick is visible
	 *
	 * @param boolean $bVisible
	 *
	 * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetVisible($bVisible)
	{
		$this->bVisible = $bVisible;
		return $this;
	}

	/**
	 * Sets if the brick is active
	 *
	 * @param boolean $bActive
     *
     * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetActive($bActive)
	{
		$this->bActive = $bActive;
		return $this;
	}

	/**
	 * Sets the rank of the brick
	 *
	 * @param float $fRank
     *
     * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetRank($fRank)
	{
		$this->fRank = $fRank;
		return $this;
	}

	/**
	 * Sets the page template path of the brick
	 *
	 * @param string $sPageTemplatePath
     *
     * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetPageTemplatePath($sPageTemplatePath)
	{
		$this->sPageTemplatePath = $sPageTemplatePath;
		return $this;
	}

	/**
	 * Sets the title of the brick
	 *
	 * @param string $sTitle
     *
     * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetTitle($sTitle)
	{
		$this->sTitle = $sTitle;
		return $this;
	}

	/**
	 * Sets the description of the brick
	 *
	 * @param string $sDescription
     *
     * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetDescription($sDescription)
	{
		$this->sDescription = $sDescription;
		return $this;
	}

	/**
	 * Sets the data loading mode of the brick
	 *
	 * @param string $sDataLoading
     *
     * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetDataLoading($sDataLoading)
	{
		$this->sDataLoading = $sDataLoading;
		return $this;
	}

	/**
	 * Sets the allowed profiles for the brick
	 *
	 * @param array $aAllowedProfiles
	 *
	 * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetAllowedProfiles($aAllowedProfiles)
	{
		$this->aAllowedProfiles = $aAllowedProfiles;

		return $this;
	}

	/**
	 * Sets the denied profiles for the brick
	 *
	 * @param array $aDeniedProfiles
	 *
	 * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetDeniedProfiles($aDeniedProfiles)
	{
		$this->aDeniedProfiles = $aDeniedProfiles;
		return $this;
	}

	/**
	 * Sets the allowed profiles oql query for the brick
	 *
	 * @param string $sAllowedProfilesOql
     *
     * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetAllowedProfilesOql($sAllowedProfilesOql)
	{
		$this->sAllowedProfilesOql = $sAllowedProfilesOql;
		return $this;
	}

	/**
	 * Sets the denied profiles oql query for the brick
	 *
	 * @param string $sDeniedProfilesOql
     *
     * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function SetDeniedProfilesOql($sDeniedProfilesOql)
	{
		$this->sDeniedProfilesOql = $sDeniedProfilesOql;
		return $this;
	}

	/**
	 * Adds $sProfile to the list of allowed profiles for that brick
	 *
	 * @param string $sProfile
	 *
	 * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function AddAllowedProfile($sProfile)
	{
		$this->aAllowedProfiles[] = $sProfile;

		return $this;
	}

	/**
	 * Removes $sProfile from the list of allowed profiles
	 *
	 * @param string $sProfile
	 *
	 * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function RemoveAllowedProfile($sProfile)
	{
		if (isset($this->aAllowedProfiles[$sProfile]))
		{
			unset($this->aAllowedProfiles[$sProfile]);
		}

		return $this;
	}

	/**
	 * Returns true if the brick has allowed profiles defined, else false
	 *
	 * @return boolean
	 */
	public function HasAllowedProfiles()
	{
		return !empty($this->aAllowedProfiles);
	}

	/**
	 * Adds $sProfile to the list of denied profiles for that brick
	 *
	 * @param string $sProfile
	 *
	 * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function AddDeniedProfile($sProfile)
	{
		$this->aDeniedProfiles[] = $sProfile;

		return $this;
	}

	/**
	 * Removes $sProfile from the list of denied profiles
	 *
	 * @param string $sProfile
	 *
	 * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 */
	public function RemoveDeniedProfile($sProfile)
	{
		if (isset($this->aDeniedProfiles[$sProfile]))
		{
			unset($this->aDeniedProfiles[$sProfile]);
		}

		return $this;
	}

	/**
	 * Returns true if the brick has denied profiles defined, else false
	 *
	 * @return boolean
	 */
	public function HasDeniedProfiles()
	{
		return !empty($this->aDeniedProfiles);
	}

	/**
	 * Returns true if the $sProfile is granted.
	 *
	 * Meaning that $sProfile is in $aAllowedProfiles and is not in $aDeniedProfiles.
	 * Priority is deny/allow
	 *
	 * @param string $sProfile
	 *
	 * @return boolean
	 */
	public function IsGrantedForProfile($sProfile)
	{
		return $this->IsGrantedForProfiles(array($sProfile));
	}

	/**
	 * Returns true if the $aProfiles are granted.
	 *
	 * Meaning that $aProfiles are in $aAllowedProfiles and are not in $aDeniedProfiles.
	 * Priority is deny/allow
	 *
	 * @param array $aProfiles
	 *
	 * @return boolean
	 */
	public function IsGrantedForProfiles($aProfiles)
	{
		$bGranted = true;

		if ($this->HasAllowedProfiles())
		{
			// We set $bGranted to false as the user must explicitly have an allowed profile to be granted
			$bGranted = false;

			foreach ($aProfiles as $sProfile)
			{
				if (in_array($sProfile, $this->aAllowedProfiles))
				{
					$bGranted = true;
					break;
				}
			}
		}

		if ($this->HasDeniedProfiles())
		{
			foreach ($aProfiles as $sProfile)
			{
				if (in_array($sProfile, $this->aDeniedProfiles))
				{
					$bGranted = false;
					break;
				}
			}
		}

		return $bGranted;
	}

	/**
	 *
	 * @return boolean
	 */
	public function HasDescription()
	{
		return ($this->sDescription !== null && $this->sDescription !== '');
	}

	/**
	 * Load the brick's data from the xml passed as a ModuleDesignElement.
	 * This is used to set all the brick attributes at once.
	 *
	 * @param \Combodo\iTop\DesignElement $oMDElement
	 *
	 * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 *
	 * @throws \DOMFormatException
	 */
	public function LoadFromXml(DesignElement $oMDElement)
	{
		// Checking mandatory elements
		if (!$oMDElement->hasAttribute('id'))
		{
			throw new DOMFormatException('Brick node must have both id and xsi:type attributes defined', null, null, $oMDElement);
		}
		$this->SetId($oMDElement->getAttribute('id'));

		// Checking others elements
		/** @var \Combodo\iTop\DesignElement $oBrickSubNode */
		foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode)
		{
			switch ($oBrickSubNode->nodeName)
			{
				case 'mandatory':
					$this->SetMandatory(($oBrickSubNode->GetText() === 'no') ? false : true);
					break;
				case 'active':
					$this->SetActive(($oBrickSubNode->GetText() === 'false') ? false : true);
					break;
				case 'rank':
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('default');
					if ($oOptionalNode !== null)
					{
						$this->SetRank((float)$oOptionalNode->GetText(static::DEFAULT_RANK));
					}
					break;
				case 'templates':
					$oTemplateNodeList = $oBrickSubNode->GetNodes('template[@id='.ModuleDesign::XPathQuote('page').']');
					if ($oTemplateNodeList->length > 0)
					{
						/** @var \Combodo\iTop\DesignElement $oTemplateNode */
						$oTemplateNode = $oTemplateNodeList->item(0);
						$this->SetPageTemplatePath($oTemplateNode->GetText(static::DEFAULT_PAGE_TEMPLATE_PATH));
					}
					break;
				case 'title':
					$oOptionalNode = $oBrickSubNode->GetOptionalElement('default');
					if ($oOptionalNode !== null)
					{
						$this->SetTitle($oOptionalNode->GetText(static::DEFAULT_TITLE));
					}
					break;
				case 'description':
					$this->SetDescription($oBrickSubNode->GetText(static::DEFAULT_DESCRIPTION));
					break;
				case 'data_loading':
					$this->SetDataLoading($oBrickSubNode->GetText(static::DEFAULT_DATA_LOADING));
					break;
				case 'security':
					/** @var \Combodo\iTop\DesignElement $oSecurityNode */
					foreach ($oBrickSubNode->GetNodes('*') as $oSecurityNode)
					{
						if ($oSecurityNode->nodeType === XML_TEXT_NODE && $oSecurityNode->GetText() === '')
						{
							throw new DOMFormatException('Brick security node "'.$oSecurityNode->nodeName.'" must contain an OQL query, it cannot be empty',
								null, null, $oMDElement);
						}

						switch ($oSecurityNode->nodeName)
						{
							case 'denied_profiles':
								$this->SetDeniedProfilesOql($oSecurityNode->GetText());
								break;
							case 'allowed_profiles':
								$this->SetAllowedProfilesOql($oSecurityNode->GetText());
								break;
						}
					}
					break;
			}
		}

		return $this;
	}

}
