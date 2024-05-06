<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
				'field' => HiddenField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => LabelField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => PasswordField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => StringField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => UrlField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => EmailField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => PhoneField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => TextAreaField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => CaseLogField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => SelectField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => MultipleSelectField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => RadioField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => CheckboxField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => SubFormField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSubFormFieldRenderer::class,
			],
			[
				'field' => SelectObjectField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSelectObjectFieldRenderer::class,
			],
			[
				'field' => LinkedSetField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsLinkedSetFieldRenderer::class,
			],
			[
				'field' => SetField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSetFieldRenderer::class,
			],
			[
				'field' => TagSetField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSetFieldRenderer::class,
			],
			[
				'field' => DateTimeField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => DurationField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => FileUploadField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsFileUploadFieldRenderer::class,
			],
			[
				'field' => BlobField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
			[
				'field' => ImageField::class,
				'form_renderer' => BsFormRenderer::class,
				'field_renderer' => BsSimpleFieldRenderer::class,
			],
		];
	}
}