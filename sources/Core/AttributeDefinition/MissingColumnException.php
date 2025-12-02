<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use Exception;

/**
 * MissingColumnException - sent if an attribute is being created but the column is missing in the row
 *
 * @package     iTopORM
 */
class MissingColumnException extends Exception
{
}
