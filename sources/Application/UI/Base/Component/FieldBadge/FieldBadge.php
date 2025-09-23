<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\FieldBadge;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * Class FieldBadge
 *
 * @package Combodo\iTop\Application\UI\Base\Component\FieldBadge
 */
class FieldBadge extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-field-badge';

	public function __construct(string $sId = null, array $aContainerClasses = [])
	{
		parent::__construct($sId, $aContainerClasses);
	}
}