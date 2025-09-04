<?php

/**
 * Helper to name the edges in a unique way
 */
class RelationEdge extends GraphEdge
{
	/**
	 * RelationEdge constructor.
	 *
	 * @param \SimpleGraph $oGraph
	 * @param \GraphNode $oSourceNode
	 * @param \GraphNode $oSinkNode
	 * @param bool $bMustBeUnique
	 *
	 * @throws \SimpleGraphException
	 */
	public function __construct(SimpleGraph $oGraph, GraphNode $oSourceNode, GraphNode $oSinkNode, $bMustBeUnique = false)
	{
		$sId = $oSourceNode->GetId().'-to-'.$oSinkNode->GetId();
		parent::__construct($oGraph, $sId, $oSourceNode, $oSinkNode, $bMustBeUnique);
	}
}