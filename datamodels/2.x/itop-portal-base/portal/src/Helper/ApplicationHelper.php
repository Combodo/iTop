<?php

/**
 * Copyright (C) 2013-2019 Combodo SARL
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
 *
 *
 */

namespace Combodo\iTop\Portal\Helper;

use cmdbAbstractObject;
use Combodo\iTop\Portal\Brick\AbstractBrick;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use Exception;
use IssueLog;
use MetaModel;
use Silex\Application;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Contains static methods to help loading / registering classes of the application.
 * Mostly used for Controllers / Routers / Entities initialization.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since  2.7.0
 */
class ApplicationHelper
{
	/** @var string FORM_ENUM_DISPLAY_MODE_COSY */
	const FORM_ENUM_DISPLAY_MODE_COSY = 'cosy';
	/** @var string FORM_ENUM_DISPLAY_MODE_COMPACT */
	const FORM_ENUM_DISPLAY_MODE_COMPACT = 'compact';
	/** @var string FORM_DEFAULT_DISPLAY_MODE */
	const FORM_DEFAULT_DISPLAY_MODE = self::FORM_ENUM_DISPLAY_MODE_COSY;
	/** @var bool FORM_DEFAULT_ALWAYS_SHOW_SUBMIT */
	const FORM_DEFAULT_ALWAYS_SHOW_SUBMIT = false;

	/**
	 * Loads classes from the base portal
	 *
	 * @param string $sScannedDir  Directory to load the files from
	 * @param string $sFilePattern Pattern of files to load
	 * @param string $sType        Type of files to load, used only in the Exception message, can be anything
	 *
	 * @throws \Exception
	 * @deprecated Since 2.7.0
	 *
	 */
	public static function LoadClasses($sScannedDir, $sFilePattern, $sType)
	{
		@trigger_error(
			sprintf(
				'Usage of legacy LoadClasses is deprecated. You should rely on autoloading (and therefore follow PSR4).',
				__FILE__
			),
			E_USER_DEPRECATED
		);

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
	 * Registers an exception handler that will intercept controllers exceptions and display them in a nice template.
	 * Note : It is only active when $oApp['debug'] is false
	 *
	 * @param Application $oApp
	 *
	 * @todo
	 */
	public static function RegisterExceptionHandler(Application $oApp)
	{
		// Intercepting fatal errors and exceptions
		ErrorHandler::register();
		ExceptionHandler::register(($oApp['debug'] === true));

		// Intercepting manually aborted request
		if (1 || !$oApp['debug'])
		{
			$oApp->error(function (Exception $oException /*, Request $oRequest*/) use ($oApp) {
				$iErrorCode = ($oException instanceof HttpException) ? $oException->getStatusCode() : 500;

				$aData = array(
					'exception' => $oException,
					'code' => $iErrorCode,
					'error_title' => '',
					'error_message' => $oException->getMessage(),
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

					$oResponse = $oApp['twig']->render('itop-portal-base/portal/templates/errors/layout.html.twig',
						$aData);
				}

				return $oResponse;
			});
		}
	}

	/**
	 * Loads the brick's security from the OQL queries to profiles arrays
	 *
	 * @param \Combodo\iTop\Portal\Brick\AbstractBrick $oBrick
	 *
	 * @throws \Exception
	 */
	public static function LoadBrickSecurity(AbstractBrick $oBrick)
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
	 * Return the form properties for the $sClassname in $sMode.
	 *
	 * If not found, tries to find one from the closest parent class.
	 * Else returns a default form based on zlist 'details'
	 *
	 * @param array  $aForms
	 * @param string $sClass Object class to find a form for
	 * @param string $sMode  Form mode to find (view|edit|create)
	 *
	 * @return array
	 *
	 * @throws \CoreException
	 */
	public static function GetLoadedFormFromClass($aForms, $sClass, $sMode)
	{
		$aForm = null;

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
	 * @param array  $aLists
	 * @param string $sClass Object class to find a list for
	 * @param string $sList  List name to find
	 *
	 * @return string[] Array of attribute codes
	 *
	 * @throws \CoreException
	 */
	public static function GetLoadedListFromClass($aLists, $sClass, $sList = 'default')
	{
		$aFoundList = null;
		$aAttCodes = array();

		// We try to find the list for that class
		if (isset($aLists[$sClass]) && isset($aLists[$sClass][$sList]))
		{
			$aFoundList = $aLists[$sClass][$sList];
		}
		// Else we try to found the default list for that class
		elseif (isset($aLists[$sClass]) && isset($aLists[$sClass]['default']))
		{
			$aFoundList = $aLists[$sClass]['default'];
		}
		// If not found, we try find one from the closest parent class
		else
		{
			foreach (MetaModel::EnumParentClasses($sClass) as $sParentClass)
			{
				// Trying to find the right list
				if (isset($aLists[$sParentClass]) && isset($aLists[$sParentClass][$sList]))
				{
					$aFoundList = $aLists[$sParentClass][$sList];
					break;
				}
				// Or the default list
				elseif (isset($aLists[$sParentClass]) && isset($aLists[$sParentClass]['default']))
				{
					$aFoundList = $aLists[$sParentClass]['default'];
					break;
				}
			}
		}

		// If found, we flatten the list to keep only the attribute codes (not the rank)
		if ($aFoundList !== null)
		{
			foreach ($aFoundList as $aItem)
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
	 * Generate the form data for the $sClass.
	 * Form will look like the "Properties" tab of a $sClass object in the console.
	 *
	 * @param string $sClass
	 * @param bool   $bAddLinksets
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
		foreach ($aPropertiesStruct as $sColId => $aColFieldsets)
		{
			if (substr($sColId, 0, 1) !== '_')
			{
				foreach ($aColFieldsets as $sFieldsetName => $aAttCodes)
				{
					if (substr($sFieldsetName, 0, 1) !== '_')
					{
						$iColCount++;
						break;
					}
				}
			}
		}
		// If no cols, return a default form with all fields one after another
		if ($iColCount === 0)
		{
			return array(
				'id' => 'default',
				'type' => 'zlist',
				'fields' => 'details',
				'layout' => null,
			);
		}
		// Warning, this might not be great when 12 modulo $iColCount is greater than 0.
		$sColCSSClass = 'col-sm-'.floor(12 / $iColCount);

		$sLinksetsHTML = "";
		$sRowHTML = "<div class=\"row\">\n";
		foreach ($aPropertiesStruct as $sColId => $aColFieldsets)
		{
			$sColsHTML = "\t<div class=\"".$sColCSSClass."\">\n";
			foreach ($aColFieldsets as $sFieldsetName => $aAttCodes)
			{
				// Add fieldset, not linkset
				if (substr($sFieldsetName, 0, 1) !== '_')
				{
					$sFieldsetHTML = "\t\t<fieldset>\n";
					$sFieldsetHTML .= "\t\t\t<legend>".htmlentities(Dict::S($sFieldsetName), ENT_QUOTES, 'UTF-8')."</legend>\n";

					foreach ($aAttCodes as $sAttCode)
					{
						$sFieldsetHTML .= "\t\t\t<div class=\"form_field\" data-field-id=\"".$sAttCode."\"></div>\n";
					}

					$sFieldsetHTML .= "\t\t</fieldset>\n";

					// Add to col
					$sColsHTML .= $sFieldsetHTML;
				}
				elseif ($bAddLinksets)
				{
					foreach ($aAttCodes as $sAttCode)
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
