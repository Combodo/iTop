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

namespace Combodo\iTop\Portal\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Exception;
use FileUploadException;
use utils;
use Dict;
use IssueLog;
use MetaModel;
use DBObject;
use DBSearch;
use DBObjectSearch;
use FalseExpression;
use BinaryExpression;
use FieldExpression;
use VariableExpression;
use ListExpression;
use ScalarExpression;
use DBObjectSet;
use AttributeEnum;
use AttributeImage;
use AttributeFinalClass;
use AttributeFriendlyName;
use UserRights;
use iPopupMenuExtension;
use URLButtonItem;
use JSButtonItem;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use Combodo\iTop\Portal\Helper\SecurityHelper;
use Combodo\iTop\Portal\Helper\ContextManipulatorHelper;
use Combodo\iTop\Portal\Form\ObjectFormManager;
use Combodo\iTop\Renderer\Bootstrap\BsFormRenderer;

/**
 * Class ObjectController
 *
 * Controller to handle basic view / edit / create of cmdbAbstractObjectClass ManageBrickController
 *
 * @package Combodo\iTop\Portal\Controller
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 2.3.0
 */
class ObjectController extends AbstractController
{

	const ENUM_MODE_VIEW = 'view';
	const ENUM_MODE_EDIT = 'edit';
	const ENUM_MODE_CREATE = 'create';

	const DEFAULT_PAGE_NUMBER = 1;
	const DEFAULT_LIST_LENGTH = 10;

    /**
     * Displays an cmdbAbstractObject if the connected user is allowed to.
     *
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param string $sObjectClass (Class must be instance of cmdbAbstractObject)
     * @param string $sObjectId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \DictExceptionMissingString
     */
	public function ViewAction(Request $oRequest, Application $oApp, $sObjectClass, $sObjectId)
	{
		// Checking parameters
		if ($sObjectClass === '' || $sObjectId === '')
		{
			IssueLog::Info(__METHOD__ . ' at line ' . __LINE__ . ' : sObjectClass and sObjectId expected, "' . $sObjectClass . '" and "' . $sObjectId . '" given.');
			$oApp->abort(500, Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
		}

		// Checking security layers
		if (!SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $sObjectClass, $sObjectId))
		{
			IssueLog::Warning(__METHOD__ . ' at line ' . __LINE__ . ' : User #' . UserRights::GetUserId() . ' not allowed to read ' . $sObjectClass . '::' . $sObjectId . ' object.');
			$oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Retrieving object
		$oObject = MetaModel::GetObject($sObjectClass, $sObjectId, false /* MustBeFound */, $oApp['scope_validator']->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass));
		if ($oObject === null)
		{
			// We should never be there as the secuirty helper makes sure that the object exists, but just in case.
			IssueLog::Info(__METHOD__ . ' at line ' . __LINE__ . ' : Could not load object ' . $sObjectClass . '::' . $sObjectId . '.');
			$oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
		}

		$sOperation = $oApp['request_manipulator']->ReadParam('operation', '');

		$aData = array('sMode' => 'view');
		$aData['form'] = $this->HandleForm($oRequest, $oApp, $aData['sMode'], $sObjectClass, $sObjectId);
		$aData['form']['title'] = Dict::Format('Brick:Portal:Object:Form:View:Title', MetaModel::GetName($sObjectClass), $oObject->GetName());

		// Add an edit button if user is allowed
		if (SecurityHelper::IsActionAllowed($oApp, UR_ACTION_MODIFY, $sObjectClass, $sObjectId))
		{
		    $oModifyButton = new URLButtonItem(
		        'modify_object',
                Dict::S('UI:Menu:Modify'),
				$oApp['url_generator']->generate('p_object_edit', array('sObjectClass' => $sObjectClass, 'sObjectId' => $sObjectId))
            );
		    // Putting this one first
		    $aData['form']['buttons']['links'][] = $oModifyButton->GetMenuItem();
		}

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			// We have to check whether the 'operation' parameter is defined or not in order to know if the form is required via ajax (to be displayed as a modal dialog) or if it's a lifecycle call from a existing form.
			if (empty($sOperation))
			{
				$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/bricks/object/modal.html.twig', $aData);
			}
			else
			{
				$oResponse = $oApp->json($aData);
			}
		}
		else
		{
			// Adding brick if it was passed
			$sBrickId = $oApp['request_manipulator']->ReadParam('sBrickId', '');
			if (!empty($sBrickId))
			{
				$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);
				if ($oBrick !== null)
				{
					$aData['oBrick'] = $oBrick;
				}
			}
			$aData['sPageTitle'] = $aData['form']['title'];
			$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/bricks/object/layout.html.twig', $aData);
		}

		return $oResponse;
	}

    /**
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param $sObjectClass
     * @param $sObjectId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \DictExceptionMissingString
     */
	public function EditAction(Request $oRequest, Application $oApp, $sObjectClass, $sObjectId)
	{
		// Checking parameters
		if ($sObjectClass === '' || $sObjectId === '')
		{
			IssueLog::Info(__METHOD__ . ' at line ' . __LINE__ . ' : sObjectClass and sObjectId expected, "' . $sObjectClass . '" and "' . $sObjectId . '" given.');
			$oApp->abort(500, Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
		}
		
		// Checking security layers
		// Warning : This is a dirty quick fix to allow editing its own contact information
		$bAllowWrite = ($sObjectClass === 'Person' && $sObjectId == UserRights::GetContactId());
		if (!SecurityHelper::IsActionAllowed($oApp, UR_ACTION_MODIFY, $sObjectClass, $sObjectId) && !$bAllowWrite)
		{
			IssueLog::Warning(__METHOD__ . ' at line ' . __LINE__ . ' : User #' . UserRights::GetUserId() . ' not allowed to modify ' . $sObjectClass . '::' . $sObjectId . ' object.');
			$oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Retrieving object
		$oObject = MetaModel::GetObject($sObjectClass, $sObjectId, false /* MustBeFound */, $oApp['scope_validator']->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass));
		if ($oObject === null)
		{
			// We should never be there as the secuirty helper makes sure that the object exists, but just in case.
			IssueLog::Info(__METHOD__ . ' at line ' . __LINE__ . ' : Could not load object ' . $sObjectClass . '::' . $sObjectId . '.');
			$oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
		}

		$sOperation = $oApp['request_manipulator']->ReadParam('operation', '');

		$aData = array('sMode' => 'edit');
		$aData['form'] = $this->HandleForm($oRequest, $oApp, $aData['sMode'], $sObjectClass, $sObjectId);
		$aData['form']['title'] = Dict::Format('Brick:Portal:Object:Form:Edit:Title', MetaModel::GetName($sObjectClass), $aData['form']['object_name']);

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			// We have to check whether the 'operation' parameter is defined or not in order to know if the form is required via ajax (to be displayed as a modal dialog) or if it's a lifecycle call from a existing form.
			if (empty($sOperation))
			{
				$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/bricks/object/modal.html.twig', $aData);
			}
			else
			{
				$oResponse = $oApp->json($aData);
			}
		}
		else
		{
			// Adding brick if it was passed
			$sBrickId = $oApp['request_manipulator']->ReadParam('sBrickId', '');
			if (!empty($sBrickId))
			{
				$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);
				if ($oBrick !== null)
				{
					$aData['oBrick'] = $oBrick;
				}
			}
			$aData['sPageTitle'] = $aData['form']['title'];
			$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/bricks/object/layout.html.twig', $aData);
		}

		return $oResponse;
	}

    /**
     * Creates an cmdbAbstractObject of the $sObjectClass
     *
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param string $sObjectClass
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @throws \CoreException
     * @throws \DictExceptionMissingString
     */
	public function CreateAction(Request $oRequest, Application $oApp, $sObjectClass)
	{
		// Checking security layers
		if (!SecurityHelper::IsActionAllowed($oApp, UR_ACTION_CREATE, $sObjectClass))
		{
			IssueLog::Warning(__METHOD__ . ' at line ' . __LINE__ . ' : User #' . UserRights::GetUserId() . ' not allowed to create ' . $sObjectClass . ' object.');
			$oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
		}

		$sOperation = $oApp['request_manipulator']->ReadParam('operation', '');

		$aData = array('sMode' => 'create');
		$aData['form'] = $this->HandleForm($oRequest, $oApp, $aData['sMode'], $sObjectClass);
		$aData['form']['title'] = Dict::Format('Brick:Portal:Object:Form:Create:Title', MetaModel::GetName($sObjectClass));

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			// We have to check whether the 'operation' parameter is defined or not in order to know if the form is required via ajax (to be displayed as a modal dialog) or if it's a lifecycle call from a existing form.
			if (empty($sOperation))
			{
				$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/bricks/object/modal.html.twig', $aData);
			}
			else
			{
				$oResponse = $oApp->json($aData);
			}
		}
		else
		{
			// Adding brick if it was passed
			$sBrickId = $oApp['request_manipulator']->ReadParam('sBrickId', '');
			if (!empty($sBrickId))
			{
				$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);
				if ($oBrick !== null)
				{
					$aData['oBrick'] = $oBrick;
				}
			}
			$aData['sPageTitle'] = $aData['form']['title'];
			$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/bricks/object/layout.html.twig', $aData);
		}

		return $oResponse;
	}

    /**
     * Creates an cmdbAbstractObject of a class determined by the method encoded in $sEncodedMethodName.
     * This method use an origin DBObject in order to determine the created cmdbAbstractObject.
     *
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param string $sObjectClass Class of the origin object
     * @param string $sObjectId ID of the origin object
     * @param string $sEncodedMethodName Base64 encoded factory method name
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */
	public function CreateFromFactoryAction(Request $oRequest, Application $oApp, $sObjectClass, $sObjectId, $sEncodedMethodName)
	{
		$sMethodName = base64_decode($sEncodedMethodName);

		// Checking that the factory method is valid
		if (!is_callable($sMethodName))
		{
			IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Invalid factory method "' . $sMethodName . '" used when creating an object.');
			$oApp->abort(500, 'Invalid factory method "' . $sMethodName . '" used when creating an object');
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
			'sObjectClass' => get_class($oTargetObject)
		);
		$sRedirectRoute = $oApp['url_generator']->generate('p_object_create', $aRouteParams);
		// - Request
		$oSubRequest = Request::create($sRedirectRoute, 'GET', $oRequest->query->all(), $oRequest->cookies->all(), array(), $oRequest->server->all());

		return $oApp->handle($oSubRequest, HttpKernelInterface::SUB_REQUEST, true);
	}

    /**
     * Applies a stimulus $sStimulus on an cmdbAbstractObject
     *
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param string $sObjectClass
     * @param string $sObjectId
     * @param string $sStimulusCode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */
	public function ApplyStimulusAction(Request $oRequest, Application $oApp, $sObjectClass, $sObjectId, $sStimulusCode)
	{
		// Checking parameters
		if ($sObjectClass === '' || $sObjectId === '' || $sStimulusCode === '')
		{
			IssueLog::Info(__METHOD__ . ' at line ' . __LINE__ . ' : sObjectClass, sObjectId and $sStimulusCode expected, "' . $sObjectClass . '", "' . $sObjectId . '" and "' . $sStimulusCode . '" given.');
			$oApp->abort(500, Dict::Format('UI:Error:3ParametersMissing', 'class', 'id', 'stimulus'));
		}

		// Checking security layers
        if(!SecurityHelper::IsStimulusAllowed($oApp, $sStimulusCode, $sObjectClass))
		{
			$oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
		}
		
		// Retrieving object
		$oObject = MetaModel::GetObject($sObjectClass, $sObjectId, false /* MustBeFound */, $oApp['scope_validator']->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass));
		if ($oObject === null)
		{
			// We should never be there as the secuirty helper makes sure that the object exists, but just in case.
			IssueLog::Info(__METHOD__ . ' at line ' . __LINE__ . ' : Could not load object ' . $sObjectClass . '::' . $sObjectId . '.');
			$oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
		}

		// Retrieving request parameters
		$sOperation = $oApp['request_manipulator']->ReadParam('operation', '');

		// Retrieving form properties
        $aStimuliForms = ApplicationHelper::GetLoadedFormFromClass($oApp, $sObjectClass, 'apply_stimulus');
        if(array_key_exists($sStimulusCode, $aStimuliForms))
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
                'layout' => null
            );
        }

        // Adding stimulus code to form
        $aFormProperties['stimulus_code'] = $sStimulusCode;

		// Adding target_state to current_values
		$oRequest->request->set('apply_stimulus', array('code' => $sStimulusCode));

		$aData = array('sMode' => 'apply_stimulus');
		$aData['form'] = $this->HandleForm($oRequest, $oApp, $aData['sMode'], $sObjectClass, $sObjectId, $aFormProperties);
		$aData['form']['title'] = Dict::Format('Brick:Portal:Object:Form:Stimulus:Title');
		$aData['form']['validation']['redirection'] = array(
			'url' => $oApp['url_generator']->generate('p_object_edit', array('sObjectClass' => $sObjectClass, 'sObjectId' => $sObjectId))
		);

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
				
				$aData = array('sMode' => 'apply_stimulus');
				$aData['form'] = $this->HandleForm($oSubRequest, $oApp, $aData['sMode'], $sObjectClass, $sObjectId, $aFormProperties);
				// Redefining the array to be as simple as possible :
				$aData = array('redirection' =>
					array('url' => $oApp['url_generator']->generate('p_object_edit', array('sObjectClass' => $sObjectClass, 'sObjectId' => $sObjectId)))
				);
			}
		}

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			// We have to check whether the 'operation' parameter is defined or not in order to know if the form is required via ajax (to be displayed as a modal dialog) or if it's a lifecycle call from a existing form.
			if (empty($sOperation))
			{
				$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/bricks/object/modal.html.twig', $aData);
			}
			elseif ($sOperation === 'redirect')
			{
				$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/modal/mode_loader.html.twig', $aData);
			}
			else
			{
				$oResponse = $oApp->json($aData);
			}
		}
		else
		{
			$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/bricks/object/layout.html.twig', $aData);
		}

		return $oResponse;
	}

    /**
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param string $sMode
     * @param string $sObjectClass
     * @param string $sObjectId
     * @param string $aFormProperties
     *
     * @return array
     *
     * @throws \Exception
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \OQLException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
	public static function HandleForm(Request $oRequest, Application $oApp, $sMode, $sObjectClass, $sObjectId = null, $aFormProperties = null)
	{
		$aFormData = array();
		$sOperation = $oApp['request_manipulator']->ReadParam('operation', '');
		$bModal = ($oRequest->isXmlHttpRequest() && empty($sOperation));

		// - Retrieve form properties
		if ($aFormProperties === null)
		{
			$aFormProperties = ApplicationHelper::GetLoadedFormFromClass($oApp, $sObjectClass, $sMode);
		}

		// - Create and
		if (empty($sOperation))
		{
			// Retrieving action rules
			//
			// Note : The action rules must be a base64-encoded JSON object, this is just so users are tempted to changes values.
			// But it would not be a security issue as it only presets values in the form.
            $sActionRulesToken = $oApp['request_manipulator']->ReadParam('ar_token', '');
			$aActionRules = (!empty($sActionRulesToken)) ? ContextManipulatorHelper::DecodeRulesToken($sActionRulesToken) : array();
			
			// Preparing object
			if ($sObjectId === null)
			{
				// Create new UserRequest
				$oObject = MetaModel::NewObject($sObjectClass);

				// Retrieve action rules information to auto-fill the form if available
				// Preparing object
				$oApp['context_manipulator']->PrepareObject($aActionRules, $oObject);
				$aPrefillFormParam = array( 'user' => $_SESSION["auth_user"],
											'origin' => 'portal');
				$oObject->PrefillForm('creation_from_0', $aPrefillFormParam);
			}
			else
			{
				$oObject = MetaModel::GetObject($sObjectClass, $sObjectId, true, $oApp['scope_validator']->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass));
			}

			// Preparing buttons
			$aFormData['buttons'] = array(
				'transitions' => array(),
                'actions' => array(),
                'links' => array(),
                'submit' => array(
                    'label' => Dict::S('Portal:Button:Submit'),
                ),
			);
			if ($sMode !== 'apply_stimulus')
			{
			    // Add transition buttons
				$oSetToCheckRights = DBObjectSet::FromObject($oObject);
				$aStimuli = Metamodel::EnumStimuli($sObjectClass);
				foreach ($oObject->EnumTransitions() as $sStimulusCode => $aTransitionDef)
				{
					if(SecurityHelper::IsStimulusAllowed($oApp, $sStimulusCode, $sObjectClass, $oSetToCheckRights))
                    {
                        $aFormData['buttons']['transitions'][$sStimulusCode] = $aStimuli[$sStimulusCode]->GetLabel();
                    }
				}

                // Add plugin buttons
                foreach (MetaModel::EnumPlugins('iPopupMenuExtension') as $oExtensionInstance)
                {
                    foreach($oExtensionInstance->EnumItems(iPopupMenuExtension::PORTAL_OBJDETAILS_ACTIONS, array('portal_id' => $oApp['combodo.portal.instance.id'], 'object' => $oObject, 'mode' => $sMode)) as $oMenuItem)
                    {
                        if (is_object($oMenuItem))
                        {
                            if($oMenuItem instanceof JSButtonItem)
                            {
                                $aFormData['buttons']['actions'][] = $oMenuItem->GetMenuItem() + array('js_files' => $oMenuItem->GetLinkedScripts());
                            }
                            elseif($oMenuItem instanceof URLButtonItem)
                            {
                                $aFormData['buttons']['links'][] = $oMenuItem->GetMenuItem();
                            }
                        }
                    }
                }

                // Hiding submit button or changing its label if necessary
                if(!empty($aFormData['buttons']['transitions']) && isset($aFormProperties['properties']) &&$aFormProperties['properties']['always_show_submit'] === false)
                {
                    unset($aFormData['buttons']['submit']);
                }
                elseif($sMode === static::ENUM_MODE_EDIT)
                {
                    $aFormData['buttons']['submit']['label'] = Dict::S('Portal:Button:Apply');
                }
			}
			else
			{
				$aPrefillFormParam = array(
				    'user' => $_SESSION["auth_user"],
					'origin' => 'portal',
					'stimulus' => $oApp['request_manipulator']->ReadParam('apply_stimulus', null)['code'],
                );
				$oObject->PrefillForm('state_change', $aPrefillFormParam);
			}

			// Preparing callback urls
			$aCallbackUrls = $oApp['context_manipulator']->GetCallbackUrls($oApp, $aActionRules, $oObject, $bModal);
			$aFormData['submit_callback'] = $aCallbackUrls['submit'];
			$aFormData['cancel_callback'] = $aCallbackUrls['cancel'];

			// Preparing renderer
			// Note : We might need to distinguish form & renderer endpoints
			if (in_array($sMode, array('create', 'edit', 'view')))
			{
				$sFormEndpoint = $oApp['url_generator']->generate('p_object_' . $sMode, array('sObjectClass' => $sObjectClass, 'sObjectId' => $sObjectId));
			}
			else
			{
				$sFormEndpoint = $_SERVER['REQUEST_URI'];
			}
			$oFormRenderer = new BsFormRenderer();
			$oFormRenderer->SetEndpoint($sFormEndpoint);

			$oFormManager = new ObjectFormManager();
			$oFormManager->SetApplication($oApp)
				->SetObject($oObject)
				->SetMode($sMode)
				->SetActionRulesToken($sActionRulesToken)
				->SetRenderer($oFormRenderer)
				->SetFormProperties($aFormProperties);

			$oFormManager->Build();

			// Check the number of editable fields
			$aFormData['editable_fields_count'] = $oFormManager->GetForm()->GetEditableFieldCount();
		}
		else
		{
			// Update / Submit / Cancel
			$sFormManagerClass = $oApp['request_manipulator']->ReadParam('formmanager_class', '', FILTER_UNSAFE_RAW);
			$sFormManagerData = $oApp['request_manipulator']->ReadParam('formmanager_data', '', FILTER_UNSAFE_RAW);
			if ( empty($sFormManagerClass) || empty($sFormManagerData) )
			{
				IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Parameters formmanager_class and formamanager_data must be defined.');
				$oApp->abort(500, 'Parameters formmanager_class and formmanager_data must be defined.');
			}

			$oFormManager = $sFormManagerClass::FromJSON($sFormManagerData);
			$oFormManager->SetApplication($oApp);
			
			// Applying action rules if present
			if (($oFormManager->GetActionRulesToken() !== null) && ($oFormManager->GetActionRulesToken() !== ''))
			{
				$aActionRules = ContextManipulatorHelper::DecodeRulesToken($oFormManager->GetActionRulesToken());
				$oObj = $oFormManager->GetObject();
				$oApp['context_manipulator']->PrepareObject($aActionRules, $oObj);
				$oFormManager->SetObject($oObj);
			}
			
			switch ($sOperation)
			{
				case 'submit':
					// Applying modification to object
					$aFormData['validation'] = $oFormManager->OnSubmit(array('currentValues' => $oApp['request_manipulator']->ReadParam('current_values', array(), FILTER_UNSAFE_RAW), 'attachmentIds' => $oApp['request_manipulator']->ReadParam('attachment_ids', array(), FILTER_UNSAFE_RAW), 'formProperties' => $aFormProperties, 'applyStimulus' => $oApp['request_manipulator']->ReadParam('apply_stimulus', null)));
					if ($aFormData['validation']['valid'] === true)
					{
						// Note : We don't use $sObjectId there as it can be null if we are creating a new one. Instead we use the id from the created object once it has been seralized
						// Check if stimulus has to be applied
                        $sStimulusCode = $oApp['request_manipulator']->ReadParam('stimulus_code', '');
						if (!empty($sStimulusCode))
						{
							$aFormData['validation']['redirection'] = array(
								'url' => $oApp['url_generator']->generate('p_object_apply_stimulus', array('sObjectClass' => $sObjectClass, 'sObjectId' => $oFormManager->GetObject()->GetKey(), 'sStimulusCode' => $sStimulusCode)),
								'ajax' => true
							);
						}
						// Otherwise, we show the object if there is no default
//						else
//						{
//							$aFormData['validation']['redirection'] = array(
//								'alternative_url' => $oApp['url_generator']->generate('p_object_edit', array('sObjectClass' => $sObjectClass, 'sObjectId' => $oFormManager->GetObject()->GetKey()))
//							);
//						}
					}
					break;

				case 'update':
					$oFormManager->OnUpdate(array('currentValues' => $oApp['request_manipulator']->ReadParam('current_values', array(), FILTER_UNSAFE_RAW), 'formProperties' => $aFormProperties));
					break;

				case 'cancel':
					$oFormManager->OnCancel();
					break;
			}
		}
		
		// Preparing field_set data
		$aFieldSetData = array(
			//'fields_list' => $oFormManager->GetRenderer()->Render(), // GLA : This should be done just after in the if statement.
			'fields_impacts' => $oFormManager->GetForm()->GetFieldsImpacts(),
			'form_path' => $oFormManager->GetForm()->GetId()
		);

		// Preparing fields list regarding the operation
		if ($sOperation === 'update')
		{
			$aRequestedFields = $oApp['request_manipulator']->ReadParam('requested_fields', array(), FILTER_UNSAFE_RAW);
			$sFormPath = $oApp['request_manipulator']->ReadParam('form_path', '');

			// Checking if the update was on a subform, if so we need to make the rendering for that part only
			if ( !empty($sFormPath) && $sFormPath !== $oFormManager->GetForm()->GetId() )
			{
				$oSubForm = $oFormManager->GetForm()->FindSubForm($sFormPath);
				$oSubFormRenderer = new BsFormRenderer($oSubForm);
				$oSubFormRenderer->SetEndpoint($oFormManager->GetRenderer()->GetEndpoint());
				$aFormData['updated_fields'] = $oSubFormRenderer->Render($aRequestedFields);
			}
			else
			{
				$aFormData['updated_fields'] = $oFormManager->GetRenderer()->Render($aRequestedFields);
			}
		}
		else
		{
			$aFieldSetData['fields_list'] = $oFormManager->GetRenderer()->Render();
		}

		// Preparing form data
		$aFormData['id'] = $oFormManager->GetForm()->GetId();
		$aFormData['transaction_id'] = $oFormManager->GetForm()->GetTransactionId();
		$aFormData['formmanager_class'] = $oFormManager->GetClass();
		$aFormData['formmanager_data'] = $oFormManager->ToJSON();
		$aFormData['renderer'] = $oFormManager->GetRenderer();
		$aFormData['object_name'] = $oFormManager->GetObject()->GetName();
		$aFormData['object_class'] = get_class($oFormManager->GetObject());
		$aFormData['object_id'] = $oFormManager->GetObject()->GetKey();
		$aFormData['object_state'] = $oFormManager->GetObject()->GetState();
		$aFormData['fieldset'] = $aFieldSetData;
        $aFormData['display_mode'] = (isset($aFormProperties['properties'])) ? $aFormProperties['properties']['display_mode'] : ApplicationHelper::FORM_DEFAULT_DISPLAY_MODE;

		return $aFormData;
	}

    /**
     * Handles the autocomplete search
     *
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param string $sTargetAttCode Attribute code of the host object pointing to the Object class to search
     * @param string $sHostObjectClass Class name of the host object
     * @param string $sHostObjectId Id of the host object
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \OQLException
     */
	public function SearchAutocompleteAction(Request $oRequest, Application $oApp, $sTargetAttCode, $sHostObjectClass, $sHostObjectId = null)
	{
		$aData = array(
			'results' => array(
				'count' => 0,
				'items' => array()
			)
		);

		// Parsing parameters from request payload
		parse_str($oRequest->getContent(), $aRequestContent);

		// Checking parameters
		if (!isset($aRequestContent['sQuery']))
		{
			IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Parameter sQuery missing.');
			$oApp->abort(500, Dict::Format('UI:Error:ParameterMissing', 'sQuery'));
		}

		// Retrieving parameters
		$sQuery = $aRequestContent['sQuery'];
		$sFieldId = $aRequestContent['sFieldId'];

		// Checking security layers
		if (!SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $sHostObjectClass, $sHostObjectId))
		{
			IssueLog::Warning(__METHOD__ . ' at line ' . __LINE__ . ' : Could not load object ' . $sHostObjectClass . '::' . $sHostObjectId . '.');
			$oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
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
            $sActionRulesToken = $oApp['request_manipulator']->ReadParam('ar_token', '');
			$aActionRules = (!empty($sActionRulesToken)) ? ContextManipulatorHelper::DecodeRulesToken($sActionRulesToken) : array();
			// Preparing object
			$oApp['context_manipulator']->PrepareObject($aActionRules, $oHostObject);
		}

		// Updating host object with form data / values
		$sFormManagerClass = $aRequestContent['formmanager_class'];
		$sFormManagerData = $aRequestContent['formmanager_data'];
		if (!empty($sFormManagerClass) && !empty($sFormManagerData))
		{
			$oFormManager = $sFormManagerClass::FromJSON($sFormManagerData);
			$oFormManager->SetApplication($oApp);
			$oFormManager->SetObject($oHostObject);

			// Applying action rules if present
			if (($oFormManager->GetActionRulesToken() !== null) && ($oFormManager->GetActionRulesToken() !== ''))
			{
				$aActionRules = ContextManipulatorHelper::DecodeRulesToken($oFormManager->GetActionRulesToken());
				$oObj = $oFormManager->GetObject();
				$oApp['context_manipulator']->PrepareObject($aActionRules, $oObj);
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
			$oTemplateFieldSearch = $oRequestTemplate->GetForm()->GetField('user_data')->GetForm()->GetField($sFieldId)->GetSearch();
			$sTargetObjectClass = $oTemplateFieldSearch->GetClass();
		}
		elseif ($oTargetAttDef->IsLinkSet())
		{
			throw new Exception('Search autocomplete cannot apply on AttributeLinkedSet objects, ' . get_class($oTargetAttDef) . ' (' . $sHostObjectClass . '->' . $sTargetAttCode . ') given.');
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
		$oSearch->AddConditionExpression(new BinaryExpression(new FieldExpression('friendlyname', $oSearch->GetClassAlias()), 'LIKE', new VariableExpression('ac_query')));
		// - Intersecting with scope constraints
		// Note : This do NOT apply to custom fields as the portal administrator is not supposed to know which objects will be put in the templates.
		// It is the responsability of the template designer to write the right query so the user see only what he should.
		if ($oTargetAttDef->GetEditClass() !== 'CustomFields')
		{
			$oScopeSearch = $oApp['scope_validator']->GetScopeFilterForProfiles(UserRights::ListProfiles(), $sTargetObjectClass, UR_ACTION_READ);
			$oSearch = $oSearch->Intersect($oScopeSearch);
			// - Allowing all data if necessary
			if ($oScopeSearch->IsAllDataAllowed())
			{
				$oSearch->AllowAllData();
			}
		}

		// Retrieving results
		// - Preparing object set
		$oSet = new DBObjectSet($oSearch, array(), array('this' => $oHostObject, 'ac_query' => '%' . $sQuery . '%'));
		$oSet->OptimizeColumnLoad(array($oSearch->GetClassAlias() => array('friendlyname')));
		// Note : This limit is also used in the field renderer by typeahead to determine how many suggestions to display
		if ($oTargetAttDef->GetEditClass() === 'CustomFields')
		{
			$oSet->SetLimit(static::DEFAULT_LIST_LENGTH);
		}
		else
		{
			$oSet->SetLimit($oTargetAttDef->GetMaximumComboLength()); // TODO : Is this the right limit value ? We might want to use another parameter
		}
		// - Retrieving objects
		while ($oItem = $oSet->Fetch())
		{
			$aData['results']['items'][] = array('id' => $oItem->GetKey(), 'name' => html_entity_decode($oItem->GetName(), ENT_QUOTES, 'UTF-8'));
			$aData['results']['count'] ++;
		}

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			$oResponse = $oApp->json($aData);
		}
		else
		{
			$oResponse = $oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
		}

		return $oResponse;
	}

    /**
     * Handles the regular (table) search from an attribute
     *
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param string $sTargetAttCode Attribute code of the host object pointing to the Object class to search
     * @param string $sHostObjectClass Class name of the host object
     * @param string $sHostObjectId Id of the host object
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \DictExceptionMissingString
     * @throws \OQLException
     */
	public function SearchFromAttributeAction(Request $oRequest, Application $oApp, $sTargetAttCode, $sHostObjectClass, $sHostObjectId = null)
	{
		$aData = array(
			'sMode' => 'search_regular',
			'sTargetAttCode' => $sTargetAttCode,
			'sHostObjectClass' => $sHostObjectClass,
			'sHostObjectId' => $sHostObjectId,
			'sActionRulesToken' => $oApp['request_manipulator']->ReadParam('ar_token', ''),
		);

		// Checking security layers
		if (!SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $sHostObjectClass, $sHostObjectId))
		{
			IssueLog::Warning(__METHOD__ . ' at line ' . __LINE__ . ' : User #' . UserRights::GetUserId() . ' not allowed to read ' . $sHostObjectClass . '::' . $sHostObjectId . ' object.');
			$oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
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
			$oApp['context_manipulator']->PrepareObject($aActionRules, $oHostObject);
		}

		// Updating host object with form data / values
		$sFormManagerClass = $oApp['request_manipulator']->ReadParam('formmanager_class', '', FILTER_UNSAFE_RAW);
		$sFormManagerData = $oApp['request_manipulator']->ReadParam('formmanager_data', '', FILTER_UNSAFE_RAW);
		if ( !empty($sFormManagerClass) && !empty($sFormManagerData) )
		{
			$oFormManager = $sFormManagerClass::FromJSON($sFormManagerData);
			$oFormManager->SetApplication($oApp);
			$oFormManager->SetObject($oHostObject);

			// Applying action rules if present
			if (($oFormManager->GetActionRulesToken() !== null) && ($oFormManager->GetActionRulesToken() !== ''))
			{
				$aActionRules = ContextManipulatorHelper::DecodeRulesToken($oFormManager->GetActionRulesToken());
				$oObj = $oFormManager->GetObject();
				$oApp['context_manipulator']->PrepareObject($aActionRules, $oObj);
				$oFormManager->SetObject($oObj);
			}
			
			// Updating host object
			$oFormManager->OnUpdate(array('currentValues' => $oApp['request_manipulator']->ReadParam('current_values', array(), FILTER_UNSAFE_RAW)));
			$oHostObject = $oFormManager->GetObject();
		}
		
		// Retrieving request parameters
		$iPageNumber = $oApp['request_manipulator']->ReadParam('iPageNumber', static::DEFAULT_PAGE_NUMBER, FILTER_SANITIZE_NUMBER_INT);
		$iListLength = $oApp['request_manipulator']->ReadParam('iListLength', static::DEFAULT_LIST_LENGTH, FILTER_SANITIZE_NUMBER_INT);
		$bInitalPass = $oApp['request_manipulator']->HasParam('draw') ? false : true;
		$sQuery = $oApp['request_manipulator']->ReadParam('sSearchValue', '');
		$sFormPath = $oApp['request_manipulator']->ReadParam('sFormPath', '');
		$sFieldId = $oApp['request_manipulator']->ReadParam('sFieldId', '');
		$aObjectIdsToIgnore = $oApp['request_manipulator']->ReadParam('aObjectIdsToIgnore', null, FILTER_UNSAFE_RAW);

		// Building search query
		// - Retrieving target object class from attcode
		$oTargetAttDef = MetaModel::GetAttributeDef($sHostObjectClass, $sTargetAttCode);
		if ($oTargetAttDef->IsExternalKey())
		{
			$sTargetObjectClass = $oTargetAttDef->GetTargetClass();
		}
		elseif ($oTargetAttDef->IsLinkSet())
		{
			if (!$oTargetAttDef->IsIndirect())
			{
				$sTargetObjectClass = $oTargetAttDef->GetLinkedClass();
			}
			else
			{
				$oRemoteAttDef = MetaModel::GetAttributeDef($oTargetAttDef->GetLinkedClass(), $oTargetAttDef->GetExtKeyToRemote());
				$sTargetObjectClass = $oRemoteAttDef->GetTargetClass();
			}
		}
		elseif ($oTargetAttDef->GetEditClass() === 'CustomFields')
		{
			$oRequestTemplate = $oHostObject->Get($sTargetAttCode);
			$oTemplateFieldSearch = $oRequestTemplate->GetForm()->GetField('user_data')->GetForm()->GetField($sFieldId)->GetSearch();
			$sTargetObjectClass = $oTemplateFieldSearch->GetClass();
		}
		else
		{
			throw new Exception('Search from attribute can only apply on AttributeExternalKey or AttributeLinkedSet objects, ' . get_class($oTargetAttDef) . ' given.');
		}
		
		// - Retrieving class attribute list
		$aAttCodes = ApplicationHelper::GetLoadedListFromClass($oApp, $sTargetObjectClass, 'list');
		// - Adding friendlyname attribute to the list is not already in it
		$sTitleAttCode = 'friendlyname';
		if (($sTitleAttCode !== null) && !in_array($sTitleAttCode, $aAttCodes))
		{
			$aAttCodes = array_merge(array($sTitleAttCode), $aAttCodes);
		}

		// - Retrieving scope search
		// Note : This do NOT apply to custom fields as the portal administrator is not supposed to know which objects will be put in the templates.
		// It is the responsability of the template designer to write the right query so the user see only what he should.
		$oScopeSearch = $oApp['scope_validator']->GetScopeFilterForProfiles(UserRights::ListProfiles(), $sTargetObjectClass, UR_ACTION_READ);
		$aInternalParams = array();
		if (($oScopeSearch === null) && ($oTargetAttDef->GetEditClass() !== 'CustomFields'))
		{
			IssueLog::Info(__METHOD__ . ' at line ' . __LINE__ . ' : User #' . UserRights::GetUserId() . ' has no scope query for ' . $sTargetObjectClass . ' class.');
			$oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
		}

		// - Base query from meta model
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
			$oSearch->AddConditionExpression(new BinaryExpression(new FieldExpression('id', $oSearch->GetClassAlias()), 'NOT IN', new ListExpression($aExpressions)));
		}
		
		// - Adding query condition
		$aInternalParams['this'] = $oHostObject;
		if (!empty($sQuery))
		{
			$oFullExpr = null;
			for ($i = 0; $i < count($aAttCodes); $i++)
			{
				// Checking if the current attcode is an external key in order to search on the friendlyname
				$oAttDef = MetaModel::GetAttributeDef($sTargetObjectClass, $aAttCodes[$i]);
				$sAttCode = (!$oAttDef->IsExternalKey()) ? $aAttCodes[$i] : $aAttCodes[$i] . '_friendlyname';
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
						$oBinExpr = new BinaryExpression(new FieldExpression($sAttCode, $oSearch->GetClassAlias()), 'IN', $oEnumeratedListExpr);
					}
					else
					{
						$oBinExpr = new FalseExpression();
					}
				}
				// - For regular attributs
				else
				{
					$oBinExpr = new BinaryExpression(new FieldExpression($sAttCode, $oSearch->GetClassAlias()), 'LIKE', new VariableExpression('re_query'));
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
			$aInternalParams['re_query'] = '%' . $sQuery . '%';
		}

		// - Intersecting with scope constraints
		// Note : This do NOT apply to custom fields as the portal administrator is not supposed to know which objects will be put in the templates.
		// It is the responsability of the template designer to write the right query so the user see only what he should.
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
				'title' => $oAttDef->GetLabel()
			);
		}
		// - Retrieving objects
		$aItems = array();
		while ($oItem = $oSet->Fetch())
		{
			$aItems[] = $this->PrepareObjectInformations($oApp, $oItem, $aAttCodes);
		}
		
		// Preparing response
		if ($bInitalPass)
		{
			$aData = $aData + array(
				'form' => array(
					'id' => 'object_search_form_' . time(),
					'title' => Dict::Format('Brick:Portal:Object:Search:Regular:Title', $oTargetAttDef->GetLabel(), MetaModel::GetName($sTargetObjectClass))
				),
				'aColumnProperties' => json_encode($aColumnProperties),
				'aResults' => array(
					'aItems' => json_encode($aItems),
					'iCount' => count($aItems)
				),
				'bMultipleSelect' => $oTargetAttDef->IsLinkSet(),
				'aSource' => array(
					'sFormPath' => $sFormPath,
					'sFieldId' => $sFieldId,
					'aObjectIdsToIgnore' => $aObjectIdsToIgnore,
					'sFormManagerClass' => $sFormManagerClass,
					'sFormManagerData' => $sFormManagerData
				)
			);
			
			if ($oRequest->isXmlHttpRequest())
			{
				$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/bricks/object/modal.html.twig', $aData);
			}
			else
			{
				//$oResponse = $oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
				$oResponse = $oApp['twig']->render('itop-portal-base/portal/src/views/bricks/object/layout.html.twig', $aData);
			}
		}
		else
		{
			$aData = $aData + array(
				'levelsProperties' => $aColumnProperties,
				'data' => $aItems,
				'recordsTotal' => $oSet->Count(),
				'recordsFiltered' => $oSet->Count()
			);

			$oResponse = $oApp->json($aData);
		}

		return $oResponse;
	}

    /**
     * Handles the hierarchical search from an attribute
     *
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param string $sTargetAttCode Attribute code of the host object pointing to the Object class to search
     * @param string $sHostObjectClass Class name of the host object
     * @param string $sHostObjectId Id of the host object
     *
     * @return void
     *
     */
	public function SearchHierarchyAction(Request $oRequest, Application $oApp, $sTargetAttCode, $sHostObjectClass, $sHostObjectId = null)
	{
		// TODO
	}

    /**
     * Handles ormDocument display / download from an object
     *
     * Note: This is inspired from pages/ajax.document.php, but duplicated as there is no secret mecanism for ormDocument yet.
     *
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param string $sOperation
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */
	public function DocumentAction(Request $oRequest, Application $oApp, $sOperation = null)
    {
        // Setting default operation
        if($sOperation === null)
        {
            $sOperation = 'display';
        }

        // Retrieving ormDocument's host object
        $sObjectClass = $oApp['request_manipulator']->ReadParam('sObjectClass', '');
        $sObjectId = $oApp['request_manipulator']->ReadParam('sObjectId', '');
        $sObjectField = $oApp['request_manipulator']->ReadParam('sObjectField', '');

        // When reaching to an Attachment, we have to check security on its host object instead of the Attachment itself
        if($sObjectClass === 'Attachment')
        {
            $oAttachment = MetaModel::GetObject($sObjectClass, $sObjectId, true, true);
            $sHostClass = $oAttachment->Get('item_class');
            $sHostId = $oAttachment->Get('item_id');
        }
        else
        {
            $sHostClass = $sObjectClass;
            $sHostId = $sObjectId;
        }

        // Checking security layers
	    // Note: Checking if host object already exists as we can try to download document from an object that is being created
        if (($sHostId > 0) && !SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $sHostClass, $sHostId))
        {
            IssueLog::Warning(__METHOD__ . ' at line ' . __LINE__ . ' : User #' . UserRights::GetUserId() . ' not allowed to retrieve document from attribute ' . $sObjectField . ' as it not allowed to read ' . $sHostClass . '::' . $sHostId . ' object.');
            $oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
        }

        // Retrieving object
        $oObject = MetaModel::GetObject($sObjectClass, $sObjectId, false /* Must not be found */, $oApp['scope_validator']->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sHostClass));
        if ($oObject === null)
        {
            // We should never be there as the secuirty helper makes sure that the object exists, but just in case.
            IssueLog::Info(__METHOD__ . ' at line ' . __LINE__ . ' : Could not load object ' . $sObjectClass . '::' . $sObjectId . '.');
            $oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
        }

        // Setting cache timeout
        // Note: Attachment download should be handle through AttachmentAction()
        if($sObjectClass === 'Attachment')
        {
            // One year ahead: an attachement cannot change
            $iCacheSec = 31556926;
        }
        else
        {
            $iCacheSec = $oApp['request_manipulator']->ReadParam('cache', 0, FILTER_SANITIZE_NUMBER_INT);
        }

        $aHeaders = array();
        if($iCacheSec > 0)
        {
            $aHeaders['Expires'] = '';
            $aHeaders['Cache-Control'] = 'no-transform, public,max-age='.$iCacheSec.',s-maxage='.$iCacheSec;
            // Reset the value set previously
            $aHeaders['Pragma'] = 'cache';
            // An arbitrary date in the past is ok
            $aHeaders['Last-Modified'] = 'Wed, 15 Jun 2015 13:21:15 GMT';
        }

        /** @var \ormDocument $oDocument */
        $oDocument = $oObject->Get($sObjectField);
        $aHeaders['Content-Type'] = $oDocument->GetMimeType();
        $aHeaders['Content-Disposition'] = (($sOperation === 'display') ? 'inline' : 'attachment') . ';filename="'.$oDocument->GetFileName().'"';

        return new Response($oDocument->GetData(), Response::HTTP_OK, $aHeaders);
    }

    /**
     * Handles attachment add/remove on an object
     *
     * Note: This is inspired from itop-attachment/ajax.attachment.php
     *
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Silex\Application $oApp
     * @param string $sOperation
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     */
	public function AttachmentAction(Request $oRequest, Application $oApp, $sOperation = null)
	{
		$aData = array(
			'att_id' => 0,
			'preview' => false,
			'msg' => ''
		);

		// Retrieving sOperation from request only if it wasn't forced (determined by the route)
		if ($sOperation === null)
		{
			$sOperation = $oApp['request_manipulator']->ReadParam('operation', null);
		}
		switch ($sOperation)
		{
			case 'add':
				$sFieldName = $oApp['request_manipulator']->ReadParam('field_name', '');
				$sObjectClass = $oApp['request_manipulator']->ReadParam('object_class', '');
				$sTempId = $oApp['request_manipulator']->ReadParam('temp_id', '');

				if (empty($sObjectClass) || empty($sTempId))
				{
					$aData['error'] = Dict::Format('UI:Error:2ParametersMissing', 'object_class', 'temp_id');
				}
				else
				{
					try
					{
						$oDocument = utils::ReadPostedDocument($sFieldName);
						$oAttachment = MetaModel::NewObject('Attachment');
						$oAttachment->Set('expire', time() + MetaModel::GetConfig()->Get('draft_attachments_lifetime')); // one hour...
						$oAttachment->Set('temp_id', $sTempId);
						$oAttachment->Set('item_class', $sObjectClass);
						$oAttachment->SetDefaultOrgId();
						$oAttachment->Set('contents', $oDocument);
						$iAttId = $oAttachment->DBInsert();

						$aData['msg'] = htmlentities($oDocument->GetFileName(), ENT_QUOTES, 'UTF-8');
						// TODO : Change icon location when itop-attachment is refactored
						//$aData['icon'] = utils::GetAbsoluteUrlAppRoot() . AttachmentPlugIn::GetFileIcon($oDoc->GetFileName());
						$aData['icon'] = utils::GetAbsoluteUrlAppRoot() . 'env-' . utils::GetCurrentEnvironment() . '/itop-attachments/icons/image.png';
						$aData['att_id'] = $iAttId;
						$aData['preview'] = $oDocument->IsPreviewAvailable() ? 'true' : 'false';
					}
					catch (FileUploadException $e)
					{
						$aData['error'] = $e->GetMessage();
					}
				}

				// Note : The Content-Type header is set to 'text/plain' in order to be IE9 compatible. Otherwise ('application/json') IE9 will download the response as a JSON file to the user computer...
				$oResponse = $oApp->json($aData, 200, array('Content-Type' => 'text/plain'));
				break;

			case 'download':
				// Preparing redirection
                // - Route
                $aRouteParams = array(
                    'sObjectClass' => 'Attachment',
                    'sObjectId' => $oApp['request_manipulator']->ReadParam('sAttachmentId', null),
                    'sObjectField' => 'contents',
                );
                $sRedirectRoute = $oApp['url_generator']->generate('p_object_document_download', $aRouteParams);
                // - Request
                $oSubRequest = Request::create($sRedirectRoute, 'GET', $oRequest->query->all(), $oRequest->cookies->all(), array(), $oRequest->server->all());

                $oResponse = $oApp->handle($oSubRequest, HttpKernelInterface::SUB_REQUEST, true);
				break;

			default:
				$oApp->abort(403, Dict::S('Error:HTTP:400'));
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
     * @param \Silex\Application $oApp
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \OQLException
     * @throws \CoreException
     */
	public function GetInformationsAsJsonAction(Request $oRequest, Application $oApp)
	{
		$aData = array();

		// Retrieving parameters
		$sObjectClass = $oApp['request_manipulator']->ReadParam('sObjectClass', '');
		$aObjectIds = $oApp['request_manipulator']->ReadParam('aObjectIds', array(), FILTER_UNSAFE_RAW);
		$aObjectAttCodes = $oApp['request_manipulator']->ReadParam('aObjectAttCodes', array(), FILTER_UNSAFE_RAW);
		if ( empty($sObjectClass) || empty($aObjectIds) || empty($aObjectAttCodes) )
		{
			IssueLog::Info(__METHOD__ . ' at line ' . __LINE__ . ' : sObjectClass, aObjectIds and aObjectAttCodes expected, "' . $sObjectClass . '", "' . implode('/', $aObjectIds) . '" given.');
			$oApp->abort(500, 'Invalid request data, some informations are missing');
		}

		// Checking that id is in the AttCodes
		if (!in_array('id', $aObjectAttCodes))
		{
			$aObjectAttCodes = array_merge(array('id'), $aObjectAttCodes);
		}

		// Building the search
		$bIgnoreSilos = $oApp['scope_validator']->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass);
		$oSearch = DBObjectSearch::FromOQL("SELECT " . $sObjectClass . " WHERE id IN ('" . implode("','", $aObjectIds) . "')");
		if ($bIgnoreSilos === true)
		{
			$oSearch->AllowAllData();
		}
		$oSet = new DBObjectSet($oSearch);
		$oSet->OptimizeColumnLoad($aObjectAttCodes);

		// Retrieving objects
		while ($oObject = $oSet->Fetch())
		{
			$aData['items'][] = $this->PrepareObjectInformations($oApp, $oObject, $aObjectAttCodes);
		}

		return $oApp->json($aData);
	}

    /**
     * Prepare a DBObject informations as an array for a client side usage (typically, add a row in a table)
     *
     * @param \Silex\Application $oApp
     * @param \DBObject $oObject
     * @param array $aAttCodes
     *
     * @return array
     *
     * @throws \Exception
     * @throws \CoreException
     */
	protected function PrepareObjectInformations(Application $oApp, DBObject $oObject, $aAttCodes = array())
	{
		$sObjectClass = get_class($oObject);
		$aObjectData = array(
			'id' => $oObject->GetKey(),
			'name' => $oObject->GetName(),
			'attributes' => array(),
		);

		// Retrieving attributes definitions
		$aAttDefs = array();
		foreach ($aAttCodes as $sAttCode)
		{
			if ($sAttCode === 'id')
				continue;

			$aAttDefs[$sAttCode] = MetaModel::GetAttributeDef($sObjectClass, $sAttCode);
		}

		// Preparing attribute data
		foreach ($aAttDefs as $oAttDef)
		{
			$aAttData = array(
				'att_code' => $oAttDef->GetCode()
			);

			if ($oAttDef->IsExternalKey())
			{
				$aAttData['value'] = $oObject->GetAsHTML($oAttDef->GetCode() . '_friendlyname');

				// Checking if user can access object's external key
				if (SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $oAttDef->GetTargetClass()))
				{
					$aAttData['url'] = $oApp['url_generator']->generate('p_object_view', array('sObjectClass' => $oAttDef->GetTargetClass(), 'sObjectId' => $oObject->Get($oAttDef->GetCode())));
				}
			}
			elseif ($oAttDef->IsLinkSet())
			{
				// We skip it
				continue;
			}
			elseif ($oAttDef instanceof AttributeImage)
            {
                $oOrmDoc = $oObject->Get($oAttDef->GetCode());
                if (is_object($oOrmDoc) && !$oOrmDoc->IsEmpty())
                {
                    $sUrl = $oApp['url_generator']->generate('p_object_document_display', array('sObjectClass' => get_class($oObject), 'sObjectId' => $oObject->GetKey(), 'sObjectField' => $oAttDef->GetCode(), 'cache' => 86400));
                }
                else
                {
                    $sUrl = $oAttDef->Get('default_image');
                }
                $aAttData['value'] = '<img src="' . $sUrl . '" />';
            }
			else
			{
				$aAttData['value'] = $oAttDef->GetAsHTML($oObject->Get($oAttDef->GetCode()));

				if ($oAttDef instanceof AttributeFriendlyName)
				{
					// Checking if user can access object
					if(SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $sObjectClass))
					{
						$aAttData['url'] = $oApp['url_generator']->generate('p_object_view', array('sObjectClass' => $sObjectClass, 'sObjectId' => $oObject->GetKey()));
					}
				}
			}

			$aObjectData['attributes'][$oAttDef->GetCode()] = $aAttData;
		}

		return $aObjectData;
	}

}
