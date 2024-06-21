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

namespace Combodo\iTop\Portal\Form;

use AttachmentPlugIn;
use AttributeDateTime;
use AttributeSet;
use CMDBChangeOpAttachmentAdded;
use CMDBChangeOpAttachmentRemoved;
use Combodo\iTop\Form\Field\Field;
use Combodo\iTop\Form\Field\FileUploadField;
use Combodo\iTop\Form\Field\LabelField;
use Combodo\iTop\Form\Form;
use Combodo\iTop\Form\FormManager;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use Combodo\iTop\Portal\Helper\ObjectFormHandlerHelper;
use CoreCannotSaveObjectException;
use DBObject;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Dict;
use DOMDocument;
use DOMXPath;
use Exception;
use ExceptionLog;
use InlineImage;
use InvalidExternalKeyValueException;
use IssueLog;
use LogChannels;
use MetaModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use UserRights;
use utils;
use const UR_ACTION_READ;

/**
 * Description of ObjectFormManager
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since  2.3.0
 */
class ObjectFormManager extends FormManager
{
	/** @var string ENUM_MODE_VIEW */
	const ENUM_MODE_VIEW = 'view';
	/** @var string ENUM_MODE_EDIT */
	const ENUM_MODE_EDIT = 'edit';
	/** @var string ENUM_MODE_CREATE */
	const ENUM_MODE_CREATE = 'create';
	/** @var string ENUM_MODE_APPLY_STIMULUS */
	const ENUM_MODE_APPLY_STIMULUS = 'apply_stimulus';

	/** @var \cmdbAbstractObject $oObject */
	protected $oObject;
	/** @var string $sMode */
	protected $sMode;
	/** @var string $sActionRulesToken */
	protected $sActionRulesToken;
	/** @var array $aFormProperties */
	protected $aFormProperties;
	/** @var array $aCallbackUrls */
	protected $aCallbackUrls = array();
	/**
	 * List of hidden fields, used for form update (eg. remove them from the form regarding they dependencies)
	 *
	 * @var array $aHiddenFieldsId
	 * @since 2.7.5
	 */
	protected $aHiddenFieldsId = array();

	/**
	 * @var ObjectFormHandlerHelper $oFormHandlerHelper
	 * @since 3.1.0 Replace container. Allow access to others applications services.
	 */
	private $oFormHandlerHelper;


	/**
	 * @param string|array $formManagerData value of the formmanager_data portal parameter, either JSON or object
	 *
	 * @return array formmanager_data as a PHP array
	 *
	 * @since 2.7.6 3.0.0 N°4384 method creation : factorize as this is used twice now
	 * @since 2.7.7 3.0.1 N°4867 now only used once, but we decided to keep this method anyway
	 */
	public static function DecodeFormManagerData($formManagerData)
	{
		if (is_array($formManagerData)) {
			return $formManagerData;
		}

		return json_decode($formManagerData, true);
	}

	/**
	 * @param string $sJson JSON data that must contain at least :
	 *       - formobject_class : The class of the object that is being edited/viewed
	 *       - formmode : view|edit|create
	 *       - values for parent
	 *
	 * @return \Combodo\iTop\Portal\Form\ObjectFormManager new instance init from JSON data
	 *
	 * @inheritDoc
	 * @throws \Exception
	 * @since 2.7.6 3.0.0 N°4384 new $bTrustContent parameter
	 * @since 2.7.7 3.0.1 N°4867 remove param $bTrustContent
	 */
	public static function FromJSON($sJson)
	{
		$aJson = static::DecodeFormManagerData($sJson);

		/** @var \Combodo\iTop\Portal\Form\ObjectFormManager $oFormManager */
		$oFormManager = parent::FromJSON($sJson);

		// Retrieving object to edit
		if (!isset($aJson['formobject_class'])) {
			throw new Exception('Object class must be defined in order to generate the form');
		}
		$sObjectClass = $aJson['formobject_class'];

		if (!isset($aJson['formobject_id']))
		{
			$oObject = MetaModel::NewObject($sObjectClass);
		}
		else
		{
			// Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
			$oObject = MetaModel::GetObject($sObjectClass, $aJson['formobject_id'], true, true);
		}
		$oFormManager->SetObject($oObject);

		// Retrieving form mode
		if (!isset($aJson['formmode']))
		{
			throw new Exception('Form mode must be defined in order to generate the form');
		}
		$oFormManager->SetMode($aJson['formmode']);

		// Retrieving actions rules
		if (isset($aJson['formactionrulestoken']))
		{
			$oFormManager->SetActionRulesToken($aJson['formactionrulestoken']);
		}

		// Retrieving form properties
		if (isset($aJson['formproperties']))
		{
			// As empty array are no passed through HTTP, this one is not always present and we have to ensure it is.
			if (!isset($aJson['formproperties']['fields']))
			{
				$aJson['formproperties']['fields'] = array();
			}
			$oFormManager->SetFormProperties($aJson['formproperties']);
		}

		// Retrieving callback urls
		if (!isset($aJson['formcallbacks'])) {
			// TODO
		}

		return $oFormManager;
	}

	/**
	 * @param \Combodo\iTop\Portal\Helper\ObjectFormHandlerHelper $oFormHandlerHelper
	 *
	 * @return $this
	 * @since 3.1.0
	 *
	 */
	public function SetObjectFormHandlerHelper(ObjectFormHandlerHelper $oFormHandlerHelper)
	{
		$this->oFormHandlerHelper = $oFormHandlerHelper;

		return $this;
	}

	/**
	 *
	 * @return \DBObject
	 */
	public function GetObject()
	{
		return $this->oObject;
	}

	/**
	 *
	 * @param \DBObject $oObject
	 *
	 * @return $this
	 */
	public function SetObject(DBObject $oObject)
	{
		$this->oObject = $oObject;

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function GetMode()
	{
		return $this->sMode;
	}

	/**
	 *
	 * @param string $sMode
	 *
	 * @return $this
	 */
	public function SetMode($sMode)
	{
		$this->sMode = $sMode;

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function GetActionRulesToken()
	{
		return $this->sActionRulesToken;
	}

	/**
	 *
	 * @param string $sActionRulesToken
	 *
	 * @return $this
	 */
	public function SetActionRulesToken($sActionRulesToken)
	{
		$this->sActionRulesToken = $sActionRulesToken;

		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function GetFormProperties()
	{
		return $this->aFormProperties;
	}

	/**
	 *
	 * @param array $aFormProperties
	 *
	 * @return $this
	 */
	public function SetFormProperties($aFormProperties)
	{
		$this->aFormProperties = $aFormProperties;

		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function GetCallbackUrls()
	{
		return $this->aCallbackUrls;
	}

	/**
	 *
	 * @param array $aCallbackUrls
	 *
	 * @return $this
	 */
	public function SetCallbackUrls($aCallbackUrls)
	{
		$this->aCallbackUrls = $aCallbackUrls;

		return $this;
	}

	/**
	 * Returns if the form manager is handling a transition form instead of a state form.
	 *
	 * @return bool
	 */
	public function IsTransitionForm()
	{
		return ($this->sMode === static::ENUM_MODE_APPLY_STIMULUS);
	}

	/**
	 * @inheritDoc
	 */
	public function ToJSON()
	{
		$aJson = parent::ToJSON();
		$aJson['formobject_class'] = get_class($this->oObject);
		if ($this->oObject->GetKey() > 0)
		{
			$aJson['formobject_id'] = $this->oObject->GetKey();
		}
		$aJson['formmode'] = $this->sMode;
		$aJson['formactionrulestoken'] = $this->sActionRulesToken;
		$aJson['formproperties'] = $this->aFormProperties;

		return $aJson;
	}

	/**
	 * @inheritDoc
	 * @throws \CoreException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function Build()
	{
		$sObjectClass = get_class($this->oObject);

		$aFieldsAtts = array();
		$aFieldsDMOnlyAttCodes = array();
		$aFieldsExtraData = array();

		if ($this->oForm !== null)
		{
			$oForm = $this->oForm;
		}
		else
		{
			$aFormId = 'objectform-'.((isset($this->aFormProperties['id'])) ? $this->aFormProperties['id'] : 'default').'-'.uniqid();
			$oForm = new Form($aFormId);
			$oForm->SetTransactionId(utils::GetNewTransactionId());
		}

		// Building form from its properties
		// - Consistency checks for stimulus form
		if (isset($this->aFormProperties['stimulus_code']))
		{
			$aTransitions = MetaModel::EnumTransitions($sObjectClass, $this->oObject->GetState());
			if (!isset($aTransitions[$this->aFormProperties['stimulus_code']]))
			{
				$aStimuli = Metamodel::EnumStimuli($sObjectClass);
				$sStimulusLabel = $aStimuli[$this->aFormProperties['stimulus_code']]->GetLabel();

				$sExceptionMessage = Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulusLabel, $this->oObject->GetName(), $this->oObject->GetStateLabel());
				throw new Exception($sExceptionMessage);
			}
		}
		// - The fields
		switch ($this->aFormProperties['type'])
		{
			case 'custom_list':
			case 'static':
				foreach ($this->aFormProperties['fields'] as $sAttCode => $aOptions)
				{
					// When in a transition and no flags are specified for the field, we will retrieve its flags from DM later
					if ($this->IsTransitionForm() && empty($aOptions))
					{
						$aFieldsDMOnlyAttCodes[] = $sAttCode;
						continue;
					}

					// Otherwise we proceed as usual
					$iFieldFlags = OPT_ATT_NORMAL;
					// Checking if field should be slave
					if (isset($aOptions['slave']) && ($aOptions['slave'] === true))
					{
						$iFieldFlags = $iFieldFlags | OPT_ATT_SLAVE;
					}
					// Checking if field should be must_change
					if (isset($aOptions['must_change']) && ($aOptions['must_change'] === true))
					{
						$iFieldFlags = $iFieldFlags | OPT_ATT_MUSTCHANGE;
					}
					// Checking if field should be must prompt
					if (isset($aOptions['must_prompt']) && ($aOptions['must_prompt'] === true))
					{
						$iFieldFlags = $iFieldFlags | OPT_ATT_MUSTPROMPT;
					}
					// Checking if field should be hidden
					if (isset($aOptions['hidden']) && ($aOptions['hidden'] === true))
					{
						$iFieldFlags = $iFieldFlags | OPT_ATT_HIDDEN;
					}
					// Checking if field should be readonly
					if (isset($aOptions['read_only']) && ($aOptions['read_only'] === true))
					{
						$iFieldFlags = $iFieldFlags | OPT_ATT_READONLY;
					}
					// Checking if field should be mandatory
					if (isset($aOptions['mandatory']) && ($aOptions['mandatory'] === true))
					{
						$iFieldFlags = $iFieldFlags | OPT_ATT_MANDATORY;
					}
					// Finally, adding the attribute and its flags
					$aFieldsAtts[$sAttCode] = $iFieldFlags;
				}
				break;

			case 'zlist':
				foreach (MetaModel::FlattenZList(MetaModel::GetZListItems($sObjectClass, $this->aFormProperties['fields'])) as $sAttCode)
				{
					$aFieldsAtts[$sAttCode] = OPT_ATT_NORMAL;
				}
				break;
		}
		// - The layout
		if ($this->aFormProperties['layout'] !== null) {
			// Checking if we need to render the template from twig to html in order to parse the fields
			if ($this->aFormProperties['layout']['type'] === 'twig') {

				if ($this->oFormHandlerHelper !== null) {
					/** @var \Combodo\iTop\Portal\Helper\ObjectFormHandlerHelper $oObjectFormHandler */
					$sRendered = $this->oFormHandlerHelper->RenderFormFromTwig(
						$oForm->GetId(),
						$this->aFormProperties['layout']['content'],
						array('oRenderer' => $this->oRenderer, 'oObject' => $this->oObject)
					);
				} else {
					$sRendered = 'Form not rendered because of missing container';
				}
			} else {
				$sRendered = $this->aFormProperties['layout']['content'];
			}

			// Parsing rendered template to find the fields
			$oHtmlDocument = new DOMDocument();
			// Note: Loading as XML instead of HTML avoid some encoding issues (eg. 'é' was transformed to '&tilde;&copy;')
			$oHtmlDocument->loadXML('<root>'.$sRendered.'</root>');

			// Adding fields to the list
			$oXPath = new DOMXPath($oHtmlDocument);
			/** @var \DOMElement $oFieldNode */
			foreach ($oXPath->query('//div[contains(@class, "form_field")][@data-field-id]') as $oFieldNode)
			{
				$sFieldId = $oFieldNode->getAttribute('data-field-id');
				$sFieldFlags = $oFieldNode->getAttribute('data-field-flags');
				$iFieldFlags = OPT_ATT_NORMAL;

				// When in a transition and no flags are specified for the field, we will retrieve its flags from DM later
				if ($this->IsTransitionForm() && $sFieldFlags === '')
				{
					// (Might have already been added from the "fields" property)
					if (!in_array($sFieldId, $aFieldsDMOnlyAttCodes))
					{
						$aFieldsDMOnlyAttCodes[] = $sFieldId;
					}
					continue;
				}

				// Otherwise we proceed as usual
				foreach (explode(' ', $sFieldFlags) as $sFieldFlag)
				{
					if ($sFieldFlag !== '')
					{
						$sConst = 'OPT_ATT_'.strtoupper(str_replace('_', '', $sFieldFlag));
						if (defined($sConst))
						{
							$iFieldFlags = $iFieldFlags | constant($sConst);
						}
						else
						{
							IssueLog::Error(__METHOD__.' at line '.__LINE__.' : Flag "'.$sFieldFlag.'" is not valid for field [@data-field-id="'.$sFieldId.'"] in form[@id="'.$this->aFormProperties['id'].'"]');
							throw new Exception('Flag "'.$sFieldFlag.'" is not valid for field [@data-field-id="'.$sFieldId.'"] in form[@id="'.$this->aFormProperties['id'].'"]');
						}
					}
				}

				// Checking if field has form_path, if not, we add it
				if (!$oFieldNode->hasAttribute('data-form-path'))
				{
					$oFieldNode->setAttribute('data-form-path', $oForm->GetId());
				}
				// Checking if field should be displayed opened (For linked set)
				if ($oFieldNode->hasAttribute('data-field-opened') && ($oFieldNode->getAttribute('data-field-opened') === 'true'))
				{
					$aFieldsExtraData[$sFieldId]['opened'] = true;
				}
				// Checking if field allows to ignore scope (For linked set)
				if ($oFieldNode->hasAttribute('data-field-ignore-scopes') && ($oFieldNode->getAttribute('data-field-ignore-scopes') === 'true'))
				{
					$aFieldsExtraData[$sFieldId]['ignore_scopes'] = true;
				}
				// Checking field display mode
				if ($oFieldNode->hasAttribute('data-field-display-mode') && $oFieldNode->getAttribute('data-field-display-mode') !== '')
				{
					$aFieldsExtraData[$sFieldId]['display_mode'] = $oFieldNode->getAttribute('data-field-display-mode');
				}
				elseif (isset($this->aFormProperties['properties']['display_mode']))
				{
					$aFieldsExtraData[$sFieldId]['display_mode'] = $this->aFormProperties['properties']['display_mode'];
				}
				else
				{
					$aFieldsExtraData[$sFieldId]['display_mode'] = ApplicationHelper::FORM_DEFAULT_DISPLAY_MODE;
				}

				// Finally adding field to the list
				if (!array_key_exists($sFieldId, $aFieldsAtts))
				{
					$aFieldsAtts[$sFieldId] = OPT_ATT_NORMAL;
				}
				$aFieldsAtts[$sFieldId] = $aFieldsAtts[$sFieldId] | $iFieldFlags;
			}

			// Adding rendered template to the form renderer as the base layout
			$this->oRenderer->SetBaseLayout($oHtmlDocument->saveHTML());
		}

		// Merging flags from metamodel with those from the form
		// Also, retrieving mandatory attributes from metamodel to be able to complete the form with them if necessary
		//
		// Note: When in a transition, we don't do this for fields that should be set from DM
		if ($this->aFormProperties['type'] !== 'static')
		{
			if ($this->IsTransitionForm())
			{
				$aDatamodelAttCodes = $this->oObject->GetTransitionAttributes($this->aFormProperties['stimulus_code']);
			}
			else
			{
				$aDatamodelAttCodes = MetaModel::ListAttributeDefs($sObjectClass);
			}

			foreach ($aDatamodelAttCodes as $sAttCode => $value)
			{
				/** var AttributeDefinition $oAttDef */

				// Skipping fields that should come from DM only as they will be process later on
				if (in_array($sAttCode, $aFieldsDMOnlyAttCodes))
				{
					continue;
				}

				// Retrieving object flags
				if ($this->IsTransitionForm())
				{
					// Retrieving only mandatory flag from DM when on a transition
					$iFieldFlags = $value & OPT_ATT_MANDATORY;
					$oAttDef = MetaModel::GetAttributeDef(get_class($this->oObject), $sAttCode);
				}
				elseif ($this->oObject->IsNew())
				{
					$iFieldFlags = $this->oObject->GetInitialStateAttributeFlags($sAttCode);
					$oAttDef = $value;
				}
				else
				{
					$iFieldFlags = $this->oObject->GetAttributeFlags($sAttCode);
					$oAttDef = $value;
				}

				// Skipping fields that were not specified to DM only list (garbage collector)
				if ($this->IsTransitionForm() && !array_key_exists($sAttCode, $aFieldsAtts))
				{
					if ((($value & OPT_ATT_MANDATORY) === OPT_ATT_MANDATORY && $oAttDef->IsNull($this->oObject->Get($sAttCode)))
						|| (($value & OPT_ATT_MUSTPROMPT) === OPT_ATT_MUSTPROMPT)
						|| (($value & OPT_ATT_MUSTCHANGE) === OPT_ATT_MUSTCHANGE))
					{
						if (!in_array($sAttCode, $aFieldsDMOnlyAttCodes))
						{
							$aFieldsDMOnlyAttCodes[] = $sAttCode;
						}
					}
					continue;
				}

				// Merging flags with those from the form definition
				// - If the field is in fields list
				if (array_key_exists($sAttCode, $aFieldsAtts))
				{
					// .. We merge them all
					$aFieldsAtts[$sAttCode] = $aFieldsAtts[$sAttCode] | $iFieldFlags;
				}
				// - or it is mandatory and has no value
				if ((($iFieldFlags & OPT_ATT_MANDATORY) === OPT_ATT_MANDATORY) && ($this->oObject->Get($sAttCode) === ''))
				{
					if (!array_key_exists($sAttCode, $aFieldsAtts))
					{
						$aFieldsAtts[$sAttCode] = OPT_ATT_NORMAL;
					}
					$aFieldsAtts[$sAttCode] = $aFieldsAtts[$sAttCode] | OPT_ATT_MANDATORY;
				}
			}
		}

		// Adding fields with DM flags only
		// Note: This should only happen when in a transition
		foreach ($aFieldsDMOnlyAttCodes as $sAttCode)
		{
			// Retrieving object flags from DM
			if ($this->IsTransitionForm())
			{
				$aTransitionAtts = $this->oObject->GetTransitionAttributes($this->aFormProperties['stimulus_code']);
				$iFieldFlags = $aTransitionAtts[$sAttCode];
			}
			elseif ($this->oObject->IsNew())
			{
				$iFieldFlags = $this->oObject->GetInitialStateAttributeFlags($sAttCode);
			}
			else
			{
				$iFieldFlags = $this->oObject->GetAttributeFlags($sAttCode);
			}

			// Resetting/Forcing flag to read/write
			$aFieldsAtts[$sAttCode] = OPT_ATT_NORMAL;
			// Checking if field should be must_change
			if (($iFieldFlags & OPT_ATT_MUSTCHANGE) === OPT_ATT_MUSTCHANGE)
			{
				$aFieldsAtts[$sAttCode] = $aFieldsAtts[$sAttCode] | OPT_ATT_MUSTCHANGE;
			}
			// Checking if field should be must_prompt
			if (($iFieldFlags & OPT_ATT_MUSTPROMPT) === OPT_ATT_MUSTPROMPT)
			{
				$aFieldsAtts[$sAttCode] = $aFieldsAtts[$sAttCode] | OPT_ATT_MUSTPROMPT;
			}
			// Checking if field should be mandatory
			if (($iFieldFlags & OPT_ATT_MANDATORY) === OPT_ATT_MANDATORY)
			{
				$aFieldsAtts[$sAttCode] = $aFieldsAtts[$sAttCode] | OPT_ATT_MANDATORY;
			}
		}

		// Building the form
		foreach ($aFieldsAtts as $sAttCode => $iFieldFlags)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this->oObject), $sAttCode);

			/** @var Field $oField */
			$oField = null;
			if (is_callable([$oAttDef, 'MakeFormField']))
			{
				$oField = $oAttDef->MakeFormField($this->oObject);
			}

			// Failsafe for AttributeType that would not have MakeFormField and therefore could not be used in a form
			if ($oField !== null)
			{
				if ($this->sMode !== static::ENUM_MODE_VIEW)
				{
					// Field dependencies
					$aFieldDependencies = $oAttDef->GetPrerequisiteAttributes();
					if (!empty($aFieldDependencies))
					{
						$oForm->AddFieldDependencies($oField->GetId(), $aFieldDependencies);
					}

					// Setting the field flags
					// - If it's locked because slave, we force it as read only
					if (($iFieldFlags & OPT_ATT_SLAVE) === OPT_ATT_SLAVE)
					{
						$oField->SetReadOnly(true);
					}
					// - Else if it's must change (transition), we force it as mustchange, not readonly and not hidden
					elseif (($iFieldFlags & OPT_ATT_MUSTCHANGE) === OPT_ATT_MUSTCHANGE && $this->IsTransitionForm())
					{
						$oField->SetMustChange(true);
						$oField->SetReadOnly(false);
						$oField->SetHidden(false);
					}
					// - Else if it's must prompt (transition), we force it as not readonly and not hidden
					elseif (($iFieldFlags & OPT_ATT_MUSTPROMPT) === OPT_ATT_MUSTPROMPT && $this->IsTransitionForm())
					{
						$oField->SetReadOnly(false);
						$oField->SetHidden(false);
					}
					// - Else if it wasn't mandatory or already had a value, and it's hidden, we force it as hidden
					elseif (($iFieldFlags & OPT_ATT_HIDDEN) === OPT_ATT_HIDDEN)
					{
						$oField->SetHidden(true);
					}
					elseif (($iFieldFlags & OPT_ATT_READONLY) === OPT_ATT_READONLY)
					{
						$oField->SetReadOnly(true);
					}
					else
					{
						// Normal field, use "flags" set by AttDef::MakeFormField()
						// Except if we are in a transition be cause $oAttDef doesn't know if the form is for a transition
						if ($this->IsTransitionForm())
						{
							$oField->SetReadOnly(false);
							$oField->SetHidden(false);
							$oField->SetMandatory(false);
						}
					}

					// Finally, if it's mandatory ...
					if (($iFieldFlags & OPT_ATT_MANDATORY) === OPT_ATT_MANDATORY)
					{
						// ... and when in a transition, we force it as mandatory
						if ($this->IsTransitionForm() && $oAttDef->IsNull($this->oObject->Get($sAttCode)))
						{
							$oField->SetMandatory(true);
						}
						// .. and has no value, we force it as mandatory
						elseif ($oAttDef->IsNull($this->oObject->Get($sAttCode)))
						{
							$oField->SetMandatory(true);
						}
					}

					// Specific operation on field
					// - Field that require a transaction id
					if (in_array(get_class($oField),
						array('Combodo\\iTop\\Form\\Field\\TextAreaField', 'Combodo\\iTop\\Form\\Field\\CaseLogField')))
					{
						/** @var \Combodo\iTop\Form\Field\TextAreaField|\Combodo\iTop\Form\Field\CaseLogField $oField */
						$oField->SetTransactionId($oForm->GetTransactionId());
					}
					// - Field that require a search endpoint
					if (in_array(get_class($oField),
						array('Combodo\\iTop\\Form\\Field\\SelectObjectField', 'Combodo\\iTop\\Form\\Field\\LinkedSetField'))) {
						/** @var \Combodo\iTop\Form\Field\SelectObjectField|\Combodo\iTop\Form\Field\LinkedSetField $oField */
						if ($this->oFormHandlerHelper !== null) {
							$sSearchEndpoint = $this->oFormHandlerHelper->GetUrlGenerator()->generate('p_object_search_generic', array(
								'sTargetAttCode'   => $oAttDef->GetCode(),
								'sHostObjectClass' => get_class($this->oObject),
								'sHostObjectId'    => ($this->oObject->IsNew()) ? null : $this->oObject->GetKey(),
								'ar_token'         => $this->GetActionRulesToken(),
							));
							$oField->SetSearchEndpoint($sSearchEndpoint);
						}
					}
					// - Field that require an information endpoint
					if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\LinkedSetField'))) {
						/** @var \Combodo\iTop\Form\Field\LinkedSetField $oField */
						if ($this->oFormHandlerHelper !== null) {
							$oField->SetInformationEndpoint($this->oFormHandlerHelper->GetUrlGenerator()->generate('p_object_get_information_for_linked_set_json'));
						}
					}
					// - Field that require to apply scope on its DM OQL
					if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\SelectObjectField')))
					{
						/** @var \Combodo\iTop\Form\Field\SelectObjectField $oField */
						if ($this->oFormHandlerHelper !== null) {
							$oScopeOriginal = ($oField->GetSearch() !== null) ? $oField->GetSearch() : DBSearch::FromOQL($oAttDef->GetValuesDef()->GetFilterExpression());

							/** @var \DBSearch $oScopeSearch */
							$oScopeSearch = $this->oFormHandlerHelper->GetScopeValidator()->GetScopeFilterForProfiles(UserRights::ListProfiles(),
								$oScopeOriginal->GetClass(), UR_ACTION_READ);
							if ($oScopeSearch === null) {
								IssueLog::Info(__METHOD__.' at line '.__LINE__.' : User #'.UserRights::GetUserId().' has no scope query for '.$oScopeOriginal->GetClass().' class.');
								throw new HttpException(Response::HTTP_NOT_FOUND, Dict::S('UI:ObjectDoesNotExist'));
							}
							$oScopeOriginal = $oScopeOriginal->Intersect($oScopeSearch);
							// Note : This is to skip the silo restriction on the final query
							if ($oScopeSearch->IsAllDataAllowed())
							{
								$oScopeOriginal->AllowAllData();
							}
							$oScopeOriginal->SetInternalParams(array('this' => $this->oObject));
							$oField->SetSearch($oScopeOriginal);
						}
					}
					// - Field that require to check if the current value is among allowed ones
					if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\SelectObjectField')))
					{
						// Note: We can't do this in AttributeExternalKey::MakeFormField() in the Field::SetOnFinalizeCallback() because at this point we have no information about the portal scope and ignore_silos flag, hence it always applies silos.
						// As a workaround we have to manually check if the field's current value is among the scope
						$oField->ResetCurrentValueIfNotAmongAllowedValues();
					}
					// - Field that require processing on their subfields
					if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\SubFormField')))
					{
						/** @var \Combodo\iTop\Form\Field\SubFormField $oField */
						$oSubForm = $oField->GetForm();
						if ($oAttDef->GetEditClass() === 'CustomFields')
						{
							// Retrieving only user data fields (not the metadata fields of the template)
							if ($oSubForm->HasField('user_data'))
							{
								/** @var \Combodo\iTop\Form\Field\SubFormField $oUserDataField */
								$oUserDataField = $oSubForm->GetField('user_data');
								$oUserDataForm = $oUserDataField->GetForm();
								foreach ($oUserDataForm->GetFields() as $oCustomField)
								{
									// - Field that require a search endpoint (OQL based dropdown list fields)
									if (in_array(get_class($oCustomField), array('Combodo\\iTop\\Form\\Field\\SelectObjectField')))
									{
										/** @var \Combodo\iTop\Form\Field\SelectObjectField $oCustomField */
										if ($this->oFormHandlerHelper->GetUrlGenerator() !== null) {

											$sSearchEndpoint = $this->oFormHandlerHelper->GetUrlGenerator()->generate('p_object_search_generic',
												array(
													'sTargetAttCode'   => $oAttDef->GetCode(),
													'sHostObjectClass' => get_class($this->oObject),
													'sHostObjectId'    => ($this->oObject->IsNew()) ? null : $this->oObject->GetKey(),
													'ar_token'         => $this->GetActionRulesToken(),
												));
											$oCustomField->SetSearchEndpoint($sSearchEndpoint);
										}
									}
									// - Field that require to check if the current value is among allowed ones
									if (in_array(get_class($oCustomField), array('Combodo\\iTop\\Form\\Field\\SelectObjectField')))
									{
										/** @var \Combodo\iTop\Form\Field\SelectObjectField $oCustomField */
										$oCustomField->ResetCurrentValueIfNotAmongAllowedValues();
									}
								}
							}
						}
					}
				}
				else
				{
					if (($iFieldFlags & OPT_ATT_HIDDEN) === OPT_ATT_HIDDEN)
					{
						$oField->SetHidden(true);
					}
					else
					{
						$oField->SetReadOnly(true);
					}
				}

				// Specific operation on field
				// - LinkedSet
				if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\LinkedSetField')))
				{
					/** @var \Combodo\iTop\Form\Field\LinkedSetField $oField */
					/** @var \AttributeLinkedSetIndirect $oAttDef */
					//   - Overriding attributes to display
					if ($this->oFormHandlerHelper !== null) {
						// Note : This snippet is inspired from AttributeLinkedSet::MakeFormField()
						$aAttCodesToDisplay = ApplicationHelper::GetLoadedListFromClass($this->oFormHandlerHelper->getCombodoPortalConf()['lists'],
							$oField->GetTargetClass(), 'list');
						// - Adding friendlyname attribute to the list is not already in it
						$sTitleAttCode = 'friendlyname';
						if (($sTitleAttCode !== null) && !in_array($sTitleAttCode, $aAttCodesToDisplay)) {
							$aAttCodesToDisplay = array_merge(array($sTitleAttCode), $aAttCodesToDisplay);
						}
						// - Adding attribute labels
						$aAttributesToDisplay = array();
						foreach ($aAttCodesToDisplay as $sAttCodeToDisplay) {
							$oAttDefToDisplay = MetaModel::GetAttributeDef($oField->GetTargetClass(), $sAttCodeToDisplay);
							$aAttributesToDisplay[$sAttCodeToDisplay] = [
								'att_code' => $sAttCodeToDisplay,
								'label'    => $oAttDefToDisplay->GetLabel(),
							];
						}
						$oField->SetAttributesToDisplay($aAttributesToDisplay);

						// Link attributes to display
						if ($oField->IsIndirect()) {
							$sClass = get_class($this->oObject);
							$oField->SetLnkAttributesToDisplay($this->GetZListAttDefsFilteredForIndirectLinkClass($sClass, $sAttCode));
						}
					}
					//    - Filtering links regarding scopes
					if ($this->oFormHandlerHelper !== null) {
						$aLimitedAccessItemIDs = array();

						/** @var \ormLinkSet $oFieldOriginalSet */
						$oFieldOriginalSet = $oField->GetCurrentValue();
						foreach ($oFieldOriginalSet as $oLink) {
							if ($oField->IsIndirect()) {
								$iRemoteKey = $oLink->Get($oAttDef->GetExtKeyToRemote());
							} else {
								$iRemoteKey = $oLink->GetKey();
							}

							if (!$this->oFormHandlerHelper->GetSecurityHelper()->IsActionAllowed(UR_ACTION_READ, $oField->GetTargetClass(), $iRemoteKey)) {
								$aLimitedAccessItemIDs[] = $iRemoteKey;
							}
						}
						$oFieldOriginalSet->rewind();
						$oField->SetLimitedAccessItemIDs($aLimitedAccessItemIDs);
					}
					//    - Displaying as opened
					if (array_key_exists($sAttCode, $aFieldsExtraData) && array_key_exists('opened', $aFieldsExtraData[$sAttCode]))
					{
						$oField->SetDisplayOpened(true);
					}
					//    - Displaying out of scopes items
					if (array_key_exists($sAttCode, $aFieldsExtraData) && array_key_exists('ignore_scopes', $aFieldsExtraData[$sAttCode]))
					{
						$oField->SetDisplayLimitedAccessItems(true);
					}
				}
				// - BlobField
				if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\BlobField', 'Combodo\\iTop\\Form\\Field\\ImageField')))
				{
					//   - Overriding attributes to display
					if ($this->oFormHandlerHelper !== null) {
						// Override hardcoded URLs in ormDocument pointing to back office console
						$oOrmDoc = $this->oObject->Get($sAttCode);
						$sDisplayUrl = $this->oFormHandlerHelper->GetUrlGenerator()->generate('p_object_document_display', [
							'sObjectClass' => get_class($this->oObject),
							'sObjectId'    => $this->oObject->GetKey(),
							'sObjectField' => $sAttCode,
							'cache'        => 86400,
							's'            => $oOrmDoc->GetSignature(),
						]);
						$sDownloadUrl = $this->oFormHandlerHelper->GetUrlGenerator()->generate('p_object_document_download', [
							'sObjectClass' => get_class($this->oObject),
							'sObjectId'    => $this->oObject->GetKey(),
							'sObjectField' => $sAttCode,
							'cache'        => 86400,
							's'            => $oOrmDoc->GetSignature(),
						]);
						/** @var \Combodo\iTop\Form\Field\BlobField $oField */
						$oField->SetDisplayUrl($sDisplayUrl)
							->SetDownloadUrl($sDownloadUrl);
					}
				}

			}
			else
			{
				$oField = new LabelField($sAttCode);
				$oField->SetReadOnly(true)
					->SetHidden(false)
					->SetCurrentValue('Sorry, that AttributeType is not implemented yet.')
					->SetLabel($oAttDef->GetLabel());
			}

			// Setting field display mode
			if (array_key_exists($sAttCode, $aFieldsExtraData) && array_key_exists('display_mode', $aFieldsExtraData[$sAttCode]))
			{
				$oField->SetDisplayMode($aFieldsExtraData[$sAttCode]['display_mode']);
			}

			// Overload (AttributeDefinition) flags metadata as they have been changed while building the form
			$oField->AddMetadata('attribute-flag-hidden', $oField->GetHidden() ? 'true' : 'false');
			$oField->AddMetadata('attribute-flag-read-only', $oField->GetReadOnly() ? 'true' : 'false');
			$oField->AddMetadata('attribute-flag-mandatory', $oField->GetMandatory() ? 'true' : 'false');
			$oField->AddMetadata('attribute-flag-must-change', $oField->GetMustChange() ? 'true' : 'false');

			// Do not add hidden fields as they are of no use, if one is necessary because another depends on it, it will be automatically added.
			// Note: We do this at the end because during the process an hidden field could have become writable if mandatory and empty for example.
			if($oField->GetHidden() === false)
			{
				$oForm->AddField($oField);
			} else {
				$this->aHiddenFieldsId[]=$oField->GetId();
			}
		}

		// Checking dependencies to ensure that all needed fields are in the form
		// (This is kind of a garbage collector for dependencies)
		foreach ($oForm->GetDependencies() as $sImpactedFieldId => $aDependencies)
		{
			foreach ($aDependencies as $sDependencyFieldId)
			{
				if (!$oForm->HasField($sDependencyFieldId))
				{
					try
					{
						$oAttDef = MetaModel::GetAttributeDef(get_class($this->oObject), $sDependencyFieldId);
						$oField = $oAttDef->MakeFormField($this->oObject);
						$oField->SetHidden(true);

						$oForm->AddField($oField);
					}
					catch (Exception $e)
					{
						// Avoid blocking a form if a RequestTemplate reference a bad attribute (e.g. :this->id)
						IssueLog::Error('May be a bad OQL (referencing :this->id) in a RequestTemplate causes the following error');
						IssueLog::Error($e);
					}
				}
			}
		}

		// Checking if the instance has attachments
		if (class_exists('Attachment') && class_exists('AttachmentPlugIn'))
		{
			// Checking if the object is allowed for attachments
			$bClassAllowed = false;
			$aAllowedClasses = MetaModel::GetModuleSetting('itop-attachments', 'allowed_classes', array('Ticket'));
			foreach ($aAllowedClasses as $sAllowedClass)
			{
				if ($this->oObject instanceof $sAllowedClass)
				{
					$bClassAllowed = true;
					break;
				}
			}

			// Adding attachment field
			if ($bClassAllowed)
			{
				// set id to a unique key - avoid collisions with another attribute that could exist with the name 'attachments'
				$oField = new FileUploadField('attachments_plugin');
				$oField->SetLabel(Dict::S('Portal:Attachments'))
					->SetUploadEndpoint($this->oFormHandlerHelper->GetUrlGenerator()->generate('p_object_attachment_add'))
					->SetDownloadEndpoint($this->oFormHandlerHelper->GetUrlGenerator()->generate('p_object_attachment_download',
						array('sAttachmentId' => '-sAttachmentId-')))
					->SetTransactionId($oForm->GetTransactionId())
					->SetAllowDelete($this->oFormHandlerHelper->getCombodoPortalConf()['properties']['attachments']['allow_delete'])
					->SetObject($this->oObject);

				// Checking if we can edit attachments in the current state
				if (($this->sMode === static::ENUM_MODE_VIEW)
					|| AttachmentPlugIn::IsReadonlyState($this->oObject, $this->oObject->GetState(),
						AttachmentPlugIn::ENUM_GUI_PORTALS) === true
					|| $oForm->GetEditableFieldCount(true) === 0)
				{
					$oField->SetReadOnly(true);
				}

				// Adding attachements field in transition only if it is editable
				if (!$this->IsTransitionForm() || ($this->IsTransitionForm() && !$oField->GetReadOnly()))
				{
					$oForm->AddField($oField);
				}
			}
		}

		$oForm->Finalize();
		$this->oForm = $oForm;
		$this->oRenderer->SetForm($this->oForm);
	}

	/**
	 * @inheritDoc
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function OnCancel($aArgs = null)
	{
		// Ask to each field to clean itself
		/** @var \Combodo\iTop\Form\Field\Field $oField */
		foreach ($this->oForm->GetFields() as $oField)
		{
			$oField->OnCancel();
		}
		// Then clean inline images from rich text editor such as TextareaField
		// Note : This could be done by TextareaField::OnCancel(), but we consider that could have been done in this form outside the field.
		// Also, it would require the field to know the transaction id which it doesn't as of today.
		InlineImage::OnFormCancel(utils::GetUploadTempId($this->oForm->GetTransactionId()));
		// Then clean attachments
		// TODO : This has to be refactored when the function from itop-attachments has been migrated into the core
		$this->CancelAttachments();
	}

	/**
	 * @inheritDoc
	 */
	public function CheckTransaction(&$aData)
	{
		$isTransactionValid = \utils::IsTransactionValid($this->oForm->GetTransactionId(), false); //The transaction token is kept in order to preserve BC with ajax forms (the second call would fail if the token is deleted). (The GC will take care of cleaning the token for us later on)
		if (!$isTransactionValid) {
			if ($this->oObject->IsNew()) {
				$sError = Dict::S('UI:Error:ObjectAlreadyCreated');
			} else {
				$sError = Dict::S('UI:Error:ObjectAlreadyUpdated');
			}

			$aData['messages']['error'] += [
				'_main' => [$sError]
			];
			$aData['valid'] = false;
		}
	}

	/**
	 * Validates the form and returns an array with the validation status and the messages.
	 * If the form is valid, creates/updates the object.
	 *
	 * eg :
	 *  array(
	 *      'status' => true|false
	 *      'messages' => array(
	 *          'errors' => array()
	 *    )
	 *
	 * @inheritDoc
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function OnSubmit($aArgs = null)
	{
		$aData = parent::OnSubmit($aArgs);

		if (!$aData['valid']) {
			return $aData;
		}

		// Update object and form
		$this->OnUpdate($aArgs);

		// Check if form valid
		if (! $this->oForm->Validate())
		{
			// Handle errors
			$aData['valid'] = false;
			$aData['messages']['error'] += $this->oForm->GetErrorMessages();
			return $aData;
		}

		$sObjectClass = get_class($this->oObject);

		$bExceptionLogged = false;
		try {
			// modification flags
			$bIsNew = $this->oObject->IsNew();
			$bWasModified = $this->oObject->IsModified();
			$bActivateTriggers = (!$bIsNew && $bWasModified);

			$oSecurityHelper = $this->oFormHandlerHelper->GetSecurityHelper();

			// Forcing allowed writing on the object if necessary. This is used in some particular cases.
			$bAllowWrite = $oSecurityHelper->IsActionAllowed($bIsNew ? UR_ACTION_CREATE : UR_ACTION_MODIFY, $sObjectClass, $this->oObject->GetKey());
			if ($bAllowWrite) {
				$this->oObject->AllowWrite(true);
			}

			// Writing object to DB
			try
			{
				$this->oObject->DBWrite();
			} catch (CoreCannotSaveObjectException $e) {
				throw new Exception($e->getTextMessage());
			} catch (InvalidExternalKeyValueException $e) {
				ExceptionLog::LogException($e, $e->getContextData());
				$bExceptionLogged = true;

				throw new Exception($e->getIssue());
			} catch (Exception $e) {
				$aContext = [
					'origin'    => __CLASS__.'::'.__METHOD__,
					'obj_class' => get_class($this->oObject),
					'obj_key' => $this->oObject->GetKey(),
				];
				ExceptionLog::LogException($e, $aContext);
				$bExceptionLogged = true;

				if ($bIsNew) {
					throw new Exception(Dict::S('Portal:Error:ObjectCannotBeCreated'));
				}
				throw new Exception(Dict::S('Portal:Error:ObjectCannotBeUpdated'));
			}
			// Finalizing images link to object, otherwise it will be cleaned by the GC
			InlineImage::FinalizeInlineImages($this->oObject);
			// Finalizing attachments link to object
			// TODO : This has to be refactored when the function from itop-attachments has been migrated into the core
			if (isset($aArgs['attachmentIds']))
			{
				$this->FinalizeAttachments($aArgs['attachmentIds']);
			}

			// Checking if we have to apply a stimulus
			if (isset($aArgs['applyStimulus']))
			{
				$this->oObject->ApplyStimulus($aArgs['applyStimulus']['code']);
			}
			// Activating triggers only on update
			if ($bActivateTriggers)
			{
				$sTriggersQuery = $this->oFormHandlerHelper->getCombodoPortalConf()['properties']['triggers_query'];
				if ($sTriggersQuery !== null)
				{
					$aParentClasses = MetaModel::EnumParentClasses($sObjectClass, ENUM_PARENT_CLASSES_ALL);
					$oTriggerSet = new DBObjectSet(DBObjectSearch::FromOQL($sTriggersQuery), array(),
						array('parent_classes' => $aParentClasses));
					/** @var \Trigger $oTrigger */
					while ($oTrigger = $oTriggerSet->Fetch())
					{
						try
						{
							$oTrigger->DoActivate($this->oObject->ToArgs('this'));
						}
						catch(Exception $e)
						{
							utils::EnrichRaisedException($oTrigger, $e);
						}
					}
				}
			}

			// Resetting caselog fields value, otherwise the value will stay in it after submit.
			$this->oForm->ResetCaseLogFields();

			if ($bWasModified)
			{
				//=if (isNew) because $bActivateTriggers = (!$this->oObject->IsNew() && $this->oObject->IsModified())
				if(!$bActivateTriggers)
				{
					$aData['messages']['success'] += array(	'_main' => array(Dict::Format('UI:Title:Object_Of_Class_Created', $this->oObject->GetName(),MetaModel::GetName(get_class($this->oObject)))));
				}
				else
				{
					$aData['messages']['success'] += array('_main' => array(Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($this->oObject)), $this->oObject->GetName())));
				}
			}
		}
		catch (CoreCannotSaveObjectException $e) {
			$aData['valid'] = false;
			$aData['messages']['error'] += array('_main' => array($e->getTextMessage()));
			if (false === $bExceptionLogged) {
				IssueLog::Error(__METHOD__.' at line '.__LINE__.' : '.$e->getMessage());
			}
		}
		catch (Exception $e) {
			$aData['valid'] = false;
			$aData['messages']['error'] += [
				'_main' => [ ($e instanceof CoreCannotSaveObjectException) ? $e->getTextMessage() : $e->getMessage()]
			];
			if (false === $bExceptionLogged) {
				IssueLog::Error(__METHOD__.' at line '.__LINE__.' : '.$e->getMessage());
			}
		}

		return $aData;
	}

	/**
	 * Updates the form and its fields with the current values
	 *
	 * Note : Doesn't update the object, see ObjectFormManager::OnSubmit() for that;
	 *
	 * @inheritDoc
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function OnUpdate($aArgs = null)
	{
		$aFormProperties = array();

		if (is_array($aArgs))
		{
			// First we need to update the Object with its new values in order to enable the dependents fields to update
			if (isset($aArgs['currentValues']))
			{
				$aCurrentValues = $aArgs['currentValues'];
				$sObjectClass = get_class($this->oObject);
				foreach ($aCurrentValues as $sAttCode => $value)
				{
					if (MetaModel::IsValidAttCode($sObjectClass, $sAttCode))
					{
						/** @var \AttributeDefinition $oAttDef */
						$oAttDef = MetaModel::GetAttributeDef($sObjectClass, $sAttCode);
						if ($oAttDef->IsLinkSet())
						{
							/** @var \AttributeLinkedSet $oAttDef */

							// Parsing JSON value
							//
							// Note : The value was passed as a string instead of an array because the attribute would not be included in the $aCurrentValues when empty.
							// Which was an issue when deleting all objects from linkedset
							$value = json_decode($value, true);

							/** @var \ormLinkSet $oLinkSet */
							$oLinkSet = $this->oObject->Get($sAttCode);
							$sLinkedClass = $oAttDef->GetLinkedClass();

							// Checking links to remove
							if (isset($value['remove']))
							{
								foreach ($value['remove'] as $iObjKey => $aObjData)
								{
									$oLinkSet->RemoveItem($iObjKey);
								}
							}

							// Checking links to add
							if (isset($value['add']))
							{
								foreach ($value['add'] as $iObjKey => $aObjdata)
								{
									// Creating link when linkset is indirect...
									if ($oAttDef->IsIndirect())
									{
										/** @var \AttributeLinkedSetIndirect $oAttDef */
										$oLink = MetaModel::NewObject($sLinkedClass);
										$oLink->Set($oAttDef->GetExtKeyToRemote(), $iObjKey);
										$oLink->Set($oAttDef->GetExtKeyToMe(), $this->oObject->GetKey());
										// Set link attributes values...
										foreach ($aObjdata as $sLinkAttCode => $oAttValue) {
											if (!is_scalar($oAttValue)) {
												IssueLog::Debug("ObjectFormManager::OnUpdate invalid link attribute value, $sLinkAttCode is not a scalar value", LogChannels::PORTAL);
												continue;
											}
											$oLink->Set($sLinkAttCode, $oAttValue);
										}
										$oLinkSet->AddItem($oLink);
									}
									// ... or adding remote object when linkset id direct
									else
									{
										// Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
										$oLink = MetaModel::GetObject($sLinkedClass, $iObjKey, false, true);
									}

									if ($oLink !== null) {
										$oLinkSet->AddItem($oLink);
									}
								}
							}

							// Checking links to modify
							if ($oAttDef->IsIndirect() && isset($value['current'])) {
								foreach ($value['current'] as $iObjKey => $aObjData) {
									if ($iObjKey < 0) {
										continue;
									}
									$oLink = null;
									$oLinkSet->Rewind();
									foreach ($oLinkSet as $oItem) {
										if ($oItem->Get('id') != $iObjKey) {
											continue;
										}
										$oLink = $oItem;
										foreach ($aObjData as $sLinkAttCode => $oAttValue) {
											if (!is_scalar($oAttValue)) {
												IssueLog::Debug("ObjectFormManager::OnUpdate invalid link attribute value, $sLinkAttCode is not a scalar value", LogChannels::PORTAL);
												continue;
											}
											$oLink->Set($sLinkAttCode, $oAttValue);
										}
										$oLinkSet->ModifyItem($oLink);
									}
								}
							}

							// Setting value in the object
							$this->oObject->Set($sAttCode, $oLinkSet);
						} elseif ($oAttDef instanceof AttributeSet) {
							/** @var \ormSet $oTagSet */
							$oOrmSet = $this->oObject->Get($sAttCode);
							if (is_null($oOrmSet)) {
								$oOrmSet = new \ormSet(get_class($this->oObject), $sAttCode, $oAttDef->GetMaxItems());
							}
							$oOrmSet->ApplyDelta(json_decode($value, true));
							$this->oObject->Set($sAttCode, $oOrmSet);
						} elseif ($oAttDef instanceof AttributeDateTime) // AttributeDate is derived from AttributeDateTime
						{
							if ($value != null) {
								$value = $oAttDef->GetFormat()->Parse($value);
								if (is_object($value)) {
									$value = $value->format($oAttDef->GetInternalFormat());
								}
							}
							$this->oObject->Set($sAttCode, $value);
						}
						elseif ($oAttDef->IsScalar() && is_array($value))
						{
							$this->oObject->Set($sAttCode, current($value));
						}
						elseif ($oAttDef->GetEditClass() === 'CustomFields')
						{
							// We don't update attribute as ormCustomField comparaison is not working as excepted.
							// When several templates available, "template_id" is not sent by the portal has it is a read-only select input
							// therefore, the TemplateFieldsHandler::CompareValues() doesn't work.
							// This use case works in the console as it always send all fields, even hidden and read-only.

							// Different templates
							if (isset($value['template_id'])
								&& ($value['template_id'] != $value['current_template_id']))
							{
								$this->oObject->Set($sAttCode, $value);
							}
							// Same template, different fields
							elseif (isset($value['template_id'], $value['template_data'])
								&& ($value['template_id'] == $value['current_template_id'])
								&& ($value['template_data'] != $value['current_template_data']))
							{
								$this->oObject->Set($sAttCode, $value);
							}
							// Update of current values
							elseif (isset($value['user_data']))
							{
								$this->oObject->Set($sAttCode, $value);
							}
							// Else don't update! Otherwise we might loose current value
						}
						else
						{
							$this->oObject->Set($sAttCode, $value);
						}
					}
				}
                /** @var SecurityHelper $oSecurityHelper */
                $oSecurityHelper = $this->oFormHandlerHelper->GetSecurityHelper();
                // N°7023 - Note that we check for ext. key now as we want the check to be done on user inputs and NOT on ext. keys set programatically, so it must be done before the DoComputeValues
                $this->oObject->CheckChangedExtKeysValues(function ($sClass, $sId) use ($oSecurityHelper): bool {
                    return $oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sClass, $sId);
                });
				$this->oObject->DoComputeValues();
			}

			// Then we retrieve properties of the form to build
			if (isset($aArgs['formProperties']))
			{
				$aFormProperties = $aArgs['formProperties'];
			}
		}
		// Then we build and update form
		// - We update form properties only we don't have any yet. This is a fallback for cases when form properties where not among the JSON data
		if ($this->GetFormProperties() === null)
		{
			$this->SetFormProperties($aFormProperties);
		}
		$this->Build();
	}

	/**
	 * This is a temporary function until the Attachment refactoring is done. It should be remove once it's done.
	 * It is inspired from itop-attachments/main.attachments.php / UpdateAttachments()
	 *
	 * @param array $aAttachmentIds
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \OQLException
	 */
	protected function FinalizeAttachments($aAttachmentIds)
	{
		$aRemovedAttachmentsIds = (isset($aAttachmentIds['removed_attachments_ids'])) ? $aAttachmentIds['removed_attachments_ids'] : array();
		// Not used for now. //$aActualAttachmentsIds = (isset($aAttachmentIds['actual_attachments_ids'])) ? $aAttachmentIds['actual_attachments_ids'] : array();
		$aActions = array();

		// Removing attachments from currents
		if (!empty($aRemovedAttachmentsIds))
		{
			$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE item_class = :class AND item_id = :item_id");
			$oSet = new DBObjectSet($oSearch, array(), array('class' => get_class($this->oObject), 'item_id' => $this->oObject->GetKey()));
			while ($oAttachment = $oSet->Fetch())
			{
				// Remove attachments that are no longer attached to the current object
				if (in_array($oAttachment->GetKey(), $aRemovedAttachmentsIds))
				{
					$aData = ['attachment' => $oAttachment];
					$this->oObject->FireEvent(EVENT_REMOVE_ATTACHMENT_FROM_OBJECT, $aData);
					$oAttachment->DBDelete();
					$aActions[] = self::GetAttachmentActionChangeOp($oAttachment, false);
				}
			}
		}

		// Processing temporary attachments
		$sTempId = utils::GetUploadTempId($this->oForm->GetTransactionId());
		$sOQL = 'SELECT Attachment WHERE temp_id = :temp_id';
		$oSearch = DBObjectSearch::FromOQL($sOQL);
		$oSet = new DBObjectSet($oSearch, array(), array('temp_id' => $sTempId));
		while ($oAttachment = $oSet->Fetch())
		{
			// Temp attachment removed
			if (in_array($oAttachment->GetKey(), $aRemovedAttachmentsIds))
			{
				$oAttachment->DBDelete();
			}
			else
			{
				$oAttachment->SetItem($this->oObject);
				$oAttachment->Set('temp_id', '');
				$oAttachment->DBUpdate();
				$aActions[] = self::GetAttachmentActionChangeOp($oAttachment, true);
				$aData = ['attachment' => $oAttachment];
				$this->oObject->FireEvent(EVENT_ADD_ATTACHMENT_TO_OBJECT, $aData);
			}
		}
		
		// Save changes to current object history
		// inspired from itop-attachments/main.attachments.php / RecordHistory
		foreach ($aActions as $oChangeOp)
		{
			$oChangeOp->Set("objclass", get_class($this->oObject));
			$oChangeOp->Set("objkey", $this->oObject->GetKey());
			$oChangeOp->DBInsertNoReload();
		}
	}

	/**
	 * This is a temporary function until the Attachment refactoring is done. It should be remove once it's done.
	 * It is inspired from itop-attachments/main.attachments.php / UpdateAttachments()
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	protected function CancelAttachments()
	{
		// Processing temporary attachments
		$sTempId = utils::GetUploadTempId($this->oForm->GetTransactionId());
		$sOQL = 'SELECT Attachment WHERE temp_id = :temp_id';
		$oSearch = DBObjectSearch::FromOQL($sOQL);
		$oSet = new DBObjectSet($oSearch, array(), array('temp_id' => $sTempId));
		while ($oAttachment = $oSet->Fetch())
		{
			$oAttachment->DBDelete();
		}
	}

	/**
	 * This is a temporary function until the Attachment refactoring is done. It should be remove once it's done.
	 * It is inspired from itop-attachments/main.attachments.php / GetActionChangeOp()
	 *
	 * @param $oAttachment
	 * @param bool $bCreate
	 *
	 * @return \CMDBChangeOpAttachmentAdded|\CMDBChangeOpAttachmentRemoved
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	private static function GetAttachmentActionChangeOp($oAttachment, $bCreate = true)
	{
		$oBlob = $oAttachment->Get('contents');
		$sFileName = $oBlob->GetFileName();
		if ($bCreate)
		{
			$oChangeOp = new CMDBChangeOpAttachmentAdded();
			$oChangeOp->Set('attachment_id', $oAttachment->GetKey());
			$oChangeOp->Set('filename', $sFileName);
		}
		else
		{
			$oChangeOp = new CMDBChangeOpAttachmentRemoved();
			$oChangeOp->Set('filename', $sFileName);
		}
		return $oChangeOp;
	}

	/**
	 * @return array
	 * @since 2.7.5
	 */
	public function GetHiddenFieldsId()
	{
		return $this->aHiddenFieldsId;
	}

	/**
	 * @param array $aHiddenFieldsId
	 *
	 * @since 2.7.5
	 */
	public function SetHiddenFieldsId($aHiddenFieldsId)
	{
		$this->aHiddenFieldsId = $aHiddenFieldsId;
	}

	/**
	 * Inspired from {@see \MetaModel::GetZListAttDefsFilteredForIndirectLinkClass}
	 * Retrieve link attributes to display from portal configuration.
	 *
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return array
	 * @throws \CoreException
	 * @since 3.1.0 N°6398
	 *
	 */
	private function GetZListAttDefsFilteredForIndirectLinkClass(string $sClass, string $sAttCode): array
	{
		$aAttCodesToPrint = [];

		$oLinkedSetAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sLinkedClass = $oLinkedSetAttDef->GetLinkedClass();
		$sExtKeyToRemote = $oLinkedSetAttDef->GetExtKeyToRemote();
		$sExtKeyToMe = $oLinkedSetAttDef->GetExtKeyToMe();

		$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
		$sDefaultState = MetaModel::GetDefaultState($sClass);

		foreach (ApplicationHelper::GetLoadedListFromClass($this->oFormHandlerHelper->getCombodoPortalConf()['lists'], $sLinkedClass, 'list') as $sLnkAttCode) {

			$oLnkAttDef = MetaModel::GetAttributeDef($sLinkedClass, $sLnkAttCode);
			if ($sStateAttCode == $sLnkAttCode) {
				// State attribute is always hidden from the UI
				continue;
			}
			if (($sLnkAttCode == $sExtKeyToMe)
				|| ($sLnkAttCode == $sExtKeyToRemote)
				|| ($sLnkAttCode == 'finalclass')) {
				continue;
			}
			if (!($oLnkAttDef->IsWritable())) {
				continue;
			}

			$iFlags = MetaModel::GetAttributeFlags($sLinkedClass, $sDefaultState, $sLnkAttCode);
			if (!($iFlags & OPT_ATT_HIDDEN) && !($iFlags & OPT_ATT_READONLY)) {
				$aAttCodesToPrint[$sLnkAttCode] = [
					'att_code'  => $sLnkAttCode,
					'label'     => $oLnkAttDef->GetLabel(),
					'mandatory' => !$oLnkAttDef->IsNullAllowed(),
				];
			}

		}

		return $aAttCodesToPrint;
	}
}
