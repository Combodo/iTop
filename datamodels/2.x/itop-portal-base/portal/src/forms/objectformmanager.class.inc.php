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

namespace Combodo\iTop\Portal\Form;

use AttachmentPlugIn;
use AttributeDateTime;
use AttributeTagSet;
use CMDBSource;
use Combodo\iTop\Form\Field\Field;
use Combodo\iTop\Form\Field\FileUploadField;
use Combodo\iTop\Form\Field\LabelField;
use Combodo\iTop\Form\Form;
use Combodo\iTop\Form\FormManager;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use CoreCannotSaveObjectException;
use DBObject;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Dict;
use Exception;
use InlineImage;
use IssueLog;
use MetaModel;
use ormTagSet;
use Silex\Application;
use UserRights;
use utils;

/**
 * Description of objectformmanager
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since iTop 2.3.0
 */
class ObjectFormManager extends FormManager
{
	const ENUM_MODE_VIEW = 'view';
	const ENUM_MODE_EDIT = 'edit';
	const ENUM_MODE_CREATE = 'create';
	const ENUM_MODE_APPLY_STIMULUS = 'apply_stimulus';

	/** @var \Silex\Application $oApp */
	protected $oApp;
    /** @var \DBObject $oObject */
	protected $oObject;
	protected $sMode;
	protected $sActionRulesToken;
	protected $aFormProperties;
	protected $aCallbackUrls = array();

    /**
     * Creates an instance of \Combodo\iTop\Portal\Form\ObjectFormManager from JSON data that must contain at least :
     * - formobject_class : The class of the object that is being edited/viewed
     * - formmode : view|edit|create
     * - values for parent
     *
     * @param string $sJson
     *
     * @return \Combodo\iTop\Portal\Form\ObjectFormManager
     *
     * @throws \Exception
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */
	static function FromJSON($sJson)
	{
		if (is_array($sJson))
		{
			$aJson = $sJson;
		}
		else
		{
			$aJson = json_decode($sJson, true);
		}

		/** @var \Combodo\iTop\Portal\Form\ObjectFormManager $oFormManager */
		$oFormManager = parent::FromJSON($sJson);

		// Retrieving object to edit
		if (!isset($aJson['formobject_class']))
		{
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
		if (!isset($aJson['formcallbacks']))
		{
			// TODO
		}

		return $oFormManager;
	}

	/**
	 *
	 * @return \Silex\Application
	 */
	public function GetApplication()
	{
		return $this->oApp;
	}

	/**
	 *
	 * @param \Silex\Application $oApp
	 * @return \Combodo\iTop\Portal\Form\ObjectFormManager
	 */
	public function SetApplication(Application $oApp)
	{
		$this->oApp = $oApp;
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
	 * @return \Combodo\iTop\Portal\Form\ObjectFormManager
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
	 * @return \Combodo\iTop\Portal\Form\ObjectFormManager
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
	 * @return \Combodo\iTop\Portal\Form\ObjectFormManager
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
	 * @return \Combodo\iTop\Portal\Form\ObjectFormManager
	 */
	public function SetFormProperties($aFormProperties)
	{
//		echo '<pre>';
//		print_r($aFormProperties);
//		echo '</pre>';
//		die();
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
	 * @return \Combodo\iTop\Portal\Form\ObjectFormManager
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
	 * Creates a JSON string from the current object including :
	 * - formobject_class
	 * - formobject_id
	 * - formmode
	 * - values for parent
	 *
	 * @return array
	 */
	public function ToJSON()
	{
		$aJson = parent::ToJSON();
		$aJson['formobject_class'] = get_class($this->oObject);
		if ($this->oObject->GetKey() > 0)
			$aJson['formobject_id'] = $this->oObject->GetKey();
		$aJson['formmode'] = $this->sMode;
		$aJson['formactionrulestoken'] = $this->sActionRulesToken;
		$aJson['formproperties'] = $this->aFormProperties;

		return $aJson;
	}

    /**
     * @throws \Exception
     * @throws \CoreException
     * @throws \OQLException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
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
			$aFormId = 'objectform-' . ((isset($this->aFormProperties['id'])) ? $this->aFormProperties['id'] : 'default') . '-' . uniqid();
			$oForm = new Form($aFormId);
			$oForm->SetTransactionId(utils::GetNewTransactionId());
		}

		// Building form from its properties
		// - The fields
		switch ($this->aFormProperties['type'])
		{
			case 'custom_list':
			case 'static':
				foreach ($this->aFormProperties['fields'] as $sAttCode => $aOptions)
				{
				    // When in a transition and no flags are specified for the field, we will retrieve its flags from DM later
				    if($this->IsTransitionForm() && empty($aOptions))
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
		if ($this->aFormProperties['layout'] !== null)
		{
			// Checking if we need to render the template from twig to html in order to parse the fields
			if ($this->aFormProperties['layout']['type'] === 'twig')
			{
				// Creating sandbox twig env. to load and test the custom form template
				$oTwig = new \Twig_Environment(new \Twig_Loader_Array( array($oForm->GetId() => $this->aFormProperties['layout']['content']) ));
				ApplicationHelper::RegisterTwigExtensions($oTwig);
				$sRendered = $oTwig->render($oForm->GetId(), array('oRenderer' => $this->oRenderer, 'oObject' => $this->oObject));
			}
			else
			{
				$sRendered = $this->aFormProperties['layout']['content'];
			}

			// Parsing rendered template to find the fields
			$oHtmlDocument = new \DOMDocument();
			// Note: Loading as XML instead of HTML avoid some encoding issues (eg. 'é' was transformed to '&tilde;&copy;')
			$oHtmlDocument->loadXML('<root>' . $sRendered . '</root>');

			// Adding fields to the list
			$oXPath = new \DOMXPath($oHtmlDocument);
			foreach ($oXPath->query('//div[contains(@class, "form_field")][@data-field-id]') as $oFieldNode)
			{
				$sFieldId = $oFieldNode->getAttribute('data-field-id');
				$sFieldFlags = $oFieldNode->getAttribute('data-field-flags');
				$iFieldFlags = OPT_ATT_NORMAL;

                // When in a transition and no flags are specified for the field, we will retrieve its flags from DM later
                if($this->IsTransitionForm() && $sFieldFlags === '')
                {
                    // (Might have already been added from the "fields" property)
                    if(!in_array($sFieldId, $aFieldsDMOnlyAttCodes))
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
                        $sConst = 'OPT_ATT_' . strtoupper(str_replace('_', '', $sFieldFlag));
                        if (defined($sConst))
                        {
                            $iFieldFlags = $iFieldFlags | constant($sConst);
                        }
                        else
                        {
                            IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Flag "' . $sFieldFlag . '" is not valid for field [@data-field-id="' . $sFieldId . '"] in form[@id="' . $this->aFormProperties['id'] . '"]');
                            throw new Exception('Flag "' . $sFieldFlag . '" is not valid for field [@data-field-id="' . $sFieldId . '"] in form[@id="' . $this->aFormProperties['id'] . '"]');
                        }
                    }
                }

                // Checking if field has form_path, if not, we add it
				if (!$oFieldNode->hasAttribute('data-form-path'))
				{
					$oFieldNode->setAttribute('data-form-path', $oForm->GetId());
				}
                // Checking if field should be displayed opened (For linked set)
                if($oFieldNode->hasAttribute('data-field-opened') && ($oFieldNode->getAttribute('data-field-opened') === 'true') )
                {
                    $aFieldsExtraData[$sFieldId]['opened'] = true;
                }
                // Checking field display mode
                if($oFieldNode->hasAttribute('data-field-display-mode') && $oFieldNode->getAttribute('data-field-display-mode') !== '')
                {
                    $aFieldsExtraData[$sFieldId]['display_mode'] = $oFieldNode->getAttribute('data-field-display-mode');
                }
                elseif(isset($this->aFormProperties['properties']['display_mode']))
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
		    if($this->IsTransitionForm())
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
                if(in_array($sAttCode, $aFieldsDMOnlyAttCodes))
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
                if($this->IsTransitionForm() && !array_key_exists($sAttCode, $aFieldsAtts))
                {
                    if( (($value & OPT_ATT_MANDATORY) === OPT_ATT_MANDATORY && $oAttDef->IsNull($this->oObject->Get($sAttCode)))
                        || (($value & OPT_ATT_MUSTPROMPT) === OPT_ATT_MUSTPROMPT)
                        || (($value & OPT_ATT_MUSTCHANGE) === OPT_ATT_MUSTCHANGE))
                    {
                        if(!in_array($sAttCode, $aFieldsDMOnlyAttCodes))
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
		foreach($aFieldsDMOnlyAttCodes as $sAttCode)
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
            if(($iFieldFlags & OPT_ATT_MUSTCHANGE) === OPT_ATT_MUSTCHANGE)
            {
                $aFieldsAtts[$sAttCode] = $aFieldsAtts[$sAttCode] | OPT_ATT_MUSTCHANGE;
            }
            // Checking if field should be must_prompt
            if(($iFieldFlags & OPT_ATT_MUSTPROMPT) === OPT_ATT_MUSTPROMPT)
            {
                $aFieldsAtts[$sAttCode] = $aFieldsAtts[$sAttCode] | OPT_ATT_MUSTPROMPT;
            }
            // Checking if field should be mandatory
            if(($iFieldFlags & OPT_ATT_MANDATORY) === OPT_ATT_MANDATORY)
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
			if (is_callable(get_class($oAttDef) . '::MakeFormField'))
			{
				$oField = $oAttDef->MakeFormField($this->oObject);
			}
			
			// Failsafe for AttributeType that would not have MakeFormField and therefore could not be used in a form
			if($oField !== null)
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
                        if($this->IsTransitionForm())
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
                        if($this->IsTransitionForm() && $oAttDef->IsNull($this->oObject->Get($sAttCode)))
                        {
                            $oField->SetMandatory(true);
                        }
                        // .. and has no value, we force it as mandatory
                        elseif($oAttDef->IsNull($this->oObject->Get($sAttCode)))
                        {
                            $oField->SetMandatory(true);
                        }
                    }

					// Specific operation on field
					// - Field that require a transaction id
					if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\TextAreaField', 'Combodo\\iTop\\Form\\Field\\CaseLogField')))
					{
						$oField->SetTransactionId($oForm->GetTransactionId());
					}
					// - Field that require a search endpoint
					if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\SelectObjectField', 'Combodo\\iTop\\Form\\Field\\LinkedSetField')))
					{
						if ($this->oApp !== null)
						{

							$sSearchEndpoint = $this->oApp['url_generator']->generate('p_object_search_generic', array(
								'sTargetAttCode' => $oAttDef->GetCode(),
								'sHostObjectClass' => get_class($this->oObject),
								'sHostObjectId' => ($this->oObject->IsNew()) ? null : $this->oObject->GetKey(),
								'ar_token' => $this->GetActionRulesToken(),
							));
							$oField->SetSearchEndpoint($sSearchEndpoint);
						}
					}
					// - Field that require an information endpoint
					if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\LinkedSetField')))
					{
						if ($this->oApp !== null)
						{
							$oField->SetInformationEndpoint($this->oApp['url_generator']->generate('p_object_get_informations_json'));
						}
					}
					// - Field that require to apply scope on its DM OQL
					if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\SelectObjectField')))
					{
						if ($this->oApp !== null)
						{
							$oScopeOriginal = ($oField->GetSearch() !== null) ? $oField->GetSearch() : DBSearch::FromOQL($oAttDef->GetValuesDef()->GetFilterExpression());

							$oScopeSearch = $this->oApp['scope_validator']->GetScopeFilterForProfiles(UserRights::ListProfiles(), $oScopeOriginal->GetClass(), UR_ACTION_READ);
							if ($oScopeSearch === null)
							{
								IssueLog::Info(__METHOD__ . ' at line ' . __LINE__ . ' : User #' . UserRights::GetUserId() . ' has no scope query for ' . $oScopeOriginal->GetClass() . ' class.');
								$this->oApp->abort(404, Dict::S('UI:ObjectDoesNotExist'));
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
						$oField->VerifyCurrentValue();
                    }
					// - Field that require processing on their subfields
					if (in_array(get_class($oField), array('Combodo\\iTop\\Form\\Field\\SubFormField')))
					{
						$oSubForm = $oField->GetForm();
						if ($oAttDef->GetEditClass() === 'CustomFields')
						{
							// Retrieving only user data fields (not the metadata fields of the template)
							if ($oSubForm->HasField('user_data'))
							{
								$oUserDataField = $oSubForm->GetField('user_data');
								$oUserDataForm = $oUserDataField->GetForm();
								foreach ($oUserDataForm->GetFields() as $oCustomField)
								{
									// - Field that require a search endpoint (OQL based dropdown list fields)
									if (in_array(get_class($oCustomField), array('Combodo\\iTop\\Form\\Field\\SelectObjectField')))
									{
										if ($this->oApp !== null)
										{

											$sSearchEndpoint = $this->oApp['url_generator']->generate('p_object_search_generic', array(
												'sTargetAttCode' => $oAttDef->GetCode(),
												'sHostObjectClass' => get_class($this->oObject),
												'sHostObjectId' => ($this->oObject->IsNew()) ? null : $this->oObject->GetKey(),
												'ar_token' => $this->GetActionRulesToken(),
											));
											$oCustomField->SetSearchEndpoint($sSearchEndpoint);
										}
									}
									// - Field that require to check if the current value is among allowed ones
									if (in_array(get_class($oCustomField), array('Combodo\\iTop\\Form\\Field\\SelectObjectField')))
									{
										$oCustomField->VerifyCurrentValue();
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
                    //   - Overriding attributes to display
					if ($this->oApp !== null)
					{
						// Note : This snippet is inspired from AttributeLinkedSet::MakeFormField()
						$aAttCodesToDisplay = ApplicationHelper::GetLoadedListFromClass($this->oApp, $oField->GetTargetClass(), 'list');
						// - Adding friendlyname attribute to the list is not already in it
						$sTitleAttCode = 'friendlyname';
						if (($sTitleAttCode !== null) && !in_array($sTitleAttCode, $aAttCodesToDisplay))
						{
							$aAttCodesToDisplay = array_merge(array($sTitleAttCode), $aAttCodesToDisplay);
						}
						// - Adding attribute labels
						$aAttributesToDisplay = array();
						foreach ($aAttCodesToDisplay as $sAttCodeToDisplay)
						{
							$oAttDefToDisplay = MetaModel::GetAttributeDef($oField->GetTargetClass(), $sAttCodeToDisplay);
							$aAttributesToDisplay[$sAttCodeToDisplay] = $oAttDefToDisplay->GetLabel();
						}
						$oField->SetAttributesToDisplay($aAttributesToDisplay);
					}
					//    - Displaying as opened
                    if(array_key_exists($sAttCode, $aFieldsExtraData) && array_key_exists('opened', $aFieldsExtraData[$sAttCode]))
                    {
                        $oField->SetDisplayOpened(true);
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
            if(array_key_exists($sAttCode, $aFieldsExtraData) && array_key_exists('display_mode', $aFieldsExtraData[$sAttCode]))
            {
                $oField->SetDisplayMode($aFieldsExtraData[$sAttCode]['display_mode']);
            }

            $oForm->AddField($oField);
		}

		// Checking dependencies to ensure that all needed fields are in the form
		// (This is kind of a garbage collector for dependancies)
		foreach ($oForm->GetDependencies() as $sImpactedFieldId => $aDependancies)
		{
			foreach ($aDependancies as $sDependancyFieldId)
			{
				if (!$oForm->HasField($sDependancyFieldId))
				{
					$oAttDef = MetaModel::GetAttributeDef(get_class($this->oObject), $sDependancyFieldId);
					$oField = $oAttDef->MakeFormField($this->oObject);
					$oField->SetHidden(true);

					$oForm->AddField($oField);
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
				$oField = new FileUploadField('attachments_for_form_' . $oForm->GetId());
				$oField->SetLabel(Dict::S('Portal:Attachments'))
					->SetUploadEndpoint($this->oApp['url_generator']->generate('p_object_attachment_add'))
					->SetDownloadEndpoint($this->oApp['url_generator']->generate('p_object_attachment_download', array('sAttachmentId' => '-sAttachmentId-')))
					->SetTransactionId($oForm->GetTransactionId())
					->SetAllowDelete($this->oApp['combodo.portal.instance.conf']['properties']['attachments']['allow_delete'])
					->SetObject($this->oObject);

				// Checking if we can edit attachments in the current state
                if (($this->sMode === static::ENUM_MODE_VIEW)
                    || AttachmentPlugIn::IsReadonlyState($this->oObject, $this->oObject->GetState(), AttachmentPlugIn::ENUM_GUI_PORTALS) === true
                    || $oForm->GetEditableFieldCount(true) === 0)
				{
					$oField->SetReadOnly(true);
				}

				// Adding attachements field in transition only if it is editable
				if(!$this->IsTransitionForm() || ($this->IsTransitionForm() && !$oField->GetReadOnly()) )
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
     * Calls all form fields OnCancel method in order to delegate them the cleanup;
     *
     * @param array $aArgs
     *
     * @throws \DeleteException
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
		// Then clean inlineimages from rich text editor such as TextareaField
		// Note : This could be done by TextareaField::OnCancel(), but we consider that could have been done in this form outside the field.
		// Also, it would require the field to know the transaction id which it doesn't as of today.
		InlineImage::OnFormCancel(utils::GetUploadTempId($this->oForm->GetTransactionId()));
		// Then clean attachments
		// TODO : This has to be refactored when the function from itop-attachent has been migrated into the core
		$this->CancelAttachments();
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
     * @param array $aArgs
     *
     * @return array
     *
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \MySQLException
     * @throws \MySQLHasGoneAwayException
     * @throws \OQLException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
	public function OnSubmit($aArgs = null)
	{
		$aData = array(
			'valid' => true,
			'messages' => array(
				'success' => array(),
				'warnings' => array(), // Not used as of today, just to show that the structure is ready for change like this.
				'error' => array()
			)
		);

		// Update object and form
		$this->OnUpdate($aArgs);

		// Check if form valid
		if ($this->oForm->Validate())
		{
			// The try catch is essentially to start a MySQL transaction in order to ensure that all or none objects are persisted when creating an object with links
			try
			{
				$sObjectClass = get_class($this->oObject);

				// Starting transaction
				CMDBSource::Query('START TRANSACTION');
				// Forcing allowed writing on the object if necessary. This is used in some particular cases.
				$bAllowWrite = ($sObjectClass === 'Person' && $this->oObject->GetKey() == UserRights::GetContactId());
				if ($bAllowWrite)
				{
					$this->oObject->AllowWrite(true);
				}

				// Writing object to DB
				$bActivateTriggers = (!$this->oObject->IsNew() && $this->oObject->IsModified());
				$bWasModified = $this->oObject->IsModified();
				try
				{
					$this->oObject->DBWrite();
				}
				catch (CoreCannotSaveObjectException $e)
				{
					throw new Exception($e->getHtmlMessage());
				}
				// Finalizing images link to object, otherwise it will be cleaned by the GC
				InlineImage::FinalizeInlineImages($this->oObject);
				// Finalizing attachments link to object
				// TODO : This has to be refactored when the function from itop-attachent has been migrated into the core
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
					$sTriggersQuery = $this->oApp['combodo.portal.instance.conf']['properties']['triggers_query'];
					if ($sTriggersQuery !== null)
					{
						$aParentClasses = MetaModel::EnumParentClasses($sObjectClass, ENUM_PARENT_CLASSES_ALL);
						$oTriggerSet = new DBObjectSet(DBObjectSearch::FromOQL($sTriggersQuery), array(), array('parent_classes' => $aParentClasses));
						while ($oTrigger = $oTriggerSet->Fetch())
						{
							$oTrigger->DoActivate($this->oObject->ToArgs('this'));
						}
					}
				}
				// Removing transaction id from DB
				// TODO : utils::RemoveTransaction($this->oForm->GetTransactionId()); ?
				// Ending transaction with a commit as everything was fine
				CMDBSource::Query('COMMIT');

				// Resetting caselog fields value, otherwise the value will stay in it after submit.
                $this->oForm->ResetCaseLogFields();

				if ($bWasModified)
				{
					$aData['messages']['success'] += array('_main' => array(Dict::S('Brick:Portal:Object:Form:Message:Saved')));
				}
			}
			catch (Exception $e)
			{
				// End transaction with a rollback as something failed
				CMDBSource::Query('ROLLBACK');
				$aData['valid'] = false;
				$aData['messages']['error'] += array('_main' => array($e->getMessage()));
				IssueLog::Error(__METHOD__ . ' at line ' . __LINE__ . ' : Rollback during submit (' . $e->getMessage() . ')');
			}
		}
		else
		{
			// Handle errors
			$aData['valid'] = false;
			$aData['messages']['error'] += $this->oForm->GetErrorMessages();
		}

		return $aData;
	}

    /**
     * Updates the form and its fields with the current values
     *
     * Note : Doesn't update the object, see ObjectFormManager::OnSubmit() for that;
     *
     * @param array $aArgs
     *
     * @throws \Exception
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \OQLException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
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
							// Parsing JSON value
							//
							// Note : The value was passed as a string instead of an array because the attribute would not be included in the $aCurrentValues when empty.
							// Which was an issue when deleting all objects from linkedset
							$value = json_decode($value, true);

							/** @var \ormLinkSet $oLinkSet */
                            $oLinkSet = $this->oObject->Get($sAttCode);
                            $sLinkedClass = $oAttDef->GetLinkedClass();

                            // Checking links to remove
                            if(isset($value['remove']))
                            {
                                foreach($value['remove'] as $iObjKey => $aObjData)
                                {
                                    $oLinkSet->RemoveItem($iObjKey);
                                }
                            }

							// Checking links to add
                            if(isset($value['add']))
                            {
                                foreach($value['add'] as $iObjKey => $aObjdata)
                                {
                                    // Creating link when linkset is indirect...
                                    if($oAttDef->IsIndirect())
                                    {
                                        $oLink = MetaModel::NewObject($sLinkedClass);
                                        $oLink->Set($oAttDef->GetExtKeyToRemote(), $iObjKey);
                                        $oLink->Set($oAttDef->GetExtKeyToMe(), $this->oObject->GetKey());
                                    }
                                    // ... or adding remote object when linkset id direct
                                    else
                                    {
                                        // Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
                                        $oLink = MetaModel::GetObject($sLinkedClass, $iObjKey, false, true);
                                    }

                                    if($oLink !== null)
                                    {
                                        $oLinkSet->AddItem($oLink);
                                    }
                                }
                            }

                            // Checking links to modify
                            // TODO: Not implemented yet as we can't change lnk properties in the portal

							// Setting value in the object
							$this->oObject->Set($sAttCode, $oLinkSet);
						}
						elseif ($oAttDef instanceof AttributeTagSet)
						{
							/** @var \ormTagSet $oTagSet */
							$oTagSet = $this->oObject->Get($sAttCode);
							if (is_null($oTagSet))
							{
								$oTagSet = new ormTagSet(get_class($this->oObject), $sAttCode, $oAttDef->GetMaxItems());
							}
							$oTagSet->ApplyDelta(json_decode($value, true));
							$this->oObject->Set($sAttCode, $oTagSet);
						}
					    elseif ($oAttDef instanceof AttributeDateTime) // AttributeDate is derived from AttributeDateTime
					    {
						    if ($value != null)
						    {
							    $value = $oAttDef->GetFormat()->Parse($value);
								if (is_object($value))
								{
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
							if( isset($value['template_id'])
								&& ($value['template_id'] != $value['current_template_id']) )
							{
								$this->oObject->Set($sAttCode, $value);
							}
							// Same template, different fields
							elseif(isset($value['template_id'], $value['template_data'])
								&& ($value['template_id'] == $value['current_template_id'])
								&& ($value['template_data'] != $value['current_template_data']) )
							{
								$this->oObject->Set($sAttCode, $value);
							}
							// Update of current values
							elseif(isset($value['user_data']))
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
					$oAttachment->DBDelete();
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
			}
		}
	}

    /**
     * This is a temporary function until the Attachment refactoring is done. It should be remove once it's done.
     * It is inspired from itop-attachments/main.attachments.php / UpdateAttachments()
     *
     * @throws \OQLException
     * @throws \DeleteException
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
}
