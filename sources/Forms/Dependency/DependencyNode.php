<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
namespace Combodo\iTop\Forms\Dependency;

class DependencyNode implements \Iterator
{
	use GraphTrait;

	public function __construct(private string $sName, private string $sType, private array $aUserOptions = [])
	{
	}

	public function GetType(): string
	{
		return $this->sType;
	}

	public function GetName() : string
	{
		return $this->sName;
	}

	public function GetUserOptions(): array
	{
		return $this->aUserOptions;
	}

	public function SearchNode(string $sName) : ?DependencyNode
	{
		if ($sName === $this->GetName()) {
			return $this;
		}

		foreach ($this as $oChildNode) {
			$oNode = $oChildNode->SearchNode($sName);
			if ($oNode instanceof DependencyNode) {
				return $oNode;
			}
		}

		return null;
	}

	public function Display(int $iDepth = 1)
	{
		$sResult = str_repeat('    ', $iDepth).$this->GetName()."\n";
		foreach ($this as $oNode) {
			$sResult .= $oNode->Display($iDepth + 1);
		}

		return $sResult;
	}
}