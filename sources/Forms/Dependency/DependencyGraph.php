<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
namespace Combodo\iTop\Forms\Dependency;

class DependencyGraph implements \Iterator
{
	use GraphTrait;
	private array $aFieldNameToPosition = [];

	/**
	 * @param string $sName
	 * @param string $sType
	 * @param array $aDependencies
	 * @param array $aUserOptions
	 *
	 * @return void
	 * @throws \Combodo\iTop\Forms\Dependency\DependencyException
	 */
	public function Add(string $sName, string $sType, array $aDependencies = [], array $aUserOptions = []): void
	{
		$oNode = new DependencyNode($sName, $sType, $aUserOptions);
		// Store field position
		$this->aFieldNameToPosition[$sName] = count($this->aFieldNameToPosition);

		if (empty($aDependencies)) {
			$this->AddChild($oNode);
			return;
		}

		// Search the last added dependency
		$oParentNode = null;
		$iParentPosition = -1;
		foreach ($aDependencies as $sNodeName) {
			if ($sNodeName === $sName) {
				throw new DependencyException("Form field '$sName' cannot reference itself");
			}
			if (!isset($this->aFieldNameToPosition[$sNodeName])) {
				throw new DependencyException("Form field '$sNodeName' is not existing");
			}
			if ($this->aFieldNameToPosition[$sNodeName] > $iParentPosition) {
				$oParentNode = $this->SearchNode($sNodeName);
				$iParentPosition = $this->aFieldNameToPosition[$sNodeName];
			}
		}

		if (is_null($oParentNode)) {
			throw new DependencyException("Could not find dependency for field '$sName'");
		}

		// Add to the latest dependency
		$oParentNode->AddChild($oNode);
	}

	private function SearchNode(string $sName) : ?DependencyNode
	{
		foreach($this as $oChildNode) {
			$oNode = $oChildNode->SearchNode($sName);
			if ($oNode instanceof DependencyNode) {
				return $oNode;
			}
		}

		return null;
	}

	public function __toString(): string
	{
		$sResult = "\n";
		foreach ($this as $oNode) {
			$sResult .= $oNode->Display();
		}

		return $sResult;
	}
}