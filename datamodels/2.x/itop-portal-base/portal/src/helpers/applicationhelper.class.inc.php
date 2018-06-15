<?php

// Copyright (C) 2010-2018 Combodo SARL
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

use ApplicationContext;
use Combodo\iTop\Portal\Brick\AbstractBrick;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use DOMFormatException;
use Exception;
use iPortalUIExtension;
use IssueLog;
use MetaModel;
use cmdbAbstractObject;
use ModuleDesign;
use Silex\Application;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Twig_Environment;
use Twig_SimpleFilter;
use UserRights;
use utils;

/**
 * Contains static methods to help loading / registering classes of the application.
 * Mostly used for Controllers / Routers / Entities initialization.
 *
 * @author Guillaume Lajarige
 */
class ApplicationHelper
{
	const FORM_ENUM_DISPLAY_MODE_COSY = 'cosy';
	const FORM_ENUM_DISPLAY_MODE_COMPACT = 'compact';
	const FORM_DEFAULT_DISPLAY_MODE = self::FORM_ENUM_DISPLAY_MODE_COSY;
	const FORM_DEFAULT_ALWAYS_SHOW_SUBMIT = false;

	/**
	 * Loads classes from the base portal
	 *
	 * @param string $sScannedDir Directory to load the files from
	 * @param string $sFilePattern Pattern of files to load
	 * @param string $sType Type of files to load, used only in the Exception message, can be anything
	 *
	 * @throws \Exception
	 */
	public static function LoadClasses($sScannedDir, $sFilePattern, $sType)
	{
		// Loading classes from base portal
		foreach (scandir($sScannedDir) as $sFile)
		{
			if (strpos($sFile, $sFilePattern) !== false && file_exists($sFilepath = $sScannedDir.'/'.$sFile))
			{
				try
				{
					require_once $sFilepath;
				}
				catch (Exception $e)
				{
					throw new Exception('Error while trying to load '.$sType.' '.$sFile);
				}
			}
		}
	}

	/**
	 * Loads controllers from the base portal
	 *
	 * @param string $sScannedDir Directory to load the controllers from
	 *
	 * @throws \Exception
	 */
	public static function LoadControllers($sScannedDir = null)
	{
		if ($sScannedDir === null)
		{
			$sScannedDir = __DIR__.'/../controllers';
		}

		// Loading controllers from base portal (those from modules have already been loaded by module.xxx.php files)
		self::LoadClasses($sScannedDir, 'controller.class.inc.php', 'controller');
	}

	/**
	 * Loads routers from the base portal
	 *
	 * @param string $sScannedDir Directory to load the routers from
	 *
	 * @throws \Exception
	 */
	public static function LoadRouters($sScannedDir = null)
	{
		if ($sScannedDir === null)
		{
			$sScannedDir = __DIR__.'/../routers';
		}

		// Loading routers from base portal (those from modules have already been loaded by module.xxx.php files)
		self::LoadClasses($sScannedDir, 'router.class.inc.php', 'router');
	}

	/**
	 * Loads bricks from the base portal
	 *
	 * @param string $sScannedDir Directory to load the bricks from
	 *
	 * @throws \Exception
	 */
	public static function LoadBricks($sScannedDir = null)
	{
		if ($sScannedDir === null)
		{
			$sScannedDir = __DIR__.'/../entities';
		}

		// Loading bricks from base portal (those from modules have already been loaded by module.xxx.php files)
		self::LoadClasses($sScannedDir, 'brick.class.inc.php', 'brick');
	}

	/**
	 * Loads form managers from the base portal
	 *
	 * @param string $sScannedDir Directory to load the managers from
	 *
	 * @throws \Exception
	 */
	public static function LoadFormManagers($sScannedDir = null)
	{
		if ($sScannedDir === null)
		{
			$sScannedDir = __DIR__.'/../forms';
		}

		// Loading form managers from base portal (those from modules have already been loaded by module.xxx.php files)
		self::LoadClasses($sScannedDir, 'formmanager.class.inc.php', 'brick');
	}

	/**
	 * Registers routes in the Silex Application from all declared Router classes
	 *
	 * @param \Silex\Application $oApp
	 *
	 * @throws \Exception
	 */
	public static function RegisterRoutes(Application $oApp)
	{
		$aAllRoutes = array();

		foreach (get_declared_classes() as $sPHPClass)
		{
			if (is_subclass_of($sPHPClass, 'Combodo\\iTop\\Portal\\Router\\AbstractRouter'))
			{
				try
				{
					// Registering to Silex Application
					$sPHPClass::RegisterAllRoutes($oApp);

					// Registering them together so we can access them from everywhere
					foreach ($sPHPClass::GetRoutes() as $aRoute)
					{
						$aAllRoutes[$aRoute['bind']] = $aRoute;
					}
				}
				catch (Exception $e)
				{
					throw new Exception('Error while trying to register routes');
				}
			}
		}

		$oApp['combodo.portal.instance.routes'] = $aAllRoutes;
	}

	/**
	 * Returns all registered routes for the current portal instance
	 *
	 * @param \Silex\Application $oApp
	 * @param boolean $bNamesOnly If set to true, function will return only the routes' names, not the objects
	 *
	 * @return array
	 */
	public static function GetRoutes(Application $oApp, $bNamesOnly = false)
	{
		return ($bNamesOnly) ? array_keys($oApp['combodo.portal.instance.routes']) : $oApp['combodo.portal.instance.routes'];
	}

	/**
	 * Registers Twig extensions such as filters or functions.
	 * It allows us to access some stuff directly in twig.
	 *
	 * @param \Twig_Environment $oTwigEnv
	 */
	public static function RegisterTwigExtensions(Twig_Environment &$oTwigEnv)
	{
		// Filter to translate a string via the Dict::S function
		// Usage in twig : {{ 'String:ToTranslate'|dict_s }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('dict_s',
				function ($sStringCode, $sDefault = null, $bUserLanguageOnly = false) {
					return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
				})
		);

		// Filter to format a string via the Dict::Format function
		// Usage in twig : {{ 'String:ToTranslate'|dict_format() }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('dict_format',
				function ($sStringCode, $sParam01 = null, $sParam02 = null, $sParam03 = null, $sParam04 = null) {
					return Dict::Format($sStringCode, $sParam01, $sParam02, $sParam03, $sParam04);
				})
		);

		// Filter to enable base64 encode/decode
		// Usage in twig : {{ 'String to encode'|base64_encode }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('base64_encode', 'base64_encode'));
		$oTwigEnv->addFilter(new Twig_SimpleFilter('base64_decode', 'base64_decode'));

		// Filter to enable json decode  (encode already exists)
		// Usage in twig : {{ aSomeArray|json_decode }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('json_decode', function ($sJsonString, $bAssoc = false) {
				return json_decode($sJsonString, $bAssoc);
			})
		);

		// Filter to add itopversion to an url
		$oTwigEnv->addFilter(new Twig_SimpleFilter('add_itop_version', function ($sUrl) {
			if (strpos($sUrl, '?') === false)
			{
				$sUrl = $sUrl."?itopversion=".ITOP_VERSION;
			}
			else
			{
				$sUrl = $sUrl."&itopversion=".ITOP_VERSION;
			}

			return $sUrl;
		}));

		// Filter to add a module's version to an url
		$oTwigEnv->addFilter(new Twig_SimpleFilter('add_module_version', function ($sUrl, $sModuleName) {
			$sModuleVersion = utils::GetCompiledModuleVersion($sModuleName);

			if (strpos($sUrl, '?') === false)
			{
				$sUrl = $sUrl."?moduleversion=".$sModuleVersion;
			}
			else
			{
				$sUrl = $sUrl."&moduleversion=".$sModuleVersion;
			}

			return $sUrl;
		}));
	}

	/**
	 * Registers an exception handler that will intercept controllers exceptions and display them in a nice template.
	 * Note : It is only active when $oApp['debug'] is false
	 *
	 * @param Application $oApp
	 */
	public static function RegisterExceptionHandler(Application $oApp)
	{
		// Intercepting fatal errors and exceptions
		ErrorHandler::register();
		ExceptionHandler::register(($oApp['debug'] === true));

		// Intercepting manually aborted request
		if (1 || !$oApp['debug'])
		{
			$oApp->error(function (Exception $oException, Request $oRequest) use ($oApp) {
				$iErrorCode = ($oException instanceof HttpException) ? $oException->getStatusCode() : 500;

				$aData = array(
					'exception' => $oException,
					'code' => $iErrorCode,
					'error_title' => '',
					'error_message' => $oException->getMessage()
				);

				switch ($iErrorCode)
				{
					case 404:
						$aData['error_title'] = Dict::S('Error:HTTP:404');
						break;
					default:
						$aData['error_title'] = Dict::S('Error:HTTP:500');
						break;
				}

				IssueLog::Error($aData['error_title'].' : '.$aData['error_message']);

				if ($oApp['request_stack']->getCurrentRequest()->isXmlHttpRequest())
				{
					$oResponse = $oApp->json($aData, $iErrorCode);
				}
				else
				{
					// Preparing debug trace
					$aSteps = array();
					foreach ($oException->getTrace() as $aStep)
					{
						// - Default file name
						if (!isset($aStep['file']))
						{
							$aStep['file'] = '';
						}
						$aFileParts = explode('\\', $aStep['file']);
						// - Default line number
						if (!isset($aStep['line']))
						{
							$aStep['line'] = 'unknown';
						}
						// - Default class name
						if (isset($aStep['class']) && isset($aStep['function']) && isset($aStep['type']))
						{
							$aClassParts = explode('\\', $aStep['class']);
							$sClassName = $aClassParts[count($aClassParts) - 1];
							$sClassFQ = $aStep['class'];

							$aArgsAsString = array();
							foreach ($aStep['args'] as $arg)
							{
								if (is_array($arg))
								{
									$aArgsAsString[] = 'array(...)';
								}
								elseif (is_object($arg))
								{
									$aArgsAsString[] = 'object('.get_class($arg).')';
								}
								else
								{
									$aArgsAsString[] = $arg;
								}
							}

							$sFunctionCall = $sClassName.$aStep['type'].$aStep['function'].'('.implode(', ',
									$aArgsAsString).')';
						}
						else
						{
							$sClassName = null;
							$sClassFQ = null;
							$sFunctionCall = null;
						}

						$aSteps[] = array(
							'file_fq' => $aStep['file'],
							'file_name' => $aFileParts[count($aFileParts) - 1],
							'line' => $aStep['line'],
							'class_name' => $sClassName,
							'class_fq' => $sClassFQ,
							'function_call' => $sFunctionCall,
						);
					}

					$aData['debug_trace_steps'] = $aSteps;

					$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/errors/layout.html.twig',
						$aData);
				}

				return $oResponse;
			});
		}
	}

	/**
	 * Loads the portal instance configuration from its module design into the Silex application
	 *
	 * @param \Silex\Application $oApp
	 *
	 * @throws Exception
	 */
	public static function LoadPortalConfiguration(Application $oApp)
	{
		try
		{
			// Loading file
			if (!defined('PORTAL_ID'))
			{
				throw new Exception('Cannot load module design, Portal ID is not defined');
			}
			$oDesign = new ModuleDesign(PORTAL_ID);

			// Parsing file
			// - Default values
			$aPortalConf = array(
				'properties' => array(
					'id' => PORTAL_ID,
					'name' => 'Page:DefaultTitle',
					'logo' => (file_exists(MODULESROOT.'branding/portal-logo.png')) ? utils::GetAbsoluteUrlModulesRoot().'branding/portal-logo.png' : '../images/logo-itop-dark-bg.svg',
					'themes' => array(
						'bootstrap' => 'itop-portal-base/portal/web/css/bootstrap-theme-combodo.scss',
						'portal' => 'itop-portal-base/portal/web/css/portal.scss',
						'others' => array(),
					),
					'templates' => array(
						'layout' => 'itop-portal-base/portal/src/views/layout.html.twig',
						'home' => 'itop-portal-base/portal/src/views/home/layout.html.twig'
					),
					'urlmaker_class' => null,
					'triggers_query' => null,
					'attachments' => array(
						'allow_delete' => true
					),
					'allowed_portals' => array(
						'opening_mode' => null,
					),
				),
				'portals' => array(),
				'forms' => array(),
				'ui_extensions' => array(
					'css_files' => array(),
					'css_inline' => null,
					'js_files' => array(),
					'js_inline' => null,
					'html' => array(),
				),
				'bricks' => array(),
				'bricks_total_width' => 0,
			);
			// - Global portal properties
			foreach ($oDesign->GetNodes('/module_design/properties/*') as $oPropertyNode)
			{
				switch ($oPropertyNode->nodeName)
				{
					case 'name':
					case 'urlmaker_class':
					case 'triggers_query':
						$aPortalConf['properties'][$oPropertyNode->nodeName] = $oPropertyNode->GetText($aPortalConf['properties'][$oPropertyNode->nodeName]);
						break;
					case 'logo':
						$aPortalConf['properties'][$oPropertyNode->nodeName] = $oPropertyNode->GetText($aPortalConf['properties'][$oPropertyNode->nodeName]);
						break;
					case 'themes':
					case 'templates':
						foreach ($oPropertyNode->GetNodes('template|theme') as $oSubNode)
						{
							if (!$oSubNode->hasAttribute('id') || $oSubNode->GetText(null) === null)
							{
								throw new DOMFormatException('Tag '.$oSubNode->nodeName.' must have a "id" attribute as well as a value',
									null, null, $oSubNode);
							}

							$sNodeId = $oSubNode->getAttribute('id');
							switch ($oSubNode->nodeName)
							{
								case 'theme':
									switch ($sNodeId)
									{
										case 'bootstrap':
										case 'portal':
										case 'custom':
											$aPortalConf['properties']['themes'][$sNodeId] = $oSubNode->GetText(null);
											break;
										default:
											$aPortalConf['properties']['themes']['others'][] = $oSubNode->GetText(null);
											break;
									}
									break;
								case 'template':
									switch ($sNodeId)
									{
										case 'layout':
										case 'home':
											$aPortalConf['properties']['templates'][$sNodeId] = $oSubNode->GetText(null);
											break;
										default:
											throw new DOMFormatException('Value "'.$sNodeId.'" is not handled for template[@id]',
												null, null, $oSubNode);
											break;
									}
									break;
							}
						}
						break;
					case 'attachments':
						foreach ($oPropertyNode->GetNodes('*') as $oSubNode)
						{
							switch ($oSubNode->nodeName)
							{
								case 'allow_delete':
									$sValue = $oSubNode->GetText();
									// If the text is null, we keep the default value
									// Else we set it
									if ($sValue !== null)
									{
										$aPortalConf['properties']['attachments'][$oSubNode->nodeName] = ($sValue === 'true') ? true : false;
									}
									break;
							}
						}
						break;
					case 'allowed_portals':
						foreach ($oPropertyNode->GetNodes('*') as $oSubNode)
						{
							switch ($oSubNode->nodeName)
							{
								case 'opening_mode':
									$sValue = $oSubNode->GetText();
									// If the text is null, we keep the default value
									// Else we set it
									if ($sValue !== null)
									{
										$aPortalConf['properties']['allowed_portals'][$oSubNode->nodeName] = ($sValue === 'self') ? 'self' : 'tab';
									}
									break;
							}
						}
						break;
				}
			}
			// - Rectifying portal logo url
			$sLogoUri = $aPortalConf['properties']['logo'];
			if (!preg_match('/^http/', $sLogoUri))
			{
				// We prefix it with the server base url
				$sLogoUri = utils::GetAbsoluteUrlAppRoot().'env-'.utils::GetCurrentEnvironment().'/'.$sLogoUri;
			}
			$aPortalConf['properties']['logo'] = $sLogoUri;
			// - User allowed portals
			$aPortalConf['portals'] = UserRights::GetAllowedPortals();
			// - Bricks
			$aPortalConf = static::LoadBricksConfiguration($oApp, $oDesign) + $aPortalConf;
			// - Forms
			$aPortalConf['forms'] = static::LoadFormsConfiguration($oApp, $oDesign);
			// - Scopes
			static::LoadScopesConfiguration($oApp, $oDesign);
			// - Lifecycle
			static::LoadLifecycleConfiguration($oApp, $oDesign);
			// - Presentation lists
			$aPortalConf['lists'] = static::LoadListsConfiguration($oApp, $oDesign);
			// - UI extensions
			$aPortalConf['ui_extensions'] = static::LoadUIExtensions($oApp);
			// - Action rules
			static::LoadActionRulesConfiguration($oApp, $oDesign);
			// - Setting UrlMakerClass
			if ($aPortalConf['properties']['urlmaker_class'] !== null)
			{
				ApplicationContext::SetUrlMakerClass($aPortalConf['properties']['urlmaker_class']);
			}
			// - Generating CSS files
			$aImportPaths = array($oApp['combodo.portal.base.absolute_path'].'css/');
			foreach ($aPortalConf['properties']['themes'] as $key => $value)
			{
				if (!is_array($value))
				{
					$aPortalConf['properties']['themes'][$key] = $oApp['combodo.absolute_url'].utils::GetCSSFromSASS('env-'.utils::GetCurrentEnvironment().'/'.$value,
							$aImportPaths);
				}
				else
				{
					$aValues = array();
					foreach ($value as $sSubvalue)
					{
						$aValues[] = $oApp['combodo.absolute_url'].utils::GetCSSFromSASS('env-'.utils::GetCurrentEnvironment().'/'.$sSubvalue,
								$aImportPaths);
					}
					$aPortalConf['properties']['themes'][$key] = $aValues;
				}
			}

			$oApp['combodo.portal.instance.conf'] = $aPortalConf;
		}
		catch (Exception $e)
		{
			throw new Exception('Error while parsing portal configuration file : '.$e->getMessage());
		}
	}

	/**
	 * Loads the current user and stores it in the Silex application so we can use it wherever in the application
	 *
	 * @param \Silex\Application $oApp
	 *
	 * @throws Exception
	 */
	public static function LoadCurrentUser(Application $oApp)
	{
		// User
		$oUser = UserRights::GetUserObject();
		if ($oUser === null)
		{
			throw new Exception('Could not load connected user.');
		}

		$oApp['combodo.current_user'] = $oUser;

		// Contact
		$sContactPhotoUrl = $oApp['combodo.portal.base.absolute_url'].'img/user-profile-default-256px.png';
		// - Checking if we can load the contact
		try
		{
			$oContact = UserRights::GetContactObject();
		}
		catch (Exception $e)
		{
			$oAllowedOrgSet = $oUser->Get('allowed_org_list');
			if ($oAllowedOrgSet->Count() > 0)
			{
				throw new Exception('Could not load contact related to connected user. (Tip: Make sure the contact\'s organization is among the user\'s allowed organizations)');
			}
			else
			{
				throw new Exception('Could not load contact related to connected user.');
			}
		}
		// - Retrieving picture
		if ($oContact)
		{
			if (MetaModel::IsValidAttCode(get_class($oContact), 'picture'))
			{
				$oImage = $oContact->Get('picture');
				if (is_object($oImage) && !$oImage->IsEmpty())
				{
					$sContactPhotoUrl = $oImage->GetDownloadURL(get_class($oContact), $oContact->GetKey(), 'picture');
				}
				else
				{
					$sContactPhotoUrl = MetaModel::GetAttributeDef(get_class($oContact),
						'picture')->Get('default_image');
				}
			}
		}
		$oApp['combodo.current_contact.photo_url'] = $sContactPhotoUrl;
	}

	/**
	 * Loads the brick's security from the OQL queries to profiles arrays
	 *
	 * @param \Combodo\iTop\Portal\Brick\AbstractBrick $oBrick
	 *
	 * @throws \Exception
	 */
	public static function LoadBrickSecurity(AbstractBrick &$oBrick)
	{
		try
		{
			// Allowed profiles
			if ($oBrick->GetAllowedProfilesOql() !== null && $oBrick->GetAllowedProfilesOql() !== '')
			{
				$oSearch = DBObjectSearch::FromOQL($oBrick->GetAllowedProfilesOql());
				$oSet = new DBObjectSet($oSearch);
				while ($oProfile = $oSet->Fetch())
				{
					$oBrick->AddAllowedProfile($oProfile->Get('name'));
				}
			}

			// Denied profiles
			if ($oBrick->GetDeniedProfilesOql() !== null && $oBrick->GetDeniedProfilesOql() !== '')
			{
				$oSearch = DBObjectSearch::FromOQL($oBrick->GetDeniedProfilesOql());
				$oSet = new DBObjectSet($oSearch);
				while ($oProfile = $oSet->Fetch())
				{
					$oBrick->AddDeniedProfile($oProfile->Get('name'));
				}
			}
		}
		catch (Exception $e)
		{
			throw new Exception('Error while loading security from '.$oBrick->GetId().' brick');
		}
	}

	/**
	 * Finds an AbstractBrick loaded in the $oApp instance configuration from its ID.
	 *
	 * @param \Silex\Application $oApp
	 * @param string $sBrickId
	 *
	 * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 * @throws Exception
	 */
	public static function GetLoadedBrickFromId(Application $oApp, $sBrickId)
	{
		$bFound = false;

		foreach ($oApp['combodo.portal.instance.conf']['bricks'] as $oBrick)
		{
			if ($oBrick->GetId() === $sBrickId)
			{
				$bFound = true;
				break;
			}
		}

		if (!$bFound)
		{
			throw new Exception('Brick with id = "'.$sBrickId.'" was not found among loaded bricks.');
		}

		return $oBrick;
	}

	/**
	 * Return the form properties for the $sClassname in $sMode.
	 *
	 * If not found, tries to find one from the closest parent class.
	 * Else returns a default form based on zlist 'details'
	 *
	 * @param Application $oApp
	 * @param string $sClass Object class to find a form for
	 * @param string $sMode Form mode to find (view|edit|create)
	 *
	 * @return array
	 * @throws \CoreException
	 */
	public static function GetLoadedFormFromClass(Application $oApp, $sClass, $sMode)
	{
		$aForms = $oApp['combodo.portal.instance.conf']['forms'];

		// We try to find the form for that class
		if (isset($aForms[$sClass]) && isset($aForms[$sClass][$sMode]))
		{
			$aForm = $aForms[$sClass][$sMode];
		}
		// If not found, we try find one from the closest parent class
		else
		{
			$bFound = false;
			foreach (MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_EXCLUDELEAF, false) as $sParentClass)
			{
				if (isset($aForms[$sParentClass]) && isset($aForms[$sParentClass][$sMode]))
				{
					$aForm = $aForms[$sParentClass][$sMode];
					$bFound = true;
					break;
				}
			}

			// If we have still not found one, we return a default form
			if (!$bFound)
			{
			    $aForm = static::GenerateDefaultFormForClass($sClass);
			}
		}

		return $aForm;
	}

	/**
	 * Return the attribute list for the $sClassname in $sList.
	 *
	 * If not found, tries to find one from the closest parent class.
	 * Else returns a default attribute list based on zlist 'list'
	 *
	 * @param Application $oApp
	 * @param string $sClass Object class to find a list for
	 * @param string $sList List name to find
	 *
	 * @return array Array of attribute codes
	 * @throws \CoreException
	 */
	public static function GetLoadedListFromClass(Application $oApp, $sClass, $sList = 'default')
	{
		$aLists = $oApp['combodo.portal.instance.conf']['lists'];
		$aList = null;
		$aAttCodes = array();

		// We try to find the list for that class
		if (isset($aLists[$sClass]) && isset($aLists[$sClass][$sList]))
		{
			$aList = $aLists[$sClass][$sList];
		}
		// Else we try to found the default list for that class
		elseif (isset($aLists[$sClass]) && isset($aLists[$sClass]['default']))
		{
			$aList = $aLists[$sClass]['default'];
		}
		// If not found, we try find one from the closest parent class
		else
		{
			foreach (MetaModel::EnumParentClasses($sClass) as $sParentClass)
			{
				// Trying to find the right list
				if (isset($aLists[$sParentClass]) && isset($aLists[$sParentClass][$sList]))
				{
					$aList = $aLists[$sParentClass][$sList];
					break;
				}
				// Or the default list
				elseif (isset($aLists[$sParentClass]) && isset($aLists[$sParentClass]['default']))
				{
					$aList = $aLists[$sParentClass]['default'];
					break;
				}
			}
		}

		// If found, we flatten the list to keep only the attribute codes (not the rank)
		if ($aList !== null)
		{
			foreach ($aList as $aItem)
			{
				$aAttCodes[] = $aItem['att_code'];
			}
		}
		else
		{
			$aAttCodes = MetaModel::FlattenZList(MetaModel::GetZListItems($sClass, 'list'));
		}

		return $aAttCodes;
	}

	/**
	 * Loads the bricks configuration from the module design XML and returns it as an hash array containing :
	 * - 'brick' => array of PortalBrick objects
	 * - 'bricks_total_width' => an integer used to create the home page grid
	 *
	 * @param \Silex\Application $oApp
	 * @param ModuleDesign $oDesign
	 *
	 * @return array
	 * @throws Exception
	 */
	protected static function LoadBricksConfiguration(Application $oApp, ModuleDesign $oDesign)
	{
		$aPortalConf = array(
			'bricks' => array(),
			'bricks_total_width' => 0,
			'bricks_home_count' => 0,
			'bricks_navigation_menu_count' => 0
		);

		foreach ($oDesign->GetNodes('/module_design/bricks/brick') as $oBrickNode)
		{
			try
			{
				$sBrickClass = $oBrickNode->getAttribute('xsi:type');
				if (class_exists($sBrickClass))
				{
					$oBrick = new $sBrickClass();
					$oBrick->LoadFromXml($oBrickNode);
					static::LoadBrickSecurity($oBrick);

					// GLA : This didn't work has the modal flag was set for all instances of that brick
//					// Checking brick modal flag
//					if ($oBrick->GetModal())
//					{
//						// We have to extract / replace the array as we can modify $oApp values directly
//						$aRoutes = $oApp['combodo.portal.instance.routes'];
//						// Init brick's array if necessary
//						if (!isset($aRoutes[$oBrick->GetRouteName()]['navigation_menu_attr']))
//						{
//							$aRoutes[$oBrick->GetRouteName()]['navigation_menu_attr'] = array();
//						}
//						// Add modal datas for the brick
//						$aRoutes[$oBrick->GetRouteName()]['navigation_menu_attr']['data-toggle'] = 'modal';
//						$aRoutes[$oBrick->GetRouteName()]['navigation_menu_attr']['data-target'] = '#modal-for-all';
//						// Finally, replace array in $oApp
//						$oApp['combodo.portal.instance.routes'] = $aRoutes;
//					}
					// Checking brick security
					if ($oBrick->GetActive() && $oBrick->IsGrantedForProfiles(UserRights::ListProfiles()))
					{
						$aPortalConf['bricks'][] = $oBrick;
						$aPortalConf['bricks_total_width'] += $oBrick->GetWidth();
						if ($oBrick->GetVisibleHome())
						{
							$aPortalConf['bricks_home_count']++;
						}
						if ($oBrick->GetVisibleNavigationMenu())
						{
							$aPortalConf['bricks_navigation_menu_count']++;
						}
					}
				}
				else
				{
					throw new DOMFormatException('Unknown brick class "'.$sBrickClass.'" from xsi:type attribute', null,
						null, $oBrickNode);
				}
			}
			catch (DOMFormatException $e)
			{
				throw new Exception('Could not create brick ('.$sBrickClass.') from XML because of a DOM problem : '.$e->getMessage());
			}
			catch (Exception $e)
			{
				throw new Exception('Could not create brick ('.$sBrickClass.') from XML : '.$oBrickNode->Dump().' '.$e->getMessage());
			}
		}
		// - Sorting bricks by rank
		$aPortalConf['bricks_ordering'] = array();
		//   - Home
		$aPortalConf['bricks_ordering']['home'] = $aPortalConf['bricks'];
		usort($aPortalConf['bricks_ordering']['home'], function ($a, $b) {
			return $a->GetRankHome() > $b->GetRankHome();
		});
		//    - Navigation menu
		$aPortalConf['bricks_ordering']['navigation_menu'] = $aPortalConf['bricks'];
		usort($aPortalConf['bricks_ordering']['navigation_menu'], function ($a, $b) {
			return $a->GetRankNavigationMenu() > $b->GetRankNavigationMenu();
		});

		return $aPortalConf;
	}

	/**
	 * Loads the forms configuration from the module design XML and returns it as an array containing :
	 * - <CLASSNAME> => array(
	 *                      'view'|'edit'|'create' => array(
	 *                          'fields_type' => 'custom_list'|'twig'|'zlist',
	 *                          'fields' => <CONTENT>
	 *                      ),
	 *                      ...
	 *                  ),
	 *  ...
	 *
	 * @param \Silex\Application $oApp
	 * @param ModuleDesign $oDesign
	 *
	 * @return array
	 * @throws Exception
	 * @throws DOMFormatException
	 */
	protected static function LoadFormsConfiguration(Application $oApp, ModuleDesign $oDesign)
	{
		$aForms = array();

		foreach ($oDesign->GetNodes('/module_design/forms/form') as $oFormNode)
		{
			try
			{
				// Parsing form id
				if ($oFormNode->getAttribute('id') === '')
				{
					throw new DOMFormatException('form tag must have an id attribute', null, null, $oFormNode);
				}

				// Parsing form object class
				if ($oFormNode->GetUniqueElement('class')->GetText() !== null)
				{
					// Parsing class
					$sFormClass = $oFormNode->GetUniqueElement('class')->GetText();

					// Parsing properties
					$aFormProperties = array(
						'display_mode' => static::FORM_DEFAULT_DISPLAY_MODE,
						'always_show_submit' => static::FORM_DEFAULT_ALWAYS_SHOW_SUBMIT,
					);
					if ($oFormNode->GetOptionalElement('properties') !== null)
					{
						foreach ($oFormNode->GetOptionalElement('properties')->childNodes as $oPropertyNode)
						{
							switch ($oPropertyNode->nodeName)
							{
								case 'display_mode':
									$aFormProperties['display_mode'] = $oPropertyNode->GetText(static::FORM_DEFAULT_DISPLAY_MODE);
									break;
								case 'always_show_submit':
									$aFormProperties['always_show_submit'] = ($oPropertyNode->GetText('false') === 'true') ? true : false;
									break;
							}
						}
					}

					// Parsing availables modes for that form (view, edit, create, apply_stimulus)
					$aFormStimuli = array();
					if (($oFormNode->GetOptionalElement('modes') !== null) && ($oFormNode->GetOptionalElement('modes')->GetNodes('mode')->length > 0))
					{
						$aModes = array();
						foreach ($oFormNode->GetOptionalElement('modes')->GetNodes('mode') as $oModeNode)
						{
							if ($oModeNode->getAttribute('id') !== '')
							{
								$aModes[] = $oModeNode->getAttribute('id');
							}
							else
							{
								throw new DOMFormatException('Mode tag must have an id attribute', null, null,
									$oFormNode);
							}

							// If apply_stimulus mode, checking if stimuli are defined
							if ($oModeNode->getAttribute('id') === 'apply_stimulus')
							{
								$oStimuliNode = $oModeNode->GetOptionalElement('stimuli');
								// if stimuli are defined, we overwrite the form that could have been set by the generic form
								if ($oStimuliNode !== null)
								{
									foreach ($oStimuliNode->GetNodes('stimulus') as $oStimulusNode)
									{
										$sStimulusCode = $oStimulusNode->getAttribute('id');

										// Removing default form is present (in case the default forms were parsed before the current one (from current or parent class))
										if (isset($aForms[$sFormClass]['apply_stimulus'][$sStimulusCode]))
										{
											unset($aForms[$sFormClass]['apply_stimulus'][$sStimulusCode]);
										}

										$aFormStimuli[] = $oStimulusNode->getAttribute('id');
									}
								}
							}
						}
					}
					else
					{
						// If no mode was specified, we set it all but stimuli as it would have no sense that every transition forms
						// have as many fields displayed as a regular edit form for example.
						$aModes = array('view', 'edit', 'create');
					}

					// Parsing fields
					$aFields = array(
						'id' => $oFormNode->getAttribute('id'),
						'type' => null,
						'properties' => $aFormProperties,
						'fields' => null,
						'layout' => null
					);
					// ... either enumerated fields ...
					if ($oFormNode->GetOptionalElement('fields') !== null)
					{
						$aFields['type'] = 'custom_list';
						$aFields['fields'] = array();

						foreach ($oFormNode->GetOptionalElement('fields')->GetNodes('field') as $oFieldNode)
						{
							$sFieldId = $oFieldNode->getAttribute('id');
							if ($sFieldId !== '')
							{
								$aField = array();
								// Parsing field options like read_only, hidden and mandatory
								if ($oFieldNode->GetOptionalElement('read_only'))
								{
									$aField['readonly'] = ($oFieldNode->GetOptionalElement('read_only')->GetText('true') === 'true') ? true : false;
								}
								if ($oFieldNode->GetOptionalElement('mandatory'))
								{
									$aField['mandatory'] = ($oFieldNode->GetOptionalElement('mandatory')->GetText('true') === 'true') ? true : false;
								}
								if ($oFieldNode->GetOptionalElement('hidden'))
								{
									$aField['hidden'] = ($oFieldNode->GetOptionalElement('hidden')->GetText('true') === 'true') ? true : false;
								}

								$aFields['fields'][$sFieldId] = $aField;
							}
							else
							{
								throw new DOMFormatException('Field tag must have an id attribute', null, null,
									$oFormNode);
							}
						}
					}
					// ... or the default zlist
					else
					{
						$aFields['type'] = 'zlist';
						$aFields['fields'] = 'details';
					}

					// Parsing presentation
					if ($oFormNode->GetOptionalElement('twig') !== null)
					{
						// Extracting the twig template and removing the first and last lines (twig tags)
						$sXml = $oDesign->saveXML($oFormNode->GetOptionalElement('twig'));
						$sXml = preg_replace('/^.+\n/', '', $sXml);
						$sXml = preg_replace('/\n.+$/', '', $sXml);

						$aFields['layout'] = array(
							'type' => (preg_match('/\{\{|\{\#|\{\%/', $sXml) === 1) ? 'twig' : 'xhtml',
							'content' => $sXml
						);
					}

					// Adding form for each class / mode
					foreach ($aModes as $sMode)
					{
						// Initializing current class if necessary
						if (!isset($aForms[$sFormClass]))
						{
							$aForms[$sFormClass] = array();
						}

						if ($sMode === 'apply_stimulus')
						{
							// Iterating over current class and child classes to fill stimuli forms
							foreach (MetaModel::EnumChildClasses($sFormClass, ENUM_CHILD_CLASSES_ALL) as $sChildClass)
							{
								// Initializing child class if necessary
								if (!isset($aForms[$sChildClass][$sMode]))
								{
									$aForms[$sChildClass][$sMode] = array();
								}

								// If stimuli are implicitly defined (empty tag), we define all those that have not already been by other forms.
								$aChildStimuli = $aFormStimuli;
								if (empty($aChildStimuli))
								{
									// Stimuli already declared
									$aDeclaredStimuli = array();
									if (array_key_exists($sChildClass, $aForms) && array_key_exists('apply_stimulus',
											$aForms[$sChildClass]))
									{
										$aDeclaredStimuli = array_keys($aForms[$sChildClass]['apply_stimulus']);
									}
									// All stimuli
									$aDatamodelStimuli = array_keys(MetaModel::EnumStimuli($sChildClass));
									// Missing stimuli
									$aChildStimuli = array_diff($aDatamodelStimuli, $aDeclaredStimuli);
								}

								foreach ($aChildStimuli as $sFormStimulus)
								{
									// Setting form if not defined OR if it was defined by a parent (abstract) class
									if (!isset($aForms[$sChildClass][$sMode][$sFormStimulus]) || !empty($aFormStimuli))
									{
										$aForms[$sChildClass][$sMode][$sFormStimulus] = $aFields;
										$aForms[$sChildClass][$sMode][$sFormStimulus]['id'] = 'apply_stimulus-'.$sChildClass.'-'.$sFormStimulus;
									}
								}
							}
						}
						elseif (!isset($aForms[$sFormClass][$sMode]))
						{
							$aForms[$sFormClass][$sMode] = $aFields;
						}
						else
						{
							throw new DOMFormatException('There is already a form for the class "'.$sFormClass.'" in "'.$sMode.'"',
								null, null, $oFormNode);
						}
					}
				}
				else
				{
					throw new DOMFormatException('Class tag must be defined', null, null, $oFormNode);
				}
			}
			catch (DOMFormatException $e)
			{
				throw new Exception('Could not create from [id="'.$oFormNode->getAttribute('id').'"] from XML because of a DOM problem : '.$e->getMessage());
			}
			catch (Exception $e)
			{
				throw new Exception('Could not create from from XML : '.$oFormNode->Dump().' '.$e->getMessage());
			}
		}

		return $aForms;
	}

	/**
	 * Loads the scopes configuration from the module design XML
	 *
	 * @param \Silex\Application $oApp
	 * @param ModuleDesign $oDesign
	 */
	public static function LoadScopesConfiguration(Application $oApp, ModuleDesign $oDesign)
	{
		$oApp['scope_validator']->Init($oDesign->GetNodes('/module_design/classes/class'));
	}

	/**
	 * Loads the lifecycle configuration from the module design XML
	 *
	 * @param \Silex\Application $oApp
	 * @param ModuleDesign $oDesign
	 */
	protected static function LoadLifecycleConfiguration(Application $oApp, ModuleDesign $oDesign)
	{
		$oApp['lifecycle_validator']->Init($oDesign->GetNodes('/module_design/classes/class'));
	}

	/**
	 * Loads the context helper from the module design XML
	 *
	 * @param \Silex\Application $oApp
	 * @param ModuleDesign $oDesign
	 */
	protected static function LoadActionRulesConfiguration(Application $oApp, ModuleDesign $oDesign)
	{
		$oApp['context_manipulator']->Init($oDesign->GetNodes('/module_design/action_rules/action_rule'));
	}

	/**
	 * Loads the classes lists from the module design XML. They are mainly used when searching an external key but
	 * could be used more extensively later
	 *
	 * @param \Silex\Application $oApp
	 * @param ModuleDesign $oDesign
	 *
	 * @return array
	 * @throws \DOMFormatException
	 */
	protected static function LoadListsConfiguration(Application $oApp, ModuleDesign $oDesign)
	{
		$iDefaultItemRank = 0;
		$aClassesLists = array();

		// Parsing XML file
		// - Each classes
		foreach ($oDesign->GetNodes('/module_design/classes/class') as $oClassNode)
		{
			$aClassLists = array();
			$sClassId = $oClassNode->getAttribute('id');
			if ($sClassId === null)
			{
				throw new DOMFormatException('Class tag must have an id attribute', null, null, $oClassNode);
			}

			// - Each lists
			foreach ($oClassNode->GetNodes('./lists/list') as $oListNode)
			{
				$aListItems = array();
				$sListId = $oListNode->getAttribute('id');
				if ($sListId === null)
				{
					throw new DOMFormatException('List tag of "'.$sClassId.'" class must have an id attribute', null,
						null, $oListNode);
				}

				// - Each items
				foreach ($oListNode->GetNodes('./items/item') as $oItemNode)
				{
					$sItemId = $oItemNode->getAttribute('id');
					if ($sItemId === null)
					{
						throw new DOMFormatException('Item tag of "'.$sItemId.'" list must have an id attribute', null,
							null, $oItemNode);
					}

					$aItem = array(
						'att_code' => $sItemId,
						'rank' => $iDefaultItemRank
					);

					$oRankNode = $oItemNode->GetOptionalElement('rank');
					if ($oRankNode !== null)
					{
						$aItem['rank'] = $oRankNode->GetText($iDefaultItemRank);
					}

					$aListItems[] = $aItem;
				}
				// - Sorting list items by rank
				usort($aListItems, function ($a, $b) {
					return $a['rank'] > $b['rank'];
				});
				$aClassLists[$sListId] = $aListItems;
			}

			// - Adding class only if it has at least one list
			if (!empty($aClassLists))
			{
				$aClassesLists[$sClassId] = $aClassLists;
			}
		}

		return $aClassesLists;
	}

	/**
	 * Loads portal UI extensions
	 *
	 * @param \Silex\Application $oApp
	 *
	 * @return array
	 */
	protected static function LoadUIExtensions(Application $oApp)
	{
		$aUIExtensions = array(
			'css_files' => array(),
			'css_inline' => null,
			'js_files' => array(),
			'js_inline' => null,
			'html' => array(),
		);
		$aUIExtensionHooks = array(
			iPortalUIExtension::ENUM_PORTAL_EXT_UI_BODY,
			iPortalUIExtension::ENUM_PORTAL_EXT_UI_NAVIGATION_MENU,
			iPortalUIExtension::ENUM_PORTAL_EXT_UI_MAIN_CONTENT,
		);

		/** @var iPortalUIExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iPortalUIExtension') as $oExtensionInstance)
		{
			// Adding CSS files
			$aUIExtensions['css_files'] = array_merge($aUIExtensions['css_files'],
				$oExtensionInstance->GetCSSFiles($oApp));

			// Adding CSS inline
			$sCSSInline = $oExtensionInstance->GetCSSInline($oApp);
			if ($sCSSInline !== null)
			{
				$aUIExtensions['css_inline'] .= "\n\n".$sCSSInline;
			}

			// Adding JS files
			$aUIExtensions['js_files'] = array_merge($aUIExtensions['js_files'],
				$oExtensionInstance->GetJSFiles($oApp));

			// Adding JS inline
			$sJSInline = $oExtensionInstance->GetJSInline($oApp);
			if ($sJSInline !== null)
			{
				// Note: Semi-colon is to prevent previous script that would have omitted it.
				$aUIExtensions['js_inline'] .= "\n\n;\n".$sJSInline;
			}

			// Adding HTML for each hook
			foreach ($aUIExtensionHooks as $sUIExtensionHook)
			{
				$sFunctionName = 'Get'.$sUIExtensionHook.'HTML';
				$sHTML = $oExtensionInstance->$sFunctionName($oApp);
				if ($sHTML !== null)
				{
					if (!array_key_exists($sUIExtensionHook, $aUIExtensions['html']))
					{
						$aUIExtensions['html'][$sUIExtensionHook] = '';
					}
					$aUIExtensions['html'][$sUIExtensionHook] .= "\n\n".$sHTML;
				}
			}
		}

		return $aUIExtensions;
	}

    /**
     * Generate the form data for the $sClass.
     * Form will look like the "Properties" tab of a $sClass object in the console.
     *
     * @param string $sClass
     *
     * @return array
     */
	protected static function GenerateDefaultFormForClass($sClass, $bAddLinksets = false)
    {
        $aForm = array(
            'id' => strtolower($sClass)."-default-".uniqid(),
            'type' => 'custom_list',
            'properties' => array(
                'display_mode' => static::FORM_DEFAULT_DISPLAY_MODE,
                'always_show_submit' => static::FORM_DEFAULT_ALWAYS_SHOW_SUBMIT,
            ),
            'fields' => array(),
            'layout' => array(
                'type' => 'xhtml',
                'content' => '',
            ),
        );

        // Generate layout
        $sContent = "";

        // - Retrieve zlist details
        $aDetailsList = MetaModel::GetZListItems($sClass, 'details');
        $aDetailsStruct = cmdbAbstractObject::ProcessZlist($aDetailsList, array(), 'UI:PropertiesTab', 'col1', '');
        $aPropertiesStruct = $aDetailsStruct['UI:PropertiesTab'];

        // Count cols (not linksets)
        $iColCount = 0;
        foreach($aPropertiesStruct as $sColId => $aColFieldsets)
        {
            if(substr($sColId, 0, 1) !== '_')
            {
                foreach($aColFieldsets as $sFieldsetName => $aAttCodes)
                {
                    if(substr($sFieldsetName, 0, 1) !== '_')
                    {
                        $iColCount++;
                        break;
                    }
                }
            }
        }
        // If no cols, return a default form with all fields one after another
        if($iColCount === 0)
        {
            return array(
                'id' => 'default',
                'type' => 'zlist',
                'fields' => 'details',
                'layout' => null
            );
        }
        // Warning, this might not be great when 12 modulo $iColCount is greater than 0.
        $sColCSSClass = 'col-sm-'.floor(12/$iColCount);

        $sLinksetsHTML = "";
        $sRowHTML = "<div class=\"row\">\n";
        foreach($aPropertiesStruct as $sColId => $aColFieldsets)
        {
            $sColsHTML = "\t<div class=\"".$sColCSSClass."\">\n";
            foreach($aColFieldsets as $sFieldsetName => $aAttCodes)
            {
                // Add fieldset, not linkset
                if(substr($sFieldsetName, 0, 1) !== '_')
                {
                    $sFieldsetHTML = "\t\t<fieldset>\n";
                    $sFieldsetHTML .= "\t\t\t<legend>".htmlentities(Dict::S($sFieldsetName), ENT_QUOTES, 'UTF-8')."</legend>\n";

                    foreach($aAttCodes as $sAttCode)
                    {
                        $sFieldsetHTML .= "\t\t\t<div class=\"form_field\" data-field-id=\"".$sAttCode."\"></div>\n";
                    }

                    $sFieldsetHTML .= "\t\t</fieldset>\n";

                    // Add to col
                    $sColsHTML .= $sFieldsetHTML;
                }
                elseif($bAddLinksets)
                {
                    foreach($aAttCodes as $sAttCode)
                    {
                        $sLinksetsHTML .= "<div class=\"form_field\" data-field-id=\"".$sAttCode."\"></div>\n";
                    }
                }
            }
            $sColsHTML .= "\t</div>\n";

            // Add col to row
            $sRowHTML .= $sColsHTML;
        }
        $sRowHTML .= "</div>\n";

        // Add row to twig
        $sContent .= $sRowHTML;
        // Add linksets to twig
        $sContent .= $sLinksetsHTML;

        $aForm['layout']['content'] = $sContent;

        return $aForm;
    }

}
