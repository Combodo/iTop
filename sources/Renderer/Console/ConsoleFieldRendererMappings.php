<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Renderer\Console;

use Combodo\iTop\Form\Field\DateTimeField;
use Combodo\iTop\Form\Field\DurationField;
use Combodo\iTop\Form\Field\HiddenField;
use Combodo\iTop\Form\Field\LabelField;
use Combodo\iTop\Form\Field\RadioField;
use Combodo\iTop\Form\Field\SelectField;
use Combodo\iTop\Form\Field\SelectObjectField;
use Combodo\iTop\Form\Field\StringField;
use Combodo\iTop\Form\Field\SubFormField;
use Combodo\iTop\Form\Field\TextAreaField;
use Combodo\iTop\Renderer\Console\FieldRenderer\ConsoleSelectObjectFieldRenderer;
use Combodo\iTop\Renderer\Console\FieldRenderer\ConsoleSimpleFieldRenderer;
use Combodo\iTop\Renderer\Console\FieldRenderer\ConsoleSubFormFieldRenderer;
use iFieldRendererMappingsExtension;

/**
 * Class ConsoleFieldRendererMappings
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Renderer\Console
 * @since 3.1.0 N°6041
 */
class ConsoleFieldRendererMappings implements iFieldRendererMappingsExtension
{

	/**
	 * @inheritDoc
	 */
	public static function RegisterSupportedFields(): array
	{
		return [
			[
				'field' => HiddenField::class,
				'form_renderer' => ConsoleFormRenderer::class,
				'field_renderer' => ConsoleSimpleFieldRenderer::class,
			],
			[
				'field' => LabelField::class,
				'form_renderer' => ConsoleFormRenderer::class,
				'field_renderer' => ConsoleSimpleFieldRenderer::class,
			],
			[
				'field' => StringField::class,
				'form_renderer' => ConsoleFormRenderer::class,
				'field_renderer' => ConsoleSimpleFieldRenderer::class,
			],
			[
				'field' => SelectField::class,
				'form_renderer' => ConsoleFormRenderer::class,
				'field_renderer' => ConsoleSimpleFieldRenderer::class,
			],
			[
				'field' => TextAreaField::class,
				'form_renderer' => ConsoleFormRenderer::class,
				'field_renderer' => ConsoleSimpleFieldRenderer::class,
			],
			[
				'field' => RadioField::class,
				'form_renderer' => ConsoleFormRenderer::class,
				'field_renderer' => ConsoleSimpleFieldRenderer::class,
			],
			[
				'field' => DurationField::class,
				'form_renderer' => ConsoleFormRenderer::class,
				'field_renderer' => ConsoleSimpleFieldRenderer::class,
			],
			[
				'field' => SelectObjectField::class,
				'form_renderer' => ConsoleFormRenderer::class,
				'field_renderer' => ConsoleSelectObjectFieldRenderer::class,
			],
			[
				'field' => SubFormField::class,
				'form_renderer' => ConsoleFormRenderer::class,
				'field_renderer' => ConsoleSubFormFieldRenderer::class,
			],
			[
				'field' => DateTimeField::class,
				'form_renderer' => ConsoleFormRenderer::class,
				'field_renderer' => ConsoleSimpleFieldRenderer::class,
			],
		];
	}
}