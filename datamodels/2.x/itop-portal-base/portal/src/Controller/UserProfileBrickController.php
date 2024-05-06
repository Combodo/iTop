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

namespace Combodo\iTop\Portal\Controller;

use Combodo\iTop\Portal\Brick\BrickCollection;
use Combodo\iTop\Portal\Brick\UserProfileBrick;
use Combodo\iTop\Portal\Form\PasswordFormManager;
use Combodo\iTop\Portal\Form\PreferencesFormManager;
use Combodo\iTop\Portal\Helper\ObjectFormHandlerHelper;
use Combodo\iTop\Portal\Helper\RequestManipulatorHelper;
use Combodo\iTop\Portal\Routing\UrlGenerator;
use Combodo\iTop\Renderer\Bootstrap\BsFormRenderer;
use Exception;
use FileUploadException;
use IssueLog;
use MetaModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use UserRights;
use utils;

/**
 * Class UserProfileBrickController
 *
 * @package Combodo\iTop\Portal\Controller
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.3.0
 */
class UserProfileBrickController extends BrickController
{
	/**
	 * @param \Combodo\iTop\Portal\Helper\RequestManipulatorHelper $oRequestManipulatorHelper
	 * @param \Combodo\iTop\Portal\Helper\ObjectFormHandlerHelper $ObjectFormHandlerHelper
	 * @param \Combodo\iTop\Portal\Brick\BrickCollection $oBrickCollection
	 * @param \Combodo\iTop\Portal\Routing\UrlGenerator $oUrlGenerator
	 *
	 * @since 3.2.0 N°6933
	 */
	public function __construct(
		protected RequestManipulatorHelper $oRequestManipulatorHelper,
		protected ObjectFormHandlerHelper $ObjectFormHandlerHelper,
		protected BrickCollection $oBrickCollection,
		protected UrlGenerator $oUrlGenerator
	)
	{
	}

	/** @var string ENUM_FORM_TYPE_PICTURE */
	const ENUM_FORM_TYPE_PICTURE = 'picture';

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param                                           $sBrickId
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \ArchivedObjectException
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \CoreException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DisplayAction(Request $oRequest, $sBrickId)
	{
		// If the brick id was not specified, we get the first one registered that is an instance of UserProfileBrick as default
		if ($sBrickId === null)
		{
			/** @var \Combodo\iTop\Portal\Brick\PortalBrick $oTmpBrick */
			foreach ($this->oBrickCollection->GetBricks() as $oTmpBrick)
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
				//throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'UserProfileBrick : Brick could not be loaded as there was no UserProfileBrick loaded in the application.');
			}
		}
		else
		{
			$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);
		}

		$aData = array();

		// Setting form mode regarding the demo mode parameter
		$bDemoMode = MetaModel::GetConfig()->Get('demo_mode');
		$sFormMode = ($bDemoMode) ? ObjectFormHandlerHelper::ENUM_MODE_VIEW : ObjectFormHandlerHelper::ENUM_MODE_EDIT;

		// If this is ajax call, we are just submitting preferences or password forms
		if ($oRequest->isXmlHttpRequest())
		{
			$aCurrentValues = $this->oRequestManipulatorHelper->ReadParam('current_values', array(), FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
			$sFormType = $aCurrentValues['form_type'];
			if ($sFormType === PreferencesFormManager::FORM_TYPE)
			{
				$aData['form'] = $this->HandlePreferencesForm($oRequest, $sFormMode);
			}
			elseif ($sFormType === PasswordFormManager::FORM_TYPE)
			{
				$aData['form'] = $this->HandlePasswordForm($oRequest, $sFormMode);
			}
			elseif ($sFormType === static::ENUM_FORM_TYPE_PICTURE)
			{
				$aData['form'] = $this->HandlePictureForm($oRequest);
			}
			else
			{
				throw new Exception('Unknown form type.');
			}
			$oResponse = new JsonResponse($aData);
		}
		// Else, we are displaying page for first time
		else
		{
			// Retrieving current contact
			/** @var \DBObject $oCurContact */
			$oCurContact = UserRights::GetContactObject();
			$sCurContactClass = get_class($oCurContact);
			$sCurContactId = $oCurContact->GetKey();

			// Preparing forms
			$aData['forms']['contact'] = $this->ObjectFormHandlerHelper->HandleForm($oRequest, $sFormMode, $sCurContactClass, $sCurContactId,
				$oBrick->GetForm());
			$aData['forms']['preferences'] = $this->HandlePreferencesForm($oRequest, $sFormMode);
			// - If user can change password, we display the form
			$aData['forms']['password'] = (UserRights::CanChangePassword()) ? $this->HandlePasswordForm($oRequest, $sFormMode) : null;

			$aData = $aData + array(
					'oBrick' => $oBrick,
					'sFormMode' => $sFormMode,
					'bDemoMode' => $bDemoMode,
				);

			$oResponse = $this->render($oBrick->GetPageTemplatePath(), $aData);
		}

		return $oResponse;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sFormMode
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	public function HandlePreferencesForm(Request $oRequest, $sFormMode)
	{


		$aFormData = array();

		// Handling form
		$sOperation = $this->oRequestManipulatorHelper->ReadParam('operation', null);
		// - Create
		if ($sOperation === null)
		{
			// - Creating renderer
			$oFormRenderer = new BsFormRenderer();
			$oFormRenderer->SetEndpoint($this->oUrlGenerator->generate('p_user_profile_brick'));
			// - Creating manager
			$oFormManager = new PreferencesFormManager();
			$oFormManager->SetRenderer($oFormRenderer)
				->Build();
			// - Checking if we have to make the form read only
			if ($sFormMode === ObjectFormHandlerHelper::ENUM_MODE_VIEW)
			{
				$oFormManager->GetForm()->MakeReadOnly();
			}
		}
		// - Submit
		else
		{
			if ($sOperation === 'submit')
			{
				$sFormManagerClass = $this->oRequestManipulatorHelper->ReadParam('formmanager_class', null, FILTER_UNSAFE_RAW);
				$sFormManagerData = $this->oRequestManipulatorHelper->ReadParam('formmanager_data', null, FILTER_UNSAFE_RAW);
				if ($sFormManagerClass === null || $sFormManagerData === null)
				{
					IssueLog::Error(__METHOD__.' at line '.__LINE__.' : Parameters formmanager_class and formmanager_data must be defined.');
					throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR,
						'Parameters formmanager_class and formmanager_data must be defined.');
				}

				// Rebuilding manager from json
				/** @var \Combodo\iTop\Form\FormManager $oFormManager */
				$oFormManager = $sFormManagerClass::FromJSON($sFormManagerData);
				// Applying modification to object
				$aFormData['validation'] = $oFormManager->OnSubmit(array(
					'currentValues' => $this->oRequestManipulatorHelper->ReadParam('current_values', array(),  FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY),
				));
				// Reloading page only if preferences were changed
				if (($aFormData['validation']['valid'] === true) && !empty($aFormData['validation']['messages']['success']))
				{
					$aFormData['validation']['redirection'] = array(
						'url' => $this->oUrlGenerator->generate('p_user_profile_brick'),
						'timeout_duration' => 1000, //since there are several ajax request, we use a longer timeout in hope that they will all be finished in time. A promise would have been more reliable, but since this change is made in a minor version, this approach is less error prone.
					);
				}
			}
		}
		// Else, submit from another form

		// Preparing field_set data
		$aFieldSetData = array(
			'fields_list' => $oFormManager->GetRenderer()->Render(),
			'fields_impacts' => $oFormManager->GetForm()->GetFieldsImpacts(),
			'form_path' => $oFormManager->GetForm()->GetId(),
		);

		// Preparing form data
		$aFormData['id'] = $oFormManager->GetForm()->GetId();
		$aFormData['formmanager_class'] = $oFormManager->GetClass();
		$aFormData['formmanager_data'] = $oFormManager->ToJSON();
		$aFormData['renderer'] = $oFormManager->GetRenderer();
		$aFormData['fieldset'] = $aFieldSetData;

		return $aFormData;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sFormMode
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	public function HandlePasswordForm(Request $oRequest, $sFormMode)
	{
		$aFormData = array();

		// Handling form
		$sOperation = /** @var \Combodo\iTop\Portal\Helper\RequestManipulatorHelper $oRequestManipulator */
			$this->oRequestManipulatorHelper->ReadParam('operation', null);
		// - Create
		if ($sOperation === null)
		{
			// - Creating renderer
			$oFormRenderer = new BsFormRenderer();
			$oFormRenderer->SetEndpoint($this->oUrlGenerator->generate('p_user_profile_brick'));
			// - Creating manager
			$oFormManager = new PasswordFormManager();
			$oFormManager->SetRenderer($oFormRenderer)
				->Build();
			// - Checking if we have to make the form read only
			if ($sFormMode === ObjectFormHandlerHelper::ENUM_MODE_VIEW)
			{
				$oFormManager->GetForm()->MakeReadOnly();
			}
		}
		// - Submit
		else
		{
			if ($sOperation === 'submit')
			{
				$sFormManagerClass = $this->oRequestManipulatorHelper->ReadParam('formmanager_class', null, FILTER_UNSAFE_RAW);
				$sFormManagerData = $this->oRequestManipulatorHelper->ReadParam('formmanager_data', null, FILTER_UNSAFE_RAW);
				if ($sFormManagerClass === null || $sFormManagerData === null) {
					IssueLog::Error(__METHOD__.' at line '.__LINE__.' : Parameters formmanager_class and formmanager_data must be defined.');
					throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR,
						'Parameters formmanager_class and formmanager_data must be defined.');
				}

				// Rebuilding manager from json
				/** @var \Combodo\iTop\Form\FormManager $oFormManager */
				$oFormManager = $sFormManagerClass::FromJSON($sFormManagerData);
				// Applying modification to object
				$aFormData['validation'] = $oFormManager->OnSubmit(array(
					'currentValues' => $this->oRequestManipulatorHelper->ReadParam('current_values', array(), FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY),
				));
			}
		}
		// Else, submit from another form

		// Preparing field_set data
		$aFieldSetData = array(
			'fields_list' => $oFormManager->GetRenderer()->Render(),
			'fields_impacts' => $oFormManager->GetForm()->GetFieldsImpacts(),
			'form_path' => $oFormManager->GetForm()->GetId(),
		);

		// Preparing form data
		$aFormData['id'] = $oFormManager->GetForm()->GetId();
		$aFormData['formmanager_class'] = $oFormManager->GetClass();
		$aFormData['formmanager_data'] = $oFormManager->ToJSON();
		$aFormData['renderer'] = $oFormManager->GetRenderer();
		$aFormData['fieldset'] = $aFieldSetData;

		return $aFormData;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	public function HandlePictureForm(Request $oRequest)
	{
		$aFormData = array();
		$sPictureAttCode = 'picture';

		// Handling form
		$sOperation = $this->oRequestManipulatorHelper->ReadParam('operation', null);
		// - No operation specified
		if ($sOperation === null)
		{
			IssueLog::Error(__METHOD__.' at line '.__LINE__.' : Operation parameter must be specified.');
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Operation parameter must be specified.');
		}
		// - Submit
		else
		{
			if ($sOperation === 'submit')
			{
				$oRequestFiles = $oRequest->files;
				$oPictureFile = $oRequestFiles->get($sPictureAttCode);
				if ($oPictureFile === null)
				{
					IssueLog::Error(__METHOD__.' at line '.__LINE__.' : Parameter picture must be defined.');
					throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Parameter picture must be defined.');
				}

				try
				{
					// Retrieving image as an ORMDocument
					$oImage = utils::ReadPostedDocument($sPictureAttCode);
					// Retrieving current contact
					/** @var \cmdbAbstractObject $oCurContact */
					$oCurContact = UserRights::GetContactObject();
					// Resizing image
					$oAttDef = MetaModel::GetAttributeDef(get_class($oCurContact), $sPictureAttCode);
					$aSize = utils::GetImageSize($oImage->GetData());
					$oImage = utils::ResizeImageToFit($oImage, $aSize[0], $aSize[1], $oAttDef->Get('storage_max_width'),
						$oAttDef->Get('storage_max_height'));
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

				// TODO: This should be changed when refactoring the ormDocument GetDisplayUrl() and GetDownloadUrl() in iTop 3.0
				$oOrmDoc = $oCurContact->Get($sPictureAttCode);
				$aFormData['picture_url'] = $this->oUrlGenerator->generate('p_object_document_display', [
					'sObjectClass' => get_class($oCurContact),
					'sObjectId' => $oCurContact->GetKey(),
					'sObjectField' => $sPictureAttCode,
					'cache' => 86400,
					's' => $oOrmDoc->GetSignature(),
					]);
				$aFormData['validation'] = array(
					'valid' => true,
					'messages' => array(),
				);
			}
		}

		// Else, submit from another form

		return $aFormData;
	}

}
