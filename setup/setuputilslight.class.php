<?php
/*
 * Copyright (C) 2010-2020 Combodo SARL
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


define('ITOP_APPLICATION', 'iTop');
define('ITOP_APPLICATION_SHORT', 'iTop');
define('ITOP_VERSION', '2.8.0-dev'); // @see utils::GetItopVersionShort() and utils::GetItopVersionWikiSyntax()
define('ITOP_REVISION', 'svn');
define('ITOP_BUILD_DATE', '$WCNOW$');
define('ITOP_VERSION_FULL', ITOP_VERSION.'-'.ITOP_REVISION);


require_once APPROOT.'setup/setupconst.class.php';


/**
 * ⚠ This file MUST contain only PHP code compatible with 5.4
 * It is used for the setup PHP version check and shouldn't create PHP error !
 *
 * @since 2.8.0 N°3253 extract PHP check version in a dedicated page
 */
class WebPageLight
{
	/**
	 * @since 2.7.0 N°2529
	 * @since 2.8.0 N°3253 moved from WebPage to new WebPageLight class
	 */
	const PAGES_CHARSET = 'utf-8';

	/**
	 * @param string $sAbsolutePathRoot see {@link utils::GetAbsoluteUrlAppRoot()}
	 * @param string $sPageContent
	 * @param string $sPageTitle
	 * @param string $sTimeStamp see {@link utils::GetCacheBusterTimestamp()}
	 *
	 * @return string content inserted in the Setup page HTML layout
	 *
	 * @since 2.8.0 N°3253 extract this method so that we can use in setup/index.php
	 */
	public static function EmbedSetupPageContent($sAbsolutePathRoot, $sPageContent, $sPageTitle, $sTimeStamp)
	{
		$sLogo = $sAbsolutePathRoot.'/images/itop-logo.png';
		$sTitleEncoded = htmlentities($sPageTitle, ENT_QUOTES, static::PAGES_CHARSET);

		return <<<HTML
<div id="header">
<h1><a href="http://www.combodo.com/itop" target="_blank"><img title="iTop by Combodo" alt=" " src="{$sLogo}?t={$sTimeStamp}"></a>&nbsp;{$sTitleEncoded}</h1>
</div>
<div id="setup">{$sPageContent}</div>
HTML;
	}
}


class CheckResult
{
	// Severity levels
	const ERROR = 0;
	const WARNING = 1;
	const INFO = 2;
	const TRACE = 3; // for log purposes : replace old SetupLog::Log calls

	public $iSeverity;
	public $sLabel;
	public $sDescription;

	public function __construct($iSeverity, $sLabel, $sDescription = '')
	{
		$this->iSeverity = $iSeverity;
		$this->sLabel = $sLabel;
		$this->sDescription = $sDescription;
	}

	/**
	 * @return string
	 * @since 2.8.0 N°2214
	 */
	public function __toString()
	{
		$sPrintDesc = (empty($this->sDescription)) ? '' : " ({$this->sDescription})";

		return "{$this->sLabel}$sPrintDesc";
	}

	/**
	 * @param \CheckResult[] $aResults
	 * @param string[] $aCheckResultSeverities list of CheckResult object severities to keep
	 *
	 * @return \CheckResult[] only elements that have one of the passed severity
	 *
	 * @since 2.8.0 N°2214
	 */
	public static function FilterCheckResultArray(array $aResults, array $aCheckResultSeverities)
	{
		return array_filter($aResults,
			static function ($v) use ($aCheckResultSeverities) {
				if (in_array($v->iSeverity, $aCheckResultSeverities, true)) {
					return $v;
				}

				return false;
			},
			ARRAY_FILTER_USE_BOTH);
	}

	/**
	 * @param \CheckResult[] $aResults
	 *
	 * @return string[]
	 * @uses \CheckResult::__toString
	 *
	 * @since 2.8.0 N°2214
	 */
	public static function FromObjectsToStrings($aResults)
	{
		return array_map(function ($value) {
			return $value->__toString();
		}, $aResults);
	}
}


/**
 * Contains all the code necessary for a PHP version check. Should
 *
 * @since 2.8.0 N°3253 move from SetupUtils to here
 */
class SetupUtilsLight
{
	/**
	 * @param CheckResult[] $aResult checks log
	 *
	 * @since 2.8.0 N°2214 replace SetupLog::Log calls by CheckResult::TRACE
	 */
	public static function CheckPhpVersion(array &$aResult)
	{
		$aResult[] = new CheckResult(CheckResult::TRACE, 'Info - CheckPHPVersion');
		$sPhpVersion = phpversion();

		if (version_compare($sPhpVersion, SetupConst::PHP_MIN_VERSION, '>=')) {
			$aResult[] = new CheckResult(CheckResult::INFO,
				"The current PHP Version (".$sPhpVersion.") is greater than the minimum version required to run ".ITOP_APPLICATION.", which is (".SetupConst::PHP_MIN_VERSION.")");


			$sPhpNextMinVersion = SetupConst::PHP_NEXT_MIN_VERSION; // mandatory before PHP 5.5 (arbitrary expressions), keeping compat because we're in the setup !
			if (!empty($sPhpNextMinVersion)) {
				if (version_compare($sPhpVersion, SetupConst::PHP_NEXT_MIN_VERSION, '>=')) {
					$aResult[] = new CheckResult(CheckResult::INFO,
						"The current PHP Version (".$sPhpVersion.") is greater than the minimum version required to run next ".ITOP_APPLICATION." release, which is (".SetupConst::PHP_NEXT_MIN_VERSION.")");
				} else {
					$aResult[] = new CheckResult(CheckResult::WARNING,
						"The current PHP Version (".$sPhpVersion.") is lower than the minimum version required to run next ".ITOP_APPLICATION." release, which is (".SetupConst::PHP_NEXT_MIN_VERSION.")");
				}
			}

			if (version_compare($sPhpVersion, SetupConst::PHP_NOT_VALIDATED_VERSION, '>=')) {
				$aResult[] = new CheckResult(CheckResult::WARNING,
					"The current PHP Version (".$sPhpVersion.") is not yet validated by Combodo. You may experience some incompatibility issues.");
			}
		} else {
			$aResult[] = new CheckResult(CheckResult::ERROR,
				"Error: The current PHP Version (".$sPhpVersion.") is lower than the minimum version required to run ".ITOP_APPLICATION.", which is (".SetupConst::PHP_MIN_VERSION.")");
		}
	}
}
