<?php

use Combodo\iTop\Application\UI\Base\Common\Metadata\AriaAttributes;

/**
 * Allows to provide ARIA attributes to classes that implement this interface.
 * The ARIA attributes will be used to improve accessibility of the UI components
 *
 * @api
 * @since 3.3.0
 */
interface iAriaAttributesProvider
{
	public function GetAriaAttributes(): AriaAttributes;
}
