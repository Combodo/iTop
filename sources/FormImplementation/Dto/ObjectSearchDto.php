<?php

namespace Combodo\iTop\FormImplementation\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ObjectSearchDto
{
	public function __construct(
		#[Assert\NotBlank]
		public readonly string $class,
		#[Assert\NotBlank]
		public readonly string $oql,
		public readonly string $fields,
		public readonly string $search = '',
	) {
	}
}
