<?php

/**
 * An object Node inside a RelationGraph
 */
class RelationObjectNode extends GraphNode
{
	public function __construct($oGraph, $oObject)
	{
		parent::__construct($oGraph, self::MakeId($oObject));
		$this->SetProperty('object', $oObject);
		$this->SetProperty('label', get_class($oObject).'::'.$oObject->GetKey().' ('.$oObject->Get('friendlyname').')');
	}

	/**
	 * Make a normalized ID to ensure the uniqueness of such a node
	 *
	 * @param string $oObject
	 *
	 * @return string
	 */
	public static function MakeId($oObject)
	{
		return get_class($oObject).'::'.$oObject->GetKey();
	}

	/**
	 * Formatting for GraphViz
	 *
	 * @param bool $bNoLabel
	 *
	 * @return string
	 */
	public function GetDotAttributes($bNoLabel = false)
	{
		$sDot = parent::GetDotAttributes();
		if ($this->GetProperty('developped', false)) {
			$sDot .= ',fontcolor=black';
		} else {
			$sDot .= ',fontcolor=lightgrey';
		}
		if ($this->GetProperty('source', false) || $this->GetProperty('sink', false)) {
			$sDot .= ',shape=rectangle';
		}
		if ($this->GetProperty('is_reached', false)) {
			$sDot .= ',fillcolor="#ffdddd"';
		} else {
			$sDot .= ',fillcolor=white';
		}

		return $sDot;
	}

	/**
	 * Recursively mark the objects nodes as reached, unless we get stopped by a redundancy node or a 'not allowed' node
	 *
	 * @param string $sProperty
	 * @param $value
	 */
	public function ReachDown($sProperty, $value)
	{
		if (is_null($this->GetProperty($sProperty)) && ($this->GetProperty($sProperty.'_allowed') !== false)) {
			$this->SetProperty($sProperty, $value);
			foreach ($this->GetOutgoingEdges() as $oOutgoingEdge) {
				// Recurse
				$oOutgoingEdge->GetSinkNode()->ReachDown($sProperty, $value);
			}
		}
	}
}