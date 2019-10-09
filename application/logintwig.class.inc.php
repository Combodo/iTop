<?php

/**
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


use Combodo\iTop\TwigExtension;

class LoginTwigData
{
	private $aBlockData;
	private $aPostedVars;
	private $sTwigLoaderPath;
	private $sCSSFile;
	/** @var array */
	private $aJsFiles;

	/**
	 * LoginTwigData constructor.
	 *
	 * @param array $aPostedVars
	 * @param string $sLoaderPath
	 * @param string $sCSSFile
	 * @param array $aJsFiles
	 */
	public function __construct($aPostedVars = array(), $sLoaderPath = null, $sCSSFile = null, $aJsFiles = array())
	{
		$this->aBlockData = array();
		$this->aPostedVars = $aPostedVars;
		$this->sTwigLoaderPath = $sLoaderPath;
		$this->sCSSFile = $sCSSFile;
		$this->aJsFiles = $aJsFiles;
	}

	/**
	 * @param string $sBlockName
	 * @param LoginBlockData $oBlockData
	 */
	public final function AddBlockData($sBlockName, $oBlockData)
	{
		$this->aBlockData[$sBlockName] = $oBlockData;
	}

	public final function GetBlockData($sBlockName)
	{
		/** @var LoginBlockData $oBlockData */
		$oBlockData = isset($this->aBlockData[$sBlockName]) ? $this->aBlockData[$sBlockName] : null;
		return $oBlockData;
	}

	public final function GetPostedVars()
	{
		return $this->aPostedVars;
	}

	public final function GetTwigLoaderPath()
	{
		return $this->sTwigLoaderPath;
	}

	public final function GetCSSFile()
	{
		return $this->sCSSFile;
	}

	/**
	 * @return array
	 */
	public function GetJsFiles()
	{
		return $this->aJsFiles;
	}
}

class LoginBlockData
{
	private $sTwig;
	private $aData;

	/**
	 * LoginBlockData constructor.
	 *
	 * @param string $sTwig
	 * @param array $aData
	 */
	public function __construct($sTwig, $aData = array())
	{
		$this->sTwig = $sTwig;
		$this->aData = $aData;
	}

	public final function GetTwig()
	{
		return $this->sTwig;
	}

	public final function GetData()
	{
		return $this->aData;
	}
}

class LoginTwigContext
{
	private $aLoginPluginList;
	private $aPluginFormData;
	private $aPostedVars;
	private $oTwig;

	public function __construct()
	{
		$this->aLoginPluginList = LoginWebPage::GetLoginPluginList('iLoginDataExtension', false);
		$this->aPluginFormData = array();
		$aTwigLoaders = array();
		$this->aPostedVars = array();
		foreach ($this->aLoginPluginList as $oLoginPlugin)
		{
			/** @var \iLoginDataExtension $oLoginPlugin */
			$oLoginData = $oLoginPlugin->GetLoginData();
			$this->aPluginFormData[] = $oLoginData;
			$sTwigLoaderPath = $oLoginData->GetTwigLoaderPath();
			if ($sTwigLoaderPath != null)
			{
				$aTwigLoaders[] = new Twig_Loader_Filesystem($sTwigLoaderPath);
			}
			$this->aPostedVars = array_merge($this->aPostedVars, $oLoginData->GetPostedVars());
		}

		$oCoreLoader = new Twig_Loader_Filesystem(array(), APPROOT.'templates');
		$aCoreTemplatesPaths = array('login', 'login/password');
		// Having this path declared after the plugins let the plugins replace the core templates
		$oCoreLoader->setPaths($aCoreTemplatesPaths);
		// Having the core templates accessible within a different namespace offer the possibility to extend them while replacing them
		$oCoreLoader->setPaths($aCoreTemplatesPaths, 'ItopCore');
		$aTwigLoaders[] = $oCoreLoader;

		$oLoader = new Twig_Loader_Chain($aTwigLoaders);
		$this->oTwig = new Twig_Environment($oLoader);
		TwigExtension::RegisterTwigExtensions($this->oTwig);
	}

	public function GetDefaultVars()
	{
		$sLogo = 'itop-logo-external.png';
		$sBrandingLogo = 'login-logo.png';

		$sVersionShort = Dict::Format('UI:iTopVersion:Short', ITOP_APPLICATION, ITOP_VERSION);
		$sIconUrl = Utils::GetConfig()->Get('app_icon_url');
		$sDisplayIcon = utils::GetAbsoluteUrlAppRoot().'images/'.$sLogo.'?t='.utils::GetCacheBusterTimestamp();
		if (file_exists(MODULESROOT.'branding/'.$sBrandingLogo))
		{
			$sDisplayIcon = utils::GetAbsoluteUrlModulesRoot().'branding/'.$sBrandingLogo.'?t='.utils::GetCacheBusterTimestamp();
		}

		$aVars = array(
			'sAppRootUrl' => utils::GetAbsoluteUrlAppRoot(),
			'aPluginFormData' => $this->GetPluginFormData(),
			'sItopVersion' => ITOP_VERSION,
			'sVersionShort' => $sVersionShort,
			'sIconUrl' => $sIconUrl,
			'sDisplayIcon' => $sDisplayIcon,
		);

		return $aVars;
	}

	public function Render(NiceWebPage $oPage, $sTwigFile, $aVars = array())
	{
		$oTemplate = $this->GetTwig()->load($sTwigFile);
		$oPage->add($oTemplate->renderBlock('body', $aVars));
		$oPage->add_script($oTemplate->renderBlock('script', $aVars));
		$oPage->add_ready_script($oTemplate->renderBlock('ready_script', $aVars));
		$oPage->add_style($oTemplate->renderBlock('css', $aVars));

		// Render CSS links
		foreach ($this->aPluginFormData as $oFormData)
		{
			/** @var \LoginTwigData $oFormData */
			$sCSSFile = $oFormData->GetCSSFile();
			if (!empty($sCSSFile))
			{
				$oPage->add_linked_stylesheet($sCSSFile);
			}
			$aJsFiles = $oFormData->GetJsFiles();
			foreach ($aJsFiles as $sJsFile)
			{
				$oPage->add_linked_script($sJsFile);

			}
		}
	}

	/**
	 * @return mixed
	 */
	public function GetLoginPluginList()
	{
		return $this->aLoginPluginList;
	}

	/**
	 * @return array
	 */
	public function GetPluginFormData()
	{
		return $this->aPluginFormData;
	}

	/**
	 * @return array
	 */
	public function GetPostedVars()
	{
		return $this->aPostedVars;
	}

	/**
	 * @return \Twig_Environment
	 */
	public function GetTwig()
	{
		return $this->oTwig;
	}
}