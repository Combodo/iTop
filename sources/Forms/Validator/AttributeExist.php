<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Validator;

use Attribute;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;

/**
 * Attribute exist constraint.
 *
 * @package Combodo\iTop\Forms\Validator
 * @since 3.3.0
 */
#[Attribute]
class AttributeExist extends Constraint
{
	/** @var string Violation message */
	public string $sMessage = 'The attribute "{{ attribute }}" doesn\'t exist in class "{{ class }}" from OQL "{{ oql }}".';

	/** @var string|mixed OQL expression property path */
	public string $sOqlPropertyPath;

	/** @var string|null Attribute list filter */
	public ?string $sFilter;

	/**
	 * Constructor.
	 *
	 * @param string|null $sOqlPropertyPath
	 * @param string|null $sFilter
	 * @param array $aOptions
	 * @param array|null $aGroups
	 * @param mixed|null $oPayload
	 */
	public function __construct(string $sOqlPropertyPath = null, string $sFilter = null, array $aOptions = [], ?array $aGroups = null, mixed $oPayload = null)
	{
		if ($sOqlPropertyPath === null) {
			throw new InvalidArgumentException('The argument "sOqlPropertyPath" must be set.');
		}

		// Merge argument into options array
		$aOptions = array_merge([
			'sOqlPropertyPath' => $sOqlPropertyPath,
		], $aOptions);

		parent::__construct($aOptions, $aGroups, $oPayload);

		// Retrieve options
		$this->sFilter = $sFilter;
		$this->sOqlPropertyPath = $aOptions['sOqlPropertyPath'];
	}

	public function getDefaultOption(): string
	{
		return 'sOqlPropertyPath';
	}

	public function getRequiredOptions(): array
	{
		return ['sOqlPropertyPath'];
	}
}
