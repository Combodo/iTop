<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\ServiceLocator;

use Combodo\iTop\Service\ServiceLocator\ServiceLocatorException;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends ServiceLocatorException implements NotFoundExceptionInterface
{
}
