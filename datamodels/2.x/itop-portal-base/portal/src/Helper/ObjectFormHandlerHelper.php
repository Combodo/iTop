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

use Combodo\iTop\Portal\Form\ObjectFormManager;
use Combodo\iTop\Portal\Twig\AppExtension;
use Combodo\iTop\Renderer\Bootstrap\BsFormRenderer;
use DBObjectSet;
use Dict;
use iPopupMenuExtension;
use IssueLog;
use JSButtonItem;
use MetaModel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig_Environment;
use Twig_Loader_Array;
use URLButtonItem;
use UserRights;

/**
 * Class ObjectFormHandlerHelper
 *
 * @package Combodo\iTop\Portal\Helper
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 2.7.0
 */
class ObjectFormHandlerHelper
{
	const ENUM_MODE_VIEW = 'view';
	const ENUM_MODE_EDIT = 'edit';
	const ENUM_MODE_CREATE = 'create';

	/** @var \Combodo\iTop\Portal\Helper\RequestManipulatorHelper */
	private $oRequestManipulator;
	/** @var \Combodo\iTop\Portal\Helper\ContextManipulatorHelper */
	private $oContextManipulator;
	/** @var \Combodo\iTop\Portal\Helper\ScopeValidatorHelper */
	private $oScopeValidator;
	/** @var \Combodo\iTop\Portal\Helper\SecurityHelper */
	private $oSecurityHelper;
	/** @var \Combodo\iTop\Portal\Routing\UrlGenerator */
	private $oUrlGenerator;
	/** @var array */
	private $aCombodoPortalInstanceConf;
	/** @var string $sPortalId */
	private $sPortalId;
	/** @var \Combodo\iTop\Portal\Twig\AppExtension */
	private $oAppExtension;
	/** @var \Symfony\Component\DependencyInjection\ContainerInterface */
	private $oContainer;

	/**
	 * ObjectFormHandlerHelper constructor.
	 *
	 * @param \Combodo\iTop\Portal\Helper\RequestManipulatorHelper       $oRequestManipulator
	 * @param \Combodo\iTop\Portal\Helper\ContextManipulatorHelper       $oContextManipulator
	 * @param \Combodo\iTop\Portal\Helper\ScopeValidatorHelper           $oScopeValidator
	 * @param \Combodo\iTop\Portal\Helper\SecurityHelper                 $oSecurityHelper
	 * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $oUrlGenerator
	 * @param array                                                      $aCombodoPortalInstanceConf
	 * @param string                                                     $sPortalId
	 * @param \Combodo\iTop\Portal\Twig\AppExtension                     $oAppExtension
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface  $oContainer
	 */
	public function __construct(RequestManipulatorHelper $oRequestManipulator, ContextManipulatorHelper $oContextManipulator, ScopeValidatorHelper $oScopeValidator, SecurityHelper $oSecurityHelper, UrlGeneratorInterface $oUrlGenerator, $aCombodoPortalInstanceConf, $sPortalId, AppExtension $oAppExtension, ContainerInterface $oContainer)
	{
		$this->oRequestManipulator = $oRequestManipulator;
		$this->oContextManipulator = $oContextManipulator;
		$this->oScopeValidator = $oScopeValidator;
		$this->oSecurityHelper = $oSecurityHelper;
		$this->oUrlGenerator = $oUrlGenerator;
		$this->aCombodoPortalInstanceConf = $aCombodoPortalInstanceConf;
		$this->sPortalId = $sPortalId;
		$this->oAppExtension = $oAppExtension;
		$this->oContainer = $oContainer;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sMode
	 * @param string                                    $sObjectClass
	 * @param string                                    $sObjectId
	 * @param string                                    $aFormProperties
	 *
	 * @return array
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function HandleForm(Request $oRequest, $sMode, $sObjectClass, $sObjectId = null, $aFormProperties = null)
	{
		$aFormData = array();
		$sOperation = $this->oRequestManipulator->ReadParam('operation', '');
		$bModal = ($oRequest->isXmlHttpRequest() && empty($sOperation));

		// - Retrieve form properties
		if ($aFormProperties === null)
		{
			$aFormProperties = ApplicationHelper::GetLoadedFormFromClass($this->aCombodoPortalInstanceConf['forms'], $sObjectClass, $sMode);
		}

		// - Create and
		if (empty($sOperation))
		{
			// Retrieving action rules
			//
			// Note : The action rules must be a base64-encoded JSON object, this is just so users are tempted to changes values.
			// But it would not be a security issue as it only presets values in the form.
			$sActionRulesToken = $this->oRequestManipulator->ReadParam('ar_token', '');
			$aActionRules = (!empty($sActionRulesToken)) ? ContextManipulatorHelper::DecodeRulesToken($sActionRulesToken) : array();

			// Preparing object
			if ($sObjectId === null)
			{
				// Create new UserRequest
				$oObject = MetaModel::NewObject($sObjectClass);

				// Retrieve action rules information to auto-fill the form if available
				// Preparing object
				$this->oContextManipulator->PrepareObject($aActionRules, $oObject);
				$aPrefillFormParam = array( 'user' => $_SESSION["auth_user"],
					'origin' => 'portal');
				$oObject->PrefillForm('creation_from_0', $aPrefillFormParam);
			}
			else
			{
				$oObject = MetaModel::GetObject($sObjectClass, $sObjectId, true, $this->oScopeValidator->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass));
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
					if($this->oSecurityHelper->IsStimulusAllowed($sStimulusCode, $sObjectClass, $oSetToCheckRights))
					{
						$aFormData['buttons']['transitions'][$sStimulusCode] = $aStimuli[$sStimulusCode]->GetLabel();
					}
				}

				// Add plugin buttons
				/** @var \iPopupMenuExtension $oExtensionInstance */
				foreach (MetaModel::EnumPlugins('iPopupMenuExtension') as $oExtensionInstance)
				{
					foreach($oExtensionInstance->EnumItems(iPopupMenuExtension::PORTAL_OBJDETAILS_ACTIONS, array('portal_id' => $this->sPortalId, 'object' => $oObject, 'mode' => $sMode)) as $oMenuItem)
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
					'stimulus' => $this->oRequestManipulator->ReadParam('apply_stimulus', null)['code'],
				);
				$oObject->PrefillForm('state_change', $aPrefillFormParam);
			}

			// Preparing callback urls
			$aCallbackUrls = $this->oContextManipulator->GetCallbackUrls($aActionRules, $oObject, $bModal);
			$aFormData['submit_callback'] = $aCallbackUrls['submit'];
			$aFormData['cancel_callback'] = $aCallbackUrls['cancel'];

			// Preparing renderer
			// Note : We might need to distinguish form & renderer endpoints
			if (in_array($sMode, array('create', 'edit', 'view')))
			{
				$sFormEndpoint = $this->oUrlGenerator->generate('p_object_' . $sMode, array('sObjectClass' => $sObjectClass, 'sObjectId' => $sObjectId));
			}
			else
			{
				$sFormEndpoint = $_SERVER['REQUEST_URI'];
			}
			$oFormRenderer = new BsFormRenderer();
			$oFormRenderer->SetEndpoint($sFormEndpoint);

			$oFormManager = new ObjectFormManager();
			$oFormManager->SetContainer($this->oContainer)
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
			$sFormManagerClass = $this->oRequestManipulator->ReadParam('formmanager_class', '', FILTER_UNSAFE_RAW);
			$sFormManagerData = $this->oRequestManipulator->ReadParam('formmanager_data', '', FILTER_UNSAFE_RAW);
			if ( empty($sFormManagerClass) || empty($sFormManagerData) )
			{
				IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Parameters formmanager_class and formamanager_data must be defined.');
				throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Parameters formmanager_class and formmanager_data must be defined.');
			}

			/** @var \Combodo\iTop\Portal\Form\ObjectFormManager $oFormManager */
			$oFormManager = $sFormManagerClass::FromJSON($sFormManagerData);
			$oFormManager->SetContainer($this->oContainer);

			// Applying action rules if present
			if (($oFormManager->GetActionRulesToken() !== null) && ($oFormManager->GetActionRulesToken() !== ''))
			{
				$aActionRules = ContextManipulatorHelper::DecodeRulesToken($oFormManager->GetActionRulesToken());
				$oObj = $oFormManager->GetObject();
				$this->oContextManipulator->PrepareObject($aActionRules, $oObj);
				$oFormManager->SetObject($oObj);
			}

			switch ($sOperation)
			{
				case 'submit':
					// Applying modification to object
					$aFormData['validation'] = $oFormManager->OnSubmit(
						array(
							'currentValues' => $this->oRequestManipulator->ReadParam('current_values', array(), FILTER_UNSAFE_RAW),
							'attachmentIds' => $this->oRequestManipulator->ReadParam('attachment_ids', array(), FILTER_UNSAFE_RAW),
							'formProperties' => $aFormProperties,
							'applyStimulus' => $this->oRequestManipulator->ReadParam('apply_stimulus', null)
						)
					);
					if ($aFormData['validation']['valid'] === true)
					{
						// Note : We don't use $sObjectId there as it can be null if we are creating a new one. Instead we use the id from the created object once it has been seralized
						// Check if stimulus has to be applied
						$sStimulusCode = $this->oRequestManipulator->ReadParam('stimulus_code', '');
						if (!empty($sStimulusCode))
						{
							$aFormData['validation']['redirection'] = array(
								'url' => $this->oUrlGenerator->generate('p_object_apply_stimulus', array('sObjectClass' => $sObjectClass, 'sObjectId' => $oFormManager->GetObject()->GetKey(), 'sStimulusCode' => $sStimulusCode)),
								'ajax' => true,
							);
						}
						// Otherwise, we show the object if there is no default
//						else
//						{
//							$aFormData['validation']['redirection'] = array(
//								'alternative_url' => $this->oUrlGenerator->generate('p_object_edit', array('sObjectClass' => $sObjectClass, 'sObjectId' => $oFormManager->GetObject()->GetKey()))
//							);
//						}
					}
					break;

				case 'update':
					$oFormManager->OnUpdate(array('currentValues' => $this->oRequestManipulator->ReadParam('current_values', array(), FILTER_UNSAFE_RAW), 'formProperties' => $aFormProperties));
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
			'form_path' => $oFormManager->GetForm()->GetId(),
		);

		// Preparing fields list regarding the operation
		if ($sOperation === 'update')
		{
			$aRequestedFields = $this->oRequestManipulator->ReadParam('requested_fields', array(), FILTER_UNSAFE_RAW);
			$sFormPath = $this->oRequestManipulator->ReadParam('form_path', '');

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

	public function RenderFormFromTwig($sId, $sTwigString, $aData)
	{
		// Creating sandbox twig env. to load and test the custom form template
		$oTwig = new Twig_Environment(new Twig_Loader_Array( array($sId => $sTwigString) ));

		// Manually registering filters and functions as we didn't find how to do it automatically
		$aFilters = $this->oAppExtension->getFilters();
		foreach($aFilters as $oFilter)
		{
			$oTwig->addFilter($oFilter);
		}
		$aFunctions = $this->oAppExtension->getFunctions();
		foreach($aFunctions as $oFunction)
		{
			$oTwig->addFunction($oFunction);
		}

		return $oTwig->render($sId, $aData);
	}
}