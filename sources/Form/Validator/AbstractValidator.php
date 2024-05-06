<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Validator;

use utils;

/**
 * @since 3.1.0 N°6414 Field Validators refactoring
 */
abstract class AbstractValidator
{
	public const VALIDATOR_NAME = 'abstract';

	/** @var string Default message / dict key when an error occurs, if no custom one is specified in the constructor */
	public const DEFAULT_ERROR_MESSAGE = 'Core:Validator:Default';
	/** @var string message / dict key to use when an error occurs */
	protected string $sErrorMessage;

	public function __construct(?string $sErrorMessage = null)
	{
		if (false === utils::IsNullOrEmptyString($sErrorMessage)) {
			$this->sErrorMessage = $sErrorMessage;
		}
		else {
			$this->sErrorMessage = static::DEFAULT_ERROR_MESSAGE;
		}
	}

	/**
	 * @param mixed $value
	 *
	 * @return string[] list of error messages, empty array if no error
	 */
	abstract public function Validate($value): array;

	/**
	 * Name to use for JS counterparts
	 *
	 * @return string
	 */
	public static function GetName()
	{
		return static::VALIDATOR_NAME;
	}

	/**
	 * Still used in \Combodo\iTop\Renderer\Console\FieldRenderer\ConsoleSelectObjectFieldRenderer::Render :(
	 *
	 * @return string
	 */
	public function GetErrorMessage()
	{
		return $this->sErrorMessage;
	}
}