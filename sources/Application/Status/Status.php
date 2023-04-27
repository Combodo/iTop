<?php

namespace Combodo\iTop\Application\Status;

use Config;
use Exception;
use MetaModel;

define('STATUS_ERROR', 'ERROR');
define('STATUS_RUNNING', 'RUNNING');

class Status
{

	/**
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MySQLException
	 */
	public function __construct(Config $oConfig = null)
	{
		$this->StatusStartup($oConfig);
	}

	/**
	 * Get approot.inc.php
	 * Move to a function for allowing a better testing
	 *
	 * @param string $sAppRootFilename
	 *
	 * @throws \Exception
	 */
	private function StatusGetAppRoot($sAppRootFilename = 'approot.inc.php')
	{
		$sAppRootFile = __DIR__.'/../../../'.$sAppRootFilename;

		/*
		* Check that the approot file exists and has the appropriate access rights
		*/
		if (!file_exists($sAppRootFile) || !is_readable($sAppRootFile)) {
			throw new Exception($sAppRootFilename.' is not readable');
		}
		@require_once($sAppRootFile);
	}

	/**
	 * Check iTop's config File existence and readability
	 * Move to a function for allowing a better testing
	 *
	 * @param string $sConfigFilename
	 *
	 * @throws \Exception
	 */
	private function StatusCheckConfigFile($sConfigFilename = 'config-itop.php')
	{
		$this->StatusGetAppRoot();

		$sConfigFile = APPCONF.ITOP_DEFAULT_ENV.'/'.$sConfigFilename;

		/**
		 * Check that the configuration file exists and has the appropriate access rights
		 */
		if (!file_exists($sConfigFile) || !is_readable($sConfigFile)) {
			throw new Exception($sConfigFilename.' is not readable');
		}
	}

	/**
	 * Start iTop's application for checking with its internal basic test every it's alright (DB connection, ...)
	 * Move to a function for allowing a better testing
	 *
	 * @param \Config|null $oConfig
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MySQLException
	 */
	private function StatusStartup(Config $oConfig = null)
	{
		$this->StatusCheckConfigFile();

		require_once(APPROOT.'/core/cmdbobject.class.inc.php');
		require_once(APPROOT.'/application/utils.inc.php');
		require_once(APPROOT.'/core/contexttag.class.inc.php');

		$soConfigFile = (null === $oConfig) ? ITOP_DEFAULT_CONFIG_FILE : $oConfig;

		//Check if application could be started
		MetaModel::Startup($soConfigFile, true);
	}
}