<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
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
				HiddenField::class,
				ConsoleFormRenderer::class,
				ConsoleSimpleFieldRenderer::class,
			],
			[
				LabelField::class,
				ConsoleFormRenderer::class,
				ConsoleSimpleFieldRenderer::class,
			],
			[
				StringField::class,
				ConsoleFormRenderer::class,
				ConsoleSimpleFieldRenderer::class,
			],
			[
				SelectField::class,
				ConsoleFormRenderer::class,
				ConsoleSimpleFieldRenderer::class,
			],
			[
				TextAreaField::class,
				ConsoleFormRenderer::class,
				ConsoleSimpleFieldRenderer::class,
			],
			[
				RadioField::class,
				ConsoleFormRenderer::class,
				ConsoleSimpleFieldRenderer::class,
			],
			[
				DurationField::class,
				ConsoleFormRenderer::class,
				ConsoleSimpleFieldRenderer::class,
			],
			[
				SelectObjectField::class,
				ConsoleFormRenderer::class,
				ConsoleSelectObjectFieldRenderer::class,
			],
			[
				SubFormField::class,
				ConsoleFormRenderer::class,
				ConsoleSubFormFieldRenderer::class,
			],
			[
				DateTimeField::class,
				ConsoleFormRenderer::class,
				ConsoleSimpleFieldRenderer::class,
			],
		];
	}
}