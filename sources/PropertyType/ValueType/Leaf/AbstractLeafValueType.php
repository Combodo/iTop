<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Leaf;

use Combodo\iTop\Forms\Block\Expression\BooleanExpressionFormBlock;
use Combodo\iTop\Forms\Block\Expression\NumberExpressionFormBlock;
use Combodo\iTop\Forms\Block\Expression\StringExpressionFormBlock;
use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\IO\Format\NumberIOFormat;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\PropertyType\PropertyTypeException;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;
use Exception;
use Expression;
use utils;

/**
 * @since 3.3.0
 */
abstract class AbstractLeafValueType extends AbstractValueType
{
	public function IsLeaf(): bool
	{
		return true;
	}

	/**
	 * @param array $aPHPFragments
	 *
	 * @return string
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 */
	public function ToPHPFormBlock(array &$aPHPFragments = []): string
	{
		return $this->GetLocalPHPForValueType();
	}
}
