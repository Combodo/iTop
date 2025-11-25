<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Converter;

use Combodo\iTop\Service\DependencyInjection\DIException;
use Combodo\iTop\Service\DependencyInjection\DIService;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\IO\FormBlockIOException;
use Exception;

/**
 * OQL expression to class converter.
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

		$oModelReflection = DIService::GetInstance()->GetService('ModelReflection');
		try {
			$oQuery = $oModelReflection->GetQuery($oData);
		} catch (Exception $e) {
			throw new FormBlockIOException($e->getMessage(), $e->getCode(), $e);
		}
		return new ClassIOFormat($oQuery->GetClass());
	}
}
