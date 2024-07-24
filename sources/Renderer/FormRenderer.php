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

namespace Combodo\iTop\Renderer;

use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Exception;
use Combodo\iTop\Form\Form;
use Combodo\iTop\Form\Field\Field;
use iFieldRendererMappingsExtension;

/**
 * Description of FormRenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
abstract class FormRenderer
{
	const ENUM_RENDER_MODE_EXPLODED = 'exploded';
	const ENUM_RENDER_MODE_JOINED = 'joined';
	const DEFAULT_RENDERER_NAMESPACE = '';

	protected $oForm;
	protected $sEndpoint;
	protected $aSupportedFields;
	protected $sBaseLayout;
	protected $aOutputs;

	/**
	 * Default constructor
	 *
	 * @param \Combodo\iTop\Form\Form $oForm
	 */
	public function __construct(Form $oForm = null)
	{
		if ($oForm !== null)
		{
			$this->oForm = $oForm;
		}
		$this->aSupportedFields = [];
		$this->sBaseLayout = '';
		$this->InitOutputs();

		/** @var iFieldRendererMappingsExtension $sImplementingClass */
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iFieldRendererMappingsExtension::class) as $sImplementingClass) {
			$aFieldRendererMappings = $sImplementingClass::RegisterSupportedFields();
			// For each mapping we need to check if it can be registered for the current form renderer or not
			foreach ($aFieldRendererMappings as $aFieldRendererMapping) {
				$sFieldClass = $aFieldRendererMapping['field'];
				$sFormRendererClass = $aFieldRendererMapping['form_renderer'];
				$sFieldRendererClass = $aFieldRendererMapping['field_renderer'];

				// Mapping not concerning current form renderer, skip it
				if (false === is_a(static::class, $sFormRendererClass, true)) {
					continue;
				}

				$this->AddSupportedField($sFieldClass, $sFieldRendererClass);
			}
		}
	}

	/**
	 *
	 * @return \Combodo\iTop\Form\Form
	 */
	public function GetForm()
	{
		return $this->oForm;
	}

	/**
	 *
	 * @param \Combodo\iTop\Form\Form $oForm
	 * @return \Combodo\iTop\Renderer\FormRenderer
	 */
	public function SetForm(Form $oForm)
	{
		$this->oForm = $oForm;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function GetEndpoint()
	{
		return $this->sEndpoint;
	}

	/**
	 *
	 * @param string $sEndpoint
	 * @return \Combodo\iTop\Renderer\FormRenderer
	 */
	public function SetEndpoint($sEndpoint)
	{
		$this->sEndpoint = $sEndpoint;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function GetBaseLayout()
	{
		return $this->sBaseLayout;
	}

	/**
	 *
	 * @param string $sBaseLayout
	 * @return \Combodo\iTop\Renderer\FormRenderer
	 */
	public function SetBaseLayout($sBaseLayout)
	{
		$this->sBaseLayout = $sBaseLayout;
		return $this;
	}

	/**
	 *
	 * @param \Combodo\iTop\Form\Field\Field $oField
	 *
     * @return string
     *
	 * @throws \Exception
	 */
	public function GetFieldRendererClass(Field $oField)
	{
		if (array_key_exists(get_class($oField), $this->aSupportedFields))
		{
			return $this->aSupportedFields[get_class($oField)];
		}
		else
		{
			throw new Exception('Field type not supported by the renderer: ' . get_class($oField));
		}
	}

    /**
     * Returns the field identified by the id $sId in $this->oForm.
     *
     * @param string $sId
     *
     * @return string
     *
     * @throws \Exception
     */
	public function GetFieldRendererClassFromId($sId)
	{
		return $this->GetFieldRendererClass($this->oForm->GetField($sId));
	}

	/**
	 *
	 * @return array
	 */
	public function GetOutputs()
	{
		return $this->aOutputs;
	}

    /**
     * Registers a Renderer class for the specified Field class.
     *
     * If the Field class is not fully qualified, the default "Combodo\iTop\Form\Field" will be prepend.
     * If the Field class already had a registered Renderer, it is replaced.
     *
     * @param string $sFieldClass
     * @param string $sRendererClass
     *
     * @return \Combodo\iTop\Renderer\FormRenderer
     */
	public function AddSupportedField($sFieldClass, $sRendererClass)
	{
		$sFieldClass = (strpos($sFieldClass, '\\') !== false) ? $sFieldClass : 'Combodo\\iTop\\Form\\Field\\' . $sFieldClass;
		$sRendererClass = (strpos($sRendererClass, '\\') !== false) ? $sRendererClass : static::DEFAULT_RENDERER_NAMESPACE . $sRendererClass;

		$this->aSupportedFields[$sFieldClass] = $sRendererClass;

		return $this;
	}

	/**
	 *
	 * @return \Combodo\iTop\Renderer\FormRenderer
	 */
	public function InitOutputs()
	{
		$this->aOutputs = array();
		return $this;
	}

    /**
     * Returns an array of Output for the form fields.
     *
     * @param array $aFieldIds An array of field ids. If specified, renders only those fields
     *
     * @return array
     *
     * @throws \Exception
     */
	public function Render($aFieldIds = null)
	{
		$this->InitOutputs();

		/** @var Field $oField */
        foreach ($this->oForm->GetFields() as $oField)
		{
			if ($aFieldIds !== null && !in_array($oField->GetId(), $aFieldIds))
			{
				continue;
			}
			$this->aOutputs[$oField->GetId()] = $this->PrepareOutputForField($oField);
		}

		return $this->aOutputs;
	}

    /**
     * Returns the output for the $oField. Output format depends on the $sMode.
     *
     * If $sMode = 'exploded', output is an has array with id / html / js_inline / js_files / css_inline / css_files / validators
     * Else if $sMode = 'joined', output is a string with everything in it
     *
     * @param \Combodo\iTop\Form\Field\Field $oField
     * @param string $sMode 'exploded'|'joined'
     *
     * @return array
     *
     * @throws \Exception
     */
	protected function PrepareOutputForField($oField, $sMode = 'exploded')
	{
		$output = array(
			'id' => $oField->GetId(),
			'html' => '',
			'html_metadata' => array(),
			'js_inline' => '',
			'css_inline' => '',
			'js_files' => array(),
			'css_files' => array(),
            'css_classes' => array(),
		);

		$sFieldRendererClass = $this->GetFieldRendererClass($oField);

		/** @var FieldRenderer $oFieldRenderer */
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
		// HTML metadata
		foreach ($oRenderingOutput->GetMetadata() as $sMetadataName => $sMetadataValue)
		{
			// Warning: Do not work with ENUM_RENDER_MODE_JOINED mode
			if ($sMode === static::ENUM_RENDER_MODE_EXPLODED)
			{
				if (!in_array($sMetadataName, $output['html_metadata']))
				{
					$output['html_metadata'][$sMetadataName] = $sMetadataValue;
				}
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
        // CSS classes
        if ($oRenderingOutput->GetHtml() !== '')
        {
            $output['css_classes'] = $oRenderingOutput->GetCssClasses();
        }

		return $output;
	}

}
