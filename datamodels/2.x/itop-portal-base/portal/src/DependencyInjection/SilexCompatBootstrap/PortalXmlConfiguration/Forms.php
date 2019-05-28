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

/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 24/01/19
 * Time: 16:52
 */

namespace Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Exception;
use utils;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use MetaModel;

class Forms extends AbstractConfiguration
{
    public function process(ContainerBuilder $container)
    {
        $aForms = array();

        foreach ($this->getModuleDesign()->GetNodes('/module_design/forms/form') as $oFormNode)
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
                    // Parsing class
                    $sFormClass = $oFormNode->GetUniqueElement('class')->GetText();

                    // Parsing properties
                    $aFormProperties = array(
                        'display_mode' => ApplicationHelper::FORM_DEFAULT_DISPLAY_MODE,
                        'always_show_submit' => ApplicationHelper::FORM_DEFAULT_ALWAYS_SHOW_SUBMIT,
                    );
                    if ($oFormNode->GetOptionalElement('properties') !== null)
                    {
                        foreach ($oFormNode->GetOptionalElement('properties')->childNodes as $oPropertyNode)
                        {
                            switch ($oPropertyNode->nodeName)
                            {
                                case 'display_mode':
                                    $aFormProperties['display_mode'] = $oPropertyNode->GetText(ApplicationHelper::FORM_DEFAULT_DISPLAY_MODE);
                                    break;
                                case 'always_show_submit':
                                    $aFormProperties['always_show_submit'] = ($oPropertyNode->GetText('false') === 'true') ? true : false;
                                    break;
                            }
                        }
                    }

                    // Parsing availables modes for that form (view, edit, create, apply_stimulus)
                    $aFormStimuli = array();
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
                                throw new DOMFormatException('Mode tag must have an id attribute', null, null,
                                    $oFormNode);
                            }

                            // If apply_stimulus mode, checking if stimuli are defined
                            if ($oModeNode->getAttribute('id') === 'apply_stimulus')
                            {
                                $oStimuliNode = $oModeNode->GetOptionalElement('stimuli');
                                // if stimuli are defined, we overwrite the form that could have been set by the generic form
                                if ($oStimuliNode !== null)
                                {
                                    foreach ($oStimuliNode->GetNodes('stimulus') as $oStimulusNode)
                                    {
                                        $sStimulusCode = $oStimulusNode->getAttribute('id');

                                        // Removing default form is present (in case the default forms were parsed before the current one (from current or parent class))
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
                        'id' => $oFormNode->getAttribute('id'),
                        'type' => null,
                        'properties' => $aFormProperties,
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
                        $sXml = $this->getModuleDesign()->saveXML($oFormNode->GetOptionalElement('twig'));
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
                        // Initializing current class if necessary
                        if (!isset($aForms[$sFormClass]))
                        {
                            $aForms[$sFormClass] = array();
                        }

                        if ($sMode === 'apply_stimulus')
                        {
                            // Iterating over current class and child classes to fill stimuli forms
                            foreach (MetaModel::EnumChildClasses($sFormClass, ENUM_CHILD_CLASSES_ALL) as $sChildClass)
                            {
                                // Initializing child class if necessary
                                if (!isset($aForms[$sChildClass][$sMode]))
                                {
                                    $aForms[$sChildClass][$sMode] = array();
                                }

                                // If stimuli are implicitly defined (empty tag), we define all those that have not already been by other forms.
                                $aChildStimuli = $aFormStimuli;
                                if (empty($aChildStimuli))
                                {
                                    // Stimuli already declared
                                    $aDeclaredStimuli = array();
                                    if (array_key_exists($sChildClass, $aForms) && array_key_exists('apply_stimulus',
                                            $aForms[$sChildClass]))
                                    {
                                        $aDeclaredStimuli = array_keys($aForms[$sChildClass]['apply_stimulus']);
                                    }
                                    // All stimuli
                                    $aDatamodelStimuli = array_keys(MetaModel::EnumStimuli($sChildClass));
                                    // Missing stimuli
                                    $aChildStimuli = array_diff($aDatamodelStimuli, $aDeclaredStimuli);
                                }

                                foreach ($aChildStimuli as $sFormStimulus)
                                {
                                    // Setting form if not defined OR if it was defined by a parent (abstract) class
                                    if (!isset($aForms[$sChildClass][$sMode][$sFormStimulus]) || !empty($aFormStimuli))
                                    {
                                        $aForms[$sChildClass][$sMode][$sFormStimulus] = $aFields;
                                        $aForms[$sChildClass][$sMode][$sFormStimulus]['id'] = 'apply_stimulus-'.$sChildClass.'-'.$sFormStimulus;
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
                            throw new \DOMFormatException('There is already a form for the class "'.$sFormClass.'" in "'.$sMode.'"',
                                null, null, $oFormNode);
                        }
                    }
                }
                else
                {
                    throw new \DOMFormatException('Class tag must be defined', null, null, $oFormNode);
                }
            }
            catch (\DOMFormatException $e)
            {
                throw new \Exception('Could not create from [id="'.$oFormNode->getAttribute('id').'"] from XML because of a DOM problem : '.$e->getMessage());
            }
            catch (\Exception $e)
            {
                throw new \Exception('Could not create from from XML : '.$oFormNode->Dump().' '.$e->getMessage());
            }
        }

        $aPortalConf = $container->getParameter('combodo.portal.instance.conf');
	    $aPortalConf['forms']  = $aForms;
	    $container->setParameter('combodo.portal.instance.conf', $aPortalConf);
    }


}