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
use \Silex\Application;
use \Symfony\Component\Debug\ErrorHandler;
use \Symfony\Component\Debug\ExceptionHandler;
use \Symfony\Component\HttpFoundation\Request;
use \Twig_SimpleFilter;
use \Dict;
use \utils;
use \UserRights;
use \DOMFormatException;
use \ModuleDesign;
use \MetaModel;
use \DBObjectSearch;
use \DBObjectSet;
use \Combodo\iTop\Portal\Brick\AbstractBrick;

/**
 * Contains static methods to help loading / registering classes of the application.
 * Mostly used for Controllers / Routers / Entities initialization.
 *
 * @author Guillaume Lajarige
 */
class ApplicationHelper
{

	/**
	 * Loads classes from the base portal
	 *
	 * @param string $sScannedDir Directory to load the files from
	 * @param string $sFilePattern Pattern of files to load
	 * @param string $sType Type of files to load, used only in the Exception message, can be anything
	 * @throws \Exception
	 */
	static function LoadClasses($sScannedDir, $sFilePattern, $sType)
	{
		// Loading classes from base portal
		foreach (scandir($sScannedDir) as $sFile)
		{
			if (strpos($sFile, $sFilePattern) !== false && file_exists($sFilepath = $sScannedDir . '/' . $sFile))
			{
				try
				{
					require_once $sFilepath;
				}
				catch (Exception $e)
				{
					throw new Exception('Error while trying to load ' . $sType . ' ' . $sFile);
				}
			}
		}
	}

	/**
	 * Loads controllers from the base portal
	 *
	 * @param string $sScannedDir Directory to load the controllers from
	 * @throws \Exception
	 */
	static function LoadControllers($sScannedDir = null)
	{
		if ($sScannedDir === null)
		{
			$sScannedDir = __DIR__ . '/../controllers';
		}

		// Loading controllers from base portal (those from modules have already been loaded by module.xxx.php files)
		self::LoadClasses($sScannedDir, 'controller.class.inc.php', 'controller');
	}

	/**
	 * Loads routers from the base portal
	 *
	 * @param string $sScannedDir Directory to load the routers from
	 * @throws \Exception
	 */
	static function LoadRouters($sScannedDir = null)
	{
		if ($sScannedDir === null)
		{
			$sScannedDir = __DIR__ . '/../routers';
		}

		// Loading routers from base portal (those from modules have already been loaded by module.xxx.php files)
		self::LoadClasses($sScannedDir, 'router.class.inc.php', 'router');
	}

	/**
	 * Loads bricks from the base portal
	 *
	 * @param string $sScannedDir Directory to load the bricks from
	 * @throws \Exception
	 */
	static function LoadBricks($sScannedDir = null)
	{
		if ($sScannedDir === null)
		{
			$sScannedDir = __DIR__ . '/../entities';
		}

		// Loading bricks from base portal (those from modules have already been loaded by module.xxx.php files)
		self::LoadClasses($sScannedDir, 'brick.class.inc.php', 'brick');
	}

	/**
	 * Registers routes in the Silex Application from all declared Router classes
	 *
	 * @param \Silex\Application $oApp
	 * @throws \Exception
	 */
	static function RegisterRoutes(Application $oApp)
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
	 * @return array
	 */
	static function GetRoutes(Application $oApp, $bNamesOnly = false)
	{
		return ($bNamesOnly) ? array_keys($oApp['combodo.portal.instance.routes']) : $oApp['combodo.portal.instance.routes'];
	}

	/**
	 * Registers Twig extensions such as filters or functions.
	 * It allows us to access some stuff directly in twig.
	 *
	 * @param \Silex\Application $oApp
	 */
	static function RegisterTwigExtensions(Application $oApp)
	{
		// A filter to translate a string via the Dict::S function
		// Usage in twig : {{ 'String:ToTranslate'|dict_s }}
		$oApp['twig']->addFilter(new Twig_SimpleFilter('dict_s', function($sStringCode, $sDefault = null, $bUserLanguageOnly = false)
		{
			return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
		})
		);
		// A filter to format a string via the Dict::Format function
		// Usage in twig : {{ 'String:ToTranslate'|dict_format() }}
		$oApp['twig']->addFilter(new Twig_SimpleFilter('dict_format', function($sStringCode, $sParam01 = null, $sParam02 = null, $sParam03 = null, $sParam04 = null)
		{
			return Dict::Format($sStringCode, $sParam01, $sParam02, $sParam03, $sParam04);
		})
		);
		// Filters to enable base64 encode/decode
		// Usage in twig : {{ 'String to encode'|base64_encode }}
		$oApp['twig']->addFilter(new Twig_SimpleFilter('base64_encode', 'base64_encode'));
		$oApp['twig']->addFilter(new Twig_SimpleFilter('base64_decode', 'base64_decode'));
		// Filters to enable json decode  (encode already exists)
		// Usage in twig : {{ aSomeArray|json_decode }}
		$oApp['twig']->addFilter(new Twig_SimpleFilter('json_decode', function($sJsonString, $bAssoc = false)
		{
			return json_decode($sJsonString, $bAssoc);
		})
		);
	}

	/**
	 * Registers an exception handler that will intercept controllers exceptions and display them in a nice template.
	 * Note : It is only active when $oApp['debug'] is false
	 *
	 * @param Application $oApp
	 */
	static function RegisterExceptionHandler(Application $oApp)
	{
		ErrorHandler::register();
		ExceptionHandler::register(($oApp['debug'] === true));

		if (!$oApp['debug'])
		{
			$oApp->error(function(Exception $e, $code) use ($oApp)
			{
				$aData = array(
					'exception' => $e,
					'code' => $code,
					'error_title' => '',
					'error_message' => $e->getMessage()
				);

				switch ($code)
				{
					case 404:
						$aData['error_title'] = Dict::S('Error:HTTP:404');
						break;
					default:
						$aData['error_title'] = Dict::S('Error:HTTP:500');
						break;
				}

				if ($oApp['request']->isXmlHttpRequest())
				{
					$oResponse = $oApp->json($aData, $code);
				}
				else
				{
					$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/errors/layout.html.twig', $aData);
				}

				return $oResponse;
			});
		}
	}

	/**
	 * Loads the portal instance configuration from its module design into the Silex application
	 *
	 * @param \Silex\Application $oApp
	 * @throws Exception
	 */
	static function LoadPortalConfiguration(Application $oApp)
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
					'logo' => null,
					'themes' => array(
						'bootstrap' => $oApp['combodo.portal.base.absolute_url'] . 'css/bootstrap-theme.min.css',
						'portal' => $oApp['combodo.portal.base.absolute_url'] . 'css/portal.css',
						'others' => array(),
					),
					'templates' => array(
						'layout' => 'itop-portal-base/portal/src/views/layout.html.twig',
						'home' => 'itop-portal-base/portal/src/views/home/layout.html.twig'
					),
					'triggers_query' => null,
					'attachments' => array(
						'allow_delete' => true
					)
				),
				'portals' => array(),
				'forms' => array(),
				'bricks' => array(),
				'bricks_total_width' => 0
			);
			// - Global portal properties
			foreach ($oDesign->GetNodes('/module_design/properties/*') as $oPropertyNode)
			{
				$bPropertyNodeError = false;
				switch ($oPropertyNode->nodeName)
				{
					case 'name':
					case 'triggers_query':
						$aPortalConf['properties'][$oPropertyNode->nodeName] = $oPropertyNode->GetText($aPortalConf['properties'][$oPropertyNode->nodeName]);
						break;
					case 'logo':
						$sLogoUri = $oPropertyNode->GetText($aPortalConf['properties'][$oPropertyNode->nodeName]);

						if ($sLogoUri === null)
						{
							// There is no logo : do nothing
						}
						elseif (preg_match('/^http/', $sLogoUri))
						{
							// The uri is already complete : do nothing
						}
						else
						{
							// We prefix it with the server base url
							$sLogoUri = utils::GetAbsoluteUrlAppRoot() . 'env-' . utils::GetCurrentEnvironment() . '/' . $sLogoUri;
						}

						$aPortalConf['properties'][$oPropertyNode->nodeName] = $sLogoUri;
						break;
					case 'themes':
					case 'templates':
						foreach ($oPropertyNode->GetNodes('template|theme') as $oSubNode)
						{
							if (!$oSubNode->hasAttribute('id') || $oSubNode->GetText(null) === null)
							{
								throw new DOMFormatException('Tag ' . $oSubNode->nodeName . ' must have a "id" attribute as well as a value', null, null, $oSubNode);
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
											$aPortalConf['properties']['themes'][$sNodeId] = $oApp['combodo.portal.instance.absolute_url'] . '' . $oSubNode->GetText(null);
											break;
										default:
											$aPortalConf['properties']['themes']['others'][] = $oApp['combodo.portal.instance.absolute_url'] . '' . $oSubNode->GetText(null);
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
											throw new DOMFormatException('Value "' . $sNodeId . '" is not handled for template[@id]', null, null, $oSubNode);
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
				}
			}
			// - User allowed portals
			$aPortalConf['portals'] = UserRights::GetAllowedPortals();
			// - Bricks
			$aPortalConf = static::LoadBricksConfiguration($oApp, $oDesign) + $aPortalConf;
			// - Forms
			$aPortalConf['forms'] = static::LoadFormsConfiguration($oApp, $oDesign);
			// - Scopes
			static::LoadScopesConfiguration($oApp, $oDesign);
			// - Action rules
			static::LoadActionRulesConfiguration($oApp, $oDesign);

			$oApp['combodo.portal.instance.conf'] = $aPortalConf;
		}
		catch (Exception $e)
		{
			throw new Exception('Error while parsing portal configuration file : ' . $e->getMessage());
		}
	}

	/**
	 * Loads the current user and stores it in the Silex application so we can use it wherever in the application
	 *
	 * @param \Silex\Application $oApp
	 * @throws Exception
	 */
	static function LoadCurrentUser(Application $oApp)
	{
		$oUser = UserRights::GetUserObject();
		if ($oUser === null)
		{
			throw new Exception('Could not load connected user.');
		}

		$oApp['combodo.current_user'] = $oUser;
	}

	/**
	 * Loads the brick's security from the OQL queries to profiles arrays
	 *
	 * @param \Combodo\iTop\Portal\Helper\AbstractBrick $oBrick
	 */
	static function LoadBrickSecurity(AbstractBrick &$oBrick)
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
			throw new Exception('Error while loading security from ' . $oBrick->GetId() . ' brick');
		}
	}

	/**
	 * Finds an AbstractBrick loaded in the $oApp instance configuration from its ID.
	 *
	 * @param \Silex\Application $oApp
	 * @param string $sBrickId
	 * @return \Combodo\iTop\Portal\Brick\AbstractBrick
	 * @throws Exception
	 */
	static function GetLoadedBrickFromId(Application $oApp, $sBrickId)
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
			throw new Exception('Brick with id = "' . $sBrickId . '" was not found among loaded bricks.');
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
	 * @return array
	 */
	static function GetLoadedFormFromClass(Application $oApp, $sClass, $sMode)
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
			foreach (MetaModel::EnumParentClasses($sClass) as $sParentClass)
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
				$aForm = array(
					'id' => 'default',
					'type' => 'zlist',
					'fields' => 'details',
					'layout' => null
				);
			}
		}

		return $aForm;
	}

	/**
	 * Loads the bricks configuration from the module design XML and returns it as an hash array containing :
	 * - 'brick' => array of PortalBrick objects
	 * - 'bricks_total_width' => an integer used to create the home page grid
	 *
	 * @param \Silex\Application $oApp
	 * @param ModuleDesign $oDesign
	 * @return array
	 * @throws Exception
	 * @throws DOMFormatException
	 */
	static protected function LoadBricksConfiguration(Application $oApp, ModuleDesign $oDesign)
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
					if ($oBrick->IsGrantedForProfiles(UserRights::ListProfiles()))
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
					throw new DOMFormatException('Unknown brick class "' . $sBrickClass . '" from xsi:type attribute', null, null, $oBrickNode);
				}
			}
			catch (DOMFormatException $e)
			{
				throw new Exception('Could not create brick (' . $sBrickClass . ') from XML because of a DOM problem : ' . $e->getMessage());
			}
			catch (Exception $e)
			{
				throw new Exception('Could not create brick (' . $sBrickClass . ') from XML : ' . $oBrickNode->Dump() . ' ' . $e->getMessage());
			}
		}
		// - Sorting bricks by rank
		usort($aPortalConf['bricks'], function($a, $b)
		{
			return $a->GetRank() > $b->GetRank();
		});

		return $aPortalConf;
	}

	/**
	 * Loads the forms configuration from the module design XML and returns it as an array containing :
	 * - <CLASSNAME> => array(
	 * 					  'view'|'edit'|'create' => array(
	 * 						  'fields_type' => 'custom_list'|'twig'|'zlist',
	 * 						  'fields' => <CONTENT>
	 * 					  ),
	 * 					  ...
	 * 				  ),
	 *  ...
	 *
	 * @param \Silex\Application $oApp
	 * @param ModuleDesign $oDesign
	 * @return array
	 * @throws Exception
	 * @throws DOMFormatException
	 */
	static protected function LoadFormsConfiguration(Application $oApp, ModuleDesign $oDesign)
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
					$sFormClass = $oFormNode->GetUniqueElement('class')->GetText();

					// Parsing availables modes for that form (view, edit, create)
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
								throw new DOMFormatException('Mode tag must have an id attribute', null, null, $oFormNode);
							}
						}
					}
					else
					{
						$aModes = array('view', 'edit', 'create');
					}

					// Parsing fields
					$aFields = array(
						'id' => $oFormNode->getAttribute('id'),
						'type' => null,
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
								throw new DOMFormatException('Field tag must have an id attribute', null, null, $oFormNode);
							}
						}
					}
//					// ... or a specified zlist
//					elseif ($oFormNode->GetOptionalElement('presentation') !== null)
//					{
//						// This is not implemented yet as it was rejected until futher notice.
//					}
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
						if (!isset($aForms[$sFormClass]))
						{
							$aForms[$sFormClass] = array();
						}

						if (!isset($aForms[$sFormClass][$sMode]))
						{
							$aForms[$sFormClass][$sMode] = $aFields;
						}
						else
						{
							throw new DOMFormatException('There is already a form for the class "' . $sFormClass . '" in "' . $sMode . '"', null, null, $oFormNode);
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
				throw new Exception('Could not create from [id="' . $oFormNode->getAttribute('id') . '"] from XML because of a DOM problem : ' . $e->getMessage());
			}
			catch (Exception $e)
			{
				throw new Exception('Could not create from from XML : ' . $oFormNode->Dump() . ' ' . $e->getMessage());
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
	static protected function LoadScopesConfiguration(Application $oApp, ModuleDesign $oDesign)
	{
		$oApp['scope_validator']->Init($oDesign->GetNodes('/module_design/classes/class'));
	}

	/**
	 * Loads the context helper from the module design XML
	 *
	 * @param \Silex\Application $oApp
	 * @param ModuleDesign $oDesign
	 */
	static protected function LoadActionRulesConfiguration(Application $oApp, ModuleDesign $oDesign)
	{
		$oApp['context_manipulator']->Init($oDesign->GetNodes('/module_design/action_rules/action_rule'));
	}

}

?>