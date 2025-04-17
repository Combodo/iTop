<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Dependency;

trait GraphTrait
{
	private array $aChildren = [];
	private int $iPosition = 0;

	public function AddChild(DependencyNode $node) {
		$this->aChildren[] = $node;
	}

	public function HasChildren() : bool {
		return count($this->aChildren) > 0;
	}

	/*
	 * Iterator interface
	 */
	public function current(): DependencyNode
	{
		return $this->aChildren[$this->iPosition];
	}

	public function next(): void
	{
		$this->iPosition++;
	}

	public function key(): int
	{
		return $this->iPosition;
	}

	public function valid(): bool
	{
		return isset($this->aChildren[$this->iPosition]);
	}

	public function rewind(): void
	{
		$this->iPosition = 0;
	}
}