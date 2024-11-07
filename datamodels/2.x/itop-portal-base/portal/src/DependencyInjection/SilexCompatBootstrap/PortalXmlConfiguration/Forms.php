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

namespace Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration;

use Combodo\iTop\Portal\Helper\NavigationRuleHelper;
use Symfony\Component\DependencyInjection\Container;
use DOMFormatException;
use Exception;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use MetaModel;

/**
 * Class Forms
 *
 * @package Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.7.0
 */
class Forms extends AbstractConfiguration
{
	/**
	 * @param \Symfony\Component\DependencyInjection\Container $oContainer
	 *
	 * @throws \Exception
	 */
	public function Process(Container $oContainer)
	{
		$aForms = array();

		/** @var \MFElement $oFormNode */
		foreach ($this->GetModuleDesign()->GetNodes('/module_design/forms/form') as $oFormNode)
		{
			try
			{
				// Parsing form id
				$sFormId = $oFormNode->getAttribute('id');
				if ($oFormNode->getAttribute('id') === '')
				{
					throw new DOMFormatException('form tag must have an id attribute', null, null, $oFormNode);
				}

				// Parsing form object class
				if ($oFormNode->GetUniqueElement('class')->GetText() === null)
				{
					throw new DOMFormatException('Class tag must be defined', null, null, $oFormNode);
				}

				// Parsing class
				$sFormClass = $oFormNode->GetUniqueElement('class')->GetText();

				// Parsing properties
				$aFormProperties = array(
					'display_mode' => ApplicationHelper::FORM_DEFAULT_DISPLAY_MODE,
					'always_show_submit' => ApplicationHelper::FORM_DEFAULT_ALWAYS_SHOW_SUBMIT,
					'navigation_rules' => array(
						'submit' => array(
							NavigationRuleHelper::ENUM_ORIGIN_PAGE => null,
							NavigationRuleHelper::ENUM_ORIGIN_MODAL => null,
						),
						'cancel' => array(
							NavigationRuleHelper::ENUM_ORIGIN_PAGE => null,
							NavigationRuleHelper::ENUM_ORIGIN_MODAL => null,
						),
					),
				);

				$aAllowedNavRulesButtonCodes = array_keys($aFormProperties['navigation_rules']);
				if ($oFormNode->GetOptionalElement('properties') !== null)
				{
					/** @var \MFElement $oPropertyNode */
					foreach ($oFormNode->GetOptionalElement('properties')->GetNodes('*') as $oPropertyNode)
					{
						switch ($oPropertyNode->nodeName)
						{
							case 'display_mode':
								$aFormProperties['display_mode'] = $oPropertyNode->GetText(ApplicationHelper::FORM_DEFAULT_DISPLAY_MODE);
								break;

							case 'always_show_submit':
								$aFormProperties['always_show_submit'] = ($oPropertyNode->GetText('false') === 'true') ? true : false;
								break;

							case 'navigation_rules':
								/** @var \MFElement $oNavRuleButtonNode */
								foreach($oPropertyNode->GetNodes('*') as $oNavRuleButtonNode)
								{
									$sNavRuleButtonCode = $oNavRuleButtonNode->nodeName;
									if(!in_array($sNavRuleButtonCode, $aAllowedNavRulesButtonCodes))
									{
										throw new DOMFormatException('navigation_rules tag must only contain '.implode('|', $aAllowedNavRulesButtonCodes).' tags, "'.$sNavRuleButtonCode.'" given.', null, null, $oPropertyNode);
									}

									/** @var \MFElement $oNavRuleOriginNode */
									foreach($oNavRuleButtonNode->GetNodes('*') as $oNavRuleOriginNode)
									{
										$sNavRuleOrigin = $oNavRuleOriginNode->nodeName;
										if(!in_array($sNavRuleOrigin, NavigationRuleHelper::GetAllowedOrigins()))
										{
											throw new DOMFormatException($sNavRuleButtonCode. ' tag must only contain '.implode('|', NavigationRuleHelper::GetAllowedOrigins()).' tags, "'.$sNavRuleOrigin.'" given.', null, null, $oPropertyNode);
										}

										$sNavRuleId = $oNavRuleOriginNode->GetText();
										// Note: We don't check is rule exists as it would introduce a dependency to the NavigationRuleHelper service.
										// Maybe we will consider it later.
										if(empty($sNavRuleId))
										{
											throw new DOMFormatException($sNavRuleButtonCode.' tag cannot be empty.', null, null, $oPropertyNode);
										}

										$aFormProperties['navigation_rules'][$sNavRuleButtonCode][$sNavRuleOrigin] = $sNavRuleId;
									}

									// Set modal rule as the same as default is not present.
									// We preset it so we don't have to make checks elsewhere in the code when using it.
									if(empty($aFormProperties['navigation_rules'][$sNavRuleButtonCode][NavigationRuleHelper::ENUM_ORIGIN_MODAL]))
									{
										$aFormProperties['navigation_rules'][$sNavRuleButtonCode][NavigationRuleHelper::ENUM_ORIGIN_MODAL] = $aFormProperties['navigation_rules'][$sNavRuleButtonCode][NavigationRuleHelper::ENUM_ORIGIN_PAGE];
									}
								}
						}
					}
				}

				// Parsing available modes for that form (view, edit, create, apply_stimulus)
				$aFormStimuli = array();
				if (($oFormNode->GetOptionalElement('modes') !== null) && ($oFormNode->GetOptionalElement('modes')->GetNodes('mode')->length > 0))
				{
					$aModes = array();
					/** @var \MFElement $oModeNode */
					foreach ($oFormNode->GetOptionalElement('modes')->GetNodes('mode') as $oModeNode)
					{
						$sModeId = $oModeNode->getAttribute('id');
						if ($sModeId === '')
						{
							throw new DOMFormatException('mode tag must have an id attribute', null, null,
								$oFormNode);
						}
						$aModes[] = $sModeId;

						// If apply_stimulus mode, checking if stimuli are defined
						if ($sModeId === 'apply_stimulus')
						{
							$oStimuliNode = $oModeNode->GetOptionalElement('stimuli');
							// If stimuli are defined, we overwrite the form that could have been set by the generic form
							if ($oStimuliNode !== null)
							{
								/** @var \MFElement $oStimulusNode */
								foreach ($oStimuliNode->GetNodes('stimulus') as $oStimulusNode)
								{
									$sStimulusCode = $oStimulusNode->getAttribute('id');

									// Removing default form if present (in case the default forms were parsed before the current one (from current or parent class))
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
					'_brought_by' => $sFormClass,
					'id' => $sFormId,
					'type' => null,
					'properties' => $aFormProperties,
					'fields' => null,
					'layout' => null,
				);
				// ... either enumerated fields ...
				if ($oFormNode->GetOptionalElement('fields') !== null)
				{
					$aFields['type'] = 'custom_list';
					$aFields['fields'] = array();

					/** @var \MFElement $oFieldNode */
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
					$sXml = $this->GetModuleDesign()->saveXML($oFormNode->GetOptionalElement('twig'));
					$sXml = preg_replace('/^.+\n/', '', $sXml);
					$sXml = preg_replace('/\n.+$/', '', $sXml);

					$aFields['layout'] = array(
						'type' => (preg_match('/{{|{#|{%/', $sXml) === 1) ? 'twig' : 'xhtml',
						'content' => $sXml,
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

					// For stimuli we need to fill the matrix as only some stimuli might have been given
					if ($sMode === 'apply_stimulus')
					{
						// Iterating over current class and child classes
						foreach (MetaModel::EnumChildClasses($sFormClass, ENUM_CHILD_CLASSES_ALL) as $sChildClass)
						{
							// Initializing child class if necessary
							if (!isset($aForms[$sChildClass][$sMode]))
							{
								$aForms[$sChildClass][$sMode] = array();
							}

							// If no explicit stimulus defined in this form, than it's the generic stimulus form
							// we need to find which stimulus are missing
							if(empty($aFormStimuli))
							{
								$aExistingStimuli = array();
								// Keep only stimuli brought by the class itself
								foreach($aForms[$sChildClass][$sMode] as $sExistingStimulus => $aExistingForm)
								{
									if(!in_array($aExistingForm['_brought_by'], MetaModel::EnumParentClasses($sFormClass, ENUM_PARENT_CLASSES_EXCLUDELEAF)))
									{
										//continue;
										$aExistingStimuli[] = $sExistingStimulus;
									}
								}
								$aDatamodelStimuli = array_keys(MetaModel::EnumStimuli($sChildClass));
								$aMissingStimulusForms = array_diff($aDatamodelStimuli, $aExistingStimuli);
							}
							// Otherwise, we process only the ones for this form
							else
							{
								$aMissingStimulusForms = $aFormStimuli;
							}

							// Retrieve missing stimuli of the child class to fill the matrix
							foreach ($aMissingStimulusForms as $sDatamodelStimulus)
							{
								// Check some facts about the target form
								$bFormExists = isset($aForms[$sChildClass][$sMode][$sDatamodelStimulus]);
								$bWasFormBroughtByParent = $bFormExists && in_array($aForms[$sChildClass][$sMode][$sDatamodelStimulus]['_brought_by'], MetaModel::EnumParentClasses($sFormClass, ENUM_PARENT_CLASSES_EXCLUDELEAF));

								// Check if we need to overwrite (form created by parent)
								$bOverwriteNecessary = false;
								if($bWasFormBroughtByParent || in_array($sDatamodelStimulus, $aFormStimuli))
								{
									$bOverwriteNecessary = true;
								}

								// Setting form if not defined OR if it was defined by a parent (abstract) class
								if (!$bFormExists || $bOverwriteNecessary)
								{
									$aForms[$sChildClass][$sMode][$sDatamodelStimulus] = $aFields;
									$aForms[$sChildClass][$sMode][$sDatamodelStimulus]['id'] = 'apply_stimulus-'.$sChildClass.'-'.$sDatamodelStimulus;
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
			catch (DOMFormatException $e)
			{
				throw new Exception('Could not create from [id="'.$oFormNode->getAttribute('id').'"] from XML because of a DOM problem : '.$e->getMessage());
			}
			catch (Exception $e)
			{
				throw new Exception('Could not create from from XML : '.$oFormNode->Dump().' '.$e->getMessage());
			}
		}

		$aPortalConf = $oContainer->getParameter('combodo.portal.instance.conf');
		$aPortalConf['forms'] = $aForms;
		$oContainer->setParameter('combodo.portal.instance.conf', $aPortalConf);
	}
}