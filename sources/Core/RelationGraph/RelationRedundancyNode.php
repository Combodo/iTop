<?php

/**
 * An redundancy Node inside a RelationGraph
 */
class RelationRedundancyNode extends GraphNode
{
	public function __construct($oGraph, $sId, $iMinUp, $fThreshold)
	{
		parent::__construct($oGraph, $sId);
		$this->SetProperty('min_up', $iMinUp);
		$this->SetProperty('threshold', $fThreshold);
	}

	/**
	 * Make a normalized ID to ensure the uniqueness of such a node
	 *
	 * @param string $sRelCode
	 * @param string $sNeighbourId
	 * @param $oSourceObject
	 * @param \DBObject $oSinkObject
	 *
	 * @return string
	 */
	public static function MakeId($sRelCode, $sNeighbourId, $oSourceObject, $oSinkObject)
	{
		return 'redundancy-'.$sRelCode.'-'.$sNeighbourId.'-'.get_class($oSinkObject).'::'.$oSinkObject->GetKey();
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
		$sDisplayThreshold = sprintf('%.1f', $this->GetProperty('threshold'));
		$sDot = 'shape=doublecircle,fillcolor=indianred,fontcolor=papayawhip,label="'.$sDisplayThreshold.'"';

		return $sDot;
	}

	/**
	 * Recursively mark the objects nodes as reached, unless we get stopped by a redundancy node
	 *
	 * @param string $sProperty
	 * @param $value
	 */
	public function ReachDown($sProperty, $value)
	{
		$this->SetProperty($sProperty.'_count', $this->GetProperty($sProperty.'_count', 0) + 1);
		if ($this->GetProperty($sProperty.'_count') > $this->GetProperty('threshold')) {
			// Looping... though there should be only ONE SINGLE outgoing edge
			foreach ($this->GetOutgoingEdges() as $oOutgoingEdge) {
				// Recurse
				$oOutgoingEdge->GetSinkNode()->ReachDown($sProperty, $value);
			}
		}
	}
}