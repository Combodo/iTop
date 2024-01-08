<?php

namespace Combodo\iTop\FormImplementation\Dto;

use ArrayAccess;

class CountDto implements ArrayAccess
{
	public function __construct(
		public int $count1 = 11,
		public int $count2 = 22,
		public int $count3 = 33,
	) {
	}

	public function offsetExists($offset) : bool
	{
		return property_exists($this, $offset);
	}

	public function offsetGet($offset) : mixed
	{
		return $this->$offset;
	}
	public function offsetSet($offset, $value) : void
	{
		$this->$offset = $value;
	}

	public function offsetUnset($offset) : void
	{
		unset($this->$offset);
	}
}
