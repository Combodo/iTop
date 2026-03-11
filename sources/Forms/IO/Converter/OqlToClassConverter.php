<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Converter;

use Combodo\iTop\Service\DependencyInjection\DIException;
use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\IO\FormBlockIOException;
use Exception;
use ModelReflection;

/**
 * Extract the selected class from an OQL query.
 *
 * @package Combodo\iTop\Forms\IO\Converter
 * @since 3.3.0
 */
class OqlToClassConverter extends AbstractConverter
{
	/** @inheritdoc
	 * @throws DIException
	 * @throws FormBlockIOException
	 */
	public function Convert(mixed $oData): ?ClassIOFormat
	{
		if ($oData === null) {
			return null;
		}

		/** @var ModelReflection $oModelReflection */
		$oModelReflection = ServiceLocator::GetInstance()->get('ModelReflection');
		try {
			$oQuery = $oModelReflection->GetQuery($oData);
		} catch (Exception $e) {
			throw new FormBlockIOException($e->getMessage(), $e->getCode(), $e);
		}
		return new ClassIOFormat($oQuery->GetClass());
	}
}
