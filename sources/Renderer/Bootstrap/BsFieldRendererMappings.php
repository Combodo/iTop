<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Renderer\Bootstrap;

use Combodo\iTop\Form\Field\BlobField;
use Combodo\iTop\Form\Field\CaseLogField;
use Combodo\iTop\Form\Field\CheckboxField;
use Combodo\iTop\Form\Field\DateTimeField;
use Combodo\iTop\Form\Field\DurationField;
use Combodo\iTop\Form\Field\EmailField;
use Combodo\iTop\Form\Field\FileUploadField;
use Combodo\iTop\Form\Field\HiddenField;
use Combodo\iTop\Form\Field\ImageField;
use Combodo\iTop\Form\Field\LabelField;
use Combodo\iTop\Form\Field\LinkedSetField;
use Combodo\iTop\Form\Field\MultipleSelectField;
use Combodo\iTop\Form\Field\PasswordField;
use Combodo\iTop\Form\Field\PhoneField;
use Combodo\iTop\Form\Field\RadioField;
use Combodo\iTop\Form\Field\SelectField;
use Combodo\iTop\Form\Field\SelectObjectField;
use Combodo\iTop\Form\Field\SetField;
use Combodo\iTop\Form\Field\StringField;
use Combodo\iTop\Form\Field\SubFormField;
use Combodo\iTop\Form\Field\TagSetField;
use Combodo\iTop\Form\Field\TextAreaField;
use Combodo\iTop\Form\Field\UrlField;
use Combodo\iTop\Renderer\Bootstrap\FieldRenderer\BsFileUploadFieldRenderer;
use Combodo\iTop\Renderer\Bootstrap\FieldRenderer\BsLinkedSetFieldRenderer;
use Combodo\iTop\Renderer\Bootstrap\FieldRenderer\BsSelectObjectFieldRenderer;
use Combodo\iTop\Renderer\Bootstrap\FieldRenderer\BsSetFieldRenderer;
use Combodo\iTop\Renderer\Bootstrap\FieldRenderer\BsSimpleFieldRenderer;
use Combodo\iTop\Renderer\Bootstrap\FieldRenderer\BsSubFormFieldRenderer;
use iFieldRendererMappingsExtension;

/**
 * Class BsFieldRendererMappings
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Renderer\Bootstrap
 * @since 3.1.0 N°6041
 */
class BsFieldRendererMappings implements iFieldRendererMappingsExtension
{

	/**
	 * @inheritDoc
	 */
	public static function RegisterSupportedFields(): array
	{
		return [
			[
				HiddenField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				LabelField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				PasswordField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				StringField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				UrlField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				EmailField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				PhoneField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				TextAreaField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				CaseLogField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				SelectField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				MultipleSelectField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				RadioField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				CheckboxField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				SubFormField::class,
				BsFormRenderer::class,
				BsSubFormFieldRenderer::class,
			],
			[
				SelectObjectField::class,
				BsFormRenderer::class,
				BsSelectObjectFieldRenderer::class,
			],
			[
				LinkedSetField::class,
				BsFormRenderer::class,
				BsLinkedSetFieldRenderer::class,
			],
			[
				SetField::class,
				BsFormRenderer::class,
				BsSetFieldRenderer::class,
			],
			[
				TagSetField::class,
				BsFormRenderer::class,
				BsSetFieldRenderer::class,
			],
			[
				DateTimeField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				DurationField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				FileUploadField::class,
				BsFormRenderer::class,
				BsFileUploadFieldRenderer::class,
			],
			[
				BlobField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
			[
				ImageField::class,
				BsFormRenderer::class,
				BsSimpleFieldRenderer::class,
			],
		];
	}
}