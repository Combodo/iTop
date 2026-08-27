<?php

use Combodo\iTop\Application\UI\Base\Common\Metadata\Grouping;

/**
 * Allows to provide grouping information to classes that implement this interface.
 * @api
 * @since 3.3.0
 */
interface iGroupingProvider
{
	public function GetGrouping(): ?Grouping;
}
