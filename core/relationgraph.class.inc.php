<?php
// Copyright (C) 2015 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/**
 * Data structures (i.e. PHP classes) to build and use relation graphs
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 * 
 */

require_once(APPROOT.'core/simplegraph.class.inc.php');

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
	 */	
	public static function MakeId($oObject)
	{
		return get_class($oObject).'::'.$oObject->GetKey();
	}

	/**
	 * Formatting for GraphViz
	 */	 	
	public function GetDotAttributes()
	{
		$sDot = parent::GetDotAttributes();
		if ($this->GetProperty('developped', false))
		{
			$sDot .= ',fontcolor=black';
		}
		else
		{
			$sDot .= ',fontcolor=lightgrey';
		}
		if ($this->GetProperty('source', false) || $this->GetProperty('sink', false))
		{
			$sDot .= ',shape=rectangle';
		}
		if ($this->GetProperty('is_reached', false))
		{
			$sDot .= ',fillcolor="#ffdddd"';
		}
		else
		{
			$sDot .= ',fillcolor=white';
		}
		return $sDot;
	}

	/**
	 * Recursively mark the objects nodes as reached, unless we get stopped by a redundancy node
	 */	 	
	public function ReachDown()
	{
		if (!$this->GetProperty('is_reached', false))
		{
			$this->SetProperty('is_reached', true);
			foreach ($this->GetOutgoingEdges() as $oOutgoingEdge)
			{
				// Recurse
				$oOutgoingEdge->GetSinkNode()->ReachDown();
			}
		}
	}
}

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
		$this->SetProperty('reach_count', 0);
	}

	/**
	 * Make a normalized ID to ensure the uniqueness of such a node	
	 */	
	public static function MakeId($sRelCode, $sNeighbourId, $oSinkObject)
	{
		return 'redundancy-'.$sRelCode.'-'.$sNeighbourId.'-'.get_class($oSinkObject).'::'.$oSinkObject->GetKey();
	}

	/**
	 * Formatting for GraphViz
	 */	 	
	public function GetDotAttributes()
	{
		$sDisplayThreshold = sprintf('%.1f', $this->GetProperty('threshold'));
		$sDot = 'shape=doublecircle,fillcolor=indianred,fontcolor=papayawhip,label="'.$sDisplayThreshold.'"';
		return $sDot;
	}

	/**
	 * Recursively mark the objects nodes as reached, unless we get stopped by a redundancy node
	 */	 	
	public function ReachDown()
	{
		$this->SetProperty('reach_count', $this->GetProperty('reach_count', 0) + 1);
		if ($this->GetProperty('reach_count') > $this->GetProperty('threshold'))
		{
			// Looping... though there should be only ONE SINGLE outgoing edge
			foreach ($this->GetOutgoingEdges() as $oOutgoingEdge)
			{
				// Recurse
				$oOutgoingEdge->GetSinkNode()->ReachDown();
			}
		}
	}
}


/**
 * Helper to name the edges in a unique way
 */ 
class RelationEdge extends GraphEdge
{
	public function __construct(SimpleGraph $oGraph, GraphNode $oSourceNode, GraphNode $oSinkNode)
	{
		$sId = $oSourceNode->GetId().'-to-'.$oSinkNode->GetId();
		parent::__construct($oGraph, $sId, $oSourceNode, $oSinkNode);
	}
}

/**
 * A graph representing the relations between objects
 * The graph is made of two types of nodes. Here is a list of the meaningful node properties
 * 1) RelationObjectNode
 *    source: boolean, that node was added as a source node
 *    sink: boolean, that node was added as a sink node
 *    reached: boolean, that node has been marked as reached (impacted by the source nodes)
 *    developped: boolean, that node has been visited to search for related objects    
 * 1) RelationRedundancyNode
 *    reached_count: int, the number of source nodes having reached=true
 *    threshold: float, if reached_count > threshold, the sink nodes become reachable    
 */ 
class RelationGraph extends SimpleGraph
{
	protected $aSourceNodes; // Index of source nodes (for a quicker access)
	protected $aSinkNodes; // Index of sink nodes (for a quicker access)
	protected $aRedundancySettings; // Cache of user settings

	public function __construct()
	{
		parent::__construct();
		$this->aSourceNodes = array();
		$this->aSinkNodes = array();
		$this->aRedundancySettings = array();
	}

	/**
	 * Add an object that will be the starting point for building the relations downstream
	 */	 	
	public function AddSourceObject(DBObject $oObject)
	{
		$oSourceNode = new RelationObjectNode($this, $oObject);
		$oSourceNode->SetProperty('source', true);
		$this->aSourceNodes[$oSourceNode->GetId()] = $oSourceNode;
	}

	/**
	 * Add an object that will be the starting point for building the relations uptream
	 */	 	
	public function AddSinkObject(DBObject$oObject)
	{
		$oSinkNode = new RelationObjectNode($this, $oObject);
		$oSinkNode->SetProperty('sink', true);
		$this->aSinkNodes[$oSinkNode->GetId()] = $oSinkNode;
	}

	/**
	 * Build the graph downstream, and mark the nodes that can be reached from the source node
	 */	 	
	public function ComputeRelatedObjectsDown($sRelCode, $iMaxDepth, $bEnableRedundancy)
	{
		//echo "<h5>Sources only...</h5>\n".$this->DumpAsHtmlImage()."<br/>\n";
		// Build the graph out of the sources
		foreach ($this->aSourceNodes as $oSourceNode)
		{
			$this->AddRelatedObjects($sRelCode, true, $oSourceNode, $iMaxDepth, $bEnableRedundancy);
			//echo "<h5>After processing of {$oSourceNode->GetId()}</h5>\n".$this->DumpAsHtmlImage()."<br/>\n";
		}
		// Determine the reached nodes
		foreach ($this->aSourceNodes as $oSourceNode)
		{
			$oSourceNode->ReachDown();
			//echo "<h5>After reaching from {$oSourceNode->GetId()}</h5>\n".$this->DumpAsHtmlImage()."<br/>\n";
		}
	}

	/**
	 * Build the graph upstream
	 */	 	
	public function ComputeRelatedObjectsUp($sRelCode, $iMaxDepth, $bEnableRedundancy)
	{
		//echo "<h5>Sinks only...</h5>\n".$this->DumpAsHtmlImage()."<br/>\n";
		// Build the graph out of the sinks
		foreach ($this->aSinkNodes as $oSinkNode)
		{
			$this->AddRelatedObjects($sRelCode, false, $oSinkNode, $iMaxDepth, $bEnableRedundancy);
			//echo "<h5>After processing of {$oSinkNode->GetId()}</h5>\n".$this->DumpAsHtmlImage()."<br/>\n";
		}
	}


	/**
	 * Recursively find related objects, and add them into the graph
	 * 
	 * @param string $sRelCode The code of the relation to use for the computation
	 * @param boolean $bDown The direction: downstream or upstream
	 * @param array $oObjectNode The node from which to compute the neighbours
	 * @param int $iMaxDepth
	 * @param boolean $bEnableReduncancy
	 * 
	 * @return void
	 */
	protected function AddRelatedObjects($sRelCode, $bDown, $oObjectNode, $iMaxDepth, $bEnableRedundancy)
	{
		if ($iMaxDepth > 0)
		{
			if ($oObjectNode instanceof RelationRedundancyNode)
			{
				// Note: this happens when recursing on an existing part of the graph 
				// Skip that redundancy node
				$aRelatedEdges = $bDown ? $oObjectNode->GetOutgoingEdges() : $oObjectNode->GetIncomingEdges();
				foreach ($aRelatedEdges as $oRelatedEdge)
				{
					$oRelatedNode = $bDown ? $oRelatedEdge->GetSinkNode() : $oRelatedEdge->GetSourceNode();
					// Recurse (same depth)
					$this->AddRelatedObjects($sRelCode, $bDown, $oRelatedNode, $iMaxDepth, $bEnableRedundancy);
				}
			}
			elseif ($oObjectNode->GetProperty('developped', false))
			{
				// No need to execute the queries again... just dig into the nodes down/up to iMaxDepth
				//
				$aRelatedEdges = $bDown ? $oObjectNode->GetOutgoingEdges() : $oObjectNode->GetIncomingEdges();
				foreach ($aRelatedEdges as $oRelatedEdge)
				{
					$oRelatedNode = $bDown ? $oRelatedEdge->GetSinkNode() : $oRelatedEdge->GetSourceNode();
					// Recurse (decrement the depth)
					$this->AddRelatedObjects($sRelCode, $bDown, $oRelatedNode, $iMaxDepth - 1, $bEnableRedundancy);
				}
			}
			else
			{
				$oObjectNode->SetProperty('developped', true);
	
				$oObject = $oObjectNode->GetProperty('object');
				foreach (MetaModel::EnumRelationQueries(get_class($oObject), $sRelCode, $bDown) as $sDummy => $aQueryInfo)
				{
	 				$sQuery = $bDown ? $aQueryInfo['sQueryDown'] : $aQueryInfo['sQueryUp'];
					try
					{
						$oFlt = DBObjectSearch::FromOQL($sQuery);
						$oObjSet = new DBObjectSet($oFlt, array(), $oObject->ToArgsForQuery());
						$oRelatedObj = $oObjSet->Fetch();
					}
					catch (Exception $e)
					{
						$sDirection = $bDown ? 'downstream' : 'upstream';
						throw new Exception("Wrong query ($sDirection) for the relation $sRelCode/{$aQueryInfo['sDefinedInClass']}/{$aQueryInfo['sNeighbour']}: ".$e->getMessage());
					}
					if ($oRelatedObj)
					{
						do
						{
							$sObjectRef = 	RelationObjectNode::MakeId($oRelatedObj);
							$oRelatedNode = $this->GetNode($sObjectRef);
							if (is_null($oRelatedNode))
							{	
								$oRelatedNode = new RelationObjectNode($this, $oRelatedObj);
							}
							$oSourceNode = $bDown ? $oObjectNode : $oRelatedNode;
							$oSinkNode = $bDown ? $oRelatedNode : $oObjectNode;
							if ($bEnableRedundancy)
							{
								$oRedundancyNode = $this->ComputeRedundancy($sRelCode, $aQueryInfo, $oSourceNode, $oSinkNode);
							}
							else
							{
								$oRedundancyNode = null;
							}
							if (!$oRedundancyNode)
							{
								// Direct link (otherwise handled by ComputeRedundancy)
								$oEdge = new RelationEdge($this, $oSourceNode, $oSinkNode);
							}
							// Recurse
							$this->AddRelatedObjects($sRelCode, $bDown, $oRelatedNode, $iMaxDepth - 1, $bEnableRedundancy);
	
						}
						while ($oRelatedObj = $oObjSet->Fetch());
					}
				}
			}
		}
	}

	/**
	 * Determine if there is a redundancy (or use the existing one) and add the corresponding nodes/edges	
	 */	
	protected function ComputeRedundancy($sRelCode, $aQueryInfo, $oFromNode, $oToNode)
	{
		$oRedundancyNode = null;
		$oObject = $oToNode->GetProperty('object');
		if ($this->IsRedundancyEnabled($sRelCode, $aQueryInfo, $oToNode))
		{

			$sId = RelationRedundancyNode::MakeId($sRelCode, $aQueryInfo['sNeighbour'], $oToNode->GetProperty('object'));

			$oRedundancyNode = $this->GetNode($sId);
			if (is_null($oRedundancyNode))
			{
				// Get the upper neighbours
				$sQuery = $aQueryInfo['sQueryUp'];
				try
				{
					$oFlt = DBObjectSearch::FromOQL($sQuery);
					$oObjSet = new DBObjectSet($oFlt, array(), $oObject->ToArgsForQuery());
					$iCount = $oObjSet->Count();
				}
				catch (Exception $e)
				{
					throw new Exception("Wrong query (upstream) for the relation $sRelCode/{$aQueryInfo['sDefinedInClass']}/{$aQueryInfo['sNeighbour']}: ".$e->getMessage());
				}
	
				$iMinUp = $this->GetRedundancyMinUp($sRelCode, $aQueryInfo, $oToNode, $iCount);
				$fThreshold = max(0, $iCount - $iMinUp);
				$oRedundancyNode = new RelationRedundancyNode($this, $sId, $iMinUp, $fThreshold);
				new RelationEdge($this, $oRedundancyNode, $oToNode);
	
				while ($oUpperObj = $oObjSet->Fetch())
				{
					$sObjectRef = 	RelationObjectNode::MakeId($oUpperObj);
					$oUpperNode = $this->GetNode($sObjectRef);
					if (is_null($oUpperNode))
					{	
						$oUpperNode = new RelationObjectNode($this, $oUpperObj);
					}
					new RelationEdge($this, $oUpperNode, $oRedundancyNode);
				}
			}
		}
		return $oRedundancyNode;
	}

	/**
	 * Helper to determine the redundancy setting on a given relation	
	 */	
	protected function IsRedundancyEnabled($sRelCode, $aQueryInfo, $oToNode)
	{
		$bRet = false;
		$oToObject = $oToNode->GetProperty('object');
		$oRedundancyAttDef = $this->FindRedundancyAttribute($sRelCode, $aQueryInfo, get_class($oToObject));
		if ($oRedundancyAttDef)
		{
			$sValue = $oToObject->Get($oRedundancyAttDef->GetCode());
			$bRet = $oRedundancyAttDef->IsEnabled($sValue);
		}
		return $bRet;
	}

	/**
	 * Helper to determine the redundancy threshold, given the count of objects upstream 	
	 */	
	protected function GetRedundancyMinUp($sRelCode, $aQueryInfo, $oToNode, $iUpstreamObjects)
	{
		$iMinUp = 0;

		$oToObject = $oToNode->GetProperty('object');
		$oRedundancyAttDef = $this->FindRedundancyAttribute($sRelCode, $aQueryInfo, get_class($oToObject));
		if ($oRedundancyAttDef)
		{
			$sValue = $oToObject->Get($oRedundancyAttDef->GetCode());
			if ($oRedundancyAttDef->GetMinUpType($sValue) == 'count')
			{
				$iMinUp = $oRedundancyAttDef->GetMinUpValue($sValue);
			}
			else
			{
				$iMinUp = $iUpstreamObjects * $oRedundancyAttDef->GetMinUpValue($sValue) / 100;
			}
		}
		return $iMinUp;
	}

	/**
	 * Helper to search for the redundancy attribute	
	 */	
	protected function FindRedundancyAttribute($sRelCode, $aQueryInfo, $sClass)
	{
		$oRet = null;
		foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeRedundancySettings)
			{
				if ($oAttDef->Get('relation_code') == $sRelCode)
				{
					if ($oAttDef->Get('neighbour_id') == $aQueryInfo['sNeighbour'])
					{
						$oRet = $oAttDef;
						break;
					}
				}
			}
		}
		return $oRet;
	}
}
