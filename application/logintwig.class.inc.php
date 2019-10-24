<?php

/**
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


use Combodo\iTop\TwigExtension;

/**
 * Twig context for modules extending the login screen
 * Class LoginTwigContext
 */
class LoginTwigContext
{
	/** @var array */
	private $aBlockExtension;
	/** @var array */
	private $aPostedVars;
	/** @var string  */
	private $sTwigLoaderPath;
	/** @var array */
	private $aCSSFiles;
	/** @var array */
	private $aJsFiles;
	private $sTwigNameSpace;

	/**
	 * Build a context to display the twig files used
	 * to extend the login screens
	 *
	 * LoginTwigContext constructor.
	 * @api
	 */
	public function __construct()
	{
		$this->aBlockExtension = array();
		$this->aPostedVars = array();
		$this->sTwigLoaderPath = null;
		$this->aCSSFiles = array();
		$this->aJsFiles = array();
		$this->sTwigNameSpace = null;
	}

	/**
	 * Set the absolute path on disk of the folder containing the twig templates
	 *
	 * @param string $sPath absolute path of twig templates directory
	 * @api
	 */
	public function SetLoaderPath($sPath)
	{
		$this->sTwigLoaderPath = $sPath;
	}

	/**
	 * Add a Twig block extension
	 *
	 * @param string $sBlockName
	 * @param LoginBlockExtension $oBlockExtension
	 */
	public function AddBlockExtension($sBlockName, $oBlockExtension)
	{
		$this->aBlockExtension[$sBlockName] = $oBlockExtension;
	}

	/**
	 * Add a variable intended to be posted on URL (and managed) by the module.
	 * Declaring the posted variables will prevent the core engine to manipulate these variables.
	 *
	 * @param string $sPostedVar Name of the posted variable
	 * @api
	 */
	public function AddPostedVar($sPostedVar)
	{
		$this->aPostedVars[] = $sPostedVar;
	}

	/**
	 * Add the URL of a CSS file to link to the login screen
	 *
	 * @param string $sFile URL of the CSS file to link
	 * @api
	 */
	public function AddCSSFile($sFile)
	{
		$this->aCSSFiles[] = $sFile;
	}

	/**
	 * Add the URL of a javascript file to link to the login screen
	 * @param string $sFile URL of the javascript file to link
	 * @api
	 */
	public function AddJsFile($sFile)
	{
		$this->aJsFiles[] = $sFile;
	}

	/**
	 * @param string $sBlockName
	 *
	 * @return \LoginBlockExtension
	 */
	public function GetBlockExtension($sBlockName)
	{
		/** @var LoginBlockExtension $oBlockExtension */
		$oBlockExtension = isset($this->aBlockExtension[$sBlockName]) ? $this->aBlockExtension[$sBlockName] : null;
		return $oBlockExtension;
	}

	/**
	 * @return array
	 */
	public function GetPostedVars()
	{
		return $this->aPostedVars;
	}

	/**
	 * @return string
	 */
	public function GetTwigLoaderPath()
	{
		return $this->sTwigLoaderPath;
	}

	/**
	 * @return array
	 */
	public function GetCSSFiles()
	{
		return $this->aCSSFiles;
	}

	/**
	 * @return array
	 */
	public function GetJsFiles()
	{
		return $this->aJsFiles;
	}

}

/**
 * Twig block description for login screen extension
 * The login screen can be extended by adding twig templates
 * to specific blocks of the login screens
 *
 * Class LoginBlockExtension
 */
class LoginBlockExtension
{
	private $sTwig;
	private $aData;

	/**
	 * Create a new twig extension block
	 * The given twig template can be HTML, CSS or JavaScript.
	 * CSS goes to the block named 'css' and is inline in the page.
	 * JavaScript goes to the blocks named 'script' or 'ready_script' and are inline in the page.
	 * HTML goes to everywhere else
	 *
	 * LoginBlockExtension constructor.
	 *
	 * @param string $sTwig name of the twig file relative to the path given to the LoginTwigContext
	 * @param array $aData Data given to the twig template (into the variable {{ aData }})
	 * @api
	 */
	public function __construct($sTwig, $aData = array())
	{
		$this->sTwig = $sTwig;
		$this->aData = $aData;
	}

	public function GetTwig()
	{
		return $this->sTwig;
	}

	public function GetData()
	{
		return $this->aData;
	}
}

/**
 * Used by LoginWebPage to display the login screen
 * Class LoginTwigRenderer
 */
class LoginTwigRenderer
{
	private $aLoginPluginList;
	private $aPluginFormData;
	private $aPostedVars;
	private $oTwig;

	public function __construct()
	{
		$this->aLoginPluginList = LoginWebPage::GetLoginPluginList('iLoginUIExtension', false);
		$this->aPluginFormData = array();
		$aTwigLoaders = array();
		$this->aPostedVars = array();
		foreach ($this->aLoginPluginList as $oLoginPlugin)
		{
			/** @var \iLoginUIExtension $oLoginPlugin */
			$oLoginContext = $oLoginPlugin->GetTwigContext();
			if (is_null($oLoginContext))
			{
				continue;
			}
			$this->aPluginFormData[] = $oLoginContext;
			$sTwigLoaderPath = $oLoginContext->GetTwigLoaderPath();
			if ($sTwigLoaderPath != null)
			{
				$oExtensionLoader = new Twig_Loader_Filesystem();
				$oExtensionLoader->setPaths($sTwigLoaderPath);
				$aTwigLoaders[] = $oExtensionLoader;
			}
			$this->aPostedVars = array_merge($this->aPostedVars, $oLoginContext->GetPostedVars());
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
			/** @var \LoginTwigContext $oFormData */
			$aCSSFiles = $oFormData->GetCSSFiles();
			foreach ($aCSSFiles as $sCSSFile)
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
