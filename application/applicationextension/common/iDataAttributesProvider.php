<?php

use Combodo\iTop\Application\UI\Base\Common\Metadata\DataAttributes;

/**
 * Allows to provide data attributes to classes that implement this interface.
 * The data attributes will be used to provide additional information to the UI components
 * @api
 * @since 3.3.0
 */
interface iDataAttributesProvider
{
	public function GetDataAttributes(): DataAttributes;
}
