<?php

/**
 * Copyright (C) 2013-2023 Combodo SARL
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

use AttachmentPlugIn;
use AttributeEnum;
use AttributeFinalClass;
use AttributeFriendlyName;
use AttributeImage;
use BinaryExpression;
use Combodo\iTop\Form\Field\DateTimeField;
use Combodo\iTop\Portal\Brick\BrickCollection;
use Combodo\iTop\Portal\Brick\CreateBrick;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use Combodo\iTop\Portal\Helper\ContextManipulatorHelper;
use Combodo\iTop\Portal\Helper\NavigationRuleHelper;
use Combodo\iTop\Portal\Helper\ObjectFormHandlerHelper;
use Combodo\iTop\Portal\Helper\RequestManipulatorHelper;
use Combodo\iTop\Portal\Helper\ScopeValidatorHelper;
use Combodo\iTop\Portal\Helper\SecurityHelper;
use Combodo\iTop\Portal\Routing\UrlGenerator;
use Combodo\iTop\Renderer\Bootstrap\FieldRenderer\BsLinkedSetFieldRenderer;
use DBObject;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Dict;
use Exception;
use FalseExpression;
use FieldExpression;
use FileUploadException;
use IssueLog;
use JSButtonItem;
use ListExpression;
use MetaModel;
use ScalarExpression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use UserRights;
use utils;
use VariableExpression;

/**
 * Class ObjectController
 *
 * Controller to handle basic view / edit / create of cmdbAbstractObjectClass ManageBrickController
 *
 * @package Combodo\iTop\Portal\Controller
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.3.0
 */
class ObjectController extends BrickController
{
	const DEFAULT_PAGE_NUMBER = 1;
	const DEFAULT_LIST_LENGTH = 10;

	/**
	 * @param \Combodo\iTop\Portal\Helper\SecurityHelper $oSecurityHelper
	 * @param \Combodo\iTop\Portal\Helper\ScopeValidatorHelper $oScopeValidatorHelper
	 * @param \Combodo\iTop\Portal\Helper\RequestManipulatorHelper $oRequestManipulatorHelper
	 * @param \Combodo\iTop\Portal\Routing\UrlGenerator $oUrlGenerator
	 * @param \Combodo\iTop\Portal\Brick\BrickCollection $oBrickCollection
	 * @param \Combodo\iTop\Portal\Helper\ObjectFormHandlerHelper $oObjectFormHandlerHelper
	 * @param \Combodo\iTop\Portal\Helper\NavigationRuleHelper $oNavigationRuleHelper
	 *
	 * @since 3.2.0 N°6933
	 */
	public function __construct(
		protected SecurityHelper $oSecurityHelper,
		protected ScopeValidatorHelper $oScopeValidatorHelper,
		protected RequestManipulatorHelper $oRequestManipulatorHelper,
		protected UrlGenerator $oUrlGenerator,
		protected BrickCollection $oBrickCollection,
		protected ObjectFormHandlerHelper $oObjectFormHandlerHelper,
		protected NavigationRuleHelper $oNavigationRuleHelper,
		protected ContextManipulatorHelper $oContextManipulatorHelper

	)
	{
	}

	/**
	 * Displays an cmdbAbstractObject (from its ID) if the connected user is allowed to.
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string $sObjectClass (Class must be an instance of cmdbAbstractObject)
	 * @param string $sObjectId
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \ArchivedObjectException
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function ViewAction(Request $oRequest, $sObjectClass, $sObjectId)
	{
		// Checking parameters
		if ($sObjectClass === '' || $sObjectId === '') {
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : sObjectClass and sObjectId expected, "'.$sObjectClass.'" and "'.$sObjectId.'" given.');
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
		}

		// Checking security layers
		if (!$this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sObjectClass, $sObjectId)) {
			IssueLog::Warning(__METHOD__.' at line '.__LINE__.' : User #'.UserRights::GetUserId().' not allowed to read '.$sObjectClass.'::'.$sObjectId.' object.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Retrieving object
		$oObject = MetaModel::GetObject($sObjectClass, $sObjectId, false /* MustBeFound */,
			$this->oScopeValidatorHelper->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass));
		if ($oObject === null) {
			// We should never be there as the secuirty helper makes sure that the object exists, but just in case.
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : Could not load object '.$sObjectClass.'::'.$sObjectId.'.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		return $this->PrepareViewObjectResponse($oRequest, $oObject);
	}

	/**
	 * Displays an cmdbAbstractObject (if the connected user is allowed to) from a specific attribute. If several or none objects are found with the attribute value, an exception is thrown.
	 * 
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string $sObjectClass (Class must be an instance of cmdbAbstractObject)
	 * @param string $sObjectAttCode
	 * @param string $sObjectAttValue
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response|null
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 *
	 * @since 2.7.7 method creation
	 */
	public function ViewFromAttributeAction(Request $oRequest, $sObjectClass, $sObjectAttCode, $sObjectAttValue)
	{
		// Checking parameters
		if ($sObjectClass === '' || $sObjectAttCode === '' || $sObjectAttValue === '') {
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : sObjectClass and sObjectAttCode/sObjectAttValue expected, "'
				.$sObjectClass.'" and "'.$sObjectAttCode.' / '.$sObjectAttValue.'" given.');
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Dict::Format('UI:Error:3ParametersMissing', 'class', 'attcode', 'attvalue'));
		}

		$oObject = MetaModel::GetObjectByColumn($sObjectClass, $sObjectAttCode, $sObjectAttValue, false,
			$this->oScopeValidatorHelper->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass));
		if ($oObject === null) {
			// null if object not found or multiple matches
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : Could not load object '.$sObjectClass.'" and "'.$sObjectAttCode.' / '.$sObjectAttValue.'.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Checking security layers
		$sObjectId = $oObject->GetKey();
		if (!$this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sObjectClass, $sObjectId)) {
			IssueLog::Warning(__METHOD__.' at line '.__LINE__.' : User #'.UserRights::GetUserId().' not allowed to read '.$sObjectClass.'::'.$sObjectId.' object.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		return $this->PrepareViewObjectResponse($oRequest, $oObject);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param \DBObject $oObject
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response|null
	 * @throws \ArchivedObjectException
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 *
	 * @since 2.7.7 method creation (refactor for new `p_object_view_from_attribute` route)
	 */
	protected function PrepareViewObjectResponse(Request $oRequest, DBObject $oObject)
	{

		$sOperation = $this->oRequestManipulatorHelper->ReadParam('operation', '');
		$sObjectClass = get_class($oObject);
		$sObjectId = $oObject->GetKey();

		$oObject->FireEvent(EVENT_DISPLAY_OBJECT_DETAILS);

		$aData = array('sMode' => 'view');
		$aData['form'] = $this->oObjectFormHandlerHelper->HandleForm($oRequest, $aData['sMode'], $sObjectClass, $sObjectId);
		$aData['form']['title'] = Dict::Format('Brick:Portal:Object:Form:View:Title', MetaModel::GetName($sObjectClass),
			$oObject->GetName());

		// Add an edit button if user is allowed
		if ($this->oSecurityHelper->IsActionAllowed(UR_ACTION_MODIFY, $sObjectClass, $sObjectId)) {
			$sModifyUrl = $this->oUrlGenerator->generate('p_object_edit', array('sObjectClass' => $sObjectClass, 'sObjectId' => $sObjectId));
			$oModifyButton = new JSButtonItem(
				'modify_object',
				Dict::S('UI:Menu:Modify'),
				'CombodoModal.OpenUrlInModal("'.$sModifyUrl.'", true);'
			);
			// Putting this one first
			$aData['form']['buttons']['actions'][] = $oModifyButton->GetMenuItem() + array('js_files' => $oModifyButton->GetLinkedScripts());
		}

		// Preparing response
		if ($oRequest->isXmlHttpRequest()) {
			// We have to check whether the 'operation' parameter is defined or not in order to know if the form is required via ajax (to be displayed as a modal dialog) or if it's a lifecycle call from a existing form.
			if (empty($sOperation)) {
				$oResponse = $this->render('itop-portal-base/portal/templates/bricks/object/modal.html.twig', $aData);
			} else {
				$oResponse = new JsonResponse($aData);
			}
		} else {
			// Adding brick if it was passed
			$sBrickId = $this->oRequestManipulatorHelper->ReadParam('sBrickId', '');
			if (!empty($sBrickId)) {
				$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);
				if ($oBrick !== null) {
					$aData['oBrick'] = $oBrick;
				}
			}
			$aData['sPageTitle'] = $aData['form']['title'];
			$oResponse = $this->render('itop-portal-base/portal/templates/bricks/object/layout.html.twig', $aData);
		}

		return $oResponse;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param                                           $sObjectClass
	 * @param                                           $sObjectId
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \ArchivedObjectException
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function EditAction(Request $oRequest, $sObjectClass, $sObjectId)
	{
		// Checking parameters
		if ($sObjectClass === '' || $sObjectId === '')
		{
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : sObjectClass and sObjectId expected, "'.$sObjectClass.'" and "'.$sObjectId.'" given.');
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
		}

		// Checking security layers
		// Warning : This is a dirty quick fix to allow editing its own contact information
		$bAllowWrite = ($sObjectClass === 'Person' && $sObjectId == UserRights::GetContactId());
		if (!$this->oSecurityHelper->IsActionAllowed(UR_ACTION_MODIFY, $sObjectClass, $sObjectId) && !$bAllowWrite)
		{
			IssueLog::Warning(__METHOD__.' at line '.__LINE__.' : User #'.UserRights::GetUserId().' not allowed to modify '.$sObjectClass.'::'.$sObjectId.' object.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Retrieving object
		$oObject = MetaModel::GetObject($sObjectClass, $sObjectId, false /* MustBeFound */,
			$this->oScopeValidatorHelper->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass));
		if ($oObject === null)
		{
			// We should never be there as the secuirty helper makes sure that the object exists, but just in case.
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : Could not load object '.$sObjectClass.'::'.$sObjectId.'.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		$sOperation = $this->oRequestManipulatorHelper->ReadParam('operation', '');

		$aData = array('sMode' => 'edit');
		$aData['form'] = $this->oObjectFormHandlerHelper->HandleForm($oRequest, $aData['sMode'], $sObjectClass, $sObjectId);
		$aData['form']['title'] = Dict::Format('Brick:Portal:Object:Form:Edit:Title', MetaModel::GetName($sObjectClass),
			$aData['form']['object_name']);

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			// We have to check whether the 'operation' parameter is defined or not in order to know if the form is required via ajax (to be displayed as a modal dialog) or if it's a lifecycle call from a existing form.
			if (empty($sOperation))
			{
				$oResponse = $this->render('itop-portal-base/portal/templates/bricks/object/modal.html.twig', $aData);
			}
			else
			{
				$oResponse = new JsonResponse($aData);
			}
		}
		else
		{
			// Adding brick if it was passed
			$sBrickId = $this->oRequestManipulatorHelper->ReadParam('sBrickId', '');
			if (!empty($sBrickId))
			{
				$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);
				if ($oBrick !== null)
				{
					$aData['oBrick'] = $oBrick;
				}
			}
			$aData['sPageTitle'] = $aData['form']['title'];
			$oResponse = $this->render('itop-portal-base/portal/templates/bricks/object/layout.html.twig', $aData);
		}

		return $oResponse;
	}

	/**
	 * Creates an cmdbAbstractObject of the $sObjectClass
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sObjectClass
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \ArchivedObjectException
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \OQLException
	 */
	public function CreateAction(Request $oRequest, $sObjectClass)
	{
		$oResponse = null;
		// Checking if the target object class is abstract or not
		// - If is not abstract, we redirect to object creation form
		if (!MetaModel::IsAbstract($sObjectClass))
		{
			$oResponse = $this->DisplayCreationForm($oRequest, $sObjectClass);
		}
		// - Else, we list the leaf classes as an intermediate step
		else
		{
			$oResponse = $this->DisplayLeafClassesForm($sObjectClass);
		}

		return $oResponse;
	}

	/**
	 * Creates an cmdbAbstractObject of a class determined by the method encoded in $sEncodedMethodName.
	 * This method use an origin DBObject in order to determine the created cmdbAbstractObject.
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sObjectClass       Class of the origin object
	 * @param string                                    $sObjectId          ID of the origin object
	 * @param string                                    $sEncodedMethodName Base64 encoded factory method name
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function CreateFromFactoryAction(Request $oRequest, $sObjectClass, $sObjectId, $sEncodedMethodName)
	{
		$sMethodName = base64_decode($sEncodedMethodName);

		// Checking that the factory method is valid
		if (!is_callable($sMethodName))
		{
			IssueLog::Error(__METHOD__.' at line '.__LINE__.' : Invalid factory method "'.$sMethodName.'" used when creating an object.');
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR,
				'Invalid factory method "'.$sMethodName.'" used when creating an object');
		}

		// Retrieving origin object
		// Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
		$oOriginObject = MetaModel::GetObject($sObjectClass, $sObjectId, true, true);

		// Retrieving target object (We check if the method is a simple function or if it's part of a class in which case only static function are supported)
		if (!strpos($sMethodName, '::'))
		{
			$oTargetObject = $sMethodName($oOriginObject);
		}
		else
		{
			$aMethodNameParts = explode('::', $sMethodName);
			$sMethodClass = $aMethodNameParts[0];
			$sMethodName = $aMethodNameParts[1];
			$oTargetObject = $sMethodClass::$sMethodName($oOriginObject);
		}

		// Preparing redirection
		// - Route
		$aRouteParams = array(
			'sObjectClass' => get_class($oTargetObject),
		);

		return $this->ForwardToRoute('p_object_create', $aRouteParams, $oRequest->query->all());
	}

	/**
	 * Applies a stimulus $sStimulus on an cmdbAbstractObject
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sObjectClass
	 * @param string                                    $sObjectId
	 * @param string                                    $sStimulusCode
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function ApplyStimulusAction(Request $oRequest, $sObjectClass, $sObjectId, $sStimulusCode)
	{
		/** @var array $aCombodoPortalInstanceConf */
		$aCombodoPortalInstanceConf = $this->getParameter('combodo.portal.instance.conf');

		// Checking parameters
		if ($sObjectClass === '' || $sObjectId === '' || $sStimulusCode === '')
		{
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : sObjectClass, sObjectId and $sStimulusCode expected, "'.$sObjectClass.'", "'.$sObjectId.'" and "'.$sStimulusCode.'" given.');
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR,
				Dict::Format('UI:Error:3ParametersMissing', 'class', 'id', 'stimulus'));
		}

		// Checking security layers
		if (!$this->oSecurityHelper->IsActionAllowed(UR_ACTION_MODIFY, $sObjectClass, $sObjectId))
		{
			IssueLog::Warning(__METHOD__.' at line '.__LINE__.' : User #'.UserRights::GetUserId().' not allowed to modify '.$sObjectClass.'::'.$sObjectId.' object.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}
		if (!$this->oSecurityHelper->IsStimulusAllowed($sStimulusCode, $sObjectClass))
		{
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Retrieving object
		$oObject = MetaModel::GetObject($sObjectClass, $sObjectId, false /* MustBeFound */, 	$this->oScopeValidatorHelper->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass));
		if ($oObject === null)
		{
			// We should never be there as the secuirty helper makes sure that the object exists, but just in case.
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : Could not load object '.$sObjectClass.'::'.$sObjectId.'.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Retrieving request parameters
		$sOperation = $this->oRequestManipulatorHelper->ReadParam('operation', '');

		// Retrieving form properties
		$aStimuliForms = ApplicationHelper::GetLoadedFormFromClass($aCombodoPortalInstanceConf['forms'], $sObjectClass, 'apply_stimulus');
		if (array_key_exists($sStimulusCode, $aStimuliForms))
		{
			$aFormProperties = $aStimuliForms[$sStimulusCode];
		}
		// Or preparing a default form for the stimulus application
		else
		{
			// Preparing default form
			$aFormProperties = array(
				'id' => 'apply-stimulus',
				'type' => 'custom_list',
				'fields' => array(),
				'layout' => null,
			);
		}

		// Adding stimulus code to form
		$aFormProperties['stimulus_code'] = $sStimulusCode;

		// Adding target_state to current_values
		$oRequest->request->set('apply_stimulus', array('code' => $sStimulusCode));

		$aData = array('sMode' => 'apply_stimulus');
		$aData['form'] = $this->oObjectFormHandlerHelper->HandleForm($oRequest, $aData['sMode'], $sObjectClass, $sObjectId, $aFormProperties);
		$aData['form']['title'] = Dict::Format('Brick:Portal:Object:Form:Stimulus:Title');

		// TODO : This is a ugly patch to avoid showing a modal with a readonly form to the user as it would prevent user from finishing the transition.
		// Instead, we apply the stimulus directly here and then go to the edited object.
		if (empty($sOperation))
		{
			if (isset($aData['form']['editable_fields_count']) && $aData['form']['editable_fields_count'] === 0)
			{
				$sOperation = 'redirect';

				$oSubRequest = $oRequest;
				$oSubRequest->request->set('operation', 'submit');
				$oSubRequest->request->set('stimulus_code', '');
				$oSubRequest->request->set('formmanager_class', $aData['form']['formmanager_class']);
				$oSubRequest->request->set('formmanager_data', json_encode($aData['form']['formmanager_data']));

				$aData = array('sMode' => 'apply_stimulus');
				$aData['form'] = $this->oObjectFormHandlerHelper->HandleForm($oSubRequest, $aData['sMode'], $sObjectClass, $sObjectId,
					$aFormProperties);

				// Reload the object to make sure we have it in a clean state
				$oObject->Reload(true);
				$aNavigationRules = $this->oNavigationRuleHelper->PrepareRulesForForm($aFormProperties, $oObject, true);

				// Redefining the array to be as simple as possible :
				$aData = array(
					'redirection' =>
						array(
							'url' => $aNavigationRules['submit']['url'],
						),
				);
			}
		}

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			// We have to check whether the 'operation' parameter is defined or not in order to know if the form is required via ajax (to be displayed as a modal dialog) or if it's a lifecycle call from a existing form.
			if (empty($sOperation))
			{
				$oResponse = $this->render('itop-portal-base/portal/templates/bricks/object/modal.html.twig', $aData);
			}
			elseif ($sOperation === 'redirect')
			{
				$oResponse = $this->render('itop-portal-base/portal/templates/modal/mode_loader.html.twig', $aData);
			}
			else
			{
				$oResponse = new JsonResponse($aData);
			}
		}
		else
		{
			$oResponse = $this->render('itop-portal-base/portal/templates/bricks/object/layout.html.twig', $aData);
		}

		return $oResponse;
	}

	/**
	 * Handles the autocomplete search
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sTargetAttCode   Attribute code of the host object pointing to the Object class to
	 *                                                                    search
	 * @param string                                    $sHostObjectClass Class name of the host object
	 * @param string                                    $sHostObjectId    Id of the host object
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function SearchAutocompleteAction(Request $oRequest, $sTargetAttCode, $sHostObjectClass, $sHostObjectId = null)
	{

		$aData = array(
			'results' => array(
				'count' => 0,
				'items' => array(),
			),
		);

		// Parsing parameters from request payload
		parse_str($oRequest->getContent(), $aRequestContent);

		// Checking parameters
		if (!isset($aRequestContent['sQuery']))
		{
			IssueLog::Error(__METHOD__.' at line '.__LINE__.' : Parameter sQuery missing.');
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Dict::Format('UI:Error:ParameterMissing', 'sQuery'));
		}

		// Retrieving parameters
		$sQuery = $aRequestContent['sQuery'];
		$sFieldId = $aRequestContent['sFieldId'];

		// Checking security layers
		if (!$this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sHostObjectClass, $sHostObjectId))
		{
			IssueLog::Warning(__METHOD__.' at line '.__LINE__.' : Could not load object '.$sHostObjectClass.'::'.$sHostObjectId.'.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Retrieving host object for future DBSearch parameters
		if ($sHostObjectId !== null)
		{
			// Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
			$oHostObject = MetaModel::GetObject($sHostObjectClass, $sHostObjectId, true, true);
		}
		else
		{
			$oHostObject = MetaModel::NewObject($sHostObjectClass);
			// Retrieving action rules
			//
			// Note : The action rules must be a base64-encoded JSON object, this is just so users are tempted to changes values.
			// But it would not be a security issue as it only presets values in the form.
			$sActionRulesToken = $this->oRequestManipulatorHelper->ReadParam('ar_token', '');
			$aActionRules = (!empty($sActionRulesToken)) ? $this->oContextManipulatorHelper->DecodeRulesToken($sActionRulesToken) : array();
			// Preparing object
			$this->oContextManipulatorHelper->PrepareObject($aActionRules, $oHostObject);
		}

		// Updating host object with form data / values
		$sFormManagerClass = $aRequestContent['formmanager_class'];
		$sFormManagerData = $aRequestContent['formmanager_data'];
		if (!empty($sFormManagerClass) && !empty($sFormManagerData)) {
			/** @var \Combodo\iTop\Portal\Form\ObjectFormManager $oFormManager */
			$oFormManager = $sFormManagerClass::FromJSON($sFormManagerData);
			$oFormManager->SetObjectFormHandlerHelper($this->oObjectFormHandlerHelper);
			$oFormManager->SetObject($oHostObject);

			// Applying action rules if present
			if (($oFormManager->GetActionRulesToken() !== null) && ($oFormManager->GetActionRulesToken() !== '')) {
				$aActionRules = ContextManipulatorHelper::DecodeRulesToken($oFormManager->GetActionRulesToken());
				$oObj = $oFormManager->GetObject();
				$this->oContextManipulatorHelper->PrepareObject($aActionRules, $oObj);
				$oFormManager->SetObject($oObj);
			}

			// Updating host object
			$oFormManager->OnUpdate(array('currentValues' => $aRequestContent['current_values']));
			$oHostObject = $oFormManager->GetObject();
		}

		// Building search query
		// - Retrieving target object class from attcode
		$oTargetAttDef = MetaModel::GetAttributeDef($sHostObjectClass, $sTargetAttCode);
		if ($oTargetAttDef->GetEditClass() === 'CustomFields')
		{
			$oRequestTemplate = $oHostObject->Get($sTargetAttCode);
			/** @var \DBSearch $oTemplateFieldSearch */
			$oTemplateFieldSearch = $oRequestTemplate->GetForm()->GetField('user_data')->GetForm()->GetField($sFieldId)->GetSearch();
			$sTargetObjectClass = $oTemplateFieldSearch->GetClass();
		}
		elseif ($oTargetAttDef->IsLinkSet())
		{
			throw new Exception('Search autocomplete cannot apply on AttributeLinkedSet objects, '.get_class($oTargetAttDef).' ('.$sHostObjectClass.'->'.$sTargetAttCode.') given.');
		}
		else
		{
			$sTargetObjectClass = $oTargetAttDef->GetTargetClass();
		}
		// - Base query from meta model
		if ($oTargetAttDef->GetEditClass() === 'CustomFields')
		{
			$oSearch = $oTemplateFieldSearch;
		}
		else
		{
			$oSearch = DBSearch::FromOQL($oTargetAttDef->GetValuesDef()->GetFilterExpression());
		}
		// - Adding query condition
		$oSearch->AddConditionExpression(new BinaryExpression(new FieldExpression('friendlyname', $oSearch->GetClassAlias()), 'LIKE',
			new VariableExpression('ac_query')));
		// - Intersecting with scope constraints
		// Note : This do NOT apply to custom fields as the portal administrator is not supposed to know which objects will be put in the templates.
		// It is the responsibility of the template designer to write the right query so the user see only what he should.
		if ($oTargetAttDef->GetEditClass() !== 'CustomFields')
		{
			$oScopeSearch = $this->oScopeValidatorHelper->GetScopeFilterForProfiles(UserRights::ListProfiles(), $sTargetObjectClass, UR_ACTION_READ);
			$oSearch = $oSearch->Intersect($oScopeSearch);
			// - Allowing all data if necessary
			if ($oScopeSearch->IsAllDataAllowed())
			{
				$oSearch->AllowAllData();
			}
		}

		// Retrieving results
		// - Preparing object set
		$oSet = new DBObjectSet($oSearch, array(), array('this' => $oHostObject, 'ac_query' => '%'.$sQuery.'%'));
		$oSet->OptimizeColumnLoad(array($oSearch->GetClassAlias() => array('friendlyname')));
		// Note : This limit is also used in the field renderer by typeahead to determine how many suggestions to display
		$oSet->SetLimit(MetaModel::GetConfig()->Get('max_autocomplete_results'));

		// - Retrieving objects
		while ($oItem = $oSet->Fetch())
		{
			$aData['results']['items'][] = array(
				'id' => $oItem->GetKey(),
				'name' => html_entity_decode($oItem->GetName(), ENT_QUOTES, 'UTF-8'),
			);
			$aData['results']['count']++;
		}

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			$oResponse = new JsonResponse($aData);
		}
		else
		{
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		return $oResponse;
	}

	/**
	 * Handles the regular (table) search from an attribute
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sTargetAttCode   Attribute code of the host object pointing to the Object class to
	 *                                                                    search
	 * @param string                                    $sHostObjectClass Class name of the host object
	 * @param string                                    $sHostObjectId    Id of the host object
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function SearchFromAttributeAction(Request $oRequest, $sTargetAttCode, $sHostObjectClass, $sHostObjectId = null)
	{
		/** @var array $aCombodoPortalInstanceConf */
		$aCombodoPortalInstanceConf = $this->getParameter('combodo.portal.instance.conf');

		$aData = array(
			'sMode'             => 'search_regular',
			'sTargetAttCode'    => $sTargetAttCode,
			'sHostObjectClass'  => $sHostObjectClass,
			'sHostObjectId'     => $sHostObjectId,
			'sActionRulesToken' => $this->oRequestManipulatorHelper->ReadParam('ar_token', ''),
		);

		// Checking security layers
		if (!$this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sHostObjectClass, $sHostObjectId))
		{
			IssueLog::Warning(__METHOD__.' at line '.__LINE__.' : User #'.UserRights::GetUserId().' not allowed to read '.$sHostObjectClass.'::'.$sHostObjectId.' object.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Retrieving host object for future DBSearch parameters
		if ($sHostObjectId !== null)
		{
			// Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
			$oHostObject = MetaModel::GetObject($sHostObjectClass, $sHostObjectId, true, true);
		}
		else
		{
			$oHostObject = MetaModel::NewObject($sHostObjectClass);
			// Retrieving action rules
			//
			// Note : The action rules must be a base64-encoded JSON object, this is just so users are tempted to changes values.
			// But it would not be a security issue as it only presets values in the form.
			$aActionRules = !empty($aData['sActionRulesToken']) ? ContextManipulatorHelper::DecodeRulesToken($aData['sActionRulesToken']) : array();
			// Preparing object
			$this->oContextManipulatorHelper->PrepareObject($aActionRules, $oHostObject);
		}

		// Updating host object with form data / values
		$sFormManagerClass = $this->oRequestManipulatorHelper->ReadParam('formmanager_class', '', FILTER_UNSAFE_RAW);
		$sFormManagerData = $this->oRequestManipulatorHelper->ReadParam('formmanager_data', '', FILTER_UNSAFE_RAW);
		if (!empty($sFormManagerClass) && !empty($sFormManagerData)) {
			/** @var \Combodo\iTop\Portal\Form\ObjectFormManager $oFormManager */
			$oFormManager = $sFormManagerClass::FromJSON($sFormManagerData);
			$oFormManager->SetObjectFormHandlerHelper($this->oObjectFormHandlerHelper);
			$oFormManager->SetObject($oHostObject);

			// Applying action rules if present
			if (($oFormManager->GetActionRulesToken() !== null) && ($oFormManager->GetActionRulesToken() !== '')) {
				$aActionRules = ContextManipulatorHelper::DecodeRulesToken($oFormManager->GetActionRulesToken());
				$oObj = $oFormManager->GetObject();
				$this->oContextManipulatorHelper->PrepareObject($aActionRules, $oObj);
				$oFormManager->SetObject($oObj);
			}

			// Updating host object
			$oFormManager->OnUpdate(array(
				'currentValues' => $this->oRequestManipulatorHelper->ReadParam('current_values', array(), FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY),
			));
			$oHostObject = $oFormManager->GetObject();
		}

		// Retrieving request parameters
		$iPageNumber = $this->oRequestManipulatorHelper->ReadParam('iPageNumber', static::DEFAULT_PAGE_NUMBER, FILTER_SANITIZE_NUMBER_INT);
		$iListLength = $this->oRequestManipulatorHelper->ReadParam('iListLength', static::DEFAULT_LIST_LENGTH, FILTER_SANITIZE_NUMBER_INT);
		$bInitialPass = $this->oRequestManipulatorHelper->HasParam('draw') ? false : true;
		$sQuery = $this->oRequestManipulatorHelper->ReadParam('sSearchValue', '');
		$sFormPath = $this->oRequestManipulatorHelper->ReadParam('sFormPath', '');
		$sFieldId = $this->oRequestManipulatorHelper->ReadParam('sFieldId', '');
		$aObjectIdsToIgnore = $this->oRequestManipulatorHelper->ReadParam('aObjectIdsToIgnore', null, FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);

		// Building search query
		// - Retrieving target object class from attcode
		$oTargetAttDef = MetaModel::GetAttributeDef($sHostObjectClass, $sTargetAttCode);
		if ($oTargetAttDef->IsExternalKey())
		{
			/** @var \AttributeExternalKey $oTargetAttDef */
			$sTargetObjectClass = $oTargetAttDef->GetTargetClass();
		}
		elseif ($oTargetAttDef->IsLinkSet())
		{
			/** @var \AttributeLinkedSet $oTargetAttDef */
			if (!$oTargetAttDef->IsIndirect())
			{
				$sTargetObjectClass = $oTargetAttDef->GetLinkedClass();
			}
			else
			{
				/** @var \AttributeLinkedSetIndirect $oTargetAttDef */
				/** @var \AttributeExternalKey $oRemoteAttDef */
				$oRemoteAttDef = MetaModel::GetAttributeDef($oTargetAttDef->GetLinkedClass(), $oTargetAttDef->GetExtKeyToRemote());
				$sTargetObjectClass = $oRemoteAttDef->GetTargetClass();
			}
		}
		elseif ($oTargetAttDef->GetEditClass() === 'CustomFields')
		{
			$oRequestTemplate = $oHostObject->Get($sTargetAttCode);
			/** @var \DBSearch $oTemplateFieldSearch */
			$oTemplateFieldSearch = $oRequestTemplate->GetForm()->GetField('user_data')->GetForm()->GetField($sFieldId)->GetSearch();
			$sTargetObjectClass = $oTemplateFieldSearch->GetClass();
		}
		else
		{
			throw new Exception('Search from attribute can only apply on AttributeExternalKey or AttributeLinkedSet objects, '.get_class($oTargetAttDef).' given.');
		}

		// - Retrieving class attribute list
		$aAttCodes = ApplicationHelper::GetLoadedListFromClass($aCombodoPortalInstanceConf['lists'], $sTargetObjectClass, 'list');
		// - Adding friendlyname attribute to the list is not already in it
		$sTitleAttCode = 'friendlyname';
		if (($sTitleAttCode !== null) && !in_array($sTitleAttCode, $aAttCodes))
		{
			$aAttCodes = array_merge(array($sTitleAttCode), $aAttCodes);
		}

		// - Retrieving scope search
		// Note : This do NOT apply to custom fields as the portal administrator is not supposed to know which objects will be put in the templates.
		// It is the responsibility of the template designer to write the right query so the user see only what he should.
		$oScopeSearch = $this->oScopeValidatorHelper->GetScopeFilterForProfiles(UserRights::ListProfiles(), $sTargetObjectClass, UR_ACTION_READ);
		$aInternalParams = array();
		if (($oScopeSearch === null) && ($oTargetAttDef->GetEditClass() !== 'CustomFields'))
		{
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : User #'.UserRights::GetUserId().' has no scope query for '.$sTargetObjectClass.' class.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		// - Base query from meta model
		/** @var \DBSearch $oSearch */
		if ($oTargetAttDef->IsExternalKey())
		{
			$oSearch = DBSearch::FromOQL($oTargetAttDef->GetValuesDef()->GetFilterExpression());
		}
		elseif ($oTargetAttDef->IsLinkSet())
		{
			$oSearch = $oScopeSearch;
		}
		elseif ($oTargetAttDef->GetEditClass() === 'CustomFields')
		{
			// Note : $oTemplateFieldSearch has been defined in the "Retrieving target object class from attcode" part, it is not available otherwise
			$oSearch = $oTemplateFieldSearch;
		}

		// - Filtering objects to ignore
		if (($aObjectIdsToIgnore !== null) && (is_array($aObjectIdsToIgnore)))
		{
			//$oSearch->AddConditionExpression('id', $aObjectIdsToIgnore, 'NOT IN');
			$aExpressions = array();
			foreach ($aObjectIdsToIgnore as $sObjectIdToIgnore)
			{
				$aExpressions[] = new ScalarExpression($sObjectIdToIgnore);
			}
			$oSearch->AddConditionExpression(new BinaryExpression(new FieldExpression('id', $oSearch->GetClassAlias()), 'NOT IN',
				new ListExpression($aExpressions)));
		}

		// - Adding query condition
		$aInternalParams['this'] = $oHostObject;
		if (!empty($sQuery))
		{
			$oFullExpr = null;
			/** @noinspection SlowArrayOperationsInLoopInspection */
			for ($i = 0; $i < count($aAttCodes); $i++)
			{
				// Checking if the current attcode is an external key in order to search on the friendlyname
				$oAttDef = MetaModel::GetAttributeDef($sTargetObjectClass, $aAttCodes[$i]);
				$sAttCode = (!$oAttDef->IsExternalKey()) ? $aAttCodes[$i] : $aAttCodes[$i].'_friendlyname';
				// Building expression for the current attcode
				// - For attributes that need conversion from their display value to storage value
				//   Note : This is dirty hack that will need to be refactored in the OQL core in order to be nicer and to be extended to other types such as dates etc...
				if (($oAttDef instanceof AttributeEnum) || ($oAttDef instanceof AttributeFinalClass))
				{
					// Looking up storage value
					$aMatchedCodes = array();
					foreach ($oAttDef->GetAllowedValues() as $sValueCode => $sValueLabel)
					{
						if (stripos($sValueLabel, $sQuery) !== false)
						{
							$aMatchedCodes[] = $sValueCode;
						}
					}
					// Building expression
					if (!empty($aMatchedCodes))
					{
						$oEnumeratedListExpr = ListExpression::FromScalars($aMatchedCodes);
						$oBinExpr = new BinaryExpression(new FieldExpression($sAttCode, $oSearch->GetClassAlias()), 'IN',
							$oEnumeratedListExpr);
					}
					else
					{
						$oBinExpr = new FalseExpression();
					}
				}
				// - For regular attributes
				else
				{
					$oBinExpr = new BinaryExpression(new FieldExpression($sAttCode, $oSearch->GetClassAlias()), 'LIKE',
						new VariableExpression('re_query'));
				}
				// Adding expression to the full expression (all attcodes)
				if ($i === 0)
				{
					$oFullExpr = $oBinExpr;
				}
				else
				{
					$oFullExpr = new BinaryExpression($oFullExpr, 'OR', $oBinExpr);
				}
			}
			// Adding full expression to the search object
			$oSearch->AddConditionExpression($oFullExpr);
			$aInternalParams['re_query'] = '%'.$sQuery.'%';
		}

		// - Intersecting with scope constraints
		// Note : This do NOT apply to custom fields as the portal administrator is not supposed to know which objects will be put in the templates.
		// It is the responsibility of the template designer to write the right query so the user see only what he should.
		if (($oScopeSearch !== null) && ($oTargetAttDef->GetEditClass() !== 'CustomFields'))
		{
			$oSearch = $oSearch->Intersect($oScopeSearch);
			// - Allowing all data if necessary
			if ($oScopeSearch->IsAllDataAllowed())
			{
				$oSearch->AllowAllData();
			}
		}

		// Retrieving results
		// - Preparing object set
		$oSet = new DBObjectSet($oSearch, array(), $aInternalParams);
		$oSet->OptimizeColumnLoad(array($oSearch->GetClassAlias() => $aAttCodes));
		$oSet->SetLimit($iListLength, $iListLength * ($iPageNumber - 1));
		// - Retrieving columns properties
		$aColumnProperties = array();
		foreach ($aAttCodes as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($sTargetObjectClass, $sAttCode);
			$aColumnProperties[$sAttCode] = array(
				'title' => $oAttDef->GetLabel(),
			);
		}
		// - Retrieving objects
		$aItems = array();
		while ($oItem = $oSet->Fetch())
		{
			$aItems[] = $this->PrepareObjectInformation($oItem, $aAttCodes);
		}

		// Preparing response
		if ($bInitialPass)
		{
			$aData = $aData + array(
					'form' => array(
						'id' => 'object_search_form_'.time(),
						'title' => Dict::Format('Brick:Portal:Object:Search:Regular:Title', $oTargetAttDef->GetLabel(),
							MetaModel::GetName($sTargetObjectClass)),
					),
					'aColumnProperties' => json_encode($aColumnProperties),
					'aResults' => array(
						'aItems' => json_encode($aItems),
						'iCount' => count($aItems),
					),
					'bMultipleSelect' => $oTargetAttDef->IsLinkSet(),
					'aSource' => array(
						'sFormPath' => $sFormPath,
						'sFieldId' => $sFieldId,
						'aObjectIdsToIgnore' => $aObjectIdsToIgnore,
						'sFormManagerClass' => $sFormManagerClass,
						'sFormManagerData' => $sFormManagerData,
					),
				);

			if ($oRequest->isXmlHttpRequest())
			{
				$oResponse = $this->render('itop-portal-base/portal/templates/bricks/object/modal.html.twig', $aData);
			}
			else
			{
				//throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
				$oResponse = $this->render('itop-portal-base/portal/templates/bricks/object/layout.html.twig', $aData);
			}
		}
		else
		{
			$aData = $aData + array(
					'levelsProperties' => $aColumnProperties,
					'data' => $aItems,
					'recordsTotal' => $oSet->Count(),
					'recordsFiltered' => $oSet->Count(),
				);

			$oResponse = new JsonResponse($aData);
		}

		return $oResponse;
	}

	/**
	 * Handles ormDocument display / download from an object
	 *
	 * Note: This is inspired from pages/ajax.document.php, but duplicated as there is no secret mecanism for ormDocument yet.
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sOperation
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function DocumentAction(Request $oRequest, $sOperation = null)
	{

		// Setting default operation
		if ($sOperation === null)
		{
			$sOperation = 'display';
		}

		// Retrieving ormDocument's host object
		$sObjectClass = $this->oRequestManipulatorHelper->ReadParam('sObjectClass', '');
		$sObjectId = $this->oRequestManipulatorHelper->ReadParam('sObjectId', '');
		$sObjectField = $this->oRequestManipulatorHelper->ReadParam('sObjectField', '');
		$bCheckSecurity = true;

		// When reaching to an Attachment, we have to check security on its host object instead of the Attachment itself
		if ($sObjectClass === 'Attachment')
		{
			
			$oAttachment = MetaModel::GetObject($sObjectClass, $sObjectId, false, true);
			if ($oAttachment === null) {
				throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
			}
			$sHostClass = $oAttachment->Get('item_class');
			$sHostId = $oAttachment->Get('item_id');
			
			// Attachments could be linked to host objects without an org_id. Retrieving the attachment would fail if enforced silos are based on org_id
			if($oAttachment->Get('item_org_id') === 0 && ($sHostId > 0) && $this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sHostClass, $sHostId)) {
				$bCheckSecurity = false;
			}
			
		}
		else
		{
			$sHostClass = $sObjectClass;
			$sHostId = $sObjectId;

			// Security bypass for the image attribute of a class
			if(MetaModel::GetImageAttributeCode($sObjectClass) === $sObjectField) {
				$bCheckSecurity = false;
			}
		}

		// Checking security layers
		// Note: Checking if host object already exists as we can try to download document from an object that is being created
		if (($bCheckSecurity === true) && ($sHostId > 0) && !$this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sHostClass, $sHostId))
		{
			IssueLog::Warning(__METHOD__.' at line '.__LINE__.' : User #'.UserRights::GetUserId().' not allowed to retrieve document from attribute '.$sObjectField.' as it not allowed to read '.$sHostClass.'::'.$sHostId.' object.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Retrieving object
		$bAllowAllDataFlag = ($bCheckSecurity === false) ? true : $this->oScopeValidatorHelper->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sHostClass);
		$oObject = MetaModel::GetObject($sObjectClass, $sObjectId, false /* Must not be found */, $bAllowAllDataFlag);
		if ($oObject === null)
		{
			IssueLog::Info(__METHOD__.' at line '.__LINE__.': Could not load object '.$sObjectClass.'::'.$sObjectId.'.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Setting cache timeout
		// Note: Attachment download should be handle through AttachmentAction()
		if ($sObjectClass === 'Attachment')
		{
			// One year ahead: an attachment cannot change
			$iCacheSec = 31556926;
		}
		else
		{
			$iCacheSec = $this->oRequestManipulatorHelper->ReadParam('cache', 0, FILTER_SANITIZE_NUMBER_INT);
		}

		$aHeaders = array();
		if ($iCacheSec > 0)
		{
			$aHeaders['Expires'] = '';
			$aHeaders['Cache-Control'] = 'no-transform, public,max-age='.$iCacheSec.',s-maxage='.$iCacheSec;
			// Reset the value set previously
			$aHeaders['Pragma'] = 'cache';

			// N°3423 Fix bug in Symphony 3.x in Response::sendHeaders(): Headers need to send directly as SF doesn't replace header of page except for Content-Type
			header('Cache-Control: no-transform, public,max-age='.$iCacheSec.',s-maxage='.$iCacheSec);
			header('Pragma: cache');
			header('Expires: ');

			// An arbitrary date in the past is ok
			$aHeaders['Last-Modified'] = 'Wed, 15 Jun 2015 13:21:15 GMT';
		}

		/** @var \ormDocument $oDocument */
		$oDocument = $oObject->Get($sObjectField);
		$aHeaders['Content-Type'] = $oDocument->GetMimeType();
		$aHeaders['Content-Disposition'] = (($sOperation === 'display') ? 'inline' : 'attachment').';filename="'.$oDocument->GetFileName().'"';

		// N°4129 - Prevent XSS attacks & other script executions
		if (utils::GetConfig()->Get('security.disable_inline_documents_sandbox') === false) {
			$aHeaders['Content-Security-Policy'] = 'sandbox';
		}

		return new Response($oDocument->GetData(), Response::HTTP_OK, $aHeaders);
	}

	/**
	 * Handles attachment add/remove on an object
	 *
	 * Note: This is inspired from itop-attachment/ajax.attachment.php
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string $sOperation
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function AttachmentAction(Request $oRequest, $sOperation = null)
	{
		$aData = array(
			'att_id' => 0,
			'preview' => false,
			'msg' => '',
		);

		// Retrieving sOperation from request only if it wasn't forced (determined by the route)
		if ($sOperation === null)
		{
			$sOperation = $this->oRequestManipulatorHelper->ReadParam('operation', null);
		}
		switch ($sOperation)
		{
			case 'add':
				$sFieldName = $this->oRequestManipulatorHelper->ReadParam('field_name', '');
				$sObjectClass = $this->oRequestManipulatorHelper->ReadParam('object_class', '');
				$sTempId = $this->oRequestManipulatorHelper->ReadParam('temp_id', '');

				if (empty($sObjectClass) || empty($sTempId))
				{
					$aData['error'] = Dict::Format('UI:Error:2ParametersMissing', 'object_class', 'temp_id');
				}
				else
				{
					try
					{
						$oDocument = utils::ReadPostedDocument($sFieldName);
						/** @noinspection PhpUndefinedClassInspection */
						/** @var \Attachment $oAttachment */
						$oAttachment = MetaModel::NewObject('Attachment');
						$oAttachment->Set('expire', time() + MetaModel::GetConfig()->Get('draft_attachments_lifetime')); // one hour...
						$oAttachment->Set('temp_id', $sTempId);
						$oAttachment->Set('item_class', $sObjectClass);
						$oAttachment->SetDefaultOrgId();
						$oAttachment->Set('contents', $oDocument);
						$iAttId = $oAttachment->DBInsert();

						$aData['msg'] = utils::EscapeHtml($oDocument->GetFileName());
						$aData['icon'] = utils::GetAbsoluteUrlAppRoot().'env-'.utils::GetCurrentEnvironment().'/itop-attachments/icons/icons8-image-file.svg';

						// Checking if the instance has attachments
						if (class_exists('AttachmentPlugIn')) {
							$aData['icon'] = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($oDocument->GetFileName());
						}

						$aData['att_id'] = $iAttId;
						$aData['preview'] = $oDocument->IsPreviewAvailable();
						$aData['file_size'] = $oDocument->GetFormattedSize();
						$aData['downloads_count'] = $oDocument->GetDownloadsCount();
						$aData['creation_date'] = $oAttachment->Get('creation_date');
						$aData['user_id_friendlyname'] = $oAttachment->Get('user_id_friendlyname');
						$aData['file_type'] = $oDocument->GetMimeType();
					}
					catch (FileUploadException $e)
					{
						$aData['error'] = $e->GetMessage();
					}
				}

				// Note : The Content-Type header is set to 'text/plain' in order to be IE9 compatible. Otherwise ('application/json') IE9 will download the response as a JSON file to the user computer...
				$oResponse = new JsonResponse($aData, Response::HTTP_OK, array('Content-Type' => 'text/plain'));
				break;

			case 'download':
				// Preparing redirection
				// - Route
				$aRouteParams = array(
					'sObjectClass' => 'Attachment',
					'sObjectId' => $this->oRequestManipulatorHelper->ReadParam('sAttachmentId', null),
					'sObjectField' => 'contents',
				);

				$oResponse = $this->ForwardToRoute('p_object_document_download', $aRouteParams, $oRequest->query->all());

				break;

			default:
				throw new HttpException(Response::HTTP_FORBIDDEN, Dict::S('Error:HTTP:400'));
				break;
		}

		return $oResponse;
	}

	/**
	 * Returns a json response containing an array of objects informations.
	 *
	 * The service must be given 3 parameters :
	 * - sObjectClass : The class of objects to retrieve information from
	 * - aObjectIds : An array of object ids
	 * - aObjectAttCodes : An array of attribute codes to retrieve
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function GetInformationAsJsonAction(Request $oRequest)
	{

		$aData = array();

		// Retrieving parameters
		$sObjectClass = $this->oRequestManipulatorHelper->ReadParam('sObjectClass', '');
		$aObjectIds = $this->oRequestManipulatorHelper->ReadParam('aObjectIds', array(), FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		$aObjectAttCodes = $this->oRequestManipulatorHelper->ReadParam('aObjectAttCodes', array(), FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		if (empty($sObjectClass) || empty($aObjectIds) || empty($aObjectAttCodes)) {
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : sObjectClass, aObjectIds and aObjectAttCodes expected, "'.$sObjectClass.'", "'.implode('/',
					$aObjectIds).'" given.');
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid request data, some information are missing');
		}

		// Building the search
		$bIgnoreSilos = $this->oScopeValidatorHelper->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass);
		$aParams = array('objects_id' => $aObjectIds);
		$oSearch = DBObjectSearch::FromOQL("SELECT $sObjectClass WHERE id IN (:objects_id)");
		if ($bIgnoreSilos === true) {
			$oSearch->AllowAllData();
		}
		$oSet = new DBObjectSet($oSearch, array(), $aParams);
		$oSet->OptimizeColumnLoad(array($oSearch->GetClassAlias() => $aObjectAttCodes));

		// Checking that id is in the AttCodes
		// Note: We do that AFTER the array is used in OptimizeColumnLoad() because the function doesn't support this anymore.
		if (!in_array('id', $aObjectAttCodes)) {
			$aObjectAttCodes = array_merge(array('id'), $aObjectAttCodes);
		}

		// Retrieving objects
		while ($oObject = $oSet->Fetch()) {
			$aData['items'][] = $this->PrepareObjectInformation($oObject, $aObjectAttCodes);
		}

		return new JsonResponse($aData);
	}

	/**
	 * GetInformationAsJsonAction for linked set usages.
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 * @since 3.1
	 *
	 */
	public function GetInformationForLinkedSetAsJsonAction(Request $oRequest)
	{
		// Data array
		$aData = array(
			'js_inline'  => '',
			'css_inline' => '',
		);

		// Retrieving parameters
		$sObjectClass = $this->oRequestManipulatorHelper->ReadParam('sObjectClass', '');
		$sLinkClass = $this->oRequestManipulatorHelper->ReadParam('sLinkClass', '');
		$aObjectIds = $this->oRequestManipulatorHelper->ReadParam('aObjectIds', array(), FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		$aObjectAttCodes = $this->oRequestManipulatorHelper->ReadParam('aObjectAttCodes', array(), FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		$aLinkAttCodes = $this->oRequestManipulatorHelper->ReadParam('aLinkAttCodes', array(), FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		$sDateTimePickerWidgetParent = $this->oRequestManipulatorHelper->ReadParam('sDateTimePickerWidgetParent', array(), FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);

		if (empty($sObjectClass) || empty($aObjectIds) || empty($aObjectAttCodes)) {
			IssueLog::Info(__METHOD__.' at line '.__LINE__.' : sObjectClass, aObjectIds and aObjectAttCodes expected, "'.$sObjectClass.'", "'.implode('/',
					$aObjectIds).'" given.');
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid request data, some information are missing');
		}

		// Building the search
		$bIgnoreSilos = $this->oScopeValidatorHelper->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass);
		$aParams = array('objects_id' => $aObjectIds);
		$oSearch = DBObjectSearch::FromOQL("SELECT $sObjectClass WHERE id IN (:objects_id)");
		if ($bIgnoreSilos === true)
		{
			$oSearch->AllowAllData();
		}
		$oSet = new DBObjectSet($oSearch, array(), $aParams);
		$oSet->OptimizeColumnLoad(array($oSearch->GetClassAlias() => $aObjectAttCodes));

		// Checking that id is in the AttCodes
		// Note: We do that AFTER the array is used in OptimizeColumnLoad() because the function doesn't support this anymore.
		if (!in_array('id', $aObjectAttCodes)) {
			$aObjectAttCodes = array_merge(array('id'), $aObjectAttCodes);
		}

		// Retrieving objects
		while ($oObject = $oSet->Fetch()) {
			// Prepare link data
			$aObjectData = $this->PrepareObjectInformation($oObject, $aObjectAttCodes);
			// New link object (needed for renderers)
			$oNewLink = new $sLinkClass();
			foreach ($aLinkAttCodes as $sAttCode) {
				$oAttDef = MetaModel::GetAttributeDef($sLinkClass, $sAttCode);
				$oField = $oAttDef->MakeFormField($oNewLink);
				// Prevent datetimepicker popup to be truncated
				if ($oField instanceof DateTimeField) {
					$oField->SetDateTimePickerWidgetParent($sDateTimePickerWidgetParent);
				}
				$sFieldRendererClass = BsLinkedSetFieldRenderer::GetFieldRendererClass($oField);
				$sValue = $oAttDef->GetAsHTML($oNewLink->Get($sAttCode));
				if ($sFieldRendererClass !== null) {
					$oFieldRenderer = new $sFieldRendererClass($oField);
					$oFieldOutput = $oFieldRenderer->Render();
					$sValue = $oFieldOutput->GetHtml();
				}
				$aObjectData['attributes']['lnk__'.$sAttCode] = [
					'att_code'   => $sAttCode,
					'value'      => $sValue,
					'css_inline' => $oFieldOutput->GetCss(),
					'js_inline'  => $oFieldOutput->GetJs(),
				];
			}

			$aData['items'][] = $aObjectData;
		}

		return new JsonResponse($aData);
	}

	/**
	 * Prepare a DBObject information as an array for a client side usage (typically, add a row in a table)
	 *
	 * @param \DBObject $oObject
	 * @param array $aAttCodes
	 *
	 * @return array
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected function PrepareObjectInformation(DBObject $oObject, $aAttCodes = array())
	{
		$sObjectClass = get_class($oObject);
		$aObjectData = [
			'id'                  => $oObject->GetKey(),
			'object_class'  => $sObjectClass,
			'name'             => $oObject->GetName(),
			'attributes'        => [],
		];

		// Retrieving attributes definitions
		$aAttDefs = [];
		foreach ($aAttCodes as $sAttCode)
		{
			if ($sAttCode === 'id')
			{
				continue;
			}

			$aAttDefs[$sAttCode] = MetaModel::GetAttributeDef($sObjectClass, $sAttCode);
		}

		// Preparing attribute data
		foreach ($aAttDefs as $oAttDef)
		{
			$aAttData = [
				'att_code' => $oAttDef->GetCode(),
				'attribute-type' => get_class($oAttDef),
			];

			// - Value raw
			if ($oAttDef::IsScalar()) {
				$aAttData['value-raw'] =utils::HtmlEntities( (string)$oObject->Get($oAttDef->GetCode()));
			}

			if ($oAttDef->IsExternalKey())
			{
				$aAttData['value'] = $oObject->GetAsHTML($oAttDef->GetCode().'_friendlyname');

				// Checking if user can access object's external key
				if ($this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $oAttDef->GetTargetClass()))
				{
					$aAttData['url'] = $this->oUrlGenerator->generate('p_object_view',
						array('sObjectClass' => $oAttDef->GetTargetClass(), 'sObjectId' => $oObject->Get($oAttDef->GetCode())));
				}
			}
			elseif ($oAttDef->IsLinkSet())
			{
				// We skip it
				continue;
			}
			elseif ($oAttDef instanceof AttributeImage)
			{
				/** @var \ormDocument $oOrmDoc */
				$oOrmDoc = $oObject->Get($oAttDef->GetCode());
				if (is_object($oOrmDoc) && !$oOrmDoc->IsEmpty())
				{
					$sUrl = $this->oUrlGenerator->generate('p_object_document_display', [
						'sObjectClass' => get_class($oObject),
						'sObjectId' => $oObject->GetKey(),
						'sObjectField' => $oAttDef->GetCode(),
						'cache' => 86400,
						's' => $oOrmDoc->GetSignature(),
					]);
				}
				else
				{
					$sUrl = $oAttDef->Get('default_image');
				}
				$aAttData['value'] = '<img src="'.$sUrl.'" />';
			}
			elseif ($oAttDef instanceof AttributeEnum) {
				$aAttData['value'] = $oAttDef->GetAsPlainText($oObject->Get($oAttDef->GetCode()));
			}
			else
			{
				$aAttData['value'] = $oAttDef->GetAsHTML($oObject->Get($oAttDef->GetCode()));

				if ($oAttDef instanceof AttributeFriendlyName)
				{
					// Checking if user can access object
					if ($this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sObjectClass))
					{
						$aAttData['url'] = $this->oUrlGenerator->generate('p_object_view',
							array('sObjectClass' => $sObjectClass, 'sObjectId' => $oObject->GetKey()));
					}
				}
			}

			$aObjectData['attributes'][$oAttDef->GetCode()] = $aAttData;
		}

		return $aObjectData;
	}

	/**
	 * Displays the creation form of an instantiable class
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sObjectClass
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \ArchivedObjectException
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	protected function DisplayCreationForm(Request $oRequest, $sObjectClass)
	{
		// Checking security layers
		if (!$this->oSecurityHelper->IsActionAllowed(UR_ACTION_CREATE, $sObjectClass))
		{
			IssueLog::Warning(__METHOD__.' at line '.__LINE__.' : User #'.UserRights::GetUserId().' not allowed to create '.$sObjectClass.' object.');
			throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
		}

		$sOperation = $this->oRequestManipulatorHelper->ReadParam('operation', '');

		$aData = array('sMode' => 'create');
		$aData['form'] = $this->oObjectFormHandlerHelper->HandleForm($oRequest, $aData['sMode'], $sObjectClass);
		$aData['form']['title'] = Dict::Format('Brick:Portal:Object:Form:Create:Title', MetaModel::GetName($sObjectClass));

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			// We have to check whether the 'operation' parameter is defined or not in order to know if the form is required via ajax (to be displayed as a modal dialog) or if it's a lifecycle call from a existing form.
			if (empty($sOperation))
			{
				$oResponse = $this->render('itop-portal-base/portal/templates/bricks/object/modal.html.twig', $aData);
			}
			else
			{
				$oResponse = new JsonResponse($aData);
			}
		}
		else
		{
			// Adding brick if it was passed
			$sBrickId = $this->oRequestManipulatorHelper->ReadParam('sBrickId', '');
			if (!empty($sBrickId))
			{
				$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);
				if ($oBrick !== null)
				{
					$aData['oBrick'] = $oBrick;
				}
			}
			$aData['sPageTitle'] = $aData['form']['title'];
			$oResponse = $this->render('itop-portal-base/portal/templates/bricks/object/layout.html.twig', $aData);
		}

		return $oResponse;
	}

	/**
	 * Displays a list of leaf classes from the abstract $sObjectClass which will lead to the actual creation form.
	 *
	 * @param string $sObjectClass
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	protected function DisplayLeafClassesForm($sObjectClass)
	{
		$aData = array(
			'aLeafClasses' => array(),
			'sPageTitle' => Dict::Format('Brick:Portal:Object:Form:Create:Title', MetaModel::GetName($sObjectClass)),
			'sLeafClassesListId' => 'leaf_classes_list_' . uniqid(),
			'ar_token' => $this->oRequestManipulatorHelper->ReadParam('ar_token', ''),
		);
		$sTemplatePath = CreateBrick::DEFAULT_PAGE_TEMPLATE_PATH;

		$sBrickId = $this->oRequestManipulatorHelper->ReadParam('sBrickId', '');
		if (!empty($sBrickId))
		{
			$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);
			$sTemplatePath = $oBrick->GetPageTemplatePath();

			$aData['sBrickId'] = $sBrickId;
			$aData['oBrick'] = $oBrick;
			$aData['sPageTitle'] = $oBrick->GetTitle();
		}

		$aLeafClasses = array();
		$aChildClasses = MetaModel::EnumChildClasses($sObjectClass);
		foreach ($aChildClasses as $sChildClass)
		{
			if (!MetaModel::IsAbstract($sChildClass) && $this->oSecurityHelper->IsActionAllowed(UR_ACTION_CREATE, $sChildClass))
			{
				$aLeafClasses[] = array(
					'id' => $sChildClass,
					'name' => MetaModel::GetName($sChildClass),
				);
			}
		}
		$aData['aLeafClasses'] = $aLeafClasses;

		$oResponse = $this->render($sTemplatePath, $aData);

		return $oResponse;
	}
}
