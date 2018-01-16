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

namespace Combodo\iTop\Portal\Controller;

use Exception;
use IssueLog;
use utils;
use MetaModel;
use UserRights;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use Combodo\iTop\Portal\Brick\UserProfileBrick;
use Combodo\iTop\Portal\Controller\ObjectController;
use Combodo\iTop\Portal\Form\PreferencesFormManager;
use Combodo\iTop\Portal\Form\PasswordFormManager;
use Combodo\iTop\Renderer\Bootstrap\BsFormRenderer;

class UserProfileBrickController extends BrickController
{
	const ENUM_FORM_TYPE_PICTURE = 'picture';

	public function DisplayAction(Request $oRequest, Application $oApp, $sBrickId)
	{
		// If the brick id was not specified, we get the first one registered that is an instance of UserProfileBrick as default
		if ($sBrickId === null)
		{
			foreach ($oApp['combodo.portal.instance.conf']['bricks'] as $oTmpBrick)
			{
				if ($oTmpBrick instanceof UserProfileBrick)
				{
					$oBrick = $oTmpBrick;
				}
			}

			// We make sure a UserProfileBrick was found
			if (!isset($oBrick) || $oBrick === null)
			{
				$oBrick = new UserProfileBrick();
				//$oApp->abort(500, 'UserProfileBrick : Brick could not be loaded as there was no UserProfileBrick loaded in the application.');
			}
		}
		else
		{
			$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);
		}

		$aData = array();
		
		// Setting form mode regarding the demo mode parameter
		$bDemoMode = MetaModel::GetConfig()->Get('demo_mode');
		$sFormMode = ($bDemoMode) ? ObjectController::ENUM_MODE_VIEW : ObjectController::ENUM_MODE_EDIT;

		// If this is ajax call, we are just submiting preferences or password forms
		if ($oRequest->isXmlHttpRequest())
		{
			$aCurrentValues = $oRequest->request->get('current_values');
			$sFormType = $aCurrentValues['form_type'];
			if ($sFormType === PreferencesFormManager::FORM_TYPE)
			{
				$aData['form'] = $this->HandlePreferencesForm($oRequest, $oApp, $sFormMode);
			}
			elseif ($sFormType === PasswordFormManager::FORM_TYPE)
			{
				$aData['form'] = $this->HandlePasswordForm($oRequest, $oApp);
			}
			elseif ($sFormType === static::ENUM_FORM_TYPE_PICTURE)
			{
				$aData['form'] = $this->HandlePictureForm($oRequest, $oApp, $sFormMode);
			}
			else
			{
				throw new Exception('Unknown form type.');
			}
			$oResponse = $oApp->json($aData);
		}
		// Else, we are displaying page for first time
		else
		{
			// Retrieving current contact
			$oCurContact = UserRights::GetContactObject();
			$sCurContactClass = get_class($oCurContact);
			$sCurContactId = $oCurContact->GetKey();

			// Preparing forms
			$aData['forms']['contact'] = ObjectController::HandleForm($oRequest, $oApp, $sFormMode, $sCurContactClass, $sCurContactId, $oBrick->GetForm());
			$aData['forms']['preferences'] = $this->HandlePreferencesForm($oRequest, $oApp, $sFormMode);
			// - If user can change password, we display the form
			$aData['forms']['password'] = (UserRights::CanChangePassword()) ? $this->HandlePasswordForm($oRequest, $oApp) : null;

			$aData = $aData + array(
				'oBrick' => $oBrick,
				'sFormMode' => $sFormMode,
				'bDemoMode' => $bDemoMode
			);

			$oResponse = $oApp['twig']->render($oBrick->GetPageTemplatePath(), $aData);
		}

		return $oResponse;
	}

	public function HandlePreferencesForm(Request $oRequest, Application $oApp, $sFormMode)
	{
		$aFormData = array();
		$oRequestParams = $oRequest->request;

		// Handling form
		$sOperation = $oRequestParams->get('operation');
		// - Create
		if ($sOperation === null)
		{
			// - Creating renderer
			$oFormRenderer = new BsFormRenderer();
			$oFormRenderer->SetEndpoint($_SERVER['REQUEST_URI']);
			// - Creating manager
			$oFormManager = new PreferencesFormManager();
			$oFormManager->SetRenderer($oFormRenderer)
				->Build();
			// - Checking if we have to make the form read only
			if ($sFormMode === ObjectController::ENUM_MODE_VIEW)
			{
				$oFormManager->GetForm()->MakeReadOnly();
			}
		}
		// - Submit
		else if ($sOperation === 'submit')
		{
			$sFormManagerClass = $oRequestParams->get('formmanager_class');
			$sFormManagerData = $oRequestParams->get('formmanager_data');
			if ($sFormManagerClass === null || $sFormManagerData === null)
			{
				IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Parameters formmanager_class and formamanager_data must be defined.');
				$oApp->abort(500, 'Parameters formmanager_class and formmanager_data must be defined.');
			}

			// Rebuilding manager from json
			$oFormManager = $sFormManagerClass::FromJSON($sFormManagerData);
			// Applying modification to object
			$aFormData['validation'] = $oFormManager->OnSubmit(array('currentValues' => $oRequestParams->get('current_values')));
			// Reloading page only if preferences were changed
			if (($aFormData['validation']['valid'] === true) && !empty($aFormData['validation']['messages']['success']))
			{
				$aFormData['validation']['redirection'] = array(
					'url' => $oApp['url_generator']->generate('p_user_profile_brick'),
				);
			}
		}
		else
		{
			// Else, submit from another form
		}

		// Preparing field_set data
		$aFieldSetData = array(
			'fields_list' => $oFormManager->GetRenderer()->Render(),
			'fields_impacts' => $oFormManager->GetForm()->GetFieldsImpacts(),
			'form_path' => $oFormManager->GetForm()->GetId()
		);

		// Preparing form data
		$aFormData['id'] = $oFormManager->GetForm()->GetId();
		$aFormData['formmanager_class'] = $oFormManager->GetClass();
		$aFormData['formmanager_data'] = $oFormManager->ToJSON();
		$aFormData['renderer'] = $oFormManager->GetRenderer();
		$aFormData['fieldset'] = $aFieldSetData;

		return $aFormData;
	}

	public function HandlePasswordForm(Request $oRequest, Application $oApp)
	{
		$aFormData = array();
		$oRequestParams = $oRequest->request;

		// Handling form
		$sOperation = $oRequestParams->get('operation');
		// - Create
		if ($sOperation === null)
		{
			// - Creating renderer
			$oFormRenderer = new BsFormRenderer();
			$oFormRenderer->SetEndpoint($_SERVER['REQUEST_URI']);
			// - Creating manager
			$oFormManager = new PasswordFormManager();
			$oFormManager->SetRenderer($oFormRenderer)
				->Build();
		}
		// - Submit
		else if ($sOperation === 'submit')
		{
			$sFormManagerClass = $oRequestParams->get('formmanager_class');
			$sFormManagerData = $oRequestParams->get('formmanager_data');
			if ($sFormManagerClass === null || $sFormManagerData === null)
			{
				IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Parameters formmanager_class and formamanager_data must be defined.');
				$oApp->abort(500, 'Parameters formmanager_class and formmanager_data must be defined.');
			}

			// Rebuilding manager from json
			$oFormManager = $sFormManagerClass::FromJSON($sFormManagerData);
			// Applying modification to object
			$aFormData['validation'] = $oFormManager->OnSubmit(array('currentValues' => $oRequestParams->get('current_values')));
		}
		else
		{
			// Else, submit from another form
		}

		// Preparing field_set data
		$aFieldSetData = array(
			'fields_list' => $oFormManager->GetRenderer()->Render(),
			'fields_impacts' => $oFormManager->GetForm()->GetFieldsImpacts(),
			'form_path' => $oFormManager->GetForm()->GetId()
		);

		// Preparing form data
		$aFormData['id'] = $oFormManager->GetForm()->GetId();
		$aFormData['formmanager_class'] = $oFormManager->GetClass();
		$aFormData['formmanager_data'] = $oFormManager->ToJSON();
		$aFormData['renderer'] = $oFormManager->GetRenderer();
		$aFormData['fieldset'] = $aFieldSetData;

		return $aFormData;
	}

	public function HandlePictureForm(Request $oRequest, Application $oApp, $sFormMode)
	{
		$aFormData = array();
		$oRequestParams = $oRequest->request;
		$sPictureAttCode = 'picture';

		// Handling form
		$sOperation = $oRequestParams->get('operation');
		// - No operation specified
		if ($sOperation === null)
		{
			IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Operation parameter must be specified.');
			$oApp->abort(500, 'Operation parameter must be specified.');
		}
		// - Submit
		else if ($sOperation === 'submit')
		{
			$oRequestFiles = $oRequest->files;
			$oPictureFile = $oRequestFiles->get($sPictureAttCode);
			if ($oPictureFile === null)
			{
				IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Parameter picture must be defined.');
				$oApp->abort(500, 'Parameter picture must be defined.');
			}
			
			try
			{
				// Retrieving image as an ORMDocument
				$oImage = utils::ReadPostedDocument($sPictureAttCode);
				// Retrieving current contact
				$oCurContact = UserRights::GetContactObject();
				// Resizing image
				$oAttDef = MetaModel::GetAttributeDef(get_class($oCurContact), $sPictureAttCode);
				$aSize = utils::GetImageSize($oImage->GetData());
				$oImage = utils::ResizeImageToFit($oImage, $aSize[0], $aSize[1], $oAttDef->Get('storage_max_width'), $oAttDef->Get('storage_max_height'));
				// Setting it to the contact
				$oCurContact->Set($sPictureAttCode, $oImage);
				// Forcing allowed writing on the object if necessary.
				$oCurContact->AllowWrite(true);
				$oCurContact->DBUpdate();
			}
			catch (FileUploadException $e)
			{
				$aFormData['error'] = $e->GetMessage();
			}

			$aFormData['picture_url'] = $oImage->GetDownloadURL(get_class($oCurContact), $oCurContact->GetKey(), $sPictureAttCode);
			$aFormData['validation'] = array(
				'valid' => true,
				'messages' => array()
			);
		}
		else
		{
			// Else, submit from another form
		}

		return $aFormData;
	}

}

?>