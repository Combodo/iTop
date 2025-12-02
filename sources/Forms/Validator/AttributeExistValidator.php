<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Validator;

use Combodo\iTop\Forms\IO\Converter\OqlToClassConverter;
use Combodo\iTop\Service\DependencyInjection\DIService;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Attribute exist validator.
 *
 * @package Combodo\iTop\Forms\Validator
 * @since 3.3.0
 */
class AttributeExistValidator extends ConstraintValidator
{
	private ?PropertyAccessorInterface $propertyAccessor;

	public function __construct()
	{
		$this->propertyAccessor = PropertyAccess::createPropertyAccessor();
	}

	/**
	 * @inheritDoc
	 */
	public function validate(mixed $value, Constraint $constraint): void
	{
		$sOql = $this->propertyAccessor->getValue($this->context->getObject(), $constraint->sOqlPropertyPath);

		$oOqlToClassConverter = new OqlToClassConverter();
		$sClass = strval($oOqlToClassConverter->Convert($sOql));

		$sClass = "UserRequest";

		/** List attributes @var ModelReflection $oModelReflection */
		$oModelReflection = DIService::GetInstance()->GetService('ModelReflection');
		$aAttributeCodes = array_keys($oModelReflection->ListAttributes($sClass));

		if (!in_array($value, $aAttributeCodes, true)) {
			$this->context->buildViolation($constraint->sMessage)
				->setParameter('{{ attribute }}', $value)
				->setParameter('{{ class }}', $sClass)
				->setParameter('{{ oql }}', $sOql)
				->addViolation();
		}

	}
}
