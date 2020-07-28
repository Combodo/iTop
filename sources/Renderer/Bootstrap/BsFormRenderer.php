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

namespace Combodo\iTop\Renderer\Bootstrap;

use Combodo\iTop\Renderer\FormRenderer;
use Combodo\iTop\Form\Form;

/**
 * Description of formrenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BsFormRenderer extends FormRenderer
{
	const DEFAULT_RENDERER_NAMESPACE = 'Combodo\\iTop\\Renderer\\Bootstrap\\FieldRenderer\\';

	/**
	 * Default constructor
	 * 
	 * @param \Combodo\iTop\Form\Form $oForm
	 */
	public function __construct(Form $oForm = null)
	{
		parent::__construct($oForm);
		$this->AddSupportedField('HiddenField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('LabelField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('PasswordField', 'BsSimpleFieldRenderer');
        $this->AddSupportedField('StringField', 'BsSimpleFieldRenderer');
        $this->AddSupportedField('UrlField', 'BsSimpleFieldRenderer');
        $this->AddSupportedField('EmailField', 'BsSimpleFieldRenderer');
        $this->AddSupportedField('PhoneField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('TextAreaField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('CaseLogField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('SelectField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('MultipleSelectField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('RadioField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('CheckboxField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('SubFormField', 'BsSubFormFieldRenderer');
		$this->AddSupportedField('SelectObjectField', 'BsSelectObjectFieldRenderer');
		$this->AddSupportedField('LinkedSetField', 'BsLinkedSetFieldRenderer');
		$this->AddSupportedField('SetField', 'BsSetFieldRenderer');
		$this->AddSupportedField('TagSetField', 'BsSetFieldRenderer');
		$this->AddSupportedField('DateTimeField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('DurationField', 'BsSimpleFieldRenderer');
		$this->AddSupportedField('FileUploadField', 'BsFileUploadFieldRenderer');
        $this->AddSupportedField('BlobField', 'BsSimpleFieldRenderer');
        $this->AddSupportedField('ImageField', 'BsSimpleFieldRenderer');
	}

}
