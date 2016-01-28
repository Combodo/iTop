<?php

// Copyright (C) 2010-2016 Combodo SARL
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

namespace Combodo\iTop\Renderer\Bootstrap;

use \Dict;
use \MetaModel;
use \Combodo\iTop\Renderer\FormRenderer;
use \Combodo\iTop\Form\Form;

/**
 * Description of formrenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BsFormRenderer extends FormRenderer
{
    const ENUM_RENDER_MODE_EXPLODED = 'exploded';
    const ENUM_RENDER_MODE_JOINED = 'joined';

    public function __construct(Form $oForm = null)
    {
        parent::__construct($oForm);
        $this->AddSupportedField('HiddenField', 'BsSimpleFieldRenderer');
        $this->AddSupportedField('StringField', 'BsSimpleFieldRenderer');
        $this->AddSupportedField('TextAreaField', 'BsSimpleFieldRenderer');
        $this->AddSupportedField('SelectField', 'BsSimpleFieldRenderer');
        $this->AddSupportedField('RadioField', 'BsSimpleFieldRenderer');
        $this->AddSupportedField('CheckboxField', 'BsSimpleFieldRenderer');
    }

    /**
     * Registers a Renderer class for the specified Field class.
     *
     * If the Field class is not fully qualified, a default namespace will be prepend (see FormRenderer::AddSupportedField).
     * If the Renderer clas is not fully qualified, the default "Combodo\iTop\Renderer\Bootstrap\FieldRenderer" will be prepend.
     *
     * @param string $sFieldClass
     * @param string $sRendererClass
     */
    public function AddSupportedField($sFieldClass, $sRendererClass)
    {
        $sRendererClass = (strpos($sRendererClass, '\\') !== false) ? $sRendererClass : 'Combodo\\iTop\\Renderer\\Bootstrap\\FieldRenderer\\' . $sRendererClass;

        parent::AddSupportedField($sFieldClass, $sRendererClass);
    }

    public function Render()
    {
        $this->InitOutputs();

        foreach ($this->oForm->GetFields() as $oField)
        {
            $this->aOutputs[] = $this->PrepareOutputForField($oField);
        }
        
        return $this->aOutputs;
    }

    /**
     * Renders a field of $oObject identified by its attribute code ($sFieldId).
     *
     * $sMode allows to defined if the result must a traditional array
     * containing the differents parts for the field or a string concataning all
     * those parts in one html tag.
     *
     * Typically, $sMode 'joined' is used when RenderField is called directly from a twig template.
     * Otherwise, the 'exploded' parameter is used to allow the renderer to optimize the assets.
     *
     * $iDesiredFlags is only used with $sMode = 'joined' to set the field flags as an information.
     *
     * @param cmdbAbstractObject $oObject
     * @param string $sFieldId
     * @param integer $iDesiredFlags
     * @param string $sMode 'joined'|'exploded'
     * @return mixed
     */
    public function RenderField($oObject, $sFieldId, $iDesiredFlags = OPT_ATT_NORMAL, $sMode = 'joined')
    {
        // Building field
        $oAttDef = MetaModel::GetAttributeDef(get_class($oObject), $sFieldId);
        $oField = $oAttDef->GetFormField($oObject);

        $aOutput = $this->PrepareOutputForField($oField, $sMode);

        if ($sMode === static::ENUM_RENDER_MODE_JOINED)
        {
            $res = '<div class="form_field" data-field-id="' . $oField->GetId() . '" data-field-flags="' . $iDesiredFlags . '">' .
                        $aOutput['html'] .
                    '</div>';
        }
        else
        {
            $res = $aOutput;
        }

        return $res;
    }

    /**
     * Returns the output for the $oField. Output format depends on the $sMode.
     *
     * If $sMode = 'exploded', output is an has array with id / html / js_inline / js_files / css_inline / css_files / validators
     * Else if $sMode = 'joined', output is a string with everything in it
     *
     * @param Combodo\iTop\Form\Field\Field $oField
     * @param string $sMode 'exploded'|'joined'
     * @return mixed
     */
    protected function PrepareOutputForField($oField, $sMode = 'exploded')
    {
        $output = array(
            'id' => $oField->GetId(),
            'html' => '',
            'js_inline' => '',
            'css_inline' => '',
            'js_files' => array(),
            'css_files' => array(),
            'validators' => null
        );

        $sFieldRendererClass = $this->GetFieldRendererClass($oField);
        // TODO : We might want to throw an exception instead when there is no renderer for that field
        if ($sFieldRendererClass !== null)
        {
            $oFieldRenderer = new $sFieldRendererClass($oField);
            $oFieldRenderer->SetEndpoint($this->GetEndpoint());

            $oRenderingOutput = $oFieldRenderer->Render();

            // HTML
            if ($oRenderingOutput->GetHtml() !== '')
            {
                if ($sMode === static::ENUM_RENDER_MODE_EXPLODED)
                {
                    $output['html'] = $oRenderingOutput->GetHtml();
                }
                else
                {
                    $output['html'] .= $oRenderingOutput->GetHtml();
                }
            }

            // JS files
            foreach ($oRenderingOutput->GetJsFiles() as $sJsFile)
            {
                if ($sMode === static::ENUM_RENDER_MODE_EXPLODED)
                {
                    if (!in_array($sJsFile, $output['js_files']))
                    {
                        $output['js_files'][] = $sJsFile;
                    }
                }
                else
                {
                    $output['html'] .= '<script src="' . $sJsFile . '" type="text/javascript"></script>';
                }
            }
            // JS inline
            if ($oRenderingOutput->GetJs() !== '')
            {
                if ($sMode === static::ENUM_RENDER_MODE_EXPLODED)
                {
                    $output['js_inline'] .= ' ' . $oRenderingOutput->GetJs();
                }
                else
                {
                    $output['html'] .= '<script type="text/javascript">' . $oRenderingOutput->GetJs() . '</script>';
                }
            }

            // CSS files
            foreach ($oRenderingOutput->GetCssFiles() as $sCssFile)
            {
                if ($sMode === static::ENUM_RENDER_MODE_EXPLODED)
                {
                    if (!in_array($sCssFile, $output['css_files']))
                    {
                    $output['css_files'][] = $sCssFile;
                    }
                }
                else
                {
                    $output['html'] .= '<link href="' . $sCssFile . '" rel="stylesheet" />';
                }
            }
            // CSS inline
            if ($oRenderingOutput->GetCss() !== '')
            {
                if ($sMode === static::ENUM_RENDER_MODE_EXPLODED)
                {
                    $output['css_inline'] .= ' ' . $oRenderingOutput->GetCss();
                }
                else
                {
                    $output['html'] .= '<style>' . $oRenderingOutput->GetCss() . '</style>';
                }
            }

            // Validators
            foreach ($oField->GetValidators() as $oValidator)
            {
                $output['validators'][$oValidator::GetName()] = array(
                    'reg_exp' => $oValidator->GetRegExp(),
                    'message' => Dict::S($oValidator->GetErrorMessage())
                );
            }

            // Subfields
            // TODO
        }

        return $output;
    }

}
