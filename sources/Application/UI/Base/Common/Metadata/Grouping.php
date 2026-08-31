<?php

namespace Combodo\iTop\Application\UI\Base\Common\Metadata;

/**
 * Describes the group an item belongs to.
 * May later carry a label, a rank, an icon...
 *
 * @api @since 3.3.0
 */
final class Grouping
{
	private string $sUID;

	public function __construct(string $sUID)
	{
		$this->sUID = $sUID;
	}

	public function GetUID(): string
	{
		return $this->sUID;
	}
}
