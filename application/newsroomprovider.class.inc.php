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
 * A provider for messages to be displayed in the iTop Newsroom
 */
interface iNewsroomProvider
{
	/**
	 * Inject the current configuration in the provider
	 * @param Config $oConfig
	 * @return void
	 */
	public function SetConfig(Config $oConfig);

	/**
	 * Tells if this provider is enabled for the given user
	 * @param User $oUser The user for who to check if the provider is applicable.
	 * return bool
	 */
	public function IsApplicable(User $oUser = null);
	
	/**
	 * The human readable (localized) label for this provider
	 * @return string
	 */
	public function GetLabel();
	
	/**
	 * The URL to query (from the browser, using jsonp) to fetch all unread messages
	 * @return string
	 */
	public function GetFetchURL();
	
	/**
	 * The URL to navigate to in order to display all messages
	 * @return string
	 */
	public function GetViewAllURL();
	
	/**
	 * The URL to query(from the browser, using jsonp) to mark all unread messages as read
	 * @return string
	 */
	public function GetMarkAllAsReadURL();
	
	/**
	 * Return the URL to configure the preferences for this provider or null is there is nothing to configure
	 * @return string|null
	 */
	public function GetPreferencesUrl();
	
	/**
	 * Return an array key => value to be replaced in URL of the messages
	 * Example: '%itop_root%' => utils::GetAbsoluteUrlAppRoot();
	 * @return string[]
	 */
	public function GetPlaceholders();
	
	/**
	 * The duration between to refreshes of the cache (in seconds)
	 * @return int
	 */
	public function GetTTL();
}

/**
 * Basic implementation of a Newsroom provider, to be overloaded by your own provider implementation
 *
 */
abstract class NewsroomProviderBase implements iNewsroomProvider
{
	/**
	 * The current configuration parameters
	 * @var Config
	 */
	protected $oConfig;
	
	public function __construct()
	{
		$this->oConfig = null;
	}
	
	/**
	 * {@inheritDoc}
	 * @see iNewsroomProvider::SetConfig()
	 */
	public function SetConfig(Config $oConfig)
	{
		$this->oConfig = $oConfig;		
	}

	/**
	 * {@inheritDoc}
	 * @see iNewsroomProvider::GetPreferencesUrl()
	 */
	public function GetPreferencesUrl()
	{
		return null; // No preferences
	}

	/**
	 * {@inheritDoc}
	 * @see iNewsroomProvider::GetLabel()
	 */
	public abstract function GetLabel();
	
	/**
	 * {@inheritDoc}
	 * @see iNewsroomProvider::GetFetchURL()
	 */
	public abstract function GetFetchURL();

	/**
	 * {@inheritDoc}
	 * @see iNewsroomProvider::GetMarkAllURL()
	 */
	public abstract function GetMarkAllAsReadURL();

	/**
	 * {@inheritDoc}
	 * @see iNewsroomProvider::GetViewAllURL()
	 */
	public abstract function GetViewAllURL();

	public function IsApplicable(User $oUser = null)
	{
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 * @see iNewsroomProvider::GetPlaceholders()
	 */
	public function GetPlaceholders()
	{
		return array(); // By default, empty set of placeholders
	}
	
	public function GetTTL()
	{
		return 10*60; // Refresh every 10 minutes
	}
}
